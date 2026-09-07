<?php

namespace App\Http\Controllers;

use App\Models\ItemPenjualan;
use App\Models\Penjualan;
use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PenjualanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $keyword = $request->input('search');

        $sales = Penjualan::query()
            ->when(optional($user->role)->name === 'Kasir', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->when($keyword, function ($query) use ($keyword) {
                $query->whereHas('user', function ($q) use ($keyword) {
                    $q->where('name', 'like', '%' . $keyword . '%');
                });
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('penjualan.index', compact('sales'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $sale = Penjualan::firstOrCreate(
            [
                'user_id' => Auth::id(),
                'status'  => 'OPEN'
            ],
            [
                'total_pembayaran'  => 0,
                'metode_pembayaran' => 'CASH'
            ]
        );

        $sale->load('itemPenjualan.produk');

        $search = $request->input('search');

        $produks = Produk::when($search, function ($query) use ($search) {
            $query->where('name', 'like', '%' . $search . '%');
        })
        ->latest()
        ->get();

        $mode = 'create';
        $grandTotal = $sale->itemPenjualan->sum('subtotal');

        return view('penjualan.pos', compact('sale', 'produks', 'mode', 'grandTotal') + ['products' => $produks]);
    }

    /**
     * Menambahkan item ke keranjang belanja
     */
    public function addToCart(Request $request)
    {
        $request->validate([
            'produk_id' => 'required|exists:produk,id',
            'qty'       => 'required|numeric|min:1'
        ]);

        $produk = Produk::findOrFail($request->produk_id);

        if ($produk->stok < $request->qty) {
            return back()->with('error', 'Stok produk tidak mencukupi.');
        }

        $sale = Penjualan::firstOrCreate(
            [
                'user_id' => Auth::id(),
                'status'  => 'OPEN'
            ],
            [
                'total_pembayaran'  => 0,
                'metode_pembayaran' => 'CASH'
            ]
        );

        $item = $sale->itemPenjualan()->where('produk_id', $produk->id)->first();

        DB::transaction(function () use ($sale, $item, $produk, $request) {
            $harga = $produk->harga_jual ?? $produk->harga ?? 0;

            if ($item) {
                $newQty = $item->kuantitas + $request->qty;
                $item->update([
                    'kuantitas'    => $newQty,
                    'harga_satuan' => $harga,
                    'subtotal'     => $newQty * $harga
                ]);
            } else {
                $sale->itemPenjualan()->create([
                    'produk_id'    => $produk->id,
                    'kuantitas'    => $request->qty,
                    'harga_satuan' => $harga,
                    'subtotal'     => $request->qty * $harga
                ]);
            }

            $produk->decrement('stok', $request->qty);
        });

        return back()->with('success', 'Produk berhasil ditambahkan ke keranjang.');
    }

    /**
     * Menghapus item dari keranjang
     */
    public function removeFromCart($id)
    {
        $item = ItemPenjualan::find($id);

        if (!$item) {
            return back()->with('error', 'Item sudah tidak ada di keranjang.');
        }

        DB::transaction(function () use ($item) {
            if ($item->produk) {
                $item->produk->increment('stok', $item->kuantitas);
            }
            $item->delete();
        });

        return back()->with('success', 'Item berhasil dihapus dari keranjang.');
    }

    /**
     * Mengosongkan seluruh keranjang (Membatalkan Transaksi)
     */
    public function clearCart()
    {
        $sale = Penjualan::where('user_id', Auth::id())->where('status', 'OPEN')->first();

        if ($sale) {
            DB::transaction(function () use ($sale) {
                foreach ($sale->itemPenjualan as $item) {
                    if ($item->produk) {
                        $item->produk->increment('stok', $item->kuantitas);
                    }
                }
                $sale->itemPenjualan()->delete();
                $sale->delete(); // Menghapus draft transaksi yang masih status OPEN
            });
        }

        // Redirect kembali ke halaman daftar penjualan
        return redirect()
            ->route('penjualan.index')
            ->with('success', 'Transaksi berhasil dibatalkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Penjualan $penjualan)
    {
        $penjualan->load(['itemPenjualan.produk', 'user']);

        return view('penjualan.show', compact('penjualan'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Penjualan $penjualan)
    {
        $sale = $penjualan;
        $sale->load('itemPenjualan.produk');

        $produks = Produk::latest()->get();
        $mode = 'edit';
        $grandTotal = $sale->itemPenjualan->sum('subtotal');

        return view('penjualan.pos', compact('sale', 'produks', 'mode', 'grandTotal') + ['products' => $produks]);
    }

    /**
     * Update/Checkout Transaksi
     */
    public function update(Request $request, Penjualan $penjualan)
    {
        $request->validate([
            'payment_method' => 'required|in:CASH,QRIS',
            'uang_masuk'     => 'required|numeric|min:0',
        ]);

        if ($penjualan->itemPenjualan()->count() === 0) {
            return back()->with('error', 'Keranjang transaksi masih kosong.');
        }

        $total = $penjualan->itemPenjualan()->sum('subtotal');

        if ($request->uang_masuk < $total) {
            return back()->with('error', 'Uang masuk tidak boleh kurang dari total pembayaran.');
        }

        DB::transaction(function () use ($penjualan, $request, $total) {
            $penjualan->update([
                'metode_pembayaran' => $request->payment_method,
                'total_pembayaran'  => $total,
                'uang_masuk'        => $request->uang_masuk,
                'kembalian'         => $request->uang_masuk - $total,
                'status'            => 'COMPLETED'
            ]);
        });

        // Redirect kembali ke halaman daftar penjualan setelah checkout berhasil
        return redirect()
            ->route('penjualan.index')
            ->with('success', 'Transaksi berhasil diselesaikan.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Penjualan $penjualan)
    {
        DB::transaction(function () use ($penjualan) {
            foreach ($penjualan->itemPenjualan as $item) {
                if ($item->produk) {
                    $item->produk->increment('stok', $item->kuantitas);
                }
            }

            $penjualan->itemPenjualan()->delete();
            $penjualan->delete();
        });

        return redirect()
            ->route('penjualan.index')
            ->with('success', 'Transaksi berhasil dihapus');
    }
}
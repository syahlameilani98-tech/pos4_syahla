<?php

namespace App\Http\Controllers;

use App\Http\Requests\SearchRequest;
use App\Http\Requests\Produk\StoreRequest;
use App\Http\Requests\Produk\UpdateRequest;
use App\Models\Produk;
use App\Models\Jenis;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProdukController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(SearchRequest $request)
    {
        $this->authorize('viewAny', Produk::class);

        $keyword = $request->input('search');
        $jenisId = $request->input('jenis_id');

        $products = Produk::with(['user', 'jenis'])
            ->when($keyword, function ($query) use ($keyword) {
                return $query->where('name', 'like', '%' . $keyword . '%')
                             ->orderBy('name');
            }, function ($query) {
                return $query->latest();
            })
            ->when($jenisId, function ($query) use ($jenisId) {
                return $query->where('jenis_id', $jenisId);
            })
            ->paginate(10)
            ->withQueryString();

        $jenis = Jenis::all();

        return view('produk.index', compact('products', 'jenis'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('create', Produk::class);

        $jenis = Jenis::all();

        return view('produk.create', compact('jenis'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request)
    {
        $this->authorize('create', Produk::class);

        $dataReq = $request->validated();

        $data = [
            'user_id'    => Auth::id(),
            'jenis_id'   => $dataReq['jenis_id'],
            'name'       => $dataReq['name'],
            'harga_beli' => $dataReq['purchase_price'],
            'harga_jual' => $dataReq['selling_price'],
            'stok'       => $dataReq['stock'] ?? 0,
        ];

        if ($request->hasFile('foto')) {
            $data['foto'] = $request->file('foto')->store('products', 'public');
        }

        Produk::create($data);

        return redirect()
            ->route('produk.index')
            ->with('success', 'Product created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Produk $produk)
    {
        $this->authorize('update', $produk);

        $jenis = Jenis::all();

        return view('produk.edit', compact('produk', 'jenis'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRequest $request, Produk $produk)
    {
        $this->authorize('update', $produk);

        $dataReq = $request->validated();

        $data = [
            'user_id'    => Auth::id(),
            'jenis_id'   => $dataReq['jenis_id'],
            'name'       => $dataReq['name'],
            'harga_beli' => $dataReq['purchase_price'],
            'harga_jual' => $dataReq['selling_price'],
            'stok'       => $dataReq['stock'],
        ];

        if ($request->hasFile('foto')) {
            if ($produk->foto && Storage::disk('public')->exists($produk->foto)) {
                Storage::disk('public')->delete($produk->foto);
            }

            $data['foto'] = $request->file('foto')->store('products', 'public');
        }

        $produk->update($data);

        return redirect()
            ->route('produk.index')
            ->with('success', 'Product updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Produk $produk)
    {
        $this->authorize('delete', $produk);

        // Hapus semua riwayat transaksi barang ini di item_penjualan
        $produk->itemPenjualan()->delete();

        // Hapus foto produk jika ada
        if ($produk->foto && Storage::disk('public')->exists($produk->foto)) {
            Storage::disk('public')->delete($produk->foto);
        }

        // Hapus data produk dari database
        $produk->delete();

        return redirect()
            ->route('produk.index')
            ->with('success', 'Product deleted successfully.');
    }
}
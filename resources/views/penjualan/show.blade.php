@extends('layouts.app')

@section('content')
<div class="container py-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold mb-0">Detail Transaksi #{{ $penjualan->id }}</h2>
        <span class="badge {{ $penjualan->status === 'COMPLETED' ? 'bg-success' : 'bg-warning text-dark' }} fs-6 px-3 py-2">
            {{ $penjualan->status }}
        </span>
    </div>

    <div class="row g-4">
        <!-- Info Transaksi -->
        <div class="col-md-5">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-white fw-semibold">
                    Informasi Transaksi
                </div>
                <div class="card-body">
                    <table class="table table-borderless mb-0">
                        <tr>
                            <td class="text-muted">Tanggal Transaksi</td>
                            <td class="fw-semibold text-end">{{ $penjualan->created_at->format('d-m-Y H:i') }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Kasir</td>
                            <td class="fw-semibold text-end">{{ $penjualan->user->name }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Metode Pembayaran</td>
                            <td class="fw-semibold text-end">{{ $penjualan->metode_pembayaran }}</td>
                        </tr>
                        <tr class="border-top">
                            <td class="text-muted pt-3">Total Pembayaran</td>
                            <td class="fw-bold text-end pt-3">Rp {{ number_format($penjualan->total_pembayaran, 0, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Uang Masuk</td>
                            <td class="fw-semibold text-end">Rp {{ number_format($penjualan->uang_masuk, 0, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Kembalian</td>
                            <td class="fw-semibold text-end text-success">Rp {{ number_format($penjualan->kembalian, 0, ',', '.') }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <!-- Daftar Produk -->
        <div class="col-md-7">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-white fw-semibold">
                    Daftar Produk
                </div>
                <div class="card-body p-0">
                    <table class="table table-striped mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Produk</th>
                                <th class="text-end">Harga</th>
                                <th class="text-center">Qty</th>
                                <th class="text-end">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($penjualan->itemPenjualan as $index => $item)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $item->produk->name ?? '-' }}</td>
                                    <td class="text-end">Rp {{ number_format($item->harga_satuan, 0, ',', '.') }}</td>
                                    <td class="text-center">{{ $item->kuantitas }}</td>
                                    <td class="text-end">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-4">Tidak ada item</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <a href="{{ route('penjualan.index') }}" class="btn btn-secondary mt-4">
        &larr; Kembali
    </a>
</div>
@endsection
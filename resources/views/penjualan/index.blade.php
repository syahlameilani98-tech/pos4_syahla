@extends('layouts.app')

@section('title', 'Penjualan')

@section('content')

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

@if(session('error'))
<div class="alert alert-danger alert-dismissible fade show" role="alert">
    {{ session('error') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

<h1>Halaman Penjualan</h1>

<a href="{{ route('penjualan.create') }}" class="btn btn-primary mb-3">Create</a>

<form action="{{ route('penjualan.index') }}" method="GET" class="mb-3">
    <div class="input-group">
        <input
            type="text"
            name="search"
            value="{{ request()->search }}"
            class="form-control"
            placeholder="Search penjualan"
        >
        <button class="btn btn-outline-secondary" type="submit">
            Search
        </button>
    </div>
</form>

<table class="table align-middle">
    <thead>
        <tr>
            <th scope="col">#</th>
            <th scope="col">Tanggal Transaksi</th>
            <th scope="col">Kasir</th>
            <th scope="col">Total Pembayaran</th>
            <th scope="col">Metode Pembayaran</th>
            <th scope="col">Status</th>
            <th scope="col">Aksi</th>
        </tr>
    </thead>
    <tbody>
        @forelse($sales as $sale)
        <tr>
            <th scope="row">{{ $sales->firstItem() + $loop->index }}</th>
            <td>{{ $sale->created_at->translatedFormat('d-m-Y H:i:s') }}</td>
            <td>{{ $sale->user->name }}</td>
            <td>Rp. {{ number_format($sale->total_pembayaran, 0, ',', '.') }}</td>
            <td>{{ $sale->metode_pembayaran }}</td>
            <td>
                <span class="badge {{ $sale->status === 'COMPLETED' ? 'bg-success' : 'bg-warning text-dark' }}">
                    {{ $sale->status }}
                </span>
            </td>
            <td>
                <div class="d-flex gap-1">
                    {{-- Tombol Detail: selalu muncul --}}
                    <a href="{{ route('penjualan.show', $sale) }}" class="btn btn-primary btn-sm">Detail</a>

                    {{-- Tombol Edit & Hapus: hanya muncul jika status masih OPEN --}}
                    @if ($sale->status === 'OPEN')
                        <a href="{{ route('penjualan.edit', $sale) }}" class="btn btn-warning btn-sm">
                            Edit
                        </a>

                        <form action="{{ route('penjualan.destroy', $sale) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button
                                type="submit"
                                class="btn btn-danger btn-sm"
                                onclick="return confirm('Apakah Anda yakin akan menghapus penjualan ini?')"
                            >
                                Hapus
                            </button>
                        </form>
                    @endif
                </div>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="7" class="text-center">
                Data Tidak Ditemukan
            </td>
        </tr>
        @endforelse
    </tbody>
</table>

{{ $sales->links() }}

@endsection
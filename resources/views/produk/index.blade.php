@extends('layouts.app')

@section('title', 'produk')

@section('content')

{{-- Alert Notifikasi Sukses --}}
@if (session('success'))
    <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

{{-- Alert Notifikasi Error --}}
@if (session('error'))
    <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<h1 class="mb-4">Halaman Produk</h1>

@can('create', App\Models\Produk::class)
    <a href="{{ route('produk.create') }}" class="btn btn-primary mb-3">
        Create
    </a>
@endcan

<form action="{{ route('produk.index') }}" method="GET" class="mb-3">
    <div class="row g-2">
        <div class="col-md-5">
            <input
                type="text"
                name="search"
                value="{{ request('search') }}"
                class="form-control"
                placeholder="Search nama produk"
            >
        </div>

        <div class="col-md-3">
            <select name="jenis_id" class="form-select" onchange="this.form.submit()">
                <option value="">Semua Kategori</option>
                @foreach ($jenis as $j)
                    <option value="{{ $j->id }}" {{ request('jenis_id') == $j->id ? 'selected' : '' }}>
                        {{ $j->nama_jenis }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="col-md-2">
            <button class="btn btn-outline-secondary w-100" type="submit">
                Search
            </button>
        </div>
    </div>
</form>

<table class="table align-middle">
<thead>
    <tr>
        <th scope="col">#</th>
        <th scope="col">User</th>
        <th scope="col">Foto</th>
        <th scope="col">Nama</th>
        <th scope="col">Kategori</th>
        <th scope="col">Harga Beli</th>
        <th scope="col">Harga Jual</th>
        <th scope="col">Stok</th>
        <th scope="col">Aksi</th>
    </tr>
</thead>

<tbody>
    @forelse ($products as $product)
    <tr>
        <th scope="row">{{ $loop->iteration }}</th>

        <td>{{ $product->user->name ?? '' }}</td>

        <td>
            @if ($product->foto)
                <img src="{{ asset('storage/' . $product->foto) }}" width="100" class="img-thumbnail">
            @else
                <span class="text-muted">Tidak ada foto</span>
            @endif
        </td>

        <td>{{ $product->name }}</td>

        <td>{{ $product->jenis->nama_jenis ?? '-' }}</td>

        <td>Rp{{ number_format($product->harga_beli, 0, ',', '.') }}</td>

        <td>Rp{{ number_format($product->harga_jual, 0, ',', '.') }}</td>

        <td>{{ $product->stok }}</td>

        <td>
            <div class="d-flex gap-1 align-items-center">

                @can('update', $product)
                    <a href="{{ route('produk.edit', $product) }}" class="btn btn-warning btn-sm">
                        Edit
                    </a>
                @endcan

                @can('delete', $product)
                    <form action="{{ route('produk.destroy', $product) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')

                        <button class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus produk ini?')">
                            Hapus
                        </button>
                    </form>
                @endcan

            </div>
        </td>
    </tr>

    @empty

    <tr>
        <td colspan="9" class="text-center py-4">
            <h5 class="text-muted">Data tidak tersedia.</h5>
        </td>
    </tr>

    @endforelse
</tbody>
</table>

<div class="mt-3">
    {{ $products->links() }}
</div>
@endsection
@extends("layouts.app")

@section("title", "jenis")

@section("content")

<h1 class="mb-4">Edit Jenis</h1>

<form action="{{ route("jenis.update", $jenis) }}" method="POST">
    @csrf
    @method("PUT")
    <div class="mb-3">
        <label for="nama" class="form-label">Nama Jenis</label>
        <input type="text" name="nama" id="nama" class="form-control @error("nama") is-invalid @enderror" value="{{ old("nama", $jenis->nama_jenis) }}">
        @error("nama")
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <button type="submit" class="btn btn-success">Update</button>
    <a href="{{ route("jenis.index") }}" class="btn btn-secondary">Kembali</a>
</form>
@endsection
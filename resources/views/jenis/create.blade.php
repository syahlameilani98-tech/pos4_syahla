@extends("layouts.app")

@section("title", "jenis")

@section("content")

<h1 class="mb-4">Tambah Jenis</h1>

<form action="{{ route("jenis.store") }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="mb-3">
        <label for="foto" class="form-label">Foto</label>
        <input type="file" name="foto" id="foto" class="form-control @error("foto") is-invalid @enderror">
        @error("foto")
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="mb-3">
        <label for="nama" class="form-label">Nama Jenis</label>
        <select name="nama" id="nama" class="form-select @error("nama") is-invalid @enderror">
            <option value="" disabled {{ old("nama") ? "" : "selected" }}>-- Pilih Jenis --</option>
            <option value="Makanan" {{ old("nama") == "Makanan" ? "selected" : "" }}>Makanan</option>
            <option value="Minuman" {{ old("nama") == "Minuman" ? "selected" : "" }}>Minuman</option>
        </select>
        @error("nama")
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <button type="submit" class="btn btn-success">Simpan</button>
    <a href="{{ route("jenis.index") }}" class="btn btn-secondary">Kembali</a>
</form>
@endsection
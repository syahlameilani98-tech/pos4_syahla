@csrf 

@if (!empty($produk->foto))
    <div class="mb-2">
        <label>Foto Saat Ini</lable><br>
        <img src="{{ asset('storage/' . $produk->foto) }}"
             width="150"
             class="img-thumbnail">
        </div>
@endif
   <div class="row">
    <div class="col">

<div>
    <label>Gambar</label>
    <input type="file"
            name="foto"
            class="form-control @error('foto') is-invalid @enderror">
            @error('foto')
            <div class="invalid-feedback d-block">
                {{ $message }}
            </div>
            @enderror
</div>

<div>
    <label>Nama Jenis</label><br>
    <select name="jenis_id"
            class="form-select @error('jenis_id') is-invalid @enderror">
        <option value="" disabled {{ old('jenis_id', $produk->jenis_id ?? '') ? '' : 'selected' }}>-- Pilih Jenis --</option>
        @foreach ($jenis as $item)
            <option value="{{ $item->id }}" {{ old('jenis_id', $produk->jenis_id ?? '') == $item->id ? 'selected' : '' }}>{{ $item->nama }}</option>
        @endforeach
    </select>
    @error('jenis_id')
    <div class="invalid-feedback d-block">
        {{ $message }}
    </div>
    @enderror
</div>

<div>
    <label>Nama produk</label><br>
    <input type="text" name="name"
            class="form-control @error('name') is-invalid @enderror"
            value="{{ old('name') ?? '' }}">
            @error('name')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
            @enderror
</div>

<div>
    <label>Harga Beli</label><br>
    <input type="number" name="purchase_price"
            class="form-control @error('purchase_price') is-invalid @enderror"
            value="{{ old('purchase_price') ?? '' }}">
            @error('purchase_price')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
            @enderror
</div>

<div>
    <label>Harga Jual</label><br>
    <input type="number" name="selling_price"
            class="form-control @error('selling_price') is-invalid @enderror"
            value="{{ old('selling_price') ?? '' }}">
            @error('selling_price')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
            @enderror
</div>

<div>
    <label>Stok</label><br>
    <input type="number" name="stock"
            class="form-control @error('stock') is-invalid @enderror"
            value="{{ old('stock', $produk->stok ?? '')}}">
            @error('stock')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
            @enderror
</div>

<button class="btn btn-success mt-3" type="submit">Simpan</button>

<script>
    function previewImage(input) {
        const preview = document.getElementById('preview');
        const file = input.files[0];

        if (file) {
            preview.src = URL.createObjectURL(file);
            preview.style.display ="block";
        }
    }
</script>
<a href="{{ route('produk.index') }}" class="btn btn-secondary mt-3">Kembali</a>
@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <h3 class="fw-bold mb-4">Tambah Penjualan</h3>

    {{-- Notifikasi --}}
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

    <div class="row g-4">
        {{-- ================= SISI KIRI: DAFTAR PRODUK ================= --}}
        <div class="col-md-6">
            <div class="card border-0 shadow-sm p-3">
                <form action="{{ route('penjualan.create') }}" method="GET" class="mb-3">
                    <input type="text" name="search" class="form-control" placeholder="Cari produk..." value="{{ request('search') }}">
                </form>

                <div style="max-height: 480px; overflow-y: auto;" class="pe-2">
                    @forelse($produks as $product)
                        <form action="{{ route('penjualan.add-to-cart') }}" method="POST" class="mb-2">
                            @csrf
                            <input type="hidden" name="produk_id" value="{{ $product->id }}">
                            <div class="card p-2 border rounded shadow-sm d-flex flex-row align-items-center justify-content-between">
                                <div class="d-flex align-items-center gap-2" style="width: 55%;">
                                    <img src="{{ $product->foto ? asset('storage/'.$product->foto) : 'https://via.placeholder.com/50' }}"
                                         alt="Gambar" class="rounded" style="width: 45px; height: 45px; object-fit: cover;">
                                    <div>
                                        <h6 class="mb-0 text-primary fw-semibold" style="font-size: 0.95rem;">
                                            {{ $product->nama ?? $product->nama_produk }}
                                        </h6>
                                        <small class="text-muted d-block">
                                            Rp.{{ number_format($product->harga_jual ?? $product->harga ?? 0, 0, ',', '.') }}
                                        </small>
                                        <span class="badge {{ $product->stok > 0 ? 'bg-info' : 'bg-danger' }}" style="font-size: 0.7rem;">
                                            Stok: {{ $product->stok }}
                                        </span>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center gap-2" style="width: 40%;">
                                    @if($product->stok > 0)
                                        <input type="number" name="qty" value="1" min="1" max="{{ $product->stok }}"
                                               class="form-control form-control-sm text-center" style="width: 65px;">
                                        <button type="submit" class="btn btn-primary btn-sm px-3 fw-bold flex-grow-1">+</button>
                                    @else
                                        <button type="button" class="btn btn-secondary btn-sm w-100" disabled>Habis</button>
                                    @endif
                                </div>
                            </div>
                        </form>
                    @empty
                        <div class="text-center text-muted py-4">Produk tidak ditemukan</div>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- ================= SISI KANAN: TABEL KERANJANG ================= --}}
        <div class="col-md-6">
            <div class="card border-0 shadow-sm p-3">
                <div class="table-responsive" style="min-height: 220px;">
                    <table class="table align-middle text-sm mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Produk</th>
                                <th>Harga</th>
                                <th style="width: 70px;">Qty</th>
                                <th>Subtotal</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($sale->itemPenjualan as $item)
                                <tr>
                                    <td class="fw-semibold text-break" style="max-width: 130px;">
                                        {{ $item->produk->nama ?? $item->produk->nama_produk }}
                                    </td>
                                    <td>Rp.{{ number_format($item->harga_satuan ?? 0, 0, ',', '.') }}</td>
                                    <td>
                                        <input type="text" class="form-control form-control-sm text-center p-1" value="{{ $item->kuantitas }}" readonly style="background-color: #f8f9fa;">
                                    </td>
                                    <td class="fw-bold">Rp.{{ number_format($item->subtotal, 0, ',', '.') }}</td>
                                    <td class="text-center">
                                        <form action="{{ route('penjualan.remove-from-cart', $item->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm px-2 py-1" style="font-size: 0.8rem;">Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-5">Belum ada item di keranjang</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <hr class="my-3">

                <form action="{{ route('penjualan.update', $sale->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="text-muted">Total</span>
                        <span class="fw-bold fs-5 text-dark">Rp.{{ number_format($grandTotal, 0, ',', '.') }}</span>
                    </div>

                    <div class="mb-3" id="uang-masuk-wrapper">
                        <label class="form-label small text-muted mb-1">Uang Masuk</label>
                        <input type="number"
                               name="uang_masuk"
                               id="uang_masuk"
                               class="form-control @error('uang_masuk') is-invalid @enderror"
                               placeholder="Masukkan jumlah uang diterima"
                               min="0">
                        @error('uang_masuk')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex justify-content-between align-items-center mb-3" id="kembalian-wrapper">
                        <span class="text-muted">Kembalian</span>
                        <span id="kembalian_display" class="fw-bold fs-6 text-success">Rp.0</span>
                    </div>

                    <div class="mb-3">
                        <select name="payment_method" id="payment_method" class="form-select form-select-sm" required>
                            <option value="CASH" {{ $sale->metode_pembayaran == 'CASH' ? 'selected' : '' }}>Cash</option>
                            <option value="QRIS" {{ $sale->metode_pembayaran == 'QRIS' ? 'selected' : '' }}>QRIS</option>
                        </select>
                    </div>

                    {{-- <!-- QRIS: box QR otomatis muncul saat QRIS dipilih --> --}}
                    <div id="qris-box" style="display:none; text-align:center; border:1px dashed #9ca3af; border-radius:8px; padding:16px; margin-bottom:16px;">
                        <div id="qris-canvas" class="d-flex justify-content-center"></div>
                        <p class="fw-bold mt-2 mb-0" id="qris-nominal">Rp 0</p>
                        <p class="small text-muted mb-0">Scan QRIS di atas untuk membayar</p>
                    </div>

                    <button type="submit" class="btn btn-success w-100 fw-bold py-2 mb-2"
                            {{ $sale->itemPenjualan->count() == 0 ? 'disabled' : '' }}>
                        Checkout
                    </button>
                </form>

                <form action="{{ route('penjualan.clear-cart') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-outline-danger w-100 py-1" style="font-size: 0.9rem;"
                            onclick="return confirm('Apakah Anda yakin ingin membatalkan transaksi ini?')"
                            {{ $sale->itemPenjualan->count() == 0 ? 'disabled' : '' }}>
                        Batalkan Transaksi
                    </button>
                </form>

            </div>
        </div>
    </div>
</div>

{{-- <!-- QRIS: library untuk render gambar QR di browser --> --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>

<script>
    (function () {
        const grandTotal = {{ $grandTotal }};
        const uangMasukInput = document.getElementById('uang_masuk');
        const kembalianDisplay = document.getElementById('kembalian_display');

        if (uangMasukInput) {
            uangMasukInput.addEventListener('input', function () {
                const uangMasuk = parseFloat(this.value) || 0;
                const kembalian = uangMasuk - grandTotal;

                kembalianDisplay.textContent = 'Rp.' + kembalian.toLocaleString('id-ID');
                kembalianDisplay.classList.toggle('text-danger', kembalian < 0);
                kembalianDisplay.classList.toggle('text-success', kembalian >= 0);
            });
        }
    })();

    // <!-- QRIS: mulai bagian baru -->
    (function () {
        // GANTI dengan string QRIS statis toko kamu dari bank/PJSP
        const QRIS_STATIS_TOKO = "00020101021126610014ID.CO.QRIS.WWW..."; // <-- WAJIB DIGANTI

        function crc16ccitt(str) {
            let crc = 0xFFFF;
            for (let i = 0; i < str.length; i++) {
                crc ^= str.charCodeAt(i) << 8;
                for (let j = 0; j < 8; j++) {
                    crc = (crc & 0x8000) ? (crc << 1) ^ 0x1021 : crc << 1;
                    crc &= 0xFFFF;
                }
            }
            return crc.toString(16).toUpperCase().padStart(4, '0');
        }

        function buildTag(tag, value) {
            const len = value.length.toString().padStart(2, '0');
            return tag + len + value;
        }

        function generateDynamicQris(staticPayload, amount) {
            let payload = staticPayload.substring(0, staticPayload.length - 4);
            payload = payload.substring(0, payload.length - 4); // buang "6304"
            payload = payload.replace('010211', '010212');       // statis -> dinamis

            const tag54 = buildTag('54', Number(amount).toFixed(2));
            const idx = payload.indexOf('5802ID');
            payload = payload.slice(0, idx) + tag54 + payload.slice(idx);

            payload += '6304';
            payload += crc16ccitt(payload);
            return payload;
        }

        function renderQris(amount) {
            const container = document.getElementById('qris-canvas');
            container.innerHTML = '';
            const dynamicPayload = generateDynamicQris(QRIS_STATIS_TOKO, amount);

            new QRCode(container, {
                text: dynamicPayload,
                width: 200,
                height: 200,
                correctLevel: QRCode.CorrectLevel.M
            });

            document.getElementById('qris-nominal').innerText =
                'Rp ' + Number(amount).toLocaleString('id-ID');
        }

        const paymentSelect = document.getElementById('payment_method');
        const qrisBox = document.getElementById('qris-box');
        const uangMasukWrapper = document.getElementById('uang-masuk-wrapper');
        const kembalianWrapper = document.getElementById('kembalian-wrapper');
        const uangMasukField = document.getElementById('uang_masuk');
        const grandTotalForQris = {{ $grandTotal }};

        function toggleQris() {
            if (paymentSelect.value === 'QRIS') {
                qrisBox.style.display = 'block';
                uangMasukWrapper.style.display = 'none';
                kembalianWrapper.style.display = 'none';
                uangMasukField.removeAttribute('required');
                renderQris(grandTotalForQris);
            } else {
                qrisBox.style.display = 'none';
                uangMasukWrapper.style.display = 'block';
                kembalianWrapper.style.display = 'flex';
                uangMasukField.setAttribute('required', 'required');
            }
        }

        paymentSelect.addEventListener('change', toggleQris);
        toggleQris(); // cek kondisi awal saat halaman dimuat
    })();
    // <!-- QRIS: selesai bagian baru -->
</script>
@endsection
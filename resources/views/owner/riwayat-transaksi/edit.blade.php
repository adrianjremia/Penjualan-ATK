@extends('layouts.owner')

@section('content')
<style>
    .container {
        max-width: 900px;
        background-color: white;
        padding: 40px;
        border-radius: 8px;
        border: 1px solid #e5e7eb;
    }

    .header {
        margin-bottom: 40px;
        border-bottom: 2px solid #f0f0f0;
        padding-bottom: 20px;
    }

    .header h1 {
        font-size: 32px;
        font-weight: 700;
        color: #1a1a1a;
        margin-bottom: 5px;
    }

    .header p {
        color: #666;
        font-size: 14px;
    }

    .transaction-info {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 30px;
        margin-bottom: 40px;
        padding: 20px 0;
        border-top: 1px solid #f0f0f0;
        border-bottom: 1px solid #f0f0f0;
    }

    .info-block label {
        display: block;
        color: #666;
        font-size: 12px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 5px;
    }

    .info-block .value {
        font-size: 16px;
        font-weight: 600;
        color: #1a1a1a;
    }

    .section-title {
        font-size: 16px;
        font-weight: 700;
        color: #1a1a1a;
        margin-bottom: 20px;
        margin-top: 30px;
    }

    .items-form {
        margin-bottom: 30px;
    }

    .item-row {
        display: grid;
        grid-template-columns: 2fr 1fr 1fr 1fr 0.5fr;
        gap: 15px;
        align-items: center;
        padding: 15px;
        background: #f9fafb;
        border-radius: 6px;
        margin-bottom: 12px;
        border: 1px solid #e5e7eb;
    }

    .item-row label {
        display: block;
        color: #666;
        font-size: 12px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 5px;
        font-weight: 600;
    }

    .item-row input {
        width: 100%;
        padding: 10px;
        border: 1px solid #d1d5db;
        border-radius: 6px;
        font-size: 14px;
        font-family: inherit;
    }

    .item-row input:focus {
        outline: none;
        border-color: #3b82f6;
        background: #ffffff;
    }

    .item-info {
        color: #666;
        font-size: 14px;
    }

    .item-info strong {
        color: #1a1a1a;
    }

    .subtotal-display {
        font-weight: 600;
        color: #1a1a1a;
        font-size: 14px;
    }

    .total-payment {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 20px;
        background-color: #f8f9ff;
        border-radius: 4px;
        margin-top: 30px;
    }

    .total-payment .label {
        font-size: 16px;
        font-weight: 700;
        color: #1a1a1a;
    }

    .total-payment .amount {
        font-size: 28px;
        font-weight: 700;
        color: #4f46e5;
    }

    .button-group {
        display: flex;
        justify-content: center;
        gap: 15px;
        margin-top: 40px;
        flex-wrap: wrap;
    }

    .btn {
        padding: 12px 24px;
        font-size: 14px;
        font-weight: 600;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        transition: all 0.2s;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .btn-primary {
        background-color: #3b82f6;
        color: white;
    }

    .btn-primary:hover {
        background-color: #2563eb;
    }

    .btn-secondary {
        background-color: transparent;
        color: #6b7280;
        border: none;
    }

    .btn-secondary:hover {
        color: #1f2937;
    }

    .btn img {
        width: 16px;
        height: 16px;
        object-fit: contain;
    }

    .back-link {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        color: #374151;
        text-decoration: none;
        font-size: 14px;
        margin-bottom: 20px;
        transition: all 0.2s;
    }

    .back-link:hover {
        gap: 12px;
        color: #1f2937;
    }

    .back-link img {
        width: 16px;
        height: 16px;
        object-fit: contain;
    }

    .alert {
        padding: 15px;
        border-radius: 6px;
        margin-bottom: 20px;
        font-size: 14px;
    }

    .alert-warning {
        background-color: #fef3c7;
        border: 1px solid #fcd34d;
        color: #92400e;
    }

    .alert-error {
        background-color: #fee2e2;
        border: 1px solid #fca5a5;
        color: #991b1b;
    }
</style>

<a href="{{ route('owner.riwayat-transaksi.index') }}" class="back-link">
    <img src="{{ asset('images/icons/back.png') }}" alt="Back">
    Kembali ke Riwayat Transaksi
</a>

<div class="container">
    <div class="header">
        <h1>Edit Invoice</h1>
        <p>Ubah jumlah item transaksi (stok akan disesuaikan otomatis)</p>
    </div>

    @if($errors->any())
        <div class="alert alert-error">
            <strong>Terjadi kesalahan:</strong>
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if(session('warning'))
        <div class="alert alert-warning">
            <strong>Perhatian:</strong> {{ session('warning') }}
        </div>
    @endif

    <div class="alert alert-warning">
        <strong>Perhatian:</strong> Mengubah jumlah item akan secara otomatis menambah atau mengurangi stok barang. Pastikan stok mencukupi sebelum menyimpan perubahan.
    </div>

    <div class="transaction-info">
        <div class="info-block">
            <label>ID Transaksi:</label>
            <div class="value">T{{ str_pad($transaksi->id_transaksi, 3, '0', STR_PAD_LEFT) }}</div>
        </div>
        <div class="info-block">
            <label>Tanggal Transaksi:</label>
            <div class="value">
                @php
                    $tanggal = \Carbon\Carbon::parse($transaksi->created_at);
                    echo $tanggal->translatedFormat('d F Y H:i');
                @endphp
            </div>
        </div>
    </div>

    <form method="POST" action="{{ route('owner.riwayat-transaksi.update', $transaksi->id_transaksi) }}" onsubmit="return validateForm()">
        @csrf
        @method('PUT')

        <h2 class="section-title">Detail Item</h2>

        <div class="items-form">
            @forelse($transaksi->detailTransaksi as $detail)
                <div class="item-row">
                    <div>
                        <label>Nama Barang</label>
                        <div class="item-info">
                            <strong>{{ $detail->barang->nama_barang }}</strong>
                        </div>
                    </div>
                    <div>
                        <label>Harga</label>
                        <div class="item-info">
                            <strong>
                                @if($detail->harga)
                                    Rp {{ number_format($detail->harga, 0, ',', '.') }}
                                @else
                                    Rp {{ number_format($detail->barang->harga_jual, 0, ',', '.') }}
                                @endif
                            </strong>
                        </div>
                    </div>
                    <div>
                        <label>Jumlah Baru</label>
                        <input 
                            type="number" 
                            name="items[{{ $loop->index }}][jumlah]" 
                            value="{{ $detail->jumlah }}" 
                            min="1"
                            class="qty-input"
                            data-harga="{{ $detail->harga ?: $detail->barang->harga_jual }}"
                            data-index="{{ $loop->index }}"
                            onchange="updateSubtotal(this)">
                        <input type="hidden" name="items[{{ $loop->index }}][id_detail]" value="{{ $detail->id_detail }}">
                    </div>
                    <div>
                        <label>Subtotal</label>
                        <div class="subtotal-display" id="subtotal-{{ $loop->index }}" data-original="{{ $detail->harga ?: $detail->barang->harga_jual }}">
                            Rp {{ number_format($detail->harga && $detail->subtotal ? $detail->subtotal : ($detail->barang->harga_jual * $detail->jumlah), 0, ',', '.') }}
                        </div>
                    </div>
                    <div style="display: flex; align-items: flex-end; padding-bottom: 5px;">
                        <span class="item-info" style="color: #666; font-size: 12px;">
                            (Stok: {{ $detail->barang->stok }})
                        </span>
                    </div>
                </div>
            @empty
                <p style="text-align: center; color: #999;">Tidak ada item dalam transaksi</p>
            @endforelse
        </div>

        <div class="total-payment">
            <div class="label">Total Pembayaran:</div>
            <div class="amount" id="total-amount">
                @php
                    $calculatedTotal = 0;
                    foreach ($transaksi->detailTransaksi as $detail) {
                        $harga = $detail->harga ?: $detail->barang->harga_jual;
                        $calculatedTotal += $harga * $detail->jumlah;
                    }
                @endphp
                Rp {{ number_format($calculatedTotal, 0, ',', '.') }}
            </div>
        </div>

        <div class="button-group">
            <button type="submit" class="btn btn-primary">
                Simpan Perubahan
            </button>
            <a href="{{ route('owner.riwayat-transaksi.index') }}" class="btn btn-secondary">
                Batal
            </a>
        </div>
    </form>
</div>

<script>
    function updateSubtotal(input) {
        const index = input.dataset.index;
        const harga = parseInt(input.dataset.harga);
        const jumlah = parseInt(input.value) || 0;
        const subtotal = harga * jumlah;

        document.getElementById(`subtotal-${index}`).textContent = 
            'Rp ' + subtotal.toLocaleString('id-ID');

        updateTotal();
    }

    function updateTotal() {
        let total = 0;
        document.querySelectorAll('.qty-input').forEach(input => {
            const index = input.dataset.index;
            const harga = parseInt(input.dataset.harga);
            const jumlah = parseInt(input.value) || 0;
            total += harga * jumlah;
        });

        document.getElementById('total-amount').textContent = 
            'Rp ' + total.toLocaleString('id-ID');
    }

    function validateForm() {
        const inputs = document.querySelectorAll('.qty-input');
        let valid = true;

        inputs.forEach(input => {
            if (parseInt(input.value) < 1) {
                alert('Jumlah barang harus minimal 1');
                valid = false;
            }
        });

        return valid;
    }
</script>

@endsection

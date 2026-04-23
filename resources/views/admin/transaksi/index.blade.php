@extends('layouts.admin')

@section('content')
<style>
    .page-header {
        margin-bottom: 2rem;
    }

    .page-header h1 {
        font-size: 28px;
        font-weight: 600;
        color: #1f2937;
        margin-bottom: 0.25rem;
    }

    .page-header p {
        color: #6b7280;
        font-size: 14px;
    }

    .container-main {
        display: grid;
        grid-template-columns: 1fr 350px;
        gap: 24px;
    }

    .card {
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        padding: 24px;
        background: #ffffff;
    }

    .card h2 {
        font-size: 18px;
        font-weight: 600;
        color: #1f2937;
        margin-bottom: 20px;
    }

    .form-group {
        display: flex;
        gap: 12px;
        align-items: flex-end;
        margin-bottom: 20px;
    }

    .form-group label {
        display: block;
        font-size: 13px;
        color: #6b7280;
        font-weight: 500;
        margin-bottom: 8px;
    }

    .form-group select,
    .form-group input {
        flex: 1;
        padding: 10px 12px;
        border: 1px solid #d1d5db;
        border-radius: 6px;
        font-size: 14px;
        font-family: inherit;
    }

    .form-group input {
        flex: 0.15;
    }

    .form-group select:focus,
    .form-group input:focus {
        outline: none;
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }

    .btn-tambah {
        padding: 10px 20px;
        background: #3b82f6;
        color: white;
        border: none;
        border-radius: 6px;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s;
        display: flex;
        align-items: center;
        gap: 6px;
        white-space: nowrap;
    }

    .btn-tambah:hover:not(:disabled) {
        background: #2563eb;
    }

    .btn-tambah:disabled {
        background: #d1d5db;
        cursor: not-allowed;
        color: #9ca3af;
    }

    .table-wrapper {
        overflow-x: auto;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        font-size: 14px;
    }

    table thead {
        background: #f9fafb;
        border-bottom: 1px solid #e5e7eb;
    }

    table th {
        padding: 12px;
        text-align: left;
        font-weight: 600;
        color: #1f2937;
    }

    table td {
        padding: 12px;
        border-bottom: 1px solid #e5e7eb;
        color: #374151;
    }

    table tbody tr:last-child td {
        border-bottom: none;
    }

    .empty-state {
        text-align: center;
        color: #9ca3af;
        padding: 32px 12px;
    }

    .btn-hapus {
        background: transparent;
        color: #ef4444;
        border: none;
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
        padding: 4px 0;
        transition: color 0.2s;
    }

    .btn-hapus:hover {
        color: #dc2626;
    }

    .summary-card {
        position: sticky;
        top: 24px;
    }

    .summary-row {
        display: flex;
        justify-content: space-between;
        padding: 12px 0;
        border-bottom: 1px solid #e5e7eb;
        font-size: 14px;
    }

    .summary-row label {
        color: #6b7280;
    }

    .summary-row span {
        color: #1f2937;
        font-weight: 500;
    }

    .summary-total {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 16px 0;
        margin-top: 8px;
        font-size: 16px;
        font-weight: 600;
    }

    .summary-total label {
        color: #1f2937;
    }

    .summary-total span {
        color: #3b82f6;
        font-size: 24px;
    }

    .btn-group {
        display: flex;
        flex-direction: column;
        gap: 12px;
        margin-top: 24px;
    }

    .btn-simpan {
        background: #10b981;
        color: white;
        border: none;
        padding: 12px 16px;
        border-radius: 6px;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
    }

    .btn-simpan:hover {
        background: #059669;
    }

    .btn-reset {
        background: transparent;
        color: #6b7280;
        border: none;
        padding: 10px 16px;
        border-radius: 6px;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
    }

    .btn-reset:hover {
        color: #1f2937;
        background: #f3f4f6;
    }

    @media (max-width: 1024px) {
        .container-main {
            grid-template-columns: 1fr;
        }

        .summary-card {
            position: static;
        }
    }
</style>

<div class="page-header">
    <h1>Transaksi Penjualan</h1>
    <p>Buat transaksi penjualan baru</p>
</div>

<div class="container-main">
    <!-- PILIH BARANG & KERANJANG -->
    <div>
        <!-- PILIH BARANG -->
        <div class="card">
            <h2>Pilih Barang</h2>
            
            <div class="form-group">
                <div style="flex: 1;">
                    <label for="barangSelect">Barang</label>
                    <select id="barangSelect">
                        <option value="">Pilih barang</option>
                        @foreach($barang as $b)
                            <option
                                value="{{ $b->id_barang }}"
                                data-nama="{{ $b->nama_barang }}"
                                data-harga="{{ $b->harga_jual }}"
                                data-stok="{{ $b->stok }}">
                                {{ $b->nama_barang }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div style="flex: 0.2;">
                    <label for="jumlahInput">Jumlah</label>
                    <input type="number" id="jumlahInput" value="1" min="1">
                </div>

                <button type="button" id="btnTambah" class="btn-tambah" onclick="addToCart()" disabled>
                    <span>+</span> Tambah
                </button>
            </div>
        </div>

        <!-- KERANJANG -->
        <div class="card">
            <h2>Keranjang</h2>

            <div class="table-wrapper">
                <table>
                    <thead>
                        <tr>
                            <th>Nama Barang</th>
                            <th>Harga</th>
                            <th>Jumlah</th>
                            <th>Subtotal</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="cartTable">
                        <tr>
                            <td colspan="5" class="empty-state">Keranjang masih kosong</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- RINGKASAN -->
    <div class="card summary-card">
        <h2>Ringkasan</h2>

        <div class="summary-row">
            <label>Total Item:</label>
            <span id="totalItem">0</span>
        </div>

        <div class="summary-row">
            <label>Total Barang:</label>
            <span id="totalBarang">0</span>
        </div>

        <div class="summary-total">
            <label>Total Harga:</label>
            <span>Rp <span id="totalHarga">0</span></span>
        </div>

        <form method="POST" action="{{ route('admin.transaksi.store') }}" id="transaksiForm">
            @csrf
            <input type="hidden" name="cart" id="cartInput">
            
            <div class="btn-group">
                <button type="submit" class="btn-simpan">
                    <img src="{{ asset('images/icons/CART.png') }}" alt="cart" style="width: 18px; height: 18px; object-fit: contain;"> Simpan Transaksi
                </button>
                <button type="button" class="btn-reset" onclick="resetCart()">
                    <img src="{{ asset('images/icons/reset.png') }}" alt="reset" style="width: 18px; height: 18px; object-fit: contain;"> Reset
                </button>
            </div>
        </form>
    </div>
</div>

@endsection

<script>
let cart = [];

function formatRupiah(value) {
    return value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
}

function updateButtonState() {
    const barangSelect = document.getElementById('barangSelect');
    const btnTambah = document.getElementById('btnTambah');
    
    if (barangSelect.value === '') {
        btnTambah.disabled = true;
    } else {
        btnTambah.disabled = false;
    }
}

document.getElementById('barangSelect').addEventListener('change', updateButtonState);

function addToCart() {
    const select = document.getElementById('barangSelect');
    const jumlah = parseInt(document.getElementById('jumlahInput').value);

    if (!select.value || jumlah <= 0) return alert('Pilih barang & jumlah');

    const id = select.value;
    const nama = select.selectedOptions[0].dataset.nama;
    const harga = parseInt(select.selectedOptions[0].dataset.harga);
    const stok = parseInt(select.selectedOptions[0].dataset.stok);

    const existing = cart.find(item => item.id_barang == id);

    if (existing) {
        if (existing.jumlah + jumlah > stok) {
            return alert('Stok tidak cukup');
        }
        existing.jumlah += jumlah;
        existing.subtotal = existing.jumlah * harga;
    } else {
        if (jumlah > stok) return alert('Stok tidak cukup');

        cart.push({
            id_barang: id,
            nama: nama,
            harga: harga,
            jumlah: jumlah,
            subtotal: harga * jumlah
        });
    }

    select.value = '';
    document.getElementById('jumlahInput').value = '1';
    renderCart();
}

function removeItem(index) {
    cart.splice(index, 1);
    renderCart();
}

function resetCart() {
    cart = [];
    renderCart();
}

function renderCart() {
    const tbody = document.getElementById('cartTable');
    tbody.innerHTML = '';

    let totalHarga = 0;
    let totalItem = cart.length;
    let totalBarang = 0;

    if (cart.length === 0) {
        tbody.innerHTML = `<tr><td colspan="5" class="empty-state">Keranjang masih kosong</td></tr>`;
    }

    cart.forEach((item, index) => {
        totalHarga += item.subtotal;
        totalBarang += item.jumlah;

        tbody.innerHTML += `
            <tr>
                <td>${item.nama}</td>
                <td>Rp ${formatRupiah(item.harga)}</td>
                <td>${item.jumlah}</td>
                <td>Rp ${formatRupiah(item.subtotal)}</td>
                <td><button type="button" class="btn-hapus" onclick="removeItem(${index})">Hapus</button></td>
            </tr>
        `;
    });

    document.getElementById('totalHarga').innerText = formatRupiah(totalHarga);
    document.getElementById('totalItem').innerText = totalItem;
    document.getElementById('totalBarang').innerText = totalBarang;

    // kirim ke backend
    document.getElementById('cartInput').value = JSON.stringify(cart);
}
</script>

@extends('layouts.admin')

@section('content')
<h1>Transaksi Penjualan</h1>
<p>Buat transaksi penjualan baru</p>

<div style="display:flex; gap:20px">

    <!-- PILIH BARANG -->
    <div style="width:60%">
        <h3>Pilih Barang</h3>

        <select id="barangSelect">
            <option value="">Pilih barang</option>
            @foreach($barang as $b)
                <option
                    value="{{ $b->id_barang }}"
                    data-nama="{{ $b->nama_barang }}"
                    data-harga="{{ $b->harga_jual }}"
                    data-stok="{{ $b->stok }}">
                    {{ $b->nama_barang }} (Stok: {{ $b->stok }})
                </option>
            @endforeach
        </select>

        <input type="number" id="jumlahInput" value="1" min="1">
        <button type="button" onclick="addToCart()">+ Tambah</button>

        <h3>Keranjang</h3>

        <table border="1" width="100%">
            <thead>
                <tr>
                    <th>Nama</th>
                    <th>Harga</th>
                    <th>Jumlah</th>
                    <th>Subtotal</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody id="cartTable">
                <tr>
                    <td colspan="5">Keranjang masih kosong</td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- RINGKASAN -->
    <div style="width:40%">
        <h3>Ringkasan</h3>

        <p>Total Item: <span id="totalItem">0</span></p>
        <p>Total Barang: <span id="totalBarang">0</span></p>

        <h2>Total Harga: Rp <span id="totalHarga">0</span></h2>

        <form method="POST" action="{{ route('admin.transaksi.store') }}">
            @csrf
            <input type="hidden" name="cart" id="cartInput">
            <button type="submit">Simpan Transaksi</button>
        </form>
    </div>

</div>
@endsection

<script>
let cart = [];

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

    renderCart();
}

function removeItem(index) {
    cart.splice(index, 1);
    renderCart();
}

function renderCart() {
    const tbody = document.getElementById('cartTable');
    tbody.innerHTML = '';

    let totalHarga = 0;
    let totalItem = cart.length;
    let totalBarang = 0;

    if (cart.length === 0) {
        tbody.innerHTML = `<tr><td colspan="5">Keranjang masih kosong</td></tr>`;
    }

    cart.forEach((item, index) => {
        totalHarga += item.subtotal;
        totalBarang += item.jumlah;

        tbody.innerHTML += `
            <tr>
                <td>${item.nama}</td>
                <td>${item.harga}</td>
                <td>${item.jumlah}</td>
                <td>${item.subtotal}</td>
                <td><button onclick="removeItem(${index})">Hapus</button></td>
            </tr>
        `;
    });

    document.getElementById('totalHarga').innerText = totalHarga;
    document.getElementById('totalItem').innerText = totalItem;
    document.getElementById('totalBarang').innerText = totalBarang;

    // kirim ke backend
    document.getElementById('cartInput').value = JSON.stringify(cart);
}
</script>
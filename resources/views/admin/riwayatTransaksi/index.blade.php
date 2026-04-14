@extends('layouts.admin')

@section('content')
<h2>Riwayat Transaksi</h2>

<table border="1" cellpadding="10" cellspacing="0" width="100%">
    <thead>
        <tr>
            <th>Kode</th>
            <th>Tanggal</th>
            <th>Jumlah Item</th>
            <th>Total Harga</th>
            <th>Aksi</th>
        </tr>
    </thead>

    <tbody>
        @forelse ($transaksis as $t)
        <tr>
            <td>T{{ str_pad($t->id_transaksi, 3, '0', STR_PAD_LEFT) }}</td>

            <td>
                {{ $t->tanggal_transaksi->translatedFormat('d F Y \p\u\k\u\l H.i') }}
            </td>

            <td>{{ $t->detail_transaksi_count }} item</td>

            <td>
                Rp {{ number_format($t->total_harga, 0, ',', '.') }}
            </td>

            <td>
                <a href="{{ route('admin.transaksi.invoice', $t->id_transaksi) }}">
                    Detail
                </a>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="5" align="center">Belum ada transaksi</td>
        </tr>
        @endforelse
    </tbody>
</table>

<br>

<strong>Total {{ $transaksis->count() }} Transaksi</strong><br>
<strong>Total Omset: Rp {{ number_format($totalKeseluruhan, 0, ',', '.') }}</strong>

@endsection
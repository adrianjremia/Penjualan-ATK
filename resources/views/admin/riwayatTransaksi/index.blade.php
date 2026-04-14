@foreach ($transaksis as $index => $t)
<tr>
    <td>T{{ str_pad($t->id_transaksi, 3, '0', STR_PAD_LEFT) }}</td>
    <td>{{ $t->tanggal_transaksi->translatedFormat('d F Y \\p\\u\\k\\u\\l H.i') }}</td>
    <td>{{ $t->detail_transaksi_count }} item</td>
    <td class="fw-bold">Rp {{ number_format($t->total_harga, 0, ',', '.') }}</td>
    <td>
        <a href="{{ route('admin.transaksi.invoice', $t->id_transaksi) }}"
           class="btn btn-outline-primary btn-sm">
            👁 Detail
        </a>
    </td>
</tr>
@endforeach

<div class="d-flex justify-content-between mt-4 p-3 bg-light rounded">
    <strong>Total {{ $transaksis->count() }} Transaksi</strong>
    <strong class="fs-4 text-primary">
        Rp {{ number_format($totalKeseluruhan, 0, ',', '.') }}
    </strong>
</div>
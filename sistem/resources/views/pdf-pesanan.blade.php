<style>
table,
th,
td {
    border: 1px solid black;
    border-collapse: collapse;
    padding: 10px;
}

.text-kop {
    text-align: center;
    line-height: 0.3;
}
</style>
<div class="container" align="center">
    <div class="row">
        <h4 class="text-center text-kop">APOTEK DUA FARMA</h4>
        <h5 class="text-center text-kop">Jalan Jendral Sudirman No. 6A RT 01 RW 09 Planjan, Kecamatan Kesugihan, 53274
        </h5>
        <h4 class="text-center text-kop">CILACAP</h4>
        <hr><br>
        <h3 class="text-center"><u>DATA INVOICE PESANAN ONLINE</u></h3>
    </div>
</div>
<table id="kategori" class="table table-responsive table-bordered table-hover table-striped" style="width: 100%;">
    <thead>
        <tr>
            <th class="text-center">No</th>
            <th class="text-center">Waktu</th>
            <th class="text-center">No Order</th>
            <th class="text-center">Total</th>
            <th class="text-center">Pengiriman</th>
            <th class="text-center">Alamat</th>
            <th class="text-center">Jarak</th>
            <th class="text-center">Ongkir</th>
            <th class="text-center">Pembayaran</th>
            <th class="text-center">Total Bayar</th>
            <th class="text-center">Status</th>
        </tr>
    </thead>
    <tbody>
        @php $no = 0 @endphp
        @foreach($pesanan as $value)
        @if ($value->status == 'Selesai')
        <tr>
            <td align="center">{{ ++$no }}</td>
            <td align="center">{{ $value->created_at }}</td>
            <td align="center">{{ $value->no_order }}</td>
            <td align="center">Rp. {{ number_format($value->grand_total) }}</td>
            <td align="center">{{ $value->metode_pengiriman }} </td>
            @foreach($pengiriman as $no => $kirim)
            @if($kirim->id_pesanan == $value->id_pesanan)
            <td align="center">{{ optional($kirim)->alamat ?? '-' }}</td>
            <td align="center">{{ optional($kirim)->jarak ?? '-' }}</td>
            <td align="center">Rp {{ number_format(optional($kirim)->ongkir ?? '-') }}</td>
            <td align="center">{{ $value->metode_pembayaran }}</td>
            <td align="center">Rp {{ number_format($kirim->ongkir + $value->grand_total) }}</td>
            @endif
            @endforeach
            <td align="center">{{ $value->status }}</td>
        </tr>
        @endif
        @endforeach
        @if ($no == 0)
        <tr>
            <td colspan="12" align="center">Data tidak ditemukan</td>
        </tr>
        @endif
    </tbody>
</table>
</div>
<div style="margin-left: 780px;">
    <p>Cilacap, ____________</p>
    <p>Penanggung Jawab</p>
    <br><br><br>
    <p>(___________________)</p>
</div>
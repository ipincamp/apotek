@extends('layouts.app-admin')
@extends('layouts.alert')

@section('content')
@if(auth()->check() && (auth()->user()->level == 'admin' || auth()->user()->level == 'owner'))
<div class="card card-data col-md-12">
    <h1 style="color: #34495e; margin: 30px 0 30px 0; font-weight: bold; text-align: center;">Laporan Keuangan</h1>
    @if(isset($tanggal))
    <p style="color: #B4B4B8; text-align: center;">Per Tanggal: {{ $tanggal }}</p>
    @elseif(isset($bulanTerpilih))
    <p style="color: #B4B4B8; text-align: center;">Per Bulan:
        {{ \Carbon\Carbon::createFromFormat('m', $bulanTerpilih)->translatedFormat('F') }}</p>
    @endif
    <div class="row">
        <div class="col-md-12 col-xs-12">
            <div class="row" style="margin: 0 10px 10px 10px;">
                <div class="col-md-8">
                    <a href="{{url('download-laporankeuangan')}}" target="_blank">
                        <button class="btn btn-danger">
                            <i class='fas fa-file-pdf'></i> Cetak Semua
                        </button>
                    </a>
                </div>
                <div class="col-md-4">
                    <form class="d-flex" action="{{url('admin-laporankeuangan')}}" method="GET">
                        <input type="date" class="form-control me-2" id="tanggal" name="tanggal">
                        <button type="submit" class="btn btn-primary">Cari</button>
                        @if(isset($tanggal))
                        <a href="{{ url('admin-laporankeuangan') }}" class="btn btn-secondary ms-2">All</a>
                        @endif
                    </form>
                </div>
            </div>
            <div class="row" style="margin: 0 10px 10px 10px;">
                <div class="col-md-4">
                    <form class="d-flex" action="{{ url('download-laporankeuanganbulanan') }}" method="GET"
                        target="_blank">
                        <select class="form-control me-2" id="bulan" name="bulan">
                            <option value="">Pilih Bulan</option>
                            <option value="01">Januari</option>
                            <option value="02">Februari</option>
                            <option value="03">Maret</option>
                            <option value="04">April</option>
                            <option value="05">Mei</option>
                            <option value="06">Juni</option>
                            <option value="07">Juli</option>
                            <option value="08">Agustus</option>
                            <option value="09">September</option>
                            <option value="10">Oktober</option>
                            <option value="11">November</option>
                            <option value="12">Desember</option>
                        </select>
                        <button type="submit" class="btn btn-danger">Cetak</button>
                    </form>
                </div>
                <div class="col-md-4"></div>
                <div class="col-md-4">
                    <form class="d-flex" action="{{url('admin-laporankeuangan')}}" method="GET">
                        <select class="form-control me-2" id="bulan" name="bulan">
                            <option value="">Pilih Bulan</option>
                            @foreach($bulanList as $key => $bulan)
                            <option value="{{$key}}" {{$key == $bulanTerpilih ? 'selected' : ''}}>{{$bulan}}
                            </option>
                            @endforeach
                        </select>
                        <button type="submit" class="btn btn-primary">Cari</button>
                        @if(isset($bulanTerpilih))
                        <a href="{{ url('admin-laporankeuangan') }}" class="btn btn-secondary ms-2">Semua Bulan</a>
                        @endif
                    </form>
                </div>
            </div>
        </div>

        <div class="table-responsive" style="width: 97%; margin-left: 15px;">
            <table id="example" class="table table-responsive table-striped table-hover table-bordered">
                <thead>
                    <tr>
                        <th class="text-center">No</th>
                        <th class="text-center">Waktu</th>
                        <th class="text-center">Produk</th>
                        <th class="text-center">Harga Beli</th>
                        <th class="text-center">Harga Jual</th>
                        <th class="text-center">QTY</th>
                        <th class="text-center">Laba Kotor</th>
                        <th class="text-center">Laba Bersih</th>
                    </tr>
                </thead>
                <tbody>
                    @if(isset($mergedDetail) && count($mergedDetail) > 0)
                    @php
                    $totalLabaKotor = 0;
                    $totalLabaBersih = 0;
                    @endphp
                    @foreach($mergedDetail as $no => $detail)
                    <tr>
                        <td align="center">{{$no+1}}</td>
                        <td align="center">{{$detail->updated_at}}</td>
                        @if($detail instanceof App\Models\PenjualanDetail || $detail instanceof
                        App\Models\PesananDetail)
                        <td align="center">{{$detail->produk->nama}}</td>
                        <td align="center">Rp. {{ number_format($detail->produk->harga_beli) }}</td>
                        <td align="center">Rp. {{ number_format($detail->produk->harga_jual) }}</td>
                        <td align="center">{{$detail->qty}}</td>
                        <td align="center">Rp. {{ number_format($detail->total) }}</td>
                        <td align="center">Rp.
                            {{ number_format($detail->total - ($detail->produk->harga_beli * $detail->qty)) }}</td>
                        @php
                        $labaKotor = $detail->total;
                        $totalLabaKotor += $labaKotor;
                        @endphp
                        @php
                        $labaBersih = $detail->total - ($detail->produk->harga_beli * $detail->qty);
                        $totalLabaBersih += $labaBersih;
                        @endphp
                        @endif
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="6" align="center"><strong>Total Pendapatan</strong></td>
                        <td align="center"><strong><?php echo "Rp." . number_format($totalLabaKotor); ?></strong></td>
                        <td align="center"><strong><?php echo "Rp." . number_format($totalLabaBersih); ?></strong></td>
                    </tr>
                    @endif
                </tfoot>
            </table>
        </div>
    </div>
</div>

@push('scripts')
<script>
new DataTable('#example', {
    responsive: true,
    rowReorder: {
        selector: 'td:nth-child(2)'
    }
});
</script>
@endpush

@else
<?php abort(403, 'Unauthorized action.'); ?>
@endif

@endsection
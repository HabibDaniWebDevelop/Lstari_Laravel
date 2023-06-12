<table class="table table-sm table-bordered" id="theTable" @isset($border) border="1" @endisset>
    <thead>
        <tr>
            <th colspan="9">Nota Terima Hasil Kerja Tukang Luar</th>
            <th colspan="7">Laboratorium</th>
        </tr>
        <tr>
            <th colspan="9">
                <table id="info">
                    <thead>
                        <tr>
                            <th>Nama Tukang Luar</th>
                            <th>:</th>
                            <th>{{$detail['NamaTukang']}}</th>
                        </tr>
                        <tr>
                            <th>No.Nota Tukang Luar</th>
                            <th>:</th>
                            <th>{{$nomorNota}}</td>
                        </tr>
                        <tr>
                            <th>Kadar</th>
                            <th>:</th>
                            <th>{{$detail['Kadar']}}</th>
                        </tr>
                        <tr>
                            <th>Proses</th>
                            <th>:</th>
                            <th>{{$detail['Proses']}}</th>
                        </tr>
                    </thead>
                </table>
            </th>
            <th colspan="6"></th>
        </tr>
        <tr>
            <th>No. Setor</th>
            <th>Tanggal</th>
            <th>Tukang</th>
            <th>No. Model</th>
            <th>Nama Barang</th>
            <th>SPK</th>
            <th>Jenis</th>
            <th>Qty</th>
            <th>Weight</th>
            <th>Tanggal</th>
            <th>Hasil Lab</th>
            <th>Toleransi</th>
            <th>Berat</th>
            <th>Selisih Berat</th>
            <th>Catatan</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($notaTukangLuar as $item)
        <tr>
            <td>@isset($item['SWSPKO']){{$item['SWSPKO']}} -@endisset @isset($item['Freq']){{$item['Freq']}}@endisset</td>
            <td>@isset($item['TanggalNTHKO']){{$item['TanggalNTHKO']}}@endisset</td>
            <td>@isset($item['Tukang']){{$item['Tukang']}}@endisset</td>
            <td>@isset($item['NamaProductFGNTHKO']){{$item['NamaProductFGNTHKO']}}@endisset</td>
            <td>@isset($item['NamaProductNTHKO']){{$item['NamaProductNTHKO']}}@endisset</td>
            <td>@isset($item['NomorSPKNTHKO']){{$item['NomorSPKNTHKO']}}@endisset</td>
            <td>@isset($item['CategoryProductFGNTHKO']){{$item['CategoryProductFGNTHKO']}}@endisset</td>
            <td>@isset($item['QtyNTHKO']){{$item['QtyNTHKO']}}@endisset</td>
            <td>@isset($item['WeightNTHKO']){{$item['WeightNTHKO']}}@endisset</td>
            <td>@isset($item['TanggalLab']){{$item['TanggalLab']}}@endisset</td>
            <td>@isset($item['HasilLab']){{$item['HasilLab']}}@endisset</td>
            <td>@isset($item['Toleransi']){{$item['Toleransi']}}@endisset</td>
            <td>@isset($item['WeightLab']){{$item['WeightLab']}}@endisset</td>
            <td>@isset($item['SelisihBerat']){{$item['SelisihBerat']}}@endisset</td>
            <td>@isset($item['Catatan']){{$item['Catatan']}}@endisset</td>
        </tr>
        @endforeach
        <tr>
            <td colspan="14" style="text-align: right;">Total:</td>
            <td>@isset($total_selisih_berat){{$total_selisih_berat}}@endisset</td>
        </tr>
    </tbody>
  </table>
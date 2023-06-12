<table class="table table-sm table-bordered" id="theTable" @isset($border) border="1" @endisset>
    <thead>
        <tr>
            <th colspan="7">Surat Perintah Kerja Tukang Luar</th>
            <th colspan="18">Nota Terima Hasil Kerja Tukang Luar</th>
        </tr>
        <tr>
            <th colspan="7">
                <table id="info">
                    <thead>
                        <tr>
                            <th>Nama Tukang Luar</th>
                            <th>:</th>
                            <th>{{$tukang}}</th>
                        </tr>
                        <tr>
                            <th>No.Nota Tukang Luar</th>
                            <th>:</th>
                            <th>{{$nomorNota}}</td>
                        </tr>
                        <tr>
                            <th>Kadar</th>
                            <th>:</th>
                            <th>{{$kadar}}</th>
                        </tr>
                        <tr>
                            <th>Proses</th>
                            <th>:</th>
                            <th>{{$proses}}</th>
                        </tr>
                    </thead>
                </table>
            </th>
            <th colspan="18"></th>
        </tr>
        <tr>
            <th rowspan="2">Tanggal</th>
            <th rowspan="2">Nama Barang</th>
            <th rowspan="2">SPK</th>
            <th rowspan="2">Jenis Barang</th>
            <th rowspan="2">Berat Timbangan Air</th>
            <th rowspan="2">Qty</th>
            <th rowspan="2">Berat</th>
            <th rowspan="2">Tanggal</th>
            <th rowspan="2">Tukang</th>
            <th rowspan="2">No. Model</th>
            <th rowspan="2">Nama Barang</th>
            <th rowspan="2">SPK</th>
            <th rowspan="2">Jenis</th>
            <th rowspan="2">Qty</th>
            <th rowspan="2">Berat</th>
            <th rowspan="2">Jumlah SS</th>
            <th rowspan="2">Berat SS</th>
            <th rowspan="2">Sisa</th>
            <th colspan="3">Catatan Lebur</th>
            <th colspan="4">Lab</th>
        </tr>
        <tr>
            <th>Tanggal</th>
            <th>Quantity</th>
            <th>Berat</th>
            <th>Tanggal</th>
            <th>Selisih Berat</th>
            <th>Denda</th>
            <th>Catatan</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($transactions as $item)
        <tr>
            <td>@isset($item['TanggalSPKO']){{$item['TanggalSPKO']}}@endisset</td>
            <td>@isset($item['NamaProdukSPKO']){{$item['NamaProdukSPKO']}}@endisset</td>
            <td>@isset($item['NomorSPKSPKO']){{$item['NomorSPKSPKO']}}@endisset</td>
            <td>@isset($item['CategoryProductFGSPKO']){{$item['CategoryProductFGSPKO']}}@endisset</td>
            <td>@isset($item['BarcodeNoteSPKO']){{$item['BarcodeNoteSPKO']}}@endisset</td>
            <td>@isset($item['QtySPKO']){{$item['QtySPKO']}}@endisset</td>
            <td>@isset($item['WeightSPKO']){{$item['WeightSPKO']}}@endisset</td>
            <td>@isset($item['TanggalNTHKO']){{$item['TanggalNTHKO']}}@endisset</td>
            <td>@isset($item['Tukang']){{$item['Tukang']}}@endisset</td>
            <td>@isset($item['NoModel']){{$item['NoModel']}}@endisset</td>
            <td>@isset($item['NamaProdukNTHKO']){{$item['NamaProdukNTHKO']}}@endisset</td>
            <td>@isset($item['NomorSPKNTHKO']){{$item['NomorSPKNTHKO']}}@endisset</td>
            <td>@isset($item['CategoryProductFGNTHKO']){{$item['CategoryProductFGNTHKO']}}@endisset</td>
            <td>@isset($item['QtyNTHKO']){{$item['QtyNTHKO']}}@endisset</td>
            <td>@isset($item['WeightNTHKO']){{$item['WeightNTHKO']}}@endisset</td>
            <td>@isset($item['ScrapQty']){{$item['ScrapQty']}}@endisset</td>
            <td>@isset($item['ScrapWeight']){{$item['ScrapWeight']}}@endisset</td>
            <td>@isset($item['Sisa']){{$item['Sisa']}}@endisset</td>
            <td></td>
            <td></td>
            <td></td>
            <td>@isset($item['TanggalLab']){{$item['TanggalLab']}}@endisset</td>
            <td>@isset($item['SelisihBerat']){{$item['SelisihBerat']}}@endisset</td>
            <td>@isset($item['Denda']){{$item['Denda']}}@endisset</td>
            <td>@isset($item['CatatanLab']){{$item['CatatanLab']}}@endisset</td>
        </tr>
        @endforeach
    </tbody>
</table>
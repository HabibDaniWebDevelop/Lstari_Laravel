<table class="table table-sm table-bordered" id="theTable" @isset($border) border="1" @endisset>
    <thead>
        <tr class="text-center">
            <td rowspan="2">No</td>
            <td colspan="7">Surat Perintah Kerja Tukang Luar</td>
            <td colspan="11">Nota Terima Hasil Kerja Tukang Luar</td>
            <td colspan="4">Lab</td>
        </tr>
        <tr>
            <td>Tanggal</td>
            <td>Nama Barang</td>
            <td>SPK</td>
            <td>Jenis Barang</td>
            <td>Berat Timbangan Air</td>
            <td>Qty</td>
            <td>Berat</td>
            <td>Tanggal</td>
            <td>Tukang</td>
            <td>No. Model</td>
            <td>Nama Barang</td>
            <td>SPK</td>
            <td>Jenis</td>
            <td>Qty</td>
            <td>Berat</td>
            <td>Jumlah SS</td>
            <td>Berat SS</td>
            <td>Sisa</td>
            <td>Tanggal</td>
            <td>Selisih Berat</td>
            <td>Denda</td>
            <td>Catatan</td>
        </tr>
    </thead>
    <tbody>
        @foreach ($transactions as $item)
        <tr>
            <td>{{$loop->iteration}}</td>
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
            <td>@isset($item['TanggalLab']){{$item['TanggalLab']}}@endisset</td>
            <td>@isset($item['SelisihBerat']){{$item['SelisihBerat']}}@endisset</td>
            <td>@isset($item['Denda']){{$item['Denda']}}@endisset</td>
            <td>@isset($item['CatatanLab']){{$item['CatatanLab']}}@endisset</td>
        </tr>
        @endforeach
    </tbody>
</table>
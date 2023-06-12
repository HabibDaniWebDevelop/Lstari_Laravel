
{{-- style="height:calc(100vh - 490px);" --}}
<br>
<h3>Stock Perhiasan Cor</h3>
<div class="table-responsive text-nowrap" >
<table class="table table-border table-hover table-sm" id="tabelstok" style="width:100%;">
    <thead class="table-secondary sticky-top zindex-2">
        <tr>
            {{-- C type --}}
            {{-- <th style="font-weight:bold; font-size:16px;">No</th> --}}
            <th style="font-weight:bold; font-size:16px; text-align:left;">Kadar</th>
            <th style="font-weight:bold; font-size:16px; text-align:right;">Abimanyu</th>
            <th style="font-weight:bold; font-size:16px; text-align:right;">Agung</th>
            <th style="font-weight:bold; font-size:16px; text-align:right;">Aldy</th>
            <th style="font-weight:bold; font-size:16px; text-align:right;">Dian A</th>
            <th style="font-weight:bold; font-size:16px; text-align:right;">Juniar</th>
            <th style="font-weight:bold; font-size:16px; text-align:right;">Konsinyasi</th>
            <th style="font-weight:bold; font-size:16px; text-align:right;">Online</th>
            <th style="font-weight:bold; font-size:16px; text-align:right;">Pameran</th>
            <th style="font-weight:bold; font-size:16px; text-align:right;">Pengiriman</th>
            <th style="font-weight:bold; font-size:16px; text-align:right;">Simon</th>
            <th style="font-weight:bold; font-size:16px; text-align:right;">Tri</th>
            {{-- S type --}} 
            <th style="font-weight:bold; font-size:16px; text-align:right;">Barang Contoh</th>
            <th style="font-weight:bold; font-size:16px; text-align:right;">Pembayaran Penjualan</th>
            <th style="font-weight:bold; font-size:16px; text-align:right;">Peminjaman</th>
            <th style="font-weight:bold; font-size:16px; text-align:right;">Reparasi Customer</th>
            <th style="font-weight:bold; font-size:16px; text-align:right;">Reparasi Customer Salesman</th>
            <th style="font-weight:bold; font-size:16px; text-align:right;">Retur Kembali</th>
            <th style="font-weight:bold; font-size:16px; text-align:right;">Retur Kembali Salesman</th>
            <th style="font-weight:bold; font-size:16px; text-align:right;">Retur Marketing</th>
            <th style="font-weight:bold; font-size:16px; text-align:right;">SA Digital</th>
            <th style="font-weight:bold; font-size:16px; text-align:right;">StockLama</th>
            <th style="font-weight:bold; font-size:16px; text-align:right;">Stockist</th>
            <th style="font-weight:bold; font-size:16px; text-align:right;">Web Portal</th>
            <th style="font-weight:bold; font-size:16px; text-align:center;">TOTAL</th>
        </tr>
    </thead>
    <tbody>

@forelse ($data as $dataOK)
            <tr>
                {{-- <td>{{ $loop->iteration }} </td> --}}
                <td style="background-color:#F9EDED;"><span class="badge bg-dark" style="font-size:14px;">{{ $dataOK->KADAR }}</span></td>
                <td style="text-align:right; color:black;">{{ number_format($dataOK->Abimanyu, 2) }}</td>
                <td style="text-align:right; color:black;">{{ number_format($dataOK->Agung, 2) }}</td>
                <td style="text-align:right; color:black;">{{ number_format($dataOK->Aldy, 2) }}</td>
                <td style="text-align:right; color:black;">{{ number_format($dataOK->DianA, 2) }}</td>
                <td style="text-align:right; color:black;">{{ number_format($dataOK->Juniar, 2) }}</td>
                <td style="text-align:right; color:black;">{{ number_format($dataOK->Konsinyasi, 2) }}</td>
                <td style="text-align:right; color:black;">{{ number_format($dataOK->Online, 2) }}</td>
                <td style="text-align:right; color:black;">{{ number_format($dataOK->Pameran, 2) }}</td>
                <td style="text-align:right; color:black;">{{ number_format($dataOK->Pengiriman, 2) }}</td>
                <td style="text-align:right; color:black;">{{ number_format($dataOK->Simon, 2) }}</td>
                <td style="text-align:right; color:black;">{{ number_format($dataOK->Tri, 2) }}</td>
                <td style="text-align:right; color:black;">{{ number_format($dataOK->BarangContoh, 2) }}</td>
                <td style="text-align:right; color:black;">{{ number_format($dataOK->PembayaranPenjualan, 2) }}</td>
                <td style="text-align:right; color:black;">{{ number_format($dataOK->Peminjaman, 2) }}</td>
                <td style="text-align:right; color:black;">{{ number_format($dataOK->ReparasiCustomer, 2) }}</td>
                <td style="text-align:right; color:black;">{{ number_format($dataOK->ReparasiCustomerSalesman, 2) }}</td>
                <td style="text-align:right; color:black;">{{ number_format($dataOK->ReturKembali, 2) }}</td>
                <td style="text-align:right; color:black;">{{ number_format($dataOK->ReturKembaliSalesman, 2) }}</td>
                <td style="text-align:right; color:black;">{{ number_format($dataOK->ReturMarketing, 2) }}</td>
                <td style="text-align:right; color:black;">{{ number_format($dataOK->SADigital, 2) }}</td>
                <td style="text-align:right; color:black;">{{ number_format($dataOK->StockLama, 2) }}</td>
                <td style="text-align:right; color:black;">{{ number_format($dataOK->Stockist, 2) }}</td>
                <td style="text-align:right; color:black;">{{ number_format($dataOK->WebPortal, 2) }}</td>
                <td style="text-align:right; font-weight:bold; color:black; background-color:#F9EDED;">{{ number_format($dataOK->totalsamping, 2) }}</td>
            </tr>
        @empty
            <div class="alert alert-danger">
                Data Belum Tersedia.
            </div>
        @endforelse
    </tbody>
    <tfoot>
        <th style="text-align:center; font-weight:bold; font-size:14px; background-color:#F9EDED;">TOTAL : </th>
        <th style="font-weight:bold; font-size:14px; text-align:right; color:black; background-color:#F9EDED;">{{ number_format($data2['TotalAbimanyu'], 2)}}</th>
        <th style="font-weight:bold; font-size:14px; text-align:right; color:black; background-color:#F9EDED;">{{ number_format($data2['TotalAgung'], 2)}}</th>
        <th style="font-weight:bold; font-size:14px; text-align:right; color:black; background-color:#F9EDED;">{{ number_format($data2['TotalAldy'], 2)}}</th>
        <th style="font-weight:bold; font-size:14px; text-align:right; color:black; background-color:#F9EDED;">{{ number_format($data2['TotalDianA'], 2)}}</th>
        <th style="font-weight:bold; font-size:14px; text-align:right; color:black; background-color:#F9EDED;">{{ number_format($data2['TotalJuniar'], 2)}}</th>
        <th style="font-weight:bold; font-size:14px; text-align:right; color:black; background-color:#F9EDED;">{{ number_format($data2['TotalKonsinyasi'], 2)}}</th>
        <th style="font-weight:bold; font-size:14px; text-align:right; color:black; background-color:#F9EDED;">{{ number_format($data2['TotalOnline'], 2)}}</th>
        <th style="font-weight:bold; font-size:14px; text-align:right; color:black; background-color:#F9EDED;">{{ number_format($data2['TotalPameran'], 2)}}</th>
        <th style="font-weight:bold; font-size:14px; text-align:right; color:black; background-color:#F9EDED;">{{ number_format($data2['TotalPengiriman'], 2)}}</th>
        <th style="font-weight:bold; font-size:14px; text-align:right; color:black; background-color:#F9EDED;">{{ number_format($data2['TotalSimon'], 2)}}</th>
        <th style="font-weight:bold; font-size:14px; text-align:right; color:black; background-color:#F9EDED;">{{ number_format($data2['TotalTri'], 2)}}</th>
        <th style="font-weight:bold; font-size:14px; text-align:right; color:black; background-color:#F9EDED;">{{ number_format($data2['TotalBarangContoh'], 2)}}</th>
        <th style="font-weight:bold; font-size:14px; text-align:right; color:black; background-color:#F9EDED;">{{ number_format($data2['TotalPembayaranPenjualan'], 2)}}</th>
        <th style="font-weight:bold; font-size:14px; text-align:right; color:black; background-color:#F9EDED;">{{ number_format($data2['TotalPeminjaman'], 2)}}</th>
        <th style="font-weight:bold; font-size:14px; text-align:right; color:black; background-color:#F9EDED;">{{ number_format($data2['TotalReparasiCustomer'], 2)}}</th>
        <th style="font-weight:bold; font-size:14px; text-align:right; color:black; background-color:#F9EDED;">{{ number_format($data2['TotalReparasiCustomerSalesman'], 2)}}</th>
        <th style="font-weight:bold; font-size:14px; text-align:right; color:black; background-color:#F9EDED;">{{ number_format($data2['TotalReturKembali'], 2)}}</th>
        <th style="font-weight:bold; font-size:14px; text-align:right; color:black; background-color:#F9EDED;">{{ number_format($data2['TotalReturKembaliSalesman'], 2)}}</th>
        <th style="font-weight:bold; font-size:14px; text-align:right; color:black; background-color:#F9EDED;">{{ number_format($data2['TotalReturMarketing'], 2)}}</th>
        <th style="font-weight:bold; font-size:14px; text-align:right; color:black; background-color:#F9EDED;">{{ number_format($data2['TotalSADigital'], 2)}}</th>
        <th style="font-weight:bold; font-size:14px; text-align:right; color:black; background-color:#F9EDED;">{{ number_format($data2['TotalStockLama'], 2)}}</th>
        <th style="font-weight:bold; font-size:14px; text-align:right; color:black; background-color:#F9EDED;">{{ number_format($data2['TotalStockist'], 2)}}</th>
        <th style="font-weight:bold; font-size:14px; text-align:right; color:black; background-color:#F9EDED;">{{ number_format($data2['TotalWebPortal'], 2)}}</th>
        <th style="font-weight:bold; font-size:14px; text-align:right; color:#913030; background-color:#E5AAA9;">{{ number_format($data2['TotalGrand'], 2)}}</th>
    </tfoot>
</table>
<br>
<table class="table table-border table-hover table-sm" id="tabelstokothercarat" style="width:100%;">
    <thead class="table-secondary sticky-top zindex-2">
        <tr>
            {{-- C type --}}
            {{-- <th style="font-weight:bold; font-size:16px;">No</th> --}}
            <th style="font-weight:bold; font-size:16px; text-align:left;">Kadar</th>
            <th style="font-weight:bold; font-size:16px; text-align:right;">Abimanyu</th>
            <th style="font-weight:bold; font-size:16px; text-align:right;">Agung</th>
            <th style="font-weight:bold; font-size:16px; text-align:right;">Aldy</th>
            <th style="font-weight:bold; font-size:16px; text-align:right;">Dian A</th>
            <th style="font-weight:bold; font-size:16px; text-align:right;">Juniar</th>
            <th style="font-weight:bold; font-size:16px; text-align:right;">Konsinyasi</th>
            <th style="font-weight:bold; font-size:16px; text-align:right;">Online</th>
            <th style="font-weight:bold; font-size:16px; text-align:right;">Pameran</th>
            <th style="font-weight:bold; font-size:16px; text-align:right;">Pengiriman</th>
            <th style="font-weight:bold; font-size:16px; text-align:right;">Simon</th>
            <th style="font-weight:bold; font-size:16px; text-align:right;">Tri</th>
            {{-- S type --}} 
            <th style="font-weight:bold; font-size:16px; text-align:right;">Barang Contoh</th>
            <th style="font-weight:bold; font-size:16px; text-align:right;">Pembayaran Penjualan</th>
            <th style="font-weight:bold; font-size:16px; text-align:right;">Peminjaman</th>
            <th style="font-weight:bold; font-size:16px; text-align:right;">Reparasi Customer</th>
            <th style="font-weight:bold; font-size:16px; text-align:right;">Reparasi Customer Salesman</th>
            <th style="font-weight:bold; font-size:16px; text-align:right;">Retur Kembali</th>
            <th style="font-weight:bold; font-size:16px; text-align:right;">Retur Kembali Salesman</th>
            <th style="font-weight:bold; font-size:16px; text-align:right;">Retur Marketing</th>
            <th style="font-weight:bold; font-size:16px; text-align:right;">SA Digital</th>
            <th style="font-weight:bold; font-size:16px; text-align:right;">StockLama</th>
            <th style="font-weight:bold; font-size:16px; text-align:right;">Stockist</th>
            <th style="font-weight:bold; font-size:16px; text-align:right;">Web Portal</th>
            <th style="font-weight:bold; font-size:16px; text-align:center;">TOTAL</th>
        </tr>
    </thead>
    <tbody>

@forelse ($dataother as $dataOKOther)
            <tr>
                {{-- <td>{{ $loop->iteration }} </td> --}}
                <td style="background-color:#F9EDED;"><span class="badge bg-dark" style="font-size:14px;">{{ $dataOKOther->KADAR }}</span></td>
                <td style="text-align:right; color:black;">{{ number_format($dataOKOther->Abimanyu, 2) }}</td>
                <td style="text-align:right; color:black;">{{ number_format($dataOKOther->Agung, 2) }}</td>
                <td style="text-align:right; color:black;">{{ number_format($dataOKOther->Aldy, 2) }}</td>
                <td style="text-align:right; color:black;">{{ number_format($dataOKOther->DianA, 2) }}</td>
                <td style="text-align:right; color:black;">{{ number_format($dataOKOther->Juniar, 2) }}</td>
                <td style="text-align:right; color:black;">{{ number_format($dataOKOther->Konsinyasi, 2) }}</td>
                <td style="text-align:right; color:black;">{{ number_format($dataOKOther->Online, 2) }}</td>
                <td style="text-align:right; color:black;">{{ number_format($dataOKOther->Pameran, 2) }}</td>
                <td style="text-align:right; color:black;">{{ number_format($dataOKOther->Pengiriman, 2) }}</td>
                <td style="text-align:right; color:black;">{{ number_format($dataOKOther->Simon, 2) }}</td>
                <td style="text-align:right; color:black;">{{ number_format($dataOKOther->Tri, 2) }}</td>
                <td style="text-align:right; color:black;">{{ number_format($dataOKOther->BarangContoh, 2) }}</td>
                <td style="text-align:right; color:black;">{{ number_format($dataOKOther->PembayaranPenjualan, 2) }}</td>
                <td style="text-align:right; color:black;">{{ number_format($dataOKOther->Peminjaman, 2) }}</td>
                <td style="text-align:right; color:black;">{{ number_format($dataOKOther->ReparasiCustomer, 2) }}</td>
                <td style="text-align:right; color:black;">{{ number_format($dataOKOther->ReparasiCustomerSalesman, 2) }}</td>
                <td style="text-align:right; color:black;">{{ number_format($dataOKOther->ReturKembali, 2) }}</td>
                <td style="text-align:right; color:black;">{{ number_format($dataOKOther->ReturKembaliSalesman, 2) }}</td>
                <td style="text-align:right; color:black;">{{ number_format($dataOKOther->ReturMarketing, 2) }}</td>
                <td style="text-align:right; color:black;">{{ number_format($dataOKOther->SADigital, 2) }}</td>
                <td style="text-align:right; color:black;">{{ number_format($dataOKOther->StockLama, 2) }}</td>
                <td style="text-align:right; color:black;">{{ number_format($dataOKOther->Stockist, 2) }}</td>
                <td style="text-align:right; color:black;">{{ number_format($dataOKOther->WebPortal, 2) }}</td>
                <td style="text-align:right; font-weight:bold; color:black; background-color:#F9EDED;">{{ number_format($dataOKOther->totalsamping, 2) }}</td>
            </tr>
        @empty
            
        @endforelse
    </tbody>
</table>
</div>
<br>
<h3>Stock Rongsok</h3>
<div class="table-responsive text-nowrap" >
<table class="table table-border table-hover table-sm" id="tabelstokrongsok" style="width:100%;">
    <thead class="table-secondary sticky-top zindex-2">
        <tr>
            {{-- C type --}}
            {{-- <th style="font-weight:bold; font-size:16px;">No</th> --}}
            <th style="font-weight:bold; font-size:16px; text-align:left;">Kadar</th>
            <th style="font-weight:bold; font-size:16px; text-align:right;">Abimanyu</th>
            <th style="font-weight:bold; font-size:16px; text-align:right;">Agung</th>
            <th style="font-weight:bold; font-size:16px; text-align:right;">Aldy</th>
            <th style="font-weight:bold; font-size:16px; text-align:right;">Dian A</th>
            <th style="font-weight:bold; font-size:16px; text-align:right;">Juniar</th>
            <th style="font-weight:bold; font-size:16px; text-align:right;">Konsinyasi</th>
            <th style="font-weight:bold; font-size:16px; text-align:right;">Online</th>
            <th style="font-weight:bold; font-size:16px; text-align:right;">Pameran</th>
            <th style="font-weight:bold; font-size:16px; text-align:right;">Pengiriman</th>
            <th style="font-weight:bold; font-size:16px; text-align:right;">Simon</th>
            <th style="font-weight:bold; font-size:16px; text-align:right;">Tri</th>
            {{-- S type --}} 
            <th style="font-weight:bold; font-size:16px; text-align:right;">Barang Contoh</th>
            <th style="font-weight:bold; font-size:16px; text-align:right;">Pembayaran Penjualan</th>
            <th style="font-weight:bold; font-size:16px; text-align:right;">Peminjaman</th>
            <th style="font-weight:bold; font-size:16px; text-align:right;">Reparasi Customer</th>
            <th style="font-weight:bold; font-size:16px; text-align:right;">Reparasi Customer Salesman</th>
            <th style="font-weight:bold; font-size:16px; text-align:right;">Retur Kembali</th>
            <th style="font-weight:bold; font-size:16px; text-align:right;">Retur Kembali Salesman</th>
            <th style="font-weight:bold; font-size:16px; text-align:right;">Retur Marketing</th>
            <th style="font-weight:bold; font-size:16px; text-align:right;">SA Digital</th>
            <th style="font-weight:bold; font-size:16px; text-align:right;">StockLama</th>
            <th style="font-weight:bold; font-size:16px; text-align:right;">Stockist</th>
            <th style="font-weight:bold; font-size:16px; text-align:right;">Web Portal</th>
            <th style="font-weight:bold; font-size:16px; text-align:center;">TOTAL</th>
        </tr>
    </thead>
    <tbody>

@forelse ($datarongsok as $dataOKrongsok)
            <tr>
                {{-- <td>{{ $loop->iteration }} </td> --}}
                <td style="background-color:#F9EDED;"><span class="badge bg-dark" style="font-size:14px;">{{ $dataOKrongsok->KADAR }}</span></td>
                <td style="text-align:right; color:black;">{{ number_format($dataOKrongsok->Abimanyu, 2) }}</td>
                <td style="text-align:right; color:black;">{{ number_format($dataOKrongsok->Agung, 2) }}</td>
                <td style="text-align:right; color:black;">{{ number_format($dataOKrongsok->Aldy, 2) }}</td>
                <td style="text-align:right; color:black;">{{ number_format($dataOKrongsok->DianA, 2) }}</td>
                <td style="text-align:right; color:black;">{{ number_format($dataOKrongsok->Juniar, 2) }}</td>
                <td style="text-align:right; color:black;">{{ number_format($dataOKrongsok->Konsinyasi, 2) }}</td>
                <td style="text-align:right; color:black;">{{ number_format($dataOKrongsok->Online, 2) }}</td>
                <td style="text-align:right; color:black;">{{ number_format($dataOKrongsok->Pameran, 2) }}</td>
                <td style="text-align:right; color:black;">{{ number_format($dataOKrongsok->Pengiriman, 2) }}</td>
                <td style="text-align:right; color:black;">{{ number_format($dataOKrongsok->Simon, 2) }}</td>
                <td style="text-align:right; color:black;">{{ number_format($dataOKrongsok->Tri, 2) }}</td>
                <td style="text-align:right; color:black;">{{ number_format($dataOKrongsok->BarangContoh, 2) }}</td>
                <td style="text-align:right; color:black;">{{ number_format($dataOKrongsok->PembayaranPenjualan, 2) }}</td>
                <td style="text-align:right; color:black;">{{ number_format($dataOKrongsok->Peminjaman, 2) }}</td>
                <td style="text-align:right; color:black;">{{ number_format($dataOKrongsok->ReparasiCustomer, 2) }}</td>
                <td style="text-align:right; color:black;">{{ number_format($dataOKrongsok->ReparasiCustomerSalesman, 2) }}</td>
                <td style="text-align:right; color:black;">{{ number_format($dataOKrongsok->ReturKembali, 2) }}</td>
                <td style="text-align:right; color:black;">{{ number_format($dataOKrongsok->ReturKembaliSalesman, 2) }}</td>
                <td style="text-align:right; color:black;">{{ number_format($dataOKrongsok->ReturMarketing, 2) }}</td>
                <td style="text-align:right; color:black;">{{ number_format($dataOKrongsok->SADigital, 2) }}</td>
                <td style="text-align:right; color:black;">{{ number_format($dataOKrongsok->StockLama, 2) }}</td>
                <td style="text-align:right; color:black;">{{ number_format($dataOKrongsok->Stockist, 2) }}</td>
                <td style="text-align:right; color:black;">{{ number_format($dataOKrongsok->WebPortal, 2) }}</td>
                <td style="text-align:right; font-weight:bold; color:black; background-color:#F9EDED;">{{ number_format($dataOKrongsok->totalsamping, 2) }}</td>
            </tr>
        @empty
            <div class="alert alert-danger">
                Data Belum Tersedia.
            </div>
        @endforelse
    </tbody>
    <tfoot>
        <th style="text-align:center; font-weight:bold; font-size:14px; background-color:#F9EDED;">TOTAL : </th>
        <th style="font-weight:bold; font-size:14px; text-align:right; color:black; background-color:#F9EDED;">{{ number_format($data2rongsok['TotalAbimanyu'], 2)}}</th>
        <th style="font-weight:bold; font-size:14px; text-align:right; color:black; background-color:#F9EDED;">{{ number_format($data2rongsok['TotalAgung'], 2)}}</th>
        <th style="font-weight:bold; font-size:14px; text-align:right; color:black; background-color:#F9EDED;">{{ number_format($data2rongsok['TotalAldy'], 2)}}</th>
        <th style="font-weight:bold; font-size:14px; text-align:right; color:black; background-color:#F9EDED;">{{ number_format($data2rongsok['TotalDianA'], 2)}}</th>
        <th style="font-weight:bold; font-size:14px; text-align:right; color:black; background-color:#F9EDED;">{{ number_format($data2rongsok['TotalJuniar'], 2)}}</th>
        <th style="font-weight:bold; font-size:14px; text-align:right; color:black; background-color:#F9EDED;">{{ number_format($data2rongsok['TotalKonsinyasi'], 2)}}</th>
        <th style="font-weight:bold; font-size:14px; text-align:right; color:black; background-color:#F9EDED;">{{ number_format($data2rongsok['TotalOnline'], 2)}}</th>
        <th style="font-weight:bold; font-size:14px; text-align:right; color:black; background-color:#F9EDED;">{{ number_format($data2rongsok['TotalPameran'], 2)}}</th>
        <th style="font-weight:bold; font-size:14px; text-align:right; color:black; background-color:#F9EDED;">{{ number_format($data2rongsok['TotalPengiriman'], 2)}}</th>
        <th style="font-weight:bold; font-size:14px; text-align:right; color:black; background-color:#F9EDED;">{{ number_format($data2rongsok['TotalSimon'], 2)}}</th>
        <th style="font-weight:bold; font-size:14px; text-align:right; color:black; background-color:#F9EDED;">{{ number_format($data2rongsok['TotalTri'], 2)}}</th>
        <th style="font-weight:bold; font-size:14px; text-align:right; color:black; background-color:#F9EDED;">{{ number_format($data2rongsok['TotalBarangContoh'], 2)}}</th>
        <th style="font-weight:bold; font-size:14px; text-align:right; color:black; background-color:#F9EDED;">{{ number_format($data2rongsok['TotalPembayaranPenjualan'], 2)}}</th>
        <th style="font-weight:bold; font-size:14px; text-align:right; color:black; background-color:#F9EDED;">{{ number_format($data2rongsok['TotalPeminjaman'], 2)}}</th>
        <th style="font-weight:bold; font-size:14px; text-align:right; color:black; background-color:#F9EDED;">{{ number_format($data2rongsok['TotalReparasiCustomer'], 2)}}</th>
        <th style="font-weight:bold; font-size:14px; text-align:right; color:black; background-color:#F9EDED;">{{ number_format($data2rongsok['TotalReparasiCustomerSalesman'], 2)}}</th>
        <th style="font-weight:bold; font-size:14px; text-align:right; color:black; background-color:#F9EDED;">{{ number_format($data2rongsok['TotalReturKembali'], 2)}}</th>
        <th style="font-weight:bold; font-size:14px; text-align:right; color:black; background-color:#F9EDED;">{{ number_format($data2rongsok['TotalReturKembaliSalesman'], 2)}}</th>
        <th style="font-weight:bold; font-size:14px; text-align:right; color:black; background-color:#F9EDED;">{{ number_format($data2rongsok['TotalReturMarketing'], 2)}}</th>
        <th style="font-weight:bold; font-size:14px; text-align:right; color:black; background-color:#F9EDED;">{{ number_format($data2rongsok['TotalSADigital'], 2)}}</th>
        <th style="font-weight:bold; font-size:14px; text-align:right; color:black; background-color:#F9EDED;">{{ number_format($data2rongsok['TotalStockLama'], 2)}}</th>
        <th style="font-weight:bold; font-size:14px; text-align:right; color:black; background-color:#F9EDED;">{{ number_format($data2rongsok['TotalStockist'], 2)}}</th>
        <th style="font-weight:bold; font-size:14px; text-align:right; color:black; background-color:#F9EDED;">{{ number_format($data2rongsok['TotalWebPortal'], 2)}}</th>
        <th style="font-weight:bold; font-size:14px; text-align:right; color:#913030; background-color:#E5AAA9;">{{ number_format($data2rongsok['TotalGrand'], 2)}}</th>
    </tfoot>
</table>
<br>
<table class="table table-border table-hover table-sm" id="tabelstokothercaratrongsok" style="width:100%;">
    <thead class="table-secondary sticky-top zindex-2">
        <tr>
            {{-- C type --}}
            {{-- <th style="font-weight:bold; font-size:16px;">No</th> --}}
            <th style="font-weight:bold; font-size:16px; text-align:left;">Kadar</th>
            <th style="font-weight:bold; font-size:16px; text-align:right;">Abimanyu</th>
            <th style="font-weight:bold; font-size:16px; text-align:right;">Agung</th>
            <th style="font-weight:bold; font-size:16px; text-align:right;">Aldy</th>
            <th style="font-weight:bold; font-size:16px; text-align:right;">Dian A</th>
            <th style="font-weight:bold; font-size:16px; text-align:right;">Juniar</th>
            <th style="font-weight:bold; font-size:16px; text-align:right;">Konsinyasi</th>
            <th style="font-weight:bold; font-size:16px; text-align:right;">Online</th>
            <th style="font-weight:bold; font-size:16px; text-align:right;">Pameran</th>
            <th style="font-weight:bold; font-size:16px; text-align:right;">Pengiriman</th>
            <th style="font-weight:bold; font-size:16px; text-align:right;">Simon</th>
            <th style="font-weight:bold; font-size:16px; text-align:right;">Tri</th>
            {{-- S type --}} 
            <th style="font-weight:bold; font-size:16px; text-align:right;">Barang Contoh</th>
            <th style="font-weight:bold; font-size:16px; text-align:right;">Pembayaran Penjualan</th>
            <th style="font-weight:bold; font-size:16px; text-align:right;">Peminjaman</th>
            <th style="font-weight:bold; font-size:16px; text-align:right;">Reparasi Customer</th>
            <th style="font-weight:bold; font-size:16px; text-align:right;">Reparasi Customer Salesman</th>
            <th style="font-weight:bold; font-size:16px; text-align:right;">Retur Kembali</th>
            <th style="font-weight:bold; font-size:16px; text-align:right;">Retur Kembali Salesman</th>
            <th style="font-weight:bold; font-size:16px; text-align:right;">Retur Marketing</th>
            <th style="font-weight:bold; font-size:16px; text-align:right;">SA Digital</th>
            <th style="font-weight:bold; font-size:16px; text-align:right;">StockLama</th>
            <th style="font-weight:bold; font-size:16px; text-align:right;">Stockist</th>
            <th style="font-weight:bold; font-size:16px; text-align:right;">Web Portal</th>
            <th style="font-weight:bold; font-size:16px; text-align:center;">TOTAL</th>
        </tr>
    </thead>
    <tbody>

@forelse ($dataotherrongsok as $dataOKOtherrongsok)
            <tr>
                {{-- <td>{{ $loop->iteration }} </td> --}}
                <td style="background-color:#F9EDED;"><span class="badge bg-dark" style="font-size:14px;">{{ $dataOKOtherrongsok->KADAR }}</span></td>
                <td style="text-align:right; color:black;">{{ number_format($dataOKOtherrongsok->Abimanyu, 2) }}</td>
                <td style="text-align:right; color:black;">{{ number_format($dataOKOtherrongsok->Agung, 2) }}</td>
                <td style="text-align:right; color:black;">{{ number_format($dataOKOtherrongsok->Aldy, 2) }}</td>
                <td style="text-align:right; color:black;">{{ number_format($dataOKOtherrongsok->DianA, 2) }}</td>
                <td style="text-align:right; color:black;">{{ number_format($dataOKOtherrongsok->Juniar, 2) }}</td>
                <td style="text-align:right; color:black;">{{ number_format($dataOKOtherrongsok->Konsinyasi, 2) }}</td>
                <td style="text-align:right; color:black;">{{ number_format($dataOKOtherrongsok->Online, 2) }}</td>
                <td style="text-align:right; color:black;">{{ number_format($dataOKOtherrongsok->Pameran, 2) }}</td>
                <td style="text-align:right; color:black;">{{ number_format($dataOKOtherrongsok->Pengiriman, 2) }}</td>
                <td style="text-align:right; color:black;">{{ number_format($dataOKOtherrongsok->Simon, 2) }}</td>
                <td style="text-align:right; color:black;">{{ number_format($dataOKOtherrongsok->Tri, 2) }}</td>
                <td style="text-align:right; color:black;">{{ number_format($dataOKOtherrongsok->BarangContoh, 2) }}</td>
                <td style="text-align:right; color:black;">{{ number_format($dataOKOtherrongsok->PembayaranPenjualan, 2) }}</td>
                <td style="text-align:right; color:black;">{{ number_format($dataOKOtherrongsok->Peminjaman, 2) }}</td>
                <td style="text-align:right; color:black;">{{ number_format($dataOKOtherrongsok->ReparasiCustomer, 2) }}</td>
                <td style="text-align:right; color:black;">{{ number_format($dataOKOtherrongsok->ReparasiCustomerSalesman, 2) }}</td>
                <td style="text-align:right; color:black;">{{ number_format($dataOKOtherrongsok->ReturKembali, 2) }}</td>
                <td style="text-align:right; color:black;">{{ number_format($dataOKOtherrongsok->ReturKembaliSalesman, 2) }}</td>
                <td style="text-align:right; color:black;">{{ number_format($dataOKOtherrongsok->ReturMarketing, 2) }}</td>
                <td style="text-align:right; color:black;">{{ number_format($dataOKOtherrongsok->SADigital, 2) }}</td>
                <td style="text-align:right; color:black;">{{ number_format($dataOKOtherrongsok->StockLama, 2) }}</td>
                <td style="text-align:right; color:black;">{{ number_format($dataOKOtherrongsok->Stockist, 2) }}</td>
                <td style="text-align:right; color:black;">{{ number_format($dataOKOtherrongsok->WebPortal, 2) }}</td>
                <td style="text-align:right; font-weight:bold; color:black; background-color:#F9EDED;">{{ number_format($dataOKOtherrongsok->totalsamping, 2) }}</td>
            </tr>
        @empty
        @endforelse
    </tbody>
</table>
</div>

<style type="text/css">
    

    .dataTables_scrollBody
        {
        overflow-x:hidden !important;
        overflow-y:auto !important;
        }
    

        .DTFC_LeftBodyLiner {
            max-height: unset !important;
            /* top: 0 !important; */
            
        }

        .DTFC_RightBodyLiner {
            max-height: unset!important;
            /* top: 0 !important; */
            /* width: 116.729px !important;
            height: 397.604px !important; */
        }

        .DTFC_LeftFootWrapper {
            top: 0px !important;
        }
   

        .DTFC_RightFootWrapper {
            top: 0 !important;
            /* overflow:hidden !important; */
        }

        /* .DTFC_RightBodyWrapper{
            height:433.75px !important;
        } */

        .table.dataTable{
            margin:0 !important;
        }

        
        .btn-outline-dark{
            display:inline-block;
            float:right;
            margin-top:28px;
        }

        .btn-primary{
            display:inline-block;
            margin-top:28px;
        }

        .btn-outline-dark{
            /* position: absolute;  */
            margin-top:0px;
            margin-bottom:15px;
          
            /* float: right; */

            /* margin-top:-20px;
            margin-bottom:20px; */
            /* overflow: hidden; */
            }
        /* #tampil{
            margin-top:5px;
        } */
</style>
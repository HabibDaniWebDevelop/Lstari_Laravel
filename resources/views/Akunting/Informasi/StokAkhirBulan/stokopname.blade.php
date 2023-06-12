
{{-- style="height:calc(100vh - 490px);" --}}
<br>
<h3>Stock Opname</h3>
<div class="table-responsive text-nowrap" >
<table class="table table-border table-hover table-sm" id="tabelstokopname" style="width:100%;">
    <thead class="table-secondary sticky-top zindex-2">
        <tr>
            {{-- C type --}}
            {{-- <th style="font-weight:bold; font-size:16px;">No</th> --}}
            <th style="font-weight:bold; font-size:16px; text-align:left;">Kadar</th>
            <th style="font-weight:bold; font-size:16px; text-align:left;">Cor</th>
            <th style="font-weight:bold; font-size:16px; text-align:left;">Kikir</th>
            <th style="font-weight:bold; font-size:16px; text-align:left;">Lilin</th>
            <th style="font-weight:bold; font-size:16px; text-align:left;">Sepuh</th>
            <th style="font-weight:bold; font-size:16px; text-align:left;">Giling Tarik</th>
            <th style="font-weight:bold; font-size:16px; text-align:left;">Campur Bahan</th>
            <th style="font-weight:bold; font-size:16px; text-align:left;">Pasang Batu</th>
            <th style="font-weight:bold; font-size:16px; text-align:left;">Bombing</th>
            <th style="font-weight:bold; font-size:16px; text-align:left;">Tukang Luar</th>
            <th style="font-weight:bold; font-size:16px; text-align:left;">Poles</th>
            <th style="font-weight:bold; font-size:16px; text-align:left;">Mal Perak</th>
            <th style="font-weight:bold; font-size:16px; text-align:left;">Slep</th>
            <th style="font-weight:bold; font-size:16px; text-align:left;">Enamel</th>
            <th style="font-weight:bold; font-size:16px; text-align:left;">QC</th>
            <th style="font-weight:bold; font-size:16px; text-align:left;">Brush</th>
            <th style="font-weight:bold; font-size:16px; text-align:left;">Reparasi</th>
            <th style="font-weight:bold; font-size:16px; text-align:left;">PCB</th>
            <th style="font-weight:bold; font-size:16px; text-align:left;">Batu</th>
            <th style="font-weight:bold; font-size:16px; text-align:left;">Rantai</th>
            <th style="font-weight:bold; font-size:16px; text-align:left;">Barang Contoh</th>
            <th style="font-weight:bold; font-size:16px; text-align:left;">Peminjaman</th>
            <th style="font-weight:bold; font-size:16px; text-align:left;">Reparasi Customer</th>
            <th style="font-weight:bold; font-size:16px; text-align:left;">Reparasi Customer Salesman</th>
            <th style="font-weight:bold; font-size:16px; text-align:left;">Retur Kembali</th>
            <th style="font-weight:bold; font-size:16px; text-align:left;">Retur Kembali Salesman</th>
            <th style="font-weight:bold; font-size:16px; text-align:left;">Retur Marketing</th>
            <th style="font-weight:bold; font-size:16px; text-align:left;">SA Digital</th>
            <th style="font-weight:bold; font-size:16px; text-align:left;">Stock Lama</th>
            <th style="font-weight:bold; font-size:16px; text-align:left;">Stockist</th>
            <th style="font-weight:bold; font-size:16px; text-align:left;">Web Portal</th>
            <th style="font-weight:bold; font-size:16px; text-align:center;">TOTAL</th>
        </tr>
    </thead>
    <tbody>

@forelse ($data as $dataOK)
            <tr>
                {{-- <td>{{ $loop->iteration }} </td> --}}
                <td style="background-color:#F9EDED;"><span class="badge bg-dark" style="font-size:14px;">{{ $dataOK->KADAR }}</span></td>
                <td style="text-align:right; color:black;">{{ number_format($dataOK->Cor, 2) }}</td>
                <td style="text-align:right; color:black;">{{ number_format($dataOK->Kikir, 2) }}</td>
                <td style="text-align:right; color:black;">{{ number_format($dataOK->Lilin, 2) }}</td>
                <td style="text-align:right; color:black;">{{ number_format($dataOK->Sepuh, 2) }}</td>
                <td style="text-align:right; color:black;">{{ number_format($dataOK->GilingTarik, 2) }}</td>
                <td style="text-align:right; color:black;">{{ number_format($dataOK->CampurBahan, 2) }}</td>
                <td style="text-align:right; color:black;">{{ number_format($dataOK->PasangBatu, 2) }}</td>
                <td style="text-align:right; color:black;">{{ number_format($dataOK->Bombing, 2) }}</td>
                <td style="text-align:right; color:black;">{{ number_format($dataOK->TukangLuar, 2) }}</td>
                <td style="text-align:right; color:black;">{{ number_format($dataOK->Poles, 2) }}</td>
                <td style="text-align:right; color:black;">{{ number_format($dataOK->MalPerak, 2) }}</td>
                <td style="text-align:right; color:black;">{{ number_format($dataOK->Slep, 2) }}</td>
                <td style="text-align:right; color:black;">{{ number_format($dataOK->Enamel, 2) }}</td>
                <td style="text-align:right; color:black;">{{ number_format($dataOK->QC, 2) }}</td>
                <td style="text-align:right; color:black;">{{ number_format($dataOK->Brush, 2) }}</td>
                <td style="text-align:right; color:black;">{{ number_format($dataOK->Reparasi, 2) }}</td>
                <td style="text-align:right; color:black;">{{ number_format($dataOK->PCB, 2) }}</td>
                <td style="text-align:right; color:black;">{{ number_format($dataOK->Batu, 2) }}</td>
                <td style="text-align:right; color:black;">{{ number_format($dataOK->Rantai, 2) }}</td>
                <td style="text-align:right; color:black;">{{ number_format($dataOK->BarangContoh, 2) }}</td>
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
        <th style="font-weight:bold; font-size:14px; text-align:right; color:black; background-color:#F9EDED;">{{ number_format($data2['Cor'], 2)}}</th>
        <th style="font-weight:bold; font-size:14px; text-align:right; color:black; background-color:#F9EDED;">{{ number_format($data2['Kikir'], 2)}}</th>
        <th style="font-weight:bold; font-size:14px; text-align:right; color:black; background-color:#F9EDED;">{{ number_format($data2['Lilin'], 2)}}</th>
        <th style="font-weight:bold; font-size:14px; text-align:right; color:black; background-color:#F9EDED;">{{ number_format($data2['Sepuh'], 2)}}</th>
        <th style="font-weight:bold; font-size:14px; text-align:right; color:black; background-color:#F9EDED;">{{ number_format($data2['GilingTarik'], 2)}}</th>
        <th style="font-weight:bold; font-size:14px; text-align:right; color:black; background-color:#F9EDED;">{{ number_format($data2['CampurBahan'], 2)}}</th>
        <th style="font-weight:bold; font-size:14px; text-align:right; color:black; background-color:#F9EDED;">{{ number_format($data2['PasangBatu'], 2)}}</th>
        <th style="font-weight:bold; font-size:14px; text-align:right; color:black; background-color:#F9EDED;">{{ number_format($data2['Bombing'], 2)}}</th>
        <th style="font-weight:bold; font-size:14px; text-align:right; color:black; background-color:#F9EDED;">{{ number_format($data2['TukangLuar'], 2)}}</th>
        <th style="font-weight:bold; font-size:14px; text-align:right; color:black; background-color:#F9EDED;">{{ number_format($data2['Poles'], 2)}}</th>
        <th style="font-weight:bold; font-size:14px; text-align:right; color:black; background-color:#F9EDED;">{{ number_format($data2['MalPerak'], 2)}}</th>
        <th style="font-weight:bold; font-size:14px; text-align:right; color:black; background-color:#F9EDED;">{{ number_format($data2['Slep'], 2)}}</th>
        <th style="font-weight:bold; font-size:14px; text-align:right; color:black; background-color:#F9EDED;">{{ number_format($data2['Enamel'], 2)}}</th>
        <th style="font-weight:bold; font-size:14px; text-align:right; color:black; background-color:#F9EDED;">{{ number_format($data2['QC'], 2)}}</th>
        <th style="font-weight:bold; font-size:14px; text-align:right; color:black; background-color:#F9EDED;">{{ number_format($data2['Brush'], 2)}}</th>
        <th style="font-weight:bold; font-size:14px; text-align:right; color:black; background-color:#F9EDED;">{{ number_format($data2['Reparasi'], 2)}}</th>
        <th style="font-weight:bold; font-size:14px; text-align:right; color:black; background-color:#F9EDED;">{{ number_format($data2['PCB'], 2)}}</th>
        <th style="font-weight:bold; font-size:14px; text-align:right; color:black; background-color:#F9EDED;">{{ number_format($data2['Batu'], 2)}}</th>
        <th style="font-weight:bold; font-size:14px; text-align:right; color:black; background-color:#F9EDED;">{{ number_format($data2['Rantai'], 2)}}</th>
        <th style="font-weight:bold; font-size:14px; text-align:right; color:black; background-color:#F9EDED;">{{ number_format($data2['BarangContoh'], 2)}}</th>
        <th style="font-weight:bold; font-size:14px; text-align:right; color:black; background-color:#F9EDED;">{{ number_format($data2['Peminjaman'], 2)}}</th>
        <th style="font-weight:bold; font-size:14px; text-align:right; color:black; background-color:#F9EDED;">{{ number_format($data2['ReparasiCustomer'], 2)}}</th>
        <th style="font-weight:bold; font-size:14px; text-align:right; color:black; background-color:#F9EDED;">{{ number_format($data2['ReparasiCustomerSalesman'], 2)}}</th>
        <th style="font-weight:bold; font-size:14px; text-align:right; color:black; background-color:#F9EDED;">{{ number_format($data2['ReturKembali'], 2)}}</th>
        <th style="font-weight:bold; font-size:14px; text-align:right; color:black; background-color:#F9EDED;">{{ number_format($data2['ReturKembaliSalesman'], 2)}}</th>
        <th style="font-weight:bold; font-size:14px; text-align:right; color:black; background-color:#F9EDED;">{{ number_format($data2['ReturMarketing'], 2)}}</th>
        <th style="font-weight:bold; font-size:14px; text-align:right; color:black; background-color:#F9EDED;">{{ number_format($data2['SADigital'], 2)}}</th>
        <th style="font-weight:bold; font-size:14px; text-align:right; color:black; background-color:#F9EDED;">{{ number_format($data2['StockLama'], 2)}}</th>
        <th style="font-weight:bold; font-size:14px; text-align:right; color:black; background-color:#F9EDED;">{{ number_format($data2['Stockist'], 2)}}</th>
        <th style="font-weight:bold; font-size:14px; text-align:right; color:black; background-color:#F9EDED;">{{ number_format($data2['WebPortal'], 2)}}</th>
        <th style="font-weight:bold; font-size:14px; text-align:right; color:#913030; background-color:#E5AAA9;">{{ number_format($data2['TotalGrand'], 2)}}</th>
    </tfoot>
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
     
</style>
{{-- style="height:calc(100vh - 490px);" --}}
{{-- <br> --}}
{{-- <h3>Stock Opname</h3> --}}
<div class="table-responsive text-nowrap" style="height:calc(100vh - 310px); margin-top:10px;">
<table class="table table-border table-hover table-sm" id="tabelformorder" style="width:100%;" border="0">
    <thead class="table-secondary sticky-top zindex-2">
        <tr>
            {{-- C type --}}
            {{-- <th style="font-weight:bold; font-size:16px;">No</th> --}}
            <th style="font-weight:bold; font-size:16px; text-align:center;">Keperluan</th>
            <th style="font-weight:bold; font-size:16px; text-align:center;">Tanggal</th>
            <th style="font-weight:bold; font-size:16px; text-align:center;">Dibutuhkan</th>
            <th style="font-weight:bold; font-size:16px; text-align:center;">Urut</th>
            <th style="font-weight:bold; font-size:16px; text-align:center;">Customer</th>
            <th style="font-weight:bold; font-size:16px; text-align:center;">Sub Kategori</th>
            <th style="font-weight:bold; font-size:16px; text-align:center;">Model</th>
            <th style="font-weight:bold; font-size:16px; text-align:center;">Kode</th>
            <th style="font-weight:bold; font-size:16px; text-align:center;">Kadar</th>
            <th style="font-weight:bold; font-size:16px; text-align:center;">Jumlah</th>
            <th style="font-weight:bold; font-size:16px; text-align:center;">Qty Enamel</th>
            <th style="font-weight:bold; font-size:16px; text-align:right;">Berat</th>
            <th style="font-weight:bold; font-size:16px; text-align:right;">Brt Pcs</th>
            <th style="font-weight:bold; font-size:16px; text-align:center;">Batu</th>
            <th style="font-weight:bold; font-size:16px; text-align:center;">Inject</th>
            <th style="font-weight:bold; font-size:16px; text-align:center;">Poles</th>
            <th style="font-weight:bold; font-size:16px; text-align:center;">Patri</th>
            <th style="font-weight:bold; font-size:16px; text-align:center;">PUK</th>
            <th style="font-weight:bold; font-size:16px; text-align:center;">Enamel</th>
            <th style="font-weight:bold; font-size:16px; text-align:center;">Slep</th>
            <th style="font-weight:bold; font-size:16px; text-align:center;">Marking</th>
            <th style="font-weight:bold; font-size:16px; text-align:center;">Var P</th>
            <th style="font-weight:bold; font-size:16px; text-align:center;">Keterangan</th>
            <th style="font-weight:bold; font-size:16px; text-align:center;">Keterangan Lilin</th>
            <th style="font-weight:bold; font-size:16px; text-align:center;">Keterangan Batu</th>
            <th style="font-weight:bold; font-size:16px; text-align:center;">Keterangan Variasi</th>
            <th style="font-weight:bold; font-size:16px; text-align:center;">Keterangan Finishing</th>
            <th style="font-weight:bold; font-size:16px; text-align:center;">Keterangan GT</th>
            <th style="font-weight:bold; font-size:16px; text-align:center;">Keterangan Kikir</th>
            <th style="font-weight:bold; font-size:16px; text-align:center;">Urgent</th>
            <th style="font-weight:bold; font-size:16px; text-align:center;">No SPK</th>
            <th style="font-weight:bold; font-size:16px; text-align:center;">Tgl SPK</th>
            <th style="font-weight:bold; font-size:16px; text-align:center;">Kategori</th>
            <th style="font-weight:bold; font-size:16px; text-align:center;">Bulan</th>
            <th style="font-weight:bold; font-size:16px; text-align:center;">ID</th>
        </tr>
        <tr>
            <th style="font-weight:bold; font-size:14px; text-align:right; color:white; background-color:#9E2A24;"></th>
            <th style="font-weight:bold; font-size:14px; text-align:right; color:white; background-color:#9E2A24;"></th>
            <th style="font-weight:bold; font-size:14px; text-align:right; color:white; background-color:#9E2A24;"></th>
            <th style="font-weight:bold; font-size:14px; text-align:right; color:white; background-color:#9E2A24;"></th>
            <th style="font-weight:bold; font-size:14px; text-align:right; color:white; background-color:#9E2A24;"></th>
            <th style="font-weight:bold; font-size:14px; text-align:right; color:white; background-color:#9E2A24;"></th>
            <th style="font-weight:bold; font-size:14px; text-align:right; color:white; background-color:#9E2A24;"></th>
            <th style="font-weight:bold; font-size:14px; text-align:right; color:white; background-color:#9E2A24;"></th>
            <th style="font-weight:bold; font-size:14px; text-align:right; color:white; background-color:#9E2A24;"></th>
            <th style="font-weight:bold; font-size:14px; text-align:center; color:white; background-color:#9E2A24;">{{ $data2['Jumlah']}}</th>
            <th style="font-weight:bold; font-size:14px; text-align:center; color:white; background-color:#9E2A24;">{{ $data2['JumlahEnm']}}</th>
            <th style="font-weight:bold; font-size:14px; text-align:right; color:white; background-color:#9E2A24;">{{ number_format($data2['Berat'], 2)}}</th>
            <th style="font-weight:bold; font-size:14px; text-align:right; color:white; background-color:#9E2A24;">{{ number_format($data2['BrtPcs'], 2)}}</th>
            <th style="font-weight:bold; font-size:14px; text-align:center; color:white; background-color:#9E2A24;">{{ $data2['Batu']}}</th>
            <th style="font-weight:bold; font-size:14px; text-align:center; color:white; background-color:#9E2A24;">{{ $data2['Inject']}}</th>
            <th style="font-weight:bold; font-size:14px; text-align:center; color:white; background-color:#9E2A24;">{{ $data2['Poles']}}</th>
            <th style="font-weight:bold; font-size:14px; text-align:center; color:white; background-color:#9E2A24;">{{ $data2['Patri']}}</th>
            <th style="font-weight:bold; font-size:14px; text-align:center; color:white; background-color:#9E2A24;">{{ $data2['PUK']}}</th>
            <th style="font-weight:bold; font-size:14px; text-align:right; color:white; background-color:#9E2A24;"></th>
            <th style="font-weight:bold; font-size:14px; text-align:right; color:white; background-color:#9E2A24;"></th>
            <th style="font-weight:bold; font-size:14px; text-align:right; color:white; background-color:#9E2A24;"></th>
            <th style="font-weight:bold; font-size:14px; text-align:right; color:white; background-color:#9E2A24;"></th>
            <th style="font-weight:bold; font-size:14px; text-align:right; color:white; background-color:#9E2A24;"></th>
            <th style="font-weight:bold; font-size:14px; text-align:right; color:white; background-color:#9E2A24;"></th>
            <th style="font-weight:bold; font-size:14px; text-align:right; color:white; background-color:#9E2A24;"></th>
            <th style="font-weight:bold; font-size:14px; text-align:right; color:white; background-color:#9E2A24;"></th>
            <th style="font-weight:bold; font-size:14px; text-align:right; color:white; background-color:#9E2A24;"></th>
            <th style="font-weight:bold; font-size:14px; text-align:right; color:white; background-color:#9E2A24;"></th>
            <th style="font-weight:bold; font-size:14px; text-align:right; color:white; background-color:#9E2A24;"></th>
            <th style="font-weight:bold; font-size:14px; text-align:right; color:white; background-color:#9E2A24;"></th>
            <th style="font-weight:bold; font-size:14px; text-align:right; color:white; background-color:#9E2A24;"></th>
            <th style="font-weight:bold; font-size:14px; text-align:right; color:white; background-color:#9E2A24;"></th>
            <th style="font-weight:bold; font-size:14px; text-align:right; color:white; background-color:#9E2A24;"></th>
            <th style="font-weight:bold; font-size:14px; text-align:right; color:white; background-color:#9E2A24;"></th>
            <th style="font-weight:bold; font-size:14px; text-align:right; color:white; background-color:#9E2A24;"></th>
        </tr>
    </thead>
    <tbody>

@forelse ($data as $dataOK)
            <tr>
                {{-- <td>{{ $loop->iteration }} </td> --}}
                <td style="text-align:right; color:black;">{{ $dataOK->Purpose }}</td>
                <td style="text-align:center; color:black;">{{ $dataOK->TransDate }}</td>
                <td style="text-align:center; color:black;">{{ $dataOK->RequireDate }}</td>
                <td style="text-align:center; color:black;">{{ $dataOK->Ordinal }}</td>
                <td style="text-align:center; color:black;">{{ $dataOK->Customer}}</td>
                <td style="text-align:right; color:black;">{{ $dataOK->MSW}}</td>
                <td style="text-align:right; color:black;">{{ $dataOK->Model }}</td>
                <td style="text-align:right; color:black;">{{ $dataOK->PSW }}</td>
                <td style="text-align:right; color:black;">{{ $dataOK->Carat }}</td>
                <td style="text-align:center; color:black;">{{ $dataOK->Qty }}</td>
                <td style="text-align:center; color:black;">{{ $dataOK->QtyEnm }}</td>
                <td style="text-align:right; color:black;">{{ number_format($dataOK->Weight, 2) }}</td>
                <td style="text-align:right; color:black;">{{ number_format($dataOK->WeightProduct, 2) }}</td>
                <td style="text-align:center; color:black;">{{ $dataOK->TotalStone }}</td>
                <td style="text-align:center; color:black;">{{ $dataOK->TotalInject }}</td>
                <td style="text-align:center; color:black;">{{ $dataOK->TotalPoles }}</td>
                <td style="text-align:center; color:black;">{{ $dataOK->TotalPatri }}</td>
                <td style="text-align:center; color:black;">{{ $dataOK->TotalPUK }}</td>
                <td style="text-align:center; color:black;">{{ $dataOK->Enamel }}</td>
                <td style="text-align:center; color:black;">{{ $dataOK->Slep }}</td>
                <td style="text-align:center; color:black;">{{ $dataOK->Marking }}</td>
                <td style="text-align:center; color:black;">{{ $dataOK->VarP }}</td>
                <td style="text-align:center; color:black;">{{ $dataOK->Note }}</td>
                <td style="text-align:center; color:black;">{{ $dataOK->WaxNote }}</td>
                <td style="text-align:center; color:black;">{{ $dataOK->StoneNote }}</td>
                <td style="text-align:center; color:black;">{{ $dataOK->SpecialNote }}</td>
                <td style="text-align:center; color:black;">{{ $dataOK->FinishingNote }}</td>
                <td style="text-align:center; color:black;">{{ $dataOK->GTNote }}</td>
                <td style="text-align:center; color:black;">{{ $dataOK->KikirNote }}</td>
                <td style="text-align:center; color:black;">{{ $dataOK->Urgent }}</td>
                <td style="text-align:center; color:black;">{{ $dataOK->WorkOrder }}</td>
                <td style="text-align:center; color:black;">{{ $dataOK->OrderDate }}</td>
                <td style="text-align:center; color:black;">{{ $dataOK->Category }}</td>
                <td style="text-align:center; color:black;">{{ $dataOK->Month }}</td>
                <td style="text-align:center;"><span class="badge bg-dark" style="font-size:14px;">{{ $dataOK->ID }}</span></td>
            </tr>
            @empty
            <div class="alert alert-danger">
                Data Belum Tersedia.
            </div>
        @endforelse
    </tbody>
   
</table>
</div>



{{-- style="height:calc(100vh - 490px);" --}}
{{-- <br> --}}
{{-- <h3>Stock Opname</h3> --}}
<div class="table-responsive text-nowrap" style="height:calc(100vh - 310px); margin-top:10px;">
<table class="table table-border table-hover table-sm" id="tabelformorder" style="width:100%;" border="0">
    <thead class="table-secondary sticky-top zindex-2">
        <tr>
            {{-- C type --}}
            {{-- <th style="font-weight:bold; font-size:16px;">No</th> --}}
            <th style="font-weight:bold; font-size:16px; text-align:center;" rowspan="2"></th>
            <th style="font-weight:bold; font-size:16px; text-align:center; vertical-align:middle;" rowspan="2">Sub Kategori</th>
            <th style="font-weight:bold; font-size:16px; text-align:center; vertical-align:middle;" rowspan="2">Kadar</th>
            <th style="font-weight:bold; font-size:16px; text-align:center;" colspan="3">Berat</th>
            <th style="font-weight:bold; font-size:16px; text-align:center;" colspan="3">Qty Pohon</th>
        </tr>
        <tr>
            {{-- C type --}}
            {{-- <th style="font-weight:bold; font-size:16px;">No</th> --}}
            <th style="font-weight:bold; font-size:16px; text-align:right;">Target</th>
            <th style="font-weight:bold; font-size:16px; text-align:right;">Realisasi</th>
            <th style="font-weight:bold; font-size:16px; text-align:right;">Selisih</th>
            <th style="font-weight:bold; font-size:16px; text-align:right;">Target</th>
            <th style="font-weight:bold; font-size:16px; text-align:right;">Realisasi</th>
            <th style="font-weight:bold; font-size:16px; text-align:right;">Selisih</th>
        </tr>
    </thead>
    <tbody>

@forelse ($data as $dataOK)
            <tr>
                {{-- <td>{{ $loop->iteration }} </td> --}}
                <td style="text-align:center;"><span class="badge bg-dark" style="font-size:14px;">{{ $dataOK->SW }}</span></td>
                <td style="text-align:center; color:black;">{{ $dataOK->Description }}</td>
                <td style="text-align:center; color:black;">{{ $dataOK->Carat }}</td>
                <td style="text-align:right; color:black;">{{ number_format($dataOK->Weight,2) }}</td>
                <td style="text-align:right; color:black;">{{ number_format($dataOK->SuggestionWeight, 2) }}</td>
                <td style="text-align:right; color:black;">{{ number_format($dataOK->Selisih1, 2) }}</td>
                <td style="text-align:right; color:black;">{{ $dataOK->WaxTree }}</td>
                <td style="text-align:right; color:black;">{{ $dataOK->SuggestionWaxTree }}</td>
                <td style="text-align:right; color:black;">{{ $dataOK->Selisih2 }}</td>
            </tr>
        @empty
            <div class="alert alert-danger">
                Data Belum Tersedia.
            </div>
        @endforelse
    </tbody>
</table>
</div>


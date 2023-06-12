@php
    $tglnow = date('Y-m-d');
    foreach ($data as $datas){};
@endphp

<form id="tampilform" >
    <div class="row">
        <div class="col-md-12">
            <div class="row">
                <div class="col-md-4 mb-0">
                    <div class="form-group">
                        <label class="form-label" style="font-size: 13px; font-weight: bold; color: black; text-transform: capitalize;">ID</label>
                        <input type="hidden" id="idsusutan" name="idsusutan" value="{{$datas->IdSusutan}}">
                        <input type="text" class="form-control" style="background-color:transparent; color: blue; font-weight: bold" name="idsusutan" id="idsusutan" value="{{$datas->IdSusutan}}" readonly>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label class="form-label" style="color: white">No SPKO</label>
                        <input type="text" class="form-control-plaintext" style="background-color:transparent; color: blue; font-weight: bold" name="nospko" id="nospko" value="{{$datas->NoSPKO}}" readonly>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label class="form-label" style="color: white">status</label>
                        @if($datas->Active == 'C')
                            <input type="text" class="form-control-plaintext" style="background-color:transparent; color: purple; font-weight: bold; font-size: 20px" value="Dibatalkan" readonly>
                        @elseif($datas->Active == 'P')
                            <input type="text" class="form-control-plaintext" style="background-color:transparent; color: purple; font-weight: bold; font-size: 20px" value="Diposting" readonly>
                        @else
                            <p></p>
                        @endif
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label" style="font-size: 13px; font-weight: bold; color: black; text-transform: capitalize;">Pekerja</label>
                        <input type="text" class="form-control" style="background-color:transparent; color: blue; font-weight: bold" name="pekerja" id="pekerja" value="{{$datas->Pekerja}}" readonly>
                    </div>
                </div>
                
                <div class="col-md-4 mb-0">
                    <div class="form-group">
                        <label class="form-label" style="font-size: 13px; font-weight: bold; color: black; text-transform: capitalize;">Tanggal</label>
                        <input type="text" class="form-control" style="background-color:transparent; color: blue; font-weight: bold" id="tgl" name="tgl" value="{{$datas->Tgl}}" readonly>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label" style="font-size: 13px; font-weight: bold; color: black; text-transform: capitalize;">No SPKO</label>
                        <input type="text" class="form-control" style="background-color:transparent; color: blue; font-weight: bold" name="nospko" id="nospko" value="{{$datas->NoSPKO}}" readonly>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label" style="font-size: 13px; font-weight: bold; color: black; text-transform: capitalize;">Kadar</label>
                        <input type="text" class="form-control" style="background-color:transparent; color: blue; font-weight: bold" name="kadar" id="kadar" value="{{$datas->Kadar}}" readonly>
                    </div>
                </div>

                <div class="col-md-4 mb-0">
                    <div class="form-group">
                        <label class="form-label" style="font-size: 13px; font-weight: bold; color: black; text-transform: capitalize;">Bagian</label>
                        <input type="text" class="form-control" style="background-color:transparent; color: blue; font-weight: bold" name="bagian" id="bagian" value="{{$datas->Bagian}}" readonly>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label" style="font-size: 13px; font-weight: bold; color: black; text-transform: capitalize;">Proses</label>
                        <input type="text" class="form-control" style="background-color:transparent; color: blue; font-weight: bold" name="proses" id="proses" value="{{$datas->Proses}}" readonly>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label" style="font-size: 13px; font-weight: bold; color: black; text-transform: capitalize;">Susut (Brt)</label>
                        <input type="text" class="form-control" style="background-color:transparent; color: blue; font-weight: bold" name="susutbrt" id="susutbrt" value="{{$datas->Susut}}" readonly>
                    </div>
                </div>

                <div class="col-md-4 mb-0">
                    <div class="form-group">
                        <label class="form-label" style="font-size: 13px; font-weight: bold; color: black; text-transform: capitalize;">Brt SPKO</label>
                        <input type="text" class="form-control" style="background-color:transparent; color: blue; font-weight: bold" name="brtspko" id="brtspko" value="{{$datas->BrtSPKO}}" readonly>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label" style="font-size: 13px; font-weight: bold; color: black; text-transform: capitalize;">Brt NTHKO</label>
                        <input type="text" class="form-control" style="background-color:transparent; color: blue; font-weight: bold" name="brtnthko" id="proses" value="{{$datas->BrtNTHKO}}" readonly>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label" style="font-size: 13px; font-weight: bold; color: black; text-transform: capitalize;">Susut (%)</label>
                        <input type="text" class="form-control" style="background-color:transparent; color: blue; font-weight: bold" name="susutpersen" id="susutpersen" value="{{$datas->SusutPersen}}" readonly>
                    </div>
                </div>

                <div class="col-md-4 mb-2">
                    <div class="form-group">
                        <label class="form-label" style="font-size: 13px; font-weight: bold; color: black; text-transform: capitalize;">Toleransi</label>
                        <input type="text" class="form-control" style="background-color:transparent; color: blue; font-weight: bold" name="toleransi" id="toleransi" value="{{$datas->Toleransi}}" readonly>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label" style="font-size: 13px; font-weight: bold; color: black; text-transform: capitalize;">Perbedaan</label>
                        <input type="text" class="form-control" style="background-color:transparent; color: blue; font-weight: bold" name="perbedaaan" id="perbedaaan" value="{{$datas->Perbedaan}}" readonly>
                    </div>
                </div>
                
            </div>
        </div>

    </div>

    <hr style="height:1px; color: #000000;">
    
    <div class="form-group row">
        <div class="col-md-2">
            <label class="control-label" style="font-size: 13px; font-weight: bold; color: black; text-transform: capitalize;">Entry Date : <span style="color: blue; font-weight: bold">{{$datas->EntryDate}}</span> </label>   
        </div>
        <div class="col-md-2">
            <label class="control-label" style="font-size: 13px; font-weight: bold; color: black; text-transform: capitalize;">User : <span style="color: blue; font-weight: bold">{{$datas->User}}</span> </label>   
        </div>
        <label class="col-md-1 col-form-label" style="font-size: 13px; font-weight: bold; color: black; text-transform: capitalize;">Catatan :</label>
        <div class="col-md-7">
            <input type="text" class="form-control" style="background-color:transparent; color: blue; font-weight: bold" name="catatan" id="catatan" value="{{$datas->Catatan}}" readonly>
        </div>
    </div>

    <hr style="height:1px; color: #000000;">

    <table class="table table-striped text-nowrap" id="tampiltabel" style="min-width: 100%">
        <thead>
            <tr bgcolor="#111111">
                <th class="text-center" style="font-size: 10px; font-weight: bold; color: white; text-transform: capitalize">No</th>
                <th class="text-center" style="font-size: 10px; font-weight: bold; color: white; text-transform: capitalize">No SPK</th>
                <th class="text-center" style="font-size: 10px; font-weight: bold; color: white; text-transform: capitalize">Produk</th>
                <th class="text-center" style="font-size: 10px; font-weight: bold; color: white; text-transform: capitalize">Jml SPKO</th>
                <th class="text-center" style="font-size: 10px; font-weight: bold; color: white; text-transform: capitalize">Jml NTHKO</th>
                <th class="text-center" style="font-size: 10px; font-weight: bold; color: white; text-transform: capitalize">Brt SPKO</th>
                <th class="text-center" style="font-size: 10px; font-weight: bold; color: white; text-transform: capitalize">Brt NTHKO</th>
                <th class="text-center" style="font-size: 10px; font-weight: bold; color: white; text-transform: capitalize">Jml Selisih</th>
                <th class="text-center" style="font-size: 10px; font-weight: bold; color: white; text-transform: capitalize">Brt Selisih</th>
            </tr>                     
        </thead>
        <tbody>

            @foreach ($data2 as $datas2)
                <tr>
                    <td align="center" style="font-size: 12px; font-weight: bold; color: black;">{{ $loop->iteration }}</td>
                    <td align="center" style="font-size: 12px; font-weight: bold; color: black;">{{ $datas2->NoSPK }}</td>
                    <td align="center" style="font-size: 12px; font-weight: bold; color: black;">{{ $datas2->Produk }}</td>
                    <td align="center" style="font-size: 12px; font-weight: bold; color: black;">{{ number_format($datas2->QtyAllocation,2) }}</td>
                    <td align="center" style="font-size: 12px; font-weight: bold; color: black;">{{ number_format($datas2->QtyCompletion,2) }}</td>
                    <td align="center" style="font-size: 12px; font-weight: bold; color: black;">{{ number_format($datas2->WeightAllocation,2) }}</td>
                    <td align="center" style="font-size: 12px; font-weight: bold; color: black;">{{ number_format($datas2->WeightCompletion,2) }}</td>
                    <td align="center" style="font-size: 12px; font-weight: bold; color: black;">{{ number_format($datas2->QtyDifference,2) }}</td>
                    <td align="center" style="font-size: 12px; font-weight: bold; color: black;">{{ number_format($datas2->WeightDifference,2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

</form>
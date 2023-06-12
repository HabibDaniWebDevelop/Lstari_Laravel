@php
    $tglnow = date('Y-m-d');
    foreach ($data as $datas){};
    $tgltm = date("d/m/Y", strtotime($datas->TransDate));
    foreach ($data3 as $datas3){};
@endphp


<form id="tampilform">
    <div class="row">
        <div class="col-md-10">
            <div class="row">
                <div class="col-md-6 mb-2">
                    <div class="form-group">
                        <label class="form-label">ID</label>
                        <input type="hidden" id="fromloc" name="fromloc" value="{{$datas->FromLoc}}">
                        <input type="hidden" id="toloc" name="toloc" value="{{$datas->ToLoc}}">
                        <input type="text" class="form-control" style="background-color:transparent" name="idtm" id="idtm" value="{{$datas->ID}}" readonly>
                    </div>
                </div>

                <div class="col-md-2">
                    <div class="form-group">
                        <label class="form-label" style="color: white">Status</label>
                        @if($datas->Active == 'S')
                            <p style="font-size: 20px; font-weight: bold; color: purple;">Disusutkan</p>
                        @elseif($datas->Active == 'C')
                            <p style="font-size: 20px; font-weight: bold; color: purple;">Dibatalkan</p>
                        @elseif($datas->Active == 'P')
                            <p style="font-size: 20px; font-weight: bold; color: purple;">Diposting</p>
                        @elseif($datas->Active == 'A')
                            <p style="font-size: 20px; font-weight: bold; color: purple;">Aktif</p>
                        @else
                            <p></p>
                        @endif
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label" style="color: white">Button</label><br>
                        <span>
                            @if($statusLoc == 0)
                                @if($cekStokHarianTM == true && $tglcek == true && $datas->Active == 'A')
                                    <button type="button" class="btn btn-dark btn-sm" id="btnposting" onclick="klikPosting()"><span class="tf-icons bx bx-send"></span>&nbsp; Posting</button>
                                @else
                                    <button type="button" class="btn btn-dark btn-sm" id="btnposting" onclick="klikPosting()" disabled><span class="tf-icons bx bx-send"></span>&nbsp; Posting</button>
                                @endif
                            @else
                                <button type="button" class="btn btn-dark btn-sm" id="btnposting" onclick="klikPosting()" disabled><span class="tf-icons bx bx-send"></span>&nbsp; Posting</button>
                            @endif
                        
                            
                            @if($location == 47 && $statusLoc == 0)
                                @if($datas3->OperationProcess <> 0 || $datas3->Level2Process <> 0 || $datas3->Level3Process <> 0 || $datas3->Level4Process <> 0)
                                    <button type="button" class="btn btn-dark btn-sm" id="btnupdateoperation" onclick="klikUpdateOperation()"><span class="tf-icons bx bx-up-arrow"></span>&nbsp; Update Operation</button>
                                @else
                                    <button type="button" class="btn btn-dark btn-sm" id="btnupdateoperation" onclick="klikUpdateOperation()" disabled><span class="tf-icons bx bx-up-arrow"></span>&nbsp; Update Operation</button>
                                @endif
                            @elseif($location == 12 && $statusLoc == 0)
                                @if($datas3->OperationProcess <> 0 || $datas3->Level2Process <> 0)
                                    <button type="button" class="btn btn-dark btn-sm" id="btnupdateoperation" onclick="klikUpdateOperation()"><span class="tf-icons bx bx-up-arrow"></span>&nbsp; Update Operation</button>
                                @else
                                    <button type="button" class="btn btn-dark btn-sm" id="btnupdateoperation" onclick="klikUpdateOperation()" disabled><span class="tf-icons bx bx-up-arrow"></span>&nbsp; Update Operation</button>
                                @endif
                            @elseif($statusLoc == 0)
                                @if($datas3->OperationProcess <> 0)
                                    <button type="button" class="btn btn-dark btn-sm" id="btnupdateoperation" onclick="klikUpdateOperation()"><span class="tf-icons bx bx-up-arrow"></span>&nbsp; Update Operation</button>
                                @else
                                    <button type="button" class="btn btn-dark btn-sm" id="btnupdateoperation" onclick="klikUpdateOperation()" disabled><span class="tf-icons bx bx-up-arrow"></span>&nbsp; Update Operation</button>
                                @endif
                            @else
                                <button type="button" class="btn btn-dark btn-sm" id="btnupdateoperation" onclick="klikUpdateOperation()" disabled><span class="tf-icons bx bx-up-arrow"></span>&nbsp; Update Operation</button>
                            @endif
                        
                        </span>
                    </div>
                </div>

                
                <div class="col-md-6 mb-2">
                    <div class="form-group">
                        <label class="form-label">Tanggal</label>
                        <input type="date" class="form-control" style="background-color:transparent" id="tgl" name="tgl" value="{{$datas->TransDate}}" readonly>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label">Penerima</label>
                        <input type="text" class="form-control" style="background-color:transparent" name="karyawan" id="karyawan" value="{{$datas->ESW}}" readonly>
                    </div>
                </div>

                <div class="col-md-6 mb-2">
                    <div class="form-group">
                        <label class="form-label">Dari Bagian</label>
                        <select class="form-select" name="daribagian" id="daribagian" readonly>
                            <option value="{{$datas->FDescription}}">{{$datas->FDescription}}</option>
                        </select>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label">Ke Bagian</label>
                        <select class="form-select" name="kebagian" id="kebagian" readonly>
                            <option value="{{$datas->ODescription}}">{{$datas->ODescription}}</option>
                        </select>
                    </div>
                </div>
                
            </div>
        </div>
        <div class="col-md-2" style="text-align: center">
            <img id="showgambar" src="http://192.168.3.100:8383/image2/NO-IMAGE.jpg" style="display:none;">
        </div>
    </div>

    
<hr style="height:1px; color: #000000;">
    <div class="row">
        <div class="col-md-3">
            <label class="control-label">Entry Date : <span style="color: blue; font-weight: bold">{{$tgltm}}</span> </label>   
        </div>
        <div class="col-md-3">
            <label class="control-label">User : <span style="color: blue; font-weight: bold">{{$datas->UserName}}</span> </label>   
        </div>
        <div class="col-md-3">
            <label class="control-label">Total Jumlah : <span style="color: blue; font-weight: bold">{{$datas->TotalQty}}</span> </label>   
        </div>
        <div class="col-md-3">
            <label class="control-label">Total Berat : <span style="color: blue; font-weight: bold">{{number_format($datas->TotalWeight,2)}}</span> </label>   
        </div>
    </div>
<hr style="height:1px; color: #000000;">

@if($statusLoc == 1)

    <table class="table table-striped text-nowrap" id="tampiltabel" style="min-width: 100%">
        <thead>
            <tr bgcolor="#111111">
                <th class="text-center" style="font-size: 12px; font-weight: bold; color: white">No</th>
                <th class="text-center" style="font-size: 12px; font-weight: bold; color: white">No Terima</th>
                <th class="text-center" style="font-size: 12px; font-weight: bold; color: white">Freq</th>
                <th class="text-center" style="font-size: 12px; font-weight: bold; color: white">Urut</th>
                <th class="text-center" style="font-size: 12px; font-weight: bold; color: white">No SPK</th>
                <th class="text-center" style="font-size: 12px; font-weight: bold; color: white">Produk SPK</th>
                <th class="text-center" style="font-size: 12px; font-weight: bold; color: white">Kadar</th> 
                <th class="text-center" style="font-size: 12px; font-weight: bold; color: white">Jml SPK</th>
                <th class="text-center" style="font-size: 12px; font-weight: bold; color: white">Barang</th> 
                <th class="text-center" style="font-size: 12px; font-weight: bold; color: white">Jumlah</th>
                <th class="text-center" style="font-size: 12px; font-weight: bold; color: white">Berat</th>
                <th class="text-center" style="font-size: 12px; font-weight: bold; color: white">Note</th>
                <th class="text-center" style="font-size: 12px; font-weight: bold; color: white">Pohon ID</th> 
                <th class="text-center" style="font-size: 12px; font-weight: bold; color: white">Pohon Urut</th>
                <th class="text-center" style="font-size: 12px; font-weight: bold; color: white">No Pohon</th> 
                <th class="text-center" style="font-size: 12px; font-weight: bold; color: white">Produk Detail</th>  
                <th class="text-center" style="font-size: 12px; font-weight: bold; color: white">BatchNo</th> 
            </tr>                     
        </thead>
        <tbody>
            @php
                $no=0;
            @endphp

            @foreach ($data2 as $datas2)
        
            <tr onclick="tampilGambar('{{ $datas2->ZPhoto }}')">
                <td align="center" style="font-size: 13px; font-weight: bold; color: black;">{{ $loop->iteration }}</td>
                <td align="center" style="font-size: 13px; font-weight: bold; color: black;">{{ $datas2->WorkAllocation }}</td>
                <td align="center" style="font-size: 13px; font-weight: bold; color: black;">{{ $datas2->LinkFreq }}</td>
                <td align="center" style="font-size: 13px; font-weight: bold; color: black;">{{ $datas2->LinkOrd }}</td>
                <td align="center" style="font-size: 13px; font-weight: bold; color: black;">{{ $datas2->OSW }}</td>
                <td align="center" style="font-size: 13px; font-weight: bold; color: black;">{{ $datas2->FSW }}</td>
                <td align="center" style="font-size: 13px; font-weight: bold; color: black;">{{ $datas2->CSW }}</td>
                <td align="center" style="font-size: 13px; font-weight: bold; color: black;">{{ $datas2->QtyOrder }}</td>
                <td align="center" style="font-size: 13px; font-weight: bold; color: black;">{{ $datas2->PDescription }}</td>
                <td align="center" style="font-size: 13px; font-weight: bold; color: black;">{{ $datas2->Qty }}</td>
                <td align="center" style="font-size: 13px; font-weight: bold; color: black;">{{ $datas2->Weight }}</td>
                <td align="center" style="font-size: 13px; font-weight: bold; color: black;">{{ $datas2->Note }}</td>
                <td align="center" style="font-size: 13px; font-weight: bold; color: black;">{{ $datas2->TreeID }}</td>
                <td align="center" style="font-size: 13px; font-weight: bold; color: black;">{{ $datas2->TreeOrd }}</td>
                <td align="center" style="font-size: 13px; font-weight: bold; color: black;">{{ $datas2->RubberPlate }}</td>
                <td align="center" style="font-size: 13px; font-weight: bold; color: black;">{{ $datas2->GSW }}</td>
                <td align="center" style="font-size: 13px; font-weight: bold; color: black;">{{ $datas2->BatchNo }}</td>

                <input type="hidden" name="fg[]" id="fg{{ $loop->iteration }}" value="{{$datas2->FG}}"> 
                <input type="hidden" name="ordinal[]" id="ordinal{{ $loop->iteration }}" value="{{$datas2->Ordinal}}"> 
            </tr>
            @php
                $no++;
            @endphp
            @endforeach
        </tbody>
    </table>

@else

    @if($status == 0 && $statusOp == 0)

        <table class="table table-striped text-nowrap" id="tampiltabel" style="min-width: 100%">
            <thead>
                <tr bgcolor="#111111">
                    <th class="text-center" style="font-size: 12px; font-weight: bold; color: white">No</th>
                    @if($location==47)
                        <th class="text-center" style="font-size: 12px; font-weight: bold; color: white; width: 150px;">Proses</th> 
                        <th class="text-center" style="font-size: 12px; font-weight: bold; color: white; width: 150px;">SubProses1</th>
                        <th class="text-center" style="font-size: 12px; font-weight: bold; color: white; width: 150px;">SubProses2</th>
                        <th class="text-center" style="font-size: 12px; font-weight: bold; color: white; width: 150px;">SubProses3</th> 
                    @elseif($location==12)
                        <th class="text-center" style="font-size: 12px; font-weight: bold; color: white; width: 150px;">Proses</th> 
                        <th class="text-center" style="font-size: 12px; font-weight: bold; color: white; width: 150px;">SubProses1</th>
                    @elseif($location==50 || $location==4 || $location==48 || $location==52 || $location==49 || $location==7 || $location==17 || $location==22)
                        <th class="text-center" style="font-size: 12px; font-weight: bold; color: white; width: 150px;">Proses</th> 
                    @else
                        <th class="text-center" style="font-size: 12px; font-weight: bold; color: white; width: 150px;">Proses</th> 
                    @endif

                    {{-- 
                    <th class="text-center" style="font-size: 12px; font-weight: bold; color: white; width: 150px;">Proses</th> 
                    <th class="text-center" style="font-size: 12px; font-weight: bold; color: white; width: 150px;">SubProses1</th>
                    <th class="text-center" style="font-size: 12px; font-weight: bold; color: white; width: 150px;">SubProses2</th>
                    <th class="text-center" style="font-size: 12px; font-weight: bold; color: white; width: 150px;">SubProses3</th>  
                    --}}

                    <th class="text-center" style="font-size: 12px; font-weight: bold; color: white; text-transform: capitalize">Jumlah</th>
                    <th class="text-center" style="font-size: 12px; font-weight: bold; color: white; text-transform: capitalize">Berat</th>
                    <th class="text-center" style="font-size: 12px; font-weight: bold; color: white; text-transform: capitalize">No Terima</th>
                    <th class="text-center" style="font-size: 12px; font-weight: bold; color: white; text-transform: capitalize">Freq</th>
                    <th class="text-center" style="font-size: 12px; font-weight: bold; color: white; text-transform: capitalize">Urut</th>
                    <th class="text-center" style="font-size: 12px; font-weight: bold; color: white; text-transform: capitalize">No SPK</th>
                    <th class="text-center" style="font-size: 12px; font-weight: bold; color: white; text-transform: capitalize">Produk SPK</th>
                    <th class="text-center" style="font-size: 12px; font-weight: bold; color: white; text-transform: capitalize">Kadar</th> 
                    <th class="text-center" style="font-size: 12px; font-weight: bold; color: white; text-transform: capitalize">Jml SPK</th>
                    <th class="text-center" style="font-size: 12px; font-weight: bold; color: white; text-transform: capitalize">Barang</th> 
                    <th class="text-center" style="font-size: 12px; font-weight: bold; color: white; text-transform: capitalize">Note</th>
                    <th class="text-center" style="font-size: 12px; font-weight: bold; color: white; text-transform: capitalize">Pohon ID</th> 
                    <th class="text-center" style="font-size: 12px; font-weight: bold; color: white; text-transform: capitalize">Pohon Urut</th>
                    <th class="text-center" style="font-size: 12px; font-weight: bold; color: white; text-transform: capitalize">No Pohon</th> 
                    <th class="text-center" style="font-size: 12px; font-weight: bold; color: white; text-transform: capitalize">Produk Detail</th>  
                    <th class="text-center" style="font-size: 12px; font-weight: bold; color: white; text-transform: capitalize">BatchNo</th> 
                </tr>                     
            </thead>
            <tbody>
                @php
                    $no=0;
                @endphp

                @foreach ($data2 as $datas2)
            
                <tr onclick="tampilGambar('{{ $datas2->ZPhoto }}')">
                    <td align="center" style="font-size: 13px; font-weight: bold; color: black;">{{ $loop->iteration }}</td>
                    @if($location==47)
                        <td align="center" style="font-size: 13px; font-weight: bold; color: black;">{{$datas2->OperationProcess}}</td>
                        <td align="center" style="font-size: 13px; font-weight: bold; color: black;">{{$datas2->Level2Process}}</td>
                        <td align="center" style="font-size: 13px; font-weight: bold; color: black;">{{$datas2->Level3Process}}</td>
                        <td align="center" style="font-size: 13px; font-weight: bold; color: black;">{{$datas2->Level4Process}}</td>
                    @elseif($location==12)
                        <td align="center" style="font-size: 13px; font-weight: bold; color: black;">{{$datas2->OperationProcess}}</td>
                        <td align="center" style="font-size: 13px; font-weight: bold; color: black;">{{$datas2->Level2Process}}</td>
                    @elseif($location==50 || $location==4 || $location==48 || $location==52 || $location==49 || $location==7 || $location==17 || $location==22)
                        <td align="center" style="font-size: 13px; font-weight: bold; color: black;">{{$datas2->OperationProcess}}</td>
                    @else
                        <td align="center" style="font-size: 13px; font-weight: bold; color: black;">{{$datas2->OperationProcess}}</td>
                    @endif
                    <td align="center" style="font-size: 13px; font-weight: bold; color: black;">{{ $datas2->Qty }}</td>
                    <td align="center" style="font-size: 13px; font-weight: bold; color: black;">{{ $datas2->Weight }}</td>

                    <td align="center" style="font-size: 13px; font-weight: bold; color: black;">{{ $datas2->WorkAllocation }}</td>
                    <td align="center" style="font-size: 13px; font-weight: bold; color: black;">{{ $datas2->LinkFreq }}</td>
                    <td align="center" style="font-size: 13px; font-weight: bold; color: black;">{{ $datas2->LinkOrd }}</td>
                    <td align="center" style="font-size: 13px; font-weight: bold; color: black;">{{ $datas2->OSW }}</td>
                    <td align="center" style="font-size: 13px; font-weight: bold; color: black;">{{ $datas2->FSW }}</td>
                    <td align="center" style="font-size: 13px; font-weight: bold; color: black;">{{ $datas2->CSW }}</td>
                    <td align="center" style="font-size: 13px; font-weight: bold; color: black;">{{ $datas2->QtyOrder }}</td>
                
                    <td align="center" style="font-size: 13px; font-weight: bold; color: black;">{{ $datas2->PDescription }}</td>
                    {{-- 
                    <td align="center" style="font-size: 13px; font-weight: bold; color: black;">{{ $datas2->Qty }}</td>
                    <td align="center" style="font-size: 13px; font-weight: bold; color: black;">{{ $datas2->Weight }}</td> 
                    --}}

                    <td align="center" style="font-size: 13px; font-weight: bold; color: black;">{{ $datas2->Note }}</td>
                    <td align="center" style="font-size: 13px; font-weight: bold; color: black;">{{ $datas2->TreeID }}</td>
                    <td align="center" style="font-size: 13px; font-weight: bold; color: black;">{{ $datas2->TreeOrd }}</td>
                    <td align="center" style="font-size: 13px; font-weight: bold; color: black;">{{ $datas2->RubberPlate }}</td>
                    <td align="center" style="font-size: 13px; font-weight: bold; color: black;">{{ $datas2->GSW }}</td>
                    <td align="center" style="font-size: 13px; font-weight: bold; color: black;">{{ $datas2->BatchNo }}</td>

                </tr>
                @php
                    $no++;
                @endphp
                @endforeach
            </tbody>
        </table>

    @else

        <table class="table table-striped text-nowrap" id="tampiltabel" style="min-width: 100%">
            <thead>
                <tr bgcolor="#111111">
                    <th class="text-center" style="font-size: 12px; font-weight: bold; color: white">No</th>
                    <th class="text-center" style="font-size: 12px; font-weight: bold; color: white">Gambar</th>
                    @if($location==47)
                        <th class="text-center" style="font-size: 12px; font-weight: bold; color: white; width: 150px;">Proses</th> 
                        <th class="text-center" style="font-size: 12px; font-weight: bold; color: white; width: 150px;">SubProses1</th>
                        <th class="text-center" style="font-size: 12px; font-weight: bold; color: white; width: 150px;">SubProses2</th>
                        <th class="text-center" style="font-size: 12px; font-weight: bold; color: white; width: 150px;">SubProses3</th> 
                    @elseif($location==12)
                        <th class="text-center" style="font-size: 12px; font-weight: bold; color: white; width: 150px;">Proses</th> 
                        <th class="text-center" style="font-size: 12px; font-weight: bold; color: white; width: 150px;">SubProses1</th>
                    @elseif($location==50 || $location==4 || $location==48 || $location==52 || $location==49 || $location==7 || $location==17 || $location==22)
                        <th class="text-center" style="font-size: 12px; font-weight: bold; color: white; width: 150px;">Proses</th> 
                    @else
                        <th class="text-center" style="font-size: 12px; font-weight: bold; color: white; width: 150px;">Proses</th> 
                    @endif

                    {{-- 
                    <th class="text-center" style="font-size: 12px; font-weight: bold; color: white; width: 150px;">Proses</th> 
                    <th class="text-center" style="font-size: 12px; font-weight: bold; color: white; width: 150px;">SubProses1</th>
                    <th class="text-center" style="font-size: 12px; font-weight: bold; color: white; width: 150px;">SubProses2</th>
                    <th class="text-center" style="font-size: 12px; font-weight: bold; color: white; width: 150px;">SubProses3</th>  
                    --}}

                    <th class="text-center" style="font-size: 12px; font-weight: bold; color: white">Jumlah</th>
                    <th class="text-center" style="font-size: 12px; font-weight: bold; color: white">Berat</th>
                    <th class="text-center" style="font-size: 12px; font-weight: bold; color: white">No Terima</th>
                    <th class="text-center" style="font-size: 12px; font-weight: bold; color: white">Freq</th>
                    <th class="text-center" style="font-size: 12px; font-weight: bold; color: white">Urut</th>
                    <th class="text-center" style="font-size: 12px; font-weight: bold; color: white">No SPK</th>
                    <th class="text-center" style="font-size: 12px; font-weight: bold; color: white">Produk SPK</th>
                    <th class="text-center" style="font-size: 12px; font-weight: bold; color: white">Kadar</th> 
                    <th class="text-center" style="font-size: 12px; font-weight: bold; color: white">Jml SPK</th>
                    <th class="text-center" style="font-size: 12px; font-weight: bold; color: white">Barang</th> 
                    <th class="text-center" style="font-size: 12px; font-weight: bold; color: white">Note</th>
                    <th class="text-center" style="font-size: 12px; font-weight: bold; color: white">Pohon ID</th> 
                    <th class="text-center" style="font-size: 12px; font-weight: bold; color: white">Pohon Urut</th>
                    <th class="text-center" style="font-size: 12px; font-weight: bold; color: white">No Pohon</th> 
                    <th class="text-center" style="font-size: 12px; font-weight: bold; color: white">Produk Detail</th>  
                    <th class="text-center" style="font-size: 12px; font-weight: bold; color: white">BatchNo</th> 
                </tr>                     
            </thead>
            <tbody>
                @php
                    $no=0;
                @endphp

                @foreach ($data2 as $key => $datas2)
            
                <tr onclick="tampilGambar('{{ $datas2->ZPhoto }}')">
                    <td align="center" style="font-size: 13px; font-weight: bold; color: black;">{{ $loop->iteration }}</td>
                    <td align="center" style="font-size: 13px; font-weight: bold; color: black;">
                        @if($datas2->ZPhoto == '')
                            <img src="http://192.168.3.100:8383/image2/NO-IMAGE.jpg" style="height: 200px; width: 200px;">
                        @else
                            <img src="http://192.168.3.100:8383/image2/{{$datas2->ZPhoto}}.jpg" onerror="this.onerror=null;this.src='http://192.168.3.100:8383/image2/NO-IMAGE.jpg'" style="height: 200px; width: 200px;">
                        @endif
                    </td>
                    @if($location==47)
                        <td align="center" style="font-size: 13px; font-weight: bold; color: black;">
                            <select class="form-select" name="operation[]" id="operation{{$loop->iteration}}" required="required"> 
                                <option value="{{$arrOperationID[$no]}}">{{$arrOperation[$no]}}</option>
                                @foreach ($dataOperation as $datasOperation)
                                    <option value="{{$datasOperation->ID}}">{{$datasOperation->Description}}</option>
                                @endforeach
                            </select> 
                        </td>
                        <td align="center" style="font-size: 13px; font-weight: bold; color: black;">
                            <select class="form-select" name="level2[]" id="level2{{$loop->iteration}}" required="required"> 
                                <option value="{{$arrLevel2ID[$no]}}">{{$arrLevel2[$no]}}</option>
                                @foreach ($enmLvl2 as $enmLvl2s)
                                <option value="{{$enmLvl2s->ID}}">{{$enmLvl2s->Description}}</option>
                                @endforeach
                            </select> 
                        </td>
                        <td align="center" style="font-size: 13px; font-weight: bold; color: black;">
                            <select class="form-select" name="level3[]" id="level3{{$loop->iteration}}" required="required"> 
                                <option value="{{$arrLevel3ID[$no]}}">{{$arrLevel3[$no]}}</option>
                                @foreach ($enmLvl3 as $enmLvl3s)
                                <option value="{{$enmLvl3s->ID}}">{{$enmLvl3s->Description}}</option>
                                @endforeach
                            </select> 
                        </td>
                        <td align="center" style="font-size: 13px; font-weight: bold; color: black;">
                            <select class="form-select" name="level4[]" id="level4{{$loop->iteration}}" required="required"> 
                                <option value="{{$arrLevel4ID[$no]}}">{{$arrLevel4[$no]}}</option>
                                @foreach ($enmLvl4 as $enmLvl4s)
                                <option value="{{$enmLvl4s->ID}}">{{$enmLvl4s->Description}}</option>
                                @endforeach
                            </select> 
                        </td>
                    @elseif($location==12)
                        <td align="center" style="font-size: 13px; font-weight: bold; color: black;">
                            <select class="form-select" name="operation[]" id="operation{{$loop->iteration}}" required="required"> 
                                <option value="{{$arrOperationID[$no]}}">{{$arrOperation[$no]}}</option>
                                @foreach ($dataOperation as $datasOperation)
                                    <option value="{{$datasOperation->ID}}">{{$datasOperation->Description}}</option>
                                @endforeach
                            </select> 
                        </td>
                        <td align="center" style="font-size: 13px; font-weight: bold; color: black;">
                            <select class="form-select" name="level2[]" id="level2{{$loop->iteration}}" required="required"> 
                                <option value="{{$arrLevel2ID[$no]}}">{{$arrLevel2[$no]}}</option>
                                @foreach ($polesLvl2 as $polesLvl2s)
                                <option value="{{$polesLvl2s->ID}}">{{$polesLvl2s->Description}}</option>
                                @endforeach
                            </select> 
                        </td>
                        <input type="hidden" name="level3[]" id="level3{{ $loop->iteration }}"> 
                        <input type="hidden" name="level4[]" id="level4{{ $loop->iteration }}"> 

                    @elseif($location==4 || $location==48 || $location==52 || $location==49 || $location==7 || $location==17 || $location==22)
                        <td align="center" style="font-size: 13px; font-weight: bold; color: black;">
                            <select class="form-select" name="operation[]" id="operation{{$loop->iteration}}" onchange="getval({{$loop->iteration}})" required="required"> 
                                <option value="{{$arrOperationID[$no]}}">{{$arrOperation[$no]}}</option>
                                @foreach ($dataOperation as $datasOperation)
                                    <option value="{{$datasOperation->ID}}">{{$datasOperation->Description}}</option>
                                @endforeach
                            </select> 
                        </td>
                        <input type="hidden" name="level2[]" id="level2{{ $loop->iteration }}"> 
                        <input type="hidden" name="level3[]" id="level3{{ $loop->iteration }}">
                        <input type="hidden" name="level4[]" id="level4{{ $loop->iteration }}">  

                    @elseif($location==50)
                        <td align="center" style="font-size: 13px; font-weight: bold; color: black;">
                            @if($key == 0)
                                <select class="form-select" name="operation[]" id="operation{{$loop->iteration}}" onchange="getval({{$loop->iteration}})" required="required"> 
                                    <option value="{{$arrOperationID[$no]}}">{{$arrOperation[$no]}}</option>
                                    @foreach ($dataOperation as $datasOperation)
                                        <option value="{{$datasOperation->ID}}">{{$datasOperation->Description}}</option>
                                    @endforeach
                                </select> 
                            @else
                                <select class="form-select" name="operation[]" id="operation{{$loop->iteration}}" required="required"> 
                                    <option value="{{$arrOperationID[$no]}}">{{$arrOperation[$no]}}</option>
                                    @foreach ($dataOperation as $datasOperation)
                                        <option value="{{$datasOperation->ID}}">{{$datasOperation->Description}}</option>
                                    @endforeach
                                </select> 
                            @endif
                        </td>
                        <input type="hidden" name="level2[]" id="level2{{ $loop->iteration }}"> 
                        <input type="hidden" name="level3[]" id="level3{{ $loop->iteration }}">
                        <input type="hidden" name="level4[]" id="level4{{ $loop->iteration }}">  

                    @elseif($location==10)
                        <td align="center" style="font-size: 13px; font-weight: bold; color: black;">
                            <select class="form-select" name="operation[]" id="operation{{$loop->iteration}}" onchange="getval({{$loop->iteration}})" required="required"> 
                                <option value="45">QC</option>
                            </select> 
                        </td>
                        <input type="hidden" name="level2[]" id="level2{{ $loop->iteration }}"> 
                        <input type="hidden" name="level3[]" id="level3{{ $loop->iteration }}">
                        <input type="hidden" name="level4[]" id="level4{{ $loop->iteration }}">  

                    @else
                        <td align="center" style="font-size: 13px; font-weight: bold; color: black;">
                            <select class="form-select" name="operation[]" id="operation{{$loop->iteration}}" onchange="getval({{$loop->iteration}})" required="required"> 
                                <option value="{{$arrOperationID[$no]}}">{{$arrOperation[$no]}}</option>
                                @foreach ($dataOperation as $datasOperation)
                                    <option value="{{$datasOperation->ID}}">{{$datasOperation->Description}}</option>
                                @endforeach
                            </select> 
                        </td>
                        <input type="hidden" name="level2[]" id="level2{{ $loop->iteration }}"> 
                        <input type="hidden" name="level3[]" id="level3{{ $loop->iteration }}">
                        <input type="hidden" name="level4[]" id="level4{{ $loop->iteration }}">  
                        
                    @endif
                    <td align="center" style="font-size: 13px; font-weight: bold; color: black;">{{ $datas2->Qty }}</td>
                    <td align="center" style="font-size: 13px; font-weight: bold; color: black;">{{ $datas2->Weight }}</td>

                    <td align="center" style="font-size: 13px; font-weight: bold; color: black;">{{ $datas2->WorkAllocation }}</td>
                    <td align="center" style="font-size: 13px; font-weight: bold; color: black;">{{ $datas2->LinkFreq }}</td>
                    <td align="center" style="font-size: 13px; font-weight: bold; color: black;">{{ $datas2->LinkOrd }}</td>
                    <td align="center" style="font-size: 13px; font-weight: bold; color: black;">{{ $datas2->OSW }}</td>
                    <td align="center" style="font-size: 13px; font-weight: bold; color: black;">{{ $datas2->FSW }}</td>
                    <td align="center" style="font-size: 13px; font-weight: bold; color: black;">{{ $datas2->CSW }}</td>
                    <td align="center" style="font-size: 13px; font-weight: bold; color: black;">{{ $datas2->QtyOrder }}</td>
                
                    <td align="center" style="font-size: 13px; font-weight: bold; color: black;">{{ $datas2->PDescription }}</td>
                    {{-- 
                    <td align="center" style="font-size: 13px; font-weight: bold; color: black;">{{ $datas2->Qty }}</td>
                    <td align="center" style="font-size: 13px; font-weight: bold; color: black;">{{ $datas2->Weight }}</td> 
                    --}}

                    <td align="center" style="font-size: 13px; font-weight: bold; color: black;">{{ $datas2->Note }}</td>
                    <td align="center" style="font-size: 13px; font-weight: bold; color: black;">{{ $datas2->TreeID }}</td>
                    <td align="center" style="font-size: 13px; font-weight: bold; color: black;">{{ $datas2->TreeOrd }}</td>
                    <td align="center" style="font-size: 13px; font-weight: bold; color: black;">{{ $datas2->RubberPlate }}</td>
                    <td align="center" style="font-size: 13px; font-weight: bold; color: black;">{{ $datas2->GSW }}</td>
                    <td align="center" style="font-size: 13px; font-weight: bold; color: black;">{{ $datas2->BatchNo }}</td>

                    <input type="hidden" name="fg[]" id="fg{{ $loop->iteration }}" value="{{$datas2->FG}}"> 
                    <input type="hidden" name="ordinal[]" id="ordinal{{ $loop->iteration }}" value="{{$datas2->Ordinal}}"> 
                </tr>
                @php
                    $no++;
                @endphp
                @endforeach
            </tbody>
        </table>

    @endif

@endif

</form>





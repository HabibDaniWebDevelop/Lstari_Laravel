@php
    // Data Header 
    foreach ($header as $datas) {
        $nthkoID = $datas->ID;
        $empID = $datas->Employee;
        $empSW = $datas->ESW;
        $empGroup = $datas->WorkBy;
        $proses = $datas->ODescription;
        $bagian = $datas->LDescription;
        $nthkoWA = $datas->WorkAllocation;
        $nthkoFreq = $datas->Freq;
        $carat = $datas->Carat;
        $spkoQty = $datas->TargetQty;
        $spkoWeight = number_format($datas->TargetWeight,2);
        $nthkoQty = $datas->Qty;
        $nthkoWeight = number_format($datas->Weight,2);
        $susut = number_format($datas->Shrink,2);
        $beda = number_format($datas->Difference,2);
        $nthkoLoc = $datas->Location;
    }
    $datenow = $dataAll['datenow']; // Get data as array
    foreach ($dataWAR as $datasWAR) {}
@endphp

@if($countHeader == 0)
    <div class="row">
        <div class="col-md-12">
            <center><span class="badge badge-danger" style="font-size: 20px; color: black;">NTHKO Tidak Ditemukan</span><center>
            <center><img id="showgambar" src="http://192.168.3.100:8383/image2/NO-IMAGE.jpg" style="display:none;" ></img></center>
        </div>
    </div>

@elseif($location <> $nthkoLoc)
    <div class="row">
        <div class="col-md-12">
            <center><span class="badge badge-danger" style="font-size: 20px; color: black;">NTHKO Bukan Area Anda</span><center>
            <center><img id="showgambar" src="http://192.168.3.100:8383/image2/NO-IMAGE.jpg" style="display:none;" ></img></center>
        </div>
    </div>
    
@else
    <form id="tampilform">

        <div class="row">
            <div class="col-md-10">
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group row"> {{-- class="form-group" untuk group beda row , class="form-group row" untuk group satu row --}}
                            <label class="col-md-3 col-form-label" style="font-size: 13px; font-weight: bold; color: black; text-transform: capitalize;">ID :</label>
                            <div class="col-md-2" >
                                <label style="font-weight:bold; color: blue;">{{$nthkoID}}</label>
                                <input type="hidden" id="idnthko" name="idnthko" value="{{$nthkoID}}">
                                <input type="hidden" id="wanthko" name="wanthko" value="{{$nthkoWA}}">
                                <input type="hidden" id="freqnthko" name="freqnthko" value="{{$nthkoFreq}}">
                            </div>
                            <div class="col-md-7" style="text-align: center">
                                <span>
                                    @if($datas->Active == 'A' && $datas->TransDate <= $datenow && $status_SH == TRUE)
                                        <button type="button" class="btn btn-dark btn-sm" id="btnposting" onclick="klikPosting()"><span class="tf-icons bx bx-send"></span>&nbsp; Posting</button>
                                    @else
                                        <button type="button" class="btn btn-dark btn-sm" id="btnposting" disabled><span class="tf-icons bx bx-send"></span>&nbsp; Posting</button>
                                    @endif
                                    &nbsp;&nbsp;&nbsp;
                                    <button type="button" class="btn btn-dark btn-sm" id="btncetakbarcode" onclick="klikCetakBarcodeDirect()"><span class="tf-icons bx bx-qr"></span>&nbsp; Barcode</button>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group row">
                            <div class="col-md-3"></div>
                            <div class="col-md-7">
                                @if($datas->Active == 'S')
                                    <p style="font-size: 18px; font-weight: bold; color: purple;">Disusutkan</p>
                                @elseif($datas->Active == 'C')
                                    <p style="font-size: 18px; font-weight: bold; color: purple;">Dibatalkan</p>
                                @elseif($datas->Active == 'P')
                                    <p style="font-size: 18px; font-weight: bold; color: purple;">Diposting</p>
                                @elseif($datas->Active == 'A')
                                    <p style="font-size: 18px; font-weight: bold; color: purple;">Aktif</p>
                                @else
                                    <p style="font-size: 18px; font-weight: bold; color: purple;"></p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row" style="padding-top: 0px;">
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label" style="font-size: 13px; font-weight: bold; color: black; text-transform: capitalize;">Tanggal :</label>
                            <div class="col-md-5">
                                <input class="form-control form-control-sm" type="date" style="font-size: 13px; font-weight: bold; color: black;" id="tgl" name="tgl" value={{$datas->TransDate}} readonly>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label" style="font-size: 13px; font-weight: bold; color: black; text-transform: capitalize;">No SPKO :</label>
                            <div class="col-md-4">
                                <input class="form-control form-control-sm" type="text" style="font-size: 13px; font-weight: bold; color: black;" value="{{$nthkoWA}}" readonly>
                            </div>
                            <div class="col-md-3">
                                <label style="font-weight:bold; color: blue;">{{$nthkoFreq}}</label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row" style="padding-top: 0px;">
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label" style="font-size: 13px; font-weight: bold; color: black; text-transform: capitalize;">Dikerjakan :</label>
                            <div class="col-md-2">
                                <label style="font-weight:bold; color: blue;">{{$empID}}</label>
                            </div>
                            <div class="col-md-7">
                                <label style="font-weight:bold; color: blue;">{{$empSW}}</label>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label" style="font-size: 13px; font-weight: bold; color: black; text-transform: capitalize;">Bagian :</label>
                            <div class="col-md-3">
                                <label style="font-weight:bold; color: blue;">{{$bagian}}</label>
                            </div>
                            <label class="col-md-3 col-form-label" style="font-size: 13px; font-weight: bold; color: black; text-transform: capitalize;">Kadar :</label>
                            <div class="col-md-3">
                                <label style="font-weight:bold; color: blue;">{{$carat}}</label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row" style="padding-top: 0px;">
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label" style="font-size: 13px; font-weight: bold; color: black; text-transform: capitalize;">Proses :</label>
                            <div class="col-md-4">
                                <label style="font-weight:bold; color: blue;">{{$proses}}</label>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label" style="font-size: 13px; font-weight: bold; color: black; text-transform: capitalize;">Mesin :</label>
                            <div class="col-md-4">
                                <select class="form-select form-select-sm" style="font-size: 13px; font-weight: bold; color: black;" disabled>
                                    <option value="">Pilih</option>
                                    <option value="1">1</option>
                                    <option value="2">2</option>
                                    <option value="3">3</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row" style="padding-top: 0px;">
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label" style="font-size: 13px; font-weight: bold; color: black; text-transform: capitalize;">Qty SPKO :</label>
                            <div class="col-md-3">
                                <label style="font-weight:bold; color: blue;">{{$spkoQty}}</label>
                            </div>
                            <label class="col-md-3 col-form-label" style="font-size: 13px; font-weight: bold; color: black; text-transform: capitalize;">Qty NTHKO :</label>
                            <div class="col-md-3">
                                <label style="font-weight:bold; color: blue;">{{$nthkoQty}}</label>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label" style="font-size: 13px; font-weight: bold; color: black; text-transform: capitalize;">Brt SPKO :</label>
                            <div class="col-md-3">
                                <label style="font-weight:bold; color: blue;">{{$spkoWeight}}</label>
                            </div>
                            <label class="col-md-3 col-form-label" style="font-size: 13px; font-weight: bold; color: black; text-transform: capitalize;">Brt NTHKO :</label>
                            <div class="col-md-3">
                                <label style="font-weight:bold; color: blue;">{{$nthkoWeight}}</label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row" style="padding-top: 0px;">
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label" style="font-size: 13px; font-weight: bold; color: black; text-transform: capitalize;">Susut :</label>
                            <div class="col-md-3">
                                <label style="font-weight:bold; color: blue;">{{$susut}}</label>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label" style="font-size: 13px; font-weight: bold; color: black; text-transform: capitalize;">Perbedaan :</label>
                            <div class="col-md-3">
                                <label style="font-weight:bold; color: blue;">{{$beda}}</label>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <div class="col-md-2" style="text-align: left" border="1">
                <img id="showgambar" src="http://192.168.3.100:8383/image2/NO-IMAGE.jpg" style="display:none;">
            </div>
        </div>

        <hr class="m-2" style="height:1px; color: #000000;">

        <div class="row">
            <div class="col-md-3">     
                <div class="form-group row">
                    <div class="col-md-12">
                        <input class="form-control form-control-sm" type="text" style="font-size: 13px; font-weight: bold; color: black" placeholder="Barcode Tunggal" id="barcode" name="barcode" disabled>
                    </div>
                </div>
            </div>
            <div class="col-md-9" id="detailtab">     
                <div class="btn-group pb-3" role="group">
                    <label id="btndetailnthko" class="btn btn-outline-info btn-sm" onclick="detailNTHKO()"> NTHKO</label>
                    <label id="btndetailspko" class="btn btn-outline-info btn-sm" onclick="detailSPKO()"> SPKO</label>
                    <label id="btndetailspk" class="btn btn-outline-info btn-sm" onclick="detailSPK()"> SPK</label>
                </div>
            </div>
        </div>

        <hr class="m-2" style="height:1px; color: #000000;">

            {{-- <div class="row">
                <div class="col-md-2">
                    <label class="control-label"><span style="color: black; font-weight: bold"> Jumlah SPKO </span> : <span style="color: blue; font-weight: bold">{{number_format($datasWAR->TargetQty,2)}}</span> </label>   
                </div>
                <div class="col-md-2">
                    <label class="control-label"><span style="color: black; font-weight: bold"> NTHKO </span> : <span style="color: blue; font-weight: bold">{{number_format($datasWAR->CompletionQty,2)}}</span> </label>   
                </div>
                <div class="col-md-2">
                    <label class="control-label"><span style="color: black; font-weight: bold"> Sisa </span> : <span style="color: blue; font-weight: bold">{{number_format($datasWAR->TargetQty-$datasWAR->CompletionQty,2)}}</span> </label>   
                </div>
                <div class="col-md-2">
                    <label class="control-label"><span style="color: black; font-weight: bold"> Berat SPKO </span> : <span style="color: blue; font-weight: bold">{{number_format($datasWAR->Weight,2)}}</span> </label>   
                </div>
                <div class="col-md-2">
                    <label class="control-label"><span style="color: black; font-weight: bold"> NTHKO </span> : <span style="color: blue; font-weight: bold">{{number_format($datasWAR->CompletionWeight,2)}}</span> </label>   
                </div>
                <div class="col-md-2">
                    <label class="control-label"><span style="color: black; font-weight: bold"> Sisa </span> : <span style="color: blue; font-weight: bold">{{number_format($datasWAR->Weight-$datasWAR->CompletionWeight,2)}}</span> </label>   
                </div>
            </div> --}}

            <div class="row">
                <div class="col-md-2">
                    <label class="control-label"><span style="color: black; font-weight: bold; font-size: 13px"> Jumlah SPKO </span> : <span style="color: blue; font-weight: bold">{{number_format($datasWAR->TargetQty,2)}}</span> </label>   
                </div>
                <div class="col-md-2">
                    <label class="control-label"><span style="color: black; font-weight: bold; font-size: 13px"> NTHKO </span> : <span style="color: blue; font-weight: bold">{{number_format($datasWAR->qtyNTHKO,2)}}</span> </label>   
                </div>
                <div class="col-md-2">
                    <label class="control-label"><span style="color: black; font-weight: bold; font-size: 13px"> Sisa </span> : <span style="color: blue; font-weight: bold">{{number_format($datasWAR->TargetQty-$datasWAR->qtyNTHKO,2)}}</span> </label>   
                </div>
                <div class="col-md-2">
                    <label class="control-label"><span style="color: black; font-weight: bold; font-size: 13px"> Berat SPKO </span> : <span style="color: blue; font-weight: bold">{{number_format($datasWAR->Weight,2)}}</span> </label>   
                </div>
                <div class="col-md-2">
                    <label class="control-label"><span style="color: black; font-weight: bold; font-size: 13px"> NTHKO </span> : <span style="color: blue; font-weight: bold">{{number_format($datasWAR->weightNTHKO,2)}}</span> </label>   
                </div>
                <div class="col-md-2">
                    <label class="control-label"><span style="color: black; font-weight: bold; font-size: 13px"> Sisa </span> : <span style="color: blue; font-weight: bold">{{number_format($datasWAR->Weight-$datasWAR->weightNTHKO,2)}}</span> </label>   
                </div>
            </div>

    </form>

    <hr class="m-2" style="height:1px; color: #000000;">

    {{-- <div class="row" id="detailtab">
        <div class="col-md-12">
            <div class="btn-group pb-3" role="group">
                <label id="btndetailnthko" class="btn btn-outline-primary" onclick="detailNTHKO()"> NTHKO</label>
                <label id="btndetailspko" class="btn btn-outline-primary" onclick="detailSPKO()"> SPKO</label>
                <label id="btndetailspk" class="btn btn-outline-primary" onclick="detailSPK()"> SPK</label>
            </div>
        </div>
    </div> --}}

    <form id="tampilform2">

        <div id=tampil2> 
            <table class="table table-striped text-nowrap" id="tampiltabel">
                <thead>
                    {{-- 32 Row --}}
                    <tr bgcolor="#111111">
                        <th class="text-center" style="font-size: 10px; font-weight: bold; color: white; text-transform: capitalize">Urut</th>
                        <th class="text-center" style="font-size: 10px; font-weight: bold; color: white; text-transform: capitalize">No SPK</th>
                        <th class="text-center" style="font-size: 10px; font-weight: bold; color: white; text-transform: capitalize">Produk SPK</th>
                        <th class="text-center" style="font-size: 10px; font-weight: bold; color: white; text-transform: capitalize">Jml SPK</th>
                        <th class="text-center" style="font-size: 10px; font-weight: bold; color: white; text-transform: capitalize">Barang</th>
                        <th class="text-center" style="font-size: 10px; font-weight: bold; color: white; text-transform: capitalize">Kadar</th>
                        <th class="text-center" style="font-size: 10px; font-weight: bold; color: white; text-transform: capitalize">Jml OK Data</th> 
                        <th class="text-center" style="font-size: 10px; font-weight: bold; color: white; text-transform: capitalize">Brt OK Data</th>
                        <th class="text-center" style="font-size: 10px; font-weight: bold; color: white; text-transform: capitalize">Jml Rep Data</th> 
                        <th class="text-center" style="font-size: 10px; font-weight: bold; color: white; text-transform: capitalize">Brt Rep Data</th>
                        <th class="text-center" style="font-size: 10px; font-weight: bold; color: white; text-transform: capitalize">Jml SS Data</th> 
                        <th class="text-center" style="font-size: 10px; font-weight: bold; color: white; text-transform: capitalize">Brt SS Data</th>
                        <th class="text-center" style="font-size: 10px; font-weight: bold; color: white; text-transform: capitalize">Jml OK</th> 
                        <th class="text-center" style="font-size: 10px; font-weight: bold; color: white; text-transform: capitalize">Brt OK</th>
                        <th class="text-center" style="font-size: 10px; font-weight: bold; color: white; text-transform: capitalize">Jml Rep</th> 
                        <th class="text-center" style="font-size: 10px; font-weight: bold; color: white; text-transform: capitalize">Brt Rep</th> 
                        <th class="text-center" style="font-size: 10px; font-weight: bold; color: white; text-transform: capitalize">Jml SS</th> 
                        <th class="text-center" style="font-size: 10px; font-weight: bold; color: white; text-transform: capitalize">Brt SS</th> 
                        <th class="text-center" style="font-size: 10px; font-weight: bold; color: white; text-transform: capitalize">Jml Batu Pecah</th>
                        <th class="text-center" style="font-size: 10px; font-weight: bold; color: white; text-transform: capitalize">Jml Batu Lepas</th> 
                        <th class="text-center" style="font-size: 10px; font-weight: bold; color: white; text-transform: capitalize">Barcode Note</th> 
                        <th class="text-center" style="font-size: 10px; font-weight: bold; color: white; text-transform: capitalize">Note</th> 
                        <th class="text-center" style="font-size: 10px; font-weight: bold; color: white; text-transform: capitalize">Brt Brg</th> 
                        <th class="text-center" style="font-size: 10px; font-weight: bold; color: white; text-transform: capitalize">Brt Air</th> 
                        <th class="text-center" style="font-size: 10px; font-weight: bold; color: white; text-transform: capitalize">Brt Jenis</th> 
                        <th class="text-center" style="font-size: 10px; font-weight: bold; color: white; text-transform: capitalize">No Pohon</th> 
                        <th class="text-center" style="font-size: 10px; font-weight: bold; color: white; text-transform: capitalize">Produk Detail</th> 
                        <th class="text-center" style="font-size: 10px; font-weight: bold; color: white; text-transform: capitalize">SPKO ID</th> 
                        <th class="text-center" style="font-size: 10px; font-weight: bold; color: white; text-transform: capitalize">SPKO Urut</th> 
                        <th class="text-center" style="font-size: 10px; font-weight: bold; color: white; text-transform: capitalize">Pohon ID</th> 
                        <th class="text-center" style="font-size: 10px; font-weight: bold; color: white; text-transform: capitalize">Pohon Urut</th> 
                        <th class="text-center" style="font-size: 10px; font-weight: bold; color: white; text-transform: capitalize">Batch</th> 
                    </tr>                     
                </thead>
                <tbody>
                    @foreach ($item as $datas2)
                    {{-- @php
                        $WorkOrderOK = ((!empty($super->WorkOrder)) ? $super->WorkOrder : 'NULL');
                    @endphp --}}
                    <tr onclick="tampilGambar('{{ $datas2->GPhoto }}')">
                        <td align="center" style="font-size: 12px; font-weight: bold; color: black;" readonly>{{ $loop->iteration }}</td>
                        <td align="center" style="font-size: 12px; font-weight: bold; color: black;" readonly>{{ $datas2->OSW }}</td>
                        <td align="center" style="font-size: 12px; font-weight: bold; color: black;" readonly>{{ $datas2->FDescription }}</td>
                        <td align="center" style="font-size: 12px; font-weight: bold; color: black;" readonly>{{ $datas2->QtyOrder }}</td>
                        <td align="center" style="font-size: 12px; font-weight: bold; color: black;" readonly>{{ $datas2->PDescription }}</td>
                        <td align="center" style="font-size: 12px; font-weight: bold; color: black;" readonly>{{ $datas2->FCarat }}</td>
                        <td align="center" style="font-size: 12px; font-weight: bold; color: black;" readonly>{{ $datas2->Qty }}</td>
                        <td align="center" style="font-size: 12px; font-weight: bold; color: black;" readonly>{{ $datas2->Weight }}</td>
                        <td align="center" style="font-size: 12px; font-weight: bold; color: black;" readonly>{{ $datas2->RepairQty }}</td>
                        <td align="center" style="font-size: 12px; font-weight: bold; color: black;" readonly>{{ $datas2->RepairWeight }}</td>
                        <td align="center" style="font-size: 12px; font-weight: bold; color: black;" readonly>{{ $datas2->ScrapQty }}</td>
                        <td align="center" style="font-size: 12px; font-weight: bold; color: black;" readonly>{{ $datas2->ScrapWeight }}</td>
                        <td align="center" style="font-size: 12px; font-weight: bold; color: black;" readonly>{{ $datas2->Qty }}</td>
                        <td align="center" style="font-size: 12px; font-weight: bold; color: black;" readonly>{{ $datas2->Weight }}</td>
                        <td align="center" style="font-size: 12px; font-weight: bold; color: black;" readonly>{{ $datas2->RepairQty }}</td>
                        <td align="center" style="font-size: 12px; font-weight: bold; color: black;" readonly>{{ $datas2->RepairWeight }}</td>
                        <td align="center" style="font-size: 12px; font-weight: bold; color: black;" readonly>{{ $datas2->ScrapQty }}</td>
                        <td align="center" style="font-size: 12px; font-weight: bold; color: black;" readonly>{{ $datas2->ScrapWeight }}</td>
                        <td align="center" style="font-size: 12px; font-weight: bold; color: black;" readonly>{{ $datas2->StoneLoss }}</td>
                        <td align="center" style="font-size: 12px; font-weight: bold; color: black;" readonly>{{ $datas2->QtyLossStone }}</td>
                        <td align="center" style="font-size: 12px; font-weight: bold; color: black;" readonly>{{ $datas2->BarcodeNote }}</td>
                        <td align="center" style="font-size: 12px; font-weight: bold; color: black;" readonly>{{ $datas2->Note }}</td>
                        <td align="center" style="font-size: 12px; font-weight: bold; color: black;" readonly></td>
                        <td align="center" style="font-size: 12px; font-weight: bold; color: black;" readonly></td>
                        <td align="center" style="font-size: 12px; font-weight: bold; color: black;" readonly></td>
                        <td align="center" style="font-size: 12px; font-weight: bold; color: black;" readonly>{{ $datas2->ZSW }}</td>
                        <td align="center" style="font-size: 12px; font-weight: bold; color: black;" readonly>{{ $datas2->GDescription }}</td>
                        <td align="center" style="font-size: 12px; font-weight: bold; color: black;" readonly>{{ $datas2->LinkID }}</td>
                        <td align="center" style="font-size: 12px; font-weight: bold; color: black;" readonly>{{ $datas2->LinkOrd }}</td>
                        <td align="center" style="font-size: 12px; font-weight: bold; color: black;" readonly>{{ $datas2->TreeID }}</td>
                        <td align="center" style="font-size: 12px; font-weight: bold; color: black;" readonly>{{ $datas2->TreeOrd }}</td>
                        <td align="center" style="font-size: 12px; font-weight: bold; color: black;" readonly>{{ $datas2->BatchNo }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

    </form>

@endif


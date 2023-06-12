@php
    foreach ($header as $headers){}
    $rowcount = $rowcountHeader;
@endphp

@if($rowcount == 0)
    <div class="row">
        <div class="col-md-12">
            <center><span class="badge badge-danger" style="font-size: 20px; color: black;">SPKO Tidak Ditemukan</span><center>
            <center><img id="showgambar" src="http://192.168.3.100:8383/image2/NO-IMAGE.jpg" style="display:none;"></img></center>
        </div>
    </div>

@elseif($location <> $headers->Location)
    <div class="row">
        <div class="col-md-12">
            <center><span class="badge badge-danger" style="font-size: 20px; color: black;">SPKO Bukan Area Anda</span><center>
            <center><img id="showgambar" src="http://192.168.3.100:8383/image2/NO-IMAGE.jpg" style="display:none;"></img></center>
        </div>
    </div>
    
@else
    <form id="tampilform">

        <div class="row">
            <div class="col-md-10">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label" style="font-size: 11px">ID :</label>
                            <div class="col-md-2">
                                <label style="font-weight:bold; color: blue;">{{$headers->ID}}</label>
                                <input type="hidden" id="idspko" name="idspko" value="{{$headers->ID}}">
                                <input type="hidden" id="swspko" name="swspko" value="{{$headers->SW}}">
                                <input type="hidden" id="ceknew" name="ceknew" value="0">
                                <input type="hidden" id="idlocation" name="idlocation" value="{{$headers->Location}}">
                            </div>
                            <div class="col-md-7">
                                <span>
                                    @if($headers->Active == 'A' && $headers->TransDate <= $datenow && $status_SH == TRUE)
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
                            <label class="col-md-3 col-form-label" style="font-size: 11px">Tanggal :</label>
                            <div class="col-md-4">
                                <input class="form-control" type="date" id="tgl" name="tgl" value="{{$headers->TransDate}}" readonly>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row" style="padding-top: 10px;">
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label">No SPKO :</label>
                            <div class="col-md-4">
                                <p style="font-weight: bold; color: blue;">{{$headers->SW}}</p> 
                            </div>
                            <div class="col-md-5">
                                @if($headers->Active == 'S')
                                    <p style="font-size: 20px; font-weight: bold; color: purple;">Disusutkan</p>
                                @elseif($headers->Active == 'C')
                                    <p style="font-size: 20px; font-weight: bold; color: purple;">Dibatalkan</p>
                                @elseif($headers->Active == 'P')
                                    <p style="font-size: 20px; font-weight: bold; color: purple;">Diposting</p>
                                @elseif($headers->Active == 'A')
                                    <p style="font-size: 20px; font-weight: bold; color: purple;">Aktif</p>
                                @else
                                    <p></p>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label" style="font-size: 11px">Kadar :</label>
                            <div class="col-md-4">
                                <select class="form-select" id="kadar">
                                    <option value="{{$headers->Carat}}">{{$headers->CSW}}</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row" style="padding-top: 10px;">
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label" style="font-size: 11px">Bagian :</label>
                            <div class="col-md-5">
                                <select class="form-select">
                                    <option value="{{$headers->Location}}">{{$headers->LDescription}}</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label" style="font-size: 11px">Proses :</label>
                            <div class="col-md-5">
                                <select class="form-select" name="proses" id="proses">
                                    <option value="{{$headers->Operation}}">{{$headers->ODescription}}</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row" style="padding-top: 10px;">
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label" style="font-size: 11px">Karyawan :</label>
                            <div class="col-md-4">
                                <input class="form-control" type="text" value="{{$headers->ESW}}" readonly>
                            </div>
                            <div class="col-md-4">
                                <label style="font-weight:bold; color: blue;">{{$headers->Employee}}</label>
                                <input type="hidden" id="karyawanid" name="karyawanid" value="{{$headers->Employee}}">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label" style="font-size: 11px">Group Kerja :</label>
                            <div class="col-md-2">
                                <input class="form-control" type="text" value="{{$headers->WorkGroup}}" readonly>
                            </div>
                            <div class="col-md-7">
                                <label style="font-weight:bold; color: blue; font-size: 13px">{{$headers->EGroup}}</label>
                                <input type="hidden" id="workgroupid" name="workgroupid" value="{{$headers->WorkGroup}}">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row" style="padding-top: 10px;">
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label" style="font-size: 11px">Qty SPKO :</label>
                            <div class="col-md-4">
                                <label style="font-weight:bold; color: blue;" id="qtyspkolabel">{{$headers->TargetQtyWA}}</label>
                                <input type="hidden" id="qtyspko" name="qtyspko" value="{{$headers->TargetQtyWA}}">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label" style="font-size: 11px">Berat SPKO :</label>
                            <div class="col-md-4">
                                <label style="font-weight:bold; color: blue;" id="weightspkolabel">{{number_format($headers->Weight,2)}}</label>
                                <input type="hidden" id="weightspko" name="weightspko" value="{{number_format($headers->Weight,2)}}">
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <div class="col-md-2" style="text-align: center; overflow-x:auto">
                <img id="showgambar" src="http://192.168.3.100:8383/image2/NO-IMAGE.jpg" style="display:none;">
            </div>
        </div>
    </form>

        <hr style="height:1px; color: #000000;">
        
        <div class="row">
            <div class="col-md-2">     
                <div class="form-group row">
                    <div class="col-md-12">
                        <input class="form-control" type="text" id="barcodeall" name="barcodeall" placeholder="Barcode Semua" onchange="klikBarcodeAll(this.value)" disabled>
                    </div>
                </div>
            </div>
            <div class="col-md-2">     
                <div class="form-group row">
                    <div class="col-md-12">
                        <input class="form-control" type="text" id="barcodeunit" name="barcodeunit" placeholder="Barcode Tunggal" onchange="klikBarcodeUnit(this.value)" disabled>
                    </div>
                </div>
            </div>
        </div> 

    <hr style="height:1px; color: #000000;">

    <form id="tampilform2">
        <table class="table table-hover text-nowrap" id="tampiltabel">
            <thead>
                <tr bgcolor="#111111">
                    <th class="text-center" style="font-size: 12px; font-weight: bold; color: white">Urut</th>
                    <th class="text-center" style="font-size: 12px; font-weight: bold; color: white; width: 100px;">No Terima</th>
                    <th class="text-center" style="font-size: 12px; font-weight: bold; color: white">Freq</th>
                    <th class="text-center" style="font-size: 12px; font-weight: bold; color: white">Urut</th>
                    <th class="text-center" style="font-size: 12px; font-weight: bold; color: white; width: 150px;">No SPK</th>
                    <th class="text-center" style="font-size: 12px; font-weight: bold; color: white">Produk SPK</th>
                    <th class="text-center" style="font-size: 12px; font-weight: bold; color: white; width: 70px;">Kadar</th> 
                    <th class="text-center" style="font-size: 12px; font-weight: bold; color: white">Jml SPK</th>
                    <th class="text-center" style="font-size: 12px; font-weight: bold; color: white; width: 200px;">Barang</th> 
                    <th class="text-center" style="font-size: 12px; font-weight: bold; color: white">Qty</th> 
                    <th class="text-center" style="font-size: 12px; font-weight: bold; color: white">Weight</th> 
                    <th class="text-center" style="font-size: 12px; font-weight: bold; color: white">Jumlah</th>
                    <th class="text-center" style="font-size: 12px; font-weight: bold; color: white">Berat</th>
                    <th class="text-center" style="font-size: 12px; font-weight: bold; color: white">Jml Pecah</th> 
                    <th class="text-center" style="font-size: 12px; font-weight: bold; color: white">Jml Lepas</th>
                    <th class="text-center" style="font-size: 12px; font-weight: bold; color: white">BarcodeNote</th>
                    <th class="text-center" style="font-size: 12px; font-weight: bold; color: white">Keterangan</th>
                    <th class="text-center" style="font-size: 12px; font-weight: bold; color: white">No Pohon</th> 
                    <th class="text-center" style="font-size: 12px; font-weight: bold; color: white; width: 450px;">Produk Detail</th> 
                    <th class="text-center" style="font-size: 12px; font-weight: bold; color: white">Pohon ID</th> 
                    <th class="text-center" style="font-size: 12px; font-weight: bold; color: white">Pohon Urut</th> 
                    <th class="text-center" style="font-size: 12px; font-weight: bold; color: white; width: 150px;">Batch</th> 
                </tr>                     
            </thead>
        <tbody>
                @foreach ($item as $items)
                <tr onclick="tampilGambar('{{ $items->GPhoto }}')">
                    <td align="center" style="font-size: 13px; font-weight: bold; color: black;" readonly>{{ $loop->iteration }}</td>
                    <td align="center" style="font-size: 13px; font-weight: bold; color: black;" readonly>{{ $items->LinkSW }}</td>
                    <td align="center" style="font-size: 13px; font-weight: bold; color: black;" readonly>{{ $items->LinkFreq }}</td>
                    <td align="center" style="font-size: 13px; font-weight: bold; color: black;" readonly>{{ $items->PrevOrd }}</td>
                    <td align="center" style="font-size: 13px; font-weight: bold; color: black;" readonly>{{ $items->OSW }}</td>
                    <td align="center" style="font-size: 13px; font-weight: bold; color: black;" readonly>{{ $items->FDescription }}</td>
                    <td align="center" style="font-size: 13px; font-weight: bold; color: black;" readonly>{{ $items->CSW }}</td>
                    <td align="center" style="font-size: 13px; font-weight: bold; color: black;" readonly>{{ $items->QtyOrder }}</td>
                    <td align="center" style="font-size: 13px; font-weight: bold; color: black;" readonly>{{ $items->PDescription }}</td>
                    <td align="center" style="font-size: 13px; font-weight: bold; color: black;" readonly>{{ $items->Qty }}</td>
                    <td align="center" style="font-size: 13px; font-weight: bold; color: black;" readonly>{{ $items->Weight }}</td>
                    <td align="center" style="font-size: 13px; font-weight: bold; color: black;" readonly>{{ $items->Qty }}</td>
                    <td align="center" style="font-size: 13px; font-weight: bold; color: black;" readonly>{{ $items->Weight }}</td>
                    <td align="center" style="font-size: 13px; font-weight: bold; color: black;" readonly>{{ $items->StoneLoss }}</td>
                    <td align="center" style="font-size: 13px; font-weight: bold; color: black;" readonly>{{ $items->QtyLossStone }}</td>
                    <td align="center" style="font-size: 13px; font-weight: bold; color: black;" readonly>{{ $items->BarcodeNote }}</td>
                    <td align="center" style="font-size: 13px; font-weight: bold; color: black;" readonly>{{ $items->Note }}</td>
                    <td align="center" style="font-size: 13px; font-weight: bold; color: black;" readonly>{{ $items->RubberPlate }}</td>
                    <td align="center" style="font-size: 13px; font-weight: bold; color: black;" readonly>{{ $items->GDescription }}</td>
                    <td align="center" style="font-size: 13px; font-weight: bold; color: black;" readonly>{{ $items->TreeID }}</td>
                    <td align="center" style="font-size: 13px; font-weight: bold; color: black;" readonly>{{ $items->TreeOrd }}</td>
                    <td align="center" style="font-size: 13px; font-weight: bold; color: black;" readonly>{{ $items->BatchNo }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </form>

@endif


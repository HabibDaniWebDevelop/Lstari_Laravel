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
                            <label class="col-md-3 col-form-label" style="font-size: 13px; font-weight: bold; color: black; text-transform: capitalize;">ID :</label>
                            <div class="col-md-2">
                                <label style="font-weight:bold; color: blue;">{{$headers->ID}}</label>
                                <input type="hidden" id="idspko" name="idspko" value="{{$headers->ID}}">
                                <input type="hidden" id="swspko" name="swspko" value="{{$headers->SW}}">
                                <input type="hidden" id="swspko" name="freqspko" value="{{$headers->Freq}}">
                                <input type="hidden" id="ceknew" name="ceknew" value="0">
                                <input type="hidden" id="idlocation" name="idlocation" value="{{$headers->Location}}">
                            </div>
                            <div class="col-md-7" style="text-align: center">
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
                            <label class="col-md-3 col-form-label" style="font-size: 13px; font-weight: bold; color: black; text-transform: capitalize;">Tanggal :</label>
                            <div class="col-md-4">
                                <input class="form-control-plaintext form-control-sm" type="date" id="tgl" name="tgl" value="{{$headers->TransDate}}" style="font-weight:bold; color: blue" readonly>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row" style="padding-top: 0px;">
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label" style="font-size: 13px; font-weight: bold; color: black; text-transform: capitalize;">No SPKO :</label>
                            <div class="col-md-4">
                                <p style="font-weight: bold; color: blue;">{{$headers->SW}}</p> 
                            </div>
                            <div class="col-md-5">
                                @if($headers->Active == 'S')
                                    <p style="font-size: 18px; font-weight: bold; color: purple;">Disusutkan</p>
                                @elseif($headers->Active == 'C')
                                    <p style="font-size: 18px; font-weight: bold; color: purple;">Dibatalkan</p>
                                @elseif($headers->Active == 'P')
                                    <p style="font-size: 18px; font-weight: bold; color: purple;">Diposting</p>
                                @elseif($headers->Active == 'A')
                                    <p style="font-size: 18px; font-weight: bold; color: purple;">Aktif</p>
                                @else
                                    <p></p>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label" style="font-size: 13px; font-weight: bold; color: black; text-transform: capitalize;">Kadar :</label>
                            <div class="col-md-5">
                                <select class="form-select form-select-sm" style="font-size: 13px; font-weight: bold; color: black" id="kadar">
                                    <option value="{{$headers->Carat}}">{{$headers->CSW}}</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row" style="padding-top: 0px;">
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label" style="font-size: 13px; font-weight: bold; color: black; text-transform: capitalize;">Bagian :</label>
                            <div class="col-md-5">
                                <select class="form-select form-select-sm" style="font-size: 13px; font-weight: bold; color: black">
                                    <option value="{{$headers->Location}}">{{$headers->LDescription}}</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label" style="font-size: 13px; font-weight: bold; color: black; text-transform: capitalize;">Proses :</label>
                            <div class="col-md-5">
                                <select class="form-select form-select-sm" name="proses" id="proses" style="font-size: 13px; font-weight: bold; color: black">
                                    <option value="{{$headers->Operation}}">{{$headers->ODescription}}</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row" style="padding-top: 0px;">
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label" style="font-size: 13px; font-weight: bold; color: black; text-transform: capitalize;">Karyawan :</label>
                            <div class="col-md-4">
                                <input class="form-control-plaintext form-control-sm" type="text" value="{{$headers->ESW}}" style="font-weight:bold; color: blue" readonly>
                            </div>
                            <div class="col-md-4">
                                <label style="font-weight:bold; color: blue;">{{$headers->Employee}}</label>
                                <input type="hidden" id="karyawanid" name="karyawanid" value="{{$headers->Employee}}">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label" style="font-size: 13px; font-weight: bold; color: black; text-transform: capitalize;">Freq :</label>
                            <div class="col-md-3">
                                <input class="form-control-plaintext form-control-sm" type="text" value="{{$headers->Freq}}" style="font-weight:bold; color: blue" readonly>
                            </div>
                            <div class="col-md-2">
                                <input type="hidden" id="workgroupid" name="workgroupid" value="{{$headers->WorkGroup}}">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row" style="padding-top: 0px;">
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label" style="font-size: 13px; font-weight: bold; color: black; text-transform: capitalize;">Qty SPKO :</label>
                            <div class="col-md-4">
                                <label style="font-weight:bold; color: blue;" id="qtyspkolabel">{{$headers->TargetQtyWA}}</label>
                                <input type="hidden" id="qtyspko" name="qtyspko" value="{{$headers->TargetQtyWA}}">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label" style="font-size: 13px; font-weight: bold; color: black; text-transform: capitalize;">Berat SPKO :</label>
                            <div class="col-md-4">
                                <label style="font-weight:bold; color: blue;" id="weightspkolabel">{{number_format($headers->Weight,2)}}</label>
                                <input type="hidden" id="weightspko" name="weightspko" value="{{number_format($headers->Weight,2)}}">
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <div class="col-md-2" style="text-align: center">
                <img id="showgambar" src="http://192.168.3.100:8383/image2/NO-IMAGE.jpg" style="display:none;">
            </div>
        </div>
    </form>

        <hr class="m-2" style="height:1px; color: #000000;">
        
        <div class="row">
            <div class="col-md-2">     
                <div class="form-group row">
                    <div class="col-md-12">
                        <input class="form-control form-control-sm" type="text" style="font-size: 13px; font-weight: bold; color: black" id="barcodeall" name="barcodeall" placeholder="Barcode Semua" onchange="klikBarcodeAll(this.value)" disabled>
                    </div>
                </div>
            </div>
            <div class="col-md-2">     
                <div class="form-group row">
                    <div class="col-md-12">
                        <input class="form-control form-control-sm" type="text" style="font-size: 13px; font-weight: bold; color: black" id="barcodeunit" name="barcodeunit" placeholder="Barcode Tunggal" onchange="klikBarcodeUnit(this.value)" disabled>
                    </div>
                </div>
            </div>
        </div> 

    <hr class="m-2" style="height:1px; color: #000000;">

    <form id="tampilform2">
        <table class="table table-hover text-nowrap" id="tampiltabel">
            <thead>
                <tr bgcolor="#111111">
                    <th class="text-center" style="font-size: 10px; font-weight: bold; color: white; text-transform: capitalize">Urut</th>
                    <th class="text-center" style="font-size: 10px; font-weight: bold; color: white; text-transform: capitalize">No Terima</th>
                    <th class="text-center" style="font-size: 10px; font-weight: bold; color: white; text-transform: capitalize">Freq</th>
                    <th class="text-center" style="font-size: 10px; font-weight: bold; color: white; text-transform: capitalize">Urut</th>
                    <th class="text-center" style="font-size: 10px; font-weight: bold; color: white; text-transform: capitalize; width: 100px;">No SPK</th>
                    <th class="text-center" style="font-size: 10px; font-weight: bold; color: white; text-transform: capitalize">Produk SPK</th>
                    <th class="text-center" style="font-size: 10px; font-weight: bold; color: white; text-transform: capitalize">Kadar</th> 
                    <th class="text-center" style="font-size: 10px; font-weight: bold; color: white; text-transform: capitalize">Jml SPK</th>
                    <th class="text-center" style="font-size: 10px; font-weight: bold; color: white; text-transform: capitalize; width: 150px;">Barang</th> 
                    <th class="text-center" style="font-size: 10px; font-weight: bold; color: white; text-transform: capitalize">Qty</th> 
                    <th class="text-center" style="font-size: 10px; font-weight: bold; color: white; text-transform: capitalize">Weight</th> 
                    <th class="text-center" style="font-size: 10px; font-weight: bold; color: white; text-transform: capitalize">Jumlah</th>
                    <th class="text-center" style="font-size: 10px; font-weight: bold; color: white; text-transform: capitalize">Berat</th>
                    <th class="text-center" style="font-size: 10px; font-weight: bold; color: white; text-transform: capitalize">Jml Pecah</th> 
                    <th class="text-center" style="font-size: 10px; font-weight: bold; color: white; text-transform: capitalize">Jml Lepas</th>
                    <th class="text-center" style="font-size: 10px; font-weight: bold; color: white; text-transform: capitalize">BarcodeNote</th>
                    <th class="text-center" style="font-size: 10px; font-weight: bold; color: white; text-transform: capitalize">Keterangan</th>
                    <th class="text-center" style="font-size: 10px; font-weight: bold; color: white; text-transform: capitalize">No Pohon</th> 
                    <th class="text-center" style="font-size: 10px; font-weight: bold; color: white; text-transform: capitalize; width: 450px;">Produk Detail</th> 
                    <th class="text-center" style="font-size: 10px; font-weight: bold; color: white; text-transform: capitalize">Pohon ID</th> 
                    <th class="text-center" style="font-size: 10px; font-weight: bold; color: white; text-transform: capitalize">Pohon Urut</th> 
                    <th class="text-center" style="font-size: 10px; font-weight: bold; color: white; text-transform: capitalize; width: 150px;">Batch</th> 
                </tr>                     
            </thead>
        <tbody>
                @foreach ($item as $items)
                <tr onclick="tampilGambar('{{ $items->GPhoto }}')">
                    <td align="center" style="font-size: 12px; font-weight: bold; color: black;" readonly>{{ $loop->iteration }}</td>
                    <td align="center" style="font-size: 12px; font-weight: bold; color: black;" readonly>{{ $items->LinkSW }}</td>
                    <td align="center" style="font-size: 12px; font-weight: bold; color: black;" readonly>{{ $items->LinkFreq }}</td>
                    <td align="center" style="font-size: 12px; font-weight: bold; color: black;" readonly>{{ $items->PrevOrd }}</td>
                    <td align="center" style="font-size: 12px; font-weight: bold; color: black;" readonly>{{ $items->OSW }}</td>
                    <td align="center" style="font-size: 12px; font-weight: bold; color: black;" readonly>{{ $items->FDescription }}</td>
                    <td align="center" style="font-size: 12px; font-weight: bold; color: black;" readonly>{{ $items->CSW }}</td>
                    <td align="center" style="font-size: 12px; font-weight: bold; color: black;" readonly>{{ $items->QtyOrder }}</td>
                    <td align="center" style="font-size: 12px; font-weight: bold; color: black;" readonly>{{ $items->PDescription }}</td>
                    <td align="center" style="font-size: 12px; font-weight: bold; color: black;" readonly>{{ $items->Qty }}</td>
                    <td align="center" style="font-size: 12px; font-weight: bold; color: black;" readonly>{{ $items->Weight }}</td>
                    <td align="center" style="font-size: 12px; font-weight: bold; color: black;" readonly>{{ $items->Qty }}</td>
                    <td align="center" style="font-size: 12px; font-weight: bold; color: black;" readonly>{{ $items->Weight }}</td>
                    <td align="center" style="font-size: 12px; font-weight: bold; color: black;" readonly>{{ $items->StoneLoss }}</td>
                    <td align="center" style="font-size: 12px; font-weight: bold; color: black;" readonly>{{ $items->QtyLossStone }}</td>
                    <td align="center" style="font-size: 12px; font-weight: bold; color: black;" readonly>{{ $items->BarcodeNote }}</td>
                    <td align="center" style="font-size: 12px; font-weight: bold; color: black;" readonly>{{ $items->Note }}</td>
                    <td align="center" style="font-size: 12px; font-weight: bold; color: black;" readonly>{{ $items->RubberPlate }}</td>
                    <td align="center" style="font-size: 12px; font-weight: bold; color: black;" readonly>{{ $items->GDescription }}</td>
                    <td align="center" style="font-size: 12px; font-weight: bold; color: black;" readonly>{{ $items->TreeID }}</td>
                    <td align="center" style="font-size: 12px; font-weight: bold; color: black;" readonly>{{ $items->TreeOrd }}</td>
                    <td align="center" style="font-size: 12px; font-weight: bold; color: black;" readonly>{{ $items->BatchNo }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </form>

@endif


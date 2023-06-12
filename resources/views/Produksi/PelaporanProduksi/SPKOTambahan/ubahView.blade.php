@php
    foreach ($header as $headers){}
    $rowcount = $rowcountHeader;
@endphp

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
                            <input type="hidden" id="ceknew" name="ceknew" value="2">
                            <input type="hidden" id="selscale">
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
                            <input class="form-control form-control-sm" type="date" id="tgl" name="tgl" value="{{$headers->TransDate}}" tabindex="1">
                        </div>
                    </div>
                </div>
            </div>
    
            <div class="row" style="padding-top: 5px;">
                <div class="col-md-6">
                    <div class="form-group row">
                        <label class="col-md-3 col-form-label" style="font-size: 13px; font-weight: bold; color: black; text-transform: capitalize;">No SPKO :</label>
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
                        <label class="col-md-3 col-form-label" style="font-size: 13px; font-weight: bold; color: black; text-transform: capitalize;">Kadar :</label>
                        <div class="col-md-5">
                            <select class="form-select form-select-sm" id="kadar" name="kadar" tabindex="2">
                                <option value="{{$headers->Carat}}">{{$headers->CSW}}</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="row" style="padding-top: 5px;">
                <div class="col-md-6">
                    <div class="form-group row">
                        <label class="col-md-3 col-form-label" style="font-size: 13px; font-weight: bold; color: black; text-transform: capitalize;">Bagian :</label>
                        <div class="col-md-5">
                            <select class="form-select form-select-sm">
                                <option value="{{$headers->Location}}">{{$headers->LDescription}}</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group row">
                        <label class="col-md-3 col-form-label" style="font-size: 13px; font-weight: bold; color: black; text-transform: capitalize;">Proses :</label>
                        <div class="col-md-5">
                            <select class="form-select form-select-sm" name="proses" id="proses" tabindex="3">
                                <option value="{{$headers->Operation}},{{$headers->ProsesProduct}}">{{$headers->ODescription}}</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
    
            <div class="row" style="padding-top: 5px;">
                <div class="col-md-6">
                    <div class="form-group row">
                        <label class="col-md-3 col-form-label" style="font-size: 13px; font-weight: bold; color: black; text-transform: capitalize;">Karyawan :</label>
                        <div class="col-md-3">
                            <input class="form-control-plaintext form-control-sm" style="font-weight:bold; color: blue" type="text" id="karyawan" name="karyawan" value="{{$headers->ESW}}" readonly tabindex="4">
                        </div>
                        <div class="col-md-4">
                            <label id="karyawanlabel" style="font-weight:bold; color: blue;">{{$headers->Employee}}</label>
                            <input type="hidden" id="karyawanid" name="karyawanid" value="{{$headers->Employee}}">
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group row">
                        <label class="col-md-3 col-form-label" style="font-size: 13px; font-weight: bold; color: black; text-transform: capitalize;">Keperluan :</label>
                        <div class="col-md-4">
                            <select class="form-select form-select-sm" name="keperluan" id="keperluan" style="background-color: azure" tabindex="5">
                                <option value="1">Tambah</option>
                                <option value="2">Kurang</option>
                            </select>
                        </div>
                        <label class="col-md-2 col-form-label" style="font-size: 13px; font-weight: bold; color: black; text-transform: capitalize;">Freq :</label>
                        <div class="col-md-1">
                            <label style="font-weight:bold; color: blue">{{$headers->Freq}}</label>
                        </div>
                    </div>
                </div>
            </div>
    
            <div class="row" style="padding-top: 5px;">
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
                        <div class="col-md-3">
                            <label style="font-weight:bold; color: blue;" id="weightspkolabel">{{number_format($headers->Weight,2)}}</label>
                            <input type="hidden" id="weightspko" name="weightspko" value="{{number_format($headers->Weight,2)}}">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-2" style="text-align: left">
            <img id="showgambar" src="http://192.168.3.100:8383/image2/NO-IMAGE.jpg" style="display:none;">
        </div>
    </div>
</form>

<hr class="m-2" style="height:1px; color: #000000;">

<div class="row">
    <div class="col-md-2">     
        <div class="form-group row">
            <div class="col-md-12">
                <input class="form-control form-control-sm" type="text" style="font-size: 13px; font-weight: bold; color: black;" id="barcodeall" name="barcodeall" placeholder="Barcode Semua"  onchange="klikBarcodeAll(this.value)" tabindex="6">
            </div>
        </div>
    </div>
    <div class="col-md-2">     
        <div class="form-group row">
            <div class="col-md-12">
                <input class="form-control form-control-sm" type="text" style="font-size: 13px; font-weight: bold; color: black;" id="barcodeunit" name="barcodeunit" placeholder="Barcode Tunggal" onchange="klikBarcodeUnit(this.value)" tabindex="7">
            </div>
        </div>
    </div>
    <div class="col-md-1">
        <button type="button" class="btn btn-dark btn-sm" onclick="klikAddRow()"><i class="fa fa-plus" aria-hidden="true"></i></button>
    </div>
</div> 

<hr class="m-2" style="height:1px; color: #000000;">

<form id="tampilform2">
    <table class="table text-nowrap" id="tampiltabel">
        <thead>
            <tr bgcolor="#111111"> {{-- 22 Row --}}
                <th class="text-center" style="font-size: 10px; font-weight: bold; color: white; text-transform: capitalize">Urut</th>
                <th class="text-center" style="font-size: 10px; font-weight: bold; color: white; text-transform: capitalize">Aksi</th>
                <th class="text-center" style="font-size: 10px; font-weight: bold; color: white; text-transform: capitalize">No Terima</th>
                <th class="text-center" style="font-size: 10px; font-weight: bold; color: white; text-transform: capitalize">Freq</th>
                <th class="text-center" style="font-size: 10px; font-weight: bold; color: white; text-transform: capitalize">Urut</th>
                <th class="text-center" style="font-size: 10px; font-weight: bold; color: white; text-transform: capitalize; width: 120px;">No SPK</th>
                <th class="text-center" style="font-size: 10px; font-weight: bold; color: white; text-transform: capitalize">Produk SPK</th>
                <th class="text-center" style="font-size: 10px; font-weight: bold; color: white; text-transform: capitalize">Kadar</th> 
                <th class="text-center" style="font-size: 10px; font-weight: bold; color: white; text-transform: capitalize">Jml SPK</th>
                <th class="text-center" style="font-size: 10px; font-weight: bold; color: white; text-transform: capitalize; width: 170px;">Barang</th> 
                <th class="text-center" style="font-size: 10px; font-weight: bold; color: white; text-transform: capitalize">Qty</th> 
                <th class="text-center" style="font-size: 10px; font-weight: bold; color: white; text-transform: capitalize">Weight</th> 
                <th class="text-center" style="font-size: 10px; font-weight: bold; color: white; text-transform: capitalize; width: 50px;">Jumlah</th>
                <th class="text-center" style="font-size: 10px; font-weight: bold; color: white; text-transform: capitalize; width: 100px;">Berat</th>
                <th class="text-center" style="font-size: 10px; font-weight: bold; color: white; text-transform: capitalize">Jml Pecah</th> 
                <th class="text-center" style="font-size: 10px; font-weight: bold; color: white; text-transform: capitalize">Jml Lepas</th>
                <th class="text-center" style="font-size: 10px; font-weight: bold; color: white; text-transform: capitalize; width: 200px;">BarcodeNote</th>
                <th class="text-center" style="font-size: 10px; font-weight: bold; color: white; text-transform: capitalize; width: 200px;">Keterangan</th>
                <th class="text-center" style="font-size: 10px; font-weight: bold; color: white; text-transform: capitalize">No Pohon</th> 
                <th class="text-center" style="font-size: 10px; font-weight: bold; color: white; text-transform: capitalize; width: 450px;">Produk Detail</th> 
                <th class="text-center" style="font-size: 10px; font-weight: bold; color: white; text-transform: capitalize">Pohon ID</th> 
                <th class="text-center" style="font-size: 10px; font-weight: bold; color: white; text-transform: capitalize">Pohon Urut</th> 
                <th class="text-center" style="font-size: 10px; font-weight: bold; color: white; text-transform: capitalize; width: 150px;">Batch</th> 
            </tr>                     
        </thead>
        <tbody>
            @foreach ($item as $items)
            <tr id="myRow{{$loop->iteration}}" onclick="tampilGambar('{{ $items->GPhoto }}')">
                <input type="hidden" id="WorkOrder{{$loop->iteration}}" name="WorkOrder[]" value="{{$items->WorkOrder}}">
                <input type="hidden" id="FinishGood{{$loop->iteration}}" name="FinishGood[]" value="{{$items->FDescription}}">
                <input type="hidden" id="Carat{{$loop->iteration}}" name="Carat[]" value="{{$items->CSW}}">
                <input type="hidden" id="TotalQty{{$loop->iteration}}" name="TotalQty[]" value="{{$items->QtyOrder}}">
                <input type="hidden" id="RubberPlate{{$loop->iteration}}" name="RubberPlate[]" value="{{$items->RubberPlate}}">
                <input type="hidden" id="TreeID{{$loop->iteration}}" name="TreeID[]" value="{{$items->TreeID}}">
                <input type="hidden" id="TreeOrd{{$loop->iteration}}" name="TreeOrd[]" value="{{$items->TreeOrd}}">
                <input type="hidden" id="BatchNo{{$loop->iteration}}" name="BatchNo[]" value="{{$items->BatchNo}}">
                <input type="hidden" id="PrevProcess{{$loop->iteration}}" name="PrevProcess[]" value="{{$items->PrevProcess}}">
                <input type="hidden" id="PrevOrd{{$loop->iteration}}" name="PrevOrd[]" value="{{$items->PrevOrd}}">
                <input type="hidden" id="FG{{$loop->iteration}}" name="FG[]" value="{{$items->FG}}">
                <input type="hidden" id="Part{{$loop->iteration}}" name="Part[]" value="{{$items->Part}}">
                <input type="hidden" id="RID{{$loop->iteration}}" name="RID[]" value="{{$items->Carat}}">
                <input type="hidden" id="OID{{$loop->iteration}}" name="OID[]" value="{{$items->OID}}">
                <input type="hidden" id="OOrd{{$loop->iteration}}" name="OOrd[]" value="{{$items->WorkOrderOrd}}">
                <td>{{ $loop->iteration }}</td>
                <td><button type="button" class="btn btn-danger btn-sm" onclick="remove({{$loop->iteration}})"><i class="fa fa-minus"></i></button></td>
                <td><input class="form-control-plaintext" type="text" style="text-align: center; color:black; font-size: 13px; font-weight: bold" id="WorkAllocation{{$loop->iteration}}" name="WorkAllocation[]" value="{{$items->LinkSW}}" onkeydown="handlerItem(event)" data-index="{{$loop->iteration}}01" row-index="{{$loop->iteration}}" readonly></td>
                <td><input class="form-control-plaintext" type="text" style="text-align: center; color:black; font-size: 13px; font-weight: bold" id="Freq{{$loop->iteration}}" name="Freq[]" value="{{$items->LinkFreq}}" onkeydown="handlerItem(event)" data-index="{{$loop->iteration}}02" row-index="{{$loop->iteration}}" readonly></td>
                <td><input class="form-control-plaintext" type="text" style="text-align: center; color:black; font-size: 13px; font-weight: bold" id="Ordinal{{$loop->iteration}}" name="Ordinal[]" value="{{$items->PrevOrd}}" onkeydown="handlerItem(event)" data-index="{{$loop->iteration}}03" row-index="{{$loop->iteration}}" readonly></td>
                <td><input class="form-control" type="text" spellcheck="false" autocomplete="off" style="text-align: center; color:black; font-size: 13px; font-weight: bold" id="NoSPK{{$loop->iteration}}" value="{{$items->OSW}}" onchange="cariSPK(this.value,{{$loop->iteration}},{{$headers->Carat}})" onkeydown="handlerItem(event)" data-index="{{$loop->iteration}}04" row-index="{{$loop->iteration}}"></td>
                <td><input class="form-control-plaintext" type="text" style="text-align: center; color:black; font-size: 13px; font-weight: bold" id="ProdukSPK{{$loop->iteration}}" value="{{$items->FDescription}}" onkeydown="handlerItem(event)" data-index="{{$loop->iteration}}05" row-index="{{$loop->iteration}}" readonly></td>
                <td><input class="form-control-plaintext" type="text" style="text-align: center; color:black; font-size: 13px; font-weight: bold" id="Kadar{{$loop->iteration}}" value="{{$items->CSW}}" onkeydown="handlerItem(event)" data-index="{{$loop->iteration}}06" row-index="{{$loop->iteration}}" readonly></td>
                <td><input class="form-control-plaintext" type="text" style="text-align: center; color:black; font-size: 13px; font-weight: bold" id="JmlSPK{{$loop->iteration}}" value="{{$items->QtyOrder}}" onkeydown="handlerItem(event)" data-index="{{$loop->iteration}}07" row-index="{{$loop->iteration}}" readonly></td>
                <td>
                    <input class="form-control" type="text" spellcheck="false" autocomplete="off" style="text-align: center; color:black; font-size: 13px; font-weight: bold" id="Product{{$loop->iteration}}" value="{{$items->PDescription}}" onchange="cariProduct(this.value,{{$loop->iteration}},{{$headers->Carat}})" onkeydown="handlerItem(event)" data-index="{{$loop->iteration}}08" row-index="{{$loop->iteration}}">
                    <input type="hidden" id="PID{{$loop->iteration}}" name="PID[]" value="{{$items->Product}}">
                </td>
                <td>{{ $items->Qty }}</td>
                <td>{{ $items->Weight }}</td>
                <td><input class="form-control" type="text" autocomplete="off" style="text-align: center; color:black; font-size: 13px; width: 100%; font-weight: bold" id="Qty{{$loop->iteration}}" name="Qty[]" value="{{$items->Qty}}" onchange="refresh_sum_qty({{$loop->iteration}})" onkeydown="handlerItem(event)" data-index="{{$loop->iteration}}09" row-index="{{$loop->iteration}}"></td>
                <td>
                    <div class="input-group" style="width: 100%;">
                        <input class="form-control" type="text" autocomplete="off" style="text-align: center; color:black; font-size: 13px; font-weight: bold" id="Weight{{$loop->iteration}}" name="Weight[]" value="{{$items->Weight}}" onchange="refresh_sum_weight({{$loop->iteration}})" onkeydown="handlerItem(event)" data-index="{{$loop->iteration}}10" row-index="{{$loop->iteration}}">
                        <button type="button" class="btn btn-info btn-sm" onclick="kliktimbang('Weight{{$loop->iteration}}')"><i class="fa fa-balance-scale"></i></button>
                    </div>
                </td>
                <td><input class="form-control" type="text" autocomplete="off" style="text-align: center; color:black; font-size: 13px; width: 100%; font-weight: bold" id="StoneLoss{{$loop->iteration}}" name="StoneLoss[]" value="{{$items->StoneLoss}}" onkeydown="handlerItem(event)" data-index="{{$loop->iteration}}11" row-index="{{$loop->iteration}}"></td>
                <td><input class="form-control" type="text" autocomplete="off" style="text-align: center; color:black; font-size: 13px; width: 100%; font-weight: bold" id="QtyLossStone{{$loop->iteration}}" name="QtyLossStone[]" value="{{$items->QtyLossStone}}" onkeydown="handlerItem(event)" data-index="{{$loop->iteration}}12" row-index="{{$loop->iteration}}"></td>
                <td><input class="form-control" type="text" autocomplete="off" style="text-align: center; color:black; font-size: 13px; width: 100%; font-weight: bold" id="BarcodeNote{{$loop->iteration}}" name="BarcodeNote[]" value="{{$items->BarcodeNote}}" onchange="notecopy({{$loop->iteration}})" onkeydown="handlerItem(event)" data-index="{{$loop->iteration}}13" row-index="{{$loop->iteration}}"></td>
                <td><input class="form-control" type="text" autocomplete="off" style="text-align: center; color:black; font-size: 13px; width: 100%; font-weight: bold" id="Note{{$loop->iteration}}" name="Note[]" value="{{$items->Note}}" onkeydown="handlerItem(event)" data-index="{{$loop->iteration}}14" row-index="{{$loop->iteration}}"></td>
                <td><input class="form-control-plaintext" type="text" style="text-align: center; color:black; font-size: 13px; font-weight: bold" id="NoPohon{{$loop->iteration}}" value="{{$items->RubberPlate}}" onkeydown="handlerItem(event)" data-index="{{$loop->iteration}}15" row-index="{{$loop->iteration}}" readonly></td>
                <td><input class="form-control-plaintext" type="text" style="text-align: center; color:black; font-size: 13px; font-weight: bold" id="ProdukDetail{{$loop->iteration}}" value="{{$items->GDescription}}" onkeydown="handlerItem(event)" data-index="{{$loop->iteration}}16" row-index="{{$loop->iteration}}" readonly></td>
                <td><input class="form-control-plaintext" type="text" style="text-align: center; color:black; font-size: 13px; font-weight: bold" id="PohonID{{$loop->iteration}}" value="{{$items->TreeID}}" onkeydown="handlerItem(event)" data-index="{{$loop->iteration}}17" row-index="{{$loop->iteration}}" readonly></td>
                <td><input class="form-control-plaintext" type="text" style="text-align: center; color:black; font-size: 13px; font-weight: bold" id="PohonUrut{{$loop->iteration}}" value="{{$items->TreeOrd}}" onkeydown="handlerItem(event)" data-index="{{$loop->iteration}}18" row-index="{{$loop->iteration}}" readonly></td>
                <td><input class="form-control-plaintext" type="text" style="text-align: center; color:black; font-size: 13px; font-weight: bold" id="Batch{{$loop->iteration}}" value="{{$items->BatchNo}}" onkeydown="handlerItem(event)" data-index="{{$loop->iteration}}19" row-index="{{$loop->iteration}}" readonly></td>
            </tr> 
            @endforeach
        </tbody>
    </table>
</form>

<script>
    $(document).on('keypress', 'input,textarea,select', function(e) {
        if (e.which == 13) {
    
            var posisi = parseFloat($(this).attr('tabindex')) + 1;
            $('[tabindex="' + posisi + '"]').focus();
    
            if (posisi != '7' && posisi != '8') {
                e.preventDefault();
            }
        }
    });
</script>



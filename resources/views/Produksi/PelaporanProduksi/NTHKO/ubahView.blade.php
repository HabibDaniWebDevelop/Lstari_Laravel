@php
    foreach ($data as $datas) {}
    $datenow = $dataAll['datenow']; // Get data as array
@endphp

<form id="tampilform">
   
    <div class="row">
        <div class="col-md-10">

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group row">
                        <label class="col-md-3 col-form-label" style="font-size: 13px; font-weight: bold; color: black; text-transform: capitalize;">ID :</label>
                        <div class="col-md-2" >
                            <label style="font-weight: bold; color: blue;">{{$datas->ID}}</label>
                            <input type="hidden" id="idnthko" name="idnthko" value="{{$datas->ID}}">
                            <input type="hidden" id="cekstatus" name="cekstatus" value="2">
                            <input type="hidden" id="productlist" name="productlist" value="">
                            <input type="hidden" id="selscale"> {{-- Hidden input for timbangan --}}
                        </div>
                        <div class="col-md-7" style="text-align: center">
                            <span>
                                @if($datas->Active == 'A' && $datas->TransDate <= $datenow && $status_SH == TRUE)
                                    <button type="button" class="btn btn-dark btn-sm" id="btnposting" onclick="()"><span class="tf-icons bx bx-send"></span>&nbsp; Posting</button>
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
                            <input class="form-control form-control-sm" type="date" style="font-size: 13px; font-weight: bold; color: black;" id="tgl" name="tgl" value="{{$datas->TransDate}}">
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group row">
                        <label class="col-md-3 col-form-label" style="font-size: 13px; font-weight: bold; color: black; text-transform: capitalize;">No SPKO :</label>
                        <div class="col-md-4">
                            <input class="form-control form-control-sm" type="text" style="font-size: 13px; font-weight: bold; color: black;" id="swspko" name="swspko" value="{{$datas->WorkAllocation}}" readonly>
                        </div>
                        <div class="col-md-3">
                            <label style="font-weight:bold; color: blue;">{{$datas->Freq}}</label>
                            <input type="hidden" id="nthkofreq" name="nthkofreq" value="{{$datas->Freq}}">
                        </div>
                    </div>
                </div>
            </div>

            <div class="row" style="padding-top: 0px;">
                <div class="col-md-6">
                    <div class="form-group row">
                        <label class="col-md-3 col-form-label" style="font-size: 13px; font-weight: bold; color: black; text-transform: capitalize;">Dikerjakan :</label>
                        <div class="col-md-2">
                            <label style="font-weight: bold; color: blue;">{{$datas->Employee}}</label>
                            <input type="hidden" id="emp" name="emp" value="{{$datas->Employee}}" >
                        </div>
                        <div class="col-md-7">
                            <label style="font-weight: bold; color: blue; font-size: 13px">{{$datas->WorkBy}}</label>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group row">
                        <label class="col-md-3 col-form-label" style="font-size: 13px; font-weight: bold; color: black; text-transform: capitalize;">Bagian :</label>
                        <div class="col-md-3">
                            <label style="font-weight: bold; color: blue;">{{$datas->LDescription}}</label>
                            <input type="hidden" id="bagian" name="bagian" value="{{$datas->Location}}" >
                        </div>
                        <label class="col-md-3 col-form-label" style="font-size: 13px; font-weight: bold; color: black; text-transform: capitalize;">Kadar :</label>
                        <div class="col-md-3">
                            <label style="font-weight: bold; color: blue;">{{$datas->Carat}}</label>
                            <input type="hidden" id="kadar" name="kadar" value="{{$datas->RID}}" >
                        </div>
                    </div>
                </div>
            </div>

            <div class="row" style="padding-top: 0px;">
                <div class="col-md-6">
                    <div class="form-group row">
                        <label class="col-md-3 col-form-label" style="font-size: 13px; font-weight: bold; color: black; text-transform: capitalize;">Proses :</label>
                        <div class="col-md-4">
                            <label style="font-weight: bold; color: blue;">{{$datas->ODescription}}</label>
                            <input type="hidden" id="proses" name="proses" value="{{$datas->Operation}}" >
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
                            <label style="font-weight: bold; color: blue;">{{$datas->TargetQty}}</label>
                            <input type="hidden" id="qtyspko" name="qtyspko" value="{{$datas->TargetQty}}" >
                        </div>
                        <label class="col-md-3 col-form-label" style="font-size: 13px; font-weight: bold; color: black; text-transform: capitalize;">Qty NTHKO :</label>
                        <div class="col-md-3">
                            <label id="qtynthkolabel" style="font-weight: bold; color: blue;">{{$datas->Qty}}</label>
                            <input type="hidden" id="qtynthko" name="qtynthko" value="{{$datas->Qty}}" >
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group row">
                        <label class="col-md-3 col-form-label" style="font-size: 13px; font-weight: bold; color: black; text-transform: capitalize;">Berat SPKO :</label>
                        <div class="col-md-3">
                            <label style="font-weight: bold; color: blue;">{{number_format($datas->TargetWeight,2)}}</label>
                            <input type="hidden" id="weightspko" name="weightspko" value="{{number_format($datas->TargetWeight,2)}}" >
                        </div>
                        <label class="col-md-3 col-form-label" style="font-size: 13px; font-weight: bold; color: black; text-transform: capitalize;">Berat NTHKO :</label>
                        <div class="col-md-3">
                            <label id="weightnthkolabel" style="font-weight: bold; color: blue;">{{number_format($datas->Weight,2)}}</label>
                            <input type="hidden" id="weightnthko" name="weightnthko" value="{{number_format($datas->Weight,2)}}" >
                        </div>
                    </div>
                </div>
            </div>

            <div class="row" style="padding-top: 0px;">
                <div class="col-md-6">
                    <div class="form-group row">
                        <label class="col-md-3 col-form-label" style="font-size: 13px; font-weight: bold; color: black; text-transform: capitalize;">Susut :</label>
                        <div class="col-md-3">
                            <label style="font-weight: bold; color: blue;">{{number_format($datas->Shrink,2)}}</label>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group row">
                        <label class="col-md-3 col-form-label" style="font-size: 13px; font-weight: bold; color: black; text-transform: capitalize;">Perbedaan :</label>
                        <div class="col-md-3">
                            <label style="font-weight: bold; color: blue;">{{number_format($datas->Difference,2)}}</label>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <div class="col-md-2" style="text-align: left">
            <img id="showgambar" src="http://192.168.3.100:8383/image2/NO-IMAGE.jpg" style="display:none;">
        </div>
    </div>

    <hr class="m-2" style="height:1px; color: #000000;">

    <div class="row">
        <div class="col-md-3">     
            <div class="form-group row">
                <div class="col-md-12">
                    <input class="form-control form-control-sm" type="text" id="barcodeunit" placeholder="Barcode Tunggal" name="barcodeunit" onchange="klikBarcodeUnit()">
                </div>
            </div>
        </div>
        <div class="col-md-1">
            <button type="button" class="btn btn-dark btn-sm" onclick="klikAddRow()"><i class="fa fa-plus" aria-hidden="true"></i></button>
        </div>
    </div>

</form>

<hr class="m-2" style="height:1px; color: #000000;">

<form id="tampilform2">
    <table class="table text-nowrap" id="tampiltabel">
        <thead>
            {{-- 32 Row --}}
            <tr bgcolor="#111111">
                <th class="text-center" style="font-size: 10px; font-weight: bold; color: white; text-transform: capitalize">Urut</th>
                <th class="text-center" style="font-size: 10px; font-weight: bold; color: white; text-transform: capitalize">Aksi</th>
                <th class="text-center" style="font-size: 10px; font-weight: bold; color: white; text-transform: capitalize; width: 100px;">No SPK</th>
                <th class="text-center" style="font-size: 10px; font-weight: bold; color: white; text-transform: capitalize">Produk SPK</th>
                <th class="text-center" style="font-size: 10px; font-weight: bold; color: white; text-transform: capitalize">Jml SPK</th>
                <th class="text-center" style="font-size: 10px; font-weight: bold; color: white; text-transform: capitalize; width: 150px;">Barang</th>
                <th class="text-center" style="font-size: 10px; font-weight: bold; color: white; text-transform: capitalize; width: 70px;">Kadar</th>
                <th class="text-center" style="font-size: 10px; font-weight: bold; color: white; text-transform: capitalize">Jml OK Data</th> 
                <th class="text-center" style="font-size: 10px; font-weight: bold; color: white; text-transform: capitalize">Brt OK Data</th>
                <th class="text-center" style="font-size: 10px; font-weight: bold; color: white; text-transform: capitalize">Jml Rep Data</th> 
                <th class="text-center" style="font-size: 10px; font-weight: bold; color: white; text-transform: capitalize">Brt Rep Data</th>
                <th class="text-center" style="font-size: 10px; font-weight: bold; color: white; text-transform: capitalize">Jml SS Data</th> 
                <th class="text-center" style="font-size: 10px; font-weight: bold; color: white; text-transform: capitalize">Brt SS Data</th>
                <th class="text-center" style="font-size: 10px; font-weight: bold; color: white; text-transform: capitalize; width: 50px;">Jml OK</th> 
                <th class="text-center" style="font-size: 10px; font-weight: bold; color: white; text-transform: capitalize; width: 100px;">Brt OK</th>
                <th class="text-center" style="font-size: 10px; font-weight: bold; color: white; text-transform: capitalize; width: 50px;">Jml Rep</th> 
                <th class="text-center" style="font-size: 10px; font-weight: bold; color: white; text-transform: capitalize; width: 100px;">Brt Rep</th> 
                <th class="text-center" style="font-size: 10px; font-weight: bold; color: white; text-transform: capitalize; width: 50px;">Jml SS</th> 
                <th class="text-center" style="font-size: 10px; font-weight: bold; color: white; text-transform: capitalize; width: 100px;">Brt SS</th> 
                <th class="text-center" style="font-size: 10px; font-weight: bold; color: white; text-transform: capitalize">Jml Batu Pecah</th>
                <th class="text-center" style="font-size: 10px; font-weight: bold; color: white; text-transform: capitalize">Jml Batu Lepas</th> 
                <th class="text-center" style="font-size: 10px; font-weight: bold; color: white; text-transform: capitalize; width: 200px">Barcode Note</th> 
                <th class="text-center" style="font-size: 10px; font-weight: bold; color: white; text-transform: capitalize; width: 200px">Note</th> 
                <th class="text-center" style="font-size: 10px; font-weight: bold; color: white; text-transform: capitalize">Brt Brg</th> 
                <th class="text-center" style="font-size: 10px; font-weight: bold; color: white; text-transform: capitalize">Brt Air</th> 
                <th class="text-center" style="font-size: 10px; font-weight: bold; color: white; text-transform: capitalize">Brt Jenis</th> 
                <th class="text-center" style="font-size: 10px; font-weight: bold; color: white; text-transform: capitalize">No Pohon</th> 
                <th class="text-center" style="font-size: 10px; font-weight: bold; color: white; text-transform: capitalize; width: 450px;">Produk Detail</th> 
                <th class="text-center" style="font-size: 10px; font-weight: bold; color: white; text-transform: capitalize">SPKO ID</th> 
                <th class="text-center" style="font-size: 10px; font-weight: bold; color: white; text-transform: capitalize">SPKO Urut</th> 
                <th class="text-center" style="font-size: 10px; font-weight: bold; color: white; text-transform: capitalize">Pohon ID</th> 
                <th class="text-center" style="font-size: 10px; font-weight: bold; color: white; text-transform: capitalize">Pohon Urut</th> 
                <th class="text-center" style="font-size: 10px; font-weight: bold; color: white; text-transform: capitalize; width: 150px;">Batch</th> 
            </tr>                     
        </thead>
        <tbody>
        
            @foreach ($data2 as $super)

            <tr id="myRow{{$loop->iteration}}" onclick="tampilGambar('{{ $super->Photo }}')">
                <input type="hidden" id="WorkOrder{{$loop->iteration}}" name="WorkOrder[]" value="{{$super->WorkOrder}}">
                <input type="hidden" id="Carat{{$loop->iteration}}" name="Carat[]" value="{{$super->Carat}}">
                <input type="hidden" id="LinkID{{$loop->iteration}}" name="LinkID[]" value="{{$super->LinkID}}">
                <input type="hidden" id="LinkOrd{{$loop->iteration}}" name="LinkOrd[]" value="{{$super->LinkOrd}}">
                <input type="hidden" id="TreeID{{$loop->iteration}}" name="TreeID[]" value="{{$super->TreeID}}">
                <input type="hidden" id="TreeOrd{{$loop->iteration}}" name="TreeOrd[]" value="{{$super->TreeOrd}}">
                <input type="hidden" id="Part{{$loop->iteration}}" name="Part[]" value="{{$super->Part}}">
                <input type="hidden" id="FG{{$loop->iteration}}" name="FG[]" value="{{$super->FG}}">
                <input type="hidden" id="BatchNo{{$loop->iteration}}" name="BatchNo[]" value="{{$super->BatchNo}}">
                <input type="hidden" id="OOrd{{$loop->iteration}}" name="OOrd[]" value="{{$super->WorkOrderOrd}}">
                <td>{{ $loop->iteration }}</td>
                <td><button type="button" class="btn btn-danger btn-sm" onclick="remove({{$loop->iteration}})"><i class="fa fa-minus"></i></button></td>
                <td><input class="form-control" type="text" spellcheck="false" autocomplete="off" style="text-align: center; color:black; font-size: 13px; font-weight: bold" id="NoSPK{{$loop->iteration}}" value="{{$super->OSW}}" onchange="cariSPK(this.value,{{$loop->iteration}},{{$datas->RID}})" onkeydown="handlerItem(event)" data-index="{{$loop->iteration}}01" row-index="{{$loop->iteration}}"></td>
                <td><input class="form-control-plaintext" type="text" style="text-align: center; color:black; font-size: 13px; font-weight: bold" id="ProdukSPK{{$loop->iteration}}" value="{{$super->FDescription}}" onkeydown="handlerItem(event)" data-index="{{$loop->iteration}}02" row-index="{{$loop->iteration}}" readonly></td>
                <td><input class="form-control-plaintext" type="text" style="text-align: center; color:black; font-size: 13px; font-weight: bold" id="JmlSPK{{$loop->iteration}}" value="{{$super->QtyOrder}}" onkeydown="handlerItem(event)" data-index="{{$loop->iteration}}03" row-index="{{$loop->iteration}}" readonly></td>
                <td>
                    <input class="form-control" type="text" spellcheck="false" autocomplete="off" style="text-align: center; color:black; font-size: 13px; font-weight: bold" id="ProductLabel{{$loop->iteration}}" value="{{$super->PDescription}}" onchange="cariProduct(this.value,{{$loop->iteration}},{{$datas->RID}})" onkeydown="handlerItem(event)" data-index="{{$loop->iteration}}04" row-index="{{$loop->iteration}}">
                    <input type="hidden" id="Product{{$loop->iteration}}" name="Product[]" value="{{$super->Product}}">
                </td>
                <td><input class="form-control-plaintext" type="text" autocomplete="off" style="text-align: center; color:black; font-size: 13px; font-weight: bold" id="Kadar{{$loop->iteration}}" value="{{$super->FCarat}}" onkeydown="handlerItem(event)" data-index="{{$loop->iteration}}05" row-index="{{$loop->iteration}}" readonly></td>
                <td>{{ $super->Qty }}</td>
                <td>{{ $super->Weight }}</td>
                <td>{{ $super->RepairQty }}</td>
                <td>{{ $super->RepairWeight }}</td>
                <td>{{ $super->ScrapQty }}</td>
                <td>{{ $super->ScrapWeight }}</td>
                <td><input class="form-control" type="text" autocomplete="off" style="text-align: center; color:black; font-size: 13px; width: 100%; font-weight: bold" id="Qty{{$loop->iteration}}" name="Qty[]" value="{{$super->Qty}}" onchange="refresh_sum_qty({{$loop->iteration}})" onkeydown="handlerItem(event)" data-index="{{$loop->iteration}}06" row-index="{{$loop->iteration}}"></td>
                <td>
                    <div class="input-group" style="width: 100%;">
                        <input class="form-control" type="text" autocomplete="off" style="text-align: center; color:black; font-size: 13px; font-weight: bold" id="Weight{{$loop->iteration}}" name="Weight[]" value="{{$super->Weight}}" onchange="refresh_sum_weight({{$loop->iteration}})" onkeydown="handlerItem(event)" data-index="{{$loop->iteration}}07" row-index="{{$loop->iteration}}">
                        <button type="button" class="btn btn-info btn-sm" onclick="klikTimbangRunOK('Weight{{$loop->iteration}}',{{$loop->iteration}})"><i class="fa fa-balance-scale"></i></button>
                    </div>
                </td>
                <td><input class="form-control" type="text" autocomplete="off" style="text-align: center; color:black; font-size: 13px; width: 100%; font-weight: bold" id="RepairQty{{$loop->iteration}}" name="RepairQty[]" value="{{$super->RepairQty}}" onchange="refresh_sum_qty({{$loop->iteration}})" onkeydown="handlerItem(event)" data-index="{{$loop->iteration}}08" row-index="{{$loop->iteration}}"></td>
                <td>
                    <div class="input-group" style="width: 100%;">
                        <input class="form-control" type="text" autocomplete="off" style="text-align: center; color:black; font-size: 13px; font-weight: bold" id="RepairWeight{{$loop->iteration}}" name="RepairWeight[]" value="{{$super->RepairWeight}}" onchange="refresh_sum_weight({{$loop->iteration}})" onkeydown="handlerItem(event)" data-index="{{$loop->iteration}}09" row-index="{{$loop->iteration}}">
                        <button type="button" class="btn btn-info btn-sm" onclick="klikTimbangRunRep('RepairWeight{{$loop->iteration}}',{{$loop->iteration}})"><i class="fa fa-balance-scale"></i></button>
                    </div>
                </td>
                <td><input class="form-control" type="text" autocomplete="off" style="text-align: center; color:black; font-size: 13px; width: 100%; font-weight: bold" id="ScrapQty{{$loop->iteration}}" name="ScrapQty[]" value="{{$super->ScrapQty}}" onchange="refresh_sum_qty({{$loop->iteration}})" onkeydown="handlerItem(event)" data-index="{{$loop->iteration}}10" row-index="{{$loop->iteration}}"></td>
                <td>
                    <div class="input-group" style="width: 100%;">
                        <input class="form-control" type="text" autocomplete="off" style="text-align: center; color:black; font-size: 13px; font-weight: bold" id="ScrapWeight{{$loop->iteration}}" name="ScrapWeight[]" value="{{$super->ScrapWeight}}" onchange="refresh_sum_weight({{$loop->iteration}})" onkeydown="handlerItem(event)" data-index="{{$loop->iteration}}11" row-index="{{$loop->iteration}}">
                        <button type="button" class="btn btn-info btn-sm" onclick="klikTimbangRunSS('ScrapWeight{{$loop->iteration}}',{{$loop->iteration}})"><i class="fa fa-balance-scale"></i></button>
                    </div>
                </td>
                <td><input class="form-control" type="text" autocomplete="off" style="text-align: center; color:black; font-size: 13px; width: 100%; font-weight: bold" id="StoneLoss{{$loop->iteration}}" name="StoneLoss[]" value="{{$super->StoneLoss}}" onkeydown="handlerItem(event)" data-index="{{$loop->iteration}}12" row-index="{{$loop->iteration}}"></td>
                <td><input class="form-control" type="text" autocomplete="off" style="text-align: center; color:black; font-size: 13px; width: 100%; font-weight: bold" id="QtyLossStone{{$loop->iteration}}" name="QtyLossStone[]" value="{{$super->QtyLossStone}}" onkeydown="handlerItem(event)" data-index="{{$loop->iteration}}13" row-index="{{$loop->iteration}}"></td>
                <td><input class="form-control" type="text" autocomplete="off" style="text-align: center; color:black; font-size: 13px; width: 100%; font-weight: bold" id="BarcodeNote{{$loop->iteration}}" name="BarcodeNote[]" value="{{$super->BarcodeNote}}" onchange="notecopy({{$loop->iteration}})" onkeydown="handlerItem(event)" data-index="{{$loop->iteration}}14" row-index="{{$loop->iteration}}"></td>
                <td><input class="form-control" type="text" autocomplete="off" style="text-align: center; color:black; font-size: 13px; width: 100%; font-weight: bold" id="Note{{$loop->iteration}}" name="Note[]" value="{{$super->Note}}" onkeydown="handlerItem(event)" data-index="{{$loop->iteration}}15" row-index="{{$loop->iteration}}"></td>
                <td><input class="form-control-plaintext" type="text" style="text-align: center; color:black; font-size: 13px; font-weight: bold" id="BrtBrg{{$loop->iteration}}" onkeydown="handlerItem(event)" data-index="{{$loop->iteration}}16" row-index="{{$loop->iteration}}" readonly></td>
                <td><input class="form-control-plaintext" type="text" style="text-align: center; color:black; font-size: 13px; font-weight: bold" id="BrtAir{{$loop->iteration}}" onkeydown="handlerItem(event)" data-index="{{$loop->iteration}}17" row-index="{{$loop->iteration}}" readonly></td>
                <td><input class="form-control-plaintext" type="text" style="text-align: center; color:black; font-size: 13px; font-weight: bold" id="BrtJenis{{$loop->iteration}}" onkeydown="handlerItem(event)" data-index="{{$loop->iteration}}18" row-index="{{$loop->iteration}}" readonly></td>
                <td><input class="form-control-plaintext" type="text" style="text-align: center; color:black; font-size: 13px; font-weight: bold" id="NoPohon{{$loop->iteration}}" value="{{$super->ZSW}}" onkeydown="handlerItem(event)" data-index="{{$loop->iteration}}19" row-index="{{$loop->iteration}}" readonly></td>
                <td><input class="form-control-plaintext" type="text" style="text-align: center; color:black; font-size: 13px; font-weight: bold" id="ProdukDetail{{$loop->iteration}}" value="{{$super->GDescription}}" onkeydown="handlerItem(event)" data-index="{{$loop->iteration}}20" row-index="{{$loop->iteration}}" readonly></td>
                <td><input class="form-control-plaintext" type="text" style="text-align: center; color:black; font-size: 13px; font-weight: bold" id="SPKOID{{$loop->iteration}}" value="{{$super->LinkID}}" onkeydown="handlerItem(event)" data-index="{{$loop->iteration}}21" row-index="{{$loop->iteration}}" readonly></td>
                <td><input class="form-control-plaintext" type="text" style="text-align: center; color:black; font-size: 13px; font-weight: bold" id="SPKOUrut{{$loop->iteration}}" value="{{$super->LinkOrd}}" onkeydown="handlerItem(event)" data-index="{{$loop->iteration}}22" row-index="{{$loop->iteration}}" readonly></td>
                <td><input class="form-control-plaintext" type="text" style="text-align: center; color:black; font-size: 13px; font-weight: bold" id="PohonID{{$loop->iteration}}" value="{{$super->TreeID}}" onkeydown="handlerItem(event)" data-index="{{$loop->iteration}}23" row-index="{{$loop->iteration}}" readonly></td>
                <td><input class="form-control-plaintext" type="text" style="text-align: center; color:black; font-size: 13px; font-weight: bold" id="PohonUrut{{$loop->iteration}}" value="{{$super->TreeOrd}}" onkeydown="handlerItem(event)" data-index="{{$loop->iteration}}24" row-index="{{$loop->iteration}}" readonly></td>
                <td><input class="form-control-plaintext" type="text" style="text-align: center; color:black; font-size: 13px; font-weight: bold" id="Batch{{$loop->iteration}}" value="{{$super->BatchNo}}" onkeydown="handlerItem(event)" data-index="{{$loop->iteration}}25" row-index="{{$loop->iteration}}" readonly></td>
            </tr>
            @endforeach
        </tbody>
    </table>
</form>

<script>
    $(document).ready(function() {
        $('.myselect').select2();
    });
</script>


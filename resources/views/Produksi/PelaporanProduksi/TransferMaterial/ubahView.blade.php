@php
    foreach ($data as $datas){}
    foreach ($item as $items){}
    // dd($items);
@endphp

<form id="tampilform">

    <div class="row">

        <div class="col-md-6 mb-2">
            <div class="form-group">
                <label class="form-label">ID</label>
                <input type="text" class="form-control" style="background-color:transparent" name="idtm" id="idtm" value="{{$datas->ID}}" readonly>
                <input type="hidden" id="cekstatus" name="cekstatus" value="2">
                <input type="hidden" id="cekspk" name="cekspk" value="">
                <input type="hidden" id="selscale">
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                <label class="form-label">Catatan</label>
                <input type="text" class="form-control" name="note" id="note" value="{{$datas->Remarks}}" tabindex="1">
            </div>
        </div>

        <div class="col-md-6 mb-2">
            <div class="form-group">
                <label class="form-label">Tanggal</label>
                <input type="date" class="form-control" id="tgl" name="tgl" value="{{$datas->TransDate}}" tabindex="2">
            </div>
        </div>

        <div class="col-md-3">
            <div class="form-group">
                <label class="form-label">Penerima</label>
                <input type="text" class="form-control" spellcheck="false" name="karyawan" id="karyawan" value="{{$datas->ESW}}" onChange="cariKaryawan(this.value)" tabindex="3">
                <input type="hidden" id="karyawanid" name="karyawanid" value="{{$datas->Employee}}">
            </div>
        </div>

        <div class="col-md-3">
            <div class="form-group">
                <label class="form-label" style="color: white">Penerima</label><br>
                <label style="font-weight:bold; color: blue;" id="karyawanlabel" name="karyawanlabel">{{$datas->Employee}}</label>
            </div>
        </div>

        <div class="col-md-6 mb-2">
            <div class="form-group">
                <label class="form-label">Dari Bagian</label>
                <select class="form-select" name="daribagian" id="daribagian" tabindex="4">
                    <option value="{{$datas->FromLoc}}">{{$datas->FDescription}}</option>
                </select>
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                <label class="form-label">Ke Bagian</label>
                <select class="form-select" name="kebagian" id="kebagian" tabindex="5">
                    <option value="{{$datas->ToLoc}}">{{$datas->ODescription}}</option>
                    @foreach ($area as $areas)
                    <option value="{{$areas->ID}}">{{$areas->Description}}</option>
                    @endforeach
                </select>
            </div>
        </div>

    </div>

</form>

<hr style="height:1px; color: #000000;">
<div class="row">
    <div class="col-md-3">
        <label class="control-label">Entry Date : <span style="color: blue; font-weight: bold" id="entrydate" value=""></span></label>   
    </div>
    <div class="col-md-3">
        <label class="control-label">User : <span style="color: blue; font-weight: bold" id="user" value=""></span></label>   
    </div>
    <div class="col-md-3">
        <label class="control-label">Total Jumlah : <span style="color: blue; font-weight: bold" id="totjumlah" value=""></span></label>   
    </div>
    <div class="col-md-3">
        <label class="control-label">Total Berat : <span style="color: blue; font-weight: bold" id="totberat" value=""></span></label>   
    </div>
</div>

<div class="row" style="margin-top: 10px">
    <div class="col-md-2">
        <div class="form-group row">
            <div class="col-md-12">
                <input class="form-control" type="text" id="barcodeunit" name="barcodeunit" placeholder="Barcode NTHKO" onchange="klikBarcodeUnit()" tabindex="6">
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="form-group row">
            <div class="col-md-12">
                <input class="form-control" type="text" id="barcodekomponen" name="barcodekomponen" placeholder="Barcode Komponen" onchange="klikBarcodeKomponen()" tabindex="7">
            </div>
        </div>
    </div>
    <div class="col-md-1">
        <button type="button" class="btn btn-dark btn-sm" onclick="klikAddRow()"><i class="fa fa-plus" aria-hidden="true"></i></button>
    </div>
</div>
<hr style="height:1px; color: #000000;">

<form id="tampilform2">
    <table class="table text-nowrap" id="tampiltabel">
        <thead style="display: table-header-group;">
            <tr bgcolor="#111111">
                <th class="text-center" style="font-size: 10px; font-weight: bold; color: white; text-transform: capitalize">No</th>
                <th class="text-center" style="font-size: 10px; font-weight: bold; color: white; text-transform: capitalize">Aksi</th>
                <th class="text-center" style="font-size: 10px; font-weight: bold; color: white; text-transform: capitalize">No Terima</th>
                <th class="text-center" style="font-size: 10px; font-weight: bold; color: white; text-transform: capitalize">Freq</th>
                <th class="text-center" style="font-size: 10px; font-weight: bold; color: white; text-transform: capitalize">Urut</th>
                <th class="text-center" style="font-size: 10px; font-weight: bold; color: white; text-transform: capitalize; width: 120px;">No SPK</th>
                <th class="text-center" style="font-size: 10px; font-weight: bold; color: white; text-transform: capitalize">Produk SPK</th>
                <th class="text-center" style="font-size: 10px; font-weight: bold; color: white; text-transform: capitalize">Kadar</th> 
                <th class="text-center" style="font-size: 10px; font-weight: bold; color: white; text-transform: capitalize">Jml SPK</th>
                <th class="text-center" style="font-size: 10px; font-weight: bold; color: white; text-transform: capitalize; width: 300px;">Barang</th> 
                <th class="text-center" style="font-size: 10px; font-weight: bold; color: white; text-transform: capitalize">Qty</th> 
                <th class="text-center" style="font-size: 10px; font-weight: bold; color: white; text-transform: capitalize">Weight</th>
                <th class="text-center" style="font-size: 10px; font-weight: bold; color: white; text-transform: capitalize; width: 50px;">Jumlah</th>
                <th class="text-center" style="font-size: 10px; font-weight: bold; color: white; text-transform: capitalize; width: 100px;">Berat</th>
                <th class="text-center" style="font-size: 10px; font-weight: bold; color: white; text-transform: capitalize; width: 100px;">Kadar</th> 
                <th class="text-center" style="font-size: 10px; font-weight: bold; color: white; text-transform: capitalize; width: 200px;">Note</th>
                <th class="text-center" style="font-size: 10px; font-weight: bold; color: white; text-transform: capitalize">Pohon ID</th> 
                <th class="text-center" style="font-size: 10px; font-weight: bold; color: white; text-transform: capitalize">Pohon Urut</th>
                <th class="text-center" style="font-size: 10px; font-weight: bold; color: white; text-transform: capitalize">No Pohon</th> 
                <th class="text-center" style="font-size: 10px; font-weight: bold; color: white; text-transform: capitalize; width: 450px;">Produk Detail</th>  
                <th class="text-center" style="font-size: 10px; font-weight: bold; color: white; text-transform: capitalize; width: 150px;">BatchNo</th> 
            </tr>
        </thead>
        <tbody>
            @foreach ($item as $items)
            <tr id="myRow{{$loop->iteration}}">
                <input type="hidden" id="WorkOrder{{$loop->iteration}}" name="WorkOrder[]" value="{{$items->OSW}}">
                <input type="hidden" id="FinishGood{{$loop->iteration}}" name="FinishGood[]" value="{{$items->FDescription}}">
                <input type="hidden" id="Carat{{$loop->iteration}}" name="Carat[]" value="{{$items->CSW}}">
                <input type="hidden" id="TotalQty{{$loop->iteration}}" name="TotalQty[]" value="{{$items->QtyOrder}}">
                <input type="hidden" id="RubberPlate{{$loop->iteration}}" name="RubberPlate[]" value="{{$items->RubberPlate}}">
                <input type="hidden" id="TreeID{{$loop->iteration}}" name="TreeID[]" value="{{$items->TreeID}}">
                <input type="hidden" id="TreeOrd{{$loop->iteration}}" name="TreeOrd[]" value="{{$items->TreeOrd}}">
                <input type="hidden" id="BatchNo{{$loop->iteration}}" name="BatchNo[]" value="{{$items->BatchNo}}">
                <input type="hidden" id="FG{{$loop->iteration}}" name="FG[]" value="{{$items->FG}}">
                <input type="hidden" id="Part{{$loop->iteration}}" name="Part[]" value="{{$items->Part}}">
                <input type="hidden" id="RID{{$loop->iteration}}" name="RID[]" value="{{$items->Carat}}">
                <input type="hidden" id="OID{{$loop->iteration}}" name="OID[]" value="{{$items->WorkOrder}}">
                <input type="hidden" id="OOrd{{$loop->iteration}}" name="OOrd[]" value="{{$items->WorkOrderOrd}}">
                <td>{{ $loop->iteration }}</td>
                <td><button type="button" class="btn btn-danger btn-sm" onclick="remove({{$loop->iteration}})"><i class="fa fa-minus"></i></button></td>
                <td><input class="form-control-plaintext" type="text" style="text-align: center; color:black; font-size: 13px; font-weight: bold" id="WorkAllocation{{$loop->iteration}}" name="WorkAllocation[]" value="{{$items->WorkAllocation}}" onkeydown="handlerItem(event)" data-index="{{$loop->iteration}}01" row-index="{{$loop->iteration}}" ></td>
                <td><input class="form-control-plaintext" type="text" style="text-align: center; color:black; font-size: 13px; font-weight: bold" id="Freq{{$loop->iteration}}" name="Freq[]" value="{{$items->LinkFreq}}" onkeydown="handlerItem(event)" data-index="{{$loop->iteration}}02" row-index="{{$loop->iteration}}" ></td>
                <td><input class="form-control-plaintext" type="text" style="text-align: center; color:black; font-size: 13px; font-weight: bold" id="Ordinal{{$loop->iteration}}" name="Ordinal[]" value="{{$items->LinkOrd}}" onchange="cariSPKO(this.value,{{$loop->iteration}})" onkeydown="handlerItem(event)" data-index="{{$loop->iteration}}03" row-index="{{$loop->iteration}}" ></td>
                <td><input class="form-control" type="text" spellcheck="false" autocomplete="off" style="text-align: center; color:black; font-size: 13px; font-weight: bold" id="NoSPK{{$loop->iteration}}" value="{{$items->OSW}}" onchange="cariSPK(this.value,{{$loop->iteration}})" onkeydown="handlerItem(event)" data-index="{{$loop->iteration}}04" row-index="{{$loop->iteration}}"></td>
                <td><input class="form-control-plaintext" type="text" style="text-align: center; color:black; font-size: 13px; font-weight: bold" id="ProdukSPK{{$loop->iteration}}" value="{{$items->FDescription}}" onkeydown="handlerItem(event)" data-index="{{$loop->iteration}}05" row-index="{{$loop->iteration}}" readonly></td>
                <td><input class="form-control-plaintext" type="text" style="text-align: center; color:black; font-size: 13px; font-weight: bold" id="Kadar{{$loop->iteration}}" value="{{$items->CSW}}" onkeydown="handlerItem(event)" data-index="{{$loop->iteration}}06" row-index="{{$loop->iteration}}" readonly></td>
                <td><input class="form-control-plaintext" type="text" style="text-align: center; color:black; font-size: 13px; font-weight: bold" id="JmlSPK{{$loop->iteration}}" value="{{$items->QtyOrder}}" onkeydown="handlerItem(event)" data-index="{{$loop->iteration}}07" row-index="{{$loop->iteration}}" readonly></td>
                <td>
                    <input class="form-control Product" type="text" spellcheck="false" autocomplete="off" style="text-align: center; color:black; font-size: 13px; font-weight: bold" id="Product{{$loop->iteration}}" value="{{$items->PDescription}}" onchange="cariProduct(this.value,{{$loop->iteration}})" onkeydown="handlerItem(event)" data-index="{{$loop->iteration}}08" row-index="{{$loop->iteration}}">
                    <input type="hidden" id="PID{{$loop->iteration}}" name="PID[]" value="{{$items->Product}}">
                </td>
                <td>{{ $items->Qty }}</td>
                <td>{{ $items->Weight }}</td>
                <td><input class="form-control" type="text" autocomplete="off" style="text-align: center; color:black; font-size: 13px; width: 100%; font-weight: bold" id="Qty{{$loop->iteration}}" name="Qty[]" value="{{$items->Qty}}" onchange="refresh_sum_qty({{$loop->iteration}})" onkeydown="handlerItem(event)" data-index="{{$loop->iteration}}09" row-index="{{$loop->iteration}}"></td>
                <td>
                    <div class="input-group" style="width: 100%;">
                        <input class="form-control" type="text" autocomplete="off" style="text-align: center; color:black; font-size: 13px; font-weight: bold" id="Weight{{$loop->iteration}}" name="Weight[]" value="{{$items->Weight}}" onchange="refresh_sum_weight({{$loop->iteration}})" onkeydown="handlerItem(event)" data-index="{{$loop->iteration}}10" row-index="{{$loop->iteration}}">
                        <button type="button" class="btn btn-info btn-sm" onclick="klikTimbangRun('Weight{{$loop->iteration}}',{{$loop->iteration}})"><i class="fa fa-balance-scale"></i></button>
                    </div>
                </td>
                <td><input class="form-control" type="text" spellcheck="false" autocomplete="off" style="text-align: center; color:black; font-size: 13px; font-weight: bold" id="KadarInput{{$loop->iteration}}" value="" onchange="cariKadar(this.value,$loop->iteration)" onkeydown="handlerItem(event)" data-index="{{$loop->iteration}}11" row-index="{{$loop->iteration}}"></td>
                <td><input class="form-control" type="text" autocomplete="off" style="text-align: center; color:black; font-size: 13px; width: 100%; font-weight: bold" id="Note{{$loop->iteration}}" name="Note[]" value="{{$items->Note}}" onkeydown="handlerItem(event)" data-index="{{$loop->iteration}}12" row-index="{{$loop->iteration}}"></td>
                <td><input class="form-control-plaintext" type="text" style="text-align: center; color:black; font-size: 13px; font-weight: bold" id="PohonID{{$loop->iteration}}" value="{{$items->TreeID}}" onkeydown="handlerItem(event)" data-index="{{$loop->iteration}}13" row-index="{{$loop->iteration}}" readonly></td>
                <td><input class="form-control-plaintext" type="text" style="text-align: center; color:black; font-size: 13px; font-weight: bold" id="PohonUrut{{$loop->iteration}}" value="{{$items->TreeOrd}}" onkeydown="handlerItem(event)" data-index="{{$loop->iteration}}14" row-index="{{$loop->iteration}}" readonly></td>
                <td><input class="form-control-plaintext" type="text" style="text-align: center; color:black; font-size: 13px; font-weight: bold" id="NoPohon{{$loop->iteration}}" value="{{$items->RubberPlate}}" onkeydown="handlerItem(event)" data-index="{{$loop->iteration}}15" row-index="{{$loop->iteration}}" readonly></td>
                <td><input class="form-control-plaintext" type="text" style="text-align: center; color:black; font-size: 13px; font-weight: bold" id="ProdukDetail{{$loop->iteration}}" value="{{$items->GSW}}" onkeydown="handlerItem(event)" data-index="{{$loop->iteration}}16" row-index="{{$loop->iteration}}" readonly></td>
                <td><input class="form-control-plaintext" type="text" style="text-align: center; color:black; font-size: 13px; font-weight: bold" id="Batch{{$loop->iteration}}" value="{{$items->BatchNo}}" onkeydown="handlerItem(event)" data-index="{{$loop->iteration}}17" row-index="{{$loop->iteration}}" readonly></td>

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
    
            if (posisi != '4' && posisi != '7' && posisi != '8') {
                e.preventDefault();
            }
        }
    });
</script>

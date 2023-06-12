@php
    foreach ($data as $datas) {}
    foreach ($data2 as $datas2) {}
@endphp

<form id="tampilform">

    <div class="row">
        <div class="col-md-10">

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group row">
                        <label class="col-md-3 col-form-label" style="font-size: 13px; font-weight: bold; color: black; text-transform: capitalize;">ID :</label>
                        <div class="col-md-2" >
                            <label style="font-weight:bold; color: blue;"></label>
                            <input type="hidden" id="idnthko" name="idnthko" value="">
                            <input type="hidden" id="cekstatus" name="cekstatus" value="1">
                            <input type="hidden" id="productlist" name="productlist" value="">
                            <input type="hidden" id="selscale"> {{-- Hidden input for timbangan --}}
                        </div>
                        <div class="col-md-7" style="text-align: center">
                            <span>
                                <button type="button" class="btn btn-dark btn-sm" id="btnposting" onclick="klikPosting()"><span class="tf-icons bx bx-send"></span>&nbsp; Posting</button>
                                &nbsp;&nbsp;&nbsp;
                                <button type="button" class="btn btn-dark btn-sm" id="btncetakbarcode" onclick="klikCetakBarcodeDirect()" disabled><span class="tf-icons bx bx-qr"></span>&nbsp; Barcode</button>
                            </span>
                        </div>
                        
                    </div>
                </div>
                <div class="col-md-6">
                </div>
            </div>

            <div class="row" style="padding-top: 0px;">
                <div class="col-md-6">
                    <div class="form-group row">
                        <label class="col-md-3 col-form-label" style="font-size: 13px; font-weight: bold; color: black; text-transform: capitalize;">Tanggal :</label>
                        <div class="col-md-5">
                            <input class="form-control form-control-sm" type="date" style="font-size: 13px; font-weight: bold; color: black;" id="tgl" name="tgl" value="{{$dataAll['datenow']}}">
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group row">
                        <label class="col-md-3 col-form-label" style="font-size: 13px; font-weight: bold; color: black; text-transform: capitalize;">No SPKO :</label>
                        <div class="col-md-4">
                            <input class="form-control form-control-sm" type="text" style="font-size: 13px; font-weight: bold; color: black;" id="swspko" name="swspko" value="{{$dataAll['swspko']}}" >
                        </div>
                        <div class="col-md-3">
                            <label style="font-weight:bold; color: blue;">{{$nthkofreq}}</label>
                            <input type="hidden" id="nthkofreq" name="nthkofreq" value="{{$nthkofreq}}">
                        </div>
                    </div>
                </div>
            </div>

            <div class="row" style="padding-top: 0px;">
                <div class="col-md-6">
                    <div class="form-group row">
                        <label class="col-md-3 col-form-label" style="font-size: 13px; font-weight: bold; color: black; text-transform: capitalize;">Dikerjakan :</label>
                        <div class="col-md-2">
                            <label style="font-weight:bold; color: blue;">{{$datas2->EID}}</label>
                            <input type="hidden" id="emp" name="emp" value="{{$datas2->EID}}" >
                    
                        </div>
                        <div class="col-md-7">
                            <label style="font-weight:bold; color: blue;">{{$datas2->Employee}}</label>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group row">
                        <label class="col-md-3 col-form-label" style="font-size: 13px; font-weight: bold; color: black; text-transform: capitalize;">Bagian :</label>
                        <div class="col-md-3">
                            <label style="font-weight:bold; color: blue;">{{$datas2->Location}}</label>
                            <input type="hidden" id="bagian" name="bagian" value="{{$datas2->LID}}" >
                        </div>
                        <label class="col-md-3 col-form-label" style="font-size: 13px; font-weight: bold; color: black; text-transform: capitalize;">Kadar :</label>
                        <div class="col-md-3">
                            <label style="font-weight:bold; color: blue;">{{$datas2->CSW}}</label>
                            <input type="hidden" id="kadar" name="kadar" value="{{$datas2->Carat}}" >
                        </div>
                    </div>
                </div>
            </div>

            <div class="row" style="padding-top: 0px;">
                <div class="col-md-6">
                    <div class="form-group row">
                        <label class="col-md-3 col-form-label" style="font-size: 13px; font-weight: bold; color: black; text-transform: capitalize;">Proses :</label>
                        <div class="col-md-4">
                            <label style="font-weight:bold; color: blue;">{{$datas2->Operation}}</label>
                            <input type="hidden" id="proses" name="proses" value="{{$datas2->OID}}" >
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group row">
                        <label class="col-md-3 col-form-label" style="font-size: 13px; font-weight: bold; color: black; text-transform: capitalize;">Mesin :</label>
                        <div class="col-md-4">
                            <select class="form-select form-select-sm" style="font-size: 13px; font-weight: bold; color: black;">
                                <option selected="">Pilih</option>
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                                <option value="4">4</option>
                                <option value="5">5</option>
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
                            <label style="font-weight:bold; color: blue;">{{number_format($datas2->TargetQty,2)}}</label>
                            <input type="hidden" id="qtyspko" name="qtyspko" value="{{$datas2->TargetQty}}" >
                        </div>
                        <label class="col-md-3 col-form-label" style="font-size: 13px; font-weight: bold; color: black; text-transform: capitalize;">Qty NTHKO :</label>
                        <div class="col-md-3">
                            <label id="qtynthkolabel" style="font-weight:bold; color: blue;">0.00</label>
                            <input type="hidden" id="qtynthko" name="qtynthko" value="" >
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group row">
                        <label class="col-md-3 col-form-label" style="font-size: 13px; font-weight: bold; color: black; text-transform: capitalize;">Brt SPKO :</label>
                        <div class="col-md-3">
                            <label style="font-weight:bold; color: blue;">{{number_format($datas2->Weight,2)}}</label>
                            <input type="hidden" id="weightspko" name="weightspko" value="{{number_format($datas2->Weight,2)}}" >
                        </div>
                        <label class="col-md-3 col-form-label" style="font-size: 13px; font-weight: bold; color: black; text-transform: capitalize;">Brt NTHKO :</label>
                        <div class="col-md-3">
                            <label id="weightnthkolabel" style="font-weight:bold; color: blue;">0.00</label>
                            <input type="hidden" id="weightnthko" name="weightnthko" value="" >
                        </div>
                    </div>
                </div>
            </div>

            <div class="row" style="padding-top: 0px;">
                <div class="col-md-6">
                    <div class="form-group row">
                        <label class="col-md-3 col-form-label" style="font-size: 13px; font-weight: bold; color: black; text-transform: capitalize;">Susut :</label>
                        <div class="col-md-4">
                            <label id="susutlabel" style="font-weight:bold; color: blue;"></label>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group row">
                        <label class="col-md-3 col-form-label" style="font-size: 13px; font-weight: bold; color: black; text-transform: capitalize;">Perbedaan :</label>
                        <div class="col-md-7">
                            <label id="perbedaanlabel" style="font-weight:bold; color: blue;"></label>
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
                    <input class="form-control form-control-sm" type="text" style="font-size: 13px; font-weight: bold; color: black;" id="barcodeunit" placeholder="Barcode Tunggal" name="barcodeunit" onchange="klikBarcodeUnit()">
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
                <th class="text-center" style="font-size: 10px; font-weight: bold; color: white; text-transform: capitalize; width: 100px">No SPK</th>
                <th class="text-center" style="font-size: 10px; font-weight: bold; color: white; text-transform: capitalize">Produk SPK</th>
                <th class="text-center" style="font-size: 10px; font-weight: bold; color: white; text-transform: capitalize">Jml SPK</th>
                <th class="text-center" style="font-size: 10px; font-weight: bold; color: white; text-transform: capitalize; width: 150px">Barang</th>
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
                <th class="text-center" style="font-size: 10px; font-weight: bold; color: white; text-transform: capitalize; width: 450px">Produk Detail</th> 
                <th class="text-center" style="font-size: 10px; font-weight: bold; color: white; text-transform: capitalize">SPKO ID</th> 
                <th class="text-center" style="font-size: 10px; font-weight: bold; color: white; text-transform: capitalize">SPKO Urut</th> 
                <th class="text-center" style="font-size: 10px; font-weight: bold; color: white; text-transform: capitalize">Pohon ID</th> 
                <th class="text-center" style="font-size: 10px; font-weight: bold; color: white; text-transform: capitalize">Pohon Urut</th> 
                <th class="text-center" style="font-size: 10px; font-weight: bold; color: white; text-transform: capitalize; width: 150px">Batch</th> 
            </tr>                     
        </thead>
        <tbody>
        </tbody>
    </table>
</form>

<script>
    $(document).ready(function() {
        $('.selectlist').selectpicker();
    });
</script>



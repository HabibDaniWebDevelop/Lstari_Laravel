@php
$tglnow = date('Y-m-d');
foreach ($header as $headers){}
@endphp

<form id="tampilform">

    <div class="row">
        <div class="col-md-10">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group row">
                        <label class="col-md-3 col-form-label" style="font-size: 13px; font-weight: bold; color: black; text-transform: capitalize;">ID : </label>
                        <div class="col-md-2">
                            <label id="idspkolabel" style="font-weight:bold; color: blue;">{{$headers->ID}}</label>
                            <input type="hidden" id="idspko" name="idspko" value="{{$headers->ID}}">
                            <input type="hidden" id="swspko" name="swspko" value="{{$headers->SW}}">
                            <input type="hidden" id="swspko" name="freqspko" value="{{$headers->Freq}}">
                            <input type="hidden" id="ceknew" name="ceknew" value="1">
                            <input type="hidden" id="selscale">
                        </div>
                        <div class="col-md-7" style="text-align: center">
                            <span>
                                <button type="button" class="btn btn-dark btn-sm" id="btnposting" onclick="klikPosting()"><span class="tf-icons bx bx-send"></span>&nbsp;Posting</button>
                                &nbsp;&nbsp;&nbsp;
                                <button type="button" class="btn btn-dark btn-sm" id="btncetakbarcodedirect" onclick="klikCetakBarcodeDirect()"><span class="tf-icons bx bx-qr"></span>&nbsp;Barcode</button>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group row">
                        <label class="col-md-3 col-form-label" style="font-size: 13px; font-weight: bold; color: black; text-transform: capitalize;">Tanggal :</label>
                        <div class="col-md-4">
                            <input class="form-control form-control-sm" type="date" id="tgl" name="tgl" value="{{$tglnow}}" tabindex="1">
                        </div>
                        <div class="col-md-3">
                            <p class=""></p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row" style="padding-top: 5px;">
                <div class="col-md-6">
                    <div class="form-group row">
                        <label class="col-md-3 col-form-label" style="font-size: 13px; font-weight: bold; color: black; text-transform: capitalize;">No SPKO :</label>
                        <div class="col-md-4">
                            <label id="swspkolabel" style="font-weight:bold; color: blue;">{{$headers->SW}}</label>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group row">
                        <label class="col-md-3 col-form-label" style="font-size: 13px; font-weight: bold; color: black; text-transform: capitalize;">Kadar :</label>
                        <div class="col-md-5">
                            <select class="form-select form-select-sm myselect" name="kadar" id="kadar" tabindex="2">
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
                            <select class="form-select form-select-sm myselect" name="proses" id="proses" tabindex="3">
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
                            <label style="font-weight:bold; color: blue">{{(int)$dataMaxFreq + 1}}</label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row" style="padding-top: 5px;">
                <div class="col-md-6">
                    <div class="form-group row">
                        <label class="col-md-3 col-form-label" style="font-size: 13px; font-weight: bold; color: black; text-transform: capitalize;">Qty SPKO :</label>
                        <div class="col-md-4">
                            <label id="qtyspkolabel" style="font-weight:bold; color: blue;"></label>
                            <input type="hidden" id="qtyspko" name="qtyspko" value="">
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group row">
                        <label class="col-md-3 col-form-label" style="font-size: 13px; font-weight: bold; color: black; text-transform: capitalize;">Berat SPKO :</label>
                        <div class="col-md-4">
                            <label id="weightspkolabel" style="font-weight:bold; color: blue;"></label>
                            <input type="hidden" id="weightspko" name="weightspko" value="">
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
                <input class="form-control form-control-sm" type="text" id="barcodeall" name="barcodeall" placeholder="Barcode Semua" onchange="klikBarcodeAll()" tabindex="6">
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="form-group row">
            <div class="col-md-12">
                <input class="form-control form-control-sm" type="text" id="barcodeunit" name="barcodeunit" placeholder="Barcode Tunggal" onchange="klikBarcodeUnit()" tabindex="7">
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
        <thead style="display: table-header-group;">
            <tr bgcolor="#111111">
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
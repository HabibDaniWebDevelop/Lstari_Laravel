@php
    $tglnow = date('Y-m-d');
    foreach ($data as $datas){}
@endphp

<form id="tampilform">

    <div class="row">

        <div class="col-md-6 mb-2">
            <div class="form-group">
                <label class="form-label" style="font-size: 13px; font-weight: bold; color: black; text-transform: capitalize;">ID</label>
                <input type="text" class="form-control-plaintext" style="font-size: 13px; font-weight: bold; color: black;" name="idtm" id="idtm" value="" readonly>
                <input type="hidden" id="cekstatus" name="cekstatus" value="1">
                <input type="hidden" id="cekspk" name="cekspk" value="">
                <input type="hidden" id="selscale">
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                <label class="form-label" style="font-size: 13px; font-weight: bold; color: black; text-transform: capitalize;">Catatan</label>
                <input type="text" class="form-control" style="font-size: 13px; font-weight: bold; color: black;" name="note" id="note" value="" tabindex="1">
            </div>
        </div>

        <div class="col-md-6 mb-2">
            <div class="form-group">
                <label class="form-label" style="font-size: 13px; font-weight: bold; color: black; text-transform: capitalize;">Tanggal</label>
                <input type="date" class="form-control" style="font-size: 13px; font-weight: bold; color: black;" id="tgl" name="tgl" value="{{$tglnow}}" tabindex="2">
            </div>
        </div>

        <div class="col-md-3">
            <div class="form-group">
                <label class="form-label" style="font-size: 13px; font-weight: bold; color: black; text-transform: capitalize;">Penerima</label>
                <input type="text" class="form-control" style="font-size: 13px; font-weight: bold; color: black;" name="karyawan" id="karyawan" value="" onChange="cariKaryawan(this.value)" tabindex="3">
                <input type="hidden" id="karyawanid" name="karyawanid" value="">
            </div>
        </div>

        <div class="col-md-3">
            <div class="form-group">
                <label class="form-label" style="color: white; text-transform: capitalize;">PenerimaLabel</label><br>
                <label style="font-weight:bold; color: blue;" id="karyawanlabel" name="karyawanlabel"></label>
            </div>
        </div>

        <div class="col-md-6 mb-2">
            <div class="form-group">
                <label class="form-label" style="font-size: 13px; font-weight: bold; color: black; text-transform: capitalize;">Dari Bagian</label>
                <select class="form-select" name="daribagian" id="daribagian" tabindex="4" style="font-size: 13px; font-weight: bold; color: black;">
                    <option value="{{$datas->ID}}">{{$datas->Description}}</option>
                </select>
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                <label class="form-label" style="font-size: 13px; font-weight: bold; color: black; text-transform: capitalize;">Ke Bagian</label>
                <select class="form-select" name="kebagian" id="kebagian" tabindex="5" style="font-size: 13px; font-weight: bold; color: black;">
                    <option value="" selected>Pilih</option>
                    @foreach ($data2 as $datas2)
                    <option value="{{$datas2->ID}}">{{$datas2->Description}}</option>
                    @endforeach
                </select>
            </div>
        </div>

    </div>

</form>

<hr style="height:1px; color: #000000;">
<div class="row">
    <div class="col-md-3">
        <label class="control-label" style="font-size: 13px; font-weight: bold; color: black; text-transform: capitalize;">Entry Date : <span style="color: blue; font-weight: bold" id="entrydate" value=""></span></label>   
    </div>
    <div class="col-md-3">
        <label class="control-label" style="font-size: 13px; font-weight: bold; color: black; text-transform: capitalize;">User : <span style="color: blue; font-weight: bold" id="user" value=""></span></label>   
    </div>
    <div class="col-md-3">
        <label class="control-label" style="font-size: 13px; font-weight: bold; color: black; text-transform: capitalize;">Total Jumlah : <span style="color: blue; font-weight: bold" id="totjumlah" value=""></span></label>   
    </div>
    <div class="col-md-3">
        <label class="control-label" style="font-size: 13px; font-weight: bold; color: black; text-transform: capitalize;">Total Berat : <span style="color: blue; font-weight: bold" id="totberat" value=""></span></label>   
    </div>
</div>

<div class="row" style="margin-top: 10px">
    <div class="col-md-2">
        <div class="form-group row">
            <div class="col-md-12">
                <input class="form-control" type="text" style="font-size: 13px; font-weight: bold; color: black; text-transform: capitalize;" id="barcodeunit" name="barcodeunit" placeholder="Barcode NTHKO" onchange="klikBarcodeUnit()" tabindex="6">
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="form-group row">
            <div class="col-md-12">
                <input class="form-control" type="text" style="font-size: 13px; font-weight: bold; color: black; text-transform: capitalize;" id="barcodekomponen" name="barcodekomponen" placeholder="Barcode Komponen" onchange="klikBarcodeKomponen()" tabindex="7">
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

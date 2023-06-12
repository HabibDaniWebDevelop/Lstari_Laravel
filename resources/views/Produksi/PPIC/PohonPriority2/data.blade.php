<div class="card-body">
    <div class="row" id="hilang">
        <div class="col-9">
            <button type="hidden" class="btn btn-dark me-4" id="btn_daftarlist" onclick="tabelpilihanpohon()"> <span
                    class="tf-icons bx bx-list-ul"></span>&nbsp; Daftar List Pohon </button>
            <button type="hidden" class="btn btn-dark me-4" id="btn_daftarlist" onclick="tabelpilihanpohondipilih()">
                <span class="tf-icons bx bx-list-ul"></span>&nbsp; Daftar Pohon Terpilih </button>
            <button type="button" class="btn btn-danger" disabled id="btn_batal" onclick="klikBatal()"> <span
                    class="fas fa-times-circle"></span>&nbsp; Batal</button>
            <button type="button" class="btn btn-warning me-4" disabled id="btn_simpan" onclick="KlikSimpan()"
                tabindex="7"><span class="tf-icons bx bx-save"></span>&nbsp; Simpan</button>
            <button type="button" class="btn btn-info" id="btn_cetak" onclick="klikCetak() "> <span
                    class="tf-icons bx bx-printer"></span>&nbsp; Cetak</button>
        </div>
    </div>
    <hr>
    <div class="row" id="hilang">
        <div class="col-2 center">
            <label class="form-label" for="IDSPKOInject" style="font-size: 17px;">Total Prioritas : <span id="tpri"
                    style="font-weight: bold; color: DodgerBlue; font-size: 30px; padding-left: 10px;"></span></label>
        </div>
        <div class="col-2">
            <label class="form-label" for="basic-icon-default-fullname" style="font-size: 17px;">Total Poles : <span
                    id="poles"
                    style="font-weight: bold; color: DodgerBlue; font-size: 30px; padding-left: 10px;"></span></label></label>
        </div>
        <div class="col-2">
            <label class="form-label" for="basic-icon-default-fullname" style="font-size: 17px;">Total Patri : <span
                    id="patri"
                    style="font-weight: bold; color: DodgerBlue; font-size: 30px; padding-left: 10px;"></span></label></label>
        </div>
        <div class="col-2">
            <label class="form-label" for="basic-icon-default-fullname" style="font-size: 17px;">Total PUK : <span
                    id="puk"
                    style="font-weight: bold; color: DodgerBlue; font-size: 30px; padding-left: 10px;"></span></label>
            </label>
        </div>
        <div class="col-3">
            <label class="form-label" for="basic-icon-default-fullname" style="font-size: 17px;">Total Berat FG : <span
                    id="tfg"
                    style="font-weight: bold; color: DodgerBlue; font-size: 30px; padding-left: 10px;"></span></label></label>
        </div>
    </div>
    <div class="row" id="hilang">

    </div>
    <hr>
    <div class="table-responsive" style="height:calc(80vh);">

        <table class="table table-border table-hover table-sm" id="tabel1">
            <thead class="table-secondary text-center sticky-top zindex-2">
                <tr class="px-0 mx-0" style="text-align: center">
                    <th class="px-0 mx-0">PILL</th>
                    <th class="px-0 mx-0">Tanggal </th>
                    <th class="px-0 mx-0">No.Pohon</th>
                    <th class="px-0 mx-0">Kadar</th>
                    <th class="px-0 mx-0">NO.SPK PPIC</th>
                    <th class="px-0 mx-0">Model</th>
                    <th class="px-0 mx-0">Product</th>
                    <th class="px-0 mx-0">Jumlah</th>
                    <th class="px-0 mx-0">Poles</th>
                    <th class="px-0 mx-0">Patri</th>
                    <th class="px-0 mx-0">PUK</th>
                    <th class="px-0 mx-0">Brt Perk</th>
                </tr>
            </thead>
            <tbody class="text-center p-0 m-0" style="text-align: center">
                @forelse ($TebelPriority as $tb)
                <tr class="klik3 m-0 p-0 {{$tb->cssterpilih}}" id="{{ $loop->iteration }}">
                    <td class="m-0 p-0">
                        <div><input class="form-check-input Priority" type="checkbox" name="id[]"
                                id="Priority_{{$loop->iteration}}" value="{{$tb->IDWaxtree}}" disabled data-itung="1"
                                data-FG="{{$tb->WeightFG}}.toFixed(2)" data-Poles="{{$tb->TotalPoles}}"
                                data-Patri="{{$tb->TotalPatri}}" data-Puk="{{$tb->TotalPUK}}"></div>
                    </td>
                    <td class="p-0 m-0"><span id="Dates_{{ $loop->iteration }}" value="">{{$tb->TransDate}}</span></td>
                    <td class="p-0 m-0"> <span class="badge bg-dark Plates" style="font-size:14px;"
                            id="Plates_{{ $loop->iteration }}">{{$tb->Plate}}</span></td>
                    <td class="p-0 m-0"><span class="badge" style="font-size:14px; background-color: {{$tb->HexColor}}"
                            id="kadarkaret_{{ $loop->iteration }}">{{$tb->Kadar}}</span></td>
                    <td>
                        <div class="badge SPK"
                            style="line-height: 1.5; font-size:14px; background-color: {{$tb->WorkText}}; color: #fff;"
                            id="SPK_{{ $loop->iteration }}">
                            {{$tb->WorkOrder}}</div>
                    </td>
                    <td><span class="badge" id="Models_{{ $loop->iteration }}" value=""
                            style="font-size:14px; background-color:{{$tb->infomodel}}; color: #fff;">
                            {{$tb->Model}}</span></td>
                    <td class="p-0 m-0"><span id="Products_{{ $loop->iteration }}" value=""
                            style="font-size:15px; color: #000; font-weight: normal; word-break: break-all;">
                            {{$tb->Product}}</span></td>
                    <td class="p-0 m-0"><span class="JumlahQty" id="Jumlah_{{ $loop->iteration }}" value="{{$tb->Qty}}">
                            {{$tb->Qty}}</span><span hidden class="Jumlahdata" id="Jumlahdata_{{ $loop->iteration }}"
                            value="{{ $loop->iteration }}">1</span><span hidden class="polespilih"
                            value="{{ $loop->iteration }}">
                            {{$tb->TotalPolespilih}}</span><span hidden class="patripilih"
                            value="{{ $loop->iteration }}">
                            {{$tb->TotalPatripilih}}</span><span hidden class="pukpilih" value="{{ $loop->iteration }}">
                            {{$tb->TotalPUKpilih}}</span><span hidden class="fgpilih" value="{{ $loop->iteration }}">
                            {{$tb->WeightFGpilih}}</span><span hidden class="pohonpilih"
                            value="">{{$tb->jumlahpohonpilih}}</span></td>
                    <td class="p-0 m-0"><span class="JumlahPoles" id="Poles_{{ $loop->iteration }}" value=""
                            onkeyup="TotalDataPoles({{$tb->TotalPoles}})">
                            {{$tb->TotalPoles}}</span></td>
                    <td class="p-0 m-0"><span class="JumlahPatri" center id="Patri_{{ $loop->iteration }}" value="">
                            {{$tb->TotalPatri}}</span></td>
                    <td class="p-0 m-0"><span class="JumlahPuk" id="PUK_{{ $loop->iteration }}"
                            value="">{{$tb->TotalPUK}}</span></td>
                    <td class="p-0 m-0"><span class="JumlahBerat" center id="Berat_{{ $loop->iteration }}" value="">
                            {{$tb->WeightFG}}.toFixed(2)</span></td>
                </tr>
                @empty
                @endforelse
            </tbody>
        </table>
        <table hidden class="table table-border table-hover table-sm" id="tabel2">
            <thead class="table-secondary text-center sticky-top zindex-2">
                <tr class="p-0 m-0" style="text-align: center">
                    <th class="p-0 m-0">PILL</th>
                    <th class="p-0 m-0">Tanggal </th>
                    <th class="p-0 m-0">No.Pohon</th>
                    <th class="p-0 m-0">Kadar</th>
                    <th class="p-0 m-0">NO.SPK PPIC</th>
                    <th class="p-0 m-0">Model</th>
                    <th class="p-0 m-0">Product</th>
                    <th class="p-0 m-0">Jumlah</th>
                    <th class="p-0 m-0">Poles</th>
                    <th class="p-0 m-0">Patri</th>
                    <th class="p-0 m-0">PUK</th>
                    <th class="p-0 m-0">Brt Perk</th>
                </tr>
            </thead>
            <tbody class="text-center p-0 m-0" style="text-align: center">
            </tbody>
        </table>
    </div>

    <div class="float-end">
        <td>Total Pohon: <span id="totaldata"
                style="font-weight: bold; color: DodgerBlue; font-size: 20px; padding-left: 1px;">0</span>
        </td>
        <td>Total Jumlah: <span id="totaldatapohon"
                style="font-weight: bold; color: DodgerBlue; font-size: 20px; padding-left: 1px;">0</span>
        </td>
        <td> &nbsp;Total Poles: <span id="totaldatapoles"
                style="font-weight: bold; color: DodgerBlue; font-size: 20px; padding-left: 1px;">0</span></td>
        <td> &nbsp;Total Patri: <span id="totaldatapatri"
                style="font-weight: bold; color: DodgerBlue; font-size: 20px; padding-left: 1px;">0</span></td>
        <td> &nbsp;Total PUK: <span id="totaldatapuk"
                style="font-weight: bold; color: DodgerBlue; font-size: 20px; padding-left: 1px;">0</span></td>
        <td> &nbsp;Total Berat FG: <span id="totaldataberat"
                style="font-weight: bold; color: DodgerBlue; font-size: 20px; padding-left: 1px;">0</span></td>
    </div>
</div>

<script>
// Data Table Settings

$(".klik3").on('click', function(e) {
    var id = $(this).attr('id');
    if ($(this).hasClass('table-primary')) {
        $(this).removeClass('table-primary');

        let IDpohon = $('#Priority_' + id).val()
        console.log(IDpohon + '-> jadi N');

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            type: "PUT",
            url: '/Produksi/PPIC/PohonPriority/UbahjadiN/' + IDpohon,
        });

        // hitung jumlah data yang di klick
        let itung = parseInt($('#Priority_' + id).attr('data-itung'))
        let totalItung = parseInt($('#tpri').text())
        if (isNaN(totalItung)) {
            totalItung = 0
        }
        let calItung = totalItung - parseInt(itung)
        $('#tpri').text(calItung)
        //---------------------------------
        let fg = parseFloat($('#Priority_' + id).attr('data-FG'))
        let totalfg = parseFloat($('#tfg').text())
        if (isNaN(totalfg)) {
            totalfg = 0
        }
        let calfg = totalfg - parseFloat(fg)
        $('#tfg').text(calfg.toFixed(2))
        //---------------------------------
        let pol = parseInt($('#Priority_' + id).attr('data-Poles'))
        let totalpol = parseInt($('#poles').text())
        if (isNaN(totalpol)) {
            totalpol = 0
        }
        let calpol = totalpol - parseInt(pol)
        $('#poles').text(calpol)
        //---------------------------------
        let pat = parseInt($('#Priority_' + id).attr('data-Patri'))
        let totalpat = parseInt($('#patri').text())
        if (isNaN(totalpat)) {
            totalpat = 0
        }
        let calpat = totalpat - parseInt(pat)
        $('#patri').text(calpat)
        //---------------------------------
        let puk = parseInt($('#Priority_' + id).attr('data-Puk'))
        let totalpuk = parseInt($('#puk').text())
        if (isNaN(totalpuk)) {
            totalpuk = 0
        }
        let calpuk = totalpuk - parseInt(puk)
        $('#puk').text(calpuk)

        //generate tampilan titakterpilih
        $('#Priority_' + id).attr('checked', false);
        // console.log(id);
    } else {
        $(this).addClass('table-primary');

        let IDpohon = $('#Priority_' + id).val()
        console.log(IDpohon + '-> jadi R');

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            type: "PUT",
            url: '/Produksi/PPIC/PohonPriority/UbahjadiR/' + IDpohon,
        });

        // hitung jumlah data yang di klick
        let itung = parseInt($('#Priority_' + id).attr('data-itung'))
        let totalItung = parseInt($('#tpri').text())
        if (isNaN(totalItung)) {
            totalItung = 0
        }
        let calItung = totalItung + parseInt(itung)
        $('#tpri').text(calItung)
        //---------------------------------
        let fg = parseFloat($('#Priority_' + id).attr('data-FG'))
        let totalfg = parseFloat($('#tfg').text())
        if (isNaN(totalfg)) {
            totalfg = 0
        }
        let calfg = totalfg + parseFloat(fg)
        $('#tfg').text(calfg.toFixed(2))
        //---------------------------------
        let pol = parseInt($('#Priority_' + id).attr('data-Poles'))
        let totalpol = parseInt($('#poles').text())
        if (isNaN(totalpol)) {
            totalpol = 0
        }
        let calpol = totalpol + parseInt(pol)
        $('#poles').text(calpol)
        //---------------------------------
        let pat = parseInt($('#Priority_' + id).attr('data-Patri'))
        let totalpat = parseInt($('#patri').text())
        if (isNaN(totalpat)) {
            totalpat = 0
        }
        let calpat = totalpat + parseInt(pat)
        $('#patri').text(calpat)
        //---------------------------------
        let puk = parseInt($('#Priority_' + id).attr('data-Puk'))
        let totalpuk = parseInt($('#puk').text())
        if (isNaN(totalpuk)) {
            totalpuk = 0
        }
        let calpuk = totalpuk + parseInt(puk)
        $('#puk').text(calpuk)

        //generate tampilan terpilih
        $('#Priority_' + id).attr('checked', true);
    }
    return false;
});
</script>
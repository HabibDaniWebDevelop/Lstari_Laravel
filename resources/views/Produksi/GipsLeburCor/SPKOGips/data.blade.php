<div class="card-body">
    <div class="row">
        <div class="col-9">
            <button type="button" class="btn btn-primary" id="btn_baru" onclick="KlikBaru()"> <span
                    class="tf-icons bx bx-plus-circle"></span>&nbsp; Baru </button>
            <button type="hidden" class="btn btn-primary me-4" disabled id="btn_edit" onclick="KlikEdit()"> <span
                    class="tf-icons bx bx-edit"></span>&nbsp; Ubah </button>
            <button type="button" class="btn btn-danger" disabled="" id="btn_batal" onclick="klikBatal()"> <span
                    class="fas fa-times-circle"></span>&nbsp; Batal</button>
            <button type="button" class="btn btn-warning me-4" disabled="" id="btn_simpan" onclick="KlikSimpan()"
                tabindex="7"><span class="tf-icons bx bx-save"></span>&nbsp; Simpan</button>

            <button type="button" class="btn btn-info" id="btn_cetak" onclick="klikCetak()" disabled=""> <span
                    class="tf-icons bx bx-printer"></span>&nbsp; Cetak Lilin</button>
            <button type="button" class="btn btn-info" id="btn_cetakrekap" onclick="klikCetakRekap()" disabled=""> <span
                    class="tf-icons bx bx-printer"></span>&nbsp; Cetak Gips</button>
            <input type="hidden" id="IDwax" value="" type="number">
            <input type="hidden" id="postingstatus" value="A">
            <input type="hidden" id="O" value="">
            <input type="hidden" id="action" value="simpan" type="text">
            <input type="hidden" id="pohon" value="emas" type="text">

            <span id="infoposting"
                style="font-weight: bold; color: Tomato; font-size: 30px; padding-left: 50px;"></span>

        </div>
        <div class="col-3">
            <div class="float-end">
                <div class="input-group input-group-merge">
                    <span class="input-group-text"><i class="bx bx-search"></i></span>
                    <input type="text" class="form-control" placeholder="Search..." onchange="Search()" autofocus=""
                        id="cari" list="carilist">
                </div>
                <datalist id="carilist">
                    @foreach ($historyGips as $item)
                    <option value="{{$item->ID}}">{{$item->ID}}</option>
                    @endforeach
                </datalist>
            </div>
        </div>
    </div>
    <hr>
    <div class="row">
        <div class="col-3 center">
            <label class="form-label" for="IDSPKOGips">ID SPKO Gips:</label>
            <input type="number" readonly class="form-control" id="IDSPKOGips" tabindex="1">
        </div>
        <div class="col-3">
            <label class="form-label" for="basic-icon-record-fill">&nbsp;</label>
            <div class="d-grid">
                <div class="row p-0">
                    <div class="col p-0 center">
                        <button disabled type="button" class="btn btn-primary" id="tomboldaftarpohon"
                            style="width: 97%;" onclick="KlickDaftarPohonEmas()">
                            <span id="daftarpohon" class="tf-icons fas fa-tree"></span>&nbsp; Emas
                        </button>
                    </div>
                    <div class="col p-0 center">
                        <button disabled type="button" class="btn btn-primary" id="tomboldaftarpohon1"
                            style="width: 97%;" onclick="KlickDaftarPohonPerak()">
                            <span id="daftarpohon" class="tf-icons fas fa-tree"></span>&nbsp; Perak
                        </button>
                    </div>
                </div>

                <button hidden type="button" class="btn btn-primary btn-block" id="tomboldaftarpohon2"
                    onclick="KlickPilihPohon()">
                    <span id="tomboldaftarpohon2" class="tf-icons bx bx-list-ul"></span>&nbsp; Pilih Pohon</button>
            </div>
        </div>
        <div class="col-3">
            <label class="form-label" for="tanggal">Tanggal :</label>
            <input type="date" class="form-control" id="tanggal" readonly value="{{$datenow}}" tabindex="2">
        </div>
        <div class="col-md-3">
            <label class="form-label" for="basic-icon-record-fill">&nbsp;</label>
            <div class="d-grid gap-2">
                <button type="button" class="btn btn-dark" id="Posting1" disabled onclick="Klik_Posting1()">
                    <span class="tf-icons bx bx-send"></span>&nbsp; Posting</button>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <label class="form-label" for="Catatan">Catatan</label>
            <input type="text" class="form-control" id="Catatan" tabindex="5">
        </div>
    </div>
    <br>
    <div class="row" id="show" hidden>
        <div class="col-6">
            <label style="text-align: center; font-weight: bold; color: #80271c;" class="form-label"
                for="UserNameAdmin">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Admin : <span
                    id="UserNameAdmin"></span></label>
        </div>
        <div class="col-6">
            <label style="text-align: center; font-weight: bold; color: #80271c;" class="form-label"
                for="TanggaPembuatan">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Tanggal Dibuat : <span
                    id="TanggaPembuatan"></span></label>
        </div>
    </div>
    <div class="float-end">
        <td>

            <span style="text-align: center; font-weight: bold; color: #000;">&nbsp;&nbsp; Pilihan :&nbsp;</span>
            <span id="Pilihan"
                style="font-weight: bold; color: DodgerBlue; font-size: 20px; padding-left: 1px;">0</span>
        </td>
        <td>
            <span style="text-align: center; font-weight: bold; color: #000;">&nbsp;&nbsp; Total Pohon
                :&nbsp;</span>
            <span id="TotalPohon"
                style="font-weight: bold; color: DodgerBlue; font-size: 20px; padding-left: 1px;">0</span>
        </td>
        <td>
            <span style="text-align: center; font-weight: bold; color: #000;">&nbsp;&nbsp; Total Berat
                :&nbsp;</span>
            <span id="TotalBerat"
                style="font-weight: bold; color: DodgerBlue; font-size: 20px; padding-left: 1px;">0</span>
        </td>
    </div>
    <hr>
    <div class="table-responsive" style="height:calc(80vh);">
        <table class="table table-border table-hover table-sm" id="tabel1">
            <thead class="table-secondary sticky-top zindex-2">
                <tr style="text-align: center">
                    <th width="5%">PILL</th>
                    <th>Tanggal</th>
                    <th>No.Pohon</th>
                    <th>Ukuran</th>
                    <th>kadar</th>
                    <th>Brt.Lilin</th>
                    <th>Brt.Batu</th>
                    <th>Qty</th>
                    <th>SPK PPIC</th>
                    <th>Brt.Perkiraan</th>
                </tr>
            </thead>
            <tbody class="text-center">
            </tbody>
        </table>
        <table hidden class="table table-border table-hover table-sm" id="tabel2">
            <thead class="table-secondary sticky-top zindex-2">
                <tr style="text-align: center">
                    <th width="5%">NO</th>
                    <th>Tanggal</th>
                    <th>No.Pohon</th>
                    <th>Ukuran</th>
                    <th>kadar</th>
                    <th>Brt.Lilin</th>
                    <th>Brt.Batu</th>
                    <th>Qty</th>
                    <th>SPK PPIC</th>
                    <th id="show2">Brt.Perkiraan</th>
                </tr>
            </thead>
            <tbody class="text-center">
            </tbody>
        </table>
    </div>
</div>

<script>
$(document).on('keypress', 'input,textarea,select', function(e) {
    if (e.which == 13) {

        var posisi = parseFloat($(this).attr('tabindex')) + 1;
        $('[tabindex="' + posisi + '"]').focus();

        if (posisi != '2') {
            e.preventDefault();
        }
    }
});
</script>
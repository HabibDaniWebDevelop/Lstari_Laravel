<div class="card-body">
    <div class="row">
        <div class="col-9">
            <button type="button" class="btn btn-primary" id="btn_baru" onclick="KlikBaru()"> <span
                    class="tf-icons bx bx-plus-circle"></span>&nbsp; Baru </button>
            <button type="hidden" class="btn btn-primary me-4" disabled id="btn_edit" onclick="KlikEdit()"> <span
                    class="tf-icons bx bx-edit"></span>&nbsp; Ubah </button>
            <button type="button" class="btn btn-danger" disabled="" id="btn_batal" onclick="klikBatal()"> <span
                    class="fas fa-times-circle"></span>&nbsp; Batal</button>
            <button type="button" class="btn btn-warning me-4" disabled="" id="btn_simpan" onclick="KlikSimpan()"><span
                    class="tf-icons bx bx-save"></span>&nbsp; Simpan</button>

            <button type="button" class="btn btn-info" id="btn_cetak" onclick="klikCetak()" disabled=""> <span
                    class="tf-icons bx bx-printer"></span>&nbsp; Cetak</button>
            <input type="hidden" id="IDRubberout" value="" type="number">
            <input type="hidden" id="postingstatus" value="A">
            <input type="hidden" id="action" value="simpan" type="text">
            <input type="hidden" id="selscale"> {{-- Hidden input for timbangan --}}
        </div>
        <div class="col-3">
            <div class="float-end">
                <div class="input-group input-group-merge">
                    <span class="input-group-text"><i class="bx bx-search"></i></span>
                    <input type="text" class="form-control" placeholder="Search..." onchange="Search()" autofocus=""
                        id="cari" list="carilist">
                </div>
                <datalist id="carilist">
                    @foreach ($historyRubberout as $item)
                    <option value="{{$item->ID}}">{{$item->ID}}</option>
                    @endforeach
                </datalist>
            </div>
        </div>
    </div>

    <div class="row" id="show" hidden>
        <hr>
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
    <hr>
    <div>
        <table hidden class="table table-border table-hover table-sm rounded-4" id="tabel1">
            <thead class="table-secondary sticky-top zindex-2 rounded-4">
                <tr style="text-align: center">
                    <th width="6%"> NO </th>
                    <th width="10%">ID Karet</th>
                    <th width="20%">Barang</th>
                    <th width="10%">Kadar Karet</th>
                    <th width="10%">Pilih Lokasi</th>
                    <th width="20%">Lokasi</th>
                    <th width="10%" class="ilang">Action</th>
                </tr>
            </thead>
            <tbody class="text-center">
                <tr style="text-align: center">
                    <!-- urut -->
                    <td> 1
                        <input type='hidden' class='form-control form-control-sm fs-6 w-100 text-center nomor'
                            id='nomor_1' readonly value='1'>
                    </td>
                    <!-- IDKaret -->
                    <td><input type='number' class='form-control form-control-sm fs-6 w-100 text-center IDKaret'
                            onchange='getIDKaret(this.value,1)' id='IDKaret_1' value=''></td>
                    <!-- Barang -->
                    <td><span class='badge bg-primary' style='font-size:14px;' id='ItemProd1'></span><br>
                        <span class='badge text-dark bg-light' id='Description1'></span>
                        <input type='hidden' class='form-control form-control-sm fs-6 w-100 text-center IDProduct'
                            id='IDProduct_1' readonly value=''>
                    </td>
                    <!-- Kadar -->
                    <td><span class='badge' style='font-size:14px; background-color: #FFF' id='KadarKar1'></span><input
                            type='hidden' class='form-control form-control-sm fs-6 w-100 text-center IDKadar'
                            id='IDKadar_1' readonly value=''></td>
                    <!-- PilihLokasi -->
                    <td>
                        <button type="button" class="btn btn-primary pilihlemari" id="pilihlemari_1" value=""
                            onclick="pilihlemari(this.value,1)"> <span class="tf-icons bx bx-plus-circle"></span>&nbsp;
                            Lemari
                        </button>
                    </td>
                    <!-- Location -->
                    <td><span class="badge text-dark bg-light" style="font-size:16px;" id="Lok1">
                        </span><input type="hidden"
                            class="form-control form-control-sm fs-6 w-100 text-center IDLocation" id="IDLocation_1"
                            readonly value=""></td>
                    <td hidden><input type='hidden' class='ML' id='ML_1' value=''></td>
                    <td hidden><input type='hidden' class='MC' id='MC_1' value=''></td>
                    <td hidden><input type='hidden' class='MK' id='MK_1' value=''></td>
                    <td hidden><input type='hidden' class='MB' id='MB_1' value=''></td>
                    <!-- <td><span class='badge text-dark bg-light' style='font-size:16px;' id='sebelum1'></td> -->
                    <!-- Action -->
                    <td align='center' class='ilang'><button class='btn btn-info btn-sm' type='button' onclick='add(1)'
                            id='add_1'><i class='fa fa-plus'></i></button>&nbsp;&nbsp;&nbsp;<button type='button'
                            class='btn btn-danger btn-sm' onclick='remove(1)' id='remove_1'><i
                                class='fa fa-minus'></i></button></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
@include('Produksi.Lilin.RegistrasiKaret.PilihLemari')
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
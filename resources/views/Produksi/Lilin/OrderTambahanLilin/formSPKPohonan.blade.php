<div class="card mb-4">
    <ul class="nav nav-pills btn-group" role="group">
        <li class="nav-item col-5 mx-auto py-1">
            <button type="button" class="nav-link py-2 active" role="tab" data-bs-toggle="tab"
                data-bs-target="#FormPermintaanKomponen" aria-controls="FormPermintaanKomponen" aria-selected="true"
                onclick="permintaan3dp()"><b>Permintaan
                    Komponen ke 3DP</b>
            </button>
        </li>
        <li class="nav-item col-5 mx-auto py-1">
            <button type="button" class="nav-link py-2" role="tab" data-bs-toggle="tab" data-bs-target="#FormSPKPohonan"
                aria-controls="FormSPKPohonan" aria-selected="false" onclick="spk3dp()"><b>Pembuatan SPK
                    Pohonan</b></button>
        </li>
    </ul>
</div>

<div class="tab-content">
    <!-- ////////////////////////////////////////////////// TAB 1 ///////////////////////////////////////////////// -->

    <div class="tab-pane fade active show" id="FormPermintaanKomponen" role="tabpanel">
        <div class="row">
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label" for="basic-icon-default-fullname">ID SPK 3DP</label>
                            <div class="input-group input-group-merge">

                                <input class="form-control" id="idspk3dp" type="" name="idspk3dp" value=""
                                    disabled="true">
                                <!-- value berisi id saat memlakuka proses permintaan ke 3dp -->
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label" for="basic-icon-default-fullname">Total Qty</label>
                            <div class="input-group input-group-merge">
                                <span id="basic-icon-default-fullname2" class="input-group-text"
                                    style="background-color: #F0F0F0;"><i class="fas fa-equals"></i></span>
                                <input class="form-control" id="totalQty" type="number" name="totalQty" value=""
                                    disabled="true">
                                <!-- value berisi qty dari hasil penjumlahan total qty yang dipilih -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-12">
            <div class="row">
                <div class="col-md-6">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label" for="basic-icon-default-fullname">Kadar</label>
                                <div class="input-group input-group-merge"><input type="text" class="form-control"
                                        id="KadarPohonan" placeholder="" disabled value="" /></div>
                                <input type="hidden" id="KadarIdPohonan" value="">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label" for="basic-icon-default-fullname">RPH</label>
                                <div class="input-group input-group-merge"><input type="text" class="form-control"
                                        id="RphLilinPohonan" placeholder="" disabled value="" /></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="row">
                        <label class="form-label" for="basic-icon-default-fullname">SPK ORDER</label>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <div class="input-group input-group-merge">
                                    <input type="text" list="ListSWWorkOrderDC"
                                        class="form-control border-primary border-4" id="SWWorkOrderDC" placeholder=""
                                        tabindex="7" />
                                </div>
                                <datalist id="listSWWorkOrderDC">
                                    @foreach ($ListSWWorkOrderDC AS $SW)
                                    <option value="{{ $SW->SW }}">{{ $SW->SW }}</option>
                                    @endforeach
                                </datalist>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <div class="d-grid gap-2">
                                    <button type="button" class="btn btn-primary" id="daftarpropohon"
                                        onclick="KlickDaftarProductPohonan()">
                                        <span class="tf-icons bx bx-list-ul"></span>&nbsp; <b>PRODUCT</b> Direct
                                        Casting
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-12">
            <div class="row">
                <div class="col-md-12">
                    <label class="form-label" for="basic-icon-default-fullname">Catatan</label>
                    <div class="input-group input-group-merge"><span id="basic-icon-default-fullname2"
                            class="input-group-text"><i class="far fa-sticky-note"></i></span>
                        <input class="form-control" id="note" type="text" name="note" value="" tabindex="9">
                    </div>
                </div>
            </div>
        </div>
        <div class="d-nonePohonan" id="ShowTabelItemPohonan">

        </div>
    </div>

    <!-- ////////////////////////////////////////////////// TAB 2 ///////////////////////////////////////////////// -->
    <div class="tab-pane fade" id="FormSPKPohonan" role="tabpanel">
        <div class="row">
            <div class="col-md-12">
                <div class="col-md-4 mx-auto">
                    <div class="card mb-4">
                        <div class="input-group input-group-merge">
                            <span class="input-group-text"><i class="bx bx-search"
                                    onclick="klikViewSelection()"></i></span>
                            <input type="text" class="form-control form-control-lg" id="IDtm3dp"
                                placeholder="Masukkan ID TM dari 3DP" autofocus id='IDlist' list="IDlist"
                                onchange="ChangeTM()" />
                        </div>
                        <datalist id="IDlist">
                            @foreach ( $DaftarIdTmResinSudahDiposting AS $TM)
                            <option value="{{ $TM->ID }}">{{ $TM->ID }}</option>
                            @endforeach
                        </datalist>
                    </div>
                </div>
            </div>
        </div>
        <div class="showtabel2" id="showtabel2">

        </div>
    </div>
</div>

<script>
$(document).on('keypress', 'input,textarea,select', function(e) {
    if (e.which == 13) {

        var posisi = parseFloat($(this).attr('tabindex')) + 1;
        $('[tabindex="' + posisi + '"]').focus();

        if (posisi != '1' && posisi != '3') {
            e.preventDefault();
        }
        // console.log(posisi);
    }
});
</script>
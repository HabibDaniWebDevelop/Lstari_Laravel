<div class="row">
    <!-- Basic -->
    <div class="col-md-12">
        <div class="row">
            <div class="col-md-6 px-5">
                <div class="row">
                    <label class="form-label" for="basic-icon-record-fill">ID</label>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <div class="input-group input-group-merge"><span id="basic-icon-default-fullname2"
                                    class="input-group-text" style="background-color: #F0F0F0;"><i
                                        class="far fa-id-badge"></i></span>
                                <input type="number" class="form-control" id="IDSPKINJECT" value="" readonly
                                    tabindex="0" />
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <div class="d-grid gap-2">
                                <button type="button" class="btn btn-info" id="Cetakbarkode" disabled
                                    onclick="printbarkode()">
                                    <span class="tf-icons bx bx-printer"></span>&nbsp; Cetak Barcode</button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label class="form-label" for="basic-icon-default-fullname">ID Operator</label>
                            <div class="input-group input-group-merge">
                                <input type="text" class="form-control" id="IdOperator" placeholder="Masukkan ID "
                                    onkeyup="isioperator()" value="" tabindex="1" />
                            </div>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="mb-3">
                            <label class="form-label" for="basic-icon-default-fullname">Nama Operator</label>
                            <div class="input-group input-group-merge">
                                <span id="basic-icon-default-fullname2" class="input-group-text"
                                    style="background-color: #F0F0F0;"><i class="fas fa-user"></i></span>
                                <input type="text" class="form-control" id="NamaOperator" placeholder="nama operator"
                                    style="font-weight: bold; color: #80271c;" disabled value="" />
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label" for="basic-icon-default-fullname">Kadar</label>
                    <div class="input-group input-group-merge" id="kadar1">
                        <select class="form-select" id="kadar" tabindex="2">
                            <option value="" selected>-- Pilih kadar --</option>
                            @foreach ($kadar AS $kdr)
                            <option value="{{ $kdr->ID }}">{{ $kdr->Description }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label" for="basic-icon-default-fullname">label Piring</label>
                            <div class="input-group input-group-merge">
                                <input type="text" class="form-control" id="LabelPiring" placeholder=""
                                    onkeyup="isipiring()" value="" tabindex="3" />
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label" for="basic-icon-default-fullname">ID Piring</label>
                            <div class="input-group input-group-merge">
                                <span id="basic-icon-default-fullname2" class="input-group-text"
                                    style="background-color: #F0F0F0;"><i class="far fa-circle"></i></span>
                                <input type="text" class="form-control" id="IdPiring" placeholder=""
                                    style="font-weight: bold; color: #80271c;" disabled value="" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 px-5">
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label" for="basic-icon-calendar">Tanggal</label>
                            <div class="input-group input-group-merge">
                                <span id="basic-icon-calendar" class="input-group-text"><i
                                        class="bx bx-calendar"></i></span>
                                <input type="date" class="form-control" id="date" name="date"
                                    value="{{ date('Y-m-d'); }}" tabindex="4" />

                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label" for="basic-icon-default-fullname">Total Qty</label>
                            <div class="input-group input-group-merge">
                                <span id="basic-icon-default-fullname2" class="input-group-text"><i
                                        class="fas fa-equals"></i></span>
                                <input type="text" class="form-control" id="FormTotalQty"
                                    style="font-weight: bold; color: #80271c;" value="" placeholder="0" />
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">

                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label" for="basic-icon-default-fullname">Kelompok</label>
                            <div class="input-group input-group-merge">
                                <span id="basic-icon-default-fullname2" class="input-group-text"><i
                                        class="fas fa-grip-horizontal"></i></span>
                                <input type="number" class="form-control" id="kelompok"
                                    style="font-weight: bold; color: #80271c;" placeholder="" min="1" max="16"
                                    id="kelompok" tabindex="5" />
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label" for="basic-icon-default-fullname">Kotak</label>
                            <div class="input-group input-group-merge">
                                <span id="basic-icon-default-fullname2" class="input-group-text"><i
                                        class="fas fa-box"></i></span>
                                <input type="number" class="form-control" id="kotak"
                                    style="font-weight: bold; color: #80271c;" placeholder="" min="1" max="80"
                                    tabindex="6" />
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <label class="form-label" for="basic-icon-default-fullname">RPH Lilin</label>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <div class="input-group input-group-merge">
                                <input type="text" list="listrphlilin" class="form-control" id="rphlilin" placeholder=""
                                    tabindex="7" />
                            </div>
                            <datalist id="listrphlilin">
                                @foreach ($rphlilin AS $rphl)
                                <option value="{{ $rphl->ID }}">{{ $rphl->ID }}</option>
                                @endforeach
                            </datalist>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <div class="d-grid gap-2">
                                <button type="button" class="btn btn-primary" id="daftarpro"
                                    onclick="Klickdaftarproduct()">
                                    <span class="tf-icons bx bx-list-ul"></span>&nbsp; Daftar Product </button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label" for="basic-icon-default-fullname">Stick Pohon</label>
                    <div class="input-group input-group-merge" id="pohon">
                        <select class="form-select" id="stickpohon" tabindex="8">
                            <option value="" selected> </option>
                            @foreach ($stickpohon AS $stickp)
                            <option value="{{ $stickp->ID }}">
                                {{ $stickp->stickpohon }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <div class="mb-3 px-5">
                <label class="form-label" for="basic-icon-default-fullname">Catatan</label>
                <div class="input-group input-group-merge"><span id="basic-icon-default-fullname2"
                        class="input-group-text"><i class="far fa-sticky-note"></i></span>

                    <input type="text" class="form-control" id="catatan" placeholder="Isi catatan bila perlu"
                        tabindex="9" />
                </div>
            </div>
        </div>
        <input type="hidden" id="hiddenid3dp" value="">
    </div>
    <div class="d-none" id="showtabel">

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

    }
});
</script>
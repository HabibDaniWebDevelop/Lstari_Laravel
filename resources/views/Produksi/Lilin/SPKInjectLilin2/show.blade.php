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
                                <input type="text" class="form-control" id="idspki" value="{{ $cariId[0]->ID}}"
                                    readonly />
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-3">
                            <div class="d-grid gap-2">
                                <button type="button" class="btn btn-info" id="Cetak1" onclick="printbarkode()">
                                    <span class="tf-icons bx bx-printer"></span>&nbsp; Cetak Barcode</button>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="mb-3">
                            <div class="d-grid gap-2">
                                <button type="button" class="btn btn-danger" id="Cetak1" onclick="printbarkodetes()">
                                    <span class="tf-icons bx bx-printer"></span>Tes</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label class="form-label" for="basic-icon-default-fullname">ID Operator</label>
                            <div class="input-group input-group-merge">
                                <input type="text" class="form-control" id="IdOperator" value="{{ $cariId[0]->IDK }}"
                                    readonly />
                            </div>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="mb-3">
                            <label class="form-label" for="basic-icon-default-fullname">Nama Operator</label>
                            <div class="input-group input-group-merge">
                                <span id="basic-icon-default-fullname2" class="input-group-text"
                                    style="background-color: #F0F0F0;"><i class="fas fa-user"></i></span>
                                <input type="text" class="form-control" id="emp" value="{{ $cariId[0]->emp }}"
                                    readonly />
                            </div>
                        </div>
                    </div>
                </div>


                <div class="mb-3">
                    <label class="form-label" for="basic-icon-default-fullname">Kadar</label>
                    <div class="input-group input-group-merge" id="kadar1">
                        <input type="text" class="form-control" id="kadar" value="{{ $cariId[0]->kadar }}" readonly />
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label" for="basic-icon-default-fullname">Piringan Karet</label>
                            <div class="input-group input-group-merge">

                                <input type="text" class="form-control" id="piring" value="{{ $cariId[0]->pkaret}}"
                                    readonly />
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label" for="basic-icon-default-fullname">ID Piring</label>
                            <div class="input-group input-group-merge">
                                <span id="basic-icon-default-fullname2" class="input-group-text"
                                    style="background-color: #F0F0F0;"><i class="far fa-circle"></i></span>
                                <input type="text" class="form-control" id="IdPiring" placeholder="ID Piring" disabled
                                    value="{{ $cariId[0]->RubberPlate}}" />
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
                                <span id="basic-icon-calendar" class="input-group-text"
                                    style="background-color: #F0F0F0;"><i class="bx bx-calendar"></i></span>
                                <input type="text" class="form-control" id="date" value="{{ $cariId[0]->TransDate}}"
                                    readonly />
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label" for="basic-icon-default-fullname">Total Qty</label>
                            <div class="input-group input-group-merge">
                                <span id="basic-icon-default-fullname2" class="input-group-text"
                                    style="background-color: #F0F0F0;"><i class="fas fa-equals"></i></span>
                                <input type="text" class="form-control" id="TQty" value="{{ $cariId[0]->Qty}}"
                                    readonly />
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label" for="basic-icon-default-fullname">Kelompok</label>
                            <div class="input-group input-group-merge">
                                <span id="basic-icon-default-fullname2" class="input-group-text"
                                    style="background-color: #F0F0F0;"><i class="fas fa-grip-horizontal"></i></span>
                                <input type="text" class="form-control" id="kelompok" value="{{ $cariId[0]->WorkGroup}}"
                                    readonly />
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label" for="basic-icon-default-fullname">Kotak</label>
                            <div class="input-group input-group-merge">
                                <span id="basic-icon-default-fullname2" class="input-group-text"
                                    style="background-color: #F0F0F0;"><i class="fas fa-box"></i></span>
                                <input type="text" class="form-control" id="kotak" value="{{ $cariId[0]->BoxNo}}"
                                    readonly />
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label" for="basic-icon-default-fullname">Stick Pohon</label>
                    <div class="input-group input-group-merge"><span id="basic-icon-default-fullname2"
                            style="background-color: #F0F0F0;" class=" input-group-text"><i
                                class="fas fa-tree"></i></span>
                        <input type="text" class="form-control" id="stick" value="{{ $cariId[0]->stickpohon }}"
                            readonly />
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label" for="basic-icon-default-fullname">User</label>
                            <div class="input-group input-group-merge">
                                <span id="basic-icon-default-fullname2" class="input-group-text"
                                    style="background-color: #F0F0F0;"><i class="far fa-user"></i></span>
                                <input type="text" class="form-control" id="user" value="{{ $cariId[0]->UserName }}"
                                    readonly />
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label" for="basic-icon-default-fullname">Entry Date</label>
                            <div class="input-group input-group-merge">
                                <span id="basic-icon-default-fullname2" class="input-group-text"
                                    style="background-color: #F0F0F0;"><i class="far fa-calendar"></i></span>
                                <input type="text" class="form-control" id="entrydate"
                                    value="{{ $cariId[0]->EntryDate }}" readonly />
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <div class="mb-3 px-5">
                <label class="form-label" for="basic-icon-default-fullname">Catatan</label>
                <div class="input-group input-group-merge"><span id="basic-icon-default-fullname2"
                        style="background-color: #F0F0F0;" class="input-group-text"><i
                            class="far fa-sticky-note"></i></span>
                    <input type="text" class="form-control" id="catatan" value="{{ $cariId[0]->Remarks}}" readonly />
                </div>
            </div>
        </div>
        <input type="hidden" id="hiddenid3dp" value="">
    </div>

    <div class="btn-group pt-4 pb-2" role="group" aria-label="Basic radio toggle button group">
        <input type="radio" class="btn-check d-none" name="btnradio" id="Item" herf="#tabel1" onclick="item()" checked
            autocomplete="off" />
        <label class="btn btn-outline-primary" for="Item"> Item</label>

        <input type="radio" class="btn-check" name="btnradio" id="karetdipilih" herf="#tabel2" onclick="karetdipilih()"
            autocomplete="off" />
        <label class="btn btn-outline-primary" for="karetdipilih">Karet Dipilih</label>

        <input type="radio" class="btn-check" name="btnradio" id="batu" herf="#tabel3" onclick="batu()"
            autocomplete="off" />
        <label class="btn btn-outline-primary" for="batu">Batu</label>

        <input type="radio" class="btn-check" name="btnradio" id="totalbatu" herf="#tabel5" onclick="totalbatu()"
            autocomplete="off" />
        <label class="btn btn-outline-primary" for="totalbatu">Total batu</label>

        <input type="radio" class="btn-check" name="btnradio" id="karetpilihan" herf="#tabel6" onclick="karetpilihan()"
            autocomplete="off" />
        <label class="btn btn-outline-primary" for="karetpilihan">Karet Pilihan</label>
    </div>

    <div id="tampilitem" class="d-none">

    </div>

    <div class="display" id="tambahitem"></div>
</div>

<!-- modal daftar product -->
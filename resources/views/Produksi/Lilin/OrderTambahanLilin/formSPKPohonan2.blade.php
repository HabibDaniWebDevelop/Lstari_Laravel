<div class="row card">
    <div class="col-md-12">
        <div class="row">
            <div class="col-md-6">
                <label class="form-label" for="basic-icon-record-fill">ID</label>
                <div class="row">
                    <div class="col-md-8">
                        <div class="mb-3">
                            <div class="input-group input-group-merge"><span id="basic-icon-default-fullname2"
                                    class="input-group-text" style="background-color: #F0F0F0;"><i
                                        class="far fa-id-badge"></i></span>
                                <input class="form-control" id="IDSPKpohonan" type="number" name="IDSPKpohonan" value=""
                                    disabled="true">
                                <!-- value berisi ID dari hasil simpan form ditampikan saat cetak -->
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="mb-3">

                            <div class="d-grid gap-2">
                                <button type="button" class="btn btn-info" id="Cetakbarkode" disabled
                                    onclick="printbarkode()">
                                    <span class="tf-icons bx bx-printer"></span>&nbsp; Cetak Barcode</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label" for="basic-icon-default-fullname">Tanggal</label>
                            <input class="form-control" id="tgl" type="date" name="tgl" value="{{ date('Y-m-d'); }}"
                                tabindex="4">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label" for="basic-icon-default-fullname">Total Qty</label>
                            <div class="input-group input-group-merge">
                                <span id="basic-icon-default-fullname2" class="input-group-text"
                                    style="background-color: #F0F0F0;"><i class="fas fa-equals"></i></span>
                                <input class="form-control" id="totalQty" type="number" name="totalQty"
                                    value="{{$dapattm3dp[0]->TQty}}" disabled="true">

                                <!-- value berisi qty dari hasil penjumlahan total qty yang dipilih -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="row">
                    <div class="col-md-4">
                        <label class="form-label" for="basic-icon-default-fullname">ID Operator</label>
                        <div class="input-group input-group-merge">
                            <input type="text" class="form-control" id="Employee" name="employee"
                                placeholder="Masukkan ID " onkeyup="isioperator()" value="" tabindex="1" />
                        </div>
                    </div>
                    <div class="col-md-8">
                        <label class="form-label" for="basic-icon-default-fullname">nama
                            Operator</label>
                        <div class="mb-3">
                            <div class="input-group input-group-merge">
                                <span id="basic-icon-default-fullname2" class="input-group-text"
                                    style="background-color: #F0F0F0;"><i class="fas fa-user"></i></span>
                                <input type="text" class="form-control" id="NamaOperator" placeholder="nama operator"
                                    disabled value="" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label" for="basic-icon-default-fullname">Kelompok</label>
                            <div class="input-group input-group-merge">
                                <span id="basic-icon-default-fullname2" class="input-group-text"><i
                                        class="fas fa-grip-horizontal"></i></span>
                                <input class="form-control" id="WorkGroup" type="number" name="WorkGroup" value="1"
                                    min="1" tabindex="5">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label" for="basic-icon-default-fullname">Kotak</label>
                            <div class="input-group input-group-merge">
                                <span id="basic-icon-default-fullname2" class="input-group-text"><i
                                        class="fas fa-box"></i></span>
                                <input class="form-control" id="BoxNo" type="number" name="BoxNo" value="1" min="1"
                                    tabindex="6">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label" for="basic-icon-default-fullname">Kadar</label>
                    <input class="form-control" id="kadar2" type="" name="kadar2"
                        value="{{$dapattm3dp[0]->descriptioncarat}}" disabled="true">
                    <input class="form-control" id="CaratFormLengkapSPKPohon" type="hidden"
                        name="CaratFormLengkapSPKPohon" value="{{$dapattm3dp[0]->carat}}" disabled="true">
                </div>
            </div>

            <div class="col-md-6">
                <label class="form-label" for="basic-icon-default-fullname">Rph Lilin</label>
                <input class="form-control" id="RPHFormLengkapSPKPohonan" type="" name="RPHFormLengkapSPKPohonan"
                    value="{{$dapattm3dp[0]->rph}}" disabled="true" onchange="RPH()">
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label" for="basic-icon-default-fullname">label
                                Piring <b>D</b></label>
                            <div class="input-group input-group-merge">
                                <input type="text" class="form-control" id="Plate" name="Plate"
                                    placeholder="Masukkan Label Piring D" onkeyup="isipiring()" value="D"
                                    tabindex="3" />
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label" for="basic-icon-default-fullname">ID
                                Piring</label>
                            <div class="input-group input-group-merge">
                                <span id="basic-icon-default-fullname2" class="input-group-text"
                                    style="background-color: #F0F0F0;"><i class="far fa-circle"></i></span>
                                <input type="text" class="form-control" id="IdPiring" name="IdPiring"
                                    placeholder="ID Piring" disabled value="" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <label class="form-label" for="basic-icon-default-fullname">Stick Pohon</label>
                <select class="form-select" id="StickPohon" name="StickPohon" required tabindex="8">
                    @foreach ($stickPohon as $stick)
                    <option value="{{ $stick->ID}}" selected>{{ $stick->stickpohon }}
                    </option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="mb-3">
                    <label class="form-label" for="basic-icon-default-fullname">Catatan</label>
                    <div class="input-group input-group-merge"><span id="basic-icon-default-fullname2"
                            class="input-group-text"><i class="far fa-sticky-note"></i></span>
                        <input class="form-control" id="note" type="text" name="note" value="" tabindex="9">
                        <input type="hidden" id="workorder" value="{{$dapattm3dp[0]->workorder}}">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="showtabelspkpohon" id="showtabelspkpohon">

    </div>
</div>
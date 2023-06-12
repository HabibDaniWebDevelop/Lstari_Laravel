<div class="card-body">
    <div class="row">
        <div class="col-9">

            <button type="button" class="btn btn-primary" id="Baru1" onclick="Klik_Baru1()"> <span
                    class="tf-icons bx bx-plus-circle"></span>&nbsp; Baru </button>
            <button type="button" class="btn btn-primary me-4" id="Ubah1" disabled onclick="Klik_Ubah1()"> <span
                    class="tf-icons bx bx-edit"></span>&nbsp;
                Ubah</button>
            <button type="button" class="btn btn-danger" id="Batal1" disabled onclick="Klik_Batal1()"> <span
                    class="fas fa-times-circle"></span>&nbsp;
                Batal</button>
            <button type="button" class="btn btn-warning me-4" id="Simpan1" disabled onclick="Klik_Simpan1()">
                <span class="tf-icons bx bx-save"></span>&nbsp; Simpan</button>
            <button type="button" class="btn btn-info" id="Cetak1" disabled onclick="Klik_Cetak1()">
                <span class="tf-icons bx bx-printer"></span>&nbsp; Cetak SPKO </button>
            <button type="button" class="btn btn-info" id="Cetak2" onclick="Klik_Cetak2()"> <span
                    class="tf-icons bx bx-printer"></span>&nbsp; Cetak Ulang SPK3DP</button>
        </div>
        <div class="col-3">
            <div class="float-end">
                <input type="hidden" id="idwaxinjectorder" value="" type="number">
                <input type="hidden" id="action" value="simpan" type="text">
                <input type="hidden" id="spkppicdipilih" value="">
                <input type="hidden" id="checkSWO" value="">
                <div class="input-group input-group-merge">
                    <span class="input-group-text"><i class="bx bx-search" onclick="klikViewSelection()"></i></span>
                    <input type="text" class="form-control" placeholder="Search..." autofocus id='cari' list="carilist"
                        onchange="ChangeCari()" />
                </div>
                <datalist id="carilist">
                    @foreach ($IDWaxInject AS $ID)
                    <option value="{{ $ID->ID }}">{{ $ID->ID }}</option>
                    @endforeach
                </datalist>
            </div>
        </div>
    </div>
    <hr>

    <div class="row">
        <div class="col-3">
            <label class="form-label" for="basic-icon-record-fill">ID</label>
            <div class="input-group input-group-merge"><span id="basic-icon-default-fullname2" class="input-group-text"
                    style="background-color: #F0F0F0;"><i class="far fa-id-badge"></i></span>
                <input type="number" class="form-control" id="IDSPKINJECT" value="" readonly tabindex="0" />
            </div>
        </div>
        <div class="col-3">
            <label class="form-label" for="basic-icon-record-fill">&nbsp;</label>
            <div class="d-grid gap-2">
                <button type="button" class="btn btn-info" id="Cetakbarkode" disabled onclick="printbarkode()">
                    <span class="tf-icons bx bx-printer"></span>&nbsp; Cetak Barcode</button>
            </div>
        </div>
        <div class="col-3">
            <label class="form-label" for="basic-icon-calendar">Tanggal</label>
            <div class="input-group input-group-merge">
                <span id="basic-icon-calendar" class="input-group-text"><i class="bx bx-calendar"></i></span>
                <input readonly type="date" class="form-control" id="date" name="date" value="{{ date('Y-m-d'); }}"
                    tabindex="4" />
            </div>
        </div>
        <div class="col-3">
            <label class="form-label" for="FromTotalQty">Total Qty</label>
            <div class="input-group input-group-merge">
                <span id="basic-icon-default-fullname2" class="input-group-text"><i class="fas fa-equals"></i></span>
                <input readonly type="text" class="form-control" id="FormTotalQty"
                    style="font-weight: bold; color: #80271c;" value="" placeholder="0" />
            </div>
        </div>

    </div>

    <div class="row">
        <div class="col-3">
            <label class="form-label" for="basic-icon-default-fullname">ID Operator</label>
            <div class="input-group input-group-merge">
                <input readonly type="text" class="form-control" id="IdOperator" placeholder="Masukkan ID "
                    onkeyup="isioperator()" value="" tabindex="1" />
            </div>
        </div>
        <div class="col-3">
            <label class="form-label" for="basic-icon-default-fullname">Nama Operator</label>
            <div class="input-group input-group-merge">
                <span id="basic-icon-default-fullname2" class="input-group-text" style="background-color: #F0F0F0;"><i
                        class="fas fa-user"></i></span>
                <input type="text" class="form-control" id="NamaOperator" placeholder="nama operator"
                    style="font-weight: bold; color: #80271c;" disabled value="" />
            </div>
        </div>
        <div class="col-3">
            <label class="form-label" for="basic-icon-default-fullname">Kelompok</label>
            <div class="input-group input-group-merge">
                <span id="basic-icon-default-fullname2" class="input-group-text"><i
                        class="fas fa-grip-horizontal"></i></span>
                <input readonly type="number" class="form-control" id="kelompok" onchange="kelompok()"
                    style="font-weight: bold; color: #80271c;" placeholder="" min="1" max="16" tabindex="5" />
            </div>
        </div>
        <div class="col-3">
            <label class="form-label" for="basic-icon-default-fullname">Kotak</label>
            <div class="input-group input-group-merge">
                <span id="basic-icon-default-fullname2" class="input-group-text"><i class="fas fa-box"></i></span>
                <input readonly type="number" class="form-control" id="kotak" onchange="kotak()"
                    style="font-weight: bold; color: #80271c;" placeholder="" min="1" max="80" tabindex="6" />
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-6">
            <label class="form-label" for="basic-icon-default-fullname">Kadar : <span id="idkadar"></span>
            </label>
            <div class="input-group input-group-merge" id="kadar1">
                <select disabled class="form-select" id="kadar" tabindex="2" onchange="klikkadar()">
                    <option value="" selected>-- Pilih kadar --</option>
                    @foreach ($kadar AS $kdr)
                    <option value="{{ $kdr->ID }}">{{ $kdr->Description }}</option>
                    @endforeach
                </select>
            </div>
            <input hidden type="text" class="form-control" id="kadarshow" placeholder=""
                style="font-weight: bold; color: #80271c;" disabled value="" />
        </div>
        <div class="col-3">
            <label class="form-label" for="basic-icon-default-fullname">RPH Lilin</label>
            <div class="col-md-12">
                <div class="mb-3">
                    <div class="input-group input-group-merge">
                        <input readonly type="text" list="listrphlilin" class="form-control" id="rphlilin"
                            placeholder="" tabindex="7" />
                    </div>
                    <datalist id="listrphlilin">
                        @foreach ($rphlilin AS $rphl)
                        <option value="{{ $rphl->ID }}">{{ $rphl->ID }}</option>
                        @endforeach
                    </datalist>
                </div>
            </div>
        </div>
        <div class="col-3">
            <label class="form-label" for="basic-icon-record-fill">&nbsp;</label>
            <div class="d-grid gap-2">
                <button disabled type="button" class="btn btn-primary" id="daftarpro" onclick="Klickdaftarproduct()">
                    <span class="tf-icons bx bx-list-ul"></span>&nbsp; Daftar Product </button>
            </div>
        </div>
    </div>


    <div class="row">
        <div class="col-3">
            <label class="form-label" for="basic-icon-default-fullname">label Piring</label>
            <div class="input-group input-group-merge">
                <input readonly type="text" class="form-control" id="LabelPiring" placeholder="" onkeyup="isipiring()"
                    value="" tabindex="3" />
            </div>
        </div>
        <div class="col-3">
            <label class="form-label" for="basic-icon-default-fullname">ID Piring</label>
            <div class="input-group input-group-merge">
                <span id="basic-icon-default-fullname2" class="input-group-text" style="background-color: #F0F0F0;"><i
                        class="far fa-circle"></i></span>
                <input type="text" class="form-control" id="IdPiring" placeholder=""
                    style="font-weight: bold; color: #80271c;" disabled value="" />
            </div>
        </div>
        <div class="col-6">
            <label class="form-label" for="basic-icon-default-fullname">Stick Pohon</label>
            <div class="input-group input-group-merge" id="pohon">
                <select disabled class="form-select" id="stickpohon" onchange="stick()" tabindex="8">
                    <option value="" selected> </option>
                    @foreach ($stickpohon AS $stickp)
                    <option value="{{ $stickp->ID }}">
                        {{ $stickp->stickpohon }}</option>
                    @endforeach
                </select>
            </div>
            <input hidden type="text" class="form-control" id="stickpohonshow" placeholder=""
                style="font-weight: bold; color: #80271c;" disabled value="" />
        </div>

        <div class="row">
            <div class="col-12">
                <label class="form-label" for="basic-icon-default-fullname">Catatan</label>
                <div class="input-group input-group-merge"><span id="basic-icon-default-fullname2"
                        class="input-group-text"><i class="far fa-sticky-note"></i></span>

                    <input readonly type="text" class="form-control" id="catatan" placeholder="Isi catatan bila perlu"
                        tabindex="9" />
                </div>
            </div>
        </div>
    </div>

    <div hidden class="row" id="show">
        <div class="col-md-6">
            <div class="mb-3">
                <label class="form-label" for="basic-icon-default-fullname">User</label>
                <div class="input-group input-group-merge">
                    <span id="basic-icon-default-fullname2" class="input-group-text"
                        style="background-color: #F0F0F0;"><i class="far fa-user"></i></span>
                    <input type="text" class="form-control" id="user" value="" readonly />
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="mb-3">
                <label class="form-label" for="basic-icon-default-fullname">Entry Date</label>
                <div class="input-group input-group-merge">
                    <span id="basic-icon-default-fullname2" class="input-group-text"
                        style="background-color: #F0F0F0;"><i class="far fa-calendar"></i></span>
                    <input type="text" class="form-control" id="entrydate" value="" readonly />
                </div>
            </div>
        </div>
    </div>

    <hr>

    <div hidden id="tampil1" class="form">
        <div class="card mx-2">
            <ul class="nav nav-pills btn-group" role="group">
                <li class="nav-item col-2 mx-auto">
                    <button type="radio" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#tabelItem"
                        aria-controls="tabelItem" aria-selected="true"> Item
                    </button>
                </li>
                <li hidden class="nav-item col-2 mx-auto" id="li2">
                    <button type="radio" class="nav-link " role="tab" data-bs-toggle="tab"
                        data-bs-target="#karetdipilih" aria-controls="karetdipilih" aria-selected="false"> karetdipilih
                    </button>
                </li>

                <li hidden class="nav-item col-2 mx-auto" id="li3">
                    <button type="radio" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#tabelBatu"
                        aria-controls="tabelBatu" aria-selected="true"> Batu
                    </button>
                </li>
                <li hidden class="nav-item col-2 mx-auto" id="li4">
                    <button type="radio" class="nav-link " role="tab" data-bs-toggle="tab" data-bs-target="#TotalBatu"
                        aria-controls="TotalBatu" aria-selected="false"> TotalBatu
                    </button>
                </li>
                <li class="nav-item col-2 mx-auto">
                    <button type="radio" class="nav-link " role="tab" data-bs-toggle="tab" data-bs-target="#tabelKaret"
                        aria-controls="tabelKaret" aria-selected="false"> karet Pilihan
                    </button>
                </li>
                <li class="nav-item col-2 mx-auto" id="li6">
                    <button type="radio" class="nav-link active" role="tab" data-bs-toggle="tab" onclick="OrderitemDc()"
                        aria-selected="false"> Item Direct Casting </button>
                </li>
            </ul>
        </div>


        <div class="tab-content">
            {{-- tab1 --}}
            <div class="tab-pane fade active show" id="tabelItem" role="tabpanel">
                <div class="table-responsive text-nowrap rounded-4" style="height:calc(70vh);">
                    <table class="table table-border table-hover table-sm rounded-4" id="tabel1">
                        <thead class="table-secondary sticky-top zindex-2 rounded-4">
                            <tr style="text-align: center">
                                <th>No</th>
                                <th>SPK PPIC</th>
                                <th>Barang jadi</th>
                                <th width="8%">&nbsp;&nbsp;Qty&nbsp;&nbsp;</th>
                                <th width="5%">Inject</th>
                                <th width="15%">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Tok&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
                                <th width="15%">SC</th>
                                <th>Rekap Lilin</th>
                                <th>Stone Note</th>
                                <th id="Action">Action</th>
                            </tr>
                        </thead>
                        <tfoot>
                        </tfoot>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
            {{-- End tab1--}}

            {{-- Panel tab2 --}}
            <div class="tab-pane fade" id="karetdipilih" role="tabpanel">
                <div class="table-responsive text-nowrap rounded-4" style="height:calc(100vh - 490px);">
                    <table class="table table-border table-hover table-sm rounded-4" id="tabel2">
                        <thead class="table-secondary sticky-top zindex-2 rounded-4">
                            <tr style="text-align: center">
                                <th>No</th>
                                <th>ID Karet</th>
                                <th>Model</th>
                                <th>PCS</th>
                                <th>Kadar</th>
                                <th>Ukuran</th>
                                <th>Digunakan</th>
                                <!-- <th>Hasil OK</th>
                                <th>Hasil SS</th> -->
                                <th>Tanggal Buat</th>
                                <th>Status</th>
                                <!-- <th>Ukuran</th> -->
                                <th>SC</th>
                                <th>Lokasi</th>
                                <th>Lihat</th>
                            </tr>
                        </thead>
                        <tfoot>

                        </tfoot>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
            {{-- End Panel tab2 --}}

            {{-- Panel tab3 --}}
            <div class="tab-pane fade" id="tabelBatu" role="tabpanel">
                <div class="table-responsive text-nowrap rounded-4" style="height:calc(100vh - 490px);">
                    <table class="table table-border table-hover table-sm rounded-4" id="tabel3">
                        <thead class="table-secondary sticky-top zindex-2 rounded-4">
                            <tr style="text-align: center">
                                <th>No</th>
                                <th>SPK PPIC</th>
                                <th>Barang Jadi</th>
                                <th>Inject</th>
                                <th>Jenis Batu</th>
                                <th>Pesan</th>
                                <th>@</th>
                                <th>Total</th>
                                <th>Keterangan Batu</th>
                            </tr>
                        </thead>
                        <tfoot>
                        </tfoot>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
            {{-- End Panel tab3 --}}

            {{-- Panel tab4 --}}
            <div class="tab-pane fade" id="TotalBatu" role="tabpanel">
                <div class="table-responsive text-nowrap rounded-4" style="height:calc(100vh - 490px);">
                    <table class="table table-border table-hover table-sm rounded-4" id="tabel5">
                        <thead class="table-secondary sticky-top zindex-2 rounded-4">
                            <tr style="text-align: center">
                                <th>No</th>
                                <th>Jenis</th>
                                <th>Batu</th>
                            </tr>
                        </thead>
                        <tfoot>
                        </tfoot>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
            {{-- End Panel tab4 --}}

            {{-- Panel tab5 --}}
            {{-- End Panel tab5 --}}

            {{-- Panel tab6 --}}
            <div class="tab-pane fade" id="tabelKaret" role="tabpanel">
                <div class="table-responsive text-nowrap rounded-4" style="height:calc(100vh);">
                    <table class="table table-border table-hover table-sm rounded-4" id="tabel6">
                        <thead class="table-secondary sticky-top zindex-2 rounded-4">
                            <tr style="text-align: center">
                                <th>No</th>

                                <th>ID Karet</th>
                                <th>Model</th>
                                <th>Pcs</th>
                                <th>Kadar</th>
                                <th>Ukuran</th>
                                <th>Digunakan</th>
                                <!-- <th>Hasil OK</th>
<th>Hasil SS</th> -->
                                <th>Tanggal buat</th>
                                <th>Status</th>

                                <th>SC</th>
                                <th>Lokasi</th>
                                <th>Activ</th>
                                <!-- <th>SPKO Inject</th> -->
                                <th>Hasil</th>
                            </tr>
                        </thead>
                        <tfoot>
                        </tfoot>
                        <tbody>
                        </tbody>
                    </table>
                </div>

            </div>
            {{-- End Panel tab6 --}}
            <!-- modal daftar product -->
        </div>
    </div>

    <div id="tampil2" class="d-none">

    </div>
</div>
@include('setting.publick_function.ViewSelectionModal')
@include('Produksi.Lilin.SPKInjectLilin.Modal')
@include('Produksi.Lilin.SPKInjectLilin.Modal2')
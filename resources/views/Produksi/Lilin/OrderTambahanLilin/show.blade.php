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
                                <input type="text" class="form-control" id="IDSPKINJECT" value="{{ $cariId[0]->ID}}"
                                    readonly />
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <div class="d-grid gap-2">
                                <button type="button" class="btn btn-info" id="Cetak1" onclick="printbarkode()">
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

    <div class="card mx-auto">
        <ul class="nav nav-pills btn-group" role="group">
            <li class="nav-item col mx-auto">
                <button type="radio" class="nav-link active" role="tab" data-bs-toggle="tab" data-bs-target="#tabel1"
                    aria-controls="tabel1" aria-selected="true"> Item
                </button>
            </li>
            <li class="nav-item col mx-auto">
                <button type="radio" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#tabel2"
                    aria-controls="tabel2" aria-selected="false"> karet Pilihan
                </button>
            </li>
            <li class="nav-item col mx-auto">
                <button type="radio" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#tabel3"
                    aria-controls="tabel3" aria-selected="false"> Batu </button>
            </li>
            <li class="nav-item col mx-auto">
                <button type="radio" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#tabel4"
                    aria-controls="tabel4" aria-selected="false"> Total Batu </button>
            </li>
            <li class="nav-item col mx-auto">
                <button type="radio" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#tabel5"
                    aria-controls="tabel5" aria-selected="false"> Karet Pilihan </button>
            </li>
        </ul>
    </div>

    <div class="tab-content">
        {{-- tab1 --}}
        <div class="tab-pane fade active show" id="tabel1" role="tabpanel">
            <div class="table-responsive text-nowrap rounded-4" style="height:calc(100vh - 490px);">
                <table class="table table-border table-hover table-sm rounded-4" id="tabel1A">
                    <thead class="table-secondary sticky-top zindex-2 rounded-4">
                        <tr>
                            <th>No</th>
                            <th>NO SPK</th>
                            <th>Produk</th>
                            <th>Inject</th>
                            <th>Qty</th>
                            <th>Tok</th>
                            <th>SC</th>
                            <!-- <th>Tatakan</th> -->
                            <th>Keterangan Batu</th>
                            <th>Kaitan</th>
                            <th>Urut</th>
                        </tr>
                    </thead>
                    <tfoot>

                    </tfoot>
                    {{-- {{ dd($query); }} --}}
                    <tbody>
                        @forelse ($TabelItem as $item)
                        <tr id="{{ $item->IDM }}">
                            <td>{{ $loop->iteration }} </td>
                            <td> <span class="badge bg-dark" style="font-size:14px;">{{ $item->nospk }}</span>
                            </td>
                            <td>{{ $item->product }}</td>
                            <td>{{ $item->Inject }}</td>
                            <td>{{ $item->Qty }}</td>
                            <td>{{ $item->Tok }}</td>
                            <td>{{ $item->StoneCast }}</td>
                            <!-- <td>{{ $item->Tatakan }}</td> -->
                            <td>{{ $item->StoneNote }}</td>
                            <td>{{ $item->WaxOrder }}</td>
                            <td>{{ $item->WaxOrderOrd }}</td>
                        </tr>
                        @empty
                        <div class="alert alert-danger">
                            Data Blog belum Tersedia.
                        </div>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        {{-- End tab1--}}

        {{-- tab2 --}}
        <div class="tab-pane fade" id="tabel2" role="tabpanel">
            <div class="table-responsive text-nowrap rounded-4" style="height:calc(100vh - 490px);">
                <table class="table table-border table-hover table-sm rounded-4" id="tabel2A">
                    <thead class="table-secondary sticky-top zindex-2 rounded-4">
                        <tr>
                            <th>No</th>
                            <th>ID Karet</th>
                            <th>Model</th>
                            <th>PCS</th>
                            <th>Digunakan</th>
                            <th>Hasil OK</th>
                            <th>Hasil SS</th>
                            <th>Tanggal Buat</th>
                            <th>Status</th>
                            <th>Ukuran</th>
                            <th>SC</th>
                            <th>Lokasi</th>
                        </tr>
                    </thead>
                    <tfoot>

                    </tfoot>
                    {{-- {{ dd($query); }} --}}
                    <tbody>
                        @forelse ($TKaretDiPilih as $kdp)
                        <tr id="{{ $kdp->IDM }}">
                            <td>{{ $loop->iteration }} </td>
                            <td> <span class="badge bg-dark" style="font-size:14px;">{{ $kdp->Rubber }}</span>
                            </td>
                            <td>{{ $kdp->Product }}</td>
                            <td>{{ $kdp->Pcs }}</td>
                            <td>{{ $kdp->WaxUsage }}</td>
                            <td>{{ $kdp->WaxCompletion }}</td>
                            <td>{{ $kdp->WaxScrap }}</td>
                            <td>{{ $kdp->TransDate }}</td>
                            <td>{{ $kdp->STATUS }}</td>
                            <td>{{ $kdp->Size }}</td>
                            <td>{{ $kdp->StoneCast }}</td>
                            <td>{{ $kdp->lokasi }}</td>
                        </tr>
                        @empty
                        <div class="alert alert-danger">
                            Data Blog belum Tersedia.
                        </div>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        {{-- End tab2 --}}

        {{-- tab3 --}}
        <div class="tab-pane fade" id="tabel3" role="tabpanel">
            <div class="table-responsive text-nowrap rounded-4" style="height:calc(100vh - 490px);">
                <table class="table table-border table-hover table-sm rounded-4" id="tabel3A">
                    <thead class="table-secondary sticky-top zindex-2 rounded-4">
                        <tr>
                            <th>No</th>
                            <th>NO SPK</th>
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
                    {{-- {{ dd($query); }} --}}
                    <tbody>
                        @forelse ($TabelBatu as $tb)
                        <tr id="{{ $tb->IDM }}">
                            <td>{{ $loop->iteration }}</td>
                            <td> <span class="badge bg-dark" style="font-size:14px;">{{ $tb->WorkOrder }}</span>
                            </td>
                            <td>{{ $tb->Product }}</td>
                            <td>{{ $tb->Inject }}</td>
                            <td>{{ $tb->Stone }}</td>
                            <td>{{ $tb->Ordered }}</td>
                            <td>{{ $tb->EachQty }}</td>
                            <td>{{ $tb->Total }}</td>
                            <td>{{ $tb->StoneNote }}</td>
                        </tr>
                        @empty
                        <div class="alert alert-danger">
                            Data Blog belum Tersedia.
                        </div>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        {{-- End tab3--}}

        {{-- tab4 --}}
        <div class="tab-pane fade" id="tabel4" role="tabpanel">
            <div class="table-responsive text-nowrap rounded-4" style="height:calc(100vh - 490px);">
                <table class="table table-border table-hover table-sm rounded-4" id="tabel4A">
                    <thead class="table-secondary sticky-top zindex-2 rounded-4">
                        <tr>
                            <th>No</th>
                            <th>Jenis</th>
                            <th>Batu</th>
                        </tr>
                    </thead>
                    <tfoot>

                    </tfoot>
                    {{-- {{ dd($query); }} --}}
                    <tbody>
                        @forelse ($TabelTotalBatu as $ttb)
                        <tr id="{{ $ttb->Stone }}">
                            <td>{{ $loop->iteration }} </td>
                            <td> <span class="badge bg-dark" style="font-size:14px;">{{ $ttb->Stone }}</span>
                            </td>
                            <td>{{ $ttb->Total }}</td>
                        </tr>
                        @empty
                        <div class="alert alert-danger">
                            Data Blog belum Tersedia.
                        </div>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        {{-- End tab4 --}}

        {{-- tab5 --}}
        <div class="tab-pane fade" id="tabel5" role="tabpanel">
            <div class="table-responsive text-nowrap rounded-4" style="height:calc(100vh - 490px);">
                <table class="table table-border table-hover table-sm rounded-4" id="tabel5A">
                    <thead class="table-secondary sticky-top zindex-2 rounded-4">
                        <tr>
                            <th>No</th>
                            <th>ID Karet</th>
                            <th>Model</th>
                            <th>Pesan</th>
                            <th>Pcs</th>
                            <th>Digunakan</th>
                            <th>Hasil OK</th>
                            <th>Hasil SS</th>
                            <th>Tanggal buat</th>
                            <th>Status</th>
                            <th>Ukuran</th>
                            <th>SC</th>
                            <th>Lokasi</th>
                            <th>Activ</th>
                            <th>SPKO Inject</th>
                        </tr>
                    </thead>
                    <tfoot>

                    </tfoot>

                    <tbody>
                        @forelse ($TabelKaretPilihan as $tkp)
                        <tr id="{{ $tkp->ID }}">
                            <td>{{ $loop->iteration }} </td>
                            <td> <span class="badge bg-dark" style="font-size:14px;">{{ $tkp->ID }}</span>
                            </td>
                            <td>{{ $tkp->Product }}</td>
                            <td>{{ $tkp->Qty }}</td>
                            <td>{{ $tkp->Pcs }}</td>
                            <td>{{ $tkp->WaxUsage }}</td>
                            <td>{{ $tkp->WaxCompletion }}</td>
                            <td>{{ $tkp->WaxScrap }}</td>
                            <td>{{ $tkp->TransDate }}</td>
                            <td>{{ $tkp->Status }}</td>
                            <td>{{ $tkp->Size }}</td>
                            <td>{{ $tkp->StoneCast }}</td>
                            <td>{{ $tkp->lokasi }}</td>
                            <td>{{ $tkp->Active }}</td>
                            <td>{{ $tkp->WaxInjectOrder }}</td>
                        </tr>
                        @empty
                        <div class="alert alert-danger">
                            Data Blog belum Tersedia.
                        </div>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        {{-- End tab5 --}}
    </div>

    <div id="tampilitem" class="d-none">

    </div>

    <div class="display" id="tambahitem"></div>
</div>

<!-- modal daftar product -->
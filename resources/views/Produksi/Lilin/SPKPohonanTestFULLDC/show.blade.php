<div id="tampil1" class="form">
    <div class="row">
        <div class="col-md-6">
            <label class="form-label" for="basic-icon-record-fill">ID</label>
            <div class="row">
                <div class="col-md-8">
                    <div class="input-group input-group-merge"><span id="basic-icon-default-fullname2"
                            class="input-group-text" style="background-color: #F0F0F0;"><i
                                class="far fa-id-badge"></i></span>
                        <input class="form-control" id="IDSPKpohonan" type="number" name="IDSPKpohonan"
                            value="{{ $getWaxinjectOrder[0]->ID }}" disabled="true">
                        <!-- value berisi ID dari hasil simpan form ditampikan saat cetak -->
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="d-grid gap-2">
                        <button type="button" class="btn btn-info" id="Cetakbarkode" onclick="printbarkode()">
                            <span class="tf-icons bx bx-printer"></span>&nbsp; Cetak Barcode</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="row">
                <div class="col-md-6">
                    <label class="form-label" for="basic-icon-default-fullname">Tanggal</label>
                    <input class="form-control" id="tgl" name="tgl" value="{{$getWaxinjectOrder[0]->kadar}}"
                        disabled="true">
                </div>
                <div class="col-md-6">
                    <label class="form-label" for="basic-icon-default-fullname">Total Qty</label>
                    <div class="input-group input-group-merge">
                        <span id="basic-icon-default-fullname2" class="input-group-text"
                            style="background-color: #F0F0F0;"><i class="fas fa-equals"></i></span>
                        <input class="form-control" id="totalQty" type="number" name="totalQty"
                            value="{{$getWaxinjectOrder[0]->Qty}}" disabled="true">
                        <!-- value berisi qty dari hasil penjumlahan total qty yang dipilih -->
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
                        <input type="text" class="form-control" id="employee" name="employee" placeholder="Masukkan ID "
                            value="{{ $getWaxinjectOrder[0]->IDK }}" disabled="true" />
                    </div>
                </div>
                <div class="col-md-8">
                    <label class="form-label" for="basic-icon-default-fullname">nama
                        Operator</label>
                    <div class="input-group input-group-merge">
                        <span id="basic-icon-default-fullname2" class="input-group-text"
                            style="background-color: #F0F0F0;"><i class="fas fa-user"></i></span>
                        <input type="text" class="form-control" id="NamaOperator" placeholder="nama operator"
                            disabled="true" value="{{ $getWaxinjectOrder[0]->karyawan }}" />
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="row">
                <div class="col-md-6">
                    <label class="form-label" for="basic-icon-default-fullname">Kelompok</label>
                    <div class="input-group input-group-merge">
                        <span id="basic-icon-default-fullname2" class="input-group-text"
                            style="background-color: #F0F0F0;"><i class="fas fa-grip-horizontal"></i></span>
                        <input class="form-control" id="WorkGroup" type="number" name="WorkGroup"
                            value="{{$getWaxinjectOrder[0]->WorkGroup}}" disabled="true">
                    </div>
                </div>
                <div class="col-md-6">
                    <label class="form-label" for="basic-icon-default-fullname">Kotak</label>
                    <div class="input-group input-group-merge">
                        <span id="basic-icon-default-fullname2" class="input-group-text"
                            style="background-color: #F0F0F0;"><i class="fas fa-box"></i></span>
                        <input class="form-control" id="BoxNo" type="number" name="BoxNo"
                            value="{{ $getWaxinjectOrder[0]->BoxNo}}" disabled="true">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="row">
                <div class="col-md-6">
                    <label class="form-label" for="basic-icon-default-fullname">Kadar</label>
                    <input class="form-control" id="IDSPKpohonan" type="" name="IDSPKpohonan"
                        value="{{$getWaxinjectOrder[0]->kadar}}" disabled="true">
                    <input type="hidden" id="Caratshow" value="{{$getWaxinjectOrder[0]->Carat}}">
                    <input type="hidden" id="idtmshow" value="{{$getWaxinjectOrder[0]->IDMtf}}">
                </div>
                <div class="col-md-6">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label" for="basic-icon-default-fullname">User</label>
                                <div class="input-group input-group-merge">
                                    <span id="basic-icon-default-fullname2" class="input-group-text"
                                        style="background-color: #F0F0F0;"><i class="far fa-user"></i></span>
                                    <input type="text" class="form-control" id="user"
                                        value="{{$getWaxinjectOrder[0]->UserName }}" readonly />
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
                                        value="{{$getWaxinjectOrder[0]->EntryDate }}" readonly />
                                </div>
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
                                <label class="form-label" for="basic-icon-default-fullname">label
                                    Piring</label>
                                <div class="input-group input-group-merge">
                                    <input type="text" class="form-control" id="Plate" name="Plate"
                                        placeholder="Masukkan Label Piring" value="{{ $getWaxinjectOrder[0]->pkaret}}"
                                        readonly />
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
                                    <input class="form-control" id="plate" type="" name="plate"
                                        value="{{$getWaxinjectOrder[0]->RubberPlate}}" disabled="true">
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="col-md-6">
                    <label class="form-label" for="basic-icon-default-fullname">Stick Pohon</label>
                    <input class="form-control" id="Stickpohon" type="" name="Stickpohon"
                        value="{{$getWaxinjectOrder[0]->stickpohon}}" disabled="true">
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <label class="form-label" for="basic-icon-default-fullname">Catatan</label>
            <div class="input-group input-group-merge"><span id="basic-icon-default-fullname2" class="input-group-text"
                    style="background-color: #F0F0F0;"><i class="far fa-sticky-note"></i></span>
                <input class="form-control" id="note" type="text" name="note" value="{{$getWaxinjectOrder[0]->Remarks}}"
                    disabled="true" />
            </div>
        </div>
        <div id="tampil" class="d-none">
        </div>
    </div>
</div>

<div id="tampil" class="d-none">
    <div class="col-xl-12 mb-3 pt-4">
        <form id="form1">
            @csrf
            <div class="table-responsive text-nowrap px-1">
                <table class="table table-border table-hover table-sm" id="tabel1">
                    <thead class="table-secondary sticky-top zindex-2" style="center">
                        <tr style="text-align: center">
                            <th>NO.</th>
                            <th>No. SPK</th>
                            <th>ProduK</th>
                            <th>Inject</th>
                            <th>QTy</th>
                            <th>Tatakan</th>
                            <th>Rekap</th>
                            <th>Lilin</th>
                            <th>Ketrangan</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <hr>
                    </tfoot>
                    {{-- {{ dd($DaftarProduct); }} --}}
                    <tbody>
                        @foreach ($datatabel as $wax )
                        <tr style="text-aling: center">
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $wax->nospk }}</td>
                            <td><span style="font-size: 14px"
                                    class="badge bg-dark">{{ $wax->product }}</span><br>{{$wax->SKU}}
                            <td>{{ $wax->Inject }}</td>
                            <td>{{ $wax->Qty }}</td>
                            <td>{{ $wax->Tatakan }}</td>
                            <td>{{ $wax->WaxOrder }}</td>
                            <td>{{ $wax->WaxOrderOrd }}</td>
                            <td>{{ $wax->StoneNote }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </form>
    </div>
</div>
</div>
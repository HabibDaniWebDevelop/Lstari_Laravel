<div class="card-body">
    <div class="row">
        <div class="col-9">
            <button type="button" class="btn btn-primary" id="btn_baru" onclick="KlikBaru()"> <span
                    class="tf-icons bx bx-plus-circle"></span>&nbsp; Baru </button>
            <button type="hidden" class="btn btn-primary me-4" disabled id="btn_edit" onclick="KlikEdit()"> <span
                    class="tf-icons bx bx-edit"></span>&nbsp; Ubah </button>
            <button type="button" class="btn btn-danger" disabled="" id="btn_batal" onclick="klikBatal()"> <span
                    class="fas fa-times-circle"></span>&nbsp; Batal</button>
            <button type="button" class="btn btn-warning me-4" disabled id="btn_simpan" onclick="KlikSimpan()"
                tabindex="7"><span class="tf-icons bx bx-save"></span>&nbsp; Simpan</button>
            <button type="button" class="btn btn-info me-4" disabled="" id="btn_info" onclick="klikBatal()"
                tabindex="7"><span class="tf-icons fas fa-tree"></span>&nbsp; Info</button>
            <button type="button" class="btn btn-primary" id="conscale" onclick="connectSerial()">Connect
                Timbangan</button>

            <input type="hidden" id="IDwax" value="" type="number">
            <input type="hidden" id="postingstatus" value="A">
            <input type="hidden" id="O" value="">
            <input type="hidden" id="action" value="simpan" type="text">
            <input type="hidden" id="pohon" value="emas" type="text">
            <input type="hidden" id="selscale"> {{-- Hidden input for timbangan --}}

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
                    @foreach ($historyPlatecor as $item)
                    <option value="{{$item->SW}}">{{$item->SW}}</option>
                    @endforeach
                </datalist>
            </div>
        </div>
    </div>
    <hr>
    <div class="row px-4" id="hiddentambah">
        <div class="col-2 p-0 center">
            <button type="button" class="btn btn-primary" id="tomboldaftarpohon" style="width: 97%;"
                onclick="ListSPKO()">
                <span id="daftarpohon" class="tf-icons fas fa-tree"></span>&nbsp; SPKO
            </button>
        </div>
        <div class="col-2 p-0 center">
            <button type="button" class="btn btn-primary" id="tomboldaftarpohon1" style="width: 97%;"
                onclick="ListPosted()">
                <span id="daftarpohon" class="tf-icons fas fa-tree"></span>&nbsp; POSTED
            </button>
        </div>
    </div>
    <div hidden id="showtambah">
        <div class="row">
            <div class="col-4 center">
                <label class="form-label" for="IDTMPohon">ID TM Pohon:</label>
                <input type="text" class="form-control" id="IDTMPohon" tabindex="1" placeholder="Masukkan ID TM Pohon"
                    autofocus="" onchange="GetDataIDTMPohon()" list="listIDTMpohon">
                <datalist id="listIDTMpohon">
                    @foreach ($historyTMPohon as $item)
                    <option value="{{$item->ID}}">{{$item->ID}}</option>
                    @endforeach
                </datalist>
            </div>
            <div class="col-4">
                <label class="form-label" for="tanggal">Tanggal :</label>
                <input type="date" class="form-control" id="tanggal" value="{{$datenow}}" tabindex="2">
            </div>
            <div class="col-4">
                <label class="form-label" for="Karyawan">Operator : </label>
                <select name="idEmployee" id="idEmployee" class="form-select" tabindex="3">
                    @foreach ($employee as $item)
                    <option value="{{$item->ID}}">{{$item->Description}}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <label class="form-label" for="Catatan">Catatan</label>
                <input type="text" class="form-control" id="Catatan" tabindex="4">
            </div>
        </div>
    </div>

    <br>

    <div hidden id="infoTM" class="float-start">
        <td>

            <span style="text-align: center; font-weight: bold; color: #000;">&nbsp;&nbsp; Admin</span>
            <span id="UserNameAdminTM"
                style="font-weight: bold; color: DodgerBlue; font-size: 15px; padding-left: 1px;"></span>
        </td>
        <td>
            <span style="text-align: center; font-weight: bold; color: #000;">&nbsp;&nbsp; Entry
                :&nbsp;</span>
            <span id="TanggaPembuatanTM"
                style="font-weight: bold; color: DodgerBlue; font-size: 15px; padding-left: 1px;"></span>
        </td>
        <td>
            <span style="text-align: center; font-weight: bold; color: #000;">&nbsp;&nbsp; Total Pohon
                :&nbsp;</span>
            <span id="TotalPohon"
                style="font-weight: bold; color: DodgerBlue; font-size: 20px; padding-left: 1px;">0</span>
        </td>
    </div>
    <div hidden id="infoTM2" class="float-end">
        <td>
            <span style="text-align: center; font-weight: bold; color: #000;">&nbsp;&nbsp; Weight Need :&nbsp;</span>
            <span id="totalberatkebutuhan"
                style="font-weight: bold; color: DodgerBlue; font-size: 15px; padding-left: 1px;"></span>
        </td>
        <td>
            <span style="text-align: center; font-weight: bold; color: #000;">&nbsp;&nbsp; Weight Gold :&nbsp;</span>
            <span id="totalberathasilnimbang"
                style="font-weight: bold; color: SlateBlue; font-size: 15px; padding-left: 1px;"></span>
        </td>
    </div>
    <hr>
    <div class="table-responsive" style="height:calc(80vh);">
        <table hidden class="table table-border table-hover table-sm" id="tabel1">
            <thead class="table-secondary sticky-top zindex-2">
                <tr style="text-align: center">
                    <th width="5%">NO.</th>
                    <th class="px-0 mx-0">ID Pohon</th>
                    <th class="px-0 mx-0">No Pohon</th>
                    <th class="px-0 mx-0">Weight Need</th>
                    <th class="px-0 mx-0">SPK PPIC</th>
                    <th class="px-0 mx-0">&nbsp;&nbsp;Sisa&nbsp;&nbsp;</th>
                    <th class="px-0 mx-0">
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Product&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
                    <th class="px-0 mx-0">
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;NTHKO&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    </th>
                    <th class="px-0 mx-0">Weight Gold</th>
                    <th class="px-0 mx-0">&nbsp;&nbsp;&nbsp;&nbsp;Note&nbsp;&nbsp;&nbsp;&nbsp;</th>
                    <th class="px-0 mx-0">Add&nbsp;&nbsp;&nbsp;&nbsp;</th>
                </tr>
            </thead>
            <tbody class="text-center">
            </tbody>
        </table>
        <!-- ----------------------------------------------------------------------------------- -->
        <table class="table table-border table-hover table-sm" id="tabel2">
            <thead class="table-secondary sticky-top zindex-2">
                <tr style="text-align: center">
                    <th width="5%">NO.</th>
                    <th>SPKO</th>
                    <th>Tanggal</th>
                    <th>Kadar</th>
                    <th>Total Weight</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody class="text-center">
                @forelse ($infoSPKOPlateCorBelumposting as $tb)
                <tr id="{{ $tb->workallocationID }}">
                    <td>{{ $loop->iteration }}</td>
                    <td> <span class="badge bg-dark" style="font-size:14px;">{{ $tb->spkocor }}</span>
                    </td>
                    <td>{{ $tb->tgl }}</td>
                    <td><span class="badge" style="background-color: {{ $tb->HexColor }};">{{ $tb->Kadar }}</span></td>
                    <td>{{ $tb->totalWeight}}</td>
                    <td><button class="btn btn-info btn-sm add" type="button"
                            onclick="lihatisiSPKOPlatcor({{ $tb->workallocationID }},{{ $loop->iteration }})"><i
                                class="fas fa-list"></i></i>&nbsp;&nbsp;Lihat</button>
                    </td>
                </tr>
                @empty
                @endforelse
            </tbody>
        </table>

        <table hidden class="table table-border table-hover table-sm" id="tabel3">
            <thead class="table-secondary sticky-top zindex-2">
                <tr style="text-align: center">
                    <th width="5%">NO.</th>
                    <th>SPKO</th>
                    <th>Tanggal</th>
                    <th>Kadar</th>
                    <th>Total Weight</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody class="text-center">
            </tbody>
        </table>
    </div>
</div>
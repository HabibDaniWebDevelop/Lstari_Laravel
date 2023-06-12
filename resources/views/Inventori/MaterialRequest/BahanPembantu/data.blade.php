
<div class="card-body">
    <div class="row">
        <div class="col-9 " id="btn-menu">
            <button type="button" class="btn btn-primary" id="btn_baru" onclick="KlikBaru()"> <span
                    class="tf-icons bx bx-plus-circle"></span>&nbsp; Baru </button>
            <button type="button" class="btn btn-primary me-4" id="btn_ubah" disabled 
                onclick="KlikUbah()"><span class="tf-icons bx bx-edit"></span>&nbsp; Ubah</button>
            <button type="button" class="btn btn-danger" disabled id="btn_batal" onclick="klikBatal()"> <span
                    class="fas fa-times-circle"></span>&nbsp; Batal</button>
            <button type="button" class="btn btn-warning me-4" disabled id="btn_simpan"
                onclick="KlikSimpan()"><span class="tf-icons bx bx-save"></span>&nbsp; Simpan</button>
            {{-- <button type="button" class="btn btn-info" id="btn_cetak" disabled onclick="klikCetak()"> <span
                    class="tf-icons bx bx-printer"></span>&nbsp; Cetak</button> --}}
            <input type="hidden" id="idMaterialRequest" value="" type="text">
            <input type="hidden" id="action" value="simpan" type="text"> {{-- Input checking for determine simpan or ubah. --}}
            <input type="hidden" id="removeAction" value="true" type="text"> {{-- Input checking for determine remove action will executed or not. --}}

            <input type="hidden" id="transfercek" value="{{ $transfercek }}">
            <input type="hidden" id="ulangtambah" value="1">

            @if ($transfercek > 0)
                @foreach ($transfer as $data)
                    <input type="hidden" id="t_{{ $loop->iteration }}" value="{{ $data }}">
                @endforeach
            @endif

        </div>
        <div class="col-3">
            <div class="float-end">
                <div class="input-group input-group-merge">
                    <span class="input-group-text"><i class="bx bx-search"></i></span>
                    <input type="search" class="form-control" placeholder="Search..." autofocus="" id="cari"
                        list="carilist">
                </div>
                <datalist class="text-warning" id="carilist">
                    @foreach ($ListUserHistory as $carilist)
                    <option value="{{ $carilist->SW }}">
                        {{ $carilist->SW }}
                    </option>
                    @endforeach
                </datalist>
            </div>
        </div>
    </div>
    <hr>
    <div id="tampil">
        <div class="row">
            <div class="col-6">
                <label class="form-label" for="employee">karyawan : <span
                        id="idEmployee">{{ $employee->ID }}</span></label>
                <input type="text" class="form-control" disabled="" value="{{ $employee->NAME }}" id="employee"
                    name="department">
            </div>
            <div class="col-6">
                <label class="form-label" for="department">Department : <span
                        id="idDepartment">{{ $employee->Department }}</span></label>
                <input type="text" class="form-control" disabled="" id="department" name="department"
                    value="{{ $employee->Bagian }}">
            </div>
        </div>
        <div class="row">
            <div class="col-6">
                <label class="form-label" for="tanggal">Tanggal Reques :</label>
                <input type="date" disabled="" class="form-control" id="tanggal" value="{{ $datenow }}">
            </div>
            <div class="col-6">
                <div class="form-group">
                    {{-- <label class="form-label">Gudang Asal</label>
                    <select class="form-select" id="GudangT" disabled onchange="PilihGudang()">
                        <option value="" selected disabled> Silahkan Pilih Gudang Asal</option>
                        @foreach ($tujuan as $item)
                            <option {{ $loc == $item['id_warehouse'] ? 'selected' : '' }}
                                value="{{ $item['name'] }}">{{ $item['name'] }}</option>
                        @endforeach
                    </select> --}}
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <label class="form-label" for="tanggal">Catatan :</label>
                <textarea class="form-control" name="catatan" id="catatan" cols="1" rows="1"> </textarea>
            </div>
        </div>
        <br>

        <select id="data-select-master" style="display:none;">
            @foreach ($barangStock as $item)
                <option value="{{ $item->ID }}">{{ $item->ID }} - {{ $item->Description }} ({{ $item->Unit }})</option>
            @endforeach
        </select>

        <select id="data-select-master-nonstok" style="display:none;">
            @foreach ($nonstok as $item)
                <option value="{{ $item['name'] }}">{{ $item['item_name'] }}</option>
            @endforeach
        </select>


        {{-- Input new form --}}
        <div>
            <form id="form1" autocomplete="off">
            <table class="table table-borderless table-sm" id="tabel1">
                <thead class="table-secondary">
                    <tr style="text-align: center">
                        <th width="3%">NO.</th>
                        <th class="bg-success">Barang Stock</th>
                        <th class="bg-info" width="10%">Kode Non Stock</th>
                        <th class="bg-info" style="min-width: 150px;">Barang Non Stock</th>
                        <th width="5%">Jumlah</th>
                        <th width="5%">Unit</th>
                        <th>Proses</th>
                        <th width="6%">Keperluan</th>
                        <th width="5%"> Kategori</th>
                        <th width="5%">Ulang</th>
                        <th style="min-width: 350px;">Keterangan</th>
                    </tr>
                </thead>
                <tbody class="text-center">
                    {{-- @foreach ($transfer as $data)
                    <tr>
                        <td class="m-0 p-0">
                            <input type="text" value="{{ $data }}">
                        </td>
                    </tr>
                @endforeach --}}


                </tbody>
            </table>
            </form>
        </div>
    </div>
</div>

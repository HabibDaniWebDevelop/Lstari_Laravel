<div class="row mb-2">
    <div class="col-4">
        <div class="form-group">
            <label class="form-label">ID </label>
            <input type="text" class="form-control" readonly value="{{ $header->ID }}" id="id" name="id">
            <input type="hidden" value="{{ $header->Active }}" id="Active">
        </div>
    </div>
    <div class="col-4">
    </div>
    <div class="col-4">
        <div class="form-group">
            <label class="form-label">Tanggal</label>
            <input type="date" class="form-control" readonly id="tgl_masuk" name="tgl_masuk"
                value="{{ $header->TransDate }}">
        </div>
    </div>
</div>
<div class="row mb-2">
    <div class="col-4">
        <div class="form-group">
            <label class="form-label">Department </label>
            <select class="form-select" id="bagian" disabled>
                <option value="" selected disabled> Silahkan Pilih Department</option>
                @foreach ($Department as $data)
                    <option {{ $header->Department == $data->ID ? 'selected' : '' }} value="{{ $data->ID }}">
                        {{ $data->Description }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="col-4">
        <div class="form-group">
            <label class="form-label">Tipe </label>
            <div class="d-flex flex-row">
                <select class="form-select w-25" id="tipe" disabled>
                    <option selected disabled value="0">Silahkan Pilih</option>
                    <option {{ $header->Reason == 'R' ? 'selected' : '' }} value="R">R</option>
                    <option {{ $header->Reason == 'I' ? 'selected' : '' }} value="I">I</option>
                </select>
                <input type="text" class="form-control mx-4" name="InOut" id="InOut"
                    style="width: 150px; text-align: center;" readonly="">
            </div>
        </div>
    </div>
    <div class="col-4">
        <div class="form-group">
            <label class="form-label">Gudang</label>
            <select class="form-select" id="gudang" name="gudang" {{ $kunci == '1' ? 'disabled' : '' }} >
                <option value="" selected disabled> Silahkan Pilih Gudang</option>
                @foreach ($Location as $data)
                    <option {{ $header->Location == $data->ID ? 'selected' : '' }} value="{{ $data->ID }}">
                        {{ $data->Description }}</option>
                @endforeach
            </select>
        </div>
    </div>
</div>
<div class="row mb-2">
    <div class="col-12">
        <div class="form-group">
            <label class="form-label">Catatan </label>
            <input type="text" class="form-control" readonly value="{{ $header->Remarks }}" id="catatan"
                name="catatan">
        </div>
    </div>
</div>

<div style="overflow-x: auto;">
    <table width="100%" class="table table-bordered table-hover table-sm mt-4" id="tabel1">
        <thead class="table-secondary sticky-top zindex-2">
            <tr>
                <th width="3%">No</th>
                <th style="min-width: 90px; width: 8%">Minta ID</th>
                <th style="min-width: 110px; width: 8%">Minta Urut</th>
                <th>Tanggal</th>
                <th>Barang</th>
                <th width="5%">Qty</th>
                <th width="5%">Unit</th>
                <th>Proses</th>
                <th>Keterangan</th>
                <th width="5%">Pilih</th>
            </tr>

        </thead>
        <tbody>
            @if ($lihat == '1')

                @foreach ($item as $data)
                <tr class="klik1">
                
                    <td class="m-0 p-0">
                        <input type="text" class="form-control form-control-sm fs-6 w-100 border-0" readonly
                            value="{{ $data->Ordinal }}">
                    </td>
                    <td class="m-0 p-0">
                        <input type="text" class="form-control form-control-sm fs-6 w-100 border-0" name="idm[{{ $data->Ordinal }}]"
                            readonly value="{{ $data->LinkID }}">
                    </td>
                    <td class="m-0 p-0">
                        <input type="text" class="form-control form-control-sm fs-6 w-100 border-0" name="ord[{{ $data->Ordinal }}]"
                            readonly value="{{ $data->LinkOrd }}">
                    </td>
                    <td class="m-0 p-0">
                        <input type="date" class="form-control form-control-sm fs-6 w-100 border-0" readonly value="{{ $data->TransDate }}">
                    </td>
                    <td class="m-0 p-0">
                        <input type="text" class="form-control form-control-sm fs-6 w-100 border-0" readonly value="{{ $data->PDescription }}">
                    </td>
                    <td class="m-0 p-0">
                        <input type="text" class="form-control form-control-sm fs-6 w-100 border-0" name="jumlah[{{ $data->Ordinal }}]" readonly value="{{ $data->Qty }}">
                    </td>
                    <td class="m-0 p-0">
                        <input type="text" class="form-control form-control-sm fs-6 w-100 border-0" readonly value="{{ $data->Unit }}">
                    </td>
                    <td class="m-0 p-0">
                        <input type="text" class="form-control form-control-sm fs-6 w-100 border-0" readonly value="{{ $data->ODescription }}">
                    </td>
                    <td class="m-0 p-0">
                        <input type="text" class="form-control form-control-sm fs-6 w-100 border-0" name="keterangan[{{ $data->Ordinal }}]" readonly value="{{ $data->Note }}">

                        <input type="hidden" name="barangid[{{ $data->Ordinal }}]" value="{{ $data->Product }}">
                        <input type="hidden" name="karid[{{ $data->Ordinal }}]" value="{{ $data->Employee }}">
                        <input type="hidden" name="id_proses[{{ $data->Ordinal }}]" value="{{ $data->Operation }}">
                    </td>
                
                    <td class="m-0 p-0 bg-light">
                        <div class="form-check d-flex justify-content-center">
                            <input type="checkbox" class="form-check-input " disabled checked="checked" value="Y" name="pilih[{{ $data->Ordinal }}]">
                        </div>
                    </td>
                </tr>
                @endforeach
            @endif

        </tbody>
    </table>
</div>

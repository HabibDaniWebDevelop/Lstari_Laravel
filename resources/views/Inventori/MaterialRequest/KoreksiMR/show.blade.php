<div class="row">
    <div class="col-6">
        <label class="form-label" for="employee">ID MR : </label>
        <input type="text" class="form-control" readonly value="{{ $header[0]->ID }}" id="hasilid" name="hasilid">
        <input type="hidden" value="{{ $header[0]->Active }}" id="Active">
    </div>
    <div class="col-6">
        <label class="form-label" for="tanggal">Tanggal Reques :</label>
        <input type="date" class="form-control" id="tgl_masuk" name="tgl_masuk" readonly
            value="{{ $header[0]->TransDate }}">
    </div>
</div>

<div class="row">
    <div class="col-12">
        <label class="form-label" for="tanggal">Catatan :</label>
        <textarea class="form-control" name="catatan" id="catatan" cols="1" rows="1" readonly> {{ $header[0]->Remarks }}</textarea>
    </div>
</div>
<br>

{{-- Input new form --}}
<div class="">

    <table class="table table-bordered table-sm" id="tabel1">
        <thead class="table-secondary">
            <tr style="text-align: center">
                <th width="6%" data-searchable="true"> NO </th>
                <th> Barang Stock </th>
                <th width="10%"> Jumlah </th>
                <th> Unit </th>
                <th width="38%"> Keterangan </th>
                <th width="5%">Purchase</th>
            </tr>
        </thead>
        <tbody class="text-center">
            @if ($menu == 'lihat')
                @foreach ($items as $key => $data)
                    <tr class='klik' id='Row_{{ $data->Ordinal }}'>
                        <td class="m-0 p-0"><input type="text"
                                class="form-control form-control-sm fs-6 w-100 border-0" name="no[]"
                                readonly value="{{ $data->Ordinal }}">
                        </td>
                        <td class="m-0 p-0">
                            <select class="form-control form-control-sm fs-6 my-select"
                                name="barang[]" disabled data-live-search="true">
                                <option value="0">-- Silahkan Pilih --</option>
                                @foreach ($barangStock as $item)
                                    <option {{ $data->Product == $item->ID ? 'selected' : '' }}
                                        value="{{ $item->ID }}">{{ $item->ID }} - {{ $item->Description }}
                                    </option>
                                @endforeach

                            </select>
                        </td>
                        <td class="m-0 p-0"><input type="number"
                                class="form-control form-control-sm fs-6 w-100 border-0 text-center"
                                name="jumlah[]" readonly value="{{ $data->Qty }}">
                        </td>
                        <td class="m-0 p-0">
                            <select name="unit[]"
                                class="form-select form-select-sm fs-6 w-100 border-0 text-center" disabled>
                                @foreach ($satuan as $item)
                                    <option {{ $data->Unit == $item->ID ? 'selected' : '' }}
                                        value="{{ $item->ID }}">{{ $item->SW }}</option>
                                @endforeach
                            </select>
                        </td>
                        <td class="m-0 p-0">
                            <input type="text" class="form-control form-control-sm fs-6 w-100 border-0"
                                name="keterangan[]" value="{{ $data->Note }}" readonly>
                        </td>
                        <td class="m-0 p-0">
                            <input type="checkbox" class="form-check-input" name="ispurchase[]" id="ispurchase{{ $loop->iteration }}" value="{{ $data->Purchase }}" onclick="changePurchase({{ $data->Ordinal }})" disabled>
                        </td>
                        <td class="m-0 p-0 d-none">
                            <input type="text" name="location[]" value="{{ $Locations[$key] }}">
                        </td>
                        <td class="m-0 p-0 d-none">
                            <input type="text" name="sw[]" value="{{ $data->SW }}">
                        </td>
                        <td class="m-0 p-0 d-none">
                            <input type="text" name="uom[]" value="{{ $data->uom }}">
                        </td>
                    </tr>
                @endforeach
            @endif
        </tbody>
    </table>

</div>

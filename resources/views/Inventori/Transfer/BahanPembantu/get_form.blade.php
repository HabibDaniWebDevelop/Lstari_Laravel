<thead class="table-secondary sticky-top zindex-2">
    <tr>
        <th width="3%">No</th>
        <th style="min-width: 90px; width: 8%">Minta ID</th>
        <th style="min-width: 110px; width: 8%">Minta Urut</th>
        <th width="100">Tanggal</th>
        <th style="min-width: 310px;">Barang</th>
        <th width="5%">Qty</th>
        <th width="5%">Unit</th>
        <th width="10%">Proses</th>
        <th style="min-width: 200px;">Keterangan</th>
        <th width="5%">Pilih</th>
    </tr>

</thead>
<tbody>
    @foreach ($item as $data)
        <tr class="klik1">

            <td class="m-0 p-0">
                <input type="text" class="form-control form-control-sm fs-6 w-100 border-0" readonly
                    value="{{ $loop->iteration }}">
            </td>

            <td class="m-0 p-0">
                <input type="text" class="form-control form-control-sm fs-6 w-100 border-0" name="idm[{{ $loop->iteration }}]" readonly
                    value="{{ $data->ID }}">
            </td>
            <td class="m-0 p-0">
                <input type="text" class="form-control form-control-sm fs-6 w-100 border-0" name="ord[{{ $loop->iteration }}]" readonly
                    value="{{ $data->Ordinal }}">
            </td>
            <td class="m-0 p-0">
                <input type="date" class="form-control form-control-sm fs-6 w-100 border-0" readonly value="{{ $data->TransDate }}">
            </td>
            <td class="m-0 p-0">
                <input type="text" class="form-control form-control-sm fs-6 w-100 border-0" readonly value="{{ $data->Product }}">
            </td>
            <td class="m-0 p-0">
                <input type="text" class="form-control form-control-sm fs-6 w-100 border-0" name="jumlah[{{ $loop->iteration }}]" readonly value="{{ $data->Qty }}">
            </td>
            <td class="m-0 p-0">
                <input type="text" class="form-control form-control-sm fs-6 w-100 border-0" readonly value="{{ $data->Unit }}">
            </td>
            <td class="m-0 p-0">
                <input type="text" class="form-control form-control-sm fs-6 w-100 border-0" readonly value="{{ $data->Operation }}">
            </td>
            <td class="m-0 p-0">
                <input type="text" class="form-control form-control-sm fs-6 w-100 border-0" name="keterangan[{{ $loop->iteration }}]"
                    value="">

                <input type="hidden" name="barangid[{{ $loop->iteration }}]" value="{{ $data->PID }}">
                <input type="hidden" name="karid[{{ $loop->iteration }}]" value="{{ $data->EID }}">
                <input type="hidden" name="id_proses[{{ $loop->iteration }}]" value="{{ $data->OID }}">
            </td>

            <td class="m-0 p-0 bg-light">
                <div class="form-check d-flex justify-content-center">
                    <input type="checkbox" class="form-check-input" value="Y" name="pilih[{{ $loop->iteration }}]">
                </div>
            </td>
        </tr>
    @endforeach
</tbody>

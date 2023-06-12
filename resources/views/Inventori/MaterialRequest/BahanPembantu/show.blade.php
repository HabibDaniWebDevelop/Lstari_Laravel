<div class="row">
    <div class="col-6">
        <label class="form-label" for="employee">karyawan : <span
                id="idEmployee">{{ $header[0]->idEmployee }}</span></label>
        <input type="text" class="form-control" readonly value="{{ $header[0]->nama }}" id="employee" name="employee">
        <input type="hidden" value="{{ $header[0]->Active }}" id="Active">
    </div>
    <div class="col-6">
        <label class="form-label" for="department">Department : <span
                id="idDepartment">{{ $header[0]->Department }}</span></label>
        <input type="text" class="form-control" readonly id="department" name="department"
            value="{{ $header[0]->Bagian }}">
    </div>
</div>
<div class="row">
    <div class="col-6">
        <label class="form-label" for="tanggal">Tanggal Reques :</label>
        <input type="date" class="form-control" id="tanggal" readonly value="{{ $header[0]->TransDate }}">
    </div>
    <div class="col-6">
        <div class="form-group">
            {{-- <label class="form-label">Gudang Tujuan</label>
            <input type="text" class="form-control" id="GudangT" readonly value="{{ $tujuan }}"> --}}
        </div>
    </div>
</div>
<div class="row">
    <div class="col-12">
        <label class="form-label" for="tanggal">Catatan :</label>
        <textarea class="form-control" name="catatan" id="catatan" cols="1" rows="1" readonly> {{ $header[0]->Remarks }}</textarea>
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
<div class="">
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
            @foreach ($items as $data)
                <tr class='klik' id='Row_{{ $data->Ordinal }}'>
                    <td class="m-0 p-0"><input type="text" class="form-control fs-6 w-100 text-center nomor"
                            name="no" disabled value="{{ $data->Ordinal }}"
                            oncontextmenu="klikme('{{ $data->Ordinal }}',event)">
                    </td>
                    <td class="m-0 p-0">
                        <select class="form-control barangStock" data-style="border" name="barangStock" 
                            id="barangStock_{{ $data->Ordinal }}" disabled
                            onchange="getDetailBarangStock(this, {{ $data->Ordinal }})" data-live-search="true"
                            oncontextmenu="klikme('{{ $data->Ordinal }}',event)">
                            <option value="0">-- Silahkan Pilih --</option>

                            @foreach ($barangStock as $item)
                            <option {{ $data->Product == $item->ID ? 'selected' : '' }} value="{{ $item->ID }}">{{ $item->Description }} ({{ $item->Unit }})</option>
                            @endforeach

                        </select>
                    </td>
                    <td class="m-0 p-0">
                        <select class="form-control kodeNonStock" data-style="border" name="kodeNonStock" 
                            id="kodeNonStock_{{ $data->Ordinal }}" disabled
                            onchange="getDetailBarangNonStock(this, {{ $data->Ordinal }})" data-live-search="true"
                            oncontextmenu="klikme('{{ $data->Ordinal }}',event)">
                            <option value="0">-- Silahkan Pilih --</option>

                            @foreach ($nonstok as $item)
                            <option {{ $data->ProductNonStock == $item['name'] ? 'selected' : '' }} value="{{ $item['name'] }}">{{ $item['item_name'] }}</option>
                            @endforeach

                        </select>
                    </td>
                    <td class="m-0 p-0"><input type="text" class="form-control fs-6 w-100 text-center barangNonStock"
                            name="barangNonStock" id="barangNonStock_{{ $data->Ordinal }}" disabled value="{{ $data->ProductNote }}"
                            oncontextmenu="klikme('{{ $data->Ordinal }}',event)">
                    </td>
                    <td class="m-0 p-0"><input type="number" class="form-control fs-6 w-100 text-center jumlah"
                            name="jumlah" id="jumlah_{{ $data->Ordinal }}" disabled value="{{ $data->Qty }}"
                            oncontextmenu="klikme('{{ $data->Ordinal }}',event)" onfocus="this.select();">
                    </td>
                    <td class="m-0 p-0">
                        <select name="unit" id="unit_{{ $data->Ordinal }}" class="form-select unit" disabled
                            oncontextmenu="klikme('{{ $data->Ordinal }}',event)">
                            @foreach ($satuan as $item)
                                <option {{ $data->Unit == $item->ID ? 'selected' : '' }} value="{{ $item->ID }}">{{ $item->SW }}</option>

                                @if ($data->Unit == $item->ID)
                                    @php
                                        $uom = $item->SW;
                                    @endphp
                                @endif
                            @endforeach
                        </select>

                    </td>
                    <td class="m-0 p-0">
                        <select name="proses" id="proses_{{ $data->Ordinal }}" class="form-select proses" disabled
                            oncontextmenu="klikme('{{ $data->Ordinal }}',event)">
                            @foreach ($proses as $item)
                                <option {{ $data->Department == $item->ID ? 'selected' : '' }} value="{{ $item->ID }}">{{ $item->Description }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td class="m-0 p-0">
                        <select name="keperluan" id="keperluan_{{ $data->Ordinal }}" class="form-select keperluan" disabled
                            oncontextmenu="klikme('{{ $data->Ordinal }}',event)">
                            <option {{ $data->Purpose == "Rutin" ? 'selected' : '' }} value="Rutin">Rutin</option>
                            <option {{ $data->Purpose == "Pembuatan" ? 'selected' : '' }} value="Pembuatan">Pembuatan</option>
                            <option {{ $data->Purpose == "Perbaikan" ? 'selected' : '' }} value="Perbaikan">Perbaikan</option>
                            <option {{ $data->Purpose == "Inventory" ? 'selected' : '' }} value="Inventory">Inventory</option>
                        </select>
                    </td>
                    <td class="m-0 p-0">
                        <select name="kategori" id="kategori_{{ $data->Ordinal }}" class="form-select kategori" disabled
                            oncontextmenu="klikme('{{ $data->Ordinal }}',event)">
                            <option {{ $data->Category == "Biasa" ? 'selected' : '' }} value="Biasa">Biasa</option>
                            <option {{ $data->Category == "Penting" ? 'selected' : '' }} value="Penting">Penting</option>
                            <option {{ $data->Category == "Darurat" ? 'selected' : '' }} value="Darurat">Darurat</option>
                        </select>
                    </td> 
                    <td class="m-0 p-0">
                        <select name="ulang" id="ulang_{{ $data->Ordinal }}" class="form-select ulang" disabled
                            oncontextmenu="klikme('{{ $data->Ordinal }}',event)">
                            <option {{ $data->ReBuy == "N" ? 'selected' : '' }} value="N">Tidak</option>
                            <option {{ $data->ReBuy == "Y" ? 'selected' : '' }} value="Y">Iya</option>
                        </select>
                    </td>
                    <td class="m-0 p-0">
                        <input type="text" class="form-control fs-6 w-100 text-center keterangan"
                            name="keterangan" id="keterangan_{{ $data->Ordinal }}" value="{{ $data->Note }}" disabled
                            onKeyPress="nextRowEvent('{{ $data->Ordinal }}',event)">
                        <input type="hidden" class="sw" id="sw_{{ $data->Ordinal }}" value="{{ $data->SW }}">
                        <input type="hidden" class="uom" id="uom_{{ $data->Ordinal }}" value="{{ $uom }}">
                        
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

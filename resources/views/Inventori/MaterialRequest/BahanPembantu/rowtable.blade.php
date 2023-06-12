<tr class='klik' id='Row_{{ $index }}'>
    <td class="m-0 p-0"><input type="text" class="form-control fs-6 w-100 text-center nomor" name="no" readonly
            value="{{ $index }}" oncontextmenu="klikme('{{ $index }}',event)"></td>
    <td class="m-0 p-0">
        <select class="form-control barangStock" data-style="border" name="barangStock"
            id="barangStock_{{ $index }}" onchange="getDetailBarangStock(this, {{ $index }})"
            data-live-search="true" oncontextmenu="klikme('{{ $index }}',event)">
            <option value="0">-- Silahkan Pilih --</option>
            {{-- @foreach ($barangStock as $item)
                <option value="{{ $item->ID }}">{{ $item->Description }} ({{ $item->Unit }})</option>
            @endforeach --}}
        </select>
    </td>
    <td class="m-0 p-0">
        <select class="form-control kodeNonStock" data-style="border" name="kodeNonStock"
            id="kodeNonStock_{{ $index }}" onchange="getDetailBarangNonStock(this, {{ $index }})"
            data-live-search="true" oncontextmenu="klikme('{{ $index }}',event)">
            <option value="0">-- Silahkan Pilih --</option>
        </select>
    </td>
    <td class="m-0 p-0"><input type="text" class="form-control fs-6 w-100 text-center barangNonStock"
            name="barangNonStock" id="barangNonStock_{{ $index }}" value=""
            oncontextmenu="klikme('{{ $index }}',event)"></td>
    <td class="m-0 p-0"><input type="number" class="form-control fs-6 w-100 text-center jumlah" name="jumlah"
            id="jumlah_{{ $index }}" oncontextmenu="klikme('{{ $index }}',event)" onfocus="this.select();"></td>
    <td class="m-0 p-0">
        <select name="unit" id="unit_{{ $index }}" class="form-select unit"
            oncontextmenu="klikme('{{ $index }}',event)">
            @foreach ($satuan as $item)
                <option value="{{ $item->ID }}">{{ $item->SW }}</option>
            @endforeach
        </select>
    </td>
    <td class="m-0 p-0">
        <select name="proses" id="proses_{{ $index }}" class="form-select proses"
            oncontextmenu="klikme('{{ $index }}',event)">
            @foreach ($proses as $item)
                <option value="{{ $item->ID }}"
                    @if($item->Description == 'IT')
                    selected
                    @else
                    @endif
                    >{{ $item->Description }}</option>
            @endforeach
        </select>
    </td>
    <td class="m-0 p-0">
        <select name="keperluan" id="keperluan_{{ $index }}" class="form-select keperluan"
            oncontextmenu="klikme('{{ $index }}',event)">
            <option value="Rutin">Rutin</option>
            <option value="Pembuatan">Pembuatan</option>
            <option value="Perbaikan">Perbaikan</option>
            <option value="Inventory">Inventory</option>
        </select>
    </td>
    <td class="m-0 p-0">
        <select name="kategori" id="kategori_{{ $index }}" class="form-select kategori"
            oncontextmenu="klikme('{{ $index }}',event)">
            <option value="Biasa">Biasa</option>
            <option value="Penting">Penting</option>
            <option value="Darurat">Darurat</option>
        </select>
    </td>
    <td class="m-0 p-0">
        <select name="ulang" id="ulang_{{ $index }}" class="form-select ulang"
            oncontextmenu="klikme('{{ $index }}',event)">
            <option value="N">Tidak</option>
            <option value="Y">Iya</option>
        </select>
    </td>
    <td class="m-0 p-0">
        <input type="text" class="form-control fs-6 w-100 text-center keterangan" name="keterangan"
            id="keterangan_{{ $index }}" onKeyPress="nextRowEvent('{{ $index }}',event)">
            <input type="hidden" class="sw" id="sw_{{ $index }}">
            <input type="hidden" class="uom" id="uom_{{ $index }}">
    </td>
</tr>



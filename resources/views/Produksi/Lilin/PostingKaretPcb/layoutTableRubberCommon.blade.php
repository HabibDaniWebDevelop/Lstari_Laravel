<table class="table table-borderless table-sm" id="tabel1">
    <thead class="table-secondary">
        <tr style="text-align: center">
            <th width="5%">#.</th>
            <th>ID Karet</th>
            <th>Nama Product</th>
            <th>Jenis Part</th>
        </tr>
    </thead>
    <tbody class="text-center">
        @foreach ($listRubberWax->items as $item)
            <tr>
                <td><input class="form-check-input checkingRubber" type="checkbox" id="inlineCheckbox1" disabled="true" value="{{$item['IDRubber']}}"></td>
                <td>{{$item['IDRubber']}}</td>
                <td>{{$item['NamaProduct']}}</td>
                <td>{{$item['jenisPart']}}</td>
            </tr>
        @endforeach
    </tbody>
</table>
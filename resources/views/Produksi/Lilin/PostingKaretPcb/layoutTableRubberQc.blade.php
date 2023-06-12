<table class="table table-borderless table-striped table-sm" id="tabel1">
    <thead class="table-secondary">
        <tr style="text-align: center">
            <th> #. </th>
            <th> No. NTHKO</th>
            <th> Product </th>
            <th> Bulan STP</th>
            <th> Rubber </th>
            <th> Nama Product Rubber </th>
        </tr>
    </thead>
    <tbody class="text-center">
        @foreach ($TMKaretLilin->items as $item)
            @foreach ($item['rubberKepala'] as $key => $itemRubber)
                <tr>
                    <td><input class="form-check-input checkingRubber" type="checkbox" id="inlineCheckbox1" disabled="true" value="{{$item['rubberKepala'][$key]}}"></td>
                    <td>{{$item['nthkoqc']}}</td>
                    <td>{{$item['Product']}}</td>
                    <td>{{$item['bulanSTP']}}</td>
                    <td>{{$item['rubberKepala'][$key]}}</td>
                    <td>{{$item['namaProductKepala'][$key]}}</td>
                </tr>
            @endforeach
            @foreach ($item['rubberMainan'] as $key => $itemRubber)
                <tr>
                    <td><input class="form-check-input checkingRubber" type="checkbox" id="inlineCheckbox1" disabled="true" value="{{$item['rubberMainan'][$key]}}"></td>
                    <td>{{$item['nthkoqc']}}</td>
                    <td>{{$item['Product']}}</td>
                    <td>{{$item['bulanSTP']}}</td>
                    <td>{{$item['rubberMainan'][$key]}}</td>
                    <td>{{$item['namaProductMainan'][$key]}}</td>
                </tr>
            @endforeach
            @foreach ($item['rubberComponent'] as $key => $itemRubber)
                <tr>
                    <td><input class="form-check-input checkingRubber" type="checkbox" id="inlineCheckbox1" disabled="true" value="{{$item['rubberComponent'][$key]}}"></td>
                    <td>{{$item['nthkoqc']}}</td>
                    <td>{{$item['Product']}}</td>
                    <td>{{$item['bulanSTP']}}</td>
                    <td>{{$item['rubberComponent'][$key]}}</td>
                    <td>{{$item['namaProductComponent'][$key]}}</td>
                </tr>
            @endforeach
        @endforeach
    </tbody>
</table>
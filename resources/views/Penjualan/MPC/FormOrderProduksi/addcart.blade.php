@foreach ($cekProd as $value)
<tr>
    <td>
        <b>1</b>
    </td>
    <td>
        <b>{{ $value->SKU }}</b>
        <input type="hidden" name="idprod[]" id="idprod{{}}" value="{{ $value->ID }}">
    </td>
    <td>
        <input type="number" class="form-control" placeholder="Jumlah Order" value="1" id="qty1" name="qty[]">
    </td>
</tr>
@endforeach

@section('script')
<script>

</script>
@endsection
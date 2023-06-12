<select class="form-select" name="Ordinal" id="Ordinal">
    @foreach ($datas as $data1)
    <option value="{{ $data1->Ordinal }}">{{ $data1->Ordinal }} &nbsp;|&nbsp; {{ $data1->Patch }}</option>
    @endforeach
    <option value="{{ $data1->Ordinal+1 }}" selected> {{ $data1->Ordinal+1 }} </option>
</select>

<input type="hidden"id="Ordinallama" name="Ordinallama" value="" />
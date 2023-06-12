<select class="form-select" name="Employee">
    <option value="" selected disabled>--- Pilih ---</option>
    @foreach ($data1 as $data1s)
    <option value="{{ $data1s->ID }}">{{ $data1s->Description }}</option>
    @endforeach
</select>
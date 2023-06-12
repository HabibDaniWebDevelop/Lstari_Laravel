

<select class="form-select" id="AnnounceTo" name="AnnounceTo">
    <option value=""> </option>
    <option value="0">-- ALL --</option>
    @foreach ($data1 as $data)
    <option value="{{ $data->id }}">{{ $data->SW }}</option>
    @endforeach
</select>
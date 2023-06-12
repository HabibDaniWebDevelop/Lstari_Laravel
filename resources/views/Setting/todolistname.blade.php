<select class="form-select" id="name" name="name">
    <?php $jenis =''; ?>
    @foreach ($data2 as $data)
        @if ($jenis <> $data->jenis && $jenis != '')
            </optgroup>
        @endif
        @if ($jenis <> $data->jenis)
            <optgroup label="{{ $data->jenis }}">
        @endif
        <option value="{{ $data->NAME }}">{{ $data->NAME }}</option>
        <?php $jenis = $data->jenis; ?>
    @endforeach
    </optgroup>
</select>

<div class="card-body">

    <div class="row">
        <div class="col-2">
            <div class="input-group" id="cari_filter">
                <label class="input-group-text btn-primary">Filter</label>
                <select class="form-select" id="name" name="name">
                    <?php $jenis =''; ?>
                    @foreach ($listnama as $data)
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
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12 dx-viewport">
            <div id="tabelex1" style="font-size: 15px;">

            </div>
        </div>
    </div>
</div>
<div class="input-group w-px-800 mb-4">

    <select class="form-select" id="pill" name="pill" onchange="filter1()">
        <option value="" selected disabled> Pencarian Data </option>
        @foreach ($query1 as $querys)
            <option <?php echo $id == $querys->ID ? 'selected' : ''; ?> value="{{ $querys->ID }}"> {{ $querys->tampil1 }} </option>
        @endforeach
    </select>
    <span class="input-group-text" onclick="checkboxblockvs()"><input class="form-check-input mt-0" id="all"
            type="checkbox" value="1" />
        &ensp; <a href="#">Lihat 1000 Data</a></span>
    {{-- <button class="btn btn-outline-primary" type="button">Cari</button> --}}
    <button class="btn btn-outline-primary" type="button" id="gunakan" onclick="klikgunakan()">Gunakan</button>
</div>
<input type="hidden" id="TabName" value="{{ $TabName }}" />

@if ($id != '')
    <div class="<?php echo $id == '' ? 'd-none' : ''; ?>">
        <div class="h6 mb-2">Filter</div>
        <div class="table-responsive text-nowrap w-75 mb-4">
            <table class="table table-borderless table-sm">
                <thead class="table-secondary">
                    <tr style="text-align: center">
                        <th> </th>
                        @foreach ($query2 as $querys)
                            <th>{{ $querys->Description }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <th class="table-active">Nilai</th>
                        @foreach ($query2 as $querys)
                            @if (str_contains($querys->FieldName, 'Date'))
                                <?php $typedate = 'Date'; ?>
                            @else
                                <?php $typedate = 'text'; ?>
                            @endif
                            <td class="m-0 p-0"> <input type="{{ $typedate }}" class="form-control form-control-sm fs-6 w-100" name="N_{{ $querys->FieldName }}"
                                    <?php echo $querys->DefUse == '1' ? 'disabled="true"' : ''; ?> value="{{ $querys->DefValue }}"> </td>
                        @endforeach
                    </tr>
                    <tr>
                        <th class="table-active">Tanda</th>
                        @foreach ($query2 as $querys)
                            <td class="m-0 p-0">
                                <select class="form-select form-select-sm fs-6 w-100 " name="T_{{ $querys->FieldName }}"
                                    <?php echo $querys->DefUse == '1' ? 'disabled="true"' : ''; ?>>
                                    <option value=""> </option>
                                    <option <?php echo $querys->DefOperator == '=' ? 'selected' : ''; ?> value="="> = </option>
                                    <option <?php echo $querys->DefOperator == '<>' ? 'selected' : ''; ?> value="<>">
                                        <>
                                    </option>
                                    <option <?php echo $querys->DefOperator == '>' ? 'selected' : ''; ?> value=">"> > </option>
                                    <option <?php echo $querys->DefOperator == '<' ? 'selected' : ''; ?> value="<">
                                        < </option>
                                    <option <?php echo $querys->DefOperator == 'Like' ? 'selected' : ''; ?> value="Like"> Like </option>
                                    <option <?php echo $querys->DefOperator == 'In' ? 'selected' : ''; ?> value="In"> In </option>
                                    <option <?php echo $querys->DefOperator == 'Not In' ? 'selected' : ''; ?> value="Not In"> Not In </option>
                                </select>
                            </td>
                        @endforeach
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="h6 mb-2">Hasil Pencarian</div>
        <div class="table-responsive text-nowrap" style="height:calc(100vh - 430px);">
            <table class="table table-border table-hover table-sm" <?php echo $id != '' ? 'id="tabelVS"' : ''; ?>>
                <thead class="table-secondary sticky-top zindex-2">
                    <tr style="text-align: center">
                        <th>No</th>
                        @foreach ($query2 as $querys)
                            <?php $judul[] = $querys->FieldName; ?>
                            <th>{{ $querys->Description }}</th>
                        @endforeach
                        <?php $count = count($query2); ?>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($query3 as $query)
                        <tr class="klik1" id="{{ $query->ID }}">
                            <td>{{ $loop->iteration }}</td>
                            @for ($i = 0; $i < $count; $i++)
                                <?php $isi = $judul[$i]; ?>
                                <td>{!! $query->$isi !!}</td>
                            @endfor
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@else
    <div class="alert alert-danger">
        Silahkan Pilih Opsi di Pencari Data
    </div>
@endif
<script>
    $('#tabelVS').DataTable({
        "paging": false,
        "lengthChange": false,
        "searching": false,
        "ordering": true,
        "info": false,
        "autoWidth": true,
        "responsive": true,
        "fixedColumns": true,
        "order": [
            [1, 'desc']
        ],
    });

    var table = $('#tabelVS').DataTable();
    table.columns().iterator('column', function(ctx, idx) {
        $(table.column(idx).header()).append('<span class="sort-icon" /> ');
    });


    function checkboxblockvs() {
        if ($('#all').is(':checked')) {
            $('#all').attr('checked', false);
        } else {
            $('#all').attr('checked', true);
        }
    }

    function filter1() {
        var TabName = $('#TabName').val();
        var pill = $('#pill').val();
        $.get('/ViewSelection?id=' + pill + '&tb=' + TabName, function(data) {
            $("#modalVS").html(data);
        });
    }

    function klikgunakan() {
        var pill = $('#pill').val();
        var TabName = $('#TabName').val();
        if ($('#all').is(':checked')) {
            var all = 1;
        } else {
            var all = 0;
        }
        var formData = $('#formmodalVS').serialize();
        // alert(formData);

        $.get('/ViewSelection?id=' + pill + '&tb=' + TabName + '&all=' + all +'&'+ formData, function(data) {
            $("#modalVS").html(data);
        });
    }

    $(".klik1").on('click', function(e) {
        var id = $(this).attr('id');
        $('#cari').val(id);
        $('#modalinfoVS').modal('hide');
        ChangeCari();
        return false;
        
        });
</script>

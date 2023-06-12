<div class="row my-4">

    <div class="col-md-4 mb-2">
        <div class="form-group">
            <label class="form-label">Tanggal</label>
            <input type="date" class="form-control" name="tanggal" id="tanggal" required="true"
                value="{{ date('Y-m-d'); }}">
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label class="form-label">Area Tujuan</label>
            <input type="text" class="form-control" disabled value="Lilin">
            <input type="hidden" name="Department" id="Department" value="19">
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label class="form-label">Penerima</label>
            <select class="form-select" name="employe" id="employe" required>
                @foreach ($employees as $employee)
                <option value="{{ $employee->ID }}" selected>{{ $employee->Description }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="col-md-12">
        <div class="form-group">
            <label class="form-label">Catatan</label>
            <input type="text" class="form-control" name="note" id="note">
        </div>
    </div>
</div>

<div class="table-responsive text-nowrap" style="height:calc(100vh - 550px);">

    <table class="table table-border table-hover table-sm" id="tabel1">
        <thead class="table-secondary sticky-top zindex-2">
            <tr style="text-align: center">
                <th>#</th>
                <th>Envelope</th>
                <th>Kode Produk</th>
                <th>Deskripsi</th>
                <th>Kadar</th>
                <th>Material</th>
                <th>Qty</th>
                <th>Result</th>
                <th>ID Permintaan</th>
            </tr>
        </thead>
        <tbody>

            {{-- {{ dd($data) }} --}}

            @foreach ($datas as $data1)
            {{-- <tr class="klik" id="{{ $data1->ID }}" id2='{{ $data1->RCIIDM }}' id3='{{ $data1->RCIOrdinal }}' style="text-align: center"> --}}
            <tr class="klik" id="{{ $data1->IDX }}"  style="text-align: center">
                <td>
                    <input class="form-check-input" type="checkbox" name="id[]" id="cek_{{ $data1->IDX }}"
                        value="{{ $data1->ID }}" data-product="{{ $data1->Product }}" data-qty="{{ $data1->Qty }}" data-wo="{{ $data1->WOO }}" data-woi="{{ $data1->WOI }}" data-nthko="{{ $data1->RCIIDM }}" data-nthkoitem="{{ $data1->RCIOrdinal }}"/>
                </td>
                <td><span class="badge bg-dark" style="font-size:14px;">{{ $data1->ENVE }}</span><br>{{ $data1->SWNTHKO }}</td>
                <td><span class="badge bg-dark" style="font-size:14px;">{{$data1->codes}}</span><br>{{ $data1->SKU }}</td>
                <td>{{ $data1->DD }}</td>
                <td>{{ $data1->Description }}</td>
                <td>{{ $data1->Material }}</td>
                <td>{{ $data1->Qty }}</td>
                <td>Good</td>
                <td>{{ $data1->ID }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<script>
$('#tabel1').DataTable({
    "paging": false,
    "lengthChange": false,
    "searching": true,
    "ordering": true,
    "info": false,
    "autoWidth": true,
    "responsive": true,
    "fixedColumns": true,
});

// -------------------- klik di tabel --------------------
$(".klik").on('click', function(e) {
    // $('.klik').css('background-color', 'white');
    var id = $(this).attr('id');
    if ($(this).hasClass('table-secondary')) {
        $(this).removeClass('table-secondary');
        $('#cek_' + id).attr('checked', false);
    } else {
        $(this).addClass('table-secondary');
        $('#cek_' + id).attr('checked', true);
    }
    return false;
});
</script>
<div class="row">
    <div class="col-xxl-6 col-xl-12 mb-3">
        <div class="card">
            <h5 class="card-header">Data Tabel</h5>
            <div class="card-body">

                <div class="table-responsive text-nowrap" style="height:calc(100vh - 490px);">
                    <table class="table table-border table-hover table-sm" id="tabel1">
                        <thead class="table-secondary sticky-top zindex-2">
                            <tr>
                                <th>No</th>
                                <th>Id</th>
                                <th>EntryDate</th>
                                <th>SW</th>
                                <th>Description</th>
                                <th>SKU</th>
                            </tr>
                        </thead>
                        <tfoot>

                        </tfoot>
                        {{-- {{ dd($query); }} --}}
                        <tbody>
                            @forelse ($query as $querys)
                            <tr id="{{ $querys->ID }}">
                                <td>{{ $loop->iteration }} </td>
                                <td> <span class="badge bg-dark" style="font-size:14px;">{{ $querys->ID }}</span>
                                </td>
                                <td>{{ $querys->EntryDate }}</td>
                                <td>{{ $querys->SW }}</td>
                                <td>{{ $querys->Description }}</td>
                                <td>{{ $querys->SKU }}</td>
                            </tr>
                            @empty
                            <div class="alert alert-danger">
                                Data Blog belum Tersedia.
                            </div>
                            @endforelse
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>

    <div class="col-xxl-6 col-xl-12 mb-3">
        <div class="card">
            <h5 class="card-header">Data Tabel paging laravel</h5>
            <div class="card-body">
                <div class="card-body d-flex justify-content-between p-0">
                    <button class="btn btn-primary mb-2" onclick="kliktambah()">Tambah Data</button>

                    <div class="float-end">
                        <div class="input-group input-group-merge">
                            <span class="input-group-text"><i class="bx bx-search"></i></span>
                            <input type="text" class="form-control" placeholder="Search..." autofocus id='cari'
                                onkeydown="klikCari()" />
                        </div>
                    </div>
                </div>
                <div class="table-responsive text-nowrap" style="height:calc(100vh - 600px);">
                    <table class="table table-border table-hover table-sm" id="tabel2">
                        <thead class="table-secondary sticky-top zindex-2">
                            <tr>
                                <th>No</th>
                                <th>Id</th>
                                <th>EntryDate</th>
                                <th>SW</th>
                                <th>Description</th>
                                <th>SKU</th>
                            </tr>
                        </thead>
                        <tfoot>

                        </tfoot>
                        {{-- {{ dd($query); }} --}}
                        <tbody>
                            @forelse ($query as $querys)
                            <tr id="{{ $querys->ID }}">
                                <td>{{ $loop->iteration }} </td>
                                <td> <span class="badge bg-dark" style="font-size:14px;">{{ $querys->ID }}</span>
                                </td>
                                <td>{{ $querys->EntryDate }}</td>
                                <td>{{ $querys->SW }}</td>
                                <td>{{ $querys->Description }}</td>
                                <td>{{ $querys->SKU }}</td>
                            </tr>
                            @empty
                            <div class="alert alert-danger">
                                Data Blog belum Tersedia.
                            </div>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                {{ $query->links('pagination::bootstrap-4') }}
            </div>
        </div>
    </div>

    <div class="col-xxl-6 col-xl-12 mb-3">
        <div class="card">
            <h5 class="card-header">Data Tabel Group</h5>
            <div class="card-body">

                <div class="table-responsive text-nowrap" style="height:calc(100vh - 490px);">
                    <table class="table table-border table-hover table-sm" id="tabel6">
                        <thead class="table-secondary sticky-top zindex-2">
                            <tr>
                                <th>No</th>
                                <th>Id</th>
                                <th>EntryDate</th>
                                <th>SW</th>
                                <th>Description</th>
                                <th>SKU</th>
                            </tr>
                        </thead>
                        <tfoot>

                        </tfoot>
                        {{-- {{ dd($query); }} --}}
                        <tbody>
                            @forelse ($query as $querys)
                            <tr>
                                <td>{{ $loop->iteration }} </td>
                                <td>{{ $querys->ID }}</td>
                                <td>{{ $querys->EntryDate }}</td>
                                <td>{{ $querys->SW }}</td>
                                <td>{{ $querys->Description }}</td>
                                <td>{{ $querys->SKU }}</td>
                            </tr>
                            @empty
                            <div class="alert alert-danger">
                                Data Blog belum Tersedia.
                            </div>
                            @endforelse
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>

    <div class="col-xxl-6 col-xl-12 mb-3">
        <div class="card">
            <h5 class="card-header">Data Tabel klik open modal</h5>
            <div class="card-body">

                <div class="table-responsive text-nowrap" style="height:calc(100vh - 490px);">
                    <table class="table table-border table-hover table-sm" id="tabel3">
                        <thead class="table-secondary sticky-top zindex-2">
                            <tr>
                                <th>No</th>
                                <th>Id</th>
                                <th>EntryDate</th>
                                <th>SW</th>
                                <th>Description</th>
                                <th>SKU</th>
                            </tr>
                        </thead>
                        <tfoot>

                        </tfoot>
                        {{-- {{ dd($query); }} --}}
                        <tbody>
                            @forelse ($query as $querys)
                            <tr class="klik1" id="{{ $querys->ID }}" id2="{{ $querys->SW }}">
                                <td>{{ $loop->iteration }} </td>
                                <td>{{ $querys->ID }}</td>
                                <td>{{ $querys->EntryDate }}</td>
                                <td>{{ $querys->SW }}</td>
                                <td>{{ $querys->Description }}</td>
                                <td>{{ $querys->SKU }}</td>
                            </tr>
                            @empty
                            <div class="alert alert-danger">
                                Data Blog belum Tersedia.
                            </div>
                            @endforelse
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>

    <div class="col-xxl-6 col-xl-12 mb-3">
        <div class="card">
            <h5 class="card-header">Data Tabel klik checkbox</h5>
            <div class="card-body">

                <div class="card-body d-flex justify-content-between p-0">
                    <button class="btn btn-primary mb-2" onclick="KlikProses()">Proses</button>
                </div>

                <div class="table-responsive text-nowrap" style="height:calc(100vh - 540px);">
                    <form id="form1">
                        <table class="table table-border table-hover table-sm" id="tabel4">
                            <thead class="table-secondary sticky-top zindex-2">
                                <tr>
                                    <th>pill</th>
                                    <th>Id</th>
                                    <th>EntryDate</th>
                                    <th>SW</th>
                                    <th>Description</th>
                                    <th>SKU</th>
                                </tr>
                            </thead>
                            <tfoot>

                            </tfoot>
                            {{-- {{ dd($query); }} --}}
                            <tbody>
                                @forelse ($query as $querys)
                                <tr class="klik2" id="{{ $querys->ID }}">
                                    <td><input class="form-check-input" type="checkbox" name="id"
                                            id="cek_{{ $querys->ID }}" value="{{ $querys->ID }}" /></td>
                                    <td>{{ $querys->ID }}</td>
                                    <td>{{ $querys->EntryDate }}</td>
                                    <td>{{ $querys->SW }}</td>
                                    <td>{{ $querys->Description }}</td>
                                    <td>{{ $querys->SKU }}</td>
                                </tr>
                                @empty
                                <div class="alert alert-danger">
                                    Data Blog belum Tersedia.
                                </div>
                                @endforelse
                            </tbody>
                        </table>
                    </form>
                </div>

            </div>
        </div>
    </div>

    <div class="col-xxl-6 col-xl-12 mb-0">
        <div class="card">
            <h5 class="card-header">Data Tabel klik menu</h5>
            <div class="card-body">
                <div class="card-body d-flex justify-content-between p-0">
                    <button class="btn btn-primary mb-2" onclick="kliktambah()">Tambah Data</button>
                </div>

                <div class="table-responsive text-nowrap" style="height:calc(100vh - 480px);">
                    <table class="table table-border table-hover table-sm" id="tabel5">
                        <thead class="table-secondary sticky-top zindex-2">
                            <tr>
                                <th>No</th>
                                <th>Id</th>
                                <th>EntryDate</th>
                                <th>SW</th>
                                <th>Description</th>
                                <th>SKU</th>
                            </tr>
                        </thead>
                        <tfoot>

                        </tfoot>
                        {{-- {{ dd($query); }} --}}
                        <tbody>
                            @forelse ($query as $querys)
                            <tr class="klik3" id="{{ $querys->ID }}" id2="{{ $querys->SW }}">
                                <td>{{ $loop->iteration }} </td>
                                <td>{{ $querys->ID }}</td>
                                <td>{{ $querys->EntryDate }}</td>
                                <td>{{ $querys->SW }}</td>
                                <td>{{ $querys->Description }}</td>
                                <td>{{ $querys->SKU }}</td>
                            </tr>
                            @empty
                            <div class="alert alert-danger">
                                Data Blog belum Tersedia.
                            </div>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalinfo" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg" id="modalformat" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="jodulmodal1">History</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="formmodal1">
                    <div id="modal1">
                        <div class="row">
                            <div class="col-6">
                                <div class="form-group">
                                    <label>ID</label>
                                    <input type="text" name="Brand" id="ID" class="form-control" value="">
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label>Type</label>
                                    <select class="form-select" type="text" name="SubType">
                                        <option value="Laser Jet">Laser Jet</option>
                                        <option value="Ink Jet">Ink Jet</option>
                                        <option value="Thermal">Thermal</option>
                                        <option value="Dot Matrix">Dot Matrix</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <label>SW</label>
                                    <input type="text" class="form-control" name="SW" id="SW" value="">
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                    Close
                </button>
                <button type="button" class="btn btn-primary d-none" id="simpan1" value=""
                    onclick="KlikSimpan1()">Save</button>
            </div>
        </div>
    </div>
</div>

@include('layouts.backend-Theme-3.DataTabelButton')

<script>
$(document).ready(function() {
    var collapsedGroups = {};
    var table = $('#tabel6').DataTable({
        "paging": false,
        "ordering": true,
        "info": true,
        "searching": true,
        rowGroup: {
            // Uses the 'row group' plugin
            dataSrc: 4,
            startRender: function(rows, group) {
                //console.log(group);
                var collapsed = !!collapsedGroups[group];

                rows.nodes().each(function(r) {
                    r.style.display = collapsed ? '' : 'none';
                });

                // Add category name to the <tr>. NOTE: Hardcoded colspan
                return $(' <tr />')
                    .append('<td colspan="7" class="table-active fw-bold">' + group + ' (' +
                        rows.count() + ')</td>')
                    .attr('data-name', group)
                    .toggleClass('collapsed', collapsed);
            }
        }
    });

    $('#tabel6_filter label input').on('focus', function() {
        this.setAttribute('id', 'cari');
        this.setAttribute('onClick', 'this.select()');
    });

    $('#tabel6 tbody').on('click', 'tr.dtrg-group', function() {
        //console.log('ikkk');
        var name = $(this).data('name');
        collapsedGroups[name] = !collapsedGroups[name];
        table.draw(false);
    });

});

var table = $('#tabel1').DataTable({
    "paging": true,
    "lengthChange": false,
    "pageLength": 9,
    "searching": true,
    "ordering": true,
    "info": false,
    "autoWidth": true,
    "responsive": true,
    "fixedColumns": false,
    "lengthChange": false,
    buttons: [{
        extend: 'print',
        split: ['excel', 'pdf'],
    }]
});
table.buttons().container().appendTo('#tabel1_wrapper .col-md-6:eq(0)');


$('#tabel2').DataTable({
    "paging": false,
    "lengthChange": false,
    "searching": false,
    "ordering": true,
    "info": true,
    "autoWidth": true,
    "responsive": true,
    "fixedColumns": false,
});

$('#tabel3').DataTable({
    "paging": true,
    "lengthChange": false,
    "pageLength": 15,
    "searching": true,
    "ordering": true,
    "info": true,
    "autoWidth": true,
    "responsive": true,
    "fixedColumns": false,
});

$('#tabel4').DataTable({
    "paging": false,
    "lengthChange": false,
    "searching": true,
    "ordering": true,
    "info": true,
    "autoWidth": true,
    "responsive": true,
    "fixedColumns": false,
});

$('#tabel5').DataTable({
    "paging": true,
    "lengthChange": false,
    "pageLength": 11,
    "searching": true,
    "ordering": true,
    "info": true,
    "autoWidth": true,
    "responsive": true,
    "fixedColumns": false,
});

// -------------------- Data Tabel klik open modal --------------------
$(".klik1").on('click', function(e) {

    var id = $(this).attr('id');
    var sw = $(this).attr('id2');
    $('#ID').val(id);
    $('#SW').val(sw);

    klikedit(id);
    return false;

});

// -------------------- Data Tabel klik checkbox --------------------
$(".klik2").on('click', function(e) {
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

// -------------------- Data Tabel klik menu --------------------
$(".klik3").on('click', function(e) {
    $('.klik3').css('background-color', 'white');

    if ($("#menuklik").css('display') == 'block') {
        $(" #menuklik ").hide();
    } else {
        var top = e.pageY + 15;
        var left = e.pageX - 100;
        var id = $(this).attr('id');
        var id2 = $(this).attr('id2');
        $('#ID').val(id);
        $('#SW').val(id2);
        $("#judulklik").html(id);

        $(this).css('background-color', '#f4f5f7');
        $("#menuklik").css({
            display: "block",
            top: top,
            left: left
        });
    }
    return false;

});

$("body").on("click", function() {
    if ($("#menuklik").css('display') == 'block') {
        $(" #menuklik ").hide();
    }
    $('.klik3').css('background-color', 'white');
});

$("#menuklik a").on("click", function() {
    $(this).parent().hide();
});

// ----------------------------------------------------------

function klikedit(id) {
    $("#jodulmodal1").html('Form Edit Data PC');
    $('#modalformat').attr('class', 'modal-dialog modal-lg');
    $("#simpan1").removeClass('d-none');
    $('#simpan1').val('Edit');

    $('#modalinfo').modal('show');
}

function klikcetak(id) {
    id = '1';
    window.open('/IT/DataPC/cetak?id=' + id, '_blank');
}

function klikinfo(id) {
    $("#jodulmodal1").html('History');
    $('#modalformat').attr('class', 'modal-dialog modal-fullscreen');
    $("#simpan1").addClass('d-none');

    $('#modalinfo').modal('show');
}

function KlikProses() {
    var formData = $('#form1').serialize();
    alert(formData);
}
</script>

@include('layouts.backend-Theme-3.XtraScript')
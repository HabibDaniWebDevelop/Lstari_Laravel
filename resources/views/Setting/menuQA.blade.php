<div class="card-body">
    <button id="tam-qa" name="tam-qa" class="btn btn-primary mb-2">Tambah Baru</button>
    <form id="traspcb">
        <div class="table-responsive text-nowrap" style="height:calc(100vh - 397px);">
            <table class="table table-border table-hover table-sm" id="tblQA">
                <thead class="table-secondary sticky-top zindex-2">
                    <tr>
                        <th>User</th>
                        <th>Ordinal</th>
                        <th>Menu</th>
                        <th>Sub Menu 1</th>
                        <th>Sub Menu 2</th>
                        <th>Sub Menu 3</th>
                        <th>Icon</th>
                        {{-- <th>Status</th> --}}
                        {{-- <th width='15%'>Actions</th> --}}
                    </tr>
                </thead>
                <tbody>

                    {{-- {{ dd($settings) }} --}}
                    @forelse ($MenuQA as $data1)
                    @if ($data1->Icon == '')
                    @php
                    $s = $data1->menu;
                    $a = preg_replace('/\b(\w)|./', '$1', $s);
                    $icon = $a;
                    @endphp
                    @else
                    @php
                    $icon = $data1->Icon;
                    @endphp
                    @endif
                    <tr class="klikqa" id="{{ $data1->ID_QA }}+{{ $data1->name }}">
                        <td>{{ $data1->name }} </td>
                        <td>{{ $data1->Ordinal }}</td>
                        <td>{{ $data1->menul1 }}</td>
                        <td>{{ $data1->menul2 }}</td>
                        <td>{{ $data1->menul3 }}</td>
                        <td>{{ $data1->menul4 }}</td>
                        <td>{!! $icon !!}</td>

                        {{-- <td>
                                <button type="button" class="btn btn-primary btn-sm edit-qa"
                                    value="{{ $data1->ID_QA }}+{{ $data1->name }}+{{ $data1->menu }}"> <i
                            class="bx bx-edit-alt me-1"></i> Edit </button> &ensp;
                        </td> --}}
                    </tr>
                    @empty
                    <div class="alert alert-danger">
                        Data Blog belum Tersedia.
                    </div>
                    @endforelse

                </tbody>
            </table>
        </div>
    </form>
</div>

</body>

</html>

@include('Setting.menuQAModall')

<script>
$(document).ready(function() {
    var collapsedGroups = {};
    var table = $('#tblQA').DataTable({
        "paging": false,
        "ordering": false,
        "info": true,
        "searching": false,
        rowGroup: {
            // Uses the 'row group' plugin
            dataSrc: 0,
            startRender: function(rows, group) {
                //console.log(group);
                var collapsed = !!collapsedGroups[group];

                rows.nodes().each(function(r) {
                    r.style.display = collapsed ? '' : 'none';
                });

                // Add category name to the <tr>. NOTE: Hardcoded colspan
                return $('<tr/>')
                    .append('<td colspan="7" class="table-active fw-bold">' + group + ' (' +
                        rows.count() + ')</td>')
                    .attr('data-name', group)
                    .toggleClass('collapsed', collapsed);
            }
        }
    });

    $('#tblQA_filter label input').on('focus', function() {
        this.setAttribute('id', 'cari');
        this.setAttribute('onClick', 'this.select()');
    });

    $('#tblQA tbody').on('click', 'tr.dtrg-group', function() {
        //console.log('ikkk');
        var name = $(this).data('name');
        collapsedGroups[name] = !collapsedGroups[name];
        table.draw(false);
    });

});


var path = "{{ route('autouser') }}";

$("#Name").autocomplete({
    source: function(request, response) {
        $.ajax({
            url: path,
            type: 'GET',
            dataType: "json",
            data: {
                search: request.term
            },
            success: function(data) {
                response(data);
            }
        });
    },

    select: function(event, ui) {
        $('#Name').val(ui.item.label);
        console.log(ui.item);
        return false;
    },
    select: function(event, ui) {
        $('#Nameid').val(ui.item.id);
    },
    open: function() {
        $(this).autocomplete('widget').css('z-index', 1100);
        return false;
    },
});

var path2 = "{{ url('automodul') }}";
$("#Menu").autocomplete({
    source: function(request, response) {
        $.ajax({
            url: path2,
            type: 'GET',
            dataType: "json",
            data: {
                search: request.term
            },
            success: function(data) {
                response(data);
            }
        });
    },

    select: function(event, ui) {
        $('#Menu').val(ui.item.label);
        console.log(ui.item);
        return false;
    },
    select: function(event, ui) {
        $('#Menuid').val(ui.item.id);
    },
    open: function() {
        $(this).autocomplete('widget').css('z-index', 1100);
        return false;
    },
});

$(".klikqa").on('click', function(e) {
    var id = $(this).attr('id');
    editqa(id);
});

$('input').attr('autocomplete', 'off');
</script>

@extends('layouts.backend-Theme-3.XtraScript')
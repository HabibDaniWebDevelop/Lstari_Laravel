<div class="card-body">
    <button id="btn-add" name="btn-add" class="btn btn-primary mb-2">Tambah Baru</button>
    <div class="table-responsive text-nowrap" style="height:calc(100vh - 397px);">
        <table class="table table-inverse table-hover table-sm" id="tabel1">
            <thead class="table-secondary sticky-top zindex-2">
                <tr>
                    <th>no</th>
                    <th>No Modul</th>
                    <th>Menu</th>
                    <th>Sub Menu 1</th>
                    <th>Sub Menu 2</th>
                    <th>Sub Menu 3</th>
                    <th>Path</th>
                    <th width='5%'>Status</th>
                    <th width='5%'>Icon</th>
                    <th width='5%'>Made By</th>
                    <th width='15%'>akses</th>
                </tr>
            </thead>
            <tbody id="links-list" name="links-list">

                @foreach ($settings as $link)
                    <tr class="klikmenu" id2="{{ $link->LEVEL }}" id="{{ $link->nomodul }}">
                        <td>{{ $loop->iteration }} </td>
                        <td>{{ $link->nomodul }}</td>
                        <td>{{ $link->menul1 }}</td>
                        <td>{{ $link->menul2 }}</td>
                        <td>{{ $link->menul3 }}</td>
                        <td>{{ $link->menul4 }}</td>
                        <td>{{ $link->Patch }}</td>
                        <td>{{ $link->STATUS }}</td>
                        <td>{!! $link->Icon !!}</td>
                        <td>{{ $link->made_by }}</td>
                        <td>{{ $link->LEVEL }}</td>
                    </tr>
                    @if ($loop->last)
                        <input type="text" class="d-none" id='lasno' value="{{ $loop->count }}">
                        <input type="text" class="d-none" id='modlastid' value="{{ $maxid }}">
                    @endif
                @endforeach
            </tbody>
        </table>
    </div>

    @include('Setting.ListMenuModal')
    @include('layouts.backend-Theme-3.DataTabelButton')

    <script>
        $(document).ready(function() {

                var table = $('#tabel1').DataTable({
                "paging": false,
                "lengthChange": true,
                // "pageLength": 12,
                "searching": true,
                "ordering": true,
                "info": false,
                "autoWidth": true,
                "responsive": true,
                buttons: [{
                    extend: 'print',
                    split: ['excel', 'pdf'],
                    orientation: 'landscape',
                    
                }]
            });
            table.buttons().container().appendTo('#tabel1_wrapper .col-md-6:eq(0)');

        });


        $(".klikmenu").on('click', function(e) {
            var id = $(this).attr('id');
            var id2 = $(this).attr('id2');
            editListMenu(id, id2);
        });
    </script>

    @extends('layouts.backend-Theme-3.XtraScript')

<div class="card-body">
    <button id="tam-user" name="tam-user" class="btn btn-primary mb-2 ">Tambah Baru</button>
    <form id="traspcb">
        <div class="table-responsive text-nowrap" style="height:calc(100vh - 397px);">
            <table class="table table-border table-hover table-sm" id="tabel1">
                <thead class="table-secondary sticky-top zindex-2">
                    <tr>
                        <th>User</th>
                        <th>nama lengkap</th>
                        <th>Status </th>
                        <th>Level</th>
                        
                    </tr>
                </thead>
                <tbody>

                    {{-- {{ dd($settings) }} --}}

                    @forelse ($settings as $data1)
                        <tr class="klikuser" id="{{ $data1->id }}">
                            <td>{{ $data1->name }} </td>
                            <td>{{ $data1->Description }}</td>
                            <td>{{ $data1->status }}</td>
                            <td>{{ $data1->Nama_level }}</td>

                            {{-- <td>
                                <button type="button" class="btn btn-primary btn-sm modal-user"
                                    value="{{ $data1->id }}"> <i class="bx bx-edit-alt me-1"></i> Edit </button>
                                &ensp;
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

@include('Setting.UserModall')

<script>
    $('#tabel1').DataTable({
        "paging": false,
        "lengthChange": true,
        "searching": true,
        "ordering": true,
        "info": false,
        "autoWidth": true,
        "responsive": true,
    });

    $(".klikuser").on('click', function(e) {
        var id = $(this).attr('id');
        edituser(id);
    });

    var path = "{{ route('autousererp') }}";

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
</script>

@extends('layouts.backend-Theme-3.XtraScript')

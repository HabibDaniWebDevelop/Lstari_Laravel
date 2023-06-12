<div class="card-body">
    <button id="tam-menuLevel" name="tam-menuLevel" class="btn btn-primary mb-2">Tambah Baru</button>
    <form id="traspcb">
        <div class="table-responsive" style="height:calc(100vh - 397px);">
            <table class="table table-border table-hover table-sm" id="tabel1">
                <thead class="table-secondary sticky-top zindex-2">
                    <tr>
                        <th>ID</th>
                        <th width='12%'>nama Level</th>
                        <th>User</th>
                        <th width='60%' >Menu</th>
                    </tr>
                </thead>
                <tbody>

                    {{-- {{ dd($settings) }} --}}

                    @forelse ($data as $data1)
                        <tr class="kliklevel" id="{{ $data1->Id_Level }}">
                            <td>{{ $data1->Id_Level }} </td>
                            <td>{{ $data1->Nama_level }}</td>
                            <td>{{ $data1->USER }}</td>
                            <td>
                                @if ($data1->Id_Level == '1')
                                    All Access
                                @elseif ($data1->Id_Level == '2')
                                    All (Active Status)
                                @else
                                    {{ $data1->akses }}
                                @endif
                            </td>
                            {{-- <td>
                <button type="button" class="btn btn-primary btn-sm modal-menuLevel" value="{{$data1->Id_Level}}"> <i class="bx bx-edit-alt me-1"></i> Edit </button> &ensp;
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

@include('Setting.menuLevelModal')

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

    $(".kliklevel").on('click', function(e) {
        var id = $(this).attr('id');
        editlevel(id);
    });
</script>

@include('layouts.backend-Theme-3.XtraScript')

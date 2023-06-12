<div class="table-responsive" style="height:calc(100vh - 380px);">

    <table class="table table-bordered table-striped table-hover table-sm" id="tabel1" style="zoom: 0.95;">
        <thead class="table-secondary sticky-top zindex-2">
            <tr style="text-align: center; ">

                <th>Order</th>
                <th>Tanggal</th>
                <th>karyawan</th>
                <th>Bagian</th>
                <th>Urut</th>
                <th width='300'>Barang</th>
                <th width='300'>Deskripsi</th>
                <th>QTY</th>
                <th>Tipe</th>
                <th>Diperlukan</th>
                <th>Dikerjakan</th>
                <th>Note</th>

            </tr>
        </thead>
        <tbody>

            @foreach ($datas as $data)
                <tr class="klik" id="{{ $data->ID }}" id2="{{ $data->LinkOrd }}">
                    <td>{{ $data->CODE }} </td>
                    <td>{{ $data->TransDate }}</td>
                    <td>{{ $data->Employee }}</td>
                    <td>{{ $data->Department }}</td>
                    <td>{{ $data->LinkOrd }}</td>
                    <td>{{ $data->Product }}</td>
                    <td>{{ $data->Description }}</td>
                    <td>{{ $data->Qty }}</td>
                    <td>{{ $data->Type }}</td>
                    <td>{{ $data->DateNeeded }}</td>
                    <td>{{ $data->Worked }}</td>
                    <td>{{ $data->ToDo }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

@include('layouts.backend-Theme-3.DataTabelButton')

<script>
    //patch lokasi modul
    // var patch = '/Workshop/WorkshopApproval/';

    // -------------------- klik di tabel --------------------
    $(".klik").on('click', function(e) {
        var id = $(this).attr('id');
        var id2 = $(this).attr('id2');
        lihat(id,id2);
    });


    var table = $('#tabel1').DataTable({
        "paging": false,
        // "pageLength": 13,
        "lengthChange": false,
        "searching": true,
        "ordering": true,
        "info": false,
        "autoWidth": true,
        "responsive": true,
        "fixedColumns": true,
        buttons: [{
            extend: 'print',
            split: ['excel', 'pdf'],
            orientation: 'landscape',

        }],
        order: [
            [0, 'desc']
        ]
    });
    table.buttons().container().appendTo('#tabel1_wrapper .col-md-6:eq(0)');
</script>

@include('layouts.backend-Theme-3.XtraScript')

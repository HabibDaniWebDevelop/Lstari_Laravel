<div class="table-responsive text-nowrap" style="height:calc(100vh - 380px);">

    <table class="table table-bordered table-striped table-hover table-sm" id="tabel1">
        <thead class="table-secondary sticky-top zindex-2">
            <tr style="text-align: center">
                @if ($cari == '1')
                    <th>ID Order</th>
                    <th>Tanggal</th>
                    <th>Karyawan</th>
                    <th>Divisi</th>
                    <th>Keperluan</th>
                    <th>Produk</th>
                    <th>QTY</th>
                    <th>Kategori</th>
                    <th>Keterangan</th>
                @elseif ($cari == '2')
                    <th>ID Order</th>
                    <th>Keperluan</th>
                    <th>Produk</th>
                    <th>QTY</th>
                    <th>Kategori</th>
                    <th>Keterangan</th>
                    <th>Tgl. Butuh</th>
                    <th>Tgl. Mulai</th>
                    <th>Pelaksana</th>
                    <th>Kegiatan</th>
                @elseif ($cari == '3')
                    <th>ID Order</th>
                    <th>Pengirim</th>
                    <th>Bagian</th>
                    <th>Keperluan</th>
                    <th>Produk</th>
                    <th>QTY</th>
                    <th>Keterangan</th>
                    <th>Dikerjakan</th>
                    <th>Pelaksana</th>
                    <th>Kegiatan</th>
                @elseif ($cari == '4')
                    <th>ID Order</th>
                    <th>Keperluan</th>
                    <th>Produk</th>
                    <th>QTY</th>
                    <th>Kategori</th>
                    <th>Keterangan</th>
                    <th>Tgl. Butuh</th>
                    <th>Tgl. Mulai</th>
                    <th>Pelaksana</th>
                    <th>Kegiatan</th>
                @else
                    <th>ID Order</th>
                    <th>Tanggal</th>
                    <th>Karyawan</th>
                    <th>Divisi</th>
                    <th>Keperluan</th>
                    <th>Produk</th>
                    <th>QTY</th>
                    <th>Kategori</th>
                    <th>Keterangan</th>
                    <th>Pelaksana</th>
                    <th>Kegiatan</th>
                @endif

            </tr>
        </thead>
        <tbody>

            @if ($cari == '1')
                @foreach ($datas as $data)
                    <tr style="text-align: center">
                        <td>{{ $data->CODE }}</td>
                        <td>{{ date('d-m-Y', strtotime($data->TransDate)) }}</td>
                        <td>{{ $data->Employee }}</td>
                        <td>{{ $data->Department }}</td>
                        <td>{{ $data->Purpose }}</td>
                        <td>{{ $data->Product }}</td>
                        <td>{{ $data->Qty }}</td>
                        <td>{{ $data->Category }}</td>
                        <td>{{ $data->Description }}</td>
                    </tr>
                @endforeach
            @elseif ($cari == '2')
                @foreach ($datas as $data)
                    <tr style="text-align: center">
                        <td>{{ $data->CODE }}</td>
                        <td>{{ $data->Purpose }} </td>
                        <td>{{ $data->Product }}</td>
                        <td>{{ $data->Qty }}</td>
                        <td>{{ $data->Category }}</td>
                        <td>{{ $data->Description }}</td>
                        <td>{{ $data->DateNeeded }}</td>
                        <td>{{ $data->DateStart }}</td>
                        <td>{{ $data->Worked }}</td>
                        <td>{{ $data->ToDo }}</td>
                    </tr>
                @endforeach
            @elseif ($cari == '3')
                @foreach ($datas as $data)
                    <tr style="text-align: center">
                        <td>{{ $data->CODE }}</td>
                        <td>{{ $data->Employee }} </td>
                        <td>{{ $data->Department }}</td>
                        <td>{{ $data->Purpose }}</td>
                        <td>{{ $data->Product }}</td>
                        <td>{{ $data->Qty }}</td>
                        <td>{{ $data->Description }}</td>
                        <td>{{ $data->DateStart }}</td>
                        <td>{{ $data->Worked }}</td>
                        <td>{{ $data->ToDo }}</td>
                    </tr>
                @endforeach
            @elseif ($cari == '4')
                @foreach ($datas as $data)
                    <tr style="text-align: center">
                        <td>{{ $data->CODE }}</td>
                        <td>{{ $data->Purpose }} </td>
                        <td>{{ $data->Product }}</td>
                        <td>{{ $data->Qty }}</td>
                        <td>{{ $data->Category }}</td>
                        <td>{{ $data->Description }}</td>
                        <td>{{ $data->DateNeeded }}</td>
                        <td>{{ $data->DateStart }}</td>
                        <td>{{ $data->Worked }}</td>
                        <td>{{ $data->ToDo }}</td>
                    </tr>
                @endforeach
            @else
                @foreach ($datas as $data)
                    <tr style="text-align: center">
                        <td>{{ $data->CODE }}</td>
                        <td>{{ date('d-m-Y', strtotime($data->TransDate)) }}</td>
                        <td>{{ $data->Employee }}</td>
                        <td>{{ $data->Department }}</td>
                        <td>{{ $data->Purpose }}</td>
                        <td>{{ $data->Product }}</td>
                        <td>{{ $data->Qty }}</td>
                        <td>{{ $data->Category }}</td>
                        <td>{{ $data->Description }}</td>
                        <td>{{ $data->Worked }}</td>
                        <td>{{ $data->ToDo }}</td>
                    </tr>
                @endforeach

            @endif

        </tbody>
    </table>
</div>

@include('layouts.backend-Theme-3.DataTabelButton')

<script>
    var table = $('#tabel1').DataTable({
        "paging": true,
        "pageLength": 13,
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
        
        }]
    });
    table.buttons().container().appendTo('#tabel1_wrapper .col-md-6:eq(0)');
</script>

@include('layouts.backend-Theme-3.XtraScript')

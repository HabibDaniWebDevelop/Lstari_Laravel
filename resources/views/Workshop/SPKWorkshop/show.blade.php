<div class="row my-4">

    <div class="col-md-6 mb-2">
        <div class="form-group">
            <label class="form-label">ID</label>
            <input type="text" class="form-control" name="tanggal" id="hasilid" readonly value="{{ $data1[0]->IDwk }}">
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label class="form-label">No Order</label>
            <input type="text" class="form-control" readonly value="{{ $data1[0]->noor }}">
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label class="form-label">Tanggal</label>
            <input type="text" class="form-control" id="tgl_masuk" name="tgl_masuk" readonly
                value="{{ $data1[0]->aku }}">
        </div>
    </div>

    <div class="col-md-6 mb-2">
        <div class="form-group">
            <label class="form-label">Karyawan</label>
            <input type="text" class="form-control" name="karyawan" id="karyawan" readonly
                value="{{ $data1[0]->namakar }}" onChange="getkary()">
        </div>
    </div>

    <div class="col-md-6 mb-2">
        <div class="form-group">
            <label class="form-label">Bagian</label>
            <input type="text" class="form-control" name="bagian" id="bagian" readonly
                value="{{ $data1[0]->jabatan }}">
        </div>
    </div>

    <div class="col-md-6 mb-2">
        <div class="form-group">
            <label class="form-label">Keperluan</label>
            <input type="text" class="form-control" name="bagian" id="bagian" readonly
                value="{{ $data1[0]->Purpose }}">
        </div>
    </div>

    <div class="col-md-12">
        <div class="form-group">
            <label class="form-label">Catatan</label>
            <input type="text" class="form-control" name="catatan" id="catatan" readonly
                value="{{ $data1[0]->Remarks }}">
        </div>
    </div>

</div>

<div class="table-responsive text-nowrap">

    <table class="table table-border table-hover table-sm" id="tabel1">
        <thead class="table-secondary sticky-top zindex-2">
            <tr style="text-align: center">
                <th>Urut</th>
                <th>ID Inventaris</th>
                <th>Barang</th>
                <th>Jumlah</th>
                <th>Tipe</th>
                <th>Kategori</th>
                <th>Tgl Butuh</th>
                <th>Deskripsi</th>
                <th>Bagian Inventaris</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($WorkShopOrderItems as $data2)
                <tr class="baris" id="2">
                    <td>{{ $loop->iteration }} </td>
                    <td>{{ $data2->Inventory }}</td>
                    <td>{{ $data2->Product }}</td>
                    <td>{{ $data2->Qty }}</td>
                    <td>{{ $data2->Type }}</td>
                    <td>{{ $data2->Category }}</td>
                    <td>{{ $data2->DateNeeded1 }}</td>
                    <td>{{ $data2->IDescription }}</td>
                    <td> </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

<script>
    $(document).ready(function() {
        $('#tabel1').DataTable({
            "paging": false,
            "lengthChange": false,
            "searching": false,
            "ordering": true,
            "info": false,
            "autoWidth": true,
            "responsive": true,
            "fixedColumns": true,
        });
    });
</script>

@extends('layouts.backend-Theme-3.XtraScript')

<div class="card-body">
    <div id="DataTableContainer">
        <table class="table table-striped" id="TabelJaminanKaryawan">
            <thead class="table-secondary">
                <tr>
                    <th width="5%">ID</th>
                    <th width="15%">Nama Karyawan</th>
                    <th width="19%">Jenis Jaminan</th>
                    <th width="15%">No Jaminan</th>
                    <th width="10%">Diterima</th>
                    <th width="10%">Diambil</th>
                    <th width="18%">Catatan</th>
                    <th width="8%" class="text-center">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($employeeguarantee as $item)
                <tr>
                    <td>{{$item->ID}}</td>
                    <td>{{$item->NAME}}</td>
                    <td>{{$item->Type}}</td>
                    <td>{{$item->SW}}</td>
                    <td>{{$item->TransDate}}</td>
                    <td>{{$item->ReturnDate}}</td>
                    <td>{{$item->Remarks}}</td>
                    <td class="text-center">
                        @if (empty($item->ReturnDate))
                            <button class="btn btn-primary" onclick="ProcessEmployeeGuarantee({{$item->ID}})">Proses</button>
                        @else
                            <span class="badge bg-info">Sudah Diambil</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
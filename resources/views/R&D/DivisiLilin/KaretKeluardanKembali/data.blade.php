<div class="card">
    <h5 class="card-header">Tabel Karet Keluar & Kembali</h5>
    <!-- <div class="card-body">
        <div class="demo-inline-spacing mb-4">
            <button type="button" class="btn btn-primary me-4 " id="Baru1" onclick="TabelLama()"> <span
                    class="tf-icons bx bx-table"></span>&nbsp; Tabel Versi Lama </button>
            <button type="button" class="btn btn-info" id="Cetak1" onclick="Klik_Cetak1()"> <span
                    class="tf-icons bx bx-printer"></span>&nbsp; Cetak</button>

            <div class="float-end">
                <div class="input-group input-group-merge">
                    <span class="input-group-text"><i class="bx bx-search" onclick="klikViewSelection()"></i></span>
                    <input type="text" class="form-control" placeholder="Search..." autofocus id='cari' list="carilist"
                        onchange="ChangeCari()" />
                </div>
                <datalist id="carilist">


                </datalist>
            </div>
            <hr class="m-0" />

        </div> -->
    <div class="card-body">

        <div class="table-responsive text-nowrap" style="height:calc(100vh - 490px);">
            <table class="table table-border table-hover table-sm" id="tabel1">
                <thead class="table-secondary sticky-top zindex-2">

                    <tr>
                        <th width="5%" class="text-center">No</th>
                        <th width="10%" class="text-center">SPK Inject</th>
                        <th class="text-center">Tanggal</th>
                        <th width="5%" class="text-center">Work Group</th>
                        <th width="15%" class="text-center">Product</th>
                        <th width="15%" class="text-center">ID Karet</th>
                        <th width="5%" class="text-center">Kadar</th>
                        <th class="text-center">Tanggal Kembali</th>
                    </tr>
                </thead>
                <tfoot>

                </tfoot>

                <tbody>
                    @forelse ($data as $datas)
                    <tr id="{{ $datas->LinkID }}">
                        <td width="5%" class="text-center">{{ $loop->iteration }} </td>
                        <td width="10%" class="text-center"> <span class="badge bg-dark"
                                style="font-size:14px;">{{ $datas->LinkID }}</span>
                        </td>
                        <td class="text-center">{{ $datas->TransDate }}</td>
                        <td width="5%" class="text-center">{{ $datas->WorkGroup }}</td>
                        <td width="15%" class="text-center">{{ $datas->product }}</td>
                        <td width="15%" class="text-center">{{ $datas->idKaret }}</td>
                        <td width="5%" class="text-center">{{ $datas->kadar }}</td>
                        <td class="text-center">{{ $datas->ReturnDate }}</td>
                    </tr>
                    @empty
                    <div class="alert alert-danger">
                        Data Blog belum Tersedia.
                    </div>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $data->links('pagination::bootstrap-4') }}
    </div>
</div>
<h5 class="card-header">Manage Hardware</h5>
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
        <div class="table-responsive text-nowrap" style="height:calc(100vh - 490px);">
        <table class="table table-border table-hover table-sm" id="tabel1">
            <thead class="table-secondary sticky-top zindex-2">
                <tr style="text-align: center">
                    <th>No.</th>
                    <th>Printer Code</th>
                    <th>Nama</th>
                    <th>IP Address</th>
                    <th>Brand</th>
                    <th>Type</th>
                    <th>Series</th>
                    <th>Serial Number</th>
                    <th>Supplier</th>
                    <th>Purchase Date</th>
                    <th>Status</th>
                    <th>Department</th>
                    <th>CPU</th>
                    <th>Note</th>
                    <th>Entry Date</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>

                {{-- {{ dd($data) }} --}}
                @forelse ($data as $data1)
                    <tr class="klik" id="{{ $data1->ID }}">
                        <td>{{ ($data->currentPage() - 1) * $data->perPage() + $loop->iteration }} </td>
                        <td> <span class="badge bg-dark" style="font-size:14px;">{{ $data1->SW }}</span> </td>
                        <td>{{ $data1->Description }}</td>
                        <td>{{ $data1->IPAddress }}</td>
                        <td>{{ $data1->Brand }}</td>
                        <td>{{ $data1->SubType }}</td>
                        <td>{{ $data1->Series }}</td>
                        <td>{{ $data1->SerialNo }}</td>
                        
                        <td>{{ $data1->Supplier }}</td>
                        <td>{{ $data1->PurchaseDate }}</td>
                        <td>{{ $data1->STATUS }}</td>
                        <td>{{ $data1->Department }}</td>
                        <td>{{ $data1->ComputerName }}</td>
                        <td>{{ $data1->Note }}</td>
                        <td>{{ $data1->EntryDate }}</td>

                        <td>
                            <div class="btn-group" role="group">
                                <button class="btn btn-danger btn-sm" style="font-size: 14px;"
                                    onclick="klikedit('{{ $data1->ID }}')">Edit</button>
                                <button class="btn btn-warning btn-sm" style="font-size: 14px;"
                                    onclick="klikcetak('{{ $data1->ID }}')">Cetak</button>
                            </div>
                        </td>
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

@include('IT.MasterDataHardware.DataPrinter.modal')

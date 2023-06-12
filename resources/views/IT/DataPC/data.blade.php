{{-- <h5 class="card-header">Manage Hardware</h5> --}}
<div class="card-body">

  <div class="card-body d-flex justify-content-between p-0">
    <button class="btn btn-primary mb-2" onclick="kliktambah()">Tambah Data</button>
  
    <div class="float-end">
      <div class="input-group input-group-merge">
        <span class="input-group-text"><i class="bx bx-search"></i></span>
        <input type="text" class="form-control" placeholder="Search..." autofocus id='cari' onkeydown="klikCari()" />
      </div>
    </div>
  </div>
    <div class="table-responsive text-nowrap" style="height:calc(100vh - 415px);">
      <table class="table table-border table-hover table-sm" id="tabel1" >
        <thead class="table-secondary sticky-top zindex-2">
          <tr style="text-align: center">
            <th >No.</th>
            <th >Kode Komputer</th>
            <th >Computer Name</th>
            <th >Type</th>
            <th >IP Address</th>
            <th >MAC Address</th>
            <th >Mainboard</th>
            <th >Processor</th>
            <th >Memory 1</th>
            <th >Memory 2</th>
            <th >Storage 1</th>
            <th >Storage 2</th>
            <th >Monitor</th>
            <th >VGA</th>
            <th >Keyboard</th>
            <th >Mouse</th>
            <th >Printer 1</th>
            <th >Printer 2</th>
            <th >WeightScale</th>
            <th >UPS</th>
            <th >Scanner</th>
            <th >Barcode Scanner</th>
            <th >Series</th>
            <th >Operating System</th>
            <th >Domain</th>
            <th >Antivirus</th>
            <th >User</th>
            <th >Status</th>
            <th >Note</th>
            <th >Supplier</th>
            <th >Purchase Date</th>
            <th >Entry Date</th>
            {{-- <th >Action</th> --}}
          </tr>
        </thead>
        <tbody>

          {{-- {{ dd($data) }} --}}
          @forelse ($data as $data1)
          <tr class="klik" id="{{ $data1->ID }}" id2="{{ $data1->SW }}">
              <td>{{ ($data->currentPage() - 1) * $data->perPage() + $loop->iteration }}</td>
              <td> <span class="badge bg-dark" style="font-size:14px;">{{ $data1->SW }}</span> </td>
              <td>{{ $data1->ComputerName }}</td>
              <td>{{ $data1->Type }}</td>
              <td>{{ $data1->IPAddress }}</td>
              <td>{{ $data1->MACAddress }}</td>
              <td>{{ $data1->Mainboard }}</td>
              <td>{{ $data1->Processor }}</td>

              <td>{{ $data1->Memory1 }}</td>
              <td>{{ $data1->Memory2 }}</td>
              <td>{{ $data1->Storage1 }}</td>
              <td>{{ $data1->Storage2 }}</td>
              <td>{{ $data1->Monitor }}</td>
              <td>{{ $data1->VGA }}</td>
              <td>{{ $data1->Keyboard }}</td>
              <td>{{ $data1->Mouse }}</td>
              <td>{{ $data1->Printer1 }}</td>
              <td>{{ $data1->Printer2 }}</td>

              <td>{{ $data1->WeightScale }}</td>
              <td>{{ $data1->UPS }}</td>
              <td>{{ $data1->Scanner }}</td>
              <td>{{ $data1->BarcodeScanner }}</td>
              <td>{{ $data1->Series }}</td>
              <td>{{ $data1->OperatingSystem }}</td>
              <td>{{ $data1->Domain }}</td>
              <td>{{ $data1->Antivirus }}</td>
              <td>{{ $data1->Employee }}</td>

              <td>{{ $data1->STATUS }}</td>
              <td>{{ $data1->Note }}</td>
              <td>{{ $data1->Supplier }}</td>
              <td>{{ $data1->PurchaseDate }}</td>
              <td>{{ $data1->EntryDate }}</td>

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

@include('it.datapc.modal')



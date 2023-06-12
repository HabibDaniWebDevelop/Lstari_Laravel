<div class="demo-inline-spacing" id="btn-menu">

    <button type="button" class="btn btn-white" id="Batal1" disabled > NTHKO</button>
   

    <div class="d-flex float-end">

        <div class="input-group input-group-merge" style="width: 200px;">
            <span class="input-group-text"><i class="bx bx-search" onclick="klikViewSelection()"></i></span>
            <input type="search" class="form-control" autofocus id='cari' onkeydown="klikCari()" placeholder="search...">
        </div>

    </div>
    <hr class="m-0" />

</div>

<div class="card-body pb-0">
    <div class="table-responsive text-nowrap" style="height:calc(100vh - 425px);">
        <table class="table table-border table-hover table-sm" id="tabel1">
            <thead class="table-secondary sticky-top zindex-2">
                <tr style="text-align: center">
                    <th>No.</th>
                    <th>WorkAllocation</th>
                    <th>Tanggal</th>
                    <th>User</th>
                    <th>Gambar</th>
                </tr>
            </thead>
            <tbody>

                {{-- {{ dd($data) }} --}}
                @forelse ($data as $data1)
                @php
                if ($data1->Active == 'P') {
                $status = 'Posting';
                } elseif ($data1->Active == 'A') {
                $status = 'Simpan';
                } else {
                $status = 'Cancle';
                }

                if ($data1->Upload == 'S') {
                $upload = '<span class="badge bg-warning" style="font-size:14px;"> Uploadad </span>';
                } else {
                $upload = '';
                }

                @endphp


                <tr class="klik" id="{{ $data1->WorkAllocation }}">
                    <td>{{ ($data->currentPage() - 1) * $data->perPage() + $loop->iteration }} </td>
                    <td> <span class="badge bg-dark" style="font-size:14px;">{{ $data1->WorkAllocation }}</span>
                    </td>
                    <td>{{ $data1->TransDate }}</td>
                    <td>{{ $data1->UserName }}</td>
                    <td>{!! $upload !!}</td>
                </tr>
                @empty
                @endforelse

            </tbody>
        </table>
    </div>
    {{ $data->links('pagination::bootstrap-4') }}
</div>

<script>

</script>

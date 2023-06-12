<div class="table-responsive text-nowrap" style="height:calc(100vh - 490px);">
    <table class="table table-border table-hover table-sm" id="tabel1">
        <thead class="table-secondary sticky-top zindex-2">
            <tr style="text-align: center">
                <th>No</th>
                <th>ID</th>
                <th>TransDate</th>
                <th>Employee</th>
                <th>Qty</th>
                <th>Brt</th>
            </tr>
        </thead>
        <tbody>
          @forelse ($data as $data1)
              <tr class="klikStatus" id="{{ $data1->ID }}" style="text-align: center">
                  <td>{{ $loop->iteration }} </td>
                  <td>{{ $data1->ID }}</td>
                  <td>{{ $data1->TransDate }}</td>
                  <td>{{ $data1->EmpName }}</td>
                  <td>{{ $data1->TotalQty }}</td>
                  <td>{{ $data1->TotalWeight }}</td>
              </tr>
          @empty
              <div class="alert alert-danger">
                  Data Belum Tersedia.
              </div>
          @endforelse
        </tbody>
        <tfoot>

        </tfoot>

        {{-- {{ dd($query); }} --}}
        
    </table>
</div>  
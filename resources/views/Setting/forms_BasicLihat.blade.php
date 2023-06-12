{{-- {{ dd($datas); }} --}}

<div class="table-responsive text-nowrap" style="height:calc(100vh - 435px);">
    <table class="table table-inverse table-hover table-sm" id="tabel1">
        <thead class="table-secondary sticky-top zindex-2">
            <tr style="text-align: center">
                <th width='6%'> NO </th>
                <th> SW </th>
                <th width='30%'> Description </th>
                <th> TGL TRANS </th>
                <th width='30%'> Keterangan </th>
            </tr>
        </thead>
        <tbody>

            @foreach ($datas as $data)
                <tr class="baris" id="2">
                    <td>{{ $loop->iteration }} </td>
                    <td>{{ $data->SW }}</td>
                    <td>{{ $data->Description }}</td>
                    <td>{{ $data->EntryDate }}</td>
                    <td> </td>
                </tr>
            @endforeach

        </tbody>

    </table>

</div>

@forelse ($datas as $data)
<tr class="klik" id="{{ $data->ID }}">
    <td>{{$loop->iteration }}
    </td>
    <td> {{ $data->UserName }} </td>
    <td> {{ date('d-m-y', strtotime($data->TransDate)) }} </td>
    <td> {{ $data->Note }}</td>
    <td> {{ $data->SW }}</td>
    <td> {{ date('d-m-y', strtotime($data->ValidToDate)) }}</td>
</tr>
@empty

@endforelse

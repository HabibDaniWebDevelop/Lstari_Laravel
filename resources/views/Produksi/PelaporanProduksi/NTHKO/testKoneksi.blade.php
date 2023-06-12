@forelse ($data as $datas)
<table>
    <tr>
        <td>{{ $loop->iteration }} </td>
        <td>{{ $datas->ID }}</td>
        <td>{{ $datas->SW }}</td>
        <td>{{ $datas->Description }}</td>
    </tr>
</table>
@empty
    <div class="alert alert-danger">
        Data Belum Tersedia.
    </div>
@endforelse
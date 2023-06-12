@foreach ($matrasAllocation->matrasAllocationItems as $item)
    <tr class='rowItem' id='Row_{{$loop->iteration}}'>
        <td>{{$loop->iteration}}</td>
        <td>{{$item->Matras->SW}}</td>
        <td>{{$item->Matras->JenisMatras}}</td>
        <td>{{$item->Matras->TipeMatras}}</td>
        <td>
            @foreach ($item->Matras->Items as $product)
                {{$product->Product->SW}}<br>
            @endforeach
        </td>
    </tr>
@endforeach
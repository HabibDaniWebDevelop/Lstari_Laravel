<table class="table table-borderless table-sm" id="tabel1">
    <thead class="table-secondary">
        <tr style="text-align: center">
            <th>NO</th>
            <th>Photo 2D</th>
            <th>Photo 3D</th>
            <th>Product</th>
            <th>File Corel</th>
            <th>File 3D</th>
        </tr>
    </thead>
    <tbody class="text-center">
        @foreach ($finalData as $item)
            <tr>
                <td>{{$loop->iteration}}</td>
                <td><a href="{{Session::get('hostfoto')}}/rnd2/Drafter 2D/{{$item['jenisPart']}}/{{$item['imageProduct']}}" onerror="this.onerror=null; this.src='{!! asset('assets/images/no-photos.jpg') !!}'" data-lightbox="image-{{$loop->iteration}}"><img src="{{Session::get('hostfoto')}}/rnd/Drafter 2D/{{$item['jenisPart']}}/{{$item['imageProduct']}}" width="150" height="150" onerror="this.onerror=null; this.src='{!! asset('assets/images/no-photos.jpg') !!}'" alt=""></a></td>
                <td><a href="{{Session::get('hostfoto')}}/rnd2/Drafter 3D/Image 3D/{{$item['imageProduct3D']}}" onerror="this.onerror=null; this.src='{!! asset('assets/images/no-photos.jpg') !!}'" data-lightbox="image-{{$loop->iteration}}"><img src="{{Session::get('hostfoto')}}/rnd/Drafter 3D/Image 3D/{{$item['imageProduct3D']}}" width="150" height="150" onerror="this.onerror=null; this.src='{!! asset('assets/images/no-photos.jpg') !!}'" alt=""></a></td>
                <td>
                    <span style="font-size: 14px" class="badge bg-primary">{{$item['swProduct']}}</span>
                    <br>
                    @foreach ($item['productFG'] as $itemFG)
                        {{$itemFG['namaProduct']}}
                    @endforeach
                </td>
                <td><a href="{{Session::get('hostfoto')}}/rnd2/Drafter 2D/{{$item['jenisPart']}}Corel/{{$item['corelFile']}}" target="_BLANK">Download</a></td>
                <td><a href="{{Session::get('hostfoto')}}/rnd2/Drafter 3D/File Rhino/{{$item['file3DM']}}" target="_BLANK">Download</a></td>
            </tr>
        @endforeach
    </tbody>
</table>
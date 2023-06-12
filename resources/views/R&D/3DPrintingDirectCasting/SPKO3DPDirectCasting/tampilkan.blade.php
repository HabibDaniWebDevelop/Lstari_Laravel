
<br>
<div class="table-responsive text-nowrap" style="height:calc(100vh - 550px);">
{{-- <form id="form2"> --}}
<table class="table table-border table-hover table-sm" id="tampiltabel">
    <thead class="table-secondary sticky-top zindex-2">
        <tr>
            <th>#</th>
            <th>Kode Produk</th>
            <th>Deskripsi</th>  
            <th>3D ID</th>
            <th>Kadar</th>
            <th>Qty</th>
            {{-- <th>Foto</th> --}}
            <th>SPK Direct Casting</th> 
            <th>ID Permintaan</th> 
        </tr>                   
    </thead>
    <tbody>
        @forelse ($data as $dataOK)
            <tr class="klik" id="{{ $dataOK->IDX }}">
                <td><input type="checkbox" class="form-check-input" name="cekan[]" id="cek_{{ $dataOK->IDX }}" value="{{ $dataOK->ID }}" data-worklistidord="{{$dataOK->WorklistOrd}}" data-product="{{ $dataOK->Product }}" data-qty="{{ $dataOK->Qty }}" data-id3d="{{ $dataOK->ID3D }}" data-worklistid="{{ $dataOK->Worklist }}"></td>   
                <td><span style="font-size: 14px" class="badge bg-dark" >{{$dataOK->codes}}</span><br>{{$dataOK->SKU}}</td>
                <td>{{$dataOK->mname}}</td>
                <td>{{$dataOK->SW}}</td>
                <td>{{$dataOK->Description}}</td>
                <td>{{$dataOK->Qty}}</td>
                {{-- <td>
                <div class="col-lg-6 col-md-6 m-b-20">
                    <a class="image-link" href="{{$dataOK->foto}}"><img src="{{$dataOK->foto}}" width="50px" height="50px"></a><br>
                    <button type="button" class="form-control" onclick="download($dataOK->File3DM)">Download</button>
                </div>
                </td>--}}
                <td ><span class="badge bg-dark" >{{$dataOK->WO}}</span>
                <td>{{$dataOK->ID}}</td>
                </td>  
            </tr>
            @empty
            <div class="alert alert-danger">
                Data Belum Tersedia.
            </div>
        @endforelse
    </tbody>
</table>
{{-- </form> --}}
</div>
<script>
    $(".klik").on('click', function(e) {
        // $('.klik').css('background-color', 'white');
        var id = $(this).attr('id');
        if ($(this).hasClass('table-secondary')) {
            $(this).removeClass('table-secondary');
            $('#cek_' + id).attr('checked', false);
        } else {
            $(this).addClass('table-secondary');
            $('#cek_' + id).attr('checked', true);
        }
        return false;
    });
</script>

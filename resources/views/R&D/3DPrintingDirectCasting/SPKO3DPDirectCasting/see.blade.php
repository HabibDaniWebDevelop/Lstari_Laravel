
<br>
<div class="table-responsive text-nowrap" style="height:calc(100vh - 550px);">
{{-- <form id="form2"> --}}
<table class="table table-border table-hover table-sm" id="tampiltabel2">
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
        </tr>                   
    </thead>
    <tbody>
        @forelse ($datap as $dataOK)
            <tr class="klik" id="{{ $dataOK->ID }}">
                <td><input type="checkbox" class="form-check-input" name="cekan[]" id="cek_{{ $dataOK->ID }}" value="{{ $dataOK->ID }}" data-product="{{ $dataOK->Product }}" data-qty="{{ $dataOK->Qty }}" data-id3d="{{ $dataOK->ID3D }}" data-worklistid="{{ $dataOK->Worklist }}"></td>   
                <td><span style="font-size: 14px" class="badge bg-dark" >{{$dataOK->codes}}</span><br>{{$dataOK->SKU}}</td>
                <td>{{$dataOK->mname}}</td>
                <td>{{$dataOK->SW}}</td>
                <td>{{$dataOK->Description}}</td>
                <td>{{$dataOK->Qty}}</td>
                {{-- <td>
                <div class="col-lg-6 col-md-6 m-b-20"><a class="image-link" href="{{$dataOK->foto}}"><img src="{{$dataOK->foto}}" width="50px" height="50px"></a><br><a href="/rnd/Drafter 3D/File Rhino/{{$dataOK->File3DM}}">3DM File</a></div>
                </td>                 --}}
                <td ><span class="badge bg-dark" >{{$dataOK->WO}}</span>
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

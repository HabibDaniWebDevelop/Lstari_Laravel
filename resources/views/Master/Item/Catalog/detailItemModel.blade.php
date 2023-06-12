{{-- Carousel Image --}}
<div class="swiper text-center" id="CarouselModel">
    <div class="swiper-wrapper">
        <div class="swiper-slide" id="CarouselModelItem_1"><a href="{{Session::get('hostfoto')}}/image2/{{$foto}}.jpg" data-lightbox="image-Model"><img src="{{Session::get('hostfoto')}}/image2/{{$foto}}.jpg" onerror="this.onerror=null; this.src='{!! asset('assets/images/no-photos.jpg') !!}'"></a></div>   
        <div class="swiper-slide" id="CarouselModelItem_2"><a href="{{Session::get('hostfoto')}}/image2/{{$foto}}-1.jpg" data-lightbox="image-Model"><img src="{{Session::get('hostfoto')}}/image2/{{$foto}}-1.jpg" onerror="removeElementModel(2);"></a></div>
        <div class="swiper-slide" id="CarouselModelItem_3"><a href="{{Session::get('hostfoto')}}/image2/{{$foto}}-2.jpg" data-lightbox="image-Model"><img src="{{Session::get('hostfoto')}}/image2/{{$foto}}-2.jpg" onerror="removeElementModel(3);"></a></div>
        <div class="swiper-slide" id="CarouselModelItem_4"><a href="{{Session::get('hostfoto')}}/image2/{{$foto}}-3.jpg" data-lightbox="image-Model"><img src="{{Session::get('hostfoto')}}/image2/{{$foto}}-3.jpg" onerror="removeElementModel(4);"></a></div>
        <div class="swiper-slide" id="CarouselModelItem_5"><a href="{{Session::get('hostfoto')}}/image2/{{$foto}}-4.jpg" data-lightbox="image-Model"><img src="{{Session::get('hostfoto')}}/image2/{{$foto}}-4.jpg" onerror="removeElementModel(5);"></a></div>
        <div class="swiper-slide" id="CarouselModelItem_6"><a href="{{Session::get('hostfoto')}}/image2/{{$foto}}-5.jpg" data-lightbox="image-Model"><img src="{{Session::get('hostfoto')}}/image2/{{$foto}}-5.jpg" onerror="removeElementModel(6);"></a></div>
        <div class="swiper-slide" id="CarouselModelItem_7"><a href="{{Session::get('hostfoto')}}/image2/{{$foto}}-6.jpg" data-lightbox="image-Model"><img src="{{Session::get('hostfoto')}}/image2/{{$foto}}-6.jpg" onerror="removeElementModel2(7);"></a></div>
    </div>
    <div class="swiper-button-next-Model"></div>
    <div class="swiper-button-prev-Model"></div>
    <div class="swiper-pagination-Model d-none" id="CarouselPaginationModel"></div>
</div>
{{-- End Carousel --}}
<br>
{{-- Tab Part, Accesories, and Plate --}}
<ul class="nav nav-pills mb-3 flex-column flex-md-row mb-3" role="tablist">
    <li class="nav-item">
        <button type="button" class="nav-link active" role="tab" data-bs-toggle="tab" data-bs-target="#partModel" aria-controls="part" aria-selected="true"> Part </button>
    </li>
    <li class="nav-item">
        <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#accesoriesModel" aria-controls="accesories" aria-selected="false"> Accesories </button>
    </li>
    <li class="nav-item">
        <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#plateModel" aria-controls="plate" aria-selected="false"> Plate </button>
    </li>
</ul>
{{-- End Tab Part, Accesories, and Plate  --}}
{{--  Content Part, Accesories, and Plate --}}
<div class="tab-content px-0 pt-1">
    {{-- Panel Part --}}
    <div class="tab-pane fade active show" id="partModel" role="tabpanel">
        <table class="table table-borderless table-sm datatable" id="tablepartModel">
            <thead class="table-secondary">
                <tr style="text-align: center">
                    <th> Part </th>
                    <th> Qty </th>
                </tr>
            </thead>
            <tbody class="text-center">
                @foreach ($part as $item)
                    <tr>
                        <td>{{$item->SW}}</td>
                        <td>{{$item->Qty}}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    {{-- End Panel Part --}}
    {{-- Panel Accesories --}}
    <div class="tab-pane fade" id="accesoriesModel" role="tabpanel">
        <table class="table table-borderless table-sm datatable" id="tableaccModel">
            <thead class="table-secondary">
                <tr style="text-align: center">
                    <th> Acc </th>
                    <th> Qty </th>
                </tr>
            </thead>
            <tbody class="text-center">
                @foreach ($accesories as $item)
                    <tr>
                        <td>{{$item->SW}}</td>
                        <td>{{$item->Qty}}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    {{-- End Panel Accesories --}}
    {{-- Panel Plate --}}
    <div class="tab-pane fade" id="plateModel" role="tabpanel">
        <table class="table table-borderless table-sm datatable" id="tableplateModel">
            <thead class="table-secondary">
                <tr style="text-align: center">
                    <th> Plate </th>
                    <th> Qty </th>
                </tr>
            </thead>
            <tbody class="text-center">
            </tbody>
        </table>
    </div>
    {{-- End Panel Plate --}}
</div>
{{--  End Content Part, Accesories, and Plate --}}

<script>
    // Data Table Settings in SPK
    var table = $('#tablepartModel').DataTable({
        "paging": false,
        "lengthChange": false,
        "searching": false,
        "ordering": true,
        "info": false,
        "autoWidth": true,
        "responsive": true,
        "fixedColumns": true,
    });
    table.columns().iterator('column', function(ctx, idx) {
        $(table.column(idx).header()).append('<span class = "sort-icon" / > ');
    });

    var table = $('#tableaccModel').DataTable({
        "paging": false,
        "lengthChange": false,
        "searching": false,
        "ordering": true,
        "info": false,
        "autoWidth": true,
        "responsive": true,
        "fixedColumns": true,
    });
    table.columns().iterator('column', function(ctx, idx) {
        $(table.column(idx).header()).append('<span class = "sort-icon" / > ');
    });

    var table = $('#tableplateModel').DataTable({
        "paging": false,
        "lengthChange": false,
        "searching": false,
        "ordering": true,
        "info": false,
        "autoWidth": true,
        "responsive": true,
        "fixedColumns": true,
    });
    table.columns().iterator('column', function(ctx, idx) {
        $(table.column(idx).header()).append('<span class = "sort-icon" / > ');
    });

    var swiperModel = new Swiper("#CarouselModel", {
        navigation: {
            nextEl: ".swiper-button-next-Model",
            prevEl: ".swiper-button-prev-Model",
        },
        pagination: {
            el: ".swiper-pagination-Model",
            clickable: true,
        },
    });
    
    function removeElementModel(index) {
        $('#CarouselModelItem_'+index).remove();
        swiperModel.update()
    }
    
    function removeElementModel2(index) {
        $('#CarouselModelItem_'+index).remove();
        $('#CarouselPaginationModel').removeClass('d-none')
        swiperModel.update()
    }
</script>
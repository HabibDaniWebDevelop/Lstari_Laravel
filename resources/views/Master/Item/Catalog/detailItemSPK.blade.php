{{-- Carousel Image --}}
<div class="swiper text-center" id="CarouselSPK">
    <div class="swiper-wrapper">
        <div class="swiper-slide" id="CarouselSPKItem_1"><a href="{{Session::get('hostfoto')}}/image2/{{$foto}}.jpg" data-lightbox="image-1"><img src="{{Session::get('hostfoto')}}/image2/{{$foto}}.jpg" onerror="this.onerror=null; this.src='{!! asset('assets/images/no-photos.jpg') !!}'"></a></div>   
        <div class="swiper-slide" id="CarouselSPKItem_2"><a href="{{Session::get('hostfoto')}}/image2/{{$foto}}-1.jpg" data-lightbox="image-1"><img src="{{Session::get('hostfoto')}}/image2/{{$foto}}-1.jpg" onerror="removeElement(2);"></a></div>
        <div class="swiper-slide" id="CarouselSPKItem_3"><a href="{{Session::get('hostfoto')}}/image2/{{$foto}}-2.jpg" data-lightbox="image-1"><img src="{{Session::get('hostfoto')}}/image2/{{$foto}}-2.jpg" onerror="removeElement(3);"></a></div>
        <div class="swiper-slide" id="CarouselSPKItem_4"><a href="{{Session::get('hostfoto')}}/image2/{{$foto}}-3.jpg" data-lightbox="image-1"><img src="{{Session::get('hostfoto')}}/image2/{{$foto}}-3.jpg" onerror="removeElement(4);"></a></div>
        <div class="swiper-slide" id="CarouselSPKItem_5"><a href="{{Session::get('hostfoto')}}/image2/{{$foto}}-4.jpg" data-lightbox="image-1"><img src="{{Session::get('hostfoto')}}/image2/{{$foto}}-4.jpg" onerror="removeElement(5);"></a></div>
        <div class="swiper-slide" id="CarouselSPKItem_6"><a href="{{Session::get('hostfoto')}}/image2/{{$foto}}-5.jpg" data-lightbox="image-1"><img src="{{Session::get('hostfoto')}}/image2/{{$foto}}-5.jpg" onerror="removeElement(6);"></a></div>
        <div class="swiper-slide" id="CarouselSPKItem_7"><a href="{{Session::get('hostfoto')}}/image2/{{$foto}}-6.jpg" data-lightbox="image-1"><img src="{{Session::get('hostfoto')}}/image2/{{$foto}}-6.jpg" onerror="removeElement2(7);"></a></div>
    </div>
    <div class="swiper-button-next-spk"></div>
    <div class="swiper-button-prev-spk"></div>
    <div class="swiper-pagination-spk d-none" id="CarouselPaginationSPK"></div>
</div>
{{-- End Carousel --}}
<br>
{{-- Tab Part, Accesories, and Plate --}}
<ul class="nav nav-pills mb-3 flex-column flex-md-row mb-3" role="tablist">
    <li class="nav-item">
        <button type="button" class="nav-link active" role="tab" data-bs-toggle="tab" data-bs-target="#partSPK" aria-controls="part" aria-selected="true"> Part </button>
    </li>
    <li class="nav-item">
        <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#accesoriesSPK" aria-controls="accesories" aria-selected="false"> Accesories </button>
    </li>
    <li class="nav-item">
        <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#plateSPK" aria-controls="plate" aria-selected="false"> Plate </button>
    </li>
</ul>
{{-- End Tab Part, Accesories, and Plate  --}}
{{--  Content Part, Accesories, and Plate --}}
<div class="tab-content px-0 pt-1">
    {{-- Panel Part --}}
    <div class="tab-pane fade active show" id="partSPK" role="tabpanel">
        <div class="table-responsive">
            <table class="table table-borderless table-sm datatable" id="tablepartSPK">
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
    </div>
    {{-- End Panel Part --}}
    {{-- Panel Accesories --}}
    <div class="tab-pane fade" id="accesoriesSPK" role="tabpanel">
        <div class="table-responsive">
            <table class="table table-borderless table-sm datatable" id="tableaccSPK">
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
    </div>
    {{-- End Panel Accesories --}}
    {{-- Panel Plate --}}
    <div class="tab-pane fade" id="plateSPK" role="tabpanel">
        <div class="table-responsive">
            <table class="table table-borderless table-sm datatable" id="tableplateSPK">
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
    </div>
    {{-- End Panel Plate --}}
</div>
{{--  End Content Part, Accesories, and Plate --}}

<script>
    // Data Table Settings in SPK
    var table = $('#tablepartSPK').DataTable({
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

    var table = $('#tableaccSPK').DataTable({
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

    var table = $('#tableplateSPK').DataTable({
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

    
    var swiperSPK = new Swiper("#CarouselSPK", {
        lazy: true,
        observer: true,
        observeParents: true,
        navigation: {
            nextEl: ".swiper-button-next-spk",
            prevEl: ".swiper-button-prev-spk",
        },
        pagination: {
            el: ".swiper-pagination-spk",
            clickable: true,
        },
    });
    
    function removeElement(index) {
        $('#CarouselSPKItem_'+index).remove();
        swiperSPK.update()
    }

    function removeElement2(index) {
        $('#CarouselSPKItem_'+index).remove();
        $('#CarouselPaginationSPK').removeClass('d-none')
        swiperSPK.update()
    }
</script>
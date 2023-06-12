{{-- Image --}}
<div class="row text-center">
    <div class="col-xxl-6 col-xl-6 col-md-12 col-sm-12 col-xs-12">
        <a href="{{Session::get('hostfoto')}}/image2/{{$fotoRaw}}.jpg" data-fancybox="tukangluar">
            <img style="max-width: 353px; max-height: 353px;" src="{{Session::get('hostfoto')}}/image2/{{$fotoRaw}}.jpg" onerror="this.onerror=null; this.src='{!! asset('assets/images/no-photos.jpg') !!}'">
        </a>
    </div>
    <div class="col-xxl-6 col-xl-6 col-md-12 col-sm-12 col-xs-12">
        <a href="{{Session::get('hostfoto')}}/image2/{{$foto}}.jpg" data-fancybox="tukangluar">
            <img style="max-width: 353px; max-height: 353px;" src="{{Session::get('hostfoto')}}/image2/{{$foto}}.jpg" onerror="this.onerror=null; this.src='{!! asset('assets/images/no-photos.jpg') !!}'">
        </a>
    </div>
</div>
{{-- End Image --}}
<br>
{{-- Tab Part, Accesories, and Plate --}}
<ul class="nav nav-pills mb-3 flex-column flex-md-row mb-3" role="tablist">
    <li class="nav-item">
        <button type="button" class="nav-link active" role="tab" data-bs-toggle="tab" data-bs-target="#partTukangLuar" aria-controls="part" aria-selected="true"> Part </button>
    </li>
    <li class="nav-item">
        <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#accesoriesTukangLuar" aria-controls="accesories" aria-selected="false"> Accesories </button>
    </li>
    <li class="nav-item">
        <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#plateTukangLuar" aria-controls="plate" aria-selected="false"> Plate </button>
    </li>
</ul>
{{-- End Tab Part, Accesories, and Plate  --}}
{{--  Content Part, Accesories, and Plate --}}
<div class="tab-content px-0 pt-1">
    {{-- Panel Part --}}
    <div class="tab-pane fade active show" id="partTukangLuar" role="tabpanel">
        <table class="table table-borderless table-sm datatable" id="tablepartTukangLuar">
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
    <div class="tab-pane fade" id="accesoriesTukangLuar" role="tabpanel">
        <table class="table table-borderless table-sm datatable" id="tableaccTukangLuar">
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
    <div class="tab-pane fade" id="plateTukangLuar" role="tabpanel">
        <table class="table table-borderless table-sm datatable" id="tableplateTukangLuar">
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
    var table = $('#tablepartTukangLuar').DataTable({
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

    var table = $('#tableaccTukangLuar').DataTable({
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

    var table = $('#tableplateTukangLuar').DataTable({
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

    Fancybox.bind('[data-fancybox="tukangluar"]', {
        dragToClose: false,

        Toolbar: false,
        closeButton: "top",

        Image: {
            zoom: false,
            Panzoom: {
                zoomFriction: 0.7,
                maxScale: function () {
                    return 5;
                },
            },
        },

        on: {
            initCarousel: (fancybox) => {
            const slide = fancybox.Carousel.slides[fancybox.Carousel.page];

            fancybox.$container.style.setProperty(
                "--bg-image",
                `url("${slide.$thumb.src}")`
            );
            },
            "Carousel.change": (fancybox, carousel, to, from) => {
            const slide = carousel.slides[to];

            fancybox.$container.style.setProperty(
                "--bg-image",
                `url("${slide.$thumb.src}")`
            );
            },
        },
    });
</script>
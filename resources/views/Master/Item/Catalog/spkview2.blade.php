{{-- Tab Gambar, Batu, Informasi --}}
<div class="row">
    <div class="col-xl-9 col-md-12 col-sm-12 col-xs-12">
    </div>
    <div class="col-xl-9 col-md-12 col-sm-12 col-xs-12">
        <div class="input-group input-group-merge float-end">
            <input type="number" class="form-control" placeholder="221002803" autofocus="" id="cari" value="{{$SWSPK}}" onchange="CariSPK()">
            <button class="btn btn-outline-primary" onclick="CariSPK()">Cari</button>
        </div>
    </div>
</div>
<hr class="mt-0">
{{-- End Tab Gambar, Batu, Informasi --}}
{{-- Keterangan --}}
<p>SPK PPIC : {{$spkPPIC}} <br>Kadar : {{$kadar}} <br>Keterangan : {{$spkNote}} </p>

<div class="row">
    @foreach ($data as $item)
    <div class="col-xxl-3 col-xl-3 col-md-4 col-sm-4 col-xs-4">
        <a href="{{Session::get('hostfoto')}}/image/{{$item->Photo}}.jpg" data-fancybox="spkviewimage{{$loop->iteration}}"
            data-caption='<p class="card-text" style=" margin-bottom: 0px;">
                {{$item->Carat}} | {{$item->Weight}}Gr | {{$item->Remarks}} | Qty:{{$item->Qty}} @if (count($item->batu) != 0) | Batu : @foreach ($item->batu as $batu){{$batu->SW}}({{$batu->QTY}}) &nbsp; @endforeach @endif @if (count($item->part) != 0) | Part : @foreach ($item->part as $part) {{$part->SW}}({{$part->Qty}})  @endforeach @endif
            </p>'
        >
        <div class="card mb-2">
            <img style="width: 100%; height: 100%; object-fit: contain;"  src="{{Session::get('hostfoto')}}/image/{{$item->Photo}}.jpg" onerror="this.onerror=null; this.src='{!! asset('assets/images/no-photos.jpg') !!}'">
            <div class="card-body spkItemCard" style="padding:5px;">
                <h5 class="card-title" style="font-size: 12px; margin-bottom: 5px;">{{$item->SW}}</h5>
                <p class="card-text" style=" margin-bottom: 0px;">
                    {{$item->Carat}} | {{$item->Weight}}Gr | {{$item->Remarks}} | Qty:{{$item->Qty}}
                </p>
                @if (count($item->batu) != 0)
                <table width="100%" style=" text-align: center; padding-top: 0; padding-bottom: 0;" class="table-bordered">
                    <thead>
                        <tr>
                            <th style="padding: 0px;" colspan="2">Batu</th>
                        </tr>
                        <tr>
                            <th style="padding: 0px;">Nama</th>
                            <th style="padding: 0px;">Qty</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach ($item->batu as $batu)
                        <tr>
                            <td style="padding: 0px;">{{$batu->SW}}</td>
                            <td style="padding: 0px;">{{$batu->QTY}}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                @endif
                @if (count($item->part) != 0)
                <table width="100%" style=" text-align: center; padding-top: 0; padding-bottom: 0;" class="table-bordered">
                    <thead>
                        <tr>
                            <th style="padding: 0px;" colspan="2">Part</th>
                        </tr>
                        <tr>
                            <th style="padding: 0px;">Nama</th>
                            <th style="padding: 0px;">Qty</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach ($item->part as $part)
                        <tr>
                            <td style="padding: 0px;">{{$part->SW}}</td>
                            <td style="padding: 0px;">{{$part->Qty}}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                @endif
            </div>
        </div>
        </a>
        <a href="{{Session::get('hostfoto')}}/image/{{$item->Photo}}-1.jpg" data-fancybox="spkviewimage{{$loop->iteration}}" class="ImageSlide" id="ImageSlide_{{$loop->iteration}}_1"></a>
        <a href="{{Session::get('hostfoto')}}/image/{{$item->Photo}}-2.jpg" data-fancybox="spkviewimage{{$loop->iteration}}" class="ImageSlide" id="ImageSlide_{{$loop->iteration}}_2"></a>
        <a href="{{Session::get('hostfoto')}}/image/{{$item->Photo}}-3.jpg" data-fancybox="spkviewimage{{$loop->iteration}}" class="ImageSlide" id="ImageSlide_{{$loop->iteration}}_3"></a>
        <a href="{{Session::get('hostfoto')}}/image/{{$item->Photo}}-4.jpg" data-fancybox="spkviewimage{{$loop->iteration}}" class="ImageSlide" id="ImageSlide_{{$loop->iteration}}_4"></a>
        <a href="{{Session::get('hostfoto')}}/image/{{$item->Photo}}-5.jpg" data-fancybox="spkviewimage{{$loop->iteration}}" class="ImageSlide" id="ImageSlide_{{$loop->iteration}}_5"></a>
        <a href="{{Session::get('hostfoto')}}/image/{{$item->Photo}}-6.jpg" data-fancybox="spkviewimage{{$loop->iteration}}" class="ImageSlide" id="ImageSlide_{{$loop->iteration}}_6"></a>
    </div>
    @endforeach
</div>

<script>
    $(document).ready(function() {
        let imageSlideList = $('.ImageSlide')
        imageSlideList.each(function(i, obj) {
            let ImageURL = $(obj).attr('href')
            let ImageID = $(obj).attr('id')
            
            $.ajax({
                type: "GET",
                url: ImageURL,
                dataType: 'jsonp',
                cors: true ,
                contentType:'application/json',
                secure: true,
                headers: {
                    'Access-Control-Allow-Origin': '*',
                },
                success: function(data) {
                //    console.log("oke");
                   return
                },
                error: function(xhr, textStatus, errorThrown){
                    $('#'+ImageID).remove();
                    return;
                }
            })
        });
    })

    @foreach ($data as $item)
    Fancybox.bind('[data-fancybox="spkviewimage{{$loop->iteration}}"]', {
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
    @endforeach
</script>
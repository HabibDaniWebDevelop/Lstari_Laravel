<div class="row">
    <div class="col-xxl-12 col-xl-12 col-md-12 col-sm-12 col-xs-12" style="margin-right: auto; margin-left: auto;">
        <div class="swiper text-center" id="CarouselLilin">
            <div class="swiper-wrapper">
                <div class="swiper-slide" id="CarouselLilinItem_1"><a
                        href="{{Session::get('hostfoto')}}{{$lihatfoto->f1}}" data-lightbox="image-Lilin"><img
                            src="{{Session::get('hostfoto')}}{{$lihatfoto->f1}}" style="width: 400px; height: 600px; "
                            onerror="this.onerror=null; this.src='{!! asset('assets/images/no-photos.jpg') !!}'"></a>

                </div>
                <div class="swiper-slide" id="CarouselLilinItem_2"><a
                        href="{{Session::get('hostfoto')}}{{$lihatfoto->f2}}" data-lightbox="image-Lilin"><img
                            src="{{Session::get('hostfoto')}}{{$lihatfoto->f2}}" style="width: 400px; height: 600px; "
                            onerror="this.onerror=null; this.src='{!! asset('assets/images/no-photos.jpg') !!}'"></a>
                </div>
                <div class="swiper-slide" id="CarouselLilinItem_3"><a
                        href="{{Session::get('hostfoto')}}{{$lihatfoto->f3}}" data-lightbox="image-Lilin"><img
                            src="{{Session::get('hostfoto')}}{{$lihatfoto->f3}}" style="width: 400px; height: 600px; "
                            onerror="this.onerror=null; this.src='{!! asset('assets/images/no-photos.jpg') !!}'"></a>
                </div>
                <div class="swiper-slide" id="CarouselLilinItem_4"><a
                        href="{{Session::get('hostfoto')}}{{$lihatfoto->f4}}" data-lightbox="image-Lilin"><img
                            src="{{Session::get('hostfoto')}}{{$lihatfoto->f4}}" style="width: 400px; height: 600px; "
                            onerror="this.onerror=null; this.src='{!! asset('assets/images/no-photos.jpg') !!}'"></a>
                </div>
            </div>
            <div class="swiper-button-next-Lilin"></div>
            <div class="swiper-button-prev-Lilin"></div>
            <div class="swiper-pagination-Lilin d-none" id="CarouselPaginationLilin"></div>
        </div>
    </div>
    <div class="col-12 text-center">
        <h2>{{$lihatfoto->SW}}</h2>
        <h5>{{$lihatfoto->Description}}</h5>
    </div>
</div>

<script>
var swiperLilin = new Swiper("#CarouselLilin", {
    lazy: true,
    observer: true,
    observeParents: true,
    navigation: {
        nextEl: ".swiper-button-next-Lilin",
        prevEl: ".swiper-button-prev-Lilin",
    },
    pagination: {
        el: ".swiper-pagination-Lilin",
        clickable: true,
    },
});
</script>
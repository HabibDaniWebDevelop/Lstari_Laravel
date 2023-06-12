<div class="demo-inline-spacing mb-4">
    <div class="row">
        <div class="col-md-6 dropdown">
            <h5> LEMARI</h5>
            <select class="from-select btn btn-primary center col-12 px-auto" onchange="ClickLaci()" id="lemari"
                name="lemari">
                <option value="" selected disabled>
                    <--- Pilih Lemari --->
                </option>
                @foreach ($lemari as $lemaris)
                <option class="text-center bg-light text-dark border-none" value="{{ $lemaris->ID }}">
                    {{ $lemaris->SW }}</option>
                @endforeach
            </select>
        </div>

        <div class="col-md-6 dropdown">
            <h5> LACI</h5>
            <select class="btn btn-primary col-12 mx-auto" onchange="ClickLaci()" id="laci" name="laci">
                <option value="" selected disabled>
                    <--- Pilih Laci --->
                </option>
                @foreach ($laci as $lacis)
                <option class="text-center bg-light text-dark border-0" value="{{ $lacis->ID }}">
                    {{ $lacis->SW }}</option>
                @endforeach
            </select>
        </div>

    </div>
    <!-- <div class="row">
        <div class="col-md-12">
            <hr class="m-0" />
            <div class="row">
                <div class="col-md-12">
                    <marquee scrolldelay="100" class="btn-danger rounded-3 disabled mt-2" style="font-weight:700">Color
                        Orange Karet tidak ada di laci</marquee>
                </div>
            </div>
        </div>
    </div> -->
</div>
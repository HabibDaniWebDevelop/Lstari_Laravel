<div class="mb-2 row">
    <label class="col-md-2 col-form-label">Material Name</label>
    <div class="col-md-10">
        <input class="form-control" type="text" readonly value="{{ $datas[0]->Description }}" />
        <input type="hidden" id="ID" value="{{ $datas[0]->ID }}" />
    </div>
</div>

<div class="mb-2 row">
    <label class="col-md-2 col-form-label">Material Type</label>
    <div class="col-md-10">
        <select class="form-select" id="Type" name="Type">
            <option value="">Pilih</option>
            <option value="Chemical Solid">Chemical Solid</option>
            <option value="Chemical Liquid">Chemical Liquid</option>
            <option value="Media">Media</option>
            <option value="Tools">Tools</option>
        </select>
    </div>
</div>

<div class="mb-2 row">
    <label class="col-md-2 col-form-label">Description</label>
    <div class="col-md-10">
        <textarea class="form-control" id="Remarks" name="Remarks" rows="5">{{ $datas[0]->Remarks }}</textarea>
    </div>
</div>

<div class="mb-2 row">
    <label class="col-md-2 col-form-label">Area</label>
    <div class="col-md-10">
        <select class="form-control my-select2" id="department2" name="department2[]" multiple data-style="border">
            @foreach ($departments as $department)
                <option {{ $department->PID }} value="{{ $department->ID }}">{{ $department->Description }}
                </option>
            @endforeach
        </select>
    </div>
</div>

<div class="mb-2 row">
    <div class="col-6 pe-1">
        <div id="k1" class="bg-white border text-center" style="height: 205px; display: none;"></div>
        <div id="l1">
            <input type="file" accept="image/jpeg" id="Image1" class="dropify" data-height="130"
                data-default-file="{{ Session::get('hostfoto') }}/rnd2/BahanPembantu/{{ $datas[0]->Image1 }}" />
        </div>

    </div>
    <div class="col-6 ps-1">
        <div id="k2" class="bg-white border text-center" style="height: 205px; display: none;"></div>
        <div id="l2">
            <input type="file" accept="image/jpeg" id="Image2" class="dropify" data-height="130"
                data-default-file="{{ Session::get('hostfoto') }}/rnd2/BahanPembantu/{{ $datas[0]->Image2 }}" />
        </div>
    </div>
</div>

<div class="mb-2 row">
    <div class="col-6 pe-1">
        <div id="k3" class="bg-white border text-center" style="height: 205px; display: none;"></div>
        <div id="l3">
            <input type="file" accept="image/jpeg" id="Image3" class="dropify" data-height="130"
                data-default-file="{{ Session::get('hostfoto') }}/rnd2/BahanPembantu/{{ $datas[0]->Image3 }}" />
        </div>
    </div>
    <div class="col-6 ps-1">
        <div id="k4" class="bg-white border text-center" style="height: 205px; display: none;"></div>
        <div id="l4">
            <input type="file" accept="image/jpeg" id="Image4" class="dropify" data-height="130"
                data-default-file="{{ Session::get('hostfoto') }}/rnd2/BahanPembantu/{{ $datas[0]->Image4 }}" />
        </div>
    </div>
</div>

<div class="mb-2 row">
    <div class="col-6 pe-1">
        <div id="k5" class="bg-white border text-center" style="height: 205px; display: none;"></div>
        <div id="l5">
            <input type="file" accept="image/jpeg" id="Image5" class="dropify" data-height="130"
                data-default-file="{{ Session::get('hostfoto') }}/rnd2/BahanPembantu/{{ $datas[0]->Image5 }}" />
        </div>
    </div>
    <div class="col-6 ps-1">
        <div id="k6" class="bg-white border text-center" style="height: 205px; display: none;"></div>
        <div id="l6">
            <input type="file" accept="image/jpeg" id="Image6" class="dropify" data-height="130"
                data-default-file="{{ Session::get('hostfoto') }}/rnd2/BahanPembantu/{{ $datas[0]->Image6 }}" />
        </div>
    </div>
</div>

<div class="mb-2 row">
    <div class="col-12">
        <div id="k7" class="bg-white border text-center" style="height: 205px; display: none;"></div>
        <div id="l7">
            <input type="file" accept="image/jpeg" id="TechnicalImage" class="dropify" data-height="130"
                data-default-file="{{ Session::get('hostfoto') }}/rnd2/BahanPembantu/{{ $datas[0]->TechnicalImage }}" />
        </div>
    </div>
</div>

<div class="row text-center">
    <div class="col-md-12 text-center" style="display: flex; align-items: center; justify-content: center;">
        {{-- <div class="mb-2 text-center" id="my_camera"></div> --}}
    </div>
    <div class="col-6">
        {{-- <select class="form-select" id="lokgambar" name="lokgambar">
            <option value="1">Image1</option>
            <option value="2">Image2</option>
            <option value="3">Image3</option>
            <option value="4">Image4</option>
            <option value="5">Image5</option>
            <option value="6">Image6</option>
            <option value="7">TechnicalImage</option>
        </select> --}}
    </div>
    <div class="col-6">
        {{-- <input type=button class="btn btn-primary" value="Take Snapshot" align='center' onclick="take_snapshot()"> --}}
    </div>

    <input type="hidden" id="gambar_k1">
    <input type="hidden" id="gambar_k2">
    <input type="hidden" id="gambar_k3">
    <input type="hidden" id="gambar_k4">
    <input type="hidden" id="gambar_k5">
    <input type="hidden" id="gambar_k6">
    <input type="hidden" id="gambar_k7">

</div>

<div class="mt-4" align='center'>
    <button type="button" class="btn btn-danger me-4" id="Batal1" onclick="Klik_Batal1()"> <span
            class="fas fa-times-circle"></span>&nbsp; Batal</button>
    <button type="button" class="btn btn-primary" id="simpan2" value=""
        onclick="KlikSimpan2()">Save</button>

</div>



<script>
    $(document).ready(function() {
        $('.my-select2').selectpicker();
        var Type = "{{ $datas[0]->Type }}";
        $('#Type').val(Type);

        // //  ------------------------webcam

        // Webcam.set({
        //     facingMode: "environment",
        //     height: 200,
        //     width: 300,
        //     dest_width: 1920,
        //     dest_height: 1080,
        //     crop_width: 1080,
        //     crop_height: 1080,
        //     image_format: 'jpeg',
        //     jpeg_quality: 90,
        //     flip_horiz:true,
        // });

        // Webcam.attach('#my_camera');

        // ------------------------dropify 
        // Basic
        $('.dropify').dropify();

        // Used events
        var drEvent = $('#input-file-events').dropify();

        drEvent.on('dropify.beforeClear', function(event, element) {
            return confirm("Do you really want to delete \"" + element.file.name + "\" ?");
        });

        drEvent.on('dropify.afterClear', function(event, element) {
            alert('File deleted');
        });

        drEvent.on('dropify.errors', function(event, element) {
            console.log('Has Errors');
        });

        var drDestroy = $('#input-file-to-destroy').dropify();
        drDestroy = drDestroy.data('dropify')
        $('#toggleDropify').on('click', function(e) {
            e.preventDefault();
            if (drDestroy.isDropified()) {
                drDestroy.destroy();
            } else {
                drDestroy.init();
            }
        })
    });

    function take_snapshot() {
        Webcam.snap(function(data_uri) {

            id = $("#lokgambar").val();

            //baca lebar layar
            var screensize = document.documentElement.clientWidth;
            width = screensize / 2 - 20;

            // console.log(id, screensize, width);

            var image = new Image();
            image.src = data_uri;
            $(image).on('load', function() {
                var imgwidth = image.width;
                var imgheight = image.height;
                var aspectRatio = imgwidth / imgheight;
                console.log(imgwidth, imgheight, aspectRatio);
            });

            $(image).css({
                'max-width': width + 'px',
                'max-height': '200px'
            });



            $('#k' + id).html(image);
            $("#gambar_k" + id).val(data_uri);
            $('#k' + id).show();
            $('#l' + id).hide();
        });
    }
</script>

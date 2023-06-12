<?php
header ("Expires: ".gmdate("D, d M Y H:i:s", time())." GMT");  
header ("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");  
header ("Cache-Control: no-cache, must-revalidate");  
header ("Pragma: no-cache");
?>

<table width='100%'>
    <tr class="h5">
        <td colspan="3" align="center"><b>Material Pictures</b></td>
        <td align="center"><b>Technical Picture</b></td>
    </tr>
    <tr>
        <td><input type="file" accept="image/jpeg" id="Image1" class="dropify" data-height="300"
                data-default-file="{{ Session::get('hostfoto') }}/rnd2/BahanPembantu/{{ $datas[0]->Image1 }}" /></td>
        <td><input type="file" accept="image/jpeg" id="Image2" class="dropify" data-height="300"
                data-default-file="{{ Session::get('hostfoto') }}/rnd2/BahanPembantu/{{ $datas[0]->Image2 }}" /></td>
        <td><input type="file" accept="image/jpeg" id="Image3" class="dropify" data-height="300"
                data-default-file="{{ Session::get('hostfoto') }}/rnd2/BahanPembantu/{{ $datas[0]->Image3 }}" /></td>
        <td rowspan="2" style="width: 35%;"> <input type="file" accept="image/jpeg" id="TechnicalImage"
                class="dropify" data-height="616"
                data-default-file="{{ Session::get('hostfoto') }}/rnd2/BahanPembantu/{{ $datas[0]->TechnicalImage }}" />
        </td>
    </tr>
    <tr>

        <td><input type="file" accept="image/jpeg" id="Image4" class="dropify" data-height="300"
                data-default-file="{{ Session::get('hostfoto') }}/rnd2/BahanPembantu/{{ $datas[0]->Image4 }}" /></td>
        <td><input type="file" accept="image/jpeg" id="Image5" class="dropify" data-height="300"
                data-default-file="{{ Session::get('hostfoto') }}/rnd2/BahanPembantu/{{ $datas[0]->Image5 }}" /></td>
        <td><input type="file" accept="image/jpeg" id="Image6" class="dropify" data-height="300"
                data-default-file="{{ Session::get('hostfoto') }}/rnd2/BahanPembantu/{{ $datas[0]->Image6 }}" /></td>
    </tr>
</table>

<input type="hidden" id="idnee" name="idnee" value="{{ $datas[0]->ID }}" />

<script>
    $(document).ready(function() {
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
</script>

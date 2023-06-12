<br>
<div class="table-responsive text-nowrap" style="height:calc(100vh - 410px);">
    <table class="table table-border table-hover table-sm" id="tabel2">
        <thead class="table-secondary sticky-top zindex-2">
            <tr style="text-align: center">
                <th>Kode Produk</th>
                <th>Deskripsi</th>
                <th>Kadar</th>
                <th>File STL</th>
                <th>Qty Request</th>
                <th>Qty OK</th>
                <th>Qty Defect</th>
                <th>No SPKO</th>
               
            </tr>
        </thead>
        <tbody>
            @foreach ($nthko as $dataOK)
            <tr class="klik" id="{{ $dataOK->ID }}" style="text-align: center">
                <td><span style="font-size: 14px" class="badge bg-dark" >{{$dataOK->codes}}</span><br>{{$dataOK->SKU}}</td>
                <td>{{$dataOK->DD}}</td>
                <td>{{$dataOK->Description}}</td>
                <td><input type="file" id="stl" name="stl[]" accept="application/stl"></td>
                <td><input class="form-control" type="number" id="qtyspko" name="qtyspko[]" value="{{$dataOK->Qty }}" readonly></td>
                <td><input class="form-control" type="number" id="qtygood" name="qtygood[]" value=""></td>
                <td><input class="form-control" type="number" id="qtynogood" name="qtynogood[]" value=""></td>
                <td>{{$dataOK->SPKO }}
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
{{-- </form> --}}
</div>
<script>
    // $(".klik").on('click', function(e) {
    //     // $('.klik').css('background-color', 'white');
    //     var id = $(this).attr('id');
    //     if ($(this).hasClass('table-secondary')) {
    //         $(this).removeClass('table-secondary');
    //         $('#cek_' + id).attr('checked', false);
    //     } else {
    //         $(this).addClass('table-secondary');
    //         $('#cek_' + id).attr('checked', true);
    //     }
    //     return false;
    // });
</script>

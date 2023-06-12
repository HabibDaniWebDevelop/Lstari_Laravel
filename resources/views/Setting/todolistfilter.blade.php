@forelse ($datas as $data)
<?php $status = $data->status;
    
    if ($data->updatestatus != '') {
        $updatestatus = date('d-m-y', strtotime($data->updatestatus));
    } else {
        $updatestatus = '';
    } 
    if ($data->TargetDate != '') {
    $TargetDate = date('d-m-y', strtotime($data->TargetDate));
    } else {
    $TargetDate = '';
    }?>

<tr class="klik" id="{{ $data->id }}" id2="{{ $data->todo }}" id3="{{ $data->name }}">
    </td>
    <td>{{ $loop->iteration }} </td>
    <td> {{ $data->name }} </td>
    <td class="todocreator">{{ $data->todocreator }}</td>
    <td>{{ $data->todo }}</td>
    <td>{{ $data->remarks }}</td>
    <td align="center"><span class="badge {!! $status == 'A'
            ? 'bg-secondary'
            : ($status == 'P'
                ? 'bg-warning'
                : ($status == 'T'
                    ? 'bg-danger'
                    : 'bg-success')) !!}">{{ $data->status }}</span></td>
    <td align="center">{{ $data->Priority }}</td>
    <td>{{ date('d-m-y', strtotime($data->tododate)) }}</td>
    <td>{!! $TargetDate !!}</td>
    <td class="tglupd">{!! $updatestatus !!}</td>
</tr>
@empty
{{-- <tr>
    <td colspan="5">
        <div class="alert alert-danger">
            Data Blog belum Tersedia.
        </div>
    </td>
</tr> --}}
@endforelse

<script>
    // -------------------- menu klik --------------------
    $(".klik").on('click', function(e) {
        $('.klik').css('background-color', 'white');

        if ($("#menuklik").css('display') == 'block') {
            $(" #menuklik ").hide();
        } else {
            var top = e.pageY + 15;
            var left = e.pageX - 100;
            var id = $(this).attr('id');
            var id2 = $(this).attr('id2');
            var id3 = $(this).attr('id3');
            var status = $(this).children("td").children("span").text();
            var todocreator = $(this).children('.todocreator').text();

            // alert(id +'|'+ id2+'|'+ id3+'|'+status+'|'+ todocreator);

            var nama = $('#nama').val();
            $(".judulklik").html(id2);
            $(".judulklik").attr('id', id);
            $("#tomboledit").addClass('d-none');
            // $("tomboledit").attr('id', id);

            if ((nama.toLowerCase() == id3.toLowerCase()) && (status != 'S')) {
                $(this).css('background-color', '#f4f5f7');
                if ((nama.toLowerCase() == todocreator.toLowerCase()) && status == 'A') {
                    $("#tomboledit").removeClass('d-none');
                }

                $("#menuklik").css({
                    display: "block",
                    top: top,
                    left: left,
                    width: '250px'
                });
            } else if (status == 'S') {
                Swal.fire({
                    icon: 'warning',
                    title: 'Oops...',
                    text: 'Todo yang Status Selesai tidak dapat dirubah lagi!'
                });
            }
        }
        return false;

    });

</script>
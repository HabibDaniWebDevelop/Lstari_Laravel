<table class="table table-border table-hover table-sm" id="tabel1">
    <thead class="table-secondary sticky-top zindex-2">
        <tr>
            <th>No.</th>
            <th>Tanggal</th>
            <th>todo</th>
            <th>remarks</th>
        </tr>
    </thead>
    <tbody>

        {{-- {{ dd($data) }} --}}
        @forelse ($todo1 as $todo1s)
            @if($todo1s->name == 'Rissa' || $todo1s->name == 'Ferri')
                <tr class="klik" id="{{ $todo1s->id }}" id2="{{ $todo1s->todo }}" status="{{ $todo1s->status }}">
                    <td>{{ $loop->iteration }}.</td>
                    <td>{{ date('d-m-y', strtotime($todo1s->tododate)) }}</td>
                    <td >{{ $todo1s->todo }}</td>
                @if($todo1s->todocreator == 'ERP')
                    <td><button type="button" class="btn btn-primary" id="btnOrder{{ $loop->iteration }}" onclick="getLink({{ $loop->iteration }})"><i class="fa fa-shopping-cart" aria-hidden="true"></i>&nbsp; Order </button>
                        <input type="hidden" id="links{{ $loop->iteration }}" value="{{ $todo1s->remarks }}">
                    </td>
                @else 
                    <td>{{ $todo1s->remarks }}</td> 
                @endif
                </tr>
            @else  
                <tr class="klik" id="{{ $todo1s->id }}" id2="{{ $todo1s->todo }}" status="{{ $todo1s->status }}">
                    <td>{{ $loop->iteration }}.</td>
                    <td>{{ date('d-m-y', strtotime($todo1s->tododate)) }}</td>
                    <td>{{ $todo1s->todo }}</td>
                    <td>{{ $todo1s->remarks }}</td>
                </tr>
            @endif
        @empty
            {{-- <div class="alert alert-danger">
            Antrian ToDoList Kosong.
        </div> --}}
        @endforelse

    </tbody>
</table>

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
            var status = $(this).attr('status');

            // alert(id +'|'+ id2+'|'+ '|'+status);

            $(".judulklik").html(id2);
            $(".judulklik").attr('id', id);

            if ((status != 'S')) {
                $(this).css('background-color', '#f4f5f7');
                $("#menuklik").css({
                    display: "block",
                    top: top,
                    left: left,
                    width: '250px'
                });
            } else if (status == 'S') {
                // Swal.fire({
                //     icon: 'warning',
                //     title: 'Oops...',
                //     text: 'Todo yang Status Selesai tidak dapat dirubah lagi!'
                // });
            }
        }
        return false;

    });

    //sembunyikan menu kilk
    $("body").on("click", function() {
        if ($("#menuklik").css('display') == 'block') {
            $(" #menuklik ").hide();
        }
        $('.klik').css('background-color', 'white');
    });

     function getLink(row){
        var liked = $("#links"+row).val();
         window.open(liked,'_blank');
     };
</script>

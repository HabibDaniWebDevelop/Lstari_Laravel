<div class="table-responsive text-nowrap" style="height:calc(100vh - 438px);">
    <table class="table table-border table-hover table-sm" id="tabel1">
        <thead class="table-secondary sticky-top zindex-2">
            <tr style="text-align: center">
                <th>No.</th>
                <th>No. Form PCB</th>
                <th>Tanggal</th>
                <th>Total Tipe</th>
                <th>Total Produk</th>
            </tr>
        </thead>
        <tbody style="text-align: center">

            {{-- {{ dd($data) }} --}}
            @forelse ($datas as $data1)
                <tr class="klik2" id="{{ $data1->ID }}" id2="{{ $data1->SW }}">
                    <td>{{ ($datas->currentPage() - 1) * $datas->perPage() + $loop->iteration }}</td>
                    <td> <span class="badge bg-dark" style="font-size:14px;">{{ $data1->SW }}</span> </td>
                    <td>{{ $data1->TransDate }}</td>
                    <td>{{ $data1->TotalType }}</td>
                    <td>{{ $data1->TotalQty }}</td>
                </tr>
            @empty
                <div class="alert alert-danger">
                    Data Blog belum Tersedia.
                </div>
            @endforelse
        </tbody>
    </table>
</div>
{{ $datas->links('pagination::bootstrap-4') }}

<script>
    // ----------------------- fungsi klik kanan sub menu -----------------------
    $(".klik2").on('click', function(e) {
        var id = $(this).attr('id');
        var id2 = $(this).attr('id2');
        var top = e.pageY + 15;
        var left = e.pageX - 100;
        
        $("#judulklik2").html(id2);
        $('#klikcetak').attr('onclick', 'Klik_Cetak1(' + id + ')');
        $("#menuklik2").css({
        display: "block",
        top: top,
        left: left
        });
        
        return false;
    });

    $(document).bind("click", function(e) {
        return false;
    });

    $("body").on("click", function() {
        if ($("#menuklik2").css('display') == 'block') {
            $(" #menuklik2 ").hide();
        }
    });

    $("#menuklik2 a").on("click", function() {
        $(this).parent().hide();
    });

</script>

{{-- ? ---------------- script untuk Sorting Datatabel ---------------- --}}
<script>
    $(document).ready(function() {

        var tabelCount = $('.table').length;

        for (let i = 1; i <=
            $('.table').length; i++) {
            // alert('#tabel' + i); 
            var table = $('#tabel' + i).DataTable();
            table.columns().iterator('column', function(ctx, idx) {
                $(table.column(idx).header()).append('<span class = "sort-icon" / > ');
            });
        }
    });
</script>

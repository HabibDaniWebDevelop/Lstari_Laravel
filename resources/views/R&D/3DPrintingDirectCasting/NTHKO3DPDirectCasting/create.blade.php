<br>
<form id="form1">
<div class="table-responsive text-nowrap" style="height:calc(100vh - 410px);">
    <table class="table table-border table-hover table-sm" id="tabel1">
        <thead class="table-secondary sticky-top zindex-2">
            <tr style="text-align: center">
                <th>No.</th>
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
            @foreach ($datas as $dataOK)
            <tr class="klik" id="{{ $dataOK->ID }}" style="text-align: center">
                <td>{{$loop->iteration}}</td>
                <td><span style="font-size: 14px" class="badge bg-dark" >{{$dataOK->codes}}</span><br>{{$dataOK->SKU}}</td>
                <td>{{$dataOK->mname}}</td>
                <td>{{$dataOK->Description}}</td>
                <td><input type="file" id="stl{{ $loop->iteration }}" name="stl[]" accept="application/stl"></td>
                <td><input class="Qtyspk1 form-control" type="number" id="qtyspko{{ $loop->iteration }}" name="qtyspko[]" value="{{$dataOK->Qty }}" readonly></td>
                <td><input class="Qtygood1 form-control" type="number" id="qtygood{{ $loop->iteration }}" name="qtygood[]" value=""></td>
                <td><input class="Qtynogood1 form-control" type="number" id="qtynogood{{ $loop->iteration }}" name="qtynogood[]" value=""></td>
                <td>{{$dataOK->SPKO }}
                <input class="Idm1" type="hidden" id="idm{{ $loop->iteration }}" name="idm[]" value="{{$dataOK->idm }}">
                <input class="Ord1" type="hidden" id="ord{{ $loop->iteration }}" name="ord[]" value="{{$dataOK->ord }}">
                <input class="Product1" type="hidden" id="product{{ $loop->iteration }}" name="product[]" value="{{$dataOK->Product }}">
                <input class="Sku1" type="hidden" id="sku{{ $loop->iteration }}" name="sku[]" value="{{$dataOK->SKU }}">
                <input class="Worklist1" type="hidden" id="worklist{{ $loop->iteration }}" name="worklist[]" value="{{$dataOK->ID }}">
            </td>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
</form>
<script>
// var collapsedGroups = {};
var table = $('#tabel1').DataTable({
    "paging": false,
    "ordering": true,
    "info": false,
    "searching": true,
    "autoWidth": true,
    "responsive": true,
    // rowGroup: {
    //     // Uses the 'row group' plugin
    //     dataSrc: 36,
    //     startRender: function(rows, group) {
    //         //console.log(group);
    //         var collapsed = !!collapsedGroups[group];
    //         rows.nodes().each(function(r) {
    //             r.style.display = collapsed ? '' : 'none';
    //         });
    //         // Add category name to the <tr>. NOTE: Hardcoded colspan
    //         return $('<tr/>')
    //             .append('<td colspan="9"><b>Produk : </b>' + group + ' (' + rows.count() +
    //                 ')</td>')
    //             .attr('data-name', group)
    //             .toggleClass('collapsed', collapsed);
    //     }
    // }
});
// $('#tampiltabel tbody').on('click', 'tr.dtrg-group', function() {
//     //console.log('ikkk');
//     var name = $(this).data('name');
//     collapsedGroups[name] = !collapsedGroups[name];
//     table.draw(false);
// });

// -------------------- klik di tabel --------------------
// $(".klik").on('click', function(e) {
//     var id = $(this).attr('id');
//     alert(id);
// });
</script>
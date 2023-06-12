<div class="row">
    <div class="col-md-12">
        <div class="card" style="height:calc(100vh - 295px);">
            <div class="card-body">
                <div class="demo-inline-spacing mb-4">


                    <button type="button" class="btn btn-primary " id="Baru1" onclick="Klik_Baru1()"> <span
                            class="tf-icons bx bx-plus-circle"></span>&nbsp; Baru </button>
                    <button type="button" class="btn btn-primary me-4" id="Ubah1" disabled onclick="Klik_Ubah1()">
                        <span class="tf-icons bx bx-edit"></span>&nbsp; Ubah</button>
                    <button type="button" class="btn btn-danger" id="Batal1" disabled onclick="Klik_Batal1()"> <span
                            class="fas fa-times-circle"></span>&nbsp; Batal</button>

                    <button type="button" class="btn btn-warning" id="Simpan1" disabled onclick="Klik_Simpan1()">
                        <span class="tf-icons bx bx-save"></span>&nbsp; Simpan</button>
                    <button type="button" class="btn btn-dark me-4" id="Posting1" disabled onclick="Klik_Posting1()">
                        <span class="tf-icons bx bx-send"></span>&nbsp; Posting</button>
                    <button type="button" class="btn btn-info" id="Cetak1" disabled onclick="Klik_Cetak1()"> <span
                            class="tf-icons bx bx-printer"></span>&nbsp; Cetak</button>

                    <div class="float-end">
                        <div class="input-group input-group-merge">
                            <span class="input-group-text"><i class="bx bx-search"
                                    onclick="klikViewSelection()"></i></span>
                            <input type="text" class="form-control" placeholder="Search..." autofocus id='cari'
                                list="carilist" onchange="ChangeCari()" />
                        </div>
                        <datalist id="carilist">
                            @foreach ($carilists as $carilist)
                            <option value="{{ $carilist->SW }}">{{ $carilist->SW }}</option>
                            @endforeach

                        </datalist>
                    </div>
                    <hr class="m-0" />

                </div>
                <form id="form1">
                    <div id="tampil" class="d-none">

                    </div>
                </form>
            </div>
        </div>

        @include('setting.publick_function.ViewSelectionModal')

    </div>

</div>

<script>
function Klik_Baru1() {
    $('#Baru1').prop('disabled', true);
    $('#Batal1').prop('disabled', false);
    $('#Simpan1').prop('disabled', false);
    $('#Cetak1').prop('disabled', true);
    $("#tampil").removeClass('d-none');

    $.get('/forms_basic/isi/2/0', function(data) {
        $("#tampil").html(data);
    });
}

function Klik_Ubah1() {
    $('#Baru1').prop('disabled', true);
    $('#Ubah1').prop('disabled', true);
    $('#Batal1').prop('disabled', false);
    $('#Simpan1').prop('disabled', false);

    var id = $('#cari').val();
    $.get('/forms_basic/isi/3/' + id, function(data) {
        $("#tampil").html(data);
    });
}

function Klik_Batal1() {
    // location.reload();
    forms_basic();
}

function Klik_Simpan1() {
    $('#Batal1').prop('disabled', true);
    $('#Simpan1').prop('disabled', true);
    $('#Posting1').prop('disabled', false);
    $('#Cetak1').prop('disabled', false);

    var formData = $('#form1').serialize();
    // alert(formData);

    Swal.fire({
        icon: 'success',
        title: 'success!',
        text: 'Data Berhasil Tersimpan'
    });
}

function Klik_Posting1() {
    $('#Simpan1').prop('disabled', true);
    $('#Posting1').prop('disabled', true);
    $('#Baru1').prop('disabled', false);

    Swal.fire({
        icon: 'success',
        title: 'success!',
        text: 'Data Berhasil Posting'
    });

    ChangeCari();
}

function ChangeCari() {
    $('#Ubah1').prop('disabled', false);
    $('#Cetak1').prop('disabled', false);
    $('#Batal1').prop('disabled', true);
    $('#Simpan1').prop('disabled', true);
    $('#Posting1').prop('disabled', true);
    $("#tampil").removeClass('d-none');

    var id = $('#cari').val();
    $.get('/forms_basic/isi/1/' + id, function(data) {
        $("#tampil").html(data);
    });
}

function klikViewSelection() {
    $("#jodulmodalVS").html('Menu filter View Selection');
    $('#modalformatVS').attr('class', 'modal-dialog modal-fullscreen');
    $.get('/ViewSelection?id=&tb=workallocation', function(data) {
        $("#modalVS").html(data);
        $('#modalinfoVS').modal('show');
    });
}
// // ----------------------- fungsi Tambah Baris dan pindah fokus input -----------------------

function add() {
    let rowCount = $('#tabel1 tr').length;

    // Setup table row
    let trStart = '<tr class="baris" id="' + rowCount + '">';
    let cell1 =
        '<td class="m-0 p-0"> <input type="text" class="form-control form-control-sm fs-6 w-100" name="no[]" readonly value="' +
        rowCount + '" data-index="' + rowCount + '1"> </td>';
    let cell2 =
        '<td class="m-0 p-0"> <input type="text" class="form-control form-control-sm fs-6 w-100" name="id[]" value="" data-index="' +
        rowCount + '2"> </td>';
    let cell3 =
        '<td class="m-0 p-0"> <input type="text" class="form-control form-control-sm fs-6 w-100" name="des[]" value="" data-index="' +
        rowCount + '3"> </td>';
    let cell4 =
        '<td class="m-0 p-0"> <input type="date" class="form-control form-control-sm fs-6 w-100" name="tgl[]" value="" data-index="' +
        rowCount + '4"> </td>';
    let cell5 =
        '<td class="m-0 p-0"> <input type="text" class="form-control form-control-sm fs-6 w-100" name="ket[]" value="" data-index="' +
        rowCount + '5" posisi-index="akhir"> </td> <input type="hidden" name="id[]" value="" >';
    let trEnd = '</tr>';
    let finalItem = "";
    let rowitem = finalItem.concat(trStart, cell1, cell2, cell3, cell4, cell5, trEnd);
    $("#tabel1 > tbody").append(rowitem);

    $posisi = "#tabel1 #" + rowCount + " input";
    $($posisi).on('contextmenu', function(e) {
        rightclik(this, e);
    });

    $($posisi).keydown(function(e) {
        var id = $(this).parent().parent().attr('id');
        // alert(id);
        tambahbaris(id);
    });

}

function klikhapus(id) {
    // var id = $(this).attr('id');
    $("#" + id).remove();

    $("#tabel1 tr").each((i, elem) => {
        Index = i + 1;
        if (Index < id) {
            newIndex = i + 1;
        } else {
            newIndex = i;
        }
        // alert(Index +' '+ newIndex)
        $('[data-index="' + Index + '1"]').attr('value', newIndex);
        $('[data-index="' + Index + '1"]').parent().parent().attr('id', newIndex);
        $('[data-index="' + Index + '1"]').attr('data-index', newIndex + '1');
        $('[data-index="' + Index + '2"]').attr('data-index', newIndex + '2');
        $('[data-index="' + Index + '3"]').attr('data-index', newIndex + '3');
        $('[data-index="' + Index + '4"]').attr('data-index', newIndex + '4');
        $('[data-index="' + Index + '5"]').attr('data-index', newIndex + '5');

        $(elem).find('.satuan').attr('id', "satuan_" + newIndex);

    })
}
</script>
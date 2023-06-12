<div class="row">
    <div class="col-md-12">
        <div class="card mb-4">
            <h5 class="card-header">Form Input</h5>
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
                        <table class="table table-borderless table-sm" id="tabel1">
                            <thead class="table-secondary">
                                <tr style="text-align: center">
                                    <th width='6%'> NO </th>
                                    <th> ID </th>
                                    <th width='30%'> Description </th>
                                    <th> TGL TRANS </th>
                                    <th width='30%'> Keterangan </th>
                                </tr>
                            </thead>

                            <tbody>

                                <tr class="baris" id="1" class="klik3">
                                    <td class="m-0 p-0">
                                        <input type="text" class="form-control form-control-sm fs-6 w-100" name="no[]"
                                            value="1" data-index="11">
                                    </td>
                                    <td class="m-0 p-0"> <input type="text"
                                            class="form-control form-control-sm fs-6 w-100" name="id[]" value="rgrtee"
                                            data-index="12">
                                    </td>
                                    <td class="m-0 p-0"> <input type="text"
                                            class="form-control form-control-sm fs-6 w-100" name="des[]" value="rgrtee"
                                            data-index="13">
                                    </td>
                                    <td class="m-0 p-0"> <input type="date"
                                            class="form-control form-control-sm fs-6 w-100" name="tgl[]" value=""
                                            data-index="14">
                                    </td>
                                    <td class="m-0 p-0"> <input type="text"
                                            class="form-control form-control-sm fs-6 w-100" name="ket[]" value="rgrtee"
                                            data-index="15" posisi-index="akhir">
                                    </td>
                                </tr>
                                <tr class="baris" id="2">
                                    <td class="m-0 p-0">
                                        <input type="text" class="form-control form-control-sm fs-6 w-100" name="no[]"
                                            value="2" data-index="21">
                                    </td>
                                    <td class="m-0 p-0"> <input type="text"
                                            class="form-control form-control-sm fs-6 w-100" name="id[]" value="rgrtee"
                                            data-index="22">
                                    </td>
                                    <td class="m-0 p-0"> <input type="text"
                                            class="form-control form-control-sm fs-6 w-100" name="des[]" value="rgrtee"
                                            data-index="23">
                                    </td>
                                    <td class="m-0 p-0"> <input type="date"
                                            class="form-control form-control-sm fs-6 w-100" name="tgl[]" value=""
                                            data-index="24">
                                    </td>
                                    <td class="m-0 p-0"> <input type="text"
                                            class="form-control form-control-sm fs-6 w-100" name="ket[]" value="rgrtee"
                                            data-index="25" posisi-index="akhir">
                                    </td>
                                </tr>

                            </tbody>

                        </table>

                    </div>
                </form>
            </div>
        </div>

        @include('Setting.publick_function.ViewSelectionModal')

    </div>




</div>

<script>
function Klik_Baru1() {
    $('#Baru1').prop('disabled', true);
    $('#Batal1').prop('disabled', false);
    $('#Simpan1').prop('disabled', false);
    $('#Cetak1').prop('disabled', true);
    $("#tampil").removeClass('d-none');
}

function Klik_Ubah1() {
    $('#Baru1').prop('disabled', true);
    $('#Ubah1').prop('disabled', true);
    $('#Batal1').prop('disabled', false);
    $('#Simpan1').prop('disabled', false);
}

function Klik_Batal1() {
    location.reload();
}

function Klik_Simpan1() {
    $('#Batal1').prop('disabled', true);
    $('#Simpan1').prop('disabled', true);
    $('#Posting1').prop('disabled', false);
    $('#Cetak1').prop('disabled', false);

    var formData = $('#form1').serialize();
    // alert(formData);

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    var type = "PUT";
    var ajaxurl = '/forms_basic/' + '11';

    $.ajax({
        type: type,
        url: ajaxurl,
        data: formData,
        dataType: 'json',
        success: function(data) {
            alert('tes_s');

        }
    });

}

function Klik_Posting1() {
    $('#Simpan1').prop('disabled', true);
    $('#Posting1').prop('disabled', true);
    $('#Baru1').prop('disabled', false);
}

function ChangeCari() {
    $('#Ubah1').prop('disabled', false);
    $('#Cetak1').prop('disabled', false);
    $('#Batal1').prop('disabled', true);
    $('#Simpan1').prop('disabled', true);
    $('#Posting1').prop('disabled', true);
    $("#tampil").removeClass('d-none');
}

function klikViewSelection() {
    $("#jodulmodalVS").html('Menu filter View Selection');
    $('#modalformatVS').attr('class', 'modal-dialog modal-fullscreen');
    $.get('/ViewSelection?id=&tb=workallocation', function(data) {
        $("#modalVS").html(data);
        $('#modalinfoVS').modal('show');
    });
}
// ----------------------- fungsi Tambah Baris dan pindah input -----------------------

$('.baris').keydown(function(e) {
    var id = $(this).attr('id');
    tambahbaris(id);
});

function tambahbaris(id) {
    var id = parseFloat(id);
    if (event.keyCode === 13 || event.keyCode === 9) {
        var $this = $(event.target);
        var index = parseFloat($this.attr('data-index'));
        var pos_index = $this.attr('posisi-index');
        // alert(index + ' | ' + id + ' | ' + pos_index);

        if (pos_index == 'akhir') {
            posisi = id + 1;
            var table = document.getElementById("tabel1");
            rowCount = table.rows.length;
            // add();
            if (posisi == rowCount) {
                add();
            }
            $('[data-index="' + (id + 1).toString() + '2"]').focus();
        } else {
            $('[data-index="' + (index + 1).toString() + '"]').focus();
        }
    }

    if (event.keyCode === 40) {
        var table = document.getElementById("tabel1");
        rowCount = table.rows.length - 1;
        // alert(rowCount + ' ' + id);
        if (id == rowCount) {
            add();
            $('[data-index="' + (id + 1).toString() + '2"]').focus();
        }
    }
}

// ----------------------- fungsi klik kanan sub menu -----------------------
$(".baris input").on('contextmenu', function(e) {
    rightclik(this, e);
});

$(document).bind("contextmenu", function(e) {
    return false;
});

$("body").on("click", function() {
    if ($("#menuklik").css('display') == 'block') {
        $(" #menuklik ").hide();
    }
});

$("#menuklik a").on("click", function() {
    $(this).parent().hide();
});

function rightclik(ids, event) {

    var id = $(ids).parent().parent().attr('id');
    var top = event.pageY + 15;
    var left = event.pageX - 100;

    $("#judulklik").html(id);
    $('#klikhapus').attr('onclick', 'klikhapus(' + id + ')');
    $("#menuklik").css({
        display: "block",
        top: top,
        left: left
    });

    return false;
}

function add() {
    let rowCount = $('#tabel1 tr').length;

    // Setup table row
    let trStart = '<tr class="baris" id="' + rowCount + '">';
    let cell1 =
        '<td class="m-0 p-0"> <input type="number" readonly class = "form-control form-control-sm fs-6 w-100 text-center" name = "no[]" id="no" value = "' +
        rowCount + '" data-index = "' + rowCount + '1" posisi-index = "awal" > </td>';
    let cell2 =
        '<td class="m-0 p-0"> <input type="number" class="form-control form-control-sm fs-6 w-100 text-center" name="karet" id="karet" value="" data-index="' +
        rowCount + '2"> </td>';
    let cell3 =
        '<td class="m-0 p-0"> <input type="text" class="form-control form-control-sm fs-6 w-100 text-center" name="kodeproduk" id="kodeproduk" value="" data-index="' +
        rowCount + '3"> </td>';
    let cell4 =
        '<td class="m-0 p-0"> <input type="text" class="form-control form-control-sm fs-6 w-100 text-center" name="deskripsi" id="deskripsi" value="" data-index="' +
        rowCount + '4"> </td>';
    let cell5 =
        '<td class="m-0 p-0"> <input type="text" class="form-control form-control-sm fs-6 w-100 text-center" name="lokasilemari" id="lokasilemari" value="" data-index="' +
        rowCount + '5" posisi-index="akhir"> </td>';
    let trEnd = '</tr>';
    let finalItem = "";
    let rowitem = finalItem.concat(trStart, cell1, cell2, cell3, cell4, cell5, trEnd);
    $("#tabel1 > tbody").append(rowitem); //on enter + baris

    // on enter
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
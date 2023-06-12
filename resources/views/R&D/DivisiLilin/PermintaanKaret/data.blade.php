<div class="row">
    <div class="col-md-12">
        <div class="card mb-4">
            <h5 class="card-header">Form Input</h5>
            <div class="card-body">
                <div class="demo-inline-spacing mb-4">


                    <button type="button" class="btn btn-primary me-4" id="Baru1" onclick="Klik_Baru1()"> <span
                            class="tf-icons bx bx-plus-circle"></span>&nbsp; Baru </button>
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
                            <span class="input-group-text"><i class="bx bx-search" onclick="klikViewSelection()"></i></span>
                            <input type="text" class="form-control" placeholder="Search..." autofocus id='cari' list="carilist"
                                onchange="ChangeCari()"/>
                        </div>
                        





                    </div>
                    <hr class="m-0" />

                </div>

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
                            <tr class="baris" id="1" idtr="1">
                                <td class="m-0 p-0">
                                    <input type="text" class="form-control form-control-sm fs-6 w-100" name="N_1"
                                        value="1" data-index="11" posisi-index="awal">
                                </td>
                                <td class="m-0 p-0"> <input type="text"
                                        class="form-control form-control-sm fs-6 w-100" name="N_1" value="rgrtee"
                                        data-index="12">
                                </td>
                                <td class="m-0 p-0"> <input type="text"
                                        class="form-control form-control-sm fs-6 w-100" name="N_1" value="rgrtee"
                                        data-index="13">
                                </td>
                                <td class="m-0 p-0"> <input type="date"
                                        class="form-control form-control-sm fs-6 w-100" name="N_1" value=""
                                        data-index="14">
                                </td>
                                <td class="m-0 p-0"> <input type="text"
                                        class="form-control form-control-sm fs-6 w-100" name="N_1" value="rgrtee"
                                        data-index="15" posisi-index="akhir">
                                </td>
                            </tr>
                            <tr class="baris" id="2" idtr="2">
                                <td class="m-0 p-0">
                                    <input type="text" class="form-control form-control-sm fs-6 w-100" name="N_1"
                                        value="2" data-index="21" posisi-index="awal">
                                </td>
                                <td class="m-0 p-0"> <input type="text"
                                        class="form-control form-control-sm fs-6 w-100" name="N_1" value="rgrtee"
                                        data-index="22">
                                </td>
                                <td class="m-0 p-0"> <input type="text"
                                        class="form-control form-control-sm fs-6 w-100" name="N_1" value="rgrtee"
                                        data-index="23">
                                </td>
                                <td class="m-0 p-0"> <input type="date"
                                        class="form-control form-control-sm fs-6 w-100" name="N_1" value=""
                                        data-index="24">
                                </td>
                                <td class="m-0 p-0"> <input type="text"
                                        class="form-control form-control-sm fs-6 w-100" name="N_1" value="rgrtee"
                                        data-index="25" posisi-index="akhir">
                                </td>
                            </tr>

                        </tbody>
                    </table>

                </div>
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

    function tambaris(idtr) {
        var idtr = parseFloat(idtr);
        if (event.keyCode === 13) {
            var $this = $(event.target);
            var index = parseFloat($this.attr('data-index'));
            var pos_index = $this.attr('posisi-index');
            // alert(index +' | '+idtr+' | '+ pos_index);

            if (pos_index == 'akhir') {
                posisi = idtr + 1;
                // alert(posisi);
                var table = document.getElementById("tabel1");
                rowCount = table.rows.length;
                if (posisi == rowCount) {
                    add();
                }
                $('[data-index="' + (idtr + 1).toString() + '2"]').focus();
            } else {
                $('[data-index="' + (index + 1).toString() + '"]').focus();
            }
        }

        if (event.keyCode === 40) {
            var table = document.getElementById("tabel1");
            rowCount = table.rows.length - 1;
            alert(rowCount + ' ' + idtr);
            if (idtr == rowCount) {
                add();
                $('[data-index="' + (idtr + 1).toString() + '2"]').focus();
            }
        }
    }

    function add() {

        var table = document.getElementById("tabel1");
        rowCount = table.rows.length;
        var limit = $("#isi").val();

        // alert(rowCount);

        var row = table.insertRow();
        row.className = 'baris';
        row.id = rowCount;
        row.idtr = rowCount;
        var cell0 = row.insertCell(0);
        var cell1 = row.insertCell(1);
        var cell2 = row.insertCell(2);
        var cell3 = row.insertCell(3);
        var cell4 = row.insertCell(4);

        cell0.className = 'm-0 p-0';
        cell1.className = 'm-0 p-0';
        cell2.className = 'm-0 p-0';
        cell3.className = 'm-0 p-0';
        cell4.className = 'm-0 p-0';

        cell0.innerHTML = '<input type="text" class="form-control form-control-sm fs-6 w-100" name="N_1" value="' +
            rowCount + '" data-index="' + rowCount + '1" >';
        cell1.innerHTML =
            '<input type="text" class="form-control form-control-sm fs-6 w-100" name="N_1" value="qqq" data-index="' +
            rowCount + '2" >';
        cell2.innerHTML =
            '<input type="text" class="form-control form-control-sm fs-6 w-100" name="N_1" value="www" data-index="' +
            rowCount + '3" >';
        cell3.innerHTML =
            '<input type="date" class="form-control form-control-sm fs-6 w-100" name="N_1" value="" data-index="' +
            rowCount + '4" >';
        cell4.innerHTML =
            '<input type="text" class="form-control form-control-sm fs-6 w-100" name="N_1" value="eee" data-index="' +
            rowCount + '5" posisi-index="akhir" >';

        var hrefs = document.getElementsByClassName("baris");
        hrefs.item(rowCount - 1).addEventListener("keydown", function(e) {
            // e.preventDefault(); 
            $(this).attr('idtr');
            var $this = $(event.target);
            var idtr = $(this).attr('idtr');
            // alert(idtr);
            // tambaris(rowCount);
            // return false;
        });

    }

    $('.baris').keydown(function(e) {
        $(this).attr('idtr');
        var $this = $(event.target);
        var idtr = $(this).attr('idtr');
        tambaris(idtr);
        return false;
    });
</script>

 <!-- modal -->    
 <div class="modal fade" id="basicModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog" role="document">
           <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="jodulmodal1">Form Tambah Data Karet</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="formmodal1">
                        <div id="modal1">
                            <div class="row">
                                <div class="col-6">
                                    <div class="form-group">
                                        <label class="form-label">ID Karet</label>
                                        <input type="number" class="form-control" onchange="caribarkode(this.value,1)" id="IdKaret" name="karet[]">
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label class="form-label">Kode Produk</label>
                                        <input type="text" class="form-control" id="KodeProduksi">
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-6">
                                    <div class="form-group">
                                        <label class="form-label">Deskripsi</label>
                                        <input type="text" class="form-control" id="Deskripsi">
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                    <label class="form-label">Pilih Lemari</label>
                                        <div class="btn-group" role="group" aria-label="Basic example">
                                            <button type="button" class="btn btn-primary col-12" data-bs-target="#exLargeModal" data-bs-toggle="modal">Pilih lemari</button>
                                            <input type="text" name="SerialNo" class="form-control" required="">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                                                            
                    </form>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" id="simpan1" value="Tambah" onclick="KlikSimpan()">Save</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Extra Large Modal -->
    <div class="modal fade" id="exLargeModal" tabindex="-1" aria-hidden="true">
          <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel4">Pilih lemari penyimpanan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body">
                <div class="row g-2">
                  <div class="col mb-0">
                    <label for="pilihlemari" class="form-label">Silahkan pilih lemari</label>
                    <div class="dropdown">
                        <a class="btn btn-primary col-12 dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-bs-toggle="dropdown" aria-expanded="false">
                        Silahkan Pilih Lemari
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                            <li><a class="dropdown-item" href="#">A01</a></li>
                            <li><a class="dropdown-item" href="#">A02</a></li>
                            <li><a class="dropdown-item" href="#">A03</a></li>
                            <li><a class="dropdown-item" href="#">A04</a></li>
                            <li><a class="dropdown-item" href="#">A05</a></li>
                            <li><a class="dropdown-item" href="#">A06</a></li>
                            <li><a class="dropdown-item" href="#">A07</a></li>
                            <li><a class="dropdown-item" href="#">A08</a></li>
                            <li><a class="dropdown-item" href="#">A09</a></li>
                            <li><a class="dropdown-item" href="#">A10</a></li>
                            <li><a class="dropdown-item" href="#">C01</a></li>
                            <li><a class="dropdown-item" href="#">C02</a></li>
                            <li><a class="dropdown-item" href="#">C03</a></li>
                            <li><a class="dropdown-item" href="#">C04</a></li>
                            <li><a class="dropdown-item" href="#">C05</a></li>
                            <li><a class="dropdown-item" href="#">L01</a></li>
                        </ul>
                    </div>
                </div>
                  <div class="col mb-0">
                    <label for="dobExLarge" class="form-label">silahkan pilih laci</label>
                    <div class="dropdown">
                        <a class="btn btn-primary col-12 dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-bs-toggle="dropdown" aria-expanded="false">
                        Silahkan Pilih Laci
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                            <li><a class="dropdown-item" href="#">01</a></li>
                            <li><a class="dropdown-item" href="#">02</a></li>
                            <li><a class="dropdown-item" href="#">03</a></li>
                            <li><a class="dropdown-item" href="#">04</a></li>
                            <li><a class="dropdown-item" href="#">05</a></li>
                            <li><a class="dropdown-item" href="#">06</a></li>
                            <li><a class="dropdown-item" href="#">07</a></li>
                            <li><a class="dropdown-item" href="#">08</a></li>
                            <li><a class="dropdown-item" href="#">09</a></li>
                            <li><a class="dropdown-item" href="#">10</a></li>
                            <li><a class="dropdown-item" href="#">11</a></li>
                            <li><a class="dropdown-item" href="#">12</a></li>
                            <li><a class="dropdown-item" href="#">13</a></li>
                        </ul>
                    </div>
                  </div>
                </div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                  Close
                </button>
                <button type="button" class="btn btn-primary">Save changes</button>
              </div>
            </div>
          </div>
        </div>
<!-- akhir modal -->
<?php $title = 'Bahan Pembantu'; ?>
@extends('layouts.backend-Theme-3.app')
@section('title', '$title')

@section('Dashboard')
    <h2 class="m-0">{{ $title }}</h2>
    <ol class="breadcrumb sm-2">
        <li class="breadcrumb-item"><a href="/">Home </a></li>
        <li class="breadcrumb-item">Inventori </li>
        <li class="breadcrumb-item">Material Request </li>
        <li class="breadcrumb-item active">Bahan Pembantu </li>
    </ol>
@endsection

@section('css')

    <style>

    </style>
    {{-- Bootstrap Select --}}
    <link rel="stylesheet" href="{!! asset('assets/sneatV1/assets/vendor/libs/BootstrapSelect/bootstrap-select.min.css') !!}">

@endsection

@section('container')
    <div class="row">
        <div class="col-md-12">
            <div class="card mb-4">

                @include('Inventori.MaterialRequest.BahanPembantu.data')
                
            </div>
        </div>
    </div>
    <div class="dropdown-menu dropdown-menu-end animate" id="menuklik" style="display:none">
        <div class="text-center fw-bold mb-2" id="judulklik"></div>
        <a class="dropdown-item" id="removeRow" ><span class="">-</span>&nbsp; Remove new</a>
    </div>
@endsection

@section('script')
    {{-- Bootstrap Select --}}
    <script src="{!! asset('assets/sneatV1/assets/vendor/libs/BootstrapSelect/bootstrap-select.min.js') !!}"></script>

    <script>
        // Data Table Settings
        $('#tabel1').DataTable({
            "paging": false,
            "lengthChange": false,
            "searching": false,
            "ordering": false,
            "info": false,
            "autoWidth": true,
            "responsive": true,
            "fixedColumns": true,
        });

        // Function for klik baru
        function KlikBaru() {
            // Getting idMaterialRequest from hidden input
            let idMaterialRequest = $('#idMaterialRequest').val()
            if (idMaterialRequest != "") {
                // If idMaterialRequest have value. It will reload th page
                window.location.reload()
                return;
            }
            // Empty row in tbody
            $("#tabel1  tbody").empty()


            // Get Row
            $.ajax({
                url: '/Inventori/MaterialRequest/BahanPembantu/generateRow',
                type: 'GET',
                data:{index:1},
                dataType: "json",
                beforeSend: function () {
                    $(".preloader").show();  
                },
                complete: function () {
                    $(".preloader").fadeOut(); 
                },
                success: function(data) {
                    $("#tabel1 > tbody").append(data.data);
                    $('#barangStock_1').selectpicker();
                }
            });

            // Setup table row
            // let number = $('#tabel1 tr').length;
            // let trStart = "<tr class='klik' id='Row_"+number+"'>"
            // let trEnd = "</tr>"
            // let no = '<td class="m-0 p-0"><input type="text" class="form-control form-control-sm fs-6 w-100 text-center nomor" name="no" readonly value="'+number+'"></td>'
            // let qty = '<td class="m-0 p-0"><input type="number" oncontextmenu="klikme('+number+',event)" min="1" class="form-control form-control-sm fs-6 w-100 text-center qty" name="qty" id="qty_'+number+'" value=""></td>'
            // let satuan = '<td class="m-0 p-0"> <input type="text" oncontextmenu="klikme('+number+',event)" class="form-control form-control-sm fs-6 w-100 text-center satuan" name="satuan" id="satuan_'+number+'" value=""> </td>'
            // let namaBarang = '<td class="m-0 p-0"> <input type="text" oncontextmenu="klikme('+number+',event)" onKeyPress="nextRowEvent('+number+',event)" class="form-control form-control-sm fs-6 w-100 text-center namaBarang" name="namaBarang" id="namaBarang_'+number+'" value=""> </td>'
            // let finalItem = ""
            // let rowitem = finalItem.concat(trStart, no, qty, satuan, namaBarang, trEnd)
            
            // add row to tbody
            // $("#tabel1 > tbody").append(rowitem);
            
            // Disable button "Baru"
            $("#btn_baru").prop('disabled',true)
            // Enable Button "Batal dan Simpan"
            $("#btn_simpan").prop('disabled',false)
            $("#btn_batal").prop('disabled',false)
            // Enable input
            $("#tanggal").prop('disabled',false)
            $("#recipient").prop('disabled',false)
            $("#alamat").prop('disabled',false)
            $("#toemployee").prop('disabled',false)
        }

        function somethingFun() {
            let barangStocks = $('select .inputBarangStock')
            let units = $('.unit')
            for (let index = 0; index < barangStocks.length; index++) {
                var barang = $(barangStocks[index]).val()
                var unit = $(unit[index]).val()
                console.log('barang = '+barang);
                console.log('unit = '+unit);
            }
        }

        function enableBootstrapSelect() {
            $('.barangStock').removeAttr("disabled")
            $('.barangStock').selectpicker('destroy')
            $('.barangStock').selectpicker()
        }

        function disableBoostrapSelect() {
            $('.barangStock').attr("disabled",true)
            $('.barangStock').selectpicker('destroy')
            $('.barangStock').selectpicker()
        }

        function getDetailBarangStock(value, index) {
            // Get value of select
            let barangStock = $('#barangStock_'+index).val()

            // Get data from that value then set Unit and etc.
            $.ajax({
                url: '/Inventori/MaterialRequest/BahanPembantu/getBarang',
                type: 'GET',
                data:{idBarang:barangStock},
                dataType: "json",
                beforeSend: function () {
                    $(".preloader").show();  
                },
                complete: function () {
                    $(".preloader").fadeOut(); 
                },
                success: function(data) {
                    if (data.data.length != 0) {
                        let dataBarang = data.data[0]
                        $('#unit_'+index).val(dataBarang.UID).change()
                        $('#keperluan_'+index).val("Rutin").change()
                        $('#kategori_'+index).val("Biasa").change()
                        $('#ulang_'+index).val('N').change()
                        return
                    }
                    return
                }
            });
        }

        // Function for createNewRow
        function newRow() {
            // Setup table row
            let number = $('#tabel1 tr').length;
            $.ajax({
                url: '/Inventori/MaterialRequest/BahanPembantu/generateRow',
                type: 'GET',
                data:{index:number},
                dataType: "json",
                beforeSend: function () {
                    $(".preloader").show();  
                },
                complete: function () {
                    $(".preloader").fadeOut(); 
                },
                success: function(data) {
                    $("#tabel1 > tbody").append(data.data);
                    $('#barangStock_'+number).selectpicker();
                    $('#barangStock_'+number).focus()
                }
            });
        }

        // Function if btn_batal clicked
        function klikBatal() {
            window.location.reload();
        }

        function KlikSimpan() {
            let action = $('#action').val()
            if (action == 'simpan'){
                SimpanMRBahanPembantu();
            } else if (action == 'ubah'){
                UbahSuratPengantar();
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: "Action must be simpan or ubah",
                })
                return;
            }
        }

        // Function for saving surat Pengantar
        function SimpanMRBahanPembantu() {
            // Gather all required data
            let idEmployee = $('#idEmployee').text()
            let idDepartment = $('#idDepartment').text()
            let tanggal = $('#tanggal').val()
            let catatan = $('#catatan').val()

            if (idEmployee == "") {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: "idEmployee cant be blank",
                })
                return;
            }

            if (idDepartment == "") {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: "idDepartment cant be blank",
                })
                return;
            }

            if (tanggal == "") {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: "tanggal cant be blank",
                })
                return;
            }

            // Get tbody length for get items name
            let rowLength = $('#tabel1 tbody tr').length;
            
            // Final data items
            let data_items = []
            
            for (let length = 0; length < rowLength; length++) {
                index = length+1;
                let barangStock = $('#barangStock_'+index).val()
                let barangNonStock = $('#barangNonStock_'+index).val()
                let jumlah = $('#jumlah_'+index).val()
                let unit = $('#unit_'+index).val()
                let proses = $('#proses_'+index).val()
                let keperluan = $('#keperluan_'+index).val()
                let kategori = $('#kategori_'+index).val()
                let ulang = $('#ulang_'+index).val()
                let keterangan = $('#keterangan_'+index).val()

                // Checking for barangStock or barangNonStock in this row is not empty
                if (barangStock != 0 || barangNonStock != "" ) {
                    if (jumlah <= 0) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: "Kolom Jumlah Wajib di Isi Jika Anda Memilih Barang",
                        })
                        return;
                    }
                    // If barangStock is not empty, it will send data that contain barangStock to server.
                    if (barangStock != 0) {
                        let itemStock = {
                            barang:barangStock,
                            jumlah:jumlah,
                            unit:unit,
                            proses:proses,
                            keperluan:keperluan,
                            kategori:kategori,
                            ulang:ulang,
                            keterangan:keterangan,
                            jenisBarang:1
                        }
                        data_items.push(itemStock)
                    } else {
                        let itemNonStock = {
                            barang:barangNonStock,
                            jumlah:jumlah,
                            unit:unit,
                            proses:proses,
                            keperluan:keperluan,
                            kategori:kategori,
                            ulang:ulang,
                            keterangan:keterangan,
                            jenisBarang:2
                        }
                        data_items.push(itemNonStock)
                    }
                }
            }
            let data = {
                idEmployee:idEmployee,
                idDepartment:idDepartment,
                tanggal:tanggal,
                catatan:catatan,
                items:data_items
            }
            // console.log(data);
            // return

            //!  ------------------------    Send Request to Server     ------------------------ !!
            // Setup CSRF Token
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            // Hit backend
            $.ajax({
                type: "POST",
                url: "/Inventori/MaterialRequest/BahanPembantu",
                data: data,
                dataType: 'json',
                beforeSend: function () {
                    $(".preloader").show();  
                },
                complete: function () {
                    $(".preloader").fadeOut(); 
                },
                success: function (data) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Tersimpan!',
                        text: "Data Berhasil Tersimpan.",
                        timer: 2000,
                        showCancelButton: false,
                        showConfirmButton: false
                    });

                    // if create success
                    // Disable button Batal
                    $('#btn_batal').prop('disabled',true)
                    // Disable button simpan
                    $('#btn_simpan').prop('disabled',true)
                    // Activate button cetak
                    $('#btn_cetak').prop('disabled',false)
                    // Activate button baru
                    $('#btn_baru').prop('disabled',false)
                    // Activate button Ubah
                    $('#btn_ubah').prop('disabled',false)
                    // Set input hidden idMaterialRequest
                    $('#idMaterialRequest').val(data.data.ID)
                    // Set input cari
                    $('#cari').val(data.data.ID)

                    // disable tanggal
                    $('#tanggal').prop('disabled',true)
                    // disable catatan
                    $('#catatan').prop('disabled',true)
                    // disable barangStock
                    disableBoostrapSelect()
                    // diable barangNonStock
                    $('.barangNonStock').attr("disabled",true);
                    // disable jumlah
                    $('.jumlah').prop('disabled',true)
                    // disable unit 
                    $('.unit').prop('disabled',true)
                    // disable proses
                    $('.proses').prop('disabled',true)
                    // disable keperluan
                    $('.keperluan').prop('disabled',true)
                    // disable kategori
                    $('.kategori').prop('disabled',true)
                    // disable ulang
                    $('.ulang').prop('disabled',true)
                    // disable keterangan
                    $('.keterangan').prop('disabled',true)
                    // set removeAction
                    $('#removeAction').val('false')
                    // set action to ubah
                    $('#action').val('ubah')
                },
                error: function(xhr, textStatus, errorThrown){
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: xhr.responseJSON.message
                    })

                    return;
                }
            })
        }

        // KlikCetak
        function klikCetak() {
            // Get no.referensi (SW)
            let no_referensi = $('#no_referensi').val()
            // Check if value is blank
            if (no_referensi !== ''){
                // Return print page
                window.open('/Lain-lain/Korespondensi/SuratPengantar/cetak/'+no_referensi, '_blank');
            }else{
                // Return aleart when value is blank
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Surat Pengantar belum terpilih',
                })
                return
            }
        }

        // Function for search surat Pengantar
        function Cari() {
            // Get SW from cari
            let cari = $('#cari').val()
            // Check if value is blank
            if (cari === ""){
                // return aleart when it blank
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: "Cari can't be blank",
                })
                return;
            }

            // Make request
            $.ajax({
                type: "GET",
                url: "/Lain-lain/Korespondensi/SuratPengantar/cari/"+cari,
                dataType: 'json',
                beforeSend: function () {
                    $(".preloader").show();  
                },
                complete: function () {
                    $(".preloader").fadeOut(); 
                },
                success: function(data) {
                    // Set Hidden SWSuratPengantar value
                    $('#SWSuratPengantar').val(data.data.sw)
                    
                    // Set tanggal
                    $('#tanggal').val(data.data.tanggal)
                    
                    // Set No.Referensi
                    $('#no_referensi').val(data.data.sw)
                    
                    // Set input cari
                    $('#cari').val(data.data.sw)

                    // recipient
                    $('#recipient').val(data.data.recipient)
                    
                    // alamat
                    $('#alamat').val(data.data.alamat)
                    
                    // Set toemployee
                    $("#toemployee").val(data.data.toemployee)

                    // Set button ubah to enable
                    $('#btn_ubah').prop('disabled',false)
                    // Set button cetak to enable
                    $('#btn_cetak').prop('disabled',false)
                    // set button batal to disable
                    $('#btn_batal').prop('disabled',true)
                    // set button simpan to disable
                    $('#btn_simpan').prop('disabled',true)
                    // set button baru to enable
                    $('#btn_baru').prop('disabled',false)                    
                    // Clear Table
                    $("#tabel1  tbody").empty()

                    // Loop data and add it to table
                    let items = data.data.items
                    items.forEach(function (value, i) {
                        // Check if value is null for not required data
                        let satuanValue = value.Satuan === null ?  '' : value.Satuan
                        
                        // Setup table row
                        let number = i+1
                        let trStart = "<tr class='klik' id='Row_"+number+"'>"
                        let trEnd = "</tr>"
                        let no = '<td class="m-0 p-0"><input type="text" class="form-control form-control-sm fs-6 w-100 text-center nomor" name="no" readonly value="'+number+'"></td>'
                        let qty = '<td class="m-0 p-0"><input type="number" oncontextmenu="klikme('+number+',event)" min="1" class="form-control form-control-sm fs-6 w-100 text-center qty" name="qty" id="qty_'+number+'" value="'+value.Qty+'"></td>'
                        let satuan = '<td class="m-0 p-0"> <input type="text" oncontextmenu="klikme('+number+',event)" class="form-control form-control-sm fs-6 w-100 text-center satuan" name="satuan" id="satuan_'+number+'" value="'+ satuanValue +'"> </td>'
                        let namaBarang = '<td class="m-0 p-0"> <input type="text" oncontextmenu="klikme('+number+',event)" onKeyPress="nextRowEvent('+number+',event)" class="form-control form-control-sm fs-6 w-100 text-center namaBarang" name="namaBarang" id="namaBarang_'+number+'" value="'+value.Item+'"> </td>'
                        let finalItem = ""
                        let rowitem = finalItem.concat(trStart, no, qty, satuan, namaBarang, trEnd)
                        $("#tabel1 > tbody").append(rowitem);
                    })

                    // disable tanggal
                    $('#tanggal').prop('disabled',true)
                    // disable recipient
                    $('#recipient').prop('disabled',true)
                    // disable alamat
                    $('#alamat').prop('disabled',true)
                    // diable toemployee
                    $('#toemployee').prop('disabled',true)
                    // disable qty
                    $('.qty').prop('disabled',true)
                    // disable satuan 
                    $('.satuan').prop('disabled',true)
                    // disable namaBarang
                    $('.namaBarang').prop('disabled',true)
                    // set removeAction
                    $('#removeAction').val('false')
                },
                error: function(xhr, textStatus, errorThrown){
                    let todayDate = new Date().toISOString().slice(0, 10);
                    // Clear Table
                    $("#tabel1  tbody").empty()

                    // set tanggal
                    $('#tanggal').val(todayDate)
                    $('#tanggal').prop('disabled',true)
                    
                    // disable recipient
                    $('#recipient').prop('disabled',true)
                    $('#recipient').val("")
                    // disable alamat
                    $('#alamat').prop('disabled',true)
                    $('#alamat').val("")
                    // diable toemployee
                    $('#toemployee').prop('disabled',true)
                    $('#toemployee').val("")
                    // set removeAction
                    $('#removeAction').val('false')
                    // Set No.Referensi
                    $('#no_referensi').val("")
                    // Set cari
                    $('#cari').val("")
                    // Set button
                    $('#btn_cetak').prop('disabled',true)
                    $('#btn_simpan').prop('disabled',true)
                    $('#btn_batal').prop('disabled',true)
                    $('#btn_ubah').prop('disabled',true)
                    $('#btn_baru').prop('disabled',false)
                    

                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: "Surat Pengantar Not Found"
                    })

                    return;
                }
            })
        }
    
        function KlikUbah() {
            // enable tanggal
            $('#tanggal').prop('disabled',false)
            // enable recipient
            $('#recipient').prop('disabled',false)
            // enable alamat
            $('#alamat').prop('disabled',false)
            // enable toemployee
            $('#toemployee').prop("disabled",false);
            // enable qty
            $('.qty').prop('disabled',false)
            // enable satuan 
            $('.satuan').prop('disabled',false)
            // enable namaBarang
            $('.namaBarang').prop('disabled',false)
            // set removeAction
            $('#removeAction').val('true')
            // set action to ubah
            $('#action').val('ubah')

            // disable button
            $('#btn_baru').prop('disabled',true)
            $('#btn_ubah').prop('disabled',true)
            $('#btn_cetak').prop('disabled',true)

            // enable button
            $('#btn_batal').prop('disabled',false)
            $('#btn_simpan').prop('disabled',false)
        }
        $('#cari').change(function(event) {
            Cari()
        })

        function nextRowEvent(index,event){
            let keyCode = event.keyCode || event.which;
            if (keyCode === 40 || keyCode === 13) {
                // count total row
                let number = $('#tabel1 tbody tr').length;
                if (number == index){
                    newRow();
                }else{
                    let nextrow = index+1
                    $('#barangStock_'+nextrow).focus()
                }

            }
        }

        function klikme(index,e) {
            $('.klik').css('background-color', 'white');

            // if ($("#menuklik").css('display') == 'block') {
            //     $(" #menuklik ").hide();
            // } else {
            var top = e.pageY + 15;
            var left = e.pageX - 100;
            window.idfiell = index;
            $("#judulklik").html(index);
            $('#removeRow').attr('onclick','removeRow2('+index+')')

            $(this).css('background-color', '#f4f5f7');
            $("#menuklik").css({
                display: "block",
                top: top,
                left: left
            });
            // }
            event.preventDefault();
            
            return false; //blocks default Webbrowser right click menu
        }

        // Function for dismiss
        $("body").on("click", function() {
            if ($("#menuklik").css('display') == 'block') {
                $(" #menuklik ").hide();
            }
            $('.klik').css('background-color', 'white');
        });

        function removeRow2(index) {

            // check if removeAction allowed
            let removeAction = $('#removeAction').val()
            if (removeAction === 'false'){
                return false;
            }

            // Get idMaterialRequest value from hidden input for checking
            let idMaterialRequest = $('#idMaterialRequest').val()
            // Check if idMaterialRequest have value
            if (idMaterialRequest == ""){
                // If idMaterialRequest didn't have value or blank it will just remove the row
                
                // Remove row 
                $("#Row_"+index).remove()

                // Getting Row length for checking row length
                let number = $('#tabel1 tbody tr').length;
                // check row length
                if (number == 0){
                    // If length of row is 0 it will reload the page
                    window.location.reload();
                } else {
                    // loop row
                    $("#tabel1 tbody tr").each((i, elem) => {
                        newIndex = i+1
                        // Set new row index
                        elem.id = "Row_"+newIndex
                        
                        // Set new barangStock properties
                        $(elem).find('.barangStock').attr('oncontextmenu',"klikme("+newIndex+",event)")
                        $(elem).find('.barangStock').attr('onchange',"getDetailBarangStock(this, "+newIndex+")")
                        $(elem).find('.barangStock').attr('id',"barangStock_"+newIndex)

                        // Set new barangNonStock properties
                        $(elem).find('.barangNonStock').attr('oncontextmenu',"klikme("+newIndex+",event)")
                        $(elem).find('.barangNonStock').attr('id',"barangNonStock_"+newIndex)

                        // Set new jumlah properties
                        $(elem).find('.jumlah').attr('oncontextmenu',"klikme("+newIndex+",event)")
                        $(elem).find('.jumlah').attr('id',"jumlah_"+newIndex)

                        // Set new unit properties
                        $(elem).find('.unit').attr('oncontextmenu',"klikme("+newIndex+",event)")
                        $(elem).find('.unit').attr('id',"unit_"+newIndex)

                        // Set new proses properties
                        $(elem).find('.proses').attr('oncontextmenu',"klikme("+newIndex+",event)")
                        $(elem).find('.proses').attr('id',"proses_"+newIndex)

                        // Set new keperluan properties
                        $(elem).find('.keperluan').attr('oncontextmenu',"klikme("+newIndex+",event)")
                        $(elem).find('.keperluan').attr('id',"keperluan_"+newIndex)

                        // Set new kategori properties
                        $(elem).find('.kategori').attr('oncontextmenu',"klikme("+newIndex+",event)")
                        $(elem).find('.kategori').attr('id',"kategori_"+newIndex)

                        // Set new ulang properties
                        $(elem).find('.ulang').attr('oncontextmenu',"klikme("+newIndex+",event)")
                        $(elem).find('.ulang').attr('id',"ulang_"+newIndex)

                        // Set new keterangan properties
                        $(elem).find('.keterangan').attr('oncontextmenu',"klikme("+newIndex+",event)")
                        $(elem).find('.keterangan').attr('onkeypress',"nextRowEvent("+newIndex+",event)")
                        $(elem).find('.keterangan').attr('id',"keterangan_"+newIndex)
                        
                        // Set Nomor
                        $(elem).find('.nomor').val(newIndex)

                    })
                }
            } else { // If SWSuratPengantar have value
                // get row length
                let number = $('#tabel1 tbody tr').length;
                if (number !== 1){
                    // If row length is more than 1 it will remove last row
                    $("#Row_"+index).remove()
                    // loop row
                    $("#tabel1 tbody tr").each((i, elem) => {
                        newIndex = i+1
                        // Set new row index
                        elem.id = "Row_"+newIndex
                        
                        // Set new barangStock properties
                        $(elem).find('.barangStock').attr('oncontextmenu',"klikme("+newIndex+",event)")
                        $(elem).find('.barangStock').attr('onchange',"getDetailBarangStock(this, "+newIndex+")")
                        $(elem).find('.barangStock').attr('id',"barangStock_"+newIndex)

                        // Set new barangNonStock properties
                        $(elem).find('.barangNonStock').attr('oncontextmenu',"klikme("+newIndex+",event)")
                        $(elem).find('.barangNonStock').attr('id',"barangNonStock_"+newIndex)

                        // Set new jumlah properties
                        $(elem).find('.jumlah').attr('oncontextmenu',"klikme("+newIndex+",event)")
                        $(elem).find('.jumlah').attr('id',"jumlah_"+newIndex)

                        // Set new unit properties
                        $(elem).find('.unit').attr('oncontextmenu',"klikme("+newIndex+",event)")
                        $(elem).find('.unit').attr('id',"unit_"+newIndex)

                        // Set new proses properties
                        $(elem).find('.proses').attr('oncontextmenu',"klikme("+newIndex+",event)")
                        $(elem).find('.proses').attr('id',"proses_"+newIndex)

                        // Set new keperluan properties
                        $(elem).find('.keperluan').attr('oncontextmenu',"klikme("+newIndex+",event)")
                        $(elem).find('.keperluan').attr('id',"keperluan_"+newIndex)

                        // Set new kategori properties
                        $(elem).find('.kategori').attr('oncontextmenu',"klikme("+newIndex+",event)")
                        $(elem).find('.kategori').attr('id',"kategori_"+newIndex)

                        // Set new ulang properties
                        $(elem).find('.ulang').attr('oncontextmenu',"klikme("+newIndex+",event)")
                        $(elem).find('.ulang').attr('id',"ulang_"+newIndex)

                        // Set new keterangan properties
                        $(elem).find('.keterangan').attr('oncontextmenu',"klikme("+newIndex+",event)")
                        $(elem).find('.keterangan').attr('onkeypress',"nextRowEvent("+newIndex+",event)")
                        $(elem).find('.keterangan').attr('id',"keterangan_"+newIndex)
                        
                        // Set Nomor
                        $(elem).find('.nomor').val(newIndex)

                    })
                } else{
                    // If row length is less than 1 or 0 it will show alert because that row is last item. it can't be deleted.
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: "This is last item in this transaction. You can't Delete it.",
                    })
                    return;
                }
            }
        }

        

    </script>
@endsection
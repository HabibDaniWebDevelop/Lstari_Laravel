<?php $title = 'Surat Pengantar'; ?>
@extends('layouts.backend-Theme-3.app')
@section('title', '$title')

@section('Dashboard')
    <h2 class="m-0">{{ $title }}</h2>
    <ol class="breadcrumb sm-2">
        <li class="breadcrumb-item"><a href="/">Home </a></li>
        <li class="breadcrumb-item">Lain-Lain </li>
        <li class="breadcrumb-item">Korespondensi </li>
        <li class="breadcrumb-item active">Surat Pengantar </li>
    </ol>
@endsection

@section('css')

    <style>

    </style>

@endsection

@section('container')
    <div class="row">
        <div class="col-md-12">
            <div class="card mb-4">

                @include('Lain-lain.korespondensi.SuratPengantar.data')
                
            </div>
        </div>
    </div>
    <div class="dropdown-menu dropdown-menu-end animate" id="menuklik" style="display:none">
        <div class="text-center fw-bold mb-2" id="judulklik"></div>
        <a class="dropdown-item" id="removeRow" ><span class="">-</span>&nbsp; Remove</a>
    </div>
@endsection

@section('script')

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
            dom: 'Bfrtip',
            buttons: [{
                extend: 'print',
                split: ['excel', 'pdf', 'print', 'copy', 'csv'],
            }]
            // buttons: ['excel', 'pdf', 'print', 'copy', 'csv']
        });

        // Function for klik baru
        function KlikBaru() {
            // Getting SWSuratPengantar from hidden input
            let SWSuratPengantar = $('#SWSuratPengantar').val()
            if (SWSuratPengantar != "") {
                // If SWSuratPengantar have value. It will reload th page
                window.location.reload()
                return;
            }
            // Empty row in tbody
            $("#tabel1  tbody").empty()

            // Setup table row
            let number = $('#tabel1 tr').length;
            let trStart = "<tr class='klik' id='Row_"+number+"'>"
            let trEnd = "</tr>"
            let no = '<td class="m-0 p-0"><input type="text" class="form-control form-control-sm fs-6 w-100 text-center nomor" name="no" readonly value="'+number+'"></td>'
            let qty = '<td class="m-0 p-0"><input type="number" oncontextmenu="klikme('+number+',event)" min="1" class="form-control form-control-sm fs-6 w-100 text-center qty" name="qty" id="qty_'+number+'" value=""></td>'
            let satuan = '<td class="m-0 p-0"> <input type="text" oncontextmenu="klikme('+number+',event)" class="form-control form-control-sm fs-6 w-100 text-center satuan" name="satuan" id="satuan_'+number+'" value=""> </td>'
            let namaBarang = '<td class="m-0 p-0"> <input type="text" oncontextmenu="klikme('+number+',event)" onKeyPress="nextRowEvent('+number+',event)" class="form-control form-control-sm fs-6 w-100 text-center namaBarang" name="namaBarang" id="namaBarang_'+number+'" value=""> </td>'
            let finalItem = ""
            let rowitem = finalItem.concat(trStart, no, qty, satuan, namaBarang, trEnd)
            
            // add row to tbody
            $("#tabel1 > tbody").append(rowitem);
            
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

        // Function for createNewRow
        function newRow() {
            // Setup table row
            let number = $('#tabel1 tr').length;
            let trStart = "<tr class='klik' id='Row_"+number+"'>"
            let trEnd = "</tr>"
            let no = '<td class="m-0 p-0"><input type="text" class="form-control form-control-sm fs-6 w-100 text-center nomor" name="no" readonly value="'+number+'"></td>'
            let qty = '<td class="m-0 p-0"><input type="number" oncontextmenu="klikme('+number+',event)" min="1" class="form-control form-control-sm fs-6 w-100 text-center qty" name="qty" id="qty_'+number+'" value=""></td>'
            let satuan = '<td class="m-0 p-0"> <input type="text" oncontextmenu="klikme('+number+',event)" class="form-control form-control-sm fs-6 w-100 text-center satuan" name="satuan" id="satuan_'+number+'" value=""> </td>'
            let namaBarang = '<td class="m-0 p-0"> <input type="text" oncontextmenu="klikme('+number+',event)" onKeyPress="nextRowEvent('+number+',event)" class="form-control form-control-sm fs-6 w-100 text-center namaBarang" name="namaBarang" id="namaBarang_'+number+'" value=""> </td>'
            let finalItem = ""
            let rowitem = finalItem.concat(trStart, no, qty, satuan, namaBarang, trEnd)
            // add row to tbody
            $("#tabel1 > tbody").append(rowitem);
            $('#qty_'+number).focus()
        }

        // Function if btn_batal clicked
        function klikBatal() {
            window.location.reload();
        }


        // Function for auto complete on recipient
        $("#recipient").autocomplete({
            // Getting autocomplete item
            source: function(request, response) {
                // hit backend for getting item
                $.ajax({
                    url: '/Lain-lain/Korespondensi/SuratPengantar/recipient?sw='+$('#recipient').val(),
                    type: 'GET',
                    dataType: "json",
                    success: function(data) {
                        // set autocomplete item
                        response(data);
                    }
                });
            },
            // Event when item in autocomplete clicked
            select: function(event, ui) {
                // Set value when autocomplete clicked
                $('#recipient').val(ui.item.label);
                $('#alamat').val(ui.item.Address);
                return false;
            },
            open: function() {
                $(this).autocomplete('widget').css('z-index', 1100);
                return false;
            },
        });

        function KlikSimpan() {
            let action = $('#action').val()
            if (action == 'simpan'){
                SimpanSuratPengantar();
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
        function SimpanSuratPengantar() {
            // Gather all required data
            let toemployee = $('#toemployee').val();
            let tanggal = $('#tanggal').val()
            let recipient = $('#recipient').val()
            let alamat = $('#alamat').val()
            // items
            let qtys = $('.qty')
            let satuans = $('.satuan')
            let namaBarangs = $('.namaBarang')

            // Check ToEmployee
            if (toemployee == "") {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: "To Employee can't be blank",
                })
                return;
            }

            // Check tanggal
            if (tanggal == ""){
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: "Tanggal Can't be blank",
                })
                return;
            }

            // Check recipient
            if (recipient == ""){
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: "Recipient Can't be blank",
                })
                return;
            }

            // Check alamat
            if (alamat == ""){
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: "Alamat Can't be blank",
                })
                return;
            }

            //!  ------------------------    Check if have items     ------------------------ !!
            if (qtys.length === 0 || satuans.length === 0 || namaBarangs.length === 0) {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: "Need One or More Data.",
                })
                return;
            }

            //!  ------------------------    Check Items namaBarang if have blank value     ------------------------ !!
            let cekNamaBarang = false
            namaBarangs.map(function () {
                if (this.value === ''){
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: "There still empty Nama Barang field. Please Fill it.",
                    })
                    cekNamaBarang = true
                    return false;
                }
            }) 
            if(cekNamaBarang == true){
                return false;
            }

            // Turn items to json format
            let items = []
            for (let index = 0; index < qtys.length; index++) {
                var qty = $(qtys[index]).val()
                var satuan = $(satuans[index]).val()
                var namabarang = $(namaBarangs[index]).val()
                let dataitems = {
                    qty:qty,
                    satuan:satuan,
                    namabarang:namabarang,
                }
                items.push(dataitems)
            }

            //!  ------------------------    Send Request to Server     ------------------------ !!
            // Setup data for server
            let data = {
                toemployee:toemployee,
                tanggal:tanggal,
                recipient:recipient,
                alamat:alamat,
                items:items
            }
            // Setup CSRF Token
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            // Hit backend
            $.ajax({
                type: "POST",
                url: "/Lain-lain/Korespondensi/SuratPengantar",
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
                    // Set input hidden SWSuratPengantar
                    $('#SWSuratPengantar').val(data.data.SW)
                    // Set No.Referensi
                    $('#no_referensi').val(data.data.SW)
                    // Set input cari
                    $('#cari').val(data.data.SW)

                    // disable tanggal
                    $('#tanggal').prop('disabled',true)
                    // disable recipient
                    $('#recipient').prop('disabled',true)
                    // disable alamat
                    $('#alamat').prop('disabled',true)
                    // diable toEmployee
                    $('#toemployee').attr("disabled",true);
                    // disable qty
                    $('.qty').prop('disabled',true)
                    // disable satuan 
                    $('.satuan').prop('disabled',true)
                    // disable namaBarang
                    $('.namaBarang').prop('disabled',true)
                    // set removeAction
                    $('#removeAction').val('false')
                    // set action to ubah
                    $('#action').val('ubah')
                },
                error: function(xhr, textStatus, errorThrown){
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: "Saving Surat Pengantar Failed. Code:"+xhr.status
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

        function UbahSuratPengantar() {
            // Gather all required data
            let no_referensi = $('#no_referensi').val()
            let toemployee = $('#toemployee').val()
            let tanggal = $('#tanggal').val()
            let recipient = $('#recipient').val()
            let alamat = $('#alamat').val()
            // items
            let qtys = $('.qty')
            let satuans = $('.satuan')
            let namaBarangs = $('.namaBarang')

            // Check ToEmployee
            if (toemployee == "") {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: "To Employee can't be blank",
                })
                return;
            }

            // Check tanggal
            if (tanggal == ""){
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: "Tanggal Can't be blank",
                })
                return;
            }

            // Check recipient
            if (recipient == ""){
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: "Recipient Can't be blank",
                })
                return;
            }

            // Check alamat
            if (alamat == ""){
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: "Alamat Can't be blank",
                })
                return;
            }

            //!  ------------------------    Check if have items     ------------------------ !!
            if (qtys.length === 0 || satuans.length === 0 || namaBarangs.length === 0) {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: "Need One or More Data.",
                })
                return;
            }

            //!  ------------------------    Check Items namaBarang if have blank value     ------------------------ !!
            let cekNamaBarang = false
            namaBarangs.map(function () {
                if (this.value === ''){
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: "There still empty Nama Barang field. Please Fill it.",
                    })
                    cekNamaBarang = true
                    return false;
                }
            }) 
            if(cekNamaBarang == true){
                return false;
            }

            // Turn items to json format
            let items = []
            for (let index = 0; index < qtys.length; index++) {
                var qty = $(qtys[index]).val()
                var satuan = $(satuans[index]).val()
                var namabarang = $(namaBarangs[index]).val()
                let dataitems = {
                    qty:qty,
                    satuan:satuan,
                    namabarang:namabarang,
                }
                items.push(dataitems)
            }

            //!  ------------------------    Send Request to Server     ------------------------ !!
            // Setup data for server
            let data = {
                sw:no_referensi,
                toemployee:toemployee,
                tanggal:tanggal,
                recipient:recipient,
                alamat:alamat,
                items:items
            }
            // Setup CSRF Token
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            // Hit backend
            $.ajax({
                type: "PUT",
                url: "/Lain-lain/Korespondensi/SuratPengantar",
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
                        title: 'Terubah!',
                        text: "Data Berhasil Terubah.",
                        timer: 2000,
                        showCancelButton: false,
                        showConfirmButton: false
                    });
                    console.log(data);

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
                    // Set input hidden SWSuratPengantar
                    $('#SWSuratPengantar').val(data.data.SW)
                    // Set No.Referensi
                    $('#no_referensi').val(data.data.SW)
                    // Set input cari
                    $('#cari').val(data.data.SW)

                    // disable tanggal
                    $('#tanggal').prop('disabled',true)
                    // disable recipient
                    $('#recipient').prop('disabled',true)
                    // disable alamat
                    $('#alamat').prop('disabled',true)
                    // diable toEmployee
                    $('#toemployee').attr("disabled",true);
                    // disable qty
                    $('.qty').prop('disabled',true)
                    // disable satuan 
                    $('.satuan').prop('disabled',true)
                    // disable namaBarang
                    $('.namaBarang').prop('disabled',true)
                    // set removeAction
                    $('#removeAction').val('false')
                    // set action to ubah
                    $('#action').val('ubah')
                },
                error: function(xhr, textStatus, errorThrown){
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: "Update Surat Pengantar Failed. Code:"+xhr.status
                    })

                    return;
                }
            })
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
                    $('#qty_'+nextrow).focus()
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

            // Get SWSuratPengantar value from hidden input for checking
            let SWSuratPengantar = $('#SWSuratPengantar').val()
            // Check if SWSuratPengantar have value
            if (SWSuratPengantar == ""){
                // If SWSuratPengantar didn't have value or blank it will just remove the row
                
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
                        
                        // Set new qty properties
                        $(elem).find('.qty').attr('oncontextmenu',"klikme("+newIndex+",event)")
                        $(elem).find('.qty').attr('id',"qty_"+newIndex)

                        // Set new satuan properties
                        $(elem).find('.satuan').attr('oncontextmenu',"klikme("+newIndex+",event)")
                        $(elem).find('.satuan').attr('id',"satuan_"+newIndex)

                        // Set new namaBarang properties
                        $(elem).find('.namaBarang').attr('oncontextmenu',"klikme("+newIndex+",event)")
                        $(elem).find('.namaBarang').attr('onkeypress',"nextRowEvent("+newIndex+",event)")
                        $(elem).find('.namaBarang').attr('id',"namaBarang_"+newIndex)                       
                        
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
                        
                        // Set new qty properties
                        $(elem).find('.qty').attr('oncontextmenu',"klikme("+newIndex+",event)")
                        $(elem).find('.qty').attr('id',"qty_"+newIndex)

                        // Set new satuan properties
                        $(elem).find('.satuan').attr('oncontextmenu',"klikme("+newIndex+",event)")
                        $(elem).find('.satuan').attr('id',"satuan_"+newIndex)

                        // Set new namaBarang_ properties
                        $(elem).find('.namaBarang').attr('oncontextmenu',"klikme("+newIndex+",event)")
                        $(elem).find('.namaBarang').attr('onkeypress',"nextRowEvent("+newIndex+",event)")
                        $(elem).find('.namaBarang').attr('id',"namaBarang_"+newIndex)                       
                        
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
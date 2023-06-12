<?php $title = 'Dispensasi Keterlambatan'; ?>
@extends('layouts.backend-Theme-3.app')
@section('title', '$title')

@section('Dashboard')
    <h2 class="m-0">{{ $title }}</h2>
    <ol class="breadcrumb sm-2">
        <li class="breadcrumb-item"><a href="/">Home </a></li>
        <li class="breadcrumb-item">Absensi </li>
        <li class="breadcrumb-item">Absensi </li>
        <li class="breadcrumb-item active">Dispensasi Keterlambatan </li>
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

                @include('Absensi.Absensi.DispensasiKeterlambatan.data')
                
            </div>
        </div>
    </div>
    <div class="dropdown-menu dropdown-menu-end animate" id="menuklik" style="display:none">
        <div class="text-center fw-bold mb-2" id="judulklik"></div>
        <a class="dropdown-item" id="removeRow" ><span class="">-</span>&nbsp; Hapus</a>
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
            "fixedColumns": true
        });

        // Function for klik baru
        function KlikBaru() {
            // Getting idDispensasiKeterlambatan from hidden input
            let idDispensasiKeterlambatan = $('#idDispensasiKeterlambatan').val()
            if (idDispensasiKeterlambatan != "") {
                // If idDispensasiKeterlambatan have value. It will reload th page
                window.location.reload()
                return;
            }
            
            // Disable button "Baru"
            $("#btn_baru").prop('disabled',true)
            // Enable Button "Batal dan Simpan"
            $("#btn_simpan").prop('disabled',false)
            $("#btn_batal").prop('disabled',false)
            // Enable input
            $("#tanggal").prop('disabled',false)
            $("#catatan").prop('disabled',false)
            $("#btn_daftarKaryawan").prop('disabled',false)
        }

        // Function for Cari Daftar Karyawan who eligible to get additional food
        function GetDaftarKaryawan() {
            let tanggal = $('#tanggal').val()
            if (tanggal == "") {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: "Tanggal Tidak Boleh Kosong",
                })
                return;
            }
            let data = {tanggal:tanggal}

            // Make request
            $.ajax({
                type: "GET",
                url: "/Absensi/Absensi/DispensasiKeterlambatan/daftarkaryawan",
                data:data,
                dataType: 'json',
                beforeSend: function () {
                    $(".preloader").show();  
                },
                complete: function () {
                    $(".preloader").fadeOut(); 
                },
                success: function(data) {
                    // Clear Table
                    $("#tabel1  tbody").empty()

                    // Loop data and add it to table
                    let items = data.data
                    items.forEach(function (value, i) {
                        let number = i+1
                        let trStart = "<tr class='klik' id='Row_"+number+"'>"
                        let trEnd = "</tr>"
                        let no = '<td class="m-0 p-0"><input type="text" class="form-control form-control-sm fs-6 w-100 text-center nomor" name="no" readonly value="'+number+'"></td>'
                        let karyawan = '<td class="m-0 p-0"><input type="text" onChange="GetEmployee(this.value,'+number+')" oncontextmenu="klikme('+number+',event)" class="form-control form-control-sm fs-6 w-100 text-center karyawan" name="karyawan" readonly id="karyawan_'+number+'" value="'+value.Employee+'"></td>'
                        let idKaryawan = '<td class="m-0 p-0"><input type="number" oncontextmenu="klikme('+number+',event)" class="form-control form-control-sm fs-6 w-100 text-center idKaryawan" name="idKaryawan" id="idKaryawan_'+number+'" readonly value="'+value.ID+'"></td>'
                        let bagian = '<td class="m-0 p-0"> <input type="text" oncontextmenu="klikme('+number+',event)" class="form-control form-control-sm fs-6 w-100 text-center bagian" name="bagian" id="bagian_'+number+'" readonly value="'+value.Department+'"> </td>'
                        let status = '<td class="m-0 p-0"> <input type="text" oncontextmenu="klikme('+number+',event)" class="form-control form-control-sm fs-6 w-100 text-center status" name="status" id="status_'+number+'" readonly value="'+value.WorkRole+'"> </td>'
                        let checkClock = '<td class="m-0 p-0"> <input type="time" step="1" oncontextmenu="klikme('+number+',event)" class="form-control form-control-sm fs-6 w-100 text-center checkClock" name="checkClock" id="checkClock_'+number+'" readonly value="'+value.WorkIn+'"> </td>'
                        let finalItem = ""
                        let rowitem = finalItem.concat(trStart, no, karyawan, idKaryawan, bagian, status, checkClock, trEnd)
                        $("#tabel1 > tbody").append(rowitem);
                    })
                    // Disable tanggal
                    $('#tanggal').prop('disabled',true)
                    // Disable button daftarkaryawan
                    $('#btn_daftarKaryawan').prop('disabled',true)
                    return;
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

        // Function if btn_batal clicked
        function klikBatal() {
            window.location.reload();
        }

        function KlikSimpan() {
            let action = $('#action').val()
            if (action == 'simpan'){
                SimpanDispensasiKeterlambatan();
            } else if (action == 'ubah'){
                UbahDispensasiKeterlambatan();
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: "Action must be simpan or ubah",
                })
                return;
            }
        }

        // Function for saving Tambahan Uang Makan
        function SimpanDispensasiKeterlambatan() {
            // Gather all required data
            let tanggal = $('#tanggal').val()
            let catatan = $('#catatan').val()
            // items
            let idEmployees = $('.idKaryawan')
            let checkClocks = $('.checkClock')
            
            // Check tanggal
            if (tanggal == ""){
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: "Tanggal Can't be blank",
                })
                return;
            }

            //!  ------------------------    Check if have items     ------------------------ !!
            if (idEmployees.length === 0 || checkClocks.length === 0) {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: "Need One or More Data.",
                })
                return;
            }

            //!  ------------------------    Check Items idEmployee if have blank value     ------------------------ !!
            let cekidEmployee = false
            idEmployees.map(function () {
                if (this.value === ''){
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: "There still empty Employee field. Please Fill it.",
                    })
                    cekidEmployee = true
                    return false;
                }
            }) 
            if(cekidEmployee == true){
                return false;
            }

            //!  ------------------------    Check Items Checkclock if have blank value     ------------------------ !!
            let cekCheckClock = false
            checkClocks.map(function () {
                if (this.value === ''){
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: "There still empty Checkclock field. Please Fill it.",
                    })
                    cekCheckClock = true
                    return false;
                }
            }) 
            if(cekCheckClock == true){
                return false;
            }

            // Turn items to json format
            let items = []
            for (let index = 0; index < idEmployees.length; index++) {
                var idEmployee = $(idEmployees[index]).val()
                var checkClock = $(checkClocks[index]).val()
                let dataitems = {
                    idEmployee:idEmployee,
                    checkClock:checkClock
                }
                items.push(dataitems)
            }

            //!  ------------------------    Send Request to Server     ------------------------ !!
            // Setup data for server
            let data = {
                tanggal:tanggal,
                catatan:catatan,
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
                url: "/Absensi/Absensi/DispensasiKeterlambatan",
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
                    // Activate button baru
                    $('#btn_baru').prop('disabled',false)
                    // Activate button Ubah
                    $('#btn_ubah').prop('disabled',false)
                    // Activate button Posting
                    $('#btn_posting').prop('disabled',false)
                    // Set input hidden idDispensasiKeterlambatan
                    $('#idDispensasiKeterlambatan').val(data.data.ID)
                    // Set Posting Status
                    $('#postingStatus').val(data.data.postingStatus)
                    // Set input cari
                    $('#cari').val(data.data.ID)

                    // disable tanggal
                    $('#tanggal').prop('disabled',true)
                    // disable catatan
                    $('#catatan').prop('disabled',true)
                    // set removeAction
                    $('#removeAction').val('false')
                    // set action to ubah
                    $('#action').val('ubah')
                    return
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

        // Function for search surat jalan
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

            let data = {keyword:cari}

            // Make request
            $.ajax({
                type: "GET",
                url: "/Absensi/Absensi/DispensasiKeterlambatan/Search",
                data:data,
                dataType: 'json',
                beforeSend: function () {
                    $(".preloader").show();  
                },
                complete: function () {
                    $(".preloader").fadeOut(); 
                },
                success: function(data) {
                    // Set Hidden idDispensasiKeterlambatan value
                    $('#idDispensasiKeterlambatan').val(data.data.ID)
                    
                    // Set tanggal
                    $('#tanggal').prop('disabled',true)
                    $('#tanggal').val(data.data.TransDate)

                    // Set catatan
                    $('#catatan').prop('disabled',true)
                    $('#catatan').val(data.data.Remarks)

                    $('#postingStatus').val(data.data.Active)
                    
                    if (data.data.Active == "A") {
                        // Set button ubah to enable
                        $('#btn_ubah').prop('disabled',false)
                        $('#btn_posting').prop('disabled',false)
                    }else {
                        // Set button ubah to disable
                        $('#btn_ubah').prop('disabled',true)
                        $('#btn_posting').prop('disabled',true)
                    }
                    // set button batal to disable
                    $('#btn_batal').prop('disabled',true)
                    // set button simpan to disable
                    $('#btn_simpan').prop('disabled',true)
                    // set button baru to enable
                    $('#btn_baru').prop('disabled',false)                    
                    // Clear Table
                    $("#tabel1  tbody").empty()

                    // Loop data and add it to table
                    let items = data.data.dispensasiKeterlambatanItem
                    items.forEach(function (value, i) {
                        let number = i+1
                        let trStart = "<tr class='klik' id='Row_"+number+"'>"
                        let trEnd = "</tr>"
                        let no = '<td class="m-0 p-0"><input type="text" class="form-control form-control-sm fs-6 w-100 text-center nomor" name="no" readonly value="'+number+'"></td>'
                        let karyawan = '<td class="m-0 p-0"><input type="text" onChange="GetEmployee(this.value,'+number+')" oncontextmenu="klikme('+number+',event)" class="form-control form-control-sm fs-6 w-100 text-center karyawan" name="karyawan" readonly id="karyawan_'+number+'" value="'+value.Karyawan+'"></td>'
                        let idKaryawan = '<td class="m-0 p-0"><input type="number" oncontextmenu="klikme('+number+',event)" class="form-control form-control-sm fs-6 w-100 text-center idKaryawan" name="idKaryawan" id="idKaryawan_'+number+'" readonly value="'+value.idEmployee+'"></td>'
                        let bagian = '<td class="m-0 p-0"> <input type="text" oncontextmenu="klikme('+number+',event)" class="form-control form-control-sm fs-6 w-100 text-center bagian" name="bagian" id="bagian_'+number+'" readonly value="'+value.Bagian+'"> </td>'
                        let status = '<td class="m-0 p-0"> <input type="text" oncontextmenu="klikme('+number+',event)" class="form-control form-control-sm fs-6 w-100 text-center status" name="status" id="status_'+number+'" readonly value="'+value.Status+'"> </td>'
                        let checkClock = '<td class="m-0 p-0"> <input type="time" step="1" oncontextmenu="klikme('+number+',event)" class="form-control form-control-sm fs-6 w-100 text-center checkClock" name="checkClock" id="checkClock_'+number+'" readonly value="'+value.CheckClock+'"> </td>'
                        let finalItem = ""
                        let rowitem = finalItem.concat(trStart, no, karyawan, idKaryawan, bagian, status, checkClock, trEnd)
                        $("#tabel1 > tbody").append(rowitem);
                    })

                    // set removeAction
                    $('#removeAction').val('false')
                    // set action to ubah
                    $('#action').val('ubah')
                },
                error: function(xhr, textStatus, errorThrown){
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: "Tambahan Uang Makan Not Found"
                    })

                    // Clear Table
                    $("#tabel1  tbody").empty()

                    // Set Hidden idTambahanUangMakan value
                    $('#idTambahanUangMakan').val("")
                    // Set Hidden postingStatus value
                    $('#postingStatus').val("A")

                    // set tanggal
                    $('#tanggal').val("")
                    $('#tanggal').prop('disabled',true)
                    
                    // set removeAction
                    $('#removeAction').val('false')
                    // Set cari
                    $('#cari').val("")
                    // Set button
                    $('#btn_simpan').prop('disabled',true)
                    $('#btn_batal').prop('disabled',true)
                    $('#btn_ubah').prop('disabled',true)
                    $('#btn_baru').prop('disabled',false)
                    $('#btn_posting').prop('disabled',true)
                    return;
                }
            })
        }
    
        function KlikUbah() {
            // Check if postingStatus == 'A'
            let postingStatus = $('#postingStatus').val()
            if (postingStatus == 'P') {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: "Tambahan Uang Makan Sudah Di Posting. Data tidak dapat diubah",
                })
                return false
            }
            // set removeAction
            $('#removeAction').val('true')
            // set action to ubah
            $('#action').val('ubah')

            // set input to enable
            $("#tanggal").prop('disabled',false)
            $("#catatan").prop('disabled',false)
            $("#btn_daftarKaryawan").prop('disabled',false)

            // disable button
            $('#btn_baru').prop('disabled',true)
            $('#btn_ubah').prop('disabled',true)
            $('#btn_posting').prop('disabled',true)

            // enable button
            $('#btn_batal').prop('disabled',false)
            $('#btn_simpan').prop('disabled',false)
        }

        function UbahDispensasiKeterlambatan() {
            // Gather all required data
            let idDispensasiKeterlambatan = $('#idDispensasiKeterlambatan').val()
            let tanggal = $('#tanggal').val()
            let catatan = $('#catatan').val()
            // items
            let idEmployees = $('.idKaryawan')
            let checkClocks = $('.checkClock')
            
            // Check tanggal
            if (tanggal == ""){
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: "Tanggal Can't be blank",
                })
                return;
            }

            //!  ------------------------    Check if have items     ------------------------ !!
            if (idEmployees.length === 0 || checkClocks.length === 0) {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: "Need One or More Data.",
                })
                return;
            }

            //!  ------------------------    Check Items idEmployee if have blank value     ------------------------ !!
            let cekidEmployee = false
            idEmployees.map(function () {
                if (this.value === ''){
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: "There still empty Employee field. Please Fill it.",
                    })
                    cekidEmployee = true
                    return false;
                }
            }) 
            if(cekidEmployee == true){
                return false;
            }

            //!  ------------------------    Check Items Checkclock if have blank value     ------------------------ !!
            let cekCheckClock = false
            checkClocks.map(function () {
                if (this.value === ''){
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: "There still empty Checkclock field. Please Fill it.",
                    })
                    cekCheckClock = true
                    return false;
                }
            }) 
            if(cekCheckClock == true){
                return false;
            }

            // Turn items to json format
            let items = []
            for (let index = 0; index < idEmployees.length; index++) {
                var idEmployee = $(idEmployees[index]).val()
                var checkClock = $(checkClocks[index]).val()
                let dataitems = {
                    idEmployee:idEmployee,
                    checkClock:checkClock
                }
                items.push(dataitems)
            }

            //!  ------------------------    Send Request to Server     ------------------------ !!
            // Setup data for server
            let data = {
                idLateException:idDispensasiKeterlambatan,
                tanggal:tanggal,
                catatan:catatan,
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
                url: "/Absensi/Absensi/DispensasiKeterlambatan",
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
                        title: 'Terupdate!',
                        text: "Data Berhasil Terupdate.",
                        timer: 2000,
                        showCancelButton: false,
                        showConfirmButton: false
                    });
                    // if create success
                    // Disable button Batal
                    $('#btn_batal').prop('disabled',true)
                    // Disable button simpan
                    $('#btn_simpan').prop('disabled',true)
                    // Activate button baru
                    $('#btn_baru').prop('disabled',false)
                    // Activate button Ubah
                    $('#btn_ubah').prop('disabled',false)
                    // Activate button Posting
                    $('#btn_posting').prop('disabled',false)
                    // Set input hidden idDispensasiKeterlambatan
                    $('#idDispensasiKeterlambatan').val(data.data.ID)
                    // Set Posting Status
                    $('#postingStatus').val(data.data.postingStatus)
                    // Set input cari
                    $('#cari').val(data.data.ID)

                    // disable tanggal
                    $('#tanggal').prop('disabled',true)
                    // disable catatan
                    $('#catatan').prop('disabled',true)
                    // set removeAction
                    $('#removeAction').val('false')
                    // set action to ubah
                    $('#action').val('ubah')
                    return
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

        function KlikPosting() {
            // Check if postingStatus == 'A'
            let postingStatus = $('#postingStatus').val()
            if (postingStatus == 'P') {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: "Dispensasi Keterlambatan Sudah Diposting",
                })
                return false;
            }
            // Check if idDispensasiKeterlambatan is set
            let idDispensasiKeterlambatan = $('#idDispensasiKeterlambatan').val()
            if (idDispensasiKeterlambatan == "") {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: "Dispensasi Keterlambatan Belum Terpilih",
                })
                return;
            }
            let data = {idLateException:idDispensasiKeterlambatan}
            // Hit Posting
            $.ajax({
                type: "GET",
                url: "/Absensi/Absensi/DispensasiKeterlambatan/Posting",
                data:data,
                dataType: 'json',
                beforeSend: function () {
                    $(".preloader").show();  
                },
                complete: function () {
                    $(".preloader").fadeOut(); 
                },
                success: function(data) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Diposting',
                        text: "Dispensasi Keterlambatan Berhasil Diposting",
                    })
                    // Set Button Disable/Enable
                    $('#btn_baru').prop('disabled',false)
                    $('#btn_ubah').prop('disabled',true)
                    $('#btn_batal').prop('disabled',true)
                    $('#btn_simpan').prop('disabled',true)
                    $('#btn_posting').prop('disabled',true)
                    
                    // Set Posting Status to P
                    $('#postingStatus').val('P')
                    return;
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

        $('#cari').change(function(event) {
            Cari()
        })

        function keteranganEvent(index,event){
            let keyCode = event.keyCode || event.which;
            if (keyCode === 40 || keyCode === 13) {
                // count total row
                let number = $('#tabel1 tbody tr').length;
                if (number == index){
                    newRow();
                }else{
                    let nextrow = index+1
                    $('#karyawan_'+nextrow).focus()
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

            // Get idLemburKerja value from hidden input for checking
            let idLemburKerja = $('#idLemburKerja').val()
            // Check if idLemburKerja have value
            if (idLemburKerja == ""){
                // If idLemburKerja didn't have value or blank it will just remove the row
                
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
                        
                        // Set new karyawan properties
                        $(elem).find('.karyawan').attr('oncontextmenu',"klikme("+newIndex+",event)")
                        $(elem).find('.karyawan').attr('id',"karyawan_"+newIndex)

                        // Set new idKaryawan properties
                        $(elem).find('.idKaryawan').attr('oncontextmenu',"klikme("+newIndex+",event)")
                        $(elem).find('.idKaryawan').attr('id',"idKaryawan_"+newIndex)

                        // Set new bagian properties
                        $(elem).find('.bagian').attr('oncontextmenu',"klikme("+newIndex+",event)")
                        $(elem).find('.bagian').attr('id',"bagian_"+newIndex)

                        // Set new aktualMulai properties
                        $(elem).find('.aktualMulai').attr('oncontextmenu',"klikme("+newIndex+",event)")
                        $(elem).find('.aktualMulai').attr('id',"aktualMulai_"+newIndex)

                        // Set new aktualSelesai properties
                        $(elem).find('.aktualSelesai').attr('oncontextmenu',"klikme("+newIndex+",event)")
                        $(elem).find('.aktualSelesai').attr('id',"aktualSelesai_"+newIndex)

                        // Set new lama properties
                        $(elem).find('.lama').attr('oncontextmenu',"klikme("+newIndex+",event)")
                        $(elem).find('.lama').attr('id',"lama_"+newIndex)

                        // Set new waktuTambah properties
                        $(elem).find('.waktuTambah').attr('oncontextmenu',"klikme("+newIndex+",event)")
                        $(elem).find('.waktuTambah').attr('id',"waktuTambah_"+newIndex)                       
                        
                        // Set new bonus properties
                        $(elem).find('.bonus').attr('oncontextmenu',"klikme("+newIndex+",event)")
                        $(elem).find('.bonus').attr('onkeypress',"keteranganEvent("+newIndex+",event)")
                        $(elem).find('.bonus').attr('id',"bonus_"+newIndex)

                        // Set Nomor
                        $(elem).find('.nomor').val(newIndex)

                    })
                }
            } else { // If idLemburKerja have value
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
                        
                        // Set new karyawan properties
                        $(elem).find('.karyawan').attr('oncontextmenu',"klikme("+newIndex+",event)")
                        $(elem).find('.karyawan').attr('id',"karyawan_"+newIndex)

                        // Set new idKaryawan properties
                        $(elem).find('.idKaryawan').attr('oncontextmenu',"klikme("+newIndex+",event)")
                        $(elem).find('.idKaryawan').attr('id',"idKaryawan_"+newIndex)

                        // Set new bagian properties
                        $(elem).find('.bagian').attr('oncontextmenu',"klikme("+newIndex+",event)")
                        $(elem).find('.bagian').attr('id',"bagian_"+newIndex)

                        // Set new aktualMulai properties
                        $(elem).find('.aktualMulai').attr('oncontextmenu',"klikme("+newIndex+",event)")
                        $(elem).find('.aktualMulai').attr('id',"aktualMulai_"+newIndex)

                        // Set new aktualSelesai properties
                        $(elem).find('.aktualSelesai').attr('oncontextmenu',"klikme("+newIndex+",event)")
                        $(elem).find('.aktualSelesai').attr('id',"aktualSelesai_"+newIndex)

                        // Set new lama properties
                        $(elem).find('.lama').attr('oncontextmenu',"klikme("+newIndex+",event)")
                        $(elem).find('.lama').attr('id',"lama_"+newIndex)

                        // Set new waktuTambah properties
                        $(elem).find('.waktuTambah').attr('oncontextmenu',"klikme("+newIndex+",event)")
                        $(elem).find('.waktuTambah').attr('id',"waktuTambah_"+newIndex)                       
                        
                        // Set new bonus properties
                        $(elem).find('.bonus').attr('oncontextmenu',"klikme("+newIndex+",event)")
                        $(elem).find('.bonus').attr('onkeypress',"keteranganEvent("+newIndex+",event)")
                        $(elem).find('.bonus').attr('id',"bonus_"+newIndex)

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
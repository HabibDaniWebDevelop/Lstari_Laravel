<?php $title = 'Lembur Kerja'; ?>
@extends('layouts.backend-Theme-3.app')
@section('title', '$title')

@section('Dashboard')
    <h2 class="m-0">{{ $title }}</h2>
    <ol class="breadcrumb sm-2">
        <li class="breadcrumb-item"><a href="/">Home </a></li>
        <li class="breadcrumb-item">Absensi </li>
        <li class="breadcrumb-item">Absensi </li>
        <li class="breadcrumb-item active">Lembur Kerja </li>
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

                @include('Absensi.Absensi.LemburKerja.data')
                
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
        function NextInput(next, haveAnotherFunction=false, anotherFunction="") {
            $("#"+next).focus();
            if(haveAnotherFunction){
                anotherFunction()
            }
        }

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
            // Getting idLemburKerja from hidden input
            let idLemburKerja = $('#idLemburKerja').val()
            if (idLemburKerja != "") {
                // If idLemburKerja have value. It will reload th page
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
            let karyawan = '<td class="m-0 p-0"><input type="text" onChange="GetEmployee(this.value,'+number+')" onKeyPress="keteranganEvent('+number+',event)" oncontextmenu="klikme('+number+',event)" class="form-control form-control-sm fs-6 w-100 text-center karyawan" name="karyawan" id="karyawan_'+number+'" value=""></td>'
            let idKaryawan = '<td class="m-0 p-0"><input type="number" oncontextmenu="klikme('+number+',event)" class="form-control form-control-sm fs-6 w-100 text-center idKaryawan" name="idKaryawan" id="idKaryawan_'+number+'" readonly value=""></td>'
            let bagian = '<td class="m-0 p-0"> <input type="text" oncontextmenu="klikme('+number+',event)" class="form-control form-control-sm fs-6 w-100 text-center bagian" name="bagian" id="bagian_'+number+'" readonly value=""> </td>'
            let aktualMulai = '<td class="m-0 p-0"> <input type="time" step="1" oncontextmenu="klikme('+number+',event)" class="form-control form-control-sm fs-6 w-100 text-center aktualMulai" name="aktualMulai" id="aktualMulai_'+number+'" value=""> </td>'
            let aktualSelesai = '<td class="m-0 p-0"> <input type="time" step="1" oncontextmenu="klikme('+number+',event)" class="form-control form-control-sm fs-6 w-100 text-center aktualSelesai" name="aktualSelesai" id="aktualSelesai_'+number+'" value=""> </td>'
            let lama = '<td class="m-0 p-0"> <input type="time" step="1" oncontextmenu="klikme('+number+',event)" class="form-control form-control-sm fs-6 w-100 text-center lama" name="lama" id="lama_'+number+'" value=""> </td>'
            let waktuTambah = '<td class="m-0 p-0"><input type="text" oncontextmenu="klikme('+number+',event)" class="form-control form-control-sm fs-6 w-100 text-center waktuTambah" name="waktuTambah" id="waktuTambah_'+number+'" value=""></td>'
            let bonus = '<td class="m-0 p-0"> <input type="number" oncontextmenu="klikme('+number+',event)" class="form-control form-control-sm fs-6 w-100 text-center bonus" autocomplete="off" onKeyPress="keteranganEvent('+number+',event)" name="bonus" id="bonus_'+number+'" value=""> </td>'
            let finalItem = ""
            let rowitem = finalItem.concat(trStart, no, karyawan, idKaryawan, bagian, aktualMulai, aktualSelesai, lama, waktuTambah, bonus, trEnd)
            
            // add row to tbody
            $("#tabel1 > tbody").append(rowitem);
            
            // Disable button "Baru"
            $("#btn_baru").prop('disabled',true)
            // Enable Button "Batal dan Simpan"
            $("#btn_simpan").prop('disabled',false)
            $("#btn_batal").prop('disabled',false)
            // Enable input
            $("#tanggal").prop('disabled',false)
            $("#jam_mulai").prop('disabled',false)
            $("#jam_selesai").prop('disabled',false)
            $("#catatan").prop('disabled',false)
        }

        // Function for createNewRow
        function newRow() {
            // Setup table row
            let number = $('#tabel1 tr').length;
            let trStart = "<tr class='klik' id='Row_"+number+"'>"
            let trEnd = "</tr>"
            let no = '<td class="m-0 p-0"><input type="text" class="form-control form-control-sm fs-6 w-100 text-center nomor" name="no" readonly value="'+number+'"></td>'
            let karyawan = '<td class="m-0 p-0"><input type="text" onChange="GetEmployee(this.value,'+number+')" onKeyPress="keteranganEvent('+number+',event)" oncontextmenu="klikme('+number+',event)" class="form-control form-control-sm fs-6 w-100 text-center karyawan" name="karyawan" id="karyawan_'+number+'" value=""></td>'
            let idKaryawan = '<td class="m-0 p-0"><input type="number" oncontextmenu="klikme('+number+',event)" class="form-control form-control-sm fs-6 w-100 text-center idKaryawan" name="idKaryawan" id="idKaryawan_'+number+'" readonly value=""></td>'
            let bagian = '<td class="m-0 p-0"> <input type="text" oncontextmenu="klikme('+number+',event)" class="form-control form-control-sm fs-6 w-100 text-center bagian" name="bagian" id="bagian_'+number+'" readonly value=""> </td>'
            let aktualMulai = '<td class="m-0 p-0"> <input type="time" step="1" oncontextmenu="klikme('+number+',event)" class="form-control form-control-sm fs-6 w-100 text-center aktualMulai" name="aktualMulai" id="aktualMulai_'+number+'" value=""> </td>'
            let aktualSelesai = '<td class="m-0 p-0"> <input type="time" step="1" oncontextmenu="klikme('+number+',event)" class="form-control form-control-sm fs-6 w-100 text-center aktualSelesai" name="aktualSelesai" id="aktualSelesai_'+number+'" value=""> </td>'
            let lama = '<td class="m-0 p-0"> <input type="time" step="1" oncontextmenu="klikme('+number+',event)" class="form-control form-control-sm fs-6 w-100 text-center lama" name="lama" id="lama_'+number+'" value=""> </td>'
            let waktuTambah = '<td class="m-0 p-0"><input type="text" oncontextmenu="klikme('+number+',event)" class="form-control form-control-sm fs-6 w-100 text-center waktuTambah" name="waktuTambah" id="waktuTambah_'+number+'" value=""></td>'
            let bonus = '<td class="m-0 p-0"> <input type="number" oncontextmenu="klikme('+number+',event)" class="form-control form-control-sm fs-6 w-100 text-center bonus" autocomplete="off" onKeyPress="keteranganEvent('+number+',event)" name="bonus" id="bonus_'+number+'" value=""> </td>'
            let finalItem = ""
            let rowitem = finalItem.concat(trStart, no, karyawan, idKaryawan, bagian, aktualMulai, aktualSelesai, lama, waktuTambah, bonus, trEnd)
            // add row to tbody
            $("#tabel1 > tbody").append(rowitem);
            $('#karyawan_'+number).focus()
        }

        function CheckDate() {
            let tanggal = $('#tanggal').val()
            let data = {tanggal : tanggal}
            $.ajax({
                type: "GET",
                url: "/Absensi/Absensi/LemburKerja/CheckDate",
                data:data,
                dataType: 'json',
                beforeSend: function () {
                    $(".preloader").show();  
                },
                complete: function () {
                    $(".preloader").fadeOut(); 
                },
                success: function(data) {
                    $('#jam_mulai').val(data.data.jamMulai)
                },
                error: function(xhr){
                    // It will executed if response from backend is error
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: xhr.responseJSON.message,
                    })
                    return;
                }
            })
        }

        function GetEmployee(EmployeeSW, index) {
            let tanggal = $('#tanggal').val()
            let jam_mulai = $('#jam_mulai').val()
            let jam_selesai = $('#jam_selesai').val()

            if (tanggal == "" || jam_mulai == "" || jam_selesai == "") {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: "Tanggal, Jam Mulai, dan Jam Selesai harus diisi",
                })
                return;
            }

            let data = {employeeSW:EmployeeSW, tanggal:tanggal, jam_mulai:jam_mulai, jam_selesai:jam_selesai}

            $.ajax({
                type: "GET",
                url: "/Absensi/Absensi/LemburKerja/employee",
                data:data,
                dataType: 'json',
                beforeSend: function () {
                    $(".preloader").show();  
                },
                complete: function () {
                    $(".preloader").fadeOut(); 
                },
                success: function(data) {
                    // set Karyawan
                    $('#karyawan_'+index).val(data.data.employee)
                    // set idEmployee
                    $('#idKaryawan_'+index).val(data.data.idEmployee)
                    // set Bagian
                    $('#bagian_'+index).val(data.data.bagian)
                    // set Aktual Mulai
                    $('#aktualMulai_'+index).val(data.data.aktualMulai)
                    // set Aktual Selesai
                    $('#aktualSelesai_'+index).val(data.data.aktualSelesai)
                    // set lama
                    $('#lama_'+index).val(data.data.lama)
                    // set waktuTambah
                    $('#waktuTambah_'+index).val(data.data.waktuTambah)
                },
                error: function(xhr){
                    // It will executed if response from backend is error
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: xhr.responseJSON.message,
                    })
                    return;
                }
            })
            return "ok"
        }

        // Function if btn_batal clicked
        function klikBatal() {
            window.location.reload();
        }

        function KlikSimpan() {
            let action = $('#action').val()
            if (action == 'simpan'){
                SimpanLemburKerja();
            } else if (action == 'ubah'){
                UbahLemburKerja();
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: "Action must be simpan or ubah",
                })
                return;
            }
        }

        // Function for saving surat jalan
        function SimpanLemburKerja() {
            // Gather all required data
            let tanggal = $('#tanggal').val()
            let jam_mulai = $('#jam_mulai').val()
            let jam_selesai = $('#jam_selesai').val()
            let catatan = $('#catatan').val()
            // items
            let idEmployees = $('.idKaryawan')
            let aktualMulais = $('.aktualMulai')
            let aktualSelesais = $('.aktualSelesai')
            let waktuTambahs = $('.waktuTambah')
            
            // Check tanggal
            if (tanggal == ""){
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: "Tanggal Can't be blank",
                })
                return;
            }

            // Check Jam Mulai
            if (jam_mulai == ""){
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: "Jam Mulai Can't be blank",
                })
                return;
            }

            // Check Jam Selesai
            if (jam_selesai == ""){
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: "Jam Selesai Can't be blank",
                })
                return;
            }

            //!  ------------------------    Check if have items     ------------------------ !!
            if (idEmployees.length === 0 || aktualMulais.length === 0 || aktualSelesais.length === 0 || waktuTambahs.length === 0) {
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

            //!  ------------------------    Check Items Aktual Mulai if have blank value     ------------------------ !!
            let cekAktualMulai = false
            aktualMulais.map(function () {
                if (this.value === ''){
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: "There still empty Aktual Mulai field. Please Fill it.",
                    })
                    cekAktualMulai = true
                    return false;
                }
            }) 
            if(cekAktualMulai == true){
                return false;
            }

            //!  ------------------------    Check Items Aktual Selesai if have blank value     ------------------------ !!
            let cekAktualSelesai = false
            aktualSelesais.map(function () {
                if (this.value === ''){
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: "There still empty Aktual Selesai field. Please Fill it.",
                    })
                    cekAktualSelesai = true
                    return false;
                }
            }) 
            if(cekAktualSelesai == true){
                return false;
            }
            
            //!  ------------------------    Check Items Waktu Tambah if have blank value     ------------------------ !!
            let cekWaktuTambah = false
            waktuTambahs.map(function () {
                if (this.value === ''){
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: "There still empty Waktu Tambah field. Please Fill it.",
                    })
                    cekWaktuTambah = true
                    return false;
                }
            }) 
            if(cekWaktuTambah == true){
                return false;
            }

            // Turn items to json format
            let items = []
            for (let index = 0; index < idEmployees.length; index++) {
                var idEmployee = $(idEmployees[index]).val()
                var aktualMulai = $(aktualMulais[index]).val()
                var aktualSelesai = $(aktualSelesais[index]).val()
                var waktuTambah = $(waktuTambahs[index]).val()
                let dataitems = {
                    idEmployee:idEmployee,
                    aktualMulai:aktualMulai,
                    aktualSelesai:aktualSelesai,
                    waktuTambah:waktuTambah
                }
                items.push(dataitems)
            }

            //!  ------------------------    Send Request to Server     ------------------------ !!
            // Setup data for server
            let data = {
                tanggal:tanggal,
                jam_mulai:jam_mulai,
                jam_selesai:jam_selesai,
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
                url: "/Absensi/Absensi/LemburKerja",
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
                    // Set input hidden idLemburKerja
                    $('#idLemburKerja').val(data.data.ID)
                    // Set input cari
                    $('#cari').val(data.data.ID)

                    // disable tanggal
                    $('#tanggal').prop('disabled',true)
                    // disable jam_mulai
                    $('#jam_mulai').prop('disabled',true)
                    // disable jam_selesai
                    $('#jam_selesai').prop('disabled',true)
                    // disable catatan
                    $('#catatan').prop('disabled',true)
                    // disable karyawan
                    $('.karyawan').prop('disabled',true)
                    // disable aktualMulai 
                    $('.aktualMulai').prop('disabled',true)
                    // disable aktualSelesai
                    $('.aktualSelesai').prop('disabled',true)
                    // disable lama
                    $('.lama').prop('disabled',true)
                    // disable waktuTambah
                    $('.waktuTambah').prop('disabled',true)
                    // disable bonus
                    $('.bonus').prop('disabled',true)
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
                url: "/Absensi/Absensi/LemburKerja/search",
                data:data,
                dataType: 'json',
                beforeSend: function () {
                    $(".preloader").show();  
                },
                complete: function () {
                    $(".preloader").fadeOut(); 
                },
                success: function(data) {
                    // Set Hidden idLemburKerja value
                    $('#idLemburKerja').val(data.data.idLemburKerja)
                    
                    // Set tanggal
                    $('#tanggal').prop('disabled',true)
                    $('#tanggal').val(data.data.tanggal)
                    
                    // Set jam_mulai
                    $('#jam_mulai').prop('disabled',true)
                    $('#jam_mulai').val(data.data.jam_mulai)

                    // Set jam_selesai
                    $('#jam_selesai').prop('disabled',true)
                    $('#jam_selesai').val(data.data.jam_selesai)

                    // Set catatan
                    $('#catatan').prop('disabled',true)
                    $('#catatan').val(data.data.catatan)

                    // Set button ubah to enable
                    $('#btn_ubah').prop('disabled',false)
                    // set button batal to disable
                    $('#btn_batal').prop('disabled',true)
                    // set button simpan to disable
                    $('#btn_simpan').prop('disabled',true)
                    // set button baru to enable
                    $('#btn_baru').prop('disabled',false)                    
                    // Clear Table
                    $("#tabel1  tbody").empty()

                    // Loop data and add it to table
                    let items = data.data.lemburKerjaItem
                    items.forEach(function (value, i) {
                        let number = i+1
                        let trStart = "<tr class='klik' id='Row_"+number+"'>"
                        let trEnd = "</tr>"
                        let no = '<td class="m-0 p-0"><input type="text" class="form-control form-control-sm fs-6 w-100 text-center nomor" name="no" readonly value="'+number+'"></td>'
                        let karyawan = '<td class="m-0 p-0"><input type="text" onChange="GetEmployee(this.value,'+number+')" onKeyPress="keteranganEvent('+number+',event)" oncontextmenu="klikme('+number+',event)" class="form-control form-control-sm fs-6 w-100 text-center karyawan" name="karyawan" id="karyawan_'+number+'" value="'+value.karyawan+'"></td>'
                        let idKaryawan = '<td class="m-0 p-0"><input type="number" oncontextmenu="klikme('+number+',event)" class="form-control form-control-sm fs-6 w-100 text-center idKaryawan" name="idKaryawan" id="idKaryawan_'+number+'" readonly value="'+value.idEmployee+'"></td>'
                        let bagian = '<td class="m-0 p-0"> <input type="text" oncontextmenu="klikme('+number+',event)" class="form-control form-control-sm fs-6 w-100 text-center bagian" name="bagian" id="bagian_'+number+'" readonly value="'+value.bagian+'"> </td>'
                        let aktualMulai = '<td class="m-0 p-0"> <input type="time" step="1" oncontextmenu="klikme('+number+',event)" class="form-control form-control-sm fs-6 w-100 text-center aktualMulai" name="aktualMulai" id="aktualMulai_'+number+'" value="'+value.aktualMulai+'"> </td>'
                        let aktualSelesai = '<td class="m-0 p-0"> <input type="time" step="1" oncontextmenu="klikme('+number+',event)" class="form-control form-control-sm fs-6 w-100 text-center aktualSelesai" name="aktualSelesai" id="aktualSelesai_'+number+'" value="'+value.aktualSelesai+'"> </td>'
                        let lama = '<td class="m-0 p-0"> <input type="time" step="1" oncontextmenu="klikme('+number+',event)" class="form-control form-control-sm fs-6 w-100 text-center lama" name="lama" id="lama_'+number+'" value="'+value.lama+'"> </td>'
                        let waktuTambah = '<td class="m-0 p-0"><input type="text" oncontextmenu="klikme('+number+',event)" class="form-control form-control-sm fs-6 w-100 text-center waktuTambah" name="waktuTambah" id="waktuTambah_'+number+'" value="'+value.waktuTambah+'"></td>'
                        let bonus = '<td class="m-0 p-0"> <input type="number" oncontextmenu="klikme('+number+',event)" class="form-control form-control-sm fs-6 w-100 text-center bonus" autocomplete="off" onKeyPress="keteranganEvent('+number+',event)" name="bonus" id="bonus_'+number+'" value="'+value.bonus+'"> </td>'
                        let finalItem = ""
                        let rowitem = finalItem.concat(trStart, no, karyawan, idKaryawan, bagian, aktualMulai, aktualSelesai, lama, waktuTambah, bonus, trEnd)
                        $("#tabel1 > tbody").append(rowitem);
                    })

                    // disable karyawan
                    $('.karyawan').prop('disabled',true)
                    // disable aktualMulai 
                    $('.aktualMulai').prop('disabled',true)
                    // disable aktualSelesai
                    $('.aktualSelesai').prop('disabled',true)
                    // disable lama
                    $('.lama').prop('disabled',true)
                    // disable waktuTambah
                    $('.waktuTambah').prop('disabled',true)
                    // disable bonus
                    $('.bonus').prop('disabled',true)
                    // set removeAction
                    $('#removeAction').val('false')
                    // set action to ubah
                    $('#action').val('ubah')
                },
                error: function(xhr, textStatus, errorThrown){
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: "Lembur Kerja Not Found"
                    })

                    // Clear Table
                    $("#tabel1  tbody").empty()

                    // Set Hidden idLemburKerja value
                    $('#idLemburKerja').val("")

                    // set tanggal
                    $('#tanggal').val("")
                    $('#tanggal').prop('disabled',true)
                    // set jam_mulai
                    $('#jam_mulai').val("")
                    $('#jam_mulai').prop('disabled',true)
                    // set jam_selesai
                    $('#jam_selesai').val("")
                    $('#jam_selesai').prop('disabled',true)
                    // set catatan
                    $('#catatan').val("")
                    $('#catatan').prop('disabled',true)
                    
                    // set removeAction
                    $('#removeAction').val('false')
                    // Set cari
                    $('#cari').val("")
                    // Set button
                    $('#btn_simpan').prop('disabled',true)
                    $('#btn_batal').prop('disabled',true)
                    $('#btn_ubah').prop('disabled',true)
                    $('#btn_baru').prop('disabled',false)
                    return;
                }
            })
        }
    
        function KlikUbah() {
            // enable tanggal
            $('#tanggal').prop('disabled',false)
            // enable jam_mulai
            $('#jam_mulai').prop('disabled',false)
            // enable jam_selesai
            $('#jam_selesai').prop('disabled',false)
            // enable catatan
            $('#catatan').prop('disabled',false)
            // enable karyawan
            $('.karyawan').prop('disabled',false)
            // enable aktualMulai 
            $('.aktualMulai').prop('disabled',false)
            // enable aktualSelesai
            $('.aktualSelesai').prop('disabled',false)
            // enable lama
            $('.lama').prop('disabled',false)
            // enable waktuTambah
            $('.waktuTambah').prop('disabled',false)
            // enable bonus
            $('.bonus').prop('disabled',false)
            // set removeAction
            $('#removeAction').val('true')
            // set action to ubah
            $('#action').val('ubah')

            // disable button
            $('#btn_baru').prop('disabled',true)
            $('#btn_ubah').prop('disabled',true)

            // enable button
            $('#btn_batal').prop('disabled',false)
            $('#btn_simpan').prop('disabled',false)
        }

        function UbahLemburKerja() {
            // Gather all required data
            let idLemburKerja = $('#idLemburKerja').val()
            let tanggal = $('#tanggal').val()
            let jam_mulai = $('#jam_mulai').val()
            let jam_selesai = $('#jam_selesai').val()
            let catatan = $('#catatan').val()
            // items
            let idEmployees = $('.idKaryawan')
            let aktualMulais = $('.aktualMulai')
            let aktualSelesais = $('.aktualSelesai')
            let waktuTambahs = $('.waktuTambah')
            
            // Check idLemburKerja
            if (idLemburKerja == ""){
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: "Lembur Kerja belum terpilih",
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

            // Check Jam Mulai
            if (jam_mulai == ""){
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: "Jam Mulai Can't be blank",
                })
                return;
            }

            // Check Jam Selesai
            if (jam_selesai == ""){
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: "Jam Selesai Can't be blank",
                })
                return;
            }

            //!  ------------------------    Check if have items     ------------------------ !!
            if (idEmployees.length === 0 || aktualMulais.length === 0 || aktualSelesais.length === 0 || waktuTambahs.length === 0) {
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

            //!  ------------------------    Check Items Aktual Mulai if have blank value     ------------------------ !!
            let cekAktualMulai = false
            aktualMulais.map(function () {
                if (this.value === ''){
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: "There still empty Aktual Mulai field. Please Fill it.",
                    })
                    cekAktualMulai = true
                    return false;
                }
            }) 
            if(cekAktualMulai == true){
                return false;
            }

            //!  ------------------------    Check Items Aktual Selesai if have blank value     ------------------------ !!
            let cekAktualSelesai = false
            aktualSelesais.map(function () {
                if (this.value === ''){
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: "There still empty Aktual Selesai field. Please Fill it.",
                    })
                    cekAktualSelesai = true
                    return false;
                }
            }) 
            if(cekAktualSelesai == true){
                return false;
            }
            
            //!  ------------------------    Check Items Waktu Tambah if have blank value     ------------------------ !!
            let cekWaktuTambah = false
            waktuTambahs.map(function () {
                if (this.value === ''){
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: "There still empty Waktu Tambah field. Please Fill it.",
                    })
                    cekWaktuTambah = true
                    return false;
                }
            }) 
            if(cekWaktuTambah == true){
                return false;
            }

            // Turn items to json format
            let items = []
            for (let index = 0; index < idEmployees.length; index++) {
                var idEmployee = $(idEmployees[index]).val()
                var aktualMulai = $(aktualMulais[index]).val()
                var aktualSelesai = $(aktualSelesais[index]).val()
                var waktuTambah = $(waktuTambahs[index]).val()
                let dataitems = {
                    idEmployee:idEmployee,
                    aktualMulai:aktualMulai,
                    aktualSelesai:aktualSelesai,
                    waktuTambah:waktuTambah
                }
                items.push(dataitems)
            }

            //!  ------------------------    Send Request to Server     ------------------------ !!
            // Setup data for server
            let data = {
                idLemburKerja:idLemburKerja,
                tanggal:tanggal,
                jam_mulai:jam_mulai,
                jam_selesai:jam_selesai,
                catatan:catatan,
                items:items
            }
            console.log(data);
            // Setup CSRF Token
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            // Hit backend
            $.ajax({
                type: "PUT",
                url: "/Absensi/Absensi/LemburKerja",
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
                    // if create success
                    // Disable button Batal
                    $('#btn_batal').prop('disabled',true)
                    // Disable button simpan
                    $('#btn_simpan').prop('disabled',true)
                    // Activate button baru
                    $('#btn_baru').prop('disabled',false)
                    // Activate button Ubah
                    $('#btn_ubah').prop('disabled',false)
                    // Set input hidden idLemburKerja
                    $('#idLemburKerja').val(data.data.ID)
                    // Set input cari
                    $('#cari').val(data.data.ID)

                    // disable tanggal
                    $('#tanggal').prop('disabled',true)
                    // disable jam_mulai
                    $('#jam_mulai').prop('disabled',true)
                    // disable jam_selesai
                    $('#jam_selesai').prop('disabled',true)
                    // disable catatan
                    $('#catatan').prop('disabled',true)
                    // disable karyawan
                    $('.karyawan').prop('disabled',true)
                    // disable aktualMulai 
                    $('.aktualMulai').prop('disabled',true)
                    // disable aktualSelesai
                    $('.aktualSelesai').prop('disabled',true)
                    // disable lama
                    $('.lama').prop('disabled',true)
                    // disable waktuTambah
                    $('.waktuTambah').prop('disabled',true)
                    // disable bonus
                    $('.bonus').prop('disabled',true)
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
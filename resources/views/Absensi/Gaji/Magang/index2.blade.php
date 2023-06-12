<?php $title = 'Data Gaji Magang'; ?>
@extends('layouts.backend-Theme-3.app')
@section('title', '$title')

@section('Dashboard')
    <h2 class="m-0">{{ $title }}</h2>
    <ol class="breadcrumb sm-2">
        <li class="breadcrumb-item"><a href="/">Home </a></li>
        <li class="breadcrumb-item">Absensi </li>
        <li class="breadcrumb-item">Gaji </li>
        <li class="breadcrumb-item active">Magang </li>
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

                @include('Absensi.Gaji.Magang.data2')
                
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script src="{!! asset('assets/sneatV1/assets/js/angka_rupiah.min.js') !!}"></script>

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
            // Getting payrollID from hidden input
            let payrollID = $('#payrollID').val()
            // Check if payrollID have value
            if (payrollID != "") {
                // If payrollID have value. It will reload th page
                window.location.reload()
                return;
            }
            // Empty row in tbody
            $("#tabel1  tbody").empty()
            
            // Setup table row
            let number = $('#tabel1 tr').length;
            let trStart = "<tr id='Row_"+number+"'>"
            let trEnd = "</tr>"
            let no = '<td><input type="text" class="form-control form-control-sm fs-6 w-100 text-center" name="no" readonly value="'+number+'"></td>'
            let inputEmployeeSW = '<td><input autocomplete="off" type="text" class="form-control form-control-sm fs-6 w-100 text-center employeeSW" onChange="getEmployeeAndSallary(this.value,'+number+')" name="idEmployee" id="employeeSW_'+number+'" value=""</td>'
            let idEmployee = '<td class="m-0 p-0 "> <span class="idEmployee" id="idEmployee_'+number+'"></span> </td>'
            let totalKerja = '<td class="m-0 p-0 "> <span id="totalKerja_'+number+'"></span> </td>'
            let totalSallary = '<td class="m-0 p-0 "> <span id="totalSallary_'+number+'"></span> </td>'
            let detailButton = '<td class="m-0 p-0"> <button class="btn btn-primary" onclick="detailSallary('+number+')">Detail</button></td>'
            let buttonNewRow = '<td class="m-0 p-0 text-center"> <button class="btn btn-primary btn-sm btnnewrow" onclick="newRow()">+</button> <button class="btn btn-warning btn-sm btnremoverow" onclick="removeRow()">-</button> </td>'
            let finalItem = ""
            let rowitem = finalItem.concat(trStart, no, inputEmployeeSW, idEmployee, totalKerja, totalSallary, detailButton, buttonNewRow, trEnd)
            
            // add row to tbody
            $("#tabel1 > tbody").append(rowitem);
            
            // Disable button "Baru"
            $("#btn_baru").prop('disabled',true)
            // Enable Button "Batal dan Simpan"
            $("#btn_simpan").prop('disabled',false)
            $("#btn_batal").prop('disabled',false)
            // Enable tanggal_awal and tanggal_akhir
            $("#tanggal_awal").prop('disabled',false)
            $("#tanggal_akhir").prop('disabled',false)
        }

        // Function for createNewRow
        function newRow() {
            // Setup table row
            let number = $('#tabel1 tr').length;
            let trStart = "<tr id='Row_"+number+"'>"
            let trEnd = "</tr>"
            let no = '<td><input type="text" class="form-control form-control-sm fs-6 w-100 text-center" name="no" readonly value="'+number+'"></td>'
            let inputEmployeeSW = '<td><input type="text" autocomplete="off" class="form-control form-control-sm fs-6 w-100 text-center employeeSW" onChange="getEmployeeAndSallary(this.value,'+number+')" name="idEmployee" id="employeeSW_'+number+'" value=""</td>'
            let idEmployee = '<td class="m-0 p-0 "> <span class="idEmployee" id="idEmployee_'+number+'"></span> </td>'
            let totalKerja = '<td class="m-0 p-0 "> <span id="totalKerja_'+number+'"></span> </td>'
            let totalSallary = '<td class="m-0 p-0 "> <span id="totalSallary_'+number+'"></span> </td>'
            let detailButton = '<td class="m-0 p-0"> <button class="btn btn-primary" onclick="detailSallary('+number+')">Detail</button></td>'
            let buttonNewRow = '<td class="m-0 p-0 text-center"> <button class="btn btn-primary btn-sm btnnewrow" onclick="newRow()">+</button> <button class="btn btn-warning btn-sm btnremoverow" onclick="removeRow()">-</button> </td>'
            let finalItem = ""
            let rowitem = finalItem.concat(trStart, no, inputEmployeeSW, idEmployee, totalKerja, totalSallary, detailButton, buttonNewRow, trEnd)
            // add row to tbody
            $("#tabel1 > tbody").append(rowitem);
        }

        // Function for remove LastRow
        function removeRow() {
            // Get last row in table
            let lastRow = $('#tabel1 tr').last().attr('id');
            // Get payrollID value from hidden input for checking
            let payrollID = $('#payrollID').val()
            // Check if payrollID have value
            if (payrollID == ""){
                // If payrollID didn't have value or blank it will just remove the row
                // Remove Last Row
                $("#"+lastRow).remove()
                // Getting Row length for checking row length
                let number = $('#tabel1 tbody tr').length;
                // check row length
                if (number == 0){
                    // If length of row is 0 it will reload the page
                    window.location.reload();
                }
            } else {  // If payrollID have value
                // get row length
                let number = $('#tabel1 tbody tr').length;
                if (number !== 1){
                    // If row length is more than 1 it will remove last row
                    $("#"+lastRow).remove()
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

        // Getting Magang Employee (it will executed if id karyawan is inputed.)
        function getEmployeeAndSallary(EmployeeSW,RowNumber) {

            // Setup CSRF TOKEN
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            // Setup Payload
            let startDate = $('#tanggal_awal').val()
            let endDate = $('#tanggal_akhir').val()
            let data = {EmployeeSW:EmployeeSW, startDate:startDate, endDate:endDate}

            // Hit backend for getting employee and his sallary
            $.ajax({
                type: "POST",
                url: "/Absensi/Gaji/Magang/getemployeeandsallary",
                data:data,
                dataType: 'json',
                beforeSend: function () {
                    $(".preloader").show();  
                },
                complete: function () {
                    $(".preloader").fadeOut(); 
                },
                success: function(data) {
                    // Check if EmployeeID Exists on table
                    let EmployeeSWs = $('.idEmployee')
                    let exists = false;
                    EmployeeSWs.map(function () {
                        if (exists !== true){
                            if (this.id !== "idEmployee_"+RowNumber){
                                if ($(this).text() == data.data.employee.ID){
                                    exists = true
                                    return;
                                }
                            }
                        }
                    })

                    // Check if this IDEmployee is exists on table
                    if (exists === true){
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'Employee with ID '+ EmployeeSW +" is Exists on table",
                        })
                        $('#employeeSW_'+RowNumber).val("")
                        $('#employeeSW_'+RowNumber).focus()
                        $('#idEmployee_'+RowNumber).text("")
                        // set Total Kerja
                        $('#totalKerja_'+RowNumber).text("")
                        // set Total Sallary
                        $('#totalSallary_'+RowNumber).text("")
                        return;
                    }
                    // Set Karyawan Name
                    $('#employeeSW_'+RowNumber).val(data.data.employee.NAME)
                    // Set Karyawan ID
                    $('#idEmployee_'+RowNumber).text(data.data.employee.ID)
                    // set Total Kerja
                    $('#totalKerja_'+RowNumber).text(data.data.totalKerja)
                    // set Total Sallary
                    $('#totalSallary_'+RowNumber).text(toRupiah(data.data.totalSallary, {floatingPoint: 0}))
                    
                },
                error: function(xhr){
                    // It will executed if response from backend is error
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: xhr.responseJSON.message,
                    })
                    // set this idEmployee input to blank
                    $('#employeeSW_'+RowNumber).val("")
                    $('#employeeSW_'+RowNumber).focus()
                    // set Total Kerja
                    $('#totalKerja_'+RowNumber).text("")
                    // set Total Sallary
                    $('#totalSallary_'+RowNumber).text("")
                    // Set Karyawan Name
                    $('#idEmployee_'+RowNumber).text("")
                    return;
                }
            })
        }

        function KlikSimpan() {
            // Get action type
            let action =  $('#action').val()
            if (action == 'simpan'){
                SimpanPayroll()
            } else if (action == 'ubah'){
                updatePayroll()
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: "Action not simpan or update",
                })
                return;
            }
        }

        // Function for Simpan Payrollgajimagang
        function SimpanPayroll(){
            // Get required data
            let IDEmployees = $('.idEmployee')
            let startDate = $('#tanggal_awal').val()
            let endDate = $('#tanggal_akhir').val()
            
            // Check if have IDEmployee
            if (IDEmployees.length === 0){
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: "Employee Can't be empty",
                })
                return;
            }
            
            // Check if any blank on IDEmployee
            let cekBlank = false
            IDEmployees.map(function () {
                if ($(this).text() === ''){
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: "There still empty ID Employee field. Please Delete or Fill it.",
                    })
                    cekBlank = true
                    return false;
                }
            }) 
            if(cekBlank == true){
                return false;
            }
            
            // Check if startDate and endDate is not blank
            if (startDate === "" || endDate === "") {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: "StartDate and EndDate can't be Empty",
                })
                return;
            }

            // Setup Payload
            IDEmployees = IDEmployees.map(function () {return $(this).text()}).get()
            let data = {startDate:startDate, endDate:endDate, idEmployees: IDEmployees}

            // Setup CSRF Token
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                type: "POST",
                url: "/Absensi/Gaji/Magang/savepayroll",
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
                        timer: 1000,
                        showCancelButton: false,
                        showConfirmButton: true
                    });

                    // Disable button Batal
                    $('#btn_batal').prop('disabled',true)
                    // Disable button simpan
                    $('#btn_simpan').prop('disabled',true)
                    // Disable Tanggal
                    // $('#tanggal_awal').prop('disabled',true)
                    // $('#tanggal_akhir').prop('disabled',true)
                    // Activate button cetak
                    $('#btn_cetak').prop('disabled',false)
                    // Activate button baru
                    $('#btn_baru').prop('disabled',false)
                    // Activate button Ubah
                    $('#btn_ubah').prop('disabled',false)
                    // Set input hidden payrollID
                    $('#payrollID').val(data.data.payrollInternLastID)
                    // Set input cari
                    $('#cari').val(data.data.payrollInternLastID)
                    
                    // Set action to update
                    $('#action').val('update')

                    // set Tanggal to disabled
                    $('#tanggal_awal').prop('disabled',true)
                    $('#tanggal_akhir').prop('disabled',true)

                    // set button newrow and removerow to disable
                    $('.btnnewrow').prop('disabled',true)
                    $('.btnremoverow').prop('disabled',true)
            

                    // Set items input to disable
                    $('.employeeSW').prop('disabled',true)
                }
            })
        }

        // This function will executed if btn_ubah pressed.
        function KlikUbah() {
            // Set action to ubah
            $('#action').val('ubah')

            // set Tanggal to disabled
            $('#tanggal_awal').prop('disabled',false)
            $('#tanggal_akhir').prop('disabled',false)

            // Set items input to disable
            $('.employeeSW').prop('disabled',false)

            // Disable button baru
            $('#btn_baru').prop('disabled',true)
            // Disable button ubah
            $('#btn_ubah').prop('disabled',true)
            // Disable button cetak
            $('#btn_cetak').prop('disabled',true)

            // Enable button simpan
            $('#btn_simpan').prop('disabled',false)
            // Enable button batal
            $('#btn_batal').prop('disabled',false)

            // set button newrow and removerow to enable
            $('.btnnewrow').prop('disabled',false)
            $('.btnremoverow').prop('disabled',false)

        }
        
        // This function is for updating selected payroll by payrollID. 
        function updatePayroll(){
            // Get required data
            let IDEmployees = $('.idEmployee')
            let startDate = $('#tanggal_awal').val()
            let endDate = $('#tanggal_akhir').val()
            let payrollID = $('#payrollID').val()

            // Check payrollID if it still blank
            if (payrollID === ''){
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: "Payroll not Set",
                })
                return;
            }
            
            // Check if have IDEmployee
            if (IDEmployees.length === 0){
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: "Employee Can't be empty",
                })
                return;
            }
            
            // Check if any blank on IDEmployee
            let cekBlank = false
            IDEmployees.map(function () {
                if ($(this).text() === ''){
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: "There still empty ID Employee field. Please Delete or Fill it.",
                    })
                    cekBlank = true
                    return false;
                }
            }) 
            if(cekBlank == true){
                return false;
            }
            
            // Check if startDate and endDate is not blank
            if (startDate === "" || endDate === "") {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: "StartDate and EndDate can't be Empty",
                })
                return;
            }

            // Setup Payload
            IDEmployees = IDEmployees.map(function () {return $(this).text()}).get()
            let data = {startDate:startDate, endDate:endDate, idEmployees: IDEmployees, payrollID:payrollID}

            // Setup CSRF Token
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            // Make Requests
            $.ajax({
                type: "PUT",
                url: "/Absensi/Gaji/Magang/ubahpayroll",
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

                    // Disable button Batal
                    $('#btn_batal').prop('disabled',true)
                    // Disable button simpan
                    $('#btn_simpan').prop('disabled',true)
                    // Disable Tanggal
                    // $('#tanggal_awal').prop('disabled',true)
                    // $('#tanggal_akhir').prop('disabled',true)
                    // Activate button cetak
                    $('#btn_cetak').prop('disabled',false)
                    // Activate button baru
                    $('#btn_baru').prop('disabled',false)
                    // Activate button Ubah
                    $('#btn_ubah').prop('disabled',false)
                    // Set input hidden payrollID
                    $('#payrollID').val(data.data.payrollInternLastID)
                    // Set input cari
                    $('#cari').val(data.data.payrollInternLastID)

                    // Set action to update
                    $('#action').val('update')

                    // set Tanggal to disabled
                    $('#tanggal_awal').prop('disabled',true)
                    $('#tanggal_akhir').prop('disabled',true)

                    // Set items input to disable
                    $('.employeeSW').prop('disabled',true)
                }
            })
        }

        // Tanggal Onchange
        // This function will executed if value from tanggal_awal or tanggal_akhir changed
        function tanggalChange(){
            let IDEmployees = $('.idEmployee')
            let startDate = $('#tanggal_awal').val()
            let endDate = $('#tanggal_akhir').val()

            // Check if startDate and endDate is not blank
            if (startDate === "" || endDate === "") {
                return;
            }

            // Check if have IDEmployee
            if (IDEmployees.length === 0){
                return;
            }
            
            // Check if any blank on IDEmployee
            let cekBlank = false
            IDEmployees.map(function () {
                if ($(this).text() === ''){
                    cekBlank = true
                    return false;
                }
            }) 
            if(cekBlank == true){
                return false;
            }

            IDEmployees = IDEmployees.map(function () {return $(this).text()}).get()
            // Loop IDEmployees for getting their data
            IDEmployees.forEach(function (value, i) {
                // Setup Payload
                let data = {EmployeeSW:value, startDate:startDate, endDate:endDate}

                // Setup CSRF Token
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                $.ajax({
                    type: "POST",
                    url: "/Absensi/Gaji/Magang/getemployeeandsallary",
                    data:data,
                    dataType: 'json',
                    beforeSend: function () {
                        $(".preloader").show();  
                    },
                    complete: function () {
                        $(".preloader").fadeOut(); 
                    },
                    success: function(data) {
                        let RowNumber = i+1
                        // Set Karyawan Name
                        $('#employeeSW_'+RowNumber).val(data.data.employee.NAME)
                        // Set Karyawan ID
                        $('#idEmployee_'+RowNumber).text(data.data.employee.ID)
                        // set Total Kerja
                        $('#totalKerja_'+RowNumber).text(data.data.totalKerja)
                        // set Total Sallary
                        $('#totalSallary_'+RowNumber).text(toRupiah(data.data.totalSallary, {floatingPoint: 0}))

                    }
                })
            });
        }

        // KlikCetak
        function klikCetak() {
            let payrollInternID = $('#payrollID').val()
            if (payrollInternID !== ''){
                window.open('/Absensi/Gaji/Magang/cetak?payrollid='+payrollInternID, '_blank');
            }else{
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Payroll belum terpilih',
                })
                return
            }
        }

        // Function for btn_batal clicked
        function klikBatal() {
            window.location.reload();
        }

        // Function DetailAbsensi (Getting Sallary/absensi detail)
        function detailSallary(RowNumber){

            // Get idEmployee
            let idEmployee = $('#idEmployee_'+RowNumber).text()

            // Check if idEmployee is not blank and nama karyawan is not blank
            if (idEmployee === "" || $('#employeeSW_'+RowNumber).val() === ""){
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: "Employee can't be blank",
                })
                return;
            }

            // Get idEmployee from specific input row.
            let id = idEmployee
            let startDate = $("#tanggal_awal").val()
            let endDate = $("#tanggal_akhir").val()
            // Change Modal properties
            $("#jodulmodal1").html('Detail Absensi');
            $('#modalformat').attr('class', 'modal-dialog modal-fullscreen');
            $("#simpan1").addClass('d-none');

            // Hit backend for getting detail sallary/absensi
            $.get('/Absensi/Gaji/Magang/getsallary?idEmployee='+id+"&startDate="+startDate+"&endDate="+endDate, function(data) {
                // Showing modal
                $("#modal1").html(data);
                $('#modalinfo').modal('show');
            });
        }

        // Klik Lihat/Search function
        function klikLihat(){
            // Get cari Data
            let cari = $('#cari').val()
            if (cari === ''){
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: "Cari can't be blank",
                })
                return;
            }
            // Make requests
            $.ajax({
                type: "GET",
                url: "/Absensi/Gaji/Magang/searchpayroll?payrollID="+cari,
                dataType: 'json',
                beforeSend: function () {
                    $(".preloader").show();  
                },
                complete: function () {
                    $(".preloader").fadeOut(); 
                },
                success: function(data) {
                    // Set Hidden payrollID value
                    $('#payrollID').val(data.data.payrollID)
                    // Set button ubah to enable
                    $('#btn_ubah').prop('disabled',false)
                    // Set button cetak to enable
                    $('#btn_cetak').prop('disabled',false)
                    // Set tanggal
                    $('#tanggal_awal').prop('disabled',true)
                    $('#tanggal_awal').val(data.data.startDate)
                    $('#tanggal_akhir').prop('disabled',true)
                    $('#tanggal_akhir').val(data.data.endDate)

                    // set button batal to disable
                    $('#btn_batal').prop('disabled',true)
                    // set button simpan to disable
                    $('#btn_simpan').prop('disabled',true)
                    // set button baru to enable
                    $('#btn_baru').prop('disabled',false)
                    

                    // Clear Table
                    $("#tabel1  tbody").empty()

                    // Loop data and add it to table
                    let datapayroll = data.data.data
                    datapayroll.forEach(function (value, i) {
                        let number = i+1;
                        let trStart = "<tr id='Row_"+number+"'>"
                        let trEnd = "</tr>"
                        let no = '<td><input type="text" class="form-control form-control-sm fs-6 w-100 text-center" name="no" readonly value="'+number+'"></td>'
                        let inputEmployeeSW = '<td><input disabled="true" autocomplete="off" type="text" class="form-control form-control-sm fs-6 w-100 text-center employeeSW" onChange="getEmployeeAndSallary(this.value,'+number+')" name="idEmployee" id="employeeSW_'+number+'"  value="'+value.Description+'"</td>'
                        let idEmployee = '<td class="m-0 p-0 "> <span class="idEmployee" id="idEmployee_'+number+'">'+value.Employee+'</span> </td>'
                        let totalKerja = '<td class="m-0 p-0 "> <span id="totalKerja_'+number+'">'+value.TotalKerja+'</span> </td>'
                        let totalSallary = '<td class="m-0 p-0 "> <span id="totalSallary_'+number+'">'+toRupiah(value.TotalSallary,{floatingPoint: 0})+'</span> </td>'
                        let detailButton = '<td class="m-0 p-0"> <button class="btn btn-primary" onclick="detailSallary('+number+')">Detail</button></td>'
                        let buttonNewRow = '<td class="m-0 p-0 text-center"> <button class="btn btn-primary btn-sm btnnewrow" onclick="newRow()">+</button> <button class="btn btn-warning btn-sm btnremoverow" onclick="removeRow()">-</button> </td>'
                        let finalItem = ""
                        let rowitem = finalItem.concat(trStart, no, inputEmployeeSW, idEmployee, totalKerja, totalSallary, detailButton, buttonNewRow, trEnd)
                        $("#tabel1 > tbody").append(rowitem);
                    })
                    // set button newrow and removerow to disable
                    $('.btnnewrow').prop('disabled',true)
                    $('.btnremoverow').prop('disabled',true)
                },
                error: function(xhr, textStatus, errorThrown){
                    let todayDate = new Date().toISOString().slice(0, 10);
                    // Clear Table
                    $("#tabel1  tbody").empty()
                    // Set input and button
                    $('#cari').val("")
                    // set tanggal
                    $('#tanggal_awal').val(todayDate)
                    $('#tanggal_awal').prop('disabled',true)
                    $('#tanggal_akhir').val(todayDate)
                    $('#tanggal_akhir').prop('disabled',true)
                    // Set button
                    $('#btn_cetak').prop('disabled',true)
                    $('#btn_simpan').prop('disabled',true)
                    $('#btn_batal').prop('disabled',true)
                    $('#btn_ubah').prop('disabled',true)
                    $('#btn_baru').prop('disabled',false)
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: xhr.responseJSON.message
                    })
                    return;
                }
            })
        }

        $('#cari').keydown(function(event) {
            let keyCode = event.keyCode || event.which;
            if (keyCode === 9 ||keyCode === 13) {
                klikLihat()
            }
        })

    </script>
    

@endsection
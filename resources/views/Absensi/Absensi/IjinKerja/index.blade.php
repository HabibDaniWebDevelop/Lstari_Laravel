<?php $title = 'Ijin Kerja'; ?>
@extends('layouts.backend-Theme-3.app')
@section('title', '$title')

@section('Dashboard')
    <h2 class="m-0">{{ $title }}</h2>
    <ol class="breadcrumb sm-2">
        <li class="breadcrumb-item"><a href="/">Home </a></li>
        <li class="breadcrumb-item">Absensi </li>
        <li class="breadcrumb-item">Absensi </li>
        <li class="breadcrumb-item active">Ijin Kerja </li>
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

                @include('Absensi.Absensi.IjinKerja.data')
                
            </div>
        </div>
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
            // Getting idIjinKerja from hidden input
            let idIjinKerja = $('#idIjinKerja').val()
            // Check if idIjinKerja have value
            if (idIjinKerja != "") {
                // If idIjinKerja have value. It will reload th page
                window.location.reload()
                return;
            }
            
            // Disable button "Baru"
            $("#btn_baru").prop('disabled',true)
            // Disable button "Ubah"
            $("#btn_ubah").prop('disabled',true)
            // Enable Button "Batal dan Simpan"
            $("#btn_simpan").prop('disabled',false)
            $("#btn_batal").prop('disabled',false)
            // Disable button "Posting"
            $("#btn_posting").prop('disabled',true)
            // Enable Input
            $("#employee").prop('disabled',false)
            $("#tanggalMulai").prop('disabled',false)
            $("#tanggalSelesai").prop('disabled',false)
            $("#waktuMulai").prop('disabled',false)
            $("#waktuSelesai").prop('disabled',false)
            $("#jenisIjin").prop('disabled',false)
            $("#pemberitahuan").prop('disabled',false)
            $("#ijinSebelumnya").prop('disabled',false)
            $("#catatan").prop('disabled',false)
        }

        function klikBatal() {
            window.location.reload()
        }

        function KlikSimpan () {
            let jenisIjin = $('#jenisIjin').val()
            let action = $('#action').val()
            if (jenisIjin == 14) {
                // Check simpan 14
                let checkSimpan14 = CheckSimpan14()
                // If result is true it will be denied to record that transaction
                if (checkSimpan14 == true){
                    Swal.fire({
                        type: 'warning',
                        title: 'Perhatian!',
                        text: "Tidak Bisa Diproses, Karena Karyawan Tersebut Terlambat Pada Hari Ini.",
                        showCancelButton: false,
                        showConfirmButton: true
                    })
                    return
                }
                
                if (action == 'simpan') {
                    SimpanIjinKerja()
                } else if (action == 'ubah'){
                    UbahIjinKerja()
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: "Action Invalid",
                    })
                    return;
                }
            }else{
                if (action == 'simpan') {
                    SimpanIjinKerja()
                } else if (action == 'ubah'){
                    UbahIjinKerja()
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: "Action Invalid",
                    })
                    return;
                }
            }
        }

        function CheckSimpan14() {
            let employee = $('#idEmployee').text()
            let tanggalMulai = $('#tanggalMulai').val()
            let data = {
                employee:employee,
                tanggalMulai:tanggalMulai
            }
            $.ajax({
                type: "GET",
                url: "/Absensi/Absensi/IjinKerja/getkurang",
                data:data,
                dataType: 'json',
                beforeSend: function () {
                    $(".preloader").show();  
                },
                complete: function () {
                    $(".preloader").fadeOut(); 
                },
                success: function(data) {
                    return data.success;
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

        function SimpanIjinKerja() {
            let idEmployee = $('#idEmployee').text()
            let rank = $('#Rank').text()
            let tanggalMulai = $('#tanggalMulai').val()
            let tanggalSelesai = $('#tanggalSelesai').val()
            let waktuMulai = $('#waktuMulai').val()
            let waktuSelesai = $('#waktuSelesai').val()
            let jenisIjin = $('#jenisIjin').val()
            let pemberitahuan = $("#pemberitahuan").is(':checked') ? "Y" : "N"
            let ijinSebelumnya = $("#ijinSebelumnya").is(':checked') ? "Y" : "N"
            let catatan = $('#catatan').val()
            
            if (idEmployee == "") {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: "Karyawan Belum Terpilih",
                })
                return
            }
            
            if (rank == "") {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: "rank Tidak Boleh Kosong",
                })
                return
            }

            if (tanggalMulai == "") {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: "tanggalMulai Tidak Boleh Kosong",
                })
                return
            }

            if (tanggalSelesai == "") {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: "tanggalSelesai Tidak Boleh Kosong",
                })
                return
            }

            if (waktuMulai == "") {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: "waktuMulai Tidak Boleh Kosong",
                })
                return
            }

            if (waktuSelesai == "") {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: "waktuSelesai Tidak Boleh Kosong",
                })
                return
            }

            let data = {
                idEmployee:idEmployee,
                rank:rank,
                tanggalMulai:tanggalMulai, 
                tanggalSelesai:tanggalSelesai,
                waktuMulai:waktuMulai,
                waktuSelesai:waktuSelesai,
                jenisIjin:jenisIjin, 
                pemberitahuan:pemberitahuan,
                ijinSebelumnya:ijinSebelumnya,
                catatan:catatan
            }
            // Setup CSRF TOKEN
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                type: "POST",
                url: "/Absensi/Absensi/IjinKerja",
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
                        title: 'Tersimpan!',
                        text: "Data Berhasil Tersimpan.",
                        timer: 2000,
                        showCancelButton: false,
                        showConfirmButton: false
                    });
                    $('#idIjinKerja').val(data.data.id)
                    $('#cari').val(data.data.id)
                    $('#action').val("ubah")
                    $('#postingStatus').val(data.data.postingStatus)

                    // Set Table Transaksi
                    $("#tabelTransaction  tbody").empty()
                    data.data.transaction.forEach(function (value, i) {
                        let no = i+1
                        let urut  = "<td>"+no+"</td>"
                        let tanggal  = "<td>"+value.tgl+"</td>"
                        let mulai = "<td>"+value.TimeFrom+"</td>"
                        let akhir = "<td>"+value.TimeTo+"</td>"
                        let absent = "<td>"+value.Absent+"</td>"
                        let time1 = "<td>"+value.ActualFrom+"</td>"
                        let time2 = "<td>"+value.ActualTo+"</td>"
                        let finalItem = ""
                        let rowitem = finalItem.concat("<tr>", urut, tanggal, mulai, akhir, absent, time1, time2, "</tr>")
                        $("#tabelTransaction > tbody").append(rowitem);
                    });

                    // Set Table History
                    $("#tabelHistory  tbody").empty()
                    data.data.history.forEach(function (value, i) {
                        let ID  = "<td>"+value.ID+"</td>"
                        let deskripsi  = "<td>"+value.Description+"</td>"
                        let tanggal = "<td>"+value.tgl+"</td>"
                        let mulai = "<td>"+value.TimeFrom+"</td>"
                        let akhir = "<td>"+value.TimeTo+"</td>"
                        let finalItem = ""
                        let rowitem = finalItem.concat("<tr>", ID, deskripsi, tanggal, mulai, akhir, "</tr>")
                        $("#tabelHistory > tbody").append(rowitem);
                    });

                    

                    // Set Button
                    $("#btn_baru").prop('disabled',false)
                    $("#btn_ubah").prop('disabled',false)
                    $("#btn_posting").prop('disabled',false)
                    $("#btn_simpan").prop('disabled',true)
                    $("#btn_batal").prop('disabled',true)

                    // Enable Input
                    $("#employee").prop('disabled',true)
                    $("#tanggalMulai").prop('disabled',true)
                    $("#tanggalSelesai").prop('disabled',true)
                    $("#waktuMulai").prop('disabled',true)
                    $("#waktuSelesai").prop('disabled',true)
                    $("#jenisIjin").prop('disabled',true)
                    $("#pemberitahuan").prop('disabled',true)
                    $("#ijinSebelumnya").prop('disabled',true)
                    $("#catatan").prop('disabled',true)
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

        function UbahIjinKerja() {
            let idAbsent = $('#idIjinKerja').val()
            let idEmployee = $('#idEmployee').text()
            let rank = $('#Rank').text()
            let tanggalMulai = $('#tanggalMulai').val()
            let tanggalSelesai = $('#tanggalSelesai').val()
            let waktuMulai = $('#waktuMulai').val()
            let waktuSelesai = $('#waktuSelesai').val()
            let jenisIjin = $('#jenisIjin').val()
            let pemberitahuan = $("#pemberitahuan").is(':checked') ? "Y" : "N"
            let ijinSebelumnya = $("#ijinSebelumnya").is(':checked') ? "Y" : "N"
            let catatan = $('#catatan').val()
            
            if (idAbsent == "") {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: "Karyawan Belum Terpilih",
                })
                return
            }
            
            if (idEmployee == "") {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: "Karyawan Belum Terpilih",
                })
                return
            }
            
            if (rank == "") {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: "rank Tidak Boleh Kosong",
                })
                return
            }

            if (tanggalMulai == "") {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: "tanggalMulai Tidak Boleh Kosong",
                })
                return
            }

            if (tanggalSelesai == "") {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: "tanggalSelesai Tidak Boleh Kosong",
                })
                return
            }

            if (waktuMulai == "") {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: "waktuMulai Tidak Boleh Kosong",
                })
                return
            }

            if (waktuSelesai == "") {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: "waktuSelesai Tidak Boleh Kosong",
                })
                return
            }

            let data = {
                idAbsent:idAbsent,
                idEmployee:idEmployee,
                rank:rank,
                tanggalMulai:tanggalMulai, 
                tanggalSelesai:tanggalSelesai,
                waktuMulai:waktuMulai,
                waktuSelesai:waktuSelesai,
                jenisIjin:jenisIjin, 
                pemberitahuan:pemberitahuan,
                ijinSebelumnya:ijinSebelumnya,
                catatan:catatan
            }
            // Setup CSRF TOKEN
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                type: "PUT",
                url: "/Absensi/Absensi/IjinKerja",
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
                        title: 'Terupdate!',
                        text: "Data Berhasil Terupdate.",
                        timer: 2000,
                        showCancelButton: false,
                        showConfirmButton: false
                    });

                    // Set Table Transaksi
                    $("#tabelTransaction  tbody").empty()
                    data.data.transaction.forEach(function (value, i) {
                        let no = i+1
                        let urut  = "<td>"+no+"</td>"
                        let tanggal  = "<td>"+value.tgl+"</td>"
                        let mulai = "<td>"+value.TimeFrom+"</td>"
                        let akhir = "<td>"+value.TimeTo+"</td>"
                        let absent = "<td>"+value.Absent+"</td>"
                        let time1 = "<td>"+value.ActualFrom+"</td>"
                        let time2 = "<td>"+value.ActualTo+"</td>"
                        let finalItem = ""
                        let rowitem = finalItem.concat("<tr>", urut, tanggal, mulai, akhir, absent, time1, time2, "</tr>")
                        $("#tabelTransaction > tbody").append(rowitem);
                    });

                    // Set Table History
                    $("#tabelHistory  tbody").empty()
                    data.data.history.forEach(function (value, i) {
                        let ID  = "<td>"+value.ID+"</td>"
                        let deskripsi  = "<td>"+value.Description+"</td>"
                        let tanggal = "<td>"+value.tgl+"</td>"
                        let mulai = "<td>"+value.TimeFrom+"</td>"
                        let akhir = "<td>"+value.TimeTo+"</td>"
                        let finalItem = ""
                        let rowitem = finalItem.concat("<tr>", ID, deskripsi, tanggal, mulai, akhir, "</tr>")
                        $("#tabelHistory > tbody").append(rowitem);
                    });

                    

                    // Set Button
                    $("#btn_baru").prop('disabled',false)
                    $("#btn_ubah").prop('disabled',false)
                    $("#btn_posting").prop('disabled',false)
                    $("#btn_simpan").prop('disabled',true)
                    $("#btn_batal").prop('disabled',true)

                    // Enable Input
                    $("#employee").prop('disabled',true)
                    $("#tanggalMulai").prop('disabled',true)
                    $("#tanggalSelesai").prop('disabled',true)
                    $("#waktuMulai").prop('disabled',true)
                    $("#waktuSelesai").prop('disabled',true)
                    $("#jenisIjin").prop('disabled',true)
                    $("#pemberitahuan").prop('disabled',true)
                    $("#ijinSebelumnya").prop('disabled',true)
                    $("#catatan").prop('disabled',true)
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

        function KlikUbah () {
            // Disable button "Baru"
            $("#btn_baru").prop('disabled',true)
            $("#btn_ubah").prop('disabled',true)
            $("#btn_posting").prop('disabled',true)
            $("#btn_simpan").prop('disabled',false)
            $("#btn_batal").prop('disabled',false)

            // Enable Input
            $("#employee").prop('disabled',false)
            $("#tanggalMulai").prop('disabled',false)
            $("#tanggalSelesai").prop('disabled',false)
            $("#waktuMulai").prop('disabled',false)
            $("#waktuSelesai").prop('disabled',false)
            $("#jenisIjin").prop('disabled',false)
            $("#pemberitahuan").prop('disabled',false)
            $("#ijinSebelumnya").prop('disabled',false)
            $("#catatan").prop('disabled',false)
        }

        function KlikPosting() {
            let idIjinKerja = $('#idIjinKerja').val()
            let postingStatus = $('#postingStatus').val()

            // Check ijin kerja
            if (idIjinKerja == "") {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: "Ijin Kerja belum terpilih",
                })
                return;
            }

            // Check postingStatus
            if (postingStatus != "A") {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: "Ijin Kerja Sudah Diposting",
                })
                return;
            }

            let data = {
                idAbsent:idIjinKerja
            }

            $.ajax({
                type: "GET",
                url: "/Absensi/Absensi/IjinKerja/Posting",
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
                        title: 'Terposting!',
                        text: "Data Berhasil Terposting.",
                        timer: 2000,
                        showCancelButton: false,
                        showConfirmButton: false
                    });
                    $('#idIjinKerja').val(data.data.id)
                    $('#cari').val(data.data.id)
                    $('#postingStatus').val(data.data.postingStatus)

                    // Set Button
                    $("#btn_baru").prop('disabled',false)
                    $("#btn_ubah").prop('disabled',true)
                    $("#btn_posting").prop('disabled',true)
                    $("#btn_simpan").prop('disabled',true)
                    $("#btn_batal").prop('disabled',true)
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

        function KlikCari() {
            let cari = $('#cari').val()
            if (cari == ""){
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: "Kolom Cari Tidak Boleh Kosong",
                })
                return;
            }
            let data = {keyword:cari}

            $.ajax({
                type: "GET",
                url: "/Absensi/Absensi/IjinKerja/Search",
                data:data,
                dataType: 'json',
                beforeSend: function () {
                    $(".preloader").show();  
                },
                complete: function () {
                    $(".preloader").fadeOut(); 
                },
                success: function(data) {
                    $('#employee').val(data.data.Karyawan)
                    $('#idEmployee').text(data.data.Employee)
                    $('#Rank').text(data.data.Rank)
                    $('#Department').text('('+data.data.Bagian+')')
                    $('#tanggalMulai').val(data.data.DateStart)
                    $('#tanggalSelesai').val(data.data.DateEnd)
                    $('#waktuMulai').val(data.data.TimeStart)
                    $('#waktuSelesai').val(data.data.TimeEnd)
                    $("#jenisIjin").val(data.data.Type).change();
                    if (data.data.Notification == "Y") {
                        $("#pemberitahuan").prop('checked',true)   
                    }else {
                        $("#pemberitahuan").prop('checked',false)   
                    }
                    if (data.data.InformBefore == "Y") {
                        $("#ijinSebelumnya").prop('checked',true)   
                    }else {
                        $("#ijinSebelumnya").prop('checked',false)   
                    }
                    $('#catatan').val(data.data.Reason)

                    $('#idIjinKerja').val(data.data.ID)
                    $('#postingStatus').val(data.data.Active)
                    $('#action').val('ubah')

                    // Set Table Transaksi
                    $("#tabelTransaction  tbody").empty()
                    data.data.transaction.forEach(function (value, i) {
                        let no = i+1
                        let urut  = "<td>"+no+"</td>"
                        let tanggal  = "<td>"+value.tgl+"</td>"
                        let mulai = "<td>"+value.TimeFrom+"</td>"
                        let akhir = "<td>"+value.TimeTo+"</td>"
                        let absent = "<td>"+value.Absent+"</td>"
                        let time1 = "<td>"+value.ActualFrom+"</td>"
                        let time2 = "<td>"+value.ActualTo+"</td>"
                        let finalItem = ""
                        let rowitem = finalItem.concat("<tr>", urut, tanggal, mulai, akhir, absent, time1, time2, "</tr>")
                        $("#tabelTransaction > tbody").append(rowitem);
                    });

                    // Set Table History
                    $("#tabelHistory  tbody").empty()
                    data.data.history.forEach(function (value, i) {
                        let ID  = "<td>"+value.ID+"</td>"
                        let deskripsi  = "<td>"+value.Description+"</td>"
                        let tanggal = "<td>"+value.tgl+"</td>"
                        let mulai = "<td>"+value.TimeFrom+"</td>"
                        let akhir = "<td>"+value.TimeTo+"</td>"
                        let finalItem = ""
                        let rowitem = finalItem.concat("<tr>", ID, deskripsi, tanggal, mulai, akhir, "</tr>")
                        $("#tabelHistory > tbody").append(rowitem);
                    });


                    // Disable Input
                    $("#employee").prop('disabled',true)
                    $("#tanggalMulai").prop('disabled',true)
                    $("#tanggalSelesai").prop('disabled',true)
                    $("#waktuMulai").prop('disabled',true)
                    $("#waktuSelesai").prop('disabled',true)
                    $("#jenisIjin").prop('disabled',true)
                    $("#pemberitahuan").prop('disabled',true)
                    $("#ijinSebelumnya").prop('disabled',true)
                    $("#catatan").prop('disabled',true)

                    // Set button
                    if (data.data.Active == "P") {
                        $("#btn_baru").prop('disabled',false)
                        $("#btn_posting").prop('disabled',true)
                        $("#btn_ubah").prop('disabled',true)
                        $("#btn_simpan").prop('disabled',true)
                        $("#btn_batal").prop('disabled',true)
                    } else {
                        $("#btn_baru").prop('disabled',false)
                        $("#btn_posting").prop('disabled',false)
                        $("#btn_ubah").prop('disabled',false)
                        $("#btn_simpan").prop('disabled',true)
                        $("#btn_batal").prop('disabled',true)
                    }
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

        function searchKaryawan() {
            let employee = $('#employee').val()
            // Check if value is blank
            if (employee == "") {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: "Karyawan tidak boleh kosong",
                })
                return
            }
            let data = {employeeSW:employee}
            $.ajax({
                type: "GET",
                url: "/Absensi/Absensi/IjinKerja/employee",
                data:data,
                dataType: 'json',
                beforeSend: function () {
                    $(".preloader").show();  
                },
                complete: function () {
                    $(".preloader").fadeOut(); 
                },
                success: function(data) {
                    // Set Employee
                    $('#employee').val(data.data.NAME)
                    $('#idEmployee').text(data.data.ID)
                    $('#Rank').text(data.data.Rank)
                    $('#Department').text('('+data.data.Bagian+')')

                    // Set Waktu Mulai & Selesai
                    if (data.data.Rank === 'Operator' || data.data.Rank === 'Driver' || data.data.Rank === 'Cleaning Service' || data.data.Rank === 'Security' || data.data.Rank === 'Kepala Bagian') {
                        if (data.data.ID == 1195 || data.data.ID == 726 || data.data.ID == 639) {
                            $("#waktuMulai").val('08:00:00');
                            $("#waktuSelesai").val('17:00:00');
                        } else {
                            $("#waktuMulai").val('08:00:00');
                            $("#waktuSelesai").val('16:30:00');
                        }
                    } else {
                        $("#waktuMulai").val('08:00:00');
                        $("#waktuSelesai").val('17:00:00');
                    }

                    // Set Table History
                    $("#tabelHistory  tbody").empty()
                    data.data.history.forEach(function (value, i) {
                        let ID  = "<td>"+value.ID+"</td>"
                        let deskripsi  = "<td>"+value.Description+"</td>"
                        let tanggal = "<td>"+value.tgl+"</td>"
                        let mulai = "<td>"+value.TimeFrom+"</td>"
                        let akhir = "<td>"+value.TimeTo+"</td>"
                        let finalItem = ""
                        let rowitem = finalItem.concat("<tr>", ID, deskripsi, tanggal, mulai, akhir, "</tr>")
                        $("#tabelHistory > tbody").append(rowitem);
                    });
                },
                error: function(xhr){
                    // It will executed if response from backend is error
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: xhr.responseJSON.message,
                    })
                    $('#employee').val("")
                    $('#idEmployee').text("")
                    $('#Rank').text("")
                    return;
                }
            })
        }

        function get_tglakhir() {
            let tanggal = $('#tanggalSelesai').val()
            // Check if value is blank
            if (tanggal == "") {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: "Tanggal Selesai tidak boleh kosong",
                })
                return
            }
            let data = {tanggal:tanggal}
            $.ajax({
                type: "GET",
                url: "/Absensi/Absensi/IjinKerja/gettanggal",
                data:data,
                dataType: 'json',
                beforeSend: function () {
                    $(".preloader").show();  
                },
                complete: function () {
                    $(".preloader").fadeOut(); 
                },
                success: function(data) {
                    if (data.data.Day == "Sabtu") {
                        $("#waktuSelesai").val('14:00:00');
                        return
                    }
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

    </script>
    

@endsection
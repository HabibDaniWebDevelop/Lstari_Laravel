<?php $title = 'NTHKO'; ?>
@extends('layouts.backend-Theme-3.app')
@section('title', '$title')

@section('Dashboard')
    <h2 class="m-0">{{ $title }}</h2>
    <ol class="breadcrumb sm-2">
        <li class="breadcrumb-item"><a href="/">Home </a></li>
        <li class="breadcrumb-item">Produksi </li>
        <li class="breadcrumb-item">Pelaporan Produksi </li>
        <li class="breadcrumb-item active">NTHKO </li>
    </ol>
@endsection

@section('css')
    <link rel="stylesheet" href="{!! asset('assets/sneatV1/assets/vendor/libs/BootstrapSelect/bootstrap-select.min.css') !!}">
    <link rel="stylesheet" href="{!! asset('assets/almas/select2.min.css') !!}">
    <style>
        .bootstrap-select.form-control.input-group-btn {
            z-index: auto;
        }

        .container-fluid{
            padding: 0px !important;
            padding-left: 10px !important;
            padding-right: 15px !important;
        }
    </style>
@endsection

@section('container')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                @include('Produksi.PelaporanProduksi.NTHKO.data')
            </div>
        </div>
    </div>
    <div class="dropdown-menu dropdown-menu-end animate" id="klikMenu" style="display:none">
        <div class="text-center fw-bold mb-2" id="klikJudul"></div>
        <a class="dropdown-item" onclick="klikEdit()"><span class="tf-icons bx bx-edit"></span>&nbsp; Edit</a>
        <a class="dropdown-item" onclick="klikCetak()"><span class="tf-icons bx bx-printer"></span>&nbsp; Cetak</a>
        <a class="dropdown-item" onclick="klikInfo()"><span class="tf-icons bx bx-list-ul"></span>&nbsp; Info</a>
    </div>
@endsection

@section('script')

    @include('layouts.backend-Theme-3.DataTabelButton')
    @include('layouts.backend-Theme-3.timbangan')

    <script src="{!! asset('assets/almas/sum().js') !!}"></script>
    <script src="{!! asset('assets/almas/websocket-printer.js') !!}"></script>
    <script src="{!! asset('assets/sneatV1/assets/vendor/libs/BootstrapSelect/bootstrap-select.min.js') !!}"></script>
    <script src="{!! asset('assets/almas/select2.min.js') !!}"></script>

    <script>
    // === Start Example Functions ===
    function klikList() {
        $('.klik').css('background-color', 'white');

        if ($("#klikMenu").css('display') == 'block') {
            $("#klikMenu").hide();
        } else {
            var top = e.pageY + 15;
            var left = e.pageX - 100;
            window.idfiell = $(this).attr('id');
            var id2 = $(this).attr('id2');
            $("#klikJudul").html(id2);

            $(this).css('background-color', '#f4f5f7');
            $("#klikMenu").css({
                display: "block",
                top: top,
                left: left
            });
        }
        return false; //blocks default Webbrowser right click menu
    }
    // Show Newest List of NTHKO
    function nthkoList() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            url: '/Produksi/PelaporanProduksi/NTHKO/nthkoList',
            beforeSend: function() {
                openModal();
            },
            dataType: 'json',
            type: 'GET',
            success: function(data) {
                $("#datalistNTHKO").load(" #datalistNTHKO > *");
            },
            complete: function() {
                closeModal();
            },
            error: function(xhr) {
                let message = xhr?.responseJSON?.message == undefined ? "Server Error" : xhr?.responseJSON?.message
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: message,
                })
                return;
            }
        });
    }
    // === End Example Functions ===



    // OK - Initiate DirectPrinting Library
    var printService = new WebSocketPrinter();
    function printPDF(data) {
        printService.submit({
            'type': 'BARCODE',
            'url': 'http://192.168.3.100/produksi/ProduksiPDF/' + data + '.pdf'
        });
    }

    // OK - Modal Loading Open
    function openModal() {
        $(".preloader").fadeIn(300);
    }

    // OK - Modal Loading Close
    function closeModal() {
        $(".preloader").fadeOut(300);
    }

    // OK - Reload the Page
    function klikBatal() {
        location.reload();
    }

    // OK - Clear Search Field
    function klikClear() {
        document.getElementById("idcari").value = '';
        document.getElementById("idcari").focus();
    }

    // OK - Show Image
    function tampilGambar(idgambar) {
        var idg = idgambar;

        if (idg != '') {

            if(idg == 'undefined'){
                var pic = 'http://192.168.3.100:8383/image2/NO-IMAGE.jpg';
                document.getElementById('showgambar').src = pic;
                document.getElementById('showgambar').style.display = 'inline-block';
                document.getElementById('showgambar').style.width = '230px';
                document.getElementById('showgambar').style.height = '230px';
            }else{
                var pic = 'http://192.168.3.100:8383/image2/' + idg + '.jpg';
                document.getElementById('showgambar').src = pic;
                document.getElementById('showgambar').style.display = 'inline-block';
                document.getElementById('showgambar').style.width = '230px';
                document.getElementById('showgambar').style.height = '230px';
            }

        } else {
            var pic = 'http://192.168.3.100:8383/image2/NO-IMAGE.jpg';
            document.getElementById('showgambar').src = pic;
            document.getElementById('showgambar').style.display = 'inline-block';
            document.getElementById('showgambar').style.width = '230px';
            document.getElementById('showgambar').style.height = '230px';
        }
    }

    // OK - Handler Default Max 100 Input
    function handlerItem(event) {
        var $this = $(event.target);
        var index = parseFloat($this.attr('data-index'));
        var id = String(index)[0];

        var rowindex = parseFloat($this.attr('row-index'));
        var rowLength = ($('#tampiltabel tbody tr').length);
        var posisiIndex = index.toString().substr(-2);
  
        if(posisiIndex == 25){
            document.getElementById("barcodeunit").value = '';
            document.getElementById("barcodeunit").focus();
        }else{
            if (event.keyCode === 39) { //RIGHT
                $('[data-index="' + (index + 1).toString() + '"]').focus().select();
                event.preventDefault();
            }
            if (event.keyCode === 37) { //LEFT
                $('[data-index="' + (index - 1).toString() + '"]').focus().select();
                event.preventDefault();
            }
            if (event.keyCode === 13) { //ENTER
                $('[data-index="' + (index + 1).toString() + '"]').focus().select();
                event.preventDefault();
            }
            if (event.keyCode === 38) { //UP
                $('[data-index="' + (index - 100).toString() + '"]').focus().select();
                event.preventDefault();
            }
            if (event.keyCode === 40) { //DOWN
                if(rowindex == rowLength){
                 
                    klikAddRow();
                             
                }else{
                    $('[data-index="' + (index + 100).toString() + '"]').focus().select();
                    event.preventDefault();
                } 
            }
            if (event.ctrlKey && event.keyCode === 46) { //DELETE
                remove(rowindex);
            }
            // addEventListener('keydown', (e) => { // CTRL+DELETE
            //     if (e.ctrlKey && e.key === 'Delete') {
            //         remove(rowindex);
            //     }
            // });
        }


       
    }

    // validasi data sebelum tambah baru
    function validasi(){

        let rowCount = $('#tampiltabel tbody tr').length;
        let qty = $('[data-index="' + rowCount + '06"]').val();
        let weight = $('[data-index="' + rowCount + '07"]').val();
        let repqty = $('[data-index="' + rowCount + '08"]').val();
        let repweight = $('[data-index="' + rowCount + '09"]').val();
        let ssqty = $('[data-index="' + rowCount + '10"]').val();
        let ssweight = $('[data-index="' + rowCount + '11"]').val();
        var product = $("#Product" + rowCount).val(); //return value as string
        var productInt = Number(product); //convert string to integer

        console.log(product, rowCount, qty, weight, repqty, repweight, ssqty, ssweight);

        const reparasi = [254, 99, 98, 2403];
        const rusak = [255, 2561, 2572];
        const baturusak = [462];

        // console.log(reparasi);
        // console.log(rusak);
        // console.log(baturusak);
        // console.log(product);

        if(reparasi.includes(productInt)){
            if (repqty == '' || repweight == '') {
                Swal.fire({
                    position: 'center',
                    icon: 'error',
                    title: 'Data Rep Belum Diisi',
                    showConfirmButton: false,
                    timer: 1800
                });
                return;
            }
        }
        
        if(rusak.includes(productInt)){
            if (ssqty == '' || ssweight == '') {
                Swal.fire({
                    position: 'center',
                    icon: 'error',
                    title: 'Data SS Belum Diisi',
                    showConfirmButton: false,
                    timer: 1800
                });
                return;
            }
        }
        
        if(baturusak.includes(productInt)){
            if (ssweight == '') {
                Swal.fire({
                    position: 'center',
                    icon: 'error',
                    title: 'Data Batu SS Belum Diisi',
                    showConfirmButton: false,
                    timer: 1800
                });
                return;
            }
        }
        
        if(!reparasi.includes(productInt) || !rusak.includes(productInt) || !baturusak.includes(productInt)){
            if(qty == '' || weight == ''){
                Swal.fire({
                    position: 'center',
                    icon: 'error',
                    title: 'Data OK Belum Diisi',
                    showConfirmButton: false,
                    timer: 1800
                });
                return;
            }
        }

    }

    // function klikTimbangAlmas(id) {
    //     sendSerialLine();
    //     $('#selscale').val(id);
    // }

    function klikTimbangRun(id,baris){
        kliktimbang(id);
 
        var selscale = $("#selscale").val();
        console.log(selscale);
        if(selscale.includes("Scrap")){
            document.getElementById("ScrapWeight"+baris).focus();
        }else if(selscale.includes("Repair")){
            document.getElementById("RepairWeight"+baris).focus();
        }else{
            document.getElementById("Weight"+baris).focus();
        }

        refresh_sum_weight(baris);
    }

    function klikTimbangRunFix(id,baris){
        kliktimbang(id);
        refresh_sum_weight(baris);
    }


    function klikTimbangRunOK(id,baris){
        kliktimbang(id);
        document.getElementById("Weight"+baris).focus();
        refresh_sum_weight(baris);
    }

    function klikTimbangRunRep(id,baris){
        kliktimbang(id);
        document.getElementById("RepairWeight"+baris).focus();
        refresh_sum_weight(baris);
    }

    function klikTimbangRunSS(id,baris){
        kliktimbang(id);
        document.getElementById("ScrapWeight"+baris).focus();
        refresh_sum_weight(baris);
    }

    // OK - Copy BarcodeNote to Note
    function notecopy(no) {
        var note = $("#BarcodeNote" + no).val();
        $("#Note" + no).val(note);
    }

    // OK - Copy Note to BarcodeNote
    function notecopy2(no) {
        var note = $("#Note" + no).val();
        $("#BarcodeNote" + no).val(note);
    }

    // OK - Calculate Qty Newest Value
    function refresh_sum_qty(id) {
        var baris = id - 1;
        var table = $('#tampiltabel').DataTable();

        var qtyinput1 = $(table.cell(baris, 13).node()).find('input').val();
        var qtyinput2 = $(table.cell(baris, 15).node()).find('input').val();
        var qtyinput3 = $(table.cell(baris, 17).node()).find('input').val();

        table.cell(baris, 7).data(qtyinput1);
        table.cell(baris, 9).data(qtyinput2);
        table.cell(baris, 11).data(qtyinput3);

        var qtydata1 = table.column(7).data().sum();
        var qtydata2 = table.column(9).data().sum();
        var qtydata3 = table.column(11).data().sum();

        var qtydata = qtydata1 + qtydata2 + qtydata3;

        document.getElementById("qtynthkolabel").innerHTML = String(qtydata);
        document.getElementById("qtynthko").value = qtydata;

    }

    // OK - Calculate Weight Newest Value
    function refresh_sum_weight(id) {
        var baris = id - 1;
        var table = $('#tampiltabel').DataTable();

        var weightinput1 = $(table.cell(baris, 14).node()).find('input').val();
        var weightinput2 = $(table.cell(baris, 16).node()).find('input').val();
        var weightinput3 = $(table.cell(baris, 18).node()).find('input').val();

        table.cell(baris, 8).data(weightinput1);
        table.cell(baris, 10).data(weightinput2);
        table.cell(baris, 12).data(weightinput3);

        var weightdata1 = table.column(8).data().sum();
        var weightdata2 = table.column(10).data().sum();
        var weightdata3 = table.column(12).data().sum();

        var weightdata = weightdata1 + weightdata2 + weightdata3;

        document.getElementById("weightnthkolabel").innerHTML = String(weightdata.toFixed(2));
        document.getElementById("weightnthko").value = weightdata.toFixed(2);
    }

    // Show Hidden Input of SPKO Scan
    function klikBaru() {
        if ($("#inputspko").css('display') == 'block') {
            $("#inputspko").hide();
        } else {
            document.getElementById('inputspko').style.display = 'block';
            document.getElementById("inputspko").value = '';
            document.getElementById("inputspko").focus();
        }

    }

    // Show Blank New Form - OK
    function baru(spko) {

        var explode = spko.split("-");
        var id = explode[0];

        data = {id: id};
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            url: '/Produksi/PelaporanProduksi/NTHKO/baru',
            beforeSend: function() {
                openModal();
            },
            data: data,
            dataType: 'json',
            type: 'POST',
            success: function(data) {
                if (data.status == 'SudahSusut') {
                    Swal.fire({
                        icon: "error",
                        title: "Hanya Tampil SPKO Diposting",
                        timer: 2000,
                        showCancelButton: false,
                        showConfirmButton: true
                    })
                    document.getElementById("inputspko").value = '';
                    document.getElementById("inputspko").focus();
                } else {
                    $("#tampil").html(data.html);
                    var table = $('#tampiltabel').DataTable({
                        ordering: false,
                        paging: true,
                        pageLength: 100,
                        searching: false,
                        lengthChange: false,
                        scrollX: true,
                        scroller: true,
                        // scrollY: '40vh',
                    });

                    table.columns(7).visible(false);
                    table.columns(8).visible(false);
                    table.columns(9).visible(false);
                    table.columns(10).visible(false);
                    table.columns(11).visible(false);
                    table.columns(12).visible(false);
                    // table.columns(21).visible(false);
                    // table.columns(22).visible(false);
                    // table.columns(23).visible(false);
                    document.getElementById('inputspko').style.display = 'none';

                    document.getElementById("barcodeunit").value = '';
                    document.getElementById("barcodeunit").focus();

                    $.get('/Produksi/PelaporanProduksi/NTHKO/cekProduct', function(data) {
                        var list = data.list;
                        var productList = list.join();
                        document.getElementById("productlist").value = productList;
                    });
                }

            },
            complete: function() {
                closeModal();
            },
            error: function(xhr) {
                let message = xhr?.responseJSON?.message == undefined ? "Server Error" : xhr?.responseJSON?.message
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: message,
                })
                return;
            }
        });
    }

    // OK - Show Edit Form
    function klikUbah() {
        var id = $('#idnthko').val();

        var data = {id: id};
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({
            url: '/Produksi/PelaporanProduksi/NTHKO/ubah',
            beforeSend: function() {
                openModal();
            },
            data: data,
            dataType: 'json',
            type: 'POST',
            success: function(data) {
                $("#tampil").html(data.html);
                var table = $('#tampiltabel').DataTable({
                    ordering: false,
                    paging: false,
                    pageLength: 100,
                    searching: true,
                    lengthChange: true,
                    scrollX: true,
                    scrollCollapse: true,
                });

                table.row(table).column(0).nodes().to$().attr('class','m-0 p-0');
                table.row(table).column(1).nodes().to$().attr('class','m-0 p-0');
                table.row(table).column(2).nodes().to$().attr('class','m-0 p-0');
                table.row(table).column(3).nodes().to$().attr('class','m-0 p-0');
                table.row(table).column(4).nodes().to$().attr('class','m-0 p-0');
                table.row(table).column(5).nodes().to$().attr('class','m-0 p-0');
                table.row(table).column(6).nodes().to$().attr('class','m-0 p-0');
                table.row(table).column(7).nodes().to$().attr('class','m-0 p-0');
                table.row(table).column(8).nodes().to$().attr('class','m-0 p-0');
                table.row(table).column(9).nodes().to$().attr('class','m-0 p-0');
                table.row(table).column(10).nodes().to$().attr('class','m-0 p-0');
                table.row(table).column(11).nodes().to$().attr('class','m-0 p-0');
                table.row(table).column(12).nodes().to$().attr('class','m-0 p-0');
                table.row(table).column(13).nodes().to$().attr('class','m-0 p-0');
                table.row(table).column(14).nodes().to$().attr('class','m-0 p-0');
                table.row(table).column(15).nodes().to$().attr('class','m-0 p-0');
                table.row(table).column(16).nodes().to$().attr('class','m-0 p-0');
                table.row(table).column(17).nodes().to$().attr('class','m-0 p-0');
                table.row(table).column(18).nodes().to$().attr('class','m-0 p-0');
                table.row(table).column(19).nodes().to$().attr('class','m-0 p-0');
                table.row(table).column(20).nodes().to$().attr('class','m-0 p-0');
                table.row(table).column(21).nodes().to$().attr('class','m-0 p-0');
                table.row(table).column(22).nodes().to$().attr('class','m-0 p-0');
                table.row(table).column(23).nodes().to$().attr('class','m-0 p-0');
                table.row(table).column(24).nodes().to$().attr('class','m-0 p-0');
                table.row(table).column(25).nodes().to$().attr('class','m-0 p-0');
                table.row(table).column(26).nodes().to$().attr('class','m-0 p-0');
                table.row(table).column(27).nodes().to$().attr('class','m-0 p-0');
                table.row(table).column(28).nodes().to$().attr('class','m-0 p-0');
                table.row(table).column(29).nodes().to$().attr('class','m-0 p-0');
                table.row(table).column(30).nodes().to$().attr('class','m-0 p-0');
                table.row(table).column(31).nodes().to$().attr('class','m-0 p-0');
                table.row(table).column(32).nodes().to$().attr('class','m-0 p-0');

                table.columns(7).visible(false);
                table.columns(8).visible(false);
                table.columns(9).visible(false);
                table.columns(10).visible(false);
                table.columns(11).visible(false);
                table.columns(12).visible(false);
                // table.columns(21).visible(false);
                // table.columns(22).visible(false);
                // table.columns(23).visible(false);

                document.getElementById("btnsimpan").disabled = false;

                $.get('/Produksi/PelaporanProduksi/NTHKO/cekProduct', function(data) {
                    var list = data.list;
                    var productList = list.join();
                    document.getElementById("productlist").value = productList;
                });
            },
            complete: function() {
                closeModal();
            },
            error: function(xhr) {
                let message = xhr?.responseJSON?.message == undefined ? "Server Error" : xhr?.responseJSON?.message
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: message,
                })
                return;
            }
        });
    }

    // OK - Show SPKO Content
    function klikLihat() {

        var swfreq = $('#idcari').val();
        var data = {swfreq: swfreq};

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({
            url: '/Produksi/PelaporanProduksi/NTHKO/lihat',
            beforeSend: function() {
                openModal();
            },
            data: data,
            dataType: 'json',
            type: 'POST',
            success: function(data) {
                $("#tampil").html(data.html);
                var table = $('#tampiltabel').DataTable({
                    ordering: false,
                    paging: false,
                    pageLength: 100,
                    searching: false,
                    lengthChange: false,
                    scrollX: true,
                    scroller: true,
                });

                document.getElementById("btncetak").disabled = false;
                document.getElementById("btncetakbarcode").disabled = false;
                document.getElementById("btnsimpan").disabled = true;

                if (data.postingStatus == 'A') {
                    document.getElementById("btnubah").disabled = false;
                } else {
                    document.getElementById("btnubah").disabled = true;
                }

                table.columns(6).visible(false);
                table.columns(7).visible(false);
                table.columns(8).visible(false);
                table.columns(9).visible(false);
                table.columns(10).visible(false);
                table.columns(11).visible(false);

                table.row(table).column(0).nodes().to$().attr('class','m-0 p-1');
                table.row(table).column(1).nodes().to$().attr('class','m-0 p-1');
                table.row(table).column(2).nodes().to$().attr('class','m-0 p-1');
                table.row(table).column(3).nodes().to$().attr('class','m-0 p-1');
                table.row(table).column(4).nodes().to$().attr('class','m-0 p-1');
                table.row(table).column(5).nodes().to$().attr('class','m-0 p-1');
                table.row(table).column(6).nodes().to$().attr('class','m-0 p-1');
                table.row(table).column(7).nodes().to$().attr('class','m-0 p-1');
                table.row(table).column(8).nodes().to$().attr('class','m-0 p-1');
                table.row(table).column(9).nodes().to$().attr('class','m-0 p-1');
                table.row(table).column(10).nodes().to$().attr('class','m-0 p-1');
                table.row(table).column(11).nodes().to$().attr('class','m-0 p-1');
                table.row(table).column(12).nodes().to$().attr('class','m-0 p-1');
                table.row(table).column(13).nodes().to$().attr('class','m-0 p-1');
                table.row(table).column(14).nodes().to$().attr('class','m-0 p-1');
                table.row(table).column(15).nodes().to$().attr('class','m-0 p-1');
                table.row(table).column(16).nodes().to$().attr('class','m-0 p-1');
                table.row(table).column(17).nodes().to$().attr('class','m-0 p-1');
                table.row(table).column(18).nodes().to$().attr('class','m-0 p-1');
                table.row(table).column(19).nodes().to$().attr('class','m-0 p-1');
                table.row(table).column(20).nodes().to$().attr('class','m-0 p-1');
                table.row(table).column(21).nodes().to$().attr('class','m-0 p-1');
                table.row(table).column(22).nodes().to$().attr('class','m-0 p-1');
                table.row(table).column(23).nodes().to$().attr('class','m-0 p-1');
                table.row(table).column(24).nodes().to$().attr('class','m-0 p-1');
                table.row(table).column(25).nodes().to$().attr('class','m-0 p-1');
                table.row(table).column(26).nodes().to$().attr('class','m-0 p-1');
                table.row(table).column(27).nodes().to$().attr('class','m-0 p-1');
                table.row(table).column(28).nodes().to$().attr('class','m-0 p-1');
                table.row(table).column(29).nodes().to$().attr('class','m-0 p-1');
                table.row(table).column(30).nodes().to$().attr('class','m-0 p-1');
                table.row(table).column(31).nodes().to$().attr('class','m-0 p-1');

            },
            complete: function() {
                closeModal();
            },
            error: function(xhr) {
                let message = xhr?.responseJSON?.message == undefined ? "Server Error" : xhr?.responseJSON?.message
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: message,
                })
                return;
            }
        });
    }

    // OK - Show SPKO Content, Need Parameter Value
    function klikLihatNext(idnthko) {

        var idnthko = idnthko;
        var data = {idnthko: idnthko};
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({
            url: '/Produksi/PelaporanProduksi/NTHKO/lihatNext',
            beforeSend: function() {
                openModal();
            },
            data: data,
            dataType: 'json',
            type: 'POST',
            success: function(data) {
                $("#tampil").html(data.html);
                var table = $('#tampiltabel').DataTable({
                    ordering: false,
                    paging: true,
                    pageLength: 100,
                    searching: true,
                    lengthChange: true,
                    scrollX: true,
                    scroller: true,
                });

                document.getElementById("btncetak").disabled = false;
                document.getElementById("btncetakbarcode").disabled = false;
                document.getElementById("btnsimpan").disabled = true;
                document.getElementById("idcari").value = idnthko;

                if (data.postingStatus == 'A') {
                    document.getElementById("btnubah").disabled = false;
                } else {
                    document.getElementById("btnubah").disabled = true;
                }

                table.columns(6).visible(false);
                table.columns(7).visible(false);
                table.columns(8).visible(false);
                table.columns(9).visible(false);
                table.columns(10).visible(false);
                table.columns(11).visible(false);

                table.row(table).column(0).nodes().to$().attr('class','m-0 p-1');
                table.row(table).column(1).nodes().to$().attr('class','m-0 p-1');
                table.row(table).column(2).nodes().to$().attr('class','m-0 p-1');
                table.row(table).column(3).nodes().to$().attr('class','m-0 p-1');
                table.row(table).column(4).nodes().to$().attr('class','m-0 p-1');
                table.row(table).column(5).nodes().to$().attr('class','m-0 p-1');
                table.row(table).column(6).nodes().to$().attr('class','m-0 p-1');
                table.row(table).column(7).nodes().to$().attr('class','m-0 p-1');
                table.row(table).column(8).nodes().to$().attr('class','m-0 p-1');
                table.row(table).column(9).nodes().to$().attr('class','m-0 p-1');
                table.row(table).column(10).nodes().to$().attr('class','m-0 p-1');
                table.row(table).column(11).nodes().to$().attr('class','m-0 p-1');
                table.row(table).column(12).nodes().to$().attr('class','m-0 p-1');
                table.row(table).column(13).nodes().to$().attr('class','m-0 p-1');
                table.row(table).column(14).nodes().to$().attr('class','m-0 p-1');
                table.row(table).column(15).nodes().to$().attr('class','m-0 p-1');
                table.row(table).column(16).nodes().to$().attr('class','m-0 p-1');
                table.row(table).column(17).nodes().to$().attr('class','m-0 p-1');
                table.row(table).column(18).nodes().to$().attr('class','m-0 p-1');
                table.row(table).column(19).nodes().to$().attr('class','m-0 p-1');
                table.row(table).column(20).nodes().to$().attr('class','m-0 p-1');
                table.row(table).column(21).nodes().to$().attr('class','m-0 p-1');
                table.row(table).column(22).nodes().to$().attr('class','m-0 p-1');
                table.row(table).column(23).nodes().to$().attr('class','m-0 p-1');
                table.row(table).column(24).nodes().to$().attr('class','m-0 p-1');
                table.row(table).column(25).nodes().to$().attr('class','m-0 p-1');
                table.row(table).column(26).nodes().to$().attr('class','m-0 p-1');
                table.row(table).column(27).nodes().to$().attr('class','m-0 p-1');
                table.row(table).column(28).nodes().to$().attr('class','m-0 p-1');
                table.row(table).column(29).nodes().to$().attr('class','m-0 p-1');
                table.row(table).column(30).nodes().to$().attr('class','m-0 p-1');
                table.row(table).column(31).nodes().to$().attr('class','m-0 p-1');

                $("#datalistNTHKO").load(" #datalistNTHKO > *");
                klikBaru();
            },
            complete: function() {
                closeModal();
            },
            error: function(xhr) {
                let message = xhr?.responseJSON?.message == undefined ? "Server Error" : xhr?.responseJSON?.message
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: message,
                })
                return;
            }
        });
    }

    // OK - Show Save Options
    function klikSimpan() {
        var cekstatus = $('#cekstatus').val();

        if (cekstatus == 1) {
            simpanNTHKO();
        } else if (cekstatus == 2) {
            updateNTHKO();
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: "Invalid Request",
            })
            return;
        }
    }

    // OK - Save NTHKO
    function simpanNTHKO() {

        var table = $('#tampiltabel').DataTable();
        var rowCount = table.data().rows().count();

        if (rowCount == 0) {
            Swal.fire({
                icon: "error",
                title: "Harap Isi Detail Item",
                timer: 2000,
                showCancelButton: false,
                showConfirmButton: true
            });
        } else {

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                url: '/Produksi/PelaporanProduksi/NTHKO/simpan',
                beforeSend: function() {
                    openModal();
                },
                data: $('#tampilform, #tampilform2').serialize(),
                dataType: 'json',
                type: 'POST',
                success: function(data) {
                    if (data.status == 'rep') {
                        Swal.fire({
                            icon: "error",
                            title: "Barang Rep hanya boleh di kolom Rep !",
                            timer: 2000,
                            showCancelButton: false,
                            showConfirmButton: true
                        });
                    } else if (data.status == 'ss') {
                        Swal.fire({
                            icon: "error",
                            title: "Barang SS, SSTK, SSTP hanya boleh di kolom SS !",
                            timer: 2000,
                            showCancelButton: false,
                            showConfirmButton: true
                        });
                    } else if (data.status == 'ok') {
                        Swal.fire({
                            icon: "error",
                            title: "Barang OK hanya boleh di kolom OK !",
                            timer: 2000,
                            showCancelButton: false,
                            showConfirmButton: true
                        });
                    } else{
                        document.getElementById("btnsimpan").disabled = true;
                        klikLihatNext(data.idnthko);
                    }

                },
                complete: function() {
                    closeModal();
                },
                error: function(xhr) {
                    document.getElementById("btnsimpan").disabled = true;
                    let message = xhr?.responseJSON?.message == undefined ? "Server Error" : xhr?.responseJSON?.message
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: message,
                    })
                    return;
                }
            });

        }
    }

    // OK - Update NTHKO
    function updateNTHKO() {

        var table = $('#tampiltabel').DataTable();
        var rowCount = table.data().rows().count();

        if (rowCount == 0) {
            Swal.fire({
                icon: "error",
                title: "Harap Isi Detail Item",
                timer: 2000,
                showCancelButton: false,
                showConfirmButton: true
            });
        } else {

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                url: '/Produksi/PelaporanProduksi/NTHKO/update',
                beforeSend: function() {
                    openModal();
                },
                data: $('#tampilform, #tampilform2').serialize(),
                dataType: 'json',
                type: 'POST',
                success: function(data) {
                    if (data.status == 'rep') {
                        Swal.fire({
                            icon: "error",
                            title: "Barang Rep hanya boleh di kolom Rep !",
                            timer: 2000,
                            showCancelButton: false,
                            showConfirmButton: true
                        });
                    } else if (data.status == 'ss') {
                        Swal.fire({
                            icon: "error",
                            title: "Barang SS, SSTK, SSTP hanya boleh di kolom SS !",
                            timer: 2000,
                            showCancelButton: false,
                            showConfirmButton: true
                        });
                    } else if (data.status == 'ok') {
                        Swal.fire({
                            icon: "error",
                            title: "Barang OK hanya boleh di kolom OK !",
                            timer: 2000,
                            showCancelButton: false,
                            showConfirmButton: true
                        });
                    } else{
                        document.getElementById("btnsimpan").disabled = true;
                        klikLihatNext(data.idnthko);
                    }
                },
                complete: function() {
                    closeModal();
                },
                error: function(xhr) {
                    document.getElementById("btnsimpan").disabled = true;
                    let message = xhr?.responseJSON?.message == undefined ? "Server Error" : xhr?.responseJSON?.message
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: message,
                    })
                    return;
                }
            });

        }
    }

    // OK - Posting NTHKO
    function klikPosting() {

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({
            url: "/Produksi/PelaporanProduksi/NTHKO/posting",
            beforeSend: function() {
                openModal();
            },
            data: $('#tampilform').serialize(),
            dataType: 'json',
            type: 'POST',
            success: function(data) {

                if (data.status == 'sukses') {

                    if(data.location == 10 && data.baris == 1){
                        Swal.fire({
                            icon: 'success',
                            title: 'Data Transfer Barang Jadi',
                            html: data.list,
                        }).then((result) => {
                            if (result['isConfirmed']){
                                document.getElementById("btnposting").disabled = true;
                                klikLihatNext(data.idnthko)
                            }
                        });
                    }else{
                        document.getElementById("btnposting").disabled = true;
                        klikLihatNext(data.idnthko);

                    }

                } else if (data.status == 'gagal') {
                    Swal.fire({
                        icon: "error",
                        title: "Posting Gagal",
                        timer: 2000,
                        showCancelButton: false,
                        showConfirmButton: true
                    });
                    document.getElementById("btnposting").disabled = true;

                } else if (data.status == 'sdhposting') {
                    Swal.fire({
                        icon: "error",
                        title: "Sudah Diposting",
                        timer: 2000,
                        showCancelButton: false,
                        showConfirmButton: true
                    });
                    // document.getElementById("btnposting").disabled = true;

                } else if (data.status == 'sdhsusut') {
                    Swal.fire({
                        icon: "error",
                        title: "Sudah Disusutkan",
                        timer: 2000,
                        showCancelButton: false,
                        showConfirmButton: true
                    });
                    // document.getElementById("btnposting").disabled = true;

                } else if (data.status == 'sdhbatal') {
                    Swal.fire({
                        icon: "error",
                        title: "Sudah Dibatalkan",
                        timer: 2000,
                        showCancelButton: false,
                        showConfirmButton: true
                    });
                    // document.getElementById("btnposting").disabled = true;
                    
                } else if (data.status == 'belumstokharian') {
                    Swal.fire({
                        icon: "error",
                        title: "Belum Stok Harian",
                        timer: 2000,
                        showCancelButton: false,
                        showConfirmButton: true
                    });
                    // document.getElementById("btnposting").disabled = true;
                }
            },
            complete: function() {
                closeModal();
            },
            error: function(xhr) {
                document.getElementById("btnposting").disabled = true;
                let message = xhr?.responseJSON?.message == undefined ? "Server Error" : xhr?.responseJSON?.message
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: message,
                })
                return;
            }
        });
    }


    function klikBarcodeUnitRun(){
        var swspko = $("#swspko").val();
        var barcodeunit = $("#barcodeunit").val();
        var kadar = $("#kadar").val();

        var explode = barcodeunit.split("-");
        var spko = explode[0];

        if (kadar == "") {
            Swal.fire({
                icon: "error",
                title: "Harap Isi Kadar",
                timer: 2000,
                showCancelButton: false,
                showConfirmButton: true
            });
            document.getElementById("barcodeunit").value = "";

        } else if (swspko != spko) {
            Swal.fire({
                icon: "error",
                title: "SPKO Tidak Sesuai",
                timer: 2000,
                showCancelButton: false,
                showConfirmButton: true
            });
            document.getElementById("barcodeunit").value = "";

        } else {

            if (barcodeunit == "") {
                Swal.fire({
                    icon: "error",
                    title: "Scan Barcode SPKO",
                    timer: 2000,
                    showCancelButton: false,
                    showConfirmButton: true
                });
                document.getElementById("barcodeunit").value = "";

            } else {

                data = {
                    barcodeunit: barcodeunit,
                    kadar: kadar,
                    swspko: swspko
                };

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                $.ajax({
                    url: "/Produksi/PelaporanProduksi/NTHKO/barcodeUnit",
                    beforeSend: function() {
                        openModal();
                    },
                    data: data,
                    dataType: 'json',
                    type: 'POST',
                    success: function(data) {

                        if (data.status == 'Duplicate') {
                            Swal.fire({
                                icon: "error",
                                title: "Duplicate NTHKO",
                                timer: 2000,
                                showCancelButton: false,
                                showConfirmButton: true
                            });
                            document.getElementById("barcodeunit").value = '';

                        } else if (data.status == 'Kosong') {
                            Swal.fire({
                                icon: "error",
                                title: "NTHKO Tidak Sesuai",
                                timer: 2000,
                                showCancelButton: false,
                                showConfirmButton: true
                            });
                            document.getElementById("barcodeunit").value = '';

                        } else {
                            $(document).ready(function() {
                                $('.myselect').select2();
                            });

                            // DataTable, Row count and column count both start at 0
                            var table = $('#tampiltabel').DataTable();
                            var rowCount = table.data().rows().count() + 1;

                            // brgSiap
                            // var brgsiapOptions = '';
                            // var brgsiapOptions = '<option value="' + data.Product + '">'+ data.BrgSiap + '</option>';
                            // for(var i = 0; i < data.brgSiapList.length; i++){
                            //     brgsiapOptions += '<option value="' + data.brgSiapList[i].ID + '">'+ data.brgSiapList[i].Description + '</option>';
                            // }

                            // SPK PPIC
                            // var noSPKOptions = '';
                            // var noSPKOptions = '<option value="' + data.WorkOrder + '">'+ data.NoSPK + '</option>';
                            // for(var i = 0; i < data.dataSPK.length; i++){
                            //     noSPKOptions += '<option value="' + data.dataSPK[i].ID + '">'+ data.dataSPK[i].SW + '</option>';
                            // }

                            // '<select class="form-select myselect" style="text-align: left; color:black; font-size: 13px;" id="Product'+rowCount+'" name="Product[]">' + brgsiapOptions + '</select>' +

                            var newrow = table.row.add([
                                '<td>' +
                                    '' + rowCount + '' +
                                    '<input type="hidden" id="WorkOrder' + rowCount + '" name="WorkOrder[]" value="' + data.WorkOrder + '">' +
                                    '<input type="hidden" id="Carat' + rowCount + '" name="Carat[]" value="' + data.Carat + '">' +
                                    '<input type="hidden" id="LinkID' + rowCount + '" name="LinkID[]" value="' + data.SPKOID + '">' +
                                    '<input type="hidden" id="LinkOrd' + rowCount + '" name="LinkOrd[]" value="' + data.SPKOUrut + '">' +
                                    '<input type="hidden" id="TreeID' + rowCount + '" name="TreeID[]" value="' + data.TreeID + '">' +
                                    '<input type="hidden" id="TreeOrd' + rowCount + '" name="TreeOrd[]" value="' + data.TreeOrd + '">' +
                                    '<input type="hidden" id="Part' + rowCount + '" name="Part[]" value="' + data.Part + '">' +
                                    '<input type="hidden" id="FG' + rowCount + '" name="FG[]" value="' + data.FG + '">' +
                                    '<input type="hidden" id="BatchNo' + rowCount + '" name="BatchNo[]" value="' + data.BatchNo + '">' +
                                    '<input type="hidden" id="OOrd' + rowCount + '" name="OOrd[]" value="' + data.OOrd + '">' +
                                '</td>',
                                '<td><button type="button" class="btn btn-danger btn-sm" onclick="remove(\''+rowCount+'\')"><i class="fa fa-minus"></i></button></td>',
                                '<td><input class="form-control" type="text" spellcheck="false" autocomplete="off" style="text-align: center; color:black; font-size: 13px; font-weight: bold" id="NoSPK' + rowCount + '" value="' + data.NoSPK + '" onchange="cariSPK(this.value,' + rowCount + ',' + data.CaratID + ')" onkeydown="handlerItem(event)" data-index="' + rowCount + '01" row-index="'+rowCount+'"></td>',
                                '<td><input class="form-control-plaintext" type="text" style="text-align: center; color:black; font-size: 13px; font-weight: bold" id="ProdukSPK' + rowCount + '" value="' + data.ProdukSPK + '" onkeydown="handlerItem(event)" data-index="' + rowCount + '02" row-index="'+rowCount+'" readonly></td>',
                                '<td><input class="form-control-plaintext" type="text" style="text-align: center; color:black; font-size: 13px; font-weight: bold" id="JmlSPK' + rowCount + '" value="' + data.JmlSPK + '" onkeydown="handlerItem(event)" data-index="' + rowCount + '03" row-index="'+rowCount+'" readonly></td>',
                                '<td>' +
                                    '<input class="form-control" type="text" spellcheck="false" autocomplete="off" style="text-align: center; color:black; font-size: 13px; font-weight: bold" id="ProductLabel' + rowCount + '" value="' + data.BrgSiap + '" onchange="cariProduct(this.value,' + rowCount + ',' + data.CaratID + ')" onkeydown="handlerItem(event)" data-index="' + rowCount + '04" row-index="'+rowCount+'"></td>' +
                                    '<input type="hidden" id="Product' + rowCount + '" name="Product[]" value="' + data.Product + '">' + 
                                '</td>',
                                '<td><input class="form-control-plaintext" type="text" autocomplete="off" spellcheck="false" style="text-align: center; color:black; font-size: 13px; font-weight: bold" id="Kadar' + rowCount + '" value="' + data.Kadar + '" onkeydown="handlerItem(event)" data-index="' + rowCount + '05" row-index="'+rowCount+'" readonly></td>',
                                '<td>' + data.Qty + '</td>',
                                '<td>' + data.Weight + '</td>',
                                '<td>' + 0 + '</td>',
                                '<td>' + 0 + '</td>',
                                '<td>' + 0 + '</td>',
                                '<td>' + 0 + '</td>',
                                '<td><input class="form-control" type="text" autocomplete="off" style="text-align: center; color:black; font-size: 13px; width: 100%; font-weight: bold" id="Qty' + rowCount + '" name="Qty[]" value="' + data.Qty + '" onchange="refresh_sum_qty(' + rowCount + ')" onkeydown="handlerItem(event)" data-index="' + rowCount + '06" row-index="'+rowCount+'"></td>',
                                '<td>' +
                                    '<div class="input-group" style="width: 100%">' +
                                        '<input class="form-control" type="text" autocomplete="off" style="text-align: center; color:black; font-size: 13px; font-weight: bold" id="Weight' + rowCount + '" name="Weight[]" value="' + data.Weight + '" onchange="refresh_sum_weight(' + rowCount + ')" onkeydown="handlerItem(event)" data-index="' + rowCount + '07" row-index="'+rowCount+'">' +
                                        '<button type="button" class="btn btn-info btn-sm" onclick="klikTimbangRunOK(\'Weight' + rowCount + '\','+rowCount+')"><i class="fa fa-balance-scale"></i></button>' +
                                    '</div>' +
                                '</td>',
                                '<td><input class="form-control" type="text" autocomplete="off" style="text-align: center; color:black; font-size: 13px; width: 100%; font-weight: bold" id="RepairQty' + rowCount + '" name="RepairQty[]" value="" onchange="refresh_sum_qty(' + rowCount + ')" onkeydown="handlerItem(event)" data-index="' + rowCount +'08" row-index="'+rowCount+'"></td>',
                                '<td>' +
                                    '<div class="input-group" style="width: 100%">' +
                                        '<input class="form-control" type="text" autocomplete="off" style="text-align: center; color:black; font-size: 13px; font-weight: bold" id="RepairWeight' + rowCount + '" name="RepairWeight[]" value="" onchange="refresh_sum_weight(' + rowCount + ')" onkeydown="handlerItem(event)" data-index="' + rowCount + '09" row-index="'+rowCount+'">' +
                                        '<button type="button" class="btn btn-info btn-sm" onclick="klikTimbangRunRep(\'RepairWeight' + rowCount + '\','+rowCount+')"><i class="fa fa-balance-scale"></i></button>' +
                                    '</div>' +
                                '</td>',
                                '<td><input class="form-control" type="text" autocomplete="off" style="text-align: center; color:black; font-size: 13px; width: 100%; font-weight: bold" id="ScrapQty' + rowCount + '" name="ScrapQty[]" value="" onchange="refresh_sum_qty(' + rowCount + ')" onkeydown="handlerItem(event)" data-index="' + rowCount +'10" row-index="'+rowCount+'"></td>',
                                '<td>' +
                                    '<div class="input-group" style="width: 100%">' +
                                        '<input class="form-control" type="text" autocomplete="off" style="text-align: center; color:black; font-size: 13px; font-weight: bold" id="ScrapWeight' + rowCount + '" name="ScrapWeight[]" value="" onchange="refresh_sum_weight(' + rowCount + ')" onkeydown="handlerItem(event)" data-index="' + rowCount + '11" row-index="'+rowCount+'">' +
                                        '<button type="button" class="btn btn-info btn-sm" onclick="klikTimbangRunSS(\'ScrapWeight' + rowCount + '\','+rowCount+')"><i class="fa fa-balance-scale"></i></button>' +
                                    '</div>' +
                                '</td>',
                                '<td><input class="form-control" type="text" autocomplete="off" style="text-align: center; color:black; font-size: 13px; width: 100%; font-weight: bold" id="StoneLoss' + rowCount + '" name="StoneLoss[]" value="' + data.StoneLoss + '" onkeydown="handlerItem(event)" data-index="' + rowCount + '12" row-index="'+rowCount+'"></td>',
                                '<td><input class="form-control" type="text" autocomplete="off" style="text-align: center; color:black; font-size: 13px; width: 100%; font-weight: bold" id="QtyLossStone' + rowCount + '" name="QtyLossStone[]" value="' + data.QtyLossStone + '" onkeydown="handlerItem(event)" data-index="' + rowCount + '13" row-index="'+rowCount+'"></td>',
                                '<td><input class="form-control" type="text" autocomplete="off" spellcheck="false" style="text-align: center; color:black; font-size: 13px; width: 100%; font-weight: bold" id="BarcodeNote' + rowCount + '" name="BarcodeNote[]" value="' + data.BarcodeNote + '" onchange="notecopy(' + rowCount + ')" onkeydown="handlerItem(event)" data-index="' + rowCount + '14" row-index="'+rowCount+'"></td>',
                                '<td><input class="form-control" type="text" autocomplete="off" spellcheck="false" style="text-align: center; color:black; font-size: 13px; width: 100%; font-weight: bold" id="Note' + rowCount + '" name="Note[]" value="' + data.Note + '" onkeydown="handlerItem(event)" data-index="' + rowCount + '15" row-index="'+rowCount+'"></td>',
                                '<td><input class="form-control-plaintext" type="text" style="text-align: center; color:black; font-size: 13px; font-weight: bold" id="BrtBrg' + rowCount + '" name="BrtBrg" value="" onkeydown="handlerItem(event)" data-index="' + rowCount + '16" row-index="'+rowCount+'" readonly></td>',
                                '<td><input class="form-control-plaintext" type="text" style="text-align: center; color:black; font-size: 13px; font-weight: bold" id="BrtAir' + rowCount + '" name="BrtAir" value="" onkeydown="handlerItem(event)" data-index="' + rowCount + '17" row-index="'+rowCount+'" readonly></td>',
                                '<td><input class="form-control-plaintext" type="text" style="text-align: center; color:black; font-size: 13px; font-weight: bold" id="BrtJenis' + rowCount + '" name="BrtJenis" value="" onkeydown="handlerItem(event)" data-index="' + rowCount + '18" row-index="'+rowCount+'" readonly></td>',
                                '<td><input class="form-control-plaintext" type="text" style="text-align: center; color:black; font-size: 13px; font-weight: bold" id="NoPohon' + rowCount + '" value="' + data.RubberPlate + '" onkeydown="handlerItem(event)" data-index="' + rowCount + '19" row-index="'+rowCount+'" readonly></td>',
                                '<td><input class="form-control-plaintext" type="text" style="text-align: center; color:black; font-size: 13px; font-weight: bold" id="ProdukDetail' + rowCount + '" value="' + data.ProductDetail + '" onkeydown="handlerItem(event)" data-index="' + rowCount + '20" row-index="'+rowCount+'" readonly></td>',
                                '<td><input class="form-control-plaintext" type="text" style="text-align: center; color:black; font-size: 13px; font-weight: bold" id="SPKOID' + rowCount + '" value="' + data.SPKOID + '" onkeydown="handlerItem(event)" data-index="' + rowCount + '21" row-index="'+rowCount+'" readonly></td>',
                                '<td><input class="form-control-plaintext" type="text" style="text-align: center; color:black; font-size: 13px; font-weight: bold" id="SPKOUrut' + rowCount + '" value="' + data.SPKOUrut + '" onkeydown="handlerItem(event)" data-index="' + rowCount + '22" row-index="'+rowCount+'" readonly></td>',
                                '<td><input class="form-control-plaintext" type="text" style="text-align: center; color:black; font-size: 13px; font-weight: bold" id="PohonID' + rowCount + '" value="' + data.TreeID + '" onkeydown="handlerItem(event)" data-index="' + rowCount + '23" row-index="'+rowCount+'" readonly></td>',
                                '<td><input class="form-control-plaintext" type="text" style="text-align: center; color:black; font-size: 13px; font-weight: bold" id="PohonUrut' + rowCount + '" value="' + data.TreeOrd + '" onkeydown="handlerItem(event)" data-index="' + rowCount + '24" row-index="'+rowCount+'" readonly></td>',
                                '<td><input class="form-control-plaintext" type="text" style="text-align: center; color:black; font-size: 13px; font-weight: bold" id="Batch' + rowCount + '" value="' + data.BatchNo + '" onkeydown="handlerItem(event)" data-index="' + rowCount + '25" row-index="'+rowCount+'" readonly></td>',
                            ]).draw();

                            table.row(newrow).nodes().to$().attr('id', 'myRow' + rowCount);
                            // table.row(newrow).column(2).nodes().to$().attr('id', 'm-0 p-0');
                            table.row(newrow).nodes().to$().attr('onclick','tampilGambar(\''+data.GPhoto+'\')');

                            table.row(newrow).column(0).nodes().to$().attr('class','m-0 p-0');
                            table.row(newrow).column(1).nodes().to$().attr('class','m-0 p-0');
                            table.row(newrow).column(2).nodes().to$().attr('class','m-0 p-0');
                            table.row(newrow).column(3).nodes().to$().attr('class','m-0 p-0');
                            table.row(newrow).column(4).nodes().to$().attr('class','m-0 p-0');
                            table.row(newrow).column(5).nodes().to$().attr('class','m-0 p-0');
                            table.row(newrow).column(6).nodes().to$().attr('class','m-0 p-0');
                            table.row(newrow).column(7).nodes().to$().attr('class','m-0 p-0');
                            table.row(newrow).column(8).nodes().to$().attr('class','m-0 p-0');
                            table.row(newrow).column(9).nodes().to$().attr('class','m-0 p-0');
                            table.row(newrow).column(10).nodes().to$().attr('class','m-0 p-0');
                            table.row(newrow).column(11).nodes().to$().attr('class','m-0 p-0');
                            table.row(newrow).column(12).nodes().to$().attr('class','m-0 p-0');
                            table.row(newrow).column(13).nodes().to$().attr('class','m-0 p-0');
                            table.row(newrow).column(14).nodes().to$().attr('class','m-0 p-0');
                            table.row(newrow).column(15).nodes().to$().attr('class','m-0 p-0');
                            table.row(newrow).column(16).nodes().to$().attr('class','m-0 p-0');
                            table.row(newrow).column(17).nodes().to$().attr('class','m-0 p-0');
                            table.row(newrow).column(18).nodes().to$().attr('class','m-0 p-0');
                            table.row(newrow).column(19).nodes().to$().attr('class','m-0 p-0');
                            table.row(newrow).column(20).nodes().to$().attr('class','m-0 p-0');
                            table.row(newrow).column(21).nodes().to$().attr('class','m-0 p-0');
                            table.row(newrow).column(22).nodes().to$().attr('class','m-0 p-0');
                            table.row(newrow).column(23).nodes().to$().attr('class','m-0 p-0');
                            table.row(newrow).column(24).nodes().to$().attr('class','m-0 p-0');
                            table.row(newrow).column(25).nodes().to$().attr('class','m-0 p-0');
                            table.row(newrow).column(26).nodes().to$().attr('class','m-0 p-0');
                            table.row(newrow).column(27).nodes().to$().attr('class','m-0 p-0');
                            table.row(newrow).column(28).nodes().to$().attr('class','m-0 p-0');
                            table.row(newrow).column(29).nodes().to$().attr('class','m-0 p-0');
                            table.row(newrow).column(30).nodes().to$().attr('class','m-0 p-0');
                            table.row(newrow).column(31).nodes().to$().attr('class','m-0 p-0');
                            table.row(newrow).column(32).nodes().to$().attr('class','m-0 p-0');

                            var qty = table.column(7).data().sum();
                            var weight = table.column(8).data().sum();
                            var qtyrep = table.column(9).data().sum();
                            var weightrep = table.column(10).data().sum();
                            var qtyss = table.column(11).data().sum();
                            var weightss = table.column(12).data().sum();

                            var qtydata = qty + qtyrep + qtyss;
                            var weightdata = weight + weightrep + weightss;

                            table.columns(7).visible(false);
                            table.columns(8).visible(false);
                            table.columns(9).visible(false);
                            table.columns(10).visible(false);
                            table.columns(11).visible(false);
                            table.columns(12).visible(false);
                            // table.columns(21).visible(false);
                            // table.columns(22).visible(false);
                            // table.columns(23).visible(false);

                            document.getElementById("tampiltabel").style.color = "black";
                            document.getElementById("tampiltabel").style.fontSize = "13px";
                            document.getElementById("tampiltabel").style.fontWeight = "bold";
                            document.getElementById("tampiltabel").style.textAlign = "center";

                            document.getElementById("btnsimpan").disabled = false;

                            document.getElementById("barcodeunit").value = '';
                            // document.getElementById("barcodeunit").focus();

                            document.getElementById("qtynthkolabel").innerHTML = String(qtydata);
                            document.getElementById("weightnthkolabel").innerHTML = String(weightdata.toFixed(2));

                            document.getElementById("qtynthko").value = qtydata;
                            document.getElementById("weightnthko").value = weightdata.toFixed(2);

                            tampilGambar(data.GPhoto);
                            selectItem("ProductLabel",rowCount);
                        }
                    },
                    complete: function() {
                        closeModal();
                    },
                    error: function(xhr) {
                        let message = xhr?.responseJSON?.message == undefined ? "Server Error" : xhr?.responseJSON?.message
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: message,
                        })
                        return;
                    }
                });
            }

        }
    }

    function selectItem(idname,number){
        const input = document.getElementById(idname+number);
        // console.log(input);
        input.focus();
        input.select();
    }

    // OK - Scan Barcode Unit
    function klikBarcodeUnit() {

        // Validasi
        let rowVal = $('#tampiltabel tbody tr').length;
        let qty = $('[data-index="' + rowVal + '06"]').val();
        let weight = $('[data-index="' + rowVal + '07"]').val();
        let repqty = $('[data-index="' + rowVal + '08"]').val();
        let repweight = $('[data-index="' + rowVal + '09"]').val();
        let ssqty = $('[data-index="' + rowVal + '10"]').val();
        let ssweight = $('[data-index="' + rowVal + '11"]').val();
        var product = $("#Product" + rowVal).val(); //return value as string
        var productInt = Number(product); //convert string to integer
        var listAll = $("#productlist").val(); //brg siap list sesuai area
        var list = listAll.split(",");

        // console.log(product, rowVal, qty, weight, repqty, repweight, ssqty, ssweight, listAll, productInt);
        console.log(product,productInt,listAll);
        if(list.includes(product)){
            console.log(1);
        }else{
            console.log(2);
        }

        const reparasi = [254, 99, 98, 2403];
        const rusak = [255, 2561, 2572];
        const batusisa = [1202];

        // if(list.includes(product)){
        //     Swal.fire({
        //         position: 'center',
        //         icon: 'error',
        //         title: 'Data Barang Tidak Boleh Sama Dengan Area !',
        //         showConfirmButton: false,
        //         timer: 1500
        //     });
        //     document.getElementById("barcodeunit").value = "";
        //     return;
        // }
        
        if(reparasi.includes(productInt)){
            if ((repqty == '' || repweight == '') || (qty != '' && qty != 0) || (weight != '' && weight != 0) || (ssqty != '' && ssqty != 0) || (ssweight != '' && ssweight != 0)) {
                Swal.fire({
                    position: 'center',
                    icon: 'error',
                    title: 'Barang Rep hanya boleh di kolom Rep !',
                    showConfirmButton: false,
                    timer: 1500
                });
                document.getElementById("barcodeunit").value = "";
                return;
            }
        }else if(rusak.includes(productInt)){
            if ((ssqty == '' || ssweight == '') || (qty != '' && qty != 0) || (weight != '' && weight != 0) || (repqty != '' && repqty != 0) || (repweight != '' && repweight != 0)) {
                Swal.fire({
                    position: 'center',
                    icon: 'error',
                    title: 'Barang SS, SSTK, SSTP hanya boleh di kolom SS !',
                    showConfirmButton: false,
                    timer: 1500
                });
                document.getElementById("barcodeunit").value = "";
                return;
            }
        }else if(batusisa.includes(productInt)){
            if (weight == '') {
                Swal.fire({
                    position: 'center',
                    icon: 'error',
                    title: 'Data Batu Sisa Belum Diisi',
                    showConfirmButton: false,
                    timer: 1500
                });
                document.getElementById("barcodeunit").value = "";
                return;
            }
        }else{
            // || (repqty != '' && repqty != 0) || (repweight != '' && repweight != 0) || (ssqty != '' && ssqty != 0) || (ssweight != '' && ssweight != 0)
            if((qty == '' || weight == '')){
                Swal.fire({
                    position: 'center',
                    icon: 'error',
                    title: 'Barang OK hanya boleh di kolom OK !',
                    showConfirmButton: false,
                    timer: 1500
                });
                document.getElementById("barcodeunit").value = "";
                return;
            }
        }
        // End Validasi

        klikBarcodeUnitRun();

        // var swspko = $("#swspko").val();
        // var barcodeunit = $("#barcodeunit").val();
        // var kadar = $("#kadar").val();
        // var listAll = $("#productlist").val();
        // var list = listAll.split(",");
        // var listrep = ["254","99","98","2403"];
        // var listrusak = ["255","2561","2572"];

        // var table = $('#tampiltabel').DataTable();
        // var rowMaxTable = table.data().rows().count();
    
        // if(rowMaxTable > 0){
        //     var product = $("#Product"+rowMaxTable).val();
        //     var jmlRep = $("#RepairQty"+rowMaxTable).val();
        //     var brtRep = $("#RepairWeight"+rowMaxTable).val();
        //     var jmlSS = $("#ScrapQty"+rowMaxTable).val();
        //     var brtSS = $("#ScrapWeight"+rowMaxTable).val();

        //     if(list.includes(product)){
        //         Swal.fire({
        //             icon: "error",
        //             title: "Brg Sama Dengan Area",
        //             timer: 2000,
        //             showCancelButton: false,
        //             showConfirmButton: true
        //         });
        //         document.getElementById("barcodeunit").value = "";
        //         console.log('1');
        //     }else{
        //         if(listrep.includes(product)){
        //             if(jmlRep == "" || brtRep == ""){
        //                 Swal.fire({
        //                     icon: "error",
        //                     title: "Data Rep Belum Diisi",
        //                     timer: 2000,
        //                     showCancelButton: false,
        //                     showConfirmButton: true
        //                 });
        //                 document.getElementById("barcodeunit").value = "";
        //                 console.log('2');
        //             }else{
        //                 klikBarcodeUnitRun();
        //                 console.log('3');
        //             }

        //         }else if(listrusak.includes(product)){
        //             if(jmlSS == "" || brtSS == ""){
        //                 Swal.fire({
        //                     icon: "error",
        //                     title: "Data SS Belum Diisi",
        //                     timer: 2000,
        //                     showCancelButton: false,
        //                     showConfirmButton: true
        //                 });
        //                 document.getElementById("barcodeunit").value = "";
        //                 console.log('4');
        //             }else{
        //                 klikBarcodeUnitRun();
        //                 console.log('5');
        //             }
        //         }else{
        //             klikBarcodeUnitRun();
        //             console.log('6');
        //         }  
        //     }
        // }else{
        //     klikBarcodeUnitRun();
        //     console.log('7');
        // }
    }

    function klikCetak() { //OK
        var id = $("#idnthko").val();
        var wa = $("#wanthko").val();

        var dataUrl = `/Produksi/PelaporanProduksi/NTHKO/cetak?id=${id}&wa=${wa}`;
        window.open(dataUrl, '_blank');
    }

    function klikCetakBarcode() { //OK
        var id = $("#idnthko").val();
        var wa = $("#wanthko").val();

        var dataUrl = `/Produksi/PelaporanProduksi/NTHKO/cetakBarcode?id=${id}&wa=${wa}`;
        window.open(dataUrl, '_blank');
    }

    function klikCetakBarcodeDirect() { //OK
        var id = $("#idnthko").val();
        var wa = $("#wanthko").val();

        data = {id: id, wa: wa};
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({
            url: "/Produksi/PelaporanProduksi/NTHKO/cetakBarcodeDirect",
            beforeSend: function() {
                openModal();
            },
            data: data,
            dataType: 'json',
            type: 'POST',
            success: function(data) {
                printPDF(data.id);
            },
            complete: function() {
                closeModal();
            },
            error: function(xhr) {
                let message = xhr?.responseJSON?.message == undefined ? "Server Error" : xhr?.responseJSON?.message
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: message,
                })
                return;
            }
        });
    }

    function cariSPK(sw, no, carat) {
        var no = no;
        var sw = sw;
        var carat = carat;
        data = {sw: sw, carat: carat};

        $.ajax({
            url: '/Produksi/PelaporanProduksi/NTHKO/cariSPK',
            beforeSend: function() {
                openModal();
            },
            data: data,
            dataType: 'json',
            type: 'POST',
            success: function(data) {

                if (data.rowcount > 0) {
                    document.getElementById("NoSPK" + no).value = data.NoSPK;
                    document.getElementById("WorkOrder" + no).value = data.WorkOrder;
                    document.getElementById("ProdukSPK" + no).value = data.ProductName;
                    document.getElementById("JmlSPK" + no).value = data.TotalQty;
                    document.getElementById("Kadar" + no).value = data.Kadar;
                    document.getElementById("Carat" + no).value = data.Carat;
                } else {
                    Swal.fire({
                        icon: "error",
                        title: "Error",
                        text: "SPK NotFound",
                        timer: 2000,
                        showCancelButton: false,
                        showConfirmButton: true
                    });
                    document.getElementById("NoSPK" + no).focus();
                    document.getElementById("NoSPK" + no).value = '';
                    document.getElementById("WorkOrder" + no).value = '';
                    document.getElementById("Kadar" + no).value = '';
                    document.getElementById("Carat" + no).value = '';
                }

            },
            complete: function() {
                closeModal();
            },
            error: function(xhr) {
                let message = xhr?.responseJSON?.message == undefined ? "Server Error" : xhr?.responseJSON?.message
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: message,
                })
                return;
            }
        });
    }

    function cariProduct(sw, no, carat) {

        var woid = $("#WorkOrder"+no).val();
        console.log(woid);

        var no = no;
        var sw = sw;
        var carat = carat;
        data = {
            sw: sw,
            carat: carat,
            woid: woid
        };

        $.ajax({
            url: '/Produksi/PelaporanProduksi/NTHKO/cariProduct',
            beforeSend: function() {
                openModal();
            },
            data: data,
            dataType: 'json',
            type: 'POST',
            success: function(data) {
                console.log(data);
                if (data.rowcount > 0) {
                    const Location = [7,17];
                    const woPurpose = ['CBP','OCBP','CBK','OCBK'];
                    const productKtk = [251,2231]; // Ktk/Ktk Cor

                    // if( data.Location == 7 && woPurpose.includes(data.woPurpose) && productKtk.includes(data.Product)){
                    //     if (data.UseCarat == 'Y') {
                    //         document.getElementById("ProductLabel" + no).value = data.ProductLabel;
                    //         document.getElementById("Product" + no).value = data.Product;
                    //         document.getElementById("Kadar" + no).value = data.caratName;
                    //         document.getElementById("Carat" + no).value = data.caratID;
                    //         document.getElementById("WorkOrder" + no).value = data.WOID;
                    //         document.getElementById("NoSPK" + no).value = data.WO;
                    //         document.getElementById("JmlSPK" + no).value = data.jmlSPK;
                    //         document.getElementById("ProdukSPK" + no).value = data.produkSPK;
                    //     } else if (data.UseCarat == 'N') {
                    //         document.getElementById("ProductLabel" + no).value = data.ProductLabel;
                    //         document.getElementById("Product" + no).value = data.Product;
                    //         document.getElementById("Kadar" + no).value = '';
                    //         document.getElementById("Carat" + no).value = 'NULL';
                    //         document.getElementById("WorkOrder" + no).value = data.WOID;
                    //         document.getElementById("NoSPK" + no).value = data.WO;
                    //         document.getElementById("WorkOrder" + no).value = data.WOID;
                    //         document.getElementById("JmlSPK" + no).value = data.jmlSPK;
                    //         document.getElementById("ProdukSPK" + no).value = data.produkSPK;
                    //     }

                    // }else if(data.Location == 17 && woPurpose.includes(data.woPurpose) && data.Product == 251){
                    //     if (data.UseCarat == 'Y') {
                    //         document.getElementById("ProductLabel" + no).value = data.ProductLabel;
                    //         document.getElementById("Product" + no).value = data.Product;
                    //         document.getElementById("Kadar" + no).value = data.caratName;
                    //         document.getElementById("Carat" + no).value = data.caratID;
                    //         document.getElementById("WorkOrder" + no).value = data.WOID;
                    //         document.getElementById("NoSPK" + no).value = data.WO;
                    //         document.getElementById("JmlSPK" + no).value = data.jmlSPK;
                    //         document.getElementById("ProdukSPK" + no).value = data.produkSPK;
                    //     } else if (data.UseCarat == 'N') {
                    //         document.getElementById("ProductLabel" + no).value = data.ProductLabel;
                    //         document.getElementById("Product" + no).value = data.Product;
                    //         document.getElementById("Kadar" + no).value = '';
                    //         document.getElementById("Carat" + no).value = 'NULL';
                    //         document.getElementById("WorkOrder" + no).value = data.WOID;
                    //         document.getElementById("NoSPK" + no).value = data.WO;
                    //         document.getElementById("WorkOrder" + no).value = data.WOID;
                    //         document.getElementById("JmlSPK" + no).value = data.jmlSPK;
                    //         document.getElementById("ProdukSPK" + no).value = data.produkSPK;
                    //     }

                    // }else if(data.Product == 251 && (data.Location != 7 || data.Location != 17) ){
                    //     if (data.UseCarat == 'Y') {
                    //         document.getElementById("ProductLabel" + no).value = data.ProductLabel;
                    //         document.getElementById("Product" + no).value = data.Product;
                    //         document.getElementById("Kadar" + no).value = data.caratName;
                    //         document.getElementById("Carat" + no).value = data.caratID;
                    //         document.getElementById("WorkOrder" + no).value = data.WOID;
                    //         document.getElementById("NoSPK" + no).value = data.WO;
                    //         document.getElementById("JmlSPK" + no).value = data.jmlSPK;
                    //         document.getElementById("ProdukSPK" + no).value = data.produkSPK;
                    //     } else if (data.UseCarat == 'N') {
                    //         document.getElementById("ProductLabel" + no).value = data.ProductLabel;
                    //         document.getElementById("Product" + no).value = data.Product;
                    //         document.getElementById("Kadar" + no).value = '';
                    //         document.getElementById("Carat" + no).value = 'NULL';
                    //         document.getElementById("WorkOrder" + no).value = data.WOID;
                    //         document.getElementById("NoSPK" + no).value = data.WO;
                    //         document.getElementById("WorkOrder" + no).value = data.WOID;
                    //         document.getElementById("JmlSPK" + no).value = data.jmlSPK;
                    //         document.getElementById("ProdukSPK" + no).value = data.produkSPK;
                    //     }

                    // }else if(data.Product == 7215 && data.Location == 49){ // Serbuk + Slep
                    //     if (data.UseCarat == 'Y') {
                    //         document.getElementById("ProductLabel" + no).value = data.ProductLabel;
                    //         document.getElementById("Product" + no).value = data.Product;
                    //         document.getElementById("Kadar" + no).value = data.caratName;
                    //         document.getElementById("Carat" + no).value = data.caratID;
                    //         document.getElementById("WorkOrder" + no).value = data.WOID;
                    //         document.getElementById("NoSPK" + no).value = data.WO;
                    //         document.getElementById("JmlSPK" + no).value = data.jmlSPK;
                    //         document.getElementById("ProdukSPK" + no).value = data.produkSPK;
                    //     } else if (data.UseCarat == 'N') {
                    //         document.getElementById("ProductLabel" + no).value = data.ProductLabel;
                    //         document.getElementById("Product" + no).value = data.Product;
                    //         document.getElementById("Kadar" + no).value = '';
                    //         document.getElementById("Carat" + no).value = 'NULL';
                    //         document.getElementById("WorkOrder" + no).value = data.WOID;
                    //         document.getElementById("NoSPK" + no).value = data.WO;
                    //         document.getElementById("JmlSPK" + no).value = data.jmlSPK;
                    //         document.getElementById("ProdukSPK" + no).value = data.produkSPK;
                    //     }

                    // }else{
                    //     if (data.UseCarat == 'Y') {
                    //         document.getElementById("ProductLabel" + no).value = data.ProductLabel;
                    //         document.getElementById("Product" + no).value = data.Product;
                    //         document.getElementById("Kadar" + no).value = data.caratName;
                    //         document.getElementById("Carat" + no).value = data.caratID;
                    //     } else if (data.UseCarat == 'N') {
                    //         document.getElementById("ProductLabel" + no).value = data.ProductLabel;
                    //         document.getElementById("Product" + no).value = data.Product;
                    //         document.getElementById("Kadar" + no).value = '';
                    //         document.getElementById("Carat" + no).value = 'NULL';
                    //     }
                    // }

                    if (data.UseCarat == 'Y') {
                        document.getElementById("ProductLabel" + no).value = data.ProductLabel;
                        document.getElementById("Product" + no).value = data.Product;
                        document.getElementById("Kadar" + no).value = data.caratName;
                        document.getElementById("Carat" + no).value = data.caratID;
                    } else if (data.UseCarat == 'N') {
                        document.getElementById("ProductLabel" + no).value = data.ProductLabel;
                        document.getElementById("Product" + no).value = data.Product;
                        document.getElementById("Kadar" + no).value = '';
                        document.getElementById("Carat" + no).value = 'NULL';
                    }
                    

                } else {
                    Swal.fire({
                        icon: "error",
                        title: "Barang NotFound",
                        timer: 2000,
                        showCancelButton: false,
                        showConfirmButton: true
                    });
                    document.getElementById("ProductLabel" + no).focus();
                    document.getElementById("ProductLabel" + no).value = '';
                    document.getElementById("Product" + no).value = '';
                }

            },
            complete: function() {
                closeModal();
            },
            error: function(xhr) {
                let message = xhr?.responseJSON?.message == undefined ? "Server Error" : xhr?.responseJSON?.message
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: message,
                })
                return;
            }
        });
    }

    function remove(id) {
        // Swal.fire({
        //     title: 'Yakin mau hapus Baris ' + id + ' ?',
        //     icon: 'warning',
        //     showCancelButton: true,
        //     confirmButtonColor: '#3085d6',
        //     cancelButtonColor: '#d33',
        //     confirmButtonText: 'Yes'
        // }).then((result) => {
        //     if (result.isConfirmed) {

                var table = $('#tampiltabel').DataTable();
                table.row('#myRow' + id).remove().draw();

                var qty = table.column(7).data().sum();
                var weight = table.column(8).data().sum();
                var qtyrep = table.column(9).data().sum();
                var weightrep = table.column(10).data().sum();
                var qtyss = table.column(11).data().sum();
                var weightss = table.column(12).data().sum();

                var qtydata = qty + qtyrep + qtyss;
                var weightdata = weight + weightrep + weightss;

                // document.getElementById("barcodeunit").value = '';
                // document.getElementById("barcodeunit").focus();

                var number = id-1;
                if(number != 0){
                    selectItem("ProdukSPK",number);
                }

                document.getElementById("qtynthkolabel").innerHTML = String(qtydata);
                document.getElementById("weightnthkolabel").innerHTML = String(weightdata.toFixed(2));

                document.getElementById("qtynthko").value = qtydata;
                document.getElementById("weightnthko").value = weightdata.toFixed(2);

        //     }
        // });
    }

    function klikAddRow() {

        var kadar = $("#kadar").val();

        if (kadar == "") {
            Swal.fire({
                icon: "error",
                title: "Harap Isi Kadar",
                timer: 2000,
                showCancelButton: false,
                showConfirmButton: true
            });

        } else {

            // Validasi
            let rowVal = $('#tampiltabel tbody tr').length;
            let qty = $('[data-index="' + rowVal + '06"]').val();
            let weight = $('[data-index="' + rowVal + '07"]').val();
            let repqty = $('[data-index="' + rowVal + '08"]').val();
            let repweight = $('[data-index="' + rowVal + '09"]').val();
            let ssqty = $('[data-index="' + rowVal + '10"]').val();
            let ssweight = $('[data-index="' + rowVal + '11"]').val();
            var product = $("#Product" + rowVal).val(); //return value as string
            var productInt = Number(product); //convert string to integer

            // console.log(product, rowVal, qty, weight, repqty, repweight, ssqty, ssweight);

            const reparasi = [254, 99, 98, 2403];
            const rusak = [255, 2561, 2572];
            const batusisa = [1202];

            if(reparasi.includes(productInt)){
                if (repqty == '' || repweight == '') {
                    Swal.fire({
                        position: 'center',
                        icon: 'error',
                        title: 'Data Rep Belum Diisi',
                        showConfirmButton: false,
                        timer: 1500
                    });
                    return;
                }
            }else if(rusak.includes(productInt)){
                if (ssqty == '' || ssweight == '') {
                    Swal.fire({
                        position: 'center',
                        icon: 'error',
                        title: 'Data SS Belum Diisi',
                        showConfirmButton: false,
                        timer: 1500
                    });
                    return;
                }
            }else if(batusisa.includes(productInt)){
                if (weight == '') {
                    Swal.fire({
                        position: 'center',
                        icon: 'error',
                        title: 'Data Batu Sisa Belum Diisi',
                        showConfirmButton: false,
                        timer: 1500
                    });
                    return;
                }
            }else{
                if(qty == '' || weight == ''){
                    Swal.fire({
                        position: 'center',
                        icon: 'error',
                        title: 'Data OK Belum Diisi',
                        showConfirmButton: false,
                        timer: 1500
                    });
                    return;
                }
            }
            // End Validasi

            var table = $('#tampiltabel').DataTable();
            var rowCount = table.data().rows().count() + 1;

            var newrow = table.row.add([
                '<td>' +
                    '' + rowCount + '' +
                    '<input type="hidden" id="WorkOrder' + rowCount + '" name="WorkOrder[]" value="">' +
                    '<input type="hidden" id="Carat' + rowCount + '" name="Carat[]" value="">' +
                    '<input type="hidden" id="LinkID' + rowCount + '" name="LinkID[]" value="">' +
                    '<input type="hidden" id="LinkOrd' + rowCount + '" name="LinkOrd[]" value="">' +
                    '<input type="hidden" id="TreeID' + rowCount + '" name="TreeID[]" value="">' +
                    '<input type="hidden" id="TreeOrd' + rowCount + '" name="TreeOrd[]" value="">' +
                    '<input type="hidden" id="Part' + rowCount + '" name="Part[]" value="">' +
                    '<input type="hidden" id="FG' + rowCount + '" name="FG[]" value="">' +
                    '<input type="hidden" id="BatchNo' + rowCount + '" name="BatchNo[]" value="">' +
                    '<input type="hidden" id="OOrd' + rowCount + '" name="OOrd[]" value="">' +
                '</td>',
                '<td><button type="button" class="btn btn-danger btn-sm" onclick="remove(\''+rowCount+'\')"><i class="fa fa-minus"></i></button></td>',
                '<td><input class="form-control" type="text" spellcheck="false" autocomplete="off" style="text-align: center; color:black; font-size: 13px; font-weight: bold" id="NoSPK' + rowCount + '" value="" onchange="cariSPK(this.value,' + rowCount + ',' + kadar + ')" onkeydown="handlerItem(event)" data-index="' + rowCount + '01" row-index="'+rowCount+'"></td>',
                '<td><input class="form-control-plaintext" type="text" style="text-align: center; color:black; font-size: 13px; font-weight: bold" id="ProdukSPK' + rowCount + '" onkeydown="handlerItem(event)" data-index="' + rowCount + '02" row-index="'+rowCount+'" readonly></td>',
                '<td><input class="form-control-plaintext" type="text" style="text-align: center; color:black; font-size: 13px; font-weight: bold" id="JmlSPK' + rowCount + '" onkeydown="handlerItem(event)" data-index="' + rowCount + '03" row-index="'+rowCount+'" readonly></td>',
                '<td>' +
                    '<input class="form-control" type="text" spellcheck="false" autocomplete="off" style="text-align: center; color:black; font-size: 13px; font-weight: bold" id="ProductLabel' + rowCount + '" onchange="cariProduct(this.value,' + rowCount + ',' + kadar + ')" onkeydown="handlerItem(event)" data-index="' + rowCount + '04" row-index="'+rowCount+'">' +
                    '<input type="hidden" id="Product' + rowCount + '" name="Product[]" value="">' +
                '</td>',
                '<td><input class="form-control-plaintext" type="text" autocomplete="off" style="text-align: center; color:black; font-size: 13px; font-weight: bold" id="Kadar' + rowCount + '" onkeydown="handlerItem(event)" data-index="' + rowCount + '05" row-index="'+rowCount+'" readonly></td>',
                '<td>' + 0 + '</td>',
                '<td>' + 0 + '</td>',
                '<td>' + 0 + '</td>',
                '<td>' + 0 + '</td>',
                '<td>' + 0 + '</td>',
                '<td>' + 0 + '</td>',
                '<td><input class="form-control" type="text" autocomplete="off" style="text-align: center; color:black; font-size: 13px; width: 100%; font-weight: bold" id="Qty' + rowCount + '" name="Qty[]" value="" onchange="refresh_sum_qty(' + rowCount + ')" onkeydown="handlerItem(event)" data-index="' + rowCount + '06" row-index="'+rowCount+'"></td>',
                '<td>' +
                    '<div class="input-group" style="width: 100%">' +
                        '<input class="form-control" type="text" autocomplete="off" style="text-align: center; color:black; font-size: 13px; font-weight: bold" id="Weight' + rowCount + '" name="Weight[]" value="" onchange="refresh_sum_weight(' + rowCount + ')" onkeydown="handlerItem(event)" data-index="' + rowCount + '07" row-index="'+rowCount+'">' +
                        '<button type="button" class="btn btn-info btn-sm" onclick="klikTimbangRunOK(\'Weight' + rowCount + '\','+rowCount+')"><i class="fa fa-balance-scale"></i></button>' +
                    '</div>' +
                '</td>',
                '<td><input class="form-control" type="text" autocomplete="off" style="text-align: center; color:black; font-size: 13px; width: 100%; font-weight: bold" id="RepairQty' + rowCount + '" name="RepairQty[]" value="" onchange="refresh_sum_qty(' + rowCount + ')" onkeydown="handlerItem(event)" data-index="' + rowCount + '08" row-index="'+rowCount+'"></td>',
                '<td>' +
                    '<div class="input-group" style="width: 100%">' +
                        '<input class="form-control" type="text" autocomplete="off" style="text-align: center; color:black; font-size: 13px; font-weight: bold" id="RepairWeight' + rowCount + '" name="RepairWeight[]" value="" onchange="refresh_sum_weight(' + rowCount + ')" onkeydown="handlerItem(event)" data-index="' + rowCount + '09" row-index="'+rowCount+'">' +
                        '<button type="button" class="btn btn-info btn-sm" onclick="klikTimbangRunRep(\'RepairWeight' + rowCount + '\','+rowCount+')"><i class="fa fa-balance-scale"></i></button>' +
                    '</div>' +
                '</td>',
                '<td><input class="form-control" type="text" autocomplete="off" style="text-align: center; color:black; font-size: 13px; width: 100%; font-weight: bold" id="ScrapQty' + rowCount + '" name="ScrapQty[]" value="" onchange="refresh_sum_qty(' + rowCount + ')" onkeydown="handlerItem(event)" data-index="' + rowCount + '10" row-index="'+rowCount+'"></td>',
                '<td>' +
                    '<div class="input-group" style="width: 100%">' +
                        '<input class="form-control" type="text" autocomplete="off" style="text-align: center; color:black; font-size: 13px; font-weight: bold" id="ScrapWeight' + rowCount + '" name="ScrapWeight[]" value="" onchange="refresh_sum_weight(' + rowCount + ')" onkeydown="handlerItem(event)" data-index="' + rowCount + '11" row-index="'+rowCount+'">' +
                        '<button type="button" class="btn btn-info btn-sm" onclick="klikTimbangRunSS(\'ScrapWeight' + rowCount + '\','+rowCount+')"><i class="fa fa-balance-scale"></i></button>' +
                    '</div>' +
                '</td>',
                '<td><input class="form-control" type="text" autocomplete="off" style="text-align: center; color:black; font-size: 13px; width: 100%; font-weight: bold" id="StoneLoss' + rowCount + '" name="StoneLoss[]" value="" onkeydown="handlerItem(event)" data-index="' + rowCount + '12" row-index="'+rowCount+'"></td>',
                '<td><input class="form-control" type="text" autocomplete="off" style="text-align: center; color:black; font-size: 13px; width: 100%; font-weight: bold" id="QtyLossStone' + rowCount + '" name="QtyLossStone[]" value="" onkeydown="handlerItem(event)" data-index="' + rowCount + '13" row-index="'+rowCount+'"></td>',
                '<td><input class="form-control" type="text" autocomplete="off" spellcheck="false" style="text-align: center; color:black; font-size: 13px; width: 100%; font-weight: bold" id="BarcodeNote' + rowCount + '" name="BarcodeNote[]" value="" onchange="notecopy(' + rowCount + ')" onkeydown="handlerItem(event)" data-index="' + rowCount + '14" row-index="'+rowCount+'"></td>',
                '<td><input class="form-control" type="text" autocomplete="off" spellcheck="false" style="text-align: center; color:black; font-size: 13px; width: 100%; font-weight: bold" id="Note' + rowCount + '" name="Note[]" value="" onkeydown="handlerItem(event)" data-index="' + rowCount + '15" row-index="'+rowCount+'"></td>',
                '<td><input class="form-control-plaintext" type="text" style="text-align: center; color:black; font-size: 13px; font-weight: bold" id="BrtBrg' + rowCount + '" onkeydown="handlerItem(event)" data-index="' + rowCount + '16" row-index="'+rowCount+'" readonly></td>',
                '<td><input class="form-control-plaintext" type="text" style="text-align: center; color:black; font-size: 13px; font-weight: bold" id="BrtAir' + rowCount + '" onkeydown="handlerItem(event)" data-index="' + rowCount + '17" row-index="'+rowCount+'" readonly></td>',
                '<td><input class="form-control-plaintext" type="text" style="text-align: center; color:black; font-size: 13px; font-weight: bold" id="BrtJenis' + rowCount + '" onkeydown="handlerItem(event)" data-index="' + rowCount + '18" row-index="'+rowCount+'" readonly></td>',
                '<td><input class="form-control-plaintext" type="text" style="text-align: center; color:black; font-size: 13px; font-weight: bold" id="NoPohon' + rowCount + '" onkeydown="handlerItem(event)" data-index="' + rowCount + '19" row-index="'+rowCount+'" readonly></td>',
                '<td><input class="form-control-plaintext" type="text" style="text-align: center; color:black; font-size: 13px; font-weight: bold" id="ProdukDetail' + rowCount + '" onkeydown="handlerItem(event)" data-index="' + rowCount + '20" row-index="'+rowCount+'" readonly></td>',
                '<td><input class="form-control-plaintext" type="text" style="text-align: center; color:black; font-size: 13px; font-weight: bold" id="SPKOID' + rowCount + '" onkeydown="handlerItem(event)" data-index="' + rowCount + '21" row-index="'+rowCount+'" readonly></td>',
                '<td><input class="form-control-plaintext" type="text" style="text-align: center; color:black; font-size: 13px; font-weight: bold" id="SPKOUrut' + rowCount + '" onkeydown="handlerItem(event)" data-index="' + rowCount + '22" row-index="'+rowCount+'" readonly></td>',
                '<td><input class="form-control-plaintext" type="text" style="text-align: center; color:black; font-size: 13px; font-weight: bold" id="PohonID' + rowCount + '" onkeydown="handlerItem(event)" data-index="' + rowCount + '23" row-index="'+rowCount+'" readonly></td>',
                '<td><input class="form-control-plaintext" type="text" style="text-align: center; color:black; font-size: 13px; font-weight: bold" id="PohonUrut' + rowCount + '" onkeydown="handlerItem(event)" data-index="' + rowCount + '24" row-index="'+rowCount+'" readonly></td>',
                '<td><input class="form-control-plaintext" type="text" style="text-align: center; color:black; font-size: 13px; font-weight: bold" id="Batch' + rowCount + '" onkeydown="handlerItem(event)" data-index="' + rowCount + '25" row-index="'+rowCount+'" readonly></td>',
            ]).draw()

            table.row(newrow).nodes().to$().attr('id', 'myRow' + rowCount);

            table.columns(7).visible(false);
            table.columns(8).visible(false);
            table.columns(9).visible(false);
            table.columns(10).visible(false);
            table.columns(11).visible(false);
            table.columns(12).visible(false);
            // table.columns(21).visible(false);
            // table.columns(22).visible(false);
            // table.columns(23).visible(false);
            

            table.row(newrow).column(0).nodes().to$().attr('class','m-0 p-0');
            table.row(newrow).column(1).nodes().to$().attr('class','m-0 p-0');
            table.row(newrow).column(2).nodes().to$().attr('class','m-0 p-0');
            table.row(newrow).column(3).nodes().to$().attr('class','m-0 p-0');
            table.row(newrow).column(4).nodes().to$().attr('class','m-0 p-0');
            table.row(newrow).column(5).nodes().to$().attr('class','m-0 p-0');
            table.row(newrow).column(6).nodes().to$().attr('class','m-0 p-0');
            table.row(newrow).column(7).nodes().to$().attr('class','m-0 p-0');
            table.row(newrow).column(8).nodes().to$().attr('class','m-0 p-0');
            table.row(newrow).column(9).nodes().to$().attr('class','m-0 p-0');
            table.row(newrow).column(10).nodes().to$().attr('class','m-0 p-0');
            table.row(newrow).column(11).nodes().to$().attr('class','m-0 p-0');
            table.row(newrow).column(12).nodes().to$().attr('class','m-0 p-0');
            table.row(newrow).column(13).nodes().to$().attr('class','m-0 p-0');
            table.row(newrow).column(14).nodes().to$().attr('class','m-0 p-0');
            table.row(newrow).column(15).nodes().to$().attr('class','m-0 p-0');
            table.row(newrow).column(16).nodes().to$().attr('class','m-0 p-0');
            table.row(newrow).column(17).nodes().to$().attr('class','m-0 p-0');
            table.row(newrow).column(18).nodes().to$().attr('class','m-0 p-0');
            table.row(newrow).column(19).nodes().to$().attr('class','m-0 p-0');
            table.row(newrow).column(20).nodes().to$().attr('class','m-0 p-0');
            table.row(newrow).column(21).nodes().to$().attr('class','m-0 p-0');
            table.row(newrow).column(22).nodes().to$().attr('class','m-0 p-0');
            table.row(newrow).column(23).nodes().to$().attr('class','m-0 p-0');
            table.row(newrow).column(24).nodes().to$().attr('class','m-0 p-0');
            table.row(newrow).column(25).nodes().to$().attr('class','m-0 p-0');
            table.row(newrow).column(26).nodes().to$().attr('class','m-0 p-0');
            table.row(newrow).column(27).nodes().to$().attr('class','m-0 p-0');
            table.row(newrow).column(28).nodes().to$().attr('class','m-0 p-0');
            table.row(newrow).column(29).nodes().to$().attr('class','m-0 p-0');
            table.row(newrow).column(30).nodes().to$().attr('class','m-0 p-0');
            table.row(newrow).column(31).nodes().to$().attr('class','m-0 p-0');
            table.row(newrow).column(32).nodes().to$().attr('class','m-0 p-0');

            document.getElementById("tampiltabel").style.color = "black";
            document.getElementById("tampiltabel").style.fontSize = "13px";
            document.getElementById("tampiltabel").style.fontWeight = "bold";
            document.getElementById("tampiltabel").style.textAlign = "center";

            selectItem("NoSPK",rowCount);
        }
    }

    function activeClass(){
        // Get the container element
        var btnContainer = document.getElementById("detailtab");

        // Get all buttons with class="btn" inside the container
        var btns = btnContainer.getElementsByClassName("btn");

        // Loop through the buttons and add the active class to the current/clicked button
        for (var i = 0; i < btns.length; i++) {
        btns[i].addEventListener("click", function() {
            var current = document.getElementsByClassName("active");

            // If there's no active class
            if (current.length > 0) {
            current[0].className = current[0].className.replace(" active", "");
            }

            // Add the active class to the current/clicked button
            this.className += " active";
        });
        }
    }

    function detailSPK(){
        var wanthko = $("#wanthko").val();

        $.get('/Produksi/PelaporanProduksi/NTHKO/detailSPK/'+wanthko, function(data) {
            // $("#contentBody").html(data.html);
            // $('#myModal').modal('show');
            // var table = $('#tampiltabel').DataTable({
            //     ordering: true,
            //     paging: true,
            //     pageLength: 10,
            //     searching: true,
            //     lengthChange: true,
            //     scrollCollapse: true,
            // });
            // table.clear().destroy();

            $("#tampil2").html(data.html);
            activeClass();
        });
    }

    function detailSPKO(){
        var wanthko = $("#wanthko").val();
        var freqnthko = $("#freqnthko").val();

        $.get('/Produksi/PelaporanProduksi/NTHKO/detailSPKO/'+wanthko+'/'+freqnthko, function(data) {
            $("#tampil2").html(data.html);
            activeClass();
        });
    }

    function detailNTHKO(){
        var idnthko = $("#idnthko").val();

        $.get('/Produksi/PelaporanProduksi/NTHKO/detailNTHKO/'+idnthko, function(data) {
            $("#tampil2").html(data.html);
            activeClass();
        });
    }

    </script>

@endsection
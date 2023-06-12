<?php $title = 'SPKO'; ?>
@extends('layouts.backend-Theme-3.app')
@section('title', '$title')

@section('Dashboard')
    <h2 class="m-0">{{ $title }}</h2>
    <ol class="breadcrumb sm-2">
        <li class="breadcrumb-item"><a href="/">Home </a></li>
        <li class="breadcrumb-item">Produksi </li>
        <li class="breadcrumb-item">Pelaporan Produksi </li>
        <li class="breadcrumb-item active">SPKO </li>
    </ol>
@endsection

@section('css')
    <link rel="stylesheet" href="{!! asset('assets/almas/select2.min.css') !!}">
    <style>
        input[readonly]{
            background-color:transparent;
            border: 0;
            font-size: 1em;
        }

        .container-fluid{
            padding: 0px !important;
            padding-left: 10px !important;
            padding-right: 15px !important;
        }

        #overlay{   
            position: fixed;
            top: 0;
            z-index: 100;
            width: 100%;
            height:100%;
            display: none;
            background: rgba(0,0,0,0.6);
        }
        .cv-spinner {
            height: 100%;
            display: flex;
            justify-content: center;
            align-items: center;  
        }
        .spinner {
            width: 40px;
            height: 40px;
            border: 4px #ddd solid;
            border-top: 4px #2e93e6 solid;
            border-radius: 50%;
            animation: sp-anime 0.8s infinite linear;
        }
        @keyframes sp-anime {
            100% { 
                transform: rotate(360deg); 
            }
    }
    </style>
@endsection

@section('container')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                @include('Produksi.PelaporanProduksi.SPKO.data')
            </div>
        </div>
    </div>

    <div id="overlay">
        <div class="cv-spinner">
            <span class="spinner"></span>
        </div>
    </div>

    {{-- <div class="dropdown-menu dropdown-menu-end animate" id="menuKlik" style="display:none">
        <div class="text-center fw-bold mb-2" id="judulKlik"></div>
        <a class="dropdown-item" onclick="klikEditTable()"><span class="tf-icons bx bx-edit"></span>&nbsp; Edit</a>
        <a class="dropdown-item" onclick="klikFotoTable()"><span class="tf-icons bx bx-list-ul"></span>&nbsp; Foto</a>
        <a class="dropdown-item" onclick="klikCetakTable()"><span class="tf-icons bx bx-printer"></span>&nbsp; Cetak</a>
        <a class="dropdown-item" onclick="klikInfoTable()"><span class="tf-icons bx bx-list-ul"></span>&nbsp; Info</a> 
    </div>  --}}
@endsection

@section('script')

    @include('layouts.backend-Theme-3.DataTabelButton')
    @include('layouts.backend-Theme-3.timbangan')

    <script src="{!! asset('assets/almas/sum().js') !!}"></script>
    <script src="{!! asset('assets/almas/websocket-printer.js') !!}"></script>
    <script src="{!! asset('assets/almas/select2.min.js') !!}"></script>

    <script>

    // === Start Example Functions ===
    // OK - Show Modal Options (Example)
    function klikTabelLihat(idm,ordinal,sw,foto,no){
        if($("#menuKlik").css('display') == 'block'){
            $("#menuKlik ").hide();
        }else{
            var top = event.pageY + 15;
            var left = event.pageX - 100;

            window.nomor = no;
            window.idm = idm; //Declaring global variable by window object 
            window.ordinal = ordinal;
            sw = sw;
            window.foto = foto;
            $("#judulKlik").html(sw);
            $(this).css('background-color', '#f4f5f7');
            $("#menuKlik").css({
                display: "block",
                top: top,
                left: left
            });
        }
        return false;
    }

    // OK - Show Newest SPKO List 
    function listspko(){
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({
            url: '/Produksi/PelaporanProduksi/SPKO/listspko',
            beforeSend: function() {
                openModal();
            },
            dataType: 'json',
            type: 'POST',
            success: function(data) {
                $("#idcari").html(data.Product);

                var listspko = '';
                var brgsiapOptions = '<option value="' + data.Product + '">'+ data.BrgSiap + '</option>';
                for(var i = 0; i < data.brgSiapList.length; i++){
                    brgsiapOptions += '<option value="' + data.brgSiapList[i].ID + '">'+ data.brgSiapList[i].Description + '</option>';
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
    // === End Example Functions ===



    // OK - Initiate DirectPrinting Library
    var printService = new WebSocketPrinter();
    function printPDF(data) {
        printService.submit({
            'type': 'BARCODE',
            'url': 'http://192.168.3.100/produksi/ProduksiPDF/'+data+'.pdf'
        });
    }

    // OK - Initiate Select2 Library
    $(document).ready(function() {
        $(".myselect").select2();
    });

    // OK - Modal Loading Open
    function openModal() {
        $(".preloader").fadeIn(300);
    }

    // OK - Modal Loading Close
    function closeModal() {
        $(".preloader").fadeOut(300);
    }

    // OK - Clear Search Field
    function klikClear() {
        document.getElementById("idcari").value = '';
        document.getElementById("idcari").focus();
    }

    // OK - Reload the Page
    function klikBatal() {
        location.reload();
    }

    function selectItem(idname,number){
        const input = document.getElementById(idname+number);
        // console.log(input);
        input.focus();
        input.select();
    }

    // OK - Handler Default Max 10 Input
    function handler(event) {
        var $this = $(event.target);
        var index = parseFloat($this.attr('data-index'));
        var id = String(index)[0];

        if (event.keyCode === 39) { //RIGHT
            $('[data-index="' + (index + 1).toString() + '"]').focus();
            event.preventDefault();
        }
        if (event.keyCode === 37) { //LEFT
            $('[data-index="' + (index - 1).toString() + '"]').focus();
            event.preventDefault();
        }
        if (event.keyCode === 13) { //ENTER
            $('[data-index="' + (index + 1).toString() + '"]').focus();
            event.preventDefault();
        }
        if (event.keyCode === 38) { //UP
            $('[data-index="' + (index - 10).toString() + '"]').focus();
            event.preventDefault();
        }
        if (event.keyCode === 40) { //DOWN
            $('[data-index="' + (index + 10).toString() + '"]').focus();
            event.preventDefault();
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

        if(posisiIndex == 19){
            document.getElementById("barcodeunit").value = '';
            document.getElementById("barcodeunit").focus();
        }else{
            if (event.keyCode === 39) { //RIGHT
                $('[data-index="' + (index + 1).toString() + '"]').focus().select();
                event.preventDefault();
            }
            if (event.keyCode === 37) { //LEFT
                var position = $('[data-index="' + (index - 1).toString() + '"]').focus().select();
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

    // function klikTimbangRun(id,baris){
    //     kliktimbang(id);
    //     refresh_sum_weight(baris);
    // }

    // function klikTimbangAlmas(id) {
    //     sendSerialLine();
    //     $('#selscale').val(id);
    // }

    function klikTimbangRun(id,baris){
        kliktimbang(id);
        var selscale = $("#selscale").val();
        document.getElementById("Weight"+baris).focus();
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

        var qtyinput = $(table.cell(baris, 12).node()).find('input').val();
        table.cell(baris, 10).data(qtyinput);
        var qtydata = table.column(10).data().sum();

        document.getElementById("qtyspkolabel").innerHTML = String(qtydata);
        document.getElementById("qtyspko").value = qtydata;
    }

    // OK - Calculate Weight Newest Value
    function refresh_sum_weight(id) {
        var baris = id - 1;
        var table = $('#tampiltabel').DataTable();

        var weightinput = $(table.cell(baris, 13).node()).find('input').val();
        table.cell(baris, 11).data(weightinput);
        var weightdata = table.column(11).data().sum();

        document.getElementById("weightspkolabel").innerHTML = String(weightdata.toFixed(2));
        document.getElementById("weightspko").value = weightdata.toFixed(2);
    }

    // OK - Show Image
    function tampilGambar(idgambar) {
        var idg = idgambar;

        if (idg != '') {
            if(idg == 'undefined'){
                var pic = 'http://192.168.3.100:8383/image2/NO-IMAGE.jpg';
                document.getElementById('showgambar').src = pic;
                document.getElementById('showgambar').style.display = 'inline-block';
                document.getElementById('showgambar').style.width = '200px';
                document.getElementById('showgambar').style.height = '200px';
            }else{
                var pic = 'http://192.168.3.100:8383/image2/' + idg + '.jpg';
                document.getElementById('showgambar').src = pic;
                document.getElementById('showgambar').style.display = 'inline-block';
                document.getElementById('showgambar').style.width = '200px';
                document.getElementById('showgambar').style.height = '200px';
            }
        } else {
            var pic = 'http://192.168.3.100:8383/image2/NO-IMAGE.jpg';
            document.getElementById('showgambar').src = pic;
            document.getElementById('showgambar').style.display = 'inline-block';
            document.getElementById('showgambar').style.width = '200px';
            document.getElementById('showgambar').style.height = '200px';
        }
    }

    // OK - Delete Row
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

                var qtydata = table.column(10).data().sum();
                var weightdata = table.column(11).data().sum();

                var initTotalTime = $("#initialworktotalsecond").val();
                var initPersen = $("#initialworkpercent").val();

                var totaltimedata = table.column(23).data().sum();
                var persendata = table.column(24).data().sum();

                var sumTotalTime = Number(initTotalTime)+totaltimedata;
                var sumPersen = Number(initPersen)+persendata;

                console.log(totaltimedata);

                // document.getElementById("barcodeunit").value = '';
                // document.getElementById("barcodeunit").focus();

                var number = id-1;
                if(number != 0){
                    selectItem("ProdukSPK",number);
                }

                document.getElementById("qtyspkolabel").innerHTML = String(qtydata);
                document.getElementById("weightspkolabel").innerHTML = String(weightdata.toFixed(2));

                document.getElementById("qtyspko").value = qtydata;
                document.getElementById("weightspko").value = weightdata.toFixed(2);

                document.getElementById("tempworktotalsecond").value = sumTotalTime;
                document.getElementById("tempworkpercent").value = sumPersen.toFixed(2);
                
                document.getElementById("worktotalsecond").innerHTML = String(sumTotalTime);
                document.getElementById("workpercent").innerHTML = String(sumPersen.toFixed(2));

        //     }
        // });
    }

    // OK - Search for Employee
    function cariKaryawan(id) {
        var proses = $("#proses").val();
        var id = id;
        var tgl = $("#tgl").val();

        if (proses == "") {
            Swal.fire({
                icon: "error",
                title: "Harap Isi Proses",
                timer: 2000,
                showCancelButton: false,
                showConfirmButton: true
            });
            document.getElementById("karyawan").value = '';
            document.getElementById("karyawan").focus();
        } else {
            data = {proses: proses, id: id, tgl: tgl};
            $.ajax({
                url: '/Produksi/PelaporanProduksi/SPKO/cariKaryawan',
                beforeSend: function() {
                    openModal();
                },
                data: data,
                dataType: 'json',
                type: 'POST',
                success: function(data) {
                    if(data.success == true){
                        document.getElementById("karyawanlabel").innerHTML = String(data.idkary);
                        document.getElementById("karyawan").value = data.swkary;
                        document.getElementById("karyawanid").value = data.idkary;
           
                        $("#initialworktotalsecond").val(data.worktotalsecond);
                        $("#initialworkpercent").val(data.workpercent);

                        var initTotalTime = $("#initialworktotalsecond").val();
                        var initPersen = $("#initialworkpercent").val();

                        var table = $('#tampiltabel').DataTable();
                        var totaltimedata = table.column(23).data().sum();
                        var persendata = table.column(24).data().sum();

                        var sumTotalTime = Number(initTotalTime)+totaltimedata;
                        var sumPersen = Number(initPersen)+persendata;

                        document.getElementById("tempworktotalsecond").value = sumTotalTime;
                        document.getElementById("tempworkpercent").value = sumPersen.toFixed(2);
                        
                        document.getElementById("worktotalsecond").innerHTML = String(sumTotalTime);
                        document.getElementById("workpercent").innerHTML = String(sumPersen.toFixed(2));
                       
                    }else{
                        Swal.fire({
                            icon: "error",
                            title: "Karyawan NotFound",
                            timer: 2000,
                            showCancelButton: false,
                            showConfirmButton: true
                        });
                        document.getElementById("karyawan").value = '';
                        document.getElementById("karyawanlabel").innerHTML = '';
                        document.getElementById("karyawanid").value = '';
                        document.getElementById("karyawan").focus();
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

    // OK - Search for WorkGroup
    function cariWorkgroup(id) {
        var empid = $("#karyawanid").val();
        var id = id;

        if (empid == "") {
            Swal.fire({
                icon: "error",
                title: "Harap Isi Karyawan",
                timer: 2000,
                showCancelButton: false,
                showConfirmButton: true
            });
            document.getElementById("workgroup").value = '';
            document.getElementById("workgrouplabel").innerHTML = '';
            document.getElementById("workgroupid").value = '';
            document.getElementById("workgroup").focus();
        } else {
            data = {id: id, empid: empid};
            $.ajax({
                url: '/Produksi/PelaporanProduksi/SPKO/cariWorkgroup',
                beforeSend: function() {
                    openModal();
                },
                data: data,
                dataType: 'json',
                type: 'POST',
                success: function(data) {
                    document.getElementById("workgrouplabel").innerHTML = String(data.swwg);
                    document.getElementById("workgroup").value = data.idwg;
                    document.getElementById("workgroupid").value = data.idwg;

                    if (data.status == 'notfound') {
                        Swal.fire({
                            icon: "error",
                            title: "Group Kerja NotFound",
                            timer: 2000,
                            showCancelButton: false,
                            showConfirmButton: true
                        });
                        document.getElementById("workgroup").value = '';
                        document.getElementById("workgrouplabel").innerHTML = '';
                        document.getElementById("workgroupid").value = '';
                        document.getElementById("workgroup").focus();

                    } else if (data.status == 'sukses') {
                        document.getElementById("workgrouplabel").innerHTML = String(data.swwg);
                        document.getElementById("workgroup").value = data.idwg;
                        document.getElementById("workgroupid").value = data.idwg;

                    } else if (data.status == 'notsame') {
                        Swal.fire({
                            icon: "error",
                            title: "Group Kerja Tidak Sesuai Karyawan",
                            timer: 2000,
                            showCancelButton: false,
                            showConfirmButton: true
                        });
                        document.getElementById("workgroup").value = '';
                        document.getElementById("workgrouplabel").innerHTML = '';
                        document.getElementById("workgroupid").value = '';
                        document.getElementById("workgroup").focus();

                    } else if (data.status == 'setnull') {
                        document.getElementById("workgroup").value = '';
                        document.getElementById("workgrouplabel").innerHTML = '';
                        document.getElementById("workgroupid").value = '';
                        document.getElementById("workgroup").focus();
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

    // OK - Show SPKO Content
    function klikLihat() {
        var id = $('#idcari').val();

        if(id == "") {
            document.getElementById("idcari").focus();
            Swal.fire({
                icon: "error",
                title: "Harap Pilih SPKO",
                timer: 2000,
                showCancelButton: false,
                showConfirmButton: true
            });
        }else{

            data = {id: id};
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                url: '/Produksi/PelaporanProduksi/SPKO/lihat',
                beforeSend: function() {
                    openModal();
                },
                data: data,
                dataType: 'JSON',
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

                    table.columns(10).visible(false);
                    table.columns(11).visible(false);

                    document.getElementById("showgambar").src = "http://192.168.3.100:8383/image2/NO-IMAGE.jpg";
                    document.getElementById("btnbaru").disabled = false;
                    document.getElementById("btnsimpan").disabled = true;
                    document.getElementById("btncetak").disabled = false;
                    document.getElementById("btncetakbarcode").disabled = false;

                    if (data.active == 'A') {
                        document.getElementById("btnubah").disabled = false;
                    } else {
                        document.getElementById("btnubah").disabled = true;
                    }

                    if (data.excludepic == 1) {
                        document.getElementById("btncetakgambar").disabled = true;
                    } else {
                        document.getElementById("btncetakgambar").disabled = false;
                    }

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

    // OK - Show SPKO Content, Need Parameter Value
    function klikLihatNext(id) {
        data = {id: id};

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({
            url: '/Produksi/PelaporanProduksi/SPKO/lihat',
            beforeSend: function() {
                openModal();
            },
            data: data,
            dataType: 'JSON',
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

                table.columns(10).visible(false);
                table.columns(11).visible(false);

                document.getElementById("showgambar").src = "http://192.168.3.100:8383/image2/NO-IMAGE.jpg";
                document.getElementById("btnbaru").disabled = false;
                document.getElementById("btnsimpan").disabled = true;
                document.getElementById("btncetak").disabled = false;
                document.getElementById("btncetakbarcode").disabled = false;
                document.getElementById("idcari").value = data.swspko;

                if (data.active == 'A') {
                    document.getElementById("btnubah").disabled = false;
                } else {
                    document.getElementById("btnubah").disabled = true;
                }

                if (data.excludepic == 1) {
                    document.getElementById("btncetakgambar").disabled = true;
                } else {
                    document.getElementById("btncetakgambar").disabled = false;
                }

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

    // OK - Show New Form
    function klikBaru() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({
            url: '/Produksi/PelaporanProduksi/SPKO/baru',
            beforeSend: function() {
                openModal();
            },
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
                    scrollCollapse: true,
                    scrollX: true,
                });

                table.columns(10).visible(false);
                table.columns(11).visible(false);
                table.columns(23).visible(false);
                table.columns(24).visible(false);

                document.getElementById("tampiltabel").style.color = "black";
                document.getElementById("tampiltabel").style.fontSize = "13px";
                document.getElementById("tampiltabel").style.fontWeight = "bold";
                document.getElementById("tampiltabel").style.textAlign = "center";

                document.getElementById("btnsimpan").disabled = false;
                document.getElementById("btnubah").disabled = true;
                document.getElementById("btncetak").disabled = true;
                document.getElementById("btncetakgambar").disabled = true;
                document.getElementById("btncetakbarcode").disabled = true;
                document.getElementById("btnposting").disabled = true;
                document.getElementById("btncetakbarcodedirect").disabled = true;
                document.getElementById("idcari").value = '';
                document.getElementById("tgl").focus();
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
        var id = $("#idcari").val();

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({
            url: "/Produksi/PelaporanProduksi/SPKO/ubah",
            beforeSend: function() {
                openModal();
            },
            data: $("#tampilform").serialize() + "&id=" + id,
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
                    scrollCollapse: false,
                    scrollX: true,
                    scroller: true,
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

                table.columns(10).visible(false);
                table.columns(11).visible(false);
                table.columns(23).visible(false);
                table.columns(24).visible(false);

                var qtydata = table.column(10).data().sum();
                var weightdata = table.column(11).data().sum();

                var totaltimedata = table.column(23).data().sum();
                var persendata = table.column(24).data().sum();

                document.getElementById("tampiltabel").style.color = "black";
                document.getElementById("tampiltabel").style.fontSize = "13px";
                document.getElementById("tampiltabel").style.fontWeight = "bold";
                document.getElementById("tampiltabel").style.textAlign = "center";

                document.getElementById("qtyspkolabel").innerHTML = String(qtydata);
                document.getElementById("weightspkolabel").innerHTML = String(weightdata.toFixed(2));

                document.getElementById("qtyspko").value = qtydata;
                document.getElementById("weightspko").value = weightdata.toFixed(2);

                document.getElementById("tempworktotalsecond").value = totaltimedata;
                document.getElementById("tempworkpercent").value = persendata.toFixed(2);
                
                document.getElementById("worktotalsecond").innerHTML = String(totaltimedata);
                document.getElementById("workpercent").innerHTML = String(persendata.toFixed(2));

                document.getElementById("showgambar").src = "http://192.168.3.100:8383/image2/NO-IMAGE.jpg";
                document.getElementById("btnsimpan").disabled = false;
                document.getElementById("btncetak").disabled = false;
                document.getElementById("btnbaru").disabled = false;
                document.getElementById("btnubah").disabled = true;

                if (data.excludepic == 1) {
                    document.getElementById("btncetakgambar").disabled = true;
                } else {
                    document.getElementById("btncetakgambar").disabled = false;
                }
                
                document.getElementById("tgl").focus();
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

    function cekSPKTest(){
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({
            url: '/Produksi/PelaporanProduksi/SPKO/cekSPKTest',
            beforeSend: function() {
                openModal();
            },
            data: $('#tampilform, #tampilform2').serialize(),
            dataType: "json",
            type: 'POST',
            success: function(data) {
                if(data.status == 'Sukses'){
                    document.getElementById("cekspk").value = data.cekspk;
                }else{
                    Swal.fire({
                        icon: 'error',
                        title: 'Cek SPK',
                        text: "No SPK Tidak Boleh Campur Antara Awalan 'O' dan Selain 'O' ",
                        showCancelButton: false,
                        showConfirmButton: true
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

    function cekSPK(){
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({
            url: '/Produksi/PelaporanProduksi/SPKO/cekSPK',
            beforeSend: function() {
                openModal();
            },
            data: $('#tampilform, #tampilform2').serialize(),
            dataType: "json",
            type: 'POST',
            success: function(data) {
                if(data.status == 'Sukses'){
                    document.getElementById("cekspk").value = data.cekspk;
                    klikSimpan();   // aktifkan saat pakai spk Char1 = 'O'

                    // var cekspk = $("#cekspk").val();
                    // console.log(cekspk);
                }else if(data.status == 'SPK_Closed'){
                    Swal.fire({
                        icon: 'error',
                        title: 'Cek SPK',
                        text: "Ada SPK Sudah Tidak Aktif",
                        // timer: 2000,
                        showCancelButton: false,
                        showConfirmButton: true
                    });
                }else{
                    Swal.fire({
                        icon: 'error',
                        title: 'Cek SPK',
                        text: "SPK Tidak Boleh Campur Antara Awalan 'O' dan Selain 'O' ",
                        // timer: 2000,
                        showCancelButton: false,
                        showConfirmButton: true
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

    // OK - Show Save Options
    function klikSimpan() {
        var ceknew = $("#ceknew").val();
        if (ceknew == 1) {
            simpanSPKO();
        } else if (ceknew == 2) {
            updateSPKO();
        } else{
            let message = xhr?.responseJSON?.message == undefined ? "Server Error" : xhr?.responseJSON?.message
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: message,
            })
            return;
        }
    }


    function test3() {
        $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

        $.ajax({
            url: "/Produksi/PelaporanProduksi/SPKO/test3",
            beforeSend: function() {
                openModal();
            },
            data: $('#tampilform, #tampilform2').serialize(),
            dataType: 'json',
            type: 'POST',
            success: function(data) {
                console.log('test3');
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

    
    function klikSimpanTest() {
        var ceknew = $("#ceknew").val();
        if (ceknew == 1) {
            simpanSPKOTest();
        } else if (ceknew == 2) {
            updateSPKOTest();
        } else{
            let message = xhr?.responseJSON?.message == undefined ? "Server Error" : xhr?.responseJSON?.message
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: message,
            })
            return;
        }
    }

    function simpanSPKOTest() {
        var carat = $("#kadar").val();
        var karyawanid = $("#karyawanid").val();
        var proses = $("#proses").val();

        var table = $('#tampiltabel').DataTable();
        var rowCount = table.data().rows().count();

        if (carat == "") {
            Swal.fire({
                icon: "error",
                title: "Harap Isi Kadar",
                timer: 2000,
                showCancelButton: false,
                showConfirmButton: true
            });
        } else if (karyawanid == "") {
            Swal.fire({
                icon: "error",
                title: "Harap Isi Karyawan",
                timer: 2000,
                showCancelButton: false,
                showConfirmButton: true
            });
        } else if (proses == "") {
            Swal.fire({
                icon: "error",
                title: "Harap Isi Proses",
                timer: 2000,
                showCancelButton: false,
                showConfirmButton: true
            });
        } else if (rowCount == 0) {
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
                url: "/Produksi/PelaporanProduksi/SPKO/simpanTest",
                beforeSend: function() {
                    openModal();
                },
                data: $('#tampilform, #tampilform2').serialize(),
                dataType: 'json',
                type: 'POST',
                success: function(data) {
                    if (data.status == 'Sukses') {
                        if(data.location == 12){
                            insertWorkPercent(data.idspko); //Insert rndnew.WorkPercent
                        }
                        klikLihatNext(data.swspko);

                    } else {
                        Swal.fire({
                            icon: "error",
                            title: "Simpan Gagal",
                            timer: 2000,
                            showCancelButton: false,
                            showConfirmButton: true
                        });
                        document.getElementById("btnsimpan").disabled = true;
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

    function updateSPKOTest() {
        var carat = $("#kadar").val();
        var karyawanid = $("#karyawanid").val();
        var proses = $("#proses").val();

        var table = $('#tampiltabel').DataTable();
        var rowCount = table.data().rows().count();

        if (carat == "") {
            Swal.fire({
                icon: "error",
                title: "Harap Isi Kadar",
                timer: 2000,
                showCancelButton: false,
                showConfirmButton: true
            });
        } else if (karyawanid == "") {
            Swal.fire({
                icon: "error",
                title: "Harap Isi Karyawan",
                timer: 2000,
                showCancelButton: false,
                showConfirmButton: true
            });
        } else if (proses == "") {
            Swal.fire({
                icon: "error",
                title: "Harap Isi Proses",
                timer: 2000,
                showCancelButton: false,
                showConfirmButton: true
            });
        } else if (rowCount == 0) {
            Swal.fire({
                icon: "error",
                title: "Harap Isi Item",
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
                url: "/Produksi/PelaporanProduksi/SPKO/updateTest",
                beforeSend: function() {
                    openModal();
                },
                data: $('#tampilform, #tampilform2').serialize(),
                dataType: 'json',
                type: 'POST',
                success: function(data) {
                    if (data.status == 'Sukses') {
                        klikLihatNext(data.swspko);

                    } else {
                        Swal.fire({
                            icon: "error",
                            title: "Update Gagal",
                            timer: 2000,
                            showCancelButton: false,
                            showConfirmButton: true
                        });
                        document.getElementById("btnsimpan").disabled = true;
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

    // OK - Save SPKO
    function simpanSPKO() {
        var carat = $("#kadar").val();
        var karyawanid = $("#karyawanid").val();
        var proses = $("#proses").val();

        var table = $('#tampiltabel').DataTable();
        var rowCount = table.data().rows().count();

        if (carat == "") {
            Swal.fire({
                icon: "error",
                title: "Harap Isi Kadar",
                timer: 2000,
                showCancelButton: false,
                showConfirmButton: true
            });
        } else if (karyawanid == "") {
            Swal.fire({
                icon: "error",
                title: "Harap Isi Karyawan",
                timer: 2000,
                showCancelButton: false,
                showConfirmButton: true
            });
        } else if (proses == "") {
            Swal.fire({
                icon: "error",
                title: "Harap Isi Proses",
                timer: 2000,
                showCancelButton: false,
                showConfirmButton: true
            });
        } else if (rowCount == 0) {
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
                url: "/Produksi/PelaporanProduksi/SPKO/simpan",
                beforeSend: function() {
                    openModal();
                },
                data: $('#tampilform, #tampilform2').serialize(),
                dataType: 'json',
                type: 'POST',
                success: function(data) {
                    if (data.status == 'Sukses') {
                        document.getElementById("btnsimpan").disabled = true;
                        if(data.location == 12){
                            insertWorkPercent(data.idspko); //Insert rndnew.WorkPercent
                        }
                        klikLihatNext(data.swspko);

                    } else {
                        Swal.fire({
                            icon: "error",
                            title: "Simpan Gagal",
                            timer: 2000,
                            showCancelButton: false,
                            showConfirmButton: true
                        });
                        document.getElementById("btnsimpan").disabled = true;
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

    // OK - Update SPKO
    function updateSPKO() {
        var carat = $("#kadar").val();
        var karyawanid = $("#karyawanid").val();
        var proses = $("#proses").val();

        var table = $('#tampiltabel').DataTable();
        var rowCount = table.data().rows().count();

        if (carat == "") {
            Swal.fire({
                icon: "error",
                title: "Harap Isi Kadar",
                timer: 2000,
                showCancelButton: false,
                showConfirmButton: true
            });
        } else if (karyawanid == "") {
            Swal.fire({
                icon: "error",
                title: "Harap Isi Karyawan",
                timer: 2000,
                showCancelButton: false,
                showConfirmButton: true
            });
        } else if (proses == "") {
            Swal.fire({
                icon: "error",
                title: "Harap Isi Proses",
                timer: 2000,
                showCancelButton: false,
                showConfirmButton: true
            });
        } else if (rowCount == 0) {
            Swal.fire({
                icon: "error",
                title: "Harap Isi Item",
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
                url: "/Produksi/PelaporanProduksi/SPKO/update",
                beforeSend: function() {
                    openModal();
                },
                data: $('#tampilform, #tampilform2').serialize(),
                dataType: 'json',
                type: 'POST',
                success: function(data) {
                    if (data.status == 'Sukses') {
                        document.getElementById("btnsimpan").disabled = true;
                        if(data.location == 12){
                            insertWorkPercent(data.idspko); //Insert rndnew.WorkPercent
                        }
                        klikLihatNext(data.swspko);

                    } else if (data.status == 'Beda_Kode') {
                        Swal.fire({
                            icon: "error",
                            title: "Kode SPK Lama Berbeda dengan SPK Ubah !",
                            timer: 2000,
                            showCancelButton: false,
                            showConfirmButton: true
                        });

                    }else {
                        Swal.fire({
                            icon: "error",
                            title: "Update Gagal",
                            timer: 2000,
                            showCancelButton: false,
                            showConfirmButton: true
                        });
                        document.getElementById("btnsimpan").disabled = true;
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


    function apiApp(idspko,username){ //OK

        var data = {idspko: idspko, username: username};

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({
            url: "/Produksi/PelaporanProduksi/SPKO/apiApp",
            beforeSend: function(){
                openModal();
            },      
            data: data,              
            dataType : 'json',
            type : 'POST',
            success: function(data)
            {
                
            },
            complete: function(){
                closeModal();
            },             
        });  
    }


    function insertWorkPercent(idspko){
        
        var data = {idspko: idspko};

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({
            url: "/Produksi/PelaporanProduksi/SPKO/insertWorkPercent",
            beforeSend: function(){
                openModal();
            },      
            data: data,              
            dataType : 'json',
            type : 'POST',
            success: function(data)
            {
                
            },
            complete: function(){
                closeModal();
            },             
        });  
    }


    function klikPosting() { //OK

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({
            url: "/Produksi/PelaporanProduksi/SPKO/posting",
            beforeSend: function() {
                openModal();
            },
            data: $('#tampilform').serialize(),
            dataType: 'json',
            type: 'POST',
            success: function(data) {
                if (data.status == 'sukses') {
                    document.getElementById("btnposting").disabled = true;
                    if(data.location == 12){
                        apiApp(data.idspko, data.username); //Insert Via API
                        insertWorkPercent(data.idspko); //Insert rndnew.WorkPercent
                    }
                    klikLihatNext(data.swspko);

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
                    document.getElementById("btnposting").disabled = true;

                } else if (data.status == 'sdhsusut') {
                    Swal.fire({
                        icon: "error",
                        title: "Sudah Disusutkan",
                        timer: 2000,
                        showCancelButton: false,
                        showConfirmButton: true
                    });
                    document.getElementById("btnposting").disabled = true;

                } else if (data.status == 'sdhbatal') {
                    Swal.fire({
                        icon: "error",
                        title: "Sudah Dibatalkan",
                        timer: 2000,
                        showCancelButton: false,
                        showConfirmButton: true
                    });
                    document.getElementById("btnposting").disabled = true;

                } else if (data.status == 'belumstokharian') {
                    Swal.fire({
                        icon: "error",
                        title: "Belum Stok Harian",
                        timer: 2000,
                        showCancelButton: false,
                        showConfirmButton: true
                    });
                    document.getElementById("btnposting").disabled = true;
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

    // OK
    function klikCetak() {
        var id = $("#idspko").val();
        var sw = $("#swspko").val();

        var dataUrl = `/Produksi/PelaporanProduksi/SPKO/cetak?id=${id}&sw=${sw}`;
        window.open(dataUrl, '_blank');
    }

    function klikCetak2() {
        var id = $("#idspko").val();
        var sw = $("#swspko").val();

        var dataUrl = `/Produksi/PelaporanProduksi/SPKO/cetak2?id=${id}&sw=${sw}`;
        window.open(dataUrl, '_blank');
    }

    function klikPersenKerja(){
        $.get('/Produksi/PelaporanProduksi/SPKO/lihatPersenKerja/', function(data) {
            $("#contentBody").html(data.html);
            $('#myModal').modal('show');
            var table = $('#tampiltabelmodal').DataTable({
                ordering: true,
                paging: true,
                pageLength: 10,
                searching: true,
                lengthChange: true,
                scrollCollapse: false,
                scrollX: true,
            });
            table.columns.adjust()
        });
    }

    function klikPersenKerjaAjax(){
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({
            url: "/Produksi/PelaporanProduksi/SPKO/lihatPersenKerjaAjax",
            beforeSend: function() {
                $("#overlay").fadeIn(300);
            },
            dataType: 'json',
            type: 'POST',
            success: function(data) {
                $("#contentBody").html(data.html);
                $('#myModal').modal('show');
                var table = $('#tampiltabelmodal').DataTable({
                    ordering: true,
                    paging: true,
                    pageLength: 10,
                    searching: true,
                    lengthChange: true,
                    scrollCollapse: true,
                    scrollX: true,
                });
            },
            complete: function() {
                $("#overlay").fadeOut(300);
            },
        });
    }

    function insertWorkPercentTest(){

        var idspko = $("#idspko").val();
        var data = {idspko: idspko};

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({
            url: "/Produksi/PelaporanProduksi/SPKO/insertWorkPercent",
            beforeSend: function() {
                openModal();
            },
            data: data,
            dataType: 'json',
            type: 'POST',
            success: function(data) {

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


    // OK
    function klikCetakBarcode() {
        var id = $("#idspko").val();
        var sw = $("#swspko").val();

        var dataUrl = `/Produksi/PelaporanProduksi/SPKO/cetakBarcode?id=${id}&sw=${sw}`;
        window.open(dataUrl, '_blank');
    }

    // OK
    function klikCetakGambar() {
        var id = $("#idspko").val();
        var sw = $("#swspko").val();

        var dataUrl = `/Produksi/PelaporanProduksi/SPKO/cetakGambar?id=${id}&sw=${sw}`;
        window.open(dataUrl, '_blank');
    }

    // OK
    function klikCetakBarcodeDirect() {
        var id = $("#idspko").val();
        var sw = $("#swspko").val();

        data = {id: id, sw: sw};
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({
            url: "/Produksi/PelaporanProduksi/SPKO/cetakBarcodeDirect",
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

    function klikBarcodeUnit() { //OK

        var barcodeunit = $("#barcodeunit").val();
        var carat = $("#kadar").val();
        var karyawan = $("#karyawan").val();
        var karyawanid = $("#karyawanid").val();
        var proses = $("#proses").val();
        var id = $("#barcodeunit").val();
    
        if (carat == "") {
            Swal.fire({
                icon: "warning",
                title: "Harap Pilih Kadar",
                timer: 2000,
                showCancelButton: false,
                showConfirmButton: true
            });
            document.getElementById("barcodeunit").value = "";

        } else if(proses == ""){
            Swal.fire({
                icon: "warning",
                title: "Harap Isi Proses!",
                timer: 2000,
                showCancelButton: false,
                showConfirmButton: true
            });
            document.getElementById("barcodeunit").value = "";

        } else if(karyawan == ""){
            Swal.fire({
                icon: "warning",
                title: "Harap Isi Karyawan",
                timer: 2000,
                showCancelButton: false,
                showConfirmButton: true
            });
            document.getElementById("barcodeunit").value = "";

        }else {
            if (barcodeunit == "") {
                Swal.fire({
                    icon: "warning",
                    title: "Harap Scan Barcode NTHKO",
                    timer: 2000,
                    showCancelButton: false,
                    showConfirmButton: true
                });
                document.getElementById("barcodeunit").value = "";

            } else {

                var data = {id: id, carat: carat, karyawanid: karyawanid};

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                $.ajax({
                    url: "/Produksi/PelaporanProduksi/SPKO/barcodeUnit",
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
                                title: "NTHKO Sudah Pernah Dibuatkan SPKO",
                                timer: 2000,
                                showCancelButton: false,
                                showConfirmButton: true
                            });
                            document.getElementById("barcodeunit").value = '';

                        } else if (data.status == 'Kosong') {
                            Swal.fire({
                                icon: "error",
                                title: "NTHKO NotFound",
                                timer: 2000,
                                showCancelButton: false,
                                showConfirmButton: true
                            });
                            document.getElementById("barcodeunit").value = '';

                        } else if (data.status == 'NotFound') {
                            Swal.fire({
                                icon: "error",
                                title: "NTHKO NotFound",
                                timer: 2000,
                                showCancelButton: false,
                                showConfirmButton: true
                            });
                            document.getElementById("barcodeunit").value = '';

                        } else if (data.status == 'SPK_T') {
                            Swal.fire({
                                icon: "error",
                                title: "SPK PPIC sudah dianggap selesai!",
                                timer: 2000,
                                showCancelButton: false,
                                showConfirmButton: true
                            });
                            document.getElementById("barcodeunit").value = '';

                        } else if (data.status == 'SPK_C') {
                            Swal.fire({
                                icon: "error",
                                title: "SPK PPIC sudah dianggap batal!",
                                timer: 2000,
                                showCancelButton: false,
                                showConfirmButton: true
                            });
                            document.getElementById("barcodeunit").value = '';

                        } else if (data.status == 'SPK_D') {
                            Swal.fire({
                                icon: "error",
                                title: "SPK PPIC sudah dianggap diskontinu!",
                                timer: 2000,
                                showCancelButton: false,
                                showConfirmButton: true
                            });
                            document.getElementById("barcodeunit").value = '';

                        } else {

                            // In DataTable, Row count and Column count both start at 0
                            var table = $('#tampiltabel').DataTable();
                            var rowCount = table.data().rows().count() + 1;
                       
                            // Looping through Returned JSON Data
                            var newrow = table.row.add([
                                '<td>' +
                                    '' + rowCount + '' +
                                    '<input type="hidden" id="WorkOrder'+rowCount+'" name="WorkOrder[]" value="'+data.WorkOrder+'">' +
                                    '<input type="hidden" id="FinishGood'+rowCount+'" name="FinishGood[]" value="'+data.FinishGood+'">' +
                                    '<input type="hidden" id="Carat'+rowCount+'" name="Carat[]" value="'+data.Carat+'">' +
                                    '<input type="hidden" id="TotalQty'+rowCount+'" name="TotalQty[]" value="'+data.TotalQty+'">' +
                                    '<input type="hidden" id="RubberPlate'+rowCount+'" name="RubberPlate[]" value="'+data.RubberPlate+'">' +
                                    '<input type="hidden" id="TreeID'+rowCount+'" name="TreeID[]" value="'+data.TreeID+'">' +
                                    '<input type="hidden" id="TreeOrd'+rowCount+'" name="TreeOrd[]" value="'+data.TreeOrd+'">' +
                                    '<input type="hidden" id="BatchNo'+rowCount+'" name="BatchNo[]" value="'+data.BatchNo+'">' +
                                    '<input type="hidden" id="PrevProcess'+rowCount+'" name="PrevProcess[]" value="'+data.PrevProcess+'">' +
                                    '<input type="hidden" id="PrevOrd'+rowCount+'" name="PrevOrd[]" value="'+data.PrevOrd+'">' +
                                    '<input type="hidden" id="FG'+rowCount+'" name="FG[]" value="'+data.FG+'">' +
                                    '<input type="hidden" id="Part'+rowCount+'" name="Part[]" value="'+data.Part+'">' +
                                    '<input type="hidden" id="RID'+rowCount+'" name="RID[]" value="'+data.RID+'">' +
                                    '<input type="hidden" id="OID'+rowCount+'" name="OID[]" value="'+data.OID+'">' +
                                    '<input type="hidden" id="OOrd'+rowCount+'" name="OOrd[]" value="'+data.OOrd+'">' +
                                '</td>',
                                '<td><button type="button" class="btn btn-danger btn-sm" onclick="remove(\''+rowCount+'\')"><i class="fa fa-minus"></i></button></td>',
                                '<td><input class="form-control-plaintext" type="text" style="text-align: center; color:black; font-size: 13px; font-weight: bold" id="WorkAllocation'+rowCount+'" name="WorkAllocation[]" value="'+data.WorkAllocation+'" onkeydown="handlerItem(event)" data-index="'+rowCount+'01" row-index="'+rowCount+'" readonly></td>',
                                '<td><input class="form-control-plaintext" type="text" style="text-align: center; color:black; font-size: 13px; font-weight: bold" id="Freq'+rowCount+'" name="Freq[]" value="'+data.Freq+'" onkeydown="handlerItem(event)" data-index="'+rowCount+'02" row-index="'+rowCount+'" readonly></td>',
                                '<td><input class="form-control-plaintext" type="text" style="text-align: center; color:black; font-size: 13px; font-weight: bold" id="Ordinal'+rowCount+'" name="Ordinal[]" value="'+data.Ordinal+'" onkeydown="handlerItem(event)" data-index="'+rowCount+'03" row-index="'+rowCount+'" readonly></td>',
                                '<td><input class="form-control" type="text" spellcheck="false" autocomplete="off" style="text-align: center; color:black; font-size: 13px; font-weight: bold" id="NoSPK'+rowCount+'" value="'+data.WorkOrder+'" onchange="cariSPK(this.value,'+rowCount+','+data.CaratID+')" onkeydown="handlerItem(event)" data-index="'+rowCount+'04" row-index="'+rowCount+'"></td>',
                                '<td><input class="form-control-plaintext" type="text" style="text-align: center; color:black; font-size: 13px; font-weight: bold" id="ProdukSPK'+rowCount+'" value="'+data.FinishGood+'" onkeydown="handlerItem(event)" data-index="'+rowCount+'05" row-index="'+rowCount+'" readonly></td>',
                                '<td><input class="form-control-plaintext" type="text" style="text-align: center; color:black; font-size: 13px; font-weight: bold" id="Kadar'+rowCount+'" value="'+data.Carat+'" onkeydown="handlerItem(event)" data-index="'+rowCount+'06" row-index="'+rowCount+'" readonly></td>',
                                '<td><input class="form-control-plaintext" type="text" style="text-align: center; color:black; font-size: 13px; font-weight: bold" id="JmlSPK'+rowCount+'" value="'+data.TotalQty+'" onkeydown="handlerItem(event)" data-index="'+rowCount+'07" row-index="'+rowCount+'" readonly></td>',
                                '<td>' +
                                    '<input class="form-control" type="text" spellcheck="false" autocomplete="off" style="text-align: center; color:black; font-size: 13px; font-weight: bold" id="Product'+rowCount+'" value="'+data.Product+'" onchange="cariProduct(this.value,'+rowCount+','+data.CaratID+')" onkeydown="handlerItem(event)" data-index="'+rowCount+'08" row-index="'+rowCount+'">' +
                                    '<input type="hidden" id="PID'+rowCount+'" name="PID[]" value="'+data.PID+'">' +
                                '</td>',
                                '<td>'+data.Qty+'</td>',
                                '<td>'+data.Weight+'</td>',
                                '<td><input class="form-control" type="text" autocomplete="off" style="text-align: center; color:black; font-size: 13px; width: 100%; font-weight: bold" id="Qty'+rowCount+'" name="Qty[]" value="'+data.Qty+'" onchange="refresh_sum_qty('+rowCount+')" onkeydown="handlerItem(event)" data-index="'+rowCount+'09" row-index="'+rowCount+'"></td>',
                                '<td>' +
                                    '<div class="input-group" style="width: 100%;">' +
                                        '<input class="form-control" type="text" autocomplete="off" style="text-align: center; color:black; font-size: 13px; font-weight: bold;" id="Weight'+rowCount+'" name="Weight[]" value="'+data.Weight+'" onchange="refresh_sum_weight('+rowCount+')" onkeydown="handlerItem(event)" data-index="'+rowCount+'10" row-index="'+rowCount+'">' +
                                        '<button type="button" class="btn btn-info btn-sm" onclick="klikTimbangRun(\'Weight' + rowCount + '\','+rowCount+')"><i class="fa fa-balance-scale"></i></button>' +
                                    '</div>' +
                                '</td>',
                                '<td><input class="form-control" type="text" autocomplete="off" style="text-align: center; color:black; font-size: 13px; width: 100%; font-weight: bold" id="StoneLoss'+rowCount+'" name="StoneLoss[]" value="'+data.StoneLoss+'" onkeydown="handlerItem(event)" data-index="'+rowCount+'11" row-index="'+rowCount+'"></td>',
                                '<td><input class="form-control" type="text" autocomplete="off" style="text-align: center; color:black; font-size: 13px; width: 100%; font-weight: bold" id="QtyLossStone'+rowCount+'" name="QtyLossStone[]" value="'+data.QtyLossStone+'" onkeydown="handlerItem(event)" data-index="'+rowCount+'12" row-index="'+rowCount+'"></td>',
                                '<td><input class="form-control" type="text" autocomplete="off" style="text-align: center; color:black; font-size: 13px; width: 100%; font-weight: bold" id="BarcodeNote'+rowCount+'" name="BarcodeNote[]" value="'+data.BarcodeNote+'" onchange="notecopy('+rowCount+')" onkeydown="handlerItem(event)" data-index="'+rowCount+'13" row-index="'+rowCount+'"></td>',
                                '<td><input class="form-control" type="text" autocomplete="off" style="text-align: center; color:black; font-size: 13px; width: 100%; font-weight: bold" id="Note'+rowCount+'" name="Note[]" value="'+data.Note+'" onkeydown="handlerItem(event)" data-index="'+rowCount+'14" row-index="'+rowCount+'"></td>',
                                '<td><input class="form-control-plaintext" type="text" style="text-align: center; color:black; font-size: 13px; font-weight: bold" id="NoPohon'+rowCount+'" value="'+data.RubberPlate+'" onkeydown="handlerItem(event)" data-index="'+rowCount+'15" row-index="'+rowCount+'" readonly></td>',
                                '<td><input class="form-control-plaintext" type="text" style="text-align: center; color:black; font-size: 13px; font-weight: bold" id="ProdukDetail'+rowCount+'" value="'+data.ProductDetail+'" onkeydown="handlerItem(event)" data-index="'+rowCount+'16" row-index="'+rowCount+'" readonly></td>',
                                '<td><input class="form-control-plaintext" type="text" style="text-align: center; color:black; font-size: 13px; font-weight: bold" id="PohonID'+rowCount+'" value="'+data.TreeID+'" onkeydown="handlerItem(event)" data-index="'+rowCount+'17" row-index="'+rowCount+'" readonly></td>',
                                '<td><input class="form-control-plaintext" type="text" style="text-align: center; color:black; font-size: 13px; font-weight: bold" id="PohonUrut'+rowCount+'" value="'+data.TreeOrd+'" onkeydown="handlerItem(event)" data-index="'+rowCount+'18" row-index="'+rowCount+'" readonly></td>',
                                '<td><input class="form-control-plaintext" type="text" style="text-align: center; color:black; font-size: 13px; font-weight: bold" id="Batch'+rowCount+'" value="'+data.BatchNo+'" onkeydown="handlerItem(event)" data-index="'+rowCount+'19" row-index="'+rowCount+'" readonly></td>',
                                '<td>'+data.TotalTime+'</td>',
                                '<td>'+data.Persen+'</td>',
                            ]);

                            // Give Attribute to Each Row
                            table.row(newrow).nodes().to$().attr('id', 'myRow' + rowCount);
                            table.row(newrow).nodes().to$().attr('onclick','tampilGambar(\''+data.ProductPhoto+'\')');

                            // Draw Table
                            table.draw(); // Lebih Cepat Draw Table Setelah Load Diatas

                            // Make Column Table Visible/Not
                            table.columns(10).visible(false);
                            table.columns(11).visible(false);
                            table.columns(23).visible(false);
                            table.columns(24).visible(false);

                            // Get Sum Data of Qty and Weight at Column 10 and Column 11
                            var qtydata = table.column(10).data().sum();
                            var weightdata = table.column(11).data().sum();

                            // Get Value of Element with ID Below
                            var initTotalTime = $("#initialworktotalsecond").val();
                            var initPersen = $("#initialworkpercent").val();

                            // Get Sum Data of TotalTime and Percent at Column 23 and Column 24
                            var totaltimedata = table.column(23).data().sum();
                            var persendata = table.column(24).data().sum();

                            // Calculate Value of Above Data
                            var sumTotalTime = Number(initTotalTime)+totaltimedata;
                            var sumPersen = Number(initPersen)+persendata;

                            // Give Attribute to Each Row with Margin=0 and Padding=0 , using Built-in Bootstrap Class
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

                            // Set Properties HTML Element of the Page
                            document.getElementById("tampiltabel").style.color = "black";
                            document.getElementById("tampiltabel").style.fontSize = "13px";
                            document.getElementById("tampiltabel").style.fontWeight = "bold";
                            document.getElementById("tampiltabel").style.textAlign = "center";

                            document.getElementById("showgambar").src = "http://192.168.3.100:8383/image2/NO-IMAGE.jpg";
                            document.getElementById("barcodeunit").value = "";
                            document.getElementById("barcodeunit").focus();

                            document.getElementById("qtyspkolabel").innerHTML = String(qtydata);
                            document.getElementById("weightspkolabel").innerHTML = String(weightdata.toFixed(2));

                            document.getElementById("qtyspko").value = qtydata;
                            document.getElementById("weightspko").value = weightdata.toFixed(2);

                            document.getElementById("tempworktotalsecond").value = sumTotalTime;
                            document.getElementById("tempworkpercent").value = sumPersen.toFixed(2);
                          
                            document.getElementById("worktotalsecond").innerHTML = String(sumTotalTime);
                            document.getElementById("workpercent").innerHTML = String(sumPersen.toFixed(2));

                            tampilGambar(data.ProductPhoto);
                    
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

    function klikBarcodeAll() { //OK

        var barcodeall = $("#barcodeall").val();
        var carat = $("#kadar").val();
        var karyawan = $("#karyawan").val();
        var karyawanid = $("#karyawanid").val();
        var proses = $("#proses").val();
        var id = $("#barcodeall").val();

        if (carat == "") {
            Swal.fire({
                icon: "warning",
                title: "Harap Isi Kadar",
                timer: 2000,
                showCancelButton: false,
                showConfirmButton: true
            });
            document.getElementById("barcodeall").value = "";

        } else if(proses == ""){
            Swal.fire({
                icon: "warning",
                title: "Harap Isi Proses!",
                timer: 2000,
                showCancelButton: false,
                showConfirmButton: true
            });
            document.getElementById("barcodeall").value = "";

        } else if(karyawan == ""){
            Swal.fire({
                icon: "warning",
                title: "Harap Isi Karyawan!",
                timer: 2000,
                showCancelButton: false,
                showConfirmButton: true
            });
            document.getElementById("barcodeall").value = "";

        } else {
            if (barcodeall == "") {
                Swal.fire({
                    icon: "warning",
                    title: "Harap Scan Barcode NTHKO!",
                    timer: 2000,
                    showCancelButton: false,
                    showConfirmButton: true
                });
                document.getElementById("barcodeall").value = "";

            } else {
                
                var data = {id: id, carat: carat, proses: proses, karyawanid: karyawanid};

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                $.ajax({
                    url: "/Produksi/PelaporanProduksi/SPKO/barcodeAll",
                    beforeSend: function() {
                        openModal();
                    },
                    data: data,
                    dataType: 'json',
                    type: 'POST',
                    success: function(data) {

                        if (data.status == 'NotFound') {
                            Swal.fire({
                                icon: "error",
                                title: "NTHKO NotFound",
                                timer: 2000,
                                showCancelButton: false,
                                showConfirmButton: true
                            });
                            document.getElementById("barcodeall").value = '';

                        } else {

                            // In DataTable, Row count and Column count both start at 0
                            var table = $('#tampiltabel').DataTable();
                            var rowCount = table.data().rows().count() + 1;
       
                            // Looping through Returned JSON Data
                            for(var i=0; i < data.baris; i++){
                           
                                var newrow = table.row.add([
                                    '<td>' +
                                        '' + rowCount + '' +
                                        '<input type="hidden" id="WorkOrder'+rowCount+'" name="WorkOrder[]" value="'+data.WorkOrder[i]+'">' +
                                        '<input type="hidden" id="FinishGood'+rowCount+'" name="FinishGood[]" value="'+data.FinishGood[i]+'">' +
                                        '<input type="hidden" id="Carat'+rowCount+'" name="Carat[]" value="'+data.Carat[i]+'">' +
                                        '<input type="hidden" id="TotalQty'+rowCount+'" name="TotalQty[]" value="'+data.TotalQty[i]+'">' +
                                        '<input type="hidden" id="RubberPlate'+rowCount+'" name="RubberPlate[]" value="'+data.RubberPlate[i]+'">' +
                                        '<input type="hidden" id="TreeID'+rowCount+'" name="TreeID[]" value="'+data.TreeID[i]+'">' +
                                        '<input type="hidden" id="TreeOrd'+rowCount+'" name="TreeOrd[]" value="'+data.TreeOrd[i]+'">' +
                                        '<input type="hidden" id="BatchNo'+rowCount+'" name="BatchNo[]" value="'+data.BatchNo[i]+'">' +
                                        '<input type="hidden" id="PrevProcess'+rowCount+'" name="PrevProcess[]" value="'+data.PrevProcess[i]+'">' +
                                        '<input type="hidden" id="PrevOrd'+rowCount+'" name="PrevOrd[]" value="'+data.PrevOrd[i]+'">' +
                                        '<input type="hidden" id="FG'+rowCount+'" name="FG[]" value="'+data.FG[i]+'">' +
                                        '<input type="hidden" id="Part'+rowCount+'" name="Part[]" value="'+data.Part[i]+'">' +
                                        '<input type="hidden" id="RID'+rowCount+'" name="RID[]" value="'+data.RID[i]+'">' +
                                        '<input type="hidden" id="OID'+rowCount+'" name="OID[]" value="'+data.OID[i]+'">' +
                                        '<input type="hidden" id="OOrd'+rowCount+'" name="OOrd[]" value="'+data.OOrd[i]+'">' +
                                    '</td>',
                                    '<td><button type="button" class="btn btn-danger btn-sm" onclick="remove(\''+rowCount+'\')"><i class="fa fa-minus"></i></button></td>',
                                    '<td><input class="form-control-plaintext" type="text" style="text-align: center; color:black; font-size: 13px; font-weight: bold" id="WorkAllocation'+rowCount+'" name="WorkAllocation[]" value="'+data.WorkAllocation[i]+'" onkeydown="handlerItem(event)" data-index="'+rowCount+'01" row-index="'+rowCount+'" readonly></td>',
                                    '<td><input class="form-control-plaintext" type="text" style="text-align: center; color:black; font-size: 13px; font-weight: bold" id="Freq'+rowCount+'" name="Freq[]" value="'+data.Freq[i]+'" onkeydown="handlerItem(event)" data-index="'+rowCount+'02" row-index="'+rowCount+'" readonly></td>',
                                    '<td><input class="form-control-plaintext" type="text" style="text-align: center; color:black; font-size: 13px; font-weight: bold" id="Ordinal'+rowCount+'" name="Ordinal[]" value="'+data.Ordinal[i]+'" onkeydown="handlerItem(event)" data-index="'+rowCount+'03" row-index="'+rowCount+'" readonly></td>',
                                    '<td><input class="form-control" type="text" spellcheck="false" style="text-align: center; color:black; font-size: 13px; font-weight: bold" id="NoSPK'+rowCount+'" value="'+data.WorkOrder[i]+'" onchange="cariSPK(this.value,'+rowCount+','+data.CaratID+')" onkeydown="handlerItem(event)" data-index="'+rowCount+'04" row-index="'+rowCount+'"></td>',
                                    '<td><input class="form-control-plaintext" type="text" style="text-align: center; color:black; font-size: 13px; font-weight: bold" id="ProdukSPK'+rowCount+'" value="'+data.FinishGood[i]+'" onkeydown="handlerItem(event)" data-index="'+rowCount+'05" row-index="'+rowCount+'" readonly></td>',
                                    '<td><input class="form-control-plaintext" type="text" style="text-align: center; color:black; font-size: 13px; font-weight: bold" id="Kadar'+rowCount+'" value="'+data.Carat[i]+'" onkeydown="handlerItem(event)" data-index="'+rowCount+'06" row-index="'+rowCount+'" readonly></td>',
                                    '<td><input class="form-control-plaintext" type="text" style="text-align: center; color:black; font-size: 13px; font-weight: bold" id="JmlSPK'+rowCount+'" value="'+data.TotalQty[i]+'" onkeydown="handlerItem(event)" data-index="'+rowCount+'07" row-index="'+rowCount+'" readonly></td>',
                                    '<td>' +
                                        '<input class="form-control" type="text" spellcheck="false" style="text-align: center; color:black; font-size: 13px; font-weight: bold" id="Product'+rowCount+'" value="'+data.Product[i]+'" onchange="cariProduct(this.value,'+rowCount+','+data.CaratID+')" onkeydown="handlerItem(event)" data-index="'+rowCount+'08" row-index="'+rowCount+'">' +
                                        '<input type="hidden" id="PID'+rowCount+'" name="PID[]" value="'+data.PID[i]+'">' +
                                    '</td>',
                                    '<td>'+data.Qty[i]+'</td>',
                                    '<td>'+data.Weight[i]+'</td>',
                                    '<td><input class="form-control" type="text" style="text-align: center; color:black; font-size: 13px; width: 100%; font-weight: bold" id="Qty'+rowCount+'" name="Qty[]" value="'+data.Qty[i]+'" onchange="refresh_sum_qty('+rowCount+')" onkeydown="handlerItem(event)" data-index="'+rowCount+'09" row-index="'+rowCount+'"></td>',
                                    '<td>' +
                                        '<div class="input-group" style="width: 100%;">' +
                                            '<input class="form-control" type="text" style="text-align: center; color:black; font-size: 13px; font-weight: bold" id="Weight'+rowCount+'" name="Weight[]" value="'+data.Weight[i]+'" onchange="refresh_sum_weight('+rowCount+')" onkeydown="handlerItem(event)" data-index="'+rowCount+'10" row-index="'+rowCount+'">' +
                                            '<button type="button" class="btn btn-info btn-sm" onclick="klikTimbangRun(\'Weight' + rowCount + '\','+rowCount+')"><i class="fa fa-balance-scale"></i></button>' +
                                        '</div>' +
                                    '</td>',
                                    '<td><input class="form-control" type="text" style="text-align: center; color:black; font-size: 13px; width: 100%; font-weight: bold" id="StoneLoss'+rowCount+'" name="StoneLoss[]" value="'+data.StoneLoss[i]+'" onkeydown="handlerItem(event)" data-index="'+rowCount+'11" row-index="'+rowCount+'"></td>',
                                    '<td><input class="form-control" type="text" style="text-align: center; color:black; font-size: 13px; width: 100%; font-weight: bold" id="QtyLossStone'+rowCount+'" name="QtyLossStone[]" value="'+data.QtyLossStone[i]+'" onkeydown="handlerItem(event)" data-index="'+rowCount+'12" row-index="'+rowCount+'"></td>',
                                    '<td><input class="form-control" type="text" style="text-align: center; color:black; font-size: 13px; width: 100%; font-weight: bold" id="BarcodeNote'+rowCount+'" name="BarcodeNote[]" value="'+data.BarcodeNote[i]+'" onchange="notecopy('+rowCount+')" onkeydown="handlerItem(event)" data-index="'+rowCount+'13" row-index="'+rowCount+'"></td>',
                                    '<td><input class="form-control" type="text" style="text-align: center; color:black; font-size: 13px; width: 100%; font-weight: bold" id="Note'+rowCount+'" name="Note[]" value="'+data.Note[i]+'" onkeydown="handlerItem(event)" data-index="'+rowCount+'14" row-index="'+rowCount+'"></td>',
                                    '<td><input class="form-control-plaintext" type="text" style="text-align: center; color:black; font-size: 13px; font-weight: bold" id="NoPohon'+rowCount+'" value="'+data.RubberPlate[i]+'" onkeydown="handlerItem(event)" data-index="'+rowCount+'15" row-index="'+rowCount+'" readonly></td>',
                                    '<td><input class="form-control-plaintext" type="text" style="text-align: center; color:black; font-size: 13px; font-weight: bold" id="ProdukDetail'+rowCount+'" value="'+data.ProductDetail[i]+'" onkeydown="handlerItem(event)" data-index="'+rowCount+'16" row-index="'+rowCount+'" readonly></td>',
                                    '<td><input class="form-control-plaintext" type="text" style="text-align: center; color:black; font-size: 13px; font-weight: bold" id="PohonID'+rowCount+'" value="'+data.TreeID[i]+'" onkeydown="handlerItem(event)" data-index="'+rowCount+'17" row-index="'+rowCount+'" readonly></td>',
                                    '<td><input class="form-control-plaintext" type="text" style="text-align: center; color:black; font-size: 13px; font-weight: bold" id="PohonUrut'+rowCount+'" value="'+data.TreeOrd[i]+'" onkeydown="handlerItem(event)" data-index="'+rowCount+'18" row-index="'+rowCount+'" readonly></td>',
                                    '<td><input class="form-control-plaintext" type="text" style="text-align: center; color:black; font-size: 13px; font-weight: bold" id="Batch'+rowCount+'" value="'+data.BatchNo[i]+'" onkeydown="handlerItem(event)" data-index="'+rowCount+'19" row-index="'+rowCount+'" readonly></td>',
                                    '<td>'+data.TotalTime[i]+'</td>',
                                    '<td>'+data.Persen[i]+'</td>',
                                ]);
                                
                                // Give Attribute to Each Row
                                table.row(newrow).nodes().to$().attr('id', 'myRow' + rowCount);
                                table.row(newrow).nodes().to$().attr('onclick','tampilGambar(\''+data.ProductPhoto[i]+'\')');

                                rowCount++;

                            }

                            // Draw Table
                            table.draw(); // Lebih Cepat Draw Table Setelah Load Diatas

                            // Make Column Table Visible/Not
                            table.columns(10).visible(false);
                            table.columns(11).visible(false);
                            table.columns(23).visible(false);
                            table.columns(24).visible(false);

                            // Get Sum Data of Qty and Weight at Column 10 and Column 11
                            var qtydata = table.column(10).data().sum();
                            var weightdata = table.column(11).data().sum();

                            // Get Value of Element with ID Below
                            var initTotalTime = $("#initialworktotalsecond").val();
                            var initPersen = $("#initialworkpercent").val();

                            // Get Sum Data of TotalTime and Percent at Column 23 and Column 24
                            var totaltimedata = table.column(23).data().sum();
                            var persendata = table.column(24).data().sum();

                            // Calculate Value of Above Data
                            var sumTotalTime = Number(initTotalTime)+totaltimedata;
                            var sumPersen = Number(initPersen)+persendata;

                            // Give Attribute to Each Row with Margin=0 and Padding=0 , using Built-in Bootstrap Class
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

                            // Set Properties HTML Element of the Page
                            document.getElementById("tampiltabel").style.color = "black";
                            document.getElementById("tampiltabel").style.fontSize = "13px";
                            document.getElementById("tampiltabel").style.fontWeight = "bold";
                            document.getElementById("tampiltabel").style.textAlign = "center";

                            document.getElementById("showgambar").src = "http://192.168.3.100:8383/image2/NO-IMAGE.jpg";
                            document.getElementById("barcodeall").value = "";

                            document.getElementById("qtyspkolabel").innerHTML = String(qtydata);
                            document.getElementById("weightspkolabel").innerHTML = String(weightdata.toFixed(2));

                            document.getElementById("qtyspko").value = qtydata;
                            document.getElementById("weightspko").value = weightdata.toFixed(2);

                            document.getElementById("tempworktotalsecond").value = sumTotalTime;
                            document.getElementById("tempworkpercent").value = sumPersen.toFixed(2);
                            
                            document.getElementById("worktotalsecond").innerHTML = String(sumTotalTime);
                            document.getElementById("workpercent").innerHTML = String(sumPersen.toFixed(2));

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

    function klikAddRow() { //OK

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

            // In DataTable, Row count and Column count both start at 0
            var table = $('#tampiltabel').DataTable();
            var rowCount = table.data().rows().count() + 1;

            // Create New Row
            var newrow = table.row.add([
                '<td>' +
                    '' + rowCount + '' +
                    '<input type="hidden" id="WorkOrder'+rowCount+'" name="WorkOrder[]" value="">' +
                    '<input type="hidden" id="FinishGood'+rowCount+'" name="FinishGood[]" value="">' +
                    '<input type="hidden" id="Carat'+rowCount+'" name="Carat[]" value="">' +
                    '<input type="hidden" id="TotalQty'+rowCount+'" name="TotalQty[]" value="">' +
                    '<input type="hidden" id="RubberPlate'+rowCount+'" name="RubberPlate[]" value="">' +
                    '<input type="hidden" id="TreeID'+rowCount+'" name="TreeID[]" value="">' +
                    '<input type="hidden" id="TreeOrd'+rowCount+'" name="TreeOrd[]" value="">' +
                    '<input type="hidden" id="BatchNo'+rowCount+'" name="BatchNo[]" value="">' +
                    '<input type="hidden" id="PrevProcess'+rowCount+'" name="PrevProcess[]" value="">' +
                    '<input type="hidden" id="PrevOrd'+rowCount+'" name="PrevOrd[]" value="">' +
                    '<input type="hidden" id="FG'+rowCount+'" name="FG[]" value="">' +
                    '<input type="hidden" id="Part'+rowCount+'" name="Part[]" value="">' +
                    '<input type="hidden" id="RID'+rowCount+'" name="RID[]" value="">' +
                    '<input type="hidden" id="OID'+rowCount+'" name="OID[]" value="">' +
                    '<input type="hidden" id="OOrd'+rowCount+'" name="OOrd[]" value="">' +
                '</td>',
                '<td><button type="button" class="btn btn-danger btn-sm" onclick="remove(\''+rowCount+'\')"><i class="fa fa-minus"></i></button></td>',
                '<td><input class="form-control-plaintext" type="text" style="text-align: center; color:black; font-size: 13px; font-weight: bold" id="WorkAllocation'+rowCount+'" name="WorkAllocation[]" value="" onkeydown="handlerItem(event)" data-index="'+rowCount+'01" row-index="'+rowCount+'" readonly></td>',
                '<td><input class="form-control-plaintext" type="text" style="text-align: center; color:black; font-size: 13px; font-weight: bold" id="Freq'+rowCount+'" name="Freq[]" value="" onkeydown="handlerItem(event)" data-index="'+rowCount+'02" row-index="'+rowCount+'" readonly></td>',
                '<td><input class="form-control-plaintext" type="text" style="text-align: center; color:black; font-size: 13px; font-weight: bold" id="Ordinal'+rowCount+'" name="Ordinal[]" value="" onkeydown="handlerItem(event)" data-index="'+rowCount+'03" row-index="'+rowCount+'" readonly></td>',
                '<td><input class="form-control" type="text" spellcheck="false" autocomplete="off" style="text-align: center; color:black; font-size: 13px; font-weight: bold" id="NoSPK'+rowCount+'" value="" onchange="cariSPK(this.value,'+rowCount+','+kadar+')" onkeydown="handlerItem(event)" data-index="'+rowCount+'04" row-index="'+rowCount+'"></td>',
                '<td><input class="form-control-plaintext" type="text" style="text-align: center; color:black; font-size: 13px; font-weight: bold" id="ProdukSPK'+rowCount+'" value="" onkeydown="handlerItem(event)" data-index="'+rowCount+'05" row-index="'+rowCount+'" readonly></td>',
                '<td><input class="form-control-plaintext" type="text" style="text-align: center; color:black; font-size: 13px; font-weight: bold" id="Kadar'+rowCount+'" value="" onkeydown="handlerItem(event)" data-index="'+rowCount+'06" row-index="'+rowCount+'" readonly></td>',
                '<td><input class="form-control-plaintext" type="text" style="text-align: center; color:black; font-size: 13px; font-weight: bold" id="JmlSPK'+rowCount+'" value="" onkeydown="handlerItem(event)" data-index="'+rowCount+'07" row-index="'+rowCount+'" readonly></td>',
                '<td>' +
                    '<input class="form-control" type="text" spellcheck="false" autocomplete="off" style="text-align: center; color:black; font-size: 13px; font-weight: bold" id="Product'+rowCount+'" value="" onchange="cariProduct(this.value,'+rowCount+','+kadar+')" onkeydown="handlerItem(event)" data-index="'+rowCount+'08" row-index="'+rowCount+'">' +
                    '<input type="hidden" id="PID'+rowCount+'" name="PID[]" value="">' +
                '</td>',
                '<td>'+0+'</td>',
                '<td>'+0+'</td>',
                '<td><input class="form-control" type="text" autocomplete="off" style="text-align: center; color:black; font-size: 13px; width: 100%; font-weight: bold" id="Qty'+rowCount+'" name="Qty[]" value="" onchange="refresh_sum_qty('+rowCount+')" onkeydown="handlerItem(event)" data-index="'+rowCount+'09" row-index="'+rowCount+'"></td>',
                '<td>' +
                    '<div class="input-group" style="width: 100%;">' +
                        '<input class="form-control" type="text" autocomplete="off" style="text-align: center; color:black; font-size: 13px; font-weight: bold" id="Weight'+rowCount+'" name="Weight[]" value="" onchange="refresh_sum_weight('+rowCount+')" onkeydown="handlerItem(event)" data-index="'+rowCount+'10" row-index="'+rowCount+'">' +
                        '<button type="button" class="btn btn-info btn-sm" onclick="klikTimbangRun(\'Weight' + rowCount + '\','+rowCount+')"><i class="fa fa-balance-scale"></i></button>' +
                    '</div>' +
                '</td>',
                '<td><input class="form-control" type="text" autocomplete="off" style="text-align: center; color:black; font-size: 13px; width: 100%; font-weight: bold" id="StoneLoss'+rowCount+'" name="StoneLoss[]" value="" onkeydown="handlerItem(event)" data-index="'+rowCount+'11" row-index="'+rowCount+'"></td>',
                '<td><input class="form-control" type="text" autocomplete="off" style="text-align: center; color:black; font-size: 13px; width: 100%; font-weight: bold" id="QtyLossStone'+rowCount+'" name="QtyLossStone[]" value="" onkeydown="handlerItem(event)" data-index="'+rowCount+'12" row-index="'+rowCount+'"></td>',
                '<td><input class="form-control" type="text" autocomplete="off" style="text-align: center; color:black; font-size: 13px; width: 100%; font-weight: bold" id="BarcodeNote'+rowCount+'" name="BarcodeNote[]" value="" onchange="notecopy('+rowCount+')" onkeydown="handlerItem(event)" data-index="'+rowCount+'13" row-index="'+rowCount+'"></td>',
                '<td><input class="form-control" type="text" autocomplete="off" style="text-align: center; color:black; font-size: 13px; width: 100%; font-weight: bold" id="Note'+rowCount+'" name="Note[]" value="" onkeydown="handlerItem(event)" data-index="'+rowCount+'14" row-index="'+rowCount+'"></td>',
                '<td><input class="form-control-plaintext" type="text" style="text-align: center; color:black; font-size: 13px; font-weight: bold" id="NoPohon'+rowCount+'" value="" onkeydown="handlerItem(event)" data-index="'+rowCount+'15" row-index="'+rowCount+'" readonly></td>',
                '<td><input class="form-control-plaintext" type="text" style="text-align: center; color:black; font-size: 13px; font-weight: bold" id="ProdukDetail'+rowCount+'" value="" onkeydown="handlerItem(event)" data-index="'+rowCount+'16" row-index="'+rowCount+'" readonly></td>',
                '<td><input class="form-control-plaintext" type="text" style="text-align: center; color:black; font-size: 13px; font-weight: bold" id="PohonID'+rowCount+'" value="" onkeydown="handlerItem(event)" data-index="'+rowCount+'17" row-index="'+rowCount+'" readonly></td>',
                '<td><input class="form-control-plaintext" type="text" style="text-align: center; color:black; font-size: 13px; font-weight: bold" id="PohonUrut'+rowCount+'" value="" onkeydown="handlerItem(event)" data-index="'+rowCount+'18" row-index="'+rowCount+'" readonly></td>',
                '<td><input class="form-control-plaintext" type="text" style="text-align: center; color:black; font-size: 13px; font-weight: bold" id="Batch'+rowCount+'" value="" onkeydown="handlerItem(event)" data-index="'+rowCount+'19" row-index="'+rowCount+'" readonly></td>',
                '<td>'+0+'</td>',
                '<td>'+0+'</td>',
            ]);

            // Give Attribute to Each Row
            table.row(newrow).nodes().to$().attr('id', 'myRow' + rowCount);
            table.row(newrow).column(10).nodes().to$().attr('id', 'QtySum' + rowCount);
            table.row(newrow).column(11).nodes().to$().attr('id', 'WeightSum' + rowCount);

            // Draw Table
            table.draw(); // Lebih Cepat Draw Table Setelah Load Diatas

            // Make Column Table Visible/Not
            table.columns(10).visible(false);
            table.columns(11).visible(false);
            table.columns(23).visible(false);
            table.columns(24).visible(false);

            // Give Attribute to Each Row with Margin=0 and Padding=0 , using Built-in Bootstrap Class
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

            // Set Properties HTML Element of the Page
            document.getElementById("tampiltabel").style.color = "black";
            document.getElementById("tampiltabel").style.fontSize = "13px";
            document.getElementById("tampiltabel").style.fontWeight = "bold";
            document.getElementById("tampiltabel").style.textAlign = "center";

            selectItem("NoSPK",rowCount);

        }

    }

    function cariSPK(sw, no, carat) { //OK

        var no = no;
        var sw = sw;
        var carat = carat;

        var data = {sw: sw, carat: carat};

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({
            url: '/Produksi/PelaporanProduksi/SPKO/cariSPK',
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
                    document.getElementById("OID" + no).value = data.WorkOrder;
                    document.getElementById("ProdukSPK" + no).value = data.ProductName;
                    document.getElementById("JmlSPK" + no).value = data.TotalQty;
                    document.getElementById("Kadar" + no).value = data.Kadar;
                    document.getElementById("Carat" + no).value = data.Carat;

                } else {
                    Swal.fire({
                        icon: "error",
                        title: "SPK NotFound",
                        timer: 2000,
                        showCancelButton: false,
                        showConfirmButton: true
                    });
                    document.getElementById("NoSPK" + no).focus();
                    document.getElementById("NoSPK" + no).value = '';
                    document.getElementById("WorkOrder" + no).value = '';
                    document.getElementById("OID" + no).value = '';
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

    function cariProduct(sw, no, carat) { //OK

        var no = no;
        var sw = sw;
        var carat = carat;

        var data = {sw: sw, carat: carat};

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({
            url: '/Produksi/PelaporanProduksi/SPKO/cariProduct',
            beforeSend: function() {
                openModal();
            },
            data: data,
            dataType: 'json',
            type: 'POST',
            success: function(data) {

                if (data.rowcount > 0) {
                    if (data.UseCarat == 'Y') {
                        document.getElementById("Product" + no).value = data.ProductLabel;
                        document.getElementById("PID" + no).value = data.Product;
                        document.getElementById("Kadar" + no).value = data.caratName;
                        document.getElementById("Carat" + no).value = data.caratID;
                        document.getElementById("RID" + no).value = data.caratID;

                    } else if (data.UseCarat == 'N') {
                        document.getElementById("Product" + no).value = data.ProductLabel;
                        document.getElementById("PID" + no).value = data.Product;
                        document.getElementById("Kadar" + no).value = '';
                        document.getElementById("Carat" + no).value = 'NULL';
                        document.getElementById("RID" + no).value = 'NULL';

                    }

                } else {
                    Swal.fire({
                        icon: "error",
                        title: "Barang NotFound",
                        timer: 2000,
                        showCancelButton: false,
                        showConfirmButton: true
                    });
                    document.getElementById("Product" + no).focus();
                    document.getElementById("Product" + no).value = '';
                    document.getElementById("PID" + no).value = '';
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


    // TEST
    function testInsertWorkPercent() {

        var idspko = $("#idspko").val();
        data = {idspko: idspko};

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({
            url: '/Produksi/PelaporanProduksi/SPKO/testInsertWorkPercent',
            beforeSend: function() {
                openModal();
            },
            // data: data,
            data: $('#tampilform, #tampilform2').serialize(),
            dataType: 'json',
            type: 'POST',
            success: function(data) {
                if(data.status == true){
                    console.log('Success');
                }else{
                    console.log('Failed');
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

    </script>
@endsection
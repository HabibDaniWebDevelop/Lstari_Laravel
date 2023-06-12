<?php $title = 'Transfer Material'; ?>
@extends('layouts.backend-Theme-3.app')
@section('title', '$title')

@section('Dashboard')
    <h2 class="m-0">{{ $title }}</h2>
    <ol class="breadcrumb sm-2">
        <li class="breadcrumb-item"><a href="/">Home </a></li>
        <li class="breadcrumb-item">Produksi </li>
        <li class="breadcrumb-item">Pelaporan Produksi </li>
        <li class="breadcrumb-item active">Transfer Material</li>
    </ol>
@endsection

@section('css')
    <style>
        .bootstrap-select.form-control.input-group-btn {
            z-index: auto;
        }
        #scroll-btn {
            display: none;
            position: fixed;
            bottom: 20px;
            right: 30px;
            z-index: 99;
            font-size: 18px;
            border: none;
            outline: none;
            background-color: blue;
            color: white;
            cursor: pointer;
            padding: 15px 19px;
            border-radius: 100px;
        }
        #scroll-btn:hover {
            background-color: black;
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
                @include('Produksi.PelaporanProduksi.TransferMaterial.data')
            </div>
        </div>
    </div>
    <button onclick="topFunction()" id="scroll-btn" title="Top">&uarr;</button>
@endsection

@section('script')

    @include('layouts.backend-Theme-3.DataTabelButton')
    @include('layouts.backend-Theme-3.timbangan')
    <script src="{!! asset('assets/almas/sum().js') !!}"></script>

    <script>

    window.onscroll = function() {scrollFunction()};
 
    function scrollFunction() {
        if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
            document.getElementById("scroll-btn").style.display = "block";
        } else {
            document.getElementById("scroll-btn").style.display = "none";
        }
    }
 
    function topFunction() {
        document.body.scrollTop = 0;
        document.documentElement.scrollTop = 0;
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

    // $(document).ready(function(){
    //     $('#btntest').click(function(){

    //         var cek = 1;

    //         $('select').each(function(){

    //             if($(this).val() == ''){

    //                 var attrName = $(this).attr('name');

    //                 if(attrName == 'operation[]'){
    //                     var attrData = 'Proses'
    //                 }else if(attrName == 'level2[]'){
    //                     var attrData = 'SubProses1'
    //                 }else if(attrName == 'level3[]'){
    //                     var attrData = 'SubProses2'
    //                 }else if(attrName == 'level4[]'){
    //                     var attrData = 'SubProses3'
    //                 }

    //                 alert('Tolong Isi Dulu ' + attrData);
    //                 cek = 0;
    //                 return false;
    //             }
    //         });

    //         if(cek == 0){
    //             return;
    //         }

    //         alert();

    //     });
    // });


    function cekOperation(){

        var cek = 1;

        $('select').each(function(){

            if($(this).val() == ''){

                var attrName = $(this).attr('name');

                if(attrName == 'operation[]'){
                    var attrData = 'Proses'
                }else if(attrName == 'level2[]'){
                    var attrData = 'SubProses1'
                }else if(attrName == 'level3[]'){
                    var attrData = 'SubProses2'
                }else if(attrName == 'level4[]'){
                    var attrData = 'SubProses3'
                }

                // alert('Tolong Isi Dulu ' + attrData);

                Swal.fire({
                    icon: "warning",
                    title: "Tolong Isi Dulu " + attrData,
                    timer: 2000,
                    showCancelButton: false,
                    showConfirmButton: true
                });

                cek = 0;
                return false;
            }
        });

        if(cek == 0){
            return;
        }

        return true;

    }



    function klikCetak() {
        var idtm = $("#idtm").val();

        var dataUrl = `/Produksi/PelaporanProduksi/TransferMaterial/cetak?id=${idtm}`;
        window.open(dataUrl, '_blank');
    }

    function klikUpdateOperation(){

        var status = cekOperation();

        if(status == true){

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                url: '/Produksi/PelaporanProduksi/TransferMaterial/updateOperation',
                beforeSend: function() {
                    openModal();
                },
                data: $('#tampilform, #tampilform2').serialize(),
                dataType: 'json',
                type: 'POST',
                success: function(data) {
                    if(data.status == 'Sukses'){
                        Swal.fire({
                            icon: "success",
                            title: "Success",
                            text: "Data Berhasil di Update",
                            timer: 1000,
                            showCancelButton: false,
                            showConfirmButton: true
                        });
                        klikLihatNext(data.id);
                    }else{
                        Swal.fire({
                            icon: "error",
                            title: "Failed",
                            text: "Data Gagal di Update",
                            timer: 2000,
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

    }

    function getval(no) {
        var nomor = no;
        console.log(nomor);
        var jumlah = $('#jumlah').val();
        id = $('#operation'+nomor).val();

        for(var i=1; i <= jumlah; i++){

            $("#operation"+i).val(id);
        
        }
    }

    function cariKaryawan(input) {

        var input = input;

        if (input == "") {
            Swal.fire({
                icon: "error",
                title: "Harap Isi Penerima",
                timer: 2000,
                showCancelButton: false,
                showConfirmButton: true
            });
            document.getElementById("karyawan").value = '';
            document.getElementById("karyawan").focus();
        } else {
            data = {input: input};
            $.ajax({
                url: '/Produksi/PelaporanProduksi/TransferMaterial/cariKaryawan',
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

    function klikBaru() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({
            url: '/Produksi/PelaporanProduksi/TransferMaterial/baru',
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

                document.getElementById("tampiltabel").style.color = "black";
                document.getElementById("tampiltabel").style.fontSize = "13px";
                document.getElementById("tampiltabel").style.fontWeight = "bold";
                document.getElementById("tampiltabel").style.textAlign = "center";

                document.getElementById("btnsimpan").disabled = false;
                document.getElementById("btnubah").disabled = true;
                document.getElementById("btncetak").disabled = true;
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

    function klikLihat() {

        var id = $('#idcari').val();
        var data = {id: id};

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({
            url: '/Produksi/PelaporanProduksi/TransferMaterial/lihat',
            beforeSend: function() {
                openModal();
            },
            data: data,
            dataType: 'json',
            type: 'POST',
            success: function(data) {

                if(data.status == 'notfound'){
                    Swal.fire({
                        icon: 'error',
                        title: 'TM Not Found',
                        text: "TM Bukan Ke atau Dari Area Anda",
                        showCancelButton: false,
                        showConfirmButton: true
                    });
                }else{
                    $("#tampil").html(data.html);
                    var table = $('#tampiltabel').DataTable({
                        ordering: false,
                        paging: false,
                        pageLength: 1000,
                        searching: false,
                        lengthChange: false,
                        scrollX: true,
                        scroller: true,
                    });
                
                    document.getElementById("btncetak").disabled = false;
                    document.getElementById("btnsimpan").disabled = true;
                    $("#jumlah").val(data.rowcountItem);

                    if (data.status == 1) {
                        document.getElementById("btnubah").disabled = false;
                    } else {
                        document.getElementById("btnubah").disabled = true;
                    }

                    const input = document.getElementById("idcari");
                    input.focus();
                    input.select();
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

    function klikLihatNext(id) {

        var id = id;
        var data = {id: id};

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({
            url: '/Produksi/PelaporanProduksi/TransferMaterial/lihat',
            beforeSend: function() {
                openModal();
            },
            data: data,
            dataType: 'json',
            type: 'POST',
            success: function(data) {

                if(data.status == 'notfound'){
                    Swal.fire({
                        icon: 'error',
                        title: 'TM Not Found',
                        text: "TM Bukan Ke atau Dari Area Anda",
                        showCancelButton: false,
                        showConfirmButton: true
                    });
                }else{
                    $("#tampil").html(data.html);
                    var table = $('#tampiltabel').DataTable({
                        ordering: false,
                        paging: false,
                        pageLength: 1000,
                        searching: false,
                        lengthChange: false,
                        scrollX: true,
                        scroller: true,
                    });
                
                    document.getElementById("btncetak").disabled = false;
                    document.getElementById("btnsimpan").disabled = true;
                    $("#jumlah").val(data.rowcountItem);

                    if (data.status == 1) {
                        document.getElementById("btnubah").disabled = false;
                    } else {
                        document.getElementById("btnubah").disabled = true;
                    }

                    const input = document.getElementById("idcari");
                    input.focus();
                    input.select();
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
                $('[data-index="' + (index - 100).toString() + '"]').focus();
                event.preventDefault();
            }
            if (event.keyCode === 40) { //DOWN
                if(rowindex == rowLength){
                    klikAddRow();
                }else{
                    $('[data-index="' + (index + 100).toString() + '"]').focus();
                    event.preventDefault();
                } 
            }
            addEventListener('keydown', (e) => { // CTRL+DELETE
                if (e.ctrlKey && e.key === 'Delete') {
                    remove(rowindex);
                }
            });
        }
    }

    // OK - Calculate Qty Newest Value
    function refresh_sum_qty(id) {
        var baris = id - 1;
        var table = $('#tampiltabel').DataTable();

        var qtyinput = $(table.cell(baris, 12).node()).find('input').val();
        table.cell(baris, 10).data(qtyinput);
        var qtydata = table.column(10).data().sum();

        console.log(qtydata);

        document.getElementById("totjumlah").innerHTML = qtydata;
                        

    }

    // OK - Calculate Weight Newest Value
    function refresh_sum_weight(id) {

        var baris = id - 1 ;
        var table = $('#tampiltabel').DataTable();

        var weightinput = $(table.cell(baris, 13).node()).find('input').val();
        table.cell(baris, 11).data(weightinput);
        var weightdata = table.column(11).data().sum();

        console.log(weightdata);

        document.getElementById("totberat").innerHTML = String(weightdata.toFixed(2));
    }

    // OK - Show Edit Form
    function klikUbah() {
        var id = $('#idtm').val();
        var data = {id: id};

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({
            url: '/Produksi/PelaporanProduksi/TransferMaterial/ubah',
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

                table.columns(10).visible(false);
                table.columns(11).visible(false);

                var qtydata = table.column(10).data().sum();
                var weightdata = table.column(11).data().sum();

                document.getElementById("totjumlah").innerHTML = qtydata;
                document.getElementById("totberat").innerHTML = String(weightdata.toFixed(2));

                document.getElementById("btnsimpan").disabled = false;

               
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
            url: '/Produksi/PelaporanProduksi/TransferMaterial/cekSPK',
            beforeSend: function() {
                openModal();
            },
            data: $('#tampilform, #tampilform2').serialize(),
            dataType: "json",
            type: 'POST',
            success: function(data) {
                if(data.status == 'Sukses'){
                    document.getElementById("cekspk").value = data.cekspk;
                    klikSimpan(); 
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


    function cekSPKAll(){

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({
            url: '/Produksi/PelaporanProduksi/TransferMaterial/cekSPK',
            beforeSend: function() {
                openModal();
            },
            data: $('#tampilform, #tampilform2').serialize(),
            dataType: "json",
            type: 'POST',
            success: function(data) {
                if(data.status == 'Sukses'){
                    simpanTest();
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

    // OK - Show Save Options
    function klikSimpan() {
        var cekstatus = $('#cekstatus').val();

        if (cekstatus == 1) {
            simpan();
        } else if (cekstatus == 2) {
            update();
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: "Invalid Request",
            })
            return;
        }
    }

    // OK - Save
    function simpan() {

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

            // items
            let Product = $('.Product');

            //!  ------------------------    Check Items Product if have blank value     ------------------------ !!
            let cekProduct = false
            Product.map(function() {
                if (this.value === '') {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: "There still empty Product field. Please Fill it.",
                    })
                    cekProduct = true
                    return false;
                }
            })
            if (cekProduct == true) {
                return false;
            }
            
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                url: '/Produksi/PelaporanProduksi/TransferMaterial/simpan',
                beforeSend: function() {
                    openModal();
                },
                data: $('#tampilform, #tampilform2').serialize(),
                dataType: 'json',
                type: 'POST',
                success: function(data) {
                    // document.getElementById("btnsimpan").disabled = true;
                    klikLihatNext(data.id);
                },
                complete: function() {
                    closeModal();
                },
                error: function(xhr) {
                    // document.getElementById("btnsimpan").disabled = true;
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


    function simpanTest() {

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

                let Product = $('.Product');

                //!  ------------------------    Check Items WorkOrder if have blank value     ------------------------ !!
                let cekProduct = false
                Product.map(function() {
                    if (this.value === '') {
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: "There still empty Product field. Please Fill it.",
                        })
                        cekProduct = true
                        return false;
                    }
                })
                if (cekProduct == true) {
                    return false;
                }

            
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                $.ajax({
                    url: '/Produksi/PelaporanProduksi/TransferMaterial/simpanTest',
                    beforeSend: function() {
                        openModal();
                    },
                    data: $('#tampilform, #tampilform2').serialize(),
                    dataType: 'json',
                    type: 'POST',
                    success: function(data) {
                        if (data.status == 'Sukses') {
                            Swal.fire({
                                icon: "success",
                                title: "Simpan Berhasil",
                                timer: 2000,
                                showCancelButton: false,
                                showConfirmButton: true
                            });
                            // klikLihatNext(data.id);
                        } else {
                            Swal.fire({
                                icon: "error",
                                title: "Simpan Gagal",
                                timer: 2000,
                                showCancelButton: false,
                                showConfirmButton: true
                            });
                            // document.getElementById("btnsimpan").disabled = true;
                        }
                    },
                    complete: function() {
                        closeModal();
                    },
                    error: function(xhr) {
                        // document.getElementById("btnsimpan").disabled = true;
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

    function update() {

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
                url: '/Produksi/PelaporanProduksi/TransferMaterial/update',
                beforeSend: function() {
                    openModal();
                },
                data: $('#tampilform, #tampilform2').serialize(),
                dataType: 'json',
                type: 'POST',
                success: function(data) {
                    if (data.status == 'Sukses') {
                        // document.getElementById("btnsimpan").disabled = true;
                        klikLihatNext(data.id);
                    } else {
                        Swal.fire({
                            icon: "error",
                            title: "Update Gagal",
                            timer: 2000,
                            showCancelButton: false,
                            showConfirmButton: true
                        });
                        // document.getElementById("btnsimpan").disabled = true;
                    }
                },
                complete: function() {
                    closeModal();
                },
                error: function(xhr) {
                    // document.getElementById("btnsimpan").disabled = true;
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

    function klikPosting() {

        var status = cekOperation();

        if(status == true){

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                url: "/Produksi/PelaporanProduksi/TransferMaterial/posting",
                beforeSend: function() {
                    openModal();
                },
                data: $('#tampilform').serialize(),
                dataType: 'json',
                type: 'POST',
                success: function(data) {

                    if (data.status == 'Sukses') {

                        if(data.location == 10 && data.baris == 1){
                            Swal.fire({
                                icon: 'success',
                                title: 'Data Transfer Barang Jadi',
                                html: data.list,
                            }).then((result) => {
                                if (result['isConfirmed']){
                                    // document.getElementById("btnposting").disabled = true;
                                    klikLihatNext(data.id)
                                }
                            });
                        }else{
                            // document.getElementById("btnposting").disabled = true;
                            klikLihatNext(data.id);
                        }
                      
                    } else if (data.status == 'Gagal') {
                        Swal.fire({
                            icon: "error",
                            title: "Posting Gagal",
                            timer: 2000,
                            showCancelButton: false,
                            showConfirmButton: true
                        });
                        document.getElementById("btnposting").disabled = true;

                    } else if (data.status == 'BelumStokHarian') {
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
    }

    function klikPostingTest() {

        var status = cekOperation();

        if(status == true){

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                url: "/Produksi/PelaporanProduksi/TransferMaterial/postingTest",
                beforeSend: function() {
                    openModal();
                },
                data: $('#tampilform').serialize(),
                dataType: 'json',
                type: 'POST',
                success: function(data) {

                    console.log('klikPostingTest');
                    // klikLihatNext(data.id);

                    // if (data.status == 'Sukses') {
                    //     // Swal.fire({
                    //     //     icon: "success",
                    //     //     title: "Posting Berhasil",
                    //     //     timer: 2000,
                    //     //     showCancelButton: false,
                    //     //     showConfirmButton: true
                    //     // });
                    //     klikLihatNext(data.id);
                    // } else if (data.status == 'Gagal') {
                    //     Swal.fire({
                    //         icon: "error",
                    //         title: "Posting Gagal",
                    //         timer: 2000,
                    //         showCancelButton: false,
                    //         showConfirmButton: true
                    //     });
                    //     document.getElementById("btnposting").disabled = true;

                    // } else if (data.status == 'BelumStokHarian') {
                    //     Swal.fire({
                    //         icon: "error",
                    //         title: "Belum Stok Harian",
                    //         timer: 2000,
                    //         showCancelButton: false,
                    //         showConfirmButton: true
                    //     });
                    //     document.getElementById("btnposting").disabled = true;
                    // }
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
    }

    function klikBarcodeUnit(){
        
        var id = $("#barcodeunit").val();
        var fromloc = $("#daribagian").val();
        var toloc = $("#kebagian").val();
        var karyawanid = $("#karyawanid").val();

        if(toloc == '') {
            Swal.fire({
                icon: "error",
                title: "Harap Isi Area Tujuan",
                timer: 2000,
                showCancelButton: false,
                showConfirmButton: true
            });
            document.getElementById("barcodeunit").value = '';
            document.getElementById("barcodeunit").focus();
        }else if(karyawanid == ''){
            Swal.fire({
                icon: "error",
                title: "Harap Isi Penerima",
                timer: 2000,
                showCancelButton: false,
                showConfirmButton: true
            });
            document.getElementById("barcodeunit").value = '';
            document.getElementById("barcodeunit").focus();
        }else{
            data = {id: id, fromloc: fromloc, toloc: toloc};
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                url: "/Produksi/PelaporanProduksi/TransferMaterial/barcodeUnit",
                beforeSend: function() {
                    openModal();
                },
                data: data,
                dataType: 'json',
                type: 'POST',
                success: function(data) {
                    // console.log(data);
                    if(data.status == 'Sukses'){
                        // DataTable, Row count and column count both start at 0
                        var table = $('#tampiltabel').DataTable();
                        var rowCount = table.data().rows().count() + 1;
                    
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
                                '<td><input class="form-control-plaintext" type="text" spellcheck="false" style="text-align: center; color:black; font-size: 13px; font-weight: bold" id="NoSPK'+rowCount+'" value="'+data.WorkOrder[i]+'" onkeydown="handlerItem(event)" data-index="'+rowCount+'04" row-index="'+rowCount+'" readonly></td>',
                                '<td><input class="form-control-plaintext" type="text" style="text-align: center; color:black; font-size: 13px; font-weight: bold" id="ProdukSPK'+rowCount+'" value="'+data.FinishGood[i]+'" onkeydown="handlerItem(event)" data-index="'+rowCount+'05" row-index="'+rowCount+'" readonly></td>',
                                '<td><input class="form-control-plaintext" type="text" style="text-align: center; color:black; font-size: 13px; font-weight: bold" id="Kadar'+rowCount+'" value="'+data.Carat[i]+'" onkeydown="handlerItem(event)" data-index="'+rowCount+'06" row-index="'+rowCount+'" readonly></td>',
                                '<td><input class="form-control-plaintext" type="text" style="text-align: center; color:black; font-size: 13px; font-weight: bold" id="JmlSPK'+rowCount+'" value="'+data.TotalQty[i]+'" onkeydown="handlerItem(event)" data-index="'+rowCount+'07" row-index="'+rowCount+'" readonly></td>',
                                '<td>' +
                                    '<input class="form-control-plaintext Product" type="text" spellcheck="false" style="text-align: center; color:black; font-size: 13px; font-weight: bold" id="Product'+rowCount+'" value="'+data.Product[i]+'" onkeydown="handlerItem(event)" data-index="'+rowCount+'08" row-index="'+rowCount+'" readonly>' +
                                    '<input type="hidden" id="PID'+rowCount+'" name="PID[]" value="'+data.PID[i]+'">' +
                                '</td>',
                                '<td>'+data.Qty[i]+'</td>',
                                '<td>'+data.Weight[i]+'</td>',
                                '<td><input class="form-control" type="text" style="text-align: center; color:black; font-size: 13px; font-weight: bold" id="Qty'+rowCount+'" name="Qty[]" value="'+data.Qty[i]+'" onchange="refresh_sum_qty('+rowCount+')" onkeydown="handlerItem(event)" data-index="'+rowCount+'09" row-index="'+rowCount+'"></td>',
                                '<td>' +
                                    '<div class="input-group" style="width: 100%;">' +
                                        '<input class="form-control" type="text" style="text-align: center; color:black; font-size: 13px; font-weight: bold" id="Weight'+rowCount+'" name="Weight[]" value="'+data.Weight[i]+'" onchange="refresh_sum_weight('+rowCount+')" onkeydown="handlerItem(event)" data-index="'+rowCount+'10" row-index="'+rowCount+'">' +
                                        '<button type="button" class="btn btn-info btn-sm" onclick="klikTimbangRun(\'Weight' + rowCount + '\','+rowCount+')"><i class="fa fa-balance-scale"></i></button>' +
                                    '</div>' +
                                '</td>',
                                '<td><input class="form-control" type="text" spellcheck="false" style="text-align: center; color:black; font-size: 13px; font-weight: bold" id="KadarInput'+rowCount+'" value="" onchange="cariKadar(this.value,' + rowCount + ')" onkeydown="handlerItem(event)" data-index="'+rowCount+'11" row-index="'+rowCount+'" readonly></td>',
                                '<td><input class="form-control" type="text" style="text-align: center; color:black; font-size: 13px; width: 100%; font-weight: bold" id="Note'+rowCount+'" name="Note[]" value="'+data.Note[i]+'" onkeydown="handlerItem(event)" data-index="'+rowCount+'12" row-index="'+rowCount+'"></td>',
                                '<td><input class="form-control-plaintext" type="text" style="text-align: center; color:black; font-size: 13px; font-weight: bold" id="PohonID'+rowCount+'" value="'+data.TreeID[i]+'" onkeydown="handlerItem(event)" data-index="'+rowCount+'13" row-index="'+rowCount+'" readonly></td>',
                                '<td><input class="form-control-plaintext" type="text" style="text-align: center; color:black; font-size: 13px; font-weight: bold" id="PohonUrut'+rowCount+'" value="'+data.TreeOrd[i]+'" onkeydown="handlerItem(event)" data-index="'+rowCount+'14" row-index="'+rowCount+'" readonly></td>',
                                '<td><input class="form-control-plaintext" type="text" style="text-align: center; color:black; font-size: 13px; font-weight: bold" id="NoPohon'+rowCount+'" value="'+data.RubberPlate[i]+'" onkeydown="handlerItem(event)" data-index="'+rowCount+'15" row-index="'+rowCount+'" readonly></td>',
                                '<td><input class="form-control-plaintext" type="text" style="text-align: center; color:black; font-size: 13px; font-weight: bold" id="ProdukDetail'+rowCount+'" value="'+data.ProductDetail[i]+'" onkeydown="handlerItem(event)" data-index="'+rowCount+'16" row-index="'+rowCount+'" readonly></td>',
                                '<td><input class="form-control-plaintext" type="text" style="text-align: center; color:black; font-size: 13px; font-weight: bold" id="Batch'+rowCount+'" value="'+data.BatchNo[i]+'" onkeydown="handlerItem(event)" data-index="'+rowCount+'17" row-index="'+rowCount+'" readonly></td>',
                            ]).draw();

                            table.row(newrow).nodes().to$().attr('id', 'myRow' + rowCount);

                            rowCount++;

                        }

                        var qtydata = table.column(10).data().sum();
                        var weightdata = table.column(11).data().sum();

                        table.columns(10).visible(false);
                        table.columns(11).visible(false);

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


                        document.getElementById("tampiltabel").style.color = "black";
                        document.getElementById("tampiltabel").style.fontSize = "13px";
                        document.getElementById("tampiltabel").style.fontWeight = "bold";
                        document.getElementById("tampiltabel").style.textAlign = "center";

                        document.getElementById("barcodeunit").value = "";
                        document.getElementById("barcodeunit").focus();

                        document.getElementById("totjumlah").innerHTML = qtydata;
                        document.getElementById("totberat").innerHTML = String(weightdata.toFixed(2));
                    }else{
                        Swal.fire({
                            icon: "error",
                            title: "Data NotFound",
                            timer: 2000,
                            showCancelButton: false,
                            showConfirmButton: true
                        });
                        document.getElementById("barcodeunit").value = '';
                        document.getElementById("barcodeunit").focus();
                    }
                },
                complete: function() {
                    closeModal();
                },
                error: function(xhr) {
                    document.getElementById("barcodeunit").value = '';
                    document.getElementById("barcodeunit").focus();

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

    function klikBarcodeKomponen(){
        
        var id = $("#barcodekomponen").val();
        var fromloc = $("#daribagian").val();
        var toloc = $("#kebagian").val();
        var karyawanid = $("#karyawanid").val();

        if(toloc == '') {
            Swal.fire({
                icon: "error",
                title: "Harap Isi Area Tujuan",
                timer: 2000,
                showCancelButton: false,
                showConfirmButton: true
            });
            document.getElementById("barcodekomponen").value = '';
            document.getElementById("barcodekomponen").focus();
        }else if(karyawanid == ''){
            Swal.fire({
                icon: "error",
                title: "Harap Isi Penerima",
                timer: 2000,
                showCancelButton: false,
                showConfirmButton: true
            });
            document.getElementById("barcodekomponen").value = '';
            document.getElementById("barcodekomponen").focus();
        }else{

            data = {id: id, fromloc: fromloc, toloc: toloc};
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                url: "/Produksi/PelaporanProduksi/TransferMaterial/barcodeKomponen",
                beforeSend: function() {
                    openModal();
                },
                data: data,
                dataType: 'json',
                type: 'POST',
                success: function(data) {
                    // console.log(data);
                    if(data.status == 'Sukses'){
                        // DataTable, Row count and column count both start at 0
                        var table = $('#tampiltabel').DataTable();
                        var rowCount = table.data().rows().count() + 1;
                    
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
                                '<td><input class="form-control" type="text" spellcheck="false" autocomplete="off" style="text-align: center; color:black; font-size: 13px; font-weight: bold" id="NoSPK'+rowCount+'" value="'+data.WorkOrder[i]+'" onkeydown="handlerItem(event)" data-index="'+rowCount+'04" row-index="'+rowCount+'" readonly></td>',
                                '<td><input class="form-control-plaintext" type="text" style="text-align: center; color:black; font-size: 13px; font-weight: bold" id="ProdukSPK'+rowCount+'" value="'+data.FinishGood[i]+'" onkeydown="handlerItem(event)" data-index="'+rowCount+'05" row-index="'+rowCount+'" readonly></td>',
                                '<td><input class="form-control-plaintext" type="text" style="text-align: center; color:black; font-size: 13px; font-weight: bold" id="Kadar'+rowCount+'" value="'+data.Carat[i]+'" onkeydown="handlerItem(event)" data-index="'+rowCount+'06" row-index="'+rowCount+'" readonly></td>',
                                '<td><input class="form-control-plaintext" type="text" style="text-align: center; color:black; font-size: 13px; font-weight: bold" id="JmlSPK'+rowCount+'" value="'+data.TotalQty[i]+'" onkeydown="handlerItem(event)" data-index="'+rowCount+'07" row-index="'+rowCount+'" readonly></td>',
                                '<td>' +
                                    '<input class="form-control-plaintext Product" type="text" spellcheck="false" autocomplete="off" style="text-align: center; color:black; font-size: 13px; font-weight: bold" id="Product'+rowCount+'" value="'+data.Product[i]+'" onkeydown="handlerItem(event)" data-index="'+rowCount+'08" row-index="'+rowCount+'" readonly>' +
                                    '<input type="hidden" id="PID'+rowCount+'" name="PID[]" value="'+data.PID[i]+'">' +
                                '</td>',
                                '<td>'+data.Qty[i]+'</td>',
                                '<td>'+data.Weight[i]+'</td>',
                                '<td><input class="form-control-plaintext" type="text" autocomplete="off" style="text-align: center; color:black; font-size: 13px; font-weight: bold" id="Qty'+rowCount+'" name="Qty[]" value="'+data.Qty[i]+'" onkeydown="handlerItem(event)" data-index="'+rowCount+'09" row-index="'+rowCount+'"></td>',
                                '<td><input class="form-control-plaintext" type="text" autocomplete="off" style="text-align: center; color:black; font-size: 13px; font-weight: bold" id="Weight'+rowCount+'" name="Weight[]" value="'+data.Weight[i]+'" onkeydown="handlerItem(event)" data-index="'+rowCount+'10" row-index="'+rowCount+'"></td>',
                                '<td><input class="form-control" type="text" spellcheck="false" autocomplete="off" style="text-align: center; color:black; font-size: 13px; font-weight: bold" id="KadarInput'+rowCount+'" value="" onchange="cariKadar(this.value,' + rowCount + ')" onkeydown="handlerItem(event)" data-index="'+rowCount+'11" row-index="'+rowCount+'" reaonly></td>',
                                '<td><input class="form-control" type="text" autocomplete="off" style="text-align: center; color:black; font-size: 13px; width: 100%; font-weight: bold" id="Note'+rowCount+'" name="Note[]" value="'+data.Note[i]+'" onkeydown="handlerItem(event)" data-index="'+rowCount+'12" row-index="'+rowCount+'"></td>',
                                '<td><input class="form-control-plaintext" type="text" style="text-align: center; color:black; font-size: 13px; font-weight: bold" id="PohonID'+rowCount+'" value="'+data.TreeID[i]+'" onkeydown="handlerItem(event)" data-index="'+rowCount+'13" row-index="'+rowCount+'" readonly></td>',
                                '<td><input class="form-control-plaintext" type="text" style="text-align: center; color:black; font-size: 13px; font-weight: bold" id="PohonUrut'+rowCount+'" value="'+data.TreeOrd[i]+'" onkeydown="handlerItem(event)" data-index="'+rowCount+'14" row-index="'+rowCount+'" readonly></td>',
                                '<td><input class="form-control-plaintext" type="text" style="text-align: center; color:black; font-size: 13px; font-weight: bold" id="NoPohon'+rowCount+'" value="'+data.RubberPlate[i]+'" onkeydown="handlerItem(event)" data-index="'+rowCount+'15" row-index="'+rowCount+'" readonly></td>',
                                '<td><input class="form-control-plaintext" type="text" style="text-align: center; color:black; font-size: 13px; font-weight: bold" id="ProdukDetail'+rowCount+'" value="'+data.ProductDetail[i]+'" onkeydown="handlerItem(event)" data-index="'+rowCount+'16" row-index="'+rowCount+'" readonly></td>',
                                '<td><input class="form-control-plaintext" type="text" style="text-align: center; color:black; font-size: 13px; font-weight: bold" id="Batch'+rowCount+'" value="'+data.BatchNo[i]+'" onkeydown="handlerItem(event)" data-index="'+rowCount+'17" row-index="'+rowCount+'" readonly></td>',
                            ]).draw();

                            table.row(newrow).nodes().to$().attr('id', 'myRow' + rowCount);

                            rowCount++;
                        }

                        var qtydata = table.column(10).data().sum();
                        var weightdata = table.column(11).data().sum();

                        table.columns(10).visible(false);
                        table.columns(11).visible(false);

                        table.row(newrow).column(0).nodes().to$().attr('class','m-1 p-1');
                        table.row(newrow).column(1).nodes().to$().attr('class','m-1 p-1');
                        table.row(newrow).column(2).nodes().to$().attr('class','m-1 p-1');
                        table.row(newrow).column(3).nodes().to$().attr('class','m-1 p-1');
                        table.row(newrow).column(4).nodes().to$().attr('class','m-1 p-1');
                        table.row(newrow).column(5).nodes().to$().attr('class','m-1 p-1');
                        table.row(newrow).column(6).nodes().to$().attr('class','m-1 p-1');
                        table.row(newrow).column(7).nodes().to$().attr('class','m-1 p-1');
                        table.row(newrow).column(8).nodes().to$().attr('class','m-1 p-1');
                        table.row(newrow).column(9).nodes().to$().attr('class','m-1 p-1');
                        table.row(newrow).column(10).nodes().to$().attr('class','m-1 p-1');
                        table.row(newrow).column(11).nodes().to$().attr('class','m-1 p-1');
                        table.row(newrow).column(12).nodes().to$().attr('class','m-1 p-1');
                        table.row(newrow).column(13).nodes().to$().attr('class','m-1 p-1');
                        table.row(newrow).column(14).nodes().to$().attr('class','m-1 p-1');
                        table.row(newrow).column(15).nodes().to$().attr('class','m-1 p-1');
                        table.row(newrow).column(16).nodes().to$().attr('class','m-1 p-1');
                        table.row(newrow).column(17).nodes().to$().attr('class','m-1 p-1');
                        table.row(newrow).column(18).nodes().to$().attr('class','m-1 p-1');
                        table.row(newrow).column(19).nodes().to$().attr('class','m-1 p-1');
                        table.row(newrow).column(20).nodes().to$().attr('class','m-1 p-1');

                        document.getElementById("tampiltabel").style.color = "black";
                        document.getElementById("tampiltabel").style.fontSize = "13px";
                        document.getElementById("tampiltabel").style.fontWeight = "bold";
                        document.getElementById("tampiltabel").style.textAlign = "center";

                        document.getElementById("barcodekomponen").value = "";
                        document.getElementById("barcodekomponen").focus();

                        document.getElementById("totjumlah").innerHTML = qtydata;
                        document.getElementById("totberat").innerHTML = String(weightdata.toFixed(2));
                    }else{
                        Swal.fire({
                            icon: "error",
                            title: "Data NotFound",
                            timer: 2000,
                            showCancelButton: false,
                            showConfirmButton: true
                        });
                        document.getElementById("barcodekomponen").value = '';
                        document.getElementById("barcodekomponen").focus();
                    }

                },
                complete: function() {
                    closeModal();
                },
                error: function(xhr) {
                    document.getElementById("barcodekomponen").value = '';
                    document.getElementById("barcodekomponen").focus();

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

    function selectItem(idname,number){
        const input = document.getElementById(idname+number);
        input.focus();
        input.select();
    }

    function remove(id) {

        var table = $('#tampiltabel').DataTable();
        table.row('#myRow' + id).remove().draw();

        var qtydata = table.column(10).data().sum();
        var weightdata = table.column(11).data().sum();

        var number = id-1;
        if(number != 0){
            selectItem("ProdukSPK",number);
        }

        document.getElementById("totjumlah").innerHTML = qtydata;
        document.getElementById("totberat").innerHTML = String(weightdata.toFixed(2));

    }

    function klikAddRow() {

            var table = $('#tampiltabel').DataTable();
            var rowCount = table.data().rows().count() + 1;

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
                    '<input type="hidden" id="FG'+rowCount+'" name="FG[]" value="">' +
                    '<input type="hidden" id="Part'+rowCount+'" name="Part[]" value="">' +
                    '<input type="hidden" id="RID'+rowCount+'" name="RID[]" value="">' +
                    '<input type="hidden" id="OID'+rowCount+'" name="OID[]" value="">' +
                    '<input type="hidden" id="OOrd'+rowCount+'" name="OOrd[]" value="">' +
                '</td>',
                '<td><button type="button" class="btn btn-danger btn-sm" onclick="remove(\''+rowCount+'\')"><i class="fa fa-minus"></i></button></td>',
                '<td><input class="form-control-plaintext" type="text" style="text-align: center; color:black; font-size: 13px; font-weight: bold" id="WorkAllocation'+rowCount+'" name="WorkAllocation[]" value="" onkeydown="handlerItem(event)" data-index="'+rowCount+'01" row-index="'+rowCount+'" ></td>',
                '<td><input class="form-control-plaintext" type="text" style="text-align: center; color:black; font-size: 13px; font-weight: bold" id="Freq'+rowCount+'" name="Freq[]" value="" onkeydown="handlerItem(event)" data-index="'+rowCount+'02" row-index="'+rowCount+'" ></td>',
                '<td><input class="form-control-plaintext" type="text" style="text-align: center; color:black; font-size: 13px; font-weight: bold" id="Ordinal'+rowCount+'" name="Ordinal[]" value="" onchange="cariSPKO(this.value,' + rowCount + ')" onkeydown="handlerItem(event)" data-index="'+rowCount+'03" row-index="'+rowCount+'" ></td>',
                '<td><input class="form-control" type="text" spellcheck="false" style="text-align: center; color:black; font-size: 13px; font-weight: bold" id="NoSPK'+rowCount+'" value="" onchange="cariSPK(this.value,' + rowCount + ')" onkeydown="handlerItem(event)" data-index="'+rowCount+'04" row-index="'+rowCount+'"></td>',
                '<td><input class="form-control-plaintext" type="text" style="text-align: center; color:black; font-size: 13px; font-weight: bold" id="ProdukSPK'+rowCount+'" value="" onkeydown="handlerItem(event)" data-index="'+rowCount+'05" row-index="'+rowCount+'" readonly></td>',
                '<td><input class="form-control-plaintext" type="text" style="text-align: center; color:black; font-size: 13px; font-weight: bold" id="Kadar'+rowCount+'" value="" onkeydown="handlerItem(event)" data-index="'+rowCount+'06" row-index="'+rowCount+'" readonly></td>',
                '<td><input class="form-control-plaintext" type="text" style="text-align: center; color:black; font-size: 13px; font-weight: bold" id="JmlSPK'+rowCount+'" value="" onkeydown="handlerItem(event)" data-index="'+rowCount+'07" row-index="'+rowCount+'" readonly></td>',
                '<td>' +
                    '<input class="form-control Product" type="text" spellcheck="false" style="text-align: center; color:black; font-size: 13px; font-weight: bold" id="Product'+rowCount+'" value="" onchange="cariProduct(this.value,' + rowCount + ')" onkeydown="handlerItem(event)" data-index="'+rowCount+'08" row-index="'+rowCount+'">' +
                    '<input type="hidden" id="PID'+rowCount+'" name="PID[]" value="">' +
                '</td>',
                '<td>'+0+'</td>',
                '<td>'+0+'</td>',
                '<td><input class="form-control" type="text" style="text-align: center; color:black; font-size: 13px; width: 100%; font-weight: bold" id="Qty'+rowCount+'" name="Qty[]" value="" onchange="refresh_sum_qty('+rowCount+')" onkeydown="handlerItem(event)" data-index="'+rowCount+'09" row-index="'+rowCount+'"></td>',
                '<td>' +
                    '<div class="input-group" style="width: 100%;">' +
                        '<input class="form-control" type="text" style="text-align: center; color:black; font-size: 13px; font-weight: bold" id="Weight'+rowCount+'" name="Weight[]" value="" onchange="refresh_sum_weight('+rowCount+')" onkeydown="handlerItem(event)" data-index="'+rowCount+'10" row-index="'+rowCount+'">' +
                        '<button type="button" class="btn btn-info btn-sm" onclick="klikTimbangRun(\'Weight' + rowCount + '\','+rowCount+')"><i class="fa fa-balance-scale"></i></button>' +
                    '</div>' +
                '</td>',
                '<td><input class="form-control" type="text" spellcheck="false" style="text-align: center; color:black; font-size: 13px; font-weight: bold" id="KadarInput'+rowCount+'" value="" onchange="cariKadar(this.value,' + rowCount + ')" onkeydown="handlerItem(event)" data-index="'+rowCount+'11" row-index="'+rowCount+'"></td>',
                '<td><input class="form-control" type="text" style="text-align: center; color:black; font-size: 13px; width: 100%; font-weight: bold" id="Note'+rowCount+'" name="Note[]" value="" onkeydown="handlerItem(event)" data-index="'+rowCount+'12" row-index="'+rowCount+'"></td>',
                '<td><input class="form-control-plaintext" type="text" style="text-align: center; color:black; font-size: 13px; font-weight: bold" id="PohonID'+rowCount+'" value="" onkeydown="handlerItem(event)" data-index="'+rowCount+'13" row-index="'+rowCount+'" readonly></td>',
                '<td><input class="form-control-plaintext" type="text" style="text-align: center; color:black; font-size: 13px; font-weight: bold" id="PohonUrut'+rowCount+'" value="" onkeydown="handlerItem(event)" data-index="'+rowCount+'14" row-index="'+rowCount+'" readonly></td>',
                '<td><input class="form-control-plaintext" type="text" style="text-align: center; color:black; font-size: 13px; font-weight: bold" id="NoPohon'+rowCount+'" value="" onkeydown="handlerItem(event)" data-index="'+rowCount+'15" row-index="'+rowCount+'" readonly></td>',
                '<td><input class="form-control-plaintext" type="text" style="text-align: center; color:black; font-size: 13px; font-weight: bold" id="ProdukDetail'+rowCount+'" value="" onkeydown="handlerItem(event)" data-index="'+rowCount+'16" row-index="'+rowCount+'" readonly></td>',
                '<td><input class="form-control-plaintext" type="text" style="text-align: center; color:black; font-size: 13px; font-weight: bold" id="Batch'+rowCount+'" value="" onkeydown="handlerItem(event)" data-index="'+rowCount+'17" row-index="'+rowCount+'" readonly></td>',
    
            ]).draw()

            table.row(newrow).nodes().to$().attr('id', 'myRow' + rowCount);

            table.columns(10).visible(false);
            table.columns(11).visible(false);

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


            document.getElementById("tampiltabel").style.color = "black";
            document.getElementById("tampiltabel").style.fontSize = "13px";
            document.getElementById("tampiltabel").style.fontWeight = "bold";
            document.getElementById("tampiltabel").style.textAlign = "center";

            selectItem("NoSPK",rowCount);


    }


    function cariSPK(sw, no) {
        var no = no;
        var sw = sw;
      
        data = {sw: sw};

        $.ajax({
            url: '/Produksi/PelaporanProduksi/TransferMaterial/cariSPK',
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
                    document.getElementById("OID" + no).value = '';
             
      
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

    function cariProduct(sw, no) {
        var no = no;
        var sw = sw;
        // var carat = carat;
        data = {
            sw: sw
        };

        $.ajax({
            url: '/Produksi/PelaporanProduksi/TransferMaterial/cariProduct',
            beforeSend: function() {
                openModal();
            },
            data: data,
            dataType: 'json',
            type: 'POST',
            success: function(data) {
                // console.log(data);
                if (data.rowcount > 0) {
                    if (data.UseCarat == 'Y') {
                        document.getElementById("Product" + no).value = data.Product;
                        document.getElementById("PID" + no).value = data.PID;
                        document.getElementById("Kadar" + no).value = '';
                        document.getElementById("Carat" + no).value = 'NULL';
                    } else if (data.UseCarat == 'N') {
                        document.getElementById("Product" + no).value = data.Product;
                        document.getElementById("PID" + no).value = data.PID;
                        document.getElementById("Kadar" + no).value = '';
                        document.getElementById("Carat" + no).value = 'NULL';
                        document.getElementById("KadarInput" + no).readOnly = true;

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

    function cariKadar(sw, no) {
        var no = no;
        var sw = sw;

        data = {sw: sw};

        $.ajax({
            url: '/Produksi/PelaporanProduksi/TransferMaterial/cariKadar',
            beforeSend: function() {
                openModal();
            },
            data: data,
            dataType: 'json',
            type: 'POST',
            success: function(data) {

                if (data.rowcount > 0) {
                    document.getElementById("KadarInput" + no).value = data.CaratName;
                    document.getElementById("RID" + no).value = data.CaratId;

                } else {
                    Swal.fire({
                        icon: "error",
                        title: "Kadar NotFound",
                        timer: 2000,
                        showCancelButton: false,
                        showConfirmButton: true
                    });
                    document.getElementById("KadarInput" + no).focus();
                    document.getElementById("KadarInput" + no).value = '';
                    document.getElementById("RID" + no).value = '';
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

    function cariSPKO(ordinal, no) {

        var wa = $('#WorkAllocation'+no).val();
        var freq = $('#Freq'+no).val();
        var ordinal = ordinal;

        data = {wa: wa, freq: freq, ordinal: ordinal};
        $.ajax({
            url: '/Produksi/PelaporanProduksi/TransferMaterial/cariSPKO',
            beforeSend: function() {
                openModal();
            },
            data: data,
            dataType: 'json',
            type: 'POST',
            success: function(data) {

                if (data.rowcount > 0) {
                    $('#WorkOrder'+no).val(data.WorkOrder);
                    $('#Carat'+no).val(data.Carat);
                    $('#TotalQty'+no).val(data.TotalQty);
                    $('#RubberPlate'+no).val(data.NoPohon);
                    $('#TreeID'+no).val(data.TreeID);
                    $('#TreeOrd'+no).val(data.TreeOrd);
                    $('#BatchNo'+no).val(data.BatchNo);
                    $('#RID'+no).val(data.Carat);
                    $('#OID'+no).val(data.WorkOrder);
                    $('#WorkAllocation'+no).val(data.SW);
                    $('#Freq'+no).val(data.Freq);
                    $('#Ordinal'+no).val(data.Ordinal);
                    $('#NoSPK'+no).val(data.NoSPK);
                    $('#ProdukSPK'+no).val(data.ProdukSPK);
                    $('#Kadar'+no).val(data.Kadar);
                    $('#JmlSPK'+no).val(data.TotalQty);
                    $('#Product'+no).val(data.Barang);
                    $('#PID'+no).val(data.PID);
                    $('#Qty'+no).val(data.Qty);
                    $('#Weight'+no).val(data.Weight);
                    $('#PohonID'+no).val(data.TreeID);
                    $('#PohonUrut'+no).val(data.TreeOrd);
                    $('#NoPohon'+no).val(data.NoPohon);
                    $('#Batch'+no).val(data.BatchNo);

                } else {
                    Swal.fire({
                        icon: "error",
                        title: "Kadar NotFound",
                        timer: 2000,
                        showCancelButton: false,
                        showConfirmButton: true
                    });
                    document.getElementById("NoSPK" + no).focus();
                    $('#WorkOrder'+no).val('');
                    $('#Carat'+no).val('');
                    $('#TotalQty'+no).val('');
                    $('#RubberPlate'+no).val('');
                    $('#TreeID'+no).val('');
                    $('#TreeOrd'+no).val('');
                    $('#BatchNo'+no).val('');
                    $('#RID'+no).val('');
                    $('#OID'+no).val('');
                    $('#WorkAllocation'+no).val('');
                    $('#Freq'+no).val('');
                    $('#Ordinal'+no).val('');
                    $('#NoSPK'+no).val('');
                    $('#ProdukSPK'+no).val('');
                    $('#Kadar'+no).val('');
                    $('#JmlSPK'+no).val('');
                    $('#Product'+no).val('');
                    $('#PID'+no).val('');
                    $('#Qty'+no).val('');
                    $('#Weight'+no).val('');
                    $('#PohonID'+no).val('');
                    $('#PohonUrut'+no).val('');
                    $('#NoPohon'+no).val('');
                    $('#Batch'+no).val('');
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
<?php $title = 'TM Karet PCB ke Lilin'; ?>
@extends('layouts.backend-Theme-3.app')
@section('title', '$title')

@section('Dashboard')
    <h2 class="m-0">{{ $title }}</h2>
    <ol class="breadcrumb sm-2">
        <li class="breadcrumb-item"><a href="/">Home </a></li>
        <li class="breadcrumb-item">R&D </li>
        <li class="breadcrumb-item">Percobaan </li>
        <li class="breadcrumb-item active">TM Karet PCB ke Lilin </li>
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

                @include('R&D.Percobaan.TMKaretPCBKeLilin.data2')
                
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

        function KlikBaru() {
            // Getting idTMKaretKeLilin from hidden input
            let idTMKaretKeLilin = $('#idTMKaretKeLilin').val()
            if (idTMKaretKeLilin != "") {
                // If idTMKaretKeLilin have value. It will reload th page
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
            let idrubber = '<td class="m-0 p-0"> <input type="text" onchange="GetRubber(this.value,'+number+')" oncontextmenu="klikme('+number+',event)" onKeyPress="nextRowEvent('+number+',event)" class="form-control form-control-sm fs-6 w-100 text-center idRubber" name="idRubber" id="idRubber_'+number+'" value=""> </td>'
            let namaProduct = '<td class="m-0 p-0 "><input type="text" oncontextmenu="klikme('+number+',event)" readonly class="form-control form-control-sm fs-6 w-100 text-center namaProduct" name="namaProduct" id="namaProduct_'+number+'" value=""> </td>'
            let jenisPart = '<td class="m-0 p-0 "> <input type="text" oncontextmenu="klikme('+number+',event)" readonly class="form-control form-control-sm fs-6 w-100 text-center jenisPart" name="jenisPart" id="jenisPart_'+number+'" value=""> </td>'
            let finalItem = ""
            let rowitem = finalItem.concat(trStart, no, idrubber, namaProduct, jenisPart, trEnd)
            
            // add row to tbody
            $("#tabel1 > tbody").append(rowitem);
            
            // Set Button
            $("#btn_baru").prop('disabled',true)
            $("#btn_ubah").prop('disabled',true)
            $("#btn_cetak").prop('disabled',true)
            $("#btn_simpan").prop('disabled',false)
            $("#btn_batal").prop('disabled',false)
        }

        // Function for createNewRow
        function newRow() {
            // Setup table row
            let number = $('#tabel1 tr').length;
            let trStart = "<tr class='klik' id='Row_"+number+"'>"
            let trEnd = "</tr>"
            let no = '<td class="m-0 p-0"><input type="text" class="form-control form-control-sm fs-6 w-100 text-center nomor" name="no" readonly value="'+number+'"></td>'
            let idrubber = '<td class="m-0 p-0"> <input type="text" onchange="GetRubber(this.value,'+number+')" oncontextmenu="klikme('+number+',event)" onKeyPress="nextRowEvent('+number+',event)" class="form-control form-control-sm fs-6 w-100 text-center idRubber" name="idRubber" id="idRubber_'+number+'" value=""> </td>'
            let namaProduct = '<td class="m-0 p-0 "><input type="text" oncontextmenu="klikme('+number+',event)" readonly class="form-control form-control-sm fs-6 w-100 text-center namaProduct" name="namaProduct" id="namaProduct_'+number+'" value=""> </td>'
            let jenisPart = '<td class="m-0 p-0 "> <input type="text" oncontextmenu="klikme('+number+',event)" readonly class="form-control form-control-sm fs-6 w-100 text-center jenisPart" name="jenisPart" id="jenisPart_'+number+'" value=""> </td>'
            let finalItem = ""
            let rowitem = finalItem.concat(trStart, no, idrubber, namaProduct, jenisPart, trEnd)
            // add row to tbody
            $("#tabel1 > tbody").append(rowitem);
            $('#idRubber_'+number).focus()
        }

        function nextRowEvent(index,event){
            let keyCode = event.keyCode || event.which;
            if (keyCode === 40 || keyCode === 13) {
                // count total row
                let number = $('#tabel1 tbody tr').length;
                if (number == index){
                    newRow();
                }else{
                    let nextrow = index+1
                    $('#idRubber_'+nextrow).focus()
                }
            }
        }

        function klikme(index,e) {
            $('.klik').css('background-color', 'white');
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

            // Get idTMKaretKeLilin value from hidden input for checking
            let idTMKaretKeLilin = $('#idTMKaretKeLilin').val()
            // Check if idTMKaretKeLilin have value
            if (idTMKaretKeLilin == ""){
                // If idTMKaretKeLilin didn't have value or blank it will just remove the row
                
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
                        
                        // Set new idRubber properties
                        $(elem).find('.idRubber').attr('onchange',"GetRubber(this.value,"+newIndex+")")
                        $(elem).find('.idRubber').attr('oncontextmenu',"klikme("+newIndex+",event)")
                        $(elem).find('.idRubber').attr('onkeypress',"nextRowEvent("+newIndex+",event)")
                        $(elem).find('.idRubber').attr('id',"idRubber_"+newIndex)                       
                        
                        // Set Nomor
                        $(elem).find('.nomor').val(newIndex)

                    })
                }
            } else { // If idTMKaretKeLilin have value
                // get row length
                let number = $('#tabel1 tbody tr').length;
                if (number !== 1){
                    // If row length is more than 1 it will remove last row
                    // Remove row 
                    $("#Row_"+index).remove()
                    // loop row
                    $("#tabel1 tbody tr").each((i, elem) => {
                        newIndex = i+1
                        // Set new row index
                        elem.id = "Row_"+newIndex
                        
                        // Set new idRubber properties
                        $(elem).find('.idRubber').attr('onchange',"GetRubber(this.value,"+newIndex+")")
                        $(elem).find('.idRubber').attr('oncontextmenu',"klikme("+newIndex+",event)")
                        $(elem).find('.idRubber').attr('onkeypress',"nextRowEvent("+newIndex+",event)")
                        $(elem).find('.idRubber').attr('id',"idRubber_"+newIndex)                       
                        
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


        function klikBatal() {
            window.location.reload()
        }

        function GetRubber(ValueIDRubber, index) {
            let idRubber = $('#idRubber_'+index).val()
            if (idRubber == "") {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: "idRubber can't be blank"
                })
                return;
            }

            // Check if that idRubber Exists on inputs
            let idRubbers = $('.idRubber')
            let exists = false;
            idRubbers.map(function () {
                if (exists !== true){
                    if (this.id !== "idRubber_"+index){
                        if ($(this).val() == idRubber){
                            exists = true
                            return;
                        }
                    }
                }
            })
            if (exists === true){
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'ID Rubber Dengan Nomor Tersebut Sudah ada.',
                })
                $("#idRubber_"+index).val("")
                return
            }

            let data = {idRubber:idRubber}
            
            // Setup CSRF TOKEN
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                type: "POST",
                url: "/R&D/Percobaan/TMKaretPCBKeLilin/items",
                data:data,
                dataType: 'json',
                beforeSend: function () {
                    $(".preloader").show();  
                },
                complete: function () {
                    $(".preloader").fadeOut(); 
                },
                success: function(data) {
                    // Set Value
                    $('#namaProduct_'+index).val(data.data.SW)
                    let jenisPart = ""
                    // Set TypeProcess
                    if (data.data.TypeProcess == 27) {
                        jenisPart = "Kepala"
                    } else if (data.data.TypeProcess == 26){
                        jenisPart = "Mainan"
                    } else {
                        jenisPart = "Component"
                    }
                    $('#jenisPart_'+index).val(jenisPart)
                    return;
                },
                error: function(xhr){
                    let message = xhr?.responseJSON?.message == undefined ? "Server Error" : xhr?.responseJSON?.message
                    // It will executed if response from backend is error
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: message,
                    })
                    $("#idRubber_"+index).val("")
                    return;
                }
            })
        }

        function KlikSimpan () {
            let action = $('#action').val()
            if (action == "simpan") {
                SimpanTMKaretLilin()
            }else if (action == "ubah"){
                UbahTMKaretLilin()
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: "Action Invalid",
                })
                return;
            }
        }

        function KlikUbah () {
            // Set Button
            $("#btn_baru").prop('disabled',true)
            $("#btn_ubah").prop('disabled',true)
            $("#btn_cetak").prop('disabled',true)
            $("#btn_simpan").prop('disabled',false)
            $("#btn_batal").prop('disabled',false)
            
            // Enable Input
            $(".idRubber").prop('disabled',false)
            $(".namaProduct").prop('disabled',false)
            $(".jenisPart").prop('disabled',false)
        }

        function SimpanTMKaretLilin() {
            let idRubbers = $('.idRubber')
            if (idRubbers.length === 0) {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: "Need One or More Data.",
                })
                return;
            }

            let listidRubbers = []
            for (let index = 0; index < idRubbers.length; index++) {
                let idRubber = $(idRubbers[index]).val()
                if (idRubber !== "") {
                    listidRubbers.push(idRubber)
                }
            }

            if (listidRubbers.length === 0) {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: "Need One or More Data.",
                })
                return;
            }

            let data = {listIDRubber:listidRubbers}
            
            // Setup CSRF TOKEN
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                type: "POST",
                url: "/R&D/Percobaan/TMKaretPCBKeLilin",
                data:data,
                dataType: 'json',
                beforeSend: function () {
                    $(".preloader").show();  
                },
                complete: function () {
                    $(".preloader").fadeOut(); 
                },
                success: function(data) {
                    // Setting Buttons
                    $("#btn_baru").prop('disabled',false)
                    $("#btn_ubah").prop('disabled',false)
                    $("#btn_cetak").prop('disabled',false)
                    $("#btn_simpan").prop('disabled',true)
                    $("#btn_batal").prop('disabled',true)
                    
                    // Setting Input
                    $(".idRubber").prop('disabled',true)
                    $(".namaProduct").prop('disabled',true)
                    $(".jenisPart").prop('disabled',true)

                    $('#idTMKaretKeLilin').val(data.data.idTMKaretLilin)
                    $('#cari').val(data.data.idTMKaretLilin)
                    $('#action').val('ubah')

                    // Swal.fire({
                    //     icon: 'success',
                    //     title: 'Yaayy...',
                    //     text: "Success",
                    // })
                    return;
                },
                error: function(xhr){
                    let message = xhr?.responseJSON?.message == undefined ? "Server Error" : xhr?.responseJSON?.message
                    // It will executed if response from backend is error
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: message,
                    })
                    return;
                }
            })
        }

        function UbahTMKaretLilin() {
            let idTMKaretPCBKeLilin = $("#idTMKaretKeLilin").val()
            let idRubbers = $('.idRubber')
            if (idRubbers.length === 0) {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: "Need One or More Data.",
                })
                return;
            }

            let listidRubbers = []
            for (let index = 0; index < idRubbers.length; index++) {
                let idRubber = $(idRubbers[index]).val()
                if (idRubber !== "") {
                    listidRubbers.push(idRubber)
                }
            }

            if (listidRubbers.length === 0) {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: "Need One or More Data.",
                })
                return;
            }

            let data = {listIDRubber:listidRubbers, idTMKaretPCBKeLilin:idTMKaretPCBKeLilin}
            // Setup CSRF TOKEN
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                type: "PUT",
                url: "/R&D/Percobaan/TMKaretPCBKeLilin",
                data:data,
                dataType: 'json',
                beforeSend: function () {
                    $(".preloader").show();  
                },
                complete: function () {
                    $(".preloader").fadeOut(); 
                },
                success: function(data) {
                    // Setting Buttons
                    $("#btn_baru").prop('disabled',false)
                    $("#btn_ubah").prop('disabled',false)
                    $("#btn_cetak").prop('disabled',false)
                    $("#btn_simpan").prop('disabled',true)
                    $("#btn_batal").prop('disabled',true)
                    
                    // Setting Input
                    $(".idRubber").prop('disabled',true)
                    $(".namaProduct").prop('disabled',true)
                    $(".jenisPart").prop('disabled',true)

                    // Swal.fire({
                    //     icon: 'success',
                    //     title: 'Yaayy...',
                    //     text: "Success",
                    // })
                    return;
                },
                error: function(xhr){
                    let message = xhr?.responseJSON?.message == undefined ? "Server Error" : xhr?.responseJSON?.message
                    // It will executed if response from backend is error
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: message,
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
                url: "/R&D/Percobaan/TMKaretPCBKeLilin/search",
                data:data,
                dataType: 'json',
                beforeSend: function () {
                    $(".preloader").show();  
                },
                complete: function () {
                    $(".preloader").fadeOut(); 
                },
                success: function(data) {
                    // Empty row in tbody
                    $("#tabel1  tbody").empty()
                    // Insert item to table
                    data.data.items.forEach(function (value, i) {
                        // Determine jenis
                        let jenis = "";
                        if (value.TypeProcess == 27) {
                            jenis = "Kepala"
                        } else if (value.TypeProcess == 26){
                            jenis = "Mainan"
                        } else if (value.TypeProcess == 25){
                            jenis = "Component"
                        } else {
                            jenis = "Component"
                        }
                        // Setup table row
                        let number = $('#tabel1 tr').length;
                        let trStart = "<tr class='klik' id='Row_"+number+"'>"
                        let trEnd = "</tr>"
                        let no = '<td class="m-0 p-0"><input type="text" class="form-control form-control-sm fs-6 w-100 text-center nomor" name="no" readonly value="'+number+'"></td>'
                        let idrubber = '<td class="m-0 p-0"> <input type="text" disabled="true" onchange="GetRubber(this.value,'+number+')" oncontextmenu="klikme('+number+',event)" onKeyPress="nextRowEvent('+number+',event)" class="form-control form-control-sm fs-6 w-100 text-center idRubber" name="idRubber" id="idRubber_'+number+'" value="'+value.IDRubber+'"> </td>'
                        let namaProduct = '<td class="m-0 p-0 "><input type="text" disabled="true" oncontextmenu="klikme('+number+',event)" readonly class="form-control form-control-sm fs-6 w-100 text-center namaProduct" name="namaProduct" id="namaProduct_'+number+'" value="'+value.NamaProduct+'"> </td>'
                        let jenisPart = '<td class="m-0 p-0 "> <input type="text" disabled="true" oncontextmenu="klikme('+number+',event)" readonly class="form-control form-control-sm fs-6 w-100 text-center jenisPart" name="jenisPart" id="jenisPart_'+number+'" value="'+jenis+'"> </td>'
                        let finalItem = ""
                        let rowitem = finalItem.concat(trStart, no, idrubber, namaProduct, jenisPart, trEnd)

                        // add row to tbody
                        $("#tabel1 > tbody").append(rowitem);
                    })


                    // Set Button
                    $("#btn_baru").prop('disabled',false)
                    $("#btn_cetak").prop('disabled',false)
                    if (data.data.PostDate == null) {
                        $("#btn_ubah").prop('disabled',false)
                    } else {
                        $("#btn_ubah").prop('disabled',true)
                    }
                    $("#btn_simpan").prop('disabled',true)
                    $("#btn_batal").prop('disabled',true)
                    
                    $('#idTMKaretKeLilin').val(data.data.ID)
                    $('#action').val('ubah')
                },
                error: function(xhr){
                    let message = xhr?.responseJSON?.message == undefined ? "Server Error" : xhr?.responseJSON?.message
                    // It will executed if response from backend is error
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: message,
                    })
                    return;
                }
            })
        }

        // KlikCetak
        function klikCetak() {
            let idTMKaretKeLilin = $('#idTMKaretKeLilin').val()
            if (idTMKaretKeLilin !== ''){
                window.open('/R&D/Percobaan/TMKaretPCBKeLilin/cetak?idTMKaretLilin='+idTMKaretKeLilin, '_blank');
            }else{
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Payroll belum terpilih',
                })
                return
            }
        }

    </script>
    

@endsection
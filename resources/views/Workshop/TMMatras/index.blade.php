<?php $title = 'TM Matras Ke GT'; ?>
@extends('layouts.backend-Theme-3.app')
@section('title', '$title')

@section('Dashboard')
    <h2 class="m-0">{{ $title }}</h2>
    <ol class="breadcrumb sm-2">
        <li class="breadcrumb-item"><a href="/">Home </a></li>
        <li class="breadcrumb-item">Workshop </li>
        <li class="breadcrumb-item active">TM Matras Ke GT </li>
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

                @include('Workshop.TMMatras.data')
                
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
            // Getting idTMMatras from hidden input
            let idTMMatras = $('#idTMMatras').val()
            if (idTMMatras != "") {
                // If idTMMatras have value. It will reload th page
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
            let idMatras = '<td class="m-0 p-0"> <input type="text" onchange="GetMatras(this.value,'+number+')" oncontextmenu="klikme('+number+',event)" onKeyPress="nextRowEvent('+number+',event)" class="form-control form-control-sm fs-6 w-100 text-center idMatras" name="idMatras" id="idMatras_'+number+'" value=""> </td>'
            let namaMatras = '<td class="m-0 p-0 "><input type="text" oncontextmenu="klikme('+number+',event)" readonly class="form-control form-control-sm fs-6 w-100 text-center namaMatras" name="namaMatras" id="namaMatras_'+number+'" value=""> </td>'
            let jenisMatras = '<td class="m-0 p-0 "> <input type="text" oncontextmenu="klikme('+number+',event)" readonly class="form-control form-control-sm fs-6 w-100 text-center jenisMatras" name="jenisMatras" id="jenisMatras_'+number+'" value=""> </td>'
            let finalItem = ""
            let rowitem = finalItem.concat(trStart, no, idMatras, namaMatras, jenisMatras, trEnd)
            
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
            let idMatras = '<td class="m-0 p-0"> <input type="text" onchange="GetMatras(this.value,'+number+')" oncontextmenu="klikme('+number+',event)" onKeyPress="nextRowEvent('+number+',event)" class="form-control form-control-sm fs-6 w-100 text-center idMatras" name="idMatras" id="idMatras_'+number+'" value=""> </td>'
            let namaMatras = '<td class="m-0 p-0 "><input type="text" oncontextmenu="klikme('+number+',event)" readonly class="form-control form-control-sm fs-6 w-100 text-center namaMatras" name="namaMatras" id="namaMatras_'+number+'" value=""> </td>'
            let jenisMatras = '<td class="m-0 p-0 "> <input type="text" oncontextmenu="klikme('+number+',event)" readonly class="form-control form-control-sm fs-6 w-100 text-center jenisMatras" name="jenisMatras" id="jenisMatras_'+number+'" value=""> </td>'
            let finalItem = ""
            let rowitem = finalItem.concat(trStart, no, idMatras, namaMatras, jenisMatras, trEnd)
            // add row to tbody
            $("#tabel1 > tbody").append(rowitem);
            $('#idMatras_'+number).focus()
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
                    $('#idMatras_'+nextrow).focus()
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

            // Get idTMMatras value from hidden input for checking
            let idTMMatras = $('#idTMMatras').val()
            // Check if idTMMatras have value
            if (idTMMatras == ""){
                // If idTMMatras didn't have value or blank it will just remove the row
                
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
                        
                        // Set new idMatras properties
                        $(elem).find('.idMatras').attr('onchange',"GetMatras(this.value,"+newIndex+")")
                        $(elem).find('.idMatras').attr('oncontextmenu',"klikme("+newIndex+",event)")
                        $(elem).find('.idMatras').attr('onkeypress',"nextRowEvent("+newIndex+",event)")
                        $(elem).find('.idMatras').attr('id',"idMatras_"+newIndex)                       
                        
                        // Set Nomor
                        $(elem).find('.nomor').val(newIndex)

                    })
                }
            } else { // If idTMMatras have value
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
                        
                        // Set new idMatras properties
                        $(elem).find('.idMatras').attr('onchange',"GetMatras(this.value,"+newIndex+")")
                        $(elem).find('.idMatras').attr('oncontextmenu',"klikme("+newIndex+",event)")
                        $(elem).find('.idMatras').attr('onkeypress',"nextRowEvent("+newIndex+",event)")
                        $(elem).find('.idMatras').attr('id',"idMatras_"+newIndex)                       
                        
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

        function GetMatras(ValueIDMatras, index) {
            let idMatras = $('#idMatras_'+index).val()
            if (idMatras == "") {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: "idMatras can't be blank"
                })
                return;
            }

            // Check if that idMatras Exists on inputs
            let idMatrass = $('.idMatras')
            let exists = false;
            idMatrass.map(function () {
                if (exists !== true){
                    if (this.id !== "idMatras_"+index){
                        if ($(this).val() == idMatras){
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
                    text: 'ID Matras Dengan Nomor Tersebut Sudah ada.',
                })
                $("#idMatras_"+index).val("")
                return
            }

            let data = {idMatras:idMatras}

            $.ajax({
                type: "GET",
                url: "/Workshop/TMMatras/matras",
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
                    $('#namaMatras_'+index).val(data.data.SW)
                    $('#jenisMatras_'+index).val(data.data.JenisMatras)
                    return;
                },
                error: function(xhr){
                    // It will executed if response from backend is error
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: xhr.responseJSON.message,
                    })
                    $("#idMatras_"+index).val("")
                    return;
                }
            })
        }

        function KlikSimpan () {
            let action = $('#action').val()
            if (action == "simpan") {
                SimpanTMMatras()
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
            $(".idMatras").prop('disabled',false)
            $(".namaMatras").prop('disabled',false)
            $(".jenisMatras").prop('disabled',false)
        }

        function SimpanTMMatras() {
            let idMatrass = $('.idMatras')
            if (idMatrass.length === 0) {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: "Minimal TM Matras adalah 1 Item.",
                })
                return;
            }

            let listIDMatras = []
            for (let index = 0; index < idMatrass.length; index++) {
                let idMatras = $(idMatrass[index]).val()
                if (idMatras !== "") {
                    listIDMatras.push(idMatras)
                }
            }

            if (listIDMatras.length === 0) {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: "Minimal TM Matras adalah 1 Item.",
                })
                return;
            }

            let data = {listIdMatras:listIDMatras}
            
            // Setup CSRF TOKEN
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                type: "POST",
                url: "/Workshop/TMMatras",
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
                    $(".idMatras").prop('disabled',true)
                    $(".namaMatras").prop('disabled',true)
                    $(".jenisMatras").prop('disabled',true)

                    $('#idTMMatras').val(data.data.id)
                    $('#cari').val(data.data.id)
                    $('#action').val('ubah')
                    return;
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

        function UbahTMKaretLilin() {
            let idTMMatras = $("#idTMMatras").val()
            let idMatrass = $('.idMatras')
            if (idMatrass.length === 0) {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: "Minimal TM Matras adalah 1 Item.",
                })
                return;
            }

            let listIDMatras = []
            for (let index = 0; index < idMatrass.length; index++) {
                let idMatras = $(idMatrass[index]).val()
                if (idMatras !== "") {
                    listIDMatras.push(idMatras)
                }
            }

            if (listIDMatras.length === 0) {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: "Minimal TM Matras adalah 1 Item.",
                })
                return;
            }

            let data = {listIdMatras:listIDMatras, idTMMatras:idTMMatras}
            // Setup CSRF TOKEN
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                type: "PUT",
                url: "/Workshop/TMMatras",
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
                    $(".idMatras").prop('disabled',true)
                    $(".namaMatras").prop('disabled',true)
                    $(".jenisMatras").prop('disabled',true)

                    return;
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
            let data = {idTMMatras:cari}

            $.ajax({
                type: "GET",
                url: "/Workshop/TMMatras/search",
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
                        // Setup table row
                        let number = $('#tabel1 tr').length;
                        let trStart = "<tr class='klik' id='Row_"+number+"'>"
                        let trEnd = "</tr>"
                        let no = '<td class="m-0 p-0"><input type="text" class="form-control form-control-sm fs-6 w-100 text-center nomor" name="no" readonly value="'+number+'"></td>'
                        let idMatras = '<td class="m-0 p-0"> <input type="text" disabled="true" onchange="GetMatras(this.value,'+number+')" oncontextmenu="klikme('+number+',event)" onKeyPress="nextRowEvent('+number+',event)" class="form-control form-control-sm fs-6 w-100 text-center idMatras" name="idMatras" id="idMatras_'+number+'" value="'+value.IDMatras+'"> </td>'
                        let namaMatras = '<td class="m-0 p-0 "><input type="text" disabled="true" oncontextmenu="klikme('+number+',event)" readonly class="form-control form-control-sm fs-6 w-100 text-center namaMatras" name="namaMatras" id="namaMatras_'+number+'" value="'+value.matras.SW+'"> </td>'
                        let jenisMatras = '<td class="m-0 p-0 "> <input type="text" disabled="true" oncontextmenu="klikme('+number+',event)" readonly class="form-control form-control-sm fs-6 w-100 text-center jenisMatras" name="jenisMatras" id="jenisMatras_'+number+'" value="'+value.matras.JenisMatras+'"> </td>'
                        let finalItem = ""
                        let rowitem = finalItem.concat(trStart, no, idMatras, namaMatras, jenisMatras, trEnd)

                        // add row to tbody
                        $("#tabel1 > tbody").append(rowitem);
                    })


                    // Set Button
                    $("#btn_baru").prop('disabled',false)
                    $("#btn_cetak").prop('disabled',false)
                    if (data.data.Active == "A") {
                        $("#btn_ubah").prop('disabled',false)
                    } else {
                        $("#btn_ubah").prop('disabled',true)
                    }
                    $("#btn_simpan").prop('disabled',true)
                    $("#btn_batal").prop('disabled',true)
                    
                    $('#idTMMatras').val(data.data.ID)
                    $('#action').val('ubah')
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

        // KlikCetak
        function klikCetak() {
            let idTMMatras = $('#idTMMatras').val()
            if (idTMMatras !== ''){
                window.open('/Workshop/TMMatras/cetak?idTMMatras='+idTMMatras, '_blank');
            }else{
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'TM Matras belum terpilih',
                })
                return
            }
        }

    </script>
    

@endsection
<?php $title = 'TM Karet QC PCB ke Lilin'; ?>
@extends('layouts.backend-Theme-3.app')
@section('title', '$title')

@section('Dashboard')
    <h2 class="m-0">{{ $title }}</h2>
    <ol class="breadcrumb sm-2">
        <li class="breadcrumb-item"><a href="/">Home </a></li>
        <li class="breadcrumb-item">R&D </li>
        <li class="breadcrumb-item">Percobaan </li>
        <li class="breadcrumb-item active">TM Karet Lilin </li>
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

                @include('R&D.Percobaan.TMKaretQcPCBKeLilin.data3')
                
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
            scrollY: '50vh',
            scrollCollapse: true,
            paging: false,
            lengthChange: false,
            searching: false,
            ordering: false,
            info: false,
            autoWidth: true,
            responsive: true,
            fixedColumns: true,
        });

        $('#tabelitem').DataTable({
            scrollY: '50vh',
            scrollCollapse: true,
            paging: false,
            lengthChange: false,
            searching: false,
            ordering: false,
            info: false,
            autoWidth: true,
            responsive: true,
            fixedColumns: true,
        });

        function CreateTable(idTable) {
            $('#'+idTable).DataTable({
                scrollY: '50vh',
                scrollCollapse: true,
                paging: false,
                lengthChange: false,
                searching: false,
                ordering: false,
                info: false,
                autoWidth: true,
                responsive: true,
                fixedColumns: true,
            });
        }

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
            let nthkoqc = '<td class="m-0 p-0"> <input type="text" onchange="GetWorkAllocation(this.value,'+number+')" oncontextmenu="klikme('+number+',event)" onKeyPress="nextRowEvent('+number+',event)" class="form-control form-control-sm fs-6 w-100 text-center nthkoqc" name="nthkoqc" id="nthkoqc_'+number+'" value=""> </td>'
            let finalItem = ""
            let rowitem = finalItem.concat(trStart, no, nthkoqc, trEnd)
            
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
            let nthkoqc = '<td class="m-0 p-0"> <input type="text" onchange="GetWorkAllocation(this.value,'+number+')" oncontextmenu="klikme('+number+',event)" onKeyPress="nextRowEvent('+number+',event)" class="form-control form-control-sm fs-6 w-100 text-center nthkoqc" name="nthkoqc" id="nthkoqc_'+number+'" value=""> </td>'
            let finalItem = ""
            let rowitem = finalItem.concat(trStart, no, nthkoqc, trEnd)
            // add row to tbody
            $("#tabel1 > tbody").append(rowitem);
            $('#nthkoqc_'+number).focus()
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
                    $('#nthkoqc_'+nextrow).focus()
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

        function klikme2(index,e) {
            $('.klik').css('background-color', 'white');
            var top = e.pageY + 15;
            var left = e.pageX - 100;
            window.idfiell = index;
            $("#judulklik").html(index);
            $('#removeRow').attr('onclick','removeRowItem('+index+')')

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
                // Remove Item
                $("#tabelitem").dataTable().fnDestroy()
                $('tr[masterindex="'+index+'"]').remove();
                CreateTable("tabelitem")

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
                        
                        // Set new nthkoqc properties
                        $(elem).find('.nthkoqc').attr('onchange',"GetWorkAllocation(this.value,"+newIndex+")")
                        $(elem).find('.nthkoqc').attr('oncontextmenu',"klikme("+newIndex+",event)")
                        $(elem).find('.nthkoqc').attr('onkeypress',"nextRowEvent("+newIndex+",event)")
                        $(elem).find('.nthkoqc').attr('id',"nthkoqc_"+newIndex)                       
                        
                        // Set Nomor
                        $(elem).find('.nomor').val(newIndex)
                        let valnthko = $('#nthkoqc_'+newIndex).val()
                        $('tr[nthkoqc="'+valnthko+'"]').attr('masterindex',newIndex)
                        $("#tabelitem tbody tr").each((k, elem2) => {
                            newIndex2 = k+1
                            elem2.id = "RowItem_"+newIndex2

                            $(elem2).find('.noNthkoQc').attr('oncontextmenu',"klikme2("+newIndex2+",event)")
                            $(elem2).find('.noNthkoQc').attr('id',"noNthkoQc_"+newIndex2)
                            $(elem2).find('.namaProduct').attr('oncontextmenu',"klikme2("+newIndex2+",event)")
                            $(elem2).find('.namaProduct').attr('id',"namaProduct_"+newIndex2)
                            $(elem2).find('.idProduct').attr('id',"idProduct_"+newIndex2)
                            $(elem2).find('.bulanSTP').attr('oncontextmenu',"klikme2("+newIndex2+",event)")
                            $(elem2).find('.bulanSTP').attr('id',"bulanSTP_"+newIndex2)
                            $(elem2).find('.idRubber').attr('oncontextmenu',"klikme2("+newIndex2+",event)")
                            $(elem2).find('.idRubber').attr('id',"idRubber_"+newIndex2)
                            $(elem2).find('.namaProductRubber').attr('oncontextmenu',"klikme2("+newIndex2+",event)")
                            $(elem2).find('.namaProductRubber').attr('id',"namaProductRubber_"+newIndex2)
                            $(elem2).find('.idProductRubber').attr('id',"idProductRubber_"+newIndex2)
                        })
                    })
                }
            } else { // If idTMKaretKeLilin have value
                // get row length
                let number = $('#tabel1 tbody tr').length;
                if (number !== 1){
                    // If row length is more than 1 it will remove last row
                    // Get Value of that input
                    let valnthko = $('#nthkoqc_'+index).val()
                    // Remove row 
                    $("#Row_"+index).remove()
                    // Remove Item
                    $("#tabelitem").dataTable().fnDestroy()
                    $('tr[masterindex="'+index+'"]').remove();
                    CreateTable('tabelitem')
                    // loop row
                    $("#tabel1 tbody tr").each((i, elem) => {
                        newIndex = i+1
                        // Set new row index
                        elem.id = "Row_"+newIndex
                        
                        // Set new nthkoqc properties
                        $(elem).find('.nthkoqc').attr('onchange',"GetWorkAllocation(this.value,"+newIndex+")")
                        $(elem).find('.nthkoqc').attr('oncontextmenu',"klikme("+newIndex+",event)")
                        $(elem).find('.nthkoqc').attr('onkeypress',"nextRowEvent("+newIndex+",event)")
                        $(elem).find('.nthkoqc').attr('id',"nthkoqc_"+newIndex)                       
                        
                        // Set Nomor
                        $(elem).find('.nomor').val(newIndex)
                        let valnthko = $('#nthkoqc_'+newIndex).val()
                        $('tr[nthkoqc="'+valnthko+'"]').attr('masterindex',newIndex)
                        $("#tabelitem tbody tr").each((k, elem2) => {
                            newIndex2 = k+1
                            elem2.id = "RowItem_"+newIndex2

                            $(elem2).find('.noNthkoQc').attr('oncontextmenu',"klikme2("+newIndex2+",event)")
                            $(elem2).find('.noNthkoQc').attr('id',"noNthkoQc_"+newIndex2)
                            $(elem2).find('.namaProduct').attr('oncontextmenu',"klikme2("+newIndex2+",event)")
                            $(elem2).find('.namaProduct').attr('id',"namaProduct_"+newIndex2)
                            $(elem2).find('.idProduct').attr('id',"idProduct_"+newIndex2)
                            $(elem2).find('.bulanSTP').attr('oncontextmenu',"klikme2("+newIndex2+",event)")
                            $(elem2).find('.bulanSTP').attr('id',"bulanSTP_"+newIndex2)
                            $(elem2).find('.idRubber').attr('oncontextmenu',"klikme2("+newIndex2+",event)")
                            $(elem2).find('.idRubber').attr('id',"idRubber_"+newIndex2)
                            $(elem2).find('.namaProductRubber').attr('oncontextmenu',"klikme2("+newIndex2+",event)")
                            $(elem2).find('.namaProductRubber').attr('id',"namaProductRubber_"+newIndex2)
                            $(elem2).find('.idProductRubber').attr('id',"idProductRubber_"+newIndex2)
                        })
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

        function removeRowItem(index) {

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
                
                $("#tabelitem").dataTable().fnDestroy()
                // Remove row 
                $("#RowItem_"+index).remove()

                // Getting Row length for checking row length
                let number = $('#tabelitem tbody tr').length;
                CreateTable("tabelitem")
                // check row length
                if (number == 0){
                    // If length of row is 0 it will reload the page
                    window.location.reload();
                } else {
                    // loop row
                    $("#tabelitem tbody tr").each((k, elem2) => {
                        newIndex2 = k+1
                        elem2.id = "RowItem_"+newIndex2

                        $(elem2).find('.noNthkoQc').attr('oncontextmenu',"klikme2("+newIndex2+",event)")
                        $(elem2).find('.noNthkoQc').attr('id',"noNthkoQc_"+newIndex2)
                        $(elem2).find('.namaProduct').attr('oncontextmenu',"klikme2("+newIndex2+",event)")
                        $(elem2).find('.namaProduct').attr('id',"namaProduct_"+newIndex2)
                        $(elem2).find('.idProduct').attr('id',"idProduct_"+newIndex2)
                        $(elem2).find('.bulanSTP').attr('oncontextmenu',"klikme2("+newIndex2+",event)")
                        $(elem2).find('.bulanSTP').attr('id',"bulanSTP_"+newIndex2)
                        $(elem2).find('.idRubber').attr('oncontextmenu',"klikme2("+newIndex2+",event)")
                        $(elem2).find('.idRubber').attr('id',"idRubber_"+newIndex2)
                        $(elem2).find('.namaProductRubber').attr('oncontextmenu',"klikme2("+newIndex2+",event)")
                        $(elem2).find('.namaProductRubber').attr('id',"namaProductRubber_"+newIndex2)
                        $(elem2).find('.idProductRubber').attr('id',"idProductRubber_"+newIndex2)
                    })
                }
            } else { // If idTMKaretKeLilin have value
                // get row length
                let number = $('#tabelitem tbody tr').length;
                if (number !== 1){
                    // Remove row 
                    $("#tabelitem").dataTable().fnDestroy()
                    $("#RowItem_"+index).remove()
                    CreateTable('tabelitem')
                    // loop row
                    $("#tabelitem tbody tr").each((k, elem2) => {
                        newIndex2 = k+1
                        elem2.id = "RowItem_"+newIndex2

                        $(elem2).find('.noNthkoQc').attr('oncontextmenu',"klikme2("+newIndex2+",event)")
                        $(elem2).find('.noNthkoQc').attr('id',"noNthkoQc_"+newIndex2)
                        $(elem2).find('.namaProduct').attr('oncontextmenu',"klikme2("+newIndex2+",event)")
                        $(elem2).find('.namaProduct').attr('id',"namaProduct_"+newIndex2)
                        $(elem2).find('.idProduct').attr('id',"idProduct_"+newIndex2)
                        $(elem2).find('.bulanSTP').attr('oncontextmenu',"klikme2("+newIndex2+",event)")
                        $(elem2).find('.bulanSTP').attr('id',"bulanSTP_"+newIndex2)
                        $(elem2).find('.idRubber').attr('oncontextmenu',"klikme2("+newIndex2+",event)")
                        $(elem2).find('.idRubber').attr('id',"idRubber_"+newIndex2)
                        $(elem2).find('.namaProductRubber').attr('oncontextmenu',"klikme2("+newIndex2+",event)")
                        $(elem2).find('.namaProductRubber').attr('id',"namaProductRubber_"+newIndex2)
                        $(elem2).find('.idProductRubber').attr('id',"idProductRubber_"+newIndex2)
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

        function GetWorkAllocation(ValueNthkoQc, index) {
            let workAllocation = $('#nthkoqc_'+index).val()
            if (workAllocation == "") {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: "WorkAllocation can't be blank"
                })
                return;
            }

            // Check if that nthkoqc Exists on inputs
            let nthkoqcs = $('.nthkoqc')
            let exists = false;
            nthkoqcs.map(function () {
                if (exists !== true){
                    if (this.id !== "nthkoqc_"+index){
                        if ($(this).val() == workAllocation){
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
                    text: 'NTHKO QC Dengan Nomor Tersebut Sudah ada.',
                })
                $("#nthkoqc_"+index).val("")
                return
            }

            let data = {workAllocation:workAllocation}
            
            // Setup CSRF TOKEN
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                type: "POST",
                url: "/R&D/Percobaan/TMKaretQcPCBKeLilin/items",
                data:data,
                cache: false,
                dataType: 'json',
                beforeSend: function () {
                    $(".preloader").show();  
                },
                complete: function () {
                    $(".preloader").fadeOut(); 
                },
                success: function(data) {
                    $("#tabelitem").dataTable().fnDestroy()
                    if ($('tr[masterindex]').length == 0) {
                        $('#tabelitem tbody > tr').remove();
                    }
                    $('tr[masterindex="'+index+'"]').remove();
                    
                    // Insert item to table
                    data.data.forEach(function (value, i) {
                        value.rubberKepala.forEach(function (valueRubber, j) {
                            let number = $('#tabelitem tr').length;
                            let trStart = "<tr id='RowItem_"+number+"' masterindex="+index+" nthkoqc="+value['nthkoqc']+">"
                            let trEnd = "</tr>"
                            let nonthko = '<td class="m-0 p-0"><input readonly type="text" oncontextmenu="klikme2('+number+',event)" class="form-control form-control-sm fs-6 w-100 text-center noNthkoQc" name="noNthkoQc" id="noNthkoQc_'+number+'" value="'+value["nthkoqc"]+'"></td>'
                            let product = '<td class="m-0 p-0"><input readonly type="text" oncontextmenu="klikme2('+number+',event)" class="form-control form-control-sm fs-6 w-100 text-center namaProduct" name="namaProduct" id="namaProduct_'+number+'" value="'+value["Product"]+'"><input type="hidden"  class="idProduct" name="idProduct" id="idProduct_'+number+'" value="'+value["idProduct"]+'"></td>'
                            let bulanSTP = '<td class="m-0 p-0"><input readonly type="text" oncontextmenu="klikme2('+number+',event)" class="form-control form-control-sm fs-6 w-100 text-center bulanSTP" name="bulanSTP" id="bulanSTP_'+number+'" value="'+value["bulanSTP"]+'"></td>'
                            let rubber = '<td class="m-0 p-0"><input readonly type="text" oncontextmenu="klikme2('+number+',event)" class="form-control form-control-sm fs-6 w-100 text-center idRubber" name="idRubber" id="idRubber_'+number+'" value="'+value["rubberKepala"][j]+'"></td>'
                            let namaRubber = '<td class="m-0 p-0"><input readonly type="text" oncontextmenu="klikme2('+number+',event)" class="form-control form-control-sm fs-6 w-100 text-center namaProductRubber" name="namaProductRubber" id="namaProductRubber_'+number+'" value="'+value["namaProductKepala"][j]+'"><input type="hidden"  class="idProductRubber" name="idProductRubber" id="idProductRubber_'+number+'" value="'+value["idProductKepala"][j]+'"></td>'
                            let finalItem = ""
                            let rowitem = finalItem.concat(trStart, nonthko, product, bulanSTP, rubber, namaRubber, trEnd)
                            $("#tabelitem > tbody").append(rowitem);
                        })
                        value.rubberMainan.forEach(function (valueRubber, j) {
                            let number = $('#tabelitem tr').length;
                            let trStart = "<tr id='RowItem_"+number+"' masterindex="+index+" nthkoqc="+value['nthkoqc']+">"
                            let trEnd = "</tr>"
                            let nonthko = '<td class="m-0 p-0"><input readonly type="text" oncontextmenu="klikme2('+number+',event)" class="form-control form-control-sm fs-6 w-100 text-center noNthkoQc" name="noNthkoQc" id="noNthkoQc_'+number+'" value="'+value["nthkoqc"]+'"></td>'
                            let product = '<td class="m-0 p-0"><input readonly type="text" oncontextmenu="klikme2('+number+',event)" class="form-control form-control-sm fs-6 w-100 text-center namaProduct" name="namaProduct" id="namaProduct_'+number+'" value="'+value["Product"]+'"><input type="hidden"  class="idProduct" name="idProduct" id="idProduct_'+number+'" value="'+value["idProduct"]+'"></td>'
                            let bulanSTP = '<td class="m-0 p-0"><input readonly type="text" oncontextmenu="klikme2('+number+',event)" class="form-control form-control-sm fs-6 w-100 text-center bulanSTP" name="bulanSTP" id="bulanSTP_'+number+'" value="'+value["bulanSTP"]+'"></td>'
                            let rubber = '<td class="m-0 p-0"><input readonly type="text" oncontextmenu="klikme2('+number+',event)" class="form-control form-control-sm fs-6 w-100 text-center idRubber" name="idRubber" id="idRubber_'+number+'" value="'+value["rubberMainan"][j]+'"></td>'
                            let namaRubber = '<td class="m-0 p-0"><input readonly type="text" oncontextmenu="klikme2('+number+',event)" class="form-control form-control-sm fs-6 w-100 text-center namaProductRubber" name="namaProductRubber" id="namaProductRubber_'+number+'" value="'+value["namaProductMainan"][j]+'"><input type="hidden"  class="idProductRubber" name="idProductRubber" id="idProductRubber_'+number+'" value="'+value["idProductMainan"][j]+'"></td>'
                            let finalItem = ""
                            let rowitem = finalItem.concat(trStart, nonthko, product, bulanSTP, rubber, namaRubber, trEnd)
                            $("#tabelitem > tbody").append(rowitem);
                        })
                        value.rubberComponent.forEach(function (valueRubber, j) {
                            let number = $('#tabelitem tr').length;
                            let trStart = "<tr id='RowItem_"+number+"' masterindex="+index+" nthkoqc="+value['nthkoqc']+">"
                            let trEnd = "</tr>"
                            let nonthko = '<td class="m-0 p-0"><input readonly type="text" oncontextmenu="klikme2('+number+',event)" class="form-control form-control-sm fs-6 w-100 text-center noNthkoQc" name="noNthkoQc" id="noNthkoQc_'+number+'" value="'+value["nthkoqc"]+'"></td>'
                            let product = '<td class="m-0 p-0"><input readonly type="text" oncontextmenu="klikme2('+number+',event)" class="form-control form-control-sm fs-6 w-100 text-center namaProduct" name="namaProduct" id="namaProduct_'+number+'" value="'+value["Product"]+'"><input type="hidden"  class="idProduct" name="idProduct" id="idProduct_'+number+'" value="'+value["idProduct"]+'"></td>'
                            let bulanSTP = '<td class="m-0 p-0"><input readonly type="text" oncontextmenu="klikme2('+number+',event)" class="form-control form-control-sm fs-6 w-100 text-center bulanSTP" name="bulanSTP" id="bulanSTP_'+number+'" value="'+value["bulanSTP"]+'"></td>'
                            let rubber = '<td class="m-0 p-0"><input readonly type="text" oncontextmenu="klikme2('+number+',event)" class="form-control form-control-sm fs-6 w-100 text-center idRubber" name="idRubber" id="idRubber_'+number+'" value="'+value["rubberComponent"][j]+'"></td>'
                            let namaRubber = '<td class="m-0 p-0"><input readonly type="text" oncontextmenu="klikme2('+number+',event)" class="form-control form-control-sm fs-6 w-100 text-center namaProductRubber" name="namaProductRubber" id="namaProductRubber_'+number+'" value="'+value["namaProductComponent"][j]+'"><input type="hidden"  class="idProductRubber" name="idProductRubber" id="idProductRubber_'+number+'" value="'+value["idProductComponent"][j]+'"></td>'
                            let finalItem = ""
                            let rowitem = finalItem.concat(trStart, nonthko, product, bulanSTP, rubber, namaRubber, trEnd)
                            $("#tabelitem > tbody").append(rowitem);
                        })
                    })
                    CreateTable("tabelitem")

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
                    $("#nthkoqc_"+index).val("")
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
            $(".nthkoqc").prop('disabled',false)
            $(".noNthkoQc").prop('disabled',false)
            $(".namaProduct").prop('disabled',false)
            $(".bulanSTP").prop('disabled',false)
            $(".idRubber").prop('disabled',false)
            $(".namaProductRubber").prop('disabled',false)
        }

        function SimpanTMKaretLilin() {
            let noNthkoQc = $('.noNthkoQc')
            let idProduct = $('.idProduct')
            let idRubber = $('.idRubber')
            let idProductRubber = $('.idProductRubber')
            if (noNthkoQc.length != idProduct.length || noNthkoQc.length != idRubber.length || noNthkoQc.length != idProductRubber.length) {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: "Panjang Data tidak sesuai.",
                })
                return;
            }
            

            let noNthkoQcs = []
            let idProducts = []
            let idRubbers = []
            let idProductRubbers = []
            for (let index = 0; index < noNthkoQc.length; index++) {
                noNthkoQcs.push($(noNthkoQc[index]).val())
                idProducts.push($(idProduct[index]).val())
                idRubbers.push($(idRubber[index]).val())
                idProductRubbers.push($(idProductRubber[index]).val())
            }

            if (noNthkoQcs.length === 0 || idProducts.length === 0 || idRubbers.length === 0 || idProductRubbers.length === 0) {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: "Need One or More Data.",
                })
                return;
            }

            let data = {noNthkoQcs:noNthkoQcs, idProducts:idProducts, idRubbers:idRubbers, idProductRubbers:idProductRubbers}
            
            // Setup CSRF TOKEN
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                type: "POST",
                url: "/R&D/Percobaan/TMKaretQcPCBKeLilin",
                data:data,
                cache: false,
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
                    $(".nthkoqc").prop('disabled',true)
                    $(".noNthkoQc").prop('disabled',true)
                    $(".namaProduct").prop('disabled',true)
                    $(".bulanSTP").prop('disabled',true)
                    $(".idRubber").prop('disabled',true)
                    $(".namaProductRubber").prop('disabled',true)

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
            let idTMKaretLilin = $("#idTMKaretKeLilin").val()
            let noNthkoQc = $('.noNthkoQc')
            let idProduct = $('.idProduct')
            let idRubber = $('.idRubber')
            let idProductRubber = $('.idProductRubber')
            if (noNthkoQc.length != idProduct.length || noNthkoQc.length != idRubber.length || noNthkoQc.length != idProductRubber.length) {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: "Panjang Data tidak sesuai.",
                })
                return;
            }
            if (idTMKaretLilin == null || idTMKaretLilin == "") {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: "idTMKaretLilin belum Terpilih.",
                })
                return;
            }
            

            let noNthkoQcs = []
            let idProducts = []
            let idRubbers = []
            let idProductRubbers = []
            for (let index = 0; index < noNthkoQc.length; index++) {
                noNthkoQcs.push($(noNthkoQc[index]).val())
                idProducts.push($(idProduct[index]).val())
                idRubbers.push($(idRubber[index]).val())
                idProductRubbers.push($(idProductRubber[index]).val())
            }

            if (noNthkoQcs.length === 0 || idProducts.length === 0 || idRubbers.length === 0 || idProductRubbers.length === 0) {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: "Need One or More Data.",
                })
                return;
            }

            let data = {idTMKaretLilin:idTMKaretLilin, noNthkoQcs:noNthkoQcs, idProducts:idProducts, idRubbers:idRubbers, idProductRubbers:idProductRubbers}
            
            // Setup CSRF TOKEN
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                type: "PUT",
                url: "/R&D/Percobaan/TMKaretQcPCBKeLilin",
                data:data,
                cache: false,
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
                    $(".nthkoqc").prop('disabled',true)
                    $(".noNthkoQc").prop('disabled',true)
                    $(".namaProduct").prop('disabled',true)
                    $(".bulanSTP").prop('disabled',true)
                    $(".idRubber").prop('disabled',true)
                    $(".namaProductRubber").prop('disabled',true)

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
                url: "/R&D/Percobaan/TMKaretQcPCBKeLilin/search",
                data:data,
                cache: false,
                dataType: 'json',
                beforeSend: function () {
                    $(".preloader").show();  
                },
                complete: function () {
                    $(".preloader").fadeOut(); 
                },
                success: function(data) {
                    // Destroy datatable;
                    $("#tabelitem").dataTable().fnDestroy()
                    $('#tabel1 tbody > tr').remove();
                    $('#tabelitem tbody > tr').remove();
                    
                    // Insert item to table
                    data.data.items.forEach(function (value, i) {
                        value.rubberKepala.forEach(function (valueRubber, j) {
                            let number = $('#tabelitem tr').length;
                            let trStart = "<tr id='RowItem_"+number+"' nthkoqc="+value['nthkoqc']+">"
                            let trEnd = "</tr>"
                            let nonthko = '<td class="m-0 p-0"><input disabled="true" readonly type="text" oncontextmenu="klikme2('+number+',event)" class="form-control form-control-sm fs-6 w-100 text-center noNthkoQc" name="noNthkoQc" id="noNthkoQc_'+number+'" value="'+value["nthkoqc"]+'"></td>'
                            let product = '<td class="m-0 p-0"><input disabled="true" readonly type="text" oncontextmenu="klikme2('+number+',event)" class="form-control form-control-sm fs-6 w-100 text-center namaProduct" name="namaProduct" id="namaProduct_'+number+'" value="'+value["Product"]+'"><input type="hidden"  class="idProduct" name="idProduct" id="idProduct_'+number+'" value="'+value["idProduct"]+'"></td>'
                            let bulanSTP = '<td class="m-0 p-0"><input disabled="true" readonly type="text" oncontextmenu="klikme2('+number+',event)" class="form-control form-control-sm fs-6 w-100 text-center bulanSTP" name="bulanSTP" id="bulanSTP_'+number+'" value="'+value["bulanSTP"]+'"></td>'
                            let rubber = '<td class="m-0 p-0"><input disabled="true" readonly type="text" oncontextmenu="klikme2('+number+',event)" class="form-control form-control-sm fs-6 w-100 text-center idRubber" name="idRubber" id="idRubber_'+number+'" value="'+value["rubberKepala"][j]+'"></td>'
                            let namaRubber = '<td class="m-0 p-0"><input disabled="true" readonly type="text" oncontextmenu="klikme2('+number+',event)" class="form-control form-control-sm fs-6 w-100 text-center namaProductRubber" name="namaProductRubber" id="namaProductRubber_'+number+'" value="'+value["namaProductKepala"][j]+'"><input type="hidden"  class="idProductRubber" name="idProductRubber" id="idProductRubber_'+number+'" value="'+value["idProductKepala"][j]+'"></td>'
                            let finalItem = ""
                            let rowitem = finalItem.concat(trStart, nonthko, product, bulanSTP, rubber, namaRubber, trEnd)
                            $("#tabelitem > tbody").append(rowitem);
                        })
                        value.rubberMainan.forEach(function (valueRubber, j) {
                            let number = $('#tabelitem tr').length;
                            let trStart = "<tr id='RowItem_"+number+"' nthkoqc="+value['nthkoqc']+">"
                            let trEnd = "</tr>"
                            let nonthko = '<td class="m-0 p-0"><input disabled="true" readonly type="text" oncontextmenu="klikme2('+number+',event)" class="form-control form-control-sm fs-6 w-100 text-center noNthkoQc" name="noNthkoQc" id="noNthkoQc_'+number+'" value="'+value["nthkoqc"]+'"></td>'
                            let product = '<td class="m-0 p-0"><input disabled="true" readonly type="text" oncontextmenu="klikme2('+number+',event)" class="form-control form-control-sm fs-6 w-100 text-center namaProduct" name="namaProduct" id="namaProduct_'+number+'" value="'+value["Product"]+'"><input type="hidden"  class="idProduct" name="idProduct" id="idProduct_'+number+'" value="'+value["idProduct"]+'"></td>'
                            let bulanSTP = '<td class="m-0 p-0"><input disabled="true" readonly type="text" oncontextmenu="klikme2('+number+',event)" class="form-control form-control-sm fs-6 w-100 text-center bulanSTP" name="bulanSTP" id="bulanSTP_'+number+'" value="'+value["bulanSTP"]+'"></td>'
                            let rubber = '<td class="m-0 p-0"><input disabled="true" readonly type="text" oncontextmenu="klikme2('+number+',event)" class="form-control form-control-sm fs-6 w-100 text-center idRubber" name="idRubber" id="idRubber_'+number+'" value="'+value["rubberMainan"][j]+'"></td>'
                            let namaRubber = '<td class="m-0 p-0"><input disabled="true" readonly type="text" oncontextmenu="klikme2('+number+',event)" class="form-control form-control-sm fs-6 w-100 text-center namaProductRubber" name="namaProductRubber" id="namaProductRubber_'+number+'" value="'+value["namaProductMainan"][j]+'"><input type="hidden"  class="idProductRubber" name="idProductRubber" id="idProductRubber_'+number+'" value="'+value["idProductMainan"][j]+'"></td>'
                            let finalItem = ""
                            let rowitem = finalItem.concat(trStart, nonthko, product, bulanSTP, rubber, namaRubber, trEnd)
                            $("#tabelitem > tbody").append(rowitem);
                        })
                        value.rubberComponent.forEach(function (valueRubber, j) {
                            let number = $('#tabelitem tr').length;
                            let trStart = "<tr id='RowItem_"+number+"' nthkoqc="+value['nthkoqc']+">"
                            let trEnd = "</tr>"
                            let nonthko = '<td class="m-0 p-0"><input disabled="true" readonly type="text" oncontextmenu="klikme2('+number+',event)" class="form-control form-control-sm fs-6 w-100 text-center noNthkoQc" name="noNthkoQc" id="noNthkoQc_'+number+'" value="'+value["nthkoqc"]+'"></td>'
                            let product = '<td class="m-0 p-0"><input disabled="true" readonly type="text" oncontextmenu="klikme2('+number+',event)" class="form-control form-control-sm fs-6 w-100 text-center namaProduct" name="namaProduct" id="namaProduct_'+number+'" value="'+value["Product"]+'"><input type="hidden"  class="idProduct" name="idProduct" id="idProduct_'+number+'" value="'+value["idProduct"]+'"></td>'
                            let bulanSTP = '<td class="m-0 p-0"><input disabled="true" readonly type="text" oncontextmenu="klikme2('+number+',event)" class="form-control form-control-sm fs-6 w-100 text-center bulanSTP" name="bulanSTP" id="bulanSTP_'+number+'" value="'+value["bulanSTP"]+'"></td>'
                            let rubber = '<td class="m-0 p-0"><input disabled="true" readonly type="text" oncontextmenu="klikme2('+number+',event)" class="form-control form-control-sm fs-6 w-100 text-center idRubber" name="idRubber" id="idRubber_'+number+'" value="'+value["rubberComponent"][j]+'"></td>'
                            let namaRubber = '<td class="m-0 p-0"><input disabled="true" readonly type="text" oncontextmenu="klikme2('+number+',event)" class="form-control form-control-sm fs-6 w-100 text-center namaProductRubber" name="namaProductRubber" id="namaProductRubber_'+number+'" value="'+value["namaProductComponent"][j]+'"><input type="hidden"  class="idProductRubber" name="idProductRubber" id="idProductRubber_'+number+'" value="'+value["idProductComponent"][j]+'"></td>'
                            let finalItem = ""
                            let rowitem = finalItem.concat(trStart, nonthko, product, bulanSTP, rubber, namaRubber, trEnd)
                            $("#tabelitem > tbody").append(rowitem);
                        })
                    })

                    CreateTable("tabelitem")

                    // Set Input
                    data.data.WorkAllocations.forEach(function (value, i) {
                        let number = $('#tabel1 tr').length;
                        let trStart = "<tr class='klik' id='Row_"+number+"'>"
                        let trEnd = "</tr>"
                        let no = '<td class="m-0 p-0"><input type="text" class="form-control form-control-sm fs-6 w-100 text-center nomor" name="no" readonly value="'+number+'"></td>'
                        let nthkoqc = '<td class="m-0 p-0"> <input type="text" disabled="true" onchange="GetWorkAllocation(this.value,'+number+')" oncontextmenu="klikme('+number+',event)" onKeyPress="nextRowEvent('+number+',event)" class="form-control form-control-sm fs-6 w-100 text-center nthkoqc" name="nthkoqc" id="nthkoqc_'+number+'" value="'+value+'"> </td>'
                        let finalItem = ""
                        let rowitem = finalItem.concat(trStart, no, nthkoqc, trEnd)
                        // add row to tbody
                        $("#tabel1 > tbody").append(rowitem);
                        $('tr[nthkoqc="'+value+'"]').attr("masterindex",i+1);
                    });


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
                window.open('/R&D/Percobaan/TMKaretQcPCBKeLilin/cetak?idTMKaretLilin='+idTMKaretKeLilin, '_blank');
            }else{
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'IDTM belum terpilih',
                })
                return
            }
        }

    </script>
    

@endsection
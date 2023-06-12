<?php $title = 'Tukang Luar'; ?>
@extends('layouts.backend-Theme-3.app')
@section('title', '$title')

@section('Dashboard')
    <h2 class="m-0">{{ $title }}</h2>
    <ol class="breadcrumb sm-2">
        <li class="breadcrumb-item"><a href="/">Home </a></li>
        <li class="breadcrumb-item">Produksi </li>
        <li class="breadcrumb-item active">Tukang Luar </li>
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
                @include('Produksi.TukangLuar.data')
                
            </div>
        </div>
    </div>
@endsection

@section('script')
{{-- sheetjs --}}
<script src="{!! asset('assets/sneatV1/assets/vendor/libs/sheetjs/xlsx.full.min.js') !!}"></script>
<script>
    function html_table_to_excel(filename,type){
        let data = document.getElementById('theTable');

        let file = XLSX.utils.table_to_book(data, {sheet: "sheet1"});

        XLSX.write(file, { bookType: type, bookSST: true, type: 'base64' });

        XLSX.writeFile(file, filename+'.' + type);
    }

    // function exportTableToExcel(tableID, filename = ''){
    //     var downloadLink;
    //     var dataType = 'application/vnd.ms-excel';
    //     var tableSelect = document.getElementById(tableID);
    //     var tableHTML = tableSelect.outerHTML.replace(/ /g, '%20');
        
    //     // Specify file name
    //     filename = filename?filename+'.xls':'excel_data.xls';
        
    //     // Create download link element
    //     downloadLink = document.createElement("a");
        
    //     document.body.appendChild(downloadLink);
        
    //     if(navigator.msSaveOrOpenBlob){
    //         var blob = new Blob(['\ufeff', tableHTML], {
    //             type: dataType
    //         });
    //         navigator.msSaveOrOpenBlob( blob, filename);
    //     }else{
    //         // Create a link to the file
    //         downloadLink.href = 'data:' + dataType + ', ' + tableHTML;
        
    //         // Setting the file name
    //         downloadLink.download = filename;
            
    //         //triggering the function
    //         downloadLink.click();
    //     }
    // }

    function KlikBaru() {
        // Getting LabTransactionID from hidden input
        let LabTransactionID = $('#LabTransactionID').val()
        // Check if LabTransactionID have value
        if (LabTransactionID != "") {
            // If LabTransactionID have value. It will reload th page
            window.location.reload()
            return;
        }
        // Disable button "Baru"
        $("#btn_baru").prop('disabled',true)
        // Enable Button "Batal dan Simpan"
        $("#btn_simpan").prop('disabled',true)
        $("#btn_batal").prop('disabled',false)
        // Enable file and buttonCheck
        $("#workallocation").prop('disabled',false)
        $("#file").prop('disabled',false)
        $('#TestResult').prop('disabled',false)
        $('#btn_check').prop('disabled',false)
    }

    function klikBatal() {
        window.location.reload()
        return;
    }

    function CariNotaTukangLuar() {
        let nomorNota = $('#nomorNota').val()
        if (nomorNota == "") {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: "Nomor Nota Tidak Boleh Kosong",
            })
            return;
        }

        let data = {nomorNota:nomorNota}

        $.ajax({
            type: "GET",
            url: "/Produksi/TukangLuar/search",
            data:data,
            dataType: 'json',
            beforeSend: function () {
                $(".preloader").show();  
            },
            complete: function () {
                $(".preloader").fadeOut(); 
            },
            success: function(data) {
                // set nomor nota to empty
                $('#nomorNota').val("")
                // set nomor nota found to nomor nota
                $('#nomorNotaFound').html(nomorNota)
                // Enable Cetak
                $('#btn_cetak').prop('disabled',false)
                // Enable Export Excel
                $('#btn_export_excel').prop('disabled',false)
                // add table
                $("#TransactionValue").html(data.data.html);
                // set Tukang
                $('#employeeName').val(data.data.tukang);
                // set Kadar
                $('#kadar').val(data.data.kadar);
                // set Proses
                $('#proses').val(data.data.proses);
                return;
            },
            error: function(xhr){
                // set nomor nota to empty
                $('#nomorNota').val("")
                // Disable Cetak
                $('#btn_cetak').prop('disabled',true)
                // Disable Export Excel
                $('#btn_export_excel').prop('disabled',true)
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

    function klikCetak() {
        let nomorNota = $('#nomorNotaFound').html()
        if (nomorNota !== ''){
            window.open('/Produksi/TukangLuar/cetak?nomorNota='+nomorNota, '_blank');
        }else{
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'nomorNota belum terpilih',
            })
            return
        }
    }

</script>
@endsection
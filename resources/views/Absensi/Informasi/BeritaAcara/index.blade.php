<?php $title = 'Data Berita Acara'; ?>
@extends('layouts.backend-Theme-3.app')
@section('title', '$title')

@section('Dashboard')
    <h2 class="m-0">{{ $title }}</h2>
    <ol class="breadcrumb sm-2">
        <li class="breadcrumb-item"><a href="/">Home </a></li>
        <li class="breadcrumb-item">Absensi </li>
        <li class="breadcrumb-item">Informasi </li>
        <li class="breadcrumb-item active">Berita Acara </li>
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

                @include('Absensi.Informasi.BeritaAcara.data')
                
            </div>
        </div>
    </div>
@endsection

@section('script')
    @include('layouts.backend-Theme-3.DataTabelButton')
    <script>
        $('#TabelBeritaAcara thead tr')
        .clone(true)
        .addClass('filters')
        .appendTo('#TabelBeritaAcara thead');
        
        var table = $('#TabelBeritaAcara').DataTable({
            scrollY: '500px',
            scrollCollapse: true,
            paging: false,
            orderCellsTop: true,
            fixedHeader: true,
            buttons: [{
                extend: 'print',
                split: ['excel', 'pdf'],
            }],
            initComplete: function() {
                var api = this.api();
                // For each column
                api.columns().eq(0).each(function(colIdx) {
                    // Set the header cell to contain the input element
                    var cell = $('.filters th').eq($(api.column(colIdx).header()).index());
                    var title = $(cell).text();
                    $(cell).html('<input type="text" placeholder="' + title + '" />');
                    // On every keypress in this input
                    $('input', $('.filters th').eq($(api.column(colIdx).header()).index())).off('keyup change').on('change', function(e) {
                        // Get the search value
                        $(this).attr('title', $(this).val());
                        var regexr = '({search})'; //$(this).parents('th').find('select').val();
                        var cursorPosition = this.selectionStart;
                        // Search the column for that value
                        api.column(colIdx).search(this.value != '' ? regexr.replace('{search}', '(((' + this.value + ')))') : '', this.value != '', this.value == '').draw();
                    }).on('keyup', function(e) {
                        e.stopPropagation();
                        $(this).trigger('change');
                        $(this).focus()[0].setSelectionRange(cursorPosition, cursorPosition);
                    });
                });
            },
            });
            table.buttons().container().appendTo('#TabelBeritaAcara_wrapper .col-md-6:eq(0)');


        function SearchBeritaAcara() {
            let tgl_awal = $("#tanggal_awal").val()
            let tgl_akhir = $('#tanggal_akhir').val()

            if (tgl_awal == "" || tgl_akhir == "") {
                return;
            }

            $.ajax({
                type:'GET',
                url:'/Absensi/Informasi/BeritaAcara/Cari',
                data: {tgl_awal: tgl_awal, tgl_akhir: tgl_akhir},
                dataType: "json",
                beforeSend: function () {
                    $(".preloader").show();  
                },
                complete: function () {
                    $(".preloader").fadeOut(); 
                },
                success:function(data){
                    $("#TabelBeritaAcara").dataTable().fnDestroy()
                    $('#TabelBeritaAcara tbody > tr').remove();
                    data.data.forEach(element => {
                        tr = document.createElement('tr');
                        Object.keys(element).forEach(function(k){
                            td = document.createElement('td'); 
                            td.innerHTML = element[k]; 
                            tr.appendChild(td);
                        });
                        $('#TabelBeritaAcara tbody').append(tr);
                    });
                    var table = $('#TabelBeritaAcara').DataTable({
                        scrollY: '500px',
                        scrollCollapse: true,
                        paging: false,
                        orderCellsTop: true,
                        fixedHeader: true,
                        buttons: [{
                            extend: 'print',
                            split: ['excel', 'pdf'],
                        }],
                        initComplete: function() {
                            var api = this.api();
                            // For each column
                            api.columns().eq(0).each(function(colIdx) {
                                // Set the header cell to contain the input element
                                var cell = $('.filters th').eq($(api.column(colIdx).header()).index());
                                var title = $(cell).text();
                                $(cell).html('<input type="text" placeholder="' + title + '" />');
                                // On every keypress in this input
                                $('input', $('.filters th').eq($(api.column(colIdx).header()).index())).off('keyup change').on('change', function(e) {
                                    // Get the search value
                                    $(this).attr('title', $(this).val());
                                    var regexr = '({search})'; //$(this).parents('th').find('select').val();
                                    var cursorPosition = this.selectionStart;
                                    // Search the column for that value
                                    api.column(colIdx).search(this.value != '' ? regexr.replace('{search}', '(((' + this.value + ')))') : '', this.value != '', this.value == '').draw();
                                }).on('keyup', function(e) {
                                    e.stopPropagation();
                                    $(this).trigger('change');
                                    $(this).focus()[0].setSelectionRange(cursorPosition, cursorPosition);
                                });
                            });
                        },
                    });
                    table.buttons().container().appendTo('#TabelBeritaAcara_wrapper .col-md-6:eq(0)');

                },
                error:function(xhr){
                    // Invalid Jenis
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: "Invalid Request",
                    })
                    return;
                }
            });   
        }
    </script>
@endsection
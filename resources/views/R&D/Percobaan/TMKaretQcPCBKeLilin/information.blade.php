<?php $title = 'Informasi TM Karet PCB Ke Lilin'; ?>
@extends('layouts.backend-Theme-3.app')
@section('title', '$title')

@section('Dashboard')
    <h2 class="m-0">{{ $title }}</h2>
    <ol class="breadcrumb sm-2">
        <li class="breadcrumb-item"><a href="/">Home </a></li>
        <li class="breadcrumb-item">R&D </li>
        <li class="breadcrumb-item">Percobaan </li>
        <li class="breadcrumb-item active">Informasi TM Karet PCB Ke Lilin </li>
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

                <div class="card-body">
                    <div id="DataTableContainer">
                        <table class="table table-striped" id="TabelInformasi">
                            <thead class="table-secondary">
                                <tr>
                                    <th>ID</th>
                                    <th>No NTHKO QC</th>
                                    <th>Tanggal Posting</th>
                                    <th>ID Karet</th>
                                    <th>Produk Karet</th>
                                    <th>Produk</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($data as $item)
                                    <tr>
                                        <td>{{$item->IDM}}</td>
                                        <td>{{$item->WorkAllocation}}</td>
                                        <td>{{$item->PostDate}}</td>
                                        <td>{{$item->IDRubber}}</td>
                                        @if ($item->ProductKaretSKU == null)
                                        <td>{{$item->ProductKaretSW}}</td>
                                        @else
                                        <td>{{$item->ProductKaretSKU}}</td>
                                        @endif
                                        <td>{{$item->Product}}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                
            </div>
        </div>
    </div>
@endsection

@section('script')
    @include('layouts.backend-Theme-3.DataTabelButton')
    <script>
        $('#TabelInformasi thead tr')
        .clone(true)
        .addClass('filters')
        .appendTo('#TabelInformasi thead');
        
        var table = $('#TabelInformasi').DataTable({
            scrollY: '500px',
            scrollCollapse: true,
            paging: true,
            lengthChange: false,
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
        table.buttons().container().appendTo('#TabelInformasi_wrapper .col-md-6:eq(0)');
    </script>
@endsection
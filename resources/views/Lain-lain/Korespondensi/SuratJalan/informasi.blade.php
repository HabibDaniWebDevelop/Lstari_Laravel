<?php $title = 'Informasi Surat Jalan'; ?>
@extends('layouts.backend-Theme-3.app')
@section('title', '$title')

@section('Dashboard')
    <h2 class="m-0">{{ $title }}</h2>
    <ol class="breadcrumb sm-2">
        <li class="breadcrumb-item"><a href="/">Home </a></li>
        <li class="breadcrumb-item">Lain-Lain </li>
        <li class="breadcrumb-item">Korespondensi </li>
        <li class="breadcrumb-item ">Surat Jalan </li>
        <li class="breadcrumb-item active">Informasi </li>
    </ol>
@endsection

@section('css')

    <style>

    </style>
    
    {{-- DevExtreme --}}
    <link rel="stylesheet" href="{!! asset('assets/sneatV1/assets/vendor/libs/DevExtreme/css/dx.common.css') !!}">
    <link rel="stylesheet" href="{!! asset('assets/sneatV1/assets/vendor/libs/DevExtreme/css/dx.material.orange.light.compact.css') !!}">

@endsection

@section('container')
    <div class="row">
        <div class="col-md-12">
            <div class="card mb-4">

                <div class="card-body">
                    <div id="gridContainer">
                    </div>
                </div>
                
            </div>
        </div>
    </div>
@endsection

@section('script')
    {{-- DevExtreme --}}
    <script src="{!! asset('assets/sneatV1/assets/vendor/libs/DevExtreme/js/jszip.min.js') !!}"></script>
    <script src="{!! asset('assets/sneatV1/assets/vendor/libs/DevExtreme/js/dx.all.js') !!}"></script>
    <script>
        $(function() {
            $("#gridContainer").dxDataGrid({
                dataSource: '/Lain-lain/Korespondensi/SuratJalan/resource-informasi',
                columnsAutoWidth: true,
                width: '100%',
                height: 1000,
                allowColumnReordering: true,
                showBorders: true,
                headerFilter: { visible: true },
                rowAlternationEnabled : true,
                allowColumnResizing: true,
                hoverStateEnabled: true,      
                filterRow: {
                    visible: true,
                    applyFilter: "auto"
                },         
                searchPanel: {
                    visible: true
                },
                paging: { enabled: true },
                grouping: {  
                    autoExpandAll: false  
                },  
                groupPanel: {
                    visible: true
                },
                "export": {
                    enabled: true,
                    fileName: "Informasi Surat Jalan",
                    allowExportSelectedData: true
                }, 
                columns: [
                    {
                        dataField: "SW",
                        dataType: "string",
                        caption: "No Referensi",
                        fixed: true,
                        groupIndex: 1                  
                    },{
                        dataField: "TransDate",
                        dataType: "date",
                        caption: "Tanggal",
                        fixed: true,
                        groupIndex: 0
                    },
                    {
                        dataField: "recipient",
                        dataType: "string",
                        caption: "Kepada",
                        fixed: true,
                                                    //groupIndex: 2
                    },
                    {
                        dataField: "Addresss",
                        dataType: "string",
                        caption: "Alamat",
                        fixed: true,
                                                    //groupIndex: 3
                    },
                    {
                        dataField: "Ordinal",
                        dataType: "number",
                        caption: "No.",
                        fixed: true
                    },
                    {
                        dataField: "Item",//SKUe
                        dataType: "string",
                        caption: "Nama",
                        fixed: true
                    },
                    {
                        dataField: "jumlah",
                        dataType: "string",
                        caption: "Qty",
                        fixed: true
                    },
                    {
                        dataField: "Note",
                        dataType: "string",
                        caption: "Catatan",
                        fixed: true
                    },
                    {
                        dataField: "Bulan",
                        dataType: "number",
                        caption: "Bulan",
                        fixed: true
                    },
                    {
                        dataField: "Tahun",
                        dataType: "number",
                        caption: "Tahun",
                        fixed: true
                    },
                    {
                        dataField: "SWPurpose",
                        dataType: "number",
                        caption: "Purpose",
                        fixed: true
                    },
                                    {
                        dataField: "linkPopupLT",
                        dataType: "string",
                        caption: "Cetak",
                        cellTemplate: function(container, options) {
                            $('<a>' + '<i class="fa fa-file-text">' + ' Cetak' + '</i>' + '</a>')
                                .attr('href', options.value)
                                .attr('target', '_blank')
                                .appendTo(container);
                        }
                    }
                
                
                ]
            });

            $("#autoExpand").dxCheckBox({
                value: true,
                text: "Expand All Groups",
                onValueChanged: function(data) {
                    dataGrid.option("grouping.autoExpandAll", data.value);
                }
            });
        });
    </script>
@endsection
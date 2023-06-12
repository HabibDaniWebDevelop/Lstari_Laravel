<?php $title = 'Informasi Bahan Pembantu'; ?>
@extends('layouts.backend-Theme-3.app')
@section('title', '$title')

@section('Dashboard')
    <h2 class="m-0">{{ $title }}</h2>
    <ol class="breadcrumb sm-2">
        <li class="breadcrumb-item"><a href="/">Home </a></li>
        <li class="breadcrumb-item">Invebtory </li>
        <li class="breadcrumb-item">Informasi </li>
        <li class="breadcrumb-item active">InformasiBahanPembanu</li>
    </ol>
@endsection

@section('css')

    <style>
        .dx-datagrid-headers {
            background-color: #e7e9ed;
            color: black;
        }
    </style>
    {{-- DevExtreme --}}
    <link rel="stylesheet" href="{!! asset('assets/sneatV1/assets/vendor/libs/DevExtreme/css/dx.common-new.css') !!}">
    <link rel="stylesheet" href="{!! asset('assets/sneatV1/assets/vendor/libs/DevExtreme/css/dx.light.compact.css') !!}">

@endsection

@section('container')
    <div class="row">
        <div class="col-md-12">
            <div class="card mb-0" style="height:calc(100vh - 255px);">
                @include('Inventori.Informasi.InformasiBahanPembanu.data')

            </div>
        </div>
    </div>
@endsection

@section('script')
    {{-- DevExtreme --}}
    <script src="{!! asset('assets/sneatV1/assets/vendor/libs/DevExtreme/js/jszip-new.min.js') !!}"></script>
    <script src="{!! asset('assets/sneatV1/assets/vendor/libs/DevExtreme/js/dx-new.all.js') !!}"></script>

    <script>
        $(document).ready(function() {

            //patch lokasi modul
            var patch = '/Workshop/SPKWorkshop/';

            $.ajax({
                type: 'GET',
                url: '/Inventori/Informasi/InformasiBahanPembanu/show',
                dataType: "json",
                beforeSend: function() {
                    $(".preloader").show();
                },
                complete: function() {
                    $(".preloader").fadeOut();
                },
                success: function(data) {

                    // Set WIP
                    var i = 0;
                    var dataGrid = $("#tabelex1").dxDataGrid({
                        dataSource: data.data,
                        allowColumnReordering: true,
                        allowColumnResizing: true,
                        columnAutoWidth: true,
                        width: '100%',
                        height: $(window).height() - 295,
                        paging: {
                            pageSize: 50,
                        },
                        pager: {
                            showPageSizeSelector: true,
                            allowedPageSizes: [50, 100, 200, 300, 500],
                        },
                        showBorders: true,
                        headerFilter: {
                            visible: true,
                        },
                        filterRow: {
                            visible: true,
                        },

                        searchPanel: {
                            visible: true,
                            highlightCaseSensitive: true,
                        },
                        scrolling: {
                            columnRenderingMode: 'virtual',
                        },
                        groupPanel: {
                            visible: true
                        },
                        grouping: {
                            autoExpandAll: true,
                        },
                        showColumnLines: false,
                        showRowLines: true,
                        rowAlternationEnabled: true,
                        hoverStateEnabled: true,
                        sorting: {
                            mode: 'multiple',
                        },

                        export: {
                            enabled: true,
                            // formats: ['pdf','excel'],
                            fileName: "Informasi Bahan Pembantu",
                            allowExportSelectedData: true
                        },

                        columns: [{
                                caption: 'No',
                                minWidth: "50",
                                cellTemplate: function(cellElement, cellInfo) {
                                    // mendapatkan nomor urut baris
                                    var rowIndex = dataGrid.pageIndex() * dataGrid
                                        .option("paging.pageSize") + cellInfo.rowIndex +
                                        1;
                                    cellElement.text(rowIndex);
                                }
                            },
                            {
                                dataField: "ID",
                                dataType: "string",
                                caption: "ID",
                                minWidth: "80"
                            },
                            {
                                dataField: "SW",
                                dataType: "string",
                                caption: "SW",
                                minWidth: "150"
                            },
                            {
                                dataField: "Description",
                                dataType: "string",
                                caption: "Description",
                                minWidth: "250",
                                // fixed: true,
                            },
                            {
                                dataField: "Lokasi",
                                dataType: "string",
                                caption: "Lokasi",
                                minWidth: "100"
                            },
                            {
                                dataField: "Type",
                                dataType: "string",
                                caption: "Type",
                                minWidth: "100"
                            },
                            {
                                dataField: "MaterialFunction",
                                dataType: "string",
                                caption: "MaterialFunction",
                                minWidth: "250"
                            },
                            {
                                dataField: "Remarks",
                                dataType: "string",
                                caption: "Remarks",
                                minWidth: "250"
                            },
                            {
                                dataField: "Gambar",
                                dataType: "string",
                                caption: "Gambar",
                                minWidth: "80",
                                cellTemplate: function(container, options) {
                                    if (options.value) {
                                        $("<i>", {
                                            "class": "fas fa-check-square"
                                        }).appendTo(container);
                                    }
                                }
                            },
                            {
                                dataField: "Area",
                                dataType: "string",
                                caption: "Area",
                                minWidth: "200",
                            },
                        ],
                        // onPageChanged: function() {
                        //     // memperbarui nomor urut pada setiap baris data
                        //     // dataGrid.refresh();
                        // },
                        onRowClick: function(e) {
                            if (e.rowType == 'group') {
                                if (e.isExpanded)
                                    e.component.collapseRow(e.key);
                                else
                                    e.component.expandRow(e.key);
                            }
                        },
                    }).dxDataGrid("instance");
                }
            })

        });
    </script>


@endsection

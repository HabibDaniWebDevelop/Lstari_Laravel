<?php $title = 'Messaging History'; ?>

@extends('layouts.backend-Theme-3.app')
@section('title', '$title')

@section('Dashboard')
    <h2 class="m-0">{{ $title }}</h2>
    <ol class="breadcrumb sm-2">
        <li class="breadcrumb-item"><a href="/">Home </a></li>
        <li class="breadcrumb-item">Lain-lain </li>
        <li class="breadcrumb-item active">Messaging</li>
    </ol>
@endsection

@section('css')
    {{-- DevExtreme --}}
    <link rel="stylesheet" href="{!! asset('assets/sneatV1/assets/vendor/libs/DevExtreme/css/dx.common-new.css') !!}">
    <link rel="stylesheet" href="{!! asset('assets/sneatV1/assets/vendor/libs/DevExtreme/css/dx.light.compact.css') !!}">

    <style>
        .dx-datagrid-headers {
            background-color: #e7e9ed;
            color: black;
        }
    </style>

@endsection

@section('container')
    <div class="row">
        <div class="col-md-12">
            <div class="card">

                @include('Lain-Lain.Messaging.data')

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
            var patch = '/Lain-lain/Messaging/';
           
            $('#name').val('{{$UserEntry}}');

            setTimeout(function() {
                $('#name').change(); 
            }, 10); 

            $('#name').change(function() {

                 var UserEntry = $('#name').val();

                $.ajax({ 
                    type: 'GET',
                    url: patch + 'show',
                    dataType: "json",
                    data: { UserEntry: UserEntry },
                    beforeSend: function() {
                        $(".preloader").show();
                    },
                    complete: function() {
                        $(".preloader").fadeOut();
                    },
                    success: function(data) {

                        var i = 0;
                        var dataGrid = $("#tabelex1").dxDataGrid({
                            dataSource: data.data,
                            allowColumnReordering: true,
                            allowColumnResizing: true,
                            columnAutoWidth: true,
                            width: '100%',
                            height: $(window).height() - 330,
                            paging: {
                                pageSize: 50,
                            },
                            pager: {
                                showPageSizeSelector: true,
                                allowedPageSizes: [50, 100, 200, 300, 500],
                            },
                            showBorders: true,
                            wordWrapEnabled: true,
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
                            showColumnLines: true,
                            showRowLines: false,
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
                                    dataField: "Sender",
                                    dataType: "string",
                                    caption: "Pengirim",
                                    minWidth: "100",
                                    cellTemplate: function(container, options) {
                                        var sender = options.value;
                                        // var userEntry = '{{$UserEntry}}';

                                        if (sender && UserEntry && sender.toLowerCase() === UserEntry.toLowerCase()) {
                                            container.append(sender);
                                        } else {
                                            var badge = $('<span>')
                                                .addClass('badge rounded-pill bg-info text-dark')
                                                .text(sender);
                                            container.append(badge);
                                        }
                                    }
                                },
                                {
                                    dataField: "ToUser",
                                    dataType: "string",
                                    caption: "Tujuan",
                                    minWidth: "100",
                                    cellTemplate: function(container, options) {
                                        var ToUser = options.value;
                                        // var userEntry = '{{$UserEntry}}';

                                        if (ToUser && UserEntry && ToUser.toLowerCase() === UserEntry.toLowerCase()) {
                                            container.append(ToUser);
                                        } else {
                                            var badge = $('<span>')
                                                .addClass('badge rounded-pill bg-info text-dark')
                                                .text(ToUser);
                                            container.append(badge);
                                        }
                                    }
                                },
                                {
                                    dataField: "Status",
                                    dataType: "string",
                                    caption: "Status",
                                    minWidth: "100",
                                    cellTemplate: function(container, options) {
                                        var Status = options.value;

                                        if (Status === 'Q') {
                                            var badge = $('<span>')
                                                .addClass('badge rounded-pill bg-secondary')
                                                .text('Antrian');
                                            container.append(badge);
                                        } else if (Status === 'P') {
                                            var badge = $('<span>')
                                                .addClass('badge rounded-pill bg-warning')
                                                .text('Preoses');
                                            container.append(badge);
                                        } else if (Status === 'S') {
                                            var badge = $('<span>')
                                                .addClass('badge rounded-pill bg-dark')
                                                .text('Selesai');
                                            container.append(badge);
                                        } else if (Status === 'C') {
                                            var badge = $('<span>')
                                                .addClass('badge rounded-pill bg-danger')
                                                .text('Batal');
                                            container.append(badge);
                                        }else {
                                            container.append(Status);
                                        }
                                    }
                                },
                                {
                                    dataField: "DateSend",
                                    dataType: 'date',
                                    caption: "Tgl Pesan",
                                    minWidth: "120",
                                    format: {
                                        formatter: function(date) {
                                            var monthNames = [
                                            'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'
                                            ];
                                            
                                            var day = date.getDate();
                                            var monthIndex = date.getMonth();
                                            var year = date.getFullYear();
                                            
                                            return day + ' ' + monthNames[monthIndex] + ' ' + year;
                                        }
                                    },
                                },
                                {
                                    dataField: "Description",
                                    dataType: "string",
                                    caption: "Pesan",
                                    minWidth: "80"
                                },
                                {
                                    dataField: "NoteReplay",
                                    dataType: "string",
                                    caption: "Tanggapan",
                                    minWidth: "80"
                                },
                                {
                                    dataField: "Date",
                                    dataType: 'date',
                                    caption: "Tgl Tanggapan",
                                    minWidth: "130",
                                    format: {
                                        formatter: function(date) {
                                            var monthNames = [
                                            'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'
                                            ];
                                            
                                            var day = date.getDate();
                                            var monthIndex = date.getMonth();
                                            var year = date.getFullYear();
                                            
                                            return day + ' ' + monthNames[monthIndex] + ' ' + year;
                                        }
                                    },
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

        });
    </script>
@endsection

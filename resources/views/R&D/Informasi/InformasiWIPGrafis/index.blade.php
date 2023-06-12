<?php $title = 'Informasi WIP Grafis'; ?>
@extends('layouts.backend-Theme-3.app')
@section('title', '$title')

@section('Dashboard')
    <h2 class="m-0">{{ $title }}</h2>
    <ol class="breadcrumb sm-2">
        <li class="breadcrumb-item"><a href="/">Home </a></li>
        <li class="breadcrumb-item">R&D </li>
        <li class="breadcrumb-item">Informasi </li>
        <li class="breadcrumb-item active">Informasi WIP Grafis </li>
    </ol>
@endsection

@section('css')

    <style>

        .dx-datagrid-headers{
            background-color: #e7e9ed;
        }

    </style>
    {{-- DevExtreme --}}
    <link rel="stylesheet" href="{!! asset('assets/sneatV1/assets/vendor/libs/DevExtreme/css/dx.common.css') !!}">
    <link rel="stylesheet" href="{!! asset('assets/sneatV1/assets/vendor/libs/DevExtreme/css/dx.material.orange.light.compact.css') !!}">

@endsection

@section('container')
    <div class="row">
        <div class="col-md-12">
            <div class="card mb-4">

                @include('R&D.Informasi.InformasiWIPGrafis.data')
                
            </div>
        </div>
    </div>
@endsection

@section('script')
    {{-- DevExtreme --}}
    <script src="{!! asset('assets/sneatV1/assets/vendor/libs/DevExtreme/js/jszip.min.js') !!}"></script>
    <script src="{!! asset('assets/sneatV1/assets/vendor/libs/DevExtreme/js/dx.all.js') !!}"></script>
    
    <script>
        function GetWIP() {
            let jenis_transaksi = $('#jenis').val()
            if (jenis_transaksi == 1) {
                $.ajax({
                    type:'GET',
                    url:'/R&D/Informasi/InformasiWIPGrafis/wipdatasource',
                    dataType: "json",
                    beforeSend: function () {
                        $(".preloader").show();  
                    },
                    complete: function () {
                        $(".preloader").fadeOut(); 
                    },
                    success:function(data){
                        console.log(data.data);
                        // Set WIP
                        var dataGrid = $("#TableDevExtreme").dxDataGrid({
                            dataSource: data.data,
                            columnsAutoWidth: true,
                            width: '100%',
                            height: 800,
                            allowColumnReordering: true,
                            showBorders: true,
                            headerFilter: { visible: true },
                            rowAlternationEnabled : true,
                            filterRow: {
                                visible: true,
                                applyFilter: "auto"
                            },         
                            selection: {
                                mode: "none" // or "multiple" | "none"
                            },  

                            searchPanel: {
                                visible: true
                            },
                            paging: { enabled: false },
                            grouping: {  
                                autoExpandAll: false  
                            },  
                            groupPanel: {
                                visible: true
                            },
                            "export": {
                                enabled: true,
                                fileName: "WIP Grafis",
                                allowExportSelectedData: true
                            },        
                            columns: [
                                {
                                    dataField: "TransDate", dataType: "date", format: 'dd-MM-yyyy', caption: "Tanggal", cssClass: "cls"
                                }, 
                                {
                                    dataField: "nthkoSebelum", dataType: "integer", caption: "No. NTHKO Sebelum", cssClass: "cls"
                                },                      
                                {
                                    dataField: "Kadar", dataType: "string", caption: "Kadar", cssClass: "cls"
                                },     
                                {
                                    dataField: "idTM", dataType: "integer", caption: "ID TM", cssClass: "cls"
                                },    
                                {
                                    dataField: "TMdari", dataType: "string", caption: "TM Area", cssClass: "cls"
                                },
                                {
                                    dataField: "TotalItem", dataType: "integer", caption: "Total Item", cssClass: "cls"
                                }, 
                                {
                                    dataField: "TotalBeratItem", dataType: "integer", caption: "Total Berat Item", cssClass: "cls"
                                }, 
                                {
                                    dataField: "spkoSekarang", dataType: "integer", caption: "SPKO", cssClass: "cls"
                                }, 
                                {
                                    dataField: "statusSPKO", dataType: "string", caption: "Status SPKO", cssClass: "cls"
                                }, 
                                {
                                    dataField: "idNTHKO", dataType: "integer", caption: "ID NTHKO", cssClass: "cls"
                                },
                                {
                                    dataField: "tanggalNTHKO", dataType: "date", format: 'dd-MM-yyyy', caption: "Tanggal NTHKO", cssClass: "cls"
                                },
                                {
                                    dataField: "statusNTHKO", dataType: "string", caption: "Status NTHKO", cssClass: "cls"
                                },                                                                                                                                                                  
                            ],
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
            } else if (jenis_transaksi == 2) {
                $.ajax({
                    type:'GET',
                    url:'/R&D/Informasi/InformasiWIPGrafis/productwipdatasource',
                    dataType: "json",
                    beforeSend: function () {
                        $(".preloader").show();  
                    },
                    complete: function () {
                        $(".preloader").fadeOut(); 
                    },
                    success:function(data){
                        console.log(data.data);
                        // Set WIP
                        var dataGrid = $("#TableDevExtreme").dxDataGrid({
                            dataSource: data.data,
                            columnsAutoWidth: true,
                            width: '100%',
                            height: 800,
                            allowColumnReordering: true,
                            showBorders: true,
                            headerFilter: { visible: true },
                            rowAlternationEnabled : true,
                            filterRow: {
                                visible: true,
                                applyFilter: "auto"
                            },         
                            selection: {
                                mode: "none" // or "multiple" | "none"
                            },  

                            searchPanel: {
                                visible: true
                            },
                            paging: { enabled: false },
                            grouping: {  
                                autoExpandAll: false  
                            },  
                            groupPanel: {
                                visible: true
                            },
                            "export": {
                                enabled: true,
                                fileName: "Product WIP Grafis",
                                allowExportSelectedData: true
                            },        
                            columns: [
                                {
                                    dataField: "id_wip_grafis", dataType: "integer", caption: "ID WIP Grafis", cssClass: "cls"
                                },
                                {
                                    dataField: "tanggal_wip", dataType: "date", format: 'dd-MM-yyyy', caption: "Tanggal WIP", cssClass: "cls"
                                },                   
                                {
                                    dataField: "product", dataType: "string", caption: "Product", cssClass: "cls"
                                },     
                                {
                                    dataField: "nomor_spko_grafis", dataType: "integer", caption: "Nomor SPKO Grafis", cssClass: "cls"
                                },    
                                {
                                    dataField: "operator", dataType: "string", caption: "Operator", cssClass: "cls"
                                },
                                {
                                    dataField: "StartFoto", dataType: "date", format: 'dd-MM-yyyy', caption: "Mulai Foto", cssClass: "cls"
                                },
                                {
                                    dataField: "EndFoto", dataType: "date", format: 'dd-MM-yyyy', caption: "Selesai Foto", cssClass: "cls"
                                },                                                                                                                                                                  
                            ],
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
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Upss Error !',
                    text: "Jenis Invalid",
                })
            }
        }
    </script>
    

@endsection
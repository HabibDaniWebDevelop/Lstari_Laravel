<?php $title = 'Informasi WIP Grafis'; ?>
@extends('layouts.backend-Theme-3.app')
@section('title', '$title')

@section('Dashboard')
    <h2 class="m-0">{{ $title }}</h2>
    <ol class="breadcrumb sm-2">
        <li class="breadcrumb-item"><a href="/">Home </a></li>
        <li class="breadcrumb-item">R&D </li>
        <li class="breadcrumb-item">Grafis </li>
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
            <div class="card mb-0" style="height:calc(100vh - 255px);">

                @include('R&D.Grafis.InformasiWIPGrafis.data')
                
            </div>
        </div>
    </div>
@endsection

@section('script')
    {{-- DevExtreme --}}
    <script src="{!! asset('assets/sneatV1/assets/vendor/libs/DevExtreme/js/jszip.min.js') !!}"></script>
    <script src="{!! asset('assets/sneatV1/assets/vendor/libs/DevExtreme/js/dx.all.js') !!}"></script>
    
    <script>
        $(document).ready(function(){
            $.ajax({
                type:'GET',
                url:'/R&D/Grafis/InformasiWIPGrafis/datasource',
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
                        columnAutoWidth: true,
                        columnMinWidth:100,
                        height: $(window).height() - 295,
                        scrollX:true,
                        scrollY:true,
                        allowColumnReordering: true,
                        allowColumnResizing:true,
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
                                dataField: "nthkoSebelum", dataType: "string", caption: "No. NTHKO Sebelum", cssClass: "cls"
                            },                      
                            {
                                dataField: "Kadar", dataType: "string", caption: "Kadar", cssClass: "cls"
                            },
                            {
                                dataField: "sumber", dataType: "string", caption: "Sumber", cssClass: "cls"
                            },    
                            {
                                dataField: "idTM", dataType: "string", caption: "ID TM", cssClass: "cls"
                            },    
                            {
                                dataField: "TMdari", dataType: "string", caption: "TM Area", cssClass: "cls"
                            },
                            {
                                dataField: "TotalItem", dataType: "string", caption: "Total Item", cssClass: "cls"
                            }, 
                            {
                                dataField: "TotalBeratItem", dataType: "string", caption: "Berat WIP", cssClass: "cls"
                            }, 
                            {
                                dataField: "spkoSekarang", dataType: "string", caption: "SPKO", cssClass: "cls"
                            },
                            {
                                dataField: "Operator", dataType: "string", caption: "Operator", cssClass: "cls"
                            }, 
                            {
                                dataField: "statusSPKO", dataType: "string", caption: "Status SPKO", cssClass: "cls"
                            },
                            {
                                dataField: "JumlahItemSPKO", dataType: "string", caption: "Item SPKO", cssClass: "cls"
                            },
                            {
                                dataField: "BeratSPKO", dataType: "string", caption: "Berat SPKO", cssClass: "cls"
                            },
                            {
                                dataField: "idNTHKO", dataType: "string", caption: "ID NTHKO", cssClass: "cls"
                            },
                            {
                                dataField: "JumlahItemNTHKO", dataType: "string", caption: "Item NTHKO", cssClass: "cls"
                            },
                            {
                                dataField: "BeratNTHKO", dataType: "string", caption: "Berat NTHKO", cssClass: "cls"
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
        });
        $('#tabel1').DataTable({
            paging: true,
            lengthChange: true,
            searching: true,
            ordering: false,
            info: false,
            autoWidth: true,
            responsive: true,
            fixedColumns: true,
        });
    </script>
    

@endsection
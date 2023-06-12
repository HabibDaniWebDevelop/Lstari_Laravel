<?php $title = 'Data Absensi Upah PSB'; ?>
@extends('layouts.backend-Theme-3.app')
@section('title', '$title')

@section('Dashboard')
    <h2 class="m-0">{{ $title }}</h2>
    <ol class="breadcrumb sm-2">
        <li class="breadcrumb-item"><a href="/">Home </a></li>
        <li class="breadcrumb-item">Absensi </li>
        <li class="breadcrumb-item">Informasi </li>
        <li class="breadcrumb-item active">Absensi Upah PSB </li>
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

                @include('Absensi.Informasi.UpahPSB.data')
                
            </div>
        </div>
    </div>
@endsection

@section('script')
    {{-- DevExtreme --}}
    <script src="{!! asset('assets/sneatV1/assets/vendor/libs/DevExtreme/js/jszip.min.js') !!}"></script>
    <script src="{!! asset('assets/sneatV1/assets/vendor/libs/DevExtreme/js/dx.all.js') !!}"></script>

    <script>
        function SearchUpahPSB() {
            let tgl_awal = $("#tanggal_awal").val()
            let tgl_akhir = $('#tanggal_akhir').val()
            let jenis = $('#jenis').val()

            if (tgl_awal == "" || tgl_akhir == "") {
                return;
            }

            $.ajax({
                type:'GET',
                url:'/Absensi/Informasi/UpahPSB/Cari',
                data: {tgl_awal: tgl_awal, tgl_akhir: tgl_akhir, jenis:jenis},
                dataType: "json",
                beforeSend: function () {
                    $(".preloader").show();  
                },
                complete: function () {
                    $(".preloader").fadeOut(); 
                },
                success:function(data){
                    if (jenis == 1) {
                        // Get Upah
                        var dataGrid = $("#TableDevExtreme").dxDataGrid({
                            dataSource: data.tampil,
                            columnsAutoWidth: true,
                            width: '100%',
                            height: 500,
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
                                fileName: "Informasi_UpahPSB_Upah",
                                allowExportSelectedData: true
                            },        
                            columns: [
                                {
                                    dataField: "ID", dataType: "string", caption: "ID", width: '10%'
                                }, 		          
                                {
                                    dataField: "Employee", dataType: "string", caption: "Karyawan", width: '20%'
                                },                  
                                {
                                    dataField: "TransDate", dataType: "date", format: 'dd-MM-yyyy', caption: "Tanggal", width: '10%', cssClass: "cls"
                                },      
                                {
                                    dataField: "Total", dataType: "string", caption: "Total", width: '10%', cssClass: "cls"
                                },    
                                {
                                    dataField: "Food", dataType: "string", caption: "Uang Makan", cssClass: "cls", width: '10%'
                                },
                                {
                                    dataField: "Allowance", dataType: "string", caption: "Tunjangan", cssClass: "cls", width: '10%'
                                },   
                                {
                                    dataField: "Bonus", dataType: "string", caption: "Bonus", width: '10%', cssClass: "cls"
                                },  
                                {
                                    dataField: "GrandTotal", dataType: "string", caption: "Grand Total", cssClass: "cls", width: '10%',
                                }, 
                                {
                                    dataField: "TotalStone", dataType: "string", caption: "Total Stone", cssClass: "cls", width: '10%',
                                }, 		          
                                {
                                    dataField: "TransDate",
                                    groupIndex: 0
                                }			      				          	          		           		          		                                                                                        
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
                    } else if (jenis == 2) {
                        // Get Model
                        var dataGrid = $("#TableDevExtreme").dxDataGrid({
                            dataSource: data.tampil,
                            columnsAutoWidth: true,
                            width: '100%',
                            height: 500,
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
                                fileName: "Informasi_UpahPSB_Model",
                                allowExportSelectedData: true
                            },        
                            columns: [
                                {
                                    dataField: "ID", dataType: "string", caption: "ID", width: '10%', cssClass: "cls"
                                },		          
                                {
                                    dataField: "Employee", dataType: "string", caption: "Karyawan", width: '15%', cssClass: "cls"
                                },                     
                                {
                                    dataField: "TransDate", dataType: "date", format: 'dd-MM-yyyy', caption: "Tanggal", width: '10%', cssClass: "cls"
                                },      
                                {
                                    dataField: "Allocation", dataType: "string", caption: "Allocation", width: '15%', cssClass: "cls"
                                },    
                                {
                                    dataField: "Product", dataType: "string", caption: "Produk", cssClass: "cls", width: '20%'
                                },
                                {
                                    dataField: "Tanam", dataType: "string", caption: "Tanam", cssClass: "cls", width: '10%',
                                }, 
                                {
                                    dataField: "Gigi", dataType: "string", caption: "Gigi", cssClass: "cls", width: '10%',
                                }, 	
                                {
                                    dataField: "Total", dataType: "string", caption: "Total", cssClass: "cls", width: '10%',
                                }, 			          	          
                                {
                                    dataField: "TransDate",
                                    groupIndex: 0
                                } 		            	          		           		          		                                                                                        
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
                    } else {
                        // Invalid Jenis
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: "jenis Invalid",
                        })
                        return;
                    }
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
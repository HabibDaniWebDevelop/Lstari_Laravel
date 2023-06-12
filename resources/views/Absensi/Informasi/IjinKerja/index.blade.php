<?php $title = 'Data Ijin Kerja'; ?>
@extends('layouts.backend-Theme-3.app')
@section('title', '$title')

@section('Dashboard')
    <h2 class="m-0">{{ $title }}</h2>
    <ol class="breadcrumb sm-2">
        <li class="breadcrumb-item"><a href="/">Home </a></li>
        <li class="breadcrumb-item">Absensi </li>
        <li class="breadcrumb-item">Informasi </li>
        <li class="breadcrumb-item active">Ijin Kerja </li>
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

                @include('Absensi.Informasi.IjinKerja.data')
                
            </div>
        </div>
    </div>
@endsection

@section('script')
    {{-- DevExtreme --}}
    <script src="{!! asset('assets/sneatV1/assets/vendor/libs/DevExtreme/js/jszip.min.js') !!}"></script>
    <script src="{!! asset('assets/sneatV1/assets/vendor/libs/DevExtreme/js/dx.all.js') !!}"></script>

    <script>
        function SearchIjinKerja() {
            let tgl_awal = $("#tanggal_awal").val()
            let tgl_akhir = $('#tanggal_akhir').val()
            let jenis = $('#jenis').val()

            if (tgl_awal == "" || tgl_akhir == "") {
                return;
            }

            $.ajax({
                type:'GET',
                url:'/Absensi/Informasi/IjinKerja/Cari',
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
                                fileName: "Informasi_ijin",
                                allowExportSelectedData: true
                            },        
                            columns: [
                                {
                                    dataField: "ID", dataType: "string", caption: "ID", width: '4%', cssClass: "cls"
                                }, 
                                {
                                    dataField: "TransDate", dataType: "date", format: 'dd-MM-yyyy', caption: "Tanggal", width: '7%', cssClass: "cls"
                                },                      
                                {
                                    dataField: "TimeFrom", dataType: "string", caption: "From", width: '6%', cssClass: "cls"
                                },      
                                {
                                    dataField: "TimeTo", dataType: "string", caption: "To", width: '6%', cssClass: "cls"
                                },    
                                {
                                    dataField: "Employee", dataType: "string", caption: "Karyawan", cssClass: "cls", width: '16%'
                                },
                                {
                                    dataField: "Active", dataType: "string", caption: "Status", cssClass: "cls", width: '5%'
                                },   
                                {
                                    dataField: "Department", dataType: "string", caption: "Divisi", width: '10%', cssClass: "cls"
                                },  
                                {
                                    dataField: "Type", dataType: "string", caption: "Jenis", cssClass: "cls", width: '7%',
                                },  
                                {
                                    dataField: "Reason", dataType: "string", caption: "Alasan", cssClass: "cls", width: '15%',
                                },  
                                {
                                    dataField: "Absent", dataType: "string", caption: "Bobot", cssClass: "cls", width: '7%',
                                }, 
                                {
                                    dataField: "WorkRole", dataType: "string", caption: "Bagian", cssClass: "cls", width: '7%',
                                }, 
                                {
                                    dataField: "Notification", dataType: "string", caption: "Notif", cssClass: "cls", width: '5%',
                                }, 
                                {
                                    dataField: "InformBefore", dataType: "string", caption: "Confirm", cssClass: "cls", width: '5%',
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
                                fileName: "Informasi_ijin",
                                allowExportSelectedData: true
                            },        
                            columns: [
                                {
                                    dataField: "Department", dataType: "string", caption: "Divisi", width: '10%'
                                }, 
                                {
                                    dataField: "Employee", dataType: "string", caption: "Karyawan", width: '20%', cssClass: "cls"
                                },                      
                                {
                                    dataField: "WorkRole", dataType: "string", caption: "Status", width: '10%', cssClass: "cls"
                                },      
                                {
                                    dataField: "Period", dataType: "string", caption: "Periode", width: '10%', cssClass: "cls"
                                },    
                                {
                                    dataField: "StartWork", dataType: "date", format: 'dd-MM-yyyy', caption: "Start Work", cssClass: "cls", width: '10%'
                                },
                                {
                                    dataField: "Sakit", dataType: "string", caption: "Sakit", cssClass: "cls", width: '7%'
                                },   
                                {
                                    dataField: "Ijin", dataType: "string", caption: "Ijin", width: '7%', cssClass: "cls"
                                },  
                                {
                                    dataField: "Terlambat", dataType: "string", caption: "Terlambat", cssClass: "cls", width: '7%',
                                },   
                                {
                                    dataField: "Absen", dataType: "string", caption: "Absen", cssClass: "cls", width: '7%',
                                },  
                                {
                                    dataField: "Cuti", dataType: "string", caption: "Cuti", cssClass: "cls", width: '7%',
                                },  
                                {
                                    dataField: "Alpha", dataType: "string", caption: "Alpha", cssClass: "cls", width: '7%',
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
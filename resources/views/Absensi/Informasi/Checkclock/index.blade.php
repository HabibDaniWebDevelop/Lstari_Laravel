<?php $title = 'Data Check Clock'; ?>
@extends('layouts.backend-Theme-3.app')
@section('title', '$title')

@section('Dashboard')
    <h2 class="m-0">{{ $title }}</h2>
    <ol class="breadcrumb sm-2">
        <li class="breadcrumb-item"><a href="/">Home </a></li>
        <li class="breadcrumb-item">Absensi </li>
        <li class="breadcrumb-item">Informasi </li>
        <li class="breadcrumb-item active">Check Clock </li>
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

                @include('Absensi.Informasi.Checkclock.data')
                
            </div>
        </div>
    </div>
@endsection

@section('script')
    {{-- DevExtreme --}}
    <script src="{!! asset('assets/sneatV1/assets/vendor/libs/DevExtreme/js/jszip.min.js') !!}"></script>
    <script src="{!! asset('assets/sneatV1/assets/vendor/libs/DevExtreme/js/dx.all.js') !!}"></script>

    <script>
        function SearchCheckClock() {
            let tgl_awal = $("#tanggal_awal").val()
            let tgl_akhir = $('#tanggal_akhir').val()
            let jenis = $('#jenis').val()

            if (tgl_awal == "" || tgl_akhir == "") {
                return;
            }

            $.ajax({
                type:'GET',
                url:'/Absensi/Informasi/Checkclock/Cari',
                data: {tgl_awal: tgl_awal, tgl_akhir: tgl_akhir, jenis:jenis},
                dataType: "json",
                beforeSend: function () {
                    $(".preloader").show();  
                },
                complete: function () {
                    $(".preloader").fadeOut(); 
                },
                success:function(data){
                    if (jenis == 3) {
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
                                fileName: "Informasi_Checkclock",
                                allowExportSelectedData: true
                            },        
                            columns: [
                                {
                                    dataField: "ID", dataType: "number", caption: "ID"
                                },
                                {
                                    dataField: "Employee", dataType: "string", caption: "Karyawan"
                                }, 
                                {
                                    dataField: "StartWork", dataType: "date", format: 'dd-MM-yyyy', caption: "Mulai Kerja"
                                },      
                                {
                                    dataField: "Department", dataType: "string", caption: "Divisi"
                                },                      
                                {
                                    dataField: "WorkRole", dataType: "string", caption: "Status Kerja"
                                },    
                                {
                                    dataField: "Rank", dataType: "string", caption: "Rank"
                                },
                                {
                                    dataField: "Total", dataType: "string", caption: "Total"
                                }		          	          		           		          		                                                                                        
                            ],
                            onRowClick: function(e) {
                                if (e.rowType == 'group') {  
                                    if (e.isExpanded)  
                                        e.component.collapseRow(e.key);  
                                    else  
                                        e.component.expandRow(e.key);  
                                } else if (e.rowType == 'data'){
                                    let startDate = $("#tanggal_awal").val()
                                    let endDate = $('#tanggal_akhir').val()
                                    let idEmployee = e.data.ID
                                    let data = {
                                        startDate:startDate,
                                        endDate:endDate,
                                        idEmployee:idEmployee
                                    }
                                    $.ajax({
                                        type:'GET',
                                        url:'/Absensi/Informasi/Checkclock/Detail',
                                        data: data,
                                        dataType: "json",
                                        beforeSend: function () {
                                            $(".preloader").show();  
                                        },
                                        complete: function () {
                                            $(".preloader").fadeOut(); 
                                        },
                                        success:function(data){
                                            // Change Modal properties
                                            $('#modalformat').attr('class', 'modal-dialog modal-fullscreen');
                                            // Showing modal
                                            $("#modal1").html(data.detailAbsensiHTML);
                                            $('#modalinfo').modal('show');
                                            return
                                        }
                                    });
                                } else {
                                    return
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
                                fileName: "Informasi_Checkclock",
                                allowExportSelectedData: true
                            },        
                            columns: [
                                {
                                    dataField: "Employee", dataType: "string", caption: "Karyawan", width: '20%'
                                }, 
                                {
                                    dataField: "Department", dataType: "string", caption: "Divisi", width: '12%', cssClass: "cls"
                                },                      
                                {
                                    dataField: "TransDate", dataType: "date", format: 'dd-MM-yyyy', caption: "Tanggal", width: '15%', cssClass: "cls"
                                },      
                                {
                                    dataField: "TransTime", dataType: "string", caption: "Jam", width: '15%', cssClass: "cls"
                                },    
                                {
                                    dataField: "STATUS", dataType: "string", caption: "Status", cssClass: "cls", width: '10%'
                                },
                                {
                                    dataField: "Type", dataType: "string", caption: "Jenis", cssClass: "cls", width: '10%'
                                },   
                                {
                                    dataField: "Machine", dataType: "string", caption: "Machine", width: '10%', cssClass: "cls"
                                },  
                                {
                                    dataField: "ID", dataType: "number", caption: "ID", cssClass: "cls", width: '8%',
                                },   	          
                                {
                                    dataField: "TransDate",
                                    groupIndex: 0
                                }				          	          		           		          		                                                                                        
                            ],
                            onRowClick: function(e) {  
                                console.log(e.data);
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
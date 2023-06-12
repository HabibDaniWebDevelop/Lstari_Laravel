<?php $title = 'Data Jam Kerja'; ?>
@extends('layouts.backend-Theme-3.app')
@section('title', '$title')

@section('Dashboard')
    <h2 class="m-0">{{ $title }}</h2>
    <ol class="breadcrumb sm-2">
        <li class="breadcrumb-item"><a href="/">Home </a></li>
        <li class="breadcrumb-item">Absensi </li>
        <li class="breadcrumb-item">Informasi </li>
        <li class="breadcrumb-item active">Jam Kerja </li>
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

                @include('Absensi.Informasi.JamKerja.data')
                
            </div>
        </div>
    </div>
@endsection

@section('script')
    {{-- DevExtreme --}}
    <script src="{!! asset('assets/sneatV1/assets/vendor/libs/DevExtreme/js/jszip.min.js') !!}"></script>
    <script src="{!! asset('assets/sneatV1/assets/vendor/libs/DevExtreme/js/dx.all.js') !!}"></script>

    <script>
        function SearchJamKerja() {
            let tgl_awal = $("#tanggal_awal").val()
            let tgl_akhir = $('#tanggal_akhir').val()

            if (tgl_awal == "" || tgl_akhir == "") {
                return;
            }

            $.ajax({
                type:'GET',
                url:'/Absensi/Informasi/JamKerja/Cari',
                data: {tgl_awal: tgl_awal, tgl_akhir: tgl_akhir},
                dataType: "json",
                beforeSend: function () {
                    $(".preloader").show();  
                },
                complete: function () {
                    $(".preloader").fadeOut(); 
                },
                success:function(data){
                    var dataGrid = $("#TableDevExtreme").dxDataGrid({
                        dataSource: data.tampil,
                        columnsAutoWidth: true,
                        width: '100%',
                        height: 700,
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
                            fileName: "Informasi_JamKerja",
                            allowExportSelectedData: true
                        },        
                        columns: [
                        {
                            dataField: "TransDate", dataType: "date", format: 'dd-MM-yyyy', caption: "Tanggal", width: '8%', cssClass: "cls"
                        },                    
                        {
                            dataField: "EDescription", dataType: "string", caption: "Nama Karyawan", width: '10%', cssClass: "cls"
                        }, 
                        {
                            dataField: "DDescription", dataType: "string", caption: "Bagian", width: '10%', cssClass: "cls"
                        },                        
                        {
                            dataField: "DAY", dataType: "string", caption: "Hari", width: '8%', cssClass: "cls"
                        }, 
                        {
                            dataField: "WorkIn", dataType: "string", caption: "Masuk", width: '8%', cssClass: "cls"
                        }, 
                        {
                            dataField: "WorkOut", dataType: "string", caption: "Pulang", width: '8%', cssClass: "cls"
                        }, 	
                        {
                            dataField: "Late", dataType: "string", caption: "Late", width: '7%', cssClass: "cls"
                        }, 
                        {
                            dataField: "Absent", dataType: "string", caption: "Absen", width: '7%', cssClass: "cls"
                        }, 	

                        {
                            dataField: "WorkTime", dataType: "string", caption: "Kerja", width: '7%', cssClass: "cls"
                        }, 
                        {
                            dataField: "AbsentPaid", dataType: "string", caption: "Ijin", width: '7%', cssClass: "cls"
                        }, 

                        {
                            dataField: "FoodAdditional", dataType: "string", caption: "Food", width: '7%', cssClass: "cls"
                        }, 	          		          
                        {
                            dataField: "OverTime", dataType: "string", caption: "Lembur", cssClass: "cls", width: '5%'
                        },  
                        {
                            dataField: "Note", dataType: "string", caption: "Note", cssClass: "cls", width: '8%'
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
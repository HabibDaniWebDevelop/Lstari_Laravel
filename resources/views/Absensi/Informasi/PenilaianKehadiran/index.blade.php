<?php $title = 'Data Penilaian Kehadiran'; ?>
@extends('layouts.backend-Theme-3.app')
@section('title', '$title')

@section('Dashboard')
    <h2 class="m-0">{{ $title }}</h2>
    <ol class="breadcrumb sm-2">
        <li class="breadcrumb-item"><a href="/">Home </a></li>
        <li class="breadcrumb-item">Absensi </li>
        <li class="breadcrumb-item">Informasi </li>
        <li class="breadcrumb-item active">Penilaian Kehadiran </li>
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

                @include('Absensi.Informasi.PenilaianKehadiran.data')
                
            </div>
        </div>
    </div>
@endsection

@section('script')
    {{-- DevExtreme --}}
    <script src="{!! asset('assets/sneatV1/assets/vendor/libs/DevExtreme/js/jszip.min.js') !!}"></script>
    <script src="{!! asset('assets/sneatV1/assets/vendor/libs/DevExtreme/js/dx.all.js') !!}"></script>

    <script>
        function SearchPenilaianKehadiran() {
            let tgl_awal = $("#tanggal_awal").val()
            let tgl_akhir = $('#tanggal_akhir').val()

            if (tgl_awal == "" || tgl_akhir == "") {
                return;
            }

            $.ajax({
                type:'GET',
                url:'/Absensi/Informasi/PenilaianKehadiran/Cari',
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
                            fileName: "Informasi_PenilaianKehadiran",
                            allowExportSelectedData: true
                        },        
                        columns: [             
                        {
                            dataField: "Employee", dataType: "string", caption: "Nama Karyawan", cssClass: "cls"
                        }, 
                        {
                            dataField: "Department", dataType: "string", caption: "Bagian", cssClass: "cls"
                        },                      
                        {
                            dataField: "HigherRank", dataType: "string", caption: "Divisi", cssClass: "cls"
                        },      
                        {
                            dataField: "StartWork", dataType: "string", caption: "Start Kerja", cssClass: "cls"
                        },    
                        {
                            dataField: "A1", dataType: "number", caption: "Sakit", cssClass: "cls"
                        },
                        {
                            dataField: "A2", dataType: "number", caption: "Ijin", cssClass: "cls"
                        },  
                        {
                            dataField: "cutikurang", dataType: "number", caption: "Ijin < 60", cssClass: "cls"
                        },  	          
                        {
                            dataField: "A3", dataType: "number", caption: "Terlambat", cssClass: "cls"
                        },
                        {
                            dataField: "A4", dataType: "number", caption: "Absen", cssClass: "cls"
                        },
                        {
                            dataField: "absenijin", dataType: "number", caption: "Absen Ijin", cssClass: "cls"
                        },	
                        {
                            dataField: "absensakit", dataType: "number", caption: "Absen Sakit", cssClass: "cls"
                        },		                    
                        {
                            dataField: "cutitahunan", dataType: "number", caption: "Cuti Tahunan", cssClass: "cls"
                        },
                        {
                            dataField: "cutikhusus", dataType: "number", caption: "Cuti Khusus", cssClass: "cls"
                        },	          
                        {
                            dataField: "covid", dataType: "number", caption: "Covid", cssClass: "cls"
                        },
                        {
                            dataField: "terlambat_lebih_jam_9", dataType: "number", caption: "Terlambat > Jam 9", cssClass: "cls"
                        },	          

                            {
                                dataField: "Employee",
                                caption: "Nama",
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
                error:function(xhr, thrownError, ajaxOptions){

                }
            });   
        }
    </script>
@endsection
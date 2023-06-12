<?php $title = 'Data Tambahan Uang Makan'; ?>
@extends('layouts.backend-Theme-3.app')
@section('title', '$title')

@section('Dashboard')
    <h2 class="m-0">{{ $title }}</h2>
    <ol class="breadcrumb sm-2">
        <li class="breadcrumb-item"><a href="/">Home </a></li>
        <li class="breadcrumb-item">Absensi </li>
        <li class="breadcrumb-item">Informasi </li>
        <li class="breadcrumb-item active">Tambahan Uang Makan </li>
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

                @include('Absensi.Informasi.TambahanUangMakan.data')
                
            </div>
        </div>
    </div>
@endsection

@section('script')
    {{-- DevExtreme --}}
    <script src="{!! asset('assets/sneatV1/assets/vendor/libs/DevExtreme/js/jszip.min.js') !!}"></script>
    <script src="{!! asset('assets/sneatV1/assets/vendor/libs/DevExtreme/js/dx.all.js') !!}"></script>

    <script>
        function SearchTambahanUangMakan() {
            let tgl_awal = $("#tanggal_awal").val()
            let tgl_akhir = $('#tanggal_akhir').val()
            let jenis = $('#jenis').val()

            if (tgl_awal == "" || tgl_akhir == "") {
                return;
            }

            $.ajax({
                type:'GET',
                url:'/Absensi/Informasi/TambahanUangMakan/Cari',
                data: {tgl_awal: tgl_awal, tgl_akhir: tgl_akhir, jenis:jenis},
                dataType: "json",
                beforeSend: function () {
                    $(".preloader").show();  
                },
                complete: function () {
                    $(".preloader").fadeOut(); 
                },
                success:function(data){
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
                                dataField: "TransDate", dataType: "date", format: 'dd-MM-yyyy', caption: "Tanggal", width: '20%', cssClass: "cls"
                            },      
                            {
                                dataField: "Description", dataType: "string", caption: "Nama Karyawan", width: '60%', cssClass: "cls"
                            },    
                            {
                                dataField: "ordinal", dataType: "string", caption: "Urutan", cssClass: "cls", width: '10%'
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
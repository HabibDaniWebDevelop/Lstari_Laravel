<?php $title = 'CycleTime'; ?>
@extends('layouts.backend-Theme-3.app')
@section('title', '$title')

@section('Dashboard')
    <h2 class="m-0">{{ $title }}</h2>
    <ol class="breadcrumb sm-2">
        <li class="breadcrumb-item"><a href="/">Home </a></li>
        <li class="breadcrumb-item">Master </li>
        <li class="breadcrumb-item">Produksi </li>
        <li class="breadcrumb-item active">CycleTime </li>
    </ol>
@endsection

@section('css')
    <style>
        .dx-datagrid-headers {
            color: #ffffff !important;
            background-color: #9e2a24 !important;
            text-align: center!important;  
        }
        .dx-datagrid{  
            font-style: verdana; 
            font-size: 10px; 
        }  
        .dx-datagrid-action.dx-cell-focus-disabled {
            text-align: center !important;
            vertical-align: middle !important;
        }   
        .dx-data-row td.cls {  
            text-align: center!important;  
        }   
        .dx-data-row td.lss {  
            text-align: right!important;  
        } 
        
        tfoot input {
            width: 100%;
            padding: 3px;
            box-sizing: border-box;
        }

    </style>
    {{-- DevExtreme --}}
    <link rel="stylesheet" href="{!! asset('assets/sneatV1/assets/vendor/libs/DevExtreme/css/dx.common.css') !!}">
    <link rel="stylesheet" href="{!! asset('assets/sneatV1/assets/vendor/libs/DevExtreme/css/dx.material.orange.light.compact.css') !!}">
@endsection

@section('container')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                @include('Master.Produksi.CycleTime.data')
            </div>
        </div>
    </div>
@endsection

@section('script')
    @include('layouts.backend-Theme-3.DataTabelButton')
    {{-- DevExtreme --}}
    <script src="{!! asset('assets/sneatV1/assets/vendor/libs/DevExtreme/js/jszip.min.js') !!}"></script>
    <script src="{!! asset('assets/sneatV1/assets/vendor/libs/DevExtreme/js/dx.all.js') !!}"></script>

    <script>

        function openModal(){
            $(".preloader").fadeIn(300);
        }

        function closeModal(){
            $(".preloader").fadeOut(300);
        }


        function lihat() { //OK
            var tglstart = $("#tglstart").val();
            var tglend = $("#tglend").val();
            var operation = $("#operation").val();

            if (operation == "") {
                Swal.fire({
                    icon: "error",
                    title: "Harap Isi Operation",
                    showCancelButton: false,
                    showConfirmButton: true
                });
            }else if(tglstart == "" || tglend == ""){
                Swal.fire({
                    icon: "error",
                    title: "Harap Isi Tanggal",
                    showCancelButton: false,
                    showConfirmButton: true
                });
            }else{

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                data = {tglstart: tglstart, tglend: tglend, operation: operation};
                $.ajax({
                    type:'POST',
                    url:'/Master/Produksi/CycleTime/lihat',
                    data: data,
                    dataType: "json",
                    beforeSend: function () {
                        $(".preloader").show();  
                    },
                    complete: function () {
                        $(".preloader").fadeOut(); 
                    },
                    success:function(data){

                        var dataGrid = $("#tampil").dxDataGrid({
                            dataSource: data.tampil,
                            keyExpr: "ID",
                            columnAutoWidth: true,
                            width: '100%',
                            height: 550,
                            allowColumnReordering: true,
                            showBorders: true,
                            headerFilter: {
                                visible: true
                            },
                            rowAlternationEnabled: true,
                            allowColumnResizing: true,     
                            selection: {
                                mode: "multiple", // "single" or "multiple" or "none"
                                allowSelectAll: true,
                                selectAllMode: 'page' 
                            },
                            searchPanel: {
                                visible: true
                            },
                            paging: {
                                enabled: false
                            },
                            grouping: {
                                autoExpandAll: false
                            },
                            groupPanel: {
                                visible: true
                            },
                            "export": {
                                enabled: true,
                                fileName: "RPH Produksi",
                                allowExportSelectedData: true
                            },
                            columns: [
                                {
                                    dataField: "KATEGORI", dataType: "string", caption: "Kategori", cssClass: "cls",
                                },
                                {
                                    dataField: "SUBKATEGORI", dataType: "string", caption: "SubKategori", cssClass: "cls",
                                },                       
                                {
                                    dataField: "SumSPKO", dataType: "number", caption: "Total SPKO (Buah)", cssClass: "cls",
                                }, 
                                {
                                    dataField: "SumAvg", dataType: "number", caption: "Total Avg (Detik)", cssClass: "cls", format: '#,##0.00',
                                },
                                {
                                    dataField: "MasterCycleTimeRound", dataType: "number", caption: "Master Cycle Time (Total Avg / Total SPKO)", cssClass: "cls",
                                }, 		
                            ],
                            onSelectionChanged: function(selectedItems) {
                                var data = selectedItems.selectedRowsData;
                                if (data.length > 0)
                                    $("#pilih").val(
                                        $.map(data, function(value) {
                                            return value.kirimkan;
                                        }).join(","));
                                selectedItems.component.refresh(true);
                            },
                        }).dxDataGrid("instance");
                        $("html, body").animate({
                            scrollTop: $(
                                'html, body').get(0).scrollHeight
                        }, 2000);
                    },
                    error:function(xhr){
                        // Invalid
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: "Invalid Request",
                        })
                        return;
                    }
                });   

            }
        }

        function simpanCek(){
            var pilih = $("#pilih").val();

            if(pilih == ""){
                Swal.fire({
                    icon: "error",
                    title: "Data Master CycleTime Belum Dipilih",
                    showCancelButton: false,
                    showConfirmButton: true
                });
            }else{
                Swal.fire({
                    title: 'Yakin mau Simpan Data MasterCycleTime ?',
                    icon: 'info',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // alert('OK');
                        simpan();
                    }
                });
            }
        }


        function simpan() {

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                url: '/Master/Produksi/CycleTime/simpan',
                beforeSend: function() {
                    openModal();
                },
                data: $("#datasimpan,#datasimpan2").serialize(),
                dataType: "json",
                type: 'POST',
                success: function(data) {
                    if(data.status == 'success'){
                        Swal.fire({
                            icon: 'success',
                            title: 'Tersimpan',
                            text: "Data Berhasil Disimpan",
                            timer: 2000,
                            showCancelButton: false,
                            showConfirmButton: true
                        });
                    }
                },
                complete: function() {
                    closeModal();
                },
                error: function(xhr, thrownError, ajaxOptions) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: "Invalid Request",
                    })
                    return; 
                }
            });
        }

        function lihatDataMaster(){
            $.get('/Master/Produksi/CycleTime/lihatDataMaster/', function(data) {
                $("#contentBody").html(data.html);
                $('#myModal').modal('show');
                
                // Setup - add a text input to each cell
                $('#tampiltabel tfoot th').each(function () {
                    var title = $(this).text();
                    $(this).html('<input type="text" placeholder="Search" style="text-align:center" />');
                });

                var table = $('#tampiltabel').DataTable({
                    ordering: true,
                    paging: true,
                    pageLength: 10,
                    searching: true,
                    lengthChange: true,
                    scrollCollapse: true,
                    search: {regex: true},
                    initComplete: function () {
                        // Apply the search
                        this.api()
                            .columns()
                            .every(function () {
                                var that = this;
            
                                $('input', this.footer()).on('keyup change clear', function () {
                                    if (that.search() !== this.value) {
                                        that.search(this.value).draw();
                                    }
                                });
                            });
                    },

                });

                // Handle change event
                $('#ctrl-show-active').on('change', function(){
                    var val = $(this).val();
              
                    // If all records should be displayed
                    if(val === 'all'){
                        table.columns(9).search('Yes|No', true, false).draw();
                    }
            
                    // If selected records should be displayed
                    if(val === 'active'){
                        table.columns(9).search('Yes').draw();
                    }

                    // If selected records should not be displayed
                    if(val === 'not-active'){
                        table.columns(9).search('No').draw();
                    }
                });

            });
        }

        

    </script>
@endsection
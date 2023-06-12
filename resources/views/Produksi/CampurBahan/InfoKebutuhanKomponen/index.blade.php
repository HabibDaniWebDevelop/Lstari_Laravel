<?php $title = 'Informasi Kebutuhan Komponen'; ?>
<?php $tab='1'; ?>
@extends('layouts.backend-Theme-3.app')
@section('title', '$title')

@section('Dashboard')
    <h2 class="m-0">{{ $title }}</h2>
    <ol class="breadcrumb sm-2">
        <li class="breadcrumb-item"><a href="/">Home </a></li>
        <li class="breadcrumb-item">Produksi </li>
        <li class="breadcrumb-item">Campur Bahan </li>
        <li class="breadcrumb-item active">Informasi Kebutuhan Komponen</li>
    </ol>
@endsection

@section('css')
   <style>
        #dailystock{
            margin-top:0px;
        }

        .breadcrumb{
           margin-bottom: 0px;
        }

        .container-fluid flex-grow-1 container-p-y {
            margin-top:0px;
        }

        .card-body{
            padding:10px 20px 10px 20px;
        }

        .m-0{
            font-size: 28px;
        }


        #btn-tampilkan{
            margin-top:0px;
        }

        .btn-outline-dark{
            display:inline-block;
            margin-top:28px;
        }

        .btn-primary{
            display:inline-block;
            margin-top:28px;
        }

        .btn-outline-dark{
            /* position: absolute;  */
            margin-top:-50px;
            margin-left:1040px;
            margin-bottom:0px;
          
            /* float: right; */

            /* margin-top:-20px;
            margin-bottom:20px; */
            /* overflow: hidden; */
            }
        /* #tampil{
            margin-top:5px;
        } */

        .col-1{
            padding:5px;
        }
        
        .col-2{
            padding:5px;
        }

        .col-3{
            padding:5px;
        }

        .table-sm > :not(caption) > * > * {
            padding: 0px !important;
        }

        .mb-4 {
            margin-bottom: 5px !important;
        }

        .demo-inline-spacing > * {
            margin: 5px !important;
        }

        .layout-horizontal .bg-menu-theme .menu-inner > .menu-item {
            margin: 0rem 0;
        }

        .container-p-y:not([class^="pt-"]):not([class*=" pt-"]) {
            padding-top: 0.6rem !important;
        }

        .m-0{
            font-size: 24px;
        }

   

    </style>
     {{-- DevExtreme --}}
     <link rel="stylesheet" href="{!! asset('assets/sneatV1/assets/vendor/libs/DevExtreme/css/dx.common.css') !!}">
     <link rel="stylesheet" href="{!! asset('assets/sneatV1/assets/vendor/libs/DevExtreme/css/dx.material.orange.light.compact.css') !!}">
@endsection

@section('container')
<div class="row mb-4">
    <div class="col-md-12">
      <ul class="nav nav-pills flex-column flex-md-row mb-3" >
            <li class="nav-item">
            <a class="nav-link btn-tab1 {{ ($tab === "1") ? 'active':' ' }}" id="idtab1" data-bs-toggle="tab" href="javascript:void(0);" style="display:none;"><i class="fas fa-chart-bar"></i> Info Form Order Produksi</a>
            </li>
            {{-- <li class="nav-item">
            <a class="nav-link btn-tab2 {{ ($tab === "2") ? 'active':' ' }}" id="idtab2" data-bs-toggle="tab" href="#" onclick="show2()"><i class="fas fa-chart-line"></i> Stock Opname</a>
            </li> --}}
      </ul> 
        <div class="row" id="routingproduksi">
        </div>
        {{-- <div class="row" id="stockopname" style="display:none;">
        </div> --}}
    </div>
</div>

@endsection

@section('script')
<script src="{!! asset('assets/sneatV1/assets/vendor/libs/datatables/dataTables.buttons.min.js') !!}"></script>
<script src="{!! asset('assets/sneatV1/assets/vendor/libs/datatables/jszip.min.js') !!}"></script>
<script src="{!! asset('assets/sneatV1/assets/vendor/libs/datatables/pdfmake.min.js') !!}"></script>
<script src="{!! asset('assets/sneatV1/assets/vendor/libs/datatables/vfs_fonts.js') !!}"></script>
<script src="{!! asset('assets/sneatV1/assets/vendor/libs/datatables/buttons.html5.min.js') !!}"></script>
<script src="{!! asset('assets/sneatV1/assets/vendor/libs/datatables/buttons.print.min.js') !!}"></script>
<script src="{!! asset('assets/sneatV1/assets/vendor/libs/datatables/dataTables.fixedColumns.min.js') !!}"></script>

{{-- DevExtreme --}}
<script src="{!! asset('assets/sneatV1/assets/vendor/libs/DevExtreme/js/jszip.min.js') !!}"></script>
<script src="{!! asset('assets/sneatV1/assets/vendor/libs/DevExtreme/js/dx.all.js') !!}"></script>



<script>
        $(document).ready(function(){
            var token = $("meta[name='csrf-token']").attr("content");

            $('.nav-link').click(function(){
            var tab = $(this).attr('id');

            if(tab == "idtab1"){show1();}
            else if(tab == "idtab2"){show2();}
            });

            show1();
            });

        function show1(){
            $.ajax({
                    url: '/Produksi/CampurBahan/InfoKebutuhanKomponen/getFilter',            
                    dataType : 'json',
                    type : 'GET',
                    success: function(data)
                    {
                        //console.log(data.html);
                        $("#routingproduksi").html(data.html);
                        document.getElementById("routingproduksi").style.display = "block";
                        // getSPKProduksi();
                    },
                });
        }

        function openModal(){
            $(".preloader").fadeIn(300);
        }

        function closeModal(){
            $(".preloader").fadeOut(300);
        }

        function setjenis(){
            var jenis = $("#jenis").val();
            if(jenis == 1 || jenis == 3){
                document.getElementById("bulannya").disabled = true;
                document.getElementById("tanggal1").disabled = true;
                document.getElementById("tanggal2").disabled = true;
                document.getElementById("tahunnya").disabled = false;
                document.getElementById("non").disabled = false;
            }else{
                document.getElementById("bulannya").disabled = false;
                document.getElementById("tanggal1").disabled = false;
                document.getElementById("tanggal2").disabled = false;
                document.getElementById("tahunnya").disabled = false;
                document.getElementById("non").disabled = true;
            }
        }

        function getSPKProduksi() {
            var tahun = $("#tahunnya").val();
            var bln = $("#bulannya").val();
            var kadar = $("#kadar").val();
            var tgl1 = $("#tanggal1").val();
            var tgl2 = $("#tanggal2").val();
            var jenis = $("#jenis").val();
            var tipe = $("#non").val();



            var data = {bln:bln, tahun:tahun, kadar:kadar, tgl1:tgl1, tgl2:tgl2, jenis:jenis, tipe:tipe};
            $.ajaxSetup({
                headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                type:'GET',
                url: '/Produksi/CampurBahan/InfoKebutuhanKomponen/getSPKProduksi', 
                beforeSend: function () {
                    $(".preloader").show();  
                },
                data: data,
                dataType: "json",
                success:function(data){
                    $("#tampil").html(data.html);
                    document.getElementById("datas").style.display = "block";
                    if(jenis == 1 || jenis == 3){
                        var dataGrid = $("#TableDevExtreme1").dxDataGrid({
                        dataSource: data.tampil,
                        showBorders: true,
                        columnsAutoWidth: true,
                        // width: '100%',
                        height: 440,
                        allowColumnReordering: true,
                        allowColumnResizing: true,
                        columnResizingMode: 'nextColumn',
                        showBorders: true,
                        headerFilter: { visible: true },
                        rowAlternationEnabled : true,
                        columnAutoWidth: true,
                        columnMinWidth: 50,
                        showBorders: true,
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
                            fileName: "Informasi Kebutuhan Komponen",
                            allowExportSelectedData: true
                        },
                        focusedRowEnabled: false,  
                        // selection: {
                        //     mode: "multiple",
                        //     allowSelectAll: true,
                        //     //selectAllMode: 'page' // or "multiple" | "none"
                        // },        
                        columns: [
                        {
                            dataField: "ACC", dataType: "string", caption: "Komponen",  cssClass: "cls"
                        },                    
                        {
                            dataField: "DesPro", dataType: "string", caption: "Deskripsi", cssClass: "cls"
                        },                    
                        {
                            dataField: "Kadar", dataType: "string", caption: "Kadar", cssClass: "cls"
                        },
                        {
                            dataField: "QtyKomponen", dataType: "number", caption: "Qty Order",  cssClass: "cls"
                        },
                        {
                            dataField: "QtyStock", dataType: "number", caption: "Qty Stok",  cssClass: "cls"
                        }, 
                        {
                            dataField: "WeightStock", dataType: "string", caption: "Weight Stok",  cssClass: "cls"
                        }, 
                        {
                            dataField: "Status", dataType: "string", caption: "Status Stok",  cssClass: "cls"
                        }
                        ],
                        summary: {
                                    groupItems: [{
                                        column: "QtyKomponen",
                                        summaryType: "sum",
                                        displayFormat: "Total Qty : {0}",
                                        alignByColumn: true
                                        // showInGroupFooter: true
                                    },
                                    // {
                                    //     column: "QtyStock",
                                      
                                    //     displayFormat: "Qty Stok : {0}",
                                    //     alignByColumn: true
                                    //     // showInGroupFooter: true
                                    // },
                                    // {
                                    //     column: "WeightStock",
                                      
                                    //     displayFormat: "Weight Stok : {0}",
                                    //     alignByColumn: true
                                    //     // showInGroupFooter: true
                                    // }
                                    // ,{
                                    //     column: "Weight",
                                    //     summaryType: "sum",
                                    //     dataType: "string",
                                    //     alignByColumn: true,
                                    //     format: {
                                    //                 type: "fixedPoint",
                                    //                 precision: 2
                                    //             }     // or "left" | "right"
                                        
                                    //     // showInGroupFooter: true
                                    // },{
                                    //     column: "WeightProduct",
                                    //     summaryType: "sum",
                                    //     dataType: "string",
                                    //     alignByColumn: true,
                                    //     format: {
                                    //                 type: "fixedPoint",
                                    //                 precision: 2
                                    //             }     // or "left" | "right"
                                       
                                    //     // showInGroupFooter: true
                                    // },{
                                    //     column: "TotalStone",
                                    //     summaryType: "sum",
                                    //     alignByColumn: true
                                    //     // showInGroupFooter: true
                                    // },{
                                    //     column: "TotalInject",
                                    //     summaryType: "sum",
                                    //     alignByColumn: true
                                    //     // showInGroupFooter: true
                                    // },{
                                    //     column: "TotalPoles",
                                    //     summaryType: "sum",
                                    //     alignByColumn: true
                                    //     // showInGroupFooter: true
                                    // },{
                                    //     column: "TotalPatri",
                                    //     summaryType: "sum",
                                    //     alignByColumn: true
                                    //     // showInGroupFooter: true
                                    // },{
                                    //     column: "TotalPUK",
                                    //     summaryType: "sum",
                                    //     alignByColumn: true
                                    //     // showInGroupFooter: true
                                    // },{
                                    //     column: "WorkOrder",
                                    //     summaryType: "count",
                                    //     alignByColumn: true
                                    //     // showInGroupFooter: true
                                    // }
                                 
                                    ],
                        // totalItems: [{
                        //                 column: "PSW",
                        //                 summaryType: "count",
                        //                 showInColumn: "Kode",
                        //                 alignment: "left"     // or "left" | "right"
                        //             },{
                        //                 column: "Qty",
                        //                 summaryType: "sum",
                        //                 showInColumn: "Jumlah",
                        //                 alignment: "left"     // or "left" | "right"
                        //             }, {
                        //                 column: "QtyEnm",
                        //                 summaryType: "sum",
                        //                 showInColumn: "Qty Enamel",
                        //                 alignment: "left"     // or "left" | "right"
                        //             }, {
                        //                 column: "Weight",
                        //                 summaryType: "sum",
                        //                 showInColumn: "Berat",
                        //                 dataType: "string",
                        //                 alignment: "left",
                        //                 format: {
                        //                             type: "fixedPoint",
                        //                             precision: 2
                        //                         }     // or "left" | "right"
                        //             }, {
                        //                 column: "WeightProduct",
                        //                 summaryType: "sum",
                        //                 showInColumn: "Brt Pcs",
                        //                 dataType: "string",
                        //                 alignment: "left",
                        //                 format: {
                        //                             type: "fixedPoint",
                        //                             precision: 2
                        //                         }     // or "left" | "right"
                        //             }, {
                        //                 column: "TotalStone",
                        //                 summaryType: "sum",
                        //                 showInColumn: "Batu",
                        //                 alignment: "left"     // or "left" | "right"
                        //             }, {
                        //                 column: "TotalInject",
                        //                 summaryType: "sum",
                        //                 showInColumn: "Inject",
                        //                 alignment: "left"     // or "left" | "right"
                        //             }, {
                        //                 column: "TotalPoles",
                        //                 summaryType: "count",
                        //                 showInColumn: "Poles",
                        //                 alignment: "left"     // or "left" | "right"
                        //             }, {
                        //                 column: "TotalPatri",
                        //                 summaryType: "sum",
                        //                 showInColumn: "Patri",
                        //                 alignment: "left"     // or "left" | "right"
                        //             }, {
                        //                 column: "TotalPUK",
                        //                 summaryType: "sum",
                        //                 showInColumn: "PUK",
                        //                 alignment: "left"     // or "left" | "right"
                        //             }, {
                        //                 column: "WorkOrder",
                        //                 summaryType: "count",
                        //                 showInColumn: "No SPK",
                        //                 alignment: "left"     // or "left" | "right"
                        //             }
                             
                        //             ]
                                },
                        onRowClick: function(e) {  
                            if (e.rowType == 'group') {  
                                if (e.isExpanded)  
                                    e.component.collapseRow(e.key);  
                                else  
                                    e.component.expandRow(e.key);  
                            }  

                             // Mengambil data pada baris yang diklik
                            // var rowData = e.data;
                            // $('#idworkorder').val(rowData.ID);
                            // Menampilkan data pada console
                           
                            var rowData = e.data;
                            //console.log(rowData.SW);
                            var id = rowData.SW;
                            var product = rowData.IDKOM;
                            var data = {id:id, product:product};
                            $.ajaxSetup({
                                headers: {
                                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                    }
                                });
                                $.ajax({
                                type:'GET',
                                url: '/Produksi/CampurBahan/InfoKebutuhanKomponen/getSPKRouting', 
                                beforeSend: function () {
                                    $(".preloader").show();  
                                },
                                data: data,
                                dataType: "json",
                                success:function(data){
                                    $("#tampil2").html(data.html);
                                    document.getElementById("tampil2").style.display = "block";
                                    var dataGrid = $("#TableDevExtreme2").dxDataGrid({
                                        dataSource: data.tampil2,
                                        showBorders: true,
                                        columnsAutoWidth: true,
                                        // width: '100%',
                                        height: 340,
                                        allowColumnReordering: true,
                                        allowColumnResizing: true,
                                        columnResizingMode: 'nextColumn',
                                        showBorders: true,
                                        headerFilter: { visible: false },
                                        rowAlternationEnabled : true,
                                        columnAutoWidth: true,
                                        columnMinWidth: 50,
                                        showBorders: true,
                                        showRowLines: true,
                                        wordWrapEnabled: true,
                                        sorting: {
                                        mode: 'multiple',
                                        },
                                        filterRow: {
                                            visible: false,
                                            applyFilter: "auto"
                                        },         
                                        selection: {
                                            mode: "none" // or "multiple" | "none"
                                        },  
                                        searchPanel: {
                                            visible: false
                                        },
                                        paging: { enabled: false },
                                        grouping: {  
                                            autoExpandAll: false  
                                        },  
                                        groupPanel: {
                                            visible: false
                                        },
                                        "export": {
                                            enabled: false,
                                            fileName: "Informasi Stok Komponen",
                                            allowExportSelectedData: false
                                        },  
                                        columns: [
                                        {
                                            dataField: "SW", dataType: "string",  caption: "SPK PPIC",  cssClass: "cls"
                                        }, 
                                        {
                                            dataField: "Ordinal", dataType: "number",  caption: "Urutan",  cssClass: "cls"
                                        },
                                        {
                                            dataField: "TransDate", dataType: "string",  caption: "Tanggal",  cssClass: "cls"
                                        },                    
                                        {
                                            dataField: "Kadar", dataType: "string", caption: "Kadar", cssClass: "cls"
                                        }, 
                                        {
                                            dataField: "FGX", dataType: "string", caption: "Finish Good",  cssClass: "cls"
                                        }, 
                                        {
                                            dataField: "QtyFG", dataType: "number", caption: "Qty FG",  cssClass: "cls"
                                        }, 
                                        {
                                            dataField: "QtyKom", dataType: "number", caption: "Qty/Produk",  cssClass: "cls"
                                        }, 
                                        {
                                            dataField: "Qty", dataType: "number", caption: "Qty Komponen",  cssClass: "cls"
                                        }                      				          	          		           		          		                                                                                        
                                        ],
                                        summary: {
                                                    groupItems: [
                                                    // {
                                                    //     column: "QtyFG",
                                                    //     summaryType: "sum",
                                                    //     alignByColumn: true
                                                    //     // showInGroupFooter: true
                                                    // }
                                                    // ,{
                                                    //     column: "Qty",
                                                    //     summaryType: "sum",
                                                    //     alignByColumn: true
                                                    //     // showInGroupFooter: true
                                                    // },{
                                                    //     column: "QtyEnm",
                                                    //     summaryType: "sum",
                                                    //     alignByColumn: true
                                                    //     // showInGroupFooter: true
                                                    // },{
                                                    //     column: "Weight",
                                                    //     summaryType: "sum",
                                                    //     dataType: "string",
                                                    //     alignByColumn: true,
                                                    //     format: {
                                                    //                 type: "fixedPoint",
                                                    //                 precision: 2
                                                    //             }     // or "left" | "right"
                                                        
                                                    //     // showInGroupFooter: true
                                                    // },{
                                                    //     column: "WeightProduct",
                                                    //     summaryType: "sum",
                                                    //     dataType: "string",
                                                    //     alignByColumn: true,
                                                    //     format: {
                                                    //                 type: "fixedPoint",
                                                    //                 precision: 2
                                                    //             }     // or "left" | "right"
                                                    
                                                    //     // showInGroupFooter: true
                                                    // },{
                                                    //     column: "TotalStone",
                                                    //     summaryType: "sum",
                                                    //     alignByColumn: true
                                                    //     // showInGroupFooter: true
                                                    // },{
                                                    //     column: "TotalInject",
                                                    //     summaryType: "sum",
                                                    //     alignByColumn: true
                                                    //     // showInGroupFooter: true
                                                    // },{
                                                    //     column: "TotalPoles",
                                                    //     summaryType: "sum",
                                                    //     alignByColumn: true
                                                    //     // showInGroupFooter: true
                                                    // },{
                                                    //     column: "TotalPatri",
                                                    //     summaryType: "sum",
                                                    //     alignByColumn: true
                                                    //     // showInGroupFooter: true
                                                    // },{
                                                    //     column: "TotalPUK",
                                                    //     summaryType: "sum",
                                                    //     alignByColumn: true
                                                    //     // showInGroupFooter: true
                                                    // },{
                                                    //     column: "WorkOrder",
                                                    //     summaryType: "count",
                                                    //     alignByColumn: true
                                                    //     // showInGroupFooter: true
                                                    // }
                                                
                                                    ],
                                        totalItems: [
                                                    {
                                                        column: "Qty",
                                                        summaryType: "sum",
                                                        showInColumn: "Qty",
                                                        displayFormat: "Total Qty Order : {0}",
                                                        alignment: "right"     // or "left" | "right"
                                                    }
                                                    // ,{
                                                    //     column: "Qty",
                                                    //     summaryType: "sum",
                                                    //     showInColumn: "Jumlah",
                                                    //     alignment: "left"     // or "left" | "right"
                                                    // }, {
                                                    //     column: "QtyEnm",
                                                    //     summaryType: "sum",
                                                    //     showInColumn: "Qty Enamel",
                                                    //     alignment: "left"     // or "left" | "right"
                                                    // }, {
                                                    //     column: "Weight",
                                                    //     summaryType: "sum",
                                                    //     showInColumn: "Berat",
                                                    //     dataType: "string",
                                                    //     alignment: "left",
                                                    //     format: {
                                                    //                 type: "fixedPoint",
                                                    //                 precision: 2
                                                    //             }     // or "left" | "right"
                                                    // }, {
                                                    //     column: "WeightProduct",
                                                    //     summaryType: "sum",
                                                    //     showInColumn: "Brt Pcs",
                                                    //     dataType: "string",
                                                    //     alignment: "left",
                                                    //     format: {
                                                    //                 type: "fixedPoint",
                                                    //                 precision: 2
                                                    //             }     // or "left" | "right"
                                                    // }, {
                                                    //     column: "TotalStone",
                                                    //     summaryType: "sum",
                                                    //     showInColumn: "Batu",
                                                    //     alignment: "left"     // or "left" | "right"
                                                    // }, {
                                                    //     column: "TotalInject",
                                                    //     summaryType: "sum",
                                                    //     showInColumn: "Inject",
                                                    //     alignment: "left"     // or "left" | "right"
                                                    // }, {
                                                    //     column: "TotalPoles",
                                                    //     summaryType: "count",
                                                    //     showInColumn: "Poles",
                                                    //     alignment: "left"     // or "left" | "right"
                                                    // }, {
                                                    //     column: "TotalPatri",
                                                    //     summaryType: "sum",
                                                    //     showInColumn: "Patri",
                                                    //     alignment: "left"     // or "left" | "right"
                                                    // }, {
                                                    //     column: "TotalPUK",
                                                    //     summaryType: "sum",
                                                    //     showInColumn: "PUK",
                                                    //     alignment: "left"     // or "left" | "right"
                                                    // }, {
                                                    //     column: "WorkOrder",
                                                    //     summaryType: "count",
                                                    //     showInColumn: "No SPK",
                                                    //     alignment: "left"     // or "left" | "right"
                                                    // }
                                            
                                                    ]
                                                },
                                        onRowClick: function(e) {  
                                            if (e.rowType == 'group') {  
                                                if (e.isExpanded)  
                                                    e.component.collapseRow(e.key);  
                                                else  
                                                    e.component.expandRow(e.key);  
                                            }  
                                        }, 
                                    }).dxDataGrid("instance");  
                                    const resizingModes = ['nextColumn', 'widget'];
                                    $('#select-resizing').dxSelectBox({
                                        items: resizingModes,
                                        value: resizingModes[0],
                                        width: 250,
                                        onValueChanged(data) {
                                        dataGrid.option('columnResizingMode', data.value);
                                        },
                                    });  		
                                },
                                complete: function () {
                                    $(".preloader").fadeOut(); 
                                },
                                    error:function(xhr){
                                        // Invalid Jenis
                                        // Swal.fire({
                                        //     icon: 'error',
                                        //     title: 'Oops...',
                                        //     text: "Invalid Request",
                                        // })
                                        return;
                                    }
                                }); 
                            }, 
                        }).dxDataGrid("instance");  
                    }

                    if(jenis == 2){
                        var dataGrid = $("#TableDevExtreme1").dxDataGrid({
                        dataSource: data.tampil,
                        showBorders: true,
                        columnsAutoWidth: true,
                        // width: '100%',
                        height: 640,
                        allowColumnReordering: true,
                        allowColumnResizing: true,
                        columnResizingMode: 'nextColumn',
                        showBorders: true,
                        headerFilter: { visible: true },
                        rowAlternationEnabled : true,
                        columnAutoWidth: true,
                        columnMinWidth: 50,
                        showBorders: true,
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
                            fileName: "Informasi Kebutuhan Komponen",
                            allowExportSelectedData: true
                        },
                        focusedRowEnabled: false,  
                        // selection: {
                        //     mode: "multiple",
                        //     allowSelectAll: true,
                        //     //selectAllMode: 'page' // or "multiple" | "none"
                        // },        
                        columns: [
                        {
                            dataField: "SW", dataType: "string", caption: "SPK PPIC",  cssClass: "cls"
                        },                    
                        {
                            dataField: "Komponen", dataType: "string", caption: "Komponen", cssClass: "cls"
                        },
                        {
                            dataField: "IDRequest", dataType: "string", caption: "ID Permintaan", cssClass: "cls", groupIndex: 1  
                        },
                        {
                            dataField: "UrutRR", dataType: "number", caption: "Urut", cssClass: "cls"
                        },                    
                        {
                            dataField: "QtyRequest", dataType: "number", caption: "Qty Order", cssClass: "cls"
                        },
                        {
                            dataField: "IDTTM", dataType: "number", caption: "ID Transfer",  cssClass: "cls"
                        },
                        {
                            dataField: "urutTM", dataType: "number", caption: "Urut",  cssClass: "cls"
                        },
                        {
                            dataField: "tglTM", dataType: "string", caption: "Tanggal Transfer",  cssClass: "cls"
                        }, 
                        {
                            dataField: "QtyTM", dataType: "number", caption: "Qty Transfer",  cssClass: "cls"
                        }, 
                        {
                            dataField: "BeratTM", dataType: "string", caption: "Berat Transfer",  cssClass: "cls"
                        }, 
                        {
                            dataField: "StatusTM", dataType: "string", caption: "Status Transfer",  cssClass: "cls", groupIndex: 0 
                        }
                        ],
                        summary: {
                                    groupItems: [{
                                        column: "UrutRR",
                                        summaryType: "count",
                                        displayFormat: "Jumlah : {0}",
                                        alignByColumn: true
                                        // showInGroupFooter: true
                                    },
                                    // {
                                    //     column: "QtyStock",
                                      
                                    //     displayFormat: "Qty Stok : {0}",
                                    //     alignByColumn: true
                                    //     // showInGroupFooter: true
                                    // },
                                    // {
                                    //     column: "WeightStock",
                                      
                                    //     displayFormat: "Weight Stok : {0}",
                                    //     alignByColumn: true
                                    //     // showInGroupFooter: true
                                    // }
                                    // ,{
                                    //     column: "Weight",
                                    //     summaryType: "sum",
                                    //     dataType: "string",
                                    //     alignByColumn: true,
                                    //     format: {
                                    //                 type: "fixedPoint",
                                    //                 precision: 2
                                    //             }     // or "left" | "right"
                                        
                                    //     // showInGroupFooter: true
                                    // },{
                                    //     column: "WeightProduct",
                                    //     summaryType: "sum",
                                    //     dataType: "string",
                                    //     alignByColumn: true,
                                    //     format: {
                                    //                 type: "fixedPoint",
                                    //                 precision: 2
                                    //             }     // or "left" | "right"
                                       
                                    //     // showInGroupFooter: true
                                    // },{
                                    //     column: "TotalStone",
                                    //     summaryType: "sum",
                                    //     alignByColumn: true
                                    //     // showInGroupFooter: true
                                    // },{
                                    //     column: "TotalInject",
                                    //     summaryType: "sum",
                                    //     alignByColumn: true
                                    //     // showInGroupFooter: true
                                    // },{
                                    //     column: "TotalPoles",
                                    //     summaryType: "sum",
                                    //     alignByColumn: true
                                    //     // showInGroupFooter: true
                                    // },{
                                    //     column: "TotalPatri",
                                    //     summaryType: "sum",
                                    //     alignByColumn: true
                                    //     // showInGroupFooter: true
                                    // },{
                                    //     column: "TotalPUK",
                                    //     summaryType: "sum",
                                    //     alignByColumn: true
                                    //     // showInGroupFooter: true
                                    // },{
                                    //     column: "WorkOrder",
                                    //     summaryType: "count",
                                    //     alignByColumn: true
                                    //     // showInGroupFooter: true
                                    // }
                                 
                                    ],
                        // totalItems: [{
                        //                 column: "PSW",
                        //                 summaryType: "count",
                        //                 showInColumn: "Kode",
                        //                 alignment: "left"     // or "left" | "right"
                        //             },{
                        //                 column: "Qty",
                        //                 summaryType: "sum",
                        //                 showInColumn: "Jumlah",
                        //                 alignment: "left"     // or "left" | "right"
                        //             }, {
                        //                 column: "QtyEnm",
                        //                 summaryType: "sum",
                        //                 showInColumn: "Qty Enamel",
                        //                 alignment: "left"     // or "left" | "right"
                        //             }, {
                        //                 column: "Weight",
                        //                 summaryType: "sum",
                        //                 showInColumn: "Berat",
                        //                 dataType: "string",
                        //                 alignment: "left",
                        //                 format: {
                        //                             type: "fixedPoint",
                        //                             precision: 2
                        //                         }     // or "left" | "right"
                        //             }, {
                        //                 column: "WeightProduct",
                        //                 summaryType: "sum",
                        //                 showInColumn: "Brt Pcs",
                        //                 dataType: "string",
                        //                 alignment: "left",
                        //                 format: {
                        //                             type: "fixedPoint",
                        //                             precision: 2
                        //                         }     // or "left" | "right"
                        //             }, {
                        //                 column: "TotalStone",
                        //                 summaryType: "sum",
                        //                 showInColumn: "Batu",
                        //                 alignment: "left"     // or "left" | "right"
                        //             }, {
                        //                 column: "TotalInject",
                        //                 summaryType: "sum",
                        //                 showInColumn: "Inject",
                        //                 alignment: "left"     // or "left" | "right"
                        //             }, {
                        //                 column: "TotalPoles",
                        //                 summaryType: "count",
                        //                 showInColumn: "Poles",
                        //                 alignment: "left"     // or "left" | "right"
                        //             }, {
                        //                 column: "TotalPatri",
                        //                 summaryType: "sum",
                        //                 showInColumn: "Patri",
                        //                 alignment: "left"     // or "left" | "right"
                        //             }, {
                        //                 column: "TotalPUK",
                        //                 summaryType: "sum",
                        //                 showInColumn: "PUK",
                        //                 alignment: "left"     // or "left" | "right"
                        //             }, {
                        //                 column: "WorkOrder",
                        //                 summaryType: "count",
                        //                 showInColumn: "No SPK",
                        //                 alignment: "left"     // or "left" | "right"
                        //             }
                             
                        //             ]
                                },
                        onRowClick: function(e) {  
                            if (e.rowType == 'group') {  
                                if (e.isExpanded)  
                                    e.component.collapseRow(e.key);  
                                else  
                                    e.component.expandRow(e.key);  
                            }  

                             // Mengambil data pada baris yang diklik
                            // var rowData = e.data;
                            // $('#idworkorder').val(rowData.ID);
                            // Menampilkan data pada console
                           
                            var rowData = e.data;
                            //console.log(rowData.SW);
                            var id = rowData.SW;
                            var product = rowData.IDKOM;
                            var data = {id:id, product:product};
                            $.ajaxSetup({
                                headers: {
                                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                    }
                                });
                                $.ajax({
                                type:'GET',
                                url: '/Produksi/CampurBahan/InfoKebutuhanKomponen/getSPKRouting', 
                                beforeSend: function () {
                                    $(".preloader").show();  
                                },
                                data: data,
                                dataType: "json",
                                success:function(data){
                                    $("#tampil2").html(data.html);
                                    document.getElementById("tampil2").style.display = "block";
                                    var dataGrid = $("#TableDevExtreme2").dxDataGrid({
                                        dataSource: data.tampil2,
                                        showBorders: true,
                                        columnsAutoWidth: true,
                                        // width: '100%',
                                        height: 280,
                                        allowColumnReordering: true,
                                        allowColumnResizing: true,
                                        columnResizingMode: 'nextColumn',
                                        showBorders: true,
                                        headerFilter: { visible: false },
                                        rowAlternationEnabled : true,
                                        columnAutoWidth: true,
                                        columnMinWidth: 50,
                                        showBorders: true,
                                        showRowLines: true,
                                        wordWrapEnabled: true,
                                        sorting: {
                                        mode: 'multiple',
                                        },
                                        filterRow: {
                                            visible: false,
                                            applyFilter: "auto"
                                        },         
                                        selection: {
                                            mode: "none" // or "multiple" | "none"
                                        },  
                                        searchPanel: {
                                            visible: false
                                        },
                                        paging: { enabled: false },
                                        grouping: {  
                                            autoExpandAll: false  
                                        },  
                                        groupPanel: {
                                            visible: false
                                        },
                                        "export": {
                                            enabled: false,
                                            fileName: "Informasi Stok Komponen",
                                            allowExportSelectedData: false
                                        },  
                                        columns: [
                                        {
                                            dataField: "SW", dataType: "string",  caption: "SPK PPIC",  cssClass: "cls"
                                        }, 
                                        {
                                            dataField: "Ordinal", dataType: "number",  caption: "Urutan",  cssClass: "cls"
                                        },
                                        {
                                            dataField: "TransDate", dataType: "string",  caption: "Tanggal",  cssClass: "cls"
                                        },                    
                                        {
                                            dataField: "Kadar", dataType: "string", caption: "Kadar", cssClass: "cls"
                                        }, 
                                        {
                                            dataField: "FGX", dataType: "string", caption: "Finish Good",  cssClass: "cls"
                                        }, 
                                        {
                                            dataField: "QtyFG", dataType: "number", caption: "Qty FG",  cssClass: "cls"
                                        }, 
                                        {
                                            dataField: "QtyKom", dataType: "number", caption: "Qty/Produk",  cssClass: "cls"
                                        }, 
                                        {
                                            dataField: "Qty", dataType: "number", caption: "Qty Komponen",  cssClass: "cls"
                                        }                      				          	          		           		          		                                                                                        
                                        ],
                                        summary: {
                                                    groupItems: [
                                                    // {
                                                    //     column: "QtyFG",
                                                    //     summaryType: "sum",
                                                    //     alignByColumn: true
                                                    //     // showInGroupFooter: true
                                                    // }
                                                    // ,{
                                                    //     column: "Qty",
                                                    //     summaryType: "sum",
                                                    //     alignByColumn: true
                                                    //     // showInGroupFooter: true
                                                    // },{
                                                    //     column: "QtyEnm",
                                                    //     summaryType: "sum",
                                                    //     alignByColumn: true
                                                    //     // showInGroupFooter: true
                                                    // },{
                                                    //     column: "Weight",
                                                    //     summaryType: "sum",
                                                    //     dataType: "string",
                                                    //     alignByColumn: true,
                                                    //     format: {
                                                    //                 type: "fixedPoint",
                                                    //                 precision: 2
                                                    //             }     // or "left" | "right"
                                                        
                                                    //     // showInGroupFooter: true
                                                    // },{
                                                    //     column: "WeightProduct",
                                                    //     summaryType: "sum",
                                                    //     dataType: "string",
                                                    //     alignByColumn: true,
                                                    //     format: {
                                                    //                 type: "fixedPoint",
                                                    //                 precision: 2
                                                    //             }     // or "left" | "right"
                                                    
                                                    //     // showInGroupFooter: true
                                                    // },{
                                                    //     column: "TotalStone",
                                                    //     summaryType: "sum",
                                                    //     alignByColumn: true
                                                    //     // showInGroupFooter: true
                                                    // },{
                                                    //     column: "TotalInject",
                                                    //     summaryType: "sum",
                                                    //     alignByColumn: true
                                                    //     // showInGroupFooter: true
                                                    // },{
                                                    //     column: "TotalPoles",
                                                    //     summaryType: "sum",
                                                    //     alignByColumn: true
                                                    //     // showInGroupFooter: true
                                                    // },{
                                                    //     column: "TotalPatri",
                                                    //     summaryType: "sum",
                                                    //     alignByColumn: true
                                                    //     // showInGroupFooter: true
                                                    // },{
                                                    //     column: "TotalPUK",
                                                    //     summaryType: "sum",
                                                    //     alignByColumn: true
                                                    //     // showInGroupFooter: true
                                                    // },{
                                                    //     column: "WorkOrder",
                                                    //     summaryType: "count",
                                                    //     alignByColumn: true
                                                    //     // showInGroupFooter: true
                                                    // }
                                                
                                                    ],
                                        totalItems: [
                                                    {
                                                        column: "Qty",
                                                        summaryType: "sum",
                                                        showInColumn: "Qty",
                                                        displayFormat: "Total Qty Order : {0}",
                                                        alignment: "right"     // or "left" | "right"
                                                    }
                                                    // ,{
                                                    //     column: "Qty",
                                                    //     summaryType: "sum",
                                                    //     showInColumn: "Jumlah",
                                                    //     alignment: "left"     // or "left" | "right"
                                                    // }, {
                                                    //     column: "QtyEnm",
                                                    //     summaryType: "sum",
                                                    //     showInColumn: "Qty Enamel",
                                                    //     alignment: "left"     // or "left" | "right"
                                                    // }, {
                                                    //     column: "Weight",
                                                    //     summaryType: "sum",
                                                    //     showInColumn: "Berat",
                                                    //     dataType: "string",
                                                    //     alignment: "left",
                                                    //     format: {
                                                    //                 type: "fixedPoint",
                                                    //                 precision: 2
                                                    //             }     // or "left" | "right"
                                                    // }, {
                                                    //     column: "WeightProduct",
                                                    //     summaryType: "sum",
                                                    //     showInColumn: "Brt Pcs",
                                                    //     dataType: "string",
                                                    //     alignment: "left",
                                                    //     format: {
                                                    //                 type: "fixedPoint",
                                                    //                 precision: 2
                                                    //             }     // or "left" | "right"
                                                    // }, {
                                                    //     column: "TotalStone",
                                                    //     summaryType: "sum",
                                                    //     showInColumn: "Batu",
                                                    //     alignment: "left"     // or "left" | "right"
                                                    // }, {
                                                    //     column: "TotalInject",
                                                    //     summaryType: "sum",
                                                    //     showInColumn: "Inject",
                                                    //     alignment: "left"     // or "left" | "right"
                                                    // }, {
                                                    //     column: "TotalPoles",
                                                    //     summaryType: "count",
                                                    //     showInColumn: "Poles",
                                                    //     alignment: "left"     // or "left" | "right"
                                                    // }, {
                                                    //     column: "TotalPatri",
                                                    //     summaryType: "sum",
                                                    //     showInColumn: "Patri",
                                                    //     alignment: "left"     // or "left" | "right"
                                                    // }, {
                                                    //     column: "TotalPUK",
                                                    //     summaryType: "sum",
                                                    //     showInColumn: "PUK",
                                                    //     alignment: "left"     // or "left" | "right"
                                                    // }, {
                                                    //     column: "WorkOrder",
                                                    //     summaryType: "count",
                                                    //     showInColumn: "No SPK",
                                                    //     alignment: "left"     // or "left" | "right"
                                                    // }
                                            
                                                    ]
                                                },
                                        onRowClick: function(e) {  
                                            if (e.rowType == 'group') {  
                                                if (e.isExpanded)  
                                                    e.component.collapseRow(e.key);  
                                                else  
                                                    e.component.expandRow(e.key);  
                                            }  
                                        }, 
                                    }).dxDataGrid("instance");  
                                    const resizingModes = ['nextColumn', 'widget'];
                                    $('#select-resizing').dxSelectBox({
                                        items: resizingModes,
                                        value: resizingModes[0],
                                        width: 250,
                                        onValueChanged(data) {
                                        dataGrid.option('columnResizingMode', data.value);
                                        },
                                    });  		
                                },
                                complete: function () {
                                    $(".preloader").fadeOut(); 
                                },
                                    error:function(xhr){
                                        // Invalid Jenis
                                        // Swal.fire({
                                        //     icon: 'error',
                                        //     title: 'Oops...',
                                        //     text: "Invalid Request",
                                        // })
                                        return;
                                    }
                                }); 
                            }, 
                        }).dxDataGrid("instance");  
                    }
                    




                    const resizingModes = ['nextColumn', 'widget'];
                    $('#select-resizing').dxSelectBox({
                        items: resizingModes,
                        value: resizingModes[0],
                        width: 250,
                        onValueChanged(data) {
                        dataGrid.option('columnResizingMode', data.value);
                        },
                    });  		
                },
                complete: function () {
                    $(".preloader").fadeOut(); 
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

        // function getSPKRouting() {
        //     var wo = $("#idworkorder").val();
        //     var data = {wo:wo};
        //     $.ajaxSetup({
        //         headers: {
        //                 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        //             }
        //         });
        //         $.ajax({
        //         type:'GET',
        //         url: '/Produksi/Informasi/RoutingProduksi/getSPKRouting', 
        //         beforeSend: function () {
        //             $(".preloader").show();  
        //         },
        //         data: data,
        //         dataType: "json",
        //         success:function(data){
        //             $("#tampil2").html(data.html);
        //             document.getElementById("datas2").style.display = "block";
        //             var dataGrid = $("#TableDevExtreme2").dxDataGrid({
        //                 dataSource: data.tampil,
        //                 showBorders: true,
        //                 columnsAutoWidth: true,
        //                 // width: '100%',
        //                 height: 380,
        //                 allowColumnReordering: true,
        //                 allowColumnResizing: true,
        //                 columnResizingMode: 'nextColumn',
        //                 showBorders: true,
        //                 headerFilter: { visible: true },
        //                 rowAlternationEnabled : true,
        //                 columnAutoWidth: true,
        //                 columnMinWidth: 50,
        //                 showBorders: true,
        //                 filterRow: {
        //                     visible: true,
        //                     applyFilter: "auto"
        //                 },         
        //                 selection: {
        //                     mode: "none" // or "multiple" | "none"
        //                 },  
        //                 searchPanel: {
        //                     visible: true
        //                 },
        //                 paging: { enabled: false },
        //                 grouping: {  
        //                     autoExpandAll: false  
        //                 },  
        //                 groupPanel: {
        //                     visible: true
        //                 },
        //                 "export": {
        //                     enabled: true,
        //                     fileName: "Informasi Routing Produksi",
        //                     allowExportSelectedData: true
        //                 },        
        //                 columns: [
        //                 {
        //                     dataField: "SW", dataType: "string", format: 'dd-MM-yyyy', caption: "SPK PPIC",  cssClass: "cls"
        //                 },                    
        //                 {
        //                     dataField: "TransDate", dataType: "date", caption: "Tanggal", cssClass: "cls"
        //                 }, 
        //                 {
        //                     dataField: "Model", dataType: "string", caption: "Model",  cssClass: "cls"
        //                 },                        
        //                 {
        //                     dataField: "Kadar", dataType: "string", caption: "Kadar", cssClass: "cls"
        //                 }, 
        //                 {
        //                     dataField: "Active", dataType: "string", caption: "Status",  cssClass: "cls"
        //                 }, 
        //                 {
        //                     dataField: "SWPurpose", dataType: "string", caption: "Kode",  cssClass: "cls"
        //                 }, 
        //                 {
        //                     dataField: "RequireDate", dataType: "date", caption: "RequireDate",  cssClass: "cls"
        //                 }, 
        //                 {
        //                     dataField: "TotalQty", dataType: "string", caption: "Total Qty",  cssClass: "cls"
        //                 },
        //                 {
        //                     dataField: "TotalWeight", dataType: "string", caption: "Total Berat",  cssClass: "cls"
        //                 },	
        //                 {
        //                     dataField: "TransferFG", dataType: "string", caption: "TransferFG",  cssClass: "cls"
        //                 }, 
        //                 {
        //                     dataField: "TransferWeight", dataType: "string", caption: "TransferWeight",  cssClass: "cls"
        //                 }, 	
        //                 {
        //                     dataField: "TransferStart", dataType: "string", caption: "TransferStart",  cssClass: "cls"
        //                 }, 
        //                 {
        //                     dataField: "TransferLast", dataType: "string", caption: "TransferLast", cssClass: "cls"
        //                 }, 
        //                 {
        //                     dataField: "WIP", dataType: "string", caption: "WIP", cssClass: "cls"
        //                 }, 	          		          
        //                 {
        //                     dataField: "Polling", dataType: "string", caption: "Polling", cssClass: "cls",
        //                 },  
        //                 {
        //                     dataField: "Outsource", dataType: "string", caption: "Outsource", cssClass: "cls",
        //                 },  	           	          
        //                 {
        //                     dataField: "Enamel", dataType: "string", caption: "Enamel", cssClass: "cls"
        //                 },  	          
        //                 {
        //                     dataField: "Wax", dataType: "string", caption: "Wax", cssClass: "cls"
        //                 }            				          	          		           		          		                                                                                        
        //                 ],
        //                 // summary: {
        //                 //             groupItems: [{
        //                 //                 column: "PSW",
        //                 //                 summaryType: "count",
        //                 //                 alignByColumn: true
        //                 //                 // showInGroupFooter: true
        //                 //             },{
        //                 //                 column: "Qty",
        //                 //                 summaryType: "sum",
        //                 //                 alignByColumn: true
        //                 //                 // showInGroupFooter: true
        //                 //             },{
        //                 //                 column: "QtyEnm",
        //                 //                 summaryType: "sum",
        //                 //                 alignByColumn: true
        //                 //                 // showInGroupFooter: true
        //                 //             },{
        //                 //                 column: "Weight",
        //                 //                 summaryType: "sum",
        //                 //                 dataType: "string",
        //                 //                 alignByColumn: true,
        //                 //                 format: {
        //                 //                             type: "fixedPoint",
        //                 //                             precision: 2
        //                 //                         }     // or "left" | "right"
                                        
        //                 //                 // showInGroupFooter: true
        //                 //             },{
        //                 //                 column: "WeightProduct",
        //                 //                 summaryType: "sum",
        //                 //                 dataType: "string",
        //                 //                 alignByColumn: true,
        //                 //                 format: {
        //                 //                             type: "fixedPoint",
        //                 //                             precision: 2
        //                 //                         }     // or "left" | "right"
                                       
        //                 //                 // showInGroupFooter: true
        //                 //             },{
        //                 //                 column: "TotalStone",
        //                 //                 summaryType: "sum",
        //                 //                 alignByColumn: true
        //                 //                 // showInGroupFooter: true
        //                 //             },{
        //                 //                 column: "TotalInject",
        //                 //                 summaryType: "sum",
        //                 //                 alignByColumn: true
        //                 //                 // showInGroupFooter: true
        //                 //             },{
        //                 //                 column: "TotalPoles",
        //                 //                 summaryType: "sum",
        //                 //                 alignByColumn: true
        //                 //                 // showInGroupFooter: true
        //                 //             },{
        //                 //                 column: "TotalPatri",
        //                 //                 summaryType: "sum",
        //                 //                 alignByColumn: true
        //                 //                 // showInGroupFooter: true
        //                 //             },{
        //                 //                 column: "TotalPUK",
        //                 //                 summaryType: "sum",
        //                 //                 alignByColumn: true
        //                 //                 // showInGroupFooter: true
        //                 //             },{
        //                 //                 column: "WorkOrder",
        //                 //                 summaryType: "count",
        //                 //                 alignByColumn: true
        //                 //                 // showInGroupFooter: true
        //                 //             }
                                 
        //                 //             ],
        //                 // totalItems: [{
        //                 //                 column: "PSW",
        //                 //                 summaryType: "count",
        //                 //                 showInColumn: "Kode",
        //                 //                 alignment: "left"     // or "left" | "right"
        //                 //             },{
        //                 //                 column: "Qty",
        //                 //                 summaryType: "sum",
        //                 //                 showInColumn: "Jumlah",
        //                 //                 alignment: "left"     // or "left" | "right"
        //                 //             }, {
        //                 //                 column: "QtyEnm",
        //                 //                 summaryType: "sum",
        //                 //                 showInColumn: "Qty Enamel",
        //                 //                 alignment: "left"     // or "left" | "right"
        //                 //             }, {
        //                 //                 column: "Weight",
        //                 //                 summaryType: "sum",
        //                 //                 showInColumn: "Berat",
        //                 //                 dataType: "string",
        //                 //                 alignment: "left",
        //                 //                 format: {
        //                 //                             type: "fixedPoint",
        //                 //                             precision: 2
        //                 //                         }     // or "left" | "right"
        //                 //             }, {
        //                 //                 column: "WeightProduct",
        //                 //                 summaryType: "sum",
        //                 //                 showInColumn: "Brt Pcs",
        //                 //                 dataType: "string",
        //                 //                 alignment: "left",
        //                 //                 format: {
        //                 //                             type: "fixedPoint",
        //                 //                             precision: 2
        //                 //                         }     // or "left" | "right"
        //                 //             }, {
        //                 //                 column: "TotalStone",
        //                 //                 summaryType: "sum",
        //                 //                 showInColumn: "Batu",
        //                 //                 alignment: "left"     // or "left" | "right"
        //                 //             }, {
        //                 //                 column: "TotalInject",
        //                 //                 summaryType: "sum",
        //                 //                 showInColumn: "Inject",
        //                 //                 alignment: "left"     // or "left" | "right"
        //                 //             }, {
        //                 //                 column: "TotalPoles",
        //                 //                 summaryType: "count",
        //                 //                 showInColumn: "Poles",
        //                 //                 alignment: "left"     // or "left" | "right"
        //                 //             }, {
        //                 //                 column: "TotalPatri",
        //                 //                 summaryType: "sum",
        //                 //                 showInColumn: "Patri",
        //                 //                 alignment: "left"     // or "left" | "right"
        //                 //             }, {
        //                 //                 column: "TotalPUK",
        //                 //                 summaryType: "sum",
        //                 //                 showInColumn: "PUK",
        //                 //                 alignment: "left"     // or "left" | "right"
        //                 //             }, {
        //                 //                 column: "WorkOrder",
        //                 //                 summaryType: "count",
        //                 //                 showInColumn: "No SPK",
        //                 //                 alignment: "left"     // or "left" | "right"
        //                 //             }
                             
        //                 //             ]
        //                 //         },
        //                 onRowClick: function(e) {  
        //                     if (e.rowType == 'group') {  
        //                         if (e.isExpanded)  
        //                             e.component.collapseRow(e.key);  
        //                         else  
        //                             e.component.expandRow(e.key);  
        //                     }  
        //                     //GET Tree Item 
        //                     // Mengambil data pada baris yang diklik
        //                     // var rowData = e.data;
        //                     // $('#idworkorder').val(rowData.ID);
        //                     // Menampilkan data pada console
        //                     //console.log(rowData);
                           

        //                      // Mengambil data pada baris yang diklik
        //                     // var rowData = e.data;
        //                     // $('#idworkorder').val(rowData.ID);
        //                     // Menampilkan data pada console
        //                     //console.log(rowData);
        //                 }, 
        //             }).dxDataGrid("instance");  
        //             const resizingModes = ['nextColumn', 'widget'];
        //             $('#select-resizing').dxSelectBox({
        //                 items: resizingModes,
        //                 value: resizingModes[0],
        //                 width: 250,
        //                 onValueChanged(data) {
        //                 dataGrid.option('columnResizingMode', data.value);
        //                 },
        //             });  		
        //         },
        //         complete: function () {
        //             $(".preloader").fadeOut(); 
        //         },
        //         error:function(xhr){
        //             // Invalid Jenis
        //             Swal.fire({
        //                 icon: 'error',
        //                 title: 'Oops...',
        //                 text: "Invalid Request",
        //             })
        //             return;
        //         }
        //     }); 
        // }

        function gettingFormOrderProduksi(){
            var jenis = $("#jenis").val();
            var bln = $("#bulannya").val();
            var kategori = $("#kategori").val();
            var kadar = $("#kadar").val();
            var id1 = $("#id1").val();
            var id2 = $("#id2").val();
            var tanggal1 = $("#tanggal1").val();
            var tanggal2 = $("#tanggal2").val();
            var data = {bln:bln, kategori:kategori, kadar:kadar, jenis:jenis, tanggal1: tanggal1, tanggal2:tanggal2, id1:id1, id2:id2 };
        if(jenis == 0){
            alert('Harap pilih Jenis Info terlebih dahulu !');
            document.getElementById("jenis").focus();
        }else if(jenis == 1){
                    if(bln == ''){
                        alert('Harap pilih Bulan terlebih dahulu !');
                    }else{
                        //alert(bln);
                        $.ajaxSetup({
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            }
                        });

                        $.ajax({
                            url: '/Penjualan/Informasi/MPC/FormOrderProduksi/gettingFormOrderProduksi', 
                            beforeSend: function(){
                                openModal();
                            },              
                            dataType : 'json',
                            type : 'GET',
                            data:data,
                            success: function(data)
                            {
                                // console.log(data.status);
                                $("#tampil").html(data.html);
                                document.getElementById("datas").style.display = "block";
                                var collapsedGroups = {};
                                var tabelstok =  $('#tabelformorder').DataTable({
                                    "paging": false,
                                    "searching": true,
                                    "ordering": true,
                                    "info": false,
                                    "autoWidth": true,
                                    "responsive": true,
                                    // "fixedColumns": true,
                                    "scrollCollapse": true,
                                    "fixedHeader": true,
                                    "fixedHeader": {
                                        header: true
                                        // footer: true
                                    },
                                    "scrollX": true,
                                    "scrollY": 500,
                                    rowGroup: {
                                        // Uses the 'row group' plugin
                                        dataSrc: 0,
                                        startRender: function(rows, group) {
                                            //console.log(group);
                                            var collapsed = !!collapsedGroups[group];
                                            rows.nodes().each(function(r) {
                                                r.style.display = collapsed ? '' : 'none';
                                            });

                                            // Remove the formatting to get integer data for summation
                                            var intVal = function (i) {
                                                return typeof i === 'string' ? i.replace(/[\$,]/g, '') * 1 : typeof i === 'number' ? i : 0;
                                            };

                                            var target1 = rows.data().pluck(3).reduce( function (a, b) {return intVal(a) + intVal(b);}, 0);
                                            var realisai1 = rows.data().pluck(4).reduce( function (a, b) {return intVal(a) + intVal(b);}, 0);
                                            var selisih1 = rows.data().pluck(5).reduce( function (a, b) {return intVal(a) + intVal(b);}, 0);
                                            var target2 = rows.data().pluck(6).reduce( function (a, b) {return intVal(a) + intVal(b);}, 0);
                                            var realisai2 = rows.data().pluck(7).reduce( function (a, b) {return intVal(a) + intVal(b);}, 0);
                                            var selisih2 = rows.data().pluck(8).reduce( function (a, b) {return intVal(a) + intVal(b);}, 0);

                                            // Add category name to the <tr>. NOTE: Hardcoded colspan
                                            return $('<tr style="background-color:#F9EDED;"/>')
                                                .append('<td colspan="3"><b>Kode : </b>' + group + ' (' + rows.count() +')</td>')
                                                .append(`<td colspan="1" style="text-align: right; color:#913030;"><b>${$.fn.dataTable.render.number('.', ',', 2, '').display(target1)}<b></td>`)
                                                .append(`<td colspan="1" style="text-align: right; color:#913030;"><b>${$.fn.dataTable.render.number('.', ',', 2, '').display(realisai1)}<b></td>`)
                                                .append(`<td colspan="1" style="text-align: right; color:#913030;"><b>${$.fn.dataTable.render.number('.', ',', 2, '').display(selisih1)}<b></td>`)
                                                .append(`<td colspan="1" style="text-align: right; color:#913030;"><b>${$.fn.dataTable.render.number('.', ',', 0, '').display(target2)}<b></td>`)
                                                .append(`<td colspan="1" style="text-align: right; color:#913030;"><b>${$.fn.dataTable.render.number('.', ',', 0, '').display(realisai2)}<b></td>`)
                                                .append(`<td colspan="1" style="text-align: right; color:#913030;"><b>${$.fn.dataTable.render.number('.', ',', 0, '').display(selisih2)}<b></td>`)
                                                .attr('data-name', group)
                                                .toggleClass('collapsed', collapsed);
                                        }
                                    },
                                    // dom: 'Bfrtip',
                                    // buttons: [
                                    //             {
                                    //             extend:    'excelHtml5', 
                                    //             // footer: true,
                                    //             text:      '<i class="tf-icons bx bx-file"></i> Excel',
                                    //             titleAttr: 'Excel',
                                    //             className: 'btn btn-outline-dark',
                                    //             exportOptions: {
                                    //                 columns: ':visible'
                                    //             }
                                    //             },
                                                
                                    //         ]
                                });
                                $('#tabelformorder tbody').on('click', 'tr.dtrg-group', function() {
                                    //console.log('ikkk');
                                    var name = $(this).data('name');
                                    collapsedGroups[name] = !collapsedGroups[name];
                                    tabelstok.draw(false);
                                });
                            },
                            complete: function(){
                                closeModal();
                            },             
                        }); 
                    }
                            
        }else if(jenis == 2){
            // if(empty(tanggal1) || empty(id1)){
            //     alert('Silahkan Pilih Tanggal ata !');
            //     document.getElementById("tanggal1").focus();
            //     document.getElementById("id1").focus();
            // }else{

            // }
            $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                $.ajax({
                type:'GET',
                url: '/Penjualan/Informasi/MPC/FormOrderProduksi/gettingFormOrderProduksi', 
                data: data,
                dataType: "json",
                beforeSend: function () {
                    $(".preloader").show();  
                },
                complete: function () {
                    $(".preloader").fadeOut(); 
                },
                success:function(data){
                    $("#tampil").html(data.html);
                    document.getElementById("datas").style.display = "block";
                    var dataGrid = $("#TableDevExtreme").dxDataGrid({
                        dataSource: data.tampil,
                        showBorders: true,
                        columnsAutoWidth: true,
                        // width: '100%',
                        height: 620,
                        allowColumnReordering: true,
                        showBorders: true,
                        headerFilter: { visible: true },
                        rowAlternationEnabled : true,
                        columnAutoWidth: true,
                        columnMinWidth: 100,
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
                            fileName: "Informasi Permintaan Produksi",
                            allowExportSelectedData: true
                        },        
                        columns: [
                        {
                            dataField: "Purpose", dataType: "string", format: 'dd-MM-yyyy', caption: "Keperluan",  cssClass: "cls"
                        },                    
                        {
                            dataField: "TransDate", dataType: "date", caption: "Tanggal", cssClass: "cls"
                        }, 
                        {
                            dataField: "RequireDate", dataType: "date", caption: "Dibutuhkan",  cssClass: "cls"
                        },                        
                        {
                            dataField: "Ordinal", dataType: "string", caption: "Urut", cssClass: "cls"
                        }, 
                        {
                            dataField: "Customer", dataType: "string", caption: "Customer",  cssClass: "cls"
                        }, 
                        {
                            dataField: "MSW", dataType: "string", caption: "Sub Kategori",  cssClass: "cls"
                        }, 	
                        {
                            dataField: "Model", dataType: "string", caption: "Model",  cssClass: "cls"
                        }, 
                        {
                            dataField: "PSW", dataType: "string", caption: "Kode",  cssClass: "cls"
                        }, 	
                        {
                            dataField: "Carat", dataType: "string", caption: "Kadar",  cssClass: "cls"
                        }, 
                        {
                            dataField: "Qty", dataType: "string", caption: "Jumlah", cssClass: "cls"
                        }, 
                        {
                            dataField: "QtyEnm", dataType: "string", caption: "Qty Enamel", cssClass: "cls"
                        }, 	          		          
                        {
                            dataField: "Weight", dataType: "string", caption: "Berat", cssClass: "cls",
                            format: {
                                        type: "fixedPoint",
                                        precision: 2
                                    }     // or "left" | "right"
                        },  
                        {
                            dataField: "WeightProduct", dataType: "string", caption: "Brt Pcs", cssClass: "cls",
                            format: {
                                        type: "fixedPoint",
                                        precision: 2
                                    }     // or "left" | "right"
                        },  	           	          
                        {
                            dataField: "TotalStone", dataType: "string", caption: "Batu", cssClass: "cls"
                        },  	          
                        {
                            dataField: "TotalInject", dataType: "string", caption: "Inject", cssClass: "cls"
                        },  	          
                        {
                            dataField: "TotalPoles", dataType: "string", caption: "Poles", cssClass: "cls"
                        },  	          
                        {
                            dataField: "TotalPatri", dataType: "string", caption: "Patri", cssClass: "cls"
                        },  	          
                        {
                            dataField: "TotalPUK", dataType: "string", caption: "PUK", cssClass: "cls"
                        },  	          
                        {
                            dataField: "Enamel", dataType: "string", caption: "Enamel", cssClass: "cls"
                        },  	          
                        {
                            dataField: "Slep", dataType: "string", caption: "Slep", cssClass: "cls"
                        },  	          
                        {
                            dataField: "Marking", dataType: "string", caption: "Marking", cssClass: "cls"
                        },  	          
                        {
                            dataField: "VarP", dataType: "string", caption: "Var P", cssClass: "cls"
                        },  	          
                        {
                            dataField: "Note", dataType: "string", caption: "Keterangan", cssClass: "cls"
                        },  	          
                        {
                            dataField: "WaxNote", dataType: "string", caption: "Keterangan Lilin", cssClass: "cls"
                        },  	          
                        {
                            dataField: "StoneNote", dataType: "string", caption: "Keterangan Batu", cssClass: "cls"
                        },  	          
                        {
                            dataField: "SpecialNote", dataType: "string", caption: "Keterangan Variasi", cssClass: "cls"
                        },  	          
                        {
                            dataField: "FinishingNote", dataType: "string", caption: "Keterangan Finishing", cssClass: "cls"
                        },  	          
                        {
                            dataField: "GTNote", dataType: "string", caption: "Keterangan GT", cssClass: "cls"
                        },  	          
                        {
                            dataField: "KikirNote", dataType: "string", caption: "Keterangan Kikir", cssClass: "cls"
                        },  	          
                        {
                            dataField: "Urgent", dataType: "string", caption: "Urgent", cssClass: "cls"
                        },  	          
                        {
                            dataField: "WorkOrder", dataType: "string", caption: "No SPK", cssClass: "cls"
                        },  	          
                        {
                            dataField: "OrderDate", dataType: "string", caption: "Tgl SPK", cssClass: "cls"
                        },  	          
                        {
                            dataField: "Category", dataType: "string", caption: "Kategori", cssClass: "cls"
                        },  	          
                        {
                            dataField: "Month", dataType: "string", caption: "Bulan", cssClass: "cls"
                        },  	          
                        {
                            dataField: "ID", dataType: "string", caption: "ID", cssClass: "cls", groupIndex: 0
                        }	          
                           				          	          		           		          		                                                                                        
                        ],
                        // summary: {
                        //             groupItems: [{
                        //                 column: "PSW",
                        //                 summaryType: "count",
                        //                 alignByColumn: true
                        //                 // showInGroupFooter: true
                        //             },{
                        //                 column: "Qty",
                        //                 summaryType: "sum",
                        //                 alignByColumn: true
                        //                 // showInGroupFooter: true
                        //             },{
                        //                 column: "QtyEnm",
                        //                 summaryType: "sum",
                        //                 alignByColumn: true
                        //                 // showInGroupFooter: true
                        //             },{
                        //                 column: "Weight",
                        //                 summaryType: "sum",
                        //                 dataType: "string",
                        //                 alignByColumn: true,
                        //                 format: {
                        //                             type: "fixedPoint",
                        //                             precision: 2
                        //                         }     // or "left" | "right"
                                        
                        //                 // showInGroupFooter: true
                        //             },{
                        //                 column: "WeightProduct",
                        //                 summaryType: "sum",
                        //                 dataType: "string",
                        //                 alignByColumn: true,
                        //                 format: {
                        //                             type: "fixedPoint",
                        //                             precision: 2
                        //                         }     // or "left" | "right"
                                       
                        //                 // showInGroupFooter: true
                        //             },{
                        //                 column: "TotalStone",
                        //                 summaryType: "sum",
                        //                 alignByColumn: true
                        //                 // showInGroupFooter: true
                        //             },{
                        //                 column: "TotalInject",
                        //                 summaryType: "sum",
                        //                 alignByColumn: true
                        //                 // showInGroupFooter: true
                        //             },{
                        //                 column: "TotalPoles",
                        //                 summaryType: "sum",
                        //                 alignByColumn: true
                        //                 // showInGroupFooter: true
                        //             },{
                        //                 column: "TotalPatri",
                        //                 summaryType: "sum",
                        //                 alignByColumn: true
                        //                 // showInGroupFooter: true
                        //             },{
                        //                 column: "TotalPUK",
                        //                 summaryType: "sum",
                        //                 alignByColumn: true
                        //                 // showInGroupFooter: true
                        //             },{
                        //                 column: "WorkOrder",
                        //                 summaryType: "count",
                        //                 alignByColumn: true
                        //                 // showInGroupFooter: true
                        //             }
                                 
                        //             ],
                        // totalItems: [{
                        //                 column: "PSW",
                        //                 summaryType: "count",
                        //                 showInColumn: "Kode",
                        //                 alignment: "left"     // or "left" | "right"
                        //             },{
                        //                 column: "Qty",
                        //                 summaryType: "sum",
                        //                 showInColumn: "Jumlah",
                        //                 alignment: "left"     // or "left" | "right"
                        //             }, {
                        //                 column: "QtyEnm",
                        //                 summaryType: "sum",
                        //                 showInColumn: "Qty Enamel",
                        //                 alignment: "left"     // or "left" | "right"
                        //             }, {
                        //                 column: "Weight",
                        //                 summaryType: "sum",
                        //                 showInColumn: "Berat",
                        //                 dataType: "string",
                        //                 alignment: "left",
                        //                 format: {
                        //                             type: "fixedPoint",
                        //                             precision: 2
                        //                         }     // or "left" | "right"
                        //             }, {
                        //                 column: "WeightProduct",
                        //                 summaryType: "sum",
                        //                 showInColumn: "Brt Pcs",
                        //                 dataType: "string",
                        //                 alignment: "left",
                        //                 format: {
                        //                             type: "fixedPoint",
                        //                             precision: 2
                        //                         }     // or "left" | "right"
                        //             }, {
                        //                 column: "TotalStone",
                        //                 summaryType: "sum",
                        //                 showInColumn: "Batu",
                        //                 alignment: "left"     // or "left" | "right"
                        //             }, {
                        //                 column: "TotalInject",
                        //                 summaryType: "sum",
                        //                 showInColumn: "Inject",
                        //                 alignment: "left"     // or "left" | "right"
                        //             }, {
                        //                 column: "TotalPoles",
                        //                 summaryType: "count",
                        //                 showInColumn: "Poles",
                        //                 alignment: "left"     // or "left" | "right"
                        //             }, {
                        //                 column: "TotalPatri",
                        //                 summaryType: "sum",
                        //                 showInColumn: "Patri",
                        //                 alignment: "left"     // or "left" | "right"
                        //             }, {
                        //                 column: "TotalPUK",
                        //                 summaryType: "sum",
                        //                 showInColumn: "PUK",
                        //                 alignment: "left"     // or "left" | "right"
                        //             }, {
                        //                 column: "WorkOrder",
                        //                 summaryType: "count",
                        //                 showInColumn: "No SPK",
                        //                 alignment: "left"     // or "left" | "right"
                        //             }
                             
                        //             ]
                        //         },
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
    }

    </script>
@endsection
<?php $title = 'LeadTime'; ?>
@extends('layouts.backend-Theme-3.app')
@section('title', '$title')

@section('Dashboard')
    <h2 class="m-0">{{ $title }}</h2>
    <ol class="breadcrumb sm-2">
        <li class="breadcrumb-item"><a href="/">Home </a></li>
        <li class="breadcrumb-item">Produksi </li>
        <li class="breadcrumb-item">Informasi </li>
        <li class="breadcrumb-item active">LeadTime </li>
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
    </style>
    <link rel="stylesheet" href="{!! asset('assets/almas/select2.min.css') !!}">
    <link rel="stylesheet" href="{!! asset('assets/sneatV1/assets/vendor/libs/BootstrapSelect/bootstrap-select.min.css') !!}">
    {{-- DevExtreme --}}
    <link rel="stylesheet" href="{!! asset('assets/sneatV1/assets/vendor/libs/DevExtreme/css/dx.common.css') !!}">
    <link rel="stylesheet" href="{!! asset('assets/sneatV1/assets/vendor/libs/DevExtreme/css/dx.material.orange.light.compact.css') !!}">
    {{-- ApexChart --}}
    <link rel="stylesheet" href="{!! asset('assets/sneatV1/assets/vendor/libs/apex-charts/apex-charts.css') !!}">
@endsection

@section('container')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                @include('Produksi.Informasi.LeadTime.data')
            </div>
        </div>
    </div>
    <div class="dropdown-menu dropdown-menu-end animate" id="klikMenu" style="display:none">
        <div class="text-center fw-bold mb-2" id="klikJudul"></div>
        <a class="dropdown-item" onclick="klikEdit()"><span class="tf-icons bx bx-edit"></span>&nbsp; Edit</a>
        <a class="dropdown-item" onclick="klikCetak()"><span class="tf-icons bx bx-printer"></span>&nbsp; Cetak</a>
        <a class="dropdown-item" onclick="klikInfo()"><span class="tf-icons bx bx-list-ul"></span>&nbsp; Info</a>
    </div> 
@endsection

@section('script')
    @include('layouts.backend-Theme-3.DataTabelButton')
    <script src="{!! asset('assets/almas/select2.min.js') !!}"></script>
    <script src="{!! asset('assets/sneatV1/assets/vendor/libs/BootstrapSelect/bootstrap-select.min.js') !!}"></script>
    {{-- DevExtreme --}}
    <script src="{!! asset('assets/sneatV1/assets/vendor/libs/DevExtreme/js/jszip.min.js') !!}"></script>
    <script src="{!! asset('assets/sneatV1/assets/vendor/libs/DevExtreme/js/dx.all.js') !!}"></script>
    {{-- ApexChart --}}
    <script src="{!! asset('assets/sneatV1/assets/vendor/libs/apex-charts/apexcharts.js') !!}"></script>

    <script>

        function lihatChart(){
            // BAR CHART
            var options = {
                chart: {
                    type: 'bar'
                },
                series: [{
                    name: 'sales',
                    data: [30,40,45,50,49,60,70,91,125]
                }],
                xaxis: {
                    categories: [1991,1992,1993,1994,1995,1996,1997,1998,1999]
                }
            };
            var chart = new ApexCharts(document.querySelector("#chart"), options);
            chart.render();

            // DONUT CHART
            var options = {
                chart: {
                    type: 'donut'
                },
                series: [44, 55, 13, 33],
                labels: ['Apple', 'Mango', 'Orange', 'Watermelon']
            };
            var chart = new ApexCharts(document.querySelector("#chart2"), options);
            chart.render();
        }


        function chartPoles(qtyspko, weightspko){
            console.log(qtyspko);
            var options = {
                chart: {
                    type: 'donut'
                },
                series: [44, 55, 13, 33],
                labels: ['Apple', 'Mango', 'Orange', 'Watermelon']
            };
            var chart = new ApexCharts(document.querySelector("#chartApp1"), options);
            chart.render();

            var options = {
                series: [{
                    data: qtyspko
                }],
                chart: {
                    type: 'bar',
                    height: 350
                },
                plotOptions: {
                    bar: {
                        borderRadius: 4,
                        horizontal: true,
                    }
                },
                dataLabels: {
                    enabled: false
                },
                // xaxis: {
                //     categories: ['South Korea', 'Canada', 'United Kingdom', 'Netherlands', 'Italy', 'France', 'Japan',
                //         'United States', 'China', 'Germany'
                //     ],
                // }
                xaxis: {
                    categories: ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
                    ],
                }
            };
            var chart = new ApexCharts(document.querySelector("#chartApp2"), options);
            chart.render();
      
        }

        function tampilChart(){
            var jenisreport = $("#jenisreport").val();

            if (jenisreport == "") {
                Swal.fire({
                    icon: "error",
                    title: "Harap Isi Jenis Report",
                    showCancelButton: false,
                    showConfirmButton: true
                });
            }else{
                if(jenisreport == 1){
                    chart1();
                }else if(jenisreport == 2){
                    chart2();
                }else if(jenisreport == 3){
                    chart3();
                }else if(jenisreport == 4){
                    chart4();
                }else if(jenisreport == 5){
                    chart5();
                }else if(jenisreport == 6){
                    chart6();
                }else if(jenisreport == 7){
                    chart7();
                }else if(jenisreport == 8){
                    chart8();
                }
            }
        }

        function chart8(arrSeries, arrLabels){
            var series1 = arrSeries;
            var labels1 = arrLabels;

            // console.log(series1);
            // console.log(labels1);

            // 
            // var options = {
            //     title: {
            //         text: 'Chart1',
            //         align: 'left',
            //         margin: 10,
            //         offsetX: 0,
            //         offsetY: 0,
            //         floating: false,
            //         style: {
            //             fontSize:  '14px',
            //             fontWeight:  'bold',
            //             fontFamily:  undefined,
            //             color:  '#263238'
            //         },
            //     },
            //     chart: {
            //         type: 'donut'
            //     },
            //     series: [44, 55, 13, 33],
            //     labels: ['Apple', 'Mango', 'Orange', 'Watermelon']
            //     // series: series1,
            //     // labels: labels1
            // };
            // var chart = new ApexCharts(document.querySelector("#chart1"), options);
            // chart.render();

            // 
            var options = {
                title: {
                    text: 'Chart',
                    align: 'center',
                    margin: 10,
                    offsetX: 0,
                    offsetY: 0,
                    floating: false,
                    style: {
                        fontSize:  '14px',
                        fontWeight:  'bold',
                        fontFamily:  undefined,
                        color:  '#263238'
                    },
                },
                chart: {
                    type: 'bar',
                    height: 500
                },
                plotOptions: {
                    bar: {
                        borderRadius: 4,
                        horizontal: false,
                    }
                },
                series: [{
                    name: 'MasterCycleTime',
                    // data: [30,40,45,50,49,60,70,91,125]
                    data: series1
                }],
                xaxis: {
                    // categories: [1991,1992,1993,1994,1995,1996,1997,1998,1999]
                    categories: labels1
                }
            };
            var chart = new ApexCharts(document.querySelector("#chart2"), options);
            chart.render();

            // 
            // var options = {
            //     title: {
            //         text: 'Test Chart3',
            //         align: 'left',
            //         margin: 10,
            //         offsetX: 0,
            //         offsetY: 0,
            //         floating: false,
            //         style: {
            //             fontSize:  '14px',
            //             fontWeight:  'bold',
            //             fontFamily:  undefined,
            //             color:  '#263238'
            //         },
            //     },
            //     series: [{
            //         name: 'Net Profit',
            //         data: [44, 55, 57, 56, 61, 58, 63, 60, 66]
            //     }, {
            //         name: 'Revenue',
            //         data: [76, 85, 101, 98, 87, 105, 91, 114, 94]
            //     }, {
            //         name: 'Free Cash Flow',
            //         data: [35, 41, 36, 26, 45, 48, 52, 53, 41]
            //     }],
            //     chart: {
            //         type: 'bar',
            //     },
            //     plotOptions: {
            //         bar: {
            //             horizontal: false,
            //             columnWidth: '55%',
            //             endingShape: 'rounded'
            //         },
            //     },
            //     dataLabels: {
            //         enabled: false
            //     },
            //     stroke: {
            //         show: true,
            //         width: 2,
            //         colors: ['transparent']
            //     },
            //     xaxis: {
            //         categories: ['Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct'],
            //     },
            //     yaxis: {
            //         title: {
            //             text: '$ (thousands)'
            //         }
            //     },
            //     fill: {
            //         opacity: 1
            //     },
            //     tooltip: {
            //         y: {
            //             formatter: function (val) {
            //             return "$ " + val + " thousands"
            //             }
            //         }
            //     }
            // };
            // var chart = new ApexCharts(document.querySelector("#chart3"), options);
            // chart.render();
        }


        function openModal(){
            $(".preloader").fadeIn(300);
        }

        function closeModal(){
            $(".preloader").fadeOut(300);
        }

        function showInput(pilih){
            var pilih = pilih;
            if(pilih == ""){
                document.getElementById('rowinput').style.display = 'none';
            }else{
                if(pilih == 1){
                    document.getElementById('rowinput').style.display = 'block';
                    document.getElementById("tgl1").disabled = false; 
                    document.getElementById("tgl2").disabled = false; 
                    document.getElementById("kadar").disabled = false; 
                    document.getElementById("operation").disabled = true; 
                    document.getElementById("operator").disabled = true; 
                    document.getElementById("kategori").disabled = true; 
                    document.getElementById("subkategori").disabled = true; 

                    document.getElementById("kadar").value = ''; 
                    document.getElementById("operation").value = ''; 
                    document.getElementById("operator").value = ''; 
                    document.getElementById("kategori").value = ''; 
                    document.getElementById("subkategori").value = ''; 
                    
                }else if(pilih == 2){
                    document.getElementById('rowinput').style.display = 'block';
                    document.getElementById("tgl1").disabled = false; 
                    document.getElementById("tgl2").disabled = false; 
                    document.getElementById("kadar").disabled = true; 
                    document.getElementById("operation").disabled = false; 
                    document.getElementById("operator").disabled = true; 
                    document.getElementById("kategori").disabled = true; 
                    document.getElementById("subkategori").disabled = true; 

                    document.getElementById("kadar").value = ''; 
                    document.getElementById("operation").value = ''; 
                    document.getElementById("operator").value = ''; 
                    document.getElementById("kategori").value = ''; 
                    document.getElementById("subkategori").value = ''; 

                }else if(pilih == 3){
                    document.getElementById('rowinput').style.display = 'block';
                    document.getElementById("tgl1").disabled = false; 
                    document.getElementById("tgl2").disabled = false; 
                    document.getElementById("kadar").disabled = true; 
                    document.getElementById("operation").disabled = true; 
                    document.getElementById("operator").disabled = false; 
                    document.getElementById("subkategori").disabled = true; 
                    document.getElementById("kategori").disabled = true; 

                    document.getElementById("kadar").value = ''; 
                    document.getElementById("operation").value = ''; 
                    document.getElementById("operator").value = ''; 
                    document.getElementById("kategori").value = ''; 
                    document.getElementById("subkategori").value = ''; 
                    
                }else if(pilih == 4){
                    document.getElementById('rowinput').style.display = 'block';
                    document.getElementById("tgl1").disabled = false; 
                    document.getElementById("tgl2").disabled = false; 
                    document.getElementById("kadar").disabled = true; 
                    document.getElementById("operation").disabled = true; 
                    document.getElementById("operator").disabled = true; 
                    document.getElementById("kategori").disabled = false; 
                    document.getElementById("subkategori").disabled = true; 

                    document.getElementById("kadar").value = ''; 
                    document.getElementById("operation").value = ''; 
                    document.getElementById("operator").value = ''; 
                    document.getElementById("kategori").value = ''; 
                    document.getElementById("subkategori").value = ''; 
                    
                }else if(pilih == 5){
                    document.getElementById('rowinput').style.display = 'block';
                    document.getElementById("tgl1").disabled = false; 
                    document.getElementById("tgl2").disabled = false; 
                    document.getElementById("kadar").disabled = true; 
                    document.getElementById("operation").disabled = true; 
                    document.getElementById("operator").disabled = true; 
                    document.getElementById("kategori").disabled = true; 
                    document.getElementById("subkategori").disabled = false; 

                    document.getElementById("kadar").value = ''; 
                    document.getElementById("operation").value = ''; 
                    document.getElementById("operator").value = ''; 
                    document.getElementById("kategori").value = ''; 
                    document.getElementById("subkategori").value = '';  
                    
                }else{
                    document.getElementById('rowinput').style.display = 'block';
                    document.getElementById("tgl1").disabled = false; 
                    document.getElementById("tgl2").disabled = false; 
                    document.getElementById("kadar").disabled = false; 
                    document.getElementById("operation").disabled = false; 
                    document.getElementById("operator").disabled = false; 
                    document.getElementById("subkategori").disabled = false; 
                    document.getElementById("kategori").disabled = false; 

                    document.getElementById("kadar").value = ''; 
                    document.getElementById("operation").value = ''; 
                    document.getElementById("operator").value = ''; 
                    document.getElementById("kategori").value = ''; 
                    document.getElementById("subkategori").value = ''; 
                }
            }
        }

        function klikLaporan(){
            var jenis = $("#jenis").val();
            var tglstart = $("#tgl1").val();
            var tglend = $("#tgl2").val();
            var kadar = $("#kadar").val();
            var operation = $("#operation").val();
            var operator = $("#operator").val();
            var kategori = $("#kategori").val();
            var subkategori = $("#subkategori").val();

            if(tglstart == "" || tglend == ""){
                Swal.fire({
                    icon: "error",
                    title: "Harap Isi Tgl Dulu",
                    timer: 1000,
                    showCancelButton: false,
                    showConfirmButton: true
                });
            }else{

                if(jenis == 1){ //Kadar
                    var dataUrl = '/Produksi/Informasi/LeadTime/reportAll';
                    var dataSrcGroup = 4;
                    var dataRowGroup = '<td colspan="14" style="color: blue"><b>Kadar : </b>';
                } else if(jenis == 2){ //Operation
                    var dataUrl = '/Produksi/Informasi/LeadTime/reportAll';
                    var dataSrcGroup = 5;
                    var dataRowGroup = '<td colspan="14" style="color: blue"><b>Operation : </b>';
                } else if(jenis == 3){ //Operator
                    var dataUrl = '/Produksi/Informasi/LeadTime/reportAll';
                    var dataSrcGroup = 6;
                    var dataRowGroup = '<td colspan="14" style="color: blue"><b>Operator : </b>';
                } else if(jenis == 4){ //Kategori
                    var dataUrl = '/Produksi/Informasi/LeadTime/reportAll';
                    var dataSrcGroup = 9;
                    var dataRowGroup = '<td colspan="14" style="color: blue"><b>Kategori : </b>';
                } else if(jenis == 5){ //SubKategori
                    var dataUrl = '/Produksi/Informasi/LeadTime/reportAll';
                    var dataSrcGroup = 8;
                    var dataRowGroup = '<td colspan="14" style="color: blue"><b>SubKategori : </b>';
                }

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                data = {jenis: jenis, tglstart: tglstart, tglend: tglend, kadar: kadar, operation: operation, operator: operator, kategori: kategori, subkategori: subkategori};
                $.ajax({
                    url: dataUrl,
                    beforeSend: function(){
                        openModal();
                    },
                    data : data,
                    dataType : 'json',
                    type : 'POST',
                    success: function(data)
                    {
                        $("#tampil").html(data.html);

                        var collapsedGroups = {};
                        var table = $('#tampiltabel').DataTable({
                            paging: false,
                            ordering: true,
                            info: false,
                            searching: true,
                            autoWidth: true,
                            responsive: true,
                            scrollX: true,
                            scrollY: '50vh',
                            scroller: true,
                            scrollCollapse: true,
                            dom: 'Bfrtip',
                            buttons: ['excel'],
                            rowGroup: {
                                dataSrc: dataSrcGroup,
                                startRender: function (rows, group) {
                                    var collapsed = !!collapsedGroups[group];
                                    rows.nodes().each(function (r) {
                                        r.style.display = collapsed ? '' : 'none';
                                    });    

                                    // Remove the formatting to get integer data for summation
                                    var intVal = function (i) {
                                        return typeof i === 'string' ? i.replace(/[\$,]/g, '') * 1 : typeof i === 'number' ? i : 0;
                                    };

                                    // var qtyspko = rows.data().pluck(11).reduce( function (a, b) {return intVal(a) + intVal(b);}, 0);                       

                                    return $('<tr/>')
                                        .append(`${dataRowGroup} ${group} (${rows.count()}) </td>`)
                                        .attr('data-name', group)
                                        .toggleClass('collapsed', collapsed);
                                
                                },
                            }
                        });  

                        $('#tampiltabel tbody').on('click', 'tr.dtrg-group', function(){
                            var name = $(this).data('name');
                            collapsedGroups[name] = !collapsedGroups[name];
                            table.draw(false);
                        }); 

                    },
                    complete: function(){
                        closeModal();
                    },
                });
                event.preventDefault();
            }
        }

        function klikCetakLaporan(){
            var jenis = $("#jenis").val();
            var jeniscetak = $("#jeniscetak").val();
            var rph = $("#rph").val();
            var kadar = $("#kadar").val();
            var operation = $("#operation").val();
            var operator = $("#operator").val();
            var tglstart = $("#tgl1").val();
            var tglend = $("#tgl2").val();
            var kategori = $("#kategori").val();
            var subkategori = $("#subkategori").val();

            console.log(jeniscetak);

            if(tglstart == "" || tglend == ""){
                alert("Harap Isi Tgl Dulu!");
            }else{

                if(jenis == 1){
                    var dataUrl = `/Produksi/Informasi/JadwalKerjaHarian/cetakPerRPH?rph=${rph}`;
                }else if(jenis == 2){
                    var dataUrl = `/Produksi/Informasi/JadwalKerjaHarian/cetakPerTgl?tglstart=${tglstart}&tglend=${tglend}&kadar=${kadar}&operation=${operation}&operator=${operator}`;
                }else if(jenis == 7 || jenis == 8 || jenis == 9 || jenis == 10 || jenis == 11){
                    var dataUrl = `/Produksi/Informasi/JadwalKerjaHarian/cetakAll?tglstart=${tglstart}&tglend=${tglend}&kadar=${kadar}&operation=${operation}&operator=${operator}&kategori=${kategori}&subkategori=${subkategori}&jenis=${jenis}&jeniscetak=${jeniscetak}`;
                }

                window.open(dataUrl, '_blank');
            }
        }

        function reportAll(){
            var jenisreport = $("#jenisreport").val();

            if (jenisreport == "") {
                Swal.fire({
                    icon: "error",
                    title: "Harap Isi Jenis Report",
                    showCancelButton: false,
                    showConfirmButton: true
                });
            }else{
                if(jenisreport == 1){
                    $('#chart2').empty();
                    report1();
                }else if(jenisreport == 2){
                    $('#chart2').empty();
                    report2();
                }else if(jenisreport == 3){
                    $('#chart2').empty();
                    report3();
                }else if(jenisreport == 4){
                    $('#chart2').empty();
                    report4();
                }else if(jenisreport == 5){
                    $('#chart2').empty();
                    report5();
                }else if(jenisreport == 6){
                    $('#chart2').empty();
                    report6();
                }else if(jenisreport == 7){
                    $('#chart2').empty();
                    report7();
                }else if(jenisreport == 8){
                    $('#chart2').empty();
                    report8();
                }

            }
        }

        function report1(){ //OK
            var tglstart = $("#tglstart").val();
            var tglend = $("#tglend").val();

            if (tglstart == "" || tglend == "") {
                // Show Warning Message
                // Swal.fire({
                //     icon: "error",
                //     title: "Harap Isi Tgl",
                //     showCancelButton: false,
                //     showConfirmButton: true
                // });

                // Get Default tglstart & tglend
                var currentdate = new Date(); 
                
                var tgl = currentdate.getDate();
                var bulan = currentdate.getMonth()+1; //.getMonth() returns a zero-based number so to get the correct month you need to add 1, so calling .getMonth() in may will return 4 and not 5
                var tahun = currentdate.getFullYear();

                var tglstart = currentdate.getFullYear() + '-' + '01' + '-' + '01';
                var tglend = currentdate.getFullYear() + '-' + ('0' + (currentdate.getMonth()+1)).slice(-2) + '-' + ('0' + currentdate.getDate()).slice(-2);
            }

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            data = {tglstart: tglstart, tglend: tglend};
            $.ajax({
                type:'POST',
                url:'/Produksi/Informasi/LeadTime/report1',
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
                        columnsAutoWidth: true,
                        columnAutoWidth: true,
                        columnMinWidth: 100,
                        height: 500,
                        scrollX: true,
                        scrollY: true,
                        allowColumnReordering: true,
                        allowColumnResizing: true,
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
                            fileName: "Report1",
                            allowExportSelectedData: true,
                            // formats: ['xlsx', 'pdf', 'csv'],
                        },        
                        columns: [
                            {
                                dataField: "KATEGORI", dataType: "string", caption: "Kategori", cssClass: "cls",
                            },                     
                            {
                                dataField: "PCS", dataType: "number", caption: "Pcs", cssClass: "cls", format: '#,##0',
                            },        
                            {
                                dataField: "WEIGHT", dataType: "number", caption: "Berat (Gr)", cssClass: "cls", format: '#,##0.00',
                            },  
                            {
                                dataField: "BULAN", dataType: "string", caption: "Bulan", cssClass: "cls",
                            }, 
                            {
                                dataField: "TAHUN", dataType: "string", caption: "Tahun", cssClass: "cls",
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

                    // $('#chart2').empty();
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

        function report2(){ //OK
            var tglstart = $("#tglstart").val();
            var tglend = $("#tglend").val();

            if (tglstart == "" || tglend == "") {
                // Show Warning Message
                // Swal.fire({
                //     icon: "error",
                //     title: "Harap Isi Tgl",
                //     showCancelButton: false,
                //     showConfirmButton: true
                // });

                // Get Default tglstart & tglend
                var currentdate = new Date(); 
                
                var tgl = currentdate.getDate();
                var bulan = currentdate.getMonth()+1; //.getMonth() returns a zero-based number so to get the correct month you need to add 1, so calling .getMonth() in may will return 4 and not 5
                var tahun = currentdate.getFullYear();

                var tglstart = currentdate.getFullYear() + '-' + '01' + '-' + '01';
                var tglend = currentdate.getFullYear() + '-' + ('0' + (currentdate.getMonth()+1)).slice(-2) + '-' + ('0' + currentdate.getDate()).slice(-2);
            }

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            data = {tglstart: tglstart, tglend: tglend};
            $.ajax({
                type:'POST',
                url:'/Produksi/Informasi/LeadTime/report2',
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
                        columnsAutoWidth: true,
                        columnAutoWidth: true,
                        columnMinWidth: 100,
                        height: 500,
                        scrollX: true,
                        scrollY: true,
                        allowColumnReordering: true,
                        allowColumnResizing: true,
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
                            fileName: "Report2",
                            allowExportSelectedData: true
                        },        
                        columns: [
                            {
                                dataField: "EMPLOYEENAME", dataType: "string", caption: "Operator", cssClass: "cls"
                            },                     
                            {
                                dataField: "TARGETQTY", dataType: "number", caption: "Qty SPKO", cssClass: "cls", format: '#,##0'
                            },        
                            {
                                dataField: "COMPLETIONQTY", dataType: "number", caption: "Qty NTHKO", cssClass: "cls", format: '#,##0'
                            },  
                            {
                                dataField: "PERSEN", dataType: "number", caption: "Rata2", cssClass: "cls", format: '#,##0.00'
                            }, 
                            {
                                dataField: "BULAN", dataType: "string", caption: "Bulan", cssClass: "cls"
                            }, 
                            {
                                dataField: "TAHUN", dataType: "string", caption: "Tahun", cssClass: "cls"
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

        function report3(){ //PENDING
            var tglstart = $("#tglstart").val();
            var tglend = $("#tglend").val();

            if (tglstart == "" || tglend == "") {
                // Show Warning Message
                // Swal.fire({
                //     icon: "error",
                //     title: "Harap Isi Tgl",
                //     showCancelButton: false,
                //     showConfirmButton: true
                // });

                // Get Default tglstart & tglend
                var currentdate = new Date(); 
                
                var tgl = currentdate.getDate();
                var bulan = currentdate.getMonth()+1; //.getMonth() returns a zero-based number so to get the correct month you need to add 1, so calling .getMonth() in may will return 4 and not 5
                var tahun = currentdate.getFullYear();

                var tglstart = currentdate.getFullYear() + '-' + '01' + '-' + '01';
                var tglend = currentdate.getFullYear() + '-' + ('0' + (currentdate.getMonth()+1)).slice(-2) + '-' + ('0' + currentdate.getDate()).slice(-2);
            }

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            data = {tglstart: tglstart, tglend: tglend};
            $.ajax({
                type:'POST',
                url:'/Produksi/Informasi/LeadTime/report2',
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
                        columnsAutoWidth: true,
                        columnAutoWidth: true,
                        columnMinWidth: 100,
                        height: 500,
                        scrollX: true,
                        scrollY: true,
                        allowColumnReordering: true,
                        allowColumnResizing: true,
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
                            fileName: "Report3",
                            allowExportSelectedData: true
                        },        
                        columns: [
                            {
                                dataField: "EMPLOYEENAME", dataType: "string", caption: "Operator", cssClass: "cls"
                            },                     
                            {
                                dataField: "TARGETQTY", dataType: "number", caption: "Qty SPKO", cssClass: "cls", format: '#,##0'
                            },        
                            {
                                dataField: "COMPLETIONQTY", dataType: "number", caption: "Qty NTHKO", cssClass: "cls", format: '#,##0'
                            },  
                            {
                                dataField: "PERSEN", dataType: "number", caption: "Rata2", cssClass: "cls", format: '#,##0.00'
                            }, 
                            {
                                dataField: "BULAN", dataType: "string", caption: "Bulan", cssClass: "cls"
                            }, 
                            {
                                dataField: "TAHUN", dataType: "string", caption: "Tahun", cssClass: "cls"
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

        function report4(){ //OK
            var tglstart = $("#tglstart").val();
            var tglend = $("#tglend").val();

            if (tglstart == "" || tglend == "") {
                // Show Warning Message
                // Swal.fire({
                //     icon: "error",
                //     title: "Harap Isi Tgl",
                //     showCancelButton: false,
                //     showConfirmButton: true
                // });

                // Get Default tglstart & tglend
                var currentdate = new Date(); 
                
                var tgl = currentdate.getDate();
                var bulan = currentdate.getMonth()+1; //.getMonth() returns a zero-based number so to get the correct month you need to add 1, so calling .getMonth() in may will return 4 and not 5
                var tahun = currentdate.getFullYear();

                var tglstart = currentdate.getFullYear() + '-' + '01' + '-' + '01';
                var tglend = currentdate.getFullYear() + '-' + ('0' + (currentdate.getMonth()+1)).slice(-2) + '-' + ('0' + currentdate.getDate()).slice(-2);
            }

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            data = {tglstart: tglstart, tglend: tglend};
            $.ajax({
                type:'POST',
                url:'/Produksi/Informasi/LeadTime/report4',
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
                        columnsAutoWidth: true,
                        columnAutoWidth: true,
                        columnMinWidth: 100,
                        height: 500,
                        scrollX: true,
                        scrollY: true,
                        allowColumnReordering: true,
                        allowColumnResizing: true,
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
                            fileName: "Report4",
                            allowExportSelectedData: true
                        },        
                        columns: [
                            {
                                dataField: "EMPLOYEENAME", dataType: "string", caption: "Operator", cssClass: "cls"
                            },      
                            {
                                dataField: "YEAR", dataType: "string", caption: "Tahun", cssClass: "cls"
                            }, 	
                            {
                                dataField: "MONTH", dataType: "string", caption: "Bulan", cssClass: "cls"
                            }, 
                            {
                                dataField: "DATE", dataType: "string", caption: "Tanggal", cssClass: "cls"
                            }, 	               
                            {
                                dataField: "TARGETQTY", dataType: "number", caption: "Qty SPKO", cssClass: "cls", format: '#,##0'
                            },        
                            {
                                dataField: "COMPLETIONQTY", dataType: "number", caption: "Qty NTHKO", cssClass: "cls", format: '#,##0'
                            },  
                            {
                                dataField: "PERSEN", dataType: "number", caption: "Rata2", cssClass: "cls", format: '#,##0.00'
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

        function report5() { //OK
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
            }else{

                if (tglstart == "" || tglend == "") {
                    // Show Warning Message
                    // Swal.fire({
                    //     icon: "error",
                    //     title: "Harap Isi Tgl",
                    //     showCancelButton: false,
                    //     showConfirmButton: true
                    // });

                    // Get Default tglstart & tglend
                    var currentdate = new Date(); 
                    
                    var tgl = currentdate.getDate();
                    var bulan = currentdate.getMonth()+1; //.getMonth() returns a zero-based number so to get the correct month you need to add 1, so calling .getMonth() in may will return 4 and not 5
                    var tahun = currentdate.getFullYear();

                    var tglstart = currentdate.getFullYear() + '-' + '01' + '-' + '01';
                    var tglend = currentdate.getFullYear() + '-' + ('0' + (currentdate.getMonth()+1)).slice(-2) + '-' + ('0' + currentdate.getDate()).slice(-2);
                }

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                data = {tglstart: tglstart, tglend: tglend, operation: operation};
                $.ajax({
                    type:'POST',
                    url:'/Produksi/Informasi/LeadTime/report5',
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
                            columnsAutoWidth: true,
                            columnAutoWidth: true,
                            columnMinWidth: 100,
                            height: 500,
                            scrollX: true,
                            scrollY: true,
                            allowColumnReordering: true,
                            allowColumnResizing: true,
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
                                fileName: "Report5",
                                allowExportSelectedData: true
                            },        
                            columns: [
                                {
                                    dataField: "SPKO", dataType: "string", caption: "SPKO", cssClass: "cls"
                                },  
                                {
                                    dataField: "TGLSPKO", dataType: "date", format: 'dd-MM-yyyy', caption: "Tgl SPKO", cssClass: "cls"
                                },                      
                                {
                                    dataField: "CARAT", dataType: "string", caption: "Kadar", cssClass: "cls"
                                },        
                                {
                                    dataField: "OPERATIONAPP", dataType: "string", caption: "Operation", cssClass: "cls"
                                },  
                                {
                                    dataField: "EMPLOYEE", dataType: "string", caption: "Operator", cssClass: "cls"
                                },
                                {
                                    dataField: "FG", dataType: "string", caption: "FinishGood", cssClass: "cls"
                                }, 
                                {
                                    dataField: "SUBKATEGORI", dataType: "string", caption: "SubKategori", cssClass: "cls"
                                },   	
                                {
                                    dataField: "KATEGORI", dataType: "string", caption: "Kategori", cssClass: "cls"
                                }, 
                                {
                                    dataField: "PCSSPKO", dataType: "number", caption: "Pcs", cssClass: "cls"
                                }, 
                                {
                                    dataField: "WAKTUMULAI", dataType: "date", caption: "Waktu Mulai Kerja", cssClass: "cls", format: "dd/MM/yyyy HH:mm:ss"
                                }, 
                                {
                                    dataField: "WAKTUSELESAI", dataType: "date", caption: "Waktu Selesai Kerja", cssClass: "cls", format: "dd/MM/yyyy HH:mm:ss"
                                }, 
                                {
                                    dataField: "TOTALSECONDS", dataType: "number", caption: "TotalTime (Detik)", cssClass: "cls"
                                }, 
                                {
                                    dataField: "AVGTIME", dataType: "number", caption: "AvgTime (Detik)", cssClass: "cls"
                                }, 
                                {
                                    dataField: "WORKHOURPERCENT", dataType: "number", caption: "WorkHour (Persen)", cssClass: "cls"
                                }, 
                                {
                                    dataField: "MONTH", dataType: "string", caption: "Bulan", cssClass: "cls"
                                }, 
                                {
                                    dataField: "YEAR", dataType: "string", caption: "Tahun", cssClass: "cls"
                                }, 	               
                                // {
                                //     dataField: "TGLSPKO",
                                //     format: 'dd-MM-yyyy',
                                //     groupIndex: 0
                                // }				          	          		           		          		                                                                                        
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

        function report6() { //OK
            var tglstart = $("#tglstart").val();
            var tglend = $("#tglend").val();
            var operation = $("#tglend").val();

            if (operation == "") {
                Swal.fire({
                    icon: "error",
                    title: "Harap Isi Operation",
                    showCancelButton: false,
                    showConfirmButton: true
                });
            }else{

                if (tglstart == "" || tglend == "") {
                    // Show Warning Message
                    // Swal.fire({
                    //     icon: "error",
                    //     title: "Harap Isi Tgl",
                    //     showCancelButton: false,
                    //     showConfirmButton: true
                    // });

                    // Get Default tglstart & tglend
                    var currentdate = new Date(); 
                    
                    var tgl = currentdate.getDate();
                    var bulan = currentdate.getMonth()+1; //.getMonth() returns a zero-based number so to get the correct month you need to add 1, so calling .getMonth() in may will return 4 and not 5
                    var tahun = currentdate.getFullYear();

                    var tglstart = currentdate.getFullYear() + '-' + '01' + '-' + '01';
                    var tglend = currentdate.getFullYear() + '-' + ('0' + (currentdate.getMonth()+1)).slice(-2) + '-' + ('0' + currentdate.getDate()).slice(-2);
                }

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                data = {tglstart: tglstart, tglend: tglend, operation: operation};
                $.ajax({
                    type:'POST',
                    url:'/Produksi/Informasi/LeadTime/report6',
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
                            columnsAutoWidth: true,
                            columnAutoWidth: true,
                            columnMinWidth: 100,
                            height: 500,
                            scrollX: true,
                            scrollY: true,
                            allowColumnReordering: true,
                            allowColumnResizing: true,
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
                                fileName: "Report6",
                                allowExportSelectedData: true
                            },        
                            columns: [
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
                                    dataField: "MasterCycleTime", dataType: "number", caption: "Master Cycle Time (Detik)", cssClass: "cls", format: '#,##0.00',
                                }, 		
                                {
                                    dataField: "MIN", dataType: "number", caption: "Avg Tercepat (Detik)", cssClass: "cls"
                                }, 	
                                {
                                    dataField: "MAX", dataType: "number", caption: "Avg Terlambat (Detik)", cssClass: "cls"
                                }, 
                                {
                                    dataField: "MasterCycleTimeRound", dataType: "number", caption: "Master Cycle Time (Pembulatan) (Detik)", cssClass: "cls",
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

                        chart8(data.arrLeadTime, data.arrSubKategori);
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

        function report7() { //OK
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
            }else{

                if (tglstart == "" || tglend == "") {
                    // Show Warning Message
                    // Swal.fire({
                    //     icon: "error",
                    //     title: "Harap Isi Tgl",
                    //     showCancelButton: false,
                    //     showConfirmButton: true
                    // });

                    // Get Default tglstart & tglend
                    var currentdate = new Date(); 
                    
                    var tgl = currentdate.getDate();
                    var bulan = currentdate.getMonth()+1; //.getMonth() returns a zero-based number so to get the correct month you need to add 1, so calling .getMonth() in may will return 4 and not 5
                    var tahun = currentdate.getFullYear();

                    var tglstart = currentdate.getFullYear() + '-' + '01' + '-' + '01';
                    var tglend = currentdate.getFullYear() + '-' + ('0' + (currentdate.getMonth()+1)).slice(-2) + '-' + ('0' + currentdate.getDate()).slice(-2);
                }

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                data = {tglstart: tglstart, tglend: tglend, operation: operation};
                $.ajax({
                    type:'POST',
                    url:'/Produksi/Informasi/LeadTime/report7',
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
                            columnsAutoWidth: true,
                            columnAutoWidth: true,
                            columnMinWidth: 100,
                            height: 500,
                            scrollX: true,
                            scrollY: true,
                            allowColumnReordering: true,
                            allowColumnResizing: true,
                            showBorders: true,
                            headerFilter: { visible: true },
                            rowAlternationEnabled : true,
                            // scrolling: {
                            //     columnRenderingMode: 'virtual',
                            //     showScrollbar = 'always',
                            // },
                            // autoWidth: true,
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
                                fileName: "Report5",
                                allowExportSelectedData: true
                            },        
                            columns: [
                                {
                                    dataField: "SPKO", dataType: "string", caption: "SPKO", cssClass: "cls"
                                },  
                                {
                                    dataField: "TGLSPKO", dataType: "date", format: 'dd-MM-yyyy', caption: "Tgl SPKO", cssClass: "cls"
                                },                      
                                {
                                    dataField: "CARAT", dataType: "string", caption: "Kadar", cssClass: "cls"
                                },        
                                {
                                    dataField: "OPERATIONAPP", dataType: "string", caption: "Operation", cssClass: "cls"
                                },  
                                {
                                    dataField: "EMPLOYEE", dataType: "string", caption: "Operator", cssClass: "cls"
                                },
                                {
                                    dataField: "FG", dataType: "string", caption: "FinishGood", cssClass: "cls"
                                }, 
                                {
                                    dataField: "SUBKATEGORI", dataType: "string", caption: "SubKategori", cssClass: "cls"
                                },   	
                                {
                                    dataField: "KATEGORI", dataType: "string", caption: "Kategori", cssClass: "cls"
                                }, 
                                {
                                    dataField: "PCSSPKO", dataType: "number", caption: "Pcs", cssClass: "cls"
                                }, 
                                {
                                    dataField: "WAKTUMULAI", dataType: "date", caption: "Waktu Mulai Kerja", cssClass: "cls", format: "dd/MM/yyyy HH:mm:ss"
                                }, 
                                {
                                    dataField: "WAKTUSELESAI", dataType: "date", caption: "Waktu Selesai Kerja", cssClass: "cls", format: "dd/MM/yyyy HH:mm:ss"
                                }, 
                                {
                                    dataField: "TOTALSECONDS", dataType: "number", caption: "TotalTime (Detik)", cssClass: "cls"
                                }, 
                                {
                                    dataField: "AVGTIME", dataType: "number", caption: "AvgTime (Detik)", cssClass: "cls"
                                }, 
                                {
                                    dataField: "WORKHOURPERCENT", dataType: "number", caption: "WorkHour (Persen)", cssClass: "cls"
                                }, 
                                {
                                    dataField: "MONTH", dataType: "string", caption: "Bulan", cssClass: "cls"
                                }, 
                                {
                                    dataField: "YEAR", dataType: "string", caption: "Tahun", cssClass: "cls"
                                }, 	               
                                // {
                                //     dataField: "TGLSPKO",
                                //     format: 'dd-MM-yyyy',
                                //     groupIndex: 0
                                // }				          	          		           		          		                                                                                        
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

        function report8() { //OK
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
            }else{

                if (tglstart == "" || tglend == "") {
                    // Show Warning Message
                    // Swal.fire({
                    //     icon: "error",
                    //     title: "Harap Isi Tgl",
                    //     showCancelButton: false,
                    //     showConfirmButton: true
                    // });

                    // Get Default tglstart & tglend
                    var currentdate = new Date(); 
                    
                    var tgl = currentdate.getDate();
                    var bulan = currentdate.getMonth()+1; //.getMonth() returns a zero-based number so to get the correct month you need to add 1, so calling .getMonth() in may will return 4 and not 5
                    var tahun = currentdate.getFullYear();

                    var tglstart = currentdate.getFullYear() + '-' + '01' + '-' + '01';
                    var tglend = currentdate.getFullYear() + '-' + ('0' + (currentdate.getMonth()+1)).slice(-2) + '-' + ('0' + currentdate.getDate()).slice(-2);
                }

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                data = {tglstart: tglstart, tglend: tglend, operation: operation};
                $.ajax({
                    type:'POST',
                    url:'/Produksi/Informasi/LeadTime/report8',
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
                            columnsAutoWidth: true,
                            columnAutoWidth: true,
                            columnMinWidth: 100,
                            height: 500,
                            scrollX: true,
                            scrollY: true,
                            allowColumnReordering: true,
                            allowColumnResizing: true,
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
                                fileName: "Report6",
                                allowExportSelectedData: true
                            },        
                            columns: [
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
                                    dataField: "MasterCycleTime", dataType: "number", caption: "Master Cycle Time (Detik)", cssClass: "cls", format: '#,##0.00',
                                }, 	
                                {
                                    dataField: "MIN", dataType: "number", caption: "Avg Tercepat (Detik)", cssClass: "cls"
                                }, 	
                                {
                                    dataField: "MAX", dataType: "number", caption: "Avg Terlambat (Detik)", cssClass: "cls"
                                }, 
                                {
                                    dataField: "MasterCycleTimeRound", dataType: "number", caption: "Master Cycle Time (Pembulatan) (Detik)", cssClass: "cls",
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

                        chart8(data.arrLeadTime, data.arrSubKategori);
                        // document.getElementById("btnchart").disabled = false; 
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

    </script>
@endsection
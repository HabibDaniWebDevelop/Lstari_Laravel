<?php $title = 'Stok Akhir Bulan'; ?>
<?php $tab='1'; ?>
@extends('layouts.backend-Theme-3.app')
@section('title', '$title')

@section('Dashboard')
    <h2 class="m-0">{{ $title }}</h2>
    <ol class="breadcrumb sm-2">
        <li class="breadcrumb-item"><a href="/">Home </a></li>
        <li class="breadcrumb-item">Akunting </li>
        <li class="breadcrumb-item">Informasi </li>
        <li class="breadcrumb-item active">Stok Akhir Bulan </li>
    </ol>
@endsection

@section('css')
   <style>

        .btn-outline-dark{
            display:inline-block;
            float:right;
            margin-top:28px;
        }

        .btn-primary{
            display:inline-block;
            margin-top:28px;
        }

        .btn-outline-dark{
            /* position: absolute;  */
            margin-top:0px;
            margin-bottom:15px;
          
            /* float: right; */

            /* margin-top:-20px;
            margin-bottom:20px; */
            /* overflow: hidden; */
            }
        /* #tampil{
            margin-top:5px;
        } */
    </style>
@endsection

@section('container')
<div class="row mb-4">
    <div class="col-md-12">
      <ul class="nav nav-pills flex-column flex-md-row mb-3" >
            <li class="nav-item">
            <a class="nav-link btn-tab1 {{ ($tab === "1") ? 'active':' ' }}" id="idtab1" data-bs-toggle="tab" href="javascript:void(0);" ><i class="fas fa-chart-bar"></i> Rekap Bulanan</a>
            </li>
            <li class="nav-item">
            <a class="nav-link btn-tab2 {{ ($tab === "2") ? 'active':' ' }}" id="idtab2" data-bs-toggle="tab" href="#" onclick="show2()"><i class="fas fa-chart-line"></i> Stock Opname</a>
            </li>
      </ul> 
        <div class="row" id="dailystock">
        </div>
        <div class="row" id="stockopname" style="display:none;">
        </div>
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

        // function tabDailyStock() {
        //     // $.get("{{ url('formDaily') }}", function(data) {
        //     // $("#dailystock").html(data);
        //     // });
        // }
        // function tabStockOpname() {
        //     $.get("{{ url('stockopname') }}", function(data) {
        //     $("#tab2").html(data);
        //     });
        // }
       

        function show1(){
            $.ajax({
                    url: '/Akunting/Informasi/StokAkhirBulan/formDaily',            
                    dataType : 'json',
                    type : 'GET',
                    success: function(data)
                    {
                        //console.log(data.html);
                        $("#dailystock").html(data.html);
                        document.getElementById("dailystock").style.display = "block";
                        document.getElementById("stockopname").style.display = "none";
                    },
                });
        }

        function show2(){
            $.ajax({
                    url: '/Akunting/Informasi/StokAkhirBulan/formOpname',            
                    dataType : 'json',
                    type : 'GET',
                    success: function(data)
                    {
                        //console.log(data.html);
                        $("#stockopname").html(data.html);
                        document.getElementById("stockopname").style.display = "block";
                        document.getElementById("dailystock").style.display = "none";
                    },
                });
        }

      

        // $("#tahunnya2").datepicker({
        //     onSelect: setyear,
        //     format: "yyyy",
        //     viewMode: "years", 
        //     minViewMode: "years",
        //     autoclose:true
            
        // });  

        // $("#tahunnya").blur(function (event){
        //     setyear();
        // });
      

        // $('#tahunnya2').on('changeDate', function (e) {
        //     setyear2();
        // });
            

        function openModal(){
            $(".preloader").fadeIn(300);
        }

        function closeModal(){
            $(".preloader").fadeOut(300);
        }

       

        // function setyear2(){
        //     var thn = $("#tahunnya2").val();
        //     var data = {thn:thn};
        //     $.ajaxSetup({
        //             headers: {
        //                 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        //             }
        //         });
        //     $.ajax({
        //             url: '/Akunting/Informasi/StokAkhirBulan/setYear2',            
        //             dataType : 'json',
        //             type : 'GET',
        //             data:data,
        //             success: function(data)
        //             {
        //                 //console.log(data.html);
        //                 $("#options2").html(data.html);
        //                 document.getElementById("selectone2").style.display = "none";
        //                 document.getElementById("options2").style.display = "block";
                       
        //             },
                                
        //         });
        // }

        

        function gettingStokAkhirBulan(){
            var bln = $("#bulannya").val();
            var thn = $("#tahunnya").val();
            var data = {bln:bln};

            if(thn == 0){
                alert('Harap pilih Tahun terlebih dahulu !');
            }else if(bln == 0){
                alert('Harap pilih Bulan terlebih dahulu !');
            }else{
                //alert(bln);
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                $.ajax({
                    url: '/Akunting/Informasi/StokAkhirBulan/gettingStokAkhirBulan', 
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
                        var tabelstok =  $('#tabelstok').DataTable({
                            "paging": false,
                            "searching": false,
                            "ordering": true,
                            "info": false,
                            "autoWidth": true,
                            "responsive": true,
                            "fixedColumns": true,
                            "scrollCollapse": true,
                            // "fixedHeader": true,
                            "fixedHeader": {
                                header: true,
                                footer: true
                            },
                            "scrollX": true,
                            "scrollY": 400,
                            //"footer": true,
                            "bPaginate": true,
                            "bLengthChange": true,
                            "fixedHeader": {
                                header: true,
                                headerOffset: 1000,
                            },
                            "fixedColumns": {
                                 leftColumns: 1,
                                 rightColumns: 1
                            },
                            fnInitComplete: function(){
                                $('.dataTables_scrollBody').css({'overflow': 'hidden','border':'0'});
                                $('.dataTables_scrollFoot').css('overflow', 'auto');
                                $('.dataTables_scrollFoot').on('scroll', function () {
                                    $('.dataTables_scrollBody').scrollLeft($(this).scrollLeft());
                                });
                            },
                            drawCallback: function( settings ) {
                                setTimeout(function(){$('.DTFC_LeftBodyWrapper, .DTFC_LeftBodyLiner, .DTFC_RightBodyWrapper, .DTFC_RightBodyLiner').height($('.dataTables_scrollBody').height());},0);
                            },
                            dom: 'Bfrtip',
                            buttons: [
                                        {
                                        extend:    'excelHtml5', 
                                        footer: true,
                                        text:      '<i class="tf-icons bx bx-file"></i> Excel',
                                        titleAttr: 'Excel',
                                        className: 'btn btn-outline-dark',
                                        exportOptions: {
                                            columns: ':visible'
                                        }
                                        },
                                        
                                    ]
                            // buttons: [   
                            //     { extend: 'excelHtml5', footer: true },
                            //     // { extend: 'csvHtml5', footer: true },
                            //     // { extend: 'pdfHtml5', footer: true }
                            // ]
                        });

                        var tabelstokcarat =  $('#tabelstokothercarat').DataTable({
                            "paging": false,
                            "searching": false,
                            "ordering": true,
                            "info": false,
                            "autoWidth": true,
                            "responsive": true,
                            "fixedColumns": true,
                            "scrollCollapse": true,
                            // "fixedHeader": true,
                            "fixedHeader": {
                                header: true,
                                footer: true
                            },
                            "scrollX": true,
                            "scrollY": 400,
                            //"footer": true,
                            "bPaginate": true,
                            "bLengthChange": true,
                            "fixedHeader": {
                                header: true,
                                headerOffset: 1000,
                            },
                            "fixedColumns": {
                                 leftColumns: 1,
                                 rightColumns: 1
                            },
                            fnInitComplete: function(){
                                $('.dataTables_scrollBody').css({'overflow': 'hidden','border':'0'});
                                $('.dataTables_scrollFoot').css('overflow', 'auto');
                                $('.dataTables_scrollFoot').on('scroll', function () {
                                    $('.dataTables_scrollBody').scrollLeft($(this).scrollLeft());
                                });
                            },
                            drawCallback: function( settings ) {
                                setTimeout(function(){$('.DTFC_LeftBodyWrapper, .DTFC_LeftBodyLiner, .DTFC_RightBodyWrapper, .DTFC_RightBodyLiner').height($('.dataTables_scrollBody').height());},0);
                            },
                            // dom: 'Bfrtip',
                            // buttons: [
                            //             {
                            //             extend:    'excelHtml5', 
                            //             footer: true,
                            //             text:      '<i class="tf-icons bx bx-file"></i> Excel',
                            //             titleAttr: 'Excel',
                            //             className: 'btn btn-outline-dark',
                            //             exportOptions: {
                            //                 columns: ':visible'
                            //             }
                            //             },
                                        
                            //         ]
                            // buttons: [   
                            //     { extend: 'excelHtml5', footer: true },
                            //     // { extend: 'csvHtml5', footer: true },
                            //     // { extend: 'pdfHtml5', footer: true }
                            // ]
                        });
                        //tabelstok.buttons().container().appendTo('#export');
                        $(window).resize(function(){$('#tabelstok').DataTable().draw()});


                        // $('#tabelstok').DataTable({
                        //     "paging": false,
                        //     "lengthChange": false,
                        //     "searching": false,
                        //     "ordering": true,
                        //     "info": false,
                        //     "autoWidth": true,
                        //     "responsive": true,
                        //     "fixedColumns": true,
                        //     // dom: 'Bfrtip',
                        //     // buttons: ['excel', 'pdf', 'print', 'copy', 'csv']
                        // });

                        var tabelstokrongsok =  $('#tabelstokrongsok').DataTable({
                            "paging": false,
                            "searching": false,
                            "ordering": true,
                            "info": false,
                            "autoWidth": true,
                            "responsive": true,
                            "fixedColumns": true,
                            "scrollCollapse": true,
                            // "fixedHeader": true,
                            "fixedHeader": {
                                header: true,
                                footer: true
                            },
                            "scrollX": true,
                            "scrollY": 400,
                            //"footer": true,
                            "bPaginate": true,
                            "bLengthChange": true,
                            "fixedHeader": {
                                header: true,
                                headerOffset: 1000,
                            },
                            "fixedColumns": {
                                 leftColumns: 1,
                                 rightColumns: 1
                            },
                            fnInitComplete: function(){
                                $('.dataTables_scrollBody').css({'overflow': 'hidden','border':'0'});
                                $('.dataTables_scrollFoot').css('overflow', 'auto');
                                $('.dataTables_scrollFoot').on('scroll', function () {
                                    $('.dataTables_scrollBody').scrollLeft($(this).scrollLeft());
                                });
                            },
                            drawCallback: function( settings ) {
                                setTimeout(function(){$('.DTFC_LeftBodyWrapper, .DTFC_LeftBodyLiner, .DTFC_RightBodyWrapper, .DTFC_RightBodyLiner').height($('.dataTables_scrollBody').height());},0);
                            },
                            dom: 'Bfrtip',
                            buttons: [
                                        {
                                        extend:    'excelHtml5', 
                                        footer: true,
                                        text:      '<i class="tf-icons bx bx-file"></i> Excel',
                                        titleAttr: 'Excel',
                                        className: 'btn btn-outline-dark',
                                        exportOptions: {
                                            columns: ':visible'
                                        }
                                        },
                                        
                                    ]
                            // buttons: [   
                            //     { extend: 'excelHtml5', footer: true },
                            //     // { extend: 'csvHtml5', footer: true },
                            //     // { extend: 'pdfHtml5', footer: true }
                            // ]
                        });

                        var tabelstokcaratrongsok =  $('#tabelstokothercaratrongsok').DataTable({
                            "paging": false,
                            "searching": false,
                            "ordering": true,
                            "info": false,
                            "autoWidth": true,
                            "responsive": true,
                            "fixedColumns": true,
                            "scrollCollapse": true,
                            // "fixedHeader": true,
                            "fixedHeader": {
                                header: true,
                                footer: true
                            },
                            "scrollX": true,
                            "scrollY": 400,
                            //"footer": true,
                            "bPaginate": true,
                            "bLengthChange": true,
                            "fixedHeader": {
                                header: true,
                                headerOffset: 1000,
                            },
                            "fixedColumns": {
                                 leftColumns: 1,
                                 rightColumns: 1
                            },
                            fnInitComplete: function(){
                                $('.dataTables_scrollBody').css({'overflow': 'hidden','border':'0'});
                                $('.dataTables_scrollFoot').css('overflow', 'auto');
                                $('.dataTables_scrollFoot').on('scroll', function () {
                                    $('.dataTables_scrollBody').scrollLeft($(this).scrollLeft());
                                });
                            },
                            drawCallback: function( settings ) {
                                setTimeout(function(){$('.DTFC_LeftBodyWrapper, .DTFC_LeftBodyLiner, .DTFC_RightBodyWrapper, .DTFC_RightBodyLiner').height($('.dataTables_scrollBody').height());},0);
                            },
                            // dom: 'Bfrtip',
                            // buttons: [
                            //             {
                            //             extend:    'excelHtml5', 
                            //             footer: true,
                            //             text:      '<i class="tf-icons bx bx-file"></i> Excel',
                            //             titleAttr: 'Excel',
                            //             className: 'btn btn-outline-dark',
                            //             exportOptions: {
                            //                 columns: ':visible'
                            //             }
                            //             },
                                        
                            //         ]
                            // buttons: [   
                            //     { extend: 'excelHtml5', footer: true },
                            //     // { extend: 'csvHtml5', footer: true },
                            //     // { extend: 'pdfHtml5', footer: true }
                            // ]
                        });
                        //tabelstok.buttons().container().appendTo('#export');
                        $(window).resize(function(){$('#tabelstokrongsok').DataTable().draw()});

                        // $('#tabelstok').DataTable({
                        //     "paging": false,
                        //     "lengthChange": false,
                        //     "searching": false,
                        //     "ordering": true,
                        //     "info": false,
                        //     "autoWidth": true,
                        //     "responsive": true,
                        //     "fixedColumns": true,
                        //     // dom: 'Bfrtip',
                        //     // buttons: ['excel', 'pdf', 'print', 'copy', 'csv']
                        // });
                               
                    },
                    complete: function(){
                        closeModal();
                    },             
                }); 
            }
        }




        function gettingStokAkhirBulanOpname(){
            var bln = $("#bulannya2").val();
            var thn = $("#tahunnya2").val();
            var data = {bln:bln, thn:thn};

            if(thn == 0){
                alert('Harap pilih Tahun terlebih dahulu !');
            }else if(bln == 0){
                alert('Harap pilih Bulan terlebih dahulu !');
            }else{
                //alert(bln);
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                $.ajax({
                    url: '/Akunting/Informasi/StokAkhirBulan/gettingStokAkhirBulanOpname', 
                    beforeSend: function(){
                        openModal();
                    },              
                    dataType : 'json',
                    type : 'GET',
                    data:data,
                    success: function(data)
                    {
                        // console.log(data.status);
                        $("#tampil2").html(data.html);
                        var tabelstok =  $('#tabelstokopname').DataTable({
                            "paging": false,
                            "searching": false,
                            "ordering": true,
                            "info": false,
                            "autoWidth": true,
                            "responsive": true,
                            "fixedColumns": true,
                            "scrollCollapse": true,
                            // "fixedHeader": true,
                            "fixedHeader": {
                                header: true,
                                footer: true
                            },
                            "scrollX": true,
                            "scrollY": 400,
                            // "footer": true,
                            "bPaginate": true,
                            "bLengthChange": true,
                            "fixedHeader": {
                                header: true,
                                headerOffset: 1000,
                            },
                            "fixedColumns": {
                                 leftColumns: 1,
                                 rightColumns: 1
                            },
                            fnInitComplete: function(){
                                $('.dataTables_scrollBody').css({'overflow': 'hidden','border':'0'});
                                $('.dataTables_scrollFoot').css('overflow', 'auto');
                                $('.dataTables_scrollFoot').on('scroll', function () {
                                    $('.dataTables_scrollBody').scrollLeft($(this).scrollLeft());
                                });
                            },
                            drawCallback: function( settings ) {
                                setTimeout(function(){$('.DTFC_LeftBodyWrapper, .DTFC_LeftBodyLiner, .DTFC_RightBodyWrapper, .DTFC_RightBodyLiner').height($('.dataTables_scrollBody').height());},0);
                            },
                            dom: 'Bfrtip',
                            buttons: [
                                        {
                                        extend:    'excelHtml5', 
                                        footer: true,
                                        text:      '<i class="tf-icons bx bx-file"></i> Excel',
                                        titleAttr: 'Excel',
                                        className: 'btn btn-outline-dark',
                                        exportOptions: {
                                            columns: ':visible'
                                        }
                                        },
                                        
                                    ]
                            // buttons: [   
                            //     { extend: 'excelHtml5', footer: true },
                            //     // { extend: 'csvHtml5', footer: true },
                            //     // { extend: 'pdfHtml5', footer: true }
                            // ]
                        });
                    },
                    complete: function(){
                        closeModal();
                    },             
                }); 
            }
        }

    </script>
@endsection
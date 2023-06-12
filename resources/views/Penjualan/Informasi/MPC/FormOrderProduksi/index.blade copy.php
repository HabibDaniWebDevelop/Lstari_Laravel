<?php $title = 'Informasi Form Order Produksi'; ?>
<?php $tab='1'; ?>
@extends('layouts.backend-Theme-3.app')
@section('title', '$title')

@section('Dashboard')
    <h2 class="m-0">{{ $title }}</h2>
    <ol class="breadcrumb sm-2">
        <li class="breadcrumb-item"><a href="/">Home </a></li>
        <li class="breadcrumb-item">Penjualan </li>
        <li class="breadcrumb-item">Informasi </li>
        <li class="breadcrumb-item active">Form Order Produksi </li>
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
        <div class="row" id="dailystock">
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
                    url: '/Penjualan/Informasi/MPC/FormOrderProduksi/formOrder',            
                    dataType : 'json',
                    type : 'GET',
                    success: function(data)
                    {
                        //console.log(data.html);
                        $("#dailystock").html(data.html);
                        document.getElementById("dailystock").style.display = "block";
                        // document.getElementById("stockopname").style.display = "none";
                    },
                });
        }

        // function show2(){
        //     $.ajax({
        //             url: '/Akunting/Informasi/StokAkhirBulan/formOpname',            
        //             dataType : 'json',
        //             type : 'GET',
        //             success: function(data)
        //             {
        //                 //console.log(data.html);
        //                 $("#stockopname").html(data.html);
        //                 document.getElementById("stockopname").style.display = "block";
        //                 document.getElementById("dailystock").style.display = "none";
        //             },
        //         });
        // }

      

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

        function pilihjenis(){
            var jenis = $("#jenis").val();
            if(jenis == 1){
                document.getElementById("bulannya").disabled = false;
                document.getElementById("kategori").disabled = false;
                document.getElementById("kadar").disabled = false;
                document.getElementById("id1").disabled = true;
                document.getElementById("id2").disabled = true;
                document.getElementById("tanggal1").disabled = true;
                document.getElementById("tanggal2").disabled = true;
            }else{
                document.getElementById("bulannya").disabled = false;
                document.getElementById("kategori").disabled = true;
                document.getElementById("kadar").disabled = true;
                document.getElementById("id1").disabled = false;
                document.getElementById("id2").disabled = false;
                document.getElementById("tanggal1").disabled = false;
                document.getElementById("tanggal2").disabled = false;
            }
        }

        

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
                            "fixedColumns": true,
                            "scrollCollapse": true,
                            "fixedHeader": true,
                            "fixedHeader": {
                                header: true,
                                footer: true
                            },
                            // "scrollX": true,
                            // "scrollY": 500,
                            rowGroup: {
                                // Uses the 'row group' plugin
                                dataSrc: 34,
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


                                   
                                    var jumlah = rows.data().pluck(9).reduce( function (a, b) {return intVal(a) + intVal(b);}, 0);
                                    var jumlahenm = rows.data().pluck(10).reduce( function (a, b) {return intVal(a) + intVal(b);}, 0);
                                    var berat = rows.data().pluck(11).reduce( function (a, b) {return intVal(a) + intVal(b);}, 0);
                                    var brtpcs = rows.data().pluck(12).reduce( function (a, b) {return intVal(a) + intVal(b);}, 0);
                                    var batu = rows.data().pluck(13).reduce( function (a, b) {return intVal(a) + intVal(b);}, 0);
                                    var inject = rows.data().pluck(14).reduce( function (a, b) {return intVal(a) + intVal(b);}, 0);
                                    var poles = rows.data().pluck(15).reduce( function (a, b) {return intVal(a) + intVal(b);}, 0);
                                    var patri = rows.data().pluck(16).reduce( function (a, b) {return intVal(a) + intVal(b);}, 0);
                                    var puk = rows.data().pluck(17).reduce( function (a, b) {return intVal(a) + intVal(b);}, 0);
                                  
                                    
                                    // Add category name to the <tr>. NOTE: Hardcoded colspan
                                    return $('<tr style="background-color:#F9EDED;"/>')
                                        .append('<td style="text-align: center; color:#913030;"><b>ID : </b>' + group + '</td>')
                                        .append('<td></td>')
                                        .append('<td></td>')
                                        .append('<td></td>')
                                        .append('<td></td>')
                                        .append('<td></td>')
                                        .append('<td></td>')
                                        .append('<td style="text-align: center; color:#913030;"><b>' + rows.count() +'</b></td>')
                                        .append('<td></td>')
                                        .append(`<td  style="text-align: center; color:#913030;"><b>${$.fn.dataTable.render.number('.', ',', 0, '').display(jumlah)}<b></td>`)
                                        .append(`<td  style="text-align: center; color:#913030;"><b>${$.fn.dataTable.render.number('.', ',', 0, '').display(jumlahenm)}<b></td>`)
                                        .append(`<td  style="text-align: right; color:#913030;"><b>${$.fn.dataTable.render.number('.', ',', 2, '').display(berat)}<b></td>`)
                                        .append(`<td  style="text-align: right; color:#913030;"><b>${$.fn.dataTable.render.number('.', ',', 2, '').display(brtpcs)}<b></td>`)
                                        .append(`<td  style="text-align: center; color:#913030;"><b>${$.fn.dataTable.render.number('.', ',', 0, '').display(batu)}<b></td>`)
                                        .append(`<td  style="text-align: center; color:#913030;"><b>${$.fn.dataTable.render.number('.', ',', 0, '').display(inject)}<b></td>`)
                                        .append(`<td  style="text-align: center; color:#913030;"><b>${$.fn.dataTable.render.number('.', ',', 0, '').display(poles)}<b></td>`)
                                        .append(`<td  style="text-align: center; color:#913030;"><b>${$.fn.dataTable.render.number('.', ',', 0, '').display(patri)}<b></td>`)
                                        .append(`<td  style="text-align: center; color:#913030;"><b>${$.fn.dataTable.render.number('.', ',', 0, '').display(puk)}<b></td>`)
                                        .append('<td></td>')
                                        .append('<td></td>')
                                        .append('<td></td>')
                                        .append('<td></td>')
                                        .append('<td></td>')
                                        .append('<td></td>')
                                        .append('<td></td>')
                                        .append('<td></td>')
                                        .append('<td></td>')
                                        .append('<td></td>')
                                        .append('<td></td>')
                                        .append('<td></td>')
                                        .append('<td style="text-align: center; color:#913030;"><b>' + rows.count() +'</b></td>')
                                        .append('<td></td>')
                                        .append('<td></td>')
                                        .append('<td></td>')
                                        .append('<td></td>')
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
    }

    </script>
@endsection
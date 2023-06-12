<?php $title = 'Jadwal Kerja Harian'; ?>
@extends('layouts.backend-Theme-3.app')
@section('title', '$title')

@section('Dashboard')
    <h2 class="m-0">{{ $title }}</h2>
    <ol class="breadcrumb sm-2">
        <li class="breadcrumb-item"><a href="/">Home </a></li>
        <li class="breadcrumb-item">Produksi </li>
        <li class="breadcrumb-item">Informasi </li>
        <li class="breadcrumb-item active">Jadwal Kerja Harian </li>
    </ol>
@endsection

@section('css')
    <link rel="stylesheet" href="{!! asset('assets/almas/select2.min.css') !!}">
    <link rel="stylesheet" href="{!! asset('assets/sneatV1/assets/vendor/libs/BootstrapSelect/bootstrap-select.min.css') !!}">
    <style>

    </style>

@endsection

@section('container')
    <div class="row">
        <div class="col-md-12">
            <div class="card">

                @include('Produksi.Informasi.JadwalKerjaHarian.data')

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

    <script>

        function openModal(){
            $(".preloader").fadeIn(300);
        }

        function closeModal(){
            $(".preloader").fadeOut(300);
        }

        $(document).ready(function() {
            $('.myselect').select2();
        });

        // $(document).ready(function() {
        //     $('.myselect').selectpicker();
        // });

        function showInput(pilih){
            var pilih = pilih;
            if(pilih == ""){
                document.getElementById('rowinput').style.display = 'none';
            }else{
                if(pilih == 1){
                    document.getElementById('rowinput').style.display = 'block';
                    document.getElementById("tgl1").disabled = true; 
                    document.getElementById("tgl2").disabled = true; 
                    document.getElementById("rph").disabled = false; 
                    document.getElementById("kadar").disabled = false; 
                    document.getElementById("operation").disabled = false; 
                    document.getElementById("operator").disabled = false; 

                    document.getElementById("tgl1").value = ''; 
                    document.getElementById("tgl2").value = ''; 
                    document.getElementById("kadar").value = ''; 
                    document.getElementById("operation").value = ''; 
                    document.getElementById("operator").value = ''; 
                    
                }else if(pilih == 2){
                    document.getElementById('rowinput').style.display = 'block';
                    document.getElementById("tgl1").disabled = false; 
                    document.getElementById("tgl2").disabled = false; 
                    document.getElementById("rph").disabled = true; 
                    document.getElementById("kadar").disabled = false; 
                    document.getElementById("operation").disabled = false; 
                    document.getElementById("operator").disabled = false; 

                    document.getElementById("rph").value = ''; 
                    document.getElementById("kadar").value = ''; 
                    document.getElementById("operation").value = ''; 
                    document.getElementById("operator").value = ''; 

                }else if(pilih == 3){
                    document.getElementById('rowinput').style.display = 'block';
                    document.getElementById("tgl1").disabled = false; 
                    document.getElementById("tgl2").disabled = false; 
                    document.getElementById("rph").disabled = true; 
                    document.getElementById("kadar").disabled = false; 
                    document.getElementById("operation").disabled = false; 
                    document.getElementById("operator").disabled = false; 

                    document.getElementById("rph").value = ''; 
                    document.getElementById("kadar").value = ''; 
                    document.getElementById("operation").value = ''; 
                    document.getElementById("operator").value = ''; 

                }else if(pilih == 4){
                    document.getElementById('rowinput').style.display = 'block';
                    document.getElementById("tgl1").disabled = false; 
                    document.getElementById("tgl2").disabled = false; 
                    document.getElementById("rph").disabled = true; 
                    document.getElementById("kadar").disabled = false; 
                    document.getElementById("operation").disabled = false; 
                    document.getElementById("operator").disabled = false; 

                    document.getElementById("rph").value = ''; 
                    document.getElementById("kadar").value = ''; 
                    document.getElementById("operation").value = ''; 
                    document.getElementById("operator").value = ''; 
                    
                }else if(pilih == 7){
                    document.getElementById('rowinput').style.display = 'block';
                    document.getElementById("tgl1").disabled = false; 
                    document.getElementById("tgl2").disabled = false; 
                    document.getElementById("rph").disabled = true; 
                    document.getElementById("operator").disabled = false; 
                    document.getElementById("kadar").disabled = true; 
                    document.getElementById("kategori").disabled = true; 
                    document.getElementById("subkategori").disabled = true; 
                    document.getElementById("operation").disabled = true; 

                    document.getElementById("rph").value = ''; 
                    document.getElementById("operator").value = ''; 
                    document.getElementById("kadar").value = ''; 
                    document.getElementById("kategori").value = ''; 
                    document.getElementById("subkategori").value = ''; 
                    document.getElementById("operation").value = ''; 
                    
                }else if(pilih == 8){
                    document.getElementById('rowinput').style.display = 'block';
                    document.getElementById("tgl1").disabled = false; 
                    document.getElementById("tgl2").disabled = false; 
                    document.getElementById("rph").disabled = true; 
                    document.getElementById("operator").disabled = true; 
                    document.getElementById("kadar").disabled = false; 
                    document.getElementById("kategori").disabled = true; 
                    document.getElementById("subkategori").disabled = true; 
                    document.getElementById("operation").disabled = true; 

                    document.getElementById("rph").value = ''; 
                    document.getElementById("operator").value = ''; 
                    document.getElementById("kadar").value = ''; 
                    document.getElementById("kategori").value = ''; 
                    document.getElementById("subkategori").value = ''; 
                    document.getElementById("operation").value = ''; 
                    
                }else if(pilih == 9){
                    document.getElementById('rowinput').style.display = 'block';
                    document.getElementById("tgl1").disabled = false; 
                    document.getElementById("tgl2").disabled = false; 
                    document.getElementById("rph").disabled = true; 
                    document.getElementById("operator").disabled = true; 
                    document.getElementById("kadar").disabled = true; 
                    document.getElementById("kategori").disabled = false; 
                    document.getElementById("subkategori").disabled = true; 
                    document.getElementById("operation").disabled = true; 

                    document.getElementById("rph").value = ''; 
                    document.getElementById("operator").value = ''; 
                    document.getElementById("kadar").value = ''; 
                    document.getElementById("kategori").value = ''; 
                    document.getElementById("subkategori").value = ''; 
                    document.getElementById("operation").value = ''; 
                    
                }else if(pilih == 10){
                    document.getElementById('rowinput').style.display = 'block';
                    document.getElementById("tgl1").disabled = false; 
                    document.getElementById("tgl2").disabled = false; 
                    document.getElementById("rph").disabled = true; 
                    document.getElementById("operator").disabled = true; 
                    document.getElementById("kadar").disabled = true; 
                    document.getElementById("kategori").disabled = true; 
                    document.getElementById("subkategori").disabled = false; 
                    document.getElementById("operation").disabled = true; 

                    document.getElementById("rph").value = ''; 
                    document.getElementById("operator").value = ''; 
                    document.getElementById("kadar").value = ''; 
                    document.getElementById("kategori").value = ''; 
                    document.getElementById("subkategori").value = ''; 
                    document.getElementById("operation").value = ''; 
                    
                }else if(pilih == 11){
                    document.getElementById('rowinput').style.display = 'block';
                    document.getElementById("tgl1").disabled = false; 
                    document.getElementById("tgl2").disabled = false; 
                    document.getElementById("rph").disabled = true; 
                    document.getElementById("operator").disabled = true; 
                    document.getElementById("kadar").disabled = true; 
                    document.getElementById("kategori").disabled = true; 
                    document.getElementById("subkategori").disabled = true; 
                    document.getElementById("operation").disabled = false; 

                    document.getElementById("rph").value = ''; 
                    document.getElementById("operator").value = ''; 
                    document.getElementById("kadar").value = ''; 
                    document.getElementById("kategori").value = ''; 
                    document.getElementById("subkategori").value = ''; 
                    document.getElementById("operation").value = ''; 
                    
                }else{
                    document.getElementById('rowinput').style.display = 'block';
                    document.getElementById("tgl1").disabled = false; 
                    document.getElementById("tgl2").disabled = false; 
                    document.getElementById("rph").disabled = true; 
                    document.getElementById("kadar").disabled = false; 
                    document.getElementById("operation").disabled = false; 
                    document.getElementById("operator").disabled = false; 

                    document.getElementById("rph").value = ''; 
                    document.getElementById("kadar").value = ''; 
                    document.getElementById("operation").value = ''; 
                    document.getElementById("operator").value = ''; 
                }
            }
        }

        // function klikArea(){
        //     var area = $("#area").val();
        //     var dataUrl = '/Produksi/Informasi/JadwalKerjaHarian/tampilArea';

        //     $.ajaxSetup({
        //         headers: {
        //             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        //         }
        //     });

        //     data = {area: area};
        //     $.ajax({
        //         url: dataUrl,
        //         beforeSend: function(){
        //             openModal();
        //         },
        //         data : data,
        //         dataType : 'json',
        //         type : 'POST',
        //         success: function(data)
        //         {
        //             // $("#tampil").html(data.html);
        //         },
        //         complete: function(){
        //             closeModal();
        //         },
        //     });
        // }

        function klikLaporanRPH(){
            var jenis = $("#jenis").val();
            var rph = $("#rph").val();
            var kadar = $("#kadar").val();
            var operation = $("#operation").val();
            var operator = $("#operator").val();
            var tglstart = $("#tgl1").val();
            var tglend = $("#tgl2").val();
            var kategori = $("#kategori").val();
            var subkategori = $("#subkategori").val();

            if(tglstart == "" || tglend == ""){
                alert("Harap Isi Tgl Dulu!");
            }else{

                if(jenis == 1){
                    var dataUrl = '/Produksi/Informasi/JadwalKerjaHarian/reportPerRPH';
                    // var dataOrderGroup = [[1, 'asc']];
                    var dataSrcGroup = 2;
                    var dataRowGroup = '<td colspan="20"><b>RPH : </b>';
                }else if(jenis == 2){
                    var dataUrl = '/Produksi/Informasi/JadwalKerjaHarian/reportPerTgl';
                    var dataSrcGroup = 2;
                    // var dataOrderGroup = [[2, 'asc'], [1, 'asc']];
                    var dataRowGroup = '<td colspan="19"><b>RPH : </b>';
                }else if(jenis == 3){
                    var dataUrl = '/Produksi/Informasi/JadwalKerjaHarian/reportPerTglPercent';
                    var dataSrcGroup = 2;
                    // var dataOrderGroup = [[2, 'asc'], [1, 'asc']];
                    var dataRowGroup = '<td colspan="8"><b>RPH : </b>';
                }else if(jenis == 4){
                    var dataUrl = '/Produksi/Informasi/JadwalKerjaHarian/reportSPKO';
                    var dataSrcGroup = 1;
                    // var dataOrderGroup = [[2, 'asc'], [1, 'asc']];
                    var dataRowGroup = '<td colspan="10"><b>Operator : </b>';
                } else if(jenis == 5){
                    var dataUrl = '/Produksi/Informasi/JadwalKerjaHarian/reportSPKO2';
                    var dataSrcGroup = 1;
                    var dataRowGroup = '<td colspan="5"><b>Operator : </b>';
                } else if(jenis == 6){
                    var dataUrl = '/Produksi/Informasi/JadwalKerjaHarian/reportNTHKO2';
                    var dataSrcGroup = 1;
                    var dataRowGroup = '<td colspan="5"><b>Operator : </b>';
                } else if(jenis == 7){
                    var dataUrl = '/Produksi/Informasi/JadwalKerjaHarian/reportAll';
                    var dataSrcGroup = 1;
                } else if(jenis == 8){
                    var dataUrl = '/Produksi/Informasi/JadwalKerjaHarian/reportAll';
                    var dataSrcGroup = 9;
                } else if(jenis == 9){
                    var dataUrl = '/Produksi/Informasi/JadwalKerjaHarian/reportAll';
                    var dataSrcGroup = 6;
                } else if(jenis == 10){
                    var dataUrl = '/Produksi/Informasi/JadwalKerjaHarian/reportAll';
                    var dataSrcGroup = 7;
                } else if(jenis == 11){
                    var dataUrl = '/Produksi/Informasi/JadwalKerjaHarian/reportAll';
                    var dataSrcGroup = 10;
                } else if(jenis == 12){
                    var dataUrl = '/Produksi/Informasi/JadwalKerjaHarian/reportAll';
                    var dataSrcGroup = 1;
                }

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                data = {jenis: jenis, rph: rph, kadar: kadar, operation: operation, operator: operator, tglstart: tglstart, tglend: tglend, kategori: kategori, subkategori: subkategori};
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

                        // console.log(data.query);
                        
                        var table = $('#tampiltabel').DataTable({
                            paging: false,
                            // pageLength: 10,
                            ordering: true,
                            info: false,
                            searching: true,
                            autoWidth: true,
                            responsive: true,
                            scrollX: true,
                            scroller: true,
                            // scrollY: '50vh',
                            scrollCollapse: true,
                            dom: 'Bfrtip',
                            buttons: ['copy', 'csv', 'excel'],
                            rowGroup: {
                                // Uses the 'row group' plugin
                                dataSrc: dataSrcGroup,
                                startRender: function (rows, group) {
                                    var collapsed = !!collapsedGroups[group];
                                    rows.nodes().each(function (r) {
                                        r.style.display = collapsed ? '' : 'none';
                                        // r.style.display = '';
                                    });    

                                    // Remove the formatting to get integer data for summation
                                    var intVal = function (i) {
                                        return typeof i === 'string' ? i.replace(/[\$,]/g, '') * 1 : typeof i === 'number' ? i : 0;
                                    };

                                    // Reference
                                    // var qtyspko = rows.data().pluck(5).reduce( function (a, b) {return a + b.replace(/[^\d]/g, '')*1;}, 0);
                                    // var brtspko = rows.data().pluck(6).reduce( function (a, b) {return parseFloat(a) + parseFloat(b);}, 0);
                                    // var brtspko = rows.data().pluck(6).reduce( function (a, b) {return intVal(a) + intVal(b);}, 0);

                                    var qtyspko = rows.data().pluck(11).reduce( function (a, b) {return intVal(a) + intVal(b);}, 0);
                                    var brtspko = rows.data().pluck(12).reduce( function (a, b) {return intVal(a) + intVal(b);}, 0);
                                    var qtynthko = rows.data().pluck(13).reduce( function (a, b) {return intVal(a) + intVal(b);}, 0);
                                    var qtynthkorep = rows.data().pluck(14).reduce( function (a, b) {return intVal(a) + intVal(b);}, 0);
                                    var qtynthkoss = rows.data().pluck(15).reduce( function (a, b) {return intVal(a) + intVal(b);}, 0);
                                    var brtnthko = rows.data().pluck(16).reduce( function (a, b) {return intVal(a) + intVal(b);}, 0);
                                    var brtnthkorep = rows.data().pluck(17).reduce( function (a, b) {return intVal(a) + intVal(b);}, 0);
                                    var brtnthkoss = rows.data().pluck(18).reduce( function (a, b) {return intVal(a) + intVal(b);}, 0);
                                    var persen = (qtynthko / qtyspko)*100;
                                    var persenrep = (qtynthkorep / qtyspko)*100;
                                    var persenss = (qtynthkoss / qtyspko)*100;

                                    // Add category name to the <tr>. NOTE: Hardcoded colspan

                                    if(data.jenis == 7){
                                        return $('<tr/>')
                                        .append(`<td colspan="11" style="color: blue"><b>Operator : </b> ${group} (${rows.count()}) </td>`)
                                        .append(`<td colspan="1" style="text-align: center; color: blue"><b>${$.fn.dataTable.render.number('.', ',', 0, '').display(qtyspko)}<b></td>`)
                                        .append(`<td colspan="1" style="text-align: center; color: blue"><b>${$.fn.dataTable.render.number('.', ',', 2, '').display(brtspko)}<b></td>`)
                                        .append(`<td colspan="1" style="text-align: center; color: blue"><b>${$.fn.dataTable.render.number('.', ',', 0, '').display(qtynthko)}<b></td>`)
                                        .append(`<td colspan="1" style="text-align: center; color: blue"><b>${$.fn.dataTable.render.number('.', ',', 0, '').display(qtynthkorep)}<b></td>`)
                                        .append(`<td colspan="1" style="text-align: center; color: blue"><b>${$.fn.dataTable.render.number('.', ',', 0, '').display(qtynthkoss)}<b></td>`)
                                        .append(`<td colspan="1" style="text-align: center; color: blue"><b>${$.fn.dataTable.render.number('.', ',', 2, '').display(brtnthko)}<b></td>`)
                                        .append(`<td colspan="1" style="text-align: center; color: blue"><b>${$.fn.dataTable.render.number('.', ',', 2, '').display(brtnthkorep)}<b></td>`)
                                        .append(`<td colspan="1" style="text-align: center; color: blue"><b>${$.fn.dataTable.render.number('.', ',', 2, '').display(brtnthkoss)}<b></td>`)
                                        .append(`<td colspan="1" style="text-align: center; color: blue"><b>${$.fn.dataTable.render.number('.', ',', 2, '').display(persen)}%<b></td>`)
                                        .append(`<td colspan="1" style="text-align: center; color: blue"><b>${$.fn.dataTable.render.number('.', ',', 2, '').display(persenrep)}%<b></td>`)
                                        .append(`<td colspan="1" style="text-align: center; color: blue"><b>${$.fn.dataTable.render.number('.', ',', 2, '').display(persenss)}%<b></td>`)
                                        .attr('data-name', group)
                                        .toggleClass('collapsed', collapsed);
                                    }if(data.jenis == 8){
                                        return $('<tr/>')
                                        .append(`<td colspan="11" style="color: blue"><b>Kadar : </b> ${group} (${rows.count()}) </td>`)
                                        .append(`<td colspan="1" style="text-align: center; color: blue"><b>${$.fn.dataTable.render.number('.', ',', 0, '').display(qtyspko)}<b></td>`)
                                        .append(`<td colspan="1" style="text-align: center; color: blue"><b>${$.fn.dataTable.render.number('.', ',', 2, '').display(brtspko)}<b></td>`)
                                        .append(`<td colspan="1" style="text-align: center; color: blue"><b>${$.fn.dataTable.render.number('.', ',', 0, '').display(qtynthko)}<b></td>`)
                                        .append(`<td colspan="1" style="text-align: center; color: blue"><b>${$.fn.dataTable.render.number('.', ',', 0, '').display(qtynthkorep)}<b></td>`)
                                        .append(`<td colspan="1" style="text-align: center; color: blue"><b>${$.fn.dataTable.render.number('.', ',', 0, '').display(qtynthkoss)}<b></td>`)
                                        .append(`<td colspan="1" style="text-align: center; color: blue"><b>${$.fn.dataTable.render.number('.', ',', 2, '').display(brtnthko)}<b></td>`)
                                        .append(`<td colspan="1" style="text-align: center; color: blue"><b>${$.fn.dataTable.render.number('.', ',', 2, '').display(brtnthkorep)}<b></td>`)
                                        .append(`<td colspan="1" style="text-align: center; color: blue"><b>${$.fn.dataTable.render.number('.', ',', 2, '').display(brtnthkoss)}<b></td>`)
                                        .append(`<td colspan="1" style="text-align: center; color: blue"><b>${$.fn.dataTable.render.number('.', ',', 2, '').display(persen)}%<b></td>`)
                                        .append(`<td colspan="1" style="text-align: center; color: blue"><b>${$.fn.dataTable.render.number('.', ',', 2, '').display(persenrep)}%<b></td>`)
                                        .append(`<td colspan="1" style="text-align: center; color: blue"><b>${$.fn.dataTable.render.number('.', ',', 2, '').display(persenss)}%<b></td>`)
                                        .attr('data-name', group)
                                        .toggleClass('collapsed', collapsed);
                                    }if(data.jenis == 9){
                                        return $('<tr/>')
                                        .append(`<td colspan="11" style="color: blue"><b>Kategori : </b> ${group} (${rows.count()}) </td>`)
                                        .append(`<td colspan="1" style="text-align: center; color: blue"><b>${$.fn.dataTable.render.number('.', ',', 0, '').display(qtyspko)}<b></td>`)
                                        .append(`<td colspan="1" style="text-align: center; color: blue"><b>${$.fn.dataTable.render.number('.', ',', 2, '').display(brtspko)}<b></td>`)
                                        .append(`<td colspan="1" style="text-align: center; color: blue"><b>${$.fn.dataTable.render.number('.', ',', 0, '').display(qtynthko)}<b></td>`)
                                        .append(`<td colspan="1" style="text-align: center; color: blue"><b>${$.fn.dataTable.render.number('.', ',', 0, '').display(qtynthkorep)}<b></td>`)
                                        .append(`<td colspan="1" style="text-align: center; color: blue"><b>${$.fn.dataTable.render.number('.', ',', 0, '').display(qtynthkoss)}<b></td>`)
                                        .append(`<td colspan="1" style="text-align: center; color: blue"><b>${$.fn.dataTable.render.number('.', ',', 2, '').display(brtnthko)}<b></td>`)
                                        .append(`<td colspan="1" style="text-align: center; color: blue"><b>${$.fn.dataTable.render.number('.', ',', 2, '').display(brtnthkorep)}<b></td>`)
                                        .append(`<td colspan="1" style="text-align: center; color: blue"><b>${$.fn.dataTable.render.number('.', ',', 2, '').display(brtnthkoss)}<b></td>`)
                                        .append(`<td colspan="1" style="text-align: center; color: blue"><b>${$.fn.dataTable.render.number('.', ',', 2, '').display(persen)}%<b></td>`)
                                        .append(`<td colspan="1" style="text-align: center; color: blue"><b>${$.fn.dataTable.render.number('.', ',', 2, '').display(persenrep)}%<b></td>`)
                                        .append(`<td colspan="1" style="text-align: center; color: blue"><b>${$.fn.dataTable.render.number('.', ',', 2, '').display(persenss)}%<b></td>`)
                                        .attr('data-name', group)
                                        .toggleClass('collapsed', collapsed);
                                    }if(data.jenis == 10){
                                        return $('<tr/>')
                                        .append(`<td colspan="11" style="color: blue"><b>SubKategori : </b> ${group} (${rows.count()}) </td>`)
                                        .append(`<td colspan="1" style="text-align: center; color: blue"><b>${$.fn.dataTable.render.number('.', ',', 0, '').display(qtyspko)}<b></td>`)
                                        .append(`<td colspan="1" style="text-align: center; color: blue"><b>${$.fn.dataTable.render.number('.', ',', 2, '').display(brtspko)}<b></td>`)
                                        .append(`<td colspan="1" style="text-align: center; color: blue"><b>${$.fn.dataTable.render.number('.', ',', 0, '').display(qtynthko)}<b></td>`)
                                        .append(`<td colspan="1" style="text-align: center; color: blue"><b>${$.fn.dataTable.render.number('.', ',', 0, '').display(qtynthkorep)}<b></td>`)
                                        .append(`<td colspan="1" style="text-align: center; color: blue"><b>${$.fn.dataTable.render.number('.', ',', 0, '').display(qtynthkoss)}<b></td>`)
                                        .append(`<td colspan="1" style="text-align: center; color: blue"><b>${$.fn.dataTable.render.number('.', ',', 2, '').display(brtnthko)}<b></td>`)
                                        .append(`<td colspan="1" style="text-align: center; color: blue"><b>${$.fn.dataTable.render.number('.', ',', 2, '').display(brtnthkorep)}<b></td>`)
                                        .append(`<td colspan="1" style="text-align: center; color: blue"><b>${$.fn.dataTable.render.number('.', ',', 2, '').display(brtnthkoss)}<b></td>`)
                                        .append(`<td colspan="1" style="text-align: center; color: blue"><b>${$.fn.dataTable.render.number('.', ',', 2, '').display(persen)}%<b></td>`)
                                        .append(`<td colspan="1" style="text-align: center; color: blue"><b>${$.fn.dataTable.render.number('.', ',', 2, '').display(persenrep)}%<b></td>`)
                                        .append(`<td colspan="1" style="text-align: center; color: blue"><b>${$.fn.dataTable.render.number('.', ',', 2, '').display(persenss)}%<b></td>`)
                                        .attr('data-name', group)
                                        .toggleClass('collapsed', collapsed);
                                    }if(data.jenis == 11){
                                        return $('<tr/>')
                                        .append(`<td colspan="11" style="color: blue"><b>Operation : </b> ${group} (${rows.count()}) </td>`)
                                        .append(`<td colspan="1" style="text-align: center; color: blue"><b>${$.fn.dataTable.render.number('.', ',', 0, '').display(qtyspko)}<b></td>`)
                                        .append(`<td colspan="1" style="text-align: center; color: blue"><b>${$.fn.dataTable.render.number('.', ',', 2, '').display(brtspko)}<b></td>`)
                                        .append(`<td colspan="1" style="text-align: center; color: blue"><b>${$.fn.dataTable.render.number('.', ',', 0, '').display(qtynthko)}<b></td>`)
                                        .append(`<td colspan="1" style="text-align: center; color: blue"><b>${$.fn.dataTable.render.number('.', ',', 0, '').display(qtynthkorep)}<b></td>`)
                                        .append(`<td colspan="1" style="text-align: center; color: blue"><b>${$.fn.dataTable.render.number('.', ',', 0, '').display(qtynthkoss)}<b></td>`)
                                        .append(`<td colspan="1" style="text-align: center; color: blue"><b>${$.fn.dataTable.render.number('.', ',', 2, '').display(brtnthko)}<b></td>`)
                                        .append(`<td colspan="1" style="text-align: center; color: blue"><b>${$.fn.dataTable.render.number('.', ',', 2, '').display(brtnthkorep)}<b></td>`)
                                        .append(`<td colspan="1" style="text-align: center; color: blue"><b>${$.fn.dataTable.render.number('.', ',', 2, '').display(brtnthkoss)}<b></td>`)
                                        .append(`<td colspan="1" style="text-align: center; color: blue"><b>${$.fn.dataTable.render.number('.', ',', 2, '').display(persen)}%<b></td>`)
                                        .append(`<td colspan="1" style="text-align: center; color: blue"><b>${$.fn.dataTable.render.number('.', ',', 2, '').display(persenrep)}%<b></td>`)
                                        .append(`<td colspan="1" style="text-align: center; color: blue"><b>${$.fn.dataTable.render.number('.', ',', 2, '').display(persenss)}%<b></td>`)
                                        .attr('data-name', group)
                                        .toggleClass('collapsed', collapsed);
                                    }if(data.jenis == 12){
                                        return $('<tr/>')
                                        .append(`<td colspan="11" style="color: blue"><b>Operator : </b> ${group} (${rows.count()}) </td>`)
                                        .append(`<td colspan="1" style="text-align: center; color: blue"><b>${$.fn.dataTable.render.number('.', ',', 0, '').display(qtyspko)}<b></td>`)
                                        .append(`<td colspan="1" style="text-align: center; color: blue"><b>${$.fn.dataTable.render.number('.', ',', 2, '').display(brtspko)}<b></td>`)
                                        .append(`<td colspan="1" style="text-align: center; color: blue"><b>${$.fn.dataTable.render.number('.', ',', 0, '').display(qtynthko)}<b></td>`)
                                        .append(`<td colspan="1" style="text-align: center; color: blue"><b>${$.fn.dataTable.render.number('.', ',', 0, '').display(qtynthkorep)}<b></td>`)
                                        .append(`<td colspan="1" style="text-align: center; color: blue"><b>${$.fn.dataTable.render.number('.', ',', 0, '').display(qtynthkoss)}<b></td>`)
                                        .append(`<td colspan="1" style="text-align: center; color: blue"><b>${$.fn.dataTable.render.number('.', ',', 2, '').display(brtnthko)}<b></td>`)
                                        .append(`<td colspan="1" style="text-align: center; color: blue"><b>${$.fn.dataTable.render.number('.', ',', 2, '').display(brtnthkorep)}<b></td>`)
                                        .append(`<td colspan="1" style="text-align: center; color: blue"><b>${$.fn.dataTable.render.number('.', ',', 2, '').display(brtnthkoss)}<b></td>`)
                                        .append(`<td colspan="1" style="text-align: center; color: blue"><b>${$.fn.dataTable.render.number('.', ',', 2, '').display(persen)}%<b></td>`)
                                        .append(`<td colspan="1" style="text-align: center; color: blue"><b>${$.fn.dataTable.render.number('.', ',', 2, '').display(persenrep)}%<b></td>`)
                                        .append(`<td colspan="1" style="text-align: center; color: blue"><b>${$.fn.dataTable.render.number('.', ',', 2, '').display(persenss)}%<b></td>`)
                                        .attr('data-name', group)
                                        .toggleClass('collapsed', collapsed);
                                    }else{
                                        return $('<tr/>')
                                        .append(`${dataRowGroup} ${group} (${rows.count()})  </td>`)
                                        .attr('data-name', group)
                                        .toggleClass('collapsed', collapsed);
                                    }
                                
                                },
                                // endRender: function ( rows, group ) {  
                                // },
                            }
                        });  

                        // table.columns.adjust().draw();

                        $('#tampiltabel tbody').on('click', 'tr.dtrg-group', function(){
                            var name = $(this).data('name');
                            collapsedGroups[name] = !collapsedGroups[name];
                            // table.columns.adjust().draw(false);
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
                }else if(jenis == 7 || jenis == 8 || jenis == 9 || jenis == 10 || jenis == 11 || jenis == 12){
                    var dataUrl = `/Produksi/Informasi/JadwalKerjaHarian/cetakAll?tglstart=${tglstart}&tglend=${tglend}&kadar=${kadar}&operation=${operation}&operator=${operator}&kategori=${kategori}&subkategori=${subkategori}&jenis=${jenis}&jeniscetak=${jeniscetak}`;
                }

                window.open(dataUrl, '_blank');
            }
        }


        



    </script>

@endsection

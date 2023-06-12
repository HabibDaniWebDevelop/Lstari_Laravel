<?php $title = 'Posting TM PCB'; ?>
@extends('layouts.backend-Theme-3.app')
@section('title', '$title')

@section('Dashboard')
    <h2 class="m-0">{{ $title }}</h2>
    <ol class="breadcrumb sm-2">
        <li class="breadcrumb-item"><a href="/">Home </a></li>
        <li class="breadcrumb-item">R & D </li>
        <li class="breadcrumb-item">Percobaan </li>
        <li class="breadcrumb-item active">Posting TM PCB </li>
    </ol>
@endsection

@section('css')

    <style>

    </style>

@endsection

@section('container')
    <div class="row">
        <div class="col-md-12">
            <div class="card mb-4">

                @include('R&D.Percobaan.PostingTMPCB.data')
                
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        $('#tabel1').DataTable({
            "paging": false,
            "lengthChange": false,
            "searching": false,
            "ordering": false,
            "info": false,
            "autoWidth": true,
            "responsive": true,
            "fixedColumns": true,
            dom: 'Bfrtip',
            buttons: [{
                extend: 'print',
                split: ['excel', 'pdf', 'print', 'copy', 'csv'],
            }]
            // buttons: ['excel', 'pdf', 'print', 'copy', 'csv']
        });
        
        function getTM() {
            let idTM = $('#idTM').val()
            if (idTM == ""){
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: "idTM Tidak Boleh Kosong",
                })
                return;
            }
            let data = {idTM:idTM}

            $.ajax({
                type: "GET",
                url: "/R&D/Percobaan/PostingTMPCB/getTM",
                data:data,
                dataType: 'json',
                beforeSend: function () {
                    $(".preloader").show();  
                },
                complete: function () {
                    $(".preloader").fadeOut(); 
                },
                success: function(data) {
                    // return
                    // Destroy datatable;
                    $("#tabel1").dataTable().fnDestroy()
                    $('#tabel1 tbody > tr').remove();

                    // Set TableItems
                    $('#TableItems').empty();
                    $('#TableItems').html(data.data.resultHTML)

                    $('#tabel1').DataTable({
                        scrollY: '50vh',
                        scrollCollapse: true,
                        paging: false,
                        lengthChange: true,
                        searching: false,
                        ordering: false,
                        info: false,
                        autoWidth: true,
                        responsive: true,
                        fixedColumns: true,
                    });

                    // Set Button
                    // $("#btn_posting").prop('disabled',false)
                    if (data.data.result.PostDate == null) {
                        $("#btn_posting").prop('disabled',false)
                    } else {
                        $("#btn_posting").prop('disabled',true)
                    }
                    
                    // Set Input
                    $('#idTMHidden').val(data.data.result.ID)
                    $('#tanggalTM').val(data.data.result.TransDate)
                    $('#totalJumlah').val(data.data.result.TotalQty)
                    $('#totalBerat').val(data.data.result.TotalWeight)
                },
                error: function(xhr){
                    // It will executed if response from backend is error
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: xhr.responseJSON.message,
                    })
                    return;
                }
            })
        }

        function KlikBaru() {
            window.location.reload()
        }

        function KlikPosting() {
            let idTM = $('#idTMHidden').val()
            if (idTM == "") {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: "TM Belum terpilih",
                })
                return;
            }

            let data = {idTM:idTM}
            
            // Setup CSRF TOKEN
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                type: "POST",
                url: "/R&D/Percobaan/PostingTMPCB/posting",
                data:data,
                dataType: 'json',
                beforeSend: function () {
                    $(".preloader").show();  
                },
                complete: function () {
                    $(".preloader").fadeOut(); 
                },
                success: function(data) {
                    // Setting Buttons
                    $("#btn_posting").prop('disabled',true)

                    // Swal.fire({
                    //     icon: 'success',
                    //     title: 'Yaayy...',
                    //     text: "Success",
                    // })
                    return;
                },
                error: function(xhr){
                    // It will executed if response from backend is error
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: xhr.responseJSON.message,
                    })
                    return;
                }
            }) 
        }

        function klikCetak() {
            let keyword = $('#idTMHidden').val()
            if (keyword == "") {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: "TM belum Terpilih",
                })
                return;
            }
            window.open('/R&D/Percobaan/PostingTMPCB/cetak?idTM='+keyword, '_blank');
            return
        }
    </script>
    

@endsection
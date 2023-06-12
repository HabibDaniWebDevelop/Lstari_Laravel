<?php $title = 'Data PC'; ?>
@extends('layouts.backend-Theme-3.app')
@section('title', '$title')

@section('Dashboard')
    <h2 class="m-0">{{ $title }}</h2>
    <ol class="breadcrumb sm-2">
        <li class="breadcrumb-item"><a href="/">Home </a></li>
        <li class="breadcrumb-item">IT </li>
        <li class="breadcrumb-item active">DataPC </li>
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

                <div class="card-body">

                    <div class="card-body d-flex justify-content-between p-0">
                        <button class="btn btn-primary mb-2" onclick="kliktambah()">Tambah Data</button>

                        <div class="float-end">
                            <div class="input-group input-group-merge">
                                <span class="input-group-text"><i class="bx bx-search"
                                        onclick="klikViewSelection()"></i></span>
                                <input type="text" class="form-control" placeholder="Search..." autofocus id='cari'
                                    onkeydown="klikCari()" />
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive text-nowrap" style="height:calc(100vh - 490px);">
                        <table class="table table-border table-hover table-sm" id="tabel1">
                            <thead class="table-secondary sticky-top zindex-2">
                                <tr style="text-align: center">
                                    <th>No.</th>
                                    <th>Kode Komputer</th>
                                    <th>Computer Name</th>
                                    <th>Type</th>
                                    <th>IP Address</th>
                                    <th>MAC Address</th>
                                    <th>Mainboard</th>
                                </tr>
                            </thead>
                            <tbody>


                            </tbody>
                        </table>
                    </div>

                    <div class="table-responsive text-nowrap" style="height:calc(100vh - 490px);">
                        <table class="table table-border table-hover table-sm" id="tabel2">
                            <thead class="table-secondary sticky-top zindex-2">
                                <tr style="text-align: center">
                                    <th>No.</th>
                                    <th>Kode Komputer</th>
                                    <th>Computer Name</th>
                                    <th>Type</th>
                                    <th>IP Address</th>
                                    <th>MAC Address</th>
                                    <th>Mainboard</th>
                                </tr>
                            </thead>
                            <tbody>


                            </tbody>
                        </table>
                    </div>


                </div>

                @include('setting.publick_function.ViewSelectionModal')

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
            "ordering": true,
            "info": false,
            "autoWidth": true,
            "responsive": true,
            "fixedColumns": true,
        });
        $('#tabel2').DataTable({
            "paging": false,
            "lengthChange": false,
            "searching": false,
            "ordering": true,
            "info": false,
            "autoWidth": true,
            "responsive": true,
            "fixedColumns": true,
        });

        function klikViewSelection() {
            $("#jodulmodalVS").html('Menu filter View Selection');
            $('#modalformatVS').attr('class', 'modal-dialog modal-fullscreen');
            $.get('/ViewSelection?id=&tb=workallocation', function(data) {
                $("#modalVS").html(data);
                $('#modalinfoVS').modal('show');
            });

        }
    </script>

@endsection

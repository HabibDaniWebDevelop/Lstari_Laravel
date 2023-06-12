<?php $title = 'Percobaan'; ?>
@extends('layouts.backend-Theme-3.app')
@section('title', '$title')

@section('Dashboard')
    <h2 class="m-0">{{ $title }}</h2>
    <ol class="breadcrumb sm-2">
        <li class="breadcrumb-item"><a href="/">Home </a></li>
        <li class="breadcrumb-item">Percobaan </li>
        <li class="breadcrumb-item active">Katalog Routing</li>
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
            @include('R&D.Percobaan.KatalogRoutingPCB.data')
        </div>
    </div>
</div>
@endsection

@section('script')

@include('layouts.backend-Theme-3.DataTabelButton')

<script>
    function getListItem(value) {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        data = {value: value};
        $.ajax({
            url: "/RnD/Percobaan/KatalogRouting/GetListRouting",
            type: "POST",
            data: data,
            dataType: "json",
            beforeSend: function () {
                $(".preloader").show();  
            },
            complete: function () {
                $(".preloader").fadeOut(); 
            },
            success: function(data) {
                var collapsedGroups = {};
                $("#tabel5 tbody").append(data.html);

                $("#idwo").val(data.swwo);

                table = $('#tabel5').DataTable({
                    "paging":   false,
                    "ordering": false,
                    "info":     false,
                    "searching" : false,
                    rowGroup: {
                        dataSrc: [1],
                        startRender: function (rows, group) {
                            var collapsed = !!collapsedGroups[group];
                            rows.nodes().each(function (r) {
                            r.style.display = collapsed ? '' : 'none';
                          });    
                          return $('<tr/>')
                            .append('<td colspan="6" style="font-weight: bold;">' + group + ' (' + rows.count() + ')</td>')
                            .attr('data-name', group)
                            .toggleClass('collapsed', collapsed);
                        }
                    }
                });

                $('#tabel5 tbody').on('click', 'tr.dtrg-group', function () {
                    var name = $(this).data('name');
                    collapsedGroups[name] = !collapsedGroups[name];
                    table.draw(false);
                });
            }
        });
    }
</script>

@endsection

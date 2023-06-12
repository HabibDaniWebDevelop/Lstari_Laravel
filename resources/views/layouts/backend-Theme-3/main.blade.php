@extends('layouts.backend-Theme-3.app')

@section('css')
{{-- <link rel="stylesheet" href="{!! asset('assets/sneatV1/assets/vendor/libs/apex-charts/apex-charts.css') !!}" /> --}}
{{-- <link rel="stylesheet" href="assets/sneatV1/assets/vendor/css/pages/page-icons.css" /> --}}
<style>
div.scrollmenu {
    overflow: auto;
    white-space: nowrap;
}

div.scrollmenu a {
    display: inline-block;
    color: #697a8d;
    margin: 0px;
    padding: 0px;
}

#icons-container .icon-card {
    width: 80px;
    height: 80px;
}

#icons-container .icon-card .card-body {
    padding: 8px;
}

#icons-container .icon-card i {
    font-size: 2em;
}
</style>
@endsection

@section('container')
<div class="row rounded" id="container1">
    <button id="resize" hidden>Change size</button>
    <div class="d-flex flex-wrap px-2" id="icons-container">
        <div class="scrollmenu mb-0" id="scrollmenu1">

            @foreach ($menus as $menu)
            <?php
                    $s = $menu->Name;
                    $a = preg_replace('/\b(\w)|./', '$1', $s);
                    if ($menu->Icon == '') {
                        $icon = '<i class="fas font-monospace" >' . $a . '</i>';
                    }
                    else if (preg_match("/class/i", $menu->Icon)) {
                        $icon = $menu->Icon;
                    } 
                    else {
                        $icon = '<i class="fas font-monospace">' . $menu->Icon . '</i>';
                        
                    }
                    ?>

            <a href="{{ $menu->Patch }}" data-bs-toggle="tooltip" data-bs-placement="bottom"
                title="{!! $menu->Name !!}">
                <div class="card icon-card text-center mx-1 mb-3">
                    <div class="card-body">
                        {{-- {!! $menu->Icon !!}  --}}
                        {!! $icon !!}
                        <p class="icon-name text-capitalize text-truncate mt-1">{!! $menu->Name !!}</p>
                    </div>
                </div>
            </a>
            @endforeach


        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-7 order-0">
        <div class="row">
            <div class="col-lg-12 p-2 order-0">
                <div class="card">
                    <div class="d-flex row">
                        <div class="col-xl-12 col-sm-12">
                            <div class="card-body">

                                <div class="row mb-2">
                                    <div class="col-11 pe-0 pb-2">
                                        <ul class="nav nav-pills" role="tablist" id="Pengumuman">
                                            <li class="nav-item">
                                                <button type="button" class="nav-link active" role="tab"
                                                    id="P">Pengumuman
                                                </button>
                                            </li>
                                            <li class="nav-item">
                                                <button type="button" class="nav-link" role="tab" id="x">
                                                    Sudah Berakhir
                                                </button>
                                            </li>
                                        </ul>
                                    </div>
                                    @if ($pengumuman == '1')
                                    <div class="col-1 p-2 ps-0 text-end"><a href="Pengumuman"><i
                                                class="fas fa-edit nav-link fs-5"></i></a></div>
                                    @endif

                                </div>

                                <div class="table-responsive text-nowrap" style="height: 220px;" id="isipengumuman">
                                </div>

                            </div>
                        </div>
                        {{-- <div class="col-xl-2 col-sm-4 text-end">
                                <div class="card-body">
                                    <img src="{!! asset('assets/images/pengumuman2.png') !!}" height="135" />
                                </div>
                            </div> --}}
                    </div>
                </div>
            </div>
            <div class="col-lg-12 p-2 order-0">
                <div class="card" style="height: 278px;">
                    <div class="card-header pb-0">

                    </div>
                    <div class="card-body p-0">
                        <div class="col-md-8">

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-5 p-2 order-0">
        <div class="card" style="height: 620px;">
            <div class="card-body">
                <div class="row mb-2">
                    <div class="col-11 pe-0">
                        <ul class="nav nav-pills" role="tablist" id="todo">
                            <li class="nav-item">
                                <button type="button" class="nav-link active" role="tab" id="A"> Antrian
                                </button>
                            </li>
                            <li class="nav-item">
                                <button type="button" class="nav-link" role="tab" id="P">Proses
                                </button>
                            </li>
                            <li class="nav-item">
                                <button type="button" class="nav-link" role="tab" id="T">Tunda
                                </button>
                            </li>
                            <li class="nav-item">
                                <button type="button" class="nav-link" role="tab" id="S">Selesai
                                </button>
                            </li>
                        </ul>
                    </div>
                    <div class="col-1 p-2 ps-0 text-end"><a href="todolist"><i
                                class="fas fa-edit nav-link fs-5"></i></a></div>
                </div>

                <div class="table-responsive text-nowrap" style="height: 500px;" id="isitodo">

                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12 col-lg-8 order-2 order-md-3 order-lg-2 p-2">
        <div class="card" style="height: 315px;">
            <div class="card-header">
                <ul class="nav nav-pills" role="tablist">
                    <li class="nav-item">
                        <button type="button" class="nav-link active" role="tab" data-bs-toggle="tab"
                            data-bs-target="#navs-tabs-line-card-income" aria-controls="navs-tabs-line-card-income"
                            aria-selected="true">
                            Income
                        </button>
                    </li>
                    <li class="nav-item">
                        <button type="button" class="nav-link" role="tab">Expenses</button>
                    </li>
                    <li class="nav-item">
                        <button type="button" class="nav-link" role="tab">Profit</button>
                    </li>
                </ul>
            </div>
            <div class="card-body">
                <div class="col-md-8">
                    <h5 class="card-header m-0 me-2 pb-3"> </h5>

                </div>
            </div>
        </div>
    </div>
    <!--/ Total Revenue -->
    <div class="col-12 col-md-8 col-lg-4 order-3 order-md-2">
        <div class="row">
            <div class="col-6 p-2">
                <div class="card" style="height: 150px;">
                    <div class="card-body">
                        <div class="card-title d-flex align-items-start justify-content-between">
                            <div class="avatar flex-shrink-0">
                                <img src="{!! asset('assets/sneatV1/assets/img/icons/unicons/paypal.png') !!}"
                                    alt="Credit Card" class="rounded" />
                            </div>
                            <div class="dropdown">
                                <button class="btn p-0" type="button" id="cardOpt4" data-bs-toggle="dropdown"
                                    aria-haspopup="true" aria-expanded="false">
                                    <i class="bx bx-dots-vertical-rounded"></i>
                                </button>
                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="cardOpt4">
                                    <a class="dropdown-item" href="javascript:void(0);">View More</a>
                                    <a class="dropdown-item" href="javascript:void(0);">Delete</a>
                                </div>
                            </div>
                        </div>
                        <span class="d-block mb-1">Payments</span>
                        {{-- <h3 class="card-title text-nowrap mb-2">$2,456</h3>
                            <small class="text-danger fw-semibold"><i class="bx bx-down-arrow-alt"></i> -14.82%</small> --}}
                    </div>
                </div>
            </div>
            <div class="col-6 p-2">
                <div class="card" style="height: 150px;">
                    <div class="card-body">
                        <div class="card-title d-flex align-items-start justify-content-between">
                            <div class="avatar flex-shrink-0">
                                <img src="{!! asset('assets/sneatV1/assets/img/icons/unicons/cc-primary.png') !!}"
                                    alt="Credit Card" class="rounded" />
                            </div>
                            <div class="dropdown">
                                <button class="btn p-0" type="button" id="cardOpt1" data-bs-toggle="dropdown"
                                    aria-haspopup="true" aria-expanded="false">
                                    <i class="bx bx-dots-vertical-rounded"></i>
                                </button>
                                <div class="dropdown-menu" aria-labelledby="cardOpt1">
                                    <a class="dropdown-item" href="javascript:void(0);">View More</a>
                                    <a class="dropdown-item" href="javascript:void(0);">Delete</a>
                                </div>
                            </div>
                        </div>
                        <span class="fw-semibold d-block mb-1">Transactions</span>
                        {{-- <h3 class="card-title mb-2">$14,857</h3>
                            <small class="text-success fw-semibold"><i class="bx bx-up-arrow-alt"></i> +28.14%</small> --}}
                    </div>
                </div>
            </div>
            <!-- </div>
                                        <div class="row"> -->
            <div class="col-12 p-2">
                <div class="card" style="height: 150px;">
                    <div class="card-body">
                        <div class="d-flex justify-content-between flex-sm-row flex-column gap-3">
                            <div class="d-flex flex-sm-column flex-row align-items-start justify-content-between">
                                <div class="card-title">
                                    <h5 class="text-nowrap mb-2">Profile Report</h5>
                                    {{-- <span class="badge bg-label-warning rounded-pill">Year 2021</span> --}}
                                </div>
                                <div class="mt-sm-auto">
                                    {{-- <small class="text-success text-nowrap fw-semibold"><i
                                                class="bx bx-chevron-up"></i> 68.2%</small>
                                        <h3 class="mb-0">$84,686k</h3> --}}
                                </div>
                            </div>
                            <div id="profileReportChart"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="dropdown-menu dropdown-menu-end animate" id="menuklik" style="display:none">
    <div class="text-center fw-bold mb-2 judulklik"></div>
    <hr class="m-2">
    <a class="dropdown-item di1" id="P"><span class="badge bg-warning">P</span>&nbsp; Proses</a>
    <a class="dropdown-item di1" id="T"><span class="badge bg-danger">T</span>&nbsp; Tunda</a>
    <a class="dropdown-item di1" id="S"><span class="badge bg-success">S</span>&nbsp; Selesai</a>
</div>
@endsection

@section('script')
{{-- <script src="{!! asset('assets/sneatV1/assets/vendor/libs/apex-charts/apexcharts.js') !!}"></script> --}}
{{-- <script src="{!! asset('assets/sneatV1/assets/js/dashboards-analytics.js') !!}"></script> --}}

<script>
$('#tabel1').DataTable({
    "paging": false,
    "lengthChange": false,
    "searching": false,
    "ordering": true,
    "info": false,
    "autoWidth": true,
    "responsive": true,
});

$(document).ready(function() {
    todo('A');
    Pengumuman('P');
});

var selector1 = '#Pengumuman .nav-link';
$(selector1).on('click', function() {
    $(selector1).removeClass('active');
    $(this).addClass('active');
    id = $(this).attr('id');
    Pengumuman(id);
});

function Pengumuman(id) {
    $.get('/Pengumumanmenu/' + id, function(data) {
        $("#isipengumuman").html(data);
    });
    // alert(id);
}

var selector2 = '#todo .nav-link';
$(selector2).on('click', function() {
    $(selector2).removeClass('active');
    $(this).addClass('active');
    id = $(this).attr('id');
    $(".judulklik").attr('id3', id);
    todo(id);
});

function todo(id) {
    $.get('/todomenu/' + id, function(data) {
        $("#isitodo").html(data);
    });
}

new PerfectScrollbar('#scrollmenu1');

// Initialize the plugin
const demo = document.querySelector('#scrollmenu1');
const ps = new PerfectScrollbar(demo);

// Handle size change
document.querySelector('#resize').addEventListener('click', () => {

    // Get updated values
    width = document.querySelector('#width').value;
    height = document.querySelector('#height').value;

    // Set demo sizes
    demo.style.width = `${width}px`;
    demo.style.height = `${height}px`;

    // Update Perfect Scrollbar
    ps.update();
});

//-------------------- klik di menu klik --------------------
var selector = '.di1';
$(selector).on('click', function() {
    id = $('.judulklik').attr('id');
    id3 = $('.judulklik').attr('id3');
    id2 = $(this).attr('id');
    // alert(id + '|' + id2 + '|'+ id3);

    //update status
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $.ajax({
        type: "PUT",
        url: '/todolist/edit/' + id,
        data: {
            "status": id2
        },
        dataType: 'json',
        success: function(data) {
            todo(id3);
        }
    });

});
</script>
@endsection
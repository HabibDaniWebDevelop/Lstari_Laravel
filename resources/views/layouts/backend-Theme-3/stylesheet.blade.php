
<!-- Icons. Uncomment required icon fonts -->
<link rel="stylesheet" href="{!! asset('assets/sneatV1/assets/vendor/fonts/boxicons.css') !!}" />
<link href="{!! asset('assets/sneatV1/assets/vendor/fonts/fontawesome/css/all.min.css" rel="stylesheet" type="text/css') !!}">

<!-- Core CSS -->
<link rel="stylesheet" href="{!! asset('assets/sneatV1/assets/vendor/css/core2.css" class="template-customizer-core-css') !!}" />
<link rel="stylesheet" href="{!! asset('assets/sneatV1/assets/vendor/css/theme-default2.css" class="template-customizer-theme-css') !!}" />
<link rel="stylesheet" href="{!! asset('assets/sneatV1/assets/css/demo.css') !!}" />

<!-- Vendors CSS -->
<link rel="stylesheet" href="{!! asset('assets/sneatV1/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css') !!}" />
<link rel="stylesheet" href="{!! asset('assets/sneatV1/assets/vendor/libs/datatables/dataTables.bootstrap4.min.css') !!}">
<link rel="stylesheet" href="{!! asset('assets/sneatV1/libs/jquery-ui/1.13.2/jquery-ui.min.css') !!}">
<link rel="stylesheet" href="{!! asset('assets/sneatV1/libs/rowGroup/rowGroup.bootstrap5.min.css') !!}" />
{{-- <link rel="stylesheet" href="{!! asset('assets/sneatV1/assets/vendor/libs/datatables/buttons.dataTables.custom.css') !!}"> --}}
<link rel="stylesheet" href="{!! asset('assets/sneatV1/assets/vendor/libs/datatables/buttons.bootstrap5.min.css') !!}">
<link rel="stylesheet" href="{!! asset('assets/sneatV1/assets/vendor/libs/datepicker/datepicker.min.css') !!}">
{{-- <link rel="stylesheet" href="{!! asset('assets/sneatV1/assets/vendor/libs/simple-qrcode/simple-qrcode.min.css') !!}"> --}}

{{-- ? ---------------- JS ---------------- --}}
<!--  Helpers -->
<script src="{!! asset('assets/sneatV1/assets/vendor/js/helpers.js.download') !!}"></script>
<script src="{!! asset('assets/sneatV1/assets/js/config.js.download') !!}"></script>
<script async="async" src="{!! asset('assets/sneatV1/assets/js2') !!}"></script>


{{-- ? ---------------- style untuk custon tampilan ---------------- --}}
<style type="text/css">
    .layout-menu-fixed .layout-navbar-full .layout-menu,
    .layout-menu-fixed-offcanvas .layout-navbar-full .layout-menu {
        top: 0px !important;
    }

    .layout-page {
        padding-top: 0px !important;
    }

    .content-wrapper {
        padding-bottom: 0px !important;
    }

    .pagination {
        justify-content: center;
        margin-top: 12px;
    }
</style>

<script>
    window.dataLayer = window.dataLayer || [];

    function gtag() {
        dataLayer.push(arguments);
    }
    gtag('js', new Date());
    gtag('config', 'GA_MEASUREMENT_ID');
</script>

{{-- ? ---------------- style untuk Loding Scraen ---------------- --}}
<style>
    .preloader {
        display: -ms-flexbox;
        display: flex;
        background-color: #f4f6f9;
        height: 100vh;
        width: 100%;
        transition: height 200ms linear;
        position: fixed;
        left: 0;
        top: 0;
        z-index: 9999;
    }
    .preloaderajax {
        display: -ms-flexbox;
        display: flex;
        background-color: #f4f6f9;
        height: 100vh;
        width: 100%;
        transition: height 200ms linear;
        position: fixed;
        left: 0;
        top: 0;
        z-index: 9999;
    }
</style>

{{-- ? ---------------- style untuk sorting data tabel ---------------- --}}
<style>
    table.dataTable thead th {
        /* background: transparent !important; */
        white-space: nowrap;
    }

    table.dataTable thead span.sort-icon {
        display: inline-block;
        padding-left: 5px;
        width: 16px;
        height: 16px;
    }

    table.dataTable thead .sorting span {
        background: url("{!! asset('assets/images/sort-solid2.svg') !!}") no-repeat center right;
        background-size: 13px 13px;
    }

    table.dataTable thead .sorting_asc span {
        background: url("{!! asset('assets/images/sort-up-solid2.svg') !!}") no-repeat center right;
        background-size: 13px 13px;
    }

    table.dataTable thead .sorting_desc span {
        background: url("{!! asset('assets/images/sort-down-solid2.svg') !!}") no-repeat center right;
        background-size: 13px 13px;
    }

    /* table.dataTable thead .sorting_asc_disabled span {
    background: url('assets/images/sort-solid2.svg') no-repeat center right;
}
table.dataTable thead .sorting_desc_disabled span {
    background: url('assets/images/sort-solid2.svg') no-repeat center right;
} */

    table.dataTable thead .sorting::before,
    table.dataTable thead .sorting_asc::before {
        content: "";
        /* content: "▲";
      font-family: "Font Awesome 5 Free"; font-weight: 900; content: "\f062"; */
    }

    table.dataTable thead .sorting::after,
    table.dataTable thead .sorting_desc::after {
        content: "";
        /* content: "▼";
      font-family: "Font Awesome 5 Free"; font-weight: 900; content: "\f063"; */
    }
</style>

{{-- ? ---------------- style untuk custom scrollbar ---------------- --}}
<style>
    /* custom scrollbar */
    ::-webkit-scrollbar {
        width: 12px;
        height: 12px;
    }

    ::-webkit-scrollbar-track {
        background-color: transparent;
    }

    ::-webkit-scrollbar-thumb {
        background-color: #c5cbd3;
        border-radius: 10px;
        border: 3px solid transparent;
        background-clip: content-box;
    }

    ::-webkit-scrollbar-thumb:hover {
        background-color: #6e7e92;
    }

    .swal2-container.swal2-backdrop-show,
    .swal2-container.swal2-noanimation {
        z-index: 11000;
    }
</style>

{{-- ? ---------------- style untuk dropdown menu ---------------- --}}
<style>
    .dropdown-menu {
        z-index: 1060;
    }
</style>

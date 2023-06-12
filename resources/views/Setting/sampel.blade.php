<?php $menu = '2'; ?>
@extends('layouts.backend-Theme-3.app')

<?php $title = 'Sampel'; ?>
@section('container')
    <h4 class="fw-bold py-3"><span class="text-muted fw-light">Home /</span> {{ $title }}</h4>
    <div class="row">
        <div class="col-md-12">

            <ul class="nav nav-pills flex-column flex-md-row mb-3">
                <li class="nav-item">
                    <a class="nav-link {{ $menu === '1' ? 'active' : ' ' }}" id="idmenu1" href="{{ route('setting') }}"><i
                            class="fas fa-arrow-circle-left"></i> Kembali</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ $menu === '2' ? 'active' : ' ' }}" id="idmenu2" data-bs-toggle="tab"
                        href="#"><i class="fas fa-boxes"></i> Buttons</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{ $menu === '4' ? 'active' : ' ' }}" id="idmenu4" data-bs-toggle="tab"
                        href="#"><i class="fas fa-pen-fancy"></i> Forms Layouts</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ $menu === '7' ? 'active' : ' ' }}" id="idmenu7" data-bs-toggle="tab"
                        href="#"><i class="fas fa-pen-alt"></i> Input Group</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ $menu === '3' ? 'active' : ' ' }}" id="idmenu3" data-bs-toggle="tab"
                        href="#"><i class="fas fa-pencil-alt"></i> Forms Input</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ $menu === '5' ? 'active' : ' ' }}" id="idmenu5" data-bs-toggle="tab"
                        href="#"><i class="fas fa-th"></i> Tabel</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ $menu === '6' ? 'active' : ' ' }}" id="idmenu6" data-bs-toggle="tab"
                        href="#"><i class="fas fa-box"></i> Modals</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ $menu === '8' ? 'active' : ' ' }}" id="idmenu8" data-bs-toggle="tab"
                        href="#"><i class="fas fa-users"></i> Publick Function</a>
                </li>



                {{-- <li class="nav-item">
            <a class="nav-link" href="{{ route('register.index') }}" target="_blank" ><i class="bx bxs-plus-circle me-1"></i> Register New User</a>
          </li> --}}

            </ul>
            <div class="" id="menu1" style="background-color: #F5F5F9;">

            </div>
        </div>
    </div>

    <div class="dropdown-menu dropdown-menu-end animate" id="menuklik" style="display:none">
        <div class="text-center fw-bold mb-2" id="judulklik"></div>
        <a class="dropdown-item" id="klikedit" onclick="klikedit()"><span class="tf-icons bx bx-edit"></span>&nbsp;
            Edit</a>
        <a class="dropdown-item" id="klikcetak" onclick="klikcetak()"><span class="tf-icons bx bx-printer"></span>&nbsp;
            Cetak</a>
        <a class="dropdown-item" id="klikinfo" onclick="klikinfo()"><span class="tf-icons bx bx-list-ul"></span>&nbsp;
            Info</a>
        <a class="dropdown-item" id="klikhapus"><span class="tf-icons bx bx-trash"></span>&nbsp; Hapus</a>
    </div>
@endsection

@section('script')
    @yield('scriptMenu')
    <script>
        $(document).ajaxStart(function() {
            $(".preloader").show();
        });
        $(document).ajaxComplete(function() {
            $(".preloader").hide();
        });


        $(document).ready(function() {
            var token = $("meta[name='csrf-token']").attr("content");

            $('.nav-link').click(function() {
                var menu = $(this).attr('id');

                if (menu == "idmenu2") {
                    Buttons();
                } else if (menu == "idmenu3") {
                    forms_basic();
                } else if (menu == "idmenu4") {
                    forms_layouts();
                } else if (menu == "idmenu5") {
                    Tabel();
                } else if (menu == "idmenu6") {
                    modals();
                } else if (menu == "idmenu7") {
                    Input_Group();
                } else if (menu == "idmenu8") {
                    Publick_Function();
                }
            });

            Buttons();
        });

        function Buttons() {
            $.get("{{ url('buttons') }}", function(data) {
                $("#menu1").html(data);
            });
        }

        function forms_basic() {
            $.get("{{ url('forms_basic') }}", function(data) {
                $("#menu1").html(data);
            });
        }

        function forms_layouts() {
            $.get("{{ url('forms_layouts') }}", function(data) {
                $("#menu1").html(data);
            });
        }

        function Tabel() {
            $.get("{{ url('tabel') }}", function(data) {
                $("#menu1").html(data);
            });
        }

        function modals() {
            $.get("{{ url('modals') }}", function(data) {
                $("#menu1").html(data);
            });
        }

        function Input_Group() {
            $.get("{{ url('Input_Group') }}", function(data) {
                $("#menu1").html(data);
            });
        }

        function Publick_Function() {
            $.get("{{ url('Publick_Function') }}", function(data) {
                $("#menu1").html(data);
            });
        }

        // Call the dataTables jQuery plugin
        $(function() {

            $('#dataTable2').DataTable({
                "paging": true,
                "lengthChange": true,
                "searching": true,
                "ordering": true,
                "info": true,
                "autoWidth": true,
                "responsive": true,
            });

        });
    </script>
@endsection

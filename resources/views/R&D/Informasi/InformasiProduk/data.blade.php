<?php $menu = '5'; ?>
<h5 class="card-header">Informasi BoM</h5>
<div class="card-body">
    @section('container')
    <div class="row">
        <ul class="nav nav-pills flex-column flex-md-row mb-3">

                <li class="nav-item">
                    <a class="nav-link {{ $menu === '2' ? 'active' : ' ' }}" id="idmenu2" data-bs-toggle="tab"
                        href="#"><i class="fas fa-boxes"></i> Produk</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ $menu === '3' ? 'active' : ' ' }}" id="idmenu3" data-bs-toggle="tab"
                        href="#"><i class="fas fa-pen-fancy"></i> Kepala</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ $menu === '4' ? 'active' : ' ' }}" id="idmenu4" data-bs-toggle="tab"
                        href="#"><i class="fas fa-pen-alt"></i> Mainan</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ $menu === '5' ? 'active' : ' ' }}" id="idmenu5" data-bs-toggle="tab"
                        href="#"><i class="fas fa-pencil-alt"></i> Komponen</a>
                </li>
            </ul>

        <div class="" id="menu1" style="background-color: #F5F5F9;">

            </div>
    </div>
</div>
@endsection

@section('script')
<script>
    $(document).ajaxStart(function() {
            $(".preloader").show();
        });
        $(document).ajaxComplete(function() {
            $(".preloader").hide();
        });
    $(document).ready(function() {
        $('.nav-link').click(function() {
            var menu = $(this).attr('id');

            if (menu == "idmenu2") {
                Produk();
            } else if (menu == "idmenu3") {
                kepala();
            } else if (menu == "idmenu4") {
                mainan();
            } else if (menu == "idmenu5") {
                komponen();
            }
        });
    });

    function Produk() {
        $.get("{{ url('produk') }}", function(data) {
            $("#menu1").html(data);
        });
    }
    function kepala() {
        $.get("{{ url('kepala') }}", function(data) {
            $("#menu1").html(data);
        });
    }
    function mainan() {
        $.get("{{ url('mainan') }}", function(data) {
            $("#menu1").html(data);
        });
    }
    function komponen() {
        $.get("{{ url('component') }}", function(data) {
            $("#menu1").html(data);
        });
    }

    function getList() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        subka = $("#subkakom").val();
        noseri = $("#nokomp").val();

        data = {subka: subka, noseri: noseri};

        $.ajax({
            type: "POST",
            url: "/RnD/Informasi/InformasiProduk/GetListProdukKomponen",
            dataType: 'json',
            data: data,
            success: function(data) {
                $("#tablekomp tbody").append(data.html);

                $('#tablekomp').DataTable({
                    "paging": true,
                    "lengthChange": false,
                    "pageLength": 9,
                    "searching": true,
                    "ordering": true,
                    "info": false,
                    "autoWidth": true,
                    "responsive": true,
                    "fixedColumns": false,
                    "lengthChange": false
                });
            },
            error: function(xhr, ajaxOptions, thrownError) {
                alert("Data Tidak Ditemukan/Belum Diregistrasi");
            }
        });
    }

    function getListMN() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        subka = $("#subkamn").val();
        noseri = $("#nomn").val();

        data = {subka: subka, noseri: noseri};

        $.ajax({
            type: "POST",
            url: "/RnD/Informasi/InformasiProduk/GetListProdukMainan",
            dataType: 'json',
            data: data,
            success: function(data) {
                $("#tablemn tbody").append(data.html);

                $('#tablemn').DataTable({
                    "paging": true,
                    "lengthChange": false,
                    "pageLength": 9,
                    "searching": true,
                    "ordering": true,
                    "info": false,
                    "autoWidth": true,
                    "responsive": true,
                    "fixedColumns": false,
                    "lengthChange": false
                });                
            },
            error: function(xhr, ajaxOptions, thrownError) {
                alert("Data Tidak Ditemukan/Belum Diregistrasi");
            }
        });
    }

    function getListKpl() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        subka = $("#subkakpl").val();
        noseri = $("#nokepala").val();

        data = {subka: subka, noseri: noseri};

        $.ajax({
            type: "POST",
            url: "/RnD/Informasi/InformasiProduk/GetListProdukKepala",
            dataType: 'json',
            data: data,
            success: function(data) {
                $("#tablekpl tbody").append(data.html);

                $('#tablekpl').DataTable({
                    "paging": true,
                    "lengthChange": false,
                    "pageLength": 9,
                    "searching": true,
                    "ordering": true,
                    "info": false,
                    "autoWidth": true,
                    "responsive": true,
                    "fixedColumns": false,
                    "lengthChange": false
                });                
            },
            error: function(xhr, ajaxOptions, thrownError) {
                alert("Data Tidak Ditemukan/Belum Diregistrasi");
            }
        });
    }

</script>
@endsection

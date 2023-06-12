@extends('layouts.backend-Theme-3.app')

<?php $title = 'Pengumuman'; ?>

@section('css')
<style>
</style>
@endsection

@section('container')
<h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Home /</span> {{ $title }}</h4>

<div class="row">
    <div class="col-md-12">

        <div class="card mb-4">

            <div class="card-body">
                <button class="btn btn-primary mb-2" onclick="kliktambah()">Buat Pengumuman</button>

                <div class="table-responsive" style="height:calc(100vh - 385px);">
                    <form id="formfilter1" autocomplete="off">
                        <table class="table table-border table-hover table-sm" id="tabel0">

                            <thead class="table-secondary sticky-top zindex-2 text-center">
                                <tr>
                                    <th width='2%'>No.</th>
                                    <th width='10%'>name</th>
                                    <th width='8%'>Trans Date</th>
                                    <th>Note</th>
                                    <th width='8%'>Announce To</th>
                                    <th width='5%'>Valid To Date</th>
                                </tr>
                                <tr class="baris">

                                    <td></td>
                                    <td><input type="text" class="form-control form-control-sm fs-6 w-100"
                                            name="UserName" value=""></td>
                                    <td><input type="date" class="form-control form-control-sm fs-6 w-100"
                                            name="TransDate" value=""></td>
                                    <td><input type="text" class="form-control form-control-sm fs-6 w-100" name="Note"
                                            value=""></td>
                                    <td><input type="text" class="form-control form-control-sm fs-6 w-100"
                                            name="AnnounceTo" value=""></td>
                                    <td><input type="date" class="form-control form-control-sm fs-6 w-100"
                                            name="ValidToDate" value=""></td>
                                </tr>
                            </thead>

                            <tbody id="isidata">
                                @forelse ($datas as $data)
                                <tr class="klik" id="{{ $data->ID }}">
                                    <td>{{ ($datas->currentPage() - 1) * $datas->perPage() + $loop->iteration }}
                                    </td>
                                    <td> {{ $data->UserName }} </td>
                                    <td> {{ date('d-m-y', strtotime($data->TransDate)) }} </td>
                                    <td> {{ $data->Note }}</td>
                                    <td> {{ $data->SW }}</td>
                                    <td> {{ date('d-m-y', strtotime($data->ValidToDate)) }}</td>
                                </tr>
                                @empty

                                @endforelse
                            </tbody>
                        </table>
                    </form>
                    <input type="hidden" id="nama" value="{{ Auth::user()->name }}">
                </div>
                {{ $datas->links('pagination::bootstrap-4') }}
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

@include('setting.PengumumanModal')
@endsection

@section('script')
<script>
    $(document).ready(function() {
            $('#tabel0').DataTable({
                "paging": false,
                "lengthChange": false,
                "searching": false,
                "ordering": false,
                "info": false,
                "autoWidth": false,
                "responsive": true,
                "fixedColumns": false,
            });
        });

        // -------------------- menu klik --------------------
        $(".klik").on('click', function(e) {
            $('.klik').css('background-color', 'white');

            if ($("#menuklik").css('display') == 'block') {
                $(" #menuklik ").hide();
            } else {
                var top = e.pageY + 15;
                var left = e.pageX - 100;
                var id = $(this).attr('id');
                var id2 = $(this).attr('id2');
                var id3 = $(this).attr('id3');
                var status = $(this).children("td").children("span").text();

                // alert(id +'|'+ id2+'|'+ id3+'|'+status);

                var nama = $('#nama').val();
                $(".judulklik").html(id2);
                $(".judulklik").attr('id', id);

                if ((nama.toLowerCase() == id3.toLowerCase()) && (status != 'S')) {
                    $(this).css('background-color', '#f4f5f7');
                    $("#menuklik").css({
                        display: "block",
                        top: top,
                        left: left
                    });
                } else if (status == 'S') {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Oops...',
                        text: 'Todo yang Status Selesai tidak dapat dirubah lagi!'
                    });
                }
            }
            return false;

        });

        //sembunyikan menu kilk
        $("body").on("click", function() {
            if ($("#menuklik").css('display') == 'block') {
                $(" #menuklik ").hide();
            }
            $('.klik').css('background-color', 'white');
        });

        //-------------------- klik di pencarian --------------------
        $('.baris').change(function(e) {
            var formData = $('#formfilter1').serialize();
            $.get('/pengumuman/filter/' + formData, function(data) {
                if (data == '0') {
                    window.location.replace('/Pengumuman');
                } else {
                    $('.pagination').addClass('d-none');
                    $("#isidata").html(data);
                }
            });
        });

        //-------------------- klik di menu klik --------------------
        var selector = '.di1';
        $(selector).on('click', function() {
            id = $('.judulklik').attr('id');
            id2 = $(this).attr('id');
            // alert(id + '|' + id2);

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
                    Swal.fire({
                        icon: 'success',
                        title: 'success!',
                        text: 'Update Status Berhasil!'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // location.reload();
                            $('#' + id).children("td").children("span").attr("class", data
                                .badge).text(data.status);
                            $('#' + id).children(".tglupd").text(data.tgl);
                        }
                    });
                }
            });

        });

        // buka modal tambah
        function kliktambah() {
            $("#jodulmodal1").html('Tambah Pengumuman');
            $('#modalformat').attr('class', 'modal-dialog');
            $("#simpan1").removeClass('d-none');
            $('#simpan1').val('Tambah');

            $.get('/Pengumuman/announceto', function(data) {
                $("#AnnounceTopp").html(data);
                $('#modal1').modal('show');
            });
            $('#modal1').modal('show');
        }

        //simpan 
        function KlikSimpan1() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            var formData = $('#formmodal1').serialize();
            $.ajax({
                type: "POST",
                url: '/pengumuman/tambah',
                data: formData,
                dataType: 'json',
                success: function(data) {

                    Swal.fire({
                        icon: 'success',
                        title: 'success',
                        text: 'Tambah Berhasil!'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $('#modal1').modal('hide');
                            // location.href = "/Pengumuman";
                            location.reload();
                        }
                    });

                }
            });
        }
</script>
@endsection
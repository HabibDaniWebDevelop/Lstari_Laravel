<?php
$iduser = Session::get('iduser');

?>
@extends('layouts.backend-Theme-3.app')

@section('css')
    <style>
    </style>
@endsection

<?php $title = 'TO DO List'; ?>
@section('container')
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Home /</span> {{ $title }}</h4>

    <div class="row">
        <div class="col-md-12">

            <div class="card">

                <div class="card-body">
                    <button class="btn btn-primary mb-2" onclick="kliktambah()">Buat To Do List</button>
                    <form id="formfilter1" autocomplete="off">
                        <div class="table-responsive" style="height:calc(100vh - 360px);">
                            <table class="table table-border table-hover table-sm w-100" id="tabel0">
                                <thead class="table-secondary sticky-top zindex-2 text-center">
                                    <tr>
                                        <th width='2%'>No.</th>
                                        <th width='80'>name</th>
                                        <th width='80'>creator</th>
                                        <th>todo</th>
                                        <th>remarks</th>
                                        <th width='10'>status</th>
                                        <th width='10'>Priority</th>
                                        <th width='10'>Tanggal</th>
                                        <th width='10'>Target</th>
                                        <th width='10'>Update</th>
                                    </tr>
                                    <tr class="baris">

                                        <th class="m-0 p-1"></th>
                                        <th class="m-0 p-1"><input type="text"
                                                class="form-control form-control-sm fs-6 w-100" name="name"
                                                value=""></th>
                                        <th class="m-0 p-1"><input type="text"
                                                class="form-control form-control-sm fs-6 w-100" name="todocreator"
                                                value=""></th>
                                        <th class="m-0 p-1"><input type="text"
                                                class="form-control form-control-sm fs-6 w-100" name="todo"
                                                value=""></th>
                                        <th class="m-0 p-1"><input type="text"
                                                class="form-control form-control-sm fs-6 w-100" name="remarks"
                                                value=""></th>
                                        <th class="m-0 p-1">
                                            <select class="form-select form-select-sm fs-6 w-100" name="status">
                                                <option value=""> </option>
                                                <option value="A">Antrian</option>
                                                <option value="P">Proses</option>
                                                <option value="T">Tunda</option>
                                                <option value="S">Selesai</option>
                                            </select>
                                        </th>
                                        <th class="m-0 p-1">
                                            <select class="form-select form-select-sm fs-6 w-100" name="Priority">
                                                <option value=""> </option>
                                                <option value="Biasa">Biasa</option>
                                                <option value="Penting">Penting</option>
                                                <option value="Darurat">Darurat</option>
                                            </select>
                                        </th>
                                        <th class="m-0 p-1">
                                            <input type="date" class="form-control form-control-sm fs-6"
                                                style="width: 120px !important;" name="tododate" value="">
                                        </th>

                                        <th class="m-0 p-1">
                                            <input type="date" class="form-control form-control-sm fs-6"
                                                style="width: 120px !important;" name="Targethate" value="">
                                        </th>

                                        <th class="m-0 p-1">
                                            <input type="date" class="form-control form-control-sm fs-6"
                                                style="width: 120px !important;" name="updatestatus" value="">
                                        </th>
                                    </tr>
                                </thead>
                                <tbody id="isidata">


                                    @forelse ($datas as $data)
                                        <?php $status = $data->status;
                                        
                                        if ($data->updatestatus != '') {
                                            $updatestatus = date('d-m-y', strtotime($data->updatestatus));
                                        } else {
                                            $updatestatus = '';
                                        }
                                        
                                        if ($data->TargetDate != '') {
                                            $TargetDate = date('d-m-y', strtotime($data->TargetDate));
                                        } else {
                                            $TargetDate = '';
                                        }
                                        ?>


                                        <tr class="klik" id="{{ $data->id }}" id2="{{ $data->todo }}"
                                            id3="{{ $data->name }}">
                                            <td>{{ ($datas->currentPage() - 1) * $datas->perPage() + $loop->iteration }}
                                            </td>
                                            <td> {{ $data->name }} </td>
                                            <td class="todocreator">{{ $data->todocreator }}</td>
                                            <td>{{ $data->todo }}</td>
                                            <td>{{ $data->remarks }}</td>
                                            <td align="center"><span
                                                    class="badge {!! $status == 'A'
                                                        ? 'bg-secondary'
                                                        : ($status == 'P'
                                                            ? 'bg-warning'
                                                            : ($status == 'T'
                                                                ? 'bg-danger'
                                                                : 'bg-success')) !!}">{{ $data->status }}</span></td>
                                            <td align="center">{{ $data->Priority }}</td>
                                            <td>{{ date('d-m-y', strtotime($data->tododate)) }}</td>
                                            <td>{!! $TargetDate !!}</td>
                                            <td class="tglupd">{!! $updatestatus !!}</td>
                                        </tr>
                                    @empty
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </form>
                    <input type="hidden" id="tglnow" value="{{ date('d-m-y') }}">
                    <input type="hidden" id="nama" value="{{ Auth::user()->name }}">
                    {{ $datas->links('pagination::bootstrap-4') }}
                </div>

            </div>

        </div>
    </div>

    <div class="dropdown-menu dropdown-menu-end animate" id="menuklik" style="display:none">
        <div class="text-center fw-bold mb-2 judulklik"></div>
        <div id="tomboledit">
            <hr class="my-1">
            <a class="dropdown-item di1 fw-bold" id="edit" id2=''><span
                    class="tf-icons bx bx-edit"></span>&nbsp; Edit</a>
        </div>

        <hr class="my-1">
        <a class="dropdown-item di1" id="P"><span class="badge bg-warning">P</span>&nbsp; Proses</a>
        <a class="dropdown-item di1" id="T"><span class="badge bg-danger">T</span>&nbsp; Tunda</a>
        <a class="dropdown-item di1" id="S"><span class="badge bg-success">S</span>&nbsp; Selesai</a>
    </div>

    @include('setting.todolistModal')
@endsection

@section('script')
    <script>
        $(document).ready(function() {
            $('#tabel0').DataTable({
                "paging": false,
                "lengthChange": true,
                "searching": false,
                "ordering": false,
                "info": false,
                "autoWidth": true,
                "responsive": true,
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
                var todocreator = $(this).children('.todocreator').text();

                // alert(id +'|'+ id2+'|'+ id3+'|'+status+'|'+ todocreator);

                var nama = $('#nama').val();
                $(".judulklik").html(id2);
                $(".judulklik").attr('id', id);
                $("#tomboledit").addClass('d-none');
                // $("tomboledit").attr('id', id);

                var edit = '0';
                if ((nama.toLowerCase() == todocreator.toLowerCase()) && status == 'A') {
                    $("#tomboledit").removeClass('d-none');
                    edit = '1';
                }

                if (((nama.toLowerCase() == id3.toLowerCase()) || (edit == '1')) && (status != 'S')) {
                    $(this).css('background-color', '#f4f5f7');

                    $("#menuklik").css({
                        display: "block",
                        top: top,
                        left: left,
                        width: '250px'
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
            $.get('/todolist/filter/' + formData, function(data) {
                if (data == '0') {
                    window.location.replace('/todolist');
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

            if (id2 == 'edit') {
                $.get('/todolist/update/' + id, function(data) {
                    $('#formmodal1').trigger("reset");
                    $('#name').val(data.name);
                    $('#tododate').val(data.tododate);
                    $('#status').val(data.status);
                    $('#todo').val(data.todo);
                    $('#remarks').val(data.remarks);
                    $('#TargetDate').val(data.TargetDate);
                    $('#Priority').val(data.Priority);
                    $('#idfield').val(data.id);

                    $.get('/todolist/name', function(data) {
                        $("#todolistname").html(data);
                        $('#modal1').modal('show');
                    });

                    $("#jodulmodal1").html('Update Todo List');
                    $('#modalformat').attr('class', 'modal-dialog');
                    $('#simpan1').val('update');
                    $('#modal1').modal('show');
                });
            } else {
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
                            text: 'Update Status Berhasil!',
                            showConfirmButton: false,
                            timer: 1200
                        }).then((result) => {
                            // location.reload();
                            tglnow = $('#tglnow').val();
                            $('#' + id).children("td").children("span").attr("class", data
                                .badge).text(data.status);
                            $('#' + id).children(".tglupd").text(tglnow);
                        });
                    }
                });
            }


        });

        // buka modal tambah
        function kliktambah() {
            $("#jodulmodal1").html('Tambah Todo List');
            $('#modalformat').attr('class', 'modal-dialog');
            $('#simpan1').val('Tambah');
            $('#formmodal1').trigger("reset");

            $.get('/todolist/name', function(data) {
                $("#todolistname").html(data);
                $('#modal1').modal('show');
            });
        }

        //simpan 
        function KlikSimpan1() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            var formData = $('#formmodal1').serialize();

            var formtype = $('#simpan1').val();
            var idfield = $('#idfield').val();

            if (formtype == "Tambah") {
                var type = "POST";
                var ajaxurl = '/todolist/tambah';
            }

            if (formtype == "update") {
                type = "PUT";
                ajaxurl = '/todolist/edit2/' + idfield;
            }

            $.ajax({
                type: type,
                url: ajaxurl,
                data: formData,
                dataType: 'json',
                success: function(data) {

                    Swal.fire({
                        icon: 'success',
                        title: 'success!',
                        text: 'input berhasil',
                        showConfirmButton: false,
                        timer: 1200
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $('#modal1').modal('hide');
                            location.href = "/todolist";
                        }
                    });

                }
            });
        }
    </script>
@endsection

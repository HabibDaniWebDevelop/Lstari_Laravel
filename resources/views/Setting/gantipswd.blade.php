<!doctype html>
<html lang="en" class="light-style" dir="ltr" data-theme="theme-default" data-assets-path="assets/sneatV1/assets/"
    data-template="horizontal-menu-template-dark">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <link rel="icon" type="image/png" sizes="16x16" href="{!! asset('assets/images/favicon.png') !!}">
    <title>Ganti Password</title>

    <link rel="stylesheet" href="assets/sneatV1/assets/vendor/css/pages/page-auth.css" />

    @include('layouts.backend-Theme-3.stylesheet')

</head>

<body class="hold-transition register-page"
    style="background-image : url('{!! asset('assets/images/login6.jpg') !!}'); background-repeat: no-repeat; background-attachment: fixed; background-position: center;">

    <div class="bs-toast toast bg-dark toast-placement-ex top-20 end-0 m-3" id="myToast">
        <div class="toast-header">
            <strong class="me-auto"><i class="bi-gift-fill"></i> Perhatian!</strong>
            <button type="button" class="btn-close" data-bs-dismiss="toast"></button>
        </div>
        <div class="toast-body">
            Password baru tidak boleh sama dengan password lama dan user name</a>
        </div>
    </div>


    <div class="container-xl">
        <div class="authentication-wrapper authentication-basic container-p-y">
            <div class="authentication-inner">

                <section id="wrapper">
                    <div class="row">
                        <h2><a href="login"><b>PT. Lestari Mulia Sentosa</b> </a></h2>
                        <div class="nav-align-top mb-4">
                            <div class="tab-content">
                                <div class="mb-4 text-center fs-4 fw-bold"> Ganti Password </div>
                                <div class="tab-pane fade show active" id="pilih1" role="tabpanel">

                                    <form id="forminput1" method="POST" onsubmit="return false">
                                        <div class="row">
                                            <div class="mb-3 col-md-12">
                                                <label class="form-label">Nama Pengguna</label>
                                                <div class="input-group">
                                                    <input class="form-control" type="text" id="name1"
                                                        data-index="1" disabled value="{{ Auth::user()->name }}" />
                                                    <input type="hidden" name="name" id="name"
                                                        value="{{ Auth::user()->name }}" />
                                                    <input type="hidden" name="id" id="id"
                                                        value="{{ Auth::user()->id }}" />
                                                    <span class="input-group-text">
                                                        <i class="fas fa-user-tie cursor-pointer"></i>
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="mb-3 col-md-12">
                                                <label for="email" class="form-label">Password Lama</label>
                                                <div class="input-group">
                                                    <input type="password" class="form-control" name="password"
                                                        id="password" autofocus data-index="2" />
                                                    <span class="input-group-text cursor-pointer" onclick="tblpsw(1)">
                                                        <i class="bx bx-hide" id="tblpsw1"></i></span>
                                                </div>
                                            </div>
                                            <div class="mb-3 col-md-12">
                                                <label for="organization" class="form-label">Password Baru</label>
                                                <div class="input-group">
                                                    <input type="password" class="form-control" id="passwordbaru"
                                                        name="passwordbaru" data-index="3" />
                                                    <span class="input-group-text cursor-pointer" onclick="tblpsw(2)">
                                                        <i class="bx bx-hide" id="tblpsw2"></i></span>
                                                </div>
                                            </div>
                                            <div class="mb-3 col-md-12">
                                                <label for="organization" class="form-label">Konfirmasi Password
                                                    Baru</label>
                                                <div class="input-group">
                                                    <input type="password" class="form-control" id="passwordbaru2"
                                                        name="passwordbaru2" data-index="4" />
                                                    <span class="input-group-text cursor-pointer" onclick="tblpsw(3)">
                                                        <i class="bx bx-hide" id="tblpsw3"></i></span>
                                                </div>

                                            </div>


                                        </div>
                                        <div class="mt-2">
                                            <button type="submit" class="btn btn-primary me-2"
                                                onclick="proses()">Save
                                                changes</button>
                                            <button type="reset" class="btn btn-outline-secondary"
                                                onClick="window.location.reload()">Cancel</button>
                                        </div>
                                    </form>

                                </div>


                            </div>
                        </div>

                    </div>

                </section>
            </div>
        </div>
    </div>

    @include('layouts.backend-Theme-3.javascript')

    <script>
        $(document).ready(function() {

            $("#myToast").toast({
                delay: 2500
            });
            $("#myToast").toast("show");

            $('#forminput1').on('keydown', 'input', function(event) {
                if (event.which == 13) {
                    event.preventDefault();
                    var $this = $(event.target);
                    var index = parseFloat($this.attr('data-index'));
                    $('[data-index="' + (index + 1).toString() + '"]').focus();
                    if (index == '4') {
                        proses();
                    }
                }
            });

        });

        function tblpsw(id) {
            if (id == 1 && $('#password').attr('type') == 'password') {
                $("#password").attr("type", "text");
                $("#tblpsw1").attr("class", "bx bx-show");
            } else if (id == 1) {
                $("#password").attr("type", "password");
                $("#tblpsw1").attr("class", "bx bx-hide");
            }

            if (id == 2 && $('#passwordbaru').attr('type') == 'password') {
                $("#passwordbaru").attr("type", "text");
                $("#tblpsw2").attr("class", "bx bx-show");
            } else if (id == 2) {
                $("#passwordbaru").attr("type", "password");
                $("#tblpsw2").attr("class", "bx bx-hide");
            }

            if (id == 3 && $('#passwordbaru2').attr('type') == 'password') {
                $("#passwordbaru2").attr("type", "text");
                $("#tblpsw3").attr("class", "bx bx-show");
            } else if (id == 3) {
                $("#passwordbaru2").attr("type", "password");
                $("#tblpsw3").attr("class", "bx bx-hide");
            }
        }

        function proses() {
            var password = $("#password").val();
            var passwordbaru = $("#passwordbaru").val();
            var passwordbaru2 = $("#passwordbaru2").val();
            var name = $("#name").val();

            if (password == passwordbaru) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Oops...',
                    text: 'passwor lama dan baru tidak boleh sama !',
                    confirmButtonColor: "#913030"
                });
            } else if (password.length == "" || passwordbaru.length == "") {
                Swal.fire({
                    icon: 'warning',
                    title: 'Oops...',
                    text: 'kolom password tidak boleh kosong !',
                    confirmButtonColor: "#913030"
                });
            } else if (passwordbaru != passwordbaru2) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Oops...',
                    text: 'Confirmation Password Baru Salah !',
                    confirmButtonColor: "#913030"
                });
            } else if (name.toLowerCase() == passwordbaru.toLowerCase()) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Oops...',
                    text: 'password tidsak boleh sama dengan nama !',
                    confirmButtonColor: "#913030"
                });
            } else {

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                var id = $("#id").val();
                var formData = $('#forminput1').serialize();
                // alert(formData);

                var type = "PUT";
                var ajaxurl = '/gantipswd/' + id;
                $.ajax({
                    type: type,
                    url: ajaxurl,
                    data: formData,
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {

                            if (response.message == 'pswsalah') {
                                Swal.fire({
                                    icon: 'warning',
                                    title: 'Input Gagal!',
                                    text: 'Password lama salah!',
                                    confirmButtonColor: "#913030"
                                });
                                $("#password").val();
                            } else {
                                Swal.fire({
                                        icon: 'success',
                                        title: 'Success',
                                        text: 'Ganti Password Berhasil!',
                                        confirmButtonColor: "#913030"
                                    })
                                    .then(function() {
                                        window.location.href = "/logout";
                                    });
                            }
                        } else {
                            Swal.fire({
                                    icon: 'error',
                                    title: 'Error!',
                                    text: 'silahkan coba lagi!',
                                    confirmButtonColor: "#913030"
                                })
                                .then(function() {
                                    window.location.href = "/gantipswd";
                                });
                        }
                        console.log(response);
                    }
                });
            }
        }
    </script>

</body>

</html>

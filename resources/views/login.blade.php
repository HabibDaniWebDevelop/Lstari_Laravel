<!doctype html>
<html lang="en" 
  class="light-style" 
  dir="ltr" 
  data-theme="theme-default" 
  data-assets-path="assets/sneatV1/assets/" 
  data-template="horizontal-menu-template-dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <link rel="icon" type="image/png" sizes="16x16" href="{!! asset('assets/images/favicon.png') !!}">
    <title>Login Akun</title>

        <!-- Google Font: Source Sans Pro -->
        {{-- <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback"> --}}
        <!-- login css -->
        <link rel="stylesheet" href="assets/sneatV1/assets/vendor/css/pages/page-auth.css" />

        @include('layouts.backend-Theme-3.stylesheet')

        
</head>
<body class="hold-transition register-page" style="background-image : url('{!! asset('assets/images/login6.jpg') !!}'); background-repeat: no-repeat; background-attachment: fixed; background-position: center;" >

    <div class="container-xl">
        <div class="authentication-wrapper authentication-basic container-p-y">
            <div class="authentication-inner">

                <section id="wrapper">
                    <div class="row">
                        <h2><a href="login"><b>PT. Lestari Mulia Sentosa</b> </a></h2>
                        <div class="nav-align-top mb-4">
                            <ul class="nav nav-tabs nav-fill" role="tablist">
                                <li class="nav-item">
                                    <button
                                        type="button"
                                        class="nav-link active"
                                        role="tab"
                                        data-bs-toggle="tab"
                                        data-bs-target="#pilih1"
                                        aria-controls="pilih1"
                                        aria-selected="true"
                                    >
                                        Login 
                                    </button>
                                </li>
                                <li class="nav-item">
                                    <button
                                        type="button"
                                        class="nav-link"
                                        role="tab"
                                        data-bs-toggle="tab"
                                        data-bs-target="#pilih2"
                                        aria-controls="pilih2"
                                        aria-selected="false"
                                    >
                                        Scan QR
                                    </button>
                                </li>

                            </ul>
                            <div class="tab-content">
                                <div class="tab-pane fade show active" id="pilih1" role="tabpanel">
                                    <div class="input-group mb-3">
                                        {{-- @csrf --}}
                                        <input type="hidden" id="prev" value="{{ $prev }}">
                                        <input type="text" class="form-control" name="name" id="name" autofocus required placeholder="Masukkan Nama" onchange="NF('password')">
                                        <span class="input-group-text" id="text-to-speech-addon">
                                            <i class="fas fa-user-tie cursor-pointer"></i>
                                        </span>
                                    </div>
                                    <div class="input-group mb-3">
                                        <input type="password" class="form-control" name="password" id="password" required placeholder="Masukkan Password" onchange="proses()">
                                        <span class="input-group-text" id="text-to-speech-addon">
                                            <i class="fas fa-lock cursor-pointer"></i>
                                        </span>
                                    </div>

                                    <div class="form-group text-center m-t-20">
                                        <div class="col-xs-12">
                                            <button type="submit" id="submit" class="btn btn-login btn-primary" onclick="proses()">Masuk</button>
                                        </div>
                                    </div>     
                                </div>
                                <div class="tab-pane fade" id="pilih2" role="tabpanel">
                                    <div class="input-group mb-3">
                                        <input type="password" class="form-control" name="qr" id="qr" autocomplete="off" required placeholder="Scan QR Code" onchange="QR()">
                                        <span class="input-group-text" id="text-to-speech-addon">
                                            <i class="fas fa-qrcode cursor-pointer"></i>
                                        </span>
                                    </div>
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

        function NF(ID){
            document.getElementById(ID).focus();
        }

        function QR(){
            document.getElementById('qr').focus();
            Swal.fire({
                icon: 'warning',
                title: 'Oops...',
                text: 'fungsi belum tersedia!'
            });
        }

        function proses() {
            var name = $("#name").val();
            var password = $("#password").val();
            var prev = $("#prev").val();
            var token = $('meta[name="csrf-token"]').attr('content');

            if(name.length == "") {
                Swal.fire({
                    icon: 'warning',
                    title: 'Oops...',
                    text: 'Nama Wajib Diisi !',
                    confirmButtonColor: "#913030"
                });
            } else if(password.length == "") {
                Swal.fire({
                    icon: 'warning',
                    title: 'Oops...',
                    text: 'Password Wajib Diisi !',
                    confirmButtonColor: "#913030"
                });
            } else {

                $.ajax({
                    url: "{{ route('login.check_login') }}",
                    type: "POST",
                    dataType: "JSON",
                    cache: false,
                    data: {
                        "name": name,
                        "password": password,
                        "prev": prev,
                        "_token": token
                    },

                    success:function(response){
                        if (response.success) {

                            if(response.message=='resetpassword'){
                                window.location.href = "/gantipswd";
                            }
                            else if(response.message=='NonActive'){
                                Swal.fire({
                                icon: 'error',
                                title: 'Errorl!!',
                                text: 'User Sudah Tidak Aktif!!',
                                confirmButtonColor: "#913030"
                                })
                                .then (function() {
                                    window.location.href = "/logout";
                                });
                                
                            }
                            else{
                                window.location.href = response.message;
                            }                            
                        } 
                        else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Login Gagal!',
                                text: 'silahkan coba lagi!'
                            })
                            .then (function() {
                                window.location.href = "/";
                            });
                        }
                        console.log(response);
                    }
                    // ,
                    // error:function(response){
                    //     Swal.fire({
                    //         icon: 'error',
                    //         title: 'Login Gagal!!',
                    //         text: 'silahkan coba lagi!!',
                    //         confirmButtonColor: "#913030"
                    //     })
                    //     .then (function() {
                    //         window.location.href = "/";
                    //     });
                    //     console.log(response);
                    // }
                });
            }
        }

    </script>

</body>
</html>

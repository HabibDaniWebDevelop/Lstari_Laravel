<?php $menu = '1'; ?>
@extends('layouts.backend-Theme-3.app')

<?php $title = 'Tes Posting TM'; ?>
@section('container')
    <h4 class="fw-bold py-3"><span class="text-muted fw-light">Home /</span>{{ $title }}</h4>
    <div class="row">
        <div class="col-12">

            <div class="card">
                <div class="card-body" style="min-height:calc(100vh - 255px);">

                    <div class="demo-inline-spacing" id="btn-menu">

                        <button type="button" class="btn btn-primary" id="Baru1" disabled onclick="Klik_Baru1()"> <span
                                class="tf-icons bx bx-plus-circle"></span>&nbsp; Baru </button>

                        <button type="button" class="btn btn-primary me-4" id="Ubah1" disabled=""
                            onclick="Klik_Ubah1()">
                            <span class="tf-icons bx bx-edit"></span>&nbsp; Ubah</button>

                        <button type="button" class="btn btn-danger" id="Batal1" onclick="Klik_Batal1()"> <span
                                class="fas fa-times-circle"></span>&nbsp; Batal</button>

                        <button type="button" class="btn btn-warning" id="Simpan1" value="" disabled
                            onclick="Klik_Simpan1()">
                            <span class="tf-icons bx bx-save"></span>&nbsp; Simpan</button>

                        <button type="button" class="btn btn-dark me-4" id="Posting1" onclick="Klik_Posting1()"> <span
                                class="tf-icons bx bx-send"></span>&nbsp; Posting</button>

                        <button type="button" class="btn btn-info" id="Cetak1" value="" disabled
                            onclick="Klik_Cetak1()">
                            <span class="tf-icons bx bx-printer"></span>&nbsp; Cetak</button>

                        <div class="d-flex float-end">

                            <div class="position-absolute d-none" id="postinglogo" style="right: 300px; top: 10px; ">
                                <img src="{!! asset('assets/images/posting.jpg') !!}"
                                    style="width: 250px; object-fit: cover; object-position: top;">
                            </div>

                            <div class="input-group input-group-merge" style="width: 200px;">
                                <span class="input-group-text"><i class="bx bx-search"
                                        onclick="klikViewSelection()"></i></span>
                                <input type="search" class="form-control" list="carilist" autofocus id='cari'
                                    onchange="ChangeCari('0')" onClick="this.select();" placeholder="search...">
                            </div>
                            <datalist class="text-warning" id="carilist">
                                {{-- @foreach ($hiscaris as $hiscari)
                                <option value="{{ $hiscari->HistList }}">
                                  @endforeach --}}
                            </datalist>

                        </div>

                    </div>
                    <hr class="mx-0 my-3" />

                    <div class="row">
                        <div class="col-4 mb-3">
                            <label class="form-label">id</label>
                            <input type="text" class="form-control" id="id" value="2301074490" />
                        </div>
                        <div class="col-4 mb-3">
                            <label class="form-label">user name</label>
                            <input type="text" class="form-control" id="UserName" value="{{ Auth::user()->name }}" />
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        $(document).ready(function($) {

            $('body').on('click', '#Posting1', function() {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                var formData = {

                    LinkID: $('#id').val(),
                    UserName: $('#UserName').val(),
                };

                $.ajax({
                    url: "/TespostingTM",
                    type: "POST",
                    dataType: "JSON",
                    cache: false,
                    data: formData,
                    success: function(data) {
                        if (data.success) {
                            Swal.fire({
                                icon: 'success',
                                title: data.message,
                                text: 'Silahkan Cek Stock Untuk Memastikan',
                            })
                        }
                        else{
                            Swal.fire({
                            icon: 'error',
                            title: data.message,
                            text: 'Silahkan Cek Ulang Data!'
                            });
                        }
                        console.log(data);
                    },
                    // error: function(data) {
                    //     Swal.fire({
                    //         icon: 'error',
                    //         title: 'terjadi masalah sistem',
                    //         text: 'silahkan Hubungi IT untuk Mengecek!'
                    //     });
                    //     console.log(data);
                    // }
                });

            });

        });

        function Klik_Batal1() {
        location.reload();
        }
    </script>
@endsection

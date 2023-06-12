<?php $menu = '1'; ?>
@extends('layouts.backend-Theme-3.app')

<?php $title = 'Tes Posting'; ?>
@section('container')
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Home /</span>{{ $title }}</h4>
    <div class="row mb-4">
        <div class="col-md-12">

            <!-- Basic Layout -->
            <div class="row">
                <div class="col-md-12 col-lg-10 col-xl-8 col-xxl-6">
                    <div class="card mb-4">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">Basic Layout</h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label">Nama Tabel </label> <b> (workallocation, workcompletion,
                                    transferrm, waxstoneusage) </b>
                                <input type="text" class="form-control" id="tabel" value="workallocation" />
                                {{-- <input type="text" class="form-control" id="UserName" value="{{ Auth::user()->name }}" /> --}}
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Id Tabel</label>
                                <input type="text" class="form-control" id="id" value="113813" />
                            </div>


                            <div class="text-end">
                                <button class="btn btn-primary" id="send-data">Posting</button>
                            </div>

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

            $('body').on('click', '#send-data', function() {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                var formData = {

                    tabel: $('#tabel').val(),
                    id: $('#id').val(),
                };

                $.ajax({
                    url: "/tespostingnew",
                    type: "POST",
                    dataType: "JSON",
                    cache: false,
                    data: formData,
                    success: function(data) {
                        if (data.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Success',
                                text: data.message,
                                showConfirmButton: false,
                                timer: 1200
                            })
                        } else {
                            // jika validasi gagal
                            var errorMessages = "";
                            Object.entries(data.message).forEach(([key, value]) => {
                                errorMessages += `${key}: ${value.join(". ")}<br />`;
                            });
                            Swal.fire({
                                icon: "error",
                                title: "Upss Error 2!",
                                html: errorMessages,
                            });
                            console.log("Error:", data.message);
                        }
                    },
                    error: function(data) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Upss Error !',
                            text: data.responseJSON.message
                        })
                        console.log('Error:', data);
                    }
                });

            });

        });
    </script>
@endsection

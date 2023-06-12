<?php $title = 'Informasi Recipient'; ?>
@extends('layouts.backend-Theme-3.app')
@section('title', '$title')

@section('Dashboard')
    <h2 class="m-0">{{ $title }}</h2>
    <ol class="breadcrumb sm-2">
        <li class="breadcrumb-item"><a href="/">Home </a></li>
        <li class="breadcrumb-item">Lain-Lain </li>
        <li class="breadcrumb-item">Korespondensi </li>
        <li class="breadcrumb-item ">Surat Jalan </li>
        <li class="breadcrumb-item active">Recipient</li>
    </ol>
@endsection

@section('css')

    <style>

    </style>

@endsection

@section('container')
    <div class="row">
        <div class="col-md-12">
            <div class="card mb-4">

                <div class="card-body">
                    <div>
                        <table class="table table-borderless table-striped table-sm" id="tabel1">
                            <thead class="table-secondary">
                                <tr style="text-align: center">
                                    <th> No </th>
                                    <th> Tujuan </th>
                                    <th> Alamat </th>
                                    <th> Option </th>
                                </tr>
                            </thead>
                            <tbody class="text-center">
                                @foreach ($dataRecipient as $item)
                                    <tr>
                                        <td>{{$loop->iteration}}</td>
                                        <td>{{$item->value}}</td>
                                        <td>{{$item->Address}}</td>
                                        <td>
                                            <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#editRecipientModel{{$loop->iteration}}">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="btn btn-danger" onclick="DeleteRecipient({{$item->ID}})"><i class="fa fa-trash" aria-hidden="true"></i></button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                
            </div>
        </div>
    </div>

    @foreach ($dataRecipient as $item)
    <div class="modal fade" id="editRecipientModel{{$loop->iteration}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Edit {{$item->value}}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <span for="idRecipient" class="form-label">id</span>
                        <input type="text" class="form-control" id="idRecipient_{{$loop->iteration}}" disabled value="{{$item->ID}}">
                    </div>
                    <div class="form-group">
                        <span for="recipient" class="form-label">Recipient</span>
                        <input type="text" class="form-control" id="recipient_{{$loop->iteration}}" value="{{$item->value}}">
                    </div>
                    <div class="form-group">
                        <span for="address" class="form-label">Alamat</span>
                        <input type="text" class="form-control" id="address_{{$loop->iteration}}" value="{{$item->Address}}">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button class="btn btn-primary" onclick="EditRecipient({{$loop->iteration}})">Save changes</button>
                </div>
            </div>
        </div>
    </div>
    @endforeach
@endsection

@section('script')
    <script>
        function DeleteRecipient(idRecipient) {
            // hit backend
            // Setup CSRF TOKEN
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                type: "DELETE",
                url: "/Lain-lain/Korespondensi/SuratJalan/listrecipient",
                data:{idRecipient:idRecipient},
                dataType: 'json',
                beforeSend: function () {
                    $(".preloader").show();  
                },
                complete: function () {
                    $(".preloader").fadeOut(); 
                },
                success: function(data) {
                    Swal.fire({
                            icon: 'success',
                            title: 'Yay..',
                            text:"Alamat Berhasil Dihapus",
                            timer: 1300,
                            showCancelButton: false,
                            showConfirmButton: false,
                        }).then((result) => {
                        /* Read more about handling dismissals below */
                        if (result.dismiss === Swal.DismissReason.timer) {
                            window.location.reload()
                        }
                    })
                },
                error: function(xhr, textStatus, errorThrown){
                    // Return if product didn't exists
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: xhr.responseJSON.message,
                        confirmButtonColor: "#913030"
                    })
                    return;
                }
            })
        }
        function EditRecipient(index) {
            let idRecipient = $('#idRecipient_'+index).val()
            let recipient = $('#recipient_'+index).val()
            let address = $('#address_'+index).val()
            
            if (idRecipient == "") {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: "idRecipient cant be blank"
                })
                return;
            }

            if (recipient == "") {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: "Recipient cant be blank"
                })
                return;
            }

            if (address == "") {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: "alamat cant be blank"
                })
                return;
            }

            let data = {idRecipient:idRecipient, recipient:recipient, address:address}

            // hit backend
            // Setup CSRF TOKEN
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                type: "PUT",
                url: "/Lain-lain/Korespondensi/SuratJalan/listrecipient",
                data:data,
                dataType: 'json',
                beforeSend: function () {
                    $(".preloader").show();  
                },
                complete: function () {
                    $(".preloader").fadeOut(); 
                },
                success: function(data) {
                    // Dismiss Modal
                    $('.modal').modal('hide');
                    // Show alert and reload
                    Swal.fire({
                            icon: 'success',
                            title: 'Yay..',
                            text:"Alamat Berhasil Diupdate",
                            timer: 1300,
                            showCancelButton: false,
                            showConfirmButton: false,
                        }).then((result) => {
                        /* Read more about handling dismissals below */
                        if (result.dismiss === Swal.DismissReason.timer) {
                            window.location.reload()
                        }
                    })
                },
                error: function(xhr, textStatus, errorThrown){
                    // Return if product didn't exists
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: xhr.responseJSON.message,
                        confirmButtonColor: "#913030"
                    })
                    return;
                }
            })
        }
    </script>
@endsection
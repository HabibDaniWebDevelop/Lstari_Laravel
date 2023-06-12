<?php $menu='1'; ?>
@extends('layouts.backend-Theme-3.app')

<?php $title='Tes Posting'; ?>
@section('container')
 
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Home /</span>{{ $title; }}</h4>
    <div class="row mb-4">
      <div class="col-md-12">

                      <!-- Basic Layout -->
          <div class="row">
            <div class="col-md-12 col-lg-6 col-xxl-4">
              <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                  <h5 class="mb-0">Basic Layout</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                      <label class="form-label" >id</label>
                      <input type="text" class="form-control" id="id" value="2212040003"/>
                    </div>
                    <div class="mb-3">
                      <label class="form-label" >usercode</label>
                      <input type="text" class="form-control" id="UserName" value="{{ Auth::user()->name }}"/>
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

$(document).ready(function($){

  $('body').on('click', '#send-data', function () {
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
        url: "/tesposting",
        type: "POST",
        dataType: "JSON",
        cache: false,
        data: formData,
        success:function(data){
          if (data.success) {
            Swal.fire({
                icon: 'success',
                title: data.message,
                text: 'Silahkan Cek Stock Untuk Memastikan',
                confirmButtonColor: "#913030",
            })
            console.log(data.success);
          } 
        },
        error:function(data){
          Swal.fire({
                icon: 'error',
                title: 'Posting Gagal!!',
                text: 'silahkan Hubungi IT untuk Mengecek!'
            });
            console.log(data);
        }
    });

  });

});   
</script>

@endsection 


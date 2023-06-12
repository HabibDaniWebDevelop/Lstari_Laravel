<?php 
$iduser=Session::get('iduser');

//!  ------------------------ Cek gambar ------------------------ !!
// Initialize an URL to the variable
$url = "http://192.168.1.100/karyawan/".$iduser.".jpg";
  
// Use get_headers() function
$headers = @get_headers($url);
  
// Use condition to check the existence of URL
if($headers && strpos( $headers[0], '404')) {
  $provil="assets/images/user.jpg"; 
}
else {
  $provil="http://192.168.1.100/karyawan/".$iduser.".jpg"; 
}

 ?>
@extends('layouts.backend-Theme-3.app')

<?php $title='Account'; ?>
@section('container')
 
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Home /</span> Account Settings</h4>

    <div class="row"> 
      <div class="col-md-12">
        <ul class="nav nav-pills flex-column flex-md-row mb-3">
          <li class="nav-item">
            <a class="nav-link active" href="javascript:void(0);"><i class="bx bx-user me-1"></i> Account</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#"
              ><i class="bx bx-bell me-1"></i> Notifications</a
            >
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#"
              ><i class="bx bx-link-alt me-1"></i> Connections</a
            >
          </li>
        </ul>
        <div class="card mb-4">
          <h5 class="card-header">Profile Details</h5>
          <!-- Account -->
          <div class="card-body">
            <div class="d-flex align-items-start align-items-sm-center gap-4">
              <img
                src="{{ $provil }}"
                class="d-block rounded"
                height="100"
                width="100"
              />
      
            </div>
          </div>
          <hr class="my-0" />
          <div class="card-body">
            <form id="formAccountSettings" method="POST" onsubmit="return false">
              <div class="row">
                <div class="mb-3 col-md-6">
                  <label for="firstName" class="form-label">Full Name</label>
                  <input
                    class="form-control"
                    type="text"
                    id="firstName"
                    name="firstName"
                    value="{{ Auth::user()->nama_lengkap }}"
                    autofocus
                  />
                </div>
                <div class="mb-3 col-md-6">
                  <label for="lastName" class="form-label">User Name</label> 
                  <input class="form-control" type="text" name="lastName" id="lastName" value="{{ Auth::user()->name }}" />
                </div>
                <div class="mb-3 col-md-6">
                  <label for="email" class="form-label">E-mail</label>
                  <input
                    class="form-control"
                    type="text"
                    id="email"
                    name="email"
                    value=""
                    placeholder="john.doe@example.com"
                  />
                </div>
                <div class="mb-3 col-md-6">
                  <label for="organization" class="form-label">Organization</label>
                  <input
                    type="text"
                    class="form-control"
                    id="organization"
                    name="organization"
                    value="ThemeSelection"
                  />
                </div>
                <div class="mb-3 col-md-6">
                  <label class="form-label" for="phoneNumber">Phone Number</label>
                  <div class="input-group input-group-merge">
                    <span class="input-group-text">ID (+62)</span>
                    <input
                      type="text"
                      id="phoneNumber"
                      name="phoneNumber"
                      class="form-control"
                      placeholder="202 555 0111"
                    />
                  </div>
                </div>
                <div class="mb-3 col-md-6">
                  <label for="address" class="form-label">Address</label>
                  <input type="text" class="form-control" id="address" name="address" placeholder="Address" />
                </div>
                <div class="mb-3 col-md-6">
                  <label for="state" class="form-label">State</label>
                  <input class="form-control" type="text" id="state" name="state" placeholder="California" />
                </div>
           
                
                
                
              </div>
              <div class="mt-2" hidden>
                <button type="submit" class="btn btn-primary me-2">Save changes</button>
                <button type="reset" class="btn btn-outline-secondary">Cancel</button>
              </div>
            </form>
          </div>
          <!-- /Account -->
        </div>

      </div>
    </div>

@endsection
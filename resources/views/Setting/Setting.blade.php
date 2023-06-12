<?php $menu='1'; ?>
@extends('layouts.backend-Theme-3.app')

<?php $title='Setting'; ?>
@section('container')
 
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Home /</span> {{ $title }}</h4>
    <div class="row">
      <div class="col-md-12">

        <ul class="nav nav-pills flex-column flex-md-row mb-3" >
          <li class="nav-item">
            <a class="nav-link btn-menu1 {{ ($menu === "1") ? 'active':' ' }}" id="idmenu1" data-bs-toggle="tab" href="javascript:void(0);"><i class="fas fa-user-tie"></i> User</a>
          </li>
          <li class="nav-item">
            <a class="nav-link btn-menu3 {{ ($menu === "4") ? 'active':' ' }}" id="idmenu4" data-bs-toggle="tab" href="#"><i class="fas fa-layer-group"></i> Level</a>
          </li>
          <li class="nav-item">
            <a class="nav-link btn-menu2 {{ ($menu === "2") ? 'active':' ' }}" id="idmenu2" data-bs-toggle="tab" href="#"><i class="fas fa-list-ol"></i> List Menu</a>
          </li>
          <li class="nav-item">
            <a class="nav-link btn-menu3 {{ ($menu === "3") ? 'active':' ' }}" id="idmenu3" data-bs-toggle="tab" href="#"><i class="fas fa-directions"></i> Quick Access</a>
          </li>
          <li class="nav-item">
            <a class="nav-link btn-menu3 {{ ($menu === "3") ? 'active':' ' }}" href="{{ route('sampel') }}"><i class="fas fa-flask"></i> Sampel</a>
          </li>
          
                    {{-- <li class="nav-item">
            <a class="nav-link" href="{{ route('register.index') }}" target="_blank" ><i class="bx bxs-plus-circle me-1"></i> Register New User</a>
          </li> --}}

        </ul> 
          <div class="card" id="menu1">
    
          </div>
      </div>
    </div>

@endsection

@section('script')


@yield('scriptMenu')
<script>

  $(document).ready(function(){
    var token = $("meta[name='csrf-token']").attr("content");

		$('.nav-link').click(function(){
			var menu = $(this).attr('id');

			if(menu == "idmenu1"){menuuser();}
      else if(menu == "idmenu2"){menuListMenu();}
      else if(menu == "idmenu3"){menuQA();}
      else if(menu == "idmenu4"){menuLevel();}
		});

 		menuuser();
	});

  function menuuser() {
    $.get("{{ url('user') }}", function(data) {
      $("#menu1").html(data);
    });
  }
  function menuLevel() {
    $.get("{{ url('menuLevel') }}", function(data) {
      $("#menu1").html(data);
    });
  }
  function menuListMenu() {
    $.get("{{ url('ListMenu') }}", function(data) {
      $("#menu1").html(data);
    });
  }
  function menuQA() {
    $.get("{{ url('MenuQA') }}", function(data) {
      $("#menu1").html(data);
    });
  }
  

</script>

@include('Setting.UserJS')
@include('Setting.menuLevelJS')
@include('Setting.ListMenuJS')
@include('Setting.MenuQAJS')

@endsection 


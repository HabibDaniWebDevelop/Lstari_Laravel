{{-- <h5 class="card-header">Manage Status Info</h5> --}}

<div class="card-body">

  <div class="card-body">

    <div class="row">

      <div class="col-md-2">

        {{-- <label class="form-label">Jenis Info</label>
        <input type="text" class="form-control" id="jenis" name="jenis" placeholder="Jenis"> --}}

        <label class="form-label">Jenis</label>
        <select class="form-select" id='jenis' name='jenis'>
          <option value="" selected>Pilih Jenis</option>
          <option value="1">TM</option>
          <option value="2">SPKO</option>
          <option value="3">Pindah Kode</option>
          <option value="4">Transfer Stock</option>
          <option value="5">Transfer FG</option>
          <option value="6">Transfer FG Persiapan</option>
          <option value="7">TM Operation</option>
        </select>

      </div>

      <div class="col-md-1">
        <label class="form-label" style="color: #FFFFFF">BtnTampil</label>
        <button class="btn btn-primary btn-md" onclick="tampilData()">Tampil</button>
      </div>

      <div class="col-md-9"></div>

    </div>

  </div>

  <hr style="height:2px; color: #000000;">
  
  <div class="card-body">
    <div class="row">
      <div class="col-md-12">
        <div id="tampil1"></div>
      </div>
    </div>
  </div>

</div>

@include('Produksi.Lain-Lain.StatusInfo.modal')



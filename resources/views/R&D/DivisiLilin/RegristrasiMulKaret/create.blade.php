 <!-- modal -->    
<div class="modal fade" id="basicModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog" role="document">
           <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="jodulmodal1">Form Tambah Data Karet</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="formmodal1">
                        <div id="modal1">
                            <div class="row">
                                <div class="col-6">
                                    <div class="form-group">
                                        <label class="form-label">ID Karet</label>
                                        <input type="number" class="form-control" onchange="caribarkode(this.value,1)" id="IdKaret" name="karet[]">
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label class="form-label">Kode Produk</label>
                                        <input type="text" class="form-control" id="KodeProduksi">
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-6">
                                    <div class="form-group">
                                        <label class="form-label">Deskripsi</label>
                                        <input type="text" class="form-control" id="Deskripsi">
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                    <label class="form-label">Pilih Lemari</label>
                                        <div class="btn-group" role="group" aria-label="Basic example">
                                            <button type="button" class="btn btn-primary col-12" data-bs-target="#exLargeModal" data-bs-toggle="modal">Pilih lemari</button>
                                            <input type="text" name="SerialNo" class="form-control" required="">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                                                            
                    </form>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" id="simpan1" value="Tambah" onclick="KlikSimpan()">Save</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Extra Large Modal -->
    <div class="modal fade" id="exLargeModal" tabindex="-1" aria-hidden="true">
          <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel4">Pilih lemari penyimpanan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body">
                <div class="row g-2">
                  <div class="col mb-0">
                    <label for="pilihlemari" class="form-label">Silahkan pilih lemari</label>
                    <div class="dropdown">
                        <a class="btn btn-primary col-12 dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-bs-toggle="dropdown" aria-expanded="false">
                        Silahkan Pilih Lemari
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                            <li><a class="dropdown-item" href="#">A01</a></li>
                            <li><a class="dropdown-item" href="#">A02</a></li>
                            <li><a class="dropdown-item" href="#">A03</a></li>
                            <li><a class="dropdown-item" href="#">A04</a></li>
                            <li><a class="dropdown-item" href="#">A05</a></li>
                            <li><a class="dropdown-item" href="#">A06</a></li>
                            <li><a class="dropdown-item" href="#">A07</a></li>
                            <li><a class="dropdown-item" href="#">A08</a></li>
                            <li><a class="dropdown-item" href="#">A09</a></li>
                            <li><a class="dropdown-item" href="#">A10</a></li>
                            <li><a class="dropdown-item" href="#">C01</a></li>
                            <li><a class="dropdown-item" href="#">C02</a></li>
                            <li><a class="dropdown-item" href="#">C03</a></li>
                            <li><a class="dropdown-item" href="#">C04</a></li>
                            <li><a class="dropdown-item" href="#">C05</a></li>
                            <li><a class="dropdown-item" href="#">L01</a></li>
                        </ul>
                    </div>
                </div>
                  <div class="col mb-0">
                    <label for="dobExLarge" class="form-label">silahkan pilih laci</label>
                    <div class="dropdown">
                        <a class="btn btn-primary col-12 dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-bs-toggle="dropdown" aria-expanded="false">
                        Silahkan Pilih Laci
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                            <li><a class="dropdown-item" href="#">01</a></li>
                            <li><a class="dropdown-item" href="#">02</a></li>
                            <li><a class="dropdown-item" href="#">03</a></li>
                            <li><a class="dropdown-item" href="#">04</a></li>
                            <li><a class="dropdown-item" href="#">05</a></li>
                            <li><a class="dropdown-item" href="#">06</a></li>
                            <li><a class="dropdown-item" href="#">07</a></li>
                            <li><a class="dropdown-item" href="#">08</a></li>
                            <li><a class="dropdown-item" href="#">09</a></li>
                            <li><a class="dropdown-item" href="#">10</a></li>
                            <li><a class="dropdown-item" href="#">11</a></li>
                            <li><a class="dropdown-item" href="#">12</a></li>
                            <li><a class="dropdown-item" href="#">13</a></li>
                        </ul>
                    </div>
                  </div>
                </div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                  Close
                </button>
                <button type="button" class="btn btn-primary">Save changes</button>
              </div>
            </div>
          </div>
        </div>
<!-- akhir modal -->
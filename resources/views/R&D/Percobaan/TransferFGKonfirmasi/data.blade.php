<body class="card-body" style="zoom:90%; ">
    <div class="card-body p-0 m-0">

        <div class="card-body">
            <div class="row">
                <div class="col-9">
                    <button type="button" class="btn btn-primary me-4" id="btn_baru" onclick="KlikBaru()"> <span class="tf-icons bx bx-plus-circle"></span>&nbsp; Baru </button>
                    <button type="button" class="btn btn-danger" disabled="" id="btn_batal" onclick="klikBatal()"> <span class="fas fa-times-circle"></span>&nbsp; Batal</button>
                    <button type="button" class="btn btn-warning me-4" disabled="" id="btn_simpan" onclick="KlikSimpan()"><span class="tf-icons bx bx-save"></span>&nbsp; Simpan</button>
                    <button type="button" class="btn btn-info" id="btn_cetak" disabled="" onclick="klikCetak()"> <span class="tf-icons bx bx-printer"></span>&nbsp; Cetak</button>
                    <input type="hidden" id="idNTHKO" value="" type="number">
                    <input type="hidden" id="action" value="simpan"> 
                </div>
                <div class="col-3">
                    <div class="float-end">
                        <div class="input-group input-group-merge">
                            <span class="input-group-text"><i class="bx bx-search"></i></span>
                            <input type="search" class="form-control" placeholder="Search..." onchange="KlikCari()" id="cari" list="carilist">
                        </div>
                        <datalist id="carilist">
                            @foreach ($data as $datas)
                                <option value="{{$datas->ID}}">{{$datas->ID}}</option>
                            @endforeach
                        </datalist>
                    </div>
                </div>
            </div>
            <hr>
            <div class="row justify-content-md-center">
                <div class="col-12">
                    <div id="awal">
                        <div class="login-box card">
                            <div class="card-body">
                                <h3 class="box-title m-b-20" style="text-align: center"><u>Masukkan ID Transfer Barang Jadi</u></h3>
                                <div class="alert alert-primary" role="alert">
                                    <br>
                                    <div class="form-group ">
                                        <div class="col-xs-12">
                                            <input type="text" name="fkpcb" id="fkpcb" style="width: 100%; text-align: center;" onchange="getItemTM()"> 
                                        </div>
                                    </div>
                                </div>         
                                <center>Form Transfer Barang Jadi Stockist &copy;<?php echo date("Y"); ?></center>
                            </div>
                        </div> 
                    </div> 
                    <div class="card" id="cek" style="display: none;">
                        <div class="row">
                            <div class="col-md-12">
                                <div id="hasil" style="padding-left: 50px; padding-top: 15px; padding-right: 50px; padding-bottom: 15px; ">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div id="overlay">
                <div class="cv-spinner">
                    <span class="spinner"></span>
                </div>
            </div> 
        </div>
    
    </div>
</body>




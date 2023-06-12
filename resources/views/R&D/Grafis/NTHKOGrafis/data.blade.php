<div class="card-body" style="min-height:calc(100vh - 255px);">

    <div class="demo-inline-spacing" id="btn-menu">
        <input type="hidden" id="action" name="action" value="simpan">
        <button type="button" class="btn btn-danger" id="Batal1" disabled onclick="Klik_Batal1()"> <span
                class="fas fa-times-circle"></span>&nbsp; Batal</button>

        <button type="button" class="btn btn-warning" id="Simpan1" disabled onclick="Klik_Simpan1()">
            <span class="tf-icons bx bx-save"></span>&nbsp; Simpan</button>
        
        <button type="button" class="btn btn-primary me-4" disabled id="btn_ubah" onclick="KlikUbah()"><span class="tf-icons bx bx-edit"></span>&nbsp; Ubah</button>

        <button type="button" class="btn btn-dark me-4" id="Posting1" disabled onclick="Klik_Posting1()">
            <span class="tf-icons bx bx-send"></span>&nbsp; Posting</button>

        <button type="button" class="btn btn-info" id="Cetak1" value="" disabled onclick="Klik_Cetak1()">
            <span class="tf-icons bx bx-printer"></span>&nbsp; Cetak</button>

            <button type="button" class="btn btn-primary" id="conscale" onclick="connectSerial()"><span class="fas fa-balance-scale"></span>&nbsp; Timbangan</button>

        <div class="d-flex float-end">

            <div class="position-absolute d-none" id="postinglogo" style="right: 300px; top: 10px; ">
                <img src="{!! asset('assets/images/posting.jpg') !!}" style="width: 250px; object-fit: cover; object-position: top;">
            </div>

            <div class="input-group input-group-merge" style="width: 200px;">
                <span class="input-group-text"><i class="bx bx-search" onclick="klikViewSelection()"></i></span>
                <input type="search" class="form-control" list="carilist" autofocus id='cari'
                    onchange="ChangeCari('0')" placeholder="search...">
            </div>
            <datalist class="text-warning" id="carilist">
                @foreach ($carilists as $carilist)
                    <option value="{{ $carilist->SW }}" label="{{ $carilist->ID }}">
                @endforeach
            </datalist>

        </div>
        <hr class="m-0" />

    </div>
    <form id="form1" autocomplete="off">
        <div id="tampil">
            <div class="row my-4">
            
                <div class="col-md-2">
                    <div class="form-group">
                        <label class="form-label">No Form Kerja</label>
                        <input type="search" class="form-control form-control-sm fs-6" list="listnthnew" id='cari2' onchange="ChangeCari('1')">
                        <datalist class="text-warning" id="listnthnew">
                            @foreach ($nthbaru as $value)
                            <option value="{{ $value->SW }}" label="{{ $value->ID }}">
                                @endforeach
                        </datalist>
                    </div>
                </div>
            
                <div class="col-md-2 mb-2">
                    <div class="form-group">
                        <label class="form-label">Tanggal</label>
                        <input type="date" class="form-control form-control-sm fs-6" name="tanggal" id="tanggal" disabled
                            value="">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label class="form-label">Operator</label>
                        <input type="text" class="form-control form-control-sm fs-6" disabled value="">
                    </div>
                </div>
            </div>
            <div class="row my-4">
                <div class="col-md-2">
                    <div class="form-group">
                        <label class="form-label">Toatl Jumlah</label>
                        <input type="text" class="form-control form-control-sm fs-6" disabled value="">
                    </div>
                </div>
            
                <div class="col-md-2">
                    <div class="form-group">
                        <label class="form-label">Total Berat</label>
                        <input type="text" class="form-control form-control-sm fs-6" name="tberat" readonly
                            value="">
                    </div>
                </div>
                
                <div class="col-md-2">
                    <div class="form-group">
                        <label class="form-label">Berat Qc</label>
                        <input type="text" class="form-control form-control-sm fs-6" name="berat_qc" readonly
                             id="berat_qc">
                    </div>
                </div>
            
                <div class="col-md-2">
                    <div class="form-group">
                        <label class="form-label">Berat Cor</label>
                        <input type="text" class="form-control form-control-sm fs-6" name="berat_cor" readonly
                            id="berat_cor">
                    </div>
                </div>
            
                <div class="col-md-2">
                    <div class="form-group">
                        <label class="form-label">Berat Reparasi</label>
                        <input type="text" class="form-control form-control-sm fs-6" name="berat_rep" readonly
                             id="berat_rep">
                    </div>
                </div>
            
                <div class="col-md-2">
                    <div class="form-group">
                        <label class="form-label">Berat SC</label>
                        <input type="text" class="form-control form-control-sm fs-6" name="berat_sc" readonly
                             id="berat_sc">
                    </div>
                </div>

                <div class="col-md-2">
                    <div class="form-group">
                        <label class="form-label">Berat Var P</label>
                        <input type="text" class="form-control form-control-sm fs-6" name="berat_varp" readonly
                             id="berat_varp">
                    </div>
                </div>

                <div class="col-md-2">
                    <div class="form-group">
                        <label class="form-label">Berat Sepuh</label>
                        <input type="text" class="form-control form-control-sm fs-6" name="berat_sepuh" readonly
                             id="berat_sepuh">
                    </div>
                </div>
            
                <div class="col-md-6">
                </div>
            
            </div>
        </div>
    </form>


</div>


<div class="card-body" style="min-height:calc(100vh - 255px);">

    <div class="demo-inline-spacing" id="btn-menu">

        <button type="button" class="btn btn-primary" id="Baru1" onclick="Klik_Baru1()"> <span
                class="tf-icons bx bx-plus-circle"></span>&nbsp; Baru </button>
        <button type="button" class="btn btn-primary me-4" id="Lihat1" onclick="Klik_Lihat1()"> <span
                class="tf-icons bx bx-list-ul"></span>&nbsp; Lihat</button>

        <button type="button" class="btn btn-danger" id="Batal1" disabled onclick="Klik_Batal1()"> <span
                class="fas fa-times-circle"></span>&nbsp; Batal</button>

        <button type="button" class="btn btn-warning me-4" id="Simpan1" disabled onclick="Klik_Simpan1()">
            <span class="tf-icons bx bx-save"></span>&nbsp; Simpan</button>

        <div class="d-flex float-end">

        </div>
        <hr class="m-0" />
    </div>
    <div class="row">
        <div class="col-6">
            <div class="row">
                <div class="col-xl-6 col-md-12 col-sm-12 col-xs-12">
                    <label for="" style="font-weight: bold;">Sub Kategori</label>
                    <select class="form-control selectpicker" data-style="border" name="subka" id="subka" data-live-search="true">
                       @foreach ($fo as $data)
                            <option value="{{ $data->ProductID }}">{{ $data->Description }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-xl-6 col-md-12 col-sm-12 col-xs-12">
                    <label for="" style="font-weight: bold;"> Pilih Kadar : </label>
                    <select class="form-control selectpicker" data-style="border" name="kadar" id="kadar" data-live-search="true">
                        <option></option>
                        @foreach ($carats as $kadar)
                        <option value="{{ $kadar->ID }}">{{ $kadar->SKU}}{{$kadar->Alloy }}</option>
                        @endforeach
                    </select>
                </div>

            </div>            
            <div class="row">
                <div class="col-xl-6 col-md-12 col-sm-12 col-xs-12">
                    <label for="" style="font-weight: bold;">Nomor Seri Mulai</label>
                    <input type="number" class="form-control" placeholder="10120" value="10000" id="noawal">
                </div>
                <div class="col-xl-6 col-md-12 col-sm-12 col-xs-12">
                    <label for="" style="font-weight: bold;">Nomor Seri Akhir</label>
                    <div class="input-group">
                        <input type="number" class="form-control" value="99999" placeholder="10000" id="noakhir">
                        <button class="btn btn-outline-primary" onclick="getModel()">Cari</button>
                    </div>
                </div>
            </div>  

            <div class="row">
                <div class="col-xl-12 col-md-12 col-sm-12 col-xs-12">
                    
                        <div id="list"  class="table-responsive text-nowrap" style="height:calc(100vh - 600px);">
                            
                        </div>
                    
                </div> 
            </div>
        </div>    

        <div class="col-6">
            <div class="row">
                <div class="col-xl-12 col-md-12 col-sm-12 col-xs-12">
                    <form id="itemworksuggestion">
                        <div id="keranjang" class="table-responsive text-nowrap" style="height:calc(100vh - 600px);">
                            <table id="cart"  class="table table-border table-hover table-sm dataTable no-footer">
                                <thead class="table-secondary sticky-top zindex-2">
                                    <th style="text-align: center; font-weight: bold;">No</th>
                                    <th style="text-align: center; font-weight: bold;">SKU</th>
                                    <th style="text-align: center; font-weight: bold;">Qty</th>
                                </thead>
                                <tbody>
                                    
                                </tbody>
                            </table>
                        </div>
                    </form>
                </div>  
            </div>
        </div>

    </div>   



    

</div>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <div class="demo-inline-spacing mb-4">

                    <button type="button" class="btn btn-primary me-4" id="Baru1" onclick="Click_Tambah1()"><span
                            class="tf-icons bx bx-plus-circle"></span>&nbsp; Baru </button>
                    <button type="button" class="btn btn-danger" id="Batal1" disabled onclick="Klik_Batal1()"> <span
                            class="fas fa-times-circle"></span>&nbsp; Batal</button>
                    <button type="button" hidden class="btn btn-warning me-4" id="Simpan1" disabled
                        onclick="Klik_Simpan1()">
                        <span class="tf-icons bx bx-save"></span>&nbsp; Simpan</button>
                    <button type="button" class="btn btn-warning me-4" id="permintaan3dp" disabled
                        onclick="prosesminta()">
                        <span class="tf-icons bx bx-save"></span>&nbsp; Request</button>
                    <button type="button" hidden class="btn btn-info" id="Cetak1" disabled onclick="Klik_Cetak1()">
                        <span class="tf-icons bx bx-printer"></span>&nbsp; Cetak SPK</button>
                    <button type="button" class="btn btn-info" id="Cetak2" disabled onclick="printSPK3DP()"> <span
                            class="tf-icons bx bx-printer"></span>&nbsp; Cetak Request</button>
                    <!-- <button type="button" class="btn btn-info" id="Cetak1" onclick="tes()"><i
                            class="fal fa-caret-circle-right"></i>&nbsp; tes</button> -->

                    <div class="float-end">
                        <div class="input-group input-group-merge">
                            <span class="input-group-text"><i class="bx bx-search"
                                    onclick="klikViewSelection()"></i></span>
                            <input type="text" class="form-control" placeholder="Search..." autofocus id='cari'
                                list="carilist" onchange="ChangeCari()" />
                        </div>
                        <datalist id="carilist">
                            @foreach ($IDMWaxOrderItem AS $ID)
                            <option value="{{ $ID->ID }}">{{ $ID->ID }}</option>
                            @endforeach
                        </datalist>
                    </div>
                    <hr class="m-0" />
                </div>
                <div id="tampil1" class="form">
                </div>
                <div id="tampil2" class="d-none">
                </div>
            </div>
            @include('Setting.publick_function.ViewSelectionModal')
        </div>
    </div>
</div>
@include('Produksi.Lilin.SPKPohonanTestFULLDC.Modal')
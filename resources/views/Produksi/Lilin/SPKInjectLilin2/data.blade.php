<div class="row">
    <div class="col-md-12">
        <div class="card">
            <h5 class="card-header">Form Input</h5>
            <div class="card-body">
                <div class="demo-inline-spacing mb-4">

                    <button type="button" class="btn btn-primary" id="Baru1" onclick="Klik_Baru1()"> <span
                            class="tf-icons bx bx-plus-circle"></span>&nbsp; Baru </button>
                    <button type="button" class="btn btn-primary me-4" id="Ubah1" disabled onclick="Klik_Ubah1()"> <span
                            class="tf-icons bx bx-edit"></span>&nbsp; Ubah</button>
                    <button type="button" class="btn btn-danger" id="Batal1" disabled onclick="Klik_Batal1()"> <span
                            class="fas fa-times-circle"></span>&nbsp; Batal</button>
                    <button type="button" class="btn btn-warning me-4" id="Simpan1" disabled onclick="Klik_Simpan1()">
                        <span class="tf-icons bx bx-save"></span>&nbsp; Simpan</button>
                    <button type="button" class="btn btn-info" id="Cetak1" disabled onclick="Klik_Cetak1()"> <span
                            class="tf-icons bx bx-printer"></span>&nbsp; Cetak SPKO </button>
                    <button type="button" class="btn btn-info" id="Cetak2" onclick="Klik_Cetak2()"> <span
                            class="tf-icons bx bx-printer"></span>&nbsp; Cetak Ulang SPK3DP</button>
                    <div class="float-end">
                        <input type="hidden" id="idwaxinjectorder" value="" type="number">
                        <input type="hidden" id="action" value="simpan" type="text">
                        <input type="hidden" id="spkppicdipilih" value="">
                        <input type="hidden" id="hiddenid3dp" value="">
                        <div class="input-group input-group-merge">
                            <span class="input-group-text"><i class="bx bx-search"
                                    onclick="klikViewSelection()"></i></span>
                            <input type="text" class="form-control" placeholder="Search..." autofocus id='cari'
                                list="carilist" onchange="ChangeCari()" />
                        </div>
                        <datalist id="carilist">
                            @foreach ($IDWaxInject AS $ID)
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
        </div>
        @include('setting.publick_function.ViewSelectionModal')
    </div>
</div>
@include('Produksi.Lilin.SPKInjectLilin.Modal')
@include('Produksi.Lilin.SPKInjectLilin.Modal2')
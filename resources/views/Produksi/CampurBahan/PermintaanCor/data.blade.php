<div class="col-md-12">
    <div class="card mb-4">
        <div class="card-body">
            <div class="row">
                <div class="demo-inline-spacing mb-4">
                    <button type="button" class="btn btn-primary " id="Baru1" onclick="Klik_Baru1()"> <span
                            class="tf-icons bx bx-plus-circle"></span>&nbsp; Baru </button>
                    {{-- <button type="button" class="btn btn-primary me-4" id="Ubah1" disabled onclick="Klik_Ubah1()">
                        <span class="tf-icons bx bx-edit"></span>&nbsp; Ubah</button> --}}
                    <button type="button" class="btn btn-danger" id="Batal1" disabled onclick="Klik_Batal1()"> <span
                            class="fas fa-times-circle"></span>&nbsp; Batal</button>
                    <button type="button" class="btn btn-warning" id="Simpan1" disabled onclick="Klik_Simpan1()">
                        <span class="tf-icons bx bx-save"></span>&nbsp; Simpan</button>
                    {{-- <button type="button" class="btn btn-dark me-4" id="Posting1" disabled onclick="Klik_Posting1()">
                        <span class="tf-icons bx bx-send"></span>&nbsp; Posting</button> --}}
                    <button type="button" class="btn btn-info" id="Cetak1" disabled onclick="Klik_Cetak1()"> <span
                            class="tf-icons bx bx-printer"></span>&nbsp; Cetak</button>
                    <div class="float-end">
                        <div class="input-group input-group-merge">
                            <span class="input-group-text"><i class="bx bx-search" onclick="klikViewSelection()"></i></span>
                            <input type="text" class="form-control" placeholder="Search..." autofocus id='cari' list="carilist"
                                onchange="ChangeCari(0)" />
                        </div>
                        <datalist id="carilist">
                            @foreach ($carilists as $carilist)
                            <option value="{{ $carilist->ID }}">{{ $carilist->ID }}</option>
                            @endforeach
                        </datalist>
                        <input type="hidden" id="idcetak" value="">
                    </div>
                    <hr class="m-0" />
                </div>
                <form id="form1">
                    <div class="row" id="filters">
                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-md-6">
                                    <label class="control-label" for="sw">ID</label>
                                    <input class="form-control" id="sw" type="text" name="sw" value="" disabled="true">
                                </div>
                                <div class="col-md-6">
                                    <label class="control-label" for="tgl">Tanggal</label>
                                    <input class="form-control" id="tgl" type="date" name="tgl" value="{{ date('Y-m-d'); }}"
                                        disabled="true">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="row">
                                {{-- <div class="col-md-6">
                                    <label class="control-label" for="tgl">Kepada</label>
                                    <select class="form-select" id="employee" name="employee" disabled="true">
                                        @foreach ($employees as $employee)
                                        <option value="{{ $employee->ID }}" selected>{{ $employee->Description }}</option>
                                        @endforeach
                                    </select>
                                </div> --}}
                                <div class="col-md-6">
                                    <label class="control-label" for="tgl">Label SPK</label>
                                    <select class="form-select" id="labelwo" name="labelwo" disabled="true">
                                        <option value="" selected>Pilih</option>
                                        <option value="O">O</option>
                                        <option value="Non O">Non O</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="control-label" for="produk">Produk</label>
                                    <select class="form-select" id="produk" name="produk" onchange="getProduk()" disabled="true">
                                        <option value="" selected>Pilih</option>
                                        <option value="KCATKMN">KCATKMN</option>
                                        <option value="KCKL">KCKL</option>
                                        <option value="LBC">LBC</option>
                                        <option value="Perak Plat">Perak Plat</option>
                                        <option value="Plat Bangkok">Plat Bangkok</option>
                                        <option value="Plat Cor">Plat Cor</option>
                                        <option value="Plat Pipa">Plat Pipa</option>
                                        <option value="Plat Plong">Plat Plong</option>
                                        <option value="Stick Kawat">Stick Kawat</option>
                                        <option value="Stick Kawat Patri">Stick Kawat Patri</option>
                                        <option value="SVC">SVC</option>
                                    </select>
                                </div>
                            </div>
                           
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <label class="control-label" for="id">Catatan</label>
                            <input class="form-control" id="note" type="text" name="note" value="" disabled="true">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xxl-12 col-xl-12">
                        <div class="row" id="datas" style="display:none;">
                            <div class="col-sm-12">
                                <div id="tampil"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>


<script src="{!! asset('assets/sneatV1/assets/vendor/libs/datepicker/bootstrap-datepicker.min.js') !!}"></script>
<script>
    
</script>


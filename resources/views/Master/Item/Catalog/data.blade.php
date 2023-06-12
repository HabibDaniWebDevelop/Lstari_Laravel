<div class="row">
    <div class="col-12">
        {{-- Tab SPK and Nomor Model --}}
        <ul class="nav nav-pills mb-3 flex-column flex-md-row mb-3" role="tablist">
            <li class="nav-item">
                <button type="button" class="nav-link active" role="tab" data-bs-toggle="tab" data-bs-target="#spk" aria-controls="spk" aria-selected="true"> SPK </button>
            </li>
            <li class="nav-item">
                <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#nomorModel" aria-controls="nomorModel" aria-selected="false"> No Model </button>
            </li>
            <li class="nav-item">
                <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#tukangLuar" aria-controls="tukangLuar" aria-selected="false"> Tukang Luar </button>
            </li>
            <li class="nav-item">
                <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#lilin" aria-controls="lilin" aria-selected="false"> Lilin </button>
            </li>
            <li class="nav-item">
                <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#marketing" aria-controls="marketing" aria-selected="false"> Marketing </button>
            </li>
        </ul>
        {{-- End Tab SPK and Nomor Model --}}
        
        <div class="card" id="myCard">
            <div class="card-body">
                {{-- Content SPK and Nomor Model --}}
                <div class="tab-content p-0">
                    {{-- Panel SPK --}}
                    <div class="tab-pane fade active show" id="spk" role="tabpanel">
                        {{-- Tab Gambar, Batu, Informasi --}}
                        <div id="spkview">
                            <div class="row">
                                <div class="col-xl-9 col-md-12 col-sm-12 col-xs-12"></div>
                                <div class="col-xl-9 col-md-12 col-sm-12 col-xs-12">
                                    <div class="input-group input-group-merge float-end">
                                        <input type="text" class="form-control" placeholder="221002803" autofocus="" id="cari" onchange="CariSPK()">
                                        <button class="btn btn-outline-primary" onclick="CariSPK()">Cari</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    {{-- End Panel SPK --}}
                    {{-- Panel Nomor Model --}}
                    <div class="tab-pane fade" id="nomorModel" role="tabpanel">
                        <div class="row">
                            <div class="col-xl-4 col-md-12 col-sm-12 col-xs-12">
                                <label for="">Sub Kateogri</label>
                                <select class="form-control selectpicker" data-style="border" name="productCategory" id="productCategory" data-live-search="true">
                                    @foreach ($productCategory as $item)
                                    <option value="{{$item->ID}}">{{$item->SW}} - {{$item->Description}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-xl-4 col-md-12 col-sm-12 col-xs-12">
                                <label for="">Nomor Seri Mulai</label>
                                <input type="number" class="form-control" placeholder="10120" value="10000" id="fromNumber">
                            </div>
                            <div class="col-xl-4 col-md-12 col-sm-12 col-xs-12">
                                <label for="">Nomor Seri Akhir</label>
                                <div class="input-group">
                                    <input type="number" class="form-control" value="999999" placeholder="10122" id="toNumber">
                                    <button class="btn btn-outline-primary" onclick="CariModel()">Cari</button>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <br>
                                <button class="btn btn-primary btn-block me-2" onclick="KlikCetak()">Cetak</button>
                                <button class="btn btn-primary btn-block" onclick="KlikCetakSelectedItem()">Cetak Item Tertentu</button>
                            </div>
                        </div>
                        <hr>

                        {{-- VIEW FOR No Model TAB --}}
                        <div id="noModelView">

                        </div>

                    </div>
                    {{-- End Panel Nomor Model --}}
                    {{-- Panel Tukang Luar --}}
                    <div class="tab-pane fade" id="tukangLuar" role="tabpanel">
                        <span class="text-danger">*Cari Produk Tukang Luar Berdasarkan Produk Kategori</span>
                        <div class="row">
                            <div class="col-xl-4 col-md-12 col-sm-12 col-xs-12">
                                <label for="">Sub Kateogri</label>
                                <select class="form-control selectpicker" data-style="border" name="productCategoryTukangLuar" id="productCategoryTukangLuar" data-live-search="true">
                                    @foreach ($productCategoryTukangLuar as $item)
                                    <option value="{{$item->ID}}">{{$item->SW}} - {{$item->Description}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-xl-4 col-md-12 col-sm-12 col-xs-12">
                                <label for="">Nomor Seri Mulai</label>
                                <input type="number" class="form-control" placeholder="10120" value="10000" id="fromNumberTukangLuar">
                            </div>
                            <div class="col-xl-4 col-md-12 col-sm-12 col-xs-12">
                                <label for="">Nomor Seri Akhir</label>
                                <div class="input-group">
                                    <input type="number" class="form-control" value="999999" placeholder="10122" id="toNumberTukangLuar">
                                    <button class="btn btn-outline-primary" onclick="CariModelTukangLuar()">Cari</button>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <span class="text-danger">*Cari Produk Tukang Luar Berdasarkan No.SPK</span>
                        <div class="row">
                            <div class="col-xl-4 col-md-12 col-sm-12 col-xs-12">
                                <label for="nomorSPK">Nomor SPK</label>
                                <div class="input-group">
                                    <input type="number" class="form-control" placeholder="230104413" id="nomorSPK">
                                    <button class="btn btn-outline-primary" onclick="CariModelTukangLuarBySPK()">Cari</button>
                                </div>
                            </div>
                        </div>
                        <hr>

                        {{-- VIEW FOR Tukang Luar TAB --}}
                        <div id="tukangLuarView">

                        </div>

                    </div>
                    {{-- End Panel Tukang Luar --}}
                    {{-- Panel Lilin --}}
                    <div class="tab-pane fade" id="lilin" role="tabpanel">
                        {{-- Tab Gambar, Batu, Informasi --}}
                        <div id="lilinview">
                            <div class="row">
                                <div class="col-xl-9 col-md-12 col-sm-12 col-xs-12"></div>
                                <div class="col-xl-9 col-md-12 col-sm-12 col-xs-12">
                                    <div class="input-group input-group-merge float-end">
                                        <input type="text" class="form-control" placeholder="ID Karet" autofocus="" id="idkaret" onchange="CariLilin()">
                                        <button class="btn btn-outline-primary" onclick="CariLilin()">Cari</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    {{-- End Panel Lilin --}}
                    {{-- Panel Marketing --}}
                    <div class="tab-pane fade" id="marketing" role="tabpanel">
                        <div class="row">
                            <div class="col-xl-4 col-md-12 col-sm-12 col-xs-12">
                                <label for="">Sub Kateogri</label>
                                <select class="form-control selectpicker" data-style="border" name="productCategory" id="productCategoryMarketing" data-live-search="true">
                                    @foreach ($productCategory as $item)
                                    <option value="{{$item->ID}}">{{$item->SW}} - {{$item->Description}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-xl-4 col-md-12 col-sm-12 col-xs-12">
                                <label for="">Nomor Seri Mulai</label>
                                <input type="number" class="form-control" placeholder="10120" value="10000" id="fromNumberMarketing">
                            </div>
                            <div class="col-xl-4 col-md-12 col-sm-12 col-xs-12">
                                <label for="">Nomor Seri Akhir</label>
                                <div class="input-group">
                                    <input type="number" class="form-control" value="999999" placeholder="10122" id="toNumberMarketing">
                                    <button class="btn btn-outline-primary" onclick="CariModelMarketing()">Cari</button>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <br>
                                <button class="btn btn-primary btn-block me-2" onclick="KlikCetakMarketing()">Cetak</button>
                            </div>
                        </div>
                        <hr>

                        {{-- VIEW FOR No Model TAB --}}
                        <div id="noModelViewMarketing">
                        </div>
                    </div>
                    {{-- End Panel Marketing --}}
                </div>
                {{-- End Content SPK and Nomor Model --}}
            </div>
        </div>
    </div>
</div>
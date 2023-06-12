<div class="modal fade" id="modalpilihlemari" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-fullscreen" id="modalformat" role="document">
        <div class="modal-content" style="background-color: rgba(0,0,0,.0001) !important;">
            <div class="modal-header" style="background-color: rgba(0,0,0,.0001) !important;">
                <h5 class="modal-title" id="jodulmodal2" style="color: #000"><b>PILIH LOKASI PENYIMPANAN</b>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body pt-1">
                <form id="formmodal2">
                    <div id="modal2">
                        <div class="row">
                            <div class="col-md-6 dropdown">
                                <select class="from-select btn btn-primary center col-12 px-auto" onchange="ClickLaci()"
                                    id="lemari" name="lemari">
                                    <option value="" selected disabled><b>LEMARI</b>
                                    </option>
                                    @foreach ($lemari as $lemaris)
                                    <option class="text-center bg-light text-dark border-none"
                                        value="{{ $lemaris->ID }}">
                                        {{ $lemaris->SW }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-6 dropdown">
                                <select class="btn btn-primary col-12 mx-auto" onchange="ClickLaci()" id="laci"
                                    name="laci">
                                    <option value="" selected disabled><b>LACI</b>
                                    </option>
                                    @foreach ($laci as $lacis)
                                    <option class="text-center bg-light text-dark border-0" value="{{ $lacis->ID }}">
                                        {{ $lacis->SW }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <input type="hidden" id="baris" value="">
                        <input type="hidden" id="bariskaret" value="">
                        <div id="modal1">
                        </div>

                    </div>

                </form>
                <hr>
                <div class="row" style="background-color: rgba(0,0,0,.0001) !important;">
                    <div class="col-md-11">
                        <div class="float-end">
                            <button type="button" onclick="SimpanLokasi()" class="btn btn-warning m-1">Simpan
                                Lokasi</button>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="card mb-4">
            <h5 class="card-header">Form Input</h5>
            <div class="card-body">
                <div class="demo-inline-spacing mb-4">


                    <button type="button" class="btn btn-primary me-4" id="Baru1" onclick="Klik_Baru1()"> <span
                            class="tf-icons bx bx-plus-circle"></span>&nbsp; Baru </button>
                    <button type="button" class="btn btn-danger" id="Batal1" disabled onclick="Klik_Batal1()"> <span
                            class="fas fa-times-circle"></span>&nbsp; Batal</button>

                    <button type="button" class="btn btn-warning me-4" id="Simpan1" disabled onclick="Klik_Simpan1()">
                        <span class="tf-icons bx bx-save"></span>&nbsp; Simpan</button>

                    <button type="button" class="btn btn-info" id="Cetak1" disabled onclick="Klik_Cetak1()"> <span
                            class="tf-icons bx bx-printer"></span>&nbsp; Cetak</button>

                    <div class="float-end">

                    </div>
                    <hr class="m-0" />

                </div>

                <form id="form1">
                    <div id="tampil" class="d-none">
                        <table class="table table-borderless table-sm" id="tabel1">
                            <thead class="table-secondary">
                                <tr style="text-align: center">
                                    <th width="10%">No.</th>
                                    <th width="15%">ID Karet</th>
                                    <th>Kode Produk</th>
                                    <th>Deskripsi</th>
                                    <th width="10%">Pilih Lokasi</th>
                                    <th width="15%">Lemari Terpilih</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="baris" id="1" class="klik3">
                                    <td class="m-0 p-0">
                                        <input type="number" readonly
                                            class="form-control form-control-sm fs-6 w-100 text-center" name="no[]"
                                            value="1" data-index="11" posisi-index="awal">
                                    </td>
                                    <td class="m-0 p-0">
                                        <input type="number" onchange="OnchangeID(this.value,1)"
                                            class="form-control form-control-sm fs-6 w-100 text-center" id="idkaret"
                                            name="karet" value="" data-index="12">
                                    </td>
                                    <td class="m-0 p-0">
                                        <input type="text" class="form-control form-control-sm fs-6 w-100 text-center"
                                            id="kodeproduk" name="kodeproduk" value=" " data-index="13" id="kodeproduk">
                                    </td>
                                    <td class="m-0 p-0">
                                        <input type="text" class="form-control form-control-sm fs-6 w-100 text-center"
                                            id="deskripsi" name="deskripsi" value="" data-index="14" id="deskripsi">
                                    </td>
                                    <td class="m-0 p-1">
                                        <button type="button" class="btn btn-primary p-1 w-100" id="pilihlokasi"
                                            data-bs-toggle="modal" data-bs-target="#exLargeModal"> <span
                                                class="tf-icons bx bx-archive"></span>&nbsp;
                                        </button>
                                    </td>
                                    <td class="m-0 p-0">
                                        <input type="text" class="form-control form-control-sm fs-6 w-100 text-center"
                                            id="lokasilemari" name="lokasilemari" value="-" data-index="15"
                                            posisi-index="akhir">
                                    </td>
                                </tr>

                            </tbody>
                        </table>

                    </div>
                </form>

            </div>
        </div>
        @include('R&D.DivisiLilin.RegristrasiMulKaret.modal')

        @include('Setting.publick_function.ViewSelectionModal')
    </div>
</div>
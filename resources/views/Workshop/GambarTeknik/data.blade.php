<div class="card-body">
    <div class="row">
        <div class="col-9">
            <button type="button" class="btn btn-primary" id="btn_baru" onclick="KlikBaru()"> <span class="tf-icons bx bx-plus-circle"></span>&nbsp; Baru </button>
            <button type="button" class="btn btn-primary me-4" disabled="" id="btn_ubah" disabled="" onclick="KlikUbah()"><span class="tf-icons bx bx-edit"></span>&nbsp; Ubah</button>
            <button type="button" class="btn btn-danger" disabled="" id="btn_batal" onclick="klikBatal()"> <span class="fas fa-times-circle"></span>&nbsp; Batal</button>
            <button type="button" class="btn btn-warning" disabled="" id="btn_simpan" onclick="KlikSimpan()"><span class="tf-icons bx bx-save"></span>&nbsp; Simpan</button>
            <button type="button" class="btn btn-dark me-4" id="btn_posting" onclick="KlikPosting()" disabled=""><span class="tf-icons bx bx-send"></span>&nbsp; Posting</button>
            <button type="button" class="btn btn-info" id="btn_cetak" disabled="" onclick="klikCetak()"> <span class="tf-icons bx bx-printer"></span>&nbsp; Cetak</button>
            <input type="hidden" id="idGambarTeknik" value="">
            <input type="hidden" id="postingStatus" value="A">
            <input type="hidden" id="action" value="simpan" type="text"> {{-- Input checking for determine simpan or ubah. --}}
        </div>
        <div class="col-3">
            <div class="float-end">
                <div class="input-group input-group-merge">
                    <span class="input-group-text"><i class="bx bx-search"></i></span>
                    <input type="text" class="form-control" placeholder="Search..." autofocus="" onchange="KlikCari()" id="cari" list="carilist">
                </div>
                <datalist id="carilist">
                </datalist>
            </div>
        </div>
    </div>
    <hr>
    <div class="row">
        <div class="col-2">
            <label class="form-label" for="jenisMatras">Jenis Matras :</label>
            <select class="form-select" disabled name="jenisMatras" id="jenisMatras">
                @foreach ($jenisMatras as $item)
                    <option value="{{$item->Name}}">{{$item->Name}}</option>
                @endforeach
            </select>
        </div>
        <div class="col-2">
            <label class="form-label" for="jumlahMatras">Jumlah Matras :</label>
            <input type="number" disabled min="1" max="2" class="form-control" id="jumlahMatras">
        </div>
        <div class="col-2">
            <label class="form-label" for="jumlahItemMatras">Jumlah Item Matras :</label>
            <input type="number" disabled min="1" max="4" class="form-control" id="jumlahItemMatras">
        </div>
        <div class="col-2">
            <label class="form-label" for="generateForm"></label>
            <button class="btn form-control btn-primary" id="generateForm" onclick="GenerateForm()">Generate Form</button>
        </div>
    </div>
    <hr>
    <div id="GeneratedForm">
        {{-- <h5>Layout Matras Tanpa Pisau. Setiap Baris Merepresentasikan Jumlah Matras.</h5>
        <table class="table table-borderless table-sm text-center" id="tabelForm" style="vertical-align: top">
            <thead class="table-secondary">
                <tr>
                    <th width="5%">#</th>
                    <th width="15%">Product</th>
                    <th width="15%">Tipe Matras</th>
                    <th width="15%">File Autocad</th>
                    <th>Bahan Baku </th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>1</td>
                    <td>
                        <select name="product" id="product_1" class="form-select">
                            <option value="1">BBGC.10001.08K</option>
                            <option value="2">BBGC.10006.08K</option>
                        </select>
                    </td>
                    <td>
                        <select name="tipeMatras" id="tipeMatras_1" class="form-select">
                            <option value="1">Samir</option>
                            <option value="2">Roll</option>
                        </select>
                    </td>
                    <td><input type="file" class="form-control" name="fileAutocad_1" id="fileAutocad_1"></td>
                    <td>
                        <table class="table table-borderless table-sm text-center" id="tabelBahanBaku">
                            <thead class="table-secondary">
                                <tr>
                                    <th>Nama Bahan</th>
                                    <th width="20%">Qty</th>
                                    <th width="20%">Option</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        <select name="namaBahan_1" id="namaBahan_1" class="form-select">
                                            <option value="1">Besi Bulat 50</option>
                                            <option value="1">Besi Bulat 20</option>
                                        </select>
                                    </td>
                                    <td><input type="number" name="qtyBahan_1" class="form-control"></td>
                                    <td>
                                        <button onclick="newRow()" class="btn btn-primary btn_add_row">+</button> <button onclick="removeRow2({{1}})" class="btn btn-primary btn_remove">-</button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <select name="namaBahan_1" id="namaBahan_1" class="form-select">
                                            <option value="1">Besi Bulat 50</option>
                                            <option value="1">Besi Bulat 20</option>
                                        </select>
                                    </td>
                                    <td><input type="number" name="qtyBahan_1" class="form-control"></td>
                                    <td>
                                        <button onclick="newRow()" class="btn btn-primary btn_add_row">+</button> <button onclick="removeRow2({{1}})" class="btn btn-primary btn_remove">-</button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <select name="namaBahan_1" id="namaBahan_1" class="form-select">
                                            <option value="1">Besi Bulat 50</option>
                                            <option value="1">Besi Bulat 20</option>
                                        </select>
                                    </td>
                                    <td><input type="number" name="qtyBahan_1" class="form-control"></td>
                                    <td>
                                        <button onclick="newRow()" class="btn btn-primary btn_add_row">+</button> <button onclick="removeRow2({{1}})" class="btn btn-primary btn_remove">-</button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <select name="namaBahan_1" id="namaBahan_1" class="form-select">
                                            <option value="1">Besi Bulat 50</option>
                                            <option value="1">Besi Bulat 20</option>
                                        </select>
                                    </td>
                                    <td><input type="number" name="qtyBahan_1" class="form-control"></td>
                                    <td>
                                        <button onclick="newRow()" class="btn btn-primary btn_add_row">+</button> <button onclick="removeRow2({{1}})" class="btn btn-primary btn_remove">-</button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td>2</td>
                    <td>
                        <select name="product" id="product_1" class="form-select">
                            <option value="1">BBGC.10001.08K</option>
                            <option value="2">BBGC.10006.08K</option>
                        </select>
                    </td>
                    <td>
                        <select name="tipeMatras" id="tipeMatras_1" class="form-select">
                            <option value="1">Samir</option>
                            <option value="2">Roll</option>
                        </select>
                    </td>
                    <td><input type="file" class="form-control" name="fileAutocad_1" id="fileAutocad_1"></td>
                    <td>
                        <table class="table table-borderless table-sm text-center" id="tabelBahanBaku">
                            <thead class="table-secondary">
                                <tr>
                                    <th>Nama Bahan</th>
                                    <th width="20%">Qty</th>
                                    <th width="20%">Option</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        <select name="namaBahan_1" id="namaBahan_1" class="form-select">
                                            <option value="1">Besi Bulat 50</option>
                                            <option value="1">Besi Bulat 20</option>
                                        </select>
                                    </td>
                                    <td><input type="number" name="qtyBahan_1" class="form-control"></td>
                                    <td>
                                        <button onclick="newRow()" class="btn btn-primary btn_add_row">+</button> <button onclick="removeRow2({{1}})" class="btn btn-primary btn_remove">-</button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <select name="namaBahan_1" id="namaBahan_1" class="form-select">
                                            <option value="1">Besi Bulat 50</option>
                                            <option value="1">Besi Bulat 20</option>
                                        </select>
                                    </td>
                                    <td><input type="number" name="qtyBahan_1" class="form-control"></td>
                                    <td>
                                        <button onclick="newRow()" class="btn btn-primary btn_add_row">+</button> <button onclick="removeRow2({{1}})" class="btn btn-primary btn_remove">-</button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <select name="namaBahan_1" id="namaBahan_1" class="form-select">
                                            <option value="1">Besi Bulat 50</option>
                                            <option value="1">Besi Bulat 20</option>
                                        </select>
                                    </td>
                                    <td><input type="number" name="qtyBahan_1" class="form-control"></td>
                                    <td>
                                        <button onclick="newRow()" class="btn btn-primary btn_add_row">+</button> <button onclick="removeRow2({{1}})" class="btn btn-primary btn_remove">-</button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <select name="namaBahan_1" id="namaBahan_1" class="form-select">
                                            <option value="1">Besi Bulat 50</option>
                                            <option value="1">Besi Bulat 20</option>
                                        </select>
                                    </td>
                                    <td><input type="number" name="qtyBahan_1" class="form-control"></td>
                                    <td>
                                        <button onclick="newRow()" class="btn btn-primary btn_add_row">+</button> <button onclick="removeRow2({{1}})" class="btn btn-primary btn_remove">-</button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
            </tbody>
        </table> --}}


        {{-- <h5>Dengan Pisau</h5>
        <table class="table table-borderless table-sm text-center" id="tabelForm" style="vertical-align: top">
            <thead class="table-secondary">
                <tr>
                    <th width="5%">#</th>
                    <th width="15%">Product</th>
                    <th width="15%">Tipe Matras</th>
                    <th width="15%">File Autocad</th>
                    <th>Pisau</th>
                    <th>Bahan Baku </th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>1</td>
                    <td>
                        <select name="product" id="product_1" class="form-select">
                            <option value="1">BBGC.10001.08K</option>
                            <option value="2">BBGC.10006.08K</option>
                        </select>
                    </td>
                    <td>
                        <select name="tipeMatras" id="tipeMatras_1" class="form-select">
                            <option value="1">Samir</option>
                            <option value="2">Roll</option>
                        </select>
                    </td>
                    <td><input type="file" class="form-control" name="fileAutocad_1" id="fileAutocad_1"></td>
                    <td>
                        <table class="table table-borderless table-sm text-center" id="tabelBahanBaku">
                            <thead class="table-secondary">
                                <tr>
                                    <th>No.</th>
                                    <th>Nama Pisau</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>1</td>
                                    <td><input type="text" class="form-control" name="namaPisau_1_1" placeholder="Nama Pisau"></td>
                                </tr>
                                <tr>
                                    <td>2</td>
                                    <td><input type="text" class="form-control" name="namaPisau_1_2" placeholder="Nama Pisau"></td>
                                </tr>
                                <tr>
                                    <td>3</td>
                                    <td><input type="text" class="form-control" name="namaPisau_1_3" placeholder="Nama Pisau"></td>
                                </tr>
                                <tr>
                                    <td>4</td>
                                    <td><input type="text" class="form-control" name="namaPisau_1_4" placeholder="Nama Pisau"></td>
                                </tr>
                                <tr>
                                    <td>5</td>
                                    <td><input type="text" class="form-control" name="namaPisau_1_5" placeholder="Nama Pisau"></td>
                                </tr>
                                <tr>
                                    <td>6</td>
                                    <td><input type="text" class="form-control" name="namaPisau_1_6" placeholder="Nama Pisau"></td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                    <td>
                        <table class="table table-borderless table-sm text-center" id="tabelBahanBaku">
                            <thead class="table-secondary">
                                <tr>
                                    <th>Nama Bahan</th>
                                    <th width="20%">Qty</th>
                                    <th width="20%">Option</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        <select name="namaBahan_1" id="namaBahan_1" class="form-select">
                                            <option value="1">Besi Bulat 50</option>
                                            <option value="1">Besi Bulat 20</option>
                                        </select>
                                    </td>
                                    <td><input type="number" name="qtyBahan_1" class="form-control"></td>
                                    <td>
                                        <button onclick="newRow()" class="btn btn-primary btn_add_row btn-sm">+</button> <button onclick="removeRow2({{1}})" class="btn btn-primary btn_remove btn-sm">-</button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <select name="namaBahan_1" id="namaBahan_1" class="form-select">
                                            <option value="1">Besi Bulat 50</option>
                                            <option value="1">Besi Bulat 20</option>
                                        </select>
                                    </td>
                                    <td><input type="number" name="qtyBahan_1" class="form-control"></td>
                                    <td>
                                        <button onclick="newRow()" class="btn btn-primary btn_add_row btn-sm">+</button> <button onclick="removeRow2({{1}})" class="btn btn-primary btn_remove btn-sm">-</button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <select name="namaBahan_1" id="namaBahan_1" class="form-select">
                                            <option value="1">Besi Bulat 50</option>
                                            <option value="1">Besi Bulat 20</option>
                                        </select>
                                    </td>
                                    <td><input type="number" name="qtyBahan_1" class="form-control"></td>
                                    <td>
                                        <button onclick="newRow()" class="btn btn-primary btn_add_row btn-sm">+</button> <button onclick="removeRow2({{1}})" class="btn btn-primary btn_remove btn-sm">-</button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <select name="namaBahan_1" id="namaBahan_1" class="form-select">
                                            <option value="1">Besi Bulat 50</option>
                                            <option value="1">Besi Bulat 20</option>
                                        </select>
                                    </td>
                                    <td><input type="number" name="qtyBahan_1" class="form-control"></td>
                                    <td>
                                        <button onclick="newRow()" class="btn btn-primary btn_add_row btn-sm">+</button> <button onclick="removeRow2({{1}})" class="btn btn-primary btn_remove btn-sm">-</button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
            </tbody>
        </table> --}}


    </div>
</div>
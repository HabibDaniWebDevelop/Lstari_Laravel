<div class="card-body">
    <div class="row" id="hilang">
        <div class="col-9">
            <button type="hidden" class="btn btn-dark me-4" id="btn_daftarlist" onclick="tabelpilihanpohon()"> <span
                    class="tf-icons bx bx-list-ul"></span>&nbsp; Daftar List Pohon </button>
            <button type="hidden" class="btn btn-dark me-4" id="btn_daftarlist" onclick="tabelpilihanpohondipilih()">
                <span class="tf-icons bx bx-list-ul"></span>&nbsp; Daftar Pohon Terpilih </button>
            <button type="button" class="btn btn-danger" disabled id="btn_batal" onclick="klikBatal()"> <span
                    class="fas fa-times-circle"></span>&nbsp; Batal</button>
            <button type="button" class="btn btn-warning me-4" disabled id="btn_simpan" onclick="KlikSimpan()"
                tabindex="7"><span class="tf-icons bx bx-save"></span>&nbsp; Simpan</button>
            <button type="button" class="btn btn-info" id="btn_cetak" onclick="klikCetak() "> <span
                    class="tf-icons bx bx-printer"></span>&nbsp; Cetak</button>
        </div>
    </div>
    <hr>
    <div class="row" id="hilang">
        <div class="col-2 center">
            <label class="form-label" for="IDSPKOInject" style="font-size: 17px;">Total Prioritas : <span id="tpri"
                    style="font-weight: bold; color: DodgerBlue; font-size: 30px; padding-left: 10px;"></span></label>
        </div>
        <div class="col-2">
            <label class="form-label" for="basic-icon-default-fullname" style="font-size: 17px;">Total Poles : <span
                    id="poles"
                    style="font-weight: bold; color: DodgerBlue; font-size: 30px; padding-left: 10px;"></span></label></label>
        </div>
        <div class="col-2">
            <label class="form-label" for="basic-icon-default-fullname" style="font-size: 17px;">Total Patri : <span
                    id="patri"
                    style="font-weight: bold; color: DodgerBlue; font-size: 30px; padding-left: 10px;"></span></label></label>
        </div>
        <div class="col-2">
            <label class="form-label" for="basic-icon-default-fullname" style="font-size: 17px;">Total PUK : <span
                    id="puk"
                    style="font-weight: bold; color: DodgerBlue; font-size: 30px; padding-left: 10px;"></span></label>
            </label>
        </div>
        <div class="col-3">
            <label class="form-label" for="basic-icon-default-fullname" style="font-size: 17px;">Total Berat FG : <span
                    id="tfg"
                    style="font-weight: bold; color: DodgerBlue; font-size: 30px; padding-left: 10px;"></span></label></label>
        </div>
    </div>
    <div class="row" id="hilang">

    </div>
    <hr>
    <div class="table-responsive" style="height:calc(80vh);">

        <table class="table table-border table-hover table-sm" id="tabel1">
            <thead class="table-secondary text-center sticky-top zindex-2">
                <tr class="px-0 mx-0" style="text-align: center">
                    <th class="px-0 mx-0">PILL</th>
                    <th class="px-0 mx-0">Tanggal </th>
                    <th class="px-0 mx-0">No.Pohon</th>
                    <th class="px-0 mx-0">Kadar</th>
                    <th class="px-0 mx-0">NO.SPK PPIC</th>
                    <th class="px-0 mx-0">Model</th>
                    <th class="px-0 mx-0">Product</th>
                    <th class="px-0 mx-0">Jumlah</th>
                    <th class="px-0 mx-0">Poles</th>
                    <th class="px-0 mx-0">Patri</th>
                    <th class="px-0 mx-0">PUK</th>
                    <th class="px-0 mx-0">Brt Perk</th>
                </tr>
            </thead>
            <tbody class="text-center p-0 m-0" style="text-align: center">
            </tbody>
        </table>
        <table hidden class="table table-border table-hover table-sm" id="tabel2">
            <thead class="table-secondary text-center sticky-top zindex-2">
                <tr class="p-0 m-0" style="text-align: center">
                    <th class="p-0 m-0">PILL</th>
                    <th class="p-0 m-0">Tanggal </th>
                    <th class="p-0 m-0">No.Pohon</th>
                    <th class="p-0 m-0">Kadar</th>
                    <th class="p-0 m-0">NO.SPK PPIC</th>
                    <th class="p-0 m-0">Model</th>
                    <th class="p-0 m-0">Product</th>
                    <th class="p-0 m-0">Jumlah</th>
                    <th class="p-0 m-0">Poles</th>
                    <th class="p-0 m-0">Patri</th>
                    <th class="p-0 m-0">PUK</th>
                    <th class="p-0 m-0">Brt Perk</th>
                </tr>
            </thead>
            <tbody class="text-center p-0 m-0" style="text-align: center">
            </tbody>
        </table>
    </div>

    <div class="float-end">
        <td>Total Pohon: <span id="totaldata"
                style="font-weight: bold; color: DodgerBlue; font-size: 20px; padding-left: 1px;">0</span>
        </td>
        <td>Total Jumlah: <span id="totaldatapohon"
                style="font-weight: bold; color: DodgerBlue; font-size: 20px; padding-left: 1px;">0</span>
        </td>
        <td> &nbsp;Total Poles: <span id="totaldatapoles"
                style="font-weight: bold; color: DodgerBlue; font-size: 20px; padding-left: 1px;">0</span></td>
        <td> &nbsp;Total Patri: <span id="totaldatapatri"
                style="font-weight: bold; color: DodgerBlue; font-size: 20px; padding-left: 1px;">0</span></td>
        <td> &nbsp;Total PUK: <span id="totaldatapuk"
                style="font-weight: bold; color: DodgerBlue; font-size: 20px; padding-left: 1px;">0</span></td>
        <td> &nbsp;Total Berat FG: <span id="totaldataberat"
                style="font-weight: bold; color: DodgerBlue; font-size: 20px; padding-left: 1px;">0</span></td>
    </div>
</div>

<script>
// Data Table Settings
</script>
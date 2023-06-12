<h5 class="card-header">SPK PPIC Direct Casting</h5>
<div class="card-body">
    <div class="row">
        <div class="col-8">
            <button type="button" class="btn btn-primary" id="btn_baru" onclick="KlikBaru()"> <span class="tf-icons bx bx-plus-circle"></span>&nbsp; Baru </button>
            <button type="button" class="btn btn-primary me-4" disabled="" id="btn_ubah" disabled="" onclick="KlikUbah()"><span class="tf-icons bx bx-edit"></span>&nbsp; Ubah</button>
            <button type="button" class="btn btn-danger" disabled="" id="btn_batal" onclick="klikBatal()"> <span class="fas fa-times-circle"></span>&nbsp; Batal</button>
            <button type="button" class="btn btn-warning" disabled="" id="btn_simpan" onclick="KlikSimpan()"><span class="tf-icons bx bx-save"></span>&nbsp; Simpan</button>
            <button type="button" class="btn btn-info" id="btn_cetak" onclick="klikCetak()" disabled=""> <span class="tf-icons bx bx-printer"></span>&nbsp; Cetak</button>
        </div>
        <div class="col-4">
            <div>
                <b>Scan SPK Produksi</b>
                <input type="text" class="form-control" name="idwo" id="idwo" width="50%" onchange="getListItem(this.value)">
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12" id="tampungtabel">
           <form id="listitem">
                <table width="100%" class="table table-border table-hover table-sm dataTable no-footer">
                    <thead class="table-secondary sticky-top zindex-2">
                        <th style="text-align: center;">No.</th>
                        <th style="text-align: center;">No.SPK</th>
                        <th style="text-align: center;">Kode Produk</th>
                        <th style="text-align: center;">Jumlah</th>
                        <th style="text-align: center;">Keterangan</th>
                    </thead>
                    <tbody>
                        
                    </tbody>
                </table>
            </form>
        </div>
    </div>
</div>
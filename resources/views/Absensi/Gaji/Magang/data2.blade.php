<h5 class="card-header">
</h5>
<div class="card-body">
    <div class="row">
        <div class="col-9">
            <button type="button" class="btn btn-primary" id="btn_baru" onclick="KlikBaru()"> <span class="tf-icons bx bx-plus-circle"></span>&nbsp; Baru </button>
            <button type="button" class="btn btn-primary me-4" disabled="" id="btn_ubah" disabled="" onclick="KlikUbah()"><span class="tf-icons bx bx-edit"></span>&nbsp; Ubah</button>
            <button type="button" class="btn btn-danger" disabled="" id="btn_batal" onclick="klikBatal()"> <span class="fas fa-times-circle"></span>&nbsp; Batal</button>
            <button type="button" class="btn btn-warning" disabled="" id="btn_simpan" onclick="KlikSimpan()"><span class="tf-icons bx bx-save"></span>&nbsp; Simpan</button>
            <button type="button" class="btn btn-info" id="btn_cetak" onclick="klikCetak()" disabled=""> <span class="tf-icons bx bx-printer"></span>&nbsp; Cetak</button>
            <input type="hidden" id="payrollID" value="" type="number">
            <input type="hidden" id="action" value="simpan" type="text">
        </div>
        <div class="col-3">
            <!-- <div class="float-end">
                <div class="input-group input-group-merge">
                    <span class="input-group-text"><i class="bx bx-search"></i></span>
                    <input type="text" class="form-control" placeholder="Search..." autofocus="" id="cari">
                </div>
            </div> -->
            <div class="float-end">
                <div class="input-group input-group-merge">
                    <span class="input-group-text"><i class="bx bx-search" onclick="klikViewSelection()"></i></span>
                    <input type="text" class="form-control" placeholder="Search..." autofocus="" id="cari" list="carilist">
                </div>
                <datalist id="carilist">
                    @foreach ($history as $item)
                    <option value="{{$item->SW}}">{{$item->SW}}</option>
                    @endforeach
                </datalist>
            </div>
        </div>
    </div>
    <hr>
    <div class="row">
        <div class="col-2">
            <label class="form-label" for="tanggal_awal">Tanggal :</label>
            <input type="date" class="form-control" disabled="" value="{{$datenow}}" onchange="tanggalChange()" id="tanggal_awal">
        </div>
        <div class="col-2">
            <label class="form-label" for="tanggal_akhir">Hingga :</label>
            <input type="date" class="form-control" disabled="" value="{{$datenow}}" onchange="tanggalChange()" id="tanggal_akhir">
        </div>
    </div>
    <br>

    {{-- Input new form --}}
    <div>
        <table class="table table-borderless table-sm" id="tabel1">
            <thead class="table-secondary">
                <tr style="text-align: center">
                    <th width="6%"> NO </th>
                    <th width="30%"> Nama Karyawan </th>
                    <th> ID Karyawan </th>
                    <th> Total Kerja </th>
                    <th> Total Gaji </th>
                    <th> Detail </th>
                    <th width="6%"> # </th>
                </tr>
            </thead>
            <tbody class="text-center">
            </tbody>
        </table>

    </div>
</div>
@include('Absensi.Gaji.Magang.modal')
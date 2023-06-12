<div class="card-body" style="height:calc(100vh - 255px);">

    <div class="demo-inline-spacing d-flex mb-3">

        <div class="input-group w-px-250">
            <span class="input-group-text btn-dark disabled">Tanggal</span>
            <input type="date" class="form-control" id="tgl_mulai" name="tgl_mulai">
        </div>

        <div class="input-group w-px-250">
            <span class="input-group-text btn-dark">Sampai</span>
            <input type="date" class="form-control" id="tgl_akhir" name="tgl_akhir">
        </div>

        <div class="input-group w-px-250 me-4">
            <label class="input-group-text btn-dark">Jenis</label>
            <select class="form-control" name="jenis" id="jenis">
                <option disabled selected value="0">Silahkan Pilih</option>
                <option value="1">SPK</option>
                <option value="2">SPK Dikerjakan</option>
                <option value="3">SPK Selesai</option>
                <option value="4">SPK Belum Selesai</option>
                <option value="5">Rekapitulasi</option>
            </select>
        </div>

        <button type="button" class="btn btn-primary me-4" id="Cari1" onclick="Klik_Cari1()">  Search </button>
    </div>
    <hr class="m-0 mb-2" />
    <form id="form1">
        <div id="tampil" class="d-none">

        </div>
    </form>


</div>

{{-- @include('IT.DataPC.modal') --}}


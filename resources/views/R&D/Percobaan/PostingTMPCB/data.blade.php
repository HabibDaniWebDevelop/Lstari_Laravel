<div class="card-body">
    <div class="row">
        <div class="col-9">
            <button type="button" class="btn btn-primary" id="btn_baru" onclick="KlikBaru()"> <span class="tf-icons bx bx-plus-circle"></span>&nbsp; Baru </button>
            <button type="button" class="btn btn-dark me-4" id="btn_posting" disabled="" onclick="KlikPosting()"><span class="tf-icons bx bx-send"></span>&nbsp; Posting</button>
            <button type="button" class="btn btn-info" id="btn_cetak" onclick="klikCetak()"> <span class="tf-icons bx bx-printer"></span>&nbsp; Cetak</button>
            <input type="hidden" id="idTMHidden" value="" type="number">
        </div>
    </div>
    <hr>
    <div class="row">
        <div class="col-3">
            <label class="form-label" for="idTM">ID TM :</label>
            <input type="text" class="form-control" onchange="getTM()" id="idTM">
        </div>
        <div class="col-3">
            <label class="form-label" for="tanggalTM">Tanggal :</label>
            <input type="date" class="form-control" disabled="" id="tanggalTM">
        </div>
        <div class="col-3">
            <label class="form-label" for="totalJumlah">Total Jumlah :</label>
            <input type="text" disabled class="form-control" id="totalJumlah">
        </div>
        <div class="col-3">
            <label class="form-label" for="totalBerat">Total Berat :</label>
            <input type="text" disabled class="form-control" id="totalBerat">
        </div>
    </div>
    <hr>

    {{-- Input new form --}}
    <div id="TableItems">
        <table class="table table-borderless table-striped table-sm" id="tabel1">
            <thead class="table-secondary">
                <tr style="text-align: center">
                    <th> No. </th>
                    <th> No. SPK </th>
                    <th> Product </th>
                    <th> Kadar </th>
                    <th> Jumlah </th>
                    <th> Berat </th>
                    <th> Gambar </th>
                </tr>
            </thead>
            <tbody class="text-center">
            </tbody>
        </table>

    </div>
</div>
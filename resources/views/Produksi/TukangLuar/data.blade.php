<div class="card-body">
    <div class="row">
        <div class="col-9">
            <button type="button" class="btn btn-info" id="btn_export_excel" onclick="html_table_to_excel('TukangLuar','xlsx')" disabled> <span class="tf-icons bx bx-printer"></span>&nbsp; Export Excel</button>
            <button type="button" class="btn btn-info" id="btn_cetak" onclick="klikCetak()" disabled> <span class="tf-icons bx bx-printer"></span>&nbsp; Cetak</button>
            <input type="hidden" id="IDSPKOTukangLuar" value="" type="number">
            <input type="hidden" id="action" value="simpan" type="text">
        </div>
    </div>
    <hr>
    <div class="row">
        <div class="col-3">
            <label class="form-label" for="nomorNota">No. Nota Tukang Luar : <span id="nomorNotaFound"></span></label>
            <div class="input-group">
                <input type="text" class="form-control" id="nomorNota" onkeydown="event.keyCode == 13 ? CariNotaTukangLuar() : console.log('nothing')">
                <button class="btn btn-primary" onclick="CariNotaTukangLuar()">Search</button>
            </div>
        </div>
        <div class="col-3">
            <label class="form-label" for="employeeName">Nama Tukang Luar :</label>
            <input type="text" disabled class="form-control" id="employeeName">
        </div>
        <div class="col-3">
            <label class="form-label" for="kadar">Kadar :</label>
            <input type="text" disabled class="form-control" id="kadar">
        </div>
        <div class="col-3">
            <label class="form-label" for="proses">Proses :</label>
            <input type="text" disabled class="form-control" id="proses">
        </div>
    </div>
    <hr>
    <div class="row">
        <div class="col-12">
            <div class="table-responsive text-nowrap" style="height:calc(100vh - 415px);" id="TransactionValue">
            </div>
        </div>
    </div>
</div>
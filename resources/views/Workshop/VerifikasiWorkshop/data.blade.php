<div class="card-body">
    <div class="row">
        <div class="col-6">
            <label class="form-label" for="spkPCB">No. SPK PCB :</label>
            <div class="input-group">
                <input type="number" placeholder="No. SPK PCB" class="form-control" id="spkPCB">
                <button class="btn btn-outline-primary" type="button" id="btn_cariSPKPCB" onclick="cariSPKPCB()">Cari</button>
            </div>
        </div>
        <div class="col-2">
            <label class="form-label" for="spkPCB">.</label>
            <div class="input-group">
                <button class="btn btn-outline-primary " type="button" id="btn_simpan" onclick="simpanWIP()">Simpan WIP</button>
            </div>
        </div>
    </div>
    <br>

    {{-- Input new form --}}
    <div id="spkItems">
        <table class="table table-borderless table-sm" id="tabel1">
            <thead class="table-secondary">
                <tr style="text-align: center">
                    <th>NO</th>
                    <th>Photo 2D</th>
                    <th>Photo 3D</th>
                    <th>Product</th>
                    <th>File Corel</th>
                    <th>File 3D</th>
                </tr>
            </thead>
            <tbody class="text-center">
            </tbody>
        </table>
    </div>
</div>
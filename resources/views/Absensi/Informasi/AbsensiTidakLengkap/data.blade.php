<div class="card-body">
    <div class="row">
        <div class="col-2">
            <label class="form-label" for="tanggal_awal">Tanggal :</label>
            <input type="date" class="form-control" onchange="SearchAbsensiTidakLengkap()" id="tanggal_awal">
        </div>
        <div class="col-2">
            <label class="form-label" for="tanggal_akhir">Hingga :</label>
            <input type="date" class="form-control" onchange="SearchAbsensiTidakLengkap()" id="tanggal_akhir">
        </div>
        
    </div>
    <div class="row">
        <div class="col-4">
            <label class="form-label" for="Jenis">Jenis :</label>
            <div class="input-group">
                <select class="form-control" name="jenis" id="jenis">
                    <option value="1">Check Clock Tidak Lengkap</option>
                    <option value="2">Tidak Masuk Kerja</option>
                    <option value="3">Tidak Ada Ijin Kerja</option>
                    <option value="4">Masuk Kerja, ada Ijin Kerja</option>
                    <option value="5">Lembur, ada Ijin Kerja</option>			    						    			
                </select>
                <button class="btn btn-primary" onclick="SearchAbsensiTidakLengkap()">Search</button>
            </div>
        </div>
    </div>
    <br>

    {{-- Input new form --}}
    <div id="TableDevExtreme">
    </div>
</div>
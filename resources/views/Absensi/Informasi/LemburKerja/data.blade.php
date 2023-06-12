<div class="card-body">
    <div class="row">
        <div class="col-2">
            <label class="form-label" for="tanggal_awal">Tanggal :</label>
            <input type="date" class="form-control" onchange="SearchLemburKerja()" id="tanggal_awal">
        </div>
        <div class="col-2">
            <label class="form-label" for="tanggal_akhir">Hingga :</label>
            <input type="date" class="form-control" onchange="SearchLemburKerja()" id="tanggal_akhir">
        </div>
        
    </div>
    <div class="row">
        <div class="col-4">
            <label class="form-label" for="Jenis">Jenis :</label>
            <div class="input-group">
                <select class="form-control" name="jenis" id="jenis">
                    <option value="1">Lembur</option>
                    <option value="2">Rekapitulasi</option>
                    <option value="3">Koreksi</option>
                    <option value="4">Belum Di-entry</option>
                    <option value="5">Beda Jam Lembur</option>	
                    <option value="6">Pulang Awal</option>	
                    <option value="7">Tambahan Uang Makan</option>			    						    					    						    			
                </select>
                <button class="btn btn-primary" onclick="SearchLemburKerja()">Search</button>
            </div>
        </div>
    </div>
    <br>

    {{-- Input new form --}}
    <div id="TableDevExtreme">
    </div>
</div>
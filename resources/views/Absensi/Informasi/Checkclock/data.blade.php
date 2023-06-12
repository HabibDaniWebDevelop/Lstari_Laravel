<div class="card-body">
    <div class="row">
        <div class="col-2">
            <label class="form-label" for="tanggal_awal">Tanggal :</label>
            <input type="date" class="form-control" onchange="SearchCheckClock()" id="tanggal_awal">
        </div>
        <div class="col-2">
            <label class="form-label" for="tanggal_akhir">Hingga :</label>
            
            <input type="date" class="form-control" onchange="SearchCheckClock()" id="tanggal_akhir">
            </div>
        
    </div>
    <div class="row">
        <div class="col-4">
            <label class="form-label" for="Jenis">Jenis :</label>
            <div class="input-group">
                <select class="form-control" name="jenis" id="jenis">
                    <option value="1">Checkclok Machine</option>
                    <option value="2">Checkclok Manual</option>
                    <option value="3">Tidak CheckClock Wajah</option>
                </select>
                <button class="btn btn-primary" onclick="SearchCheckClock()">Search</button>
            </div>
        </div>
    </div>
    <br>

    {{-- Input new form --}}
    <div id="TableDevExtreme">
    </div>

    <div class="modal fade" id="modalinfo" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg" id="modalformat" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="JudulModal">History</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="formmodal1">
                        <div id="modal1">
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>    
</div>
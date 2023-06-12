<div class="card-body">
    <div class="row">
        <div class="col-4">
            <label class="form-label" for="Jenis">Jenis :</label>
            <div class="input-group">
                <select class="form-control" name="jenis" id="jenis">
                    <option value="1">Informasi WIP Grafis</option>
                    <option value="2">Informasi WIP Produk FG Grafis</option>	    						    			
                </select>
                <button class="btn btn-primary" onclick="GetWIP()">Search</button>
            </div>
        </div>
        <div class="col-12">
            <div id="TableDevExtreme">
            </div>
        </div>
    </div>
</div>
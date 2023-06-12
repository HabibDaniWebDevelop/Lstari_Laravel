<div class="card-body">
    <div class="row">
        <div class="col-2">
            <label class="form-label" for="tanggal_awal">Tanggal :</label>
            <input type="date" class="form-control" id="tanggal_awal">
        </div>
        <div class="col-2">
            <label class="form-label" for="tanggal_akhir">Hingga :</label>
            <input type="date" class="form-control" id="tanggal_akhir">
        </div>
        
    </div>
    <div class="row">
        <div class="col-4">
            <label class="form-label" for="department">Department :</label>
            <div class="input-group">
                <select class="form-select" name="department" id="department">
                    @foreach ($department as $item)
                        <option value="{{$item->ID}}">{{$item->Description}}</option>
                    @endforeach
                </select>
                <button class="btn btn-primary" onclick="SearchPenilaianAbsensi()">Search</button>
            </div>
        </div>
    </div>
    <br>
</div>
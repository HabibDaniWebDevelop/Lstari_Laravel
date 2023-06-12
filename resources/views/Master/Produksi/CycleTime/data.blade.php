<div class="card-body">
    
    <div class="row">
        <div class="col-2">
            <label class="form-label">Operation :</label>
            <form id="datasimpan">
            <select class="form-select" name="operation" id="operation">
                <option value="">Pilih</option>
                @foreach ($data as $datas)
                <option value="{{$datas->ID}}">{{$datas->Name}}</option>
                @endforeach
            </select>
            </form>
        </div>
        <div class="col-2">
            <label class="form-label" for="tglstart">Tanggal :</label>
            <input type="date" class="form-control" id="tglstart">
        </div>
        <div class="col-2">
            <label class="form-label" for="tglend">Hingga :</label>
            <div class="input-group">
                <input type="date" class="form-control" id="tglend">
                <button class="btn btn-primary" onclick="lihat()">Search</button>
            </div>
        </div>
        <div class="col-1">
            <label class="form-label" for="simpan" style="color:white">Simpan :</label><br>
            <button class="btn btn-primary" onclick="simpanCek()">Simpan</button>
        </div>
        <div class="col-1">
            <label class="form-label" for="simpan" style="color:white">DataMaster :</label><br>
            <button class="btn btn-dark" onclick="lihatDataMaster()">MasterData</button>
        </div>
        <form id="datasimpan2">
        <input type="hidden" name="pilih" id="pilih" value="">	
        </form>
    </div>
    <br>
    <div id="tampil"></div> 
</div>

  
@include('Master.Produksi.CycleTime.modal')





  
  
  

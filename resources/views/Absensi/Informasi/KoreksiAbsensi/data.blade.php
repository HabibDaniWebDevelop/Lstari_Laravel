<div class="card-body">
    <div class="row">
        <div class="col-4">
            <label class="form-label" for="periode">Periode :</label>
            <select class="form-control" name="periode" id="periode">
                @foreach ($periode as $item)
                    <option value="{{$item->ID}}">{{$item->PeriodDate}}</option>
                @endforeach		    						    			
            </select>
        </div>
        
    </div>
    <div class="row">
        <div class="col-4">
            <label class="form-label" for="Jenis">Jenis :</label>
            <div class="input-group">
                <select class="form-control" name="jenis" id="jenis">
                    <option value="1">Ijin</option>
                    <option value="2">Lembur</option>
                    <option value="3">Penggajian</option>		    			
                </select>
                <button class="btn btn-primary" onclick="SearchKoreksiAbsensi()">Search</button>
            </div>
        </div>
    </div>
    <br>

    {{-- Input new form --}}
    <div id="TableDevExtreme">
    </div>
</div>
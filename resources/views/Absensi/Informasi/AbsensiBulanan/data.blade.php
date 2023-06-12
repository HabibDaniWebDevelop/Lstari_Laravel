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
                    <option value="1">Rekap</option>
                    <option value="2">Lembur Kerja</option>
                    <option value="3">Jam Kerja</option>		    			
                </select>
                <button class="btn btn-primary" onclick="SearchAbsensiBulanan()">Search</button>
            </div>
        </div>
    </div>
    <br>

    {{-- Input new form --}}
    <div id="TableDevExtreme">
    </div>
</div>
<label class="form-label">Bulan</label>
<select class="form-select" id='bulannyax' name='bulannyax'>
    <option value="0" selected>Pilih</option>
    <div id="options"></div>
    @foreach($data as $dataOK)
    <option value="{{$dataOK->Tanggal}}" >{{$dataOK->Bulan}}</option>
    @endforeach
</select>
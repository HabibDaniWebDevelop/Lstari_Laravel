<input class="form-control" list="datalistNTHKO" name="idcari" id="idcari" placeholder="Cari" onchange="klikLihat()">
<datalist id="datalistNTHKO">
    @foreach($data as $datas)
        <option value={{ $datas->ID }}>{{ $datas->NTHKO }}</option>
    @endforeach
</datalist>
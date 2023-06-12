<label class="control-label" for="produk">Produk</label>
<select class="form-select" id='produk' name='produk' onchange="getProduk()">
    <option value="" selected>Pilih</option>
    @foreach($produklist as $produkOK)
    <option value="{{$produkOK->Produk}}" >{{$produkOK->Produk}}</option>
    @endforeach
</select>
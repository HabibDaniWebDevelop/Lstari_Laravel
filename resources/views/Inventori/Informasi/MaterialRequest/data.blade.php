<div class="card-body pt-0">

    <div class="demo-inline-spacing d-flex mb-0">

        <div class="input-group  w-px-250">
            <span class="input-group-text btn-primary">Tanggal</span>
            <input type="date" class="form-control" id="tgl_mulai" name="tgl_mulai" value="{{date("Y-m-01")}}">
        </div>

        <div class="input-group  w-px-250 ms-2">
            <span class="input-group-text btn-primary">Sampai</span>
            <input type="date" class="form-control" id="tgl_akhir" name="tgl_akhir" value="{{date("Y-m-d")}}">
        </div>

        <div class="input-group  w-px-300 ms-3">
            <span class="input-group-text btn-primary">Department</span>

            @if($Akses == '1')
                <select name="Department" id="Department" class="form-select">
                    <option value=""> </option>
                    @foreach ($Department as $item)
                        <option value="{{ $item->ID }}">{{ $item->Description }}</option>
                    @endforeach
                </select>
            @else
                <select name="Department" id="Department" class="form-select" disabled>
                    @foreach ($Department as $item)
                        <option {{ $iddept == $item->ID ? 'selected' : '' }} value="{{ $item->ID }}">{{ $item->Description }}</option>
                    @endforeach
                </select>
            @endif
            

        </div>
    </div>

     <hr class="my-2" />

    <div class="demo-inline-spacing d-flex tombol-group mb-2">
        <button type="button" class="btn btn-secondary" id="btn1" onclick="Klik_btn(1)">Permintaan Barang</button>
        <button type="button" class="btn btn-secondary" id="btn2" onclick="Klik_btn(2)">Belum Dibelikan</button>
        <button type="button" class="btn btn-secondary" id="btn3" onclick="Klik_btn(3)">Belum Diambil</button>
        <button type="button" class="btn btn-secondary" id="btn4" onclick="Klik_btn(4)">Min Stock</button>
        <button type="button" class="btn btn-secondary" id="btn5" onclick="Klik_btn(5)">Transfer Stock</button>
        <button type="button" class="btn btn-secondary" id="btn6" onclick="Klik_btn(6)">Transfer Non Stock</button>
        <button type="button" class="btn btn-secondary" id="btn7" onclick="Klik_btn(7)">Transfer Inventori</button>
    </div>

       
    <form id="form1">
        <div id="tampil">
        </div>
    </form>

</div>



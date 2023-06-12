<div class="card-body">
    <div class="row">
        <div class="col-9">
            <button type="button" class="btn btn-dark me-4" id="btn_posting" disabled="" onclick="KlikPosting()"><span class="tf-icons bx bx-send"></span>&nbsp; Posting</button>
            <input type="hidden" id="idTMKaretKeLilin" value="" type="number">
        </div>
        <div class="col-3">
            <div class="float-end">
                <div class="input-group input-group-merge">
                    <span class="input-group-text"><i class="bx bx-search"></i></span>
                    <input type="text" class="form-control" placeholder="Search..." onchange="KlikCari()" autofocus="" id="cari" list="carilist">
                </div>
                <datalist id="carilist">
                    @foreach ($listhist as $item)
                        <option value="{{$item->ID}}">{{$item->ID}}</option>
                    @endforeach
                </datalist>
            </div>
        </div>
    </div>
    <hr>
    <div class="row">
        <div class="col-3">
            <label for="postingStatus" class="form-label">Posting Status :</label>
            <input type="text" class="form-control" id="postingStatus" disabled="">
        </div>
    </div>
    <div class="row">
        <div class="col-3">
            <label for="validateKaret" class="form-label">ID Karet Scan :</label>
            <input type="text" class="form-control" placeholder="ID Karet Scan" onchange="validateKaret()" autofocus="" id="validateKaret">
        </div>
    </div>

    {{-- Input new form --}}
    <div id="TableItems">
        <table class="table table-borderless table-striped table-sm" id="tabel1">
            <thead class="table-secondary">
                <tr style="text-align: center">
                    <th> No. </th>
                    <th> No. NTHKO</th>
                    <th> Product </th>
                    <th> Bulan STP</th>
                    <th> Rubber Kepala </th>
                    <th> Nama Product Kepala </th>
                    <th> Rubber Mainan </th>
                    <th> Nama Product Mainan </th>
                    <th> Rubber Komponen </th>
                    <th> Nama Product Komponen </th>
                </tr>
            </thead>
            <tbody class="text-center">
            </tbody>
        </table>

    </div>
</div>
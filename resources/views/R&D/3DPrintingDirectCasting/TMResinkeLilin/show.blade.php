<div class="row my-4">

    <div class="col-md-4 mb-2">
        <div class="form-group">
            <label class="form-label">Tanggal</label>
            <input type="date" class="form-control" name="tanggal" id="tanggal" disabled
                value="{{ $tmresinkelilins[0]->TransDate }}">
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label class="form-label">Area Tujuan</label>
            <input type="text" class="form-control" disabled value="Lilin">
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label class="form-label">Penerima</label>
            <input type="text" class="form-control" disabled value="{{ $tmresinkelilins[0]->penerima }}">
        </div>
    </div>

    <div class="col-md-12">
        <div class="form-group">
            <label class="form-label">Catatan</label>
            <input type="text" class="form-control" name="note" id="note" disabled
                value="{{ $tmresinkelilins[0]->Remarks }}">
        </div>
    </div>
</div>

<input type="hidden" id="idm" value="{{ $tmresinkelilins[0]->ID }}">
<input type="hidden" id="postingstatus" value="{{ $tmresinkelilins[0]->Active }}">

<div class="table-responsive text-nowrap" style="height:calc(100vh - 550px);">

    <table class="table table-border table-hover table-sm" id="tabel1">
        <thead class="table-secondary sticky-top zindex-2">
            <tr style="text-align: center">
                <th>Envelope</th>
                <th>Kode Produk</th>
                <th>Deskripsi</th>
                <th>Kadar</th>
                <th>Material</th>
                <th>Qty</th>
                <th>Result</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($datas as $data1)
            <tr class="klik" id="{{ $data1->ID }}" style="text-align: center">
                <td><span class="badge bg-dark"
                        style="font-size:14px;">{{ $data1->ENVE }}</span><br>{{ $data1->SWNTHKO }}</td>
                <td><span class="badge bg-dark" style="font-size:14px;">{{$data1->codes}}</span><br>{{ $data1->SKU }}
                </td>
                <td>{{ $data1->DD }}</td>
                <td>{{ $data1->Description }}</td>
                <td>{{ $data1->Material }}</td>
                <td>{{ $data1->Qty }}</td>
                <td>Good</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
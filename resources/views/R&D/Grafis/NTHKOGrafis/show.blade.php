<style>
    #tabel1 {
        /* zoom: 90%; */
        color: black;
    }

    .tabel2 td,
    .tabel3 th {
        border: 1px solid black;
        padding: 0px;
        white-space: nowrap;
        text-overflow: ellipsis;
    }

    .tabel2 td input,
    .tabel2 td select {
        color: black !important;
        border: 0px;
        border-radius: 0px;
    }

    .dropify-wrapper .dropify-message p {
        font-size: initial;
    }
</style>

<div class="row my-4">

    <div class="col-md-2">
        <div class="form-group">
            <label class="form-label">No Form Kerja</label>
            <input type="text" class="form-control form-control-sm fs-6" readonly name="SW" id='cari2' value="{{ $data1[0]->SW }}">
        </div>
    </div>

    <div class="col-md-2 mb-2">
        <div class="form-group">
            <label class="form-label">Tanggal</label>
            <input type="date" class="form-control form-control-sm fs-6" name="tanggal" id="tanggal" disabled
                value="{{ $data1[0]->EntryDate }}">
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            <label class="form-label">Operator</label>
            <input type="text" class="form-control form-control-sm fs-6" disabled value="{{ $data1[0]->name }}">
        </div>
    </div>
</div>
<div class="row my-4">
    <div class="col-md-2">
        <div class="form-group">
            <label class="form-label">Toatl Jumlah</label>
            <input type="text" class="form-control form-control-sm fs-6" disabled value="{{ $data1[0]->TargetQty }}">
        </div>
    </div>

    <div class="col-md-2">
        <div class="form-group">
            <label class="form-label">Total Berat</label>
            <input type="text" class="form-control form-control-sm fs-6" name="tberat" readonly
                value="{{ $data1[0]->Weight }}" id="tberat">
        </div>
    </div>

    <div class="col-md-2">
        <div class="form-group">
            <label class="form-label">Berat Qc</label>
            <input type="text" class="form-control form-control-sm fs-6" name="berat_qc" readonly
                value="{{ $berat['berat_qc'] }}" id="berat_qc">
        </div>
    </div>

    <div class="col-md-2">
        <div class="form-group">
            <label class="form-label">Berat Cor</label>
            <input type="text" class="form-control form-control-sm fs-6" name="berat_cor" readonly
                value="{{ $berat['berat_cor'] }}" id="berat_cor">
        </div>
    </div>

    <div class="col-md-2">
        <div class="form-group">
            <label class="form-label">Berat Reparasi</label>
            <input type="text" class="form-control form-control-sm fs-6" name="berat_rep" readonly
                value="{{ $berat['berat_rep'] }}" id="berat_rep">
        </div>
    </div>

    <div class="col-md-2">
        <div class="form-group">
            <label class="form-label">Berat SC</label>
            <input type="text" class="form-control form-control-sm fs-6" name="berat_sc" readonly
                value="{{ $berat['berat_sc'] }}" id="berat_sc">
        </div>
    </div>

    <div class="col-md-2">
        <div class="form-group">
            <label class="form-label">Berat Var P</label>
            <input type="text" class="form-control form-control-sm fs-6" name="berat_varp" readonly
                value="{{ $berat['berat_varp'] }}" id="berat_varp">
        </div>
    </div>

    <div class="col-md-2">
        <div class="form-group">
            <label class="form-label">Berat Sepuh</label>
            <input type="text" class="form-control form-control-sm fs-6" name="berat_sepuh" readonly
                value="{{ $berat['berat_sepuh'] }}" id="berat_sepuh">
        </div>
    </div>

    <div class="col-md-6">
    </div>

</div>

<input type="hidden" name="idworkallocation" id="idworkallocation" value="{{ $data1[0]->ID }}">
<input type="hidden" id="postingstatus" value="{{ $status }}">
<input type="hidden" id="selscale">

<hr>

<div class="table-responsive">
<table class="table table-bordered table-striped table-sm" id="tabel1">
    <thead class="table-secondary">
        <tr class="text-center">
            <th width="5%"> No. </th>
            <th width="10%"> Product </th>
            <th width="8%"> Kadar </th>
            <th width="5%"> Jumlah </th>
            <th width="5%"> Berat </th>
            <th width="10%"> Proses Selanjutnya </th>
            <th width="10%"> Variasi </th>
            <th width="25%"> Gambar </th> 
        </tr>
    </thead>
    <tbody>
        @foreach ($listwa as $item)
        @php
            $item->gambar = str_replace(".jpg", "", $item->gambar).".jpg";
        @endphp
        <tr class="text-center">
            <td>{{$loop->iteration}}</td>
            <td>{{$item->Product}}</td>
            <td>{{$item->kadar}}</td>
            <td>{{$item->jumlah}}</td>
            <td>
                <input type="text" class="form-control form-control-sm fs-6 w-100 weight-item" style="background-color: #FCF3CF;" id="{{ $item->Ordinal }}"
                name="brthasilgrf[{{ $item->Ordinal }}]" readonly value="{{ $item->berat }}" >
            </td>
            <td>
                <select class="form-select form-select-sm fs-6 w-100 next-process" id="next1" name="next[{{ $item->Ordinal }}]" onchange="calculateWeight()">
                    <option value="753" <?php echo $item->next == '753' ? 'selected' : ''; ?> >Barang Siap QC</option>
                    <option value="256" <?php echo $item->next == '256' ? 'selected' : ''; ?>>Selesai Cor</option>
                    <option value="254" <?php echo $item->next == '254' ? 'selected' : ''; ?>>Barang Siap Reparasi</option>
                    <option value="98" <?php echo $item->next == '98' ? 'selected' : ''; ?>>Barang Siap Rep SC</option>
                    <option value="2234" <?php echo $item->next == '2234' ? 'selected' : ''; ?>>Barang Siap Var P</option>
                    <option value="260" <?php echo $item->next == '260' ? 'selected' : ''; ?>>Barang Siap Sepuh</option>
                </select>
            </td>
            <td>{{$item->Variasi}}</td>
            <td><img src="{{Session::get('hostfoto')}}/image/{{$item->gambar}}"
                    style="max-width: 500px; max-height: 100px; max-width: 350px;" 
                    onerror="this.onerror=null; this.src='{!! asset('assets/images/no-photos.jpg') !!}'"></td>
        </tr>
        @endforeach
    </tbody>
</table>

</div>


<script>
    
    $(document).ready(function() {
        // Basic
        $('.dropify').dropify();

        // Used events
        var drEvent = $('#input-file-events').dropify();

        drEvent.on('dropify.beforeClear', function(event, element) {
            return confirm("Do you really want to delete \"" + element.file.name + "\" ?");
        });

        drEvent.on('dropify.afterClear', function(event, element) {
            alert('File deleted');
        });

        drEvent.on('dropify.errors', function(event, element) {
            console.log('Has Errors');
        });

        var drDestroy = $('#input-file-to-destroy').dropify();
        drDestroy = drDestroy.data('dropify')
        $('#toggleDropify').on('click', function(e) {
            e.preventDefault();
            if (drDestroy.isDropified()) {
                drDestroy.destroy();
            } else {
                drDestroy.init();
            }
        })
    });
</script>

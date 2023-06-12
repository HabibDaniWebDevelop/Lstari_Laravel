<div class="row">
    <div class="col-md-12">
        <div class="row">
            <div class="col-md-8">
                <form id="form2" method="POST">
                    <div class="table-responsive text-nowrap" style="height:calc(100vh - 510px); margin-top:15px;">
                        <table class="table table-border table-hover table-sm" id="tabwo" width="100%" border="0">
                            <thead class="table-secondary sticky-top zindex-2">
                                <tr>
                                    <th width="10%" style="font-weight:bold; font-size:16px; text-align:center;">SPK Patri</th>
                                    <th width="10%" style="font-weight:bold; font-size:16px; text-align:center;">Tanggal</th>
                                    <th width="10%" style="font-weight:bold; font-size:16px; text-align:center;">User</th>
                                    <th width="10%" style="font-weight:bold; font-size:16px; text-align:center;">Kode Produk</th>
                                    <th width="15%" style="font-weight:bold; font-size:16px; text-align:center;">Deskripsi</th>
                                    <th width="10%" style="font-weight:bold; font-size:16px; text-align:center;">Kadar</th>
                                    <th width="10%" style="font-weight:bold; font-size:16px; text-align:center;">Qty</th>
                                    <th width="10%" style="font-weight:bold; font-size:16px; text-align:center;">#</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($komponenlist as $dataOK)
                                    <tr>
                                        <td width="10%" style="text-align:center; color:black;">{{ $dataOK->WO }}</td>
                                        <td width="10%" style="text-align:center; color:black;">{{ $dataOK->tglWO }}</td>
                                        <td width="10%" style="text-align:center; color:black;">{{ $dataOK->UserName }}</td>
                                        <td width="10%" style="text-align:center;"><span class="badge bg-dark" style="font-size:14px;">{{ $dataOK->SW }}</span></td>
                                        <td width="15%" style="text-align:center; color:black;">{{ $dataOK->Description }}</td>
                                        <td width="10%" style="text-align:center; color:black;">{{ $dataOK->Kadar }}</td>
                                        <td width="10%" style="text-align:center; color:black;">{{ $dataOK->Qty }}</td>
                                        <td width="10%" style="text-align:center;">
                                            <input type="hidden" id="idkadar{{ $loop->iteration }}" name="idkadar[]" value="{{ $dataOK->IDKadar }}">
                                            <input type="hidden" id="swo{{ $loop->iteration }}" name="swo[]" value="{{ $dataOK->LWO }}">
                                            <input type="hidden" id="idwo{{ $loop->iteration }}" name="idwo[]" value="{{ $dataOK->IDWO }}">
                                            <input type="hidden" id="employee{{ $loop->iteration }}" name="employee[]" value="149">
                                            <input type="hidden" id="locgt{{ $loop->iteration }}" name="locgt[]" value="17">
                                            <input type="hidden" id="swworkorder{{ $loop->iteration }}" name="locgt[]" value="{{ $dataOK->WO }}">
                                            <button type="button" class="btn btn-primary" id="btnmaterial{{ $loop->iteration }}" onclick="scanmaterial({{ $loop->iteration }})">Proses</button>
                                        </td>
                                    </tr>    
                                @empty
                                    <div class="alert alert-danger">
                                        Data Belum Tersedia.
                                    </div>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </form>
            </div>
            <div class="col-md-4">
                <input type="text" class="form-control" id="ketwo" readonly>
                <input type="hidden" name="workorder" id="workorder" value="">
                <input type="hidden" name="idcarat" id="idcarat" value="">
                <input type="hidden" name="labelspk" id="labelspk" value="">
                <div id="material" style="display:none;">
                </div>
            </div>
        </div>
    </div>
</div>


<script>
</script>
    
    
<br>
<div class="table-responsive text-nowrap" style="height:calc(100vh - 100px);">
    <form id="form1">
        <table class="table table-border table-hover table-sm" id="tabel1">
            <thead class="table-secondary sticky-top zindex-2" style="center">
                <tr style="text-align: center">
                    <th>PILL</th>
                    <th>SPK.PPIC</th>
                    <th>Komponen</th>
                    <th width="10%">Qty</th>
                    <th>SC</th>
                    <th>Kadar</th>

                </tr>
            </thead>

            {{-- {{ dd($DaftarProduct); }} --}}
            <tbody>

                @forelse ($datas as $data)
                <tr id="baris" style="text-align: center">
                    <td>{{ $loop->iteration }} </td>
                    <td> <span class="badge bg-dark" style="font-size:14px;">{{ $data->SWWorkOrder }}</span>
                        <input type="hidden" name="WorkOr[]" class="WorkOrder form-control form-control-sm fs-6 w-100"
                            id="WorkOrder{{ $loop->iteration }}" value="{{ $data->WorkOrder }}">
                    </td>
                    <td> <span class="badge bg-primary" style="font-size:14px;">{{ $data->SW }}</span> <br>
                        {{ $data->Description }}
                        <input type="hidden" name="Product[]" class="Product form-control form-control-sm fs-6 w-100 "
                            id="Product{{ $loop->iteration }}" value="{{ $data->Product }}">
                    </td>
                    <td class="m-0 p-0">
                        <input style="text-align: center" type="text" class="Qty form-control form-control-lg fs-6 w-5"
                            name="Qty[]" id="Qty{{ $loop->iteration }}" value="{{ $data->Qty}}">
                    </td>
                    <td class="m-0 p-0">
                        <input style="text-align: center" type="text"
                            class="Sc form-control form-control-lg fs-6 w-100 " name="Sc[]"
                            id="Sc{{ $loop->iteration }}" style="font-weight: bold !important;" value=""
                            placeholder="Harap diisi">
                    </td>
                    <td><span class="badge"
                            style="font-size:14px; background-color: {{$data->HexColor}}">{{$data->Kadar}}
                        </span>
                        <input type="hidden" name="Kadar[]" class="Kadar form-control form-control-sm fs-6 w-100 "
                            id="Kadar{{ $loop->iteration }}" value="{{$data->Kadar }}">
                    </td>

                    <td hidden><input type="hidden" class="WorkOrderOrd form-control form-control-sm fs-6 w-100"
                            name="WorkOrderOrd[]" id="WorkOrderOrd{{ $loop->iteration }}"
                            value="{{ $data->WorkOrderOrd }}">
                    </td>
                    <td hidden><input type="hidden" class="WaxOrder form-control form-control-sm fs-6 w-100"
                            name="WaxOrder[]" id="WaxOrder{{ $loop->iteration }}" value="{{ $data->WaxOrder }}">
                    </td>
                    <td hidden><input type="hidden" class="WaxOrderOrd form-control form-control-sm fs-6 w-100"
                            name="WaxOrderOrd[]" id="WaxOrderOrd{{ $loop->iteration }}"
                            value="{{ $data->WaxOrderOrd }}">
                    </td>
                    <td hidden><input type="hidden" class="Tfdc form-control form-control-sm fs-6 w-100" name="Tfdc[]"
                            id="Tfdc{{ $loop->iteration }}" value="{{ $data->TransferResinDC }}">
                    </td>
                    <td hidden><input type="hidden" class="Tfdcor form-control form-control-sm fs-6 w-100"
                            name="Tfdcor[]" id="Tfdcor{{ $loop->iteration }}" value="{{ $data->TransferResinDCOrd }}">
                    </td>
                    <td hidden><input type="hidden" class="idworklist form-control form-control-sm fs-6 w-100"
                            name="idworklist[]" id="idworklist{{ $loop->iteration }}" value="{{ $data->idworklist }}">
                    </td>
                </tr>
                @empty
                <div class="alert alert-danger">
                    Data Blog belum Tersedia.
                </div>
                @endforelse
            </tbody>
            <tfoot>

            </tfoot>
        </table>
    </form>
</div>

<div class="d-grid gap-2 d-md-flex justify-content-md-end px-4">

</div>
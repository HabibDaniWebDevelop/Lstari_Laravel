<br>
<div class="tab-pane fade active show" id="tabelItem" role="tabpanel">
    <div class="table-responsive text-nowrap rounded-4" style="height:calc(100vh - 490px);">
        <table class="table table-border table-hover table-sm rounded-4" id="tabel1">
            <thead class="table-secondary sticky-top zindex-2 rounded-4">
                <tr style="text-align: center">
                    <th>No</th>
                    <th>NO SPK</th>
                    <th>Produk</th>
                    <th width="5%">Qty</th>
                    <!-- <th width="5%">Inject</th>
                    <th width="10%">Tok</th>
                    <th width="10%">SC</th> -->
                    <th>Kaitan</th>
                    <th>Urut</th>
                </tr>
            </thead>
            <tfoot>

            </tfoot>
            {{-- {{ dd($query); }} --}}
            <tbody>
                @forelse ($tambahitemSPKPohonan as $item)
                <tr id="baris" style="text-align: center">
                    <td>{{ $loop->iteration }} </td>
                    <td> <span class="badge bg-dark" style="font-size:14px;">{{ $item->WorkOrder }}</span>
                        <input type="hidden" name="workorder[]"
                            class="WorkOrder form-control form-control-sm fs-6 w-100"
                            id="WorkOrder{{ $loop->iteration }}" value="{{ $item->WorkOrder }}">
                    </td>
                    <td> <span class="badge bg-primary" style="font-size:14px;">{{ $item->Product }}</span> <br>
                        {{ $item->Description }}
                        <input type="hidden" name="Product[]" class="Product form-control form-control-sm fs-6 w-100 "
                            id="Product{{ $loop->iteration }}" value="{{ $item->IDprod }}">
                    </td>
                    <td class="m-0 p-0">
                        <input style="text-align: center" type="text" class="Qty form-control form-control-sm fs-6 w-20"
                            name="Qty[]" id="Qty{{ $loop->iteration }}" onkeyup="jumlahqty(this.value)"
                            value="{{ $item->Qty}}">
                    </td>
                    <!-- <td class="m-0 p-0">
                        <input style="text-align: center" type="text" name="Inject[]"
                            class="Inject form-control form-control-sm fs-6 w-100" id="Inject{{ $loop->iteration }}"
                            value="{{ $item->Inject}}">
                    </td>
                    <td class="m-0 p-0">
                        <input style="text-align: center" type="text"
                            class="Tok form-control form-control-sm fs-6 w-100" name="Tok[]"
                            id="Tok{{ $loop->iteration }}" value="">
                    </td>
                    <td class="m-0 p-0">
                        <input style="text-align: center" type="text" class="Sc form-control form-control-sm fs-6 w-100"
                            name="Sc[]" id="Sc{{ $loop->iteration }}" value="" placeholder="Harap diisi">
                    </td> -->
                    <td>{{ $item->waxorder }}
                        <input type="hidden" name="waxorder[]" class="waxorder form-control form-control-sm fs-6 w-100 "
                            id="waxorder{{ $loop->iteration }}" value="{{ $item->waxorder }}">
                    </td>
                    <td>{{ $item->waxorderord }} <input type="hidden"
                            class="waxorderord form-control form-control-sm fs-6 w-100" name="waxorderord[]"
                            id="waxorderord{{ $loop->iteration }}" value="{{ $item->waxorderord }}">
                    </td>
                    <td hidden><input type="hidden" class="Rph form-control form-control-sm fs-6 w-100" name="Rph[]"
                            id="Rph{{ $loop->iteration }}" value="{{ $item->Rph }}">
                    </td>
                    <td hidden><input type="hidden" class="Ordinal form-control form-control-sm fs-6 w-100"
                            name="Ordinal[]" id="Ordinal{{ $loop->iteration }}" value="{{ $item->Ordinal }}">
                    </td>
                    <td hidden><input type="hidden" class="IDWorkOrder form-control form-control-sm fs-6 w-100"
                            name="IDWorkOrder[]" id="IDWorkOrder{{ $loop->iteration }}"
                            value="{{ $item->IDWorkOrder }}">
                    </td>
                </tr>
                @empty
                <div class="alert alert-danger">
                    Data Blog belum Tersedia.
                </div>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
<div class="card mx-2">
    <ul class="nav nav-pills btn-group" role="group">
        <li class="nav-item col-4 mx-auto">
            <button type="radio" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#tabelItem"
                aria-controls="tabelItem" aria-selected="true"> Item
            </button>
        </li>
        <li class="nav-item col-4 mx-auto">
            <button type="radio" class="nav-link " role="tab" data-bs-toggle="tab" data-bs-target="#tabelKaret"
                aria-controls="tabelKaret" aria-selected="false"> karet Pilihan
            </button>
        </li>
        <li class="nav-item col-4 mx-auto">
            <button type="radio" class="nav-link active" role="tab" data-bs-toggle="tab" onclick="OrderitemDc()"
                aria-selected="false"> Item Direct Casting </button>
        </li>
    </ul>
</div>


<div class="tab-content">
    {{-- tab1 --}}
    <div class="tab-pane fade active show" id="tabelItem" role="tabpanel">
        <div class="table-responsive text-nowrap rounded-4" style="height:calc(70vh);">
            <table class="table table-border table-hover table-sm rounded-4" id="tabel1">
                <thead class="table-secondary sticky-top zindex-2 rounded-4">
                    <tr style="text-align: center">
                        <th>No</th>
                        <th>NO SPK PPIC</th>
                        <th>Produk</th>
                        <th width="8%">Qty</th>
                        <th width="8%">Inject</th>
                        <th width="10%">Tok</th>
                        <th width="10%">SC</th>
                        <th>Rekap Lilin (RL)</th>
                        <th>Urutan RL</th>
                    </tr>
                </thead>
                <tfoot>

                </tfoot>
                {{-- {{ dd($query); }} --}}
                <tbody>
                    @forelse ($tambahdataitem as $item)
                    <tr id="baris" style="text-align: center">
                        <td>{{ $loop->iteration }} </td>
                        <td> <span class="badge bg-dark" style="font-size:14px;">{{ $item->WorkOrder }}</span>
                            <input type="hidden" name="workorder[]"
                                class="WorkOrder form-control form-control-sm fs-6 w-100"
                                id="WorkOrder{{ $loop->iteration }}" value="{{ $item->WorkOrder }}">
                        </td>
                        <td> <span class="badge bg-primary" style="font-size:14px;">{{ $item->Product }}</span> <br>
                            <span class="{{$item->dcinfo}}">{{ $item->Description }}</span>
                            <input type="hidden" name="Product[]"
                                class="Product form-control form-control-sm fs-6 w-100 "
                                id="Product{{ $loop->iteration }}" value="{{ $item->IDprod }}">
                        </td>
                        <td class="m-0 p-0">
                            <input style="text-align: center" type="text"
                                class="Qty form-control form-control-lg fs-6 w-20" name="Qty[]"
                                id="Qty{{ $loop->iteration }}" value="{{ $item->Qty}}">
                        </td>
                        <td class="m-0 p-0">
                            <input style="text-align: center" type="text" name="Inject[]"
                                class="Inject form-control form-control-lg fs-6 w-100" id="Inject{{ $loop->iteration }}"
                                style="font-size:20px;" value="{{ $item->Inject}}">
                        </td>
                        <td class="m-0 p-0">
                            <input style="text-align: center" type="text"
                                class="Tok form-control form-control-lg fs-6 w-100" name="Tok[]"
                                id="Tok{{ $loop->iteration }}" value="">
                        </td>
                        <td class="m-0 p-0">
                            <input style="text-align: center" type="text"
                                class="Sc form-control form-control-lg fs-6 w-100 " name="Sc[]"
                                id="Sc{{ $loop->iteration }}" style="font-weight: bold !important;" value=""
                                placeholder="Harap diisi">
                        </td>
                        <td>{{ $item->waxorder }}
                            <input type="hidden" name="waxorder[]"
                                class="waxorder form-control form-control-sm fs-6 w-100 "
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
    {{-- End tab1--}}

    {{-- Panel tab2 --}}
    <div class="tab-pane fade" id="tabelKaret" role="tabpanel">
        <div class="table-responsive text-nowrap rounded-4" style="height:calc(100vh);">
            <table class="table table-border table-hover table-sm rounded-4" id="tabel6">
                <thead class="table-secondary sticky-top zindex-2 rounded-4">
                    <tr>
                        <th>PILL</th>

                        <th>ID Karet</th>
                        <th>Model</th>
                        <th>Pcs</th>
                        <th>Kadar</th>
                        <th>Ukuran</th>
                        <th>Digunakan</th>
                        <th>Hasil OK</th>
                        <th>Hasil SS</th>
                        <th>Tanggal buat</th>
                        <th>Status</th>

                        <th>SC</th>
                        <th>Lokasi</th>
                        <th>Activ</th>
                        <th>SPKO Inject</th>
                    </tr>
                </thead>
                <tfoot>

                </tfoot>

                <tbody>
                    @forelse ($tambahdatakaret as $tkp)
                    <tr class="klik2" id="{{ $tkp->ID }}">
                        <td>
                            <input class="form-check-input karet" type="checkbox" name="id[]" id="cek_{{ $tkp->ID }}"
                                value="{{  $tkp->ID }}" data-lokasi="{{$tkp->lokasi}}" disabled>
                        </td>
                        <td> <span class="badge bg-dark" style="font-size:14px;">{{ $tkp->ID }}</span>
                        </td>
                        <td> <span class="badge bg-primary" style="font-size:14px;">{{ $tkp->Product }}</span></td>
                        <td>{{ $tkp->Pcs }}</td>
                        <td><span class="badge"
                                style="font-size:14px; background-color: {{$tkp->HexColor}}">{{$tkp->Kadar}} </span>
                        </td>
                        <td>{{ $tkp->Size }}</td>
                        <td>{{ $tkp->WaxUsage }}</td>
                        <td>{{ $tkp->WaxCompletion }}</td>
                        <td>{{ $tkp->WaxScrap }}</td>
                        <td>{{ $tkp->TransDate }}</td>
                        <td>{{ $tkp->STATUS }}</td>

                        <td>{{ $tkp->StoneCast }}</td>
                        <td>{{ $tkp->lokasi }}</td>
                        <td>{{ $tkp->Active }}</td>
                        <td>{{ $tkp->WaxInjectOrder }}</td>
                    </tr>
                    @empty
                    <div class="alert alert-danger">
                        Data Blog belum Tersedia.
                    </div>
                    @endforelse
                </tbody>
            </table>
        </div>

        <script>
        $(".klik2").on('click', function(e) {
            // $('.klik').css('background-color', 'white');
            var id = $(this).attr('id');
            if ($(this).hasClass('table-primary')) {
                $(this).removeClass('table-primary');
                $('#cek_' + id).attr('checked', false);
            } else {
                $(this).addClass('table-primary');
                $('#cek_' + id).attr('checked', true);
            }
            return false;
        });
        </script>
    </div>
    {{-- End Panel tab2 --}}
    <!-- modal daftar product -->
</div>
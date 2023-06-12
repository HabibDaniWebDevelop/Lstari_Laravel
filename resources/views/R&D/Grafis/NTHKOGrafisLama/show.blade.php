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
            <input type="text" class="form-control form-control-sm fs-6" readonly name="SW" value="{{ $data1[0]->SW }}">
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
                value="{{ $data1[0]->Weight }}">
        </div>
    </div>

    <div class="col-md-6">
    </div>

    <div class="col-md-2">
        <div class="form-group">
            <label class="form-label">Scan Barcode Produk</label>
            <input type="text" class="form-control form-control-sm fs-6" id="cari2" value=""
                onchange="ChangeCari2(this.value)" >
        </div>
    </div>
</div>

<input type="hidden" name="count" id="count" value="{{ $count }}">
<input type="hidden" name="idworkallocation" id="idworkallocation" value="{{ $data1[0]->ID }}">
<input type="hidden" id="postingstatus" value="{{ $status }}">
<input type="hidden" id="selscale">

<hr>

<div class="table-responsive">

    <table class="table table-bordered table-sm" id="tabel1">
        <tbody>
            @php
                $i = 0;
            @endphp
            @foreach ($getheaderwaxtree as $item)

            @php
                $item->gambar = str_replace(".jpg", "", $item->gambar).".jpg";
            @endphp
                <tr>
                    <td width='25%' style="padding: 0px;">
                        <table width="100%" class="tabel2">
                            <tbody height="322">
                                <tr>
                                    <td height="34" class="p-1"
                                        style="max-width: 0px; text-overflow: ellipsis !important; overflow: hidden;">
                                        Kode Produk : {{ $item->SKU }}
                                    </td>
                                </tr>
                                <tr>
                                    <td align="center">
                                        <img class="text-center"
                                            src="{{ Session::get('hostfoto') }}/image/{{ $item->gambar }}"
                                            class="img-fluid" style="max-height: 180px; max-width: 200px;"
                                            onerror="this.onerror=null; this.src='{!! asset('assets/images/no-photos.jpg') !!}'">
                                    </td>
                                </tr>
                                <tr>
                                    <td align="center" height="64" class="p-1">
                                        <b style="font-size: 35px;">Nomor : {{ $loop->iteration }}</b>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                    <td width='25%' style="padding: 0px;">
                        <table width="100%" class="tabel2">
                            <tbody>
                                <tr>
                                    <td width="40%" align="center">Berat Hasil Grafis</td>
                                    <td width="60%" align="center">
                                        <input type="text" class="form-control form-control-sm fs-6 w-100"
                                            @if (isset($Ordinal[$item->LinkID][1])) style="background-color: #FCF3CF;" id="{{ $timbang[$item->LinkID][1] }}"
                                            name="brthasilgrf[{{ $ids[$item->LinkID][1] }}]" readonly value="{{ $berat[$ids[$item->LinkID][1]] }}"
                                            onchange="hasilgrafis()" onfocus="kliktimbang('{{ $timbang[$item->LinkID][1] }}')"
                                            @else
                                            disabled @endif
                                            >
                                    </td>
                                </tr>

                                <tr>
                                    <td colspan="2" height="224px" style="vertical-align:top; overflow: auto;">
                                        @if (isset($Ordinal[$item->LinkID][1]))
                                            @php $i++; @endphp
                                            <input type="file" accept="image/jpeg" id="gambar{{ $i }}" name="gambar{{ $i }}"
                                                class="dropify" data-height="209px" data-default-file="" multiple
                                                data-id="{{ $ids[$item->LinkID][1] }}" />
                                            <input type="hidden" id="SKU{{ $i }}"
                                            value="{{ $SKU[$item->LinkID][1] }}" @else @endif
                                    </td>
                                </tr>

                                <tr style="text-align: center;">
                                    <td>Kondisi</td>
                                    <td width="60%">
                                        @if (isset($Ordinal[$item->LinkID][1]))
                                            <select class="form-select form-select-sm fs-6 w-100" name="kondisi[{{ $ids[$item->LinkID][1] }}]">
                                                <option value="OK">OK</option>
                                                <option value="Keropos">Keropos</option>
                                                <option value="Reparasi">Reparasi</option>
                                                <option value="Rusak">Rusak</option>
                                            </select>
                                        @else
                                            <input type="text" class="form-control form-control-sm fs-tiny w-100"
                                                style="height: 31px" disabled>
                                        @endif

                                    </td>
                                </tr>
                                <tr width="40%" style="text-align: center;">
                                    <td>Proses Selanjutnya</td>
                                    <td width="60%">

                                        @if (isset($Ordinal[$item->LinkID][1]))
                                            <select class="form-select form-select-sm fs-6 w-100" id="next1"
                                                name="next[{{ $ids[$item->LinkID][1] }}]">
                                                <option value="753">Barang Siap QC</option>
                                                <option value="256">Selesai Cor</option>
                                                <option value="254">Barang Siap Reparasi</option>
                                                <option value="98">Barang Siap Rep SC</option>
                                            </select>
                                        @else
                                            <input type="text" class="form-control form-control-sm fs-tiny w-100"
                                                style="height: 31px" disabled>
                                        @endif

                                    </td>
                                </tr>


                            </tbody>
                        </table>
                    </td>
                    <td width='25%' style="padding: 0px;">
                        <table width="100%" class="tabel2">
                            <tbody>
                                <tr>
                                    <td width="40%" align="center">Berat Hasil Grafis</td>
                                    <td width="60%" align="center">
                                        <input type="text" class="form-control form-control-sm fs-6 w-100"
                                            @if (isset($Ordinal[$item->LinkID][2])) style="background-color: #FCF3CF;" id="{{ $timbang[$item->LinkID][2] }}"
                                            name="brthasilgrf[{{ $ids[$item->LinkID][2] }}]" readonly value="{{ $berat[$ids[$item->LinkID][2]] }}"
                                            onchange="hasilgrafis()" onfocus="kliktimbang('{{ $timbang[$item->LinkID][2] }}')"
                                        @else
                                            disabled @endif
                                            >
                                    </td>
                                </tr>

                                <tr>
                                    <td colspan="2" height="224px" style="vertical-align:top; overflow: auto;">
                                        @if (isset($Ordinal[$item->LinkID][2]))
                                            @php $i++; @endphp
                                            <input type="file" accept="image/jpeg" id="gambar{{ $i }}" name="gambar{{ $i }}"
                                                class="dropify" data-height="209px" data-default-file="" multiple
                                                data-id="{{ $ids[$item->LinkID][2] }}" />
                                            <input type="hidden" id="SKU{{ $i }}"
                                            value="{{ $SKU[$item->LinkID][2] }}" @else @endif
                                    </td>
                                </tr>


                                <tr style="text-align: center;">
                                    <td>Kondisi</td>
                                    <td width="60%">
                                        @if (isset($Ordinal[$item->LinkID][2]))
                                            <select class="form-select form-select-sm fs-6 w-100" name="kondisi[{{ $ids[$item->LinkID][2] }}]">
                                                <option value="OK">OK</option>
                                                <option value="Keropos">Keropos</option>
                                                <option value="Reparasi">Reparasi</option>
                                                <option value="Rusak">Rusak</option>
                                            </select>
                                        @else
                                            <input type="text" class="form-control form-control-sm fs-tiny w-100"
                                                style="height: 31px" disabled>
                                        @endif

                                    </td>
                                </tr>
                                <tr width="40%" style="text-align: center;">
                                    <td>Proses Selanjutnya</td>
                                    <td width="60%">

                                        @if (isset($Ordinal[$item->LinkID][2]))
                                            <select class="form-select form-select-sm fs-6 w-100" id="next1"
                                                name="next[{{ $ids[$item->LinkID][2] }}]">
                                                <option value="753">Barang Siap QC</option>
                                                <option value="256">Selesai Cor</option>
                                                <option value="254">Barang Siap Reparasi</option>
                                                <option value="98">Barang Siap Rep SC</option>
                                            </select>
                                        @else
                                            <input type="text" class="form-control form-control-sm fs-tiny w-100"
                                                style="height: 31px" disabled>
                                        @endif

                                    </td>
                                </tr>


                            </tbody>
                        </table>
                    </td>
                    <td width='25%' style="padding: 0px;">
                        <table width="100%" class="tabel2">
                            <tbody>
                                <tr>
                                    <td width="40%" align="center">Berat Hasil Grafis</td>
                                    <td width="60%" align="center">
                                        <input type="text" class="form-control form-control-sm fs-6 w-100"
                                            @if (isset($Ordinal[$item->LinkID][3])) style="background-color: #FCF3CF;" id="{{ $timbang[$item->LinkID][3] }}"
                                            name="brthasilgrf[{{ $ids[$item->LinkID][3] }}]" readonly value="{{ $berat[$ids[$item->LinkID][3]] }}"
                                            onchange="hasilgrafis()" onfocus="kliktimbang('{{ $timbang[$item->LinkID][3] }}')"
                                        @else
                                            disabled @endif>
                                    </td>
                                </tr>

                                <tr>
                                    <td colspan="2" height="224px" style="vertical-align:top; overflow: auto;">
                                        @if (isset($Ordinal[$item->LinkID][3]))
                                            @php $i++; @endphp
                                            <input type="file" accept="image/jpeg" id="gambar{{ $i }}" name="gambar{{ $i }}"
                                                class="dropify" data-height="209px" data-default-file="" multiple
                                                data-id="{{ $ids[$item->LinkID][3] }}" />
                                            <input type="hidden" id="SKU{{ $i }}"
                                            value="{{ $SKU[$item->LinkID][3] }}" @else @endif
                                    </td>
                                </tr>


                                <tr style="text-align: center;">
                                    <td>Kondisi</td>
                                    <td width="60%">
                                        @if (isset($Ordinal[$item->LinkID][3]))
                                            <select class="form-select form-select-sm fs-6 w-100" name="kondisi[{{ $ids[$item->LinkID][3] }}]">
                                                <option value="OK">OK</option>
                                                <option value="Keropos">Keropos</option>
                                                <option value="Reparasi">Reparasi</option>
                                                <option value="Rusak">Rusak</option>
                                            </select>
                                        @else
                                            <input type="text" class="form-control form-control-sm fs-tiny w-100"
                                                style="height: 31px" disabled>
                                        @endif

                                    </td>
                                </tr>
                                <tr width="40%" style="text-align: center;">
                                    <td>Proses Selanjutnya</td>
                                    <td width="60%">

                                        @if (isset($Ordinal[$item->LinkID][3]))
                                            <select class="form-select form-select-sm fs-6 w-100" id="next1"
                                                name="next[{{ $ids[$item->LinkID][3] }}]">
                                                <option value="753">Barang Siap QC</option>
                                                <option value="256">Selesai Cor</option>
                                                <option value="254">Barang Siap Reparasi</option>
                                                <option value="98">Barang Siap Rep SC</option>
                                            </select>
                                        @else
                                            <input type="text" class="form-control form-control-sm fs-tiny w-100"
                                                style="height: 31px" disabled>
                                        @endif

                                    </td>
                                </tr>

                            </tbody>
                        </table>
                    </td>

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

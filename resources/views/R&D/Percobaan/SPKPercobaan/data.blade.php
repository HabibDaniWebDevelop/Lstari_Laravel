<h5 class="card-header">SPK Percobaan</h5>
<div class="card-body">
    <div class="row">
        <div class="col-8">
            <button type="button" class="btn btn-primary" id="btn_baru" onclick="KlikBaru()"> <span class="tf-icons bx bx-plus-circle"></span>&nbsp; Baru </button>
            <button type="button" class="btn btn-primary me-4" disabled="" id="btn_ubah" disabled="" onclick="KlikUbah()"><span class="tf-icons bx bx-edit"></span>&nbsp; Ubah</button>
            <button type="button" class="btn btn-danger" disabled="" id="btn_batal" onclick="klikBatal()"> <span class="fas fa-times-circle"></span>&nbsp; Batal</button>
            <button type="button" class="btn btn-warning" disabled="" id="btn_simpan" onclick="KlikSimpan()"><span class="tf-icons bx bx-save"></span>&nbsp; Simpan</button>
            <button type="button" class="btn btn-info" id="btn_cetak" onclick="klikCetak()" disabled=""> <span class="tf-icons bx bx-printer"></span>&nbsp; Cetak</button>
            <input type="hidden" id="payrollID" value="" type="number">
            <input type="hidden" id="action" value="simpan" type="text">
        </div>
        <div class="col-4">
            <table class="table table-border table-sm" width="100%">
                <thead class="table-secondary sticky-top zindex-2">
                    <tr style="text-align: center">
                      <th colspan="6">Pilih Kadar</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $no = 1;
                    @endphp
                    @forelse ($carats as $carat)
                        @if($no === 1)
                        @php
                            $no++;
                        @endphp
                        <tr>
                            <td> 
                                <input type="checkbox" class="chk-col-black" name="idKadar[]" id="{{ $carat->SKU }}" style="width: 100%; box-sizing: border-box;" value="{{ $carat->ID }}" onchange="getIdKadar(this.value)">
                            </td>
                            <td>
                                <label for="{{$carat->SKU}}">{{$carat->SKU}}{{$carat->Alloy}}</label>
                            </td>
                        @elseif($no === 2)
                        @php
                            $no++;
                        @endphp
                            <td> 
                                <input type="checkbox" class="chk-col-black" name="idKadar[]" id="{{ $carat->SKU }}" style="width: 100%; box-sizing: border-box;" value="{{ $carat->ID }}" onchange="getIdKadar(this.value)">
                            </td>
                            <td>
                                <label for="{{$carat->SKU}}">{{$carat->SKU}}{{$carat->Alloy}}</label>
                            </td>
                        @else
                        @php
                            $no=1;
                        @endphp
                            <td> 
                                <input type="checkbox" class="chk-col-black" name="idKadar[]" id="{{ $carat->SKU }}" style="width: 100%; box-sizing: border-box;" value="{{ $carat->ID }}" onchange="getIdKadar(this.value)">
                            </td>
                            <td>
                                <label for="{{$carat->SKU}}">{{$carat->SKU}}{{$carat->Alloy}}</label>
                            </td>                    
                        </tr>
                        @endif
                    @empty
                        <tr>
                            <td colspan="3" align="center">No Data</td>
                        </tr>
                    @endforelse            
                </tbody>
            </table>
        </div>
    </div>
</div>
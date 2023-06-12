<table class="table table-sm table-bordered" id="theTable" @isset($border) border="1" @endisset>
  <thead>
      <tr>
          <th rowspan="2">No</th>
          <th colspan="9">Nota Terima Hasil Kerja Tukang Luar</th>
          <th colspan="7">Laboratorium</th>
      </tr>
      <tr>
          <th>No. Setor</th>
          <th>Tanggal</th>
          <th>Tukang</th>
          <th>No. Model</th>
          <th>Nama Barang</th>
          <th>SPK</th>
          <th>Jenis</th>
          <th>Qty</th>
          <th>Weight</th>
          <th>Tanggal</th>
          <th>Hasil Lab</th>
          <th>Toleransi</th>
          <th>Berat</th>
          <th>Selisih Berat</th>
          <th>Catatan</th>
          <th>Option</th>
      </tr>
  </thead>
  <tbody>
      @foreach ($notaTukangLuar as $item)
      <tr>
          <td>{{$loop->iteration}}</td>
          <td>@isset($item['SWSPKO']){{$item['SWSPKO']}} -@endisset @isset($item['Freq']){{$item['Freq']}}@endisset</td>
          <td>@isset($item['TanggalNTHKO']){{$item['TanggalNTHKO']}}@endisset</td>
          <td>@isset($item['Tukang']){{$item['Tukang']}}@endisset</td>
          <td>@isset($item['NamaProductFGNTHKO']){{$item['NamaProductFGNTHKO']}}@endisset</td>
          <td>@isset($item['NamaProductNTHKO']){{$item['NamaProductNTHKO']}}@endisset</td>
          <td>@isset($item['NomorSPKNTHKO']){{$item['NomorSPKNTHKO']}}@endisset</td>
          <td>@isset($item['CategoryProductFGNTHKO']){{$item['CategoryProductFGNTHKO']}}@endisset</td>
          <td>@isset($item['QtyNTHKO']){{$item['QtyNTHKO']}}@endisset</td>
          <td>@isset($item['WeightNTHKO']){{$item['WeightNTHKO']}}@endisset</td>
          <td>@isset($item['TanggalLab']){{$item['TanggalLab']}}@endisset</td>
          <td>@isset($item['HasilLab']){{$item['HasilLab']}}@endisset</td>
          <td>@isset($item['Toleransi']){{$item['Toleransi']}}@endisset</td>
          <td>@isset($item['WeightLab']){{$item['WeightLab']}}@endisset</td>
          <td>@isset($item['SelisihBerat']){{$item['SelisihBerat']}}@endisset</td>
          <td>@isset($item['Catatan']){{$item['Catatan']}}@endisset</td>
          <td><button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalKu_{{$loop->iteration}}">Edit</button></td>
      </tr>
      @endforeach
      <tr>
          <td colspan="16" style="text-align: right;">Total:</td>
          <td>@isset($total_selisih_berat){{$total_selisih_berat}}@endisset</td>
      </tr>
  </tbody>
</table>
@foreach ($notaTukangLuar as $item)
<div class="modal fade" id="modalKu_{{$loop->iteration}}" tabindex="-1" aria-modal="true" role="dialog">
  <div class="modal-dialog" role="document">
      <div class="modal-content">
          <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLabel1">Catatan Turun Kadar</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
              <div class="row g-2">
                  <div class="col mb-0">
                      <label for="noSetor_{{$loop->iteration}}" class="form-label">No. Setor</label>
                      <input type="text" id="noSetor_{{$loop->iteration}}" class="form-control" value="@isset($item['SWSPKO']){{$item['SWSPKO']}} -@endisset @isset($item['Freq']){{$item['Freq']}}@endisset" disabled>
                  </div>
                  <div class="col mb-0">
                      <label for="tanggalLab_{{$loop->iteration}}" class="form-label">Tanggal</label>
                      <input type="date" id="tanggalLab_{{$loop->iteration}}" class="form-control" value="@isset($item['TanggalLab']){{$item['TanggalLab']}}@endisset">
                  </div>
              </div>
              <div class="row g-2">
                  <div class="col mb-0">
                      <label for="noSPK_{{$loop->iteration}}" class="form-label">SPK</label>
                      <input type="text" id="noSPK_{{$loop->iteration}}" class="form-control" value="@isset($item['NomorSPKNTHKO']){{$item['NomorSPKNTHKO']}}@endisset" disabled>
                  </div>
                  <div class="col mb-0">
                      <label for="carat_{{$loop->iteration}}" class="form-label">Kadar Hasil Lab</label>
                      <input type="text" id="carat_{{$loop->iteration}}" class="form-control" value="@isset($item['HasilLab']){{$item['HasilLab']}}@endisset" onchange="calculateWeighDiff({{$loop->iteration}})">
                  </div>
              </div>
              <div class="row g-2">
                  <div class="col mb-0">
                      <label for="jenisBarang_{{$loop->iteration}}" class="form-label">Jenis Barang</label>
                      <input type="text" id="jenisBarang_{{$loop->iteration}}" class="form-control" value="@isset($item['CategoryProductFGNTHKO']){{$item['CategoryProductFGNTHKO']}}@endisset" disabled>
                  </div>
                  <div class="col mb-0">
                      <label for="caratTolerance_{{$loop->iteration}}" class="form-label">Kadar Toleransi</label>
                      <input type="text" id="caratTolerance_{{$loop->iteration}}" class="form-control" value="@isset($item['Toleransi']){{$item['Toleransi']}}@endisset" onchange="calculateWeighDiff({{$loop->iteration}})">
                  </div>
              </div>
              <div class="row g-2">
                  <div class="col mb-0">
                      <label for="NamaProductNTHKO_{{$loop->iteration}}" class="form-label">Nama Barang</label>
                      <input type="text" id="NamaProductNTHKO_{{$loop->iteration}}" class="form-control" value="@isset($item['NamaProductNTHKO']){{$item['NamaProductNTHKO']}}@endisset" disabled>
                  </div>
                  <div class="col mb-0">
                      <label for="weightLab_{{$loop->iteration}}" class="form-label">Berat</label>
                      <input type="text" id="weightLab_{{$loop->iteration}}" class="form-control" value="@isset($item['WeightLab']){{$item['WeightLab']}}@endisset" onchange="calculateWeighDiff({{$loop->iteration}})">
                  </div>
              </div>
              <div class="row g-2">
                  <div class="col mb-0">
                      <label for="tukang_{{$loop->iteration}}" class="form-label">Tukang</label>
                      <input type="text" id="tukang_{{$loop->iteration}}" class="form-control" value="@isset($item['Tukang']){{$item['Tukang']}}@endisset" disabled>
                  </div>
                  <div class="col mb-0">
                      <hr>
                      <p>((Hasil Lab - Toleransi) / 100%) x Berat</p>
                      <hr>
                  </div>
              </div>
              <div class="row g-2">
                  <div class="col mb-0">
                      <label for="noModel_{{$loop->iteration}}" class="form-label">No. Model</label>
                      <input type="text" id="noModel_{{$loop->iteration}}" class="form-control" value="@isset($item['NamaProductFGNTHKO']){{$item['NamaProductFGNTHKO']}}@endisset" disabled>
                  </div>
                  <div class="col mb-0">
                      <label for="weightDiff_{{$loop->iteration}}" class="form-label">Selisih Berat</label>
                      <input type="text" id="weightDiff_{{$loop->iteration}}" class="form-control" value="@isset($item['SelisihBerat']){{$item['SelisihBerat']}}@endisset" readonly>
                  </div>
              </div>
              <div class="row g-2">
                  <div class="col mb-0"></div>
                  <div class="col mb-0">
                      <label for="remarks_{{$loop->iteration}}" class="form-label">Catatan</label>
                      <input type="text" id="remarks_{{$loop->iteration}}" class="form-control" value="@isset($item['Catatan']){{$item['Catatan']}}@endisset">
                  </div>
              </div>
          </div>
          <div class="modal-footer">
              <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
              Close
              </button>
              <button type="button" class="btn btn-primary" onclick="updatecjepsi(@isset($item['IDNTHKO']){{$item['IDNTHKO']}}@endisset, @isset($item['OrdinalNTHKO']){{$item['OrdinalNTHKO']}}@endisset, {{$loop->iteration}})">Save changes</button>
          </div>
      </div>
  </div>
</div>
@endforeach
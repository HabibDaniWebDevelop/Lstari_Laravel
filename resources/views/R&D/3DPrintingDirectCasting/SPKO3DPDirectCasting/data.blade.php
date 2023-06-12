{{-- <h5 class="card-header">Manage Hardware</h5> --}}
<div class="card-body">
    <div class="demo-inline-spacing mb-4">
        <button type="button" class="btn btn-primary " id="Baru1" onclick="Klik_Baru1()"> <span
                class="tf-icons bx bx-plus-circle"></span>&nbsp; Baru </button>
        {{-- <button type="button" class="btn btn-primary me-4" id="Ubah1" disabled onclick="Klik_Ubah1()">
            <span class="tf-icons bx bx-edit"></span>&nbsp; Ubah</button> --}}
        <button type="button" class="btn btn-danger" id="Batal1" disabled onclick="Klik_Batal1()"> <span
                class="fas fa-times-circle"></span>&nbsp; Batal</button>
        <button type="button" class="btn btn-warning" id="Simpan1" disabled onclick="Klik_Simpan1()">
            <span class="tf-icons bx bx-save"></span>&nbsp; Simpan</button>
        {{-- <button type="button" class="btn btn-dark me-4" id="Posting1" disabled onclick="Klik_Posting1()">
            <span class="tf-icons bx bx-send"></span>&nbsp; Posting</button> --}}
        <button type="button" class="btn btn-info" id="Cetak1" disabled onclick="Klik_Cetak1()"> <span
                class="tf-icons bx bx-printer"></span>&nbsp; Cetak</button>
        <div class="float-end">
            <div class="input-group input-group-merge">
                <span class="input-group-text"><i class="bx bx-search" onclick="klikViewSelection()"></i></span>
                <input type="text" class="form-control" placeholder="Search..." autofocus id='cari' list="carilist"
                    onchange="ChangeCari(0)" />
            </div>
            <datalist id="carilist">
                @foreach ($carilists as $carilist)
                <option value="{{ $carilist->ID }}">{{ $carilist->SW }}</option>
                @endforeach
            </datalist>
            <input type="hidden" id="idcetak" value="">
        </div>
        <hr class="m-0" />
    </div>
    <form id="form1">
        <div class="row">
            <div class="col-md-6">
                <div class="row">
                    <div class="col-md-6">
                        <label class="control-label" for="id">No. SPKO</label>
                        <input class="form-control" id="sw" type="text" name="sw" value="" disabled="true">
                    </div>
                    <div class="col-md-6">
                        <label class="control-label" for="tgl">Tanggal</label>
                        <input class="form-control" id="tgl" type="date" name="tgl" value="{{ date('Y-m-d'); }}"
                            disabled="true">
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <label class="control-label" for="tgl">Karyawan</label>
                <select class="form-select" id="employee" name="employee" required>
                    @foreach ($employees as $employee)
                    <option value="{{ $employee->ID }}" selected>{{ $employee->Description }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <label class="control-label" for="id">Catatan</label>
                <input class="form-control" id="note" type="text" name="note" value="" disabled="true">
            </div>
        </div>
    </form>
    <div id="tampil" class="d-none">
    </div>
</div>
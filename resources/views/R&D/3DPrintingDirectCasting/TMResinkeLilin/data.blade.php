<div class="card-body" style="height:calc(100vh - 155px);">

    <div class="demo-inline-spacing">

        @if ($menu == '1')
        <button type="button" class="btn btn-primary me-4" id="Baru1" onclick="Klik_Baru1()"> <span
                class="tf-icons bx bx-plus-circle"></span>&nbsp; Baru </button>

        <button type="button" class="btn btn-danger" id="Batal1" disabled onclick="Klik_Batal1()"> <span
                class="fas fa-times-circle"></span>&nbsp; Batal</button>

        <button type="button" class="btn btn-warning me-4" id="Simpan1" disabled onclick="Klik_Simpan1()">
            <span class="tf-icons bx bx-save"></span>&nbsp; Simpan</button>

        <button type="button" class="btn btn-info" id="Cetak1" value="" disabled onclick="Klik_Cetak1()">
            <span class="tf-icons bx bx-printer"></span>&nbsp; Cetak</button>
        @else
        <button type="button" class="btn btn-dark" id="Posting1" disabled onclick="Klik_Posting1()">
            <span class="tf-icons bx bx-send"></span>&nbsp; Posting</button>
        @endif



        <div class="d-flex float-end">

            <div class="position-absolute d-none" id="postinglogo" style="right: 300px; top: 10px; ">
                <img src="{!! asset('assets/images/posting.jpg') !!}"
                    style="width: 250px; object-fit: cover; object-position: top;">
            </div>

            <div class="input-group input-group-merge" style="width: 200px;">
                <span class="input-group-text"><i class="bx bx-search" onclick="klikViewSelection()"></i></span>
                <input class="form-control" list="carilist" autofocus id='cari' onchange="ChangeCari('0')"
                    placeholder="search...">
            </div>
            <datalist class="text-warning" id="carilist">
                @foreach ($carilists as $carilist)
                <option value="{{ $carilist->ID }}">
                    @if ($carilist->Active == 'P')
                    <br><small>Posting</small>
                    @endif
                </option>
                @endforeach
            </datalist>

        </div>
        <hr class="m-0" />

    </div>
    <form id="form1">
        <div id="tampil" class="d-none">

        </div>
    </form>


</div>

@include('IT.DataPC.modal')
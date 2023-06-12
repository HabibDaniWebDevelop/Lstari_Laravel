<div class="card-body " style="min-height:calc(100vh - 255px);">

    <div class="demo-inline-spacing" id="btn-menu">

        <button type="button" class="btn btn-danger me-4" id="Batal1" disabled onclick="Klik_Batal1()"> <span
                class="fas fa-times-circle"></span>&nbsp; Batal</button>
        <button type="button" class="btn btn-primary " id="generate" disabled value="detail" onclick="Klik_generate()">
            <span class="fas fa-qrcode"></span>&nbsp; Generate </button>

    </div>

    <hr class="mx-0 my-3" />
    <form id="form1" autocomplete="off" onsubmit="return false;">
        <div id="tampil">
            <div class="row">

                <div class="col-md-3 mb-2">
                    <div class="form-group">
                        <label class="form-label">Scan Form SPK PCB</label>
                        <input type="text" class="form-control fs-6" id="idnthko" value=""
                            onchange="cari_data()">
                    </div>
                </div>
            </div>
        </div>
    </form>


</div>

<script></script>

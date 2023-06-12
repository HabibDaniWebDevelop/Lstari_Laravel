<div class="card-body" style="height:calc(100vh - 255px);">

    <div class="card-body d-flex justify-content-between p-0">

        <div class="d-flex mb-3">

            <div class="input-group w-px-300 me-4">
                <span class="input-group-text btn-dark disabled">Dari No Order</span>
                <input type="text" class="form-control" id="dari" name="dari">
            </div>

            <div class="input-group w-px-300 me-4">
                <span class="input-group-text btn-dark">Hingga No Order</span>
                <input type="text" class="form-control" id="hingga" name="hingga">
            </div>

            <button type="button" class="btn btn-primary me-4" id="Cari1" onclick="Klik_Cari1()"> Search </button>
        </div>
        <div class="float-end">

                <b >Auto Refresh Dalam <b style="color: green" id="time">1800</b> Detik &ensp;</b>
                <button class="btn btn-warning" onclick="refrsh_page()">Refresh</button>

        </div>
    </div>

    <hr class="m-0 mb-2" />
        <div id="tampil" class="d-none">

        </div>
    @include('Workshop.WorkshopApproval.modal')

</div>



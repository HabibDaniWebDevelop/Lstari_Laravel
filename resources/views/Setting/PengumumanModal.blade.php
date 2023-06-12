<div class="modal fade" id="modal1" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg" id="modalformat" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="jodulmodal1">History</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="formmodal1" autocomplete="off">
                    <div id="modal1">

                        <div class="row g-2">
                            <div class="mb-0 col-md-6">
                                <label class="form-label">Name</label>
                                <div id="todolistname">
                                    <input class="form-control" type="text" name="UserName" id="UserName"
                                       readonly value="{{ Auth::user()->name }}" />
                                </div>
                            </div>
                            <div class="mb-0 col-md-6">
                                <label class="form-label">Trans Date</label>
                                <input class="form-control" type="date" name="TransDate" readonly value="{{ date("Y-m-d") }}" />
                            </div>
                            
                        </div>
                        <div class="row g-2">
                            <div class="mb-2 col-md-6">
                                <label class="form-label">Announce To</label>
                                <div id="AnnounceTopp">
                                    <input class="form-control" type="text" name="AnnounceTo" id="AnnounceTo" value="" />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Valid To Date</label>
                                <input class="form-control" type="date" name="ValidToDate" value="" />
                            </div>
                        </div>
                        <div class="row g-2">
                            <div class="mb-2 col-md-12">
                                <label class="form-label">Note</label>
                                <input class="form-control" type="text" name="Note" value="" />
                            </div>
                        </div>
                    </div>
            </div>
            </form>

            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                    Close
                </button>
                <button type="button" class="btn btn-primary d-none" id="simpan1" value=""
                    onclick="KlikSimpan1()">Save</button>
            </div>
        </div>

    </div>
</div>
</div>

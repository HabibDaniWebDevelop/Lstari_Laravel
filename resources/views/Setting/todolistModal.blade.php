<div class="modal fade" id="modal1" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg" id="modalformat" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="jodulmodal1">History</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="formmodal1">
                    <div id="modal1">

                        <div class="row g-2">
                            <div class="mb-0 col-md-4">
                                <label class="form-label">Nama</label>
                                <div id="todolistname">
                                    <input class="form-control" type="text" name="name" id="name"
                                        value="{{ Auth::user()->name }}" />
                                </div>
                            </div>
                            <div class="mb-0 col-md-4">
                                <label class="form-label">Tanggal</label>
                                <input class="form-control" type="date" name="tododate" id="tododate" value="{{ date("Y-m-d") }}" />
                            </div>
                            <div class="mb-2 col-md-4">
                                <label class="form-label">Target</label>
                                <input class="form-control" type="date" name="TargetDate" id="TargetDate"  value="" />
                            </div>
                        </div>
                        <div class="row g-2">
                            <div class="mb-2 col-md-8">
                                <label class="form-label">Todo</label>
                                <input class="form-control" type="text" name="todo" id="todo" value="" />
                            </div>
                            <div class="mb-2 col-md-4">
                                <label class="form-label">Priority</label>
                                <select class="form-select" id="Priority" name="Priority">
                                    <option value="Biasa">Biasa</option>
                                    <option value="Penting">Penting</option>
                                    <option value="Darurat">Darurat</option>
                                </select>
                                <input class="form-control" type="hidden" name="status" id="status" value="A" />
                            </div>
                        </div>
                        <div class="row g-2">
                            <div class="col-md-12">
                                <label class="form-label">Keterangan</label>
                                <textarea name="remarks"id="remarks" class="form-control" rows="5"></textarea>
                            </div>
                        </div>
                    </div>
            </div>
            <input type="hidden" id="idfield">
            </form>

            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                    Close
                </button>
                <button type="button" class="btn btn-primary" id="simpan1" value=""
                    onclick="KlikSimpan1()">Save</button>
            </div>
        </div>

    </div>
</div>
</div>

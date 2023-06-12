<div class="modal fade" id="modalmenuLevel" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="modaluserLabel">Link Editor</h4>
                <button
                type="button"
                class="btn-close"
                data-bs-dismiss="modal"
                aria-label="Close"
              ></button>
            </div>
            <div class="modal-body">
                <form id="modalFormData" name="modalFormData" class="form-horizontal" novalidate="">


                  <div class="row g-1">
                    <div class="col mb-0">
                      <label for="inputLink" class="form-label">ID</label>
                      <input type="text" class="form-control" id="id" name="id" readonly value="">
                    </div>
                  </div>
                    
                  <div class="row g-2">
                    <div class="col mb-0">
                      <label class="form-label">Name Level</label>
                      <input type="text" class="form-control" id="Name" name="Name" value="">
                    </div>
                  </div>

                  {{-- <div class="row g-2">
                    <div class="col mb-0">
                      <label class="form-label">Status</label>
                      <select class="form-select" id="Status" name="Status">
                        <option selected disabled>Open this select menu</option>
                        <option value="A">A (Aktif)</option>
                        <option value="N">N (Non Aktif)</option>
                      </select>
                    </div>
                  </div> --}}

    

                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="save-menuLevel" value="add">Save changes
                </button>
                <input type="hidden" id="link_id" name="link_id" value="0">
            </div>
        </div>
    </div>
</div>
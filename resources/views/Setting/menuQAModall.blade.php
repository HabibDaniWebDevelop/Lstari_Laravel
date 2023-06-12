<div class="modal fade" id="modalqa" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="modaluserLabel">Link Editor</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="modalFormData" name="modalFormData" class="form-horizontal" novalidate="">

                    <div class="row g-1">
                        <div class="col mb-0">
                            <label for="inputLink" class="form-label">ID</label>
                            <input type="text" class="form-control" id="id" name="id" readonly
                                value="">
                        </div>
                    </div>

                    <div class="row g-2">
                        <div class="col mb-0">
                            <label class="form-label">User</label>
                            <div class="input-group">
                                <input type="text" class="form-control" id="Name" name="Name"
                                    style="width:80%; " />
                                <input type="text" class="form-control" disabled id="Nameid" name="Nameid" />
                            </div>
                        </div>
                    </div>

                    <div class="row g-2">
                        <div class="col mb-0">
                            <label class="form-label">Menu</label>
                            <div class="input-group">
                                <input type="text" class="form-control" id="Menu" name="Menu" value=""
                                    style="width:80%; ">
                                <input type="text" class="form-control" disabled id="Menuid" name="Menuid"
                                    value="">
                            </div>
                        </div>
                    </div>

                    <div class="row g-2">
                        <div class="col mb-0">
                            <label class="form-label">Ordinal</label>
                            <div id="Ordinalqa">
                                <input type="text" class="form-control" id="Ordinal" name="Ordinal"
                                    onclick="tampilordinal()" />
                                <input type="hidden" id="Ordinallama" name="Ordinallama"/>
                            </div>
                        </div>
                    </div>

                </form>
            </div>

            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-dark" id="hapus-QA" value="" onclick="hapusQA()">Hapus
                </button>
                <button type="button" class="btn btn-primary" id="save-QA" value="">Save changes </button>
                <input type="hidden" id="link_id" name="link_id" value="0">
            </div>

        </div>
    </div>
</div>

<script>
    function tampilordinal() {
        var id = $('#Nameid').val();
        var Ordinal = $('#Ordinal').val();
        // alert(id);
        $.get('/MenuQA/ordinal/' + id, function(data) {
            $("#Ordinalqa").html(data);
            $('#Ordinal').val(Ordinal);
            $('#Ordinallama').val(Ordinal);
        });
    }
</script>

{{-- {{ dd($Levels); }} --}}
<div class="row g-1">
    <div class="col mb-0">
        <label for="inputLink" class="form-label">ID_Modul</label>
        <input type="text" class="form-control" id="ID_Modul" name="ID_Modul" readonly value="{{ $link['ID_Modul'] }}">
    </div>
</div>

<div class="row g-2">
    <div class="col mb-0">
        <label class="form-label">Menu</label>
        <input type="text" class="form-control" id="Name" name="Name" value="{{ $link['Name'] }}">
    </div>
</div>

<div class="row g-2">
    <div class="col mb-0">
        <label class="form-label">Parent</label>
        <div class="input-group">
            <input type="text" class="form-control" id="Parentname" name="Parentname" style="width:80%; "
                value="{{ $Parent }}" />
            <input type="text" class="form-control" disabled id="Parent" name="Parent"
                value="{{ $link['Parent'] }}" />
        </div>
    </div>
</div>

<div class="row g-2">
    <div class="col mb-0">
        <label class="form-label">Ordinal</label>
        <div id="Ordinalmenu">
            <input type="number" class="form-control" id="Ordinal" name="Ordinal" readonly
                value="{{ $link['Ordinal'] }}" onclick="tampilordinal({{ $link['Ordinal'] }})">
        </div>
    </div>
</div>

<div class="row g-2">
    <div class="col mb-0">
        <label class="form-label">Path</label>
        <input type="text" class="form-control" id="Patch" name="Patch" value="{{ $link['Patch'] }}">
    </div>
</div>

<div class="row g-2">
    <div class="col mb-0">
        <label class="form-label">Status</label>
        <select class="form-select" id="Status" name="Status">
            <option selected disabled>Open this select menu</option>
            <option value="A" <?php echo $link['Status'] == 'A' ? 'selected' : ''; ?>>A (Aktif)</option>
            <option value="D" <?php echo $link['Status'] == 'D' ? 'selected' : ''; ?>>D (Devlopemen)</option>
            <option value="N" <?php echo $link['Status'] == 'N' ? 'selected' : ''; ?>>N (Non Aktif)</option>
        </select>
    </div>
</div>

<div class="row g-2">
    <div class="col mb-0">
        <label class="form-label">Icon</label>
        <input type="text" class="form-control" id="Icon" name="Icon" value="{{ $link['Icon'] }}">
    </div>
</div>

<div class="row g-2">
    <div class="col mb-0">
        <label class="form-label">made_by</label>
        <input type="text" class="form-control" id="made_by" name="made_by" value="{{ $link['made_by'] }}">
    </div>
</div>

<div class="row g-2">
    <div class="col mb-0">
        <label class="form-label">Akses</label>
        <input type="text" class="form-control" id="akseslis" name="akseslis" readonly onclick="aksesmodal();">
    </div>
</div>

<div class="modal fade" id="modalakses" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="linkEditorModalLabel">AKSES</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="modaltambahakses" name="modaltambahakses" class="form-horizontal" novalidate="">
                    <div id="aksesmenu">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" onclick="saveakses()">Save changes
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    $("#Parentname").autocomplete({
        source: function(request, response) {
            $.ajax({
                url: '/autoparent',
                type: 'GET',
                dataType: "json",
                data: {
                    search: request.term
                },
                success: function(data) {
                    response(data);
                }
            });
        },

        select: function(event, ui) {
            var parent = ui.item.label.replace(/ /g, "");
            $('#Parentname').val(ui.item.label);
            $('#Parent').val(ui.item.id);
            var name = $('#Name').val().replace(/ /g, "");
            $('#Patch').val(parent + name);
            tampilordinal();
            return false;
        },
        open: function() {
            $(this).autocomplete('widget').css('z-index', 1100);
            return false;
        },
    });

    function tampilordinal(ordinal) {
        var id = $('#Parent').val();
        // alert(ordinal);
        $.get('/ListMenu/ordinal/' + id, function(data) {
            $("#Ordinalmenu").html(data);
            $('#Ordinal').val(ordinal);
            $('#Ordinallama').val(ordinal);
        });
    }

    function aksesmodal() {
        var id = $('#ID_Modul').val();
        $.get('/ListMenu/Akses/' + id, function(data) {
            $("#aksesmenu").html(data);
        });

        $('#modalakses').modal('show');
    }

    function kliktambahakses() {
        var id = $('#ID_Modul').val();
        var Parent = $('#Parent').val();
        var aksesbaru = $('#tambahakses').val();

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({
            type: "POST",
            url: '/ListMenu/Akses',
            data: {
                "id": id,
                "aksesbaru": aksesbaru,
                "Parent": Parent
            },
            dataType: 'json',
            success: function(data) {
                aksesmodal();

            }
        });
    }

    function saveakses() {
        var id = $('#ID_Modul').val();
        var formData = $('#modaltambahakses').serialize();

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({
            type: "put",
            url: '/ListMenu/Akses/' + id,
            data: formData,
            dataType: 'json',
            success: function(data) {
                // aksesmodal();
                $('#modalakses').modal('hide');
            }
        });
    }
</script>

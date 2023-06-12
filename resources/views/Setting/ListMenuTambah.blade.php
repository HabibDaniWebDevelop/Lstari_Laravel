{{-- {{ dd($Levels); }} --}}
<div class="row g-1">
    <div class="col mb-0">
        <label for="inputLink" class="form-label">ID_Modul</label>
        <input type="text" class="form-control" id="ID_Modul" name="ID_Modul" readonly value="">
    </div>
</div>

<div class="row g-2">
    <div class="col mb-0">
        <label class="form-label">Menu</label>
        <input type="text" class="form-control" id="Name" name="Name" value="">
    </div>
</div>

<div class="row g-2">
    <div class="col mb-0">
        <label class="form-label">Parent</label>
        <div class="input-group">
            <input type="text" class="form-control" id="Parentname" name="Parentname" style="width:80%; "
                value="" />
            <input type="text" class="form-control" disabled id="Parent" name="Parent"
                value="" />
        </div>
    </div>
</div>

<div class="row g-2">
    <div class="col mb-0">
        <label class="form-label">Ordinal</label>
        <div id="Ordinalmenu">
            <input type="number" class="form-control" id="Ordinal" name="Ordinal" readonly
                value="" onclick="tampilordinal()">
        </div>
    </div>
</div>

<div class="row g-2">
    <div class="col mb-0">
        <label class="form-label">Path</label>
        <input type="text" class="form-control" id="Patch" name="Patch" value="">
    </div>
</div>

<div class="row g-2">
    <div class="col mb-0">
        <label class="form-label">Status</label>
        <select class="form-select" id="Status" name="Status">
            <option value="A" >A (Aktif)</option>
            <option selected value="D" >D (Devlopemen)</option>
            <option value="N" >N (Non Aktif)</option>
        </select>
    </div>
</div>

<div class="row g-2">
    <div class="col mb-0">
        <label class="form-label">Icon</label>
        <input type="text" class="form-control" id="Icon" name="Icon" value="">
    </div>
</div>

<div class="row g-2">
    <div class="col mb-0">
        <label class="form-label">made_by</label>
        <input type="text" class="form-control" id="made_by" name="made_by" value="{{ Auth::user()->name }}">
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

</script>

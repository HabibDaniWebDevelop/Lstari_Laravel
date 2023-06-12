
<div class="mb-2 row">
    <label class="col-md-2 col-form-label">Material ID</label>
    <div class="col-md-10">
        <input class="form-control" type="text" id="idnee" name="idnee" readonly value="{{ $datas[0]->ID }}" />
    </div>
</div>

<div class="mb-2 row">
    <label class="col-md-2 col-form-label">Material Name</label>
    <div class="col-md-10">
        <input class="form-control" type="text" readonly value="{{ $datas[0]->Description }}" />
    </div>
</div>

<div class="mb-2 row">
    <label class="col-md-2 col-form-label">Merk</label>
    <div class="col-md-10">
        <input class="form-control" type="text" id="Brand" name="Brand" value="{{ $datas[0]->Brand }}" />
    </div>
</div>

<div class="mb-2 row">
    <label class="col-md-2 col-form-label">Category</label>
    <div class="col-md-10">
        <input class="form-control" type="text" readonly value="{{ $datas[0]->KAT }}" />
    </div>
</div>

<div class="mb-2 row">
    <label class="col-md-2 col-form-label">Material Type</label>
    <div class="col-md-10">
        <select class="form-select" id="Type" name="Type">
            <option value="">Pilih</option>
            <option value="Chemical Solid">Chemical Solid</option>
            <option value="Chemical Liquid">Chemical Liquid</option>
            <option value="Media">Media</option>
            <option value="Tools">Tools</option>
        </select>
    </div>
</div>

<div class="mb-2 row">
    <label class="col-md-2 col-form-label">TDS <a class="float-end text-secondary fst-italic"> * Pdf file</a></label>
    <div class="col-md-10">
        <input type="file" class="form-control" id="TDS" name="TDS" accept="application/pdf">
        {{ $datas[0]->TDS }}
    </div>
</div>

<div class="mb-2 row">
    <label class="col-md-2 col-form-label">MSDS <a class="float-end text-secondary fst-italic"> * Pdf file</a></label>
    <div class="col-md-10">
        <input class="form-control" type="file" id="MSDS" name="MSDS" accept="application/pdf" />
        {{ $datas[0]->MSDS }}
    </div>
</div>

<div class="mb-2 row">
    <label class="col-md-2 col-form-label">Description</label>
    <div class="col-md-10">
        <textarea class="form-control" id="Remarks" name="Remarks" rows="3">{{ $datas[0]->Remarks }}</textarea>
    </div>
</div>

<div class="mb-2 row">
    <label class="col-md-2 col-form-label">Material Function</label>
    <div class="col-md-10">
        <input class="form-control" type="text" id="MaterialFunction" name="MaterialFunction"
            value="{{ $datas[0]->MaterialFunction }}" />
    </div>
</div>

<div class="mb-2 row">
    <label class="col-md-2 col-form-label">Area</label>
    <div class="col-md-10">
        <select class="form-control my-select2" id="department2" name="department2[]" multiple data-style="border">
            @foreach ($departments as $department)
                <option {{ $department->PID }} value="{{ $department->ID }}">{{ $department->Description }}
                </option>
            @endforeach
        </select>
    </div>
</div>

<script>
    $(document).ready(function() {
        var Type = "<?php echo $datas[0]->Type; ?>";
        $('#Type').val(Type);
        var department2 = $('#department2').val();
        // alert(department2);
        $('.my-select2').selectpicker();
    });
</script>
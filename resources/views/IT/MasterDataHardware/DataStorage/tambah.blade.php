<div class="row">
    <div class="col-6">
        <div class="form-group">
            <label class="form-label">Brand</label>
            <input type="text" name="Brand" class="form-control" required>
        </div>
    </div>
    <div class="col-6">
        <div class="form-group">
            <label class="form-label">Type</label>
            <select type="text" class="form-select" name="SubType" required>
                <option value="">--- Pilih ---</option>
                <option value="Laser Jet">Laser Jet</option>
                <option value="Ink Jet">Ink Jet</option>
                <option value="Thermal">Thermal</option>
                <option value="Dot Matrix">Dot Matrix</option>
            </select>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-6">
        <div class="form-group">
            <label class="form-label">IP Address</label>
            <input type="text" name="IPAddress" class="form-control" required>
        </div>
    </div>
    <div class="col-6">
        <div class="form-group">
            <label class="form-label">Series</label>
            <input type="text" name="Series" class="form-control" required>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-6">
        <div class="form-group">
            <label class="form-label">Serial Number</label>
            <input type="text" name="SerialNo" class="form-control" required>
        </div>
    </div>
    <div class="col-6">
        <div class="form-group">
            <label class="form-label">Purchase Date</label>
            <input type="date" name="PurchaseDate" value="" class="form-control" required>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-6">
        <div class="form-group">
            <label class="form-label">Supplier</label>
            <input type="text" name="Supplier" class="form-control" required>
        </div>
    </div>
    <div class="col-6">
        <div class="form-group">
            <label class="form-label">Department</label>
            <select class="form-select" name="Department" required>
                <option value="">--- Pilih ---</option>
                {{-- {{ dd($department); }} --}}
                    @foreach ($department as $departments)
                        <option value="{{ $departments->ID }}">{{ $departments->Description }}</option>
                    @endforeach
            </select>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-6">
        <div class="form-group">
            <label class="form-label">Note</label>
            <input type="text" class="form-control" name="Note" required>
        </div>
    </div>
</div>
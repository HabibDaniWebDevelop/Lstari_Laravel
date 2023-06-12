
@foreach ($data1 as $data1s)
@endforeach
{{-- {{ dd($data1s->Department); }} --}}

<div class="row">
    <div class="col-6">
        <div class="form-group">
            <label>Merk</label>
            <input type="hidden" name="id" value="' . $id . '">
            <input type="text" name="Brand" class="form-control" value="{{ $data1s->Brand }}">
        </div>
    </div>
    <div class="col-6">
        <div class="form-group">
            <label>Type</label>
            <select class="form-select" type="text" name="SubType">
                <option value="{{ $data1s->SubType }}">{{ $data1s->SubType }}</option>
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
            <label>IP Address</label>
            <input type="text" class="form-control" name="IPAddress" value="{{ $data1s->Var1 }}">
        </div>
    </div>
    <div class="col-6">
        <div class="form-group">
            <label>Series</label>
            <input type="text" class="form-control" name="Series" value="{{ $data1s->Series }}">
        </div>
    </div>
</div>

<div class="row">
    <div class="col-6">
        <div class="form-group">
            <label>Serial Number</label>
            <input type="text" class="form-control" name="SerialNo" value="{{ $data1s->SerialNo }}">
        </div>
    </div>
    <div class="col-6">
        <div class="form-group">
            <label>Supplier</label>
            <input type="text" class="form-control" name="Supplier" value="{{ $data1s->Supplier }}">
        </div>
    </div>
</div>

<div class="row">
    <div class="col-6">
        <div class="form-group">
            <label>Purchase Date</label>
            <input type="date" name="PurchaseDate" class="form-control" value="{{ $data1s->PurchaseDate }}">
        </div>
    </div>
    <div class="col-6">
        <div class="form-group">
            <label>Department</label>
            <select class="form-select" name="Department">
                <option value="{{ $data1s->Department }}">{{ $data1s->Department }}</option>';
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
            <label>Status</label>
            <select class="form-select" type="text" name="Status">
                <option value="{{ $data1s->STATUS }}">{{ $data1s->STATUS }}</option>
                <option value="Terpakai">Terpakai</option>
                <option value="Belum dipakai">Belum dipakai</option>
                <option value="Rusak">Rusak</option>
            </select>
        </div>
    </div>
    <div class="col-6">
        <div class="form-group">
            <label>Note</label>
            <input type="text" class="form-control" name="Note" value="{{ $data1s->Note }}">
        </div>
    </div>
</div>

{{-- {{ dd($data1); }} --}}
@foreach ($data1 as $data1s)
@endforeach

<div class="row">
    <div class="col-6">
        <div class="form-group">
            <label class="form-label">Computer Name</label>
            <input type="text" name="ComputerName" class="form-control" value="{{ $data1s->ComputerName }}">
            <input type="hidden" name="sw" value="{{ $data1s->SW }}">
            <input type="hidden" name="id" id="ID" value="{{ $data1s->ID }}">
        </div>
    </div>
    <div class="col-6">
        <div class="form-group">
            <label class="form-label">Type</label>
            <input type="text" class="form-control" name="Type" value="{{ $data1s->Type }}">
        </div>
    </div>
</div>

<div class="row">
    <div class="col-6">
        <div class="form-group">
            <label class="form-label">IP Address</label>
            <input type="text" class="form-control" name="IPAddress" value="{{ $data1s->IPAddress }}">
        </div>
    </div>
    <div class="col-6">
        <div class="form-group">
            <label class="form-label">MAC Address (Gunakan Tanda ":")</label>
            <input type="text" class="form-control" name="MACAddress" value="{{ $data1s->MACAddress }}">
        </div>
    </div>
</div>


<div class="row">
    <div class="col-6">
        <div class="form-group">
            <label class="form-label">Mainboard</label>
            <select class="form-select" name="Mainboard">
                <option value="{{ $data1s->b }}">{{ $data1s->Mainboard }}</option>
                @foreach ($Mainboard as $Mainboards)
                    <option value="{{ $Mainboards->ID }}">{{ $Mainboards->Deskripsi }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="col-6">
        <div class="form-group">
            <label class="form-label">Processor</label>
            <select class="form-select" name="Processor">
                <option value="{{ $data1s->c }}">{{ $data1s->Processor }}</option>
                @foreach ($Processor as $Processors)
                    <option value="{{ $Processors->ID }}">{{ $Processors->Deskripsi }}</option>
                @endforeach
            </select>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-6">
        <div class="form-group">
            <label class="form-label">Memory 1</label>
            <select class="form-select" name="Memory1">
                <option value="{{ $data1s->l }}">{{ $data1s->Memory1 }}</option>
                @foreach ($Memory as $Memory1)
                    <option value="{{ $Memory1->ID }}">{{ $Memory1->Deskripsi }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="col-6">
        <div class="form-group">
            <label class="form-label">Memory 2</label>
            <select class="form-select" name="Memory2">
                <option value="{{ $data1s->m }}">{{ $data1s->Memory2 }}</option>
                <option value="0">-- Tidak Ada --</option>
                @foreach ($Memory as $Memory2)
                    <option value="{{ $Memory2->ID }}">{{ $Memory2->Deskripsi }}</option>
                @endforeach
            </select>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-6">
        <div class="form-group">
            <label class="form-label">Storage 1</label>
            <select class="form-select" name="Storage1">
                <option selcted="selected" value="{{ $data1s->d }}">{{ $data1s->Storage1 }}</option>
                @foreach ($Storage as $Storage1)
                    <option value="{{ $Storage1->ID }}">{{ $Storage1->Deskripsi }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="col-6">
        <div class="form-group">
            <label class="form-label">Storage 2</label>
            <select class="form-select" name="Storage2">
                <option value="{{ $data1s->e }}">{{ $data1s->Storage2 }}</option>
                <option value="0">-- Tidak Ada --</option>
                @foreach ($Storage as $Storage2)
                    <option value="{{ $Storage2->ID }}">{{ $Storage2->Deskripsi }}</option>
                @endforeach
            </select>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-6">
        <div class="form-group">
            <label class="form-label">VGA</label>
            <select class="form-select" name="VGA">

                <option value="{{ $data1s->j }}">{{ $data1s->VGA }}</option>
                <option value="0">-- Tidak Ada --</option>
                @foreach ($VGA as $VGAs)
                    <option value="{{ $VGAs->ID }}">{{ $VGAs->Deskripsi }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="col-6">
        <div class="form-group">
            <label class="form-label">Monitor</label>
            <select class="form-select" name="Monitor">

                <option value="{{ $data1s->k }}">{{ $data1s->Monitor }}</option>
                <option value="0">-- Tidak Ada --</option>
                @foreach ($Monitor as $Monitors)
                    <option value="{{ $Monitors->ID }}">{{ $Monitors->Deskripsi }}</option>
                @endforeach
            </select>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-6">
        <div class="form-group">
            <label class="form-label">Keyboard</label>
            <select class="form-select" name="Keyboard">
                <option value="{{ $data1s->i }}">{{ $data1s->Keyboard }}</option>
                <option value="0">-- Tidak Ada --</option>
                @foreach ($Keyboard as $Keyboards)
                    <option value="{{ $Keyboards->ID }}">{{ $Keyboards->Deskripsi }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="col-6">
        <div class="form-group">
            <label class="form-label">Mouse</label>
            <select class="form-select" name="Mouse">
                <option value="{{ $data1s->h }}">{{ $data1s->Mouse }}</option>
                <option value="0">-- Tidak Ada --</option>
                @foreach ($Mouse as $Mouses)
                    <option value="{{ $Mouses->ID }}">{{ $Mouses->Deskripsi }}</option>
                @endforeach
            </select>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-6">
        <div class="form-group">
            <label class="form-label">Printer 1</label>
            <select class="form-select" name="Printer1">
                <option value="{{ $data1s->f }}">{{ $data1s->Printer1 }}</option>
                <option value="0">-- Tidak Ada --</option>
                @foreach ($Printer as $Printer1)
                    <option value="{{ $Printer1->ID }}">{{ $Printer1->Deskripsi }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="col-6">
        <div class="form-group">
            <label class="form-label">Printer 2</label>
            <select class="form-select" name="Printer2">
                <option value="{{ $data1s->g }}">{{ $data1s->Printer2 }}</option>
                <option value="0">-- Tidak Ada --</option>
                @foreach ($Printer as $Printer2)
                    <option value="{{ $Printer2->ID }}">{{ $Printer2->Deskripsi }}</option>
                @endforeach
            </select>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-6">
        <div class="form-group">
            <label class="form-label">Weight Scale</label>
            <select class="form-select" name="WeightScale">
                <option value="{{ $data1s->q }}">{{ $data1s->WeightScale }}</option>
                <option value="0">-- Tidak Ada --</option>
                @foreach ($WeightScale as $WeightScales)
                    <option value="{{ $WeightScales->ID }}">{{ $WeightScales->Deskripsi }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="col-6">
        <div class="form-group">
            <label class="form-label">UPS</label>
            <select class="form-select" name="UPS">
                <option value="{{ $data1s->p }}">{{ $data1s->UPS }}</option>
                <option value="0">-- Tidak Ada --</option>
                @foreach ($UPS as $UPSs)
                    <option value="{{ $UPSs->ID }}">{{ $UPSs->Deskripsi }}</option>
                @endforeach
            </select>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-6">
        <div class="form-group">
            <label class="form-label">Scanner</label>
            <select class="form-select" name="Scanner">
                <option value="{{ $data1s->n }}">{{ $data1s->Scanner }}</option>
                <option value="0">-- Tidak Ada --</option>
                @foreach ($Scanner as $Scanners)
                    <option value="{{ $Scanners->ID }}">{{ $Scanners->Deskripsi }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="col-6">
        <div class="form-group">
            <label class="form-label">Barcode Scanner</label>
            <select class="form-select" name="BarcodeScanner">
                <option value="{{ $data1s->o }}">{{ $data1s->BarcodeScanner }}</option>
                <option value="0">-- Tidak Ada --</option>
                @foreach ($BarcodeScanner as $BarcodeScanners)
                    <option value="{{ $BarcodeScanners->ID }}">{{ $BarcodeScanners->Deskripsi }}</option>
                @endforeach
            </select>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-6">
        <div class="form-group">
            <label class="form-label">Series</label>
            <input type="text" class="form-control" name="Series" value="{{ $data1s->Series }}">
        </div>
    </div>
    <div class="col-6">
        <div class="form-group">
            <label class="form-label">Operating System</label>
            <select class="form-select" name="OperatingSystem">
                <option value="{{ $data1s->OperatingSystem }}">{{ $data1s->OperatingSystem }}</option>
                <option value="Windows 7 Ultimate 32-bit">Windows 7 Ultimate 32-bit</option>
                <option value="Windows 7 Ultimate 64-bit">Windows 7 Ultimate 64-bit</option>
                <option value="Windows 7 Enterprice 64-bit">Windows 7 Enterprice 64-bit</option>
                <option value="Windows 7 Ultimate 64-bit SP1">Windows 7 Ultimate 64-bit SP1</option>
                <option value="Windows Server 2008 R2 Enterprice 64-bit">Windows Server 2008 R2 Enterprice 64-bit
                </option>
                <option value="Windows 10 Pro 64-bit">Windows 10 Pro 64-bit</option>
                <option value="Windows 11 Pro 64-bit">Windows 11 Pro 64-bit</option>
            </select>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-6">
        <div class="form-group">
            <label class="form-label">Domain</label>
            <input type="text" class="form-control" name="Domain" value="{{ $data1s->Domain }}">
        </div>
    </div>
    <div class="col-6">
        <div class="form-group">
            <label class="form-label">Antivirus</label>
            <select class="form-select" name="Antivirus">
                <option value="{{ $data1s->Antivirus }}">{{ $data1s->Antivirus }}</option>

                <option value="AVAST Free Antivirus">AVAST Free Antivirus</option>
                <option value="AVIRA">AVIRA</option>
                <option value="AVG">AVG</option>
                <option value="Windows Defender">Windows Defender</option>
            </select>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="row">
            <div class="col-md-6">
                <label class="form-label">Department</label>
                <select class="form-select" id="Department" name="Department" onchange="pilihkar1()">
                    <option value="{{ $data1s->s }}">{{ $data1s->Department }}</option>
                    @foreach ($department as $departments)
                        <option value="{{ $departments->ID }}">{{ $departments->Description }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-6">
                <label class="form-label">Karyawan</label>
                <div id="optionsto1">
                    <select class="form-select" id="exemploree" name="Employee">
                        <option value="{{ $data1s->r }}">{{ $data1s->Employee }}</option>
                    </select>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6">
        <div class="form-group">
            <label class="form-label">Status</label>
            <select class="form-select" name="Status">
                <option value="{{ $data1s->STATUS }}">{{ $data1s->STATUS }}</option>
                <option value="Terpakai">Terpakai</option>
                <option value="Belum dipakai">Belum dipakai</option>
                <option value="Rusak">Rusak</option>
            </select>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-6">
        <div class="form-group">
            <label class="form-label">Note</label>
            <input type="text" class="form-control" name="Note" value="{{ $data1s->Note }}">
        </div>
    </div>
    <div class="col-6">
        <div class="form-group">
            <label class="form-label">Supplier</label>
            <input type="text" class="form-control" name="Supplier" value="{{ $data1s->Supplier }}">
        </div>
    </div>
</div>


<div class="row">
    <div class="col-6">
        <div class="form-group">
            <label class="form-label">Purchase Date</label>
            <input type="date" class="form-control" value="{{ $data1s->PurchaseDate }}" name="PurchaseDate"
                value="{{ $data1s->PurchaseDate }}">
        </div>
    </div>
</div>

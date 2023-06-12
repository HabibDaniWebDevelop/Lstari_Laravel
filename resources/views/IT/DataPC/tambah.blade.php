<div class="row">
    <div class="col-6">
        <div class="form-group">
            <label class="form-label">Computer Name</label>
            <input type="text" name="ComputerName" class="form-control" required>
        </div>
    </div>
    <div class="col-6">
        <div class="form-group">
            <label class="form-label">Type</label>
            <select class="form-select" name="Type" id="Type" required>
                <option value="">--- Pilih ---</option>
                <option value="Laptop">Laptop</option>
                <option value="PC Dekstop">PC Dekstop</option>
            </select>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-6">
        <div class="form-group">
            <label class="form-label">IP Address</label>
            <input type="text" class="form-control" name="IPAddress" required>
        </div>
    </div>
    <div class="col-6">
        <div class="form-group">
            <label class="form-label">MAC Address (Gunakan Tanda ":")</label>
            <input type="text" class="form-control" name="MACAddress" required>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-6">
        <div class="form-group">
            <label class="form-label">Mainboard</label>
            <div class="input-group">
                <select class="form-select" name="Mainboard" required>
                    <option value="">--- Pilih ---</option>
                    @foreach ($Mainboard as $Mainboards)
                        <option value="{{ $Mainboards->ID }}">{{ $Mainboards->Deskripsi }}</option>
                    @endforeach
                </select>
                <button class="btn btn-outline-primary" type="button" onclick="window.open('datamainboard')"><span
                        class="fas fa-plus"></span></button>
            </div>
        </div>
    </div>
    <div class="col-6">
        <div class="form-group">
            <label class="form-label">Processor</label>
            <div class="input-group">
                <select class="form-select" name="Processor" required>
                    <option value="">--- Pilih ---</option>
                    @foreach ($Processor as $Processors)
                        <option value="{{ $Processors->ID }}">{{ $Processors->Deskripsi }}</option>
                    @endforeach
                </select>
                <button class="btn btn-outline-primary" type="button" onclick="window.open('dataprocessor')"><span
                        class="fas fa-plus"></span></button>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-6">
        <div class="form-group">
            <label class="form-label">Memory 1</label>
            <div class="input-group">
                <select class="form-select" name="Memory1" required>
                    <option value="">--- Pilih ---</option>
                    @foreach ($Memory as $Memory1)
                        <option value="{{ $Memory1->ID }}">{{ $Memory1->Deskripsi }}</option>
                    @endforeach
                </select>
                <button class="btn btn-outline-primary" type="button" onclick="window.open('datamemory')"><span
                        class="fas fa-plus"></span></button>
            </div>
        </div>
    </div>
    <div class="col-6">
        <div class="form-group">
            <label class="form-label">Memory 2</label>
            <div class="input-group">
                <select class="form-select" name="Memory2">
                    <option value="">--- Pilih ---</option>
                    @foreach ($Memory as $Memory2)
                        <option value="{{ $Memory2->ID }}">{{ $Memory2->Deskripsi }}</option>
                    @endforeach
                </select>
                <button class="btn btn-outline-primary" type="button" onclick="window.open('datamemory')"><span
                        class="fas fa-plus"></span></button>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-6">
        <div class="form-group">
            <label class="form-label">Storage 1</label>
            <div class="input-group">
                <select class="form-select" name="Storage1" required>
                    <option value="">--- Pilih ---</option>
                    @foreach ($Storage as $Storage1)
                        <option value="{{ $Storage1->ID }}">{{ $Storage1->Deskripsi }}</option>
                    @endforeach
                </select>
                <button class="btn btn-outline-primary" type="button" onclick="window.open('datahdd')"><span
                        class="fas fa-plus"></span></button>
            </div>
        </div>
    </div>
    <div class="col-6">
        <div class="form-group">
            <label class="form-label">Storage 2</label>
            <div class="input-group">
                <select class="form-select" name="Storage2">
                    <option value="">--- Pilih ---</option>
                    @foreach ($Storage as $Storage2)
                        <option value="{{ $Storage2->ID }}">{{ $Storage2->Deskripsi }}</option>
                    @endforeach
                </select>
                <button class="btn btn-outline-primary" type="button" onclick="window.open('datahdd')"><span
                        class="fas fa-plus"></span></button>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-6">
        <div class="form-group">
            <label class="form-label">VGA</label>
            <div class="input-group">
                <select class="form-select" name="VGA" required>
                    <option value="">--- Pilih ---</option>
                    @foreach ($VGA as $VGAs)
                        <option value="{{ $VGAs->ID }}">{{ $VGAs->Deskripsi }}</option>
                    @endforeach
                </select>
                <button class="btn btn-outline-primary" type="button" onclick="window.open('datavga')"><span
                        class="fas fa-plus"></span></button>
            </div>
        </div>
    </div>
    <div class="col-6">
        <div class="form-group">
            <label class="form-label">Monitor</label>
            <div class="input-group">
                <select class="form-select" name="Monitor" required>
                    <option value="">--- Pilih ---</option>
                    @foreach ($Monitor as $Monitors)
                        <option value="{{ $Monitors->ID }}">{{ $Monitors->Deskripsi }}</option>
                    @endforeach
                </select>
                <button class="btn btn-outline-primary" type="button" onclick="window.open('datamonitor')"><span
                        class="fas fa-plus"></span></button>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-6">
        <div class="form-group">
            <label class="form-label">Keyboard</label>
            <div class="input-group">
                <select class="form-select" name="Keyboard" required>
                    <option value="">--- Pilih ---</option>
                    @foreach ($Keyboard as $Keyboards)
                        <option value="{{ $Keyboards->ID }}">{{ $Keyboards->Deskripsi }}</option>
                    @endforeach
                </select>
                <button class="btn btn-outline-primary" type="button" onclick="window.open('datakeyboard')"><span
                        class="fas fa-plus"></span></button>
            </div>
        </div>
    </div>
    <div class="col-6">
        <div class="form-group">
            <label class="form-label">Mouse</label>
            <div class="input-group">
                <select class="form-select" name="Mouse" required>
                    <option value="">--- Pilih ---</option>
                    @foreach ($Mouse as $Mouses)
                        <option value="{{ $Mouses->ID }}">{{ $Mouses->Deskripsi }}</option>
                    @endforeach
                </select>
                <button class="btn btn-outline-primary" type="button" onclick="window.open('datamouse')"><span
                        class="fas fa-plus"></span></button>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-6">
        <div class="form-group">
            <label class="form-label">Printer 1</label>
            <div class="input-group">
                <select class="form-select" name="Printer1">
                    <option value="">--- Pilih ---</option>
                    @foreach ($Printer as $Printer1)
                        <option value="{{ $Printer1->ID }}">{{ $Printer1->Deskripsi }}</option>
                    @endforeach
                </select>
                <button class="btn btn-outline-primary" type="button" onclick="window.open('dataprinter')"><span
                        class="fas fa-plus"></span></button>
            </div>
        </div>
    </div>
    <div class="col-6">
        <div class="form-group">
            <label class="form-label">Printer 2</label>
            <div class="input-group">
                <select class="form-select" name="Printer2">
                    <option value="">--- Pilih ---</option>
                    @foreach ($Printer as $Printer2)
                        <option value="{{ $Printer2->ID }}">{{ $Printer2->Deskripsi }}</option>
                    @endforeach
                </select>
                <button class="btn btn-outline-primary" type="button" onclick="window.open('dataprinter')"><span
                        class="fas fa-plus"></span></button>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-6">
        <div class="form-group">
            <label class="form-label">Weight Scale</label>
            <div class="input-group">
                <select class="form-select" name="WeightScale">
                    <option value="">--- Pilih ---</option>
                    @foreach ($WeightScale as $WeightScales)
                        <option value="{{ $WeightScales->ID }}">{{ $WeightScales->Deskripsi }}</option>
                    @endforeach
                </select>
                <button class="btn btn-outline-primary" type="button" onclick="window.open('datatimbangan')"><span
                        class="fas fa-plus"></span></button>
            </div>
        </div>
    </div>
    <div class="col-6">
        <div class="form-group">
            <label class="form-label">UPS</label>
            <div class="input-group">
                <select class="form-select" name="UPS">
                    <option value="">--- Pilih ---</option>
                    @foreach ($UPS as $UPSs)
                        <option value="{{ $UPSs->ID }}">{{ $UPSs->Deskripsi }}</option>
                    @endforeach
                </select>
                <button class="btn btn-outline-primary" type="button" onclick="window.open('dataups')"><span
                        class="fas fa-plus"></span></button>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-6">
        <div class="form-group">
            <label class="form-label">Scanner</label>
            <div class="input-group">
                <select class="form-select" name="Scanner">
                    <option value="">--- Pilih ---</option>
                    @foreach ($Scanner as $Scanners)
                        <option value="{{ $Scanners->ID }}">{{ $Scanners->Deskripsi }}</option>
                    @endforeach
                </select>
                <button class="btn btn-outline-primary" type="button" onclick="window.open('datascanner')"><span class="fas fa-plus"></span></button>
            </div>
        </div>
    </div>

    <div class="col-6">
        <div class="form-group">
            <label class="form-label">Barcode Scanner</label>
            <div class="input-group">
                <select class="form-select" name="BarcodeScanner">
                    <option value="">--- Pilih ---</option>
                    @foreach ($BarcodeScanner as $BarcodeScanners)
                        <option value="{{ $BarcodeScanners->ID }}">{{ $BarcodeScanners->Deskripsi }}</option>
                    @endforeach
                </select>
                <button class="btn btn-outline-primary" type="button" onclick="window.open('databarcodescanner')"><span class="fas fa-plus"></span></button>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-6">
        <div class="form-group">
            <label class="form-label">Series</label>
            <input type="text" class="form-control" name="Series">

        </div>
    </div>
    <div class="col-6">
        <div class="form-group">
            <label class="form-label">Operating System</label>
            <select class="form-select" name="OperatingSystem" id="OperatingSystem" required>
                <option value="">--- Pilih ---</option>
                <option value="Windows 7 Ultimate 32-bit">Windows 7 Ultimate 32-bit</option>
                <option value="Windows 7 Ultimate 64-bit">Windows 7 Ultimate 64-bit</option>
                <option value="Windows 7 Enterprice 64-bit">Windows 7 Enterprice 64-bit</option>
                <option value="Windows 7 Ultimate 64-bit SP1">Windows 7 Ultimate 64-bit SP1</option>
                <option value="Windows Server 2008 R2 Enterprice 64-bit">Windows Server 2008 R2 Enterprice 64-bit </option>
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
            <input type="text" class="form-control" name="Domain" required>
        </div>
    </div>
    <div class="col-6">
        <div class="form-group">
            <label class="form-label">Antivirus</label>
            <select class="form-select" name="Antivirus" id="Antivirus" required>
                <option value="">--- Pilih ---</option>
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
                <select class="form-select" id="Department" name="Department" onchange="pilihkar2()">
                    <option value="">--- Pilih ---</option>
                    @foreach ($department as $departments)
                        <option value="{{ $departments->ID }}">{{ $departments->Description }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-6">
                <label class="form-label">Karyawan</label>
                <div id="optionsto2">
                    <input type="text" class="form-control" id="exemploree2">
                </div>
            </div>
        </div>
    </div>

    <div class="col-6">
        <div class="form-group">
            <label class="form-label">Status</label>
            <select type="text" class="form-select" name="Status" required>
                <option value="">--- Pilih ---</option>
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
            <input type="text" class="form-control" name="Note" required>
        </div>
    </div>
    <div class="col-6">
        <div class="form-group">
            <label class="form-label">Supplier</label>
            <input type="text" name="Supplier" class="form-control">
        </div>
    </div>
</div>

<div class="row">
    <div class="col-6">
        <div class="form-group">
            <label class="form-label">Purchase Date</label>
            <input type="date" name="PurchaseDate" value="" class="form-control">
        </div>
    </div>
</div>

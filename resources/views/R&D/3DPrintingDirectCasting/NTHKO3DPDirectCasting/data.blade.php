<div class="card-body">
    <div class="demo-inline-spacing">
        @if ($menu == '1')
            <button type="button" class="btn btn-primary me-4" id="Baru1" onclick="Klik_Baru1()"> <span
                    class="tf-icons bx bx-plus-circle"></span>&nbsp; Baru </button>

            <button type="button" class="btn btn-danger" id="Batal1" disabled onclick="Klik_Batal1()"> <span
                    class="fas fa-times-circle"></span>&nbsp; Batal</button>

            <button type="button" class="btn btn-warning me-4" id="Simpan1" disabled onclick="Klik_Simpan1()">
                <span class="tf-icons bx bx-save"></span>&nbsp; Simpan</button>

            <button type="button" class="btn btn-info" id="Cetak1" value="" disabled onclick="Klik_Cetak1()">
                <span class="tf-icons bx bx-printer"></span>&nbsp; Cetak</button>
        @else
            <button type="button" class="btn btn-dark" id="Posting1" disabled onclick="Klik_Posting1()">
                <span class="tf-icons bx bx-send"></span>&nbsp; Posting</button>
        @endif
        <div class="float-end">
            <div class="input-group input-group-merge">
                <span class="input-group-text"><i class="bx bx-search" onclick="klikViewSelection()"></i></span>
                <input class="form-control" list="carilist" autofocus id='cari' onchange="ChangeCari('0')"
                    placeholder="Type to search...">
            </div>
            <datalist class="text-warning" id="carilist">
                @foreach ($carilists as $carilist)
                    <option value="{{ $carilist->SW }}" >
                        @if ($carilist->Active == "P")
                          
                        @endif   
                    </option>
                @endforeach
            </datalist>
        </div>
        <hr class="m-0"/>
    <br>
    </div>
        <form id="form1" method="POST"> 
            <div class="row">
                <div class="col-md-4">
                    <label class="control-label">Tanggal</label>
                    <input type="date" class="form-control" name="tanggal2" id="tanggal2" required="true" value="{{ date('Y-m-d'); }}" disabled="true"/>
                </div>  
                <div class="col-md-4">
                    <label class="control-label" for="tgl">Karyawan</label>
                    <select class="form-select" id="employee" name="employee" required>
                        @foreach ($employees as $employee)
                        <option value="{{ $employee->ID }}" selected>{{ $employee->Description }}</option>
                        @endforeach
                    </select>
                </div>  
                <div class="col-md-4">
                    <label class="control-label" for="envelope">File Job</label>
                    <select class="form-control" id="envelope2" name="envelope2" disabled="true" onchange="seeenve()">
                        <option  selected="selected" value="0">Pilih</option>
                        @foreach ($enve as $envelope)
                        <option value="{{ $envelope->ID }}">{{ $envelope->ENVEP }}</option>
                        @endforeach
                    </select>
                </div>  
            </div>
            <div id="tutup" class="d-none">
                <div class="row">                                               
                    <div class="col-md-4">
                        <label class="control-label">No Mesin</label>
                        <select class="form-control" id="mesin1" name="mesin1" onchange="speky()">
                            <option value="0">Pilih</option>
                            @foreach ($spek as $resinspek)
                            <option value="{{ $resinspek->ID }}" selected>{{ $resinspek->MechineNo }}</option>
                            @endforeach                                                
                        </select>
                    </div>                                                                      
                    <div class="col-md-4">
                        <label class="control-label">Material</label>
                        <input class="form-control" id="material1" name="material1" value="Jet Cast 1000"> 
                        </input>                                                           
                    </div>
                    <div class="col-md-4">
                        <label class="control-label">Luas Envelope</label>
                        <input class="form-control" id="dias" name="dias" value="210 x 130">
                        </input>
                    </div>                                                                              
                </div>                                                  
                <div class="row">
                    <div class="col-md-4">
                        <label class="control-label">No Tabung</label>
                        <input type="number" class="form-control" name="tabug" id="tabug" placeholder="Masukan No Tabung">      
                    </div>                                                  
                    <div class="col-md-4">
                        <label class="control-label">Tray</label>
                        <input type="number" class="form-control" name="trak" id="trak" placeholder="Masukan Tray">                                                     
                    </div>                  
                    <div class="col-md-4">
                        <label class="control-label">Lamp</label>
                        <input type="number" class="form-control" id="lamp" name="lamp" placeholder="Masukan Lamp">
                    </div>                      
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <label class="control-label">Log File</label>
                        <input type="text" class="form-control" name="log1" id="log1"/> 
                    </div>                                                      
                    <div class="col-md-4">
                        <label class="control-label">Ketebalan</label>
                        <input class="form-control" id="thickness1" name="thickness1" value="0.05"> 
                        </input>                                                       
                    </div>                                                                              
                    <div class="col-md-4">
                        <label class="control-label">Kecerahan</label>
                        <input type="number" class="form-control" name="brigness1" id="brigness1"/>
                    </div>                          
                </div>  
                <div class="row">
                    <div class="col-md-12">
                        <label class="control-label">Catatan</label>
                        <input class="form-control" name="catatan2" id="catatan2" type="text"  />
                    </div>          
                </div>
            </div>     
            <div id="tampil" class="d-none">
            </div>
        </form>
</div>

@include('IT.DataPC.modal')

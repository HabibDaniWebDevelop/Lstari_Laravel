
{{-- style="height:calc(100vh - 490px);" --}}
{{-- <br> --}}
{{-- <h3>Stock Opname</h3> --}}
<div class="table-responsive text-nowrap" style=" margin-top:10px;">
<table style="width:100%;" border="1">
    <tr>
        <td>
            <table class="table table-hover table-sm" style="width:100%;" border="1">
                <thead class="table-secondary sticky-top zindex-2">
                    <tr>
                        <th style="font-weight:bold; font-size:14px; text-align:center; vertical-align:middle;" rowspan="4">No.</th>
                        <th style="font-weight:bold; font-size:14px; text-align:center; vertical-align:middle;" rowspan="4">Nama Operator</th>
                    </tr>
                    <tr>
                        <th style="font-weight:bold; font-size:14px; text-align:center;" colspan="6">Januari</th>
                    </tr>
                    <tr>
                        <th style="font-weight:bold; font-size:14px; text-align:center; background-color:#E49396; color:white;" colspan="2">Baru</th>
                        <th style="font-weight:bold; font-size:14px; text-align:center; background-color:#777B7E; color:white;" colspan="2">Revisi</th>
                        <th style="font-weight:bold; font-size:14px; text-align:center; background-color:#343A40; color:white;" colspan="2">Total</th>
                    </tr>
                    <tr>
                        <th style="font-weight:bold; font-size:14px; text-align:center;">Model</th>
                        <th style="font-weight:bold; font-size:14px; text-align:center;">Pcs</th>
                        <th style="font-weight:bold; font-size:14px; text-align:center;">Model</th>
                        <th style="font-weight:bold; font-size:14px; text-align:center;">Pcs</th>
                        <th style="font-weight:bold; font-size:14px; text-align:center;">Model</th>
                        <th style="font-weight:bold; font-size:14px; text-align:center;">Pcs</th>
                    </tr>
                </thead>
                <tbody>
                @forelse ($data1 as $datas)
                        <tr>
                            {{-- Bulan1 --}}
                            <td style="text-align:center; color:black;">{{ $loop->iteration }}.</td>
                            <td style="text-align:left; color:black;">{{ $datas->Description }}</td>
                            <td style="text-align:center; color:black;">{{ $datas->COUNTModel1 }}</td>
                            <td style="text-align:center; color:black;">{{ $datas->COUNTPcs1 }}</td>
                            <td style="text-align:center; color:black;">{{ $datas->COUNTModel2 }}</td>
                            <td style="text-align:center; color:black;">{{ $datas->COUNTPcs2 }}</td>
                            <td style="text-align:center; color:black;">{{ $datas->totalModel }}</td>
                            <td style="text-align:center; color:black;">{{ $datas->totalPcs }}</td>
                        </tr>
                    @empty
                        {{-- <div class="alert alert-danger">
                            Data Belum Tersedia.
                        </div> --}}
                    @endforelse
                </tbody>
            </table>
        </td>
        <td><table class="table table-hover table-sm" style="width:100%;" border="1">
            <thead class="table-secondary sticky-top zindex-2">
                <tr>
                    <th style="font-weight:bold; font-size:14px; text-align:center;" colspan="6">Februari</th>
                </tr>
                <tr>
                    <th style="font-weight:bold; font-size:14px; text-align:center; background-color:#E49396; color:white;" colspan="2">Baru</th>
                    <th style="font-weight:bold; font-size:14px; text-align:center; background-color:#777B7E; color:white;" colspan="2">Revisi</th>
                    <th style="font-weight:bold; font-size:14px; text-align:center; background-color:#343A40; color:white;" colspan="2">Total</th>
                </tr>
                <tr>
                    <th style="font-weight:bold; font-size:14px; text-align:center;">Model</th>
                    <th style="font-weight:bold; font-size:14px; text-align:center;">Pcs</th>
                    <th style="font-weight:bold; font-size:14px; text-align:center;">Model</th>
                    <th style="font-weight:bold; font-size:14px; text-align:center;">Pcs</th>
                    <th style="font-weight:bold; font-size:14px; text-align:center;">Model</th>
                    <th style="font-weight:bold; font-size:14px; text-align:center;">Pcs</th>
                </tr>
            </thead>
            <tbody>
        
            @forelse ($data2 as $databulan2)
                    <tr>
                        <td style="text-align:center; color:black;">{{ $databulan2->COUNTModel1 }}</td>
                        <td style="text-align:center; color:black;">{{ $databulan2->COUNTPcs1 }}</td>
                        <td style="text-align:center; color:black;">{{ $databulan2->COUNTModel2 }}</td>
                        <td style="text-align:center; color:black;">{{ $databulan2->COUNTPcs2 }}</td>
                        <td style="text-align:center; color:black;">{{ $databulan2->totalModel }}</td>
                        <td style="text-align:center; color:black;">{{ $databulan2->totalPcs }}</td>
                    </tr>
                @empty
                    {{-- <div class="alert alert-danger">
                        Data Belum Tersedia.
                    </div> --}}
                @endforelse
            </tbody>
        </table></td>
        <td><table class="table table-hover table-sm" style="width:100%;" border="1">
                <thead class="table-secondary sticky-top zindex-2">
                    <tr>
                        <th style="font-weight:bold; font-size:14px; text-align:center;" colspan="6">Maret</th>
                    </tr>
                    <tr>
                        <th style="font-weight:bold; font-size:14px; text-align:center; background-color:#E49396; color:white;" colspan="2">Baru</th>
                        <th style="font-weight:bold; font-size:14px; text-align:center; background-color:#777B7E; color:white;" colspan="2">Revisi</th>
                        <th style="font-weight:bold; font-size:14px; text-align:center; background-color:#343A40; color:white;" colspan="2">Total</th>
                    </tr>
                    <tr>
                        <th style="font-weight:bold; font-size:14px; text-align:center;">Model</th>
                        <th style="font-weight:bold; font-size:14px; text-align:center;">Pcs</th>
                        <th style="font-weight:bold; font-size:14px; text-align:center;">Model</th>
                        <th style="font-weight:bold; font-size:14px; text-align:center;">Pcs</th>
                        <th style="font-weight:bold; font-size:14px; text-align:center;">Model</th>
                        <th style="font-weight:bold; font-size:14px; text-align:center;">Pcs</th>
                    </tr>
                </thead>
                <tbody>
            
                @forelse ($data3 as $databulan3)
                        <tr>
                            <td style="text-align:center; color:black;">{{ $databulan3->COUNTModel1 }}</td>
                            <td style="text-align:center; color:black;">{{ $databulan3->COUNTPcs1 }}</td>
                            <td style="text-align:center; color:black;">{{ $databulan3->COUNTModel2 }}</td>
                            <td style="text-align:center; color:black;">{{ $databulan3->COUNTPcs2 }}</td>
                            <td style="text-align:center; color:black;">{{ $databulan3->totalModel }}</td>
                            <td style="text-align:center; color:black;">{{ $databulan3->totalPcs }}</td>
                        </tr>
                    @empty
                        {{-- <div class="alert alert-danger">
                            Data Belum Tersedia.
                        </div> --}}
                    @endforelse
                </tbody>
            </table></td>
            <td><table class="table table-hover table-sm" style="width:100%;" border="1">
                <thead class="table-secondary sticky-top zindex-2">
                    <tr>
                        <th style="font-weight:bold; font-size:14px; text-align:center;" colspan="6">April</th>
                    </tr>
                    <tr>
                        <th style="font-weight:bold; font-size:14px; text-align:center; background-color:#E49396; color:white;" colspan="2">Baru</th>
                        <th style="font-weight:bold; font-size:14px; text-align:center; background-color:#777B7E; color:white;" colspan="2">Revisi</th>
                        <th style="font-weight:bold; font-size:14px; text-align:center; background-color:#343A40; color:white;" colspan="2">Total</th>
                    </tr>
                    <tr>
                        <th style="font-weight:bold; font-size:14px; text-align:center;">Model</th>
                        <th style="font-weight:bold; font-size:14px; text-align:center;">Pcs</th>
                        <th style="font-weight:bold; font-size:14px; text-align:center;">Model</th>
                        <th style="font-weight:bold; font-size:14px; text-align:center;">Pcs</th>
                        <th style="font-weight:bold; font-size:14px; text-align:center;">Model</th>
                        <th style="font-weight:bold; font-size:14px; text-align:center;">Pcs</th>
                    </tr>
                </thead>
                <tbody>
            
                @forelse ($data4 as $databulan4)
                        <tr>
                            <td style="text-align:center; color:black;">{{ $databulan4->COUNTModel1 }}</td>
                            <td style="text-align:center; color:black;">{{ $databulan4->COUNTPcs1 }}</td>
                            <td style="text-align:center; color:black;">{{ $databulan4->COUNTModel2 }}</td>
                            <td style="text-align:center; color:black;">{{ $databulan4->COUNTPcs2 }}</td>
                            <td style="text-align:center; color:black;">{{ $databulan4->totalModel }}</td>
                            <td style="text-align:center; color:black;">{{ $databulan4->totalPcs }}</td>
                        </tr>
                    @empty
                        {{-- <div class="alert alert-danger">
                            Data Belum Tersedia.
                        </div> --}}
                    @endforelse
                </tbody>
            </table></td>
            <td><table class="table table-hover table-sm" style="width:100%;" border="1">
                <thead class="table-secondary sticky-top zindex-2">
                    <tr>
                        <th style="font-weight:bold; font-size:14px; text-align:center;" colspan="6">Mei</th>
                    </tr>
                    <tr>
                        <th style="font-weight:bold; font-size:14px; text-align:center; background-color:#E49396; color:white;" colspan="2">Baru</th>
                        <th style="font-weight:bold; font-size:14px; text-align:center; background-color:#777B7E; color:white;" colspan="2">Revisi</th>
                        <th style="font-weight:bold; font-size:14px; text-align:center; background-color:#343A40; color:white;" colspan="2">Total</th>
                    </tr>
                    <tr>
                        <th style="font-weight:bold; font-size:14px; text-align:center;">Model</th>
                        <th style="font-weight:bold; font-size:14px; text-align:center;">Pcs</th>
                        <th style="font-weight:bold; font-size:14px; text-align:center;">Model</th>
                        <th style="font-weight:bold; font-size:14px; text-align:center;">Pcs</th>
                        <th style="font-weight:bold; font-size:14px; text-align:center;">Model</th>
                        <th style="font-weight:bold; font-size:14px; text-align:center;">Pcs</th>
                    </tr>
                </thead>
                <tbody>
            
                @forelse ($data5 as $databulan5)
                        <tr>
                            <td style="text-align:center; color:black;">{{ $databulan5->COUNTModel1 }}</td>
                            <td style="text-align:center; color:black;">{{ $databulan5->COUNTPcs1 }}</td>
                            <td style="text-align:center; color:black;">{{ $databulan5->COUNTModel2 }}</td>
                            <td style="text-align:center; color:black;">{{ $databulan5->COUNTPcs2 }}</td>
                            <td style="text-align:center; color:black;">{{ $databulan5->totalModel }}</td>
                            <td style="text-align:center; color:black;">{{ $databulan5->totalPcs }}</td>
                        </tr>
                    @empty
                        {{-- <div class="alert alert-danger">
                            Data Belum Tersedia.
                        </div> --}}
                    @endforelse
                </tbody>
            </table></td>
            <td><table class="table table-hover table-sm" style="width:100%;" border="1">
                <thead class="table-secondary sticky-top zindex-2">
                    <tr>
                        <th style="font-weight:bold; font-size:14px; text-align:center;" colspan="6">Juni</th>
                    </tr>
                    <tr>
                        <th style="font-weight:bold; font-size:14px; text-align:center; background-color:#E49396; color:white;" colspan="2">Baru</th>
                        <th style="font-weight:bold; font-size:14px; text-align:center; background-color:#777B7E; color:white;" colspan="2">Revisi</th>
                        <th style="font-weight:bold; font-size:14px; text-align:center; background-color:#343A40; color:white;" colspan="2">Total</th>
                    </tr>
                    <tr>
                        <th style="font-weight:bold; font-size:14px; text-align:center;">Model</th>
                        <th style="font-weight:bold; font-size:14px; text-align:center;">Pcs</th>
                        <th style="font-weight:bold; font-size:14px; text-align:center;">Model</th>
                        <th style="font-weight:bold; font-size:14px; text-align:center;">Pcs</th>
                        <th style="font-weight:bold; font-size:14px; text-align:center;">Model</th>
                        <th style="font-weight:bold; font-size:14px; text-align:center;">Pcs</th>
                    </tr>
                </thead>
                <tbody>
            
                @forelse ($data6 as $databulan6)
                        <tr>
                            <td style="text-align:center; color:black;">{{ $databulan6->COUNTModel1 }}</td>
                            <td style="text-align:center; color:black;">{{ $databulan6->COUNTPcs1 }}</td>
                            <td style="text-align:center; color:black;">{{ $databulan6->COUNTModel2 }}</td>
                            <td style="text-align:center; color:black;">{{ $databulan6->COUNTPcs2 }}</td>
                            <td style="text-align:center; color:black;">{{ $databulan6->totalModel }}</td>
                            <td style="text-align:center; color:black;">{{ $databulan6->totalPcs }}</td>
                        </tr>
                    @empty
                        {{-- <div class="alert alert-danger">
                            Data Belum Tersedia.
                        </div> --}}
                    @endforelse
                </tbody>
            </table></td>
            <td><table class="table table-hover table-sm" style="width:100%;" border="1">
                <thead class="table-secondary sticky-top zindex-2">
                    <tr>
                        <th style="font-weight:bold; font-size:14px; text-align:center;" colspan="6">Juli</th>
                    </tr>
                    <tr>
                        <th style="font-weight:bold; font-size:14px; text-align:center; background-color:#E49396; color:white;" colspan="2">Baru</th>
                        <th style="font-weight:bold; font-size:14px; text-align:center; background-color:#777B7E; color:white;" colspan="2">Revisi</th>
                        <th style="font-weight:bold; font-size:14px; text-align:center; background-color:#343A40; color:white;" colspan="2">Total</th>
                    </tr>
                    <tr>
                        <th style="font-weight:bold; font-size:14px; text-align:center;">Model</th>
                        <th style="font-weight:bold; font-size:14px; text-align:center;">Pcs</th>
                        <th style="font-weight:bold; font-size:14px; text-align:center;">Model</th>
                        <th style="font-weight:bold; font-size:14px; text-align:center;">Pcs</th>
                        <th style="font-weight:bold; font-size:14px; text-align:center;">Model</th>
                        <th style="font-weight:bold; font-size:14px; text-align:center;">Pcs</th>
                    </tr>
                </thead>
                <tbody>
            
                @forelse ($data7 as $databulan7)
                        <tr>
                            <td style="text-align:center; color:black;">{{ $databulan7->COUNTModel1 }}</td>
                            <td style="text-align:center; color:black;">{{ $databulan7->COUNTPcs1 }}</td>
                            <td style="text-align:center; color:black;">{{ $databulan7->COUNTModel2 }}</td>
                            <td style="text-align:center; color:black;">{{ $databulan7->COUNTPcs2 }}</td>
                            <td style="text-align:center; color:black;">{{ $databulan7->totalModel }}</td>
                            <td style="text-align:center; color:black;">{{ $databulan7->totalPcs }}</td>
                        </tr>
                    @empty
                        {{-- <div class="alert alert-danger">
                            Data Belum Tersedia.
                        </div> --}}
                    @endforelse
                </tbody>
            </table></td>
            <td><table class="table table-hover table-sm" style="width:100%;" border="1">
                <thead class="table-secondary sticky-top zindex-2">
                    <tr>
                        <th style="font-weight:bold; font-size:14px; text-align:center;" colspan="6">Agustus</th>
                    </tr>
                    <tr>
                        <th style="font-weight:bold; font-size:14px; text-align:center; background-color:#E49396; color:white;" colspan="2">Baru</th>
                        <th style="font-weight:bold; font-size:14px; text-align:center; background-color:#777B7E; color:white;" colspan="2">Revisi</th>
                        <th style="font-weight:bold; font-size:14px; text-align:center; background-color:#343A40; color:white;" colspan="2">Total</th>
                    </tr>
                    <tr>
                        <th style="font-weight:bold; font-size:14px; text-align:center;">Model</th>
                        <th style="font-weight:bold; font-size:14px; text-align:center;">Pcs</th>
                        <th style="font-weight:bold; font-size:14px; text-align:center;">Model</th>
                        <th style="font-weight:bold; font-size:14px; text-align:center;">Pcs</th>
                        <th style="font-weight:bold; font-size:14px; text-align:center;">Model</th>
                        <th style="font-weight:bold; font-size:14px; text-align:center;">Pcs</th>
                    </tr>
                </thead>
                <tbody>
            
                @forelse ($data8 as $databulan8)
                        <tr>
                            <td style="text-align:center; color:black;">{{ $databulan8->COUNTModel1 }}</td>
                            <td style="text-align:center; color:black;">{{ $databulan8->COUNTPcs1 }}</td>
                            <td style="text-align:center; color:black;">{{ $databulan8->COUNTModel2 }}</td>
                            <td style="text-align:center; color:black;">{{ $databulan8->COUNTPcs2 }}</td>
                            <td style="text-align:center; color:black;">{{ $databulan8->totalModel }}</td>
                            <td style="text-align:center; color:black;">{{ $databulan8->totalPcs }}</td>
                        </tr>
                    @empty
                        {{-- <div class="alert alert-danger">
                            Data Belum Tersedia.
                        </div> --}}
                    @endforelse
                </tbody>
            </table></td>
            <td><table class="table table-hover table-sm" style="width:100%;" border="1">
                <thead class="table-secondary sticky-top zindex-2">
                    <tr>
                        <th style="font-weight:bold; font-size:14px; text-align:center;" colspan="6">September</th>
                    </tr>
                    <tr>
                        <th style="font-weight:bold; font-size:14px; text-align:center; background-color:#E49396; color:white;" colspan="2">Baru</th>
                        <th style="font-weight:bold; font-size:14px; text-align:center; background-color:#777B7E; color:white;" colspan="2">Revisi</th>
                        <th style="font-weight:bold; font-size:14px; text-align:center; background-color:#343A40; color:white;" colspan="2">Total</th>
                    </tr>
                    <tr>
                        <th style="font-weight:bold; font-size:14px; text-align:center;">Model</th>
                        <th style="font-weight:bold; font-size:14px; text-align:center;">Pcs</th>
                        <th style="font-weight:bold; font-size:14px; text-align:center;">Model</th>
                        <th style="font-weight:bold; font-size:14px; text-align:center;">Pcs</th>
                        <th style="font-weight:bold; font-size:14px; text-align:center;">Model</th>
                        <th style="font-weight:bold; font-size:14px; text-align:center;">Pcs</th>
                    </tr>
                </thead>
                <tbody>
            
                @forelse ($data9 as $databulan9)
                        <tr>
                            <td style="text-align:center; color:black;">{{ $databulan9->COUNTModel1 }}</td>
                            <td style="text-align:center; color:black;">{{ $databulan9->COUNTPcs1 }}</td>
                            <td style="text-align:center; color:black;">{{ $databulan9->COUNTModel2 }}</td>
                            <td style="text-align:center; color:black;">{{ $databulan9->COUNTPcs2 }}</td>
                            <td style="text-align:center; color:black;">{{ $databulan9->totalModel }}</td>
                            <td style="text-align:center; color:black;">{{ $databulan9->totalPcs }}</td>
                        </tr>
                    @empty
                        {{-- <div class="alert alert-danger">
                            Data Belum Tersedia.
                        </div> --}}
                    @endforelse
                </tbody>
            </table></td>
            <td><table class="table table-hover table-sm" style="width:100%;" border="1">
                <thead class="table-secondary sticky-top zindex-2">
                    <tr>
                        <th style="font-weight:bold; font-size:14px; text-align:center;" colspan="6">Oktober</th>
                    </tr>
                    <tr>
                        <th style="font-weight:bold; font-size:14px; text-align:center; background-color:#E49396; color:white;" colspan="2">Baru</th>
                        <th style="font-weight:bold; font-size:14px; text-align:center; background-color:#777B7E; color:white;" colspan="2">Revisi</th>
                        <th style="font-weight:bold; font-size:14px; text-align:center; background-color:#343A40; color:white;" colspan="2">Total</th>
                    </tr>
                    <tr>
                        <th style="font-weight:bold; font-size:14px; text-align:center;">Model</th>
                        <th style="font-weight:bold; font-size:14px; text-align:center;">Pcs</th>
                        <th style="font-weight:bold; font-size:14px; text-align:center;">Model</th>
                        <th style="font-weight:bold; font-size:14px; text-align:center;">Pcs</th>
                        <th style="font-weight:bold; font-size:14px; text-align:center;">Model</th>
                        <th style="font-weight:bold; font-size:14px; text-align:center;">Pcs</th>
                    </tr>
                </thead>
                <tbody>
            
                @forelse ($data10 as $databulan10)
                        <tr>
                            <td style="text-align:center; color:black;">{{ $databulan10->COUNTModel1 }}</td>
                            <td style="text-align:center; color:black;">{{ $databulan10->COUNTPcs1 }}</td>
                            <td style="text-align:center; color:black;">{{ $databulan10->COUNTModel2 }}</td>
                            <td style="text-align:center; color:black;">{{ $databulan10->COUNTPcs2 }}</td>
                            <td style="text-align:center; color:black;">{{ $databulan10->totalModel }}</td>
                            <td style="text-align:center; color:black;">{{ $databulan10->totalPcs }}</td>
                        </tr>
                    @empty
                        {{-- <div class="alert alert-danger">
                            Data Belum Tersedia.
                        </div> --}}
                    @endforelse
                </tbody>
            </table></td>
            <td><table class="table table-hover table-sm" style="width:100%;" border="1">
                <thead class="table-secondary sticky-top zindex-2">
                    <tr>
                        <th style="font-weight:bold; font-size:14px; text-align:center;" colspan="6">November</th>
                    </tr>
                    <tr>
                        <th style="font-weight:bold; font-size:14px; text-align:center; background-color:#E49396; color:white;" colspan="2">Baru</th>
                        <th style="font-weight:bold; font-size:14px; text-align:center; background-color:#777B7E; color:white;" colspan="2">Revisi</th>
                        <th style="font-weight:bold; font-size:14px; text-align:center; background-color:#343A40; color:white;" colspan="2">Total</th>
                    </tr>
                    <tr>
                        <th style="font-weight:bold; font-size:14px; text-align:center;">Model</th>
                        <th style="font-weight:bold; font-size:14px; text-align:center;">Pcs</th>
                        <th style="font-weight:bold; font-size:14px; text-align:center;">Model</th>
                        <th style="font-weight:bold; font-size:14px; text-align:center;">Pcs</th>
                        <th style="font-weight:bold; font-size:14px; text-align:center;">Model</th>
                        <th style="font-weight:bold; font-size:14px; text-align:center;">Pcs</th>
                    </tr>
                </thead>
                <tbody>
            
                @forelse ($data11 as $databulan11)
                        <tr>
                            <td style="text-align:center; color:black;">{{ $databulan11->COUNTModel1 }}</td>
                            <td style="text-align:center; color:black;">{{ $databulan11->COUNTPcs1 }}</td>
                            <td style="text-align:center; color:black;">{{ $databulan11->COUNTModel2 }}</td>
                            <td style="text-align:center; color:black;">{{ $databulan11->COUNTPcs2 }}</td>
                            <td style="text-align:center; color:black;">{{ $databulan11->totalModel }}</td>
                            <td style="text-align:center; color:black;">{{ $databulan11->totalPcs }}</td>
                        </tr>
                    @empty
                        {{-- <div class="alert alert-danger">
                            Data Belum Tersedia.
                        </div> --}}
                    @endforelse
                </tbody>
            </table></td>
            <td><table class="table table-hover table-sm" style="width:100%;" border="1">
                <thead class="table-secondary sticky-top zindex-2">
                    <tr>
                        <th style="font-weight:bold; font-size:14px; text-align:center;" colspan="6">Desember</th>
                    </tr>
                    <tr>
                        <th style="font-weight:bold; font-size:14px; text-align:center; background-color:#E49396; color:white;" colspan="2">Baru</th>
                        <th style="font-weight:bold; font-size:14px; text-align:center; background-color:#777B7E; color:white;" colspan="2">Revisi</th>
                        <th style="font-weight:bold; font-size:14px; text-align:center; background-color:#343A40; color:white;" colspan="2">Total</th>
                    </tr>
                    <tr>
                        <th style="font-weight:bold; font-size:14px; text-align:center;">Model</th>
                        <th style="font-weight:bold; font-size:14px; text-align:center;">Pcs</th>
                        <th style="font-weight:bold; font-size:14px; text-align:center;">Model</th>
                        <th style="font-weight:bold; font-size:14px; text-align:center;">Pcs</th>
                        <th style="font-weight:bold; font-size:14px; text-align:center;">Model</th>
                        <th style="font-weight:bold; font-size:14px; text-align:center;">Pcs</th>
                    </tr>
                </thead>
                <tbody>
            
                @forelse ($data12 as $databulan12)
                        <tr>
                            <td style="text-align:center; color:black;">{{ $databulan12->COUNTModel1 }}</td>
                            <td style="text-align:center; color:black;">{{ $databulan12->COUNTPcs1 }}</td>
                            <td style="text-align:center; color:black;">{{ $databulan12->COUNTModel2 }}</td>
                            <td style="text-align:center; color:black;">{{ $databulan12->COUNTPcs2 }}</td>
                            <td style="text-align:center; color:black;">{{ $databulan12->totalModel }}</td>
                            <td style="text-align:center; color:black;">{{ $databulan12->totalPcs }}</td>
                        </tr>
                    @empty
                        {{-- <div class="alert alert-danger">
                            Data Belum Tersedia.
                        </div> --}}
                    @endforelse
                </tbody>
            </table></td>
    </tr>
</table> 

</div>




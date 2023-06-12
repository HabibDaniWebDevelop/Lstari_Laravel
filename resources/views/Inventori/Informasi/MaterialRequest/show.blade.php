 <div class="table-responsive" style="height:calc(100vh - 380px);" >

    <table class="table table-bordered table-striped table-hover table-sm" style="zoom: 0.98;" id="tabel1">

        @if($no == '1')
            <thead class="table-secondary sticky-top zindex-2">
                <tr style="text-align: center">
                    <th>No</th>
                    <th>Urutan</th>   
                    <th>Tanggal</th>                          
                    <th>Divisi</th>
                    <th>Karyawan</th>
                    <th>Produk</th>
                    <th>Produk Non Stok</th>
                    <th>Qty</th>
                    <th>Unit</th>
                    <th>Proses</th> 
                    <th>Keperluan</th>
                    <th>Catatan</th> 
                    {{-- <th>Gambar</th> --}}
                </tr>
            </thead>
            <tbody class="text-center">
                @foreach ($datas as $data)
                    <tr>
                        <td>{{$data->ID}}</td>
                        <td>{{$data->Ordinal}}</td>
                        <td>{{$data->TransDate}}</td>                            
                        <td>{{$data->Department}}</td>
                        <td>{{$data->Employee}}</td>
                        <td>{{$data->Product}}</td>
                        <td>{{$data->ProductNote}}</td>                            
                        <td>{{$data->Qty}}</td> 
                        <td>{{$data->Unit}}</td>  
                        <td>{{$data->ForUse}}</td>                      
                        <td>{{$data->Purpose}}</td>
                        <td>{{$data->Note}}</td>
                        {{-- <td></td> --}}
                    </tr>
                @endforeach

            </tbody>

        @elseif($no == '2')
            <thead class="table-secondary sticky-top zindex-2 ">
                <tr style="text-align: center">
                    <th>No</th>
                    <th>Tanggal</th>                          
                    <th>Divisi</th>
                    <th>Karyawan</th>
                    <th>Produk</th>
                    <th>Urutan</th>   
                    <th>Produk Non Stok</th>
                    <th>Qty</th>
                    <th>Unit</th>
                    <th>Proses</th> 
                    <th>Keperluan</th>
                    <th>Catatan</th> 
                </tr>
            </thead>
            <tbody class="text-center">
                @foreach ($datas as $data)
                    <tr>
                        <td>{{$data->ID}}</td>
                        <td>{{$data->TransDate}}</td>                            
                        <td>{{$data->Department}}</td>
                        <td>{{$data->Employee}}</td>
                        <td>{{$data->Product}}</td>
                        <td>{{$data->Ordinal}}</td>
                        <td>{{$data->ProductNote}}</td>                            
                        <td>{{$data->Qty}}</td> 
                        <td>{{$data->Unit}}</td>  
                        <td>{{$data->ForUse}}</td>                      
                        <td>{{$data->Purpose}}</td>
                        <td>{{$data->Note}}</td>
                    </tr>
                @endforeach

            </tbody>

        @elseif($no == '3')
            <thead class="table-secondary sticky-top zindex-2 ">
                <tr style="text-align: center">
                    <th>No</th>
                    <th>Tanggal</th>                          
                    <th>Divisi</th>
                    <th>Karyawan</th>
                    <th>Produk</th>
                    <th>Urut</th>   
                    <th>Qty</th>
                    <th>Proses</th> 
                    <th>Keperluan</th>
                    <th>Catatan</th> 
                </tr>
            </thead>
            <tbody class="text-center">
                @foreach ($datas as $data)
                    <tr>
                        <td>{{$data->ID}}</td>
                        <td>{{$data->TransDate}}</td>                            
                        <td>{{$data->Department}}</td>
                        <td>{{$data->Employee}}</td>
                        <td>{{$data->Product}}</td>
                        <td>{{$data->Ordinal}}</td>                         
                        <td>{{$data->Qty}}</td> 
                        <td>{{$data->ForUse}}</td>                      
                        <td>{{$data->Purpose}}</td>
                        <td>{{$data->Note}}</td>   
                    </tr>
                @endforeach

            </tbody>

        @elseif($no == '4')
            <thead class="table-secondary sticky-top zindex-2 ">
                <tr style="text-align: center">
                    <th>No</th>
                    <th>Kode</th>                          
                    <th>Produk</th>
                    <th>Jenis Gudang</th>   
                    <th>Stock Minimal</th>
                    <th>Stock</th> 
                    <th>Satuan</th>
                </tr>
            </thead>
            <tbody class="text-center">
                @foreach ($datas as $data)
                    <tr>
                        <td>{{$loop->iteration}}</td>
                        <td>{{$data->SW}}</td>                            
                        <td>{{$data->Description}}</td>
                        <td>{{$data->ProdGroup}}</td>
                        <td>{{$data->MinStock}}</td>
                        <td>{{$data->Stock}}</td>                         
                        <td>{{$data->Unit}}</td>      
                    </tr>
                @endforeach
            </tbody>

        @elseif($no == '5')
            <thead class="table-secondary sticky-top zindex-2 ">
                <tr style="text-align: center">
                    <th>ID</th>
                    <th>Tanggal</th>                          
                    <th>Produk</th>
                    <th>Divisi</th>   
                    <th>Jenis</th>
                    <th>Karyawan</th> 
                    <th>Proses</th>
                    <th>Qty</th>
                    <th>Satuan</th>       
                </tr>
            </thead>
            <tbody class="text-center">
                @foreach ($datas as $data)
                    <tr>
                        <td>{{$data->ID}}</td>
                        <td>{{$data->TransDate}}</td>                            
                        <td>{{$data->Product}}</td>
                        <td>{{$data->Department}}</td>
                        <td>{{$data->ProdGroup}}</td>
                        <td>{{$data->Employee}}</td>                         
                        <td>{{$data->Operation}}</td>  
                        <td>{{$data->Qty}}</td>     
                        <td>{{$data->Unit}}</td> 
                    </tr>
                @endforeach
            </tbody>

        @elseif($no == '6')
            <thead class="table-secondary sticky-top zindex-2 ">
                <tr style="text-align: center">
                    <th>ID</th>
                    <th>Tanggal</th>                          
                    <th>Produk</th>
                    <th>Divisi</th>   
                    <th>Qty</th>
                    <th>Satuan</th>    
                    <th>Keterangan</th>    
                </tr>
            </thead>
            <tbody class="text-center">
                @foreach ($datas as $data)
                    <tr>
                        <td>{{$data->ID}}</td>
                        <td>{{$data->TransDate}}</td>                            
                        <td>{{$data->ProductNote}}</td>
                        <td>{{$data->Department}}</td>
                        <td>{{$data->Qty}}</td>
                        <td>{{$data->Unit}}</td>                         
                        <td>{{$data->MRNote}}</td>  
                    </tr>
                @endforeach
            </tbody>

         @elseif($no == '7')
            <thead class="table-secondary sticky-top zindex-2 ">
                <tr style="text-align: center">
                    <th>ID</th>
                    <th>Tanggal</th>                          
                    <th>Produk</th>
                    <th>Dari</th>   
                    <th>Ke</th>
                    <th>Qty</th>    
                    <th>Satuan</th>             
                </tr>
            </thead>
            <tbody class="text-center">
                @foreach ($datas as $data)
                    <tr>
                        <td>{{$data->ID}}</td>
                        <td>{{$data->TransDate}}</td>                            
                        <td>{{$data->Product}}</td>
                        <td>{{$data->FromLocation}}</td>
                        <td>{{$data->ToLocation}}</td>
                        <td>{{$data->Qty}}</td>                         
                        <td>{{$data->Unit}}</td>  
                    </tr>
                @endforeach
            </tbody>

        @endif

    </table>

    <script>
    var table = $('#tabel1').DataTable({
        "paging": false,
        "lengthChange": false,
        "searching": false,
        "ordering": true,
        "info": false,
        "autoWidth": true,
        "responsive": true,
        "fixedColumns": true,
        "order": [],
    });
</script>

@include('layouts.backend-Theme-3.XtraScript')
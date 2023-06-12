@php
    foreach ($data as $datas) {}
@endphp

<div class="row">
    <div class="col-md-4">                    
        {{-- 
            <button type="button" id="btnSimpan" name="btnSimpan" class="btn btn-info" onclick="simpan({{$idtm}})">Simpan</button>
            <button type="button" id="btnPosting" name="btnPosting" class="btn btn-inverse" onclick="aktivasi({{$idtm}})">Posting</button> 
        --}}
    </div> 
    <div class="col-md-4">
        
    </div>  
    <div class="col-md-4">
        <form id="header">
            <table style="width: 100%;">
                <tr class="header">
                    <td><label>ID Transfer Barang Jadi</label></td>
                    <td>:</td>
                    <td><input type="text" name="idtransferfg" id="idtransferfg" style="width:100%; border: 0 none; border-bottom: 1px solid black;" value="{{$idtm}}"> </td>
                </tr>
                <tr class="header">
                    <td><label>Nama Karyawan</label></td>
                    <td>:</td>
                    <td>
                        <select id="kary" name="kary" style="border: 0px none; border-bottom: 1px solid black; width: 100%;">
                            @foreach ($data4 as $datas4)
                                <option value="{{$datas4->ID}}">{{$datas4->SW}}</option>
                            @endforeach
                        </select>
                    </td>
                </tr>
                <tr class="header">
                    <td><label>Tanggal</label></td>
                    <td>:</td>
                    <td><input type="date" name="tglIDTG" id="tglIDTG" style="width:100%; border: 0 none; border-bottom: 1px solid black;" value="{{$datas->TransDate}}"></td>
                </tr>
            </table>
        </form>
    </div>
</div>

<div style="padding-left: 50px; padding-top: 15px;">
            
</div>

</br>

<form id="item">
    <table width="100%" border="1" id="tbl1">
        <thead style="background-color: #DCDCDC; color: black; text-align: center; font-weight: bold;">
            <tr>
                <td align="center">No</td>
                <td align="center">No SPK</td>
                <td align="center">Sub Kategori</td>
                <td align="center">Berat</td>
                <td align="center">Jumlah</td>
                <td align="center">Salin/Hapus</td>
            </tr>
        </thead>
        <tbody>
            @php
                $no = 1;
                $totalBerat = 0;
                $totalQty = 0;
            @endphp
            @foreach ($data3 as $datas3)
            @php
                $totalBerat += $datas3->Weight;
                $totalQty += $datas3->Qty;
            @endphp
            <tr id="myRow{{$no}}">
                <td align="center"></td>
                <td align="center">
                    <input type="text" style="width:100%; text-align: center; border: 0 none; background-color: #FCF3CF;" value="{{$datas3->NoSPK}}">  
                    <input type="hidden" id="idwo{{$no}}" name="idwo[]" value="{{$datas3->WorkOrder}}">   
                    <input type="hidden" id="idlinkord{{$datas3->Ordinal}}" name="idlinkord[]"  value="{{$datas3->Ordinal}}">
                    <input type="hidden" id="kadar{{$datas3->Ordinal}}" name="kadar[]"  value="{{$datas3->Carat}}">
                </td>
                <td align="center">
                    <input type="text" style="width:100%; text-align: center; border: 0 none; background-color: #FCF3CF;" id="produk{{$no}}" name="produk[]" value="{{$datas3->Model}}" onchange="getproduct('{{$datas3->Model}}', this.value, {{$no}})">
                    <input type="hidden" id="idmodel{{$no}}" name="idmodel[]" value="{{$datas3->IDProduk}}"> 
                </td>
                <td align="center"> 
                    <input type="text" style="width:100%; text-align: center; border: 0 none; background-color: #FCF3CF;" id="berat{{$no}}" name="berat[]" value="{{$datas3->Weight}}">    
                </td>
                <td align="center">
                    <input type="text" style="width:100%; text-align: center; border: 0 none; background-color: #FCF3CF;" id="qty{{$no}}" name="qty[]" value="{{$datas3->Qty}}">    
                </td>
                <td align="center">
                    <button type="button" onclick="copyrow({{$datas3->ID}},{{$datas3->Ordinal}})"><i class="fa fa-copy"></i></button>
                </td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="3" align="center" style="font: black; font-size: 20px"><b style="color: black;">Total :</b></td>
                <td align="center">
                    <input type="text" style="color: red; font-weight: bold; font-size: 20px; width:100%; border: 0 none; text-align: center;" value="{{$totalBerat}}">
                </td>
                <td align="center" style="color: red; font-weight: bold; font-size: 20px;">
                    <input type="text" style="color: red; font-weight: bold; font-size: 20px; width:100%; border: 0 none; text-align: center;" value="{{$totalQty}}">
                </td>
            </tr>
        </tfoot>
    </table>
</form>


<div class="row">
    <div class="col-md-8">
        <table width="100%" class="table" id="tampilitem">
            <thead bgcolor="#8FBC8F">
                <tr>
                    <th style="text-align:center; font-weight:bold; color:black">NTHKO</th>
                    <th style="text-align:center; font-weight:bold; color:black">Kategori</th>
                    <th style="text-align:center; font-weight:bold; color:black">Kode Produk</th>
                    <th style="text-align:center; font-weight:bold; color:black">Komponen</th>
                    <th style="text-align:center; font-weight:bold; color:black">Kadar</th>
                    <th style="text-align:center; font-weight:bold; color:black">Qty</th>
                    <th style="text-align:center; font-weight:bold; color:black">Weight</th>
                </tr>			  		
            </thead>
            <tbody>
                @foreach ($data2 as $datas2)
                <tr>
                    <td align="center">{{$datas2->NTKHO}}</td>
                    <td align="center">{{$datas2->Kategori}}</td>
                    <td align="center">{{$datas2->FG}}</td>
                    <td align="center">{{$datas2->Komponen}}</td>
                    <td align="center">{{$datas2->Kadar}}</td>
                    <td align="center">{{$datas2->Qty}}</td>
                    <td align="center">{{$datas2->Weight}}</td>
                    {{-- <form id="dataitem">
                        <input type="hidden" name="prod[]" value="{{$datas2->verid}}"> 
                        <input type="hidden" name="idm[]" value="{{$datas2->IDM}}">  
                        <input type="hidden" name="ordinal[]" value="{{$datas2->Ordinal}}">  
                        <input type="hidden" name="qtypcs[]" value="{{$datas2->Qtycin}}">    
                        <input type="hidden" name="brtpcs[]" value="{{$datas2->Weight}}">    
                    </form>	 --}}
                 </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="col-md-4">
        <table width="100%" class="table" id="tampiltotal">
            <thead bgcolor="#8FBC8F">
                <tr>
                    <th style="text-align:center; font-weight:bold; color:black">Komponen</th>
                    <th style="text-align:center; font-weight:bold; color:black">Kadar</th>
                    <th style="text-align:center; font-weight:bold; color:black">Total Qty</th>
                    <th style="text-align:center; font-weight:bold; color:black">Total Weight</th>
                </tr>			  		
            </thead>
            <tbody>
                @php
                    $totqty = 0;
			  		$totbrt = 0;
                @endphp
                @foreach ($data3 as $datas3)
                @php
                    $totqty += $datas3->jml;
                    $totbrt += $datas3->brt;
                @endphp
                <tr>
                    <td align="center">{{$datas3->SW}}</td> 
                    <td align="center">{{$datas3->kadar}}</td>
                    <td align="center">{{$datas3->jml}}</td>
                    <td align="center">{{$datas3->brt}}</td>
                 </tr>
                @endforeach
            </tbody>
                <tr>
                    <td align="right" style="font-weight: bold" colspan="2">Grand Total</td>
                    <td align="center" style="font-weight: bold">{{$totqty}}</td>
                    <td align="center" style="font-weight: bold">{{$totbrt}}</td>
                    {{-- <form id="datatotal">
                        <input type="hidden" name="jml" id="jml" value={{$totqty}}>
                        <input type="hidden" name="brt" id="brt" value={{$totbrt}}>
                    </form> --}}
                </tr>
        </table>			  
    </div>		  
</div>
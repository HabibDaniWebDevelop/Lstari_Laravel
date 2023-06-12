@if($location == 50)

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
                    @foreach ($data as $datas)
                    <tr>
                        <td align="center">{{$datas->NTKHO}}</td>
                        <td align="center">{{$datas->Kateg}}</td>
                        <td align="center">{{$datas->SW}}</td>
                        <td align="center">{{$datas->kom}}</td>
                        <td align="center">{{$datas->kadar}}</td>
                        <td align="center">{{$datas->Qtycin}}</td>
                        <td align="center">{{$datas->Weight}}
                            <form id="dataitem">
                                <input type="hidden" name="prod[]" value="{{$datas->verid}}"> 
                                <input type="hidden" name="idm[]" value="{{$datas->IDM}}">  
                                <input type="hidden" name="acc[]" value="{{$datas->ACC}}">
                                <input type="hidden" name="ordinal[]" value="{{$datas->Ordinal}}">  
                                <input type="hidden" name="qtypcs[]" value="{{$datas->Qtycin}}">    
                                <input type="hidden" name="brtpcs[]" value="{{$datas->Weight}}">  
                                <input type="hidden" name="caratpcs[]" value="{{$datas->Carat}}">
                                <input type="hidden" name="wkorder[]" value="{{$datas->WorkOrder}}">
                                <input type="hidden" name="fg[]" value="{{$datas->IDFG}}">
                            </form>	
                            <form id="dataitem2">
                                <input type="hidden" name="qtyorder[]">
                            </form
                        </td>
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
                    @foreach ($data2 as $datas2)
                    @php
                        $totqty += $datas2->jml;
                        $totbrt += $datas2->brt;
                    @endphp
                    <tr>
                        <td align="center">{{$datas2->SW}}</td> 
                        <td align="center">{{$datas2->kadar}}</td>
                        <td align="center">{{$datas2->jml}}</td>
                        <td align="center">{{$datas2->brt}}</td>
                    </tr>
                    @endforeach
                </tbody>
                    <tr>
                        <td align="right" style="font-weight: bold" colspan="2">Grand Total</td>
                        <td align="center" style="font-weight: bold">{{$totqty}}</td>
                        <td align="center" style="font-weight: bold">{{$totbrt}}
                            <form id="datatotal">
                                <input type="hidden" name="jml" id="jml" value={{$totqty}}>
                                <input type="hidden" name="brt" id="brt" value={{$totbrt}}>
                            </form>
                        </td>
                    </tr>
            </table>			  
        </div>		  
    </div>

@else

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
                        <th style="text-align:center; font-weight:bold; color:black">Qty Order</th>
                        <th style="text-align:center; font-weight:bold; color:black">Qty Order Value</th>
                        <th style="text-align:center; font-weight:bold; color:black">Weight</th>
                    </tr>			  		
                </thead>
                <tbody>
                    @php $no=1 @endphp
                    @foreach ($data as $datas)
                    <tr>
                        <td align="center">{{$datas->NTKHO}}</td>
                        <td align="center">{{$datas->Kateg}}</td>
                        <td align="center">{{$datas->SW}}</td>
                        <td align="center">{{$datas->kom}}</td>
                        <td align="center">{{$datas->kadar}}</td>
                        @if(in_array($datas->Ordinal,$arrOrdinal) && in_array($datas->verid,$arrPID) && in_array($datas->LinkID,$arrWO)) 
                            <td align="center">{{$datas->Qtycin}}+4</td>
                        @else
                            <td align="center">{{$datas->Qtycin}}</td>
                        @endif
                        
                        <td align="center" style="width: 100px;">
                            <form id="dataitem2">
                                <input class="form-control" type="text" autocomplete="off" style="text-align: center; color:black; font-size: 13px; font-weight: bold" id="qtyorder{{$no}}" name="qtyorder[]" onchange="refresh_sum_qty({{$no}})">
                            </form>
                        </td>
                        <td>0</td>
                        <td align="center">{{$datas->Weight}}
                            <form id="dataitem">
                                <input type="hidden" name="prod[]" value="{{$datas->verid}}"> 
                                <input type="hidden" name="idm[]" value="{{$datas->IDM}}">  
                                <input type="hidden" name="ordinal[]" value="{{$datas->Ordinal}}">  
                                @if(in_array($datas->Ordinal,$arrOrdinal) && in_array($datas->verid,$arrPID) && in_array($datas->LinkID,$arrWO)) 
                                    <input type="hidden" name="qtypcs[]" value="{{$datas->Qtycin+4}}">  
                                @else
                                    <input type="hidden" name="qtypcs[]" value="{{$datas->Qtycin}}">  
                                @endif 
                                <input type="hidden" name="brtpcs[]" value="{{$datas->Weight}}">  
                                <input type="hidden" name="caratpcs[]" value="{{$datas->Carat}}">
                                <input type="hidden" name="wkorder[]" value="{{$datas->WorkOrder}}">
                                <input type="hidden" id="qtyorder2{{$no}}" name="qtyorder2[]" value="">
                            </form>
                        </td>
                    </tr>
                    @php $no++ @endphp
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
                    @foreach ($data2 as $datas2)
                    @php
                        $totqty += $datas2->jml;
                        $totbrt += $datas2->brt;
                    @endphp
                    <tr>
                        <td align="center">{{$datas2->SW}}</td> 
                        <td align="center">{{$datas2->kadar}}</td>
                        @if(in_array($datas2->PID, $arrPID))
                            @for($i=0; $i<count($arrPID); $i++)
                                @if($datas2->PID == $arrPID[$i])
                                    @php $tambah = $arrQtyFix[$i]; @endphp
                                    <td align="center">{{$datas2->jml}}+{{$tambah}}</td>
                                @endif
                            @endfor
						@else
                            <td align="center">{{$datas2->jml}}</td>
						@endif
                        <td align="center">{{$datas2->brt}}</td>
                    </tr>
                    @endforeach
                </tbody>
                    <tr>
                        <td align="right" style="font-weight: bold" colspan="2">Grand Total</td>
                        <td align="center" style="font-weight: bold" id="jmlLabel">{{$totqty + $jmlqty}}</td>
                        <td align="center" style="font-weight: bold" id="brtLabel">{{$totbrt}}
                            <form id="datatotal">
                                <input type="hidden" name="jml" id="jml" value={{$totqty + $jmlqty}}>
                                <input type="hidden" name="brt" id="brt" value={{$totbrt}}>
                            </form>
                        </td>
                    </tr>
            </table>	
            <br>
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
                    @foreach ($data2 as $datas2)
                    @php
                        $totqty += $datas2->jml;
                        $totbrt += $datas2->brt;
                    @endphp
                    <tr>
                        <td align="center">{{$datas2->SW}}</td> 
                        <td align="center">{{$datas2->kadar}}</td>
                        @if(in_array($datas2->PID, $arrPID))
                            @for($i=0; $i<count($arrPID); $i++)
                                @if($datas2->PID == $arrPID[$i])
                                    @php $tambah = $arrQtyFix[$i]; @endphp
                                    <td align="center">{{$datas2->jml}}+{{$tambah}}</td>
                                @endif
                            @endfor
						@else
                            <td align="center">{{$datas2->jml}}</td>
						@endif
                        <td align="center">{{$datas2->brt}}</td>
                    </tr>
                    @endforeach
                </tbody>
                    <tr>
                        <td align="right" style="font-weight: bold" colspan="2">Grand Total Test</td>
                        <td align="center" style="font-weight: bold" id="jmlOrderLabel">0</td>
                        <td align="center" style="font-weight: bold" id="brtOrderLabel">{{$totbrt}}
                            {{-- <form id="datatotal"> --}}
                                <input type="hidden" name="jmlOrder" id="jmlOrder" value="">
                                <input type="hidden" name="brtOrder" id="brtOrder" value={{$totbrt}}>
                            {{-- </form> --}}
                        </td>
                    </tr>
            </table>	
        </div>		  
    </div>

@endif




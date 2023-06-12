@if($jenis == 'detailNTHKO')

<table class="table text-nowrap" id="tampiltabel" style="width: 100%">
    <thead>
        <tr bgcolor="#111111">
            <th class="text-center" style="font-size: 10px; font-weight: bold; color: white; text-transform: capitalize">Urut</th>
            <th class="text-center" style="font-size: 10px; font-weight: bold; color: white; text-transform: capitalize; width: 150px">No SPK</th>
            <th class="text-center" style="font-size: 10px; font-weight: bold; color: white; text-transform: capitalize">Produk SPK</th>
            <th class="text-center" style="font-size: 10px; font-weight: bold; color: white; text-transform: capitalize">Jml SPK</th>
            <th class="text-center" style="font-size: 10px; font-weight: bold; color: white; text-transform: capitalize; width: 200px">Barang</th>
            <th class="text-center" style="font-size: 10px; font-weight: bold; color: white; text-transform: capitalize; width: 70px;">Kadar</th>
            <th class="text-center" style="font-size: 10px; font-weight: bold; color: white; text-transform: capitalize">Jml OK</th> 
            <th class="text-center" style="font-size: 10px; font-weight: bold; color: white; text-transform: capitalize">Brt OK</th>
            <th class="text-center" style="font-size: 10px; font-weight: bold; color: white; text-transform: capitalize">Jml Rep</th> 
            <th class="text-center" style="font-size: 10px; font-weight: bold; color: white; text-transform: capitalize">Brt Rep</th> 
            <th class="text-center" style="font-size: 10px; font-weight: bold; color: white; text-transform: capitalize">Jml SS</th> 
            <th class="text-center" style="font-size: 10px; font-weight: bold; color: white; text-transform: capitalize">Brt SS</th> 
            <th class="text-center" style="font-size: 10px; font-weight: bold; color: white; text-transform: capitalize">Jml Batu Pecah</th>
            <th class="text-center" style="font-size: 10px; font-weight: bold; color: white; text-transform: capitalize">Jml Batu Lepas</th> 
            <th class="text-center" style="font-size: 10px; font-weight: bold; color: white; text-transform: capitalize">Brt Brg</th> 
            <th class="text-center" style="font-size: 10px; font-weight: bold; color: white; text-transform: capitalize">Brt Air</th> 
            <th class="text-center" style="font-size: 10px; font-weight: bold; color: white; text-transform: capitalize">Brt Jenis</th> 
            <th class="text-center" style="font-size: 10px; font-weight: bold; color: white; text-transform: capitalize">Barcode Note</th> 
            <th class="text-center" style="font-size: 10px; font-weight: bold; color: white; text-transform: capitalize">Note</th> 
            <th class="text-center" style="font-size: 10px; font-weight: bold; color: white; text-transform: capitalize">No Pohon</th> 
            <th class="text-center" style="font-size: 10px; font-weight: bold; color: white; text-transform: capitalize; width: 450px">Produk Detail</th> 
            <th class="text-center" style="font-size: 10px; font-weight: bold; color: white; text-transform: capitalize">SPKO ID</th> 
            <th class="text-center" style="font-size: 10px; font-weight: bold; color: white; text-transform: capitalize">SPKO Urut</th> 
            <th class="text-center" style="font-size: 10px; font-weight: bold; color: white; text-transform: capitalize">Pohon ID</th> 
            <th class="text-center" style="font-size: 10px; font-weight: bold; color: white; text-transform: capitalize">Pohon Urut</th> 
            <th class="text-center" style="font-size: 10px; font-weight: bold; color: white; text-transform: capitalize; width: 150px">Batch</th> 
        </tr>       
    </thead>
    <tbody>
        @foreach ($data as $datas)
        <tr onclick="tampilGambar('{{ $datas->GPhoto }}')">
            <td class="m-0 p-1" align="center" style="font-size: 12px; font-weight: bold; color: black;">{{ $loop->iteration }}</td>
            <td class="m-0 p-1" align="center" style="font-size: 12px; font-weight: bold; color: black;">{{ $datas->OSW }}</td>
            <td class="m-0 p-1" align="center" style="font-size: 12px; font-weight: bold; color: black;">{{ $datas->FDescription }}</td>
            <td class="m-0 p-1" align="center" style="font-size: 12px; font-weight: bold; color: black;">{{ $datas->QtyOrder }}</td>
            <td class="m-0 p-1" align="center" style="font-size: 12px; font-weight: bold; color: black;">{{ $datas->PDescription }}</td>
            <td class="m-0 p-1" align="center" style="font-size: 12px; font-weight: bold; color: black;">{{ $datas->FCarat }}</td>
            <td class="m-0 p-1" align="center" style="font-size: 12px; font-weight: bold; color: black;">{{ $datas->Qty }}</td>
            <td class="m-0 p-1" align="center" style="font-size: 12px; font-weight: bold; color: black;">{{ $datas->Weight }}</td>
            <td class="m-0 p-1" align="center" style="font-size: 12px; font-weight: bold; color: black;">{{ $datas->RepairQty }}</td>
            <td class="m-0 p-1" align="center" style="font-size: 12px; font-weight: bold; color: black;">{{ $datas->RepairWeight }}</td>
            <td class="m-0 p-1" align="center" style="font-size: 12px; font-weight: bold; color: black;">{{ $datas->ScrapQty }}</td>
            <td class="m-0 p-1" align="center" style="font-size: 12px; font-weight: bold; color: black;">{{ $datas->ScrapWeight }}</td>
            <td class="m-0 p-1" align="center" style="font-size: 12px; font-weight: bold; color: black;">{{ $datas->StoneLoss }}</td>
            <td class="m-0 p-1" align="center" style="font-size: 12px; font-weight: bold; color: black;">{{ $datas->QtyLossStone }}</td>
            <td class="m-0 p-1" align="center" style="font-size: 12px; font-weight: bold; color: black;"></td>
            <td class="m-0 p-1" align="center" style="font-size: 12px; font-weight: bold; color: black;"></td>
            <td class="m-0 p-1" align="center" style="font-size: 12px; font-weight: bold; color: black;"></td>
            <td class="m-0 p-1" align="center" style="font-size: 12px; font-weight: bold; color: black;">{{ $datas->BarcodeNote }}</td>
            <td class="m-0 p-1" align="center" style="font-size: 12px; font-weight: bold; color: black;">{{ $datas->Note }}</td>
            <td class="m-0 p-1" align="center" style="font-size: 12px; font-weight: bold; color: black;">{{ $datas->ZSW }}</td>
            <td class="m-0 p-1" align="center" style="font-size: 12px; font-weight: bold; color: black;">{{ $datas->GDescription }}</td>
            <td class="m-0 p-1" align="center" style="font-size: 12px; font-weight: bold; color: black;">{{ $datas->LinkID }}</td>
            <td class="m-0 p-1" align="center" style="font-size: 12px; font-weight: bold; color: black;">{{ $datas->LinkOrd }}</td>
            <td class="m-0 p-1" align="center" style="font-size: 12px; font-weight: bold; color: black;">{{ $datas->TreeID }}</td>
            <td class="m-0 p-1" align="center" style="font-size: 12px; font-weight: bold; color: black;">{{ $datas->TreeOrd }}</td>
            <td class="m-0 p-1" align="center" style="font-size: 12px; font-weight: bold; color: black;">{{ $datas->BatchNo }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

@elseif($jenis == 'detailSPKO')

<table class="table text-nowrap" id="tampiltabel" style="width: 100%">
    <thead>
        <tr bgcolor="#111111">
            <th align="center" style="font-size: 10px; font-weight: bold; color: white; text-align: center; text-transform: capitalize">Freq</th>
            <th align="center" style="font-size: 10px; font-weight: bold; color: white; text-align: center; text-transform: capitalize">Keperluan</th>
            <th align="center" style="font-size: 10px; font-weight: bold; color: white; text-align: center; text-transform: capitalize">Tanggal</th>
            <th align="center" style="font-size: 10px; font-weight: bold; color: white; text-align: center; text-transform: capitalize">Urut</th>
            <th align="center" style="font-size: 10px; font-weight: bold; color: white; text-align: center; text-transform: capitalize">Produk</th>
            <th align="center" style="font-size: 10px; font-weight: bold; color: white; text-align: center; text-transform: capitalize">Kadar</th>
            <th align="center" style="font-size: 10px; font-weight: bold; color: white; text-align: center; text-transform: capitalize">Jumlah</th>
            <th align="center" style="font-size: 10px; font-weight: bold; color: white; text-align: center; text-transform: capitalize">Berat</th>
            <th align="center" style="font-size: 10px; font-weight: bold; color: white; text-align: center; text-transform: capitalize">Jml Pecah</th>
            <th align="center" style="font-size: 10px; font-weight: bold; color: white; text-align: center; text-transform: capitalize">Jml Lepas</th>
            <th align="center" style="font-size: 10px; font-weight: bold; color: white; text-align: center; text-transform: capitalize">No SPK</th>
            <th align="center" style="font-size: 10px; font-weight: bold; color: white; text-align: center; text-transform: capitalize">Barang Jadi</th>
            <th align="center" style="font-size: 10px; font-weight: bold; color: white; text-align: center; text-transform: capitalize">Aktif</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($data2 as $datas2)
        @php $Tgl = date('d/m/y', strtotime($datas2->TransDate)); @endphp
        <tr>
            <td class="m-0 p-1" align="center" style="font-size: 12px; font-weight: bold; color: black">{{$datas2->Freq}}</td>
            <td class="m-0 p-1" align="center" style="font-size: 12px; font-weight: bold; color: black">{{$datas2->Purpose}}</td>
            <td class="m-0 p-1" align="center" style="font-size: 12px; font-weight: bold; color: black">{{$Tgl}}</td>
            <td class="m-0 p-1" align="center" style="font-size: 12px; font-weight: bold; color: black">{{$datas2->Ordinal}}</td>
            <td class="m-0 p-1" align="center" style="font-size: 12px; font-weight: bold; color: black">{{$datas2->PDescription}}</td>
            <td class="m-0 p-1" align="center" style="font-size: 12px; font-weight: bold; color: black">{{$datas2->CSW}}</td>
            <td class="m-0 p-1" align="center" style="font-size: 12px; font-weight: bold; color: black">{{$datas2->Qty}}</td>
            <td class="m-0 p-1" align="center" style="font-size: 12px; font-weight: bold; color: black">{{$datas2->Weight}}</td>
            <td class="m-0 p-1" align="center" style="font-size: 12px; font-weight: bold; color: black">{{$datas2->StoneLoss}}</td>
            <td class="m-0 p-1" align="center" style="font-size: 12px; font-weight: bold; color: black">{{$datas2->QtyLossStone}}</td>
            <td class="m-0 p-1" align="center" style="font-size: 12px; font-weight: bold; color: black">{{$datas2->OSW}}</td>
            <td class="m-0 p-1" align="center" style="font-size: 12px; font-weight: bold; color: black">{{$datas2->FDescription}}</td>
            <td class="m-0 p-1" align="center" style="font-size: 12px; font-weight: bold; color: black">{{$datas2->Active}}</td>
        </tr>
        @endforeach
    </tbody>
</table>

@elseif($jenis == 'detailSPK')

<table class="table " id="tampiltabel" style="max-width: 100%">
    <thead>
        <tr hidden>
            <th width="4.16%"></th>
            <th width="4.16%"></th>
            <th width="4.16%"></th>
            <th width="4.16%"></th>
            <th width="4.16%"></th>
            <th width="4.16%"></th>
            <th width="4.16%"></th>
            <th width="4.16%"></th>
            <th width="4.16%"></th>
            <th width="4.16%"></th>
            <th width="4.16%"></th>
            <th width="4.16%"></th>
            <th width="4.16%"></th>
            <th width="4.16%"></th>
            <th width="4.16%"></th>
            <th width="4.16%"></th>
            <th width="4.16%"></th>
            <th width="4.16%"></th>
            <th width="4.16%"></th>
            <th width="4.16%"></th>
            <th width="4.16%"></th>
            <th width="4.16%"></th>
            <th width="4.16%"></th>
            <th width="4.16%"></th>	
        </tr>    	
        <tr bgcolor="#111111">
            <th align="center" style="font-size: 10px; font-weight: bold; color: white; text-align: center; text-transform: capitalize" colspan="4">SPK PPIC</th> 
            <th align="center" style="font-size: 10px; font-weight: bold; color: white; text-align: center; text-transform: capitalize" colspan="4">Jumlah SPKO</th>
            <th align="center" style="font-size: 10px; font-weight: bold; color: white; text-align: center; text-transform: capitalize" colspan="4">Berat SPKO</th>
            <th align="center" style="font-size: 10px; font-weight: bold; color: white; text-align: center; text-transform: capitalize" colspan="4">Jumlah NTHKO</th>
            <th align="center" style="font-size: 10px; font-weight: bold; color: white; text-align: center; text-transform: capitalize" colspan="4">Berat NTHKO</th>
            <th align="center" style="font-size: 10px; font-weight: bold; color: white; text-align: center; text-transform: capitalize" colspan="4">Selisih</th>
        </tr>
        <tr bgcolor="#111111">
            <th align="center" style="font-size: 10px; font-weight: bold; color: white; text-align: center; text-transform: capitalize" colspan="2">No SPK</th>
            <th align="center" style="font-size: 10px; font-weight: bold; color: white; text-align: center; text-transform: capitalize" colspan="2">Produk</th>
            <th align="center" style="font-size: 10px; font-weight: bold; color: white; text-align: center; text-transform: capitalize" colspan="1">Posting</th>
            <th align="center" style="font-size: 10px; font-weight: bold; color: white; text-align: center; text-transform: capitalize" colspan="1">Aktif</th>
            <th align="center" style="font-size: 10px; font-weight: bold; color: white; text-align: center; text-transform: capitalize" colspan="2">Total</th>
            <th align="center" style="font-size: 10px; font-weight: bold; color: white; text-align: center; text-transform: capitalize" colspan="1">Posting</th>
            <th align="center" style="font-size: 10px; font-weight: bold; color: white; text-align: center; text-transform: capitalize" colspan="1">Aktif</th>
            <th align="center" style="font-size: 10px; font-weight: bold; color: white; text-align: center; text-transform: capitalize" colspan="2">Total</th>
            <th align="center" style="font-size: 10px; font-weight: bold; color: white; text-align: center; text-transform: capitalize" colspan="1">Posting</th>
            <th align="center" style="font-size: 10px; font-weight: bold; color: white; text-align: center; text-transform: capitalize" colspan="1">Aktif</th>
            <th align="center" style="font-size: 10px; font-weight: bold; color: white; text-align: center; text-transform: capitalize" colspan="2">Total</th>
            <th align="center" style="font-size: 10px; font-weight: bold; color: white; text-align: center; text-transform: capitalize" colspan="1">Posting</th>
            <th align="center" style="font-size: 10px; font-weight: bold; color: white; text-align: center; text-transform: capitalize" colspan="1">Aktif</th>
            <th align="center" style="font-size: 10px; font-weight: bold; color: white; text-align: center; text-transform: capitalize" colspan="2">Total</th>
            <th align="center" style="font-size: 10px; font-weight: bold; color: white; text-align: center; text-transform: capitalize" colspan="2">Jumlah</th>
            <th align="center" style="font-size: 10px; font-weight: bold; color: white; text-align: center; text-transform: capitalize" colspan="2">Berat</th>
        </tr>
    </thead>
    <tbody>
        <tr hidden>
            <td width="4.16%"></td>
            <td width="4.16%"></td>
            <td width="4.16%"></td>
            <td width="4.16%"></td>
            <td width="4.16%"></td>
            <td width="4.16%"></td>
            <td width="4.16%"></td>
            <td width="4.16%"></td>
            <td width="4.16%"></td>
            <td width="4.16%"></td>
            <td width="4.16%"></td>
            <td width="4.16%"></td>
            <td width="4.16%"></td>
            <td width="4.16%"></td>
            <td width="4.16%"></td>
            <td width="4.16%"></td>
            <td width="4.16%"></td>
            <td width="4.16%"></td>
            <td width="4.16%"></td>
            <td width="4.16%"></td>
            <td width="4.16%"></td>
            <td width="4.16%"></td>
            <td width="4.16%"></td>
            <td width="4.16%"></td>	
        </tr>  
        @foreach ($data as $datas)
        <tr>
            <td class="m-0 p-1" align="center" style="font-size: 12px; font-weight: bold; color: black" colspan="2">{{$datas->SW}}</td>
            <td class="m-0 p-1" align="center" style="font-size: 12px; font-weight: bold; color: black" colspan="2">{{$datas->Production}}</td>
            <td class="m-0 p-1" align="center" style="font-size: 12px; font-weight: bold; color: black" colspan="1">{{$datas->QtyAllocation}}</td>
            <td class="m-0 p-1" align="center" style="font-size: 12px; font-weight: bold; color: black" colspan="1">{{$datas->QtyAllocationActive}}</td>
            <td class="m-0 p-1" align="center" style="font-size: 12px; font-weight: bold; color: black" colspan="2">{{$datas->QtyAllocationTotal}}</td>
            <td class="m-0 p-1" align="center" style="font-size: 12px; font-weight: bold; color: black" colspan="1">{{$datas->WeightAllocation}}</td>
            <td class="m-0 p-1" align="center" style="font-size: 12px; font-weight: bold; color: black" colspan="1">{{$datas->WeightAllocationActive}}</td>
            <td class="m-0 p-1" align="center" style="font-size: 12px; font-weight: bold; color: black" colspan="2">{{$datas->WeightAllocationTotal}}</td>
            <td class="m-0 p-1" align="center" style="font-size: 12px; font-weight: bold; color: black" colspan="1">{{$datas->QtyCompletion}}</td>
            <td class="m-0 p-1" align="center" style="font-size: 12px; font-weight: bold; color: black" colspan="1">{{$datas->QtyCompletionActive}}</td>
            <td class="m-0 p-1" align="center" style="font-size: 12px; font-weight: bold; color: black" colspan="2">{{$datas->QtyCompletionTotal}}</td>
            <td class="m-0 p-1" align="center" style="font-size: 12px; font-weight: bold; color: black" colspan="1">{{$datas->WeightCompletion}}</td>
            <td class="m-0 p-1" align="center" style="font-size: 12px; font-weight: bold; color: black" colspan="1">{{$datas->WeightCompletionActive}}</td>
            <td class="m-0 p-1" align="center" style="font-size: 12px; font-weight: bold; color: black" colspan="2">{{$datas->WeightCompletionTotal}}</td>
            <td class="m-0 p-1" align="center" style="font-size: 12px; font-weight: bold; color: black" colspan="2">{{number_format($datas->QtyDifference,2)}}</td>
            <td class="m-0 p-1" align="center" style="font-size: 12px; font-weight: bold; color: black" colspan="2">{{number_format($datas->WeightDifference,2)}}</td>
        </tr>
        @endforeach
    </tbody>
</table>

@endif

<script type="text/javascript">
    var table = $('#tampiltabel').DataTable({
        ordering: false,
        paging: true,
        pageLength: 100,
        searching: true,
        lengthChange: true,
        scrollX: true,
        scroller: true,
    });
</script>
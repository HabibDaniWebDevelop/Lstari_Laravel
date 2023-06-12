<table width="100%" border="1" class="table text-nowrap" id="tampilstokCB">
    <thead>
        <tr bgcolor="#708090">
            <td align="center" style="font-weight: bold; color: white" width="5%">No</td>
            <td align="center" style="font-weight: bold; color: white" width="15%">Kode</td>
            <td align="center" style="font-weight: bold; color: white" width="40%">Nama</td>
            <td align="center" style="font-weight: bold; color: white" width="40%">Kadar</td>
            <td align="center" style="font-weight: bold; color: white" width="10%">Jumlah</td>
            <td align="center" style="font-weight: bold; color: white" width="10%">Berat</td>
        </tr>
    </thead>
    <tbody>
        @foreach ($data as $datas)
        <tr>
            <td align="center">{{$loop->iteration}}</td>
            <td align="center">{{$datas->Kode}}</td>
            <td align="center">{{$datas->Nama}}</td>
            <td align="center">{{$datas->Kadar}}</td>
            <td align="center">{{$datas->Jumlah}}</td>
            <td align="center">{{$datas->Berat}}</td>
        </tr>
        @endforeach
    </tbody>
</table>
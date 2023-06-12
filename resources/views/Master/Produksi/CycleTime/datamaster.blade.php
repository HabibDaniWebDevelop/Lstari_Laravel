<div class="col-md-2">
    <select class="form-select" id="ctrl-show-active" style="margin-bottom: 10px">
        <option value="all" selected>Show All</option>
        <option value="active">Show Active</option>
        <option value="not-active">Show Not Active</option>
    </select>
</div>

<table width="100%" border="1" class="table text-nowrap" id="tampiltabel">
    <thead>
        <tr bgcolor="#708090">
            <td align="center" style="font-weight: bold; color: white">No</td>
            <td align="center" style="font-weight: bold; color: white">Location</td>
            <td align="center" style="font-weight: bold; color: white">Operation</td>
            <td align="center" style="font-weight: bold; color: white">Category</td>
            <td align="center" style="font-weight: bold; color: white">SubCategory</td>
            <td align="center" style="font-weight: bold; color: white">Model</td>
            <td align="center" style="font-weight: bold; color: white">Operator</td>
            <td align="center" style="font-weight: bold; color: white">Valid Date</td>
            <td align="center" style="font-weight: bold; color: white">CycleTime</td>
            <td align="center" style="font-weight: bold; color: white">Active</td>
        </tr>
    </thead>
    <tbody>
        @foreach ($data as $datas)
        <tr>
            <td align="center">{{$loop->iteration}}</td>
            <td align="center">{{$datas->LocationName}}</td>
            <td align="center">{{$datas->OperationName}}</td>
            <td align="center">{{$datas->CategoryName}}</td>
            <td align="center">{{$datas->SubCategoryName}}</td>
            <td align="center"></td>
            <td align="center"></td>
            <td align="center">{{$datas->ValidDate}}</td>
            <td align="center">{{$datas->CycleTime}}</td>
            <td align="center">{{$datas->StatusActive}}</td>
        </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <th>No</th>
            <th>Location</th>
            <th>Operation</th>
            <th>Category</th>
            <th>SubCategory</th>
            <th>Model</th>
            <th>Operator</th>
            <th>Valid Date</th>
            <th>CycleTime</th>
            <th>Active</th>
        </tr>
    </tfoot>
</table>


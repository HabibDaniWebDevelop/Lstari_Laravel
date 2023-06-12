<div class="card-body">
    <div class="row">
        <div class="col-12">
            <ul class="nav nav-pills mb-3 flex-column flex-md-row mb-3" role="tablist">
                <li class="nav-item">
                    <button type="button" class="nav-link active" role="tab" data-bs-toggle="tab" data-bs-target="#SudahTerdaftar" aria-controls="SudahTerdaftar" aria-selected="true"> Sudah Terdaftar </button>
                </li>
                <li class="nav-item">
                    <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#BelumTerdaftar" aria-controls="BelumTerdaftar" aria-selected="false"> Belum Terdaftar </button>
                </li>
            </ul>
            <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade show active" id="SudahTerdaftar" role="tabpanel" aria-labelledby="SudahTerdaftar">
                    <table class="table table-striped" id="tableSudahTerdaftar">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>id Employee</th>
                                <th>Nama</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($registeredEmployeeList as $item)
                            <tr>
                                <td>{{$loop->iteration}}</td>
                                <td>{{$item['emp_code']}}</td>
                                <td>{{$item['employee_name']}}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="tab-pane fade" id="BelumTerdaftar" role="tabpanel" aria-labelledby="BelumTerdaftar">
                    <table class="table table-striped" id="tableBelumTerdaftar">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>id Employee</th>
                                <th>Nama</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($unregisteredEmployeeList as $item)
                            <tr>
                                <td>{{$loop->iteration}}</td>
                                <td>{{$item['emp_code']}}</td>
                                <td>{{$item['employee_name']}}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <br>
</div>
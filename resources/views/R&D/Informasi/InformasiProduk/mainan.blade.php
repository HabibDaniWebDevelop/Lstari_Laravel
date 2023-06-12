<div class="input-group">
    
        <div class="col-md-6">
            
            <div class="card mb-12">
                <h5 class="card-header">Filter</h5>
                <div class="card-body demo-vertical-spacing demo-only-element">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="input-group input-group-merge">
                                <input type="text" class="form-control" placeholder="Kategori Mainan" aria-describedby="button-addon2" id="subkamn" />
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="input-group input-group-merge">
                                <input type="text" class="form-control" placeholder="No Seri" aria-describedby="button-addon2" id="nomn" onchange="getListMN()" />
                                <button class="btn btn-outline-primary" type="button" id="button-addon2" onclick="getListMN()">Cari</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <div class="col-md-6">
    
            <div class="card mb-12">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive text-nowrap" style="height:calc(100vh - 490px);">
                            <table class="table table-border table-hover table-sm" id="tablemn">
                                <thead class="table-secondary sticky-top zindex-2">
                                    <tr>
                                        <th style="text-align: center;">No</th>
                                        <th style="text-align: center;">Sub Kategori</th>
                                        <th style="text-align: center;">No. Seri</th>
                                        <th style="text-align: center;">Ukuran</th>
                                    </tr>
                                </thead>
                                <tfoot>

                                </tfoot>
                                <tbody>
                                    
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
</div>
@section('script')

    <script type="text/javascript">
        var table = $('#tablemn').DataTable({
            "paging": true,
            "lengthChange": false,
            "pageLength": 9,
            "searching": true,
            "ordering": true,
            "info": false,
            "autoWidth": true,
            "responsive": true,
            "fixedColumns": false,
            "lengthChange": false
        });
    </script>

@endsection
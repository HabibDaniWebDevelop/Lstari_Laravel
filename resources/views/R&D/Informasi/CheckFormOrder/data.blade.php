<div class="input-group">
    <div class="col-md-12">
        <div class="card mb-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive text-nowrap" style="height:calc(100vh - 490px);">
                        <table class="table table-border table-hover table-sm" id="tableProd">
                            <thead class="table-secondary sticky-top zindex-2">
                                <tr>
                                    <th style="text-align: center;">Sub Kategori</th>                                    
                                    <th style="text-align: center;">No. Seri</th>
                                    <th style="text-align: center;">SKU</th>
                                    <th style="text-align: center;">Form Order</th>
                                    <th style="text-align: center;">Action</th>
                                </tr>
                            </thead>
                            @php
                                $no = 1;
                            @endphp
                            <tbody>
                                @forelse ($fo as $fos)  
                                    <tr>
                                        <td> 
                                            <input type="text" class="chk-col-black" name="no[]" id="no" style="width: 100%; box-sizing: border-box;" value="{{ $fos->SW }}">
                                            <input type="hidden" class="chk-col-black" name="no[]" id="no" style="width: 100%; box-sizing: border-box;" value="{{ $fos->EnamelGroup }}">
                                        </td>
                                        <td> 
                                            <input type="text" class="chk-col-black" name="no[]" id="no" style="width: 100%; box-sizing: border-box;" value="{{ $fos->SerialNo }}">
                                        </td>
                                        <td> 
                                            <input type="text" class="chk-col-black" name="no[]" id="no" style="width: 100%; box-sizing: border-box;" value="{{ $fos->SKU }}">
                                        </td>
                                        <td> 
                                            <input type="text" class="chk-col-black" name="no[]" id="no" style="width: 100%; box-sizing: border-box;" value="{{ $fos->IDFormOrder }}">
                                        </td>
                                        <td> 
                                            <button type="button" class="btn btn-primary" id="Form1" onclick="cekProduk({{$fos->EnamelGroup}})"> <span class="tf-icons bx bx-file"></span>&nbsp; Cek Produk</button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" align="center">No Data</td>
                                    </tr>
                                @php
                                    $no++;
                                @endphp
                                @endforelse 
                            </tbody>
                            <tfoot>

                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@section('script')
<script>
    $(document).ajaxStart(function() {
        $(".preloader").show();
    });
    $(document).ajaxComplete(function() { 
        $(".preloader").hide();
    });


    var table = $('#tableProd').DataTable({
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

    function cekProduk(value) {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        window.open('http://192.168.1.100/lestarinew/listmasterproduk/cekVariasi.php?idproduk='+value);
    }

</script>
@endsection

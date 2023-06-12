{{-- {{ dd($searchs); }} --}}

<div class="row mb-3">
    <div class="col-md-12">
        <div class="card" id="kotak-filter" style="width:100%;">
            <div class="card-body">

                <div class="col d-flex justify-content-center" id="filter1">

                    <div class="card-filter text-center" id="btn-all">
                        <div class="card-body">
                            <i class="fas fa-list-ul fa-lg"></i>
                            <br>
                            <a>All Category</a>
                        </div>
                    </div>
                    <div class="card-filter text-center" id="btn-solid">
                        <div class="card-body">
                            <i class="fas fa-cube fa-lg"></i>
                            <br>
                            <a>Chemical Solid</a>
                        </div>
                    </div>
                    <div class="card-filter text-center" id="btn-liquid">
                        <div class="card-body">
                            <i class="fas fa-tint fa-lg"></i>
                            <br>
                            <a>Chemical Liquid</a>
                        </div>
                    </div>
                    <div class="card-filter text-center" id="btn-media">
                        <div class="card-body">
                            <i class="fas fa-glass-whiskey fa-lg"></i>
                            <br>
                            <a>Media</a>
                        </div>
                    </div>
                    <div class="card-filter text-center" id="btn-tools">
                        <div class="card-body">
                            <i class="fas fa-tools fa-lg"></i>
                            <br>
                            <a>Tools</a>
                        </div>
                    </div>

                    <button class="btn btn-dark shadow rounded-circle hover-scale text-center text-white p-1 fs-3"
                        style="width: 60px; height: 60px; position: fixed; right: 50px; bottom: 50px; z-index: 100;" id="keranjang" onclick="transfer()">

                        <i class="fas fa-shopping-basket fa-xl"></i>
                        <span class="badge badge-center rounded-pill bg-warning p-3 fs-5 d-none"
                            style="position: absolute; top: -10px; right: -10px;" id="keranjangisi">
                            <div>0</div>
                        </span>

                    </button>

                </div>
                <br>
                <div class="row d-flex justify-content-center">

                    <div class="col-3">
                        <div class="input-group" id="cari_filter">
                            <label class="input-group-text btn-primary">Material</label>
                            <select class="form-control selectpicker my-select res1" id="cari" name="cari"
                                data-live-search="true" data-style="border">
                                <option selected="">Choose...</option>
                                @foreach ($searchs as $search)
                                    <option value="{{ $search->SW }}">{{ $search->ID }} - {{ $search->Description }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-3">
                        <div class="input-group">
                            <label class="input-group-text btn-primary">Department</label>
                            <select class="form-control selectpicker my-select" id="department" name="department"
                                data-live-search="true" data-style="border">
                                <option selected="">Choose...</option>
                                @foreach ($departments as $department)
                                    <option value="{{ $department->ID }}">{{ $department->ID }} -
                                        {{ $department->Description }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-3">
                        <div class="input-group">
                            <label class="input-group-text btn-primary">Location</label>
                            <select class="form-control selectpicker my-select res1" id="Location" name="Location" data-live-search="true"
                                data-style="border">
                                <option selected="" value="">Choose...</option>
                                @foreach ($Location as $data)
                                <option {{-- $data->ID=='64'?'selected':'' --}} value="{{ $data->ID }}">{{ $data->ID }} - {{ $data->Description }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>


                    {{-- <div style="width: 720px;"> --}}
                    {{-- </div> --}}
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card p-4" id="table_data">
            @include('Master.Gudang.KatalogBahanPembantu.pagination_data')
        </div>
    </div>
</div>

@include('Master.Gudang.KatalogBahanPembantu.modal')

<script>
    //filtering
    $(document).ready(function() {
        // var val = "<?php echo $menu; ?>";
        var menu = $('#menu').val();
        $("#" + menu).addClass('bg-primary text-white');
        $('.my-select').selectpicker();

    });

    //pencarian
    $(document).on('change', '#cari', function(){
        var cari = $('#cari').val();
        $('.card-filter').removeClass('bg-primary text-white');
        $("button[data-id=department] .filter-option-inner-inner").html("Choose...");
        $('#department').val('');
        $.ajax({
            url: "/Master/Gudang/KatalogBahanPembantu/pagination?cari=" + cari,
            success: function(data) {
                $('#table_data').html(data);
            }
        });
    });

    //pencarian department
    $('select[name=department]').change(function() {
        var department = $('#department').val();
        
        $('.card-filter').removeClass('bg-primary text-white');

        //reset pencarian
        $('.res1').attr("disabled", true)
        $('.res1').selectpicker('destroy')
        $('.res1').selectpicker()
        $('#Location').val('');
        $('#cari').val('');
        $('.res1').removeAttr("disabled")
        $('.res1').selectpicker('destroy')
        $('.res1').selectpicker()
        
        // alert(cari);
        $.ajax({
            url: "/Master/Gudang/KatalogBahanPembantu/pagination?department=" + department,
            success: function(data) {
                $('#table_data').html(data);
            }
        });
    });

    //tombol menu
    var selector1 = '.card-filter';
    $(selector1).on('click', function() {

        $(selector1).removeClass('bg-primary text-white');
        $(this).addClass('bg-primary text-white');
        btn = $(this).attr('id');

        //reset pencarian
        $('.res1').attr("disabled", true)
        $('.res1').selectpicker('destroy')
        $('.res1').selectpicker()
        $('#Location').val('');
        $('#cari').val('');
        $('#department').val('');
        $('.res1').removeAttr("disabled")
        $('.res1').selectpicker('destroy')
        $('.res1').selectpicker()


        $.ajax({
            url: "/Master/Gudang/KatalogBahanPembantu/pagination?menu=" + btn,
            headers: {
                'Cache-Control': 'no-cache, no-store, must-revalidate',
                'Pragma': 'no-cache',
                'Expires': '0'
            },
            success: function(data) {
                $('#table_data').html(data);
            }
        });


    });    

    //paging
    $(document).ready(function() {
        $(document).on('click', '.pagination a', function(event) {
            event.preventDefault();
            var page = $(this).attr('href').split('page=')[1];
            fetch_data(page);
        });

        function fetch_data(page) {
            var menu = $('#menu').val();
            var department = $('#department').val();
            let Location = $('#Location').val();

            if (department == 'Choose...' || department == null) {
                department = '';
            }

            let datas = {
                "menu": menu,
                "department": department,
                "Location": Location,
                "page": page,
                }

            $.ajax({
                url: "/Master/Gudang/KatalogBahanPembantu/pagination",
                data: datas,
                success: function(data) {
                    $('#table_data').html(data);
                }
            });
        }
    });
</script>

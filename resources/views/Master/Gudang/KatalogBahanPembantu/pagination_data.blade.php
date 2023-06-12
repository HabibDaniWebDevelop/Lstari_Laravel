<h3> <span class="badge bg-primary">{{ $type }}</span> </h3>

<div class="row row-cols-xxl-6 row-cols-xl-5 row-cols-lg-3 row-cols-md-3 row-cols-sm-1 row-cols-1 mb-4">

    @forelse ($datas as $data)
        <div class="col px-0">
            <div class="card card-material m-2">
                <div class="portfolio-item">
                    <div class="thumb">

                        <?php
                        if ($data->Image1 == '') { ?>
                        <img class="img-material" src="{!! asset('assets/images/no-photos2.jpg') !!}" height="255">
                        <?php }else{ ?>
                        <img class="img-material"
                            src="{{ Session::get('hostfoto') }}/rnd2/BahanPembantu/{{ $data->Image1 }}?{{ Date('U') }}"
                            {{-- src="{{ Session::get('hostfoto') }}/DiskD/BahanPembantu/{{ $data->Image1 }}?{{ Date('U') }}" --}} height="255"
                            onerror="this.onerror=null; this.src='{!! asset('assets/images/no-photos2.jpg') !!}'">
                        <?php }
                        ?>

                        <div class="mask m-0"></div>
                    </div>
                    {{-- kotak item --}}
                    <div class="tt">
                        <span class="badge rounded-pill bg-danger m-2 type">{{ $data->Type }}</span>
                        <span class="badge bg-dark fs-6">ID : {{ $data->ID }}</span>
                        <div class="fw-semibold text-dark">{{ $data->Description }}</div>
                        <div class="text-dark">{{ $data->KAT }}</div>
                        <div class="text-dark d-none">{{ $data->Loc }}</div>
                    </div>
                    {{-- kotak item hover --}}
                    <div class="details">
                        <h4 class="title"><b>{{ $data->Description }}</b></h4>
                        <br>
                        <h4 class="title"><span class="badge bg-dark">{{ $data->Location }}</span></h4>
                        <h4 class="fs-6">{{ $data->Supplier }}</h4>
                        <h4 class="fs-6">{{ $data->KAT }}</h4>
                        <h4 class="fs-6">{{ $data->LocSW }}</h4>
                        <div class="btn-action text-center mt-5">
                            <button class="btn btn-light btn-detail btn-sm m-1" onclick="lihat({{ $data->ID }})"><i
                                    class="fas fa-bookmark"></i>
                                Detail</button>
                            @if ($iddept == '48')
                                <button class="btn btn-light btn-detail btn-sm m-1"
                                    onclick="ubah({{ $data->ID }})"><i class="fas fa-edit"></i>
                                    Update</button>

                                <button class="btn btn-light btn-detail btn-sm m-1"
                                    onclick="gambar({{ $data->ID }})"><i class="fas fa-image"></i>
                                    Gambar</button>
                            @endif

                            <button class="btn btn-light btn-detail btn-sm m-1"
                                onclick="ambil({{ $data->ID }},{{ $data->LocationID }})"><i
                                    class="fas fa-shopping-cart"></i>
                                Ambil</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="alert alert-danger w-100">
            Data Material belum Tersedia.
        </div>
    @endforelse

</div>
<input type="hidden" id="menu" value="{{ $menu }}">

{{ $datas->links('pagination::bootstrap-4') }}

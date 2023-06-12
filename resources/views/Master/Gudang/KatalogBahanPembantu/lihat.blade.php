<div class="row">
    <div class="col-md-12">
        <table border="0" style="width: 100%; height: 70vh;">
            <input type="hidden" id="idd" name="idd" value="'.$id.'">
            <tr>
                <td style="width: 55%; ">
                    <div class="card" style="margin-left:20px; padding:10px; height: 100%;">
                        <div class="row">
                            <!-- Bootstrap crossfade carousel -->
                            <div class="col-md d-table">

                                <div id="carouselExample-cf" class="carousel carousel-dark slide carousel-fade"
                                    data-bs-ride="carousel">
                                    <ol class="carousel-indicators">
                                        <?php $i = 0; ?>
                                        @if ($datas[0]->foto1 != '')
                                            <li data-bs-target="#carouselExample-cf"
                                                data-bs-slide-to="{{ $i }}" class="active"></li>
                                        @endif

                                        @if ($datas[0]->foto2 != '')
                                            <?php $i = $i + 1; ?>
                                            <li data-bs-target="#carouselExample-cf"
                                                data-bs-slide-to="{{ $i }}"></li>
                                        @endif

                                        @if ($datas[0]->foto3 != '')
                                            <?php $i = $i + 1; ?>
                                            <li data-bs-target="#carouselExample-cf"
                                                data-bs-slide-to="{{ $i }}"></li>
                                        @endif

                                        @if ($datas[0]->foto4 != '')
                                            <?php $i = $i + 1; ?>
                                            <li data-bs-target="#carouselExample-cf"
                                                data-bs-slide-to="{{ $i }}"></li>
                                        @endif

                                        @if ($datas[0]->foto5 != '')
                                            <?php $i = $i + 1; ?>
                                            <li data-bs-target="#carouselExample-cf"
                                                data-bs-slide-to="{{ $i }}"></li>
                                        @endif

                                        @if ($datas[0]->foto6 != '')
                                            <?php $i = $i + 1; ?>
                                            <li data-bs-target="#carouselExample-cf"
                                                data-bs-slide-to="{{ $i }}"></li>
                                        @endif

                                        @if ($datas[0]->foto7 != '')
                                            <?php $i = $i + 1; ?>
                                            <li data-bs-target="#carouselExample-cf"
                                                data-bs-slide-to="{{ $i }}"></li>
                                        @endif

                                    </ol>
                                    <div class="carousel-inner">

                                        @if ($datas[0]->foto1 != '')
                                            <div class="carousel-item text-center active" style="height: 560px;">
                                                <img style="max-width: 550px; max-height: 550px; position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);"
                                                    src="{{ Session::get('hostfoto') }}{{ $datas[0]->foto1 }}?{{ Date('U') }}"
                                                    onerror="this.onerror=null; this.src='{!! asset('assets/images/no-photos.jpg') !!}'">
                                                <div class="carousel-caption d-none d-md-block">
                                                    <h4 class="text-primary fw-bold" style="-webkit-text-stroke: 0.1px rgb(255, 255, 255);">Foto 1</h4>
                                                </div>
                                            </div>
                                        @endif

                                        @if ($datas[0]->foto2 != '')
                                            <div class="carousel-item text-center" style="height: 560px;">
                                                <img style="max-width: 550px; max-height: 550px; position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);"
                                                    src="{{ Session::get('hostfoto') }}{{ $datas[0]->foto2 }}?{{ Date('U') }}"
                                                    onerror="this.onerror=null; this.src='{!! asset('assets/images/no-photos.jpg') !!}'">
                                                    <div class="carousel-caption d-none d-md-block">
                                                        <h4 class="text-primary fw-bold" style="-webkit-text-stroke: 0.1px rgb(255, 255, 255);">Foto 2</h4>
                                                    </div>
                                            </div>
                                        @endif

                                        @if ($datas[0]->foto3 != '')
                                            <div class="carousel-item text-center" style="height: 560px;">
                                                <img style="max-width: 550px; max-height: 550px; position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);"
                                                    src="{{ Session::get('hostfoto') }}{{ $datas[0]->foto3 }}?{{ Date('U') }}"
                                                    onerror="this.onerror=null; this.src='{!! asset('assets/images/no-photos.jpg') !!}'">
                                                    <div class="carousel-caption d-none d-md-block">
                                                        <h4 class="text-primary fw-bold" style="-webkit-text-stroke: 0.1px rgb(255, 255, 255);">Foto 3</h4>
                                                    </div>
                                            </div>
                                        @endif

                                        @if ($datas[0]->foto4 != '')
                                            <div class="carousel-item text-center" style="height: 560px;">
                                                <img style="max-width: 550px; max-height: 550px; position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);"
                                                    src="{{ Session::get('hostfoto') }}{{ $datas[0]->foto4 }}?{{ Date('U') }}"
                                                    onerror="this.onerror=null; this.src='{!! asset('assets/images/no-photos.jpg') !!}'">
                                                    <div class="carousel-caption d-none d-md-block">
                                                        <h4 class="text-primary fw-bold" style="-webkit-text-stroke: 0.1px rgb(255, 255, 255);">Foto 4</h4>
                                                    </div>
                                            </div>
                                        @endif

                                        @if ($datas[0]->foto5 != '')
                                            <div class="carousel-item text-center" style="height: 560px;">
                                                <img style="max-width: 550px; max-height: 550px; position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);"
                                                    src="{{ Session::get('hostfoto') }}{{ $datas[0]->foto5 }}?{{ Date('U') }}"
                                                    onerror="this.onerror=null; this.src='{!! asset('assets/images/no-photos.jpg') !!}'">
                                                    <div class="carousel-caption d-none d-md-block">
                                                        <h4 class="text-primary fw-bold" style="-webkit-text-stroke: 0.1px rgb(255, 255, 255);">Foto 5</h4>
                                                    </div>
                                            </div>
                                        @endif

                                        @if ($datas[0]->foto6 != '')
                                            <div class="carousel-item text-center" style="height: 560px;">
                                                <img style="max-width: 550px; max-height: 550px; position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);"
                                                    src="{{ Session::get('hostfoto') }}{{ $datas[0]->foto6 }}?{{ Date('U') }}"
                                                    onerror="this.onerror=null; this.src='{!! asset('assets/images/no-photos.jpg') !!}'">
                                                    <div class="carousel-caption d-none d-md-block">
                                                        <h4 class="text-primary fw-bold" style="-webkit-text-stroke: 0.1px rgb(255, 255, 255);">Foto 6</h4>
                                                    </div>
                                            </div>
                                        @endif

                                        @if ($datas[0]->foto7 != '')
                                            <div class="carousel-item text-center" style="height: 560px;">
                                                <img style="max-width: 550px; max-height: 550px; position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);"
                                                    src="{{ Session::get('hostfoto') }}{{ $datas[0]->foto7 }}?{{ Date('U') }}"
                                                    onerror="this.onerror=null; this.src='{!! asset('assets/images/no-photos.jpg') !!}'">
                                                    <div class="carousel-caption d-none d-md-block">
                                                        <h4 class="text-primary fw-bold" style="-webkit-text-stroke: 0.1px rgb(255, 255, 255);">Foto Technical</h4>
                                                    </div>
                                            </div>
                                        @endif

                                    </div>

                                    @if ($datas[0]->foto1 != '')
                                        <a class="carousel-control-prev" href="#carouselExample-cf" role="button" data-bs-slide="prev">
                                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                            <span class="visually-hidden">Previous</span>
                                        </a>
                                        <a class="carousel-control-next" href="#carouselExample-cf" role="button" data-bs-slide="next">
                                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                            <span class="visually-hidden">Next</span>
                                        </a>
                                    @else
                                        <img class="m-4" style="width: 92%; text-align: center;"
                                            src="{!! asset('assets/images/no-photos.jpg') !!}">
                                    @endif
                                    
                                </div>
                            </div>
                        </div>
                    </div>
                </td>

                <td style="width: 45%;">
                    <div class="card" style="margin-left:20px; padding:10px; height: 100%;">
                        <table width="100%" style="color:black;">
                            <tr>
                                <td class="pt-2" width="110">ID Material</td>
                                <td class="pt-2" width="10">:</td>
                                <td class="pt-2"><span class="badge bg-dark fs-6">
                                        {{ $datas[0]->ID }}</span></td>
                            </tr>
                            <tr>
                                <td class="pt-2">Nama Material</td>
                                <td class="pt-2">:</td>
                                <td class="pt-2"> {{ $datas[0]->Description }} </td>
                            </tr>
                            <tr>
                                <td class="align-top pt-2">Suppllier</td>
                                <td class="align-top">:</td>
                                <td class="align-top">
                                    @foreach ($Suppliers as $Supplier)
                                        {{ $Supplier->SWSupp }} - {{ $Supplier->Supplier }} <br>
                                    @endforeach
                                </td>
                            </tr>
                            <tr>
                                <td class="pt-2">Merk</td>
                                <td class="pt-2">:</td>
                                <td class="pt-2"> {{ $datas[0]->Brand }}
                                </td>
                            </tr>
                            <tr>
                                <td class="pt-2">Kategori</td>
                                <td class="pt-2">:</td>
                                <td class="pt-2"> {{ $datas[0]->KAT }}
                                </td>
                            </tr>
                            <tr>
                                <td class="pt-2">Satusan</td>
                                <td class="pt-2">:</td>
                                <td class="pt-2"> {{ $datas[0]->EE }} </td>
                            </tr>
                            <tr>
                                <td>TDS</td>
                                <td>:</td>
                                <td>
                                    @if ($datas[0]->TDS != '-')
                                        <a href="{{ Session::get('hostfoto') }}{{ $datas[0]->TDS }}" target="_blank"
                                            rel="noopener noreferrer" style="font-style:italic;">View</a>
                                    @else
                                        {{ $datas[0]->TDS }}
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td>MSDS</td>
                                <td>:</td>
                                <td>
                                    @if ($datas[0]->MSDS != '-')
                                        <a href="{{ Session::get('hostfoto') }}{{ $datas[0]->MSDS }}"
                                            target="_blank" rel="noopener noreferrer"
                                            style="font-style:italic;">View</a>
                                    @else
                                        {{ $datas[0]->MSDS }}
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td class="align-top">Deskripsi</td>
                                <td class="align-top">:</td>
                                <td class="align-top"> {{ $datas[0]->Remarks }}
                                </td>
                            </tr>
                            <tr>
                                <td>Fungsi</td>
                                <td>:</td>
                                <td> {{ $datas[0]->MaterialFunction }} </td>
                            </tr>
                            <tr>
                                <td class="align-top">Area</td>
                                <td class="align-top">:</td>
                                <td class="align-top">
                                    @foreach ($areas as $area)
                                        {{ $area->Description }} <br>
                                    @endforeach
                                </td>
                            </tr>
                        </table>
                    </div>
                </td>
            </tr>
        </table>
    </div>
</div>

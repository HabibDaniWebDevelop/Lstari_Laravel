<div class="card-body">
    <ul class="nav nav-pills mb-3 flex-column flex-md-row mb-3" id="myTab" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="verification-tab" data-bs-toggle="tab" data-bs-target="#verificationTab" type="button" role="tab" aria-controls="verificationTab" aria-selected="true">Verifikasi</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="ready-tab" data-bs-toggle="tab" data-bs-target="#readyTab" type="button" role="tab" aria-controls="readyTab" aria-selected="false">Siap SPKO</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="revisi-tab" data-bs-toggle="tab" data-bs-target="#revisiTab" type="button" role="tab" aria-controls="revisiTab" aria-selected="false">Revisi</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="spko-tab" data-bs-toggle="tab" data-bs-target="#spkoTab" type="button" role="tab" aria-controls="spkoTab" aria-selected="false">SPKO</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="nthko-tab" data-bs-toggle="tab" data-bs-target="#nthkoTab" type="button" role="tab" aria-controls="nthkoTab" aria-selected="false">NTHKO</button>
        </li>
    </ul>

    <div class="tab-content" id="myTabContent">
        {{-- Content Verifikasi --}}
        <div class="tab-pane fade show active" id="verificationTab" role="tabpanel" aria-labelledby="home-tab">
            <div>
                <table class="table table-borderless table-sm" id="tabel1">
                    <thead class="table-secondary">
                        <tr style="text-align: center">
                            <th>NO</th>
                            <th>Photo 2D</th>
                            <th>Photo 3D</th>
                            <th>Product</th>
                            <th>File Corel</th>
                            <th>File 3D</th>
                            <th>Option</th>
                        </tr>
                    </thead>
                    <tbody class="text-center">
                        @foreach ($dataWIP as $item)
                            @if ($item->ProgressStatus == 0)
                            <tr id="verification_{{$item->IDWIP}}">
                                <td>{{$loop->iteration}}</td>
                                <td><a href="{{Session::get('hostfoto')}}/rnd2/Drafter 2D/{{$item->jenisPart}}/{{$item->imageProduct}}" onerror="this.onerror=null; this.src='{!! asset('assets/images/no-photos.jpg') !!}'" data-lightbox="image-{{$loop->iteration}}"><img src="{{Session::get('hostfoto')}}/rnd2/Drafter 2D/{{$item->jenisPart}}/{{$item->imageProduct}}" width="150" height="150" onerror="this.onerror=null; this.src='{!! asset('assets/images/no-photos.jpg') !!}'" alt=""></a></td>
                                <td><a href="{{Session::get('hostfoto')}}/rnd2/Drafter 3D/Image 3D/{{$item->imageProduct3D}}" onerror="this.onerror=null; this.src='{!! asset('assets/images/no-photos.jpg') !!}'" data-lightbox="image-{{$loop->iteration}}"><img src="{{Session::get('hostfoto')}}/rnd2/Drafter 3D/Image 3D/{{$item->imageProduct3D}}" width="150" height="150" onerror="this.onerror=null; this.src='{!! asset('assets/images/no-photos.jpg') !!}'" alt=""></a></td>
                                <td>
                                    <span style="font-size: 14px" class="badge bg-primary">{{$item->swProduct}}</span>
                                </td>
                                <td><a href="{{Session::get('hostfoto')}}/rnd2/Drafter 2D/{{$item->jenisPart}}Corel/{{$item->corelFile}}" target="_BLANK">Download</a></td>
                                <td>
                                    <a href="{{Session::get('hostfoto')}}/rnd2/Drafter 3D/File Rhino/{{$item->file3DM}}" target="_BLANK">Download</a>
                                    <br>
                                    <a href="#" onclick="previewFunction({{$item->ID}})"><span style="font-size: 14px" class="badge bg-dark">Preview</span></a>
                                </td>
                                <td>
                                    <button class="btn btn-primary" onclick="verifiedWIP({{$item->IDWIP}})">Verified</button> <button class="btn btn-primary">Revisi</button>
                                </td>
                            </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        {{-- Content Siap SPKO --}}
        <div class="tab-pane fade" id="readyTab" role="tabpanel" aria-labelledby="profile-tab">
            <div>
                <table class="table table-borderless table-sm" id="tabel1">
                    <thead class="table-secondary">
                        <tr style="text-align: center">
                            <th>NO</th>
                            <th>Photo 2D</th>
                            <th>Photo 3D</th>
                            <th>Product</th>
                            <th>File Corel</th>
                            <th>File 3D</th>
                            <th>Option</th>
                        </tr>
                    </thead>
                    <tbody class="text-center">
                        @foreach ($dataWIP as $item)
                            @if ($item->ProgressStatus == 1)
                            <tr id="active_{{$item->IDWIP}}">
                                <td>{{$loop->iteration}}</td>
                                <td><a href="{{Session::get('hostfoto')}}/rnd2/Drafter 2D/{{$item->jenisPart}}/{{$item->imageProduct}}" onerror="this.onerror=null; this.src='{!! asset('assets/images/no-photos.jpg') !!}'" data-lightbox="image-{{$loop->iteration}}"><img src="{{Session::get('hostfoto')}}/rnd2/Drafter 2D/{{$item->jenisPart}}/{{$item->imageProduct}}" width="150" height="150" onerror="this.onerror=null; this.src='{!! asset('assets/images/no-photos.jpg') !!}'" alt=""></a></td>
                                <td><a href="{{Session::get('hostfoto')}}/rnd2/Drafter 3D/Image 3D/{{$item->imageProduct3D}}" onerror="this.onerror=null; this.src='{!! asset('assets/images/no-photos.jpg') !!}'" data-lightbox="image-{{$loop->iteration}}"><img src="{{Session::get('hostfoto')}}/rnd2/Drafter 3D/Image 3D/{{$item->imageProduct3D}}" width="150" height="150" onerror="this.onerror=null; this.src='{!! asset('assets/images/no-photos.jpg') !!}'" alt=""></a></td>
                                <td>
                                    <span style="font-size: 14px" class="badge bg-primary">{{$item->swProduct}}</span>
                                </td>
                                <td><a href="{{Session::get('hostfoto')}}/rnd2/Drafter 2D/{{$item->jenisPart}}Corel/{{$item->corelFile}}" target="_BLANK">Download</a></td>
                                <td>
                                    <a href="{{Session::get('hostfoto')}}/rnd2/Drafter 3D/File Rhino/{{$item->file3DM}}" target="_BLANK">Download</a>
                                    <br>
                                    <a href="#" onclick="previewFunction({{$item->ID}})"><span style="font-size: 14px" class="badge bg-dark">Preview</span></a>
                                </td>
                                <td><a href="#" target="_BLANK"><span style="font-size: 14px" class="badge bg-info">Buat SPKO</span></a></td>
                            </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        {{-- Content Revisi --}}
        <div class="tab-pane fade" id="revisiTab" role="tabpanel" aria-labelledby="contact-tab">
            <div>
                <table class="table table-borderless table-sm" id="tabel1">
                    <thead class="table-secondary">
                        <tr style="text-align: center">
                            <th>NO</th>
                            <th>Photo 2D</th>
                            <th>Photo 3D</th>
                            <th>Product</th>
                            <th>File Corel</th>
                            <th>File 3D</th>
                            <th>Option</th>
                        </tr>
                    </thead>
                    <tbody class="text-center">
                        @foreach ($dataWIP as $item)
                            @if ($item->ProgressStatus == 3 or $item->ProgressStatus == 4)
                            <tr id="revision_{{$item->IDWIP}}">
                                <td>{{$loop->iteration}}</td>
                                <td><a href="{{Session::get('hostfoto')}}/rnd2/Drafter 2D/{{$item->jenisPart}}/{{$item->imageProduct}}" onerror="this.onerror=null; this.src='{!! asset('assets/images/no-photos.jpg') !!}'" data-lightbox="image-{{$loop->iteration}}"><img src="{{Session::get('hostfoto')}}/rnd2/Drafter 2D/{{$item->jenisPart}}/{{$item->imageProduct}}" width="150" height="150" onerror="this.onerror=null; this.src='{!! asset('assets/images/no-photos.jpg') !!}'" alt=""></a></td>
                                <td><a href="{{Session::get('hostfoto')}}/rnd2/Drafter 3D/Image 3D/{{$item->imageProduct3D}}" onerror="this.onerror=null; this.src='{!! asset('assets/images/no-photos.jpg') !!}'" data-lightbox="image-{{$loop->iteration}}"><img src="{{Session::get('hostfoto')}}/rnd2/Drafter 3D/Image 3D/{{$item->imageProduct3D}}" width="150" height="150" onerror="this.onerror=null; this.src='{!! asset('assets/images/no-photos.jpg') !!}'" alt=""></a></td>
                                <td>
                                    <span style="font-size: 14px" class="badge bg-primary">{{$item->swProduct}}</span>
                                </td>
                                <td><a href="{{Session::get('hostfoto')}}/rnd2/Drafter 2D/{{$item->jenisPart}}Corel/{{$item->corelFile}}" target="_BLANK">Download</a></td>
                                <td>
                                    <a href="{{Session::get('hostfoto')}}/rnd2/Drafter 3D/File Rhino/{{$item->file3DM}}" target="_BLANK">Download</a>
                                    <br>
                                    <a href="#" onclick="previewFunction({{$item->ID}})"><span style="font-size: 14px" class="badge bg-dark">Preview</span></a>
                                </td>
                                <td><a href="#" target="_BLANK"><span style="font-size: 14px" class="badge bg-info">Buat SPKO</span></a></td>
                            </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        {{-- Content SPKO --}}
        <div class="tab-pane fade" id="spkoTab" role="tabpanel" aria-labelledby="contact-tab">
            <div>
                <table class="table table-borderless table-sm" id="tabel1">
                    <thead class="table-secondary">
                        <tr style="text-align: center">
                            <th>NO</th>
                            <th>Photo 2D</th>
                            <th>Photo 3D</th>
                            <th>Product</th>
                            <th>File Corel</th>
                            <th>File 3D</th>
                            <th>Option</th>
                        </tr>
                    </thead>
                    <tbody class="text-center">
                        @foreach ($dataWIP as $item)
                            @if ($item->ProgressStatus == 5)
                            <tr id="spko_{{$item->IDWIP}}">
                                <td>{{$loop->iteration}}</td>
                                <td><a href="{{Session::get('hostfoto')}}/rnd2/Drafter 2D/{{$item->jenisPart}}/{{$item->imageProduct}}" onerror="this.onerror=null; this.src='{!! asset('assets/images/no-photos.jpg') !!}'" data-lightbox="image-{{$loop->iteration}}"><img src="{{Session::get('hostfoto')}}/rnd2/Drafter 2D/{{$item->jenisPart}}/{{$item->imageProduct}}" width="150" height="150" onerror="this.onerror=null; this.src='{!! asset('assets/images/no-photos.jpg') !!}'" alt=""></a></td>
                                <td><a href="{{Session::get('hostfoto')}}/rnd2/Drafter 3D/Image 3D/{{$item->imageProduct3D}}" onerror="this.onerror=null; this.src='{!! asset('assets/images/no-photos.jpg') !!}'" data-lightbox="image-{{$loop->iteration}}"><img src="{{Session::get('hostfoto')}}/rnd2/Drafter 3D/Image 3D/{{$item->imageProduct3D}}" width="150" height="150" onerror="this.onerror=null; this.src='{!! asset('assets/images/no-photos.jpg') !!}'" alt=""></a></td>
                                <td>
                                    <span style="font-size: 14px" class="badge bg-primary">{{$item->swProduct}}</span>
                                </td>
                                <td><a href="{{Session::get('hostfoto')}}/rnd2/Drafter 2D/{{$item->jenisPart}}Corel/{{$item->corelFile}}" target="_BLANK">Download</a></td>
                                <td>
                                    <a href="{{Session::get('hostfoto')}}/rnd2/Drafter 3D/File Rhino/{{$item->file3DM}}" target="_BLANK">Download</a>
                                    <br>
                                    <a href="#" onclick="previewFunction({{$item->ID}})"><span style="font-size: 14px" class="badge bg-dark">Preview</span></a>
                                </td>
                                <td><a href="#" target="_BLANK"><span style="font-size: 14px" class="badge bg-info">Buat SPKO</span></a></td>
                            </tr>
                        @endif
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        {{-- Content NTHKO --}}
        <div class="tab-pane fade" id="nthkoTab" role="tabpanel" aria-labelledby="contact-tab">
            <div>
                <table class="table table-borderless table-sm" id="tabel1">
                    <thead class="table-secondary">
                        <tr style="text-align: center">
                            <th>NO</th>
                            <th>Photo 2D</th>
                            <th>Photo 3D</th>
                            <th>Product</th>
                            <th>File Corel</th>
                            <th>File 3D</th>
                            <th>Option</th>
                        </tr>
                    </thead>
                    <tbody class="text-center">
                        @foreach ($dataWIP as $item)
                            @if ($item->ProgressStatus == 6)
                            <tr id="nthko_{{$item->IDWIP}}">
                                <td>{{$loop->iteration}}</td>
                                <td><a href="{{Session::get('hostfoto')}}/rnd2/Drafter 2D/{{$item->jenisPart}}/{{$item->imageProduct}}" onerror="this.onerror=null; this.src='{!! asset('assets/images/no-photos.jpg') !!}'" data-lightbox="image-{{$loop->iteration}}"><img src="{{Session::get('hostfoto')}}/rnd2/Drafter 2D/{{$item->jenisPart}}/{{$item->imageProduct}}" width="150" height="150" onerror="this.onerror=null; this.src='{!! asset('assets/images/no-photos.jpg') !!}'" alt=""></a></td>
                                <td><a href="{{Session::get('hostfoto')}}/rnd2/Drafter 3D/Image 3D/{{$item->imageProduct3D}}" onerror="this.onerror=null; this.src='{!! asset('assets/images/no-photos.jpg') !!}'" data-lightbox="image-{{$loop->iteration}}"><img src="{{Session::get('hostfoto')}}/rnd2/Drafter 3D/Image 3D/{{$item->imageProduct3D}}" width="150" height="150" onerror="this.onerror=null; this.src='{!! asset('assets/images/no-photos.jpg') !!}'" alt=""></a></td>
                                <td>
                                    <span style="font-size: 14px" class="badge bg-primary">{{$item->swProduct}}</span>
                                </td>
                                <td><a href="{{Session::get('hostfoto')}}/rnd2/Drafter 2D/{{$item->jenisPart}}Corel/{{$item->corelFile}}" target="_BLANK">Download</a></td>
                                <td>
                                    <a href="{{Session::get('hostfoto')}}/rnd2/Drafter 3D/File Rhino/{{$item->file3DM}}" target="_BLANK">Download</a>
                                    <br>
                                    <a href="#" onclick="previewFunction({{$item->ID}})"><span style="font-size: 14px" class="badge bg-dark">Preview</span></a>
                                </td>
                                <td><a href="#" target="_BLANK"><span style="font-size: 14px" class="badge bg-info">Buat SPKO</span></a></td>
                            </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- Modal --}}
<div class="modal fade" id="modalinfo" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-fullscreen" id="modalformat" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="judulModal"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="modalBody">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                    Close
                </button>
            </div>
        </div>
    </div>
</div>
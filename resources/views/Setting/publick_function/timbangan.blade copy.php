@extends('layouts.backend-Theme-3.app2')

<?php $title = 'Timbangan'; ?>
@section('container')
    <h4 class="fw-bold py-3"><span class="text-muted fw-light">Home /</span> {{ $title }}</h4>
    <div class="row">
        <div class="col-md-12">

            <!-- Basic Layout -->
            <div class="row p-4">
                <div class="col-md-12 col-lg-6 col-xxl-4">
                    <div class="card">
                        <div class="card-body">

                            <div class="demo-inline-spacing mb-3">
                                <button type="button" class="btn btn-primary" id="conscale" onclick="connectSerial()"><span
                                        class="fas fa-balance-scale"></span>&nbsp; Timbangan</button>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Timbang 1</label>
                                <div class="input-group input-group-merge">
                                    <input type="text" class="form-control" id="hasil" name="timbang[]" readonly />
                                    <button type="button" class="btn btn-primary" onclick="kliktimbang('hasil')"><span
                                            class="fas fa-balance-scale"></span></button>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Timbang 2</label>
                                <div class="input-group input-group-merge">
                                    <input type="text" class="form-control" id="hasil1" name="timbang[]" readonly
                                        onfocus="kliktimbang('hasil1')" />
                                        <button type="button" class="btn btn-primary" onclick="kliktimbang('hasil1')"><span
                                            class="fas fa-balance-scale"></span></button>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Timbang 3</label>
                                <div class="input-group input-group-merge">
                                    <input type="text" class="form-control" id="hasil2" name="timbang[]" readonly  />
                                        <button type="button" class="btn btn-primary" onclick="kliktimbang('hasil2')"><span
                                            class="fas fa-balance-scale"></span></button>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Timbang 4</label>
                                <div class="input-group input-group-merge">
                                    <input type="text" class="form-control" id="hasil3" name="timbang[]" readonly />
                                        <button type="button" class="btn btn-primary" onclick="kliktimbang('hasil3')"><span
                                            class="fas fa-balance-scale"></span></button>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Timbang 5</label>
                                <div class="input-group input-group-merge">
                                    <input type="text" class="form-control" id="hasil4" name="timbang[]" readonly  />
                                        <button type="button" class="btn btn-primary" onclick="kliktimbang('hasil4')"><span
                                            class="fas fa-balance-scale"></span></button>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Total</label>
                                <div class="input-group input-group-merge">
                                    <input type="text" class="form-control" id="total" readonly />
                                </div>
                            </div>

                            <div class="mb-3">
                                {{-- <label class="form-label">Note: Tekan Enter di Input Form Untuk Mendapatkan nilai hasil timbangan</label> --}}
                            </div>

                            <input type="hidden" id="selscale">
                        </div>

                    </div>
                </div>

            </div>

        </div>
    </div>
@endsection

@section('script')
    {{-- untuk di taruh di modul user --}}
    {{-- @include('layouts.backend-Theme-3.timbangan') --}}

    {{-- untuk debuging saja --}}
    @include('layouts.backend-Theme-3.timbangandev')

    <script>
        // mendapatkan elemen total
        var total = $('#total');
        $(document).on("change", 'input[name="timbang[]"]', function() {

            var sum = 0;
            // menghitung total dari semua input
            $('input[name="timbang[]"]').each(function() {
                sum += Number($(this).val());
            });
            // menampilkan total pada elemen total
            total.val(sum.toFixed(2));

        });
    </script>
@endsection

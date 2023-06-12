<style>
    .dropify-wrapper .dropify-message p {
        font-size: initial;
    }
</style>

<?php
header('Cache-Control: no-store, no-cache, must-revalidate, proxy-revalidate, max-age=0');
?>

<div class="demo-inline-spacing" id="btn-menu">

    <button type="button" class="btn btn-danger" id="Batal1" onclick="Klik_Batal1()"> <span
            class="tf-icons bx bx-arrow-back"></span>&nbsp; Kembali</button>

    <button type="button" class="btn btn-warning" id="Simpan1" onclick="Klik_Simpan1()">
        <span class="tf-icons bx bx-save"></span>&nbsp; Simpan</button>


    <div class="d-flex float-end">
        {{-- <button type="button" class="btn btn-white" id="Batal1" disabled> {{ $id }}</button> --}}
        <b class="h4">NTHKO : {{ $id }}</b>
    </div>

    <hr class="m-0" />

</div>


<form id="form1" autocomplete="off">
    <input type="hidden" name="TransDate" id="TransDate" value="{{ $data[0]->TransDate }}">
    <div class="card-body">
        <div class="table-responsive text-nowrap mb-0" style="height:calc(100vh - 385px);">
            <table class="table table-bordered table-sm" id="tabel3">
                <thead class="table-secondary sticky-top zindex-2">
                    <tr style="text-align: center">
                        <th>Gambar</th>
                        <th>Local Grafis</th>
                        <th>Tampak Perspektif</th>
                        <th>Tampak Atas</th>
                        <th>Tampak Bawah</th>
                        <th>Tampak Depan</th>
                        <th>Tampak Belakang</th>
                        <th>Tampak Kiri</th>
                        <th>Tampak Kanan</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $i = 0;
                    @endphp
                    @forelse ($data as $data1)
                        <?php
                        $Enamel = $data1->Enamel == '0' ? '' : ' &emsp; Enamel : ' . $data1->Enamel;
                        $data1->gambar = str_replace('.jpg', '', $data1->gambar) . '.jpg';
                        $i++;
                        ?>
                        <tr class="klik" id="{{ $data1->WorkAllocation }}">
                            <td>
                                <table style="width: 250px;" class="tabel2">
                                    <tbody height="222">
                                        <tr>
                                            <td height="34" class="p-1"
                                                style="max-width: 0px; text-overflow: ellipsis !important; overflow: hidden;">
                                                {{ $data1->SKU }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td align="center">
                                                <img class="text-center"
                                                    src="{{ Session::get('hostfoto') }}/rnd2/Drafter 2D/Original/{{ $data1->ImageOriginal }}.jpg"
                                                    class="img-fluid" style="max-height: 150px; max-width: 235px;"
                                                    onerror="this.onerror=null; this.src='{!! asset('assets/images/no-photos.jpg') !!}'">
                                                <input type="hidden" name="id[{{ $loop->iteration }}]"
                                                    value="{{ $data1->ID }}">
                                                <input type="hidden" name="sku[{{ $loop->iteration }}]"
                                                    value="{{ $data1->SKU }}">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td align="center" height="25" class="p-1">
                                                <b style="font-size: 18px;"> {{ $data1->Warna }} {!! $Enamel !!}
                                                </b>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </td>
                            <td class="p-0"><input type="file" accept="image/jpeg" id="loc{{ $loop->iteration }}"
                                    multiple name="gambar" class="dropify" data-height="220px"
                                    data-default-file="{{ Session::get('hostfoto') }}/UploadGrafis/{{ $data[0]->TransDate }}/{{ $data1->SKU }}/{{ $data1->SKU }}-1.jpg" />
                            </td>
                            <td class="p-0"><input type="file" accept="image/jpeg"
                                    id="g[{{ $loop->iteration }}][1]" name="gambar" class="dropify"
                                    data-height="220px"
                                    data-default-file="{{ Session::get('hostfoto') }}/image2/{{ $data1->SKU }}.jpg" />
                            </td>
                            <td class="p-0"><input type="file" accept="image/jpeg"
                                    id="g[{{ $loop->iteration }}][2]" name="gambar" class="dropify"
                                    data-height="220px"
                                    data-default-file="{{ Session::get('hostfoto') }}/image2/{{ $data1->SKU }}-1.jpg" />
                            </td>
                            <td class="p-0"><input type="file" accept="image/jpeg"
                                    id="g[{{ $loop->iteration }}][3]" name="gambar" class="dropify"
                                    data-height="220px"
                                    data-default-file="{{ Session::get('hostfoto') }}/image2/{{ $data1->SKU }}-2.jpg" />
                            </td>
                            <td class="p-0"><input type="file" accept="image/jpeg"
                                    id="g[{{ $loop->iteration }}][4]" name="gambar" class="dropify"
                                    data-height="220px"
                                    data-default-file="{{ Session::get('hostfoto') }}/image2/{{ $data1->SKU }}-3.jpg" />
                            </td>
                            <td class="p-0"><input type="file" accept="image/jpeg"
                                    id="g[{{ $loop->iteration }}][5]" name="gambar" class="dropify"
                                    data-height="220px"
                                    data-default-file="{{ Session::get('hostfoto') }}/image2/{{ $data1->SKU }}-4.jpg" />
                            </td>
                            <td class="p-0"><input type="file" accept="image/jpeg"
                                    id="g[{{ $loop->iteration }}][6]" name="gambar"
                                    class="dropify"data-height="220px"
                                    data-default-file="{{ Session::get('hostfoto') }}/image2/{{ $data1->SKU }}-5.jpg" />
                            </td>
                            <td class="p-0"><input type="file" accept="image/jpeg"
                                    id="g[{{ $loop->iteration }}][7]" name="gambar" class="dropify"
                                    data-height="220px"
                                    data-default-file="{{ Session::get('hostfoto') }}/image2/{{ $data1->SKU }}-6.jpg" />
                            </td>
                        </tr>
                    @empty
                    @endforelse

                </tbody>
            </table>

            <input type="hidden" id="urut" value="{{ $i }}">
        </div>
    </div>
</form>

<script>
    $(document).ready(function() {
        // Basic
        $('.dropify').dropify();

        // Used events
        var drEvent = $('#input-file-events').dropify();

        // Tangani error jika gambar tidak tersedia
        $('img').on('error', function() {
            $(this).attr('src', '{!! asset('assets/images/no-photos.jpg') !!}');
        });

        drEvent.on('dropify.beforeClear', function(event, element) {
            return confirm("Do you really want to delete \"" + element.file.name + "\" ?");
        });

        drEvent.on('dropify.afterClear', function(event, element) {
            alert('File deleted');
        });

        drEvent.on('dropify.errors', function(event, element) {
            console.log('Has Errors');
        });

        var drDestroy = $('#input-file-to-destroy').dropify();
        drDestroy = drDestroy.data('dropify')
        $('#toggleDropify').on('click', function(e) {
            e.preventDefault();
            if (drDestroy.isDropified()) {
                drDestroy.destroy();
            } else {
                drDestroy.init();
            }
        })
    });
</script>

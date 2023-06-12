<table width="100%">
    <tr>
        <td class="p-1">
            <table class="p-1">
                @foreach ($kolom1 AS $tess)
                <tr>
                    <td>
                        <div id="card" class="card p-1 my-2 text-center border border-dark "
                            style="Height: 120px; width: 150px; ">

                            <button id="button" class="btn btn-primary p-0">
                                <span id="sw{{$loop->iteration}}"
                                    style="color:white; text-align:center; font-weight: bold;"
                                    value="{{$tess->datamu}}">
                                    {{$tess->datamu}}</span>
                            </button>
                            <button id="button" class="btn btn-dark mt-1 p-0">
                                <span
                                    style="color:white; text-align:center; font-weight: bold;">{{$tess->ahahaha}}</span>
                            </button>
                            <span class="card-title text-center px-2"
                                style=" font-weight: bold;">{{$tess->lokasi}}</span>
                            <INPUT hidden id="isi{{$loop->iteration}}" value="{{$tess->Available}}"></INPUT>
                        </div>
                    </td>
                </tr>
                @endforeach
            </table>
        </td>
        <!-- background-color: hsl(220, 90%, 50%); -->
        <td class="p-1">
            <table class="p-1">
                @foreach ($kolom2 AS $tess)
                <tr>
                    <td>
                        <div id="card" class="card p-1 my-2 text-center border border-dark "
                            style="Height: 120px; width: 150px; ">

                            <button id="button" class="btn btn-primary p-0">
                                <span id="sw{{$loop->iteration}}"
                                    style="color:white; text-align:center; font-weight: bold;"
                                    value="{{$tess->datamu}}">
                                    {{$tess->datamu}}</span>
                            </button>
                            <button id="button" class="btn btn-dark mt-1 p-0">
                                <span
                                    style="color:white; text-align:center; font-weight: bold;">{{$tess->ahahaha}}</span>
                            </button>
                            <span class="card-title text-center px-2"
                                style=" font-weight: bold;">{{$tess->lokasi}}</span>
                            <INPUT hidden id="isi{{$loop->iteration}}" value="{{$tess->Available}}"></INPUT>
                        </div>
                    </td>
                </tr>

                @endforeach
            </table>
        </td>
        <td>
            <table class="p-4">
                @foreach ($kolom3 AS $tess)

                <tr>
                    <td>
                        <div id="card" class="card p-1 my-2 text-center border border-dark "
                            style="Height: 120px; width: 150px; ">

                            <button id="button" class="btn btn-primary p-0">
                                <span id="sw{{$loop->iteration}}"
                                    style="color:white; text-align:center; font-weight: bold;"
                                    value="{{$tess->datamu}}">
                                    {{$tess->datamu}}</span>
                            </button>
                            <button id="button" class="btn btn-dark mt-1 p-0">
                                <span
                                    style="color:white; text-align:center; font-weight: bold;">{{$tess->ahahaha}}</span>
                            </button>
                            <span class="card-title text-center px-2"
                                style=" font-weight: bold;">{{$tess->lokasi}}</span>
                            <INPUT hidden id="isi{{$loop->iteration}}" value="{{$tess->Available}}"></INPUT>
                        </div>
                    </td>
                </tr>

                @endforeach
            </table>
        </td>
        <td>
            <table class="p-4">
                @foreach ($kolom4 AS $tess)

                <tr>
                    <td>
                        <div id="card" class="card p-1 my-2 text-center border border-dark "
                            style="Height: 120px; width: 150px; ">

                            <button id="button" class="btn btn-primary p-0">
                                <span id="sw{{$loop->iteration}}"
                                    style="color:white; text-align:center; font-weight: bold;"
                                    value="{{$tess->datamu}}">
                                    {{$tess->datamu}}</span>
                            </button>
                            <button id="button" class="btn btn-dark mt-1 p-0">
                                <span
                                    style="color:white; text-align:center; font-weight: bold;">{{$tess->ahahaha}}</span>
                            </button>
                            <span class="card-title text-center px-2"
                                style=" font-weight: bold;">{{$tess->lokasi}}</span>
                            <INPUT hidden id="isi{{$loop->iteration}}" value="{{$tess->Available}}"></INPUT>
                        </div>
                    </td>
                </tr>

                @endforeach
            </table>
        </td>
        <td>
            <table class="p-4">
                @foreach ($kolom5 AS $tess)

                <tr>
                    <td>
                        <div id="card" class="card p-1 my-2 text-center border border-dark "
                            style="Height: 120px; width: 150px; ">

                            <button id="button" class="btn btn-primary p-0">
                                <span id="sw{{$loop->iteration}}"
                                    style="color:white; text-align:center; font-weight: bold;"
                                    value="{{$tess->datamu}}">
                                    {{$tess->datamu}}</span>
                            </button>
                            <button id="button" class="btn btn-dark mt-1 p-0">
                                <span
                                    style="color:white; text-align:center; font-weight: bold;">{{$tess->ahahaha}}</span>
                            </button>
                            <span class="card-title text-center px-2"
                                style=" font-weight: bold;">{{$tess->lokasi}}</span>
                            <INPUT hidden id="isi{{$loop->iteration}}" value="{{$tess->Available}}"></INPUT>
                        </div>
                    </td>
                </tr>

                @endforeach
            </table>
        </td>
        <td>
            <table class="p-4">
                @foreach ($kolom6 AS $tess)

                <tr>
                    <td>
                        <div id="card" class="card p-1 my-2 text-center border border-dark "
                            style="Height: 120px; width: 150px; ">

                            <button id="button" class="btn btn-primary p-0">
                                <span id="sw{{$loop->iteration}}"
                                    style="color:white; text-align:center; font-weight: bold;"
                                    value="{{$tess->datamu}}">
                                    {{$tess->datamu}}</span>
                            </button>
                            <button id="button" class="btn btn-dark mt-1 p-0">
                                <span
                                    style="color:white; text-align:center; font-weight: bold;">{{$tess->ahahaha}}</span>
                            </button>
                            <span class="card-title text-center px-2"
                                style=" font-weight: bold;">{{$tess->lokasi}}</span>
                            <INPUT hidden id="isi{{$loop->iteration}}" value="{{$tess->Available}}"></INPUT>
                        </div>
                    </td>
                </tr>

                @endforeach
            </table>
        </td>
        <td>
            <table class="p-4">
                @foreach ($kolom7 AS $tess)

                <tr>
                    <td>
                        <div id="card" class="card p-1 my-2 text-center border border-dark "
                            style="Height: 120px; width: 150px; ">

                            <button id="button" class="btn btn-primary p-0">
                                <span id="sw{{$loop->iteration}}"
                                    style="color:white; text-align:center; font-weight: bold;"
                                    value="{{$tess->datamu}}">
                                    {{$tess->datamu}}</span>
                            </button>
                            <button id="button" class="btn btn-dark mt-1 p-0">
                                <span
                                    style="color:white; text-align:center; font-weight: bold;">{{$tess->ahahaha}}</span>
                            </button>
                            <span class="card-title text-center px-2"
                                style=" font-weight: bold;">{{$tess->lokasi}}</span>
                            <INPUT hidden id="isi{{$loop->iteration}}" value="{{$tess->Available}}"></INPUT>
                        </div>
                    </td>
                </tr>

                @endforeach
            </table>
        </td>
        <td>
            <table class="p-4">
                @foreach ($kolom8 AS $tess)

                <tr>
                    <td>
                        <div id="card" class="card p-1 my-2 text-center border border-dark "
                            style="Height: 120px; width: 150px; ">

                            <button id="button" class="btn btn-primary p-0">
                                <span id="sw{{$loop->iteration}}"
                                    style="color:white; text-align:center; font-weight: bold;"
                                    value="{{$tess->datamu}}">
                                    {{$tess->datamu}}</span>
                            </button>
                            <button id="button" class="btn btn-dark mt-1 p-0">
                                <span
                                    style="color:white; text-align:center; font-weight: bold;">{{$tess->ahahaha}}</span>
                            </button>
                            <span class="card-title text-center px-2"
                                style=" font-weight: bold;">{{$tess->lokasi}}</span>
                            <INPUT hidden id="isi{{$loop->iteration}}" value="{{$tess->Available}}"></INPUT>
                        </div>
                    </td>
                </tr>

                @endforeach
            </table>
        </td>
        <td>
            <table class="p-4">
                @foreach ($kolom9 AS $tess)

                <tr>
                    <td>
                        <div id="card" class="card p-1 my-2 text-center border border-dark "
                            style="Height: 120px; width: 150px; ">

                            <button id="button" class="btn btn-primary p-0">
                                <span id="sw{{$loop->iteration}}"
                                    style="color:white; text-align:center; font-weight: bold;"
                                    value="{{$tess->datamu}}">
                                    {{$tess->datamu}}</span>
                            </button>
                            <button id="button" class="btn btn-dark mt-1 p-0">
                                <span
                                    style="color:white; text-align:center; font-weight: bold;">{{$tess->ahahaha}}</span>
                            </button>
                            <span class="card-title text-center px-2"
                                style=" font-weight: bold;">{{$tess->lokasi}}</span>
                            <INPUT hidden id="isi{{$loop->iteration}}" value="{{$tess->Available}}"></INPUT>
                        </div>
                    </td>
                </tr>
                <!-- <script>
                // var id = $(this).attr('id');
                if ($('.btn-primary').val('')) {
                    $(this).removeClass('border-dark');
                    $(this).addClass('border-primary');
                } else {
                    $(this).addClass('border-primary');
                }
                </script> -->
                @endforeach
            </table>
        </td>

    </tr>
</table>
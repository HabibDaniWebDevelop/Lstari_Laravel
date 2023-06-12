<style>
/* The container */
.container {
    display: block;
    position: relative;
    padding-left: 28px;
    margin-bottom: 12px;
    cursor: pointer;
    font-size: 22px;
    -webkit-user-select: none;
    -moz-user-select: none;
    -ms-user-select: none;
    user-select: none;
}

/* Hide the browser's default radio button */
.container input {
    position: absolute;
    opacity: 0;
    cursor: pointer;
    height: 0;
    width: 0;
}

/* Create a custom radio button */
.checkmark {
    position: absolute;
    top: 8px;
    left: 0;
    height: 25px;
    width: 25px;
    background-color: #eee;
    border-radius: 18%;
}

/* On mouse-over, add a grey background color */
.container:hover input~.checkmark {
    background-color: #ccc;
}

/* When the radio button is checked, add a blue background */
.container input:checked~.checkmark {
    background-color: #2196F3;
}

/* Create the indicator (the dot/circle - hidden when not checked) */
.checkmark:after {
    content: "";
    position: absolute;
    display: none;
}

/* Show the indicator (dot/circle) when checked */
.container input:checked~.checkmark:after {
    display: block;
}

/* Style the indicator (dot/circle) */
.container .checkmark:after {
    left: 9px;
    top: 5px;
    width: 5px;
    height: 10px;
    border: solid white;
    border-width: 0 3px 3px 0;
    -webkit-transform: rotate(45deg);
    -ms-transform: rotate(45deg);
    transform: rotate(45deg);
}

.dd {
    font-size: 11px;
}
</style>

<hr>

<table width="100%">
    <tr>
        <td class="p-1">
            <table class="p-1">
                @foreach ($kolom1 AS $item)
                <tr>
                    <td>
                        <label class="container m-0 {{$item->Available}}" onclick="PilihLokasi()"><span
                                class="{{$item->ClassButton}} dd {{$item->Available}}"
                                style="width: 100px; font-size: 11px;">{{$item->datamu}}</span>
                            <input type="radio" checked="checked" class="{{$item->Available}} lokasi"
                                value="{{$item->ID}}" name="radio">
                            <span class="checkmark {{$item->Available}}"></span>
                        </label>

                    </td>
                </tr>
                @endforeach
            </table>
        </td>
        <!-- background-color: hsl(220, 90%, 50%); -->
        <td class="p-1">
            <table class="p-1">
                @foreach ($kolom2 AS $item)
                <tr>
                    <td>
                        <label class="container m-0 {{$item->Available}}" onclick="PilihLokasi()"><span
                                class="{{$item->ClassButton}} dd {{$item->Available}}"
                                style="width: 100px; font-size: 11px;">{{$item->datamu}}</span>
                            <input type="radio" checked="checked" class="{{$item->Available}} lokasi"
                                value="{{$item->ID}}" name="radio">
                            <span class="checkmark {{$item->Available}}"></span>
                        </label>
                    </td>
                </tr>

                @endforeach
            </table>
        </td>
        <td>
            <table class="p-4">
                @foreach ($kolom3 AS $item)

                <tr>
                    <td>
                        <label class="container m-0 {{$item->Available}}" onclick="PilihLokasi()"><span
                                class="{{$item->ClassButton}} dd {{$item->Available}}"
                                style="width: 100px; font-size: 11px;">{{$item->datamu}}</span>
                            <input type="radio" checked="checked" class="{{$item->Available}} lokasi"
                                value="{{$item->ID}}" name="radio">
                            <span class="checkmark {{$item->Available}}"></span>
                        </label>
                    </td>
                </tr>

                @endforeach
            </table>
        </td>
        <td>
            <table class="p-4">
                @foreach ($kolom4 AS $item)

                <tr>
                    <td>
                        <label class="container m-0 {{$item->Available}}" onclick="PilihLokasi()"><span
                                class="{{$item->ClassButton}} dd {{$item->Available}}"
                                style="width: 100px; font-size: 11px;">{{$item->datamu}}</span>
                            <input type="radio" checked="checked" class="{{$item->Available}} lokasi"
                                value="{{$item->ID}}" name="radio">
                            <span class="checkmark {{$item->Available}}"></span>
                        </label>
                    </td>
                </tr>

                @endforeach
            </table>
        </td>
        <td>
            <table class="p-4">
                @foreach ($kolom5 AS $item)

                <tr>
                    <td>
                        <label class="container m-0 {{$item->Available}}" onclick="PilihLokasi()"><span
                                class="{{$item->ClassButton}} dd {{$item->Available}}"
                                style="width: 100px; font-size: 11px;">{{$item->datamu}}</span>
                            <input type="radio" checked="checked" class="{{$item->Available}} lokasi"
                                value="{{$item->ID}}" name="radio">
                            <span class="checkmark {{$item->Available}}"></span>
                        </label>
                    </td>
                </tr>

                @endforeach
            </table>
        </td>
        <td>
            <table class="p-4">
                @foreach ($kolom6 AS $item)

                <tr>
                    <td>
                        <label class="container m-0 {{$item->Available}}" onclick="PilihLokasi()"><span
                                class="{{$item->ClassButton}} dd {{$item->Available}}"
                                style="width: 100px; font-size: 11px;">{{$item->datamu}}</span>
                            <input type="radio" checked="checked" class="{{$item->Available}} lokasi"
                                value="{{$item->ID}}" name="radio">
                            <span class="checkmark {{$item->Available}}"></span>
                        </label>
                    </td>
                </tr>

                @endforeach
            </table>
        </td>
        <td>
            <table class="p-4">
                @foreach ($kolom7 AS $item)

                <tr>
                    <td>
                        <label class="container m-0 {{$item->Available}}" onclick="PilihLokasi()"><span
                                class="{{$item->ClassButton}} dd {{$item->Available}}"
                                style="width: 100px; font-size: 11px;">{{$item->datamu}}</span>
                            <input type="radio" checked="checked" class="{{$item->Available}} lokasi"
                                value="{{$item->ID}}" name="radio">
                            <span class="checkmark {{$item->Available}}"></span>
                        </label>
                    </td>
                </tr>

                @endforeach
            </table>
        </td>
        <td>
            <table class="p-4">
                @foreach ($kolom8 AS $item)

                <tr>
                    <td>
                        <label class="container m-0 {{$item->Available}}" onclick="PilihLokasi()"><span
                                class="{{$item->ClassButton}} dd {{$item->Available}}"
                                style="width: 100px; font-size: 11px;">{{$item->datamu}}</span>
                            <input type="radio" checked="checked" class="{{$item->Available}} lokasi"
                                value="{{$item->ID}}" name="radio">
                            <span class="checkmark {{$item->Available}}"></span>
                        </label>
                    </td>
                </tr>

                @endforeach
            </table>
        </td>
        <td>
            <table class="p-4">
                @foreach ($kolom9 AS $item)

                <tr>
                    <td>
                        <label class="container m-0 {{$item->Available}}" onclick="PilihLokasi()"><span
                                class="{{$item->ClassButton}} dd {{$item->Available}}"
                                style="width: 100px; font-size: 11px;">{{$item->datamu}}</span>
                            <input type="radio" checked="checked" class="{{$item->Available}} lokasi"
                                value="{{$item->ID}}" name="radio">
                            <span class="checkmark {{$item->Available}}"></span>
                        </label>
                    </td>
                </tr>
                @endforeach
            </table>
        </td>
    </tr>
</table>

<script>
// $('.dd').css("fontSize", "11px");
$('.b').prop('disabled', true);
</script>
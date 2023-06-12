<li class="dropdown-menu-header border-bottom">
    <div class="dropdown-header d-flex align-items-center py-3">
        <h5 class="text-body mb-0 me-auto">Tulis Pesan</h5>
        <a href="javascript:void(0)" class="dropdown-notifications-all" onclick="Messaging_list()"><i
                class="fas fa-times fa-lg"></i></a>
    </div>
</li>
<?php
$pecah = explode('&', $id);
$id = $pecah[0];
$id2 = $pecah[1];
?>
<li class="list-group-item list-group-item-action dropdown-notifications-item">

    @if ($id != '0' && $id2 != '0')
        @if ($id2 == '1')
            <div class="d-grid mb-3">
                <p class="text-center h5"> Pilih Status </p>
                <div class="d-grid gap-2 col-8 mx-auto">
                    <button class="btn btn-warning" type="button" onclick="Messaging_kirim('P')">Process</button>
                    <button class="btn btn-dark" type="button" onclick="Messaging_kirim('S')">OK</button>
                    <button class="btn btn-danger" type="button" onclick=" Messaging_kirim('C')">Cancelled</button>
                </div>
            </div>
        @else
            <div class="d-flex mb-2">
                <div class="flex-grow-1">
                   <textarea class="form-control" rows="6" readonly> {{$Description}} </textarea>
                   {{-- {{$Description}} --}}
                </div>
            </div>

            <div class="d-flex">
                <div class="flex-grow-1">
                    <textarea class="form-control" id="pesan2" rows="6"></textarea>
                </div>
            </div>
        @endif
 
        <input type="hidden" value="update" id="type">
        <input type="hidden" value="{{ $id }}" id="id">
    @else
        @if ($id2 == '0')
            <div class="d-grid mb-3">
                <p class="text-center h5"> Pilih Status </p>
                <div class="d-grid gap-2 col-8 mx-auto">
                    <button class="btn btn-danger" type="button" onclick=" Messaging_kirim('C')">Cancelled</button>
                </div>
            </div>
            <input type="hidden" value="update" id="type">
            <input type="hidden" value="{{ $id }}" id="id">
        @else
            <div class="d-flex mb-3">
                <input type="text" list="pilihhlist" class="form-control" id="pilihh" onClick="this.select();">
                <datalist id="pilihhlist">
                    @foreach ($datas as $data)
                        <option value="{{ $data->name }}">
                    @endforeach
                </datalist>
            </div>

            <div class="d-flex">
                <div class="flex-grow-1">
                    <textarea class="form-control" id="pesan3" rows="6"></textarea>
                </div>
            </div>
            <input type="hidden" value="add" id="type">
            <input type="hidden" value="{{ Auth::user()->name }}" id="name">
        @endif
    @endif

</li>
@if ($id2 == '2')
    <li class="dropdown-menu-footer border-top">
        <a href="#" class="dropdown-item d-flex justify-content-center p-3" onclick="Messaging_kirim('0')">
            <b>Kirim</b>
        </a>
    </li>
@endif
<script></script>
 
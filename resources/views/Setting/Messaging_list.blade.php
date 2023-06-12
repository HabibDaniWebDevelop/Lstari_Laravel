<li class="dropdown-menu-header border-bottom">
    <div class="dropdown-header d-flex align-items-center py-3">
        <a href="/Lain-lain/Messaging">
        <h5 class="text-body mb-0 me-auto">Messaging <span class="badge bg-warning rounded-pill"
                id="mscount2">{{ $count }}</span></h5>
        </a>
        <a href="#" class="dropdown-item d-flex justify-content-center p-3" onclick="Messaging_write('0',2)">
            <h6 class="mb-0">Tulis Pesan &nbsp; <i class="fas fa-feather-alt"></i></h6>
        </a>
        <a href="javascript:void(0)" class="dropdown-notifications-all text-body" onclick="Messaging_readall()"><i
                class="fas fa-check-double fa-lg"></i></a>
    </div>
</li>

@forelse ($data as $data1)
<li class="list-group-item list-group-item-action dropdown-notifications-item" id="{{$data1->ID}}"
    <?php echo ($data1->Respon == '1') ? ' id2="1" ' : ' id2="0" '; ?>>

    @if (in_array(Auth::user()->name, ["Niko", "Ahmad H"])) 

        <div class="d-flex">
            <div class="flex-shrink-0 me-3">
                <div class="avatar">
                    @if ($data1->Status == 'P')
                    <span class="avatar-initial rounded-circle bg-label-warning">{{ $data1->Status }}</span>
                    @elseif ($data1->Status == 'S')
                    <span class="avatar-initial rounded-circle bg-label-success">{{ $data1->Status }}</span>
                    @elseif ($data1->Status == 'C')
                    <span class="avatar-initial rounded-circle bg-label-danger">{{ $data1->Status }}</span>
                    @else
                    <span class="avatar-initial rounded-circle bg-label-secondary">{{ $data1->Status }}</span>
                    @endif
                </div>
            </div>
            <div class="flex-grow-1">
                <p class="mb-1 h6">{{ $data1->Dari }}
                    <span class="badge rounded-pill bg-label-secondary">{{ $data1->Date }}</span>
                </p>
                <p class="mb-0">{{ $data1->Description }}</p>
                <i class="">Balasan: {{ $data1->NoteReplay }}</i> <br>
                <span class="badge rounded-pill bg-dark float-end klikpesan mx-2 cursor-pointer">Respon</span>

                @if ($data1->Respon == '1')
                <a href="#" onclick="Messaging_write({{ $data1->ID}},2)">
                    <span class="badge rounded-pill bg-primary float-end">Balas</span>
                </a>
                @endif

            </div>
            @if ($data1->Respon == '0' && ($data1->Status == 'S' || $data1->Status == 'C'))
            <div class="flex-shrink-0 dropdown-notifications-actions">
                <a href="javascript:void(0)" class="dropdown-notifications-archive"
                    onclick="Messaging_read({{ $data1->ID }})"><i class="fas fa-check"></i></a>
            </div>
            @endif
        </div>

    @else

        <div class="d-flex">
            <div class="flex-shrink-0 me-3">
                <div class="avatar klikpesan">
                    @if ($data1->Status == 'P')
                    <span class="avatar-initial rounded-circle bg-label-warning">{{ $data1->Status }}</span>
                    @elseif ($data1->Status == 'S')
                    <span class="avatar-initial rounded-circle bg-label-success">{{ $data1->Status }}</span>
                    @elseif ($data1->Status == 'C')
                    <span class="avatar-initial rounded-circle bg-label-danger">{{ $data1->Status }}</span>
                    @else
                    <span class="avatar-initial rounded-circle bg-label-secondary">{{ $data1->Status }}</span>
                    @endif
                </div>
            </div>
            <div class="flex-grow-1">
                <p class="mb-1 h6 klikpesan">{{ $data1->Dari }}
                    <span class="badge rounded-pill bg-label-secondary">{{ $data1->Date }}</span>
                </p>
                <p class="mb-0 klikpesan">{{ $data1->Description }}</p>
                <i class=" klikpesan">Balasan: {{ $data1->NoteReplay }}</i>

                @if ($data1->Respon == '1')
                <a href="#" onclick="Messaging_write({{ $data1->ID}},2)">
                    <span class="badge rounded-pill bg-primary float-end">Balas</span>
                </a>
                @endif

            </div>
            @if ($data1->Respon == '0' && ($data1->Status == 'S' || $data1->Status == 'C'))
            <div class="flex-shrink-0 dropdown-notifications-actions">
                <a href="javascript:void(0)" class="dropdown-notifications-archive"
                    onclick="Messaging_read({{ $data1->ID }})"><i class="fas fa-check"></i></a>
            </div>
            @endif
        </div>

    @endif

</li>

@empty
<div class="alert alert-success text-center">
    Tidak ada pesan
</div>
@endforelse


<li class="dropdown-menu-footer border-top">
    <a href="#" class="dropdown-item d-flex justify-content-center p-3" onclick="Messaging_write('0',2)">
        Tulis Pesan &nbsp; <i class="fas fa-feather-alt"></i>
    </a>
</li>

<script>
var mscount2 = document.getElementById("mscount2").innerText;
document.getElementById("mscount").innerHTML = mscount2;
if (mscount2 == '0') {
    $("#mscountmaster").addClass('d-none');
} else {
    $("#mscountmaster").removeClass('d-none');
}

$(".list-group-item .klikpesan").on('click', function(e) {
    var ID = $(this).parents().eq(2).attr('id');
    var ID2 = $(this).parents().eq(2).attr('id2');
    Messaging_write(ID, ID2);
});
</script>
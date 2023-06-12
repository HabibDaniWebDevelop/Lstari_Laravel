
    <li class="dropdown-menu-header border-bottom">
      <div class="dropdown-header d-flex align-items-center py-3">
        <h5 class="text-body mb-0 me-auto">Notification <span class="badge bg-warning rounded-pill" id="ntcount2">{{ $count }}</span></h5>
        <a href="javascript:void(0)" class="dropdown-notifications-all text-body" onclick="Notification_readall()"><i class="fas fa-check-double fa-lg"></i></a>
      </div>
    </li>

    @forelse ($data as $data1)

        <li class="list-group-item list-group-item-action dropdown-notifications-item">
            <div class="d-flex">
            <div class="flex-shrink-0 me-3">
                <div class="avatar">
                    @if ($data1->Status =='P')
                        <span class="avatar-initial rounded-circle bg-label-warning">{{ $data1->Status }}</span>
                    @elseif ($data1->Status =='S')
                        <span class="avatar-initial rounded-circle bg-label-success">{{ $data1->Status }}</span>
                    @elseif ($data1->Status =='C')
                        <span class="avatar-initial rounded-circle bg-label-danger">{{ $data1->Status }}</span>
                    @else
                        <span class="avatar-initial rounded-circle bg-label-secondary">{{ $data1->Status }}</span>
                    @endif
                </div>
            </div>
            <div class="flex-grow-1">
                <p class="mb-1 h6">{{ $data1->Modul }} <span class="badge rounded-pill bg-label-secondary">{{ $data1->created_at }}</span></p>
                
                <p class="mb-0">{{ $data1->Description }}</p>
                <small class="text-muted">Nomer: {{ $data1->Nomor }}</small>
                
            </div>
                @if ($data1->Status =='S' || $data1->Status =='C')
                    <div class="flex-shrink-0 dropdown-notifications-actions">
                        <a href="javascript:void(0)" class="dropdown-notifications-archive" onclick="Notification_read({{ $data1->ID }})"><i class="fas fa-check"></i></a>
                    </div>
                @endif
            </div>
        </li>

    @empty
    <div class="alert alert-success text-center">
        Tidak ada Notifikasi
    </div>
    @endforelse

    <script>
        var ntcount2 = document.getElementById("ntcount2").innerText;
        document.getElementById("ntcount").innerHTML = ntcount2;
        // alert(ntcount2);
        if(ntcount2=='0'){ $("#ntcountmaster").addClass('d-none'); }
        else{ $("#ntcountmaster").removeClass('d-none');}
    </script>

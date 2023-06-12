<div class="row">
    @foreach ($getList as $value)
    <div class="col-3">
        <div class="card border-dark mb-3" style="text-align:center;">
            <img src="{{ Session::get('hostfoto') }}/image/{{ $value->Photo }}.jpg" width="50px" align="center" height="auto" style="object-fit: cover; margin: 0 auto;" class="card-img-top" onerror="this.onerror=null; this.src='{!! asset('assets/images/no-photos.jpg') !!}'"></img>
            <div class="card-body">
                <!-- <span>{{ $value->SerialNo }}</span> -->
                @if($value->Stat =='Available')       
                    <button type="button" value="{{ $value->EnamelGroup }}" class="btn btn-primary" onclick="tambahKeranjang(this.value, {{$value->VarStone}}, {{$value->VarEnamel}}, {{$value->VarSlep}}, {{$value->VarMarking}}, {{$value->Kadar}}, '{{$value->Subka}}', {{$value->SerialNo}})">Pilih</button>
                @else
                    <button type="button" class="btn btn-primary" disabled>Kadar Belum Tersedia</button>
                @endif
            </div>
        </div>
    </div>
    @endforeach
</div>

@section('script')
<script>


</script>

@endsection
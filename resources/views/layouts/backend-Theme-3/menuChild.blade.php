<ul class="menu-sub">
    @foreach ($childs as $child)
    <li class="menu-item">
        <a href="{{ url('/'.$child->Patch) }}" class="menu-link py-1 @if (count($child->childs)) menu-toggle @endif ">
            {{-- {!! $child->Icon !!}  --}}
            {!! $child->Name !!}
        </a>
        @if (count($child->childs))
        @include('layouts.backend-Theme-3.menuChild',['childs' => $child->childs])
        @endif

    </li>
    @endforeach
</ul>
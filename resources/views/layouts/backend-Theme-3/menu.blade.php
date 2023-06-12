<aside id="layout-menu" class="layout-menu-horizontal menu menu-horizontal flex-grow-0 bg-menu-theme"
    data-bg-class="bg-menu-theme"
    style="height: 2.5rem; touch-action: none; user-select: none; -webkit-user-drag: none; -webkit-tap-highlight-color: rgba(0, 0, 0, 0);">
    <div class="container-fluid d-flex h-100">
        <!-- sticky-top -->
        <a href="#" class="menu-horizontal-prev disabled d-none"></a>
        <div class="menu-horizontal-wrapper">

            <ul class="menu-inner">
                <!-- Dashboard -->
                {{-- <li class="menu-item active">
          <a href="/" class="menu-link">
            <i class="menu-icon tf-icons bx bx-home-circle"></i>
            <div data-i18n="Analytics">Dashboard</div>
          </a>
        </li> --}}
                {{-- {{ $menu_categoris }} --}}
                @foreach ($menu_categoris as $category)
                <li class="menu-item">
                    <a href="javascript:void(0)" class="menu-link menu-toggle py-0">
                        {!! $category->Icon !!}
                        {!! $category->Name !!}
                    </a>
                    @if (count($category->childs))
                    @include('layouts.backend-Theme-3.menuChild',['childs' => $category->childs])
                    @endif

                </li>
                @endforeach

            </ul>
        </div>
        <a href="#" class="menu-horizontal-next d-none"></a>
    </div>
</aside>
<!-- / Menu -->
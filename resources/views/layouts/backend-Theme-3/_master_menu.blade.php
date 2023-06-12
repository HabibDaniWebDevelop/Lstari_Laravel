<aside id="layout-menu" class="layout-menu-horizontal menu menu-horizontal flex-grow-0 bg-menu-theme sticky-top"
    data-bg-class="bg-menu-theme"
    style="touch-action: none; user-select: none; -webkit-user-drag: none; -webkit-tap-highlight-color: rgba(0, 0, 0, 0);">
    <div class="container-fluid d-flex h-100">
        <a href="#" class="menu-horizontal-prev disabled d-none"></a>
        <div class="menu-horizontal-wrapper">

            <ul class="menu-inner py-1">
                <!-- Dashboard -->
                <li class="menu-item active">
                    <a href="/" class="menu-link">
                        <i class="menu-icon tf-icons bx bx-home-circle"></i>
                        <div data-i18n="Analytics">Dashboard</div>
                    </a>
                </li>

                <!-- Layouts -->
                <li class="menu-item">
                    <a href="javascript:void(0)" class="menu-link menu-toggle">
                        <i class="menu-icon tf-icons bx bx-layout"></i>
                        <div data-i18n="Layouts">Layouts</div>
                    </a>
                    <ul class="menu-sub">
                        <li class="menu-item">
                            <a href="{{ route('tabel3') }}" class="menu-link">
                                <i class="menu-icon tf-icons bx bx-table"></i>
                                <div data-i18n="Tables">Tables</div>
                            </a>
                        </li>
                        <li class="menu-item">
                            <a href="{{ route('forms_basic') }}" class="menu-link">
                                <i class="menu-icon tf-icons bx bx-detail"></i>
                                <div data-i18n="Form Elements">Form Elements</div>
                            </a>
                        </li>
                        <li class="menu-item">
                            <a href="{{ route('forms_layouts') }}" class="menu-link">
                                <i class="menu-icon tf-icons bx bx-detail"></i>
                                <div data-i18n="Form Layouts">Form Layouts</div>
                            </a>
                        </li>
                        <li class="menu-item">
                            <a href="buttons" class="menu-link">
                                <i class="menu-icon tf-icons bx bx-radio-circle bx-xs"></i>
                                <div data-i18n="Buttons">Buttons</div>
                            </a>
                        </li>
                        <li class="menu-item">
                            <a href="#" class="menu-link">
                                <i class="menu-icon tf-icons bx bx-radio-circle bx-xs"></i>
                                <div data-i18n="Carousel">Carousel</div>
                            </a>
                        </li>
                    </ul>
                </li>


                <!-- Pages -->
                <li class="menu-item">
                    <a href="javascript:void(0)" class="menu-link menu-toggle">
                        <i class="menu-icon tf-icons bx bx-collection"></i>
                        <div data-i18n="Pages">Pages</div>
                    </a>
                    <ul class="menu-sub">
                        <li class="menu-item">
                            <a href="blog3" class="menu-link">
                                <i class="menu-icon tf-icons bx bx-radio-circle bx-xs"></i>
                                <div data-i18n="Buttons">Tes Crud</div>
                            </a>
                        </li>
                        <li class="menu-item">
                            <a href="{{ route('rnd3') }}" class="menu-link">
                                <i class="menu-icon tf-icons bx bx-radio-circle bx-xs"></i>
                                <div data-i18n="Buttons">RND</div>
                            </a>
                        </li>
                        <li class="menu-item">
                            <a href="{{ route('erp3') }}" class="menu-link">
                                <i class="menu-icon tf-icons bx bx-radio-circle bx-xs"></i>
                                <div data-i18n="Buttons">ERP</div>
                            </a>
                        </li>
                        <li class="menu-item">
                            <a href="{{ route('blog.index') }}" class="menu-link">
                                <i class="menu-icon tf-icons bx bx-radio-circle bx-xs"></i>
                                <div data-i18n="Buttons">Blog</div>
                            </a>
                        </li>
                        <li class="menu-item">
                            <a href="javascript:void(0);" class="menu-link menu-toggle">
                                <i class="menu-icon tf-icons bx bx-user-circle"></i>
                                <div data-i18n="User Profile">User Profile</div>
                            </a>
                            <ul class="menu-sub">
                                <li class="menu-item">
                                    <a href="#" class="menu-link">
                                        <div data-i18n="Profile">Profile</div>
                                    </a>
                                </li>
                                <li class="menu-item">
                                    <a href="#" class="menu-link">
                                        <div data-i18n="Teams">Teams</div>
                                    </a>
                                </li>
                                <li class="menu-item">
                                    <a href="#" class="menu-link">
                                        <div data-i18n="Projects">Projects</div>
                                    </a>
                                </li>
                                <li class="menu-item">
                                    <a href="#" class="menu-link">
                                        <div data-i18n="Connections">Connections</div>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="menu-item ">
                            <a href="javascript:void(0);" class="menu-link menu-toggle">
                                <i class="menu-icon tf-icons bx bx-lock-open-alt"></i>
                                <div data-i18n="Authentications">Authentications</div>
                            </a>
                            <ul class="menu-sub">
                                <li class="menu-item">
                                    <a href="javascript:void(0);" class="menu-link menu-toggle">
                                        <div data-i18n="Login">Login</div>
                                    </a>
                                    <ul class="menu-sub">
                                        <li class="menu-item">
                                            <a href="#" class="menu-link" target="_blank">
                                                <div data-i18n="Basic">Basic</div>
                                            </a>
                                        </li>
                                        <li class="menu-item">
                                            <a href="#" class="menu-link" target="_blank">
                                                <div data-i18n="Cover">Cover</div>
                                            </a>
                                        </li>
                                    </ul>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </li>

                @canany(['Admin', 'Managemen'])
                <li class="menu-item">
                    <a href="javascript:void(0)" class="menu-link">
                        <i class="menu-icon tf-icons bx bxl-sketch"></i>
                        <div data-i18n="Pages">Pages1</div>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="javascript:void(0)" class="menu-link">
                        <i class="menu-icon tf-icons bx bx-diamond"></i>
                        <div data-i18n="Pages">Pages2</div>
                    </a>
                </li>
                @cannot(['Managemen'])
                <li class="menu-item">
                    <a href="javascript:void(0)" class="menu-link">
                        <i class="menu-icon tf-icons bx bxs-contact"></i>
                        <div data-i18n="Pages">Pages3</div>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="javascript:void(0)" class="menu-link">
                        <i class="menu-icon tf-icons bx bxs-factory"></i>
                        <div data-i18n="Pages">Pages4</div>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="javascript:void(0)" class="menu-link">
                        <i class="menu-icon tf-icons bx bx-briefcase"></i>
                        <div data-i18n="Pages">Pages5</div>
                    </a>
                </li>

                <li class="menu-item">
                    <a href="javascript:void(0)" class="menu-link">
                        <i class="menu-icon tf-icons bx bx-paint"></i>
                        <div data-i18n="Pages">Pages6</div>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="javascript:void(0)" class="menu-link">
                        <i class="menu-icon tf-icons bx bx-palette"></i>
                        <div data-i18n="Pages">Pages7</div>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="javascript:void(0)" class="menu-link">
                        <i class="menu-icon tf-icons bx bx-cart"></i>
                        <div data-i18n="Pages">Pages8</div>
                    </a>
                </li>
                @endcannot
                <li class="menu-item">
                    <a href="javascript:void(0)" class="menu-link">
                        <i class="menu-icon tf-icons bx bx-package"></i>
                        <div data-i18n="Pages">Pages9</div>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="javascript:void(0)" class="menu-link">
                        <i class="menu-icon tf-icons bx bx-store"></i>
                        <div data-i18n="Pages">Pages1</div>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="javascript:void(0)" class="menu-link">
                        <i class="menu-icon tf-icons bx bx-money"></i>
                        <div data-i18n="Pages">Pages2</div>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="javascript:void(0)" class="menu-link">
                        <i class="menu-icon tf-icons bx bx-book"></i>
                        <div data-i18n="Pages">Pages3</div>
                    </a>
                </li>


                <li class="menu-item">
                    <a href="javascript:void(0)" class="menu-link">
                        <i class="menu-icon tf-icons bx bxs-truck"></i>
                        <div data-i18n="Pages">Pages4</div>
                    </a>
                </li>

                @endcanany

                <li class="menu-item">
                    <a href="javascript:void(0)" class="menu-link menu-toggle">
                        <i class="menu-icon tf-icons bx bx-layout"></i>
                        <div data-i18n="Layouts">Layouts</div>
                    </a>
                    <ul class="menu-sub">
                        <li class="menu-item">
                            <a href="{{ route('tabel3') }}" class="menu-link">
                                <i class="menu-icon tf-icons bx bx-table"></i>
                                <div data-i18n="Tables">Tables</div>
                            </a>
                        </li>
                        <li class="menu-item">
                            <a href="{{ route('forms_basic') }}" class="menu-link">
                                <i class="menu-icon tf-icons bx bx-detail"></i>
                                <div data-i18n="Form Elements">Form Elements</div>
                            </a>
                        </li>
                    </ul>
                </li>

                @foreach ($menu_categoris as $category)
                <li class="menu-item">
                    <a href="javascript:void(0)" class="menu-link menu-toggle">
                        {!! $category->Icon !!}
                        {{ $category->Name }}
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
<!DOCTYPE html>

<html lang="en" class="light-style" dir="ltr" data-theme="theme-default" data-assets-path="../../assets/" data-template="horizontal-menu-template-dark">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>Dashboard - CRM </title>
    
    
    <link rel="stylesheet" href="assets/sneat/demo.css">
    <!-- Helpers -->
    <script src="assets/sneat/helpers.js.download"></script>
    <script src="assets/sneat/config.js.download"></script>

    <link rel="stylesheet" type="text/css" href="assets/sneat/core.css" class="template-customizer-core-css">
    <link rel="stylesheet" type="text/css" href="assets/sneat/theme-default.css" class="template-customizer-theme-css">
    
    <script async="async" src="assets/sneat/js"></script>

    <script>
    window.dataLayer = window.dataLayer || [];
    function gtag() {
      dataLayer.push(arguments);
    }
    gtag('js', new Date());
    gtag('config', 'GA_MEASUREMENT_ID');
    </script>
  </head>

  <body class="">

    <!-- Layout wrapper -->
    <div class="layout-wrapper layout-navbar-full layout-horizontal layout-without-menu">
      <div class="layout-container">
        <nav class="layout-navbar navbar navbar-expand-xl align-items-center bg-navbar-theme" id="layout-navbar">
          <div class="container-xxl">
            <div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0 d-xl-none d-none">
              <a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)">
                <img src="assets/sneat/1.png" alt="" class="w-px-40 h-auto rounded-circle">
                <i class="bx bx-chevron-left bx-sm align-middle"></i>
              </a>
            </div>
      
            <div class="navbar-brand app-brand demo d-xl-flex py-0 me-4 d-none d-lg-flex">
              <a href="#" class="app-brand-link gap-2">
                <span class="app-brand-text demo menu-text fw-bolder">Sneat V1</span>
              </a>

              <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-xl-none">
                <img src="assets/sneat/1.png" alt="" class="w-px-40 h-auto rounded-circle">
              </a>
            </div>
            <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">

              <ul class="navbar-nav flex-row align-items-center ms-auto">
                <!-- User -->
                <li class="nav-item navbar-dropdown dropdown-user dropdown">
                  <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown">
                    <div class="avatar avatar-online">
                      <img src="assets/sneat/1.png" alt="" class="w-px-40 h-auto rounded-circle">
                    </div>
                  </a>
                </li>
                <!--/ User -->
              </ul>
            </div>
          </div>
        </nav>
        <!-- / Navbar -->

        <!-- Layout container -->
        <div class="layout-page">

          <!-- Content wrapper -->
          <div class="content-wrapper">
            
            <!-- Menu -->
            <aside id="layout-menu" class="layout-menu-horizontal menu menu-horizontal container-fluid flex-grow-0 bg-menu-theme" data-bg-class="bg-menu-theme" style="touch-action: none; user-select: none; -webkit-user-drag: none; -webkit-tap-highlight-color: rgba(0, 0, 0, 0);">
              <div class="container-xxl d-flex h-100">
                <div class="menu-horizontal-wrapper">
                  <ul class="menu-inner" style="margin-left: 0px;">

                    <!-- Dashboards -->
                    <li class="menu-item active">
                      <a href="javascript:void(0)" class="menu-link menu-toggle">
                        <i class="menu-icon tf-icons bx bx-home-circle"></i>
                        <div data-i18n="Dashboards">Dashboards</div>
                      </a>
                      <ul class="menu-sub">
                        <li class="menu-item">
                          <a href="#" class="menu-link">
                            <i class="menu-icon tf-icons bx bx-pie-chart-alt-2"></i>
                            <div data-i18n="Analytics">Analytics</div>
                          </a>
                        </li>
                        <li class="menu-item active">
                          <a href="#" class="menu-link">
                            <i class="menu-icon tf-icons bx bx-shape-circle"></i>
                            <div data-i18n="CRM">CRM</div>
                          </a>
                        </li>
                        <li class="menu-item">
                          <a href="#" class="menu-link">
                            <i class="menu-icon tf-icons bx bx-analyse"></i>
                            <div data-i18n="eCommerce">eCommerce</div>
                          </a>
                        </li>
                      </ul>
                    </li>

                    <!-- Layouts -->
                    <li class="menu-item">
                      <a href="javascript:void(0)" class="menu-link menu-toggle">
                        <i class="menu-icon tf-icons bx bx-layout"></i>
                        <div data-i18n="Layouts">Layouts</div>
                      </a>

                      <ul class="menu-sub">
                        <li class="menu-item">
                          <a href="#" class="menu-link">
                            <i class="menu-icon tf-icons bx bx-menu"></i>
                            <div data-i18n="Without menu">Without menu</div>
                          </a>
                        </li>
                        <li class="menu-item">
                          <a href="#" class="menu-link">
                            <i class="menu-icon tf-icons bx bx-border-bottom"></i>
                            <div data-i18n="Without navbar">Without navbar</div>
                          </a>
                        </li>
                        <li class="menu-item">
                          <a href="#" class="menu-link" target="_blank">
                            <i class="menu-icon tf-icons bx bx-vertical-center"></i>
                            <div data-i18n="Vertical">Vertical</div>
                          </a>
                        </li>
                        <li class="menu-item">
                          <a href="#" class="menu-link">
                            <i class="menu-icon tf-icons bx bx-fullscreen"></i>
                            <div data-i18n="Fluid">Fluid</div>
                          </a>
                        </li>
                        <li class="menu-item">
                          <a href="#" class="menu-link">
                            <i class="menu-icon tf-icons bx bx-exit-fullscreen"></i>
                            <div data-i18n="Container">Container</div>
                          </a>
                        </li>
                        <li class="menu-item">
                          <a href="#" class="menu-link">
                            <i class="menu-icon tf-icons bx bx-square-rounded"></i>
                            <div data-i18n="Blank">Blank</div>
                          </a>
                        </li>
                      </ul>
                    </li>
                  </ul>
                </div>
              </div>
            </aside>
            <!-- / Menu -->
          
            <!-- Content -->
            <div class="container-xxl flex-grow-1 container-p-y"> 
                tesss
            </div>
            <!--/ Content -->

          </div>
          <!--/ Content wrapper -->
        </div>
        <!--/ Layout container -->
      </div>

    </div>
   
    <!-- Overlay -->
    <div class="layout-overlay layout-menu-toggle"></div>
    <script src="assets/sneat/menu.js.download"></script>
    <!-- Main JS -->
    <script src="assets/sneat/main.js.download"></script>
  </body>
</html>
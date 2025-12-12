<!DOCTYPE html>
<html lang="en">

    <head>
        @include('layout.partials.head')
    </head>

    <body id="kt_app_body" data-kt-app-layout="light-sidebar" data-kt-app-header-fixed="true"
        data-kt-app-sidebar-enabled="true" data-kt-app-sidebar-fixed="true" data-kt-app-sidebar-hoverable="true"
        data-kt-app-sidebar-push-header="true" data-kt-app-sidebar-push-toolbar="true"
        data-kt-app-sidebar-push-footer="true" data-kt-app-toolbar-enabled="true" class="app-default">

        <script>
            var defaultThemeMode = "light";
            var themeMode;
            if (document.documentElement) {
                if (document.documentElement.hasAttribute("data-bs-theme-mode")) {
                    themeMode = document.documentElement.getAttribute("data-bs-theme-mode");
                } else {
                    if (localStorage.getItem("data-bs-theme") !== null) {
                        themeMode = localStorage.getItem("data-bs-theme");
                    } else {
                        themeMode = defaultThemeMode;
                    }
                }
                if (themeMode === "system") {
                    themeMode = window.matchMedia("(prefers-color-scheme: dark)").matches ? "dark" : "light";
                }
                document.documentElement.setAttribute("data-bs-theme", themeMode);
            }
        </script>

        <div class="d-flex flex-column flex-root app-root" id="kt_app_root">
            <div class="app-page flex-column flex-column-fluid" id="kt_app_page">
                <!-- Header -->
                <div id="kt_app_header" class="app-header" data-kt-sticky="true"
                    data-kt-sticky-activate="{default: true, lg: true}" data-kt-sticky-name="app-header-minimize"
                    data-kt-sticky-offset="{default: '200px', lg: '0'}" data-kt-sticky-animation="false">
                    <div class="app-container container-fluid d-flex align-items-stretch justify-content-between">
                        <div class="d-flex align-items-center d-lg-none ms-n3 me-1 me-md-2" title="Show sidebar menu">
                            <div class="btn btn-icon btn-active-color-primary w-35px h-35px"
                                id="kt_app_sidebar_mobile_toggle">
                                <i class="ki-duotone ki-abstract-14 fs-2 fs-md-1">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                </i>
                            </div>
                        </div>

                        <div class="d-flex align-items-center flex-grow-1 flex-lg-grow-0">
                            <a href="{{ route('superadmin.dashboard') }}" class="d-lg-none">
                                <img alt="Logo" src="{{ asset('images/logo.png') }}" class="h-30px" />
                            </a>
                        </div>

                        <div class="d-flex align-items-stretch justify-content-between flex-lg-grow-1">
                            <div class="d-flex align-items-stretch" id="kt_app_header_wrapper">
                                <div class="app-header-menu app-header-mobile-drawer align-items-stretch">
                                    <div class="d-flex align-items-center ms-1 ms-lg-3">
                                        <span class="badge badge-light-danger fs-6">SUPERADMIN</span>
                                    </div>
                                </div>
                            </div>

                            <div class="app-navbar flex-shrink-0">
                                <div class="app-navbar-item ms-1 ms-md-4" id="kt_header_user_menu_toggle">
                                    <div class="cursor-pointer symbol symbol-35px"
                                        data-kt-menu-trigger="{default: 'click', lg: 'hover'}"
                                        data-kt-menu-attach="parent" data-kt-menu-placement="bottom-end">
                                        <div class="symbol-label fs-6 fw-bold text-success">
                                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div>
                                    </div>

                                    <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg menu-state-color fw-semibold py-4 fs-6 w-275px"
                                        data-kt-menu="true">
                                        <div class="menu-item px-3">
                                            <div class="menu-content d-flex align-items-center px-3">
                                                <div class="symbol symbol-50px me-5">
                                                    <div class="symbol-label fs-3 fw-bold text-success">
                                                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div>
                                                </div>
                                                <div class="d-flex flex-column">
                                                    <div class="fw-bold d-flex align-items-center fs-5">
                                                        {{ auth()->user()->name }}
                                                    </div>
                                                    <a class="fw-semibold text-muted text-hover-primary fs-7">
                                                        {{ auth()->user()->email }}
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="separator my-2"></div>
                                        <div class="menu-item px-5">
                                            <form method="POST" action="{{ route('logout') }}">
                                                @csrf
                                                <button type="submit"
                                                    class="menu-link px-5 btn btn-link text-start w-100">
                                                    Sign Out
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="app-wrapper flex-column flex-row-fluid" id="kt_app_wrapper">
                    <!-- Sidebar -->
                    <div id="kt_app_sidebar" class="app-sidebar flex-column" data-kt-drawer="true"
                        data-kt-drawer-name="app-sidebar" data-kt-drawer-activate="{default: true, lg: false}"
                        data-kt-drawer-overlay="true" data-kt-drawer-width="225px" data-kt-drawer-direction="start"
                        data-kt-drawer-toggle="#kt_app_sidebar_mobile_toggle">
                        <div class="app-sidebar-logo px-6" id="kt_app_sidebar_logo">
                            <a href="{{ route('superadmin.dashboard') }}">
                                <img alt="Logo" src="{{ asset('images/logo.png') }}"
                                    class="h-25px app-sidebar-logo-default" />
                                <img alt="Logo" src="{{ asset('images/logo.png') }}"
                                    class="h-20px app-sidebar-logo-minimize" />
                            </a>
                            <div id="kt_app_sidebar_toggle"
                                class="app-sidebar-toggle btn btn-icon btn-shadow btn-sm btn-color-muted btn-active-color-primary h-30px w-30px position-absolute top-50 start-100 translate-middle rotate"
                                data-kt-toggle="true" data-kt-toggle-state="active" data-kt-toggle-target="body"
                                data-kt-toggle-name="app-sidebar-minimize">
                                <i class="ki-duotone ki-black-left-line fs-3 rotate-180">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                </i>
                            </div>
                        </div>

                        <div class="app-sidebar-menu overflow-hidden flex-column-fluid">
                            <div id="kt_app_sidebar_menu_wrapper" class="app-sidebar-wrapper">
                                <div id="kt_app_sidebar_menu_scroll" class="scroll-y my-5 mx-3" data-kt-scroll="true"
                                    data-kt-scroll-activate="true" data-kt-scroll-height="auto"
                                    data-kt-scroll-dependencies="#kt_app_sidebar_logo, #kt_app_sidebar_footer"
                                    data-kt-scroll-wrappers="#kt_app_sidebar_menu" data-kt-scroll-offset="5px"
                                    data-kt-scroll-save-state="true">
                                    <div class="menu menu-column menu-rounded menu-sub-indention fw-semibold fs-6"
                                        id="#kt_app_sidebar_menu" data-kt-menu="true" data-kt-menu-expand="false">

                                        <div class="menu-item">
                                            <a class="menu-link {{ request()->routeIs('superadmin.dashboard') ? 'active' : '' }}"
                                                href="{{ route('superadmin.dashboard') }}">
                                                <span class="menu-icon">
                                                    <i class="ki-duotone ki-element-11 fs-2">
                                                        <span class="path1"></span>
                                                        <span class="path2"></span>
                                                        <span class="path3"></span>
                                                        <span class="path4"></span>
                                                    </i>
                                                </span>
                                                <span class="menu-title">Dashboard</span>
                                            </a>
                                        </div>

                                        <div class="menu-item">
                                            <a class="menu-link {{ request()->routeIs('superadmin.organizations.*') ? 'active' : '' }}"
                                                href="{{ route('superadmin.organizations.index') }}">
                                                <span class="menu-icon">
                                                    <i class="ki-duotone ki-home fs-2">
                                                        <span class="path1"></span>
                                                        <span class="path2"></span>
                                                    </i>
                                                </span>
                                                <span class="menu-title">Organizations</span>
                                            </a>
                                        </div>

                                        <div class="menu-item">
                                            <a class="menu-link {{ request()->routeIs('superadmin.users.*') ? 'active' : '' }}"
                                                href="{{ route('superadmin.users.index') }}">
                                                <span class="menu-icon">
                                                    <i class="ki-duotone ki-people fs-2">
                                                        <span class="path1"></span>
                                                        <span class="path2"></span>
                                                        <span class="path3"></span>
                                                        <span class="path4"></span>
                                                        <span class="path5"></span>
                                                    </i>
                                                </span>
                                                <span class="menu-title">Users</span>
                                            </a>
                                        </div>

                                        <div class="menu-item">
                                            <a class="menu-link {{ request()->routeIs('superadmin.subscriptions.*') ? 'active' : '' }}"
                                                href="{{ route('superadmin.subscriptions.index') }}">
                                                <span class="menu-icon">
                                                    <i class="ki-duotone ki-credit-cart fs-2">
                                                        <span class="path1"></span>
                                                        <span class="path2"></span>
                                                    </i>
                                                </span>
                                                <span class="menu-title">Subscriptions</span>
                                            </a>
                                        </div>

                                        <div class="menu-item">
                                            <a class="menu-link {{ request()->routeIs('superadmin.revenue.*') ? 'active' : '' }}"
                                                href="{{ route('superadmin.revenue.index') }}">
                                                <span class="menu-icon">
                                                    <i class="ki-duotone ki-chart-line fs-2">
                                                        <span class="path1"></span>
                                                        <span class="path2"></span>
                                                    </i>
                                                </span>
                                                <span class="menu-title">Laporan Pendapatan</span>
                                            </a>
                                        </div>

                                        <div class="menu-item">
                                            <a class="menu-link {{ request()->routeIs('superadmin.settings.*') ? 'active' : '' }}"
                                                href="{{ route('superadmin.settings.index') }}">
                                                <span class="menu-icon">
                                                    <i class="ki-duotone ki-setting-2 fs-2">
                                                        <span class="path1"></span>
                                                        <span class="path2"></span>
                                                    </i>
                                                </span>
                                                <span class="menu-title">Settings</span>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Main Content -->
                    <div class="app-main flex-column flex-row-fluid" id="kt_app_main">
                        <div class="d-flex flex-column flex-column-fluid">
                            <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
                                <div id="kt_app_toolbar_container"
                                    class="app-container container-fluid d-flex flex-stack">
                                    <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                                        <h1
                                            class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">
                                            @yield('title', 'Dashboard')
                                        </h1>
                                    </div>
                                </div>
                            </div>

                            <div id="kt_app_content" class="app-content flex-column-fluid">
                                <div id="kt_app_content_container" class="app-container container-fluid">
                                    @yield('content')
                                </div>
                            </div>
                        </div>

                        @include('layout.partials.footer')
                    </div>
                </div>
            </div>
        </div>

        @include('layout.partials.scripts')
    </body>

</html>

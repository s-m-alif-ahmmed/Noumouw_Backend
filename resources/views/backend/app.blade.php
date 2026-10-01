<!DOCTYPE html>
<html lang="en" class="light scroll-smooth group" data-layout="vertical" data-sidebar="light" data-sidebar-size="lg"
    data-mode="light" data-topbar="light" data-skin="default" data-navbar="sticky" data-content="fluid" dir="ltr">

<head>
    <!-- Meta Information -->
    <meta charset="utf-8">
    <title>@yield('title') | {{ config('app.name') }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
    <!-- App favicon -->
    <link rel="shortcut icon"
        href="{{ App\Models\SystemSetting::first()?->favicon ? asset(App\Models\SystemSetting::first()?->favicon) : asset('/backend/favicon_logo.png') }}">
    @include('backend.partials.style')
</head>

<body
    class="text-base bg-body-bg text-body font-public dark:text-zink-100 dark:bg-zink-800 group-data-[skin=bordered]:bg-body-bordered group-data-[skin=bordered]:dark:bg-zink-700">

    <div class="group-data-[sidebar-size=sm]:min-h-sm group-data-[sidebar-size=sm]:relative">

        <!-- Left Sidebar -->
        @include('backend.partials.left_sidebar')
        <div id="sidebar-overlay" class="absolute inset-0 z-[1002] bg-slate-500/30 hidden"></div>

        <!-- Header -->
        @include('backend.partials.header')

        <!-- Right Sidebar -->
        @include('backend.partials.right_sidebar')

        <div class="relative min-h-screen group-data-[sidebar-size=sm]:min-h-sm">

            <div id="main-content-wrapper"
                class="group-data-[sidebar-size=lg]:ltr:md:ml-vertical-menu group-data-[sidebar-size=lg]:rtl:md:mr-vertical-menu group-data-[sidebar-size=md]:ltr:ml-vertical-menu-md group-data-[sidebar-size=md]:rtl:mr-vertical-menu-md group-data-[sidebar-size=sm]:ltr:ml-vertical-menu-sm group-data-[sidebar-size=sm]:rtl:mr-vertical-menu-sm pt-[calc(theme('spacing.header')_*_1)] pb-[calc(theme('spacing.header')_*_0.8)] px-4 group-data-[navbar=bordered]:pt-[calc(theme('spacing.header')_*_1.3)] group-data-[navbar=hidden]:pt-0 group-data-[layout=horizontal]:mx-auto group-data-[layout=horizontal]:max-w-screen-2xl group-data-[layout=horizontal]:px-0 group-data-[layout=horizontal]:group-data-[sidebar-size=lg]:ltr:md:ml-auto group-data-[layout=horizontal]:group-data-[sidebar-size=lg]:rtl:md:mr-auto group-data-[layout=horizontal]:md:pt-[calc(theme('spacing.header')_*_1.6)] group-data-[layout=horizontal]:px-3 group-data-[layout=horizontal]:group-data-[navbar=hidden]:pt-[calc(theme('spacing.header')_*_0.9)]">
                <div class="container-fluid group-data-[content=boxed]:max-w-boxed mx-auto">

                    <!-- Premium Breadcrumb & Title -->
                    @if (Route::currentRouteName() != 'dashboard')
                        <div class="flex flex-col md:flex-row md:items-center justify-between py-6 gap-4 print:hidden">
                            <div class="grow">
                                <h5 class="text-xl font-bold text-slate-800 tracking-tight">@yield('title')</h5>
                                <div
                                    class="flex items-center gap-2 mt-1 text-xs font-medium text-slate-400 uppercase tracking-widest">
                                    <a href="{{ route('dashboard') }}"
                                        class="hover:text-blue-600 transition-colors">Home</a>
                                    <span class="size-1 rounded-full bg-slate-300"></span>
                                    <span class="text-slate-600">@yield('title')</span>
                                </div>
                            </div>
                            {{-- <div class="flex items-center gap-3">
                                <div
                                    class="px-4 py-2 bg-white rounded-xl border border-slate-100 shadow-sm flex items-center gap-3">
                                    <div class="flex items-center gap-2 text-sm font-semibold text-slate-700">
                                        <i data-lucide="layout-grid" class="size-4 text-blue-500"></i>
                                        @yield('title_url')
                                    </div>
                                    <div class="w-px h-4 bg-slate-200"></div>
                                    <div class="flex items-center gap-2 text-sm font-medium text-slate-500">
                                        <i data-lucide="home" class="size-4"></i>
                                        @yield('tabName')
                                    </div>
                                </div>
                            </div> --}}
                        </div>
                    @endif

                    <!-- Main Content Start -->
                    @yield('content')
                    <!-- Main Content  End -->


                </div>
                <!-- container-fluid -->
            </div>
            <!-- End Page-content -->

            <!-- Footer -->
            @include('backend.partials.footer')
        </div>

    </div>
    <!-- end main content -->

    <!-- Custom Scripts -->
    {{-- @include('backend.partials.customize') --}}

    @include('backend.partials.script')

</body>

</html>

<div
    class="app-menu w-vertical-menu bg-vertical-menu ltr:border-r rtl:border-l border-vertical-menu-border fixed bottom-0 top-0 z-[1003] transition-all duration-300 ease-in-out group-data-[sidebar-size=md]:w-vertical-menu-md group-data-[sidebar-size=sm]:w-vertical-menu-sm group-data-[sidebar-size=sm]:pt-header group-data-[sidebar=dark]:bg-vertical-menu-dark group-data-[sidebar=dark]:border-vertical-menu-dark group-data-[sidebar=brand]:bg-vertical-menu-brand group-data-[sidebar=brand]:border-vertical-menu-brand group-data-[sidebar=modern]:bg-gradient-to-tr group-data-[sidebar=modern]:to-vertical-menu-to-modern group-data-[sidebar=modern]:from-vertical-menu-form-modern group-data-[layout=horizontal]:w-full group-data-[layout=horizontal]:bottom-auto group-data-[layout=horizontal]:top-header hidden md:block print:hidden group-data-[sidebar-size=sm]:absolute group-data-[sidebar=modern]:border-vertical-menu-border-modern group-data-[layout=horizontal]:dark:bg-zink-700 group-data-[layout=horizontal]:border-t group-data-[layout=horizontal]:dark:border-zink-500 group-data-[layout=horizontal]:border-r-0 group-data-[sidebar=dark]:dark:bg-zink-700 group-data-[sidebar=dark]:dark:border-zink-600 group-data-[layout=horizontal]:group-data-[navbar=scroll]:absolute group-data-[layout=horizontal]:group-data-[navbar=bordered]:top-[calc(theme('spacing.header')_+_theme('spacing.4'))] group-data-[layout=horizontal]:group-data-[navbar=bordered]:inset-x-4 group-data-[layout=horizontal]:group-data-[navbar=hidden]:top-0 group-data-[layout=horizontal]:group-data-[navbar=hidden]:h-16 group-data-[layout=horizontal]:group-data-[navbar=bordered]:w-[calc(100%_-_2rem)] group-data-[layout=horizontal]:group-data-[navbar=bordered]:[&.sticky]:top-header group-data-[layout=horizontal]:group-data-[navbar=bordered]:rounded-b-md group-data-[layout=horizontal]:shadow-md group-data-[layout=horizontal]:shadow-slate-500/10 group-data-[layout=horizontal]:dark:shadow-zink-500/10 group-data-[layout=horizontal]:opacity-0">
    <div
        class="flex items-center justify-center px-5 text-center h-header group-data-[layout=horizontal]:hidden group-data-[sidebar-size=sm]:fixed group-data-[sidebar-size=sm]:top-0 group-data-[sidebar-size=sm]:bg-vertical-menu group-data-[sidebar-size=sm]:group-data-[sidebar=dark]:bg-vertical-menu-dark group-data-[sidebar-size=sm]:group-data-[sidebar=brand]:bg-vertical-menu-brand group-data-[sidebar-size=sm]:group-data-[sidebar=modern]:bg-gradient-to-br group-data-[sidebar-size=sm]:group-data-[sidebar=modern]:to-vertical-menu-to-modern group-data-[sidebar-size=sm]:group-data-[sidebar=modern]:from-vertical-menu-form-modern group-data-[sidebar-size=sm]:group-data-[sidebar=modern]:bg-vertical-menu-modern group-data-[sidebar-size=sm]:z-10 group-data-[sidebar-size=sm]:w-[calc(theme('spacing.vertical-menu-sm')_-_1px)] group-data-[sidebar-size=sm]:group-data-[sidebar=dark]:dark:bg-zink-700">
        {{-- Full Logo - visible when sidebar expanded --}}
        <a href="{{ route('dashboard') }}"
            class="sidebar-logo-full group-data-[sidebar=dark]:hidden group-data-[sidebar=brand]:hidden group-data-[sidebar=modern]:hidden">
            <span class="">
                <img src=" {{ App\Models\SystemSetting::first()?->logo ? asset(App\Models\SystemSetting::first()?->logo) : asset('/logo.png') }}"
                    class="max-h-[50px]" alt="Logo">
            </span>
        </a>
        <a href="{{ route('dashboard') }}"
            class="sidebar-logo-full hidden group-data-[sidebar=dark]:block group-data-[sidebar=brand]:block group-data-[sidebar=modern]:block">
            <span>
                <img src=" {{ App\Models\SystemSetting::first()?->logo ? asset(App\Models\SystemSetting::first()?->logo) : asset('/backend/favicon_logo.png') }}"
                    class="max-h-[50px]" alt="Logo">
            </span>
        </a>
        {{-- Favicon - visible when sidebar collapsed --}}
        <a href="{{ route('dashboard') }}" class="sidebar-logo-favicon hidden">
            <img src="{{ App\Models\SystemSetting::first()?->favicon ? asset(App\Models\SystemSetting::first()?->favicon) : asset('/backend/favicon_logo.png') }}"
                class="w-8 h-8 object-contain rounded" alt="Favicon">
        </a>
        <button type="button" class="hidden p-0 float-end" id="vertical-hover">
            <i class="ri-record-circle-line"></i>
        </button>
    </div>

    <div id="scrollbar" class="h-[calc(100%-4.375rem)] overflow-y-auto overflow-x-hidden">
        <div class="px-2 py-4">
            <ul class="space-y-1" id="navbar-nav">

                <li class="menu-title sidebar-menu-title">Main Dashboard</li>

                {{-- Dashboard Menu  --}}
                <x-backend.nav-link :link="route('dashboard')" :active="request()->routeIs('dashboard')" title="Dashboard">
                    <i data-lucide="layout-dashboard"></i>
                </x-backend.nav-link>

                {{-- User Management --}}
                <x-backend.nav-link :link="route('user.index')" :active="request()->routeIs('user.*')" title="User Management">
                    <i data-lucide="users"></i>
                </x-backend.nav-link>

                {{-- Get Start Menu  --}}
                <x-backend.nav-link :link="route('get-start.index')" :active="request()->routeIs('get-start.index')" title="Get start">
                    <i data-lucide="play"
                        class="h-4 group-data-[sidebar-size=sm]:h-5 group-data-[sidebar-size=sm]:w-5 transition group-hover/menu-link:animate-icons fill-slate-100 group-hover/menu-link:fill-blue-200 group-data-[sidebar=dark]:dark:fill-zink-600 group-data-[layout=horizontal]:dark:fill-zink-600 group-data-[sidebar=dark]:group-hover/menu-link:dark:fill-custom-500/20 group-data-[layout=horizontal]:dark:group-hover/menu-link:fill-custom-500/20 group-data-[sidebar-size=md]:block group-data-[sidebar-size=md]:mx-auto group-data-[sidebar-size=md]:mb-2"></i>
                </x-backend.nav-link>

                <li class="menu-title sidebar-menu-title">Learning Management</li>

                {{-- Learning Core --}}
                <x-backend.nav-dropdown :active="request()->routeIs([
                    'course.*',
                    'video.*',
                    'category.*',
                    'tag.*',
                    'instructor.*',
                    'ratings.*',
                ])" title="Courses" dataKey="t-course">
                    <x-slot:icon><i data-lucide="book-open"></i></x-slot:icon>

                    <x-backend.nav-dropdown-link :link="route('course.index_new')" :active="request()->routeIs('course.*')" title="All Courses" />
                    <x-backend.nav-dropdown-link :link="route('category.index')" :active="request()->routeIs('category.*')" title="Categories" />
                    <x-backend.nav-dropdown-link :link="route('tag.index')" :active="request()->routeIs('tag.*')" title="Tags" />
                    <x-backend.nav-dropdown-link :link="route('instructor.index')" :active="request()->routeIs('instructor.*')" title="Instructors" />
                    <x-backend.nav-dropdown-link :link="route('ratings.index')" :active="request()->routeIs('ratings.*')" title="Reviews & Ratings" />
                </x-backend.nav-dropdown>

                {{-- Subscriptions --}}
                <x-backend.nav-link :link="route('subscription.index')" :active="request()->routeIs('subscription.*')" title="Subscription Plans">
                    <i data-lucide="credit-card"></i>
                </x-backend.nav-link>

                {{-- Questions --}}
                <x-backend.nav-link :link="route('question.index')" :active="request()->routeIs('question.*')" title="Question Bank">
                    <i data-lucide="help-circle"></i>
                </x-backend.nav-link>

                <li class="menu-title sidebar-menu-title">Communication</li>

                {{-- Push notification --}}
                <x-backend.nav-link :link="route('push-notification.index')" :active="request()->routeIs('push-notification.*')" title="Push Notifications">
                    <i data-lucide="send"></i>
                </x-backend.nav-link>

                {{-- Support --}}
                <x-backend.nav-link :link="route('support.tickets')" :active="request()->routeIs('support.*')" title="Support Tickets">
                    <i data-lucide="life-buoy"></i>
                </x-backend.nav-link>

                <li class="menu-title sidebar-menu-title">System Settings</li>

                <x-backend.nav-dropdown :active="request()->routeIs(['setting.*', 'dynamic-page.*'])" title="Settings" dataKey="t-settings">
                    <x-slot:icon><i data-lucide="settings"></i></x-slot:icon>
                    <x-backend.nav-dropdown-link :link="route('setting.profile.index')" :active="request()->routeIs('setting.profile.*')" title="My Profile" />
                    <x-backend.nav-dropdown-link :link="route('setting.system.index')" :active="request()->routeIs('setting.system.*')" title="System Info" />
                    <x-backend.nav-dropdown-link :link="route('setting.configuration.index')" :active="request()->routeIs('setting.configuration.*')" title="Configuration" />
                    <x-backend.nav-dropdown-link :link="route('dynamic-page.index')" :active="request()->routeIs('dynamic-page.*')" title="Dynamic Page" />
                </x-backend.nav-dropdown>
            </ul>
        </div>
    </div>
    <!-- Sidebar -->
</div>
</div>
<!-- Left Sidebar End -->

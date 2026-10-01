@stack('styles_top')
  <script>
      (function() {
          try {
              if (sessionStorage.getItem('sidebar-collapsed') === 'true') {
                  document.documentElement.classList.add('sidebar-collapsed');
              }
          } catch (error) {
              // Storage can be unavailable in restricted browser contexts.
          }
      })();
  </script>
  <!-- Layout config Js -->
    <script src="{{asset('backend/js/layout.js')}}"></script>

    <!-- Tailwind CSS -->
    <link rel="stylesheet" href="{{asset('backend/css/tailwind2.css')}}">
    <link rel="stylesheet"href="https://cdn.jsdelivr.net/gh/habibmhamadi/multi-select-tag@3.1.0/dist/css/multi-select-tag.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/nprogress/0.2.0/nprogress.min.css" integrity="sha512-42kB9yDlYiCEfx2xVwq0q7hT4uf26FUgSIZBK8uiaEnTdShXjwr8Ip1V4xGJMg3mHkUt9nNuTDxunHF0/EgxLQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    @vite('resources/css/app.css')
    <style>
        .fl-wrapper {
            z-index: 999999 !important;
        }

        /* Modern Sidebar Styling */
        .app-menu {
            background: #ffffff !important;
            border-right: 1px solid #f1f5f9 !important;
            box-shadow: 4px 0 24px rgba(0, 0, 0, 0.02) !important;
            transition: width 0.3s cubic-bezier(0.4, 0, 0.2, 1) !important;
        }

        [data-sidebar="dark"] .app-menu {
            background: #0f172a !important;
            border-right: 1px solid #1e293b !important;
        }

        .menu-link {
            margin: 4px 12px !important;
            border-radius: 12px !important;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1) !important;
            color: #64748b !important;
            font-weight: 500 !important;
        }

        .menu-link:hover {
            background: #f8fafc !important;
            color: #3b82f6 !important;
            transform: translateX(4px);
        }

        .menu-link.active {
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%) !important;
            color: #ffffff !important;
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.2) !important;
        }

        .menu-link.active i {
            color: #ffffff !important;
        }

        .menu-title {
            padding: 24px 24px 8px !important;
            font-size: 11px !important;
            text-transform: uppercase !important;
            letter-spacing: 0.05em !important;
            color: #94a3b8 !important;
            font-weight: 700 !important;
        }

        .nav-icon {
            transition: transform 0.3s ease !important;
        }

        .menu-link:hover .nav-icon {
            transform: scale(1.1);
        }

        /* Sidebar Logo Area */
        .logo-box {
            height: 80px !important;
            border-bottom: 1px solid #f1f5f9 !important;
            margin-bottom: 16px !important;
        }

        [data-sidebar="dark"] .logo-box {
            border-bottom: 1px solid #1e293b !important;
        }

        /* ========== Collapsed Sidebar Styles (custom class approach) ========== */

        /* Sidebar width when collapsed */
        .sidebar-collapsed .app-menu {
            width: 4.5rem !important;
        }

        /* Header left offset when collapsed */
        .sidebar-collapsed #page-topbar {
            left: 4.5rem !important;
        }

        /* Main content margin when collapsed */
        .sidebar-collapsed #main-content-wrapper {
            margin-left: 4.5rem !important;
        }

        /* Footer left offset when collapsed */
        .sidebar-collapsed footer {
            left: 4.5rem !important;
        }

        /* Smooth transition on content area */
        #main-content-wrapper {
            transition: margin-left 0.3s cubic-bezier(0.4, 0, 0.2, 1) !important;
        }

        /* Hide full logo, show favicon */
        .sidebar-collapsed .sidebar-logo-full {
            display: none !important;
        }

        .sidebar-collapsed .sidebar-logo-favicon {
            display: flex !important;
            align-items: center;
            justify-content: center;
        }

        /* Hide menu section titles */
        .sidebar-collapsed .sidebar-menu-title {
            display: none !important;
        }

        /* Hide nav text labels */
        .sidebar-collapsed .sidebar-nav-text {
            display: none !important;
        }

        /* Hide dropdown chevron arrows */
        .sidebar-collapsed .sidebar-dropdown-arrow {
            display: none !important;
        }

        /* Hide dropdown sub-menus */
        .sidebar-collapsed .sidebar-dropdown-content {
            display: none !important;
        }

        /* ========== Flyout Menu for Collapsed Sidebar ========== */
        .sidebar-collapsed .sidebar-dropdown-content,
        html.sidebar-collapsed .sidebar-dropdown-content,
        .group-data-[sidebar-size=sm] .sidebar-dropdown-content {
            position: fixed !important;
            left: calc(var(--sidebar-width-sm, 70px) + 0.5rem) !important; 
            top: auto !important;
            background: white;
            border-radius: 0.75rem;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
            padding: 0.5rem 0;
            min-width: 200px;
            z-index: 1050;
            display: none !important;
            transform: translateY(-1.5rem) !important;
        }

        .sidebar-collapsed .dropdown-content.sidebar-dropdown-content.show-flyout,
        html.sidebar-collapsed .dropdown-content.sidebar-dropdown-content.show-flyout,
        .group-data-[sidebar-size=sm] .dropdown-content.sidebar-dropdown-content.show-flyout {
            display: block !important;
        }

        .sidebar-collapsed .sidebar-dropdown-arrow,
        html.sidebar-collapsed .sidebar-dropdown-arrow,
        .group-data-[sidebar-size=sm] .sidebar-dropdown-arrow {
            display: none;
        }

        /* Center nav link icons */
        .sidebar-collapsed .sidebar-nav-link {
            justify-content: center !important;
            padding-left: 0.5rem !important;
            padding-right: 0.5rem !important;
            margin-left: 0.25rem !important;
            margin-right: 0.25rem !important;
        }

        /* Remove min-width on icons so they center properly */
        .sidebar-collapsed .nav-icon {
            min-width: unset !important;
        }

        /* Custom scrollbar styling and heights */
        #scrollbar {
            height: calc(100% - 4.375rem) !important;
            overflow-y: auto !important;
            overflow-x: hidden !important;
            scrollbar-width: thin;
            scrollbar-color: #cbd5e1 transparent;
        }

        [data-sidebar="dark"] #scrollbar {
            scrollbar-color: #475569 transparent;
        }

        /* Custom scrollbar for Webkit (Chrome, Safari, Edge) */
        #scrollbar::-webkit-scrollbar {
            width: 6px;
        }

        #scrollbar::-webkit-scrollbar-track {
            background: transparent;
        }

        #scrollbar::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 3px;
        }

        [data-sidebar="dark"] #scrollbar::-webkit-scrollbar-thumb {
            background: #475569;
        }

        #scrollbar::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }

        /* Prevent horizontal overflow */
        .sidebar-collapsed #scrollbar {
            overflow-x: hidden !important;
        }

        /* Disable translateX hover when collapsed */
        .sidebar-collapsed .menu-link:hover {
            transform: none !important;
        }

        /* Smooth transition helpers */
        .sidebar-nav-text {
            transition: opacity 0.2s ease;
            white-space: nowrap;
            overflow: hidden;
        }

        .sidebar-dropdown-arrow {
            transition: opacity 0.2s ease, transform 0.3s ease;
        }

        .sidebar-logo-full,
        .sidebar-logo-favicon {
            transition: opacity 0.2s ease;
        }

        /* Ensure the main content area transitions smoothly */
        #page-topbar {
            transition: left 0.3s cubic-bezier(0.4, 0, 0.2, 1) !important;
        }

        /* Dropdown arrow rotation — uses .show class toggled by our custom handler */
        .dropdown-button.show .sidebar-dropdown-arrow {
            transform: rotate(180deg);
        }
    </style>
@stack('styles')

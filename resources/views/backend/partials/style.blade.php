@stack('styles_top')
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
    </style>
@stack('styles')


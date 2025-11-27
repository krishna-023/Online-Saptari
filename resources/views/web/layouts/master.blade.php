@include('web.layouts.header')

<body class="d-flex flex-column min-vh-100">

    {{-- Page Content --}}
    <main class="flex-grow-1">
        @yield('content')
    </main>

    {{-- Footer --}}
    @include('web.layouts.footer')

    {{-- CSS/JS libraries (keep non-duplicated includes only) --}}
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

    {{-- Site scripts (depends on jQuery which is loaded by header) --}}
    <script src="{{ asset('web/js/jquery_custom.js') }}"></script>
    <script src="{{ asset('web/js/typed.js') }}"></script>
    @yield('script')
</body>

<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let timeout = null;
            const searchInputs = document.querySelectorAll('.real-time-search');

            searchInputs.forEach(input => {
                input.addEventListener('input', function() {
                    clearTimeout(timeout);
                    timeout = setTimeout(() => {
                        this.closest('form').submit();
                    }, 100);
                });

                // Set focus at the end of the input if there's text
                if (input.value.length > 0) {
                    input.focus();
                    const val = input.value;
                    input.value = '';
                    input.value = val;
                }
            });
        });
    </script>
</head>
<body class="bg-white">
    <div id="app">

        @include('layouts.partials.nav')

        <main class="py-4">
            @yield('content')
        </main>
    </div>
    @include('sweetalert::alert')
    @stack('scripts')
</body>
</html>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>SPACEBJ</title>
    <link href={{ asset('css/outerSpace.css') }} rel="stylesheet">
    <!-- Favicon links -->
    <link rel="icon" href="/favicon.ico" sizes="any"> <!-- Fallback for older browsers -->
    <link rel="icon" href="/images/logo/dibujo.svg" type="image/svg+xml"> <!-- Your SVG favicon -->


    @livewireStyles

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>
    <script src="{{ asset('js/customsbp.js') }}" defer></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" crossorigin="anonymous"></script>
    <script type="text/javascript"
        src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.1/jquery.validate.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"
        integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>

</head>

<body>
    {{-- <x-jet-banner /> --}}

    <canvas></canvas>

    <div class="wrapper">
        <div class="wrapper-content">

            {{-- <!-- Page Heading --> --}}
            <x-header />

            <!-- Page Content -->
            <main class="content">
                {{ $slot }}
            </main>

        </div>
    </div>
    @stack('modals')
    @livewireScripts
</body>

</html>
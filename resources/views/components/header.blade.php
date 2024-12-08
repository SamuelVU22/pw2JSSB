<header class="header">

    @php
        $path = Request::path();
        $path = substr($path, 3);
        // dd($path)
    @endphp

    <x-spacebpLogo url="{{ Request::root() }}/outerSpace" />

    <x-nav-bar />



    <x-mobileMenu path={{$path}} />

    @if (!Auth::check())
        
        <x-signButtons />
    @endif

</header>
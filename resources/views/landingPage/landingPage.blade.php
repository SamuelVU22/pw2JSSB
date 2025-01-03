<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description"
        content="A web page where you can learn and know about the latest news about the outer space.">
    <title>SpaceBJ</title>
    <link href={{ asset('css/landingPage.css') }} rel="stylesheet">
    <script src="{{ asset('js/customsbp.js') }}" defer></script>

</head>

<body>
    <div class="wrapper">

        <x-bgVideo />
        <div class="wrapper-content">
            @php
                $path = Request::path();
                $path = substr($path, 3);
                
            @endphp
            <header class="header">

                <x-spacebpLogo :url="Request::root()" />


                <x-mobileMenu path={{$path}} />

                <x-signButtons />
            </header>

            <section id="content">
                <div class="content-name">
                    <h1>TRAVEL TO OUTER SPACE WITH SPACEBJ</h1>
                </div>
                <div class="content-description">
                    <p>SpaceBJ is a web page where you can learn, share knowledge, know about the latest news, and many other things about the space.</p>
                </div>
                <div class="content-explore">
                    <a href="{{-- route('outerspace', app()->getLocale()) --}}" id="explore">Explore</a>
                </div>
            </section>
        </div>
    </div>
</body>


</html>
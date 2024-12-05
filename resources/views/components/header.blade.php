<header class="header">

    @php
        $path = Request::path();
        $path = substr($path, 3);
        // dd($path)
    @endphp

    <x-spacebpLogo url="{{ Request::root() }}/outerSpace" />

    <x-nav-bar />



    <x-mobileMenu path={{$path}} />

    @if (Auth::check())
        @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
            <div class="wrapper-profile">

                <div class="dropdown" id="profileMenu">
                    <img id="profile" src="{{ Auth::user()->profile_photo_url }}" alt="{{ Auth::user()->name }}" />

                    <div class="dropdown-content">

                        @if (Auth::user()->idRole == 1 || Auth::user()->idRole == 2)
                            <a class="dropdown-item-0" href="{{ route('user.show') }}">
                                {{ __('Manage users') }}
                            </a>
                        @endif
                        <a class="dropdown-item-1" href="{{ route('profile.show')}}">
                            {{ __('Profile') }}
                        </a>

                        <a class="dropdown-item-2" href="{{ route('news.showSavedNews') }}">
                            {{ __('News saved') }}
                        </a>

                        <a class="dropdown-item-3"
                            href="{{ route('gallery.showSavedPictures') }}">
                            {{ __('Pictures saved') }}
                        </a>

                        <form class="dropdown-item-4" method="POST" action="{{ route('logout') }}"
                            x-data>
                            @csrf

                            <x-dropdown-link href="{{ route('logout') }}"
                                @click.prevent="$root.submit();">
                                {{ __('Log Out') }}
                            </x-jet-dropdown-link>
                        </form>
                    </div>
                </div>
            @else
                <span id="profile">
                    <button type="button">
                        {{ Auth::user()->name }}
                    </button>
                </span>
            </div>
        @endif
    @else
        <x-signButtons />
    @endif

</header>
<x-guest-layout>



    <form class="auth" method="POST" action="{{ route('register', app()->getLocale()) }}">
        @csrf
        <x-validation-errors />

        <input type="text" class="input1" id="name" type="text" name="name" :value="old('name')" required
            autofocus autocomplete="name" placeholder="{{ __('Name') }}" />

        <input type="email" class="input2" id="email" name="email" value="{{ old('email') }}" required autofocus
            placeholder="{{ __('Email') }}" />

        <input type="password" class="input3" id="password" name="password" required
            autocomplete="{{ __('current-password') }}" placeholder="{{ __('Password') }}" />

        <input type="password" class="input4" id="password_confirmation" name="password_confirmation" required
            placeholder="{{ __('Confirm Password') }}" />



        <a class="link3" href="{{ route('login') }}">
            {{ __('Already registered?') }}
        </a>

        <input type="submit" id="send" value=" {{ __('Register') }}">

    </form>

</x-guest-layout>
<?php
/**
 * Created by PhpStorm.
 * User: David Pratt Jr
 * Date: 1/24/2021
 * Time: 10:08 PM
 */
?>

<div class="navigation-bar">
    <a href="{{ route('home') }}" class='logo'></a>
    

    <form class="navigation-item" id="home_navbar" action="{{ route('home') }}">
        @csrf
        <span class="nav-link">{{ __('Home') }}</span>
    </form>
    @auth
        <form class="navigation-item" id="logout_navbar" method="POST" action="{{ route('logout') }}">
            @csrf
            <span class="nav-link">{{ __('Logout')  }}</span>
        </form>


        <form class="navigation-item" id="search_navbar" action="{{ route('search') }}">
            @csrf
            <span class="nav-link">{{ __('Search') }}</span>
        </form>

    @else
        @if (Route::has('register'))
            <form class="navigation-item" id="register_navbar" action="{{ route('register') }}">
                @csrf
                <span class="nav-link">{{ __('Register') }}</span>
            </form>
        @endif

        @if (Route::has('login'))
            <form class="navigation-item" id="login_navbar" action="{{ route('login') }}" >
                @csrf
                <span class="nav-link">{{ __('Login') }}</span>
            </form>
        @endif
    @endauth
</div>
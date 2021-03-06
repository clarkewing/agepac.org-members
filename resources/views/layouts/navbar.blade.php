<nav class="navbar navbar-expand-md navbar-dark py-3 shadow-sm bg-primary">
    <div class="container">
        <a class="navbar-brand" href="{{ url('/') }}">
            <img src="{{ asset('logo_white.svg') }}" height="28" alt="AGEPAC">
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            @auth
                <!-- Authenticated Member links -->
                <x-menus.main />
            @endauth

            <!-- Right Side Of Navbar -->
            <ul class="navbar-nav ml-auto">
                <!-- Authentication Links -->
                @guest
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                    </li>
                    @if (Route::has('register'))
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                        </li>
                    @endif
                @else
                    <user-notifications></user-notifications>

                    <li class="nav-item dropdown">
                        <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                            {{ Auth::user()->name }} <span class="caret"></span>
                        </a>

                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">

                            <a class="dropdown-item" href="{{ route('profiles.show', Auth::user()) }}">
                                Mon Profil
                            </a>

                            <div class="dropdown-divider"></div>

                            <h6 class="dropdown-header">Mon Compte</h6>

                            <a class="dropdown-item" href="{{ route('account.edit') }}">
                                Mes informations
                            </a>

                            <a class="dropdown-item" href="{{ route('subscription.edit') }}">
                                Ma cotisation
                            </a>

                            <div class="dropdown-divider"></div>

                            @can('viewNova')
                                <a class="dropdown-item" href="{{ config('nova.path') }}">
                                    Administration
                                </a>

                                <div class="dropdown-divider"></div>
                            @endcan

                            <logout-button class="dropdown-item" route="{{ route('logout') }}">
                                {{ __('Logout') }}
                            </logout-button>
                        </div>
                    </li>
                @endguest
            </ul>
        </div>
    </div>
</nav>

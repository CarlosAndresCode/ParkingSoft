<nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
    <div class="container">
        <a class="navbar-brand" href="{{ url('/home') }}">
            {{ config('app.name', 'Laravel') }}
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <!-- Left Side Of Navbar -->
            <ul class="navbar-nav me-auto">
                @auth
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('parking.index') ? 'active' : '' }}" href="{{ route('parking.index') }}">Parking</a>
                    </li>
                    <li class="nav-item dropdown {{ request()->routeIs('parking.index') ? 'active' : '' }}">
                        <a id="navbarDropdown" class="nav-link dropdown-toggle {{ request()->routeIs(['owners.index', 'vehicles.index', 'subscriptions.index']) ? 'active' : '' }} " href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                            Subscriptions
                        </a>
                        <div class="dropdown-menu dropdown-menu-end bg-white" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item" href="{{ route('owners.index') }}">Owners</a>
                                <a class="dropdown-item" href="{{ route('vehicles.index') }}">Vehicles</a>
                                <a class="dropdown-item" href="{{ route('subscriptions.index') }}">Subscriptions</a>
                        </div>
                    </li>
                    @if(Auth::user()->isAdmin())
                    <li class="nav-item dropdown">
                        <a id="navbarDropdown" class="nav-link dropdown-toggle {{ request()->routeIs(['rates.index', 'roles.index', 'users.index']) ? 'active' : '' }}" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                            Config
                        </a>
                        <div class="dropdown-menu dropdown-menu-end bg-white" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item"  href="{{ route('rates.index') }}">Rates</a>
                            <a class="dropdown-item"  href="{{ route('roles.index') }}">Roles</a>
                            <a class="dropdown-item"  href="{{ route('users.index') }}">Users</a>
                        </div>
                    </li>
                    @endif

                @endauth
            </ul>

            <!-- Right Side Of Navbar -->
            <ul class="navbar-nav ms-auto">
                <!-- Authentication Links -->
                @guest
                    @if (Route::has('login'))
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                        </li>
                    @endif

                    @if (Route::has('register'))
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                        </li>
                    @endif
                @else
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('cash-register.show') ? 'active' : '' }}" href="{{ route('cash-register.show') }}">Caja</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                            {{ Auth::user()->name }}
                        </a>

                        <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item" href="{{ route('logout') }}"
                               onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                {{ __('Logout') }}
                            </a>

                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                @csrf
                            </form>
                        </div>
                    </li>
                @endguest
            </ul>
        </div>
    </div>
</nav>

<nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm sticky-bottom">
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
                    @if(Auth::user()->isAdmin())
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('home') ? 'active text-primary fw-medium' : '' }}" href="{{ route('home') }}">Dashboard</a>
                    </li>
                    @endif
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('parking.index') ? 'active text-primary fw-medium' : '' }}" href="{{ route('parking.index') }}">{{ __('Parking') }}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('washes.index') ? 'active text-primary fw-medium' : '' }}" href="{{ route('washes.index') }}">Lavadero</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('products.index') ? 'active text-primary fw-medium' : '' }}" href="{{ route('products.index') }}">Tienda</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('transactions.index') ? 'active text-primary fw-medium' : '' }}" href="{{ route('transactions.index') }}">Facturación</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a id="navbarDropdown" class="nav-link dropdown-toggle {{ request()->routeIs(['owners.*', 'vehicles.*', 'subscriptions.*']) ? 'active text-primary fw-medium' : '' }} " href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                            Suscripciones
                        </a>
                        <div class="dropdown-menu dropdown-menu-end bg-white" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item" href="{{ route('owners.index') }}">Propietarios</a>
                                <a class="dropdown-item" href="{{ route('vehicles.index') }}">Vehículos</a>
                                <a class="dropdown-item" href="{{ route('subscriptions.index') }}">Suscripciones</a>
                        </div>
                    </li>
                    @if(Auth::user()->isAdmin())
                    <li class="nav-item dropdown">
                        <a id="navbarDropdown" class="nav-link dropdown-toggle {{ request()->routeIs(['rates.index', 'wash-types.index', 'roles.index', 'users.index']) ? 'active text-primary fw-medium' : '' }}" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                            Configuración
                        </a>
                        <div class="dropdown-menu dropdown-menu-end bg-white" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item"  href="{{ route('rates.index') }}">Tarifas Parqueo</a>
                            <a class="dropdown-item"  href="{{ route('wash-types.index') }}">Tipos de Lavado</a>
                            <a class="dropdown-item"  href="{{ route('roles.index') }}">Roles</a>
                            <a class="dropdown-item"  href="{{ route('users.index') }}">Usuarios</a>
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
                            <a class="nav-link" href="{{ route('login') }}">{{ __('Log In') }}</a>
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

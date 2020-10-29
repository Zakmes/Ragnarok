<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-dark bg-dark shadow-sm">
            <a class="navbar-brand" href="#">
                {{ config('app.name', 'Laravel') }}
            </a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <!-- Left Side Of Navbar -->
                <ul class="navbar-nav mr-auto">

                </ul>

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
                        @can('leave-impersonation', auth()->user())
                            <li class="nav-item">
                                <a href="{{ kioskRoute('users.impersonate.leave') }}" class="nav-link text-danger">
                                    <x-heroicon-o-logout class="icon mr-2"/> {{ __('Leave impersonation') }}
                                </a>
                            </li>
                        @endcan

                        @if (Auth::user()->canAccessKiosk())
                            <li class="nav-item">
                                <a class="nav-link" href="{{ kioskRoute('dashboard') }}">
                                    <x:heroicon-o-adjustments class="icon mr-1"/> {{ __('Kiosk') }}
                                </a>
                            </li>
                        @elseif (Auth::user()->isOnKiosk())
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('home') }}">
                                    <x:heroicon-o-logout class="icon mr-1"/> {{ __('Leave kiosk') }}
                                </a>
                            </li>
                       @endif

                            @if (config('spoon.modules.announcements'))
                                <li class="nav-item mr-2">
                                    <a href="{{ route('announcements.index') }}" class="nav-link">
                                        <x:heroicon-o-speakerphone class="icon mr-1"/>
                                        <span class="badge badge-pill align-middle badge-announcement">{{ $announcementsUnread }}</span>
                                    </a>
                                </li>
                            @endif

                       <li class="nav-item dropdown">
                            <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                <x:heroicon-o-user-circle class="icon mr-1"/> {{ Auth::user()->name }} <span class="caret"></span>
                            </a>

                            <div class="dropdown-menu border-0 shadow-sm dropdown-menu-right" aria-labelledby="navbarDropdown">
                                <h6 class="dropdown-header">{{ __('Account management') }}</h6>

                                <a href="" class="dropdown-item">
                                    <x:heroicon-o-key class="icon text-muted mr-1"/> {{ __('API tokens') }}
                                </a>

                                <a class="dropdown-item" href="{{ route('account.information') }}">
                                    <x:heroicon-o-adjustments class="icon text-muted mr-1"/> {{ __('Settings') }}
                                </a>

                                <div class="dropdown-divider"></div>

                                <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    <x:heroicon-o-logout class="icon mr-1 text-danger"/> {{ __('Logout') }}
                                </a>

                                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                    @csrf
                                </form>
                            </div>
                       </li>
                    @endguest
                </ul>
            </div>
        </nav>

        <x-kiosk-navbar/>
        <x-flash-session/>

        <main role="main">
            {{ $slot }}
        </main>

        <footer class="footer">
            <div class="container-fluid">
                <span class="footer-text font-weight-bold">&copy; 2019 - {{ date('Y') }} <span class="ml-1">{{ config('app.name') }}</span></span>

                <div class="float-right">
                    <a class="text-decoration-none footer-link" id="toTop" href="#">
                        <i class="fe font-weight-bold fe-chevrons-up icon-pl-1"></i> {{ __('to top') }}
                    </a>

                    <span class="dot align-middle"></span>

                    <a href="" target="_blank" class="footer-link text-decoration-none">
                        {{ __('Privacy') }}
                    </a>

                    <span class="dot align-middle"></span>

                    <a href="" target="_blank" class="footer-link text-decoration-none">
                        {{ __('Terms') }}
                    </a>
                </div>
            </div>
        </footer>
    </div>
</body>
</html>

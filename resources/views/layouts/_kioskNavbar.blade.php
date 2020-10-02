@if (auth()->check() && Auth::user()->isOnKiosk())
    <div class="nav-scroller bg-white shadow-sm">
        <nav class="nav nav-underline">
            <a href="{{ kioskRoute('dashboard') }}" class="nav-link {{ kioskActive('dashboard') }}">
                <x:heroicon-o-home class="icon icon-subnav mr-1"/> {{ __('Dashboard') }}
            </a>

            <a href="{{ kioskRoute('users.index') }}" class="nav-link {{ kioskActive('users*') }}">
                <x:heroicon-o-users class="icon icon-subnav mr-1"/> {{ __('Users') }}
            </a>

            <a href="{{ kioskRoute('roles.index') }}" class="{{ kioskActive('roles*') }} nav-link">
                <x:heroicon-o-menu class="icon icon-subnav mr-1"/> {{ __('Roles') }}
            </a>

            @if (auth()->user()->apiModuleEnabled())
                <a href="{{ kioskRoute('api-management.index') }}" class="{{ kioskActive('api-management.*') }} nav-link">
                    <x:heroicon-o-key class="icon icon-subnav mr-1"/> {{ __('API management') }}
                </a>
            @endif

            @can ('view', \App\Domains\Activity\Models\Activity::class)
                <a href="{{ kioskRoute('activity.index') }}" class="{{ kioskActive('activity.*') }} nav-link">
                    <x:heroicon-o-annotation class="icon icon-subnav mr-1"/> {{ __('Logs') }}
                </a>
            @endcan

            @can ('view-any', \App\Domains\Announcements\Models\Announcement::class)
                <a href="{{ kioskRoute('announcements.overview') }}" class="{{ kioskActive('announcements.*') }} nav-link">
                    <x:heroicon-o-speakerphone class="icon icon-subnav mr-1"/> {{ __('Announcements') }}
                </a>
            @endcan
        </nav>
    </div>
@endif

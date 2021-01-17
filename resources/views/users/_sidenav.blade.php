<div class="list-group list-group-transparent">
    @can ('view', $user)
        <a href="{{ kioskRoute('users.show', $user) }}" class="{{ kioskActive('users.show') }} list-group-item list-group-item-action">
            <x:heroicon-o-information-circle class="icon mr-2"/> {{ __('General information') }}
        </a>
    @endcan

    @can ('update', $user)
        <a href="{{ kioskRoute('users.update', $user) }}" class="{{ kioskActive('users.update') }} list-group-item list-group-item-action">
            <x:heroicon-o-pencil-alt class="icon mr-2" /> {{ __('Edit account information') }}
        </a>
    @endcan

    @if (auth()->user()->can('lock', $user) && $user->isNotBanned())
        <a href="{{ kioskRoute('users.lock', $user) }}" class="{{ kioskActive('users.lock') }} list-group-item list-group-item-action">
            <x:heroicon-o-lock-closed class="icon mr-2"/> {{ __('Deactivate account') }}
        </a>
    @elseif (auth()->user()->can('unlock', $user) && $user->isBanned())
        <a href="{{ kioskRoute('users.unlock', $user) }}" class="list-group-item list-group-item-action">
            <x:heroicon-o-lock-open class="icon mr-2"/> {{ __('Activate user') }}
        </a>
    @endcan

    @can('impersonate', [$user])
        <a href="{{ kioskRoute('users.impersonate', $user) }}" class="list-group-item list-group-item-action">
            <x-heroicon-o-login class="icon mr-2"/> {{ __('Impersonate user') }}
        </a>
    @endcan

    @can ('delete', $user)
        <a href="{{ kioskRoute('users.destroy', $user) }}" class="{{ kioskActive('users.destroy') }} list-group-item list-group-item-action">
            <x:heroicon-o-trash class="icon mr-2 text-danger"/> {{ __('Delete :user', ['user' => $user->name]) }}
        </a>
    @endcan
</div>

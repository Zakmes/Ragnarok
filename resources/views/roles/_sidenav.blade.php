<div class="list-group list-group-transparent">
    <a href="{{ kioskRoute('roles.index') }}" class="list-group-item list-group-item-action">
        <x:heroicon-o-menu class="icon mr-2"/> {{ __('Back to overview') }}
    </a>

    @can ('view', $role)
        <a href="{{ kioskRoute('roles.show', $role) }}" class="list-group-item list-group-item-action {{ kioskActive('roles.show') }}">
            <x:heroicon-o-information-circle class="icon mr-2"/> {{ __('Role information') }}
        </a>
    @endcan

    @can ('update', $role)
        <a href="{{ kioskRoute('roles.edit', $role) }}" class="list-group-item list-group-item-action {{ kioskActive('roles.edit') }}">
            <x:heroicon-o-pencil-alt class="icon mr-2"/> {{ __('Edit permission role') }}
        </a>
    @endcan

    @can ('delete', $role)
        <a href="{{ kioskRoute('roles.destroy', $role) }}" class="list-group-item list-group-item-action">
            <x:heroicon-o-trash class="text-danger icon mr-2"/> {{ __('Delete role') }}
        </a>
    @endcan
</div>

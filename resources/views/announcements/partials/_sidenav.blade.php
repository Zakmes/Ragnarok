<div class="list-group list-group-transparent">
    @can ('view', $announcement)
        <a href="{{ kioskRoute('announcements.show', $announcement) }}" class="{{ kioskActive('announcements.show') }} list-group-item-action list-group-item">
            <x:heroicon-o-speakerphone class="icon icon-subnav mr-2"/> {{ __('Announcement information') }}
        </a>
    @endcan

    @if ($announcement->enabled && auth()->user()->can('disable-announcement'))
        <a href="{{ kioskRoute('announcements.disable', $announcement) }}" class="list-group-item list-group-item-action">
            <x:heroicon-o-lock-closed class="icon icon-subnav mr-2"/> {{ __('Disable announcement') }}
        </a>
    @elseif (! $announcement->enabled && auth()->user()->can('enable-announcement'))
        <a href="{{ kioskRoute('announcements.enable', $announcement) }}" class="list-group-item list-group-item-action">
            <x:heroicon-o-lock-open class="icon icon-subnav mr-2"/> {{ __('Enable announcement') }}
        </a>
    @endif

    @can ('delete', $announcement)
        <a href="{{ kioskRoute('announcements.delete', $announcement) }}" class="list-group-item list-group-item-action">
            <x:heroicon-o-trash class="icon text-danger mr-2"/> {{ __('Delete announcement') }}
        </a>
    @endcan
</div>

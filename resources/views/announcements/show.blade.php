<x-app-layout>
    <div class="container-fluid py-3">
        <div class="page-header">
            <h1 class="page-title">{{ __('Announcements') }}</h1>
            <div class="page-subtitle">{{ $announcement->title }}</div>

            <div class="page-options d-flex">
                <a href="{{ kioskRoute('announcements.overview') }}" class="btn btn-option shadow-sm border-0">
                    <x:heroicon-o-menu class="icon mr-1"/> {{ __('Overview') }}
                </a>
            </div>
        </div>
    </div>

    <div class="container-fluid pb-3">
        <div class="row">
            <div class="col-3">
                <x-announcement-sidenav :announcement="$announcement"/>
            </div>

            <div class="col-9">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <h6 class="border-bottom border-gray pb-1 mb-3">{{ __('Announcement information') }}</h6>

                        <div class="table-responsive">

                            <table class="table table-borderless mb-0">
                                <tbody>
                                    <tr>
                                        <td class="announcement-creator pt-1 px-0">
                                            <span class="font-weight-bold text-muted">{{ __('Creator') }}</span> <br>
                                            {{ $announcement->creator->name }}
                                        </td>

                                        <td class="announcement-visibility pt-1 px-0">
                                            <span class="font-weight-bold text-muted">{{ __('Visibility area') }}</span> <br>

                                            @switch ($announcement->area)
                                                @case(null)       {{ __('Global') }}        @break
                                                @case('frontend') {{ __('Application') }}   @break
                                                @case('backend')  {{ __('Kiosk')  }}        @break
                                            @endswitch
                                        </td>

                                        <td class="announcement-status pt-1 px-0">
                                            <span class="font-weight-bold text-muted">{{ __('Status') }}</span> <br>
                                            {{ $announcement->enabled ? __('Enabled') : __('Disabled') }}
                                        </td>
                                    </tr>
                                </tbody>
                            </table>

                            <hr class="mt-0 mb-2">

                            <table class="table table-borderless mb-0">
                                <tbody>
                                    <tr>
                                        <td class="announcement-title pt-1 px-0">
                                            <span class="font-weight-bold text-muted">{{ __('Title') }}</span> <br>
                                            {{ $announcement->title }}
                                        </td>

                                        <td class="announcement-period pt-1 px-0">
                                            <span class="font-weight-bold text-muted">{{ __('Period') }}</span> <br>
                                            {{ optional($announcement->starts_at)->format('d/m/Y') ?? __('Unknown start') }} - {{ optional($announcement->ends_at)->format('d/m/Y') ?? __('Unknown end') }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="2" class="w-100 pt-1 px-0">
                                            <span class="font-weight-bold text-muted">{{ __('Announcement message') }}</span><br>
                                            {{ $announcement->message }}
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

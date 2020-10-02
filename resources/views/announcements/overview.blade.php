<x-app-layout>
    <div class="container-fluid py-3">
        <div class="page-header">
            <h1 class="page-title">{{ __('Announcements') }}</h1>
            <div class="page-subtitle">{{ __('Management overview for all the announcements.') }}</div>

            <div class="page-options d-flex">
                @can ('create', \App\Domains\Announcements\Models\Announcement::class)
                    <a href="{{ kioskRoute('announcements.create') }}" class="btn btn-option border-0 shadow-sm mr-2">
                        <x:heroicon-o-plus class="icon"/>
                    </a>
                @endcan

                <form method="" action="" class="form-inline">
                    <input type="text" class="form-control form-search border-0 shadow-sm" placeholder="{{ __('Search an announcement') }}">
                </form>
            </div>
        </div>
    </div>

    <div class="container-fluid pb-3">
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="mb-0 table table-sm table-hover">
                        <thead>
                            <tr>
                                <th class="border-top-0" scope="col">{{ __('Creator') }}</th>
                                <th class="border-top-0" scope="col">{{ __('Visibility') }}</th>
                                <th class="border-top-0" scope="col">{{ __('Status') }}</th>
                                <th class="border-top-0" scope="col">{{ __('Title') }}</th>
                                <th class="border-top-0" scope="col" colspan="2">{{ __('Period') }}</th> {{-- Colspan 2 for the functions --}}
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($announcements as $announcement)
                                <tr>
                                    <td class="font-weight-bold text-muted">{{ $announcement->creator->name ?? __('Unknown user') }}</td>
                                    <td>
                                        @switch ($announcement->area)
                                            @case(null)       {{ __('Global') }}        @break
                                            @case('frontend') {{ __('Application') }}   @break
                                            @case('backend')  {{ __('Kiosk')  }}        @break
                                        @endswitch
                                    </td>
                                    <td>{{ $announcement->enabled ? __('Enabled') : __('Disabled') }}</td>
                                    <td>{{ $announcement->title }}</td>
                                    <td>{{ optional($announcement->starts_at)->format('d/m/Y') ?? __('Unknown start') }} - {{ optional($announcement->ends_at)->format('d/m/Y') ?? __('Unknown end') }}</td>

                                    <td> {{-- Options --}}
                                        <span class="float-right mr-1">
                                            @can ('view', $announcement)
                                                <a href="{{ kioskRoute('announcements.show', $announcement) }}" class="text-decoration-none text-muted mr-1">
                                                    <x:heroicon-o-eye class="icon"/>
                                                </a>
                                            @endcan

                                            @if (! $announcement->enabled && auth()->user()->can('enable-announcement', $announcement))
                                                <a href="{{ kioskRoute('announcements.enable', $announcement) }}" class="text-decoration-none text-success mr-1">
                                                    <x:heroicon-o-lock-open class="icon"/>
                                                </a>
                                            @elseif ($announcement->enabled && auth()->user()->can('disable-announcement', $announcement))
                                                <a href="{{ kioskRoute('announcements.disable', $announcement) }}" class="text-decoration-none text-danger mr-1">
                                                    <x:heroicon-o-lock-closed class="icon" />
                                                </a>
                                            @endif

                                            @can ('delete', $announcement)
                                                <a href="{{ kioskRoute('announcements.delete', $announcement) }}" class="text-decoration-none text-danger">
                                                    <x:heroicon-o-trash class="icon"/>
                                                </a>
                                            @endcan
                                        </span>
                                    </td> {{-- /// Options --}}
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-muted">
                                        <x:heroicon-o-information-circle class="icon mr-1"/> {{ __('Currently there are no announcements in the application.') }}
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

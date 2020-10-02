<x-app-layout>
    <div class="container-fluid py-3">
        <div class="page-header">
            <h1 class="page-title">{{ __('Activity log') }}</h1>
            <div class="page-subtitle">
                @if ($filter !== null)
                    {{ __('All logged activities on the based filter: :filter', ['filter' => $filter]) }}
                @else
                    {{ __('Overview from all the logged activities') }}
                @endif
            </div>

            <div class="page-options d-flex">
                <div class="btn-group mr-2">
                    <button type="button" class="btn btn-option border-0 shadow-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <x:heroicon-o-filter class="icon mr-1"/> {{ __('Filter') }}
                    </button>
                    <div class="dropdown-menu border-0 shadow-sm">
                        @foreach ($filterKeywords as $keyword)
                            <a class="dropdown-item" href="{{ kioskRoute('activity.index', ['filter' => $keyword->log_name]) }}">{{ $keyword->log_name }}</a>
                        @endforeach

                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="{{ kioskRoute('activity.index') }}">All logs</a>
                    </div>
                </div>

                <form method="" action="" class="form-inline">
                    <input type="text" class="form-control form-search border-0 shadow-sm" placeholder="{{ __('Search logged activity ') }}">
                </form>
            </div>
        </div>
    </div>

    <div class="container-fluid pb-3">
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm mb-0 table-hover">
                        <thead>
                            <tr>
                                <th class="border-top-0" scope="col">{{ __('Log name') }}</th>
                                <th class="border-top-0" scope="col">{{ __('Causer') }}</th>
                                <th class="border-top-0" scope="col">{{ __('Message') }}</th>
                                <th class="border-top-0" scope="col">{{ __('Timestamp') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($activities as $activity)
                                <tr>
                                    <td>{{ ucfirst($activity->log_name) }}</td>
                                    <td class="font-weight-bold text-muted">{{ $activity->causer->name ?? __('Unknown user') }}</td>
                                    <td>{{ $activity->description }}</td>
                                    <td>{{ $activity->created_at->format('d/m/Y - H:i:s') }}</td>
                                </tr>
                            @empty
                                <td class="text-muted" colspan="4">
                                    <x:heroicon-o-information-circle class="icon mr-1"/>
                                    {{ __('Currently there are no loggied activities in the application.') }}
                                </td>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

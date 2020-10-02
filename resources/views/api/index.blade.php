<x-app-layout>
    <div class="container-fluid py-3">
        <div class="page-header">
            <h1 class="page-title">{{ __('API management') }}</h1>
            <div class="page-subtitle">
                @if ($filter === null)
                    {{ __('Overview from all the API tokens in the application.') }}
                @elseif ($filter === 'revoked')
                    {{ __('Overview from all the revoked API tokens in the application.') }}
                @endif
            </div>

            <div class="page-options d-flex">
                <div class="btn-group mr-2">
                    <button type="button" class="btn btn-option border-0 shadow-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <x:heroicon-o-filter class="icon mr-1"/> {{ __('Filter') }}
                    </button>
                    <div class="dropdown-menu border-0 shadow-sm">
                        <a href="{{ kioskRoute('api-management.index') }}" class="dropdown-item">{{ __('Active tokens') }}</a>
                        <a href="{{ kioskRoute('api-management.index', ['filter' => 'revoked']) }}" class="dropdown-item">{{ __('Revoked tokens') }}</a>
                    </div>
                </div>

                <form method="" action="" class="form-inline">
                    <input type="text" class="form-control form-search border-0 shadow-sm" placeholder="Search api token">
                </form>
            </div>
        </div>
    </div>

    <div class="container-fluid pb-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm table-hover mb-0">
                        <thead>
                            <tr>
                                <th class="border-top-0" scope="col">{{ __('Token name') }}</th>
                                <th class="border-top-0" scope="col">{{ __('Status') }}</th>
                                <th class="border-top-0" scope="col">{{ __('Issuer') }}</th>
                                <th class="border-top-0" scope="col">{{ __('Created at') }}</th>
                                <th colspan="2" class="border-top-0" scope="col">{{ __('Last used') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($tokens as $personalAccessToken)
                                <tr>
                                    <td class="font-weight-bold text-muted">{{ $personalAccessToken->name }}</td>
                                    <td>
                                        @if ($personalAccessToken->trashed())
                                            <span class="badge badge-danger">{{ __('Revoked') }}</span>
                                        @else
                                            <span class="badge badge-active">{{ __('Active') }}</span>
                                        @endif
                                    </td>
                                    <td>{{ $personalAccessToken->tokenable->name }}</td>
                                    <td>{{ $personalAccessToken->created_at->format('d/m/Y') }}</td>
                                    <td>{{ $personalAccessToken->last_used_at ?? '-'}}</td>

                                    <td>
                                        @if ($personalAccessToken->canRevoke())
                                            <a href="{{ route('account.tokens.revoke', $personalAccessToken) }}" class="small float-right text-decoration-none text-danger">
                                                {{ __('Revoke') }}
                                            </a>
                                        @endcan

                                        @if ($personalAccessToken->canRestore())
                                            <a href="{{ kioskRoute('api-management.restore', $personalAccessToken) }}" class="small float-right text-decoration-none text-success">
                                                {{ __('Restore') }}
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-muted">
                                        <x:heroicon-o-information-circle class="icon mr-1"/>

                                        @if ($filter === null)
                                            {{ __('There are currently no active access tokens in the application.') }}
                                        @elseif ($filter === 'revoked')
                                            {{ __('There are currently no revoked access tokens in the application.') }}
                                        @endif
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

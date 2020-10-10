<x-app-layout>
    <div class="container-fluid py-3">
        <div class="page-header">
            <h1 class="page-title">{{ __('Kiosk dashboard') }}</h1>

            <div class="page-options d-flex">
                <a href="{{ route('home') }}" class="btn btn-option shadow-sm border-0">
                    <x:heroicon-o-logout class="icon mr-1"/> {{ __('Leave kiosk') }}
                </a>
            </div>
        </div>
    </div>

    <div class="container-fluid pb-3">
        <div class="row"> {{-- Widgets- --}}
            <div class="col-4"> {{-- User widget --}}
                <div class="card border-0 shadow-sm p-2">
                    <div class="d-flex align-items-center">
                        <span class="stamp stamp-md stamp-bg-brand mr-3">
                            <x:heroicon-o-users class="icon"/>
                        </span>

                        <div>
                            <h5 class="m-0">0 <small>{{ __('Users') }}</small></h5>
                            <small class="text-muted">{{ __(':amount registered today.', ['amount' => 0]) }}</small>
                        </div>
                    </div>
                </div>
            </div> {{-- /// END widgets --}}

            <div class="col-4"> {{-- User widget --}}
                <div class="card border-0 shadow-sm p-2">
                    <div class="d-flex align-items-center">
                        <span class="stamp stamp-md stamp-bg-brand mr-3">
                            <x:heroicon-o-annotation class="icon"/>
                        </span>

                        <div>
                            <h5 class="m-0">0 <small>{{ __('Activity logs') }}</small></h5>
                            <small class="text-muted">{{ __(':amount activities logged today.', ['amount' => 0]) }}</small>
                        </div>
                    </div>
                </div>
            </div> {{-- /// END widgets --}}

            <div class="col-4"> {{-- User widget --}}
                <div class="card border-0 shadow-sm p-2">
                    <div class="d-flex align-items-center">
                        <span class="stamp stamp-md stamp-bg-brand mr-3">
                            <x:heroicon-o-key class="icon"/>
                        </span>

                        <div>
                            <h5 class="m-0">0 <small>{{ __('Personal access tokens') }}</small></h5>
                            <small class="text-muted">{{ __(':amount personal access tokens issued today.', ['amount' => 0]) }}</small>
                        </div>
                    </div>
                </div>
            </div> {{-- /// END widgets --}}
        </div>

        <hr>

        <div class="row">
            <div class="col-md-12 col-lg-6 col-sm-12">
                <div class="card h-100 card-body border-0 shadow-sm">
                    <h6 class="border-bottom border-gray pb-1 mb-3">
                        {{ __('Recent created roles') }}
                        <a href="{{ kioskRoute('roles.index') }}" class="small text-decoration-none dashboard-link float-right">
                            <x:heroicon-o-menu class="icon mr-1"/> {{ __('See more') }}
                        </a>
                    </h6>

                    <div class="table-responsive">
                        <table class="table table-sm mb-0 table-hover">
                            <thead>
                                <tr>
                                    <th class="border-top-0 "scope="col">{{ __('Role Name') }}</th>
                                    <th class="border-top-0" scope="col">{{ __('Creator') }}</th>
                                    <th class="border-top-0" scope="col" colspan="2">{{ __('Description') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($roles as $role)
                                    <tr>
                                        <td class="text-muted font-weight-bold">{{ $role->name }}</td>
                                        <td>{{ $role->creator->name ?? __('Unknown user') }}</td>
                                        <td>{{ $role->description }}</td>
                                        <td>
                                            <a href="{{ kioskRoute('roles.show', $role) }}" class="text-muted text-decoration-none float-right mr-1">
                                                <x:heroicon-o-eye class="icon"/>
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr class="text-muted">
                                        <td colspan="4">
                                            <x:heroicon-o-information-circle class="icon"/> {{ __('Currently there are no new registered permission roles.') }}
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-md-12 col-lg-6 col-sm-12">
                <div class="card h-100 card-body border-0 shadow-sm">
                    <h6 class="border-bottom border-gray pb-1 mb-3">
                        {{ __('Recent created permission roles') }}

                        <a href="{{ kioskRoute('roles.index') }}" class="small text-decoration-none dashboard-link float-right">
                            <x:heroicon-o-menu class="icon mr-1"/> {{ __('See more') }}
                        </a>
                    </h6>

                    <div class="table-responsive">
                        <table class="table table-sm mb-0 table-hover">
                            <thead>
                                <tr>
                                    <th scope="col" class="border-top-0">{{ __('Name') }}</th>
                                    <th scope="col" class="border-top-0">{{ __('Status') }}</th>
                                    <th scope="col" class="border-top-0">{{ __('User Group') }}</th>
                                    <th scope="col" class="border-top-0" colspan="2">{{ __('Created at') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($users as $user)
                                    <tr>
                                        <td>
                                            <a href="mailto:{{ $user->email }}" class="text-decoration-none text-muted font-weight-bold">
                                                {{ $user->name }}
                                            </a>
                                        </td>
                                        <td>
                                            @if ($user->isBanned())
                                                <span class="badge badge-nonactive">{{ __('Deactivated') }}</span>
                                            @elseif ($user->trashed())
                                                <span class="badge badge-nonactive">{{ __('Marked for deletion') }}</span>
                                            @else
                                                <span class="badge badge-active">{{ __('Active') }}</span>
                                            @endif
                                        </td>
                                        <td>{{ $user->user_group }}</td>
                                        <td>{{ $user->created_at->diffForHumans() }}</td>
                                        <td>
                                            <span class="float-right">
                                                <a href="{{ kioskRoute('users.show', $user) }}" class="text-muted text-decoration-none mr-1">
                                                    <x:heroicon-o-eye class="icon"/>
                                                </a>

                                                <a href="mailto:{{ $user->email }}" class="text-muted text-decoration-none">
                                                    <x:heroicon-o-mail class="icon"/>
                                                </a>
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr class="test-muted">
                                        <td colspan="5">
                                            <x:heroicon-o-information-circle class="icon"/> {{ __('Currently there are no new registered users.') }}
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-12 pt-4">
                <div class="card card-body border-0 shadow-sm">
                    <h6 class="border-bottom border-gray pb-1 mb-3">
                        {{ __('Recent logged activities') }}

                        <a href="{{ kioskRoute('activity.index') }}" class="small text-decoration-none dashboard-link float-right">
                            <x:heroicon-o-menu class="icon mr-1"/> {{ __('See more') }}
                        </a>
                    </h6>

                    <div class="table-responsive">
                        <table class="table table-sm mb-0 table-hover">
                            <thead>
                                <tr>
                                    <th class="border-top-0" scope="col">{{ __('Log Name') }}</th>
                                    <th class="border-top-0" scope="col">{{ __('User') }}</th>
                                    <th class="border-top-0" scope="col">{{ __('Description') }}</th>
                                    <th class="border-top-0" scope="col">{{ __('Timestamp') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($activities as $activity)
                                    <tr>
                                        <td class="font-weight-bold text-muted">{{ $activity->log_name }}</td>
                                        <td class="font-weight-bold text-muted">{{ $activity->causer->name ?? __('Unknown user') }}</td>
                                        <td>{{ $activity->description }}</td>
                                        <td>{{ $activity->created_at->format('d/m/Y - H:i:s') }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td class="text-muted" colspan="4">
                                            <x:heroicon-o-information-circle class="icon"/> {{ __('Currently there are no logged activities in :application', ['application' => config('app.name')]) }}
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

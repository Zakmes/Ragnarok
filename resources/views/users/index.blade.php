<x-app-layout>
    <div class="container-fluid py-3">
        <div class="page-header">
            <h1 class="page-title">{{ __('Users')  }}</h1>
            <x-user-overview-subtitle :filter="$filter"/>

            <div class="page-options d-flex">
                @can ('create', \App\User::class)
                    <a href="{{ kioskRoute('users.create') }}" class="btn btn-option border-0 shadow-sm">
                        <x:heroicon-o-user-add class="icon"/>
                    </a>
                @endcan

                <div class="btn-group mx-2">
                    <button type="button" class="btn btn-option border-0 shadow-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <x:heroicon-o-filter class="icon mr-1"/> {{ __('Filter') }}
                    </button>
                    <div class="dropdown-menu border-0 shadow-sm">
                        <a class="dropdown-item" href="{{ kioskRoute('users.index') }}">{{ __('All users') }}</a>
                        <a class="dropdown-item" href="{{ kioskRoute('users.index', ['filter' => $groupEnum::USER]) }}">{{ __('Normal users') }}</a>
                        <a class="dropdown-item" href="{{ kioskRoute('users.index', ['filter' => $groupEnum::DEVELOPER]) }}">{{ __('Developers') }}</a>
                        <a class="dropdown-item" href="{{ kioskRoute('users.index', ['filter' => $groupEnum::WEBMASTER]) }}">{{ __('Webmasters') }}</a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="{{ kioskRoute('users.index', ['filter' =>'deleted']) }}">{{ __('Deleted users') }}</a>
                    </div>
                </div>

                <form method="" action="" class="form-inline">
                    <input type="text" class="form-control form-search border-0 shadow-sm" placeholder="{{ __('Search user by name or email ') }}">
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
                                <th class="border-top-0" scope="col">{{ __('Name') }}</th>
                                <th class="border-top-0" scope="col">{{ __('User group') }}</th>
                                <th class="border-top-0" scope="col">{{ __('Status') }}</th>
                                <th class="border-top-0" scope="col">{{ __('Email address') }}</th>
                                <th class="border-top-0" colspan="2" scope="col">{{ ($filter === 'deleted') ?  __('Deletion date') : __('Registration date') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($users as $user)
                                <tr>
                                    <td class="text-muted font-weight-bold">{{ $user->name }}</td>
                                    <td>{{ $user->user_group }}</td>
                                    <td>
                                        @if ($user->isBanned())
                                            <span class="badge badge-nonactive">{{ __('Deactivated') }}</span>
                                        @elseif ($user->trashed())
                                            <span class="badge badge-nonactive">{{ __('Marked for deletion') }}</span>
                                        @else
                                            <span class="badge badge-active">{{ __('Active') }}</span>
                                        @endif
                                    </td>
                                    <td><a href="mailto:{{ $user->email }}" class="text-decoration-none">{{ $user->email }}</a></td>
                                    <td>
                                        @if($user->trashed())
                                            {{ $user->deleted_at->addWeeks(2)->format('d/m/Y') }}
                                            <span class="small text-muted">({{ $user->deleted_at->addWeeks(2)->diffForHumans() }})</span>
                                        @else
                                            {{ $user->created_at->format('d/m/Y') }}
                                        @endif
                                    </td>

                                    <td> {{-- Options --}}
                                        <span class="float-right">
                                            @if ($user->trashed())
                                                @can('restore', $user)
                                                    <a href="{{ kioskRoute('users.restore', $user) }}" class="text-success text-decoration-none">
                                                        <x:heroicon-o-refresh class="icon mr-1"/>
                                                        <span class="small">{{ __('Restore account') }}</span>
                                                    </a>
                                                @endcan
                                            @else
                                                @can ('view', $user)
                                                    <a href="{{ kioskRoute('users.show', $user) }}" class="text-decoration-none text-muted mr-1">
                                                    <x:heroicon-o-eye class="icon" />
                                                </a>
                                                @endcan

                                                @can('update', $user)
                                                    <a href="{{ kioskRoute('users.update', $user) }}" class="text-decoration-none text-muted mr-1">
                                                    <x:heroicon-o-pencil-alt class="icon"/>
                                                </a>
                                                @endcan

                                                @if (auth()->user()->can('lock', $user) && $user->isNotBanned())
                                                    <a href="{{ kioskRoute('users.lock', $user) }}" class="text-decoration-none text-muted mr-1">
                                                        <x:heroicon-o-lock-closed class="icon"/>
                                                    </a>
                                                @elseif (auth()->user()->can('unlock', $user) && $user->isBanned())
                                                    <a href="{{ kioskRoute('users.unlock', $user) }}" class="text-decoration-none text-success mr-1">
                                                        <x:heroicon-o-lock-open class="icon"/>
                                                    </a>
                                                @endif

                                                @can ('delete', $user)
                                                    <a href="{{ kioskRoute('users.destroy', $user) }}" class="text-decoration-none text-danger">
                                                        <x:heroicon-o-trash class="icon" />
                                                    </a>
                                                @endcan
                                            @endif
                                        </span>
                                    </td> {{-- /// END options --}}
                                </tr>
                            @empty
                                <tr>
                                    <td class="text-muted" colspan="6">
                                        <x:heroicon-o-information-circle class="icon"/> {{ __('Currently there are no users found with the matching criteria') }}
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            @if ($users->hasPages())
                <div class="card-footer border-top-0">
                    <div class="row">
                        <div class="col">{{ $users->links() }}</div>
                        <div class="col text-secondary text-right my-auto">
                            Showing {{ $users->firstItem() }} to {{ $users->lastItem() }} out of {{ $users->total() }} results
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>

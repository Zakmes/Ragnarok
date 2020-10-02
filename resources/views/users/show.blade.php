<x-app-layout>
    <div class="container-fluid py-3">
        <div class="page-header">
            <h1 class="page-title">{{ $user->name }}</h1>
            <div class="page-subtitle">{{ __('General information') }}</div>

            <div class="page-options d-flex">
                <a href="{{ kioskRoute('users.index') }}" class="btn btn-option border-0 shadow-sm">
                    <x:heroicon-o-menu class="icon mr-1"/> {{ __('Overview') }}
                </a>
            </div>
        </div>
    </div>

    <div class="container-fluid pb-3">
        <div class="row">
            <div class="col-3">
                <x-account-sidenav :user="$user"/>
            </div>

            <div class="col-9">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <h6 class="border-bottom border-gray pb-1 mb-3">{{ __('General information from :user', ['user' => $user->name]) }}</h6>

                        <div class="table-responsive">
                            <table class="table table-borderless mb-0">
                                <tbody>
                                    <tr>
                                        <td class="w-50 pt-1 px-0">
                                            <span class="font-weight-bold text-muted">{{ __('Full name') }}</span> <br>
                                            {{ $user->name }}

                                            @if ($user->isNotBanned())
                                                <span class="ml-2 badge badge-active">{{ __('active') }}</span>
                                            @else ($user->isBanned())
                                                <span class="ml-2 badge badge-nonactive">{{ __('Deactivated') }}</span>
                                            @endif
                                        </td>
                                        <td class="w-50 pt-1">
                                            <span class="font-weight-bold text-muted">{{ __('Email address') }}</span> <br>
                                            {{ $user->email }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="w-50 px-0 {{ ($user->isBanned()) ? 'pb-1' : 'pb-0' }}">
                                            <span class="font-weight-bold text-muted">{{ __('User group') }}</span> <br>
                                            {{ $user->user_group }}
                                        </td>
                                        <td class="w-50 {{ ($user->isBanned()) ? 'pb-1' : 'pb-0' }}">
                                            <span class="font-weight-bold text-muted">{{ __('Permission roles') }}</span> <br>

                                            @forelse($user->roles as $role)
                                                {{ $user->role }}
                                            @empty
                                                <span class="font-italic text-info">
                                                    <x:heroicon-o-information-circle class="icon"/>
                                                    {{ __('The user has no permission roles at the moment.') }}
                                                </span>
                                            @endforelse
                                        </td>
                                    </tr>

                                    @if ($user->isBanned())
                                        <tr>
                                            <td colspan="2" class="w-100 px-0 pb-0">
                                                <span class="text-muted font-weight-bold">Deactivation reason</span> <br>
                                                {{ $user->deactivationReason() }}
                                            </td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

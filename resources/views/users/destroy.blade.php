<x-app-layout>
    <div class="container-fluid py-3">
        <div class="page-header">
            <h1 class="page-title">{{ $user->name }}</h1>
            <div class="page-subtitle">{{ __('Account deletion') }}</div>

            <div class="page-options d-flex">
                <a href="{{ kioskRoute('users.index') }}" class="btn btn-option shadow-sm border-0">
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
                <form action="{{ kioskRoute('users.destroy', $user) }}" method="POST" class="card border-0 shadow-sm">
                    @csrf
                    @method('DELETE')

                    <div class="card-body">
                        <h6 class="border-bottom border-gray pb-1 mb-3">{{ __('Delete account from :user', ['user' => $user->name]) }}</h6>

                        <p class="card-text text-danger">
                            <x:heroicon-o-exclamation class="icon mr-1"/> {{ __('Your considering to delete a user account.') }}
                        </p>

                        <p class="card-text">
                            {{ __('By deleting a user account all the information will be deleted and the user can\'t login anymore') }} <br>
                            {{ __('Please be sure that the account has no more use on the application. Or if is it ok to delete his account.') }} <br>
                            {{ __('The account will be marked for deletion and after 2 weeks it will be delete automatically.') }}
                        </p>
                    </div>
                    <div class="card-footer border-0">
                        <a href="{{ kioskRoute('users.show', $user) }}" class="btn btn-link text-decoration-none border0">{{ __('Cancel') }}</a>
                        <button type="submit" class="btn btn-danger border-0 shadow-sm">{{ __('Delete') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>

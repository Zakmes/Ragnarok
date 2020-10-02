<x-app-layout>
    <div class="container-fluid py-4">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <h6 class="border-bottom text-danger border-gray pb-1 mb-3">
                    <x:heroicon-o-exclamation class="icon mr-1"/>
                    {{ __('Your account has been deactivated.') }}
                </h6>

                <p class="card-text">
                    {{ __('Your account has been deactivated from :date by :user.', [
                        'user' => $banInformation->createdBy->name,
                        'date' => $banInformation->created_at->format('d/m/Y')
                    ]) }} <br>

                    {{ __("This means that you can't going any further into the application.") }}<br>
                    {{ __('If think the deactivation of your account is a fault you are free to contact :user,', ['user' => $banInformation->createdBy->name]) }}
                    {{ __('to discuss your account deactivation.') }}
                </p>

                <hr>

                <dl class="mb-0">
                    <dt class="text-muted">{{ __('Deactivation reason') }}</dt>
                    <dd class="mb-0">{{ $user->deactivationReason() }}</dd>
                </dl>
            </div>
            <div class="card-footer border-0">
                <a class="btn btn-option border-0 shadow-sm" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <x:heroicon-o-logout class="icon"/> {{ __('Logout') }}

                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                </a>

                <a href="" class="border-0 btn btn-option shadow-sm">
                    {{ __('Contact') }}
                </a>
            </div>
        </div>
    </div>
</x-app-layout>

<form action="{{ $url }}" method="POST" class="mt-4 card border-0 shadow-sm">
    @csrf {{-- Form field protection --}}

    <div class="card-body">
        <h6 class="border-bottom border-gray pb-1 mb-3">{{ __('Setup two factor authentication.') }}</h6>

        @if (session('error'))
            <div class="alert alert-danger border-0" role="alert">{{ session('error') }}</div>
        @elseif (session('success'))
            <div class="alert alert-success border-0" role="alert">{{ session('success') }}</div>
        @endif

        <p>{{ __('Add additional security to you account using two factor authentication.') }}</p>

        <p class="card-text">
            {{ __('When two factor authentication is enabled, you will be prompted for a secure, random token during authentication. You may retrieve this token from your phone\'s Google Authenticator application.') }}
        </p>

        <p class="card-text">
            {{ __('To enable two factor authentication your account. U will need to execute the following steps.') }}
        </p>

        <ul class="list-unstyled mb-1 font-weight-bold">
            <li>1. {{ __('Click on the "enable 2FA" button to generate your unique 2FA challenge.') }}</li>
            <li>2. {{ __('Verify the OTP token in the Google Authenticator app on your telephone.') }}</li>
        </ul>
    </div>

    <div class="card-footer border-0">
        <button type="submit" class="btn btn-submit border-0 shadow-sm">
            Enable 2FA
        </button>
    </div>
</form>

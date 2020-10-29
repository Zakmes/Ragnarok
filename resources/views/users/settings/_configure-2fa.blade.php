<form action="{{ $url }}" method="POST" class="card mt-4 border-0 shadow-sm">
    @csrf {{-- form field protection --}}

    <div class="card-body">
        <h6 class="border-bottom border-gray pb-1 mb-3">{{ __('Two factor authentication configuration.') }}</h6>

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

        <img class="qr-2fa-code pb-2" src="{{ $qrCode ?? '' }}" alt="2Fa {{ config('app.name') }}">

        <ul class="list-unstyled mb-2 font-weight-bold">
            <li>1. {{ __('Scan the QR code with your Google Authenticator app on your phone.') }}</li>
            <li>2. {{ __('Enter the given code from the authenticator app to activate Two factor auth.') }}</li>
        </ul>

        <hr class="mt-1">

        <div class="form-row">
            <div class="form-group mb-0 col-6">
                <label for="verify-code" class="sr-only">{{ __('Authenticator code') }}</label>
                <input id="verify-code" autocomplete="off" type="password" class="form-control @error('verify-code', 'is-invalid')" placeholder="Authenticator code" @input('verify-code') required>
                @error('verify-code')
            </div>
        </div>
    </div>

    <div class="card-footer border-0">
        <button type="submit" class="btn btn-submit shadow-sm border-0">
            {{ __('Enable 2FA') }}
        </button>
    </div>
</form>

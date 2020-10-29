<x-app-layout-auth>
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card border-0 shadow-sm">
                    <div class="card-header border-0">{{ __('Two factor authentication') }}</div>

                    <div class="card-body">
                        <form method="POST" action="{{ route('home') }}">
                            @csrf

                            <p class="card-text">
                                {{ __('Two factor authentication (2FA) strengthens access security by requiring two method (also referred to as factors) to verify your identity.') }}
                                {{ __('Two factor authentication protects against phishing, social engineering and password brute force attacks and secures') }}
                                {{ __('your logins from attackers exploiting weak or stolen credentials.') }}
                            </p>

                            <p class="card-text">{{ __('Enter de pin from Google Authenticator app:') }}</p>

                            <hr>

                            <div class="form-group row">
                                <label for="authenticatorCode" class="col-md-4 col-form-label text-md-right">{{ __('One time password') }}</label>

                                <div class="col-md-6">
                                    <input id="authenticatorCode" type="text" class="form-control @error(config('google2fa.otp_input'), 'is-invalid')" @input(config('google2fa.otp_input')) required autofocus>
                                    @error(config('google2fa.otp_input'))
                                </div>
                            </div>

                            <div class="form-group row mb-0">
                                <div class="col-md-8 offset-md-4">
                                    <button type="submit" class="btn btn-submit border-0 shadow-sm">
                                        {{ __('Authenticate') }}
                                    </button>

                                    <a class="btn btn-link text-decoration-none" href="{{ route('2fa.recovery') }}">
                                        {{ __('Use a recovery code?') }}
                                    </a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout-auth>

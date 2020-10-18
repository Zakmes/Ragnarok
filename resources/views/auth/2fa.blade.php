<x-app-layout-auth>
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card border-0 shadow-sm">
                    <div class="card-header border-0">{{ __('Two factor authentication') }}</div>

                    <div class="card-body">
                        <form method="POST" action="{{ route('2faVerify') }}">
                            @csrf

                            <p class="card-text">
                                {{ __('You have enabled Two factor authentication your account. Fill in the Google authenticator code to complete your login.') }}
                            </p>

                            <hr>

                            <div class="form-group row">
                                <label for="authenticatorCode" class="col-md-4 col-form-label text-md-right">{{ __('Authenticator code') }}</label>

                                <div class="col-md-6">
                                    <input id="authenticatorCode" type="password" class="form-control @error('one_time_password-code', 'is-invalid')" @input('one_time_password-code') required autofocus>
                                    @error('one_time_password-code')
                                </div>
                            </div>

                            <div class="form-group row mb-0">
                                <div class="col-md-8 offset-md-4">
                                    <button type="submit" class="btn btn-submit border-0 shadow-sm">
                                        {{ __('Login') }}
                                    </button>

                                    <a class="btn btn-link text-decoration-none" href="">
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

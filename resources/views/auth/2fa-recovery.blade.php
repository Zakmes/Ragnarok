<x-app-layout-auth>
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card border-0 shadow-sm">
                    <div class="card-header border-0">{{ __('Two factor authentication recovery') }}</div>

                    <div class="card-body">
                        <form method="POST" action="{{ route('2fa.recovery.challenge') }}">
                            @csrf

                            <p class="card-text">
                                {{ __('You lost your mobile devices of forgotten it. No problem we got u!') }}
                                {{ __('By simple fill in a recovery code you got from the 2FA configuration u can recover your account and successfully authenticate it.') }}
                                {{ __('Also note that using a recovery code will disable your two factor authentication configuration.') }}
                            </p>

                            <hr>

                            <div class="form-group row">
                                <label for="recoveryToken" class="col-md-4 col-form-label text-md-right">{{ __('Recovery token') }}</label>

                                <div class="col-md-6">
                                    <input id="recoveryToken" type="text" class="form-control @error('recovery_token', 'is-invalid')" @input('recovery_token') required autofocus>
                                    @error('recovery_token')
                                </div>
                            </div>

                            <div class="form-group row mb-0">
                                <div class="col-md-8 offset-md-4">
                                    <button type="submit" class="btn btn-submit border-0 shadow-sm">
                                        {{ __('Authenticate') }}
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout-auth>

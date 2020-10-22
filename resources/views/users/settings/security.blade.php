<x-app-layout>
    <div class="container-fluid py-3">
        <div class="page-header">
            <h1 class="page-title">{{ Auth::user()->name }}</h1>
            <div class="page-subtitle">{{ __('Account security information') }}</div>
        </div>
    </div>

    <div class="container-fluid pb-3">
        <div class="row">
            <div class="col-3">
                <x-profile-settings-nav/>
            </div>

            <div class="col-9">
                <form method="POST" action="{{ route('account.security.patch') }}" class="card border-0 shadow-sm">
                    @csrf
                    @method('PATCH')

                    <div class="card-body">
                        <h6 class="border-bottom border-gray pb-1 mb-3">{{ __('Account security settings') }}</h6>

                        <div class="form-row">
                            <div class="form-group col-12">
                                <label for="currentPassword">{{ __('Current password') }} <span class="text-danger">*</span></label>
                                <input type="password" class="form-control @error('currentPassword', 'is-invalid')" id="currentPassword" name="currentPassword">
                                @error('currentPassword')
                            </div>

                            <div class="form-group col-6 mb-0">
                                <label for="newPassword">{{ __('New password') }} <span class="text-danger">*</span></label>
                                <input type="password" class="form-control @error('password', 'is-invalid')" id="newPassword" name="password">
                                @error('password')
                            </div>

                            <div class="form-group col-6 mb-0">
                                <label for="passwordConfirmation">{{ __('Confirm password') }} <span class="text-danger">*</span></label>
                                <input id="passwordConfirmation" class="form-control" type="password" name="password_confirmation">
                            </div>
                        </div>
                    </div>

                    <div class="card-footer border-0">
                        <button type="submit" class="btn btn-submit border-0 shadow-sm">{{ __('Submit') }}</button>
                        <button type="reset" class="btn btn-link border-0 text-decoration-none">{{ __('Reset') }}</button>
                    </div>
                </form>

                {{-- Refactor to includeWhen --}}
                @if (auth()->user()->canSetupTwoFactorAuthentication())
                    <x-setup-two-factor-authentication :url="route('generate2faSecret')"/>
                @elseif (! auth()->user()->hasTwoFactorAuthEnabled())
                    <x-configure-two-factor-authentication :url="route('enable2fa')" :qrCode="$qrCodeInline"/>
                @elseif (auth()->user()->isUsingTwoFactorAuthentication())
                    <x-two-factor-authentication-configured :url="route('disable2fa')"/>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>

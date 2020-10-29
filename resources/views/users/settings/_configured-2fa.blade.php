<form action="{{ $url }}" method="POST" class="card mt-4 border-0 shadow-sm">
    @csrf
    @method('DELETE')

    <div class="card-body">
        <h6 class="border-bottom border-gray pb-1 mb-3">{{ __('Two Factor authentication') }} </h6>

        <p class="card-text text-success mb-3">
            <span class="font-weight-bold">
                <x:heroicon-o-badge-check class="icon mr-1"/> {{ __('Two factor authentication is currently enabled on your account.') }}
            </span>
        </p>

        <p class="card-text">
            {{ __('If you want ot disable the two facotr authentication you can do it simply by adding your current password in the form below.') }}
        </p>

        @if (session('recoveryTokens'))
            <h4>{{ __('Recovery tokens') }}</h4>

            <p class="card-text">
                {{ __('Here are the recovery tokens from your two factor authentication configuration. Make sure u write them down') }}
                {{ __('and store them safely.') }}
            </p>

            <div class="card card-two-factor card-body p-3 border-0 text-muted">
                {{-- dd(session('recoveryTokens')) --}}
                @foreach (session('recoveryTokens') as $value => $token)
                    {{ $token }} <br>
                @endforeach
            </div>
        @endif

        <hr>

        <div class="form-row">
            <div class="form-group mb-0 col-md-6">
                <label for="change-password" class="sr-only">{{ __('Current password') }}</label>
                <input id="current-password" autocomplete="off" placeholder="{{ __('Current password') }}" type="password" class="form-control @error('current-password', 'is-invalid')" @input('current-password') required>
                @error('current-password')
            </div>
        </div>
    </div>

    <div class="card-footer border-0 bg-card-footer">
        <button type="submit" class="btn btn-danger border-0 shadow-sm">{{ __('Disable 2FA') }}</button>
        <a href="{{ route('2faTokens.regenerate') }}" class="btn btn-link border-0">Regenerate recovery tokens</a>
    </div>
</form>

<div class="list-group list-group-transparent">
    <a href="{{ route('account.information') }}" class="{{ active('account.information') }} list-group-item list-group-item-action">
        <x:heroicon-o-information-circle class="icon mr-2"/> {{ __('General information') }}
    </a>

    @if (auth()->user()->apiModuleEnabled())
        <a href="{{ route('account.tokens') }}" class="{{ active('account.tokens') }} list-group-item-action list-group-item">
            <x:heroicon-o-key class="icon mr-2"/> {{ __('API tokens') }}
        </a>
    @endif

    <a href="{{ route('account.security') }}" class="{{ active('account.security') }} list-group-item list-group-item-action">
        <x:heroicon-o-lock-closed class="icon mr-2"/> {{ __('Account security') }}
    </a>
</div>

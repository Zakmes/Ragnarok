<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-modal-header border-0">
                <h5 class="modal-title" id="exampleModalLabel">{{ __('API token created') }}</h5>
            </div>
            <div class="modal-body">
                {{ __('Here is your new API token. This is the only time the token will ever be displayed,') }}
                {{ __('so be sure not to lose it! You may revoke the token at any time from your API settings.') }}

                <hr class="mt-2">

                <textarea cols="3" class="form-control">{{ session()->get('token') }}</textarea>
            </div>
            <div class="modal-footer bg-modal-footer border-0">
                <button type="button" class="btn btn-option border-0 shadow-sm" data-dismiss="modal">{{ __('Close') }}</button>
            </div>
        </div>
    </div>
</div>

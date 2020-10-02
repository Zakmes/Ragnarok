<x-app-layout>
   <div class="container-fluid py-3">
       <div class="page-header">
           <h1 class="page-title">{{ $user->name }}</h1>
           <div class="page-subtitle">{{ __('User deactivation') }}</div>

           <div class="page-options d-flex">
               <a href="{{ kioskRoute('users.index') }}" class="btn btn-option shadow-sm border-0">
                   <x:heroicon-o-menu class="icon mr-1"/> {{ __('Overview') }}
               </a>
           </div>
       </div>
   </div>

    <div class="container-fluid pb-3">
        <div class="row">
            <div class="col-md-3">
                <x-account-sidenav :user="$user"/>
            </div>

            <div class="col-md-9">
                <form action="{{ kioskRoute('users.lock', $user) }}" method="POST" class="card border-0 shadow-sm">
                    @csrf

                    <div class="card-body">
                        <h6 class="border-bottom border-gray pb-1 mb-3">{{ __('Deactivate account from :user', ['user' => $user->name]) }}</h6>

                        <p class="card-text text-danger">
                            <x:heroicon-o-exclamation class="icon mr-1"/> {{ __('By deactivating the account the user can\'t login anymore') }}
                        </p>

                        <p class="card-text">
                            {{ __('Before we can deactivate :user we ask you to provide a reason.', ['user' => $user->name]) }} <br>
                            {{ __('So we can inform :user and other administrators why the account is deactivated.', ['user' => $user->name]) }}
                        </p>

                        <hr>

                        <div class="form-row">
                            <div class="form-group col-12 mb-0">
                                <label for="reason">{{ __('Deactivation reason') }} <span class="text-danger">*</span></label>
                                <textarea placeholder="{{ __('Shortly explain why u want to deactivate the account.') }}" id="reason" rows="3" class="form-control @error('reason', 'is-invalid')" @input('reason')>{{ old('reason') }}</textarea>
                                @error('reason')
                            </div>
                        </div>
                    </div>

                    <div class="card-footer border-0">
                        <a href="{{ kioskRoute('users.show', $user) }}" class="btn btn-link text-decoration-none border-0">
                            {{ __('Cancel') }}
                        </a>

                        <button type="submit" class="btn btn-submit border-0 shadow-sm">
                            {{ __('Deactivate') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>

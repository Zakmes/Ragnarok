<x-app-layout>
    <div class="container-fluid py-3">
        <div class="page-header">
            <h1 class="page-title">{{ Auth::user()->name }}</h1>
            <div class="page-subtitle">{{ __('Account information') }}</div>
        </div>
    </div>

    <div class="container-fluid pb-3">
        <div class="row">
            <div class="col-3">
                <x-profile-settings-nav/>
            </div>

            <div class="col-9">
                <form action="{{ route('account.information.patch') }}" method="POST" class="card shadow-sm border-0">
                    @csrf
                    @method('PATCH')
                    @form(auth()->user())

                    <div class="card-body">
                        <h6 class="border-bottom border-gray pb-1 mb-3">{{ __('Account information settings') }}</h6>

                        <div class="form-row">
                            <div class="form-group col-6">
                                <label for="firstName"> {{ __('First name') }} <span class="text-danger">*</span></label>
                                <input id="firstName" type="text" class="form-control @error('firstName', 'is-invalid')" @input('firstName')>
                                @error('firstName')
                            </div>

                            <div class="form-group col-6">
                                <label for="lastName"> {{ __('Last name') }} <span class="text-danger">*</span></label>
                                <input id="lastName" type="text" class="form-control @error('lastName', 'is-invalid')" @input('lastName')>
                                @error('lastName')
                            </div>

                            <div class="form-group col-12 mb-0">
                                <label for="email">{{ __('Email address') }} <span class="text-danger">*</span></label>
                                <input id="email" type="email" class="form-control @error('email', 'is-invalid')" @input('email')>
                                @error('email')
                            </div>
                        </div>
                    </div>
                    <div class="card-footer border-0">
                        <button type="submit" class="btn btn-submit border-0 shadow-sm">{{ __('Submit') }}</button>
                        <button type="reset" class="btn btn-link text-decoration-none border-0">{{ __('Reset') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>

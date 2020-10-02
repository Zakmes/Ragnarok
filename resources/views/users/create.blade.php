<x-app-layout>
    <div class="container-fluid py-3">
        <div class="page-header">
            <h1 class="page-title">{{ __('Users') }}</h1>
            <div class="page-subtitle">{{ __('Create new user in :application', ['application' => config('app.name')]) }}</div>

            <div class="page-options d-flex">
                <a href="{{ kioskRoute('users.index') }}" class="btn btn-option shadow-sm border-0">
                    <x:heroicon-o-menu class="icon mr-1"/> {{ __('Overview') }}
                </a>
            </div>
        </div>
    </div>

    <div class="container-fluid pb-3">
        <form action="{{ kioskRoute('users.store') }}" method="POST" class="card border-0 shadow-sm">
            @csrf

            <div class="card-body">
                <h6 class="border-bottom border-gray pb-1 mb-3">{{ __('Create new user in :application', ['application' => config('app.name')]) }}</h6>

                <div class="row">
                    <div class="col-4">
                        <h5>{{ __('General information') }}</h5>
                    </div>
                    <div class="offset-1 col-7">
                        <div class="form-row">
                            <div class="form-group col-5">
                                <label for="firstname">{{ __('First name') }} <span class="text-danger">*</span></label>
                                <input id="firstname" type="text" class="form-control @error('firstName', 'is-invalid')" @input('firstName')>
                                @error('firstName')
                            </div>

                            <div class="form-group col-7">
                                <label for="lastName">{{ __('Last name') }} <span class="text-danger">*</span></label>
                                <input id="lastName" type="text" class="form-control @error('lastName', 'is-invalid')" @input('lastName')>
                                @error('lastName')
                            </div>

                            <div class="form-group col-12 mb-0">
                                <label for="email">{{ __('Email address') }} <span class="text-danger">*</span></label>
                                <input type="email" class="form-control @error('email', 'is-invalid')" id="email" @input('email')>
                                @error('email')
                            </div>
                        </div>
                    </div>
                </div>

                <hr>

                <div class="row">
                    <div class="col-4">
                        <h5>{{ __('User group and permission group') }}</h5>
                    </div>

                    <div class="offset-1 col-7">
                        <div class="form-row">
                            <div class="form-group col-6 mb-0">
                                <label for="userGroup">{{ __('User type') }}</label>
                                <select id="userGroup" class="custom-select @error('userGroup', 'is-invalid')" @input('userGroup')>
                                    @foreach ($userGroups as $value => $group)
                                        <option value="{{ $group }}" @if (old('userGroup') === $group) selected @endif>{{ $group }}</option>
                                    @endforeach
                                </select>

                                @error('userGroup')
                            </div>

                            <div class="form-group col-6 mb-0">
                                <label for="permissionGroup">{{ __('Permission group') }}</label>
                                <select id="permissionGroup" class="custom-select @error('role', 'is-invalid')" @input('role') @if(count($roles) === 0) disabled @endif>
                                    @if (count($roles) === 0)
                                        <option value="">No permission groups found</option>
                                    @endif

                                    <option value="">-- {{ __('Select the permission role for the user.') }} --</option>

                                    @foreach ($roles as $role)
                                        <option value="{{ $role }}" @if (old('role') === $role) selected @endif>{{ $role }}</option>
                                    @endforeach
                                </select>

                                @error('role')
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer border-0">
                <div class="float-right">
                    <button type="submit" class="btn btn-submit border-0 shadow-sm">{{ __('Submit') }}</button>
                    <button type="reset" class="btn btn-link text-decoration-none border-0">{{ __('Reset') }}</button>
                </div>
            </div>
        </form>
    </div>
</x-app-layout>

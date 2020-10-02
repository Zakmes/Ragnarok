<x-app-layout>
    <div class="container-fluid py-3">
        <div class="page-header">
            <h1 class="page-title">{{ $user->name }}</h1>
            <div class="page-subtitle">{{ __('Edit user account') }}</div>

            <div class="page-options d-flex">
                <a href="{{ kioskRoute('users.index') }}" class="btn btn-option border-0 shadow-sm">
                    <x:heroicon-o-menu class="icon mr-1"/> {{ __('Overview') }}
                </a>
            </div>
        </div>
    </div>

    <div class="container-fluid pb-3">
        <div class="row">
            <div class="col-3">
                <x-account-sidenav :user="$user"/>
            </div>

            <div class="col-9">
                <form action="{{ kioskRoute('users.update', $user) }}" method="POST" class="card border-0 shadow-sm">
                    @csrf
                    @method ('PATCH')
                    @form($user)

                    <div class="card-body">
                        <h6 class="border-bottom border-gray pb-1 mb-3">{{ __('Edit :user in the application', ['user' => $user->name]) }}</h6>

                        <div class="form-row">
                            <div class="col-5 form-group">
                                <label for="firstName">{{ __('First name') }} <span class="text-danger">*</span></label>
                                <input type="text" id="firstName" class="form-control @error('firstName', 'is-invalid')" @input('firstName')>
                                @error('firstName')
                            </div>

                            <div class="col-7 form-group">
                                <label for="lastName">{{ __('Last name') }} <span class="text-danger">*</span></label>
                                <input type="text" id="lastName" class="form-control @error('lastName', 'is-invalid')" @input('lastName')>
                                @error('lastName')
                            </div>

                            <div class="col-12 form-group">
                                <label for="email">{{ __('Email address') }} <span class="text-danger">*</span></label>
                                <input type="email" id="email" class="form-control @error('email', 'is-invalid')" @input('email')>
                                @error('email')
                            </div>
                            @foreach ($userGroups as $value => $group)
                                @endforeach

                            <div class="col-form-group col-6 mb-0">
                                <label for="userGroup">{{ __('User type') }} <span class="text-danger">*</span></label>
                                <select id="userGroup" class="custom-select @error('userGroup', 'is-invalid')" @input('userGroup')>
                                    @foreach ($userGroups as $value => $group)
                                        <option value="{{ $group }}" @if ($user->user_group === $group) selected @endif>
                                            {{ $group }}
                                        </option>
                                    @endforeach
                                </select>

                                @error('userGroup')
                            </div>

                            <div class="col-form-group col-6 mb-0">
                                <label for="permissionGroup">{{ __('Permission group') }}</label>

                                <select id="permissionGroup" class="custom-select @error('role', 'is-invalid')" @input('role') @if(count($roles) === 0) disabled @endif>
                                    @if (count($roles) === 0)
                                        <option value="">No permission groups found</option>
                                    @endif

                                    <option value="">-- {{ __('Select the permission role for the user.') }} --</option>

                                    @foreach ($roles as $role)
                                        <option value="{{ $role }}" @if ($user->hasRole($role)) selected @endif>
                                            {{ $role }}
                                        </option>
                                    @endforeach
                                </select>

                                @error('role')
                            </div>
                        </div>

                        <fieldset @cannot ('change-passwords', $user) disabled @endcan>
                            <hr class="py-0">

                            <div class="form-row">
                                <div class="form-group mb-0 col-6">
                                    <label for="newPassword">{{ __('Password') }}</label>
                                    <input type="password" id="newPassword" class="form-control @error('password', 'is-invalid')" name="password">
                                    @error('password')
                                </div>

                                <div class="form-group mb-0 col-6">
                                    <label for="passwordConfirmation">{{ __('Repeat password') }}</label>
                                    <input type="password" class="form-control" name="password_confirmation" id="passwordConfirmation">
                                </div>
                            </div>
                        </fieldset>
                    </div>

                    <div class="card-footer border-0">
                        <button type="submit" class="btn btn-submit border-0 shadow-sm">{{ __('Submit') }}</button>
                        <button type="reset" class="btn btn-link border-0 text-decoration-none">{{ __('Reset') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>

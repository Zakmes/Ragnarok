<x-app-layout>
    <div class="container-fluid py-3">
        <div class="page-header">
            <h1 class="page-title">{{ __('Roles') }}</h1>
            <div class="page-subtitle">{{ __('Edit permission role') }}</div>
        </div>
    </div>

    <div class="container-fluid pb-3">
        <div class="row">
            <div class="col-3">
                <x-role-information-sidenav :role="$role"/>
            </div>

            <div class="col-9">
                <form action="{{ kioskRoute('roles.update', $role) }}" method="POST" class="card border-0 shadow-sm">
                    @csrf
                    @method('patch')
                    @form($role)

                    <div class="card-body">
                        <h6 class="border-bottom border-gray pb-1 mb-3">{{ __('Edit permission role in the application.') }}</h6>

                        <div class="form-row">
                            <div class="form-group col-12">
                                <label for="name">{{ __('Role name') }} <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('name', 'is-invalid')" id="name" @input('name')>
                                @error('name')
                            </div>

                            <div class="form-group col-12 mb-0">
                                <label for="description">{{ __('Role description') }} <span class="text-danger">*</span></label>
                                <textarea type="text" rows="3" class="form-control @error('description', 'is-invalid')" id="name" name="description">{{ old('description') ?? $role->description}}</textarea>
                                @error('description')
                            </div>
                        </div>

                        <hr>

                        <h5>{{ __('User Permissions') }}</h5>

                        <div class="form-row">
                            @foreach ($userPermissions as $userPermission)
                                <div class="form-group col-6 mb-0">
                                    <div class="form-check">
                                        <input class="form-check-input" name="permission[]" type="checkbox" @if ($role->hasPermissionTo($userPermission->name)) checked @endif  value="{{ $userPermission->name }}" id="{{ $userPermission->name }}">
                                        <label class="form-check-label" for="{{ $userPermission->name }}">
                                            {{ $userPermission->description }}
                                        </label>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <hr>

                        <h5 class="mt-2">{{ __('Role permissions') }}</h5>

                        <div class="form-row">
                            @foreach ($rolePermissions as $rolePermission)
                                <div class="form-group col-6 mb-0">
                                    <div class="form-check">
                                        <input class="form-check-input" name="permission[]" type="checkbox" @if ($role->hasPermissionTo($rolePermission->name)) checked @endif value="{{ $rolePermission->name }}" id="{{ $rolePermission->name }}">
                                        <label class="form-check-label" for="{{ $rolePermission->name }}">
                                            {{ $rolePermission->description }}
                                        </label>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <hr>

                        <h5 class="mt-2">{{ __('API management permissions') }}</h5>

                        <div class="form-row">
                            @foreach ($apiTokenPermissions as $apiTokenPermission)
                                <div class="form-group col-6 mb-0">
                                    <div class="form-check">
                                        <input class="form-check-input" name="permission[]" type="checkbox" @if ($role->hasPermissionTo($apiTokenPermission->name)) checked @endif value="{{ $apiTokenPermission->name }}" id="{{ $apiTokenPermission->name }}">
                                        <label class="form-check-label" for="{{ $apiTokenPermission->name }}">
                                            {{ $apiTokenPermission->description }}
                                        </label>
                                    </div>
                                </div>
                            @endforeach
                        </div>
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

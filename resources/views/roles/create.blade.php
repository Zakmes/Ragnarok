<x-app-layout>
    <div class="container-fluid py-3">
        <div class="page-header">
            <h1 class="page-title">{{ __('Roles') }}</h1>
            <div class="page-subtitle">{{ __('Create new user permission group') }}</div>

            <div class="page-options d-flex">
                <a href="{{ kioskRoute('roles.index') }}" class="btn btn-option shadow-sm border-0">
                    <x:heroicon-o-menu class="icon mr-1"/> {{ __('Overview') }}
                </a>
            </div>
        </div>
    </div>

    <div class="container-fluid pb-3">
        <form method="POST" action="{{ kioskRoute('roles.store') }}" class="card border-0 shadow-sm">
            @csrf

            <div class="card-body">
                <h6 class="border-bottom border-gray pb-1 mb-3">{{ __('Create new permission role in the application') }}</h6>

                <div class="row">
                    <div class="col-3">
                        <h5>{{ __('General information') }}</h5>
                    </div>
                    <div class="offset-1 col-8">
                        <div class="form-row">
                            <div class="form-group col-12">
                                <label for="name">{{ __('Role name') }} <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('name', 'is-invalid')" id="name" @input('name')>
                                @error('name')
                            </div>

                            <div class="form-group col-12">
                                <label for="description">{{ __('Role description') }} <span class="text-danger">*</span></label>
                                <textarea type="text" rows="3" class="form-control @error('description', 'is-invalid')" id="name" name="description">{{ old('description') }}</textarea>
                                @error('description')
                            </div>
                        </div>
                    </div>
                </div>

                <hr class="mt-0 mb-3">

               <div class="row">
                   <div class="col-3">
                       <h5>{{ __('User permissions') }}</h5>
                   </div>
                   <div class="offset-1 col-8">
                       <div class="form-row">
                            @foreach ($userPermissions as $userPermission)
                               <div class="form-group col-6 mb-0">
                                   <div class="form-check">
                                       <input class="form-check-input" name="permission[]" type="checkbox" value="{{ $userPermission->name }}" id="{{ $userPermission->name }}">
                                       <label class="form-check-label" for="{{ $userPermission->name }}">
                                           {{ $userPermission->description }}
                                       </label>
                                   </div>
                               </div>
                            @endforeach
                       </div>
                   </div>
               </div>

                <hr class="mt-3 mb-3">

                <div class="row">
                    <div class="col-3">
                        <h5>{{ __('Role permissions') }}</h5>
                    </div>
                    <div class="offset-1 col-8">
                        <div class="form-row">
                            @foreach ($rolePermissions as $rolePermission)
                                <div class="form-group col-6 mb-0">
                                    <div class="form-check">
                                        <input class="form-check-input" name="permission[]" type="checkbox" value="{{ $rolePermission->name }}" id="{{ $rolePermission->name }}">
                                        <label class="form-check-label" for="{{ $rolePermission->name }}">
                                            {{ $rolePermission->description }}
                                        </label>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <hr class="mt-3 mb-3">

                <div class="row">
                    <div class="col-3">
                        <h5>{{ __('API management permissions') }}</h5>
                    </div>
                    <div class="offset-1 col-8">
                        <div class="form-row">
                            @foreach ($apiTokenPermissions as $apiTokenPermission)
                                <div class="form-group col-6 mb-0">
                                    <div class="form-check">
                                        <input class="form-check-input" name="permission[]" type="checkbox" value="{{ $apiTokenPermission->name }}" id="{{ $rolePermission->name }}">
                                        <label class="form-check-label" for="{{ $apiTokenPermission->name }}">
                                            {{ $apiTokenPermission->description }}
                                        </label>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer border-0">
                <div class="float-right">
                    <button type="reset" class="btn btn-link border-0 text-decoration-none">{{ __('Cancel') }}</button>
                    <button type="submit" class="btn btn-submit border-0 shadow-sm">{{ __('Submit') }}</button>
                </div>
            </div>
        </form>
    </div>
</x-app-layout>

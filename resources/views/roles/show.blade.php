<x-app-layout>
    <div class="container-fluid py-3">
        <div class="page-header">
            <h1 class="page-title">{{ __('Roles') }}</h1>
            <div class="page-subtitle">{{ __('General information about :role', ['role' => $role->name]) }}</div>
        </div>
    </div>

    <div class="container-fluid pb-3">
        <div class="row">
            <div class="col-3">
                <x-role-information-sidenav :role="$role"/>
            </div>

            <div class="col-9">
                <div class="card card-body shadow-sm border-0">
                    <div class="table-responsive">
                        <table class="table table-sm table-borderless mb-0">
                            <tr>
                                <td class="w-50 pt-1 px-0">
                                    <span class="font-weight-bold text-muted">{{ __('Role name') }}</span> <br>
                                    {{ $role->name }}
                                </td>
                                <td class="w-50 pt-1">
                                    <span class="font-weight-bold text-muted">{{ __('Description') }}</span> <br>
                                    {{ $role->description }}
                                </td>
                            </tr>
                            <tr>
                                <td class="mb-0 pt-1 px-0" colspan="2">
                                    <span class="font-weight-bold pb-4 mb-4 text-muted">{{ __('Permissions') }}</span> <br class="mb-3">

                                    @forelse ($role->permissions as $permission)
                                        <x:heroicon-o-check class="icon text-success mr-1"/> {{ $permission->description }} <br/>
                                    @empty
                                        <span class="text-info">
                                             <x:heroicon-o-information-circle class="icon mr-1"/> {{ __('There are no permissions attached to the role') }}
                                        </span>
                                    @endforelse
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

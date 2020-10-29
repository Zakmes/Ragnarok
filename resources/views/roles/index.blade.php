<x-app-layout>
    <div class="container-fluid py-3">
        <div class="page-header">
            <h1 class="page-title">{{ __('Roles') }}</h1>
            <div class="page-subtitle">{{ __('Overview from all user roles in the application.') }}</div>

            <div class="page-options d-flex">
                <a href="{{ kioskRoute('roles.create') }}" class="mr-2 btn btn-option border-0 shadow-sm">
                    <x:heroicon-o-plus class="icon"/>
                </a>

                <form method="GET" action="{{ kioskRoute('roles.search') }}" class="form-inline">
                    <input type="text" name="term" value="{{ request('term') }}" class="form-control form-search border-0 shadow-sm" placeholder="{{ __('Search user role') }}">
                </form>
            </div>
        </div>
    </div>

    <div class="container-fluid pb-3">
        <div class="border-0 shadow-sm card card-body">
            <div class="table-responsive">
                <table class="table table-hover mb-0 table-sm">
                    <thead>
                        <tr>
                            <th class="border-top-0" scope="col">{{ __('Role name') }}</th>
                            <th class="border-top-0" scope="col">{{ __('Creator') }}</th>
                            <th class="border-top-0" scope="col">{{ __('Description') }}</th>
                            <th class="border-top-0" scope="col" colspan="2">{{ __('Created at') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($roles as $role)
                            <tr>
                                <td class="text-muted font-weight-bold">{{ $role->name }}</td>
                                <td>{{ $role->creator->name ?? __('Unknown user') }}</td>
                                <td>{{ $role->description }}</td>
                                <td>{{ $role->created_at->format('d/m/Y') }}</td>

                                <td> {{-- Options --}}
                                   <div class="float-right">
                                       @can ('view', $role)
                                           <a href="{{ kioskRoute('roles.show', $role) }}" class="text-muted text-decoration-none mr-1">
                                               <x:heroicon-o-eye class="icon"/>
                                           </a>
                                       @endcan

                                       @can ('update', $role)
                                           <a href="{{ kioskRoute('roles.edit', $role) }}" class="text-muted text-decoration-none mr-1">
                                              <x:heroicon-o-pencil-alt class="icon"/>
                                            </a>
                                       @endcan

                                       @can('destroy', $role)
                                           <a href="{{ kioskRoute('roles.destroy', $role) }}" class="text-danger text-decoration-none">
                                               <x:heroicon-o-trash class="icon"/>
                                           </a>
                                       @endcan
                                   </div>
                                </td> {{-- /// Options --}}
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-muted">
                                    <x:heroicon-o-information-circle class="icon"/>

                                    @if (request('term'))
                                        {{ __('There are no roles found with the following keyword: :keyword', ['keyword' => request('keyword')]) }}
                                    @else {{-- It is not a search request --}}
                                        {{ __('There are currently no user roles found.') }}
                                    @endif
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>

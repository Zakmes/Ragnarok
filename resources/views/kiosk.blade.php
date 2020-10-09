<x-app-layout>
    <div class="container-fluid py-3">
        <div class="row">
            <div class="col-4">
                <div class="text-center">
                    <h3>{{ __('Activity Logs') }}</h3>
                </div>
                <div class="table-responsive">
                    <table class="table table-sm mb-0 table-hover">
                        <thead class="text-center">
                          <tr>
                            <th scope="col">{{ __('Log Name') }}</th>
                            <th scope="col">{{ __('Description') }}</th>
                            <th scope="col">{{ __('User') }}</th>
                            <th>{{ __('Created At') }}</th>
                          </tr>
                        </thead>
                        <tbody>
                            @foreach($activities as $activity)
                            <tr>
                                <th scope="row">{{ $activity->log_name }}</th>
                                <td>{{ $activity->description }}</td>
                                <td>
                                    {{ $activity->causer->name ?? __('Unknown user') }}
                                </td>
                                <td>
                                    {{ $activity->created_at->format('d/m/Y - H:i:s') }}
                                </td>
                            </tr>
                            @endforeach
                            <tr class="text-center">
                                <td colspan="4">
                                    <a href="{{ kioskRoute('activity.index') }}">{{ __('See more') }}</a>
                                </td>
                            </tr>
                        </tbody>
                      </table>
                </div>
            </div>
            <div class="col-4">
                <div class="text-center">
                    <h3>{{ __('Latest Created User') }}</h3>
                </div>
                <div class="table-responsive">
                    <table class="table table-sm mb-0 table-hover">
                        <thead class="text-center">
                          <tr>
                            <th scope="col">{{ __('User Group') }}</th>
                            <th scope="col">{{ __('E-Mail') }}</th>
                            <th>{{ __('Name') }}</th>
                          </tr>
                        </thead>
                        <tbody>
                            @foreach($users as $user)
                            <tr>
                                <td>{{ $user->user_group }}</td>
                                <td>{{ $user->email }}</td>
                                <td>{{ $user->name }}</td>
                            </tr>
                            @endforeach
                            <tr class="text-center">
                                <td colspan="3">
                                    <a href="{{ kioskRoute('users.index') }}">{{ __('See more') }}</a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="col-4">
                <div class="text-center">
                    <h3>{{ __('Latest Created Roles') }}</h3>
                </div>
                <div class="table-repsonsive">
                    <table class="table table-sm mb-0 table-hover">
                        <thead class="text-center">
                          <tr>
                            <th scope="col">{{ __('Role Name') }}</th>
                            <th scope="col">{{ __('Description') }}</th>
                            <th scope="col">{{ __('Created By') }}</th>
                            <th scope="col"></th>
                          </tr>
                        </thead>
                        <tbody>
                            @foreach($roles as $role)
                            <tr>
                              <th scope="row">{{ $role->name }}</th>
                              <td>{{ $role->description }}</td>
                              <td>{{ $role->creator->name ?? __('Unknown user') }}</td>
                              <td>
                                <a href="{{ kioskRoute('roles.show', $role) }}" class="text-muted text-decoration-none mr-1">
                                    <x:heroicon-o-eye class="icon"/>
                                </a>
                              </td>
                            </tr>
                            @endforeach
                            <tr class="text-center">
                                <td colspan="4">
                                    <a href="{{ kioskRoute('roles.index') }}">{{ __('See more') }}</a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

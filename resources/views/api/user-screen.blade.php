<x-app-layout>
    <div class="container-fluid py-3">
        <div class="page-header">
            <h1 class="page-title">{{ $user->name }}</h1>
            <div class="page-subtitle">{{ __('API token management') }}</div>
        </div>
    </div>

    <div class="container-fluid pb-3">
        <div class="row">
            <div class="col-3">
                <x-profile-settings-nav/>
            </div>

            <div class="col-9">
                <form action="{{ route('account.tokens') }}" method="POST" class="card border-0 shadow-sm mb-4">
                    @csrf

                    <div class="card-body">
                        <h6 class="border-bottom border-gray pb-1 mb-3">{{ __('Create new API token') }}</h6>

                        <div class="form-group mb-0">
                            <label for="serviceName">{{ __('Service name') }} <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('tokenName', 'is-invalid')" id="serviceName" @input('tokenName')>
                            @error('tokenName')
                        </div>
                    </div>
                    <div class="card-footer border-0">
                        <button type="submit" class="btn btn-submit border-0 shadow-sm">{{ __('Create token') }}</button>
                        <button type="reset" class="btn btn-link border-0 text-decoration-none">{{ __('Reset') }}</button>
                    </div>
                </form>

                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <h6 class="border-bottom border-gray pb-1 mb-3">{{ __('Your API tokens') }}</h6>

                        <table class="table table-sm table-hover table-hover mb-0">
                            <thead>
                                <tr>
                                    <th class="border-top-0 w-50" scope="col">{{ __('Name') }}</th>
                                    <th class="border-top-0 w-25" scope="col">{{ __('Last used at') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($tokens as $token)
                                   <tr>
                                       <td>{{ $token->name }}</td>
                                       <td>{{ $token->last_used_at ?? '-' }}</td>
                                       <td> {{-- Options --}}
                                           <a href="{{ route('account.tokens.revoke', $token) }}" class="small text-decoration-none float-right text-danger mr-1">
                                               {{ __('Revoke') }}
                                           </a>
                                       </td> {{-- /// Options --}}
                                   </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-muted">
                                            <x:heroicon-o-information-circle class="icon"/>
                                            {{ __('You have any personal access token in the application.') }}
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if ($tokens->hasPages())
                        <div class="card-footer border-0">
                            <div class="row d-flex align-items-center">
                                <div class="col">{{ $tokens->links() }}</div>

                                <div class="col text-right text-muted">
                                    {{ __('Showing :firstItem to :lastItem out of :total tokens', [
                                        'firstItem' => $tokens->firstItem(), 'lastItem' => $tokens->lastItem(), 'total' => $tokens->total()
                                    ]) }}
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @if (session()->has('token'))
        <x-token-modal/>

        <script>
            window.addEventListener('load', function() {
                $(function() {
                    $('#exampleModal').modal('show');
                });
            })
        </script>
    @endif
</x-app-layout>

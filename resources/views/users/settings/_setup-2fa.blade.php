<form action="{{ $url }}" method="POST" class="mt-4 card border-0 shadow-sm">
    @csrf {{-- Form field protection --}}

    <div class="card-body">
        <h6 class="border-bottom border-gray pb-1 mb-3">{{ __('Setup two factor authentication.') }}</h6>

        @if (session('error'))
            <div class="alert alert-danger border-0" role="alert">{{ session('error') }}</div>
        @elseif (session('success'))
            <div class="alert alert-success border-0" role="alert">{{ session('success') }}</div>
        @endif
    </div>

    <div class="card-footer border-0">

    </div>
</form>

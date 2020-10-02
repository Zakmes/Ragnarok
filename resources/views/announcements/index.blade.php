<x-app-layout>
    <div class="container-fluid py-3">
        <div class="page-header">
            <h1 class="page-title">{{ __('Announcements') }}</h1>
            <div class="page-subtitle">{{ __('Overview from all the unread announcements') }}</div>
        </div>
    </div>

    <div class="container-fluid pb-3">
        <div class="row">
            <div class="col-3">
                <div class="list-group list-group-transparent">
                    <a href="{{ route('announcements.index') }}" class="{{ active('announcements.index') }} list-group-item list-group-item-action">
                        <x:heroicon-o-speakerphone class="icon mr-2 icon-subnav"/> {{ __('Announcements') }}
                    </a>
                </div>
            </div>

            <div class="col-9">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <h6 class="border-bottom border-gray pb-1 mb-2">{{ __('Announcements from :application', ['application' => config('app.name')]) }}</h6>

                        @if ($announcements->count() > 0) {{-- There are notifications found in the application --}}
                            @foreach ($announcements as $announcement)
                                <div class="media pt-2">
                                    <img src="{{ avatar($announcement->creator->name) }}" alt="{{ $announcement->creator->name }}" class="mr-3 shadow-sm rounded announcement-avatar">

                                    <div class="card w-100 card-text border-0 mb-0">
                                        <div class="w-100">
                                            <strong class="float-left mr-1">{{ $announcement->title }} - {{ $announcement->created_at->diffForHumans() }}</strong><br>
                                        </div>

                                        <p class="text-muted card-text mb-2">{{ $announcement->message }}</p>

                                        <a href="{{ route('announcements.mark', $announcement) }}" class="text-decoration-none card-text announcement-action-text">
                                            <x:heroicon-o-eye-off class="icon mr-1"/> {{ __('Mark as read') }}
                                        </a>
                                    </div>
                                </div>

                                @if (! $loop->last) {{-- This announcement is not the latest so we need a breakline --}}
                                    <hr class="mt-2 mb-0">
                                @endif
                            @endforeach
                        @else
                            <p class="card-text text-muted">
                                <x:heroicon-o-speakerphone class="icon mr-1"/> {{ __('There are currently no unread announcements for u.') }}
                            </p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

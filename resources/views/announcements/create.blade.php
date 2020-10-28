<x-app-layout>
    <div class="container-fluid py-3">
        <div class="page-header">
            <h1 class="page-title">{{ __('Announcements') }}</h1>
            <div class="page-subtitle">{{ __('Create an new announcement') }}</div>

            <div class="page-options d-flex">
                <a href="{{ kioskRoute('announcements.overview') }}" class="btn btn-option border-0 shadow-sm">
                    <x:heroicon-o-menu class="icon mr-1"/> {{ __('Overview') }}
                </a>
            </div>
        </div>
    </div>

    <div class="container-fluid pb-3">
        <form method="POST" action="{{ kioskRoute('announcements.store') }}" class="border-0 card shadow-sm">
            @csrf

            <div class="card-body">
                <div class="row mt-1">
                    <div class="col-4">
                        <h5>{{ __('Announcement title') }}</h5>
                    </div>

                    <div class="offset-1 col-7">
                        <input type="text" class="form-control @error('title', 'is-invalid')" @input('title')/>
                        @error('title')
                    </div>
                </div>

                <hr>

                <div class="row">
                    <div class="col-4">
                        <h5>{{ __('General settings &amp; time window') }}</h5>
                    </div>

                    <div class="offset-1 col-7">
                        <div class="form-row">
                            <div class="form-group col-4">
                                <label for="status">{{ __('Status') }}</label>

                                <select class="custom-select @error('status', 'is-invalid')" @input('status') id="status">
                                    <option value="true" @if (old('status') === true) selected @endif>{{ __('Enabled') }}</option>
                                    <option value="false">{{ __('Disabled') }} @if(old('status') === false) selected @endif</option>
                                </select>

                                @error('status')
                            </div>

                            <div class="form-group col-4">
                                <label for="visibility">{{ __('Visibility') }}</label>

                                <select id="visibility" class="custom-select @error('visibility', 'is-invalid')" @input('visibility')>
                                    @foreach ($areas as $value => $area)
                                        <option value="{{ $area }}" @if ($area === old('area')) selected @endif)>
                                            {{ ucfirst($area ?? __('Global')) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group col-4">
                                <label for="type">{{ __('Type') }}</label>

                                <select class="custom-select @error('type', 'is-invalid')" id="type" @input('type')>
                                    @foreach ($types as $value => $type)
                                        <option value="{{ $type }}" @if (old('type') === $type) selected @endif>
                                            {{ ucfirst($type) }}
                                        </option>
                                    @endforeach
                                </select>

                                @error('type')
                            </div>

                            <div class="form-group col-6 mb-0">
                                <label for="startDate">{{ __('Start date') }} <span class="text-danger">*</span></label>
                                <input id="startDate" type="text" autocomplete="off" class="form-control datepicker @error('start_date', 'is-invalid')" @input('start_date')>
                                @error('start_date')
                            </div>

                            <div class="form-group col-6 mb-0">
                                <label for="endDate">{{ __('End date') }} <span class="text-danger">*</span></label>
                                <input id="endDate" type="text" autocomplete="off" class="form-control datepicker @error('end_date', 'is-invalid')" @input('end_date')>
                                @error('end_date')
                            </div>
                        </div>
                    </div>
                </div>

                <hr>

                <div class="row">
                    <div class="col-4">
                        <h5>{{ __('Announcement') }}</h5>
                    </div>

                    <div class="offset-1 col-7">
                        <textarea rows="4" class="form-control @error('message', 'is-invalid')" placeholder="{{ __('Keep your announcement as short as possible') }}" @input('message')>{{ old('message') }}</textarea>
                        @error('message')
                    </div>
                </div>
            </div>

            <div class="card-footer border-0">
                <button type="submit" class="btn btn-submit border-0 shadow-sm">{{ __('Submit') }}</button>
                <button type="reset" class="btn btn-link text-decoration-none">{{ __('Reset') }}</button>
            </div>
        </form>
    </div>
</x-app-layout>

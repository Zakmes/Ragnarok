@if (flash()->message)
    <div class="border-0 mb-0 rounded-0 alert {{ flash()->class }}" role="alert">
        {{ flash()->message }}
    </div>
@endif

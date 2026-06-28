@props(['status'])

@if($status)
    <div class="alert alert-success d-flex align-items-center gap-2 mb-3" style="font-size:.82rem">
        <i class="fa-solid fa-circle-check flex-shrink-0"></i>
        {{ $status }}
    </div>
@endif

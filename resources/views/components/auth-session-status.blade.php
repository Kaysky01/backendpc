@props(['status'])

@if ($status)
    <div {{ $attributes->merge(['class' => 'rounded-xl border border-sky-200 bg-sky-50 px-4 py-3 text-sm font-medium text-sky-700']) }}>
        {{ $status }}
    </div>
@endif

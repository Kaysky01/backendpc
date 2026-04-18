@props([
    'title' => null,
    'description' => null,
    'as' => 'section',
    'padding' => 'p-6',
])

<{{ $as }} {{ $attributes->class(['surface-card', $padding]) }}>
    @if ($title || $description || isset($header))
        <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
            <div>
                @if ($title)
                    <h3 class="text-lg font-semibold text-slate-900">{{ $title }}</h3>
                @endif

                @if ($description)
                    <p class="mt-1 text-sm text-slate-500">{{ $description }}</p>
                @endif
            </div>

            @isset($header)
                <div class="shrink-0">
                    {{ $header }}
                </div>
            @endisset
        </div>
    @endif

    {{ $slot }}

    @isset($footer)
        <div class="mt-6 border-t border-gray-200 pt-4">
            {{ $footer }}
        </div>
    @endisset
</{{ $as }}>

@props([
    'name',
    'label'    => null,
    'hint'     => null,
    'error'    => null,
    'options'  => [],
    'selected' => null,
    'placeholder' => 'Selecciona una opción',
])

@php
    $selectClasses = implode(' ', [
        'w-full px-3 py-2 text-sm font-sans appearance-none',
        'bg-bg-surface border rounded-md',
        'text-content-primary',
        'outline-none transition-all duration-150',
        'hover:border-border-mid',
        'focus:border-accent-border focus:ring-2 focus:ring-accent-muted',
        $error
            ? 'border-[rgba(239,68,68,0.5)] focus:border-state-danger focus:ring-[rgba(239,68,68,0.10)]'
            : 'border-border-subtle',
    ]);
@endphp

<div {{ $attributes->merge(['class' => 'flex flex-col gap-1.5']) }}>

    @if ($label)
        <label for="{{ $name }}" class="text-xs font-medium text-content-secondary">
            {{ $label }}
        </label>
    @endif

    <div class="relative">
        <select
            id="{{ $name }}"
            name="{{ $name }}"
            class="{{ $selectClasses }}"
        >
            @if ($placeholder)
                <option value="" disabled {{ !$selected ? 'selected' : '' }}>
                    {{ $placeholder }}
                </option>
            @endif

            @foreach ($options as $value => $label)
                <option
                    value="{{ $value }}"
                    {{ old($name, $selected) == $value ? 'selected' : '' }}
                >
                    {{ $label }}
                </option>
            @endforeach
        </select>

        {{-- Chevron icon --}}
        <div class="pointer-events-none absolute inset-y-0 right-3 flex items-center">
            <svg class="w-3.5 h-3.5 text-content-secondary" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M6 9l6 6 6-6"/>
            </svg>
        </div>
    </div>

    @if ($error)
        <span class="text-xs text-state-danger">{{ $error }}</span>
    @elseif ($hint)
        <span class="text-xs text-content-disabled">{{ $hint }}</span>
    @endif

</div>

@props([
    'name',
    'label'       => null,
    'hint'        => null,
    'error'       => null,
    'type'        => 'text',
    'placeholder' => '',
    'value'       => null,
])

@php
    $inputClasses = implode(' ', [
        'w-full px-3 py-2 text-sm font-sans',
        'bg-bg-surface border rounded-md',
        'text-content-primary placeholder:text-content-disabled',
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

    <input
        id="{{ $name }}"
        name="{{ $name }}"
        type="{{ $type }}"
        placeholder="{{ $placeholder }}"
        value="{{ old($name, $value) }}"
        class="{{ $inputClasses }}"
    />

    @if ($error)
        <span class="text-xs text-state-danger">{{ $error }}</span>
    @elseif ($hint)
        <span class="text-xs text-content-disabled">{{ $hint }}</span>
    @endif

</div>
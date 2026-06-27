@props([
    'label' => null,
    'type' => 'text',
    'name',
    'id' => null,
    'value' => null,
    'placeholder' => null,
    'helperText' => null,
    'required' => false,
    'autofocus' => false,
    'autocomplete' => null,
    'disabled' => false
])

@php
    $inputId = $id ?? $name;
    $hasError = $errors->has($name);
@endphp

<div class="flex flex-col gap-1 w-full font-sans">
    {{-- Label --}}
    @if($label)
        <label
            for="{{ $inputId }}"
            class="text-xs font-semibold text-brandText-muted tracking-wide"
        >
            {{ $label }}
        </label>
    @endif

    {{-- Input Element --}}
    <div class="relative">
        <input
            type="{{ $type }}"
            id="{{ $inputId }}"
            name="{{ $name }}"
            value="{{ old($name, $value) }}"
            placeholder="{{ $placeholder }}"
            {{ $required ? 'required' : '' }}
            {{ $autofocus ? 'autofocus' : '' }}
            {{ $autocomplete ? 'autocomplete=' . $autocomplete : '' }}
            {{ $disabled ? 'disabled' : '' }}
            class="w-full px-3.5 py-2 text-sm bg-brandSurface border rounded-lg transition-all duration-200 focus:outline-none focus:bg-white focus:ring-2 focus:ring-primary-muted/40 focus:border-primary disabled:bg-brandSurface-low disabled:text-brandText-muted/50 {{
                $hasError
                    ? 'border-danger focus:border-danger focus:ring-danger/20'
                    : 'border-brandOutline-soft focus:border-primary'
            }} {{ $attributes->get('class') }}"
            {{ $attributes->except('class') }}
        />
    </div>

    {{-- Error Message --}}
    @error($name)
        <span class="text-xs font-medium text-danger transition-all duration-200">
            {{ $message }}
        </span>
    @enderror

    {{-- Helper Text --}}
    @if(!$hasError && $helperText)
        <span class="text-xs text-brandText-muted/70">
            {{ $helperText }}
        </span>
    @endif
</div>

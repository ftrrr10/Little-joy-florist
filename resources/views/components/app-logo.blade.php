@props([
    'className' => 'h-9 w-auto',
    'showText' => true,
    'variant' => 'dark'
])

@php
    $leafColor = '#064E3B';
    $flowerColor = '#8A486F';
    $textColor = 'text-primary';
    $subtextColor = 'text-brandText-muted';

    if ($variant === 'light') {
        $leafColor = '#B0F0D6';
        $flowerColor = '#FFFFFF';
        $textColor = 'text-white';
        $subtextColor = 'text-primary-soft';
    } elseif ($variant === 'gold') {
        $leafColor = '#FFE088';
        $flowerColor = '#FFE088';
        $textColor = 'text-tertiary-soft';
        $subtextColor = 'text-tertiary-soft/80';
    }
@endphp

<div class="flex items-center gap-3 select-none">
    {{-- Elegant Botanical Emblem --}}
    <svg
        class="{{ $className }}"
        viewBox="0 0 32 32"
        fill="none"
        xmlns="http://www.w3.org/2000/svg"
    >
        {{-- Outer leaf curve --}}
        <path
            d="M16 2C23.732 2 30 8.268 30 16C30 23.732 23.732 30 16 30C12.134 30 8.634 28.433 6.072 25.871C6.024 25.823 5.977 25.774 5.931 25.724C3.513 23.109 2 19.61 2 15.75C2 8.156 8.268 2 16 2Z"
            stroke="{{ $leafColor }}"
            stroke-width="1.5"
            stroke-linecap="round"
        />
        {{-- Internal flower bud curve --}}
        <path
            d="M16 8C11.582 8 8 11.582 8 16C8 19.314 10.015 22.157 13 23.364V16C13 14.343 14.343 13 16 13C17.657 13 19 14.343 19 16V23.364C21.985 22.157 24 19.314 24 16C24 11.582 20.418 8 16 8Z"
            fill="{{ $flowerColor }}"
            fill-opacity="0.85"
        />
        {{-- Center pistil --}}
        <circle cx="16" cy="16" r="2.5" fill="#FFE088" />
        {{-- Secondary abstract leaf line --}}
        <path
            d="M16 2V8"
            stroke="{{ $leafColor }}"
            stroke-width="1.5"
            stroke-linecap="round"
        />
    </svg>

    {{-- Brand Typography --}}
    @if($showText)
        <div class="flex flex-col leading-none">
            <span class="font-serif text-lg font-bold tracking-wide {{ $textColor }}">
                Little Joy
            </span>
            <span class="font-sans text-[9px] font-semibold tracking-[0.2em] uppercase mt-0.5 {{ $subtextColor }}">
                Jakarta
            </span>
        </div>
    @endif
</div>

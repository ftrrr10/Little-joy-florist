<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>@yield('title', config('app.name', 'Little Joy Florist'))</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Libre+Caslon+Text:ital,wght@0,400;0,700;1,400&family=Plus+Jakarta+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,400;1,600&display=swap" rel="stylesheet">

        <!-- Scripts & Styles -->
        @vite(['resources/js/app.js'])
        @yield('head')
    </head>
    <body class="font-sans antialiased bg-brandBackground text-brandText min-h-screen flex flex-col">
        <!-- Toast Notification system with AlpineJS -->
        @if (session('success') || session('error') || session('warning') || session('info') || $errors->any())
            <div x-data="{ 
                show: true, 
                message: '{{ session('success') ?: (session('error') ?: (session('warning') ?: (session('info') ?: ($errors->any() ? 'Ada kesalahan input. Silakan cek form.' : '')))) }}',
                variant: '{{ session('success') ? 'success' : (session('error') ? 'danger' : (session('warning') ? 'warning' : (session('info') ? 'info' : ($errors->any() ? 'danger' : 'success')))) }}'
            }" 
            x-show="show" 
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-y-2 sm:translate-y-0 sm:translate-x-2"
            x-transition:enter-end="opacity-100 translate-y-0 sm:translate-x-0"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            x-init="setTimeout(() => show = false, 5000)"
            class="fixed top-4 right-4 z-50 max-w-sm w-full bg-white shadow-xl rounded-2xl border pointer-events-auto overflow-hidden"
            :class="{
                'border-success/30': variant === 'success',
                'border-danger/30': variant === 'danger',
                'border-warning/30': variant === 'warning',
                'border-info/30': variant === 'info'
            }">
                <div class="p-4 flex items-start gap-3">
                    <div class="flex-shrink-0">
                        <template x-if="variant === 'success'">
                            <span class="text-success">
                                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </span>
                        </template>
                        <template x-if="variant === 'danger'">
                            <span class="text-danger">
                                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                </svg>
                            </span>
                        </template>
                        <template x-if="variant === 'warning'">
                            <span class="text-warning">
                                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                </svg>
                            </span>
                        </template>
                        <template x-if="variant === 'info'">
                            <span class="text-info">
                                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </span>
                        </template>
                    </div>
                    <div class="flex-grow pt-0.5">
                        <p class="text-xs font-bold uppercase tracking-wider"
                           :class="{
                               'text-success': variant === 'success',
                               'text-danger': variant === 'danger',
                               'text-warning': variant === 'warning',
                               'text-info': variant === 'info'
                           }">
                            <span x-text="variant === 'success' ? 'Sukses' : (variant === 'danger' ? 'Gagal' : (variant === 'warning' ? 'Perhatian' : 'Informasi'))"></span>
                        </p>
                        <p class="mt-1 text-sm text-brandText-muted leading-relaxed" x-text="message"></p>
                    </div>
                    <div class="flex-shrink-0 flex">
                        <button @click="show = false" class="inline-flex text-brandText-muted hover:text-brandText focus:outline-none transition-colors">
                            <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        @endif

        @yield('body')
    </body>
</html>

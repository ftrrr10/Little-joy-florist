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
        <!-- Global Top Loading Progress Bar -->
        <div id="global-loading-bar" class="fixed top-0 left-0 h-[3px] bg-gradient-to-r from-emerald-400 to-primary z-[9999] transition-all duration-300 w-0 opacity-0 pointer-events-none"></div>

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

        <!-- Custom Confirmation Modal -->
        <div 
            id="custom-confirm-modal" 
            class="fixed inset-0 z-[10000] flex items-center justify-center p-4 opacity-0 pointer-events-none transition-all duration-300"
        >
            <!-- Backdrop -->
            <div id="confirm-modal-backdrop" class="absolute inset-0 bg-[#022C22]/35 backdrop-blur-sm transition-opacity duration-300 opacity-0"></div>
            
            <!-- Modal Card -->
            <div 
                id="confirm-modal-card" 
                class="relative bg-white rounded-3xl p-6 shadow-2xl border border-brandOutline-soft/25 max-w-sm w-full transform scale-95 transition-all duration-300 opacity-0 font-sans"
            >
                <div class="text-center">
                    <!-- Icon -->
                    <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-[#E6F4EA] text-primary-dark mb-4">
                        <svg class="h-6 w-6 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                    </div>
                    
                    <h3 class="font-serif text-base font-bold text-primary mb-2">Konfirmasi Tindakan</h3>
                    <p id="confirm-modal-message" class="text-xs text-brandText-muted leading-relaxed mb-6">Apakah Anda yakin ingin menyimpan perubahan ini?</p>
                    
                    <!-- Actions -->
                    <div class="flex items-center justify-center gap-3">
                        <button 
                            type="button" 
                            id="confirm-modal-cancel"
                            class="flex-1 px-4 py-2.5 border border-brandOutline hover:bg-gray-50 text-brandText-dark text-xs font-bold rounded-xl transition-all"
                        >
                            Batal
                        </button>
                        <button 
                            type="button" 
                            id="confirm-modal-confirm"
                            class="flex-1 px-4 py-2.5 bg-primary hover:bg-primary-dark text-white text-xs font-bold rounded-xl transition-all shadow-sm"
                        >
                            Ya, Lanjutkan
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Global Page Transition and Form Submitting Loading Script -->
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const loadingBar = document.getElementById('global-loading-bar');
                let loadingInterval;

                // Confirm Modal Elements
                const confirmModal = document.getElementById('custom-confirm-modal');
                const confirmBackdrop = document.getElementById('confirm-modal-backdrop');
                const confirmCard = document.getElementById('confirm-modal-card');
                const confirmMessage = document.getElementById('confirm-modal-message');
                const confirmCancelBtn = document.getElementById('confirm-modal-cancel');
                const confirmConfirmBtn = document.getElementById('confirm-modal-confirm');
                let onConfirmCallback = null;

                function showConfirmModal(message, callback) {
                    confirmMessage.textContent = message;
                    onConfirmCallback = callback;

                    confirmModal.classList.remove('pointer-events-none');
                    confirmModal.classList.add('opacity-100');
                    
                    setTimeout(() => {
                        confirmBackdrop.classList.add('opacity-100');
                        confirmCard.classList.remove('scale-95', 'opacity-0');
                        confirmCard.classList.add('scale-100', 'opacity-100');
                    }, 10);
                }

                function hideConfirmModal() {
                    confirmBackdrop.classList.remove('opacity-100');
                    confirmCard.classList.remove('scale-100', 'opacity-100');
                    confirmCard.classList.add('scale-95', 'opacity-0');
                    
                    setTimeout(() => {
                        confirmModal.classList.add('pointer-events-none');
                        confirmModal.classList.remove('opacity-100');
                    }, 300);
                }

                confirmCancelBtn.addEventListener('click', hideConfirmModal);
                confirmBackdrop.addEventListener('click', hideConfirmModal);
                
                confirmConfirmBtn.addEventListener('click', function () {
                    hideConfirmModal();
                    if (onConfirmCallback) {
                        onConfirmCallback();
                    }
                });

                function startLoading() {
                    clearInterval(loadingInterval);
                    loadingBar.style.width = '0%';
                    loadingBar.style.opacity = '1';
                    
                    let width = 0;
                    loadingInterval = setInterval(() => {
                        if (width >= 90) {
                            clearInterval(loadingInterval);
                        } else {
                            width += (90 - width) * 0.15;
                            loadingBar.style.width = width + '%';
                        }
                    }, 100);
                }

                function stopLoading() {
                    clearInterval(loadingInterval);
                    loadingBar.style.width = '100%';
                    setTimeout(() => {
                        loadingBar.style.opacity = '0';
                        setTimeout(() => {
                            loadingBar.style.width = '0%';
                        }, 300);
                    }, 200);
                }

                // 1. Page Transition: Trigger loading bar on link clicks
                document.addEventListener('click', function (e) {
                    const link = e.target.closest('a');
                    if (!link) return;

                    const href = link.getAttribute('href');
                    const target = link.getAttribute('target');
                    
                    if (!href || 
                        href.startsWith('#') || 
                        href.startsWith('javascript:') || 
                        target === '_blank' || 
                        link.hasAttribute('download') ||
                        e.ctrlKey || e.metaKey || e.shiftKey
                    ) {
                        return;
                    }

                    try {
                        const url = new URL(href, window.location.href);
                        if (url.origin === window.location.origin && url.pathname !== window.location.pathname) {
                            startLoading();
                        }
                    } catch (err) {
                        startLoading();
                    }
                });

                // 2. Global Form Submission: Confirmation & Loading States
                document.addEventListener('submit', function (e) {
                    const form = e.target;
                    
                    // If the submit was already prevented, do nothing
                    if (e.defaultPrevented) return;

                    // If form has data-confirm, prompt the user using our custom modal
                    if (form.hasAttribute('data-confirm') && form.dataset.confirmed !== 'true') {
                        e.preventDefault();
                        
                        // Perform HTML5 form validation before showing modal
                        if (form.checkValidity && !form.checkValidity()) {
                            form.reportValidity();
                            return;
                        }

                        const message = form.getAttribute('data-confirm');
                        showConfirmModal(message, function () {
                            form.dataset.confirmed = 'true';
                            // Re-submit the form
                            form.requestSubmit ? form.requestSubmit() : form.submit();
                        });
                        return;
                    }

                    // Trigger loading bar
                    startLoading();

                    // Find and disable submit button, showing loading state
                    const submitBtn = form.querySelector('button[type="submit"], input[type="submit"]');
                    if (submitBtn) {
                        // Delay slightly to allow browser form validation to run first
                        setTimeout(() => {
                            // If HTML5 validation fails, the form won't submit, but we check if it is still submitting
                            if (form.checkValidity && !form.checkValidity()) {
                                stopLoading();
                                return;
                            }
                            
                            submitBtn.disabled = true;
                            submitBtn.dataset.originalHtml = submitBtn.innerHTML;
                            
                            // Check if button is small or large, adjust spinner
                            submitBtn.innerHTML = `
                                <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white inline-block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                <span>Memproses...</span>
                            `;
                            submitBtn.classList.add('opacity-75', 'cursor-not-allowed');
                        }, 10);
                    }
                });

                // Stop loading bar on page show (handles browser back/forward cache)
                window.addEventListener('pageshow', function () {
                    stopLoading();
                });
            });
        </script>
    </body>
</html>

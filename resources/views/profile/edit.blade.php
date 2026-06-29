@php
    $layout = auth()->user()->role === 'customer' ? 'layouts.public' : 'layouts.dashboard';
    $isCustomer = auth()->user()->role === 'customer';
@endphp

@extends($layout)

@section('title', 'Pengaturan Profil')

@section('content')
<div class="{{ $isCustomer ? 'max-w-4xl mx-auto px-4 py-12' : 'space-y-6 w-full max-w-6xl' }} font-sans">
    
    {{-- Header (Hanya untuk konsumen, karena Dashboard sudah menampilkan judul di Top Bar) --}}
    @if($isCustomer)
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
            <div>
                <span class="inline-block px-2.5 py-0.5 text-[9px] font-bold tracking-wider uppercase bg-primary-soft text-primary-dark rounded-full mb-2">
                    Akun Pengguna
                </span>
                <h2 class="font-serif text-2xl font-bold text-primary">Pengaturan Profil</h2>
                <p class="text-xs text-brandText-muted mt-1">Perbarui informasi dasar profil dan pengaturan kata sandi Anda.</p>
            </div>
        </div>
    @endif

    {{-- Status Messages --}}
    @if (session('status') === 'profile-updated' || session('success'))
        <div class="p-4 mb-6 text-sm text-green-800 rounded-2xl bg-green-50 border border-green-200 flex items-center gap-2">
            <svg class="w-4 h-4 text-green-600 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span>{{ session('success') ?? 'Informasi profil berhasil diperbarui.' }}</span>
        </div>
    @endif

    @if (session('status') === 'password-updated')
        <div class="p-4 mb-6 text-sm text-green-800 rounded-2xl bg-green-50 border border-green-200 flex items-center gap-2">
            <svg class="w-4 h-4 text-green-600 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span>Kata sandi berhasil diperbarui.</span>
        </div>
    @endif

    <div class="space-y-6">
        
        {{-- Profile Info Form --}}
        <div class="bg-white border border-brandOutline-soft/30 p-6 md:p-8 rounded-3xl shadow-sm">
            <div class="flex items-center gap-3 mb-6 pb-3 border-b border-brandOutline-soft/30">
                <div class="h-8 w-8 rounded-full bg-primary-soft text-primary-dark font-bold text-sm flex items-center justify-center">
                    ID
                </div>
                <div>
                    <h3 class="font-serif text-base font-bold text-primary">Informasi Profil</h3>
                    <p class="text-[10px] text-brandText-muted">Ubah nama, email, nomor kontak, dan alamat utama Anda.</p>
                </div>
            </div>
            
            <form method="post" action="{{ route('profile.update') }}" class="space-y-6" data-confirm="Apakah Anda yakin ingin menyimpan perubahan profil Anda?">
                @csrf
                @method('patch')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- Name --}}
                    <div>
                        <label for="name" class="block text-xs font-bold text-brandText-muted uppercase mb-1.5">Nama Lengkap</label>
                        <input 
                            type="text" 
                            name="name" 
                            id="name" 
                            value="{{ old('name', auth()->user()->name) }}" 
                            required 
                            class="w-full text-xs px-4 py-3 rounded-xl border border-brandOutline-soft/50 focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all @error('name') border-red-500 @enderror font-semibold text-brandText-dark"
                        >
                        @error('name')
                            <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Email --}}
                    <div>
                        <label for="email" class="block text-xs font-bold text-brandText-muted uppercase mb-1.5">Alamat Email</label>
                        <input 
                            type="email" 
                            name="email" 
                            id="email" 
                            value="{{ old('email', auth()->user()->email) }}" 
                            required 
                            class="w-full text-xs px-4 py-3 rounded-xl border border-brandOutline-soft/50 focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all @error('email') border-red-500 @enderror font-semibold text-brandText-dark"
                        >
                        @error('email')
                            <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Phone --}}
                    <div>
                        <label for="phone" class="block text-xs font-bold text-brandText-muted uppercase mb-1.5">Nomor Telepon / WhatsApp</label>
                        <input 
                            type="text" 
                            name="phone" 
                            id="phone" 
                            value="{{ old('phone', auth()->user()->phone) }}" 
                            class="w-full text-xs px-4 py-3 rounded-xl border border-brandOutline-soft/50 focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all @error('phone') border-red-500 @enderror font-semibold text-brandText-dark"
                            placeholder="Contoh: 081234567890"
                        >
                        @error('phone')
                            <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Empty slot or other field --}}
                    <div class="hidden md:block"></div>

                    {{-- Address --}}
                    <div class="md:col-span-2">
                        <label for="address" class="block text-xs font-bold text-brandText-muted uppercase mb-1.5">Alamat Utama</label>
                        <textarea 
                            name="address" 
                            id="address" 
                            rows="3"
                            class="w-full text-xs px-4 py-3 rounded-xl border border-brandOutline-soft/50 focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all @error('address') border-red-500 @enderror font-semibold text-brandText-dark"
                            placeholder="Alamat lengkap pengiriman bunga..."
                        >{{ old('address', auth()->user()->address) }}</textarea>
                        @error('address')
                            <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="flex justify-end pt-2">
                    <button 
                        type="submit" 
                        class="w-full sm:w-auto inline-flex items-center justify-center px-6 py-3 bg-primary hover:bg-primary-dark text-white text-xs font-bold rounded-xl transition-all shadow-sm"
                    >
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>

        {{-- Password Form --}}
        <div class="bg-white border border-brandOutline-soft/30 p-6 md:p-8 rounded-3xl shadow-sm">
            <div class="flex items-center gap-3 mb-6 pb-3 border-b border-brandOutline-soft/30">
                <div class="h-8 w-8 rounded-full bg-primary-soft text-primary-dark font-bold text-sm flex items-center justify-center">
                    🔑
                </div>
                <div>
                    <h3 class="font-serif text-base font-bold text-primary">Perbarui Kata Sandi</h3>
                    <p class="text-[10px] text-brandText-muted">Amankan akun Anda dengan mengganti kata sandi secara berkala.</p>
                </div>
            </div>
            
            <form method="post" action="{{ route('password.update') }}" class="space-y-6" data-confirm="Apakah Anda yakin ingin memperbarui kata sandi Anda?">
                @csrf
                @method('put')

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    {{-- Current Password --}}
                    <div>
                        <label for="update_password_current_password" class="block text-xs font-bold text-brandText-muted uppercase mb-1.5">Kata Sandi Saat Ini</label>
                        <input 
                            type="password" 
                            name="current_password" 
                            id="update_password_current_password" 
                            class="w-full text-xs px-4 py-3 rounded-xl border border-brandOutline-soft/50 focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all @error('current_password', 'updatePassword') border-red-500 @enderror font-semibold text-brandText-dark"
                        >
                        @error('current_password', 'updatePassword')
                            <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- New Password --}}
                    <div>
                        <label for="update_password_password" class="block text-xs font-bold text-brandText-muted uppercase mb-1.5">Kata Sandi Baru</label>
                        <input 
                            type="password" 
                            name="password" 
                            id="update_password_password" 
                            class="w-full text-xs px-4 py-3 rounded-xl border border-brandOutline-soft/50 focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all @error('password', 'updatePassword') border-red-500 @enderror font-semibold text-brandText-dark"
                        >
                        @error('password', 'updatePassword')
                            <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Confirm Password --}}
                    <div>
                        <label for="update_password_password_confirmation" class="block text-xs font-bold text-brandText-muted uppercase mb-1.5">Konfirmasi Kata Sandi</label>
                        <input 
                            type="password" 
                            name="password_confirmation" 
                            id="update_password_password_confirmation" 
                            class="w-full text-xs px-4 py-3 rounded-xl border border-brandOutline-soft/50 focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all @error('password_confirmation', 'updatePassword') border-red-500 @enderror font-semibold text-brandText-dark"
                        >
                        @error('password_confirmation', 'updatePassword')
                            <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="flex justify-end pt-2">
                    <button 
                        type="submit" 
                        class="w-full sm:w-auto inline-flex items-center justify-center px-6 py-3 bg-primary hover:bg-primary-dark text-white text-xs font-bold rounded-xl transition-all shadow-sm"
                    >
                        Perbarui Sandi
                    </button>
                </div>
            </form>
        </div>

    </div>
</div>
@endsection

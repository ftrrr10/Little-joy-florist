@php
    $layout = auth()->user()->role === 'customer' ? 'layouts.public' : 'layouts.dashboard';
@endphp

@extends($layout)

@section('title', 'Pengaturan Profil - Little Joy Jakarta')

@section('content')
<div class="{{ auth()->user()->role === 'customer' ? 'max-w-4xl mx-auto px-4 py-12' : 'space-y-6 max-w-4xl' }}">
    
    {{-- Header --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
        <div>
            <h2 class="font-serif text-2xl font-bold text-primary">Pengaturan Profil</h2>
            <p class="text-xs text-brandText-muted mt-1">Perbarui informasi profil dan kata sandi akun Anda di sini.</p>
        </div>
    </div>

    @if (session('status') === 'profile-updated' || session('success'))
        <div class="p-4 mb-6 text-sm text-green-800 rounded-2xl bg-green-50 border border-green-200 font-sans flex items-center gap-2">
            <svg class="w-4 h-4 text-green-600 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span>{{ session('success') ?? 'Informasi profil berhasil diperbarui.' }}</span>
        </div>
    @endif

    @if (session('status') === 'password-updated')
        <div class="p-4 mb-6 text-sm text-green-800 rounded-2xl bg-green-50 border border-green-200 font-sans flex items-center gap-2">
            <svg class="w-4 h-4 text-green-600 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span>Kata sandi berhasil diperbarui.</span>
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        
        {{-- Left Navigation/Info Info --}}
        <div class="md:col-span-1 space-y-6">
            <div class="bg-white border border-brandOutline-soft/30 p-6 rounded-2xl shadow-sm text-center">
                <div class="h-20 w-20 mx-auto rounded-full bg-primary-soft text-primary-dark font-bold text-2xl flex items-center justify-center ring-4 ring-primary-soft/50 mb-4">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>
                <h3 class="font-serif text-lg font-bold text-brandText">{{ auth()->user()->name }}</h3>
                <p class="text-xs text-brandText-muted mt-1 uppercase font-bold tracking-wider">
                    @if(auth()->user()->role === 'admin')
                        Store Manager
                    @elseif(auth()->user()->role === 'operator')
                        Florist Operator
                    @else
                        Pelanggan
                    @endif
                </p>
                <p class="text-[11px] text-brandText-muted/80 mt-1">{{ auth()->user()->email }}</p>
            </div>
            
            <div class="bg-[#064E3B] text-white p-6 rounded-2xl shadow-sm relative overflow-hidden">
                <div class="absolute -right-8 -bottom-8 text-white/5 pointer-events-none">
                    <svg class="w-32 h-32" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-6h2v6zm0-8h-2V7h2v2z"/>
                    </svg>
                </div>
                <h4 class="font-serif text-sm font-bold text-[#FFE088] mb-2">Keamanan Akun</h4>
                <p class="text-[11px] text-white/80 leading-relaxed">
                    Gunakan kata sandi yang kuat berupa kombinasi huruf besar, kecil, angka, dan simbol untuk menjaga keamanan akun Anda.
                </p>
            </div>
        </div>

        {{-- Right Forms Column --}}
        <div class="md:col-span-2 space-y-8">
            
            {{-- Profile Info Form --}}
            <div class="bg-white border border-brandOutline-soft/30 p-6 md:p-8 rounded-2xl shadow-sm">
                <h3 class="font-serif text-base font-bold text-primary mb-4 pb-2 border-b border-brandOutline-soft/30">
                    Informasi Profil
                </h3>
                
                <form method="post" action="{{ route('profile.update') }}" class="space-y-4 font-sans">
                    @csrf
                    @method('patch')

                    <div>
                        <label for="name" class="block text-xs font-bold text-brandText-muted uppercase mb-1">Nama Lengkap</label>
                        <input 
                            type="text" 
                            name="name" 
                            id="name" 
                            value="{{ old('name', auth()->user()->name) }}" 
                            required 
                            class="w-full text-sm px-4 py-2.5 rounded-xl border border-brandOutline-soft/50 focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all @error('name') border-red-500 @enderror"
                        >
                        @error('name')
                            <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="email" class="block text-xs font-bold text-brandText-muted uppercase mb-1">Alamat Email</label>
                        <input 
                            type="email" 
                            name="email" 
                            id="email" 
                            value="{{ old('email', auth()->user()->email) }}" 
                            required 
                            class="w-full text-sm px-4 py-2.5 rounded-xl border border-brandOutline-soft/50 focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all @error('email') border-red-500 @enderror"
                        >
                        @error('email')
                            <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="phone" class="block text-xs font-bold text-brandText-muted uppercase mb-1">Nomor Telepon</label>
                        <input 
                            type="text" 
                            name="phone" 
                            id="phone" 
                            value="{{ old('phone', auth()->user()->phone) }}" 
                            class="w-full text-sm px-4 py-2.5 rounded-xl border border-brandOutline-soft/50 focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all @error('phone') border-red-500 @enderror"
                            placeholder="Contoh: 08123456789"
                        >
                        @error('phone')
                            <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="address" class="block text-xs font-bold text-brandText-muted uppercase mb-1">Alamat Utama</label>
                        <textarea 
                            name="address" 
                            id="address" 
                            rows="3"
                            class="w-full text-sm px-4 py-2.5 rounded-xl border border-brandOutline-soft/50 focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all @error('address') border-red-500 @enderror"
                            placeholder="Alamat lengkap pengiriman bunga..."
                        >{{ old('address', auth()->user()->address) }}</textarea>
                        @error('address')
                            <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="pt-2">
                        <button 
                            type="submit" 
                            class="inline-flex items-center justify-center px-6 py-2.5 bg-primary hover:bg-primary-dark text-white text-xs font-bold rounded-xl transition-all shadow-sm"
                        >
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>

            {{-- Password Form --}}
            <div class="bg-white border border-brandOutline-soft/30 p-6 md:p-8 rounded-2xl shadow-sm">
                <h3 class="font-serif text-base font-bold text-primary mb-4 pb-2 border-b border-brandOutline-soft/30">
                    Perbarui Kata Sandi
                </h3>
                
                <form method="post" action="{{ route('password.update') }}" class="space-y-4 font-sans">
                    @csrf
                    @method('put')

                    <div>
                        <label for="update_password_current_password" class="block text-xs font-bold text-brandText-muted uppercase mb-1">Kata Sandi Saat Ini</label>
                        <input 
                            type="password" 
                            name="current_password" 
                            id="update_password_current_password" 
                            class="w-full text-sm px-4 py-2.5 rounded-xl border border-brandOutline-soft/50 focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all @error('current_password', 'updatePassword') border-red-500 @enderror"
                        >
                        @error('current_password', 'updatePassword')
                            <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="update_password_password" class="block text-xs font-bold text-brandText-muted uppercase mb-1">Kata Sandi Baru</label>
                        <input 
                            type="password" 
                            name="password" 
                            id="update_password_password" 
                            class="w-full text-sm px-4 py-2.5 rounded-xl border border-brandOutline-soft/50 focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all @error('password', 'updatePassword') border-red-500 @enderror"
                        >
                        @error('password', 'updatePassword')
                            <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="update_password_password_confirmation" class="block text-xs font-bold text-brandText-muted uppercase mb-1">Konfirmasi Kata Sandi Baru</label>
                        <input 
                            type="password" 
                            name="password_confirmation" 
                            id="update_password_password_confirmation" 
                            class="w-full text-sm px-4 py-2.5 rounded-xl border border-brandOutline-soft/50 focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all @error('password_confirmation', 'updatePassword') border-red-500 @enderror"
                        >
                        @error('password_confirmation', 'updatePassword')
                            <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="pt-2">
                        <button 
                            type="submit" 
                            class="inline-flex items-center justify-center px-6 py-2.5 bg-primary hover:bg-primary-dark text-white text-xs font-bold rounded-xl transition-all shadow-sm"
                        >
                            Perbarui Sandi
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>
@endsection

@extends('layouts.dashboard')

@php
    $isEditMode = isset($category) && $category->exists;
@endphp

@section('title', $isEditMode ? 'Ubah Kategori Bunga' : 'Tambah Kategori Baru')

@section('content')
<div class="w-full font-sans" x-data="{
    name: '{{ old('name', $category->name ?? '') }}',
    slug: '{{ old('slug', $category->slug ?? '') }}',
    autoSlug: {{ $isEditMode ? 'false' : 'true' }},
    updateSlug() {
        if (this.autoSlug) {
            this.slug = this.name.toLowerCase()
                .replace(/[^a-z0-9]+/g, '-')
                .replace(/(^-|-$)/g, '');
        }
    }
}">
    {{-- Back link --}}
    <div class="mb-6 reveal">
        <a
            href="{{ route('admin.categories.index') }}"
            class="inline-flex items-center gap-1.5 text-xs font-bold text-primary hover:underline"
        >
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7" />
            </svg>
            Kembali ke Daftar Kategori
        </a>
    </div>

    {{-- Form Card --}}
    <div class="bg-white border border-brandOutline-soft/25 rounded-2xl p-8 shadow-sm reveal" data-delay="100">
        <form action="{{ $isEditMode ? route('admin.categories.update', $category->id) : route('admin.categories.store') }}" method="POST" class="space-y-6" data-confirm="{{ $isEditMode ? 'Apakah Anda yakin ingin menyimpan perubahan kategori ini?' : 'Apakah Anda yakin ingin menambahkan kategori baru ini?' }}">
            @csrf
            @if($isEditMode)
                @method('PUT')
            @endif

            {{-- Name Input --}}
            <div>
                <label for="name" class="block text-xs font-bold text-brandText-muted uppercase tracking-wider mb-2">
                    Nama Kategori <span class="text-red-500">*</span>
                </label>
                <input
                    type="text"
                    id="name"
                    name="name"
                    x-model="name"
                    @input="updateSlug()"
                    placeholder="Contoh: Bloom Box, Hand Bouquet, dll."
                    class="w-full border border-brandOutline rounded-xl px-4 py-2.5 text-sm bg-cream-light/10 text-brandText focus:outline-none focus:ring-2 focus:ring-primary-muted/40 focus:border-primary transition-all @error('name') border-red-300 focus:ring-red-500 @enderror"
                    required
                />
                @error('name')
                    <p class="text-xs text-red-500 mt-1.5 font-medium">{{ $message }}</p>
                @enderror
            </div>

            {{-- Slug Input --}}
            <div>
                <label for="slug" class="block text-xs font-bold text-brandText-muted uppercase tracking-wider mb-2">
                    Slug URL (Otomatis) <span class="text-red-500">*</span>
                </label>
                <input
                    type="text"
                    id="slug"
                    name="slug"
                    x-model="slug"
                    @input="autoSlug = false"
                    placeholder="Contoh: bloom-box"
                    class="w-full border border-brandOutline rounded-xl px-4 py-2.5 text-sm bg-cream-light/10 text-brandText focus:outline-none focus:ring-2 focus:ring-primary-muted/40 focus:border-primary transition-all @error('slug') border-red-300 focus:ring-red-500 @enderror"
                    required
                />
                <p class="text-[10px] text-brandText-muted mt-1.5 leading-relaxed">
                    Digunakan sebagai penunjuk URL unik di website (contoh: /katalog?category=slug).
                </p>
                @error('slug')
                    <p class="text-xs text-red-500 mt-1.5 font-medium">{{ $message }}</p>
                @enderror
            </div>

            {{-- Description Input --}}
            <div class="flex flex-col gap-1 w-full">
                <label htmlFor="description" class="text-xs font-bold text-brandText-muted uppercase tracking-wider mb-2">
                    Deskripsi Kategori
                </label>
                <textarea
                    id="description"
                    name="description"
                    rows="4"
                    placeholder="Tulis deskripsi singkat kategori ini..."
                    class="w-full border border-brandOutline rounded-xl px-4 py-2.5 text-sm bg-cream-light/10 text-brandText focus:outline-none focus:ring-2 focus:ring-primary-muted/40 focus:border-primary transition-all @error('description') border-red-300 focus:ring-red-500 @enderror"
                >{{ old('description', $category->description ?? '') }}</textarea>
                @error('description')
                    <p class="text-xs text-red-500 mt-1.5 font-medium">{{ $message }}</p>
                @enderror
            </div>

            {{-- Active status Toggle --}}
            <div class="flex items-center pt-2">
                <label class="inline-flex items-center cursor-pointer">
                    <input
                        type="checkbox"
                        name="is_active"
                        value="1"
                        {{ old('is_active', $category->is_active ?? true) ? 'checked' : '' }}
                        class="rounded border-brandOutline-soft text-primary shadow-sm focus:ring-primary-soft/40 focus:ring-2 focus:border-primary h-4 w-4"
                    />
                    <div class="ms-2.5">
                        <span class="block text-xs font-bold text-brandText">Aktifkan Kategori</span>
                        <span class="block text-[10px] text-brandText-muted mt-0.5">
                            Kategori yang aktif akan ditampilkan di halaman filter katalog publik.
                        </span>
                    </div>
                </label>
            </div>

            {{-- Action Buttons --}}
            <div class="pt-4 border-t border-brandSurface-low flex items-center justify-end gap-3 select-none">
                <a 
                    href="{{ route('admin.categories.index') }}"
                    class="inline-flex items-center justify-center font-sans font-semibold rounded-lg transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 active:scale-[0.98] border border-brandOutline bg-transparent text-brandText hover:bg-brandSurface-low focus:ring-brandSurface-high py-2 px-4 text-sm"
                >
                    Batal
                </a>
                <button
                    type="submit"
                    class="inline-flex items-center justify-center font-sans font-semibold rounded-lg transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 active:scale-[0.98] bg-primary text-white hover:bg-primary-dark focus:ring-primary-muted shadow-sm py-2 px-4 text-sm font-bold"
                >
                    {{ $isEditMode ? 'Simpan Perubahan' : 'Tambahkan Kategori' }}
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

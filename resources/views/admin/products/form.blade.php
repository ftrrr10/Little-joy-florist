@extends('layouts.dashboard')

@php
    $isEditMode = isset($product) && $product->exists;
@endphp

@section('title', $isEditMode ? 'Ubah Produk Rangkaian Bunga' : 'Tambah Produk Baru')

@section('content')
<div class="w-full font-sans" x-data="{
    name: '{{ old('name', $product->name ?? '') }}',
    slug: '{{ old('slug', $product->slug ?? '') }}',
    autoSlug: {{ $isEditMode ? 'false' : 'true' }},
    imagePreview: null,
    updateSlug() {
        if (this.autoSlug) {
            this.slug = this.name.toLowerCase()
                .replace(/[^a-z0-9]+/g, '-')
                .replace(/(^-|-$)/g, '');
        }
    },
    previewImage(event) {
        const file = event.target.files[0];
        if (file) {
            this.imagePreview = URL.createObjectURL(file);
        } else {
            this.imagePreview = null;
        }
    }
}">
    {{-- Back link --}}
    <div class="mb-6 reveal">
        <a
            href="{{ route('admin.products.index') }}"
            class="inline-flex items-center gap-1.5 text-xs font-bold text-primary hover:underline"
        >
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7" />
            </svg>
            Kembali ke Daftar Produk
        </a>
    </div>

    {{-- Form Wrapper --}}
    <div class="bg-white border border-brandOutline-soft/25 rounded-2xl p-8 shadow-sm reveal" data-delay="100">
        <form action="{{ $isEditMode ? route('admin.products.update', $product->id) : route('admin.products.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @if($isEditMode)
                @method('PUT')
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- Left Form Column --}}
                <div class="space-y-5">
                    {{-- Category Dropdown --}}
                    <div class="flex flex-col gap-1 w-full">
                        <label for="category_id" class="text-xs font-bold text-brandText-muted uppercase tracking-wider mb-2">
                            Pilih Kategori Bunga <span class="text-red-500">*</span>
                        </label>
                        <select
                            id="category_id"
                            name="category_id"
                            required
                            class="w-full px-3.5 py-2.5 text-sm bg-cream-light/10 border border-brandOutline rounded-xl transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-primary-muted/40 focus:border-primary cursor-pointer @error('category_id') border-red-300 focus:ring-red-500 @enderror"
                        >
                            <option value="">-- Pilih Kategori --</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" {{ old('category_id', $product->category_id ?? '') == $cat->id ? 'selected' : '' }}>
                                    {{ $cat->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('category_id')
                            <p class="text-xs text-red-500 mt-1.5 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Name Input --}}
                    <div>
                        <label for="name" class="block text-xs font-bold text-brandText-muted uppercase tracking-wider mb-2">
                            Nama Produk Rangkaian Bunga <span class="text-red-500">*</span>
                        </label>
                        <input
                            type="text"
                            id="name"
                            name="name"
                            x-model="name"
                            @input="updateSlug()"
                            placeholder="Contoh: Classic Red Roses Bouquet"
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
                            placeholder="Contoh: classic-red-roses-bouquet"
                            class="w-full border border-brandOutline rounded-xl px-4 py-2.5 text-sm bg-cream-light/10 text-brandText focus:outline-none focus:ring-2 focus:ring-primary-muted/40 focus:border-primary transition-all @error('slug') border-red-300 focus:ring-red-500 @enderror"
                            required
                        />
                        <p class="text-[10px] text-brandText-muted mt-1.5 leading-relaxed">
                            Digunakan untuk penunjuk URL unik produk di website.
                        </p>
                        @error('slug')
                            <p class="text-xs text-red-500 mt-1.5 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        {{-- Price Input --}}
                        <div>
                            <x-input
                                label="Harga Jual (Rupiah)"
                                id="price"
                                type="number"
                                name="price"
                                value="{{ old('price', $product->price ?? '') }}"
                                placeholder="Contoh: 350000"
                                min="0"
                                required
                            />
                        </div>

                        {{-- Stock Input --}}
                        <div>
                            <x-input
                                label="Stok Awal"
                                id="stock"
                                type="number"
                                name="stock"
                                value="{{ old('stock', $product->stock ?? '0') }}"
                                placeholder="Contoh: 15"
                                min="0"
                                required
                            />
                        </div>
                    </div>
                </div>

                {{-- Right Form Column: Image Upload & Preview --}}
                <div class="flex flex-col gap-4">
                    <label class="text-xs font-bold text-brandText-muted uppercase tracking-wider mb-2">
                        Foto Rangkaian Bunga
                    </label>

                    {{-- Interactive Preview Box --}}
                    <div class="border-2 border-dashed border-brandOutline-soft/75 rounded-2xl aspect-[4/3] bg-brandSurface-low/30 overflow-hidden flex flex-col items-center justify-center relative group select-none">
                        {{-- Local New Preview --}}
                        <template x-if="imagePreview">
                            <img
                                :src="imagePreview"
                                alt="Preview unggahan baru"
                                class="w-full h-full object-cover"
                            />
                        </template>

                        {{-- Database Existing Preview --}}
                        <template x-if="!imagePreview">
                            @if(isset($product) && $product->image_path)
                                <img
                                    src="/storage/{{ $product->image_path }}"
                                    alt="{{ $product->name }}"
                                    class="w-full h-full object-cover"
                                />
                            @else
                                <div class="text-center p-6 space-y-2">
                                    <svg class="h-10 w-10 text-primary/45 mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.25" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    <p class="text-xs font-bold text-primary">Belum ada foto terpilih</p>
                                    <p class="text-[10px] text-brandText-muted leading-relaxed">Mendukung format JPG, PNG, WebP (Maks. 2 MB)</p>
                                </div>
                            @endif
                        </template>

                        {{-- Upload Trigger overlay --}}
                        <div class="absolute inset-0 bg-black/45 opacity-0 group-hover:opacity-100 flex items-center justify-center transition-all duration-200 cursor-pointer">
                            <span class="px-4 py-2 bg-white text-primary font-bold text-xs rounded-lg shadow-md">
                                Pilih Foto Bunga
                            </span>
                        </div>
                        <input
                            type="file"
                            id="image"
                            name="image"
                            accept="image/*"
                            class="absolute inset-0 opacity-0 cursor-pointer"
                            @change="previewImage($event)"
                        />
                    </div>
                    @error('image')
                        <p class="text-xs text-red-500 mt-1.5 font-medium text-center">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Description Input --}}
            <div class="flex flex-col gap-1 w-full font-sans">
                <label htmlFor="description" class="text-xs font-bold text-brandText-muted uppercase tracking-wider mb-2">
                    Deskripsi Rangkaian Bunga
                </label>
                <textarea
                    id="description"
                    name="description"
                    rows="4"
                    placeholder="Jelaskan detail bunga segar yang digunakan, ukuran rangkaian, kecocokan acara, dan instruksi perawatan khusus..."
                    class="w-full border border-brandOutline rounded-xl px-4 py-2.5 text-sm bg-cream-light/10 text-brandText focus:outline-none focus:ring-2 focus:ring-primary-muted/40 focus:border-primary transition-all @error('description') border-red-300 focus:ring-red-500 @enderror"
                >{{ old('description', $product->description ?? '') }}</textarea>
                @error('description')
                    <p class="text-xs text-red-500 mt-1.5 font-medium">{{ $message }}</p>
                @enderror
            </div>

            {{-- Active Status --}}
            <div class="flex items-center pt-2 font-sans">
                <label class="inline-flex items-center cursor-pointer">
                    <input
                        type="checkbox"
                        name="is_active"
                        value="1"
                        {{ old('is_active', $product->is_active ?? true) ? 'checked' : '' }}
                        class="rounded border-brandOutline-soft text-primary shadow-sm focus:ring-primary-soft/40 focus:ring-2 focus:border-primary h-4 w-4"
                    />
                    <div class="ms-2.5">
                        <span class="block text-xs font-bold text-brandText">Tampilkan di Katalog Publik</span>
                        <span class="block text-[10px] text-brandText-muted mt-0.5">
                            Produk yang aktif akan ditampilkan di halaman katalog publik dan dapat dibeli oleh pelanggan.
                        </span>
                    </div>
                </label>
            </div>

            {{-- Action Buttons --}}
            <div class="pt-4 border-t border-brandSurface-low flex items-center justify-end gap-3 select-none font-sans">
                <a 
                    href="{{ route('admin.products.index') }}"
                    class="inline-flex items-center justify-center font-sans font-semibold rounded-lg transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 active:scale-[0.98] border border-brandOutline bg-transparent text-brandText hover:bg-brandSurface-low focus:ring-brandSurface-high py-2 px-4 text-sm"
                >
                    Batal
                </a>
                <button
                    type="submit"
                    class="inline-flex items-center justify-center font-sans font-semibold rounded-lg transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 active:scale-[0.98] bg-primary text-white hover:bg-primary-dark focus:ring-primary-muted shadow-sm py-2 px-4 text-sm font-bold"
                >
                    {{ $isEditMode ? 'Simpan Perubahan' : 'Tambahkan Produk' }}
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

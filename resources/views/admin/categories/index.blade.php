@extends('layouts.dashboard')

@section('title', 'Daftar Kategori Bunga')

@section('content')
<div class="space-y-6 font-sans">
    {{-- Upper Action Bar --}}
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 bg-white p-5 rounded-2xl border border-brandOutline-soft/25 shadow-sm reveal">
        <div>
            <h2 class="text-base font-bold text-brandText">Kelola Kategori Master</h2>
            <p class="text-xs text-brandText-muted mt-1">
                Tambahkan, ubah, atau hapus kategori penataan bunga untuk sistem katalog Anda.
            </p>
        </div>
        <a href="{{ route('admin.categories.create') }}" class="inline-flex items-center justify-center font-sans font-semibold rounded-lg transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 active:scale-[0.98] bg-primary text-white hover:bg-primary-dark focus:ring-primary-muted shadow-sm text-xs py-2 px-3 gap-1.5 font-bold">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" />
            </svg>
            Tambah Kategori Baru
        </a>
    </div>

    {{-- Categories Table Card --}}
    <div class="bg-white border border-brandOutline-soft/25 rounded-2xl shadow-sm overflow-hidden reveal" data-delay="100">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-brandSurface-high/60 text-left">
                <thead class="bg-brandSurface-low/40">
                    <tr>
                        <th scope="col" class="px-6 py-4 text-xs font-bold text-brandText-muted uppercase tracking-wider">
                            Nama Kategori
                        </th>
                        <th scope="col" class="px-6 py-4 text-xs font-bold text-brandText-muted uppercase tracking-wider">
                            Slug URL
                        </th>
                        <th scope="col" class="px-6 py-4 text-xs font-bold text-brandText-muted uppercase tracking-wider">
                            Deskripsi
                        </th>
                        <th scope="col" class="px-6 py-4 text-xs font-bold text-brandText-muted uppercase tracking-wider text-center">
                            Jumlah Produk
                        </th>
                        <th scope="col" class="px-6 py-4 text-xs font-bold text-brandText-muted uppercase tracking-wider">
                            Status
                        </th>
                        <th scope="col" class="px-6 py-4 text-xs font-bold text-brandText-muted uppercase tracking-wider text-right">
                            Aksi
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-brandSurface-low bg-white text-sm">
                    @if(empty($categories) || count($categories) === 0)
                        <tr>
                            <td colSpan="6" class="px-6 py-12 text-center text-brandText-muted/70 font-semibold">
                                Belum ada kategori terdaftar. Silakan tambahkan kategori baru.
                            </td>
                        </tr>
                    @else
                        @foreach($categories as $cat)
                            <tr class="hover:bg-brandSurface-low/20 transition-colors">
                                {{-- Category Name --}}
                                <td class="px-6 py-4 whitespace-nowrap font-bold text-brandText">
                                    {{ $cat->name }}
                                </td>
                                {{-- Slug --}}
                                <td class="px-6 py-4 whitespace-nowrap text-xs text-brandText-muted font-mono">
                                    {{ $cat->slug }}
                                </td>
                                {{-- Description --}}
                                <td class="px-6 py-4 text-xs text-brandText-muted max-w-xs truncate">
                                    {{ $cat->description ?? '-' }}
                                </td>
                                {{-- Products Count --}}
                                <td class="px-6 py-4 whitespace-nowrap text-xs font-bold text-primary text-center font-mono">
                                    {{ $cat->products_count ?? 0 }}
                                </td>
                                {{-- Active Status Badge --}}
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($cat->is_active)
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-green-50 text-success border border-green-200">
                                            Aktif
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-red-50 text-danger border border-red-200">
                                            Nonaktif
                                        </span>
                                    @endif
                                </td>
                                {{-- Action Buttons --}}
                                <td class="px-6 py-4 whitespace-nowrap text-right text-xs font-bold space-x-3 select-none">
                                    <a
                                        href="{{ route('admin.categories.edit', $cat->id) }}"
                                        class="text-primary hover:text-primary-dark transition-colors inline-block"
                                    >
                                        Ubah
                                    </a>
                                    <form action="{{ route('admin.categories.destroy', $cat->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus kategori &ldquo;{{ addslashes($cat->name) }}&rdquo;?')" class="inline-block">
                                        @csrf
                                        @method('DELETE')
                                        <button
                                            type="submit"
                                            class="text-danger hover:text-red-700 transition-colors"
                                        >
                                            Hapus
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>

        {{-- Pagination Footer block --}}
        @if(!empty($categories) && method_exists($categories, 'links') && $categories->total() > 0)
            <div class="px-6 py-4 bg-brandSurface-low/20 border-t border-brandSurface-high/40 flex flex-col sm:flex-row justify-between items-center gap-4">
                <p class="text-xs text-brandText-muted font-semibold">
                    Menampilkan {{ $categories->firstItem() }}-{{ $categories->lastItem() }} dari {{ $categories->total() }} Kategori
                </p>
                <x-pagination :paginator="$categories" />
            </div>
        @endif
    </div>
</div>
@endsection

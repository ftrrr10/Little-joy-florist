@extends('layouts.dashboard')

@section('title', 'Daftar Produk Rangkaian Bunga')

@section('content')
<div class="space-y-6 font-sans">
    {{-- Upper Action Bar --}}
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 bg-white p-5 rounded-2xl border border-brandOutline-soft/25 shadow-sm reveal">
        <div>
            <h2 class="text-base font-bold text-brandText">Kelola Produk Master</h2>
            <p class="text-xs text-brandText-muted mt-1">
                Tambahkan rangkaian bunga baru, sesuaikan harga, stok, maupun unggah foto produk florist Anda.
            </p>
        </div>
        <a href="{{ route('admin.products.create') }}" class="inline-flex items-center justify-center font-sans font-semibold rounded-lg transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 active:scale-[0.98] bg-primary text-white hover:bg-primary-dark focus:ring-primary-muted shadow-sm text-xs py-2 px-3 gap-1.5 font-bold">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" />
            </svg>
            Tambah Produk Baru
        </a>
    </div>

    {{-- Products Table Card --}}
    <div class="bg-white border border-brandOutline-soft/25 rounded-2xl shadow-sm overflow-hidden reveal" data-delay="100">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-brandSurface-high/60 text-left">
                <thead class="bg-brandSurface-low/40">
                    <tr>
                        <th scope="col" class="px-6 py-4 text-xs font-bold text-brandText-muted uppercase tracking-wider">
                            Foto
                        </th>
                        <th scope="col" class="px-6 py-4 text-xs font-bold text-brandText-muted uppercase tracking-wider">
                            Nama Produk
                        </th>
                        <th scope="col" class="px-6 py-4 text-xs font-bold text-brandText-muted uppercase tracking-wider">
                            Kategori
                        </th>
                        <th scope="col" class="px-6 py-4 text-xs font-bold text-brandText-muted uppercase tracking-wider">
                            Harga
                        </th>
                        <th scope="col" class="px-6 py-4 text-xs font-bold text-brandText-muted uppercase tracking-wider text-center">
                            Stok
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
                    @if(empty($products) || count($products) === 0)
                        <tr>
                            <td colSpan="7" class="px-6 py-12 text-center text-brandText-muted/70 font-semibold">
                                Belum ada produk terdaftar. Silakan tambahkan produk baru.
                            </td>
                        </tr>
                    @else
                        @foreach($products as $product)
                            @php
                                $isLowStock = $product->stock <= 5 && $product->stock > 0;
                                $isOutOfStock = $product->stock <= 0;
                            @endphp
                            <tr class="hover:bg-brandSurface-low/20 transition-colors">
                                {{-- Image Thumbnail --}}
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="h-11 w-14 rounded-lg overflow-hidden bg-brandSurface-low border border-brandOutline-soft/15 flex-shrink-0">
                                        @if($product->image_path)
                                            <img
                                                src="/storage/{{ $product->image_path }}"
                                                alt="{{ $product->name }}"
                                                class="w-full h-full object-cover"
                                            />
                                        @else
                                            <div class="w-full h-full flex items-center justify-center bg-primary-soft/10 text-primary">
                                                <span class="text-lg opacity-40 font-serif">❀</span>
                                            </div>
                                        @endif
                                    </div>
                                </td>

                                {{-- Product Name --}}
                                <td class="px-6 py-4 whitespace-nowrap font-bold text-brandText">
                                    {{ $product->name }}
                                </td>

                                {{-- Category --}}
                                <td class="px-6 py-4 whitespace-nowrap text-xs font-semibold text-primary">
                                    {{ $product->category->name ?? 'Uncategorized' }}
                                </td>

                                {{-- Price --}}
                                <td class="px-6 py-4 whitespace-nowrap font-semibold text-brandText font-mono">
                                    Rp {{ number_format($product->price, 0, ',', '.') }}
                                </td>

                                {{-- Stock Indicator --}}
                                <td class="px-6 py-4 whitespace-nowrap text-center font-mono">
                                    @if($isOutOfStock)
                                        <span class="px-2 py-0.5 rounded-md text-[10px] font-bold bg-red-50 text-danger border border-red-200">
                                            Habis
                                        </span>
                                    @elseif($isLowStock)
                                        <span class="px-2 py-0.5 rounded-md text-[10px] font-bold bg-yellow-50 text-warning border border-yellow-200">
                                            Terbatas ({{ $product->stock }})
                                        </span>
                                    @else
                                        <span class="px-2 py-0.5 rounded-md text-[10px] font-bold bg-green-50 text-success border border-green-200">
                                            {{ $product->stock }}
                                        </span>
                                    @endif
                                </td>

                                {{-- Active Status --}}
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($product->is_active)
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-green-50 text-success border border-green-200">
                                            Aktif
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-red-50 text-danger border border-red-200">
                                            Nonaktif
                                        </span>
                                    @endif
                                </td>

                                {{-- Actions --}}
                                <td class="px-6 py-4 whitespace-nowrap text-right text-xs font-bold space-x-3 select-none">
                                    <a
                                        href="{{ route('admin.products.edit', $product->id) }}"
                                        class="text-primary hover:text-primary-dark transition-colors inline-block"
                                    >
                                        Ubah
                                    </a>
                                    <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus produk &ldquo;{{ addslashes($product->name) }}&rdquo;? Hapus produk akan bersifat soft-delete untuk menjaga keutuhan riwayat transaksi.')" class="inline-block">
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

        {{-- Pagination Footer --}}
        @if(!empty($products) && method_exists($products, 'links') && $products->total() > 0)
            <div class="px-6 py-4 bg-brandSurface-low/20 border-t border-brandSurface-high/40 flex flex-col sm:flex-row justify-between items-center gap-4">
                <p class="text-xs text-brandText-muted font-semibold">
                    Menampilkan {{ $products->firstItem() }}-{{ $products->lastItem() }} dari {{ $products->total() }} Produk
                </p>
                <x-pagination :paginator="$products" />
            </div>
        @endif
    </div>
</div>
@endsection

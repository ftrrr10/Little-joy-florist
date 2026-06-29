@extends('layouts.dashboard')

@section('title', 'Stok & Inventori Bunga')

@section('content')
@php
    // Helper to get stock level indicator details
    function getStockIndicator($stock) {
        if ($stock === 0) {
            return [
                'text' => 'Habis',
                'bg' => 'bg-red-50 text-red-700 border-red-200',
            ];
        } elseif ($stock <= 5) {
            return [
                'text' => 'Stok Terbatas',
                'bg' => 'bg-yellow-50 text-yellow-700 border-yellow-200',
            ];
        } else {
            return [
                'text' => 'Tersedia',
                'bg' => 'bg-green-50 text-green-700 border-green-200',
            ];
        }
    }

    // Helper to get movement type details
    function getMovementTypeDetails($type, $note) {
        $isOut = $type === 'out' || ($type === 'adjustment' && str_contains(strtolower($note), 'kurang'));
        if ($type === 'in') {
            return [
                'text' => 'Masuk',
                'bg' => 'bg-green-50 text-green-700',
                'icon' => '<svg class="w-3 h-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 10l7-7m0 0l7 7m-7-7v18" /></svg>',
            ];
        } elseif ($isOut) {
            return [
                'text' => 'Keluar',
                'bg' => 'bg-red-50 text-red-700',
                'icon' => '<svg class="w-3 h-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 14l-7 7m0 0l-7-7m7 7V3" /></svg>',
            ];
        } else {
            return [
                'text' => 'Penyesuaian',
                'bg' => 'bg-amber-50 text-amber-700',
                'icon' => '<svg class="w-3 h-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /></svg>',
            ];
        }
    }
@endphp

<div class="space-y-8 font-sans" x-data="{
    showModal: false,
    productId: '',
    productName: '',
    productStock: 0,
    adjustmentType: 'add',
    quantity: '',
    note: '',
    openModal(product) {
        this.productId = product.id;
        this.productName = product.name;
        this.productStock = product.stock;
        this.adjustmentType = 'add';
        this.quantity = '';
        this.note = '';
        this.showModal = true;
    },
    closeModal() {
        this.showModal = false;
    }
}">
    {{-- 1. Welcome Header Banner --}}
    <div class="bg-gradient-to-r from-primary to-primary-dark p-8 rounded-3xl text-white shadow-sm relative overflow-hidden reveal">
        <div class="absolute top-0 right-0 w-64 h-64 bg-white/5 rounded-full blur-3xl -mr-10 -mt-10 pointer-events-none"></div>
        <span class="inline-block px-2.5 py-0.5 text-[10px] font-bold tracking-wider uppercase bg-white/20 text-white rounded-full mb-3 backdrop-blur-md">
            Inventori Gudang
        </span>
        <h3 class="font-serif text-3xl font-bold mb-2">
            Ringkasan Stok & Inventori
        </h3>
        <p class="text-white/80 text-sm max-w-2xl leading-relaxed">
            Kelola ketersediaan tangkai bunga segar secara real-time. Anda dapat melakukan penyesuaian jumlah persediaan secara berkala dan memantau log historis riwayat keluar-masuk barang secara transparan.
        </p>
    </div>

    {{-- 2. Main Workspace Grid --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        {{-- Left Column: Product Stock Level Table --}}
        <div class="lg:col-span-2 bg-white p-6 rounded-2xl border border-brandOutline-soft/30 shadow-sm reveal" data-delay="100">
            <div class="flex items-center justify-between mb-4">
                <h4 class="font-serif text-lg font-bold text-primary">
                    Tingkat Ketersediaan Produk Bunga
                </h4>
                <span class="text-xs text-brandText-muted font-semibold">
                    Total {{ count($products) }} produk terdaftar
                </span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs border-collapse">
                    <thead>
                        <tr class="border-b border-gray-100 text-brandText-muted font-bold uppercase tracking-wider bg-gray-50/50">
                            <th class="py-3 px-4">Nama Produk Bunga</th>
                            <th class="py-3 px-3">Kategori</th>
                            <th class="py-3 px-3 text-center">Indikator</th>
                            <th class="py-3 px-3 text-right">Jumlah Stok</th>
                            <th class="py-3 px-4 text-right">Tindakan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 text-brandText">
                        @if(empty($products) || count($products) === 0)
                            <tr>
                                <td colSpan="5" class="py-8 text-center text-brandText-muted/60">
                                    Belum ada produk bunga aktif terdaftar.
                                </td>
                            </tr>
                        @else
                            @foreach($products as $product)
                                @php
                                    $indicator = getStockIndicator($product->stock);
                                @endphp
                                <tr class="hover:bg-cream-light/5 transition-colors">
                                    <td class="py-3.5 px-4 font-semibold text-primary text-xs">
                                        {{ $product->name }}
                                    </td>
                                    <td class="py-3.5 px-3 uppercase tracking-wider text-[10px] text-brandText-muted">
                                        {{ $product->category->name ?? 'Umum' }}
                                    </td>
                                    <td class="py-3.5 px-3 text-center">
                                        <span class="inline-block px-2.5 py-0.5 border rounded-full text-[10px] font-bold tracking-wide {{ $indicator['bg'] }}">
                                            {{ $indicator['text'] }}
                                        </span>
                                    </td>
                                    <td class="py-3.5 px-3 text-right font-mono font-bold text-sm">
                                        {{ $product->stock }} tangkai
                                    </td>
                                    <td class="py-3.5 px-4 text-right">
                                        <button
                                            type="button"
                                            @click="openModal({
                                                id: {{ $product->id }},
                                                name: '{{ addslashes($product->name) }}',
                                                stock: {{ $product->stock }}
                                            })"
                                            class="inline-flex items-center justify-center px-2.5 py-1.5 bg-primary-soft/30 hover:bg-primary-soft text-primary-dark text-[10px] font-bold rounded-lg transition-all"
                                        >
                                            <svg class="w-3.5 h-3.5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            Adjust
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Right Column: Stock Movement History Log --}}
        <div class="bg-white p-6 rounded-2xl border border-brandOutline-soft/30 shadow-sm flex flex-col justify-between reveal" data-delay="200">
            <div>
                <div class="flex items-center gap-2 mb-1">
                    <svg class="w-5 h-5 text-gold" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <h4 class="font-serif text-lg font-bold text-primary">
                        Riwayat Log Mutasi Stok
                    </h4>
                </div>
                <p class="text-xs text-brandText-muted mb-4">
                    Catatan log 30 transaksi mutasi inventori terbaru.
                </p>

                <div class="space-y-4 max-h-[480px] overflow-y-auto pr-1">
                    @if(empty($movements) || count($movements) === 0)
                        <div class="py-16 text-center text-brandText-muted/60 flex flex-col items-center justify-center gap-2">
                            <svg class="w-8 h-8 text-brandOutline-soft/40" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                            </svg>
                            <p class="text-xs font-bold text-brandText-muted">Tidak Ada Mutasi</p>
                            <p class="text-[10px] text-brandText-muted/70 max-w-[180px]">Belum ada riwayat perubahan stok yang tercatat.</p>
                        </div>
                    @else
                        @foreach($movements as $move)
                            @php
                                $badge = getMovementTypeDetails($move->movement_type, $move->note);
                                $moveDate = Carbon\Carbon::parse($move->created_at)->translatedFormat('d M H:i');
                            @endphp
                            <div class="p-3 border border-brandOutline-soft/20 rounded-xl bg-cream-light/5 hover:bg-cream-light/10 transition-all space-y-2 text-xs">
                                <div class="flex items-center justify-between">
                                    <span class="font-bold text-primary truncate max-w-[150px]" title="{{ $move->product->name ?? '' }}">
                                        {{ $move->product->name ?? 'Produk Dihapus' }}
                                    </span>
                                    <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[9px] font-bold uppercase {{ $badge['bg'] }}">
                                        {!! $badge['icon'] !!}
                                        {{ $badge['text'] }}
                                    </span>
                                </div>
                                
                                <div class="grid grid-cols-2 gap-1 text-[10px] text-brandText-muted">
                                    <div class="flex items-center">
                                        <svg class="w-3 h-3 mr-1 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                        {{ $moveDate }} WIB
                                    </div>
                                    <div class="flex items-center justify-end">
                                        <svg class="w-3 h-3 mr-1 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                        </svg>
                                        {{ $move->actor->name ?? 'Sistem' }}
                                    </div>
                                </div>

                                <div class="pt-1.5 border-t border-brandOutline-soft/10 flex justify-between items-center">
                                    <span class="text-[10px] text-brandText-muted italic truncate max-w-[170px]" title="{{ $move->note ?? '' }}">
                                        &ldquo;{{ $move->note ?? 'Tanpa catatan' }}&rdquo;
                                    </span>
                                    <span class="font-mono font-bold text-primary">
                                        {{ $move->stock_before }} &rarr; {{ $move->stock_after }} ({{ $move->stock_after >= $move->stock_before ? '+' : '' }}{{ $move->quantity }})
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- 3. Manual Stock Adjustment Modal --}}
    <div 
        x-show="showModal" 
        style="display: none;" 
        class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-center justify-center p-4"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
    >
        <div class="bg-white border border-brandOutline-soft/30 rounded-3xl max-w-md w-full overflow-hidden shadow-xl" @click.outside="closeModal()">
            {{-- Modal Header --}}
            <div class="px-6 py-4 border-b border-brandOutline-soft/20 flex items-center justify-between bg-cream/10">
                <h3 class="font-serif text-lg font-bold text-primary flex items-center">
                    <svg class="w-4.5 h-4.5 mr-2 text-gold" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4" />
                    </svg>
                    Penyesuaian Stok Bunga
                </h3>
                <button
                    type="button"
                    @click="closeModal()"
                    class="p-1 text-brandText-muted hover:text-primary rounded-lg transition-colors focus:outline-none"
                >
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            {{-- Modal Form --}}
            <form action="{{ route('operator.stock.adjust') }}" method="POST" class="p-6 space-y-4 font-sans text-xs" data-confirm="Apakah Anda yakin ingin melakukan penyesuaian stok produk ini?">
                @csrf
                
                <input type="hidden" name="product_id" :value="productId">
                <input type="hidden" name="quantity" :value="adjustmentType === 'add' ? quantity : -quantity">

                {{-- Product Summary --}}
                <div class="p-4 bg-brandBackground border border-brandOutline-soft/20 rounded-2xl flex justify-between items-center text-xs">
                    <div>
                        <p class="text-[10px] font-bold text-brandText-muted uppercase tracking-wider">Nama Produk</p>
                        <p class="font-serif text-sm font-bold text-primary mt-0.5" x-text="productName"></p>
                    </div>
                    <div class="text-right">
                        <p class="text-[10px] font-bold text-brandText-muted uppercase tracking-wider">Stok Saat Ini</p>
                        <p class="font-mono font-bold text-base text-primary mt-0.5" x-text="productStock + ' tangkai'"></p>
                    </div>
                </div>

                {{-- Type of adjustment --}}
                <div>
                    <label class="block text-xs font-bold text-brandText-muted uppercase tracking-wider mb-2">
                        Jenis Penyesuaian
                    </label>
                    <div class="grid grid-cols-2 gap-3">
                        <button
                            type="button"
                            @click="adjustmentType = 'add'"
                            class="py-2 px-4 text-xs font-bold rounded-xl border text-center transition-all focus:outline-none"
                            :class="adjustmentType === 'add' ? 'bg-green-50 border-green-400 text-green-700 ring-2 ring-green-100' : 'border-brandOutline hover:bg-gray-50 text-brandText-muted'"
                        >
                            + Tambah Stok
                        </button>
                        <button
                            type="button"
                            @click="adjustmentType = 'subtract'"
                            class="py-2 px-4 text-xs font-bold rounded-xl border text-center transition-all focus:outline-none"
                            :class="adjustmentType === 'subtract' ? 'bg-red-50 border-red-300 text-red-700 ring-2 ring-red-50' : 'border-brandOutline hover:bg-gray-50 text-brandText-muted'"
                        >
                            - Kurangi Stok
                        </button>
                    </div>
                </div>

                {{-- Adjustment quantity --}}
                <div>
                    <label for="display_qty" class="block text-xs font-bold text-brandText-muted uppercase tracking-wider mb-2">
                        Jumlah Penyesuaian (tangkai)
                    </label>
                    <input
                        type="number"
                        id="display_qty"
                        min="1"
                        required
                        x-model="quantity"
                        placeholder="Contoh: 10"
                        class="w-full border border-brandOutline rounded-xl px-4 py-2.5 text-sm bg-cream-light/10 text-brandText font-semibold focus:outline-none focus:ring-2 focus:ring-primary-soft focus:border-primary transition-all"
                    />
                </div>

                {{-- Adjustment note --}}
                <div>
                    <label for="note" class="block text-xs font-bold text-brandText-muted uppercase tracking-wider mb-2">
                        Alasan / Catatan Penyesuaian
                    </label>
                    <textarea
                        id="note"
                        name="note"
                        rows="3"
                        required
                        x-model="note"
                        placeholder="Contoh: Pengiriman bunga segar pagi, Koreksi stok bunga layu, dll..."
                        class="w-full border border-brandOutline rounded-xl px-4 py-2.5 text-sm bg-cream-light/10 text-brandText focus:outline-none focus:ring-2 focus:ring-primary-soft focus:border-primary transition-all"
                    ></textarea>
                </div>

                {{-- Modal Footer buttons --}}
                <div class="pt-4 border-t border-brandOutline-soft/20 flex space-x-3 justify-end">
                    <button
                        type="button"
                        @click="closeModal()"
                        class="px-4 py-2 text-xs font-semibold border border-brandOutline rounded-xl text-brandText hover:bg-gray-50 transition-all focus:outline-none"
                    >
                        Batal
                    </button>
                    <button
                        type="submit"
                        class="px-4 py-2 text-xs font-semibold bg-primary text-white hover:bg-primary-dark rounded-xl transition-all focus:outline-none shadow-md disabled:opacity-50"
                        :disabled="!quantity || !note"
                    >
                        Simpan Penyesuaian
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

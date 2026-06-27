@extends('layouts.dashboard')

@section('title', 'Pusat Kendali Operasional')

@section('content')
@php
    function getOperatorStatusBadgeClass($status) {
        switch ($status) {
            case 'completed':
                return 'bg-green-50 text-green-700 border-green-200';
            case 'shipped':
                return 'bg-blue-50 text-blue-700 border-blue-200';
            case 'ready':
                return 'bg-indigo-50 text-indigo-700 border-indigo-200';
            case 'processing':
                return 'bg-amber-50 text-amber-700 border-amber-200';
            case 'paid':
                return 'bg-emerald-50 text-emerald-700 border-emerald-200';
            case 'waiting_verification':
                return 'bg-yellow-50 text-yellow-700 border-yellow-200 animate-pulse';
            case 'pending_payment':
                return 'bg-gray-100 text-gray-700 border-gray-200';
            case 'cancelled':
                return 'bg-red-50 text-red-600 border-red-150';
            case 'rejected':
                return 'bg-red-50 text-red-700 border-red-200';
            default:
                return 'bg-gray-50 text-gray-700 border-gray-200';
        }
    }

    function getOperatorStatusLabel($status) {
        switch ($status) {
            case 'completed': return 'Selesai';
            case 'shipped': return 'Dikirim';
            case 'ready': return 'Siap Kirim';
            case 'processing': return 'Diproses';
            case 'paid': return 'Lunas';
            case 'waiting_verification': return 'Menunggu Verifikasi';
            case 'pending_payment': return 'Belum Bayar';
            case 'cancelled': return 'Batal';
            case 'rejected': return 'Ditolak';
            default: return $status;
        }
    }
@endphp

<div class="space-y-8 font-sans bg-[#FBF9F4] -m-8 p-8 min-h-screen">
    {{-- Welcome Message Banner --}}
    <div class="bg-gradient-to-r from-primary to-primary-dark p-8 rounded-3xl text-white shadow-md relative overflow-hidden reveal">
        <div class="absolute top-0 right-0 w-64 h-64 bg-white/5 rounded-full blur-3xl -mr-10 -mt-10 pointer-events-none"></div>
        <span class="inline-block px-2.5 py-0.5 text-[10px] font-bold tracking-wider uppercase bg-white/20 text-white rounded-full mb-3 backdrop-blur-md">
            Operator Florist
        </span>
        <h3 class="font-serif text-3xl font-bold mb-2">
            Selamat Bekerja, {{ auth()->user()->name }}
        </h3>
        <p class="text-white/80 text-sm max-w-2xl leading-relaxed">
            Pantau antrean verifikasi pembayaran masuk dari pelanggan, koordinasikan perangkaian buket bunga segar hari ini, dan perbarui status logistik pengiriman secara tepat waktu.
        </p>
    </div>

    {{-- 1. TOP ROW: THREE MAIN CARDS --}}
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 reveal" data-delay="100">
        {{-- Card 1: Active Work --}}
        <div class="lg:col-span-6 bg-[#064E3B] text-white p-8 rounded-3xl shadow-sm relative overflow-hidden flex flex-col justify-between min-h-[180px]">
            <div class="absolute right-6 bottom-6 text-white/10 pointer-events-none">
                <svg class="w-40 h-40" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="3" />
                    <path d="M12 5a3 3 0 1 0 0 6 3 3 0 1 0 0-6z" />
                    <path d="M12 13a3 3 0 1 0 0 6 3 3 0 1 0 0-6z" />
                    <path d="M5 12a3 3 0 1 0 6 0 3 3 0 1 0-6 0z" />
                    <path d="M13 12a3 3 0 1 0 6 0 3 3 0 1 0-6 0z" />
                    <path d="M7.05 7.05a3 3 0 1 0 4.24 4.24 3 3 0 1 0-4.24-4.24z" />
                    <path d="M12.71 12.71a3 3 0 1 0 4.24 4.24 3 3 0 1 0-4.24-4.24z" />
                    <path d="M7.05 16.95a3 3 0 1 0 4.24-4.24 3 3 0 1 0-4.24 4.24z" />
                    <path d="M12.71 11.29a3 3 0 1 0 4.24-4.24 3 3 0 1 0-4.24 4.24z" />
                </svg>
            </div>
            <div class="z-10">
                <p class="text-[10px] font-bold tracking-[0.2em] text-[#FFE088] uppercase mb-1">
                    Rangkaian Sedang Dirangkai
                </p>
                <h2 class="text-4xl font-bold font-serif tracking-tight mt-1">
                    {{ $metrics['processing'] ?? 0 }} <span class="text-xl font-normal text-white/90">Pesanan</span>
                </h2>
            </div>
            <div class="z-10 flex items-center gap-2 mt-6 text-xs text-white/80 font-medium">
                <span class="inline-flex items-center gap-0.5 px-2 py-0.5 bg-white/10 rounded-full text-[#FFE088] text-[10px] font-bold">
                    <span class="w-1.5 h-1.5 bg-[#FFE088] rounded-full animate-ping mr-1"></span> Antrean Aktif
                </span>
                <span>Harap segera menyelesaikan perangkaian buket bunga hari ini</span>
            </div>
        </div>

        {{-- Card 2: Waiting Verification --}}
        <div class="lg:col-span-3 bg-white p-6 rounded-3xl border border-brandOutline-soft/35 shadow-sm flex flex-col justify-between min-h-[180px]">
            <div>
                <div class="flex items-center justify-between">
                    <span class="text-[9px] font-bold tracking-[0.15em] text-brandText-muted uppercase">
                        Butuh Verifikasi
                    </span>
                    <div class="p-2 bg-amber-50 text-amber-600 rounded-xl">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
                <h3 class="text-3xl font-bold text-primary mt-3">
                    {{ $metrics['waiting_verification'] ?? 0 }} <span class="text-xs font-semibold text-brandText-muted">pesanan</span>
                </h3>
            </div>
            <div class="text-[10px] text-brandText-muted/80 mt-4 flex items-center justify-between">
                <span>Status: Menunggu Resi</span>
                <span class="font-bold text-amber-600">Segera Tinjau</span>
            </div>
        </div>

        {{-- Card 3: Low Stock Warnings --}}
        <div class="lg:col-span-3 bg-white p-6 rounded-3xl border border-brandOutline-soft/35 shadow-sm flex flex-col justify-between min-h-[180px]">
            <div>
                <div class="flex items-center justify-between">
                    <span class="text-[9px] font-bold tracking-[0.15em] text-brandText-muted uppercase">
                        Stok Bunga Kritis
                    </span>
                    <div class="p-2 bg-red-50 text-red-600 rounded-xl">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                    </div>
                </div>
                <h3 class="text-3xl font-bold text-red-600 mt-3">
                    {{ $metrics['low_stock_count'] ?? 0 }} <span class="text-xs font-semibold text-brandText-muted">item</span>
                </h3>
            </div>
            <div class="mt-4">
                <a 
                    href="{{ route('operator.stock.index') }}" 
                    class="text-[10px] font-bold text-red-600 hover:text-red-700 flex items-center gap-0.5 group w-fit"
                >
                    Periksa Stok <svg class="w-3.5 h-3.5 group-hover:translate-x-0.5 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3" /></svg>
                </a>
            </div>
        </div>
    </div>

    {{-- 2. ROW 2: HORIZONTAL STATUS TRACKING BADGES --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 reveal" data-delay="150">
        {{-- Status 1: Menunggu Verifikasi --}}
        <div class="bg-white p-4 rounded-2xl border border-brandOutline-soft/30 shadow-sm flex flex-col justify-between relative overflow-hidden">
            @if(($metrics['waiting_verification'] ?? 0) > 0)
                <span class="absolute top-0 right-0 w-2 h-2 bg-yellow-500 rounded-full animate-ping m-3"></span>
            @endif
            <span class="text-[9px] font-bold tracking-wider text-brandText-muted uppercase">Menunggu Verifikasi</span>
            <div class="flex items-baseline justify-between mt-2">
                <span class="text-2xl font-bold text-yellow-600">{{ $metrics['waiting_verification'] ?? 0 }}</span>
                <span class="text-[10px] text-brandText-muted font-medium">order</span>
            </div>
        </div>

        {{-- Status 2: Sedang Dirangkai --}}
        <div class="bg-white p-4 rounded-2xl border border-brandOutline-soft/30 shadow-sm flex flex-col justify-between">
            <span class="text-[9px] font-bold tracking-wider text-brandText-muted uppercase">Sedang Dirangkai</span>
            <div class="flex items-baseline justify-between mt-2">
                <span class="text-2xl font-bold text-primary">{{ $metrics['processing'] ?? 0 }}</span>
                <span class="text-[10px] text-brandText-muted font-medium">order</span>
            </div>
        </div>

        {{-- Status 3: Siap Kirim --}}
        <div class="bg-white p-4 rounded-2xl border border-brandOutline-soft/30 shadow-sm flex flex-col justify-between">
            <span class="text-[9px] font-bold tracking-wider text-brandText-muted uppercase">Siap Kirim</span>
            <div class="flex items-baseline justify-between mt-2">
                <span class="text-2xl font-bold text-indigo-600">
                    {{ count(collect($processing_orders)->where('order_status', 'ready')) }}
                </span>
                <span class="text-[10px] text-brandText-muted font-medium">order</span>
            </div>
        </div>

        {{-- Status 4: Dalam Pengiriman --}}
        <div class="bg-white p-4 rounded-2xl border border-brandOutline-soft/30 shadow-sm flex flex-col justify-between">
            <span class="text-[9px] font-bold tracking-wider text-brandText-muted uppercase">Dalam Pengiriman</span>
            <div class="flex items-baseline justify-between mt-2">
                <span class="text-2xl font-bold text-blue-600">{{ $metrics['shipped'] ?? 0 }}</span>
                <span class="text-[10px] text-brandText-muted font-medium">order</span>
            </div>
        </div>
    </div>

    {{-- 3. ROW 3: VERIFICATION QUEUE & LOW STOCK PANELS --}}
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 reveal" data-delay="200">
        {{-- Antrean Verifikasi Pembayaran --}}
        <div class="lg:col-span-8 bg-white p-6 rounded-3xl border border-brandOutline-soft/35 shadow-sm">
            <div class="flex items-center justify-between mb-6">
                <h4 class="font-serif text-base font-bold text-primary">
                    Antrean Verifikasi Pembayaran
                </h4>
                <span class="inline-flex items-center px-2 py-0.5 text-[9px] font-bold tracking-wide rounded-md bg-amber-50 border border-amber-200 text-amber-800 animate-pulse">
                    Butuh Tindakan Segera
                </span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs border-collapse">
                    <thead>
                        <tr class="border-b border-brandOutline-soft/20 text-brandText-muted font-bold bg-cream/10">
                            <th class="py-3 px-4">No. Pesanan</th>
                            <th class="py-3 px-2">Nama Pengirim</th>
                            <th class="py-3 px-2 text-right">Nominal</th>
                            <th class="py-3 px-4 text-center">Status</th>
                            <th class="py-3 px-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-brandOutline-soft/10 text-brandText">
                        @if(empty($waiting_orders) || count($waiting_orders) === 0)
                            <tr>
                                <td colSpan="5" class="py-8 text-center text-brandText-muted/65">
                                    Tidak ada antrean verifikasi pembayaran saat ini.
                                </td>
                            </tr>
                        @else
                            @foreach($waiting_orders as $order)
                                <tr class="hover:bg-cream/5 transition-colors">
                                    <td class="py-3.5 px-4 font-bold text-primary">
                                        #{{ $order->order_number }}
                                    </td>
                                    <td class="py-3.5 px-2">
                                        <p class="font-semibold text-brandText-dark">{{ $order->user->name ?? '' }}</p>
                                        <p class="text-[9px] text-brandText-muted flex items-center mt-0.5">
                                            <svg class="w-3 h-3 mr-1 text-gold" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                            </svg>
                                            {{ $order->recipient_name }}
                                        </p>
                                    </td>
                                    <td class="py-3.5 px-2 font-bold text-right">
                                        Rp {{ number_format($order->total, 0, ',', '.') }}
                                    </td>
                                    <td class="py-3.5 px-4 text-center">
                                        <span class="inline-block px-2.5 py-0.5 border rounded-md text-[9px] font-bold bg-yellow-50 border-yellow-200 text-yellow-700">
                                            Menunggu Verifikasi
                                        </span>
                                    </td>
                                    <td class="py-3.5 px-4 text-right">
                                        <a 
                                            href="{{ route('operator.orders.show', $order->order_number) }}"
                                            class="inline-flex items-center justify-center px-2.5 py-1.5 bg-primary hover:bg-primary-dark text-white text-[10px] font-bold rounded-xl transition-all shadow-sm"
                                        >
                                            Tinjau Resi
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Low Stock Watch --}}
        <div class="lg:col-span-4 bg-white p-6 rounded-3xl border border-brandOutline-soft/35 shadow-sm flex flex-col justify-between">
            <div>
                <h4 class="font-serif text-base font-bold text-primary mb-1">
                    Pemantauan Stok Kritis
                </h4>
                <p class="text-[10px] text-brandText-muted mb-4">
                    Bunga aktif terdaftar dengan sisa stok &le; 5 tangkai.
                </p>

                <div class="space-y-3 max-h-[220px] overflow-y-auto pr-1">
                    @if(empty($low_stock_products) || count($low_stock_products) === 0)
                        <div class="py-12 text-center text-brandText-muted/60 flex flex-col items-center justify-center gap-2">
                            <svg class="w-8 h-8 text-green-500/30" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                            </svg>
                            <p class="text-xs font-bold text-green-700">Persediaan Aman</p>
                            <p class="text-[9px] text-brandText-muted/70 max-w-[180px]">Seluruh sisa stok bunga di gudang mencukupi.</p>
                        </div>
                    @else
                        @foreach($low_stock_products as $product)
                            <div class="flex items-center justify-between p-3 rounded-2xl border border-red-100 bg-red-50/5 hover:bg-red-50/20 transition-all">
                                <div class="min-w-0 flex-1 pr-2">
                                    <p class="font-bold text-xs text-brandText truncate">
                                        {{ $product->name }}
                                    </p>
                                    <p class="text-[9px] text-brandText-muted uppercase tracking-wider">
                                        {{ $product->category->name ?? 'Umum' }}
                                    </p>
                                </div>
                                <div class="text-right">
                                    <span class="inline-block px-2.5 py-1 text-xs font-bold bg-red-100 text-red-700 rounded-xl">
                                        {{ $product->stock }} tangkai
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>

            @if(!empty($low_stock_products) && count($low_stock_products) > 0)
                <a
                    href="{{ route('operator.stock.index') }}"
                    class="w-full mt-6 py-2.5 bg-red-50 hover:bg-red-100 text-red-700 text-center text-xs font-bold rounded-xl transition-all block border border-red-200 font-semibold"
                >
                    Periksa Stok Bunga
                </a>
            @endif
        </div>
    </div>

    {{-- 4. ROW 4: CRAFTING WORK QUEUE --}}
    <div class="bg-white p-6 rounded-3xl border border-brandOutline-soft/35 shadow-sm mt-8 reveal" data-delay="250">
        <div class="flex items-center justify-between mb-6">
            <div class="flex items-center gap-2">
                <svg class="w-5 h-5 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="3" />
                    <path d="M12 5a3 3 0 1 0 0 6 3 3 0 1 0 0-6z" />
                    <path d="M12 13a3 3 0 1 0 0 6 3 3 0 1 0 0-6z" />
                    <path d="M5 12a3 3 0 1 0 6 0 3 3 0 1 0-6 0z" />
                    <path d="M13 12a3 3 0 1 0 6 0 3 3 0 1 0-6 0z" />
                    <path d="M7.05 7.05a3 3 0 1 0 4.24 4.24 3 3 0 1 0-4.24-4.24z" />
                    <path d="M12.71 12.71a3 3 0 1 0 4.24 4.24 3 3 0 1 0-4.24-4.24z" />
                    <path d="M7.05 16.95a3 3 0 1 0 4.24-4.24 3 3 0 1 0-4.24 4.24z" />
                    <path d="M12.71 11.29a3 3 0 1 0 4.24-4.24 3 3 0 1 0-4.24 4.24z" />
                </svg>
                <h4 class="font-serif text-base font-bold text-primary">
                    Pekerjaan Merangkai Bunga Hari Ini
                </h4>
            </div>
            <a 
                href="{{ route('operator.orders.index') }}" 
                class="text-[9px] font-bold text-[#064E3B] hover:text-primary uppercase tracking-wider"
            >
                Lihat Semua Pekerjaan
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs border-collapse">
                <thead>
                    <tr class="border-b border-brandOutline-soft/20 text-brandText-muted font-bold bg-cream/10">
                        <th class="py-3 px-4">No. Pesanan</th>
                        <th class="py-3 px-3">Penerima Bunga</th>
                        <th class="py-3 px-3">Tanggal Pengiriman</th>
                        <th class="py-3 px-3 text-center">Status Kerja</th>
                        <th class="py-3 px-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-brandOutline-soft/10 text-brandText">
                    @if(empty($processing_orders) || count($processing_orders) === 0)
                        <tr>
                            <td colSpan="5" class="py-8 text-center text-brandText-muted/65">
                                Tidak ada antrean perangkaian bunga aktif hari ini.
                            </td>
                        </tr>
                    @else
                        @foreach($processing_orders as $order)
                            @php
                                $delivDate = Carbon\Carbon::parse($order->delivery_date)->translatedFormat('d F Y');
                            @endphp
                            <tr class="hover:bg-cream/5 transition-colors">
                                <td class="py-3.5 px-4 font-bold text-primary">
                                    #{{ $order->order_number }}
                                </td>
                                <td class="py-3.5 px-3 font-semibold text-brandText-dark">
                                    {{ $order->recipient_name }}
                                </td>
                                <td class="py-3.5 px-3 text-brandText-muted">
                                    <span class="flex items-center">
                                        <svg class="w-3.5 h-3.5 mr-1 text-primary/70" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                        {{ $delivDate }}
                                    </span>
                                </td>
                                <td class="py-3.5 px-3 text-center">
                                    <span class="inline-block px-2.5 py-0.5 border rounded-md text-[9px] font-bold {{ getOperatorStatusBadgeClass($order->order_status) }}">
                                        {{ getOperatorStatusLabel($order->order_status) }}
                                    </span>
                                </td>
                                <td class="py-3.5 px-4 text-right">
                                    <a 
                                        href="{{ route('operator.orders.show', $order->order_number) }}"
                                        class="inline-flex items-center justify-center p-1 bg-brandOutline-soft/10 text-primary hover:bg-primary hover:text-white rounded-lg transition-all"
                                        title="Buka Detail Kerja"
                                    >
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7" />
                                        </svg>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@extends('layouts.dashboard')

@section('title', 'Executive Summary & Dashboard')

@section('content')
@php
    $weeklyTotal = collect($weekly_trend)->sum('revenue');
    $monthlyTotal = collect($monthly_trend)->sum('revenue');

    function getAdminStatusBadgeClass($status) {
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
                return 'bg-red-50 text-red-650 border-red-150';
            case 'rejected':
                return 'bg-red-50 text-red-700 border-red-200';
            default:
                return 'bg-gray-50 text-gray-700 border-gray-200';
        }
    }

    function getAdminStatusLabel($status) {
        switch ($status) {
            case 'completed': return 'Selesai';
            case 'shipped': return 'Dikirim';
            case 'ready': return 'Siap';
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

<div class="space-y-8 font-sans bg-[#FBF9F4] -m-8 p-8 min-h-screen" x-data="{ trendPeriod: 'weekly' }">
    {{-- Welcome Message Banner --}}
    <div class="bg-gradient-to-r from-primary to-primary-dark p-8 rounded-3xl text-white shadow-md relative overflow-hidden reveal">
        <div class="absolute top-0 right-0 w-64 h-64 bg-white/5 rounded-full blur-3xl -mr-10 -mt-10 pointer-events-none"></div>
        <span class="inline-block px-2.5 py-0.5 text-[10px] font-bold tracking-wider uppercase bg-white/20 text-white rounded-full mb-3 backdrop-blur-md">
            Administrator Portal
        </span>
        <h3 class="font-serif text-3xl font-bold mb-2">
            Selamat Datang Kembali, {{ auth()->user()->name }}
        </h3>
        <p class="text-white/80 text-sm max-w-2xl leading-relaxed">
            Pantau realisasi penjualan, kendalikan stok bunga kritis, verifikasi pembayaran pelanggan, serta audit kinerja staf operator secara terintegrasi.
        </p>
    </div>

    {{-- 1. TOP ROW: THREE MAIN CARDS --}}
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 reveal" data-delay="100">
        {{-- Card 1: Total Omset --}}
        <div class="lg:col-span-6 bg-[#064E3B] text-white p-8 rounded-3xl shadow-sm relative overflow-hidden flex flex-col justify-between min-h-[180px]">
            <div class="absolute right-6 bottom-6 text-white/10 pointer-events-none">
                <svg class="w-40 h-40" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                </svg>
            </div>
            <div class="z-10">
                <p class="text-[10px] font-bold tracking-[0.2em] text-[#FFE088] uppercase mb-1">
                    Total Penjualan
                </p>
                <h2 class="text-4xl font-bold font-serif tracking-tight mt-1">
                    Rp {{ number_format($metrics['total_sales'] ?? 0, 0, ',', '.') }}
                </h2>
            </div>
            <div class="z-10 flex items-center gap-2 mt-6 text-xs text-white/80 font-medium">
                <span class="inline-flex items-center gap-0.5 px-2 py-0.5 bg-white/10 rounded-full text-[#FFE088] text-[10px] font-bold">
                    +12.5%
                </span>
                <span>dibandingkan minggu lalu</span>
            </div>
        </div>

        {{-- Card 2: Pesanan Hari Ini --}}
        <div class="lg:col-span-3 bg-white p-6 rounded-3xl border border-brandOutline-soft/35 shadow-sm flex flex-col justify-between min-h-[180px]">
            <div>
                <div class="flex items-center justify-between">
                    <span class="text-[9px] font-bold tracking-[0.15em] text-brandText-muted uppercase">
                        Pesanan Hari Ini
                    </span>
                    <div class="p-2 bg-blue-50 text-blue-600 rounded-xl">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                        </svg>
                    </div>
                </div>
                <h3 class="text-3xl font-bold text-primary mt-3">
                    {{ $metrics['orders_today'] ?? 0 }} <span class="text-xs font-semibold text-brandText-muted">pesanan</span>
                </h3>
            </div>
            <div class="text-[10px] text-brandText-muted/80 mt-4 flex items-center justify-between">
                <span>Target harian: 20</span>
                @php
                    $targetPct = min(round((($metrics['orders_today'] ?? 0) / 20) * 100), 100);
                @endphp
                <span class="font-bold text-primary">{{ $targetPct }}% selesai</span>
            </div>
        </div>

        {{-- Card 3: Peringatan Stok --}}
        <div class="lg:col-span-3 bg-white p-6 rounded-3xl border border-brandOutline-soft/35 shadow-sm flex flex-col justify-between min-h-[180px]">
            <div>
                <div class="flex items-center justify-between">
                    <span class="text-[9px] font-bold tracking-[0.15em] text-brandText-muted uppercase">
                        Stok Rendah
                    </span>
                    <div class="p-2 bg-red-50 text-red-600 rounded-xl animate-pulse">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                    </div>
                </div>
                <h3 class="text-3xl font-bold text-red-600 mt-3">
                    {{ count($low_stock_products) }} <span class="text-xs font-semibold text-brandText-muted">produk</span>
                </h3>
            </div>
            <div class="mt-4">
                <a 
                    href="{{ route('operator.stock.index') }}" 
                    class="text-[10px] font-bold text-red-600 hover:text-red-700 flex items-center gap-0.5 group w-fit"
                >
                    Lihat Produk <svg class="w-3.5 h-3.5 group-hover:translate-x-0.5 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3" /></svg>
                </a>
            </div>
        </div>
    </div>

    {{-- 2. ROW 2: HORIZONTAL STATUS TRACKING BADGES --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 reveal" data-delay="150">
        {{-- Status 1: Menunggu Pembayaran --}}
        <div class="bg-white p-4 rounded-2xl border border-brandOutline-soft/30 shadow-sm flex flex-col justify-between">
            <span class="text-[9px] font-bold tracking-wider text-brandText-muted uppercase">Menunggu Pembayaran</span>
            <div class="flex items-baseline justify-between mt-2">
                <span class="text-2xl font-bold text-primary">{{ $metrics['status_counts']['pending_payment'] ?? 0 }}</span>
                <span class="text-[10px] text-brandText-muted">order</span>
            </div>
        </div>

        {{-- Status 2: Menunggu Verifikasi --}}
        <div class="bg-white p-4 rounded-2xl border border-brandOutline-soft/30 shadow-sm flex flex-col justify-between relative overflow-hidden">
            @if(($metrics['status_counts']['waiting_verification'] ?? 0) > 0)
                <span class="absolute top-0 right-0 w-2 h-2 bg-yellow-500 rounded-full animate-ping m-3"></span>
            @endif
            <span class="text-[9px] font-bold tracking-wider text-brandText-muted uppercase">Menunggu Verifikasi</span>
            <div class="flex items-baseline justify-between mt-2">
                <span class="text-2xl font-bold text-yellow-600">{{ $metrics['status_counts']['waiting_verification'] ?? 0 }}</span>
                <span class="text-[10px] text-brandText-muted">order</span>
            </div>
        </div>

        {{-- Status 3: Sedang Diproses --}}
        <div class="bg-white p-4 rounded-2xl border border-brandOutline-soft/30 shadow-sm flex flex-col justify-between">
            <span class="text-[9px] font-bold tracking-wider text-brandText-muted uppercase">Sedang Diproses</span>
            <div class="flex items-baseline justify-between mt-2">
                <span class="text-2xl font-bold text-indigo-600">
                    {{ ($metrics['status_counts']['processing'] ?? 0) + ($metrics['status_counts']['ready'] ?? 0) }}
                </span>
                <span class="text-[10px] text-brandText-muted">order</span>
            </div>
        </div>

        {{-- Status 4: Sedang Dikirim --}}
        <div class="bg-white p-4 rounded-2xl border border-brandOutline-soft/30 shadow-sm flex flex-col justify-between">
            <span class="text-[9px] font-bold tracking-wider text-brandText-muted uppercase">Sedang Dikirim</span>
            <div class="flex items-baseline justify-between mt-2">
                <span class="text-2xl font-bold text-blue-600">{{ $metrics['status_counts']['shipped'] ?? 0 }}</span>
                <span class="text-[10px] text-brandText-muted">order</span>
            </div>
        </div>
    </div>

    {{-- 3. ROW 3: TREND CHART & RECENT ORDERS TABLE --}}
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 reveal" data-delay="200">
        {{-- Sales Trend Column --}}
        <div class="lg:col-span-4 bg-white p-6 rounded-3xl border border-brandOutline-soft/35 shadow-sm flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between mb-4">
                    <h4 class="font-serif text-base font-bold text-primary">
                        Tren Penjualan
                    </h4>
                    <div class="flex bg-gray-50 border border-gray-200/60 p-0.5 rounded-lg">
                        <button
                            @click="trendPeriod = trendPeriod === 'weekly' ? 'monthly' : 'weekly'"
                            class="px-2.5 py-1 rounded-md text-[10px] font-bold bg-white text-primary shadow-sm transition-all"
                            x-text="trendPeriod === 'weekly' ? '7 Hari' : '6 Bulan'"
                        ></button>
                    </div>
                </div>
                
                {{-- Weekly Trend (CSS Bars) --}}
                <div x-show="trendPeriod === 'weekly'" class="flex items-end justify-between h-48 pt-4 pb-2 border-b border-brandOutline-soft/10">
                    @php $maxWeekly = collect($weekly_trend)->max('revenue') ?: 1; @endphp
                    @foreach($weekly_trend as $item)
                        @php $hPct = ($item['revenue'] / $maxWeekly) * 100; @endphp
                        <div class="flex flex-col items-center flex-1 group relative mx-0.5">
                            <div class="absolute bottom-full mb-2 hidden group-hover:block bg-[#022C22] text-[#F7F4EB] text-[9px] font-bold py-1 px-2 rounded-lg shadow-md whitespace-nowrap z-10">
                                Rp {{ number_format($item['revenue'], 0, ',', '.') }}
                            </div>
                            <div class="w-full bg-[#064E3B] hover:bg-primary-dark rounded-t transition-all duration-300" style="height: {{ max($hPct, 4) }}%; min-height: 4px;"></div>
                            <span class="text-[9px] text-brandText-muted mt-2 font-semibold select-none">{{ $item['label'] }}</span>
                        </div>
                    @endforeach
                </div>

                {{-- Monthly Trend (CSS Bars) --}}
                <div x-show="trendPeriod === 'monthly'" style="display: none;" class="flex items-end justify-between h-48 pt-4 pb-2 border-b border-brandOutline-soft/10">
                    @php $maxMonthly = collect($monthly_trend)->max('revenue') ?: 1; @endphp
                    @foreach($monthly_trend as $item)
                        @php $hPct = ($item['revenue'] / $maxMonthly) * 100; @endphp
                        <div class="flex flex-col items-center flex-1 group relative mx-1">
                            <div class="absolute bottom-full mb-2 hidden group-hover:block bg-[#022C22] text-[#F7F4EB] text-[9px] font-bold py-1 px-2 rounded-lg shadow-md whitespace-nowrap z-10">
                                Rp {{ number_format($item['revenue'], 0, ',', '.') }}
                            </div>
                            <div class="w-full bg-[#064E3B] hover:bg-primary-dark rounded-t transition-all duration-300" style="height: {{ max($hPct, 4) }}%; min-height: 4px;"></div>
                            <span class="text-[9px] text-brandText-muted mt-2 font-semibold select-none">{{ $item['label'] }}</span>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="border-t border-brandOutline-soft/15 pt-4 mt-4 flex justify-between items-center text-xs">
                <span class="text-brandText-muted">Total Periode Ini:</span>
                <span class="font-bold text-primary font-serif" x-text="trendPeriod === 'weekly' ? 'Rp {{ number_format($weeklyTotal, 0, ',', '.') }}' : 'Rp {{ number_format($monthlyTotal, 0, ',', '.') }}'"></span>
            </div>
        </div>

        {{-- Recent Orders --}}
        <div class="lg:col-span-8 bg-white p-6 rounded-3xl border border-brandOutline-soft/35 shadow-sm">
            <div class="flex items-center justify-between mb-6">
                <h4 class="font-serif text-base font-bold text-primary">
                    Pesanan Terbaru
                </h4>
                <a 
                    href="{{ route('operator.orders.index') }}" 
                    class="text-[10px] font-bold text-[#064E3B] hover:text-primary uppercase tracking-wider flex items-center gap-0.5 group"
                >
                    Lihat Semua <svg class="w-3.5 h-3.5 group-hover:translate-x-0.5 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3" /></svg>
                </a>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs border-collapse">
                    <thead>
                        <tr class="border-b border-brandOutline-soft/20 text-brandText-muted font-bold bg-cream/10">
                            <th class="py-3 px-4">ID Pesanan</th>
                            <th class="py-3 px-2">Pelanggan</th>
                            <th class="py-3 px-2 text-right">Total</th>
                            <th class="py-3 px-4 text-center">Status</th>
                            <th class="py-3 px-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-brandOutline-soft/10">
                        @if(empty($recent_orders) || count($recent_orders) === 0)
                            <tr>
                                <td colSpan="5" class="py-8 text-center text-brandText-muted/60">
                                    Belum ada pesanan terdaftar.
                                </td>
                            </tr>
                        @else
                            @foreach($recent_orders as $order)
                                @php
                                    $orderDate = Carbon\Carbon::parse($order->created_at)->translatedFormat('d M Y');
                                @endphp
                                <tr class="hover:bg-cream/5 transition-colors">
                                    <td class="py-3 px-4 font-bold text-primary">
                                        {{ $order->order_number }}
                                    </td>
                                    <td class="py-3 px-2">
                                        <div class="flex items-center gap-2">
                                            <div class="h-6 w-6 rounded-full bg-primary-soft/30 text-primary font-bold text-[10px] flex items-center justify-center">
                                                {{ strtoupper(substr($order->user->name ?? 'G', 0, 1)) }}
                                            </div>
                                            <div>
                                                <p class="font-semibold text-brandText-dark">{{ $order->user->name ?? 'Guest' }}</p>
                                                <p class="text-[9px] text-brandText-muted">{{ $orderDate }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="py-3 px-2 font-bold text-primary text-right">
                                        Rp {{ number_format($order->total, 0, ',', '.') }}
                                    </td>
                                    <td class="py-3 px-4 text-center">
                                        <span class="inline-flex px-2 py-0.5 text-[9px] font-bold tracking-wide rounded-md border {{ getAdminStatusBadgeClass($order->order_status) }}">
                                            {{ getAdminStatusLabel($order->order_status) }}
                                        </span>
                                    </td>
                                    <td class="py-3 px-4 text-right">
                                        <a 
                                            href="{{ route('operator.orders.show', $order->order_number) }}"
                                            class="inline-flex items-center justify-center p-1 bg-brandOutline-soft/10 text-primary hover:bg-primary hover:text-white rounded-lg transition-all"
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

    {{-- 4. ROW 4: DYNAMIC USER & STAFF DIRECTORIES --}}
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 border-t border-brandOutline-soft/15 pt-8 reveal" data-delay="250">
        {{-- Recent Registered Customers --}}
        <div class="lg:col-span-6 bg-white p-6 rounded-3xl border border-brandOutline-soft/35 shadow-sm">
            <div class="flex items-center justify-between mb-6">
                <div class="flex items-center gap-2">
                    <svg class="w-5 h-5 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    <h4 class="font-serif text-base font-bold text-primary">
                        Pelanggan Terbaru
                    </h4>
                </div>
                <a 
                    href="{{ route('admin.customers.index') }}" 
                    class="text-[9px] font-bold text-[#064E3B] hover:underline uppercase tracking-wider"
                >
                    Kelola Pelanggan
                </a>
            </div>

            <div class="space-y-4 max-h-[300px] overflow-y-auto pr-1">
                @if(empty($recent_customers) || count($recent_customers) === 0)
                    <p class="text-xs text-brandText-muted/70 py-8 text-center">Belum ada pelanggan terdaftar.</p>
                @else
                    @foreach($recent_customers as $cust)
                        <div class="flex items-center justify-between p-3 rounded-2xl border border-brandOutline-soft/10 hover:bg-cream/5 transition-all">
                            <div class="flex items-center gap-3">
                                <div class="h-9 w-9 rounded-full bg-primary-soft/30 text-primary font-bold text-xs flex items-center justify-center">
                                    {{ strtoupper(substr($cust->name, 0, 1)) }}
                                </div>
                                <div>
                                    <p class="font-bold text-xs text-brandText">{{ $cust->name }}</p>
                                    <p class="text-[10px] text-brandText-muted">{{ $cust->email }}</p>
                                </div>
                            </div>
                            <div class="text-right font-sans">
                                <p class="font-bold text-xs text-primary">Rp {{ number_format($cust->total_spent, 0, ',', '.') }}</p>
                                <p class="text-[9px] text-brandText-muted font-medium">{{ $cust->orders_count }} Transaksi</p>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>

        {{-- Operator Staff Performance List --}}
        <div class="lg:col-span-6 bg-white p-6 rounded-3xl border border-brandOutline-soft/35 shadow-sm">
            <div class="flex items-center justify-between mb-6">
                <div class="flex items-center gap-2">
                    <svg class="w-5 h-5 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <h4 class="font-serif text-base font-bold text-primary">
                        Tim Operator Staf
                    </h4>
                </div>
                <a 
                    href="{{ route('admin.operators.index') }}" 
                    class="text-[9px] font-bold text-[#064E3B] hover:underline uppercase tracking-wider"
                >
                    Kelola Staf
                </a>
            </div>

            <div class="space-y-4 max-h-[300px] overflow-y-auto pr-1">
                @if(empty($operators_list) || count($operators_list) === 0)
                    <p class="text-xs text-brandText-muted/70 py-8 text-center">Belum ada operator terdaftar.</p>
                @else
                    @foreach($operators_list as $op)
                        <div class="flex items-center justify-between p-3 rounded-2xl border border-brandOutline-soft/10 hover:bg-cream/5 transition-all">
                            <div class="flex items-center gap-3">
                                <div class="h-9 w-9 rounded-full bg-emerald-50 text-emerald-700 font-bold text-xs flex items-center justify-center ring-2 ring-emerald-500/20 p-0.5">
                                    {{ strtoupper(substr($op->name, 0, 1)) }}
                                </div>
                                <div>
                                    <div class="flex items-center gap-1.5">
                                        <span class="font-bold text-xs text-brandText">{{ $op->name }}</span>
                                        <span class="w-1.5 h-1.5 rounded-full {{ $op->is_active ? 'bg-emerald-500 animate-pulse' : 'bg-red-500' }}" title="{{ $op->is_active ? 'Aktif' : 'Nonaktif' }}"></span>
                                    </div>
                                    <p class="text-[10px] text-brandText-muted">{{ $op->phone }}</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-emerald-50 text-emerald-700 rounded-lg text-[10px] font-bold border border-emerald-100 font-semibold">
                                    {{ $op->verified_payments_count }} Verifikasi
                                </span>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

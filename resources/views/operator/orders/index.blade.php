@extends('layouts.dashboard')

@section('title', 'Kelola Pesanan Masuk')

@section('content')
@php
    // Helper to get status badge class
    function getOperatorStatusBadgeClass($status) {
        switch ($status) {
            case 'pending_payment':
                return 'bg-amber-50 border-amber-200 text-amber-800';
            case 'waiting_verification':
                return 'bg-blue-50 border-blue-200 text-blue-800';
            case 'paid':
                return 'bg-green-50 border-green-200 text-green-800';
            case 'processing':
                return 'bg-indigo-50 border-indigo-200 text-indigo-800';
            case 'ready':
                return 'bg-indigo-50 border-indigo-200 text-indigo-800';
            case 'shipped':
                return 'bg-purple-50 border-purple-200 text-purple-800';
            case 'completed':
                return 'bg-green-50 border-green-200 text-green-800';
            case 'cancelled':
                return 'bg-red-50 border-red-200 text-red-800';
            case 'rejected':
                return 'bg-red-50 border-red-200 text-red-800';
            default:
                return 'bg-gray-50 border-gray-200 text-gray-800';
        }
    }

    // Helper to get status label
    function getOperatorStatusLabel($status) {
        switch ($status) {
            case 'pending_payment': return 'Belum Bayar';
            case 'waiting_verification': return 'Menunggu Verifikasi';
            case 'paid': return 'Lunas';
            case 'processing': return 'Diproses';
            case 'ready': return 'Siap Dikirim';
            case 'shipped': return 'Dikirim';
            case 'completed': return 'Selesai';
            case 'cancelled': return 'Dibatalkan';
            case 'rejected': return 'Ditolak';
            default: return $status;
        }
    }
@endphp

<div x-data="{ 
    searchTerm: '', 
    statusFilter: 'all',
    orders: [
        @foreach($orders as $order)
        {
            id: {{ $order->id }},
            order_number: '{{ $order->order_number }}',
            recipient_name: '{{ addslashes($order->recipient_name) }}',
            user_name: '{{ addslashes($order->user->name ?? 'Guest') }}',
            order_date: '{{ Carbon\Carbon::parse($order->order_date)->translatedFormat('d M y') }}',
            delivery_date: '{{ Carbon\Carbon::parse($order->delivery_date)->translatedFormat('d M y') }}',
            total: 'Rp {{ number_format($order->total, 0, ',', '.') }}',
            order_status: '{{ $order->order_status }}',
            showUrl: '{{ route('operator.orders.show', $order->order_number) }}',
            badgeClass: '{{ getOperatorStatusBadgeClass($order->order_status) }}',
            statusLabel: '{{ getOperatorStatusLabel($order->order_status) }}',
            isUnpaid: {{ in_array($order->order_status, ['pending_payment', 'rejected']) ? 'true' : 'false' }},
            isWaiting: {{ $order->order_status === 'waiting_verification' ? 'true' : 'false' }},
            isProcessing: {{ in_array($order->order_status, ['paid', 'processing', 'ready']) ? 'true' : 'false' }},
            isShipped: {{ $order->order_status === 'shipped' ? 'true' : 'false' }},
            isCompleted: {{ $order->order_status === 'completed' ? 'true' : 'false' }},
            isCancelled: {{ $order->order_status === 'cancelled' ? 'true' : 'false' }}
        },
        @endforeach
    ],
    get filteredOrders() {
        return this.orders.filter(order => {
            const matchesSearch = order.order_number.toLowerCase().includes(this.searchTerm.toLowerCase()) ||
                order.recipient_name.toLowerCase().includes(this.searchTerm.toLowerCase()) ||
                order.user_name.toLowerCase().includes(this.searchTerm.toLowerCase());
            
            const matchesStatus = this.statusFilter === 'all' ||
                (this.statusFilter === 'unpaid' && order.isUnpaid) ||
                (this.statusFilter === 'waiting' && order.isWaiting) ||
                (this.statusFilter === 'processing' && order.isProcessing) ||
                (this.statusFilter === 'shipped' && order.isShipped) ||
                (this.statusFilter === 'completed' && order.isCompleted) ||
                (this.statusFilter === 'cancelled' && order.isCancelled);

            return matchesSearch && matchesStatus;
        });
    }
}" class="space-y-6 font-sans">
    
    {{-- Filters and Search Bar --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-white p-4 rounded-2xl border border-brandOutline-soft/30 shadow-sm reveal">
        {{-- Search Input --}}
        <div class="relative flex-1 max-w-md">
            <svg class="absolute left-3 top-3 w-4 h-4 text-brandText-muted" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
            <input
                type="text"
                placeholder="Cari nomor pesanan atau nama pelanggan..."
                x-model="searchTerm"
                class="w-full pl-9 pr-4 py-2 text-sm border border-brandOutline rounded-xl bg-cream-light/5 text-brandText focus:outline-none focus:ring-2 focus:ring-primary-soft focus:border-primary transition-all"
            />
        </div>

        {{-- Filters count indicator --}}
        <div class="text-xs text-brandText-muted font-semibold">
            Menampilkan <span x-text="filteredOrders.length"></span> dari <span x-text="orders.length"></span> pesanan
        </div>
    </div>

    {{-- Tabs --}}
    <div class="border-b border-brandOutline flex space-x-6 overflow-x-auto whitespace-nowrap scrollbar-none pb-0.5 reveal" data-delay="100">
        <button
            @click="statusFilter = 'all'"
            class="pb-3 text-sm font-semibold transition-all border-b-2 focus:outline-none"
            :class="statusFilter === 'all' ? 'border-primary text-primary' : 'border-transparent text-brandText-muted hover:text-primary'"
        >
            Semua
        </button>
        <button
            @click="statusFilter = 'unpaid'"
            class="pb-3 text-sm font-semibold transition-all border-b-2 focus:outline-none"
            :class="statusFilter === 'unpaid' ? 'border-primary text-primary' : 'border-transparent text-brandText-muted hover:text-primary'"
        >
            Belum Bayar
        </button>
        <button
            @click="statusFilter = 'waiting'"
            class="pb-3 text-sm font-semibold transition-all border-b-2 focus:outline-none"
            :class="statusFilter === 'waiting' ? 'border-primary text-primary' : 'border-transparent text-brandText-muted hover:text-primary'"
        >
            Verifikasi Pembayaran
        </button>
        <button
            @click="statusFilter = 'processing'"
            class="pb-3 text-sm font-semibold transition-all border-b-2 focus:outline-none"
            :class="statusFilter === 'processing' ? 'border-primary text-primary' : 'border-transparent text-brandText-muted hover:text-primary'"
        >
            Sedang Diproses
        </button>
        <button
            @click="statusFilter = 'shipped'"
            class="pb-3 text-sm font-semibold transition-all border-b-2 focus:outline-none"
            :class="statusFilter === 'shipped' ? 'border-primary text-primary' : 'border-transparent text-brandText-muted hover:text-primary'"
        >
            Dikirim
        </button>
        <button
            @click="statusFilter = 'completed'"
            class="pb-3 text-sm font-semibold transition-all border-b-2 focus:outline-none"
            :class="statusFilter === 'completed' ? 'border-primary text-primary' : 'border-transparent text-brandText-muted hover:text-primary'"
        >
            Selesai
        </button>
        <button
            @click="statusFilter = 'cancelled'"
            class="pb-3 text-sm font-semibold transition-all border-b-2 focus:outline-none"
            :class="statusFilter === 'cancelled' ? 'border-primary text-primary' : 'border-transparent text-brandText-muted hover:text-primary'"
        >
            Dibatalkan
        </button>
    </div>

    {{-- Table Area --}}
    <div class="bg-white border border-brandOutline-soft/30 rounded-2xl shadow-sm overflow-hidden reveal" data-delay="150">
        {{-- Empty State --}}
        <div x-show="filteredOrders.length === 0" style="display: none;" class="p-12 text-center">
            <svg class="mx-auto h-12 w-12 text-brandText-muted/40" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
            </svg>
            <h3 class="mt-4 font-serif text-lg font-bold text-primary">Tidak Ada Transaksi</h3>
            <p class="mt-2 text-xs text-brandText-muted leading-relaxed">Tidak ada pesanan masuk yang cocok dengan pencarian atau status terpilih.</p>
        </div>

        <div class="overflow-x-auto" x-show="filteredOrders.length > 0">
            <table class="w-full text-left border-collapse text-sm">
                <thead>
                    <tr class="bg-cream/15 border-b border-brandOutline-soft/30 text-xs font-bold text-brandText-muted uppercase tracking-wider">
                        <th class="px-6 py-4">No. Pesanan</th>
                        <th class="px-6 py-4">Pelanggan</th>
                        <th class="px-6 py-4">Tanggal Order</th>
                        <th class="px-6 py-4">Pengiriman</th>
                        <th class="px-6 py-4">Total Tagihan</th>
                        <th class="px-6 py-4 text-center">Status</th>
                        <th class="px-6 py-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-brandOutline-soft/20 text-brandText">
                    <template x-for="order in filteredOrders" :key="order.id">
                        <tr class="hover:bg-cream/5 transition-colors">
                            {{-- Order Number --}}
                            <td class="px-6 py-4 font-mono font-bold text-primary" x-text="'#' + order.order_number"></td>

                            {{-- Customer --}}
                            <td class="px-6 py-4">
                                <div class="font-semibold" x-text="order.recipient_name"></div>
                                <div class="text-[10px] text-brandText-muted flex items-center mt-0.5">
                                    <svg class="w-3 h-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                    <span x-text="order.user_name"></span>
                                </div>
                            </td>

                            {{-- Order Date --}}
                            <td class="px-6 py-4 text-xs text-brandText-muted">
                                <span class="flex items-center">
                                    <svg class="w-3.5 h-3.5 mr-1 text-gold" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    <span x-text="order.order_date"></span>
                                </span>
                            </td>

                            {{-- Delivery Date --}}
                            <td class="px-6 py-4 text-xs text-brandText-muted font-semibold">
                                <span class="flex items-center">
                                    <svg class="w-3.5 h-3.5 mr-1 text-primary/75" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    <span x-text="order.delivery_date"></span>
                                </span>
                            </td>

                            {{-- Total Price --}}
                            <td class="px-6 py-4 font-bold text-primary" x-text="order.total"></td>

                            {{-- Status Badge --}}
                            <td class="px-6 py-4 text-center">
                                <span class="inline-block px-2.5 py-0.5 border rounded-full text-[10px] font-bold tracking-wide uppercase" :class="order.badgeClass" x-text="order.statusLabel"></span>
                            </td>

                            {{-- Action --}}
                            <td class="px-6 py-4 text-center">
                                <a :href="order.showUrl" class="p-1.5 text-brandText-muted hover:text-primary hover:bg-cream/25 rounded-lg transition-all inline-block">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                </a>
                            </td>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@extends('layouts.public')

@section('title', 'Riwayat Pesanan | Little Joy Jakarta')

@section('content')
@php
    // Indonesian translations for order status helper
    function getStatusInfo($status) {
        switch ($status) {
            case 'pending_payment':
                return ['text' => 'Belum Bayar', 'style' => 'bg-amber-50 border-amber-200 text-amber-800'];
            case 'waiting_verification':
                return ['text' => 'Menunggu Verifikasi', 'style' => 'bg-blue-50 border-blue-200 text-blue-800'];
            case 'paid':
                return ['text' => 'Sudah Bayar', 'style' => 'bg-indigo-50 border-indigo-200 text-indigo-800'];
            case 'processing':
                return ['text' => 'Sedang Diproses', 'style' => 'bg-indigo-50 border-indigo-200 text-indigo-800'];
            case 'ready':
                return ['text' => 'Siap Dikirim', 'style' => 'bg-indigo-50 border-indigo-200 text-indigo-800'];
            case 'shipped':
                return ['text' => 'Sedang Dikirim', 'style' => 'bg-purple-50 border-purple-200 text-purple-800'];
            case 'completed':
                return ['text' => 'Selesai', 'style' => 'bg-green-50 border-green-200 text-green-800'];
            case 'cancelled':
                return ['text' => 'Dibatalkan', 'style' => 'bg-red-50 border-red-200 text-red-800'];
            case 'rejected':
                return ['text' => 'Pembayaran Ditolak', 'style' => 'bg-red-50 border-red-200 text-red-800'];
            default:
                return ['text' => $status, 'style' => 'bg-gray-50 border-gray-200 text-gray-800'];
        }
    }
@endphp

<div class="bg-cream-light/30 min-h-screen py-12 font-sans" x-data="{ activeTab: 'all' }">
    <div class="max-w-5xl mx-auto px-4">
        {{-- Header --}}
        <div class="mb-8 reveal">
            <h1 class="font-serif text-3xl font-bold text-primary tracking-tight">
                Riwayat Pesanan
            </h1>
            <p class="text-sm text-brandText-muted mt-1">
                Pantau status pembayaran dan pengiriman rangkaian bunga Anda di sini.
            </p>
        </div>

        {{-- Tabs navigation --}}
        <div class="mb-6 border-b border-brandOutline-soft overflow-x-auto whitespace-nowrap scrollbar-none flex space-x-6 reveal" data-delay="100">
            <button
                @click="activeTab = 'all'"
                class="pb-3 text-sm font-semibold transition-all border-b-2 focus:outline-none"
                :class="activeTab === 'all' ? 'border-primary text-primary' : 'border-transparent text-brandText-muted hover:text-primary'"
            >
                Semua
            </button>
            <button
                @click="activeTab = 'unpaid'"
                class="pb-3 text-sm font-semibold transition-all border-b-2 focus:outline-none"
                :class="activeTab === 'unpaid' ? 'border-primary text-primary' : 'border-transparent text-brandText-muted hover:text-primary'"
            >
                Belum Bayar
            </button>
            <button
                @click="activeTab = 'waiting'"
                class="pb-3 text-sm font-semibold transition-all border-b-2 focus:outline-none"
                :class="activeTab === 'waiting' ? 'border-primary text-primary' : 'border-transparent text-brandText-muted hover:text-primary'"
            >
                Menunggu Verifikasi
            </button>
            <button
                @click="activeTab = 'processing'"
                class="pb-3 text-sm font-semibold transition-all border-b-2 focus:outline-none"
                :class="activeTab === 'processing' ? 'border-primary text-primary' : 'border-transparent text-brandText-muted hover:text-primary'"
            >
                Diproses
            </button>
            <button
                @click="activeTab = 'shipped'"
                class="pb-3 text-sm font-semibold transition-all border-b-2 focus:outline-none"
                :class="activeTab === 'shipped' ? 'border-primary text-primary' : 'border-transparent text-brandText-muted hover:text-primary'"
            >
                Dikirim
            </button>
            <button
                @click="activeTab = 'completed'"
                class="pb-3 text-sm font-semibold transition-all border-b-2 focus:outline-none"
                :class="activeTab === 'completed' ? 'border-primary text-primary' : 'border-transparent text-brandText-muted hover:text-primary'"
            >
                Selesai
            </button>
            <button
                @click="activeTab = 'cancelled'"
                class="pb-3 text-sm font-semibold transition-all border-b-2 focus:outline-none"
                :class="activeTab === 'cancelled' ? 'border-primary text-primary' : 'border-transparent text-brandText-muted hover:text-primary'"
            >
                Dibatalkan
            </button>
        </div>

        {{-- Orders List --}}
        @if(empty($orders) || count($orders) === 0)
            <div class="bg-white border border-brandOutline-soft/30 rounded-2xl p-12 shadow-sm text-center reveal" data-delay="150">
                <div class="w-16 h-16 bg-primary-soft/10 text-primary rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                    </svg>
                </div>
                <h3 class="font-serif text-lg font-bold text-primary mb-2">Tidak Ada Pesanan</h3>
                <p class="text-xs text-brandText-muted leading-relaxed">
                    Anda belum memiliki riwayat transaksi pemesanan bunga saat ini.
                </p>
                <div class="mt-8 flex justify-center">
                    <a href="{{ route('catalogue.index') }}" class="inline-flex items-center justify-center font-sans font-semibold rounded-lg transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 active:scale-[0.98] bg-primary text-white hover:bg-primary-dark focus:ring-primary-muted shadow-sm px-4 py-2 text-sm gap-2">
                        Mulai Belanja
                    </a>
                </div>
            </div>
        @else
            <div class="space-y-6 reveal" data-delay="150">
                @php $hasVisibleOrders = false; @endphp
                @foreach($orders as $order)
                    @php
                        $status = $order->order_status;
                        $isUnpaid = in_array($status, ['pending_payment', 'rejected']);
                        $isWaiting = $status === 'waiting_verification';
                        $isProcessing = in_array($status, ['paid', 'processing', 'ready']);
                        $isShipped = $status === 'shipped';
                        $isCompleted = $status === 'completed';
                        $isCancelled = $status === 'cancelled';
                        $statusInfo = getStatusInfo($status);
                        
                        $items = $order->items;
                        $firstItem = $items->first() ?? null;
                        $extraItemsCount = count($items) - 1;
                        
                        $orderDateFormatted = Carbon\Carbon::parse($order->order_date)->translatedFormat('d F Y');
                    @endphp

                    <div 
                        x-show="activeTab === 'all' || 
                                (activeTab === 'unpaid' && {{ $isUnpaid ? 'true' : 'false' }}) || 
                                (activeTab === 'waiting' && {{ $isWaiting ? 'true' : 'false' }}) || 
                                (activeTab === 'processing' && {{ $isProcessing ? 'true' : 'false' }}) || 
                                (activeTab === 'shipped' && {{ $isShipped ? 'true' : 'false' }}) || 
                                (activeTab === 'completed' && {{ $isCompleted ? 'true' : 'false' }}) || 
                                (activeTab === 'cancelled' && {{ $isCancelled ? 'true' : 'false' }})"
                        class="bg-white border border-brandOutline-soft/30 rounded-2xl p-6 shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-6 hover:shadow-md transition-shadow"
                    >
                        {{-- Left Side: Order Info & Product Preview --}}
                        <div class="flex-1 space-y-4">
                            {{-- Top Meta info --}}
                            <div class="flex flex-wrap items-center gap-3 text-xs">
                                <span class="font-mono font-bold text-primary">
                                    #{{ $order->order_number }}
                                </span>
                                <span class="text-brandText-muted flex items-center">
                                    <svg class="w-3.5 h-3.5 mr-1 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    {{ $orderDateFormatted }}
                                </span>
                                <span class="px-2.5 py-0.5 border rounded-full text-[10px] font-bold tracking-wide uppercase {{ $statusInfo['style'] }}">
                                    {{ $statusInfo['text'] }}
                                </span>
                            </div>

                            {{-- Product Preview Row --}}
                            @if($firstItem)
                                <div class="flex items-center space-x-4">
                                    <div class="w-16 h-16 rounded-xl overflow-hidden border border-brandOutline-soft bg-cream-light/20 flex-shrink-0 flex items-center justify-center">
                                        @if($firstItem->product && $firstItem->product->image_path)
                                            <img
                                                src="/storage/{{ $firstItem->product->image_path }}"
                                                alt="{{ $firstItem->product_name }}"
                                                class="w-full h-full object-cover"
                                            />
                                        @else
                                            <span class="text-xl text-primary/30 font-serif">✿</span>
                                        @endif
                                    </div>
                                    <div>
                                        <h4 class="font-serif text-sm font-bold text-primary leading-tight">
                                            {{ $firstItem->product_name }}
                                        </h4>
                                        <p class="text-xs text-brandText-muted mt-1">
                                            {{ $firstItem->quantity }} barang x Rp {{ number_format($firstItem->unit_price, 0, ',', '.') }}
                                        </p>
                                        @if($extraItemsCount > 0)
                                            <p class="text-[10px] text-brandText-muted mt-1 font-semibold">
                                                + {{ $extraItemsCount }} rangkaian bunga lainnya
                                            </p>
                                        @endif
                                    </div>
                                </div>
                            @endif
                        </div>

                        {{-- Right Side: Total Price & Actions --}}
                        <div class="flex flex-row md:flex-col items-center md:items-end justify-between md:justify-center gap-4 pt-4 md:pt-0 border-t md:border-t-0 border-brandOutline-soft/30">
                            <div class="text-left md:text-right">
                                <p class="text-xs text-brandText-muted">Total Belanja</p>
                                <p class="font-serif text-base font-bold text-primary mt-0.5">
                                    Rp {{ number_format($order->total, 0, ',', '.') }}
                                </p>
                            </div>

                            <div class="flex gap-2.5">
                                @if(in_array($order->order_status, ['pending_payment', 'rejected']))
                                    <a href="{{ route('customer.payments.create', $order->order_number) }}" class="inline-flex items-center justify-center font-sans font-semibold rounded-lg transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 active:scale-[0.98] bg-primary text-white hover:bg-primary-dark focus:ring-primary-muted shadow-sm text-xs py-2 px-3 gap-1">
                                        <svg class="w-3.5 h-3.5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                                        </svg>
                                        Bayar
                                    </a>
                                @endif

                                <a href="{{ route('customer.orders.show', $order->order_number) }}" class="inline-flex items-center justify-center font-sans font-semibold rounded-lg transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 active:scale-[0.98] border border-brandOutline-soft bg-transparent text-primary hover:bg-brandSurface-low hover:border-primary focus:ring-primary-soft text-xs py-2 px-3 gap-1">
                                    <svg class="w-3.5 h-3.5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                    Detail
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
@endsection

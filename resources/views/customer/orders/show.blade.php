@extends('layouts.public')

@section('title', 'Detail Pesanan #' . $order->order_number . ' | Little Joy Jakarta')

@section('content')
@php
    // Status translations and styling helper
    function getDetailStatusInfo($status) {
        switch ($status) {
            case 'pending_payment':
                return ['text' => 'Menunggu Pembayaran', 'color' => 'text-amber-700 bg-amber-50 border-amber-200'];
            case 'waiting_verification':
                return ['text' => 'Menunggu Verifikasi', 'color' => 'text-blue-700 bg-blue-50 border-blue-200'];
            case 'paid':
                return ['text' => 'Pembayaran Diterima', 'color' => 'text-indigo-700 bg-indigo-50 border-indigo-200'];
            case 'processing':
                return ['text' => 'Sedang Diproses', 'color' => 'text-indigo-700 bg-indigo-50 border-indigo-200'];
            case 'ready':
                return ['text' => 'Siap Dikirim', 'color' => 'text-indigo-700 bg-indigo-50 border-indigo-200'];
            case 'shipped':
                return ['text' => 'Dalam Pengiriman', 'color' => 'text-purple-700 bg-purple-50 border-purple-200'];
            case 'completed':
                return ['text' => 'Selesai', 'color' => 'text-green-700 bg-green-50 border-green-200'];
            case 'cancelled':
                return ['text' => 'Dibatalkan', 'color' => 'text-red-700 bg-red-50 border-red-200'];
            case 'rejected':
                return ['text' => 'Pembayaran Ditolak', 'color' => 'text-red-700 bg-red-50 border-red-200'];
            default:
                return ['text' => $status, 'color' => 'text-gray-700 bg-gray-50 border-gray-200'];
        }
    }

    $statusInfo = getDetailStatusInfo($order->order_status);

    $orderDateFormatted = Carbon\Carbon::parse($order->order_date)->translatedFormat('d F Y H:i');
    $deliveryDateFormatted = Carbon\Carbon::parse($order->delivery_date)->translatedFormat('d F Y');

    // Helper for timeline status Indonesian labels
    function getTimelineLabel($status) {
        switch ($status) {
            case 'pending_payment': return 'Pesanan Dibuat';
            case 'waiting_verification': return 'Bukti Pembayaran Diunggah';
            case 'paid': return 'Pembayaran Berhasil Diverifikasi';
            case 'processing': return 'Pesanan Mulai Dirangkai';
            case 'ready': return 'Bunga Siap Dikirim';
            case 'shipped': return 'Pesanan Diserahkan ke Kurir';
            case 'completed': return 'Pesanan Selesai';
            case 'cancelled': return 'Pesanan Dibatalkan';
            case 'rejected': return 'Bukti Transfer Ditolak';
            default: return $status;
        }
    }
@endphp

<div class="bg-cream-light/30 min-h-screen py-12 font-sans">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        {{-- Header bar --}}
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8 reveal">
            <div>
                <a
                    href="{{ route('customer.orders.index') }}"
                    class="inline-flex items-center text-xs font-semibold text-brandText-muted hover:text-primary transition-colors mb-3 group"
                >
                    <svg class="w-3.5 h-3.5 mr-1 group-hover:-translate-x-0.5 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Kembali ke Riwayat Pesanan
                </a>
                <div class="flex flex-wrap items-center gap-3">
                    <h1 class="font-serif text-3xl font-bold text-primary tracking-tight">
                        Pesanan #{{ $order->order_number }}
                    </h1>
                    <span class="px-3 py-0.5 border rounded-full text-xs font-bold uppercase tracking-wider {{ $statusInfo['color'] }}">
                        {{ $statusInfo['text'] }}
                    </span>
                </div>
                <p class="text-xs text-brandText-muted mt-1.5">
                    Dibuat pada: <span class="font-semibold text-brandText">{{ $orderDateFormatted }} WIB</span>
                </p>
            </div>

            {{-- Order action buttons --}}
            <div class="flex flex-wrap gap-2.5">
                @if(in_array($order->order_status, ['pending_payment', 'rejected']))
                    <a href="{{ route('customer.payments.create', $order->order_number) }}" class="inline-flex items-center justify-center font-sans font-semibold rounded-lg transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 active:scale-[0.98] bg-primary text-white hover:bg-primary-dark focus:ring-primary-muted shadow-sm text-xs py-2 px-4 gap-1.5 shadow-md">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                        </svg>
                        Unggah Pembayaran
                    </a>
                    
                    <form action="{{ route('customer.orders.cancel', $order->order_number) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin membatalkan pesanan ini? Aksi ini tidak dapat dibatalkan.')" class="inline-block">
                        @csrf
                        <button
                            type="submit"
                            class="inline-flex items-center justify-center font-sans font-semibold rounded-lg transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 active:scale-[0.98] border border-red-200 bg-transparent text-red-600 hover:bg-red-50 text-xs py-2 px-4 gap-1.5"
                        >
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Batalkan Pesanan
                        </button>
                    </form>
                @endif
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
            {{-- Left Column: Timeline, Delivery, Card Message --}}
            <div class="lg:col-span-2 space-y-6 reveal" data-delay="100">
                
                {{-- 1. Status Timeline --}}
                <div class="bg-white border border-brandOutline-soft/30 rounded-2xl p-6 shadow-sm">
                    <h3 class="font-serif text-lg font-bold text-primary mb-6 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Linimasa Status Pesanan
                    </h3>

                    <div class="relative pl-6 border-l-2 border-dashed border-brandOutline-soft/60 space-y-8 ml-3">
                        @foreach($order->histories as $index => $log)
                            @php
                                $isLast = $index === count($order->histories) - 1;
                                $logDate = Carbon\Carbon::parse($log->created_at)->translatedFormat('d M Y H:i');
                            @endphp

                            <div class="relative">
                                {{-- Timeline Node Icon --}}
                                <span class="absolute -left-[31px] top-1 w-4 h-4 rounded-full border-2 bg-white {{ $isLast ? 'border-primary ring-4 ring-primary-soft/35 bg-primary' : 'border-brandOutline' }}"></span>
                                
                                <div>
                                    <h4 class="text-sm font-bold {{ $isLast ? 'text-primary' : 'text-brandText' }}">
                                        {{ getTimelineLabel($log->current_status) }}
                                    </h4>
                                    <p class="text-[10px] text-brandText-muted mt-0.5">
                                        {{ $logDate }} WIB {{$log->actor ? 'oleh ' . $log->actor->name : ''}}
                                    </p>
                                    @if($log->note)
                                        <p class="text-xs text-brandText-muted/90 bg-cream/10 border border-brandOutline-soft/10 rounded-lg p-2.5 mt-2 max-w-lg leading-relaxed italic">
                                            &ldquo;{{ $log->note }}&rdquo;
                                        </p>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- 2. Recipient & Delivery Info --}}
                <div class="bg-white border border-brandOutline-soft/30 rounded-2xl p-6 shadow-sm">
                    <h3 class="font-serif text-lg font-bold text-primary mb-5 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        Informasi Pengiriman
                    </h3>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 text-sm">
                        <div class="space-y-3">
                            <div>
                                <p class="text-[10px] font-bold text-brandText-muted uppercase tracking-wider">Nama Penerima</p>
                                <p class="font-semibold text-brandText mt-0.5 flex items-center">
                                    <svg class="w-3.5 h-3.5 mr-1.5 text-primary/60" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                    {{ $order->recipient_name }}
                                </p>
                            </div>
                            
                            <div>
                                <p class="text-[10px] font-bold text-brandText-muted uppercase tracking-wider">No. Telepon Penerima</p>
                                <p class="font-semibold text-brandText mt-0.5 flex items-center">
                                    <svg class="w-3.5 h-3.5 mr-1.5 text-primary/60" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.94.725l.548 2.2a1 1 0 01-.321.988l-1.305.98a10.582 10.582 0 004.872 4.872l.98-1.305a1 1 0 01.988-.321l2.2.548a1 1 0 01.725.94V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                    </svg>
                                    {{ $order->recipient_phone }}
                                </p>
                            </div>
                        </div>

                        <div class="space-y-3">
                            <div>
                                <p class="text-[10px] font-bold text-brandText-muted uppercase tracking-wider">Tanggal Pengantaran</p>
                                <p class="font-semibold text-brandText mt-0.5 flex items-center">
                                    <svg class="w-3.5 h-3.5 mr-1.5 text-primary/60" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    {{ $deliveryDateFormatted }}
                                </p>
                            </div>

                            <div>
                                <p class="text-[10px] font-bold text-brandText-muted uppercase tracking-wider">Alamat Lengkap Tujuan</p>
                                <p class="text-brandText mt-0.5 leading-relaxed bg-cream-light/10 border border-brandOutline-soft/20 p-2.5 rounded-xl">
                                    {{ $order->delivery_address }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- 3. Greeting Card Rendering --}}
                @if($order->greeting_message)
                    <div class="bg-white border border-brandOutline-soft/30 rounded-2xl p-6 shadow-sm">
                        <h3 class="font-serif text-lg font-bold text-primary mb-5 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                            </svg>
                            Kartu Ucapan Gratis
                        </h3>

                        {{-- Virtual Card Rendering --}}
                        <div class="max-w-md bg-cream/10 border-2 border-double border-gold/45 p-6 rounded-2xl shadow-inner relative overflow-hidden mx-auto text-center font-serif flex flex-col items-center justify-center min-h-[160px]">
                            <span class="text-lg text-gold mb-2">✿</span>
                            <p class="text-sm text-primary/80 italic leading-relaxed whitespace-pre-wrap px-4 font-medium">
                                &ldquo;{{ $order->greeting_message }}&rdquo;
                            </p>
                            <span class="text-xs tracking-[0.2em] text-gold uppercase mt-4 block">
                                Little Joy Jakarta
                            </span>
                        </div>
                    </div>
                @endif

                {{-- 4. Customer Note --}}
                @if($order->customer_note)
                    <div class="bg-white border border-brandOutline-soft/30 rounded-2xl p-6 shadow-sm">
                        <h3 class="font-serif text-lg font-bold text-primary mb-3 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            Catatan Operasional untuk Florist
                        </h3>
                        <p class="text-xs text-brandText leading-relaxed italic bg-cream-light/10 border border-brandOutline-soft/20 p-3 rounded-xl">
                            &ldquo;{{ $order->customer_note }}&rdquo;
                        </p>
                    </div>
                @endif
            </div>

            {{-- Right Column: Invoice items, totals, payment bank manual instructions --}}
            <div class="space-y-6 reveal" data-delay="200">
                
                {{-- 1. Items invoice --}}
                <div class="bg-white border border-brandOutline-soft/30 rounded-2xl shadow-sm p-6">
                    <h3 class="font-serif text-lg font-bold text-primary mb-4 pb-3 border-b border-brandOutline-soft/30">
                        Rangkaian Bunga Dipesan
                    </h3>

                    <div class="divide-y divide-brandOutline-soft/20 max-h-80 overflow-y-auto pr-1 font-sans">
                        @foreach($order->items as $item)
                            <div class="py-3 flex justify-between items-start gap-4 text-xs">
                                <div class="flex-1 space-y-0.5">
                                    <p class="font-bold text-primary">{{ $item->product_name }}</p>
                                    <p class="text-brandText-muted">
                                        {{ $item->quantity }} x Rp {{ number_format($item->unit_price, 0, ',', '.') }}
                                    </p>
                                </div>
                                <span class="font-bold text-brandText flex-shrink-0">
                                    Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                                </span>
                            </div>
                        @endforeach
                    </div>

                    {{-- Totals cost breakdown --}}
                    <div class="space-y-3 text-sm pt-4 border-t border-brandOutline-soft/30 font-sans">
                        <div class="flex justify-between text-brandText-muted text-xs">
                            <span>Subtotal</span>
                            <span class="font-semibold text-brandText">
                                Rp {{ number_format($order->subtotal, 0, ',', '.') }}
                            </span>
                        </div>
                        <div class="flex justify-between text-brandText-muted text-xs">
                            <span>Ongkos Kirim (Flat)</span>
                            <span class="font-semibold text-brandText">
                                Rp {{ number_format($order->delivery_fee, 0, ',', '.') }}
                            </span>
                        </div>
                        <div class="pt-3 border-t border-brandOutline-soft/30 flex justify-between items-center font-bold text-base text-primary">
                            <span>Total Pembayaran</span>
                            <span class="font-serif text-lg">
                                Rp {{ number_format($order->total, 0, ',', '.') }}
                            </span>
                        </div>
                    </div>
                </div>

                {{-- 2. Bank Manual Instructions --}}
                @if(in_array($order->order_status, ['pending_payment', 'rejected']))
                    <div class="bg-white border border-brandOutline-soft/30 rounded-2xl shadow-sm p-6 space-y-4" x-data="{ copyText(text) { navigator.clipboard.writeText(text); alert('Disalin ke papan klip!'); } }">
                        <h3 class="font-serif text-base font-bold text-primary border-b border-brandOutline-soft/30 pb-2 flex items-center">
                            <svg class="w-4 h-4 mr-2 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z" />
                            </svg>
                            Pilihan Rekening Transfer
                        </h3>
                        <p class="text-xs text-brandText-muted leading-relaxed">
                            Silakan transfer sebesar <span class="font-bold text-primary">Rp {{ number_format($order->total, 0, ',', '.') }}</span> ke rekening berikut, lalu unggah struk bukti pembayarannya:
                        </p>
                        
                        <div class="space-y-2 text-xs">
                            <div class="p-3 border border-brandOutline-soft/40 rounded-xl bg-cream-light/10 flex justify-between items-center">
                                <div>
                                    <p class="font-bold text-primary">BCA Jakarta</p>
                                    <p class="font-mono font-semibold mt-0.5">123-456-7890</p>
                                    <p class="text-[10px] text-brandText-muted">a/n Little Joy Jakarta</p>
                                </div>
                                <button type="button" @click="copyText('1234567890')" class="text-[10px] font-bold text-primary hover:underline">Salin</button>
                            </div>
                            <div class="p-3 border border-brandOutline-soft/40 rounded-xl bg-cream-light/10 flex justify-between items-center">
                                <div>
                                    <p class="font-bold text-primary">Mandiri Jakarta</p>
                                    <p class="font-mono font-semibold mt-0.5">098-765-4321</p>
                                    <p class="text-[10px] text-brandText-muted">a/n Little Joy Jakarta</p>
                                </div>
                                <button type="button" @click="copyText('0987654321')" class="text-[10px] font-bold text-primary hover:underline">Salin</button>
                            </div>
                        </div>
                    </div>
                @endif

                {{-- 3. Eager Loaded Payment details --}}
                @if($order->payment)
                    <div class="bg-white border border-brandOutline-soft/30 rounded-2xl shadow-sm p-6 space-y-4">
                        <h3 class="font-serif text-base font-bold text-primary border-b border-brandOutline-soft/30 pb-2">
                            Informasi Bukti Transfer
                        </h3>

                        <div class="space-y-3 text-xs leading-relaxed font-sans">
                            <div class="flex justify-between">
                                <span class="text-brandText-muted">Bank Pengirim:</span>
                                <span class="font-bold text-brandText">{{ $order->payment->sender_bank }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-brandText-muted">Pemilik Rekening:</span>
                                <span class="font-bold text-brandText">{{ $order->payment->account_holder_name }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-brandText-muted">Jumlah Ditransfer:</span>
                                <span class="font-bold text-primary">Rp {{ number_format($order->payment->amount, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-brandText-muted">Tanggal Transfer:</span>
                                <span class="font-bold text-brandText">
                                    {{ Carbon\Carbon::parse($order->payment->transfer_date)->translatedFormat('d F Y') }}
                                </span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-brandText-muted">Status Verifikasi:</span>
                                <span class="font-bold uppercase text-[10px] text-primary">{{ $order->payment->verification_status }}</span>
                            </div>

                            {{-- Zoomable Small Receipt preview --}}
                            <div class="pt-2">
                                <p class="text-brandText-muted mb-2 font-semibold">Lampiran Gambar Resi:</p>
                                <div class="w-full aspect-[4/3] rounded-xl overflow-hidden border border-brandOutline bg-cream-light/10 relative group">
                                    <img
                                        src="/storage/{{ $order->payment->proof_path }}"
                                        alt="Resi Transfer"
                                        class="w-full h-full object-cover cursor-pointer hover:scale-105 transition-transform"
                                        onclick="window.open('/storage/{{ $order->payment->proof_path }}', '_blank')"
                                    />
                                </div>
                                <p class="text-[9px] text-brandText-muted/80 mt-1.5 text-center italic">Klik gambar untuk melihat resolusi penuh.</p>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

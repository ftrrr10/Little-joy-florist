@extends('layouts.dashboard')

@section('title', 'Pesanan #' . $order->order_number)

@section('content')
@php
    function getOperatorStatusBadge($status) {
        switch ($status) {
            case 'pending_payment': return 'bg-amber-50 border-amber-200 text-amber-800';
            case 'waiting_verification': return 'bg-blue-50 border-blue-200 text-blue-800';
            case 'paid': return 'bg-green-50 border-green-200 text-green-800';
            case 'processing': return 'bg-indigo-50 border-indigo-200 text-indigo-800';
            case 'ready': return 'bg-indigo-50 border-indigo-200 text-indigo-800';
            case 'shipped': return 'bg-purple-50 border-purple-200 text-purple-800';
            case 'completed': return 'bg-green-50 border-green-200 text-green-800';
            case 'cancelled': return 'bg-red-50 border-red-200 text-red-800';
            case 'rejected': return 'bg-red-50 border-red-200 text-red-800';
            default: return 'bg-gray-50 border-gray-200 text-gray-800';
        }
    }

    function getOperatorStatusLabel($status) {
        switch ($status) {
            case 'pending_payment': return 'Belum Bayar';
            case 'waiting_verification': return 'Menunggu Verifikasi';
            case 'paid': return 'Lunas (Menunggu Rangkaian)';
            case 'processing': return 'Sedang Dirangkai';
            case 'ready': return 'Siap Dikirim';
            case 'shipped': return 'Sedang Dikirim';
            case 'completed': return 'Selesai';
            case 'cancelled': return 'Dibatalkan';
            case 'rejected': return 'Pembayaran Ditolak';
            default: return $status;
        }
    }

    function getAllowedTransitions($status) {
        switch ($status) {
            case 'paid': return [['value' => 'processing', 'label' => 'Mulai Dirangkai (Processing)']];
            case 'processing': return [['value' => 'ready', 'label' => 'Rangkaian Selesai & Siap (Ready)']];
            case 'ready': return [['value' => 'shipped', 'label' => 'Kirimkan Bunga (Shipped)']];
            case 'shipped': return [['value' => 'completed', 'label' => 'Pesanan Diterima (Completed)']];
            default: return [];
        }
    }

    $allowedTransitions = getAllowedTransitions($order->order_status);
    $orderDateFormatted = Carbon\Carbon::parse($order->order_date)->translatedFormat('d F Y H:i');
    $deliveryDateFormatted = Carbon\Carbon::parse($order->delivery_date)->translatedFormat('d F Y');
@endphp

<div class="space-y-6 font-sans">
    {{-- Back button and Meta summary --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 reveal">
        <a
            href="{{ route('operator.orders.index') }}"
            class="inline-flex items-center text-xs font-semibold text-brandText-muted hover:text-primary transition-colors group"
        >
            <svg class="w-3.5 h-3.5 mr-1 group-hover:-translate-x-0.5 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Kembali ke Kelola Pesanan
        </a>
        
        <div class="flex items-center space-x-3">
            <span class="px-3 py-1 border rounded-full text-xs font-bold uppercase tracking-wider {{ getOperatorStatusBadge($order->order_status) }}">
                {{ getOperatorStatusLabel($order->order_status) }}
            </span>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-start">
        {{-- Left & Middle Column: General Details, Items, Timeline, Card Message --}}
        <div class="lg:col-span-2 space-y-6 reveal" data-delay="100">
            
            {{-- 1. General Billing & Customer Information --}}
            <div class="bg-white border border-brandOutline-soft/30 rounded-2xl p-6 shadow-sm">
                <h3 class="font-serif text-base font-bold text-primary mb-4 pb-2 border-b border-brandOutline-soft/30 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                    Rincian Pelanggan & Pemesanan
                </h3>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 text-sm">
                    <div class="space-y-3">
                        <div>
                            <p class="text-[10px] font-bold text-brandText-muted uppercase tracking-wider">Pemesan Akun</p>
                            <p class="font-semibold text-brandText mt-0.5">{{ $order->user->name ?? 'Guest' }}</p>
                            <p class="text-xs text-brandText-muted">{{ $order->user->email ?? '' }}</p>
                        </div>
                        <div>
                            <p class="text-[10px] font-bold text-brandText-muted uppercase tracking-wider">Tanggal Order</p>
                            <p class="font-semibold text-brandText mt-0.5">{{ $orderDateFormatted }} WIB</p>
                        </div>
                    </div>

                    <div class="space-y-3">
                        <div>
                            <p class="text-[10px] font-bold text-brandText-muted uppercase tracking-wider">No. Telepon Akun</p>
                            <p class="font-semibold text-brandText mt-0.5">{{ $order->user->phone ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="text-[10px] font-bold text-brandText-muted uppercase tracking-wider">Metode Pembayaran</p>
                            <p class="font-semibold text-primary mt-0.5">Transfer Bank Manual</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- 2. Recipient & Delivery Address --}}
            <div class="bg-white border border-brandOutline-soft/30 rounded-2xl p-6 shadow-sm">
                <h3 class="font-serif text-base font-bold text-primary mb-4 pb-2 border-b border-brandOutline-soft/30 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    Rincian Penerima & Pengiriman
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
                        <div>
                            <p class="text-[10px] font-bold text-brandText-muted uppercase tracking-wider">Tanggal Pengantaran</p>
                            <p class="font-bold text-primary mt-0.5 flex items-center">
                                <svg class="w-3.5 h-3.5 mr-1.5 text-primary/60" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                {{ $deliveryDateFormatted }}
                            </p>
                        </div>
                    </div>

                    <div class="space-y-3">
                        <div>
                            <p class="text-[10px] font-bold text-brandText-muted uppercase tracking-wider">Alamat Pengiriman Lengkap</p>
                            <p class="text-xs text-brandText mt-0.5 leading-relaxed bg-cream/10 border border-brandOutline-soft/25 p-3 rounded-xl">
                                {{ $order->delivery_address }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- 3. Items list invoice --}}
            <div class="bg-white border border-brandOutline-soft/30 rounded-2xl p-6 shadow-sm">
                <h3 class="font-serif text-base font-bold text-primary mb-4 pb-2 border-b border-brandOutline-soft/30">
                    Daftar Rangkaian Bunga
                </h3>

                <div class="divide-y divide-brandOutline-soft/20 text-sm">
                    @foreach($order->items as $item)
                        <div class="py-3.5 flex justify-between items-center gap-4">
                            <div>
                                <p class="font-bold text-primary">{{ $item->product_name }}</p>
                                <p class="text-xs text-brandText-muted mt-0.5">
                                    {{ $item->quantity }} barang x Rp {{ number_format($item->unit_price, 0, ',', '.') }}
                                </p>
                            </div>
                            <span class="font-bold text-brandText">
                                Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                            </span>
                        </div>
                    @endforeach
                </div>

                {{-- Total details --}}
                <div class="space-y-2.5 text-sm pt-4 border-t border-brandOutline-soft/30">
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
                        <span>Total Tagihan</span>
                        <span class="font-serif text-lg">
                            Rp {{ number_format($order->total, 0, ',', '.') }}
                        </span>
                    </div>
                </div>
            </div>

            {{-- 4. Greeting Card --}}
            @if($order->greeting_message)
                <div class="bg-white border border-brandOutline-soft/30 rounded-2xl p-6 shadow-sm">
                    <h3 class="font-serif text-base font-bold text-primary mb-4 pb-2 border-b border-brandOutline-soft/30 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                        </svg>
                        Pesan Kartu Ucapan Gratis
                    </h3>

                    <div class="max-w-md bg-cream/10 border-2 border-double border-gold/45 p-6 rounded-2xl shadow-inner relative overflow-hidden mx-auto text-center font-serif flex flex-col items-center justify-center min-h-[140px] select-all" title="Klik & seret untuk menyalin pesan">
                        <span class="text-base text-gold mb-1">✿</span>
                        <p class="text-sm text-primary/90 italic leading-relaxed whitespace-pre-wrap px-4 font-medium">
                            &ldquo;{{ $order->greeting_message }}&rdquo;
                        </p>
                    </div>
                </div>
            @endif

            {{-- 5. Customer Notes --}}
            @if($order->customer_note)
                <div class="bg-white border border-brandOutline-soft/30 rounded-2xl p-6 shadow-sm">
                    <h3 class="font-serif text-base font-bold text-primary mb-3 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        Catatan Tambahan Pelanggan
                    </h3>
                    <p class="text-xs text-brandText leading-relaxed italic bg-cream-light/10 border border-brandOutline-soft/20 p-3 rounded-xl">
                        &ldquo;{{ $order->customer_note }}&rdquo;
                    </p>
                </div>
            @endif

            {{-- 6. Timeline status history --}}
            <div class="bg-white border border-brandOutline-soft/30 rounded-2xl p-6 shadow-sm">
                <h3 class="font-serif text-base font-bold text-primary mb-5 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Linimasa Riwayat & Catatan Status
                </h3>

                <div class="relative pl-6 border-l-2 border-dashed border-brandOutline-soft/60 space-y-6 ml-3 text-sm">
                    @foreach($order->histories as $index => $log)
                        @php
                            $isLast = $index === count($order->histories) - 1;
                            $logDate = Carbon\Carbon::parse($log->created_at)->translatedFormat('d M Y H:i');
                        @endphp
                        <div class="relative">
                            <span class="absolute -left-[31px] top-1 w-4 h-4 rounded-full border-2 bg-white {{ $isLast ? 'border-primary bg-primary' : 'border-brandOutline' }}"></span>
                            
                            <div>
                                <h4 class="text-xs font-bold {{ $isLast ? 'text-primary' : 'text-brandText' }}">
                                    {{ getOperatorStatusLabel($log->current_status) }}
                                </h4>
                                <p class="text-[10px] text-brandText-muted mt-0.5">
                                    {{ $logDate }} WIB oleh {{ $log->actor->name ?? 'Sistem' }}
                                </p>
                                @if($log->note)
                                    <p class="text-xs text-brandText-muted/80 bg-cream/5 border border-brandOutline-soft/10 rounded-lg p-2 mt-2 leading-relaxed italic max-w-lg">
                                        &ldquo;{{ $log->note }}&rdquo;
                                    </p>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

        </div>

        {{-- Right Column: Payments Verification & Status Transitions --}}
        <div class="space-y-6 reveal" data-delay="200">
            
            {{-- 1. Payments Verification Panel --}}
            <div class="bg-white border border-brandOutline-soft/30 rounded-2xl p-6 shadow-sm space-y-4" x-data="{ rejectionMode: false }">
                <h3 class="font-serif text-base font-bold text-primary border-b border-brandOutline-soft/30 pb-2 flex items-center">
                    <svg class="w-4 h-4 mr-2 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Verifikasi Struk Pembayaran
                </h3>

                @if($order->payment)
                    <div class="space-y-4 text-xs">
                        {{-- Bank Details Table --}}
                        <div class="space-y-2 border-b border-brandOutline-soft/20 pb-3 leading-relaxed">
                            <div class="flex justify-between">
                                <span class="text-brandText-muted">Bank Tujuan:</span>
                                <span class="font-bold text-brandText">{{ $order->payment->destination_bank }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-brandText-muted">Bank Pengirim:</span>
                                <span class="font-bold text-brandText">{{ $order->payment->sender_bank }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-brandText-muted">Nama Pengirim:</span>
                                <span class="font-bold text-brandText">{{ $order->payment->account_holder_name }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-brandText-muted">Jumlah Transfer:</span>
                                <span class="font-bold text-primary">Rp {{ number_format($order->payment->amount, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-brandText-muted">Tanggal Transfer:</span>
                                <span class="font-bold text-brandText">
                                    {{ Carbon\Carbon::parse($order->payment->transfer_date)->translatedFormat('d F Y') }}
                                </span>
                            </div>
                        </div>

                        {{-- Attachment preview --}}
                        <div>
                            <p class="font-bold text-brandText mb-2">Gambar Bukti Transfer:</p>
                            <div class="w-full aspect-[3/4] border border-brandOutline rounded-xl overflow-hidden bg-cream-light/10 relative group">
                                <img
                                    src="/storage/{{ $order->payment->proof_path }}"
                                    alt="Bukti transfer"
                                    class="w-full h-full object-contain cursor-pointer hover:scale-[1.02] transition-transform"
                                    onclick="window.open('/storage/{{ $order->payment->proof_path }}', '_blank')"
                                />
                            </div>
                            <p class="text-[9px] text-brandText-muted/80 text-center mt-1 italic">Klik gambar untuk melihat resolusi penuh di tab baru.</p>
                        </div>

                        {{-- Verification Controls --}}
                        @if($order->order_status === 'waiting_verification')
                            <div class="pt-3 border-t border-brandOutline-soft/30 space-y-3">
                                <div x-show="!rejectionMode">
                                    <div class="grid grid-cols-2 gap-3">
                                        <form action="{{ route('operator.payments.verify', $order->order_number) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menyetujui pembayaran ini? Stok produk akan dikurangi otomatis.')">
                                            @csrf
                                            <input type="hidden" name="action" value="approve">
                                            <button
                                                type="submit"
                                                class="w-full inline-flex items-center justify-center font-sans font-semibold rounded-lg transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 active:scale-[0.98] bg-primary text-white hover:bg-primary-dark focus:ring-primary-muted shadow-sm text-xs py-2"
                                            >
                                                Setujui
                                            </button>
                                        </form>

                                        <button
                                            type="button"
                                            @click="rejectionMode = true"
                                            class="w-full inline-flex items-center justify-center font-sans font-semibold rounded-lg transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 active:scale-[0.98] border border-red-200 bg-transparent text-red-600 hover:bg-red-50 text-xs py-2"
                                        >
                                            Tolak
                                        </button>
                                    </div>
                                </div>

                                <div x-show="rejectionMode" style="display: none;">
                                    <form action="{{ route('operator.payments.verify', $order->order_number) }}" method="POST" class="space-y-3 p-3 bg-red-50/50 border border-red-100 rounded-xl" data-confirm="Apakah Anda yakin ingin menolak pembayaran ini? Pelanggan akan diminta mengunggah ulang bukti transfer.">
                                        @csrf
                                        <input type="hidden" name="action" value="reject">
                                        <label for="rejection_note" class="block text-[10px] font-bold text-red-800 uppercase tracking-wider mb-1">
                                            Alasan Penolakan Pembayaran <span class="text-red-500">*</span>
                                        </label>
                                        <textarea
                                            id="rejection_note"
                                            name="rejection_note"
                                            rows="3"
                                            class="w-full border border-red-300 rounded-lg p-2 text-xs bg-white text-brandText focus:outline-none focus:ring-1 focus:ring-red-500 focus:border-red-500"
                                            placeholder="Tuliskan alasan struk transfer ini tidak valid..."
                                            required
                                        ></textarea>
                                        
                                        <div class="grid grid-cols-2 gap-2">
                                            <button
                                                type="submit"
                                                class="w-full inline-flex items-center justify-center font-sans font-semibold rounded-lg transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 active:scale-[0.98] bg-red-600 text-white hover:bg-red-700 focus:ring-red-500 text-xs py-1.5"
                                            >
                                                Kirim Penolakan
                                            </button>
                                            <button
                                                type="button"
                                                @click="rejectionMode = false"
                                                class="w-full inline-flex items-center justify-center font-sans font-semibold rounded-lg transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 active:scale-[0.98] border border-brandOutline bg-transparent text-brandText hover:bg-brandSurface-low text-xs py-1.5"
                                            >
                                                Batal
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        @endif

                        {{-- Already Verified Info --}}
                        @if($order->payment->verification_status === 'verified')
                            <div class="p-3 bg-green-50 border border-green-100 rounded-xl text-green-800 space-y-1">
                                <p class="font-bold flex items-center">
                                    <svg class="w-4 h-4 mr-1 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                                    </svg>
                                    Pembayaran Disetujui
                                </p>
                                <p class="text-[10px]">
                                    Diverifikasi oleh: {{ $order->payment->verifier->name ?? 'Operator' }}
                                </p>
                                <p class="text-[10px]">
                                    Pada: {{ Carbon\Carbon::parse($order->payment->verified_at)->translatedFormat('d M y H:i') }} WIB
                                </p>
                            </div>
                        @endif

                        {{-- Already Rejected Info --}}
                        @if($order->payment->verification_status === 'rejected')
                            <div class="p-3 bg-red-50 border border-red-100 rounded-xl text-red-800 space-y-1">
                                <p class="font-bold flex items-center">
                                    <svg class="w-4 h-4 mr-1 text-red-650" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                    Pembayaran Ditolak
                                </p>
                                <p class="text-[10px] leading-relaxed italic">
                                    Alasan: &ldquo;{{ $order->payment->rejection_note }}&rdquo;
                                </p>
                            </div>
                        @endif
                    </div>
                @else
                    <div class="p-4 border border-dashed border-brandOutline rounded-xl text-center text-xs text-brandText-muted">
                        Pelanggan belum mengunggah bukti transfer untuk pesanan ini.
                    </div>
                @endif
            </div>

            {{-- 2. Order Status Transition Controls --}}
            @if(count($allowedTransitions) > 0)
                <div class="bg-white border border-brandOutline-soft/30 rounded-2xl p-6 shadow-sm space-y-4">
                    <h3 class="font-serif text-base font-bold text-primary border-b border-brandOutline-soft/30 pb-2 flex items-center">
                        <svg class="w-4.5 h-4.5 mr-1 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                        Pembaruan Progres Pengiriman
                    </h3>
                    
                    <form action="{{ route('operator.orders.update-status', $order->order_number) }}" method="POST" class="space-y-4 text-xs" onsubmit="return confirm('Apakah Anda yakin ingin memperbarui status pesanan?')">
                        @csrf
                        @method('PUT')

                        <div>
                            <label for="order_status" class="block text-[10px] font-bold text-brandText-muted uppercase tracking-wider mb-2">
                                Langkah Progres Selanjutnya <span class="text-red-500">*</span>
                            </label>
                            <select
                                id="order_status"
                                name="order_status"
                                class="w-full border border-brandOutline rounded-xl px-3 py-2 text-xs bg-cream-light/5 text-brandText focus:outline-none focus:ring-2 focus:ring-primary-soft focus:border-primary"
                                required
                            >
                                <option value="">-- Pilih Langkah Progres --</option>
                                @foreach($allowedTransitions as $t)
                                    <option value="{{ $t['value'] }}">{{ $t['label'] }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="note" class="block text-[10px] font-bold text-brandText-muted uppercase tracking-wider mb-2">
                                Catatan Progres / Alasan (Opsional)
                            </label>
                            <textarea
                                id="note"
                                name="note"
                                rows="2"
                                class="w-full border border-brandOutline rounded-xl p-2 text-xs bg-cream-light/5 text-brandText focus:outline-none focus:ring-2 focus:ring-primary-soft focus:border-primary"
                                placeholder="Tulis informasi pengiriman atau catatan perangkaian bunga..."
                            ></textarea>
                        </div>

                        <button
                            type="submit"
                            class="w-full inline-flex items-center justify-center font-sans font-semibold rounded-lg transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 active:scale-[0.98] bg-primary text-white hover:bg-primary-dark focus:ring-primary-muted shadow-sm py-2"
                        >
                            Perbarui Status Pesanan
                        </button>
                    </form>
                </div>
            @endif

            {{-- Safety Disclaimer --}}
            <div class="bg-cream/15 border border-brandOutline-soft/20 rounded-2xl p-4 text-[10px] text-brandText-muted/95 flex items-start gap-2 leading-relaxed">
                <svg class="w-4 h-4 text-gold flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
                <div>
                    <p class="font-bold text-primary">Informasi Penjagaan Keamanan</p>
                    <p class="mt-1">Penyetujuan pembayaran mengunci database dan memotong persediaan stok produk secara real-time. Pastikan Anda telah melihat keselarasan nominal struk transfer di m-Banking sebelum mengeklik tombol Setuju.</p>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection

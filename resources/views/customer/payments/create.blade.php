@extends('layouts.public')

@section('title', 'Unggah Bukti Pembayaran #' . $order->order_number . ' | Little Joy Jakarta')

@section('content')
@php
    $todayStr = date('Y-m-d');
@endphp

<div class="bg-cream-light/30 min-h-screen py-12 font-sans">
    <div class="max-w-3xl mx-auto px-4">
        {{-- Back button --}}
        <div class="mb-8 reveal">
            <a
                href="{{ route('customer.orders.show', $order->order_number) }}"
                class="inline-flex items-center text-xs font-semibold text-brandText-muted hover:text-primary transition-colors mb-3 group"
            >
                <svg class="w-3.5 h-3.5 mr-1 group-hover:-translate-x-0.5 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Kembali ke Detail Pesanan
            </a>
            <h1 class="font-serif text-3xl font-bold text-primary tracking-tight">
                Unggah Bukti Pembayaran
            </h1>
            <p class="text-sm text-brandText-muted mt-1">
                Isi rincian transfer dan unggah foto resi transaksi Anda untuk verifikasi.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 items-start">
            {{-- Order Snapshot Card --}}
            <div class="md:col-span-1 bg-white border border-brandOutline-soft/30 rounded-2xl p-5 shadow-sm space-y-4 reveal" data-delay="100">
                <h4 class="font-serif text-sm font-bold text-primary border-b border-brandOutline-soft/30 pb-2">
                    Detail Tagihan
                </h4>
                <div>
                    <p class="text-[10px] font-bold text-brandText-muted uppercase tracking-wider">No. Pesanan</p>
                    <p class="font-mono text-sm font-bold text-primary mt-0.5">#{{ $order->order_number }}</p>
                </div>
                <div>
                    <p class="text-[10px] font-bold text-brandText-muted uppercase tracking-wider">Total Harus Dibayar</p>
                    <p class="font-serif text-base font-bold text-primary mt-0.5">
                        Rp {{ number_format($order->total, 0, ',', '.') }}
                    </p>
                </div>
                
                @if($order->order_status === 'rejected' && $order->payment && $order->payment->rejection_note)
                    <div class="p-3 bg-red-50 border border-red-100 rounded-xl space-y-1">
                        <p class="text-[10px] font-bold text-red-700 flex items-center gap-1">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                            Pembayaran Ditolak
                        </p>
                        <p class="text-[10px] text-red-600 leading-relaxed italic">
                            &ldquo;{{ $order->payment->rejection_note }}&rdquo;
                        </p>
                    </div>
                @endif
            </div>

            {{-- Upload Form --}}
            <div class="md:col-span-2 reveal" data-delay="150" x-data="{ 
                imagePreview: null,
                fileName: '',
                fileSize: '',
                dragActive: false,
                handleFileSelect(event) {
                    const file = event.target.files[0];
                    this.processFile(file);
                },
                handleFileDrop(event) {
                    this.dragActive = false;
                    const file = event.dataTransfer.files[0];
                    this.processFile(file);
                },
                processFile(file) {
                    if (!file) return;
                    if (file.size > 2 * 1024 * 1024) {
                        alert('Ukuran berkas melebihi 2 MB. Silakan unggah gambar yang lebih kecil.');
                        return;
                    }
                    this.fileName = file.name;
                    this.fileSize = file.size > 1024 * 1024 
                        ? (file.size / (1024 * 1024)).toFixed(2) + ' MB' 
                        : (file.size / 1024).toFixed(0) + ' KB';
                    
                    const reader = new FileReader();
                    reader.onloadend = () => {
                        this.imagePreview = reader.result;
                    };
                    reader.readAsDataURL(file);
                },
                removeImage() {
                    this.imagePreview = null;
                    this.fileName = '';
                    this.fileSize = '';
                    document.getElementById('proof_image').value = '';
                }
            }">
                <form action="{{ route('customer.payments.store', $order->order_number) }}" method="POST" enctype="multipart/form-data" class="bg-white border border-brandOutline-soft/30 rounded-2xl p-6 shadow-sm space-y-6" data-confirm="Apakah Anda yakin data transfer sudah sesuai dan ingin mengunggah bukti pembayaran ini?">
                    @csrf

                    {{-- Bank Details --}}
                    <div class="space-y-4 font-sans">
                        <h3 class="font-serif text-base font-bold text-primary border-b border-brandOutline-soft/30 pb-2 flex items-center">
                            <svg class="w-4 h-4 mr-2 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z" />
                            </svg>
                            Informasi Transfer Bank
                        </h3>

                        {{-- Destination Bank --}}
                        <div>
                            <label htmlFor="destination_bank" class="block text-xs font-bold text-brandText-muted uppercase tracking-wider mb-2">
                                Rekening Bank Tujuan <span class="text-red-500">*</span>
                            </label>
                            <select
                                id="destination_bank"
                                name="destination_bank"
                                class="w-full border border-brandOutline rounded-xl px-4 py-2.5 text-sm bg-cream-light/10 text-brandText focus:outline-none focus:ring-2 focus:ring-primary-muted/40 focus:border-primary transition-all @error('destination_bank') border-red-300 focus:ring-red-500 @enderror"
                                required
                            >
                                <option value="">-- Pilih Rekening Tujuan --</option>
                                <option value="BCA (123-456-7890 a/n Little Joy Jakarta)" {{ old('destination_bank') === 'BCA (123-456-7890 a/n Little Joy Jakarta)' ? 'selected' : '' }}>BCA - 123-456-7890 a/n Little Joy Jakarta</option>
                                <option value="Mandiri (098-765-4321 a/n Little Joy Jakarta)" {{ old('destination_bank') === 'Mandiri (098-765-4321 a/n Little Joy Jakarta)' ? 'selected' : '' }}>Mandiri - 098-765-4321 a/n Little Joy Jakarta</option>
                            </select>
                            @error('destination_bank')
                                <p class="text-xs text-red-500 mt-1.5 font-medium">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            {{-- Sender Bank --}}
                            <div>
                                <x-input
                                    label="Bank Pengirim"
                                    id="sender_bank"
                                    type="text"
                                    name="sender_bank"
                                    placeholder="Contoh: BCA, Mandiri, BRI, BNI"
                                    required
                                />
                            </div>

                            {{-- Account Holder Name --}}
                            <div>
                                <x-input
                                    label="Nama Pemilik Rekening Pengirim"
                                    id="account_holder_name"
                                    type="text"
                                    name="account_holder_name"
                                    placeholder="Sesuai nama di tabungan"
                                    required
                                />
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            {{-- Transfer Amount --}}
                            <div>
                                <label htmlFor="amount" class="block text-xs font-bold text-brandText-muted uppercase tracking-wider mb-2">
                                    Nominal yang Ditransfer <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <span class="absolute left-4 top-2.5 text-sm text-brandText-muted font-semibold">Rp</span>
                                    <input
                                        type="number"
                                        id="amount"
                                        name="amount"
                                        value="{{ old('amount', $order->total) }}"
                                        class="w-full border border-brandOutline rounded-xl pl-10 pr-4 py-2.5 text-sm bg-cream-light/10 text-brandText font-semibold focus:outline-none focus:ring-2 focus:ring-primary-muted/40 focus:border-primary transition-all @error('amount') border-red-300 focus:ring-red-500 @enderror"
                                        required
                                    />
                                </div>
                                @error('amount')
                                    <p class="text-xs text-red-500 mt-1.5 font-medium">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Transfer Date --}}
                            <div>
                                <label htmlFor="transfer_date" class="block text-xs font-bold text-brandText-muted uppercase tracking-wider mb-2 flex items-center">
                                    <svg class="w-3.5 h-3.5 mr-1.5 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    Tanggal Transfer <span class="text-red-500">*</span>
                                </label>
                                <input
                                    type="date"
                                    id="transfer_date"
                                    name="transfer_date"
                                    max="{{ $todayStr }}"
                                    value="{{ old('transfer_date', $todayStr) }}"
                                    class="w-full border border-brandOutline rounded-xl px-4 py-2.5 text-sm bg-cream-light/10 text-brandText focus:outline-none focus:ring-2 focus:ring-primary-muted/40 focus:border-primary transition-all @error('transfer_date') border-red-300 focus:ring-red-500 @enderror"
                                    required
                                />
                                @error('transfer_date')
                                    <p class="text-xs text-red-500 mt-1.5 font-medium">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    {{-- Drag-and-Drop Image Uploader --}}
                    <div class="space-y-3 font-sans">
                        <label class="block text-xs font-bold text-brandText-muted uppercase tracking-wider">
                            Foto Bukti Pembayaran / Struk Transfer <span class="text-red-500">*</span>
                        </label>
                        
                        <input
                            type="file"
                            id="proof_image"
                            name="proof_image"
                            @change="handleFileSelect($event)"
                            class="hidden"
                            accept="image/jpeg,image/png,image/webp"
                            required
                        />

                        {{-- Drop Area --}}
                        <div x-show="!imagePreview">
                            <div
                                @dragenter.prevent="dragActive = true"
                                @dragover.prevent="dragActive = true"
                                @dragleave.prevent="dragActive = false"
                                @drop.prevent="handleFileDrop($event)"
                                @click="document.getElementById('proof_image').click()"
                                class="border-2 border-dashed rounded-2xl p-8 flex flex-col items-center justify-center text-center cursor-pointer transition-all"
                                :class="dragActive ? 'border-primary bg-primary-soft/10' : 'border-brandOutline hover:border-primary hover:bg-cream-light/10'"
                            >
                                <svg class="w-10 h-10 text-brandText-muted mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                                </svg>
                                <p class="text-sm font-semibold text-brandText">
                                    Seret & taruh foto resi di sini, atau <span class="text-primary hover:underline">pilih berkas</span>
                                </p>
                                <p class="text-[10px] text-brandText-muted mt-2">
                                    Mendukung JPG, JPEG, PNG, dan WebP (Maksimal 2 MB).
                                </p>
                            </div>
                        </div>

                        {{-- Preview Area --}}
                        <div x-show="imagePreview" style="display: none;">
                            <div class="relative border border-brandOutline rounded-2xl overflow-hidden bg-cream-light/10 p-4 flex flex-col items-center justify-center">
                                <div class="relative max-w-xs aspect-[3/4] w-full rounded-xl overflow-hidden border border-brandOutline bg-white">
                                    <img
                                        :src="imagePreview"
                                        alt="Preview Bukti Transfer"
                                        class="w-full h-full object-contain"
                                    />
                                    <button
                                        type="button"
                                        @click="removeImage()"
                                        class="absolute top-2 right-2 p-1.5 bg-black/60 hover:bg-black/80 rounded-full text-white transition-colors focus:outline-none"
                                        title="Hapus gambar"
                                    >
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </button>
                                </div>
                                <p class="text-[10px] text-brandText-muted mt-3 font-semibold flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    <span x-text="fileName"></span> (<span x-text="fileSize"></span>)
                                </p>
                            </div>
                        </div>
                        
                        @error('proof_image')
                            <p class="text-xs text-red-500 mt-1.5 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Submit button --}}
                    <div class="pt-4 border-t border-brandOutline-soft/30 font-sans">
                        <button
                            type="submit"
                            class="w-full inline-flex items-center justify-center font-sans font-semibold rounded-lg transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 active:scale-[0.98] bg-primary text-white hover:bg-primary-dark focus:ring-primary-muted shadow-sm py-2.5 text-sm gap-2 shadow-md"
                        >
                            <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                            </svg>
                            Kirim Bukti Pembayaran
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

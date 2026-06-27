@extends('layouts.dashboard')

@section('title', 'Laporan & Manajemen Keuangan')

@section('content')
@php
    $weeklyTotal = collect($orders)->sum('total');

    function getReportStatusBadgeClass($status) {
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
                return 'bg-yellow-50 text-yellow-700 border-yellow-200';
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

    function getReportStatusLabel($status) {
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

    $topProduct = $summary['best_sellers'][0] ?? null;
    $startDateFormatted = !empty($filters['start_date']) ? Carbon\Carbon::parse($filters['start_date'])->translatedFormat('d F Y') : '-';
    $endDateFormatted = !empty($filters['end_date']) ? Carbon\Carbon::parse($filters['end_date'])->translatedFormat('d F Y') : '-';
@endphp

<div class="space-y-8 font-sans">
    {{-- Print Header (Only visible on print) --}}
    <div class="hidden print:block border-b border-primary/30 pb-5 mb-8 pt-4">
        <div class="flex justify-between items-start">
            <div class="space-y-1.5">
                <h2 class="font-serif text-2xl font-bold tracking-wide text-primary">LITTLE JOY JAKARTA</h2>
                <p class="text-[10px] text-brandText-muted uppercase tracking-wider font-semibold">Premium Florist & Flower Bouquet Services</p>
                <p class="text-[9px] text-brandText-muted">Jakarta, Indonesia | info@littlejoyjakarta.com | +62 812-3456-7890</p>
            </div>
            <div class="text-right space-y-1.5">
                <h3 class="font-serif text-lg font-bold text-primary uppercase tracking-wide">Laporan Omset & Penjualan</h3>
                <p class="text-xs text-brandText-dark font-semibold">
                    Periode: {{ $startDateFormatted }} s/d {{ $endDateFormatted }}
                </p>
                <p class="text-[9px] text-brandText-muted/80">Dicetak pada: {{ now()->translatedFormat('d F Y H:i') }} WIB</p>
            </div>
        </div>
    </div>

    {{-- Filter and Actions Panel (Hidden on print) --}}
    <div class="bg-white p-6 rounded-2xl border border-brandOutline-soft/30 shadow-sm print:hidden reveal">
        <form action="{{ route('admin.reports.index') }}" method="GET" class="space-y-4">
            <div class="flex items-center gap-2 pb-3 border-b border-gray-100">
                <svg class="w-4 h-4 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                </svg>
                <h4 class="font-serif text-base font-bold text-primary">Filter Laporan Penjualan</h4>
            </div>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 text-xs font-sans">
                {{-- Start Date --}}
                <div class="space-y-1.5">
                    <label class="font-bold text-brandText-muted uppercase tracking-wider block">Tanggal Mulai</label>
                    <input 
                        type="date"
                        name="start_date"
                        value="{{ $filters['start_date'] ?? '' }}"
                        class="w-full text-xs bg-gray-50/50 border border-brandOutline-soft rounded-xl px-3 py-2 text-brandText-dark focus:outline-none focus:ring-2 focus:ring-primary-soft focus:border-primary transition-all font-semibold"
                    />
                </div>

                {{-- End Date --}}
                <div class="space-y-1.5">
                    <label class="font-bold text-brandText-muted uppercase tracking-wider block">Tanggal Selesai</label>
                    <input 
                        type="date"
                        name="end_date"
                        value="{{ $filters['end_date'] ?? '' }}"
                        class="w-full text-xs bg-gray-50/50 border border-brandOutline-soft rounded-xl px-3 py-2 text-brandText-dark focus:outline-none focus:ring-2 focus:ring-primary-soft focus:border-primary transition-all font-semibold"
                    />
                </div>

                {{-- Order Status --}}
                <div class="space-y-1.5">
                    <label class="font-bold text-brandText-muted uppercase tracking-wider block">Status Pesanan</label>
                    <select 
                        name="order_status"
                        class="w-full text-xs bg-gray-50/50 border border-brandOutline-soft rounded-xl px-3 py-2 text-brandText-dark focus:outline-none focus:ring-2 focus:ring-primary-soft focus:border-primary transition-all font-semibold"
                    >
                        <option value="all" {{ ($filters['order_status'] ?? '') === 'all' ? 'selected' : '' }}>Semua Status</option>
                        <option value="pending_payment" {{ ($filters['order_status'] ?? '') === 'pending_payment' ? 'selected' : '' }}>Belum Bayar</option>
                        <option value="waiting_verification" {{ ($filters['order_status'] ?? '') === 'waiting_verification' ? 'selected' : '' }}>Menunggu Verifikasi</option>
                        <option value="paid" {{ ($filters['order_status'] ?? '') === 'paid' ? 'selected' : '' }}>Lunas</option>
                        <option value="processing" {{ ($filters['order_status'] ?? '') === 'processing' ? 'selected' : '' }}>Diproses</option>
                        <option value="ready" {{ ($filters['order_status'] ?? '') === 'ready' ? 'selected' : '' }}>Siap Kirim</option>
                        <option value="shipped" {{ ($filters['order_status'] ?? '') === 'shipped' ? 'selected' : '' }}>Dalam Pengiriman</option>
                        <option value="completed" {{ ($filters['order_status'] ?? '') === 'completed' ? 'selected' : '' }}>Selesai</option>
                        <option value="cancelled" {{ ($filters['order_status'] ?? '') === 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
                        <option value="rejected" {{ ($filters['order_status'] ?? '') === 'rejected' ? 'selected' : '' }}>Ditolak</option>
                    </select>
                </div>

                {{-- Payment Status --}}
                <div class="space-y-1.5">
                    <label class="font-bold text-brandText-muted uppercase tracking-wider block">Status Pembayaran</label>
                    <select 
                        name="payment_status"
                        class="w-full text-xs bg-gray-50/50 border border-brandOutline-soft rounded-xl px-3 py-2 text-brandText-dark focus:outline-none focus:ring-2 focus:ring-primary-soft focus:border-primary transition-all font-semibold"
                    >
                        <option value="all" {{ ($filters['payment_status'] ?? '') === 'all' ? 'selected' : '' }}>Semua Status</option>
                        <option value="pending" {{ ($filters['payment_status'] ?? '') === 'pending' ? 'selected' : '' }}>Belum Bayar</option>
                        <option value="waiting_verification" {{ ($filters['payment_status'] ?? '') === 'waiting_verification' ? 'selected' : '' }}>Menunggu Verifikasi</option>
                        <option value="verified" {{ ($filters['payment_status'] ?? '') === 'verified' ? 'selected' : '' }}>Diterima (Lunas)</option>
                        <option value="rejected" {{ ($filters['payment_status'] ?? '') === 'rejected' ? 'selected' : '' }}>Ditolak</option>
                    </select>
                </div>
            </div>

            {{-- Action Buttons --}}
            <div class="flex flex-wrap items-center justify-between gap-4 pt-4 border-t border-gray-100 font-sans text-xs">
                <div class="flex items-center gap-2">
                    <button
                        type="submit"
                        class="px-5 py-2 bg-primary hover:bg-primary-dark text-white text-xs font-semibold rounded-xl transition-all shadow-sm focus:outline-none font-bold"
                    >
                        Terapkan Filter
                    </button>
                    <a
                        href="{{ route('admin.reports.index') }}"
                        class="px-4 py-2 border border-brandOutline hover:bg-gray-50 text-brandText-dark text-xs font-semibold rounded-xl transition-all flex items-center gap-1 font-bold"
                    >
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 4v5h.582m15.356 2A8.001 8.001 0 1121.21 8H18.2" />
                        </svg> 
                        Reset
                    </a>
                </div>

                <div class="flex items-center gap-2">
                    <a
                        href="{{ route('admin.reports.export', [
                            'start_date' => $filters['start_date'] ?? '',
                            'end_date' => $filters['end_date'] ?? '',
                            'order_status' => $filters['order_status'] ?? '',
                            'payment_status' => $filters['payment_status'] ?? ''
                        ]) }}"
                        class="px-4 py-2 border border-brandOutline hover:bg-gray-50 text-brandText-dark text-xs font-semibold rounded-xl transition-all flex items-center gap-1.5 shadow-sm font-bold"
                    >
                        <svg class="w-4 h-4 text-brandText-muted" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                        </svg>
                        Unduh Excel
                    </a>
                    <button
                        type="button"
                        onclick="window.print()"
                        class="px-4 py-2 bg-secondary hover:bg-secondary-dark text-white text-xs font-semibold rounded-xl transition-all flex items-center gap-1.5 shadow-sm focus:outline-none font-bold"
                    >
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                        </svg>
                        Cetak Laporan
                    </button>
                </div>
            </div>
        </form>
    </div>

    {{-- Summary Metrics Grid --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 reveal" data-delay="100">
        {{-- Realized Revenue --}}
        <div class="bg-white p-5 rounded-2xl border border-brandOutline-soft/30 shadow-sm flex items-center justify-between print:border-gray-200">
            <div>
                <p class="text-xs font-bold text-brandText-muted uppercase tracking-wider mb-0.5 print:text-gray-500">
                    Pendapatan Omset
                </p>
                <h4 class="text-xl font-bold text-primary font-serif print:text-black">
                    Rp {{ number_format($summary['total_revenue'] ?? 0, 0, ',', '.') }}
                </h4>
                <p class="text-[9px] text-green-650 font-semibold mt-1 print:hidden">
                    Akumulasi transaksi berhasil
                </p>
            </div>
            <div class="p-3 bg-green-50 text-green-600 rounded-xl print:bg-transparent print:text-black">
                <span class="text-xl font-bold font-serif">Rp</span>
            </div>
        </div>

        {{-- Total Transactions --}}
        <div class="bg-white p-5 rounded-2xl border border-brandOutline-soft/30 shadow-sm flex items-center justify-between print:border-gray-200">
            <div>
                <p class="text-xs font-bold text-brandText-muted uppercase tracking-wider mb-0.5 print:text-gray-500">
                    Total Transaksi
                </p>
                <h4 class="text-xl font-bold text-primary print:text-black">
                    {{ $summary['total_transactions'] ?? 0 }} <span class="text-xs font-semibold text-brandText-muted">nota</span>
                </h4>
                <p class="text-[9px] text-brandText-muted/70 mt-1 print:hidden">
                    Jumlah pesanan dalam filter
                </p>
            </div>
            <div class="p-3 bg-blue-50 text-blue-600 rounded-xl print:bg-transparent print:text-black">
                <span class="text-lg font-bold font-mono">#</span>
            </div>
        </div>

        {{-- Total Flower Items Sold --}}
        <div class="bg-white p-5 rounded-2xl border border-brandOutline-soft/30 shadow-sm flex items-center justify-between print:border-gray-200">
            <div>
                <p class="text-xs font-bold text-brandText-muted uppercase tracking-wider mb-0.5 print:text-gray-500">
                    Total Item Terjual
                </p>
                <h4 class="text-xl font-bold text-primary print:text-black">
                    {{ $summary['total_items_sold'] ?? 0 }} <span class="text-xs font-semibold text-brandText-muted">item</span>
                </h4>
                <p class="text-[9px] text-brandText-muted/70 mt-1 print:hidden">
                    Karangan bunga terdistribusi
                </p>
            </div>
            <div class="p-3 bg-indigo-50 text-indigo-600 rounded-xl print:bg-transparent print:text-black">
                <span class="text-lg font-serif">✿</span>
            </div>
        </div>

        {{-- Top Selling Arrangement --}}
        <div class="bg-white p-5 rounded-2xl border border-brandOutline-soft/30 shadow-sm flex items-center justify-between print:border-gray-200">
            <div class="min-w-0 flex-1">
                <p class="text-xs font-bold text-brandText-muted uppercase tracking-wider mb-0.5 print:text-gray-500">
                    Rangkaian Terlaris
                </p>
                <h4 class="text-sm font-bold text-primary truncate pr-1 print:text-black font-serif" title="{{ $topProduct['product_name'] ?? 'N/A' }}">
                    {{ $topProduct ? $topProduct['product_name'] : 'Belum Ada' }}
                </h4>
                <p class="text-[9px] text-secondary font-bold mt-1 print:text-gray-700">
                    {{ $topProduct ? $topProduct['total_qty'] . ' Item Terjual' : 'N/A' }}
                </p>
            </div>
            <div class="p-3 bg-amber-50 text-amber-600 rounded-xl flex-shrink-0 print:bg-transparent print:text-black">
                <span class="text-lg font-serif">★</span>
            </div>
        </div>
    </div>

    {{-- Main Ledger Table --}}
    <div class="bg-white rounded-2xl border border-brandOutline-soft/30 shadow-sm overflow-hidden print:border-gray-200 reveal" data-delay="150">
        <div class="p-6 border-b border-gray-100 flex items-center justify-between print:px-0">
            <div>
                <h4 class="font-serif text-lg font-bold text-primary print:text-black">
                    Rincian Log Transaksi Penjualan
                </h4>
                <p class="text-xs text-brandText-muted print:hidden">
                    Menampilkan riwayat pemesanan yang sesuai dengan filter pencarian di atas.
                </p>
            </div>
            <span class="hidden print:inline-block px-3 py-1 border border-gray-200 rounded-lg text-[10px] font-semibold text-brandText-dark">
                Total: {{ $summary['total_transactions'] ?? 0 }} Transaksi
            </span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs border-collapse">
                <thead>
                    <tr class="border-b border-gray-100 text-brandText-muted font-bold bg-gray-50/50 print:bg-gray-100 print:text-black">
                        <th class="py-3 px-6">No. Pesanan</th>
                        <th class="py-3 px-4">Tgl Pesanan</th>
                        <th class="py-3 px-4">Pelanggan</th>
                        <th class="py-3 px-4 print:hidden">Alamat Penerima</th>
                        <th class="py-3 px-4">Status Pesanan</th>
                        <th class="py-3 px-4">Status Bayar</th>
                        <th class="py-3 px-6 text-right">Omset Bersih</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 print:divide-gray-200 text-brandText">
                    @if(empty($orders) || count($orders) === 0)
                        <tr>
                            <td colSpan="7" class="py-12 text-center text-brandText-muted/60 font-semibold">
                                Tidak ada transaksi yang cocok dengan kriteria filter.
                            </td>
                        </tr>
                    @else
                        @foreach($orders as $order)
                            @php
                                $orderDate = Carbon\Carbon::parse($order->created_at)->translatedFormat('d M Y');
                            @endphp
                            <tr class="hover:bg-gray-50/20 transition-colors print:hover:bg-transparent">
                                <td class="py-4 px-6 font-bold text-primary print:text-black">
                                    {{ $order->order_number }}
                                </td>
                                <td class="py-4 px-4 text-brandText-muted print:text-black font-semibold">
                                    {{ $orderDate }}
                                </td>
                                <td class="py-4 px-4">
                                    <p class="font-bold text-brandText-dark print:text-black">{{ $order->user->name ?? 'Guest' }}</p>
                                    <p class="text-[10px] text-brandText-muted print:text-gray-500">{{ $order->user->email ?? '' }}</p>
                                </td>
                                <td class="py-4 px-4 max-w-[200px] truncate print:hidden" title="{{ $order->delivery_address }}">
                                    <p class="font-bold text-brandText-dark truncate print:text-black">{{ $order->recipient_name }}</p>
                                    <p class="text-[10px] text-brandText-muted truncate print:text-gray-500">{{ $order->delivery_address }}</p>
                                </td>
                                <td class="py-4 px-4">
                                    <span class="inline-flex px-2 py-0.5 text-[9px] font-bold tracking-wide rounded-md border {{ getReportStatusBadgeClass($order->order_status) }}">
                                        {{ getReportStatusLabel($order->order_status) }}
                                    </span>
                                </td>
                                <td class="py-4 px-4">
                                    @if($order->payment_status === 'verified')
                                        <span class="inline-flex px-2 py-0.5 text-[9px] font-bold tracking-wide rounded-md border bg-green-50 text-green-700 border-green-200">
                                            Lunas (Verified)
                                        </span>
                                    @elseif($order->payment_status === 'waiting_verification')
                                        <span class="inline-flex px-2 py-0.5 text-[9px] font-bold tracking-wide rounded-md border bg-yellow-50 text-yellow-700 border-yellow-200">
                                            Menunggu Verifikasi
                                        </span>
                                    @elseif($order->payment_status === 'rejected')
                                        <span class="inline-flex px-2 py-0.5 text-[9px] font-bold tracking-wide rounded-md border bg-red-50 text-red-700 border-red-200">
                                            Ditolak
                                        </span>
                                    @else
                                        <span class="inline-flex px-2 py-0.5 text-[9px] font-bold tracking-wide rounded-md border bg-gray-100 text-gray-700 border-gray-200">
                                            Belum Bayar
                                        </span>
                                    @endif
                                </td>
                                <td class="py-4 px-6 text-right font-bold text-primary print:text-black font-mono">
                                    Rp {{ number_format($order->total, 0, ',', '.') }}
                                </td>
                            </tr>
                        @endforeach
                    @endif
                </tbody>
                @if(!empty($orders) && count($orders) > 0)
                    <tfoot>
                        <tr class="bg-gray-50/50 font-bold border-t border-gray-200 print:bg-gray-100 print:text-black">
                            <td colSpan="6" class="py-4 px-6 text-brandText-dark print:text-black text-right uppercase tracking-wider font-bold print:hidden">
                                Total Akumulasi Terfilter
                            </td>
                            <td colSpan="5" class="hidden print:table-cell py-4 px-6 text-brandText-dark print:text-black text-right uppercase tracking-wider font-bold">
                                Total Akumulasi Terfilter
                            </td>
                            <td class="py-4 px-6 text-right text-primary print:text-black text-sm font-mono font-bold">
                                Rp {{ number_format(collect($orders)->sum('total'), 0, ',', '.') }}
                            </td>
                        </tr>
                    </tfoot>
                @endif
            </table>
        </div>
    </div>

    {{-- Secondary Tables: Best Selling Arrangements --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 print:grid-cols-1 reveal" data-delay="200">
        {{-- Best Selling --}}
        <div class="bg-white p-6 rounded-2xl border border-brandOutline-soft/30 shadow-sm print:border-gray-200">
            <div class="flex items-center gap-2 pb-4 mb-4 border-b border-gray-100">
                <span class="text-lg font-serif">★</span>
                <h4 class="font-serif text-base font-bold text-primary print:text-black">
                    Peringkat Rangkaian Bunga Terlaris
                </h4>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs border-collapse">
                    <thead>
                        <tr class="border-b border-gray-100 text-brandText-muted font-bold print:text-black">
                            <th class="py-2.5 px-0 w-12 text-center">Rank</th>
                            <th class="py-2.5 px-3">Nama Karangan Bunga</th>
                            <th class="py-2.5 px-3 text-center">Volume Terjual</th>
                            <th class="py-2.5 px-6 text-right">Total Penjualan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50 text-brandText">
                        @if(empty($summary['best_sellers']) || count($summary['best_sellers']) === 0)
                            <tr>
                                <td colSpan="4" class="py-8 text-center text-brandText-muted/60 font-semibold">
                                    Belum ada data penjualan karangan bunga.
                                </td>
                            </tr>
                        @else
                            @foreach($summary['best_sellers'] as $index => $item)
                                <tr class="hover:bg-gray-50/30 transition-colors">
                                    <td class="py-3 px-0 text-center">
                                        <span class="inline-flex items-center justify-center w-5 h-5 rounded-full text-[10px] font-bold {{
                                            $index === 0 ? 'bg-amber-100 text-amber-700' : (
                                            $index === 1 ? 'bg-gray-200 text-gray-700' : (
                                            $index === 2 ? 'bg-amber-50 text-amber-800' :
                                            'bg-gray-100 text-gray-600'))
                                        }}">
                                            {{ $index + 1 }}
                                        </span>
                                    </td>
                                    <td class="py-3 px-3 font-bold text-brandText-dark">
                                        {{ $item['product_name'] }}
                                    </td>
                                    <td class="py-3 px-3 text-center font-bold text-brandText-dark font-mono">
                                        {{ $item['total_qty'] }} item
                                    </td>
                                    <td class="py-3 px-6 text-right font-bold text-primary font-mono">
                                        Rp {{ number_format($item['total_sales'], 0, ',', '.') }}
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Print Disclaimer (Only visible on print) --}}
        <div class="hidden print:flex flex-col justify-end p-6 border-2 border-dashed border-gray-200 rounded-2xl h-full font-sans text-xs">
            <h5 class="font-serif text-sm font-bold text-black mb-1">Pernyataan Validitas Dokumen</h5>
            <p class="text-[10px] text-gray-500 leading-relaxed">
                Laporan omset dan rincian log transaksi ini diproduksi secara otomatis oleh sistem administrasi utama **Little Joy Management**. Seluruh data keuangan yang tertera di atas bersifat final dan sesuai dengan mutasi manual bank transfer yang telah divalidasi penuh oleh staf operator yang bertugas.
            </p>
            <div class="flex justify-between items-center mt-6 pt-6 border-t border-gray-100">
                <div class="text-center w-36">
                    <p class="text-[9px] text-gray-500">Dibuat Oleh,</p>
                    <div class="h-10"></div>
                    <p class="text-[10px] font-bold text-black border-t border-gray-200 pt-1">Sistem Administrasi</p>
                </div>
                <div class="text-center w-36">
                    <p class="text-[9px] text-gray-500">Disetujui Oleh,</p>
                    <div class="h-10"></div>
                    <p class="text-[10px] font-bold text-black border-t border-gray-200 pt-1">Pemilik Toko (Admin)</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

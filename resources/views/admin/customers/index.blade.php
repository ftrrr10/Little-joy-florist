@extends('layouts.dashboard')

@section('title', 'Pengelolaan Data Pelanggan')

@section('content')
<div class="space-y-6 font-sans" x-data="{ searchTerm: '' }">
    {{-- Search Bar & Filters --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-white p-4 rounded-2xl border border-brandOutline-soft/30 shadow-sm reveal">
        {{-- Search Input --}}
        <div class="relative flex-1 max-w-md">
            <svg class="absolute left-3 top-3 w-4 h-4 text-brandText-muted" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
            <input
                type="text"
                x-model="searchTerm"
                placeholder="Cari nama, email, atau telepon pelanggan..."
                class="w-full pl-9 pr-4 py-2 text-sm border border-brandOutline rounded-xl bg-cream-light/5 text-brandText focus:outline-none focus:ring-2 focus:ring-primary-soft focus:border-primary transition-all"
            />
        </div>

        <div class="text-xs text-brandText-muted font-semibold">
            Total {{ count($customers) }} pelanggan terdaftar
        </div>
    </div>

    {{-- Customer Table Card --}}
    <div class="bg-white border border-brandOutline-soft/30 rounded-2xl shadow-sm overflow-hidden reveal" data-delay="100">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-sm">
                <thead>
                    <tr class="bg-cream/15 border-b border-brandOutline-soft/30 text-xs font-bold text-brandText-muted uppercase tracking-wider">
                        <th class="px-6 py-4">Nama Pelanggan</th>
                        <th class="px-6 py-4">Info Kontak</th>
                        <th class="px-6 py-4">Tanggal Bergabung</th>
                        <th class="px-6 py-4 text-center">Jumlah Order</th>
                        <th class="px-6 py-4 text-right">Total Belanja</th>
                        <th class="px-6 py-4 text-center">Status Akun</th>
                        <th class="px-6 py-4 text-center">Tindakan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-brandOutline-soft/20 text-brandText">
                    @if(empty($customers) || count($customers) === 0)
                        <tr>
                            <td colSpan="7" class="px-6 py-12 text-center text-brandText-muted/70 font-semibold">
                                Belum ada pelanggan terdaftar.
                            </td>
                        </tr>
                    @else
                        @foreach($customers as $cust)
                            @php
                                $joinDate = Carbon\Carbon::parse($cust->created_at)->translatedFormat('d F Y');
                            @endphp
                            <tr 
                                class="hover:bg-cream/5 transition-colors"
                                x-show="searchTerm === '' || '{{ strtolower($cust->name) }}'.includes(searchTerm.toLowerCase()) || '{{ strtolower($cust->email) }}'.includes(searchTerm.toLowerCase()) || '{{ $cust->phone }}'.includes(searchTerm)"
                            >
                                {{-- Customer Name --}}
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="h-8 w-8 rounded-full bg-primary-soft/30 text-primary font-bold text-xs flex items-center justify-center">
                                            {{ strtoupper(substr($cust->name, 0, 1)) }}
                                        </div>
                                        <div class="font-serif font-bold text-sm text-primary">
                                            {{ $cust->name }}
                                        </div>
                                    </div>
                                </td>

                                {{-- Contact info --}}
                                <td class="px-6 py-4 text-xs">
                                    <p class="font-semibold">{{ $cust->email }}</p>
                                    <p class="text-brandText-muted mt-0.5">{{ $cust->phone }}</p>
                                </td>

                                {{-- Joined date --}}
                                <td class="px-6 py-4 text-xs text-brandText-muted">
                                    <span class="flex items-center">
                                        <svg class="w-3.5 h-3.5 mr-1 text-gold" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                        {{ $joinDate }}
                                    </span>
                                </td>

                                {{-- Orders count --}}
                                <td class="px-6 py-4 text-center font-semibold text-xs font-mono">
                                    <span class="inline-flex items-center gap-1">
                                        <svg class="w-3.5 h-3.5 text-primary/70" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                                        </svg>
                                        {{ $cust->orders_count }} Transaksi
                                    </span>
                                </td>

                                {{-- Total spent --}}
                                <td class="px-6 py-4 text-right font-bold text-primary font-mono">
                                    Rp {{ number_format($cust->total_spent, 0, ',', '.') }}
                                </td>

                                {{-- Status Badge --}}
                                <td class="px-6 py-4 text-center">
                                    @if($cust->is_active)
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 border rounded-full text-[10px] font-bold tracking-wide uppercase bg-green-50 border-green-200 text-green-700">
                                            Aktif
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 border rounded-full text-[10px] font-bold tracking-wide uppercase bg-red-50 border-red-200 text-red-700">
                                            Banned
                                        </span>
                                    @endif
                                </td>

                                {{-- Toggle Status Button --}}
                                <td class="px-6 py-4 text-center">
                                    <form action="{{ route('admin.customers.toggle-status', $cust->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin {{ $cust->is_active ? 'menonaktifkan' : 'mengaktifkan' }} akun pelanggan &ldquo;{{ addslashes($cust->name) }}&rdquo;?')" class="inline-block">
                                        @csrf
                                        <button
                                            type="submit"
                                            class="px-3 py-1.5 text-[10px] font-bold rounded-lg border transition-all focus:outline-none {{ $cust->is_active ? 'border-red-200 hover:bg-red-50 text-red-600' : 'border-green-200 hover:bg-green-50 text-green-700' }}"
                                        >
                                            {{ $cust->is_active ? 'Blokir Akun' : 'Aktifkan Akun' }}
                                        </button>
                                    </form>
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

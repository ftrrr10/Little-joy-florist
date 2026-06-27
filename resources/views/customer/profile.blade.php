@extends('layouts.public')

@section('title', 'Profil Saya - Little Joy Jakarta')

@section('content')
<div class="max-w-3xl mx-auto px-4 py-12">
    <h2 class="font-serif text-2xl font-bold text-primary mb-6 reveal">Profil Pelanggan</h2>
    <div class="bg-white border border-brandOutline-soft/30 p-8 rounded-2xl shadow-sm space-y-4 font-sans reveal" data-delay="100">
        <div class="grid grid-cols-3 border-b border-brandSurface-low pb-3">
            <span class="text-xs font-bold text-brandText-muted">Nama Lengkap</span>
            <span class="col-span-2 text-sm font-semibold text-brandText">{{ auth()->user()->name }}</span>
        </div>
        <div class="grid grid-cols-3 border-b border-brandSurface-low pb-3">
            <span class="text-xs font-bold text-brandText-muted">Alamat Email</span>
            <span class="col-span-2 text-sm font-semibold text-brandText">{{ auth()->user()->email }}</span>
        </div>
        <div class="grid grid-cols-3 border-b border-brandSurface-low pb-3">
            <span class="text-xs font-bold text-brandText-muted">Nomor Telepon</span>
            <span class="col-span-2 text-sm font-semibold text-brandText">{{ auth()->user()->phone ?? '-' }}</span>
        </div>
        <div class="grid grid-cols-3">
            <span class="text-xs font-bold text-brandText-muted">Alamat Utama</span>
            <span class="col-span-2 text-sm font-semibold text-brandText whitespace-pre-wrap">{{ auth()->user()->address ?? 'Belum diisi' }}</span>
        </div>
    </div>
</div>
@endsection

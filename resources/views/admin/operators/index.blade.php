@extends('layouts.dashboard')

@section('title', 'Pengelolaan Akun Staf Operator')

@section('content')
<div class="space-y-6 font-sans" x-data="{
    searchTerm: '',
    showModal: false,
    editingOperator: null,
    operatorId: '',
    name: '',
    email: '',
    phone: '',
    password: '',
    openCreateModal() {
        this.editingOperator = null;
        this.operatorId = '';
        this.name = '';
        this.email = '';
        this.phone = '';
        this.password = '';
        this.showModal = true;
    },
    openEditModal(op) {
        this.editingOperator = op;
        this.operatorId = op.id;
        this.name = op.name;
        this.email = op.email;
        this.phone = op.phone;
        this.password = '';
        this.showModal = true;
    },
    closeModal() {
        this.showModal = false;
    }
}">
    {{-- Filters, Search Bar & Add Operator Button --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-white p-4 rounded-2xl border border-brandOutline-soft/30 shadow-sm reveal">
        {{-- Search Input --}}
        <div class="relative flex-1 max-w-md">
            <svg class="absolute left-3 top-3 w-4 h-4 text-brandText-muted" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
            <input
                type="text"
                x-model="searchTerm"
                placeholder="Cari nama, email, atau telepon operator..."
                class="w-full pl-9 pr-4 py-2 text-sm border border-brandOutline rounded-xl bg-cream-light/5 text-brandText focus:outline-none focus:ring-2 focus:ring-primary-soft focus:border-primary transition-all"
            />
        </div>

        <div class="flex items-center gap-4 self-end md:self-auto">
            <button
                type="button"
                @click="openCreateModal()"
                class="inline-flex items-center justify-center font-sans font-semibold rounded-lg transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 active:scale-[0.98] bg-primary text-white hover:bg-primary-dark focus:ring-primary-muted shadow-sm py-2 px-3 text-xs flex items-center gap-1 font-bold"
            >
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" />
                </svg>
                Tambah Staf Operator
            </button>
        </div>
    </div>

    {{-- Operator Table Card --}}
    <div class="bg-white border border-brandOutline-soft/30 rounded-2xl shadow-sm overflow-hidden reveal" data-delay="100">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-sm">
                <thead>
                    <tr class="bg-cream/15 border-b border-brandOutline-soft/30 text-xs font-bold text-brandText-muted uppercase tracking-wider">
                        <th class="px-6 py-4">Nama Staf</th>
                        <th class="px-6 py-4">Info Kontak</th>
                        <th class="px-6 py-4">Tanggal Bergabung</th>
                        <th class="px-6 py-4 text-center">Verifikasi Pembayaran</th>
                        <th class="px-6 py-4 text-center">Status Akun</th>
                        <th class="px-6 py-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-brandOutline-soft/20 text-brandText">
                    @if(empty($operators) || count($operators) === 0)
                        <tr>
                            <td colSpan="6" class="px-6 py-12 text-center text-brandText-muted/70 font-semibold">
                                Belum ada staf operator terdaftar.
                            </td>
                        </tr>
                    @else
                        @foreach($operators as $op)
                            @php
                                $joinDate = Carbon\Carbon::parse($op->created_at)->translatedFormat('d F Y');
                            @endphp
                            <tr 
                                class="hover:bg-cream/5 transition-colors"
                                x-show="searchTerm === '' || '{{ strtolower($op->name) }}'.includes(searchTerm.toLowerCase()) || '{{ strtolower($op->email) }}'.includes(searchTerm.toLowerCase()) || '{{ $op->phone }}'.includes(searchTerm)"
                            >
                                {{-- Operator Name --}}
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="h-8 w-8 rounded-full bg-primary-soft/30 text-primary font-bold text-xs flex items-center justify-center">
                                            {{ strtoupper(substr($op->name, 0, 1)) }}
                                        </div>
                                        <div class="font-serif font-bold text-sm text-primary">
                                            {{ $op->name }}
                                        </div>
                                    </div>
                                </td>

                                {{-- Contact info --}}
                                <td class="px-6 py-4 text-xs">
                                    <p class="font-semibold">{{ $op->email }}</p>
                                    <p class="text-brandText-muted mt-0.5">{{ $op->phone }}</p>
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

                                {{-- Verified payments count --}}
                                <td class="px-6 py-4 text-center font-semibold text-xs font-mono">
                                    <span class="inline-flex items-center gap-1">
                                        <svg class="w-3.5 h-3.5 text-primary/70" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        {{ $op->verified_payments_count }} Verifikasi
                                    </span>
                                </td>

                                {{-- Status Badge --}}
                                <td class="px-6 py-4 text-center">
                                    @if($op->is_active)
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 border rounded-full text-[10px] font-bold tracking-wide uppercase bg-green-50 border-green-200 text-green-700">
                                            Aktif
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 border rounded-full text-[10px] font-bold tracking-wide uppercase bg-red-50 border-red-200 text-red-700">
                                            Nonaktif
                                        </span>
                                    @endif
                                </td>

                                {{-- Actions --}}
                                <td class="px-6 py-4 text-center">
                                    <div class="flex justify-center items-center gap-2">
                                        <button
                                            type="button"
                                            @click="openEditModal({
                                                id: {{ $op->id }},
                                                name: '{{ addslashes($op->name) }}',
                                                email: '{{ addslashes($op->email) }}',
                                                phone: '{{ $op->phone }}'
                                            })"
                                            class="p-1 text-brandText-muted hover:text-primary hover:bg-cream/20 rounded-lg transition-colors focus:outline-none"
                                            title="Ubah data operator"
                                        >
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </button>
                                        <form action="{{ route('admin.operators.toggle-status', $op->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin {{ $op->is_active ? 'menonaktifkan' : 'mengaktifkan' }} akun operator &ldquo;{{ addslashes($op->name) }}&rdquo;?')" class="inline-block">
                                            @csrf
                                            <button
                                                type="submit"
                                                class="px-2.5 py-1 text-[10px] font-bold rounded-lg border transition-all focus:outline-none {{ $op->is_active ? 'border-red-200 hover:bg-red-50 text-red-600' : 'border-green-200 hover:bg-green-50 text-green-700' }}"
                                            >
                                                {{ $op->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>
    </div>

    {{-- Pendaftaran / Perubahan Staf Operator Modal --}}
    <div 
        x-show="showModal" 
        style="display: none;" 
        class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-center justify-center p-4"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
    >
        <div class="bg-white border border-brandOutline-soft/30 rounded-3xl max-w-md w-full overflow-hidden shadow-xl" @click.outside="closeModal()">
            {{-- Modal Header --}}
            <div class="px-6 py-4 border-b border-brandOutline-soft/20 flex items-center justify-between bg-cream/10">
                <h3 class="font-serif text-lg font-bold text-primary flex items-center">
                    <svg class="w-4.5 h-4.5 mr-2 text-gold" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                    <span x-text="editingOperator ? 'Perbarui Data Operator' : 'Pendaftaran Operator Baru'"></span>
                </h3>
                <button
                    type="button"
                    @click="closeModal()"
                    class="p-1 text-brandText-muted hover:text-primary rounded-lg transition-colors focus:outline-none"
                >
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            {{-- Modal Form --}}
            <form :action="editingOperator ? '{{ url('admin/operators') }}/' + operatorId : '{{ route('admin.operators.store') }}'" method="POST" class="p-6 space-y-4 font-sans text-xs" data-confirm="Apakah Anda yakin ingin menyimpan data operator ini?">
                @csrf
                <template x-if="editingOperator">
                    <input type="hidden" name="_method" value="PUT">
                </template>

                {{-- Operator Name --}}
                <div>
                    <label for="name" class="block text-xs font-bold text-brandText-muted uppercase tracking-wider mb-2">
                        Nama Lengkap Staf <span class="text-red-500">*</span>
                    </label>
                    <input
                        type="text"
                        id="name"
                        name="name"
                        required
                        x-model="name"
                        placeholder="Contoh: Puput Lestari"
                        class="w-full border border-brandOutline rounded-xl px-4 py-2.5 text-sm bg-cream-light/10 text-brandText focus:outline-none focus:ring-2 focus:ring-primary-soft focus:border-primary transition-all"
                    />
                </div>

                {{-- Operator Email --}}
                <div>
                    <label for="email" class="block text-xs font-bold text-brandText-muted uppercase tracking-wider mb-2">
                        Alamat Email <span class="text-red-500">*</span>
                    </label>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        required
                        x-model="email"
                        placeholder="Contoh: operator1@littlejoy.com"
                        class="w-full border border-brandOutline rounded-xl px-4 py-2.5 text-sm bg-cream-light/10 text-brandText focus:outline-none focus:ring-2 focus:ring-primary-soft focus:border-primary transition-all"
                    />
                </div>

                {{-- Operator Phone --}}
                <div>
                    <label for="phone" class="block text-xs font-bold text-brandText-muted uppercase tracking-wider mb-2">
                        Nomor Telepon / WhatsApp <span class="text-red-500">*</span>
                    </label>
                    <input
                        type="tel"
                        id="phone"
                        name="phone"
                        required
                        x-model="phone"
                        placeholder="Contoh: 081234567890"
                        class="w-full border border-brandOutline rounded-xl px-4 py-2.5 text-sm bg-cream-light/10 text-brandText focus:outline-none focus:ring-2 focus:ring-primary-soft focus:border-primary transition-all"
                    />
                </div>

                {{-- Operator Password --}}
                <div>
                    <label for="password" class="block text-xs font-bold text-brandText-muted uppercase tracking-wider mb-2 flex items-center justify-between">
                        <span>Kata Sandi <span class="text-red-500" x-show="!editingOperator">*</span></span>
                        <span class="text-[9px] font-normal text-brandText-muted italic" x-show="editingOperator">Biarkan kosong jika tidak diubah</span>
                    </label>
                    <div class="relative">
                        <svg class="absolute left-3.5 top-3 w-4 h-4 text-brandText-muted" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                        <input
                            type="password"
                            id="password"
                            name="password"
                            :required="!editingOperator"
                            x-model="password"
                            placeholder="••••••••"
                            class="w-full border border-brandOutline rounded-xl pl-10 pr-4 py-2.5 text-sm bg-cream-light/10 text-brandText focus:outline-none focus:ring-2 focus:ring-primary-soft focus:border-primary transition-all"
                        />
                    </div>
                </div>

                {{-- Modal Footer buttons --}}
                <div class="pt-4 border-t border-brandOutline-soft/20 flex space-x-3 justify-end">
                    <button
                        type="button"
                        @click="closeModal()"
                        class="px-4 py-2 text-xs font-semibold border border-brandOutline rounded-xl text-brandText hover:bg-gray-50 transition-all focus:outline-none"
                    >
                        Batal
                    </button>
                    <button
                        type="submit"
                        class="px-4 py-2 text-xs font-semibold bg-primary text-white hover:bg-primary-dark rounded-xl transition-all focus:outline-none shadow-md"
                    >
                        Simpan Operator
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

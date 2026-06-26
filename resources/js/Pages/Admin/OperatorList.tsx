import React, { useState } from 'react';
import DashboardLayout from '@/Layouts/DashboardLayout';
import { Head, useForm, usePage } from '@inertiajs/react';
import { PageProps } from '@/types';
import Alert from '@/Components/common/Alert';
import Button from '@/Components/common/Button';
import EmptyState from '@/Components/common/EmptyState';
import { 
    Search, 
    User, 
    Calendar, 
    CheckCircle, 
    Plus, 
    X, 
    Edit, 
    ShieldCheck, 
    ShieldAlert, 
    Lock 
} from 'lucide-react';

interface Operator {
    id: number;
    name: string;
    email: string;
    phone: string;
    is_active: boolean;
    created_at: string;
    verified_payments_count: number;
}

interface OperatorListProps extends PageProps {
    operators: Operator[];
}

export default function OperatorList() {
    const { operators = [], flash } = usePage<OperatorListProps>().props;
    const [searchTerm, setSearchTerm] = useState('');
    const [showModal, setShowModal] = useState(false);
    const [editingOperator, setEditingOperator] = useState<Operator | null>(null);

    // Form setup for create/update operator
    const { data, setData, post, put, processing, reset, errors } = useForm({
        name: '',
        email: '',
        phone: '',
        password: '',
    });

    const handleOpenCreateModal = () => {
        setEditingOperator(null);
        reset();
        setShowModal(true);
    };

    const handleOpenEditModal = (op: Operator) => {
        setEditingOperator(op);
        setData({
            name: op.name,
            email: op.email,
            phone: op.phone,
            password: '', // blank by default when editing
        });
        setShowModal(true);
    };

    const handleCloseModal = () => {
        setShowModal(false);
        setEditingOperator(null);
        reset();
    };

    const handleSubmit = (e: React.FormEvent) => {
        e.preventDefault();
        
        if (editingOperator) {
            put(route('admin.operators.update', editingOperator.id), {
                onSuccess: () => {
                    handleCloseModal();
                },
            });
        } else {
            post(route('admin.operators.store'), {
                onSuccess: () => {
                    handleCloseModal();
                },
            });
        }
    };

    const handleToggleStatus = (op: Operator) => {
        const actionText = op.is_active ? 'menonaktifkan' : 'mengaktifkan';
        if (confirm(`Apakah Anda yakin ingin ${actionText} akun operator ${op.name}?`)) {
            post(route('admin.operators.toggle-status', op.id), {
                preserveScroll: true,
            });
        }
    };

    // Filter logic
    const filteredOperators = operators.filter((op) => {
        return (
            op.name.toLowerCase().includes(searchTerm.toLowerCase()) ||
            op.email.toLowerCase().includes(searchTerm.toLowerCase()) ||
            op.phone.includes(searchTerm)
        );
    });

    return (
        <DashboardLayout title="Pengelolaan Akun Staf Operator">
            <Head title="Kelola Staf Operator | Little Joy Management" />

            <div className="space-y-6 font-sans">
                {flash?.success && (
                    <Alert variant="success" message={flash.success} />
                )}
                {flash?.error && (
                    <Alert variant="danger" message={flash.error} />
                )}

                {/* Filters, Search Bar & Add Operator Button */}
                <div className="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-white p-4 rounded-2xl border border-brandOutline-soft/30 shadow-sm">
                    {/* Search Input */}
                    <div className="relative flex-1 max-w-md">
                        <Search className="absolute left-3 top-3 w-4 h-4 text-brandText-muted" />
                        <input
                            type="text"
                            placeholder="Cari nama, email, atau telepon operator..."
                            value={searchTerm}
                            onChange={(e) => setSearchTerm(e.target.value)}
                            className="w-full pl-9 pr-4 py-2 text-sm border border-brandOutline rounded-xl bg-cream-light/5 text-brandText focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary transition-all"
                        />
                    </div>

                    <div className="flex items-center gap-4 self-end md:self-auto">
                        <div className="text-xs text-brandText-muted font-semibold hidden sm:block">
                            Menampilkan {filteredOperators.length} dari {operators.length} operator
                        </div>
                        <Button
                            type="button"
                            variant="primary"
                            onClick={handleOpenCreateModal}
                            className="py-2 text-xs flex items-center gap-1 shadow-sm"
                        >
                            <Plus className="w-4 h-4" /> Tambah Staf Operator
                        </Button>
                    </div>
                </div>

                {/* Operator Table */}
                {filteredOperators.length === 0 ? (
                    <div className="bg-white border border-brandOutline-soft/30 rounded-2xl p-12 shadow-sm text-center">
                        <EmptyState
                            title="Tidak Ada Staf Operator"
                            message="Belum ada akun operator terdaftar yang cocok dengan pencarian Anda."
                        />
                    </div>
                ) : (
                    <div className="bg-white border border-brandOutline-soft/30 rounded-2xl shadow-sm overflow-hidden">
                        <div className="overflow-x-auto">
                            <table className="w-full text-left border-collapse text-sm">
                                <thead>
                                    <tr className="bg-cream/15 border-b border-brandOutline-soft/30 text-xs font-bold text-brandText-muted uppercase tracking-wider">
                                        <th className="px-6 py-4">Nama Staf</th>
                                        <th className="px-6 py-4">Info Kontak</th>
                                        <th className="px-6 py-4">Tanggal Bergabung</th>
                                        <th className="px-6 py-4 text-center">Verifikasi Pembayaran</th>
                                        <th className="px-6 py-4 text-center">Status Akun</th>
                                        <th className="px-6 py-4 text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody className="divide-y divide-brandOutline-soft/20 text-brandText">
                                    {filteredOperators.map((op) => {
                                        const joinDate = new Date(op.created_at).toLocaleDateString('id-ID', {
                                            day: 'numeric',
                                            month: 'long',
                                            year: 'numeric',
                                        });

                                        return (
                                            <tr key={op.id} className="hover:bg-cream/5 transition-colors">
                                                {/* Operator Name */}
                                                <td className="px-6 py-4">
                                                    <div className="flex items-center gap-3">
                                                        <div className="h-8 w-8 rounded-full bg-primary-soft/30 text-primary font-bold text-xs flex items-center justify-center">
                                                            {op.name.charAt(0).toUpperCase()}
                                                        </div>
                                                        <div className="font-serif font-bold text-sm text-primary">
                                                            {op.name}
                                                        </div>
                                                    </div>
                                                </td>

                                                {/* Contact info */}
                                                <td className="px-6 py-4 text-xs">
                                                    <p className="font-semibold">{op.email}</p>
                                                    <p className="text-brandText-muted mt-0.5">{op.phone}</p>
                                                </td>

                                                {/* Joined date */}
                                                <td className="px-6 py-4 text-xs text-brandText-muted">
                                                    <span className="flex items-center">
                                                        <Calendar className="w-3.5 h-3.5 mr-1 text-gold" />
                                                        {joinDate}
                                                    </span>
                                                </td>

                                                {/* Verified payments count */}
                                                <td className="px-6 py-4 text-center font-semibold text-xs">
                                                    <span className="inline-flex items-center gap-1">
                                                        <CheckCircle className="w-3.5 h-3.5 text-primary/70" />
                                                        {op.verified_payments_count} Verifikasi
                                                    </span>
                                                </td>

                                                {/* Status Badge */}
                                                <td className="px-6 py-4 text-center">
                                                    <span className={`inline-flex items-center gap-1 px-2.5 py-0.5 border rounded-full text-[10px] font-bold tracking-wide uppercase ${
                                                        op.is_active 
                                                            ? 'bg-green-50 border-green-200 text-green-700' 
                                                            : 'bg-red-50 border-red-200 text-red-700'
                                                    }`}>
                                                        {op.is_active ? (
                                                            <>
                                                                <ShieldCheck className="w-3 h-3 text-green-600" />
                                                                Aktif
                                                            </>
                                                        ) : (
                                                            <>
                                                                <ShieldAlert className="w-3 h-3 text-red-600" />
                                                                Nonaktif
                                                            </>
                                                        )}
                                                    </span>
                                                </td>

                                                {/* Actions */}
                                                <td className="px-6 py-4 text-center">
                                                    <div className="flex justify-center items-center gap-2">
                                                        <button
                                                            type="button"
                                                            onClick={() => handleOpenEditModal(op)}
                                                            className="p-1 text-brandText-muted hover:text-primary hover:bg-cream/20 rounded-lg transition-colors focus:outline-none"
                                                            title="Ubah data operator"
                                                        >
                                                            <Edit className="w-4 h-4" />
                                                        </button>
                                                        <button
                                                            type="button"
                                                            onClick={() => handleToggleStatus(op)}
                                                            className={`px-2.5 py-1 text-[10px] font-bold rounded-lg border transition-all ${
                                                                op.is_active
                                                                    ? 'border-red-200 hover:bg-red-50 text-red-600'
                                                                    : 'border-green-200 hover:bg-green-50 text-green-700'
                                                            }`}
                                                        >
                                                            {op.is_active ? 'Nonaktifkan' : 'Aktifkan'}
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                        );
                                    })}
                                </tbody>
                            </table>
                        </div>
                    </div>
                )}
            </div>

            {/* 3. Pendaftaran / Perubahan Staf Operator Modal */}
            {showModal && (
                <div className="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-center justify-center p-4">
                    <div className="bg-white border border-brandOutline-soft/30 rounded-3xl max-w-md w-full overflow-hidden shadow-xl animate-in fade-in zoom-in-95 duration-200">
                        {/* Modal Header */}
                        <div className="px-6 py-4 border-b border-brandOutline-soft/20 flex items-center justify-between bg-cream/10">
                            <h3 className="font-serif text-lg font-bold text-primary flex items-center">
                                <User className="w-4.5 h-4.5 mr-2 text-gold" />
                                {editingOperator ? 'Perbarui Data Operator' : 'Pendaftaran Operator Baru'}
                            </h3>
                            <button
                                type="button"
                                onClick={handleCloseModal}
                                className="p-1 text-brandText-muted hover:text-primary rounded-lg transition-colors focus:outline-none"
                            >
                                <X className="w-5 h-5" />
                            </button>
                        </div>

                        {/* Modal Form */}
                        <form onSubmit={handleSubmit} className="p-6 space-y-4 font-sans">
                            {/* Operator Name */}
                            <div>
                                <label htmlFor="name" className="block text-xs font-bold text-brandText-muted uppercase tracking-wider mb-2">
                                    Nama Lengkap Staf <span className="text-red-500">*</span>
                                </label>
                                <input
                                    type="text"
                                    id="name"
                                    required
                                    value={data.name}
                                    onChange={(e) => setData('name', e.target.value)}
                                    placeholder="Contoh: Puput Lestari"
                                    className={`w-full border rounded-xl px-4 py-2.5 text-sm bg-cream-light/10 text-brandText focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary transition-all ${
                                        errors.name ? 'border-red-300 focus:ring-red-500' : 'border-brandOutline'
                                    }`}
                                />
                                {errors.name && (
                                    <p className="text-xs text-red-500 mt-1.5 font-medium">{errors.name}</p>
                                )}
                            </div>

                            {/* Operator Email */}
                            <div>
                                <label htmlFor="email" className="block text-xs font-bold text-brandText-muted uppercase tracking-wider mb-2">
                                    Alamat Email <span className="text-red-500">*</span>
                                </label>
                                <input
                                    type="email"
                                    id="email"
                                    required
                                    value={data.email}
                                    onChange={(e) => setData('email', e.target.value)}
                                    placeholder="Contoh: operator1@littlejoy.com"
                                    className={`w-full border rounded-xl px-4 py-2.5 text-sm bg-cream-light/10 text-brandText focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary transition-all ${
                                        errors.email ? 'border-red-300 focus:ring-red-500' : 'border-brandOutline'
                                    }`}
                                />
                                {errors.email && (
                                    <p className="text-xs text-red-500 mt-1.5 font-medium">{errors.email}</p>
                                )}
                            </div>

                            {/* Operator Phone */}
                            <div>
                                <label htmlFor="phone" className="block text-xs font-bold text-brandText-muted uppercase tracking-wider mb-2">
                                    Nomor Telepon / WhatsApp <span className="text-red-500">*</span>
                                </label>
                                <input
                                    type="tel"
                                    id="phone"
                                    required
                                    value={data.phone}
                                    onChange={(e) => setData('phone', e.target.value)}
                                    placeholder="Contoh: 081234567890"
                                    className={`w-full border rounded-xl px-4 py-2.5 text-sm bg-cream-light/10 text-brandText focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary transition-all ${
                                        errors.phone ? 'border-red-300 focus:ring-red-500' : 'border-brandOutline'
                                    }`}
                                />
                                {errors.phone && (
                                    <p className="text-xs text-red-500 mt-1.5 font-medium">{errors.phone}</p>
                                )}
                            </div>

                            {/* Operator Password */}
                            <div>
                                <label htmlFor="password" className="block text-xs font-bold text-brandText-muted uppercase tracking-wider mb-2 flex items-center justify-between">
                                    <span>Kata Sandi {editingOperator ? '' : <span className="text-red-500">*</span>}</span>
                                    {editingOperator && <span className="text-[10px] font-normal text-brandText-muted italic">Biarkan kosong jika tidak diubah</span>}
                                </label>
                                <div className="relative">
                                    <Lock className="absolute left-3.5 top-3 w-4 h-4 text-brandText-muted" />
                                    <input
                                        type="password"
                                        id="password"
                                        required={!editingOperator}
                                        value={data.password}
                                        onChange={(e) => setData('password', e.target.value)}
                                        placeholder={editingOperator ? "••••••••" : "Kata Sandi Akun Baru"}
                                        className={`w-full border rounded-xl pl-10 pr-4 py-2.5 text-sm bg-cream-light/10 text-brandText focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary transition-all ${
                                            errors.password ? 'border-red-300 focus:ring-red-500' : 'border-brandOutline'
                                        }`}
                                    />
                                </div>
                                {errors.password && (
                                    <p className="text-xs text-red-500 mt-1.5 font-medium">{errors.password}</p>
                                )}
                            </div>

                            {/* Modal Footer buttons */}
                            <div className="pt-4 border-t border-brandOutline-soft/20 flex space-x-3 justify-end">
                                <Button
                                    type="button"
                                    variant="outline"
                                    onClick={handleCloseModal}
                                    className="py-2 text-xs"
                                    disabled={processing}
                                >
                                    Batal
                                </Button>
                                <Button
                                    type="submit"
                                    variant="primary"
                                    className="py-2 text-xs shadow-md"
                                    isLoading={processing}
                                    disabled={processing}
                                >
                                    {editingOperator ? 'Simpan Perubahan' : 'Daftarkan Staf'}
                                </Button>
                            </div>
                        </form>
                    </div>
                </div>
            )}
        </DashboardLayout>
    );
}

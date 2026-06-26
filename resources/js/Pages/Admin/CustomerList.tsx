import React, { useState } from 'react';
import DashboardLayout from '@/Layouts/DashboardLayout';
import { Head, router, usePage } from '@inertiajs/react';
import { PageProps } from '@/types';
import CurrencyText from '@/Components/common/CurrencyText';
import Alert from '@/Components/common/Alert';
import EmptyState from '@/Components/common/EmptyState';
import { 
    Search, 
    User, 
    Calendar, 
    ShoppingBag, 
    DollarSign, 
    ShieldAlert,
    ShieldCheck 
} from 'lucide-react';

interface Customer {
    id: number;
    name: string;
    email: string;
    phone: string;
    is_active: boolean;
    created_at: string;
    orders_count: number;
    total_spent: number;
}

interface CustomerListProps extends PageProps {
    customers: Customer[];
}

export default function CustomerList() {
    const { customers = [], flash } = usePage<CustomerListProps>().props;
    const [searchTerm, setSearchTerm] = useState('');

    const handleToggleStatus = (customer: Customer) => {
        const actionText = customer.is_active ? 'menonaktifkan' : 'mengaktifkan';
        if (confirm(`Apakah Anda yakin ingin ${actionText} akun pelanggan ${customer.name}?`)) {
            router.post(route('admin.customers.toggle-status', customer.id), {}, {
                preserveScroll: true,
            });
        }
    };

    // Filter logic
    const filteredCustomers = customers.filter((cust) => {
        return (
            cust.name.toLowerCase().includes(searchTerm.toLowerCase()) ||
            cust.email.toLowerCase().includes(searchTerm.toLowerCase()) ||
            cust.phone.includes(searchTerm)
        );
    });

    return (
        <DashboardLayout title="Pengelolaan Data Pelanggan">
            <Head title="Daftar Pelanggan | Little Joy Management" />

            <div className="space-y-6 font-sans">
                {flash?.success && (
                    <Alert variant="success" message={flash.success} />
                )}
                {flash?.error && (
                    <Alert variant="danger" message={flash.error} />
                )}

                {/* Filters and Search Bar */}
                <div className="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-white p-4 rounded-2xl border border-brandOutline-soft/30 shadow-sm">
                    {/* Search Input */}
                    <div className="relative flex-1 max-w-md">
                        <Search className="absolute left-3 top-3 w-4 h-4 text-brandText-muted" />
                        <input
                            type="text"
                            placeholder="Cari nama, email, atau telepon pelanggan..."
                            value={searchTerm}
                            onChange={(e) => setSearchTerm(e.target.value)}
                            className="w-full pl-9 pr-4 py-2 text-sm border border-brandOutline rounded-xl bg-cream-light/5 text-brandText focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary transition-all"
                        />
                    </div>

                    <div className="text-xs text-brandText-muted font-semibold">
                        Menampilkan {filteredCustomers.length} dari {customers.length} pelanggan terdaftar
                    </div>
                </div>

                {/* Customer Table */}
                {filteredCustomers.length === 0 ? (
                    <div className="bg-white border border-brandOutline-soft/30 rounded-2xl p-12 shadow-sm text-center">
                        <EmptyState
                            title="Tidak Ada Pelanggan"
                            message="Tidak ada data pelanggan terdaftar yang cocok dengan kata kunci pencarian."
                        />
                    </div>
                ) : (
                    <div className="bg-white border border-brandOutline-soft/30 rounded-2xl shadow-sm overflow-hidden">
                        <div className="overflow-x-auto">
                            <table className="w-full text-left border-collapse text-sm">
                                <thead>
                                    <tr className="bg-cream/15 border-b border-brandOutline-soft/30 text-xs font-bold text-brandText-muted uppercase tracking-wider">
                                        <th className="px-6 py-4">Nama Pelanggan</th>
                                        <th className="px-6 py-4">Info Kontak</th>
                                        <th className="px-6 py-4">Tanggal Bergabung</th>
                                        <th className="px-6 py-4 text-center">Jumlah Order</th>
                                        <th className="px-6 py-4 text-right">Total Belanja</th>
                                        <th className="px-6 py-4 text-center">Status Akun</th>
                                        <th className="px-6 py-4 text-center">Tindakan</th>
                                    </tr>
                                </thead>
                                <tbody className="divide-y divide-brandOutline-soft/20 text-brandText">
                                    {filteredCustomers.map((cust) => {
                                        const joinDate = new Date(cust.created_at).toLocaleDateString('id-ID', {
                                            day: 'numeric',
                                            month: 'long',
                                            year: 'numeric',
                                        });

                                        return (
                                            <tr key={cust.id} className="hover:bg-cream/5 transition-colors">
                                                {/* Customer Name */}
                                                <td className="px-6 py-4">
                                                    <div className="flex items-center gap-3">
                                                        <div className="h-8 w-8 rounded-full bg-primary-soft/30 text-primary font-bold text-xs flex items-center justify-center">
                                                            {cust.name.charAt(0).toUpperCase()}
                                                        </div>
                                                        <div className="font-serif font-bold text-sm text-primary">
                                                            {cust.name}
                                                        </div>
                                                    </div>
                                                </td>

                                                {/* Contact info */}
                                                <td className="px-6 py-4 text-xs">
                                                    <p className="font-semibold">{cust.email}</p>
                                                    <p className="text-brandText-muted mt-0.5">{cust.phone}</p>
                                                </td>

                                                {/* Joined date */}
                                                <td className="px-6 py-4 text-xs text-brandText-muted">
                                                    <span className="flex items-center">
                                                        <Calendar className="w-3.5 h-3.5 mr-1 text-gold" />
                                                        {joinDate}
                                                    </span>
                                                </td>

                                                {/* Orders count */}
                                                <td className="px-6 py-4 text-center font-semibold text-xs">
                                                    <span className="inline-flex items-center gap-1">
                                                        <ShoppingBag className="w-3.5 h-3.5 text-primary/70" />
                                                        {cust.orders_count} Transaksi
                                                    </span>
                                                </td>

                                                {/* Total spent */}
                                                <td className="px-6 py-4 text-right font-bold text-primary">
                                                    <CurrencyText value={cust.total_spent} />
                                                </td>

                                                {/* Status Badge */}
                                                <td className="px-6 py-4 text-center">
                                                    <span className={`inline-flex items-center gap-1 px-2.5 py-0.5 border rounded-full text-[10px] font-bold tracking-wide uppercase ${
                                                        cust.is_active 
                                                            ? 'bg-green-50 border-green-200 text-green-700' 
                                                            : 'bg-red-50 border-red-200 text-red-700'
                                                    }`}>
                                                        {cust.is_active ? (
                                                            <>
                                                                <ShieldCheck className="w-3 h-3 text-green-600" />
                                                                Aktif
                                                            </>
                                                        ) : (
                                                            <>
                                                                <ShieldAlert className="w-3 h-3 text-red-600" />
                                                                Banned
                                                            </>
                                                        )}
                                                    </span>
                                                </td>

                                                {/* Toggle Status Button */}
                                                <td className="px-6 py-4 text-center">
                                                    <button
                                                        type="button"
                                                        onClick={() => handleToggleStatus(cust)}
                                                        className={`px-3 py-1.5 text-[10px] font-bold rounded-lg border transition-all ${
                                                            cust.is_active
                                                                ? 'border-red-200 hover:bg-red-50 text-red-600'
                                                                : 'border-green-200 hover:bg-green-50 text-green-700'
                                                        }`}
                                                    >
                                                        {cust.is_active ? 'Blokir Akun' : 'Aktifkan Akun'}
                                                    </button>
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
        </DashboardLayout>
    );
}

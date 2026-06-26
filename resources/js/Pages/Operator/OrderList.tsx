import React, { useState } from 'react';
import DashboardLayout from '@/Layouts/DashboardLayout';
import { Head, Link } from '@inertiajs/react';
import CurrencyText from '@/Components/common/CurrencyText';
import EmptyState from '@/Components/common/EmptyState';
import Alert from '@/Components/common/Alert';
import { Order } from '@/types';
import { Search, Eye, Calendar, User, ShoppingBag } from 'lucide-react';

interface ListProps {
    orders: Order[];
}

export default function OrderList({ orders = [], flash }: ListProps & { flash?: any }) {
    const [searchTerm, setSearchTerm] = useState('');
    const [statusFilter, setStatusFilter] = useState('all');

    // Status formatting helpers
    const getStatusBadge = (status: string) => {
        switch (status) {
            case 'pending_payment':
                return 'bg-amber-50 border-amber-200 text-amber-800';
            case 'waiting_verification':
                return 'bg-blue-50 border-blue-200 text-blue-800';
            case 'paid':
                return 'bg-green-50 border-green-200 text-green-800';
            case 'processing':
                return 'bg-indigo-50 border-indigo-200 text-indigo-800';
            case 'ready':
                return 'bg-indigo-50 border-indigo-200 text-indigo-800';
            case 'shipped':
                return 'bg-purple-50 border-purple-200 text-purple-800';
            case 'completed':
                return 'bg-green-50 border-green-200 text-green-800';
            case 'cancelled':
                return 'bg-red-50 border-red-200 text-red-800';
            case 'rejected':
                return 'bg-red-50 border-red-200 text-red-800';
            default:
                return 'bg-gray-50 border-gray-200 text-gray-800';
        }
    };

    const getStatusLabel = (status: string) => {
        switch (status) {
            case 'pending_payment': return 'Belum Bayar';
            case 'waiting_verification': return 'Menunggu Verifikasi';
            case 'paid': return 'Lunas';
            case 'processing': return 'Diproses';
            case 'ready': return 'Siap Dikirim';
            case 'shipped': return 'Dikirim';
            case 'completed': return 'Selesai';
            case 'cancelled': return 'Dibatalkan';
            case 'rejected': return 'Ditolak';
            default: return status;
        }
    };

    // Filter and search logic
    const filteredOrders = orders.filter((order) => {
        const matchesSearch = 
            order.order_number.toLowerCase().includes(searchTerm.toLowerCase()) ||
            (order.user?.name || '').toLowerCase().includes(searchTerm.toLowerCase()) ||
            order.recipient_name.toLowerCase().includes(searchTerm.toLowerCase());

        const matchesStatus = 
            statusFilter === 'all' ||
            (statusFilter === 'unpaid' && ['pending_payment', 'rejected'].includes(order.order_status)) ||
            (statusFilter === 'waiting' && order.order_status === 'waiting_verification') ||
            (statusFilter === 'processing' && ['paid', 'processing', 'ready'].includes(order.order_status)) ||
            (statusFilter === 'shipped' && order.order_status === 'shipped') ||
            (statusFilter === 'completed' && order.order_status === 'completed') ||
            (statusFilter === 'cancelled' && order.order_status === 'cancelled');

        return matchesSearch && matchesStatus;
    });

    const filterTabs = [
        { id: 'all', label: 'Semua' },
        { id: 'unpaid', label: 'Belum Bayar' },
        { id: 'waiting', label: 'Verifikasi Pembayaran' },
        { id: 'processing', label: 'Sedang Diproses' },
        { id: 'shipped', label: 'Dikirim' },
        { id: 'completed', label: 'Selesai' },
        { id: 'cancelled', label: 'Dibatalkan' },
    ];

    return (
        <DashboardLayout title="Kelola Pesanan Masuk">
            <Head title="Kelola Pesanan | Little Joy Management" />

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
                            placeholder="Cari nomor pesanan atau nama pelanggan..."
                            value={searchTerm}
                            onChange={(e) => setSearchTerm(e.target.value)}
                            className="w-full pl-9 pr-4 py-2 text-sm border border-brandOutline rounded-xl bg-cream-light/5 text-brandText focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary transition-all"
                        />
                    </div>

                    {/* Filters count indicator */}
                    <div className="text-xs text-brandText-muted font-semibold">
                        Menampilkan {filteredOrders.length} dari {orders.length} pesanan
                    </div>
                </div>

                {/* Tabs */}
                <div className="border-b border-brandOutline flex space-x-6 overflow-x-auto whitespace-nowrap scrollbar-none pb-0.5">
                    {filterTabs.map((tab) => (
                        <button
                            key={tab.id}
                            onClick={() => setStatusFilter(tab.id)}
                            className={`pb-3 text-sm font-semibold transition-all border-b-2 focus:outline-none ${
                                statusFilter === tab.id
                                    ? 'border-primary text-primary'
                                    : 'border-transparent text-brandText-muted hover:text-primary'
                            }`}
                        >
                            {tab.label}
                        </button>
                    ))}
                </div>

                {/* Orders Table */}
                {filteredOrders.length === 0 ? (
                    <div className="bg-white border border-brandOutline-soft/30 rounded-2xl p-12 shadow-sm text-center">
                        <EmptyState
                            title="Tidak Ada Transaksi"
                            message="Tidak ada pesanan masuk yang cocok dengan pencarian atau status terpilih."
                        />
                    </div>
                ) : (
                    <div className="bg-white border border-brandOutline-soft/30 rounded-2xl shadow-sm overflow-hidden">
                        <div className="overflow-x-auto">
                            <table className="w-full text-left border-collapse text-sm">
                                <thead>
                                    <tr className="bg-cream/15 border-b border-brandOutline-soft/30 text-xs font-bold text-brandText-muted uppercase tracking-wider">
                                        <th className="px-6 py-4">No. Pesanan</th>
                                        <th className="px-6 py-4">Pelanggan</th>
                                        <th className="px-6 py-4">Tanggal Order</th>
                                        <th className="px-6 py-4">Pengiriman</th>
                                        <th className="px-6 py-4">Total Tagihan</th>
                                        <th className="px-6 py-4 text-center">Status</th>
                                        <th className="px-6 py-4 text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody className="divide-y divide-brandOutline-soft/20 text-brandText">
                                    {filteredOrders.map((order) => {
                                        const orderDate = new Date(order.order_date).toLocaleDateString('id-ID', {
                                            day: 'numeric',
                                            month: 'short',
                                            year: '2-digit',
                                        });

                                        const delivDate = new Date(order.delivery_date).toLocaleDateString('id-ID', {
                                            day: 'numeric',
                                            month: 'short',
                                            year: '2-digit',
                                        });

                                        const badgeClass = getStatusBadge(order.order_status);
                                        const statusLabel = getStatusLabel(order.order_status);

                                        return (
                                            <tr key={order.id} className="hover:bg-cream/5 transition-colors">
                                                {/* Order Number */}
                                                <td className="px-6 py-4 font-mono font-bold text-primary">
                                                    #{order.order_number}
                                                </td>

                                                {/* Customer Name */}
                                                <td className="px-6 py-4">
                                                    <div className="font-semibold">{order.recipient_name}</div>
                                                    <div className="text-[10px] text-brandText-muted flex items-center mt-0.5">
                                                        <User className="w-3 h-3 mr-1" />
                                                        {order.user?.name || 'Guest'}
                                                    </div>
                                                </td>

                                                {/* Order Date */}
                                                <td className="px-6 py-4 text-xs text-brandText-muted">
                                                    <span className="flex items-center">
                                                        <Calendar className="w-3.5 h-3.5 mr-1 text-gold" />
                                                        {orderDate}
                                                    </span>
                                                </td>

                                                {/* Delivery Date */}
                                                <td className="px-6 py-4 text-xs text-brandText-muted font-semibold">
                                                    <span className="flex items-center">
                                                        <Calendar className="w-3.5 h-3.5 mr-1 text-primary/75" />
                                                        {delivDate}
                                                    </span>
                                                </td>

                                                {/* Total Price */}
                                                <td className="px-6 py-4 font-bold text-primary">
                                                    <CurrencyText value={Number(order.total)} />
                                                </td>

                                                {/* Status Badge */}
                                                <td className="px-6 py-4 text-center">
                                                    <span className={`inline-block px-2.5 py-0.5 border rounded-full text-[10px] font-bold tracking-wide uppercase ${badgeClass}`}>
                                                        {statusLabel}
                                                    </span>
                                                </td>

                                                {/* Action Buttons */}
                                                <td className="px-6 py-4 text-center">
                                                    <Link href={route('operator.orders.show', order.order_number)}>
                                                        <button
                                                            type="button"
                                                            className="p-1.5 text-brandText-muted hover:text-primary hover:bg-cream/25 rounded-lg transition-all focus:outline-none"
                                                            title="Tinjau detail pesanan"
                                                        >
                                                            <Eye className="w-4 h-4" />
                                                        </button>
                                                    </Link>
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

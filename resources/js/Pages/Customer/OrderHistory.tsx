import React, { useState } from 'react';
import { Head, Link } from '@inertiajs/react';
import PublicLayout from '@/Layouts/PublicLayout';
import Button from '@/Components/common/Button';
import CurrencyText from '@/Components/common/CurrencyText';
import EmptyState from '@/Components/common/EmptyState';
import Alert from '@/Components/common/Alert';
import { Order } from '@/types';
import { ShoppingBag, CreditCard, Eye, Calendar, AlertCircle } from 'lucide-react';

interface HistoryProps {
    orders: Order[];
}

export default function OrderHistory({ orders = [], flash }: HistoryProps & { flash?: any }) {
    const [activeTab, setActiveTab] = useState<string>('all');

    // Indonesian translations for order status
    const getStatusLabel = (status: string) => {
        switch (status) {
            case 'pending_payment':
                return { text: 'Belum Bayar', style: 'bg-amber-50 border-amber-200 text-amber-800' };
            case 'waiting_verification':
                return { text: 'Menunggu Verifikasi', style: 'bg-blue-50 border-blue-200 text-blue-800' };
            case 'paid':
                return { text: 'Sudah Bayar', style: 'bg-indigo-50 border-indigo-200 text-indigo-800' };
            case 'processing':
                return { text: 'Sedang Diproses', style: 'bg-indigo-50 border-indigo-200 text-indigo-800' };
            case 'ready':
                return { text: 'Siap Dikirim', style: 'bg-indigo-50 border-indigo-200 text-indigo-800' };
            case 'shipped':
                return { text: 'Sedang Dikirim', style: 'bg-purple-50 border-purple-200 text-purple-800' };
            case 'completed':
                return { text: 'Selesai', style: 'bg-green-50 border-green-200 text-green-800' };
            case 'cancelled':
                return { text: 'Dibatalkan', style: 'bg-red-50 border-red-200 text-red-800' };
            case 'rejected':
                return { text: 'Pembayaran Ditolak', style: 'bg-red-50 border-red-200 text-red-800' };
            default:
                return { text: status, style: 'bg-gray-50 border-gray-200 text-gray-800' };
        }
    };

    // Filter logic based on tabs
    const filteredOrders = orders.filter((order) => {
        if (activeTab === 'all') return true;
        if (activeTab === 'unpaid') return ['pending_payment', 'rejected'].includes(order.order_status);
        if (activeTab === 'waiting') return order.order_status === 'waiting_verification';
        if (activeTab === 'processing') return ['paid', 'processing', 'ready'].includes(order.order_status);
        if (activeTab === 'shipped') return order.order_status === 'shipped';
        if (activeTab === 'completed') return order.order_status === 'completed';
        if (activeTab === 'cancelled') return order.order_status === 'cancelled';
        return true;
    });

    const tabs = [
        { id: 'all', label: 'Semua' },
        { id: 'unpaid', label: 'Belum Bayar' },
        { id: 'waiting', label: 'Menunggu Verifikasi' },
        { id: 'processing', label: 'Diproses' },
        { id: 'shipped', label: 'Dikirim' },
        { id: 'completed', label: 'Selesai' },
        { id: 'cancelled', label: 'Dibatalkan' },
    ];

    return (
        <PublicLayout>
            <Head title="Riwayat Pesanan | Little Joy Jakarta" />

            <div className="bg-cream-light/30 min-h-screen py-12 font-sans">
                <div className="max-w-5xl mx-auto px-4">
                    {/* Header */}
                    <div className="mb-8">
                        <h1 className="font-serif text-3xl font-bold text-primary tracking-tight">
                            Riwayat Pesanan
                        </h1>
                        <p className="text-sm text-brandText-muted mt-1">
                            Pantau status pembayaran dan pengiriman rangkaian bunga Anda di sini.
                        </p>
                    </div>

                    {flash?.success && (
                        <div className="mb-6">
                            <Alert variant="success" message={flash.success} />
                        </div>
                    )}
                    {flash?.error && (
                        <div className="mb-6">
                            <Alert variant="danger" message={flash.error} />
                        </div>
                    )}

                    {/* Tabs navigation */}
                    <div className="mb-6 border-b border-brandOutline-soft overflow-x-auto whitespace-nowrap scrollbar-none flex space-x-6">
                        {tabs.map((tab) => (
                            <button
                                key={tab.id}
                                onClick={() => setActiveTab(tab.id)}
                                className={`pb-3 text-sm font-semibold transition-all border-b-2 focus:outline-none ${
                                    activeTab === tab.id
                                        ? 'border-primary text-primary'
                                        : 'border-transparent text-brandText-muted hover:text-primary'
                                }`}
                            >
                                {tab.label}
                            </button>
                        ))}
                    </div>

                    {/* Orders List */}
                    {filteredOrders.length === 0 ? (
                        <div className="bg-white border border-brandOutline-soft/30 rounded-2xl p-12 shadow-sm text-center">
                            <EmptyState
                                title="Tidak Ada Pesanan"
                                message="Tidak ada transaksi yang cocok dengan filter yang Anda pilih."
                            />
                            <div className="mt-8 flex justify-center">
                                <Link href={route('catalogue.index')}>
                                    <Button variant="primary" className="flex items-center space-x-2">
                                        <ShoppingBag className="w-4 h-4 mr-1.5" />
                                        Mulai Belanja
                                    </Button>
                                </Link>
                            </div>
                        </div>
                    ) : (
                        <div className="space-y-6">
                            {filteredOrders.map((order) => {
                                const status = getStatusLabel(order.order_status);
                                const firstItem = order.items?.[0];
                                const extraItemsCount = (order.items?.length || 0) - 1;
                                const orderDateFormatted = new Date(order.order_date).toLocaleDateString('id-ID', {
                                    year: 'numeric',
                                    month: 'long',
                                    day: 'numeric',
                                });

                                return (
                                    <div key={order.id} className="bg-white border border-brandOutline-soft/30 rounded-2xl p-6 shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-6 hover:shadow-md transition-shadow">
                                        
                                        {/* Left Side: Order Info & Product Preview */}
                                        <div className="flex-1 space-y-4">
                                            {/* Top Meta info */}
                                            <div className="flex flex-wrap items-center gap-3 text-xs">
                                                <span className="font-mono font-bold text-primary">
                                                    #{order.order_number}
                                                </span>
                                                <span className="text-brandText-muted flex items-center">
                                                    <Calendar className="w-3.5 h-3.5 mr-1 text-gold" />
                                                    {orderDateFormatted}
                                                </span>
                                                <span className={`px-2.5 py-0.5 border rounded-full text-[10px] font-bold tracking-wide uppercase ${status.style}`}>
                                                    {status.text}
                                                </span>
                                            </div>

                                            {/* Product Preview Row */}
                                            {firstItem && (
                                                <div className="flex items-center space-x-4">
                                                    <div className="w-16 h-16 rounded-xl overflow-hidden border border-brandOutline-soft bg-cream-light/20 flex-shrink-0 flex items-center justify-center">
                                                        {firstItem.product?.image_path ? (
                                                            <img
                                                                src={`/storage/${firstItem.product.image_path}`}
                                                                alt={firstItem.product_name}
                                                                className="w-full h-full object-cover"
                                                            />
                                                        ) : (
                                                            <span className="text-xl text-primary/30 font-serif">✿</span>
                                                        )}
                                                    </div>
                                                    <div>
                                                        <h4 className="font-serif text-sm font-bold text-primary leading-tight">
                                                            {firstItem.product_name}
                                                        </h4>
                                                        <p className="text-xs text-brandText-muted mt-1">
                                                            {firstItem.quantity} barang x <CurrencyText value={Number(firstItem.unit_price)} />
                                                        </p>
                                                        {extraItemsCount > 0 && (
                                                            <p className="text-[10px] text-brandText-muted mt-1 font-semibold">
                                                                + {extraItemsCount} rangkaian bunga lainnya
                                                            </p>
                                                        )}
                                                    </div>
                                                </div>
                                            )}
                                        </div>

                                        {/* Right Side: Total Price & Actions */}
                                        <div className="flex flex-row md:flex-col items-center md:items-end justify-between md:justify-center gap-4 pt-4 md:pt-0 border-t md:border-t-0 border-brandOutline-soft/30">
                                            <div className="text-left md:text-right">
                                                <p className="text-xs text-brandText-muted">Total Belanja</p>
                                                <p className="font-serif text-base font-bold text-primary mt-0.5">
                                                    <CurrencyText value={Number(order.total)} />
                                                </p>
                                            </div>

                                            <div className="flex gap-2.5">
                                                {['pending_payment', 'rejected'].includes(order.order_status) && (
                                                    <Link href={route('customer.payments.create', order.order_number)}>
                                                        <Button variant="primary" className="text-xs py-2 px-3 flex items-center space-x-1">
                                                            <CreditCard className="w-3.5 h-3.5 mr-1" />
                                                            Bayar
                                                        </Button>
                                                    </Link>
                                                )}

                                                <Link href={route('customer.orders.show', order.order_number)}>
                                                    <Button variant="outline" className="text-xs py-2 px-3 flex items-center space-x-1">
                                                        <Eye className="w-3.5 h-3.5 mr-1" />
                                                        Detail
                                                    </Button>
                                                </Link>
                                            </div>
                                        </div>
                                    </div>
                                );
                            })}
                        </div>
                    )}
                </div>
            </div>
        </PublicLayout>
    );
}

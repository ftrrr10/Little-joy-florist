import React from 'react';
import DashboardLayout from '@/Layouts/DashboardLayout';
import { Head, Link, usePage } from '@inertiajs/react';
import { PageProps } from '@/types';
import CurrencyText from '@/Components/common/CurrencyText';
import { 
    Clock, 
    AlertTriangle, 
    ArrowRight, 
    ChevronRight, 
    Package, 
    Truck, 
    User, 
    ShoppingBag, 
    Scissors, 
    Calendar,
    CheckCircle,
    Activity
} from 'lucide-react';

interface Category {
    id: number;
    name: string;
}

interface Product {
    id: number;
    name: string;
    stock: number;
    is_active: boolean;
    category?: Category;
}

interface OrderUser {
    id: number;
    name: string;
    email: string;
}

interface Order {
    id: number;
    order_number: string;
    total: string;
    order_status: string;
    payment_status: string;
    recipient_name: string;
    recipient_phone: string;
    delivery_date: string;
    created_at: string;
    user: OrderUser;
}

interface OperatorMetrics {
    waiting_verification: number;
    processing: number;
    shipped: number;
    low_stock_count: number;
}

interface OperatorDashboardProps extends PageProps {
    metrics: OperatorMetrics;
    waiting_orders: Order[];
    processing_orders: Order[];
    low_stock_products: Product[];
}

export default function Dashboard() {
    const { auth, metrics, waiting_orders, processing_orders, low_stock_products } = usePage<OperatorDashboardProps>().props;

    const getStatusBadgeClass = (status: string) => {
        switch (status) {
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
                return 'bg-yellow-50 text-yellow-700 border-yellow-200 animate-pulse';
            case 'pending_payment':
                return 'bg-gray-100 text-gray-700 border-gray-200';
            case 'cancelled':
                return 'bg-red-50 text-red-600 border-red-150';
            case 'rejected':
                return 'bg-red-50 text-red-700 border-red-200';
            default:
                return 'bg-gray-50 text-gray-700 border-gray-200';
        }
    };

    const getStatusLabel = (status: string) => {
        switch (status) {
            case 'completed': return 'Selesai';
            case 'shipped': return 'Dikirim';
            case 'ready': return 'Siap Kirim';
            case 'processing': return 'Diproses';
            case 'paid': return 'Lunas';
            case 'waiting_verification': return 'Menunggu Verifikasi';
            case 'pending_payment': return 'Belum Bayar';
            case 'cancelled': return 'Batal';
            case 'rejected': return 'Ditolak';
            default: return status;
        }
    };

    return (
        <DashboardLayout title="Pusat Kendali Operasional">
            <Head title="Dashboard Operator | Little Joy Florist" />

            <div className="space-y-8 font-sans bg-[#FBF9F4] -m-8 p-8 min-h-screen">
                {/* Welcome Message Banner */}
                <div className="bg-gradient-to-r from-primary to-primary-dark p-8 rounded-3xl text-white shadow-md relative overflow-hidden">
                    <div className="absolute top-0 right-0 w-64 h-64 bg-white/5 rounded-full blur-3xl -mr-10 -mt-10 pointer-events-none"></div>
                    <span className="inline-block px-2.5 py-0.5 text-[10px] font-bold tracking-wider uppercase bg-white/20 text-white rounded-full mb-3 backdrop-blur-md">
                        Operator Florist
                    </span>
                    <h3 className="font-serif text-3xl font-bold mb-2">
                        Selamat Bekerja, {auth.user?.name}
                    </h3>
                    <p className="text-white/80 text-sm max-w-2xl leading-relaxed">
                        Pantau antrean verifikasi pembayaran masuk dari pelanggan, koordinasikan perangkaian buket bunga segar hari ini, dan perbarui status logistik pengiriman secara tepat waktu.
                    </p>
                </div>

                {/* 1. TOP ROW: THREE MAIN CARDS */}
                <div className="grid grid-cols-1 lg:grid-cols-12 gap-6">
                    {/* Card 1: Active Work (Large Green Card, Col-span 6) */}
                    <div className="lg:col-span-6 bg-[#064E3B] text-white p-8 rounded-3xl shadow-sm relative overflow-hidden flex flex-col justify-between min-h-[180px]">
                        {/* Decorative Scissor/Crafting Watermark */}
                        <div className="absolute right-6 bottom-6 text-white/5 pointer-events-none">
                            <Scissors className="w-40 h-40 stroke-[0.75]" />
                        </div>
                        <div className="z-10">
                            <p className="text-[10px] font-bold tracking-[0.2em] text-[#FFE088] uppercase mb-1">
                                Rangkaian Sedang Dirangkai
                            </p>
                            <h2 className="text-4xl font-bold font-serif tracking-tight mt-1">
                                {metrics.processing} <span className="text-xl font-normal text-white/90">Pesanan</span>
                            </h2>
                        </div>
                        <div className="z-10 flex items-center gap-2 mt-6 text-xs text-white/80 font-medium">
                            <span className="inline-flex items-center gap-0.5 px-2 py-0.5 bg-white/10 rounded-full text-[#FFE088] text-[10px] font-bold">
                                <Activity className="w-3 h-3" /> Antrean Aktif
                            </span>
                            <span>Harap segera menyelesaikan perangkaian buket bunga hari ini</span>
                        </div>
                    </div>

                    {/* Card 2: Waiting Verification (White Card, Col-span 3) */}
                    <div className="lg:col-span-3 bg-white p-6 rounded-3xl border border-brandOutline-soft/35 shadow-sm flex flex-col justify-between min-h-[180px]">
                        <div>
                            <div className="flex items-center justify-between">
                                <span className="text-[9px] font-bold tracking-[0.15em] text-brandText-muted uppercase">
                                    Butuh Verifikasi
                                </span>
                                <div className="p-2 bg-amber-50 text-amber-600 rounded-xl">
                                    <Clock className="w-4 h-4" />
                                </div>
                            </div>
                            <h3 className="text-3xl font-bold text-primary mt-3">
                                {metrics.waiting_verification} <span className="text-xs font-semibold text-brandText-muted">pesanan</span>
                            </h3>
                        </div>
                        <div className="text-[10px] text-brandText-muted/80 mt-4 flex items-center justify-between">
                            <span>Status: Menunggu Resi</span>
                            <span className="font-bold text-amber-600">Segera Tinjau</span>
                        </div>
                    </div>

                    {/* Card 3: Low Stock Warnings (White Card, Col-span 3) */}
                    <div className="lg:col-span-3 bg-white p-6 rounded-3xl border border-brandOutline-soft/35 shadow-sm flex flex-col justify-between min-h-[180px]">
                        <div>
                            <div className="flex items-center justify-between">
                                <span className="text-[9px] font-bold tracking-[0.15em] text-brandText-muted uppercase">
                                    Stok Bunga Kritis
                                </span>
                                <div className="p-2 bg-red-50 text-red-600 rounded-xl">
                                    <AlertTriangle className="w-4 h-4" />
                                </div>
                            </div>
                            <h3 className="text-3xl font-bold text-red-600 mt-3">
                                {metrics.low_stock_count} <span className="text-xs font-semibold text-brandText-muted">item</span>
                            </h3>
                        </div>
                        <div className="mt-4">
                            <Link 
                                href={route('operator.stock.index')} 
                                className="text-[10px] font-bold text-red-600 hover:text-red-700 flex items-center gap-0.5 group w-fit"
                            >
                                Periksa Stok <ArrowRight className="w-3.5 h-3.5 group-hover:translate-x-0.5 transition-transform" />
                            </Link>
                        </div>
                    </div>
                </div>

                {/* 2. ROW 2: HORIZONTAL STATUS TRACKING BADGES */}
                <div className="grid grid-cols-2 md:grid-cols-4 gap-4">
                    {/* Status 1: Menunggu Verifikasi */}
                    <div className="bg-white p-4 rounded-2xl border border-brandOutline-soft/30 shadow-sm flex flex-col justify-between relative overflow-hidden">
                        {metrics.waiting_verification > 0 && (
                            <span className="absolute top-0 right-0 w-2 h-2 bg-yellow-500 rounded-full animate-ping m-3"></span>
                        )}
                        <span className="text-[9px] font-bold tracking-wider text-brandText-muted uppercase">Menunggu Verifikasi</span>
                        <div className="flex items-baseline justify-between mt-2">
                            <span className="text-2xl font-bold text-yellow-600">{metrics.waiting_verification}</span>
                            <span className="text-[10px] text-brandText-muted font-medium">order</span>
                        </div>
                    </div>

                    {/* Status 2: Sedang Dirangkai */}
                    <div className="bg-white p-4 rounded-2xl border border-brandOutline-soft/30 shadow-sm flex flex-col justify-between">
                        <span className="text-[9px] font-bold tracking-wider text-brandText-muted uppercase">Sedang Dirangkai</span>
                        <div className="flex items-baseline justify-between mt-2">
                            <span className="text-2xl font-bold text-primary">{metrics.processing}</span>
                            <span className="text-[10px] text-brandText-muted font-medium">order</span>
                        </div>
                    </div>

                    {/* Status 3: Siap Kirim */}
                    <div className="bg-white p-4 rounded-2xl border border-brandOutline-soft/30 shadow-sm flex flex-col justify-between">
                        <span className="text-[9px] font-bold tracking-wider text-brandText-muted uppercase">Siap Kirim</span>
                        <div className="flex items-baseline justify-between mt-2">
                            <span className="text-2xl font-bold text-indigo-600">
                                {processing_orders.filter(o => o.order_status === 'ready').length}
                            </span>
                            <span className="text-[10px] text-brandText-muted font-medium">order</span>
                        </div>
                    </div>

                    {/* Status 4: Dalam Pengiriman */}
                    <div className="bg-white p-4 rounded-2xl border border-brandOutline-soft/30 shadow-sm flex flex-col justify-between">
                        <span className="text-[9px] font-bold tracking-wider text-brandText-muted uppercase">Dalam Pengiriman</span>
                        <div className="flex items-baseline justify-between mt-2">
                            <span className="text-2xl font-bold text-blue-600">{metrics.shipped}</span>
                            <span className="text-[10px] text-brandText-muted font-medium">order</span>
                        </div>
                    </div>
                </div>

                {/* 3. ROW 3: VERIFICATION QUEUE & LOW STOCK PANELS */}
                <div className="grid grid-cols-1 lg:grid-cols-12 gap-8">
                    {/* Antrean Verifikasi Pembayaran (Col-span 8) */}
                    <div className="lg:col-span-8 bg-white p-6 rounded-3xl border border-brandOutline-soft/35 shadow-sm">
                        <div className="flex items-center justify-between mb-6">
                            <h4 className="font-serif text-base font-bold text-primary">
                                Antrean Verifikasi Pembayaran
                            </h4>
                            <span className="inline-flex items-center px-2 py-0.5 text-[9px] font-bold tracking-wide rounded-md bg-amber-50 border border-amber-200 text-amber-750 animate-pulse">
                                Butuh Tindakan Segera
                            </span>
                        </div>

                        <div className="overflow-x-auto">
                            <table className="w-full text-left text-xs border-collapse">
                                <thead>
                                    <tr className="border-b border-brandOutline-soft/20 text-brandText-muted font-bold bg-cream/10">
                                        <th className="py-3 px-4">No. Pesanan</th>
                                        <th className="py-3 px-2">Nama Pengirim</th>
                                        <th className="py-3 px-2 text-right">Nominal</th>
                                        <th className="py-3 px-4 text-center">Status</th>
                                        <th className="py-3 px-4 text-right">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody className="divide-y divide-brandOutline-soft/10 text-brandText">
                                    {waiting_orders.length === 0 ? (
                                        <tr>
                                            <td colSpan={5} className="py-8 text-center text-brandText-muted/65">
                                                Tidak ada antrean verifikasi pembayaran saat ini.
                                            </td>
                                        </tr>
                                    ) : (
                                        waiting_orders.map((order) => (
                                            <tr key={order.id} className="hover:bg-cream/5 transition-colors">
                                                <td className="py-3.5 px-4 font-bold text-primary">
                                                    #{order.order_number}
                                                </td>
                                                <td className="py-3.5 px-2">
                                                    <p className="font-semibold text-brandText-dark">{order.user?.name}</p>
                                                    <p className="text-[9px] text-brandText-muted flex items-center mt-0.5">
                                                        <User className="w-3.5 h-3.5 mr-1 text-gold" />
                                                        {order.recipient_name}
                                                    </p>
                                                </td>
                                                <td className="py-3.5 px-2 font-bold text-right">
                                                    <CurrencyText value={Number(order.total)} />
                                                </td>
                                                <td className="py-3.5 px-4 text-center">
                                                    <span className="inline-block px-2.5 py-0.5 border rounded-md text-[9px] font-bold bg-yellow-50 border-yellow-200 text-yellow-700">
                                                        Menunggu Verifikasi
                                                    </span>
                                                </td>
                                                <td className="py-3.5 px-4 text-right">
                                                    <Link 
                                                        href={route('operator.orders.show', order.order_number)}
                                                        className="inline-flex items-center justify-center px-2.5 py-1.5 bg-primary hover:bg-primary-dark text-white text-[10px] font-bold rounded-xl transition-all shadow-sm"
                                                    >
                                                        Tinjau Resi
                                                    </Link>
                                                </td>
                                            </tr>
                                        ))
                                    )}
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {/* Low Stock Watch (Col-span 4) */}
                    <div className="lg:col-span-4 bg-white p-6 rounded-3xl border border-brandOutline-soft/35 shadow-sm flex flex-col justify-between">
                        <div>
                            <h4 className="font-serif text-base font-bold text-primary mb-1">
                                Pemantauan Stok Kritis
                            </h4>
                            <p className="text-[10px] text-brandText-muted mb-4">
                                Bunga aktif terdaftar dengan jumlah stok ≤ 5 tangkai.
                            </p>

                            <div className="space-y-3 max-h-[220px] overflow-y-auto pr-1">
                                {low_stock_products.length === 0 ? (
                                    <div className="py-12 text-center text-brandText-muted/60 flex flex-col items-center justify-center gap-2">
                                        <Package className="w-8 h-8 text-green-500/30" />
                                        <p className="text-xs font-bold text-green-700">Persediaan Aman</p>
                                        <p className="text-[9px] text-brandText-muted/70 max-w-[180px]">Seluruh sisa stok bunga di gudang mencukupi.</p>
                                    </div>
                                ) : (
                                    low_stock_products.map((product) => (
                                        <div 
                                            key={product.id} 
                                            className="flex items-center justify-between p-3 rounded-2xl border border-red-100 bg-red-50/5 hover:bg-red-50/20 transition-all"
                                        >
                                            <div className="min-w-0 flex-1 pr-2">
                                                <p className="font-bold text-xs text-brandText truncate">
                                                    {product.name}
                                                </p>
                                                <p className="text-[9px] text-brandText-muted uppercase tracking-wider">
                                                    {product.category?.name || 'Umum'}
                                                </p>
                                            </div>
                                            <div className="text-right">
                                                <span className="inline-block px-2.5 py-1 text-xs font-bold bg-red-100 text-red-700 rounded-xl">
                                                    {product.stock} tangkai
                                                </span>
                                            </div>
                                        </div>
                                    ))
                                )}
                            </div>
                        </div>

                        {low_stock_products.length > 0 && (
                            <Link
                                href={route('operator.stock.index')}
                                className="w-full mt-6 py-2.5 bg-red-50 hover:bg-red-100 text-red-700 text-center text-xs font-bold rounded-xl transition-all block border border-red-200"
                            >
                                Periksa Stok Bunga
                            </Link>
                        )}
                    </div>
                </div>

                {/* 4. ROW 4: CRAFTING WORK QUEUE (Pekerjaan Merangkai Bunga Hari Ini) */}
                <div className="bg-white p-6 rounded-3xl border border-brandOutline-soft/35 shadow-sm mt-8">
                    <div className="flex items-center justify-between mb-6">
                        <div className="flex items-center gap-2">
                            <Scissors className="w-5 h-5 text-primary" />
                            <h4 className="font-serif text-base font-bold text-primary">
                                Pekerjaan Merangkai Bunga Hari Ini
                            </h4>
                        </div>
                        <Link 
                            href={route('operator.orders.index')} 
                            className="text-[9px] font-bold text-secondary hover:text-secondary-dark uppercase tracking-wider"
                        >
                            Lihat Semua Pekerjaan
                        </Link>
                    </div>

                    <div className="overflow-x-auto">
                        <table className="w-full text-left text-xs border-collapse">
                            <thead>
                                <tr className="border-b border-brandOutline-soft/20 text-brandText-muted font-bold bg-cream/10">
                                    <th className="py-3 px-4">No. Pesanan</th>
                                    <th className="py-3 px-3">Penerima Bunga</th>
                                    <th className="py-3 px-3">Tanggal Pengiriman</th>
                                    <th className="py-3 px-3 text-center">Status Kerja</th>
                                    <th className="py-3 px-4 text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody className="divide-y divide-brandOutline-soft/10 text-brandText">
                                {processing_orders.length === 0 ? (
                                    <tr>
                                        <td colSpan={5} className="py-8 text-center text-brandText-muted/65">
                                            Tidak ada antrean perangkaian bunga aktif hari ini.
                                        </td>
                                    </tr>
                                ) : (
                                    processing_orders.map((order) => {
                                        const delivDate = new Date(order.delivery_date).toLocaleDateString('id-ID', {
                                            day: 'numeric',
                                            month: 'long',
                                            year: 'numeric'
                                        });

                                        return (
                                            <tr key={order.id} className="hover:bg-cream/5 transition-colors">
                                                <td className="py-3.5 px-4 font-bold text-primary">
                                                    #{order.order_number}
                                                </td>
                                                <td className="py-3.5 px-3 font-semibold text-brandText-dark">
                                                    {order.recipient_name}
                                                </td>
                                                <td className="py-3.5 px-3 text-brandText-muted">
                                                    <span className="flex items-center">
                                                        <Calendar className="w-3.5 h-3.5 mr-1 text-primary/70" />
                                                        {delivDate}
                                                    </span>
                                                </td>
                                                <td className="py-3.5 px-3 text-center">
                                                    <span className={`inline-block px-2.5 py-0.5 border rounded-md text-[9px] font-bold ${getStatusBadgeClass(order.order_status)}`}>
                                                        {getStatusLabel(order.order_status)}
                                                    </span>
                                                </td>
                                                <td className="py-3.5 px-4 text-right">
                                                    <Link 
                                                        href={route('operator.orders.show', order.order_number)}
                                                        className="inline-flex items-center justify-center p-1 bg-brandOutline-soft/10 text-primary hover:bg-primary hover:text-white rounded-lg transition-all"
                                                        title="Buka Detail Kerja"
                                                    >
                                                        <ChevronRight className="w-4 h-4" />
                                                    </Link>
                                                </td>
                                            </tr>
                                        );
                                    })
                                )}
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </DashboardLayout>
    );
}

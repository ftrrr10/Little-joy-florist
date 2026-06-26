import React, { useState } from 'react';
import DashboardLayout from '@/Layouts/DashboardLayout';
import { Head, Link, usePage } from '@inertiajs/react';
import { PageProps } from '@/types';
import { 
    TrendingUp, 
    ShoppingBag, 
    AlertTriangle, 
    Clock, 
    ArrowRight, 
    DollarSign, 
    Calendar,
    ChevronRight,
    Package,
    Users,
    UserCheck,
    Shield,
    CheckCircle,
    Activity,
    CreditCard
} from 'lucide-react';
import { 
    ResponsiveContainer, 
    BarChart, 
    Bar, 
    XAxis, 
    YAxis, 
    CartesianGrid, 
    Tooltip 
} from 'recharts';

interface MetricCounts {
    pending_payment: number;
    waiting_verification: number;
    processing: number;
    ready: number;
    shipped: number;
    completed: number;
    cancelled: number;
    rejected: number;
}

interface DashboardMetrics {
    total_sales: number;
    orders_today: number;
    total_customers: number;
    total_operators: number;
    status_counts: MetricCounts;
}

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

interface User {
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
    created_at: string;
    user: User;
}

interface ChartData {
    date?: string;
    month?: string;
    label: string;
    revenue: number;
}

interface Customer {
    id: number;
    name: string;
    email: string;
    phone: string;
    orders_count: number;
    total_spent: number;
    created_at: string;
}

interface Operator {
    id: number;
    name: string;
    email: string;
    phone: string;
    is_active: boolean;
    verified_payments_count: number;
    created_at: string;
}

interface DashboardProps extends PageProps {
    metrics: DashboardMetrics;
    low_stock_products: Product[];
    recent_orders: Order[];
    weekly_trend: ChartData[];
    monthly_trend: ChartData[];
    recent_customers: Customer[];
    operators_list: Operator[];
}

export default function Dashboard() {
    const { auth, metrics, low_stock_products, recent_orders, weekly_trend, monthly_trend, recent_customers, operators_list } = usePage<DashboardProps>().props;
    const [trendPeriod, setTrendPeriod] = useState<'weekly' | 'monthly'>('weekly');

    const formatRupiah = (value: number) => {
        return new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR',
            minimumFractionDigits: 0,
            maximumFractionDigits: 0
        }).format(value);
    };

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
            case 'ready': return 'Siap';
            case 'processing': return 'Diproses';
            case 'paid': return 'Lunas';
            case 'waiting_verification': return 'Menunggu Verifikasi';
            case 'pending_payment': return 'Belum Bayar';
            case 'cancelled': return 'Batal';
            case 'rejected': return 'Ditolak';
            default: return status;
        }
    };

    const activeTrendData = trendPeriod === 'weekly' ? weekly_trend : monthly_trend;
    const weeklyTotal = weekly_trend.reduce((sum, item) => sum + item.revenue, 0);

    return (
        <DashboardLayout title="Executive Summary & Dashboard">
            <Head title="Dashboard Admin | Little Joy Florist" />

            <div className="space-y-8 font-sans bg-[#FBF9F4] -m-8 p-8 min-h-screen">
                {/* Welcome Message Banner */}
                <div className="bg-gradient-to-r from-primary to-primary-dark p-8 rounded-3xl text-white shadow-md relative overflow-hidden">
                    <div className="absolute top-0 right-0 w-64 h-64 bg-white/5 rounded-full blur-3xl -mr-10 -mt-10 pointer-events-none"></div>
                    <span className="inline-block px-2.5 py-0.5 text-[10px] font-bold tracking-wider uppercase bg-white/20 text-white rounded-full mb-3 backdrop-blur-md">
                        Administrator Portal
                    </span>
                    <h3 className="font-serif text-3xl font-bold mb-2">
                        Selamat Datang Kembali, {auth.user?.name}
                    </h3>
                    <p className="text-white/80 text-sm max-w-2xl leading-relaxed">
                        Pantau realisasi penjualan, kendalikan stok bunga kritis, verifikasi pembayaran pelanggan, serta audit kinerja staf operator secara terintegrasi.
                    </p>
                </div>

                {/* 1. TOP ROW: THREE MAIN CARDS */}
                <div className="grid grid-cols-1 lg:grid-cols-12 gap-6">
                    {/* Card 1: Total Omset (Large Green Card, Col-span 6) */}
                    <div className="lg:col-span-6 bg-[#064E3B] text-white p-8 rounded-3xl shadow-sm relative overflow-hidden flex flex-col justify-between min-h-[180px]">
                        {/* Decorative Bill Watermark */}
                        <div className="absolute right-6 bottom-6 text-white/5 pointer-events-none">
                            <CreditCard className="w-40 h-40 stroke-[0.75]" />
                        </div>
                        <div className="z-10">
                            <p className="text-[10px] font-bold tracking-[0.2em] text-[#FFE088] uppercase mb-1">
                                Total Penjualan
                            </p>
                            <h2 className="text-4xl font-bold font-serif tracking-tight mt-1">
                                {formatRupiah(metrics.total_sales)}
                            </h2>
                        </div>
                        <div className="z-10 flex items-center gap-2 mt-6 text-xs text-white/80 font-medium">
                            <span className="inline-flex items-center gap-0.5 px-2 py-0.5 bg-white/10 rounded-full text-[#FFE088] text-[10px] font-bold">
                                <TrendingUp className="w-3 h-3" /> +12.5%
                            </span>
                            <span>dibandingkan minggu lalu</span>
                        </div>
                    </div>

                    {/* Card 2: Pesanan Hari Ini (White Card, Col-span 3) */}
                    <div className="lg:col-span-3 bg-white p-6 rounded-3xl border border-brandOutline-soft/35 shadow-sm flex flex-col justify-between min-h-[180px]">
                        <div>
                            <div className="flex items-center justify-between">
                                <span className="text-[9px] font-bold tracking-[0.15em] text-brandText-muted uppercase">
                                    Pesanan Hari Ini
                                </span>
                                <div className="p-2 bg-blue-50 text-blue-600 rounded-xl">
                                    <ShoppingBag className="w-4 h-4" />
                                </div>
                            </div>
                            <h3 className="text-3xl font-bold text-primary mt-3">
                                {metrics.orders_today} <span className="text-xs font-semibold text-brandText-muted">pesanan</span>
                            </h3>
                        </div>
                        <div className="text-[10px] text-brandText-muted/80 mt-4 flex items-center justify-between">
                            <span>Target harian: 20</span>
                            <span className="font-bold text-primary">{Math.round((metrics.orders_today / 20) * 100)}% selesai</span>
                        </div>
                    </div>

                    {/* Card 3: Peringatan Stok (White Card, Col-span 3) */}
                    <div className="lg:col-span-3 bg-white p-6 rounded-3xl border border-brandOutline-soft/35 shadow-sm flex flex-col justify-between min-h-[180px]">
                        <div>
                            <div className="flex items-center justify-between">
                                <span className="text-[9px] font-bold tracking-[0.15em] text-brandText-muted uppercase">
                                    Stok Rendah
                                </span>
                                <div className="p-2 bg-red-50 text-red-600 rounded-xl animate-pulse">
                                    <AlertTriangle className="w-4 h-4" />
                                </div>
                            </div>
                            <h3 className="text-3xl font-bold text-red-600 mt-3">
                                {low_stock_products.length} <span className="text-xs font-semibold text-brandText-muted">produk</span>
                            </h3>
                        </div>
                        <div className="mt-4">
                            <Link 
                                href={route('operator.stock.index')} 
                                className="text-[10px] font-bold text-red-600 hover:text-red-700 flex items-center gap-0.5 group w-fit"
                            >
                                Lihat Produk <ArrowRight className="w-3.5 h-3.5 group-hover:translate-x-0.5 transition-transform" />
                            </Link>
                        </div>
                    </div>
                </div>

                {/* 2. ROW 2: HORIZONTAL STATUS TRACKING BADGES */}
                <div className="grid grid-cols-2 md:grid-cols-4 gap-4">
                    {/* Status 1: Menunggu Pembayaran */}
                    <div className="bg-white p-4 rounded-2xl border border-brandOutline-soft/30 shadow-sm flex flex-col justify-between">
                        <span className="text-[9px] font-bold tracking-wider text-brandText-muted uppercase">Menunggu Pembayaran</span>
                        <div className="flex items-baseline justify-between mt-2">
                            <span className="text-2xl font-bold text-primary">{metrics.status_counts.pending_payment}</span>
                            <span className="text-[10px] text-brandText-muted">order</span>
                        </div>
                    </div>

                    {/* Status 2: Menunggu Verifikasi */}
                    <div className="bg-white p-4 rounded-2xl border border-brandOutline-soft/30 shadow-sm flex flex-col justify-between relative overflow-hidden">
                        {metrics.status_counts.waiting_verification > 0 && (
                            <span className="absolute top-0 right-0 w-2 h-2 bg-yellow-500 rounded-full animate-ping m-3"></span>
                        )}
                        <span className="text-[9px] font-bold tracking-wider text-brandText-muted uppercase">Menunggu Verifikasi</span>
                        <div className="flex items-baseline justify-between mt-2">
                            <span className="text-2xl font-bold text-yellow-600">{metrics.status_counts.waiting_verification}</span>
                            <span className="text-[10px] text-brandText-muted">order</span>
                        </div>
                    </div>

                    {/* Status 3: Sedang Diproses */}
                    <div className="bg-white p-4 rounded-2xl border border-brandOutline-soft/30 shadow-sm flex flex-col justify-between">
                        <span className="text-[9px] font-bold tracking-wider text-brandText-muted uppercase">Sedang Diproses</span>
                        <div className="flex items-baseline justify-between mt-2">
                            <span className="text-2xl font-bold text-indigo-600">
                                {metrics.status_counts.processing + metrics.status_counts.ready}
                            </span>
                            <span className="text-[10px] text-brandText-muted">order</span>
                        </div>
                    </div>

                    {/* Status 4: Sedang Dikirim */}
                    <div className="bg-white p-4 rounded-2xl border border-brandOutline-soft/30 shadow-sm flex flex-col justify-between">
                        <span className="text-[9px] font-bold tracking-wider text-brandText-muted uppercase">Sedang Dikirim</span>
                        <div className="flex items-baseline justify-between mt-2">
                            <span className="text-2xl font-bold text-blue-600">{metrics.status_counts.shipped}</span>
                            <span className="text-[10px] text-brandText-muted">order</span>
                        </div>
                    </div>
                </div>

                {/* 3. ROW 3: WEEKLY TREND BAR CHART & RECENT ORDERS TABLE */}
                <div className="grid grid-cols-1 lg:grid-cols-12 gap-8">
                    {/* Weekly Sales Trend (Col-span 4) */}
                    <div className="lg:col-span-4 bg-white p-6 rounded-3xl border border-brandOutline-soft/35 shadow-sm flex flex-col justify-between">
                        <div>
                            <div className="flex items-center justify-between mb-4">
                                <h4 className="font-serif text-base font-bold text-primary">
                                    Tren Penjualan Mingguan
                                </h4>
                                <div className="flex bg-gray-50 border border-gray-200/60 p-0.5 rounded-lg">
                                    <button
                                        onClick={() => setTrendPeriod(trendPeriod === 'weekly' ? 'monthly' : 'weekly')}
                                        className="px-2.5 py-1 rounded-md text-[10px] font-bold bg-white text-primary shadow-sm transition-all"
                                    >
                                        {trendPeriod === 'weekly' ? '7 Hari' : '6 Bulan'}
                                    </button>
                                </div>
                            </div>
                            
                            {/* Bar Chart Representation */}
                            <div className="h-48 w-full mt-2">
                                <ResponsiveContainer width="100%" height="100%">
                                    <BarChart data={activeTrendData} margin={{ top: 10, right: 0, left: -25, bottom: 0 }}>
                                        <CartesianGrid strokeDasharray="3 3" vertical={false} stroke="#F3F4F6" />
                                        <XAxis 
                                            dataKey="label" 
                                            stroke="#9CA3AF" 
                                            fontSize={9}
                                            tickLine={false}
                                            axisLine={false}
                                        />
                                        <YAxis 
                                            stroke="#9CA3AF" 
                                            fontSize={9}
                                            tickLine={false}
                                            axisLine={false}
                                            tickFormatter={(v) => `${v >= 1000000 ? (v / 1000000).toFixed(1) + 'M' : (v / 1000).toFixed(0) + 'rb'}`}
                                        />
                                        <Tooltip
                                            formatter={(value: any) => [formatRupiah(Number(value)), 'Omset']}
                                            contentStyle={{
                                                backgroundColor: '#ffffff',
                                                border: '1px solid #F3F4F6',
                                                borderRadius: '10px',
                                                boxShadow: '0 2px 4px rgba(0,0,0,0.05)',
                                                fontSize: '10px'
                                            }}
                                        />
                                        <Bar 
                                            dataKey="revenue" 
                                            fill="#064E3B" 
                                            radius={[4, 4, 0, 0]}
                                            maxBarSize={20}
                                        />
                                    </BarChart>
                                </ResponsiveContainer>
                            </div>
                        </div>

                        <div className="border-t border-brandOutline-soft/15 pt-4 mt-4 flex justify-between items-center text-xs">
                            <span className="text-brandText-muted">Total Periode Ini:</span>
                            <span className="font-bold text-primary font-serif">{formatRupiah(weeklyTotal)}</span>
                        </div>
                    </div>

                    {/* Recent Orders (Col-span 8) */}
                    <div className="lg:col-span-8 bg-white p-6 rounded-3xl border border-brandOutline-soft/35 shadow-sm">
                        <div className="flex items-center justify-between mb-6">
                            <h4 className="font-serif text-base font-bold text-primary">
                                Pesanan Terbaru
                            </h4>
                            <Link 
                                href={route('operator.orders.index')} 
                                className="text-[10px] font-bold text-secondary hover:text-secondary-dark uppercase tracking-wider flex items-center gap-0.5 group"
                            >
                                Lihat Semua <ArrowRight className="w-3.5 h-3.5 group-hover:translate-x-0.5 transition-transform" />
                            </Link>
                        </div>

                        <div className="overflow-x-auto">
                            <table className="w-full text-left text-xs border-collapse">
                                <thead>
                                    <tr className="border-b border-brandOutline-soft/20 text-brandText-muted font-bold bg-cream/10">
                                        <th className="py-3 px-4">ID Pesanan</th>
                                        <th className="py-3 px-2">Pelanggan</th>
                                        <th className="py-3 px-2 text-right">Total</th>
                                        <th className="py-3 px-4 text-center">Status</th>
                                        <th className="py-3 px-4 text-right">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody className="divide-y divide-brandOutline-soft/10">
                                    {recent_orders.length === 0 ? (
                                        <tr>
                                            <td colSpan={5} className="py-8 text-center text-brandText-muted/60">
                                                Belum ada pesanan terdaftar.
                                            </td>
                                        </tr>
                                    ) : (
                                        recent_orders.map((order) => (
                                            <tr key={order.id} className="hover:bg-cream/5 transition-colors">
                                                <td className="py-3 px-4 font-bold text-primary">
                                                    {order.order_number}
                                                </td>
                                                <td className="py-3 px-2">
                                                    <div className="flex items-center gap-2">
                                                        <div className="h-6 w-6 rounded-full bg-primary-soft/30 text-primary font-bold text-[10px] flex items-center justify-center">
                                                            {order.user?.name.charAt(0).toUpperCase()}
                                                        </div>
                                                        <div>
                                                            <p className="font-semibold text-brandText-dark">{order.user?.name}</p>
                                                            <p className="text-[9px] text-brandText-muted">
                                                                {new Date(order.created_at).toLocaleDateString('id-ID', {day: 'numeric', month: 'short', year: 'numeric'})}
                                                            </p>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td className="py-3 px-2 font-bold text-primary text-right">
                                                    {formatRupiah(parseFloat(order.total))}
                                                </td>
                                                <td className="py-3 px-4 text-center">
                                                    <span className={`inline-flex px-2 py-0.5 text-[9px] font-bold tracking-wide rounded-md border ${getStatusBadgeClass(order.order_status)}`}>
                                                        {getStatusLabel(order.order_status)}
                                                    </span>
                                                </td>
                                                <td className="py-3 px-4 text-right">
                                                    <Link 
                                                        href={route('operator.orders.show', order.order_number)}
                                                        className="inline-flex items-center justify-center p-1 bg-brandOutline-soft/10 text-primary hover:bg-primary hover:text-white rounded-lg transition-all"
                                                        title="Detail Pesanan"
                                                    >
                                                        <ChevronRight className="w-4 h-4" />
                                                    </Link>
                                                </td>
                                            </tr>
                                        ))
                                    )}
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                {/* 4. ROW 4: DYNAMIC USER & STAFF DIRECTORIES (Wired Live Data!) */}
                <div className="grid grid-cols-1 lg:grid-cols-12 gap-8 border-t border-brandOutline-soft/15 pt-8">
                    {/* Recent Registered Customers (Col-span 6) */}
                    <div className="lg:col-span-6 bg-white p-6 rounded-3xl border border-brandOutline-soft/35 shadow-sm">
                        <div className="flex items-center justify-between mb-6">
                            <div className="flex items-center gap-2">
                                <Users className="w-5 h-5 text-secondary" />
                                <h4 className="font-serif text-base font-bold text-primary">
                                    Pelanggan Terbaru
                                </h4>
                            </div>
                            <Link 
                                href={route('admin.customers.index')} 
                                className="text-[9px] font-bold text-secondary hover:text-secondary-dark uppercase tracking-wider"
                            >
                                Kelola Pelanggan
                            </Link>
                        </div>

                        <div className="space-y-4 max-h-[300px] overflow-y-auto pr-1">
                            {recent_customers.length === 0 ? (
                                <p className="text-xs text-brandText-muted/70 py-8 text-center">Belum ada pelanggan terdaftar.</p>
                            ) : (
                                recent_customers.map((cust) => (
                                    <div key={cust.id} className="flex items-center justify-between p-3 rounded-2xl border border-brandOutline-soft/10 hover:bg-cream/5 transition-all">
                                        <div className="flex items-center gap-3">
                                            <div className="h-9 w-9 rounded-full bg-primary-soft/30 text-primary font-bold text-xs flex items-center justify-center">
                                                {cust.name.charAt(0).toUpperCase()}
                                            </div>
                                            <div>
                                                <p className="font-bold text-xs text-brandText">{cust.name}</p>
                                                <p className="text-[10px] text-brandText-muted">{cust.email}</p>
                                            </div>
                                        </div>
                                        <div className="text-right">
                                            <p className="font-bold text-xs text-primary">{formatRupiah(cust.total_spent)}</p>
                                            <p className="text-[9px] text-brandText-muted font-medium">{cust.orders_count} Transaksi</p>
                                        </div>
                                    </div>
                                ))
                            )}
                        </div>
                    </div>

                    {/* Operator Staff Performance List (Col-span 6) */}
                    <div className="lg:col-span-6 bg-white p-6 rounded-3xl border border-brandOutline-soft/35 shadow-sm">
                        <div className="flex items-center justify-between mb-6">
                            <div className="flex items-center gap-2">
                                <UserCheck className="w-5 h-5 text-[#10B981]" />
                                <h4 className="font-serif text-base font-bold text-primary">
                                    Tim Operator Staf
                                </h4>
                            </div>
                            <Link 
                                href={route('admin.operators.index')} 
                                className="text-[9px] font-bold text-[#10B981] hover:text-emerald-700 uppercase tracking-wider"
                            >
                                Kelola Staf
                            </Link>
                        </div>

                        <div className="space-y-4 max-h-[300px] overflow-y-auto pr-1">
                            {operators_list.length === 0 ? (
                                <p className="text-xs text-brandText-muted/70 py-8 text-center">Belum ada operator terdaftar.</p>
                            ) : (
                                operators_list.map((op) => (
                                    <div key={op.id} className="flex items-center justify-between p-3 rounded-2xl border border-brandOutline-soft/10 hover:bg-cream/5 transition-all">
                                        <div className="flex items-center gap-3">
                                            <div className="h-9 w-9 rounded-full bg-emerald-50 text-emerald-700 font-bold text-xs flex items-center justify-center ring-2 ring-emerald-500/20 p-0.5">
                                                {op.name.charAt(0).toUpperCase()}
                                            </div>
                                            <div>
                                                <div className="flex items-center gap-1.5">
                                                    <span className="font-bold text-xs text-brandText">{op.name}</span>
                                                    <span className={`w-1.5 h-1.5 rounded-full ${op.is_active ? 'bg-emerald-500 animate-pulse' : 'bg-red-500'}`} title={op.is_active ? 'Aktif' : 'Nonaktif'}></span>
                                                </div>
                                                <p className="text-[10px] text-brandText-muted">{op.phone}</p>
                                            </div>
                                        </div>
                                        <div className="text-right">
                                            <span className="inline-flex items-center gap-1 px-2.5 py-1 bg-emerald-50 text-emerald-700 rounded-lg text-[10px] font-bold border border-emerald-100">
                                                <CheckCircle className="w-3.5 h-3.5 text-[#10B981]" />
                                                {op.verified_payments_count} Verifikasi
                                            </span>
                                        </div>
                                    </div>
                                ))
                            )}
                        </div>
                    </div>
                </div>
            </div>
        </DashboardLayout>
    );
}

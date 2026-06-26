import React, { useState } from 'react';
import DashboardLayout from '@/Layouts/DashboardLayout';
import { Head, usePage, router } from '@inertiajs/react';
import { PageProps } from '@/types';
import { 
    Calendar, 
    FileText, 
    Download, 
    Printer, 
    Filter, 
    RotateCcw,
    ShoppingBag,
    DollarSign,
    Award,
    Hash
} from 'lucide-react';

interface BestSeller {
    product_name: string;
    total_qty: string;
    total_sales: string;
}

interface OrderItem {
    id: number;
    product_name: string;
    unit_price: string;
    quantity: number;
    subtotal: string;
}

interface User {
    id: number;
    name: string;
    email: string;
}

interface Order {
    id: number;
    order_number: string;
    created_at: string;
    recipient_name: string;
    delivery_date: string;
    order_status: string;
    payment_status: string;
    subtotal: string;
    delivery_fee: string;
    total: string;
    user: User;
    items: OrderItem[];
    delivery_address: string;
}

interface ReportSummary {
    total_transactions: number;
    total_revenue: number;
    total_items_sold: number;
    best_sellers: BestSeller[];
}

interface ReportFilters {
    start_date: string;
    end_date: string;
    order_status: string;
    payment_status: string;
}

interface SalesReportProps extends PageProps {
    orders: Order[];
    filters: ReportFilters;
    summary: ReportSummary;
}

export default function SalesReport() {
    const { orders, filters, summary } = usePage<SalesReportProps>().props;

    // State for local inputs
    const [startDate, setStartDate] = useState(filters.start_date);
    const [endDate, setEndDate] = useState(filters.end_date);
    const [orderStatus, setOrderStatus] = useState(filters.order_status);
    const [paymentStatus, setPaymentStatus] = useState(filters.payment_status);

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
                return 'bg-yellow-50 text-yellow-700 border-yellow-200';
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

    const handleApplyFilters = (e: React.FormEvent) => {
        e.preventDefault();
        router.get(route('admin.reports.index'), {
            start_date: startDate,
            end_date: endDate,
            order_status: orderStatus,
            payment_status: paymentStatus
        }, { preserveState: true });
    };

    const handleResetFilters = () => {
        setStartDate('');
        setEndDate('');
        setOrderStatus('all');
        setPaymentStatus('all');
        router.get(route('admin.reports.index'));
    };

    const handlePrint = () => {
        window.print();
    };

    // Format top product
    const topProduct = summary.best_sellers[0];

    return (
        <DashboardLayout title="Laporan & Manajemen Keuangan">
            <Head title="Laporan Penjualan" />

            <div className="space-y-8 font-sans">
                {/* Print Header (Only visible on print) */}
                <div className="hidden print:block border-b-2 border-primary pb-6 mb-8">
                    <div className="flex justify-between items-end">
                        <div>
                            <h2 className="font-serif text-3xl font-bold text-primary">LITTLE JOY JAKARTA</h2>
                            <p className="text-xs text-brandText-muted mt-1">Layanan Premium Flower Bouquet & Florist</p>
                            <p className="text-[10px] text-brandText-muted">Jakarta, Indonesia | info@littlejoyjakarta.com</p>
                        </div>
                        <div className="text-right">
                            <h3 className="font-serif text-xl font-bold text-primary uppercase">Laporan Omset & Penjualan</h3>
                            <p className="text-xs text-brandText-dark mt-1 font-medium">
                                Periode: {new Date(filters.start_date).toLocaleDateString('id-ID', {day:'numeric', month:'long', year:'numeric'})} s/d {new Date(filters.end_date).toLocaleDateString('id-ID', {day:'numeric', month:'long', year:'numeric'})}
                            </p>
                            <p className="text-[9px] text-brandText-muted">Dicetak pada: {new Date().toLocaleString('id-ID')}</p>
                        </div>
                    </div>
                </div>

                {/* Filter and Actions Panel (Hidden on print) */}
                <div className="bg-white p-6 rounded-2xl border border-brandOutline-soft/30 shadow-sm print:hidden">
                    <form onSubmit={handleApplyFilters} className="space-y-4">
                        <div className="flex items-center gap-2 pb-3 border-b border-gray-100">
                            <Filter className="w-4 h-4 text-primary" />
                            <h4 className="font-serif text-base font-bold text-primary">Filter Laporan Penjualan</h4>
                        </div>
                        
                        <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                            {/* Start Date */}
                            <div className="space-y-1.5">
                                <label className="text-xs font-semibold text-brandText-dark flex items-center gap-1">
                                    <Calendar className="w-3.5 h-3.5 text-brandText-muted" /> Tanggal Mulai
                                </label>
                                <input 
                                    type="date"
                                    value={startDate}
                                    onChange={(e) => setStartDate(e.target.value)}
                                    className="w-full text-xs bg-gray-50/50 border border-brandOutline-soft rounded-xl px-3 py-2 text-brandText-dark focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition-all"
                                />
                            </div>

                            {/* End Date */}
                            <div className="space-y-1.5">
                                <label className="text-xs font-semibold text-brandText-dark flex items-center gap-1">
                                    <Calendar className="w-3.5 h-3.5 text-brandText-muted" /> Tanggal Selesai
                                </label>
                                <input 
                                    type="date"
                                    value={endDate}
                                    onChange={(e) => setEndDate(e.target.value)}
                                    className="w-full text-xs bg-gray-50/50 border border-brandOutline-soft rounded-xl px-3 py-2 text-brandText-dark focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition-all"
                                />
                            </div>

                            {/* Order Status */}
                            <div className="space-y-1.5">
                                <label className="text-xs font-semibold text-brandText-dark">Status Pesanan</label>
                                <select 
                                    value={orderStatus}
                                    onChange={(e) => setOrderStatus(e.target.value)}
                                    className="w-full text-xs bg-gray-50/50 border border-brandOutline-soft rounded-xl px-3 py-2 text-brandText-dark focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition-all"
                                >
                                    <option value="all">Semua Status</option>
                                    <option value="pending_payment">Belum Bayar</option>
                                    <option value="waiting_verification">Menunggu Verifikasi</option>
                                    <option value="paid">Lunas</option>
                                    <option value="processing">Diproses</option>
                                    <option value="ready">Siap Kirim</option>
                                    <option value="shipped">Dalam Pengiriman</option>
                                    <option value="completed">Selesai</option>
                                    <option value="cancelled">Dibatalkan</option>
                                    <option value="rejected">Ditolak</option>
                                </select>
                            </div>

                            {/* Payment Status */}
                            <div className="space-y-1.5">
                                <label className="text-xs font-semibold text-brandText-dark">Status Pembayaran</label>
                                <select 
                                    value={paymentStatus}
                                    onChange={(e) => setPaymentStatus(e.target.value)}
                                    className="w-full text-xs bg-gray-50/50 border border-brandOutline-soft rounded-xl px-3 py-2 text-brandText-dark focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition-all"
                                >
                                    <option value="all">Semua Status</option>
                                    <option value="pending">Belum Bayar</option>
                                    <option value="waiting_verification">Menunggu Verifikasi</option>
                                    <option value="verified">Diterima (Lunas)</option>
                                    <option value="rejected">Ditolak</option>
                                </select>
                            </div>
                        </div>

                        {/* Action Buttons */}
                        <div className="flex flex-wrap items-center justify-between gap-4 pt-4 border-t border-gray-100">
                            <div className="flex items-center gap-2">
                                <button
                                    type="submit"
                                    className="px-5 py-2 bg-primary hover:bg-primary-dark text-white text-xs font-semibold rounded-xl transition-all shadow-sm"
                                >
                                    Terapkan Filter
                                </button>
                                <button
                                    type="button"
                                    onClick={handleResetFilters}
                                    className="px-4 py-2 border border-brandOutline hover:bg-gray-50 text-brandText-dark text-xs font-semibold rounded-xl transition-all flex items-center gap-1"
                                >
                                    <RotateCcw className="w-3.5 h-3.5" /> Reset
                                </button>
                            </div>

                            <div className="flex items-center gap-2">
                                <a
                                    href={route('admin.reports.export', {
                                        start_date: filters.start_date,
                                        end_date: filters.end_date,
                                        order_status: filters.order_status,
                                        payment_status: filters.payment_status
                                    })}
                                    className="px-4 py-2 border border-brandOutline hover:bg-gray-50 text-brandText-dark text-xs font-semibold rounded-xl transition-all flex items-center gap-1.5 shadow-sm"
                                >
                                    <Download className="w-4 h-4 text-brandText-muted" /> Unduh CSV
                                </a>
                                <button
                                    type="button"
                                    onClick={handlePrint}
                                    className="px-4 py-2 bg-secondary hover:bg-secondary-dark text-white text-xs font-semibold rounded-xl transition-all flex items-center gap-1.5 shadow-sm"
                                >
                                    <Printer className="w-4 h-4" /> Cetak Laporan
                                </button>
                            </div>
                        </div>
                    </form>
                </div>

                {/* Summary Metrics Grid */}
                <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    {/* Realized Revenue */}
                    <div className="bg-white p-5 rounded-2xl border border-brandOutline-soft/30 shadow-sm flex items-center justify-between print:border-gray-200">
                        <div>
                            <p className="text-xs font-semibold text-brandText-muted uppercase tracking-wider mb-0.5 print:text-gray-500">
                                Pendapatan Omset
                            </p>
                            <h4 className="text-xl font-bold text-primary font-serif print:text-black">
                                {formatRupiah(summary.total_revenue)}
                            </h4>
                            <p className="text-[9px] text-green-600 font-medium mt-1 print:hidden">
                                Akumulasi transaksi berhasil
                            </p>
                        </div>
                        <div className="p-3 bg-green-50 text-green-600 rounded-xl print:bg-transparent print:text-black">
                            <DollarSign className="w-5 h-5" />
                        </div>
                    </div>

                    {/* Total Transactions */}
                    <div className="bg-white p-5 rounded-2xl border border-brandOutline-soft/30 shadow-sm flex items-center justify-between print:border-gray-200">
                        <div>
                            <p className="text-xs font-semibold text-brandText-muted uppercase tracking-wider mb-0.5 print:text-gray-500">
                                Total Transaksi
                            </p>
                            <h4 className="text-xl font-bold text-primary print:text-black">
                                {summary.total_transactions} <span className="text-xs font-normal text-brandText-muted">nota</span>
                            </h4>
                            <p className="text-[9px] text-brandText-muted/70 mt-1 print:hidden">
                                Jumlah pesanan dalam filter
                            </p>
                        </div>
                        <div className="p-3 bg-blue-50 text-blue-600 rounded-xl print:bg-transparent print:text-black">
                            <Hash className="w-5 h-5" />
                        </div>
                    </div>

                    {/* Total Flower Items Sold */}
                    <div className="bg-white p-5 rounded-2xl border border-brandOutline-soft/30 shadow-sm flex items-center justify-between print:border-gray-200">
                        <div>
                            <p className="text-xs font-semibold text-brandText-muted uppercase tracking-wider mb-0.5 print:text-gray-500">
                                Total Item Terjual
                            </p>
                            <h4 className="text-xl font-bold text-primary print:text-black">
                                {summary.total_items_sold} <span className="text-xs font-normal text-brandText-muted">item</span>
                            </h4>
                            <p className="text-[9px] text-brandText-muted/70 mt-1 print:hidden">
                                Karangan bunga terdistribusi
                            </p>
                        </div>
                        <div className="p-3 bg-indigo-50 text-indigo-600 rounded-xl print:bg-transparent print:text-black">
                            <ShoppingBag className="w-5 h-5" />
                        </div>
                    </div>

                    {/* Top Selling Arrangement */}
                    <div className="bg-white p-5 rounded-2xl border border-brandOutline-soft/30 shadow-sm flex items-center justify-between print:border-gray-200">
                        <div className="min-w-0 flex-1">
                            <p className="text-xs font-semibold text-brandText-muted uppercase tracking-wider mb-0.5 print:text-gray-500">
                                Rangkaian Terlaris
                            </p>
                            <h4 className="text-sm font-bold text-primary truncate pr-1 print:text-black" title={topProduct?.product_name || 'N/A'}>
                                {topProduct ? topProduct.product_name : 'Belum Ada'}
                            </h4>
                            <p className="text-[9px] text-secondary font-semibold mt-1 print:text-gray-700">
                                {topProduct ? `${topProduct.total_qty} Item Terjual` : 'N/A'}
                            </p>
                        </div>
                        <div className="p-3 bg-amber-50 text-amber-600 rounded-xl flex-shrink-0 print:bg-transparent print:text-black">
                            <Award className="w-5 h-5" />
                        </div>
                    </div>
                </div>

                {/* Main Ledger Table */}
                <div className="bg-white rounded-2xl border border-brandOutline-soft/30 shadow-sm overflow-hidden print:border-gray-200">
                    <div className="p-6 border-b border-gray-100 flex items-center justify-between print:px-0">
                        <div>
                            <h4 className="font-serif text-lg font-bold text-primary print:text-black">
                                Rincian Log Transaksi Penjualan
                            </h4>
                            <p className="text-xs text-brandText-muted print:hidden">
                                Menampilkan riwayat pemesanan yang sesuai dengan filter pencarian di atas.
                            </p>
                        </div>
                        <span className="hidden print:inline-block px-3 py-1 border border-gray-200 rounded-lg text-[10px] font-semibold text-brandText-dark">
                            Total: {summary.total_transactions} Transaksi
                        </span>
                    </div>

                    <div className="overflow-x-auto">
                        <table className="w-full text-left text-xs border-collapse">
                            <thead>
                                <tr className="border-b border-gray-100 text-brandText-muted font-semibold bg-gray-50/50 print:bg-gray-100 print:text-black">
                                    <th className="py-3 px-6">No. Pesanan</th>
                                    <th className="py-3 px-4">Tgl Pesanan</th>
                                    <th className="py-3 px-4">Pelanggan</th>
                                    <th className="py-3 px-4">Alamat Penerima</th>
                                    <th className="py-3 px-4">Status Pesanan</th>
                                    <th className="py-3 px-4">Status Bayar</th>
                                    <th className="py-3 px-6 text-right">Omset Bersih</th>
                                </tr>
                            </thead>
                            <tbody className="divide-y divide-gray-100 print:divide-gray-200">
                                {orders.length === 0 ? (
                                    <tr>
                                        <td colSpan={7} className="py-12 text-center text-brandText-muted/60 font-medium">
                                            Tidak ada transaksi yang cocok dengan kriteria filter.
                                        </td>
                                    </tr>
                                ) : (
                                    orders.map((order) => (
                                        <tr key={order.id} className="hover:bg-gray-50/20 transition-colors print:hover:bg-transparent">
                                            <td className="py-4 px-6 font-semibold text-primary print:text-black">
                                                {order.order_number}
                                            </td>
                                            <td className="py-4 px-4 text-brandText-muted print:text-black">
                                                {new Date(order.created_at).toLocaleDateString('id-ID', {
                                                    day: 'numeric',
                                                    month: 'short',
                                                    year: 'numeric'
                                                })}
                                            </td>
                                            <td className="py-4 px-4">
                                                <p className="font-semibold text-brandText-dark print:text-black">{order.user?.name}</p>
                                                <p className="text-[10px] text-brandText-muted print:text-gray-500">{order.user?.email}</p>
                                            </td>
                                            <td className="py-4 px-4 max-w-[200px] truncate" title={order.delivery_address}>
                                                <p className="font-semibold text-brandText-dark truncate print:text-black">{order.recipient_name}</p>
                                                <p className="text-[10px] text-brandText-muted truncate print:text-gray-500">{order.delivery_address}</p>
                                            </td>
                                            <td className="py-4 px-4">
                                                <span className={`inline-flex px-2 py-0.5 text-[10px] font-bold tracking-wide rounded-md border ${getStatusBadgeClass(order.order_status)}`}>
                                                    {getStatusLabel(order.order_status)}
                                                </span>
                                            </td>
                                            <td className="py-4 px-4">
                                                <span className={`inline-flex px-2 py-0.5 text-[10px] font-bold tracking-wide rounded-md border ${
                                                    order.payment_status === 'verified'
                                                        ? 'bg-green-50 text-green-700 border-green-200'
                                                        : order.payment_status === 'waiting_verification'
                                                        ? 'bg-yellow-50 text-yellow-700 border-yellow-200'
                                                        : order.payment_status === 'rejected'
                                                        ? 'bg-red-50 text-red-700 border-red-200'
                                                        : 'bg-gray-100 text-gray-700 border-gray-200'
                                                }`}>
                                                    {order.payment_status === 'verified' ? 'Lunas (Verified)' :
                                                     order.payment_status === 'waiting_verification' ? 'Menunggu Verifikasi' :
                                                     order.payment_status === 'rejected' ? 'Ditolak' : 'Belum Bayar'}
                                                </span>
                                            </td>
                                            <td className="py-4 px-6 text-right font-bold text-primary print:text-black">
                                                {formatRupiah(parseFloat(order.total))}
                                            </td>
                                        </tr>
                                    ))
                                )}
                            </tbody>
                            {orders.length > 0 && (
                                <tfoot>
                                    <tr className="bg-gray-50/50 font-bold border-t border-gray-200 print:bg-gray-100 print:text-black">
                                        <td colSpan={6} className="py-4 px-6 text-brandText-dark print:text-black text-right uppercase tracking-wider">
                                            Total Akumulasi Terfilter
                                        </td>
                                        <td className="py-4 px-6 text-right text-primary print:text-black text-sm">
                                            {formatRupiah(orders.reduce((sum, order) => sum + parseFloat(order.total), 0))}
                                        </td>
                                    </tr>
                                </tfoot>
                            )}
                        </table>
                    </div>
                </div>

                {/* Secondary Tables: Best Selling Arrangements */}
                <div className="grid grid-cols-1 lg:grid-cols-2 gap-8 print:grid-cols-1">
                    {/* Best Selling arrangements */}
                    <div className="bg-white p-6 rounded-2xl border border-brandOutline-soft/30 shadow-sm print:border-gray-200">
                        <div className="flex items-center gap-2 pb-4 mb-4 border-b border-gray-100">
                            <Award className="w-5 h-5 text-primary" />
                            <h4 className="font-serif text-base font-bold text-primary print:text-black">
                                Peringkat Rangkaian Bunga Terlaris
                            </h4>
                        </div>

                        <div className="overflow-x-auto">
                            <table className="w-full text-left text-xs border-collapse">
                                <thead>
                                    <tr className="border-b border-gray-100 text-brandText-muted font-semibold print:text-black">
                                        <th className="py-2.5 px-0 w-12 text-center">Rank</th>
                                        <th className="py-2.5 px-3">Nama Karangan Bunga</th>
                                        <th className="py-2.5 px-3 text-center">Volume Terjual</th>
                                        <th className="py-2.5 px-6 text-right">Total Penjualan</th>
                                    </tr>
                                </thead>
                                <tbody className="divide-y divide-gray-50">
                                    {summary.best_sellers.length === 0 ? (
                                        <tr>
                                            <td colSpan={4} className="py-8 text-center text-brandText-muted/60">
                                                Belum ada data penjualan karangan bunga.
                                            </td>
                                        </tr>
                                    ) : (
                                        summary.best_sellers.map((item, index) => (
                                            <tr key={index} className="hover:bg-gray-50/30 transition-colors">
                                                <td className="py-3 px-0 text-center">
                                                    <span className={`inline-flex items-center justify-center w-5 h-5 rounded-full text-[10px] font-bold ${
                                                        index === 0 ? 'bg-amber-100 text-amber-700' :
                                                        index === 1 ? 'bg-gray-200 text-gray-700' :
                                                        index === 2 ? 'bg-amber-50 text-amber-800' :
                                                        'bg-gray-100 text-gray-600'
                                                    }`}>
                                                        {index + 1}
                                                    </span>
                                                </td>
                                                <td className="py-3 px-3 font-semibold text-brandText-dark">
                                                    {item.product_name}
                                                </td>
                                                <td className="py-3 px-3 text-center font-bold text-brandText-dark">
                                                    {item.total_qty} item
                                                </td>
                                                <td className="py-3 px-6 text-right font-bold text-primary">
                                                    {formatRupiah(parseFloat(item.total_sales))}
                                                </td>
                                            </tr>
                                        ))
                                    )}
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {/* Print Disclaimer (Only visible on print) */}
                    <div className="hidden print:flex flex-col justify-end p-6 border-2 border-dashed border-gray-200 rounded-2xl h-full">
                        <h5 className="font-serif text-sm font-bold text-black mb-1">Pernyataan Validitas Dokumen</h5>
                        <p className="text-[10px] text-gray-500 leading-relaxed">
                            Laporan omset dan rincian log transaksi ini diproduksi secara otomatis oleh sistem administrasi utama **Little Joy Management**. Seluruh data keuangan yang tertera di atas bersifat final dan sesuai dengan mutasi manual bank transfer yang telah divalidasi penuh oleh staf operator yang bertugas.
                        </p>
                        <div className="flex justify-between items-center mt-6 pt-6 border-t border-gray-100">
                            <div className="text-center w-36">
                                <p className="text-[9px] text-gray-500">Dibuat Oleh,</p>
                                <div className="h-10"></div>
                                <p className="text-[10px] font-bold text-black border-t border-gray-200 pt-1">Sistem Administrasi</p>
                            </div>
                            <div className="text-center w-36">
                                <p className="text-[9px] text-gray-500">Disetujui Oleh,</p>
                                <div className="h-10"></div>
                                <p className="text-[10px] font-bold text-black border-t border-gray-200 pt-1">Pemilik Toko (Admin)</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </DashboardLayout>
    );
}

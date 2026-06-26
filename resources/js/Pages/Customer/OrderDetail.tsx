import React, { useState } from 'react';
import { Head, Link, router } from '@inertiajs/react';
import PublicLayout from '@/Layouts/PublicLayout';
import Button from '@/Components/common/Button';
import CurrencyText from '@/Components/common/CurrencyText';
import Alert from '@/Components/common/Alert';
import { Order, PageProps } from '@/types';
import { ArrowLeft, CreditCard, XCircle, MapPin, Calendar, MessageSquare, Landmark, User, Phone, FileText, CheckCircle2 } from 'lucide-react';

interface DetailProps extends Record<string, unknown> {
    order: Order;
}

export default function OrderDetail({ order, flash }: PageProps<DetailProps>) {
    const [isCancelling, setIsCancelling] = useState(false);

    const handleCancelOrder = () => {
        if (confirm('Apakah Anda yakin ingin membatalkan pesanan ini? Aksi ini tidak dapat dibatalkan.')) {
            setIsCancelling(true);
            router.post(
                route('customer.orders.cancel', order.order_number),
                {},
                {
                    preserveScroll: true,
                    onFinish: () => setIsCancelling(false),
                }
            );
        }
    };

    // Status translations and styling
    const getStatusInfo = (status: string) => {
        switch (status) {
            case 'pending_payment':
                return { text: 'Menunggu Pembayaran', color: 'text-amber-700 bg-amber-50 border-amber-200' };
            case 'waiting_verification':
                return { text: 'Menunggu Verifikasi', color: 'text-blue-700 bg-blue-50 border-blue-200' };
            case 'paid':
                return { text: 'Pembayaran Diterima', color: 'text-indigo-700 bg-indigo-50 border-indigo-200' };
            case 'processing':
                return { text: 'Sedang Diproses', color: 'text-indigo-700 bg-indigo-50 border-indigo-200' };
            case 'ready':
                return { text: 'Siap Dikirim', color: 'text-indigo-700 bg-indigo-50 border-indigo-200' };
            case 'shipped':
                return { text: 'Dalam Pengiriman', color: 'text-purple-700 bg-purple-50 border-purple-200' };
            case 'completed':
                return { text: 'Selesai', color: 'text-green-700 bg-green-50 border-green-200' };
            case 'cancelled':
                return { text: 'Dibatalkan', color: 'text-red-700 bg-red-50 border-red-200' };
            case 'rejected':
                return { text: 'Pembayaran Ditolak', color: 'text-red-700 bg-red-50 border-red-200' };
            default:
                return { text: status, color: 'text-gray-700 bg-gray-50 border-gray-200' };
        }
    };

    const statusInfo = getStatusInfo(order.order_status);

    const orderDateFormatted = new Date(order.order_date).toLocaleDateString('id-ID', {
        year: 'numeric',
        month: 'long',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit',
    });

    const deliveryDateFormatted = new Date(order.delivery_date).toLocaleDateString('id-ID', {
        year: 'numeric',
        month: 'long',
        day: 'numeric',
    });

    // Helper for timeline status Indonesian labels
    const getTimelineLabel = (status: string) => {
        switch (status) {
            case 'pending_payment': return 'Pesanan Dibuat';
            case 'waiting_verification': return 'Bukti Pembayaran Diunggah';
            case 'paid': return 'Pembayaran Berhasil Diverifikasi';
            case 'processing': return 'Pesanan Mulai Dirangkai';
            case 'ready': return 'Bunga Siap Dikirim';
            case 'shipped': return 'Pesanan Diserahkan ke Kurir';
            case 'completed': return 'Pesanan Selesai';
            case 'cancelled': return 'Pesanan Dibatalkan';
            case 'rejected': return 'Bukti Transfer Ditolak';
            default: return status;
        }
    };

    return (
        <PublicLayout>
            <Head title={`Detail Pesanan #${order.order_number} | Little Joy Jakarta`} />

            <div className="bg-cream-light/30 min-h-screen py-12 font-sans">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    {/* Header bar */}
                    <div className="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
                        <div>
                            <Link
                                href={route('customer.orders.index')}
                                className="inline-flex items-center text-xs font-semibold text-brandText-muted hover:text-primary transition-colors mb-3 group"
                            >
                                <ArrowLeft className="w-3.5 h-3.5 mr-1 group-hover:-translate-x-0.5 transition-transform" />
                                Kembali ke Riwayat Pesanan
                            </Link>
                            <div className="flex flex-wrap items-center gap-3">
                                <h1 className="font-serif text-3xl font-bold text-primary tracking-tight">
                                    Pesanan #{order.order_number}
                                </h1>
                                <span className={`px-3 py-0.5 border rounded-full text-xs font-bold uppercase tracking-wider ${statusInfo.color}`}>
                                    {statusInfo.text}
                                </span>
                            </div>
                            <p className="text-xs text-brandText-muted mt-1.5">
                                Dibuat pada: <span className="font-semibold text-brandText">{orderDateFormatted} WIB</span>
                            </p>
                        </div>

                        {/* Order action buttons */}
                        <div className="flex flex-wrap gap-2.5">
                            {['pending_payment', 'rejected'].includes(order.order_status) && (
                                <>
                                    <Link href={route('customer.payments.create', order.order_number)}>
                                        <Button variant="primary" className="flex items-center space-x-2 shadow-sm text-xs py-2 px-4">
                                            <CreditCard className="w-4 h-4 mr-1.5" />
                                            Unggah Pembayaran
                                        </Button>
                                    </Link>
                                    <Button
                                        onClick={handleCancelOrder}
                                        variant="outline"
                                        className="border-red-200 text-red-600 hover:bg-red-50 flex items-center space-x-2 text-xs py-2 px-4"
                                        isLoading={isCancelling}
                                        disabled={isCancelling}
                                    >
                                        <XCircle className="w-4 h-4 mr-1.5 text-red-600" />
                                        Batalkan Pesanan
                                    </Button>
                                </>
                            )}
                        </div>
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

                    <div className="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
                        {/* Left Column: Timeline, Delivery, Card Message */}
                        <div className="lg:col-span-2 space-y-6">
                            
                            {/* 1. Status Timeline */}
                            <div className="bg-white border border-brandOutline-soft/30 rounded-2xl p-6 shadow-sm">
                                <h3 className="font-serif text-lg font-bold text-primary mb-6 flex items-center">
                                    <CheckCircle2 className="w-5 h-5 mr-2 text-gold" />
                                    Linimasa Status Pesanan
                                </h3>

                                <div className="relative pl-6 border-l-2 border-dashed border-brandOutline-soft/60 space-y-8 ml-3">
                                    {order.histories?.map((log: any, index: number) => {
                                        const isLast = index === (order.histories?.length || 0) - 1;
                                        const logDate = new Date(log.created_at).toLocaleDateString('id-ID', {
                                            day: 'numeric',
                                            month: 'short',
                                            year: 'numeric',
                                            hour: '2-digit',
                                            minute: '2-digit',
                                        });

                                        return (
                                            <div key={log.id} className="relative">
                                                {/* Timeline Node Icon */}
                                                <span className={`absolute -left-[31px] top-1 w-4 h-4 rounded-full border-2 bg-white ${
                                                    isLast ? 'border-primary ring-4 ring-primary-soft/35 bg-primary' : 'border-brandOutline'
                                                }`} />
                                                
                                                <div>
                                                    <h4 className={`text-sm font-bold ${isLast ? 'text-primary' : 'text-brandText'}`}>
                                                        {getTimelineLabel(log.current_status)}
                                                    </h4>
                                                    <p className="text-[10px] text-brandText-muted mt-0.5">
                                                        {logDate} WIB {log.actor ? `oleh ${log.actor.name}` : ''}
                                                    </p>
                                                    {log.note && (
                                                        <p className="text-xs text-brandText-muted/90 bg-cream/10 border border-brandOutline-soft/10 rounded-lg p-2.5 mt-2 max-w-lg leading-relaxed italic">
                                                            &ldquo;{log.note}&rdquo;
                                                        </p>
                                                    )}
                                                </div>
                                            </div>
                                        );
                                    })}
                                </div>
                            </div>

                            {/* 2. Recipient & Delivery Info */}
                            <div className="bg-white border border-brandOutline-soft/30 rounded-2xl p-6 shadow-sm">
                                <h3 className="font-serif text-lg font-bold text-primary mb-5 flex items-center">
                                    <MapPin className="w-5 h-5 mr-2 text-gold" />
                                    Informasi Pengiriman
                                </h3>

                                <div className="grid grid-cols-1 sm:grid-cols-2 gap-6 text-sm">
                                    <div className="space-y-3">
                                        <div>
                                            <p className="text-[10px] font-bold text-brandText-muted uppercase tracking-wider">Nama Penerima</p>
                                            <p className="font-semibold text-brandText mt-0.5 flex items-center">
                                                <User className="w-3.5 h-3.5 mr-1.5 text-primary/60" />
                                                {order.recipient_name}
                                            </p>
                                        </div>
                                        
                                        <div>
                                            <p className="text-[10px] font-bold text-brandText-muted uppercase tracking-wider">No. Telepon Penerima</p>
                                            <p className="font-semibold text-brandText mt-0.5 flex items-center">
                                                <Phone className="w-3.5 h-3.5 mr-1.5 text-primary/60" />
                                                {order.recipient_phone}
                                            </p>
                                        </div>
                                    </div>

                                    <div className="space-y-3">
                                        <div>
                                            <p className="text-[10px] font-bold text-brandText-muted uppercase tracking-wider">Tanggal Pengantaran</p>
                                            <p className="font-semibold text-brandText mt-0.5 flex items-center">
                                                <Calendar className="w-3.5 h-3.5 mr-1.5 text-primary/60" />
                                                {deliveryDateFormatted}
                                            </p>
                                        </div>

                                        <div>
                                            <p className="text-[10px] font-bold text-brandText-muted uppercase tracking-wider">Alamat Lengkap Tujuan</p>
                                            <p className="text-brandText mt-0.5 leading-relaxed bg-cream-light/10 border border-brandOutline-soft/20 p-2.5 rounded-xl">
                                                {order.delivery_address}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {/* 3. Greeting Card Rendering */}
                            {order.greeting_message && (
                                <div className="bg-white border border-brandOutline-soft/30 rounded-2xl p-6 shadow-sm">
                                    <h3 className="font-serif text-lg font-bold text-primary mb-5 flex items-center">
                                        <MessageSquare className="w-5 h-5 mr-2 text-gold" />
                                        Kartu Ucapan Gratis
                                    </h3>

                                    {/* Virtual Card Rendering */}
                                    <div className="max-w-md bg-cream/10 border-2 border-double border-gold/45 p-6 rounded-2xl shadow-inner relative overflow-hidden mx-auto text-center font-serif flex flex-col items-center justify-center min-h-[160px]">
                                        <span className="text-lg text-gold mb-2">✿</span>
                                        <p className="text-sm text-primary/80 italic leading-relaxed whitespace-pre-wrap px-4 font-medium">
                                            &ldquo;{order.greeting_message}&rdquo;
                                        </p>
                                        <span className="text-xs tracking-[0.2em] text-gold uppercase mt-4 block">
                                            Little Joy Jakarta
                                        </span>
                                    </div>
                                </div>
                            )}

                            {/* 4. Customer Note */}
                            {order.customer_note && (
                                <div className="bg-white border border-brandOutline-soft/30 rounded-2xl p-6 shadow-sm">
                                    <h3 className="font-serif text-lg font-bold text-primary mb-3 flex items-center">
                                        <FileText className="w-5 h-5 mr-2 text-gold" />
                                        Catatan Operasional untuk Florist
                                    </h3>
                                    <p className="text-xs text-brandText leading-relaxed italic bg-cream-light/10 border border-brandOutline-soft/20 p-3 rounded-xl">
                                        &ldquo;{order.customer_note}&rdquo;
                                    </p>
                                </div>
                            )}
                        </div>

                        {/* Right Column: Invoice items, totals, payment bank manual instructions */}
                        <div className="space-y-6">
                            
                            {/* 1. Items invoice */}
                            <div className="bg-white border border-brandOutline-soft/30 rounded-2xl shadow-sm p-6">
                                <h3 className="font-serif text-lg font-bold text-primary mb-4 pb-3 border-b border-brandOutline-soft/30">
                                    Rangkaian Bunga Dipesan
                                </h3>

                                <div className="divide-y divide-brandOutline-soft/20 max-h-80 overflow-y-auto pr-1">
                                    {order.items?.map((item: any) => (
                                        <div key={item.id} className="py-3 flex justify-between items-start gap-4 text-xs">
                                            <div className="flex-1 space-y-0.5">
                                                <p className="font-bold text-primary">{item.product_name}</p>
                                                <p className="text-brandText-muted">
                                                    {item.quantity} x <CurrencyText value={Number(item.unit_price)} />
                                                </p>
                                            </div>
                                            <span className="font-bold text-brandText flex-shrink-0">
                                                <CurrencyText value={Number(item.subtotal)} />
                                            </span>
                                        </div>
                                    ))}
                                </div>

                                {/* Totals cost breakdown */}
                                <div className="space-y-3 text-sm pt-4 border-t border-brandOutline-soft/30">
                                    <div className="flex justify-between text-brandText-muted text-xs">
                                        <span>Subtotal</span>
                                        <span className="font-semibold text-brandText">
                                            <CurrencyText value={Number(order.subtotal)} />
                                        </span>
                                    </div>
                                    <div className="flex justify-between text-brandText-muted text-xs">
                                        <span>Ongkos Kirim (Flat)</span>
                                        <span className="font-semibold text-brandText">
                                            <CurrencyText value={Number(order.delivery_fee)} />
                                        </span>
                                    </div>
                                    <div className="pt-3 border-t border-brandOutline-soft/30 flex justify-between items-center font-bold text-base text-primary">
                                        <span>Total Pembayaran</span>
                                        <span className="font-serif text-lg">
                                            <CurrencyText value={Number(order.total)} />
                                        </span>
                                    </div>
                                </div>
                            </div>

                            {/* 2. Bank Manual Instructions (Only if pending payment or rejected) */}
                            {['pending_payment', 'rejected'].includes(order.order_status) && (
                                <div className="bg-white border border-brandOutline-soft/30 rounded-2xl shadow-sm p-6 space-y-4">
                                    <h3 className="font-serif text-base font-bold text-primary border-b border-brandOutline-soft/30 pb-2 flex items-center">
                                        <Landmark className="w-4 h-4 mr-2 text-gold" />
                                        Pilihan Rekening Transfer
                                    </h3>
                                    <p className="text-xs text-brandText-muted leading-relaxed">
                                        Silakan transfer sebesar <span className="font-bold text-primary"><CurrencyText value={Number(order.total)} /></span> ke rekening berikut, lalu unggah struk bukti pembayarannya:
                                    </p>
                                    
                                    <div className="space-y-2 text-xs">
                                        <div className="p-3 border border-brandOutline-soft/40 rounded-xl bg-cream-light/10">
                                            <p className="font-bold text-primary">BCA Jakarta</p>
                                            <p className="font-mono font-semibold mt-0.5">123-456-7890</p>
                                            <p className="text-[10px] text-brandText-muted">a/n Little Joy Jakarta</p>
                                        </div>
                                        <div className="p-3 border border-brandOutline-soft/40 rounded-xl bg-cream-light/10">
                                            <p className="font-bold text-primary">Mandiri Jakarta</p>
                                            <p className="font-mono font-semibold mt-0.5">098-765-4321</p>
                                            <p className="text-[10px] text-brandText-muted">a/n Little Joy Jakarta</p>
                                        </div>
                                    </div>
                                </div>
                            )}

                            {/* 3. Eager Loaded Payment details (If waiting_verification, verified, or rejected) */}
                            {order.payment && (
                                <div className="bg-white border border-brandOutline-soft/30 rounded-2xl shadow-sm p-6 space-y-4">
                                    <h3 className="font-serif text-base font-bold text-primary border-b border-brandOutline-soft/30 pb-2">
                                        Informasi Bukti Transfer
                                    </h3>

                                    <div className="space-y-3 text-xs leading-relaxed">
                                        <div className="flex justify-between">
                                            <span className="text-brandText-muted">Bank Pengirim:</span>
                                            <span className="font-bold text-brandText">{order.payment.sender_bank}</span>
                                        </div>
                                        <div className="flex justify-between">
                                            <span className="text-brandText-muted">Pemilik Rekening:</span>
                                            <span className="font-bold text-brandText">{order.payment.account_holder_name}</span>
                                        </div>
                                        <div className="flex justify-between">
                                            <span className="text-brandText-muted">Jumlah Ditransfer:</span>
                                            <span className="font-bold text-primary"><CurrencyText value={Number(order.payment.amount)} /></span>
                                        </div>
                                        <div className="flex justify-between">
                                            <span className="text-brandText-muted">Tanggal Transfer:</span>
                                            <span className="font-bold text-brandText">
                                                {new Date(order.payment.transfer_date).toLocaleDateString('id-ID', { year: 'numeric', month: 'long', day: 'numeric' })}
                                            </span>
                                        </div>
                                        <div className="flex justify-between">
                                            <span className="text-brandText-muted">Status Verifikasi:</span>
                                            <span className="font-bold uppercase text-[10px] text-primary">{order.payment.verification_status}</span>
                                        </div>

                                        {/* Zoomable Small Receipt preview */}
                                        <div className="pt-2">
                                            <p className="text-brandText-muted mb-2 font-semibold">Lampiran Gambar Resi:</p>
                                            <div className="w-full aspect-[4/3] rounded-xl overflow-hidden border border-brandOutline bg-cream-light/10 relative group">
                                                <img
                                                    src={`/storage/${order.payment?.proof_path}`}
                                                    alt="Resi Transfer"
                                                    className="w-full h-full object-cover cursor-pointer hover:scale-105 transition-transform"
                                                    onClick={() => window.open(`/storage/${order.payment?.proof_path}`, '_blank')}
                                                />
                                            </div>
                                            <p className="text-[9px] text-brandText-muted/80 mt-1.5 text-center italic">Klik gambar untuk melihat resolusi penuh.</p>
                                        </div>
                                    </div>
                                </div>
                            )}
                        </div>
                    </div>
                </div>
            </div>
        </PublicLayout>
    );
}

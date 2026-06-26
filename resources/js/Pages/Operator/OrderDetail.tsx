import React, { useState } from 'react';
import DashboardLayout from '@/Layouts/DashboardLayout';
import { Head, Link, router } from '@inertiajs/react';
import Button from '@/Components/common/Button';
import CurrencyText from '@/Components/common/CurrencyText';
import Alert from '@/Components/common/Alert';
import { Order } from '@/types';
import { ArrowLeft, User, Phone, MapPin, Calendar, FileText, MessageSquare, Landmark, CheckCircle2, XCircle, ChevronRight, Check, AlertTriangle } from 'lucide-react';

interface DetailProps {
    order: Order;
}

export default function OrderDetail({ order, flash }: DetailProps & { flash?: any }) {
    const [actionLoading, setActionLoading] = useState(false);
    
    // Payment Verification State
    const [rejectionMode, setRejectionMode] = useState(false);
    const [rejectionNote, setRejectionNote] = useState('');

    // Status Transition State
    const [nextStatus, setNextStatus] = useState('');
    const [transitionNote, setTransitionNote] = useState('');

    // Status labels and styling
    const getStatusBadge = (status: string) => {
        switch (status) {
            case 'pending_payment': return 'bg-amber-50 border-amber-200 text-amber-800';
            case 'waiting_verification': return 'bg-blue-50 border-blue-200 text-blue-800';
            case 'paid': return 'bg-green-50 border-green-200 text-green-800';
            case 'processing': return 'bg-indigo-50 border-indigo-200 text-indigo-800';
            case 'ready': return 'bg-indigo-50 border-indigo-200 text-indigo-800';
            case 'shipped': return 'bg-purple-50 border-purple-200 text-purple-800';
            case 'completed': return 'bg-green-50 border-green-200 text-green-800';
            case 'cancelled': return 'bg-red-50 border-red-200 text-red-800';
            case 'rejected': return 'bg-red-50 border-red-200 text-red-800';
            default: return 'bg-gray-50 border-gray-200 text-gray-800';
        }
    };

    const getStatusLabel = (status: string) => {
        switch (status) {
            case 'pending_payment': return 'Belum Bayar';
            case 'waiting_verification': return 'Menunggu Verifikasi';
            case 'paid': return 'Lunas (Menunggu Rangkaian)';
            case 'processing': return 'Sedang Dirangkai';
            case 'ready': return 'Siap Dikirim';
            case 'shipped': return 'Sedang Dikirim';
            case 'completed': return 'Selesai';
            case 'cancelled': return 'Dibatalkan';
            case 'rejected': return 'Pembayaran Ditolak';
            default: return status;
        }
    };

    // Form submission handlers
    const handleVerifyPayment = (action: 'approve' | 'reject') => {
        if (action === 'reject' && !rejectionNote.trim()) {
            alert('Silakan isi alasan penolakan terlebih dahulu.');
            return;
        }

        const confirmMsg = action === 'approve' 
            ? 'Apakah Anda yakin ingin menyetujui pembayaran ini? Stok produk akan dikurangi otomatis.'
            : 'Apakah Anda yakin ingin menolak bukti transfer ini?';

        if (confirm(confirmMsg)) {
            setActionLoading(true);
            router.post(
                route('operator.payments.verify', order.order_number),
                {
                    action,
                    rejection_note: action === 'reject' ? rejectionNote : null,
                },
                {
                    preserveScroll: true,
                    onFinish: () => {
                        setActionLoading(false);
                        setRejectionMode(false);
                        setRejectionNote('');
                    },
                }
            );
        }
    };

    const handleUpdateStatus = (e: React.FormEvent) => {
        e.preventDefault();
        if (!nextStatus) return;

        if (confirm(`Apakah Anda yakin ingin memperbarui status pesanan menjadi: ${getStatusLabel(nextStatus)}?`)) {
            setActionLoading(true);
            router.put(
                route('operator.orders.update-status', order.order_number),
                {
                    order_status: nextStatus,
                    note: transitionNote || null,
                },
                {
                    preserveScroll: true,
                    onFinish: () => {
                        setActionLoading(false);
                        setNextStatus('');
                        setTransitionNote('');
                    },
                }
            );
        }
    };

    // Determine next allowed status based on state machine
    const getAllowedTransitions = (status: string) => {
        switch (status) {
            case 'paid': return [{ value: 'processing', label: 'Mulai Dirangkai (Processing)' }];
            case 'processing': return [{ value: 'ready', label: 'Rangkaian Selesai & Siap (Ready)' }];
            case 'ready': return [{ value: 'shipped', label: 'Kirimkan Bunga (Shipped)' }];
            case 'shipped': return [{ value: 'completed', label: 'Pesanan Diterima (Completed)' }];
            default: return [];
        }
    };

    const allowedTransitions = getAllowedTransitions(order.order_status);
    const orderDateFormatted = new Date(order.order_date).toLocaleDateString('id-ID', {
        day: 'numeric',
        month: 'long',
        year: 'numeric',
        hour: '2-digit',
        minute: '2-digit',
    });

    const deliveryDateFormatted = new Date(order.delivery_date).toLocaleDateString('id-ID', {
        day: 'numeric',
        month: 'long',
        year: 'numeric',
    });

    return (
        <DashboardLayout title={`Pesanan #${order.order_number}`}>
            <Head title={`Detail Pesanan #${order.order_number} | Little Joy Management`} />

            <div className="space-y-6 font-sans">
                {/* Back button and Meta summary */}
                <div className="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <Link
                        href={route('operator.orders.index')}
                        className="inline-flex items-center text-xs font-semibold text-brandText-muted hover:text-primary transition-colors group"
                    >
                        <ArrowLeft className="w-3.5 h-3.5 mr-1 group-hover:-translate-x-0.5 transition-transform" />
                        Kembali ke Kelola Pesanan
                    </Link>
                    
                    <div className="flex items-center space-x-3">
                        <span className={`px-3 py-1 border rounded-full text-xs font-bold uppercase tracking-wider ${getStatusBadge(order.order_status)}`}>
                            {getStatusLabel(order.order_status)}
                        </span>
                    </div>
                </div>

                {flash?.success && <Alert variant="success" message={flash.success} />}
                {flash?.error && <Alert variant="danger" message={flash.error} />}

                <div className="grid grid-cols-1 lg:grid-cols-3 gap-6 items-start">
                    
                    {/* Left & Middle Column: General Details, Items, Timeline, Card Message */}
                    <div className="lg:col-span-2 space-y-6">
                        
                        {/* 1. General Billing & Customer Information */}
                        <div className="bg-white border border-brandOutline-soft/30 rounded-2xl p-6 shadow-sm">
                            <h3 className="font-serif text-base font-bold text-primary mb-4 pb-2 border-b border-brandOutline-soft/30 flex items-center">
                                <User className="w-4.5 h-4.5 mr-2 text-gold" />
                                Rincian Pelanggan & Pemesanan
                            </h3>
                            
                            <div className="grid grid-cols-1 sm:grid-cols-2 gap-6 text-sm">
                                <div className="space-y-3">
                                    <div>
                                        <p className="text-[10px] font-bold text-brandText-muted uppercase tracking-wider">Pemesan Akun</p>
                                        <p className="font-semibold text-brandText mt-0.5">{order.user?.name || 'Guest'}</p>
                                        <p className="text-xs text-brandText-muted">{order.user?.email}</p>
                                    </div>
                                    <div>
                                        <p className="text-[10px] font-bold text-brandText-muted uppercase tracking-wider">Tanggal Order</p>
                                        <p className="font-semibold text-brandText mt-0.5">{orderDateFormatted} WIB</p>
                                    </div>
                                </div>

                                <div className="space-y-3">
                                    <div>
                                        <p className="text-[10px] font-bold text-brandText-muted uppercase tracking-wider">No. Telepon Akun</p>
                                        <p className="font-semibold text-brandText mt-0.5">{order.user?.phone || '-'}</p>
                                    </div>
                                    <div>
                                        <p className="text-[10px] font-bold text-brandText-muted uppercase tracking-wider">Metode Pembayaran</p>
                                        <p className="font-semibold text-primary mt-0.5">Transfer Bank Manual</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {/* 2. Recipient & Delivery Address */}
                        <div className="bg-white border border-brandOutline-soft/30 rounded-2xl p-6 shadow-sm">
                            <h3 className="font-serif text-base font-bold text-primary mb-4 pb-2 border-b border-brandOutline-soft/30 flex items-center">
                                <MapPin className="w-4.5 h-4.5 mr-2 text-gold" />
                                Rincian Penerima & Pengiriman
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
                                    <div>
                                        <p className="text-[10px] font-bold text-brandText-muted uppercase tracking-wider">Tanggal Pengantaran</p>
                                        <p className="font-bold text-primary mt-0.5 flex items-center">
                                            <Calendar className="w-3.5 h-3.5 mr-1.5 text-primary/60" />
                                            {deliveryDateFormatted}
                                        </p>
                                    </div>
                                </div>

                                <div className="space-y-3">
                                    <div>
                                        <p className="text-[10px] font-bold text-brandText-muted uppercase tracking-wider">Alamat Pengiriman Lengkap</p>
                                        <p className="text-xs text-brandText mt-0.5 leading-relaxed bg-cream/10 border border-brandOutline-soft/25 p-3 rounded-xl">
                                            {order.delivery_address}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {/* 3. Items list invoice */}
                        <div className="bg-white border border-brandOutline-soft/30 rounded-2xl p-6 shadow-sm">
                            <h3 className="font-serif text-base font-bold text-primary mb-4 pb-2 border-b border-brandOutline-soft/30">
                                Daftar Rangkaian Bunga
                            </h3>

                            <div className="divide-y divide-brandOutline-soft/20 text-sm">
                                {order.items?.map((item: any) => (
                                    <div key={item.id} className="py-3.5 flex justify-between items-center gap-4">
                                        <div>
                                            <p className="font-bold text-primary">{item.product_name}</p>
                                            <p className="text-xs text-brandText-muted mt-0.5">
                                                {item.quantity} barang x <CurrencyText value={Number(item.unit_price)} />
                                            </p>
                                        </div>
                                        <span className="font-bold text-brandText">
                                            <CurrencyText value={Number(item.subtotal)} />
                                        </span>
                                    </div>
                                ))}
                            </div>

                            {/* Total details */}
                            <div className="space-y-2.5 text-sm pt-4 border-t border-brandOutline-soft/30">
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
                                    <span>Total Tagihan</span>
                                    <span className="font-serif text-lg">
                                        <CurrencyText value={Number(order.total)} />
                                    </span>
                                </div>
                            </div>
                        </div>

                        {/* 4. Greeting Card */}
                        {order.greeting_message && (
                            <div className="bg-white border border-brandOutline-soft/30 rounded-2xl p-6 shadow-sm">
                                <h3 className="font-serif text-base font-bold text-primary mb-4 pb-2 border-b border-brandOutline-soft/30 flex items-center">
                                    <MessageSquare className="w-4.5 h-4.5 mr-2 text-gold" />
                                    Pesan Kartu Ucapan Gratis
                                </h3>

                                <div className="max-w-md bg-cream/10 border-2 border-double border-gold/45 p-6 rounded-2xl shadow-inner relative overflow-hidden mx-auto text-center font-serif flex flex-col items-center justify-center min-h-[140px] select-all" title="Klik & seret untuk menyalin pesan">
                                    <span className="text-base text-gold mb-1">✿</span>
                                    <p className="text-sm text-primary/90 italic leading-relaxed whitespace-pre-wrap px-4 font-medium">
                                        &ldquo;{order.greeting_message}&rdquo;
                                    </p>
                                </div>
                            </div>
                        )}

                        {/* 5. Customer Notes */}
                        {order.customer_note && (
                            <div className="bg-white border border-brandOutline-soft/30 rounded-2xl p-6 shadow-sm">
                                <h3 className="font-serif text-base font-bold text-primary mb-3 flex items-center">
                                    <FileText className="w-4.5 h-4.5 mr-2 text-gold" />
                                    Catatan Tambahan Pelanggan
                                </h3>
                                <p className="text-xs text-brandText leading-relaxed italic bg-cream-light/10 border border-brandOutline-soft/20 p-3 rounded-xl">
                                    &ldquo;{order.customer_note}&rdquo;
                                </p>
                            </div>
                        )}

                        {/* 6. Timeline status history */}
                        <div className="bg-white border border-brandOutline-soft/30 rounded-2xl p-6 shadow-sm">
                            <h3 className="font-serif text-base font-bold text-primary mb-5 flex items-center">
                                <CheckCircle2 className="w-4.5 h-4.5 mr-2 text-gold" />
                                Linimasa Riwayat & Catatan Status
                            </h3>

                            <div className="relative pl-6 border-l-2 border-dashed border-brandOutline-soft/60 space-y-6 ml-3 text-sm">
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
                                            <span className={`absolute -left-[31px] top-1 w-4 h-4 rounded-full border-2 bg-white ${
                                                isLast ? 'border-primary bg-primary' : 'border-brandOutline'
                                            }`} />
                                            
                                            <div>
                                                <h4 className={`text-xs font-bold ${isLast ? 'text-primary' : 'text-brandText'}`}>
                                                    {getStatusLabel(log.current_status)}
                                                </h4>
                                                <p className="text-[10px] text-brandText-muted mt-0.5">
                                                    {logDate} WIB oleh {log.actor?.name || 'Sistem'}
                                                </p>
                                                {log.note && (
                                                    <p className="text-xs text-brandText-muted/80 bg-cream/5 border border-brandOutline-soft/10 rounded-lg p-2 mt-2 leading-relaxed italic max-w-lg">
                                                        &ldquo;{log.note}&rdquo;
                                                    </p>
                                                )}
                                            </div>
                                        </div>
                                    );
                                })}
                            </div>
                        </div>

                    </div>

                    {/* Right Column: Payments Verification & Status Transitions */}
                    <div className="space-y-6">
                        
                        {/* 1. Payments Verification Panel */}
                        <div className="bg-white border border-brandOutline-soft/30 rounded-2xl p-6 shadow-sm space-y-4">
                            <h3 className="font-serif text-base font-bold text-primary border-b border-brandOutline-soft/30 pb-2 flex items-center">
                                <Landmark className="w-4 h-4 mr-2 text-gold" />
                                Verifikasi Struk Pembayaran
                            </h3>

                            {order.payment ? (
                                <div className="space-y-4 text-xs">
                                    {/* Bank Details Table */}
                                    <div className="space-y-2 border-b border-brandOutline-soft/20 pb-3 leading-relaxed">
                                        <div className="flex justify-between">
                                            <span className="text-brandText-muted">Bank Tujuan:</span>
                                            <span className="font-bold text-brandText">{order.payment.destination_bank}</span>
                                        </div>
                                        <div className="flex justify-between">
                                            <span className="text-brandText-muted">Bank Pengirim:</span>
                                            <span className="font-bold text-brandText">{order.payment.sender_bank}</span>
                                        </div>
                                        <div className="flex justify-between">
                                            <span className="text-brandText-muted">Nama Pengirim:</span>
                                            <span className="font-bold text-brandText">{order.payment.account_holder_name}</span>
                                        </div>
                                        <div className="flex justify-between">
                                            <span className="text-brandText-muted">Jumlah Transfer:</span>
                                            <span className="font-bold text-primary"><CurrencyText value={Number(order.payment.amount)} /></span>
                                        </div>
                                        <div className="flex justify-between">
                                            <span className="text-brandText-muted">Tanggal Transfer:</span>
                                            <span className="font-bold text-brandText">
                                                {new Date(order.payment.transfer_date).toLocaleDateString('id-ID', { year: 'numeric', month: 'long', day: 'numeric' })}
                                            </span>
                                        </div>
                                    </div>

                                    {/* Attachment preview */}
                                    <div>
                                        <p className="font-bold text-brandText mb-2">Gambar Bukti Transfer:</p>
                                        <div className="w-full aspect-[3/4] border border-brandOutline rounded-xl overflow-hidden bg-cream-light/10 relative group">
                                            <img
                                                src={`/storage/${order.payment?.proof_path}`}
                                                alt="Bukti transfer"
                                                className="w-full h-full object-contain cursor-pointer hover:scale-102 transition-transform"
                                                onClick={() => window.open(`/storage/${order.payment?.proof_path}`, '_blank')}
                                            />
                                        </div>
                                        <p className="text-[9px] text-brandText-muted/80 text-center mt-1 italic">Klik gambar untuk melihat resolusi penuh di tab baru.</p>
                                    </div>

                                    {/* Verification Controls (Only if waiting verification) */}
                                    {order.order_status === 'waiting_verification' && (
                                        <div className="pt-3 border-t border-brandOutline-soft/30 space-y-3">
                                            {!rejectionMode ? (
                                                <div className="grid grid-cols-2 gap-3">
                                                    <Button
                                                        onClick={() => handleVerifyPayment('approve')}
                                                        variant="primary"
                                                        className="w-full justify-center text-xs py-2 px-1 flex items-center justify-center gap-1 shadow-sm"
                                                        disabled={actionLoading}
                                                    >
                                                        <Check className="w-3.5 h-3.5" />
                                                        Setujui
                                                    </Button>
                                                    <Button
                                                        onClick={() => setRejectionMode(true)}
                                                        variant="outline"
                                                        className="w-full justify-center text-xs py-2 px-1 border-red-200 text-red-600 hover:bg-red-50 flex items-center justify-center gap-1"
                                                        disabled={actionLoading}
                                                    >
                                                        <XCircle className="w-3.5 h-3.5" />
                                                        Tolak
                                                    </Button>
                                                </div>
                                            ) : (
                                                <div className="space-y-3 p-3 bg-red-50/50 border border-red-100 rounded-xl">
                                                    <label htmlFor="rejection_note" className="block text-[10px] font-bold text-red-800 uppercase tracking-wider mb-1">
                                                        Alasan Penolakan Pembayaran <span className="text-red-500">*</span>
                                                    </label>
                                                    <textarea
                                                        id="rejection_note"
                                                        rows={3}
                                                        value={rejectionNote}
                                                        onChange={(e) => setRejectionNote(e.target.value)}
                                                        className="w-full border border-red-300 rounded-lg p-2 text-xs bg-white text-brandText focus:outline-none focus:ring-1 focus:ring-red-500 focus:border-red-500"
                                                        placeholder="Tuliskan alasan struk transfer ini tidak valid (contoh: Gambar buram, nominal transfer kurang)..."
                                                        required
                                                    />
                                                    <div className="grid grid-cols-2 gap-2">
                                                        <Button
                                                            onClick={() => handleVerifyPayment('reject')}
                                                            variant="primary"
                                                            className="w-full justify-center text-xs py-1.5 bg-red-600 hover:bg-red-700"
                                                            disabled={actionLoading || !rejectionNote.trim()}
                                                        >
                                                            Kirim Penolakan
                                                        </Button>
                                                        <Button
                                                            onClick={() => {
                                                                setRejectionMode(false);
                                                                setRejectionNote('');
                                                            }}
                                                            variant="outline"
                                                            className="w-full justify-center text-xs py-1.5"
                                                            disabled={actionLoading}
                                                        >
                                                            Batal
                                                        </Button>
                                                    </div>
                                                </div>
                                            )}
                                        </div>
                                    )}

                                    {/* Already Verified Info */}
                                    {order.payment.verification_status === 'verified' && (
                                        <div className="p-3 bg-green-50 border border-green-100 rounded-xl text-green-800 space-y-1">
                                            <p className="font-bold flex items-center">
                                                <Check className="w-3.5 h-3.5 mr-1" /> Pembayaran Disetujui
                                            </p>
                                            <p className="text-[10px]">
                                                Diverifikasi oleh: {order.payment.verifier?.name || 'Operator'}
                                            </p>
                                            <p className="text-[10px]">
                                                Pada:{' '}
                                                {new Date(order.payment.verified_at || '').toLocaleDateString('id-ID', {
                                                    day: 'numeric',
                                                    month: 'short',
                                                    year: '2-digit',
                                                    hour: '2-digit',
                                                    minute: '2-digit',
                                                })}{' '}
                                                WIB
                                            </p>
                                        </div>
                                    )}

                                    {/* Already Rejected Info */}
                                    {order.payment.verification_status === 'rejected' && (
                                        <div className="p-3 bg-red-50 border border-red-100 rounded-xl text-red-800 space-y-1">
                                            <p className="font-bold flex items-center">
                                                <XCircle className="w-3.5 h-3.5 mr-1" /> Pembayaran Ditolak
                                            </p>
                                            <p className="text-[10px] leading-relaxed italic">
                                                Alasan: &ldquo;{order.payment.rejection_note}&rdquo;
                                            </p>
                                        </div>
                                    )}
                                </div>
                            ) : (
                                <div className="p-4 border border-dashed border-brandOutline rounded-xl text-center text-xs text-brandText-muted">
                                    Pelanggan belum mengunggah bukti transfer untuk pesanan ini.
                                </div>
                            )}
                        </div>

                        {/* 2. Order Status Transition Controls (Only if paid, processing, ready, or shipped) */}
                        {allowedTransitions.length > 0 && (
                            <div className="bg-white border border-brandOutline-soft/30 rounded-2xl p-6 shadow-sm space-y-4">
                                <h3 className="font-serif text-base font-bold text-primary border-b border-brandOutline-soft/30 pb-2 flex items-center">
                                    <ChevronRight className="w-4.5 h-4.5 mr-1 text-gold" />
                                    Pembaruan Progres Pengiriman
                                </h3>
                                
                                <form onSubmit={handleUpdateStatus} className="space-y-4 text-xs">
                                    <div>
                                        <label htmlFor="next_status" className="block text-[10px] font-bold text-brandText-muted uppercase tracking-wider mb-2">
                                            Langkah Progres Selanjutnya <span className="text-red-500">*</span>
                                        </label>
                                        <select
                                            id="next_status"
                                            value={nextStatus}
                                            onChange={(e) => setNextStatus(e.target.value)}
                                            className="w-full border border-brandOutline rounded-xl px-3 py-2 text-xs bg-cream-light/5 text-brandText focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary"
                                            required
                                        >
                                            <option value="">-- Pilih Langkah Progres --</option>
                                            {allowedTransitions.map((t) => (
                                                <option key={t.value} value={t.value}>{t.label}</option>
                                            ))}
                                        </select>
                                    </div>

                                    <div>
                                        <label htmlFor="transition_note" className="block text-[10px] font-bold text-brandText-muted uppercase tracking-wider mb-2">
                                            Catatan Progres / Alasan (Opsional)
                                        </label>
                                        <textarea
                                            id="transition_note"
                                            rows={2}
                                            value={transitionNote}
                                            onChange={(e) => setTransitionNote(e.target.value)}
                                            className="w-full border border-brandOutline rounded-xl p-2 text-xs bg-cream-light/5 text-brandText focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary"
                                            placeholder="Tulis informasi pengiriman atau catatan perangkaian bunga..."
                                        />
                                    </div>

                                    <Button
                                        type="submit"
                                        variant="primary"
                                        className="w-full justify-center py-2 flex items-center justify-center gap-1 shadow-sm"
                                        isLoading={actionLoading}
                                        disabled={actionLoading || !nextStatus}
                                    >
                                        Perbarui Status Pesanan
                                    </Button>
                                </form>
                            </div>
                        )}

                        {/* Safety Disclaimer */}
                        <div className="bg-cream/15 border border-brandOutline-soft/20 rounded-2xl p-4 text-[10px] text-brandText-muted/95 flex items-start gap-2 leading-relaxed">
                            <AlertTriangle className="w-4 h-4 text-gold flex-shrink-0 mt-0.5" />
                            <div>
                                <p className="font-bold text-primary">Informasi Penjagaan Keamanan</p>
                                <p className="mt-1">Penyetujuan pembayaran mengunci database dan memotong persediaan stok produk secara real-time. Pastikan Anda telah melihat keselarasan nominal struk transfer di m-Banking sebelum mengeklik tombol Setuju.</p>
                            </div>
                        </div>

                    </div>

                </div>
            </div>
        </DashboardLayout>
    );
}

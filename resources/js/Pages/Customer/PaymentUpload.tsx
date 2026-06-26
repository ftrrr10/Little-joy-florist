import React, { useState, useRef } from 'react';
import { Head, Link, useForm } from '@inertiajs/react';
import PublicLayout from '@/Layouts/PublicLayout';
import Button from '@/Components/common/Button';
import CurrencyText from '@/Components/common/CurrencyText';
import Alert from '@/Components/common/Alert';
import { Order, PageProps } from '@/types';
import { ArrowLeft, Upload, Image as ImageIcon, X, AlertCircle, Calendar, CreditCard, Landmark } from 'lucide-react';

interface UploadProps extends Record<string, unknown> {
    order: Order;
}

export default function PaymentUpload({ order, flash }: PageProps<UploadProps>) {
    const fileInputRef = useRef<HTMLInputElement>(null);
    const [imagePreview, setImagePreview] = useState<string | null>(null);
    const [dragActive, setDragActive] = useState(false);
    
    const todayStr = new Date().toISOString().split('T')[0];

    const { data, setData, post, processing, errors } = useForm({
        destination_bank: '',
        sender_bank: '',
        account_holder_name: '',
        amount: String(order.total),
        transfer_date: todayStr,
        proof_image: null as File | null,
    });

    const handleFileChange = (file: File | null) => {
        if (!file) {
            setData('proof_image', null);
            setImagePreview(null);
            return;
        }

        // Validate client-side
        if (file.size > 2 * 1024 * 1024) {
            alert('Ukuran berkas melebihi 2 MB. Silakan unggah gambar yang lebih kecil.');
            return;
        }

        setData('proof_image', file);
        
        // Generate preview URL
        const reader = new FileReader();
        reader.onloadend = () => {
            setImagePreview(reader.result as string);
        };
        reader.readAsDataURL(file);
    };

    const handleDrag = (e: React.DragEvent) => {
        e.preventDefault();
        e.stopPropagation();
        if (e.type === "dragenter" || e.type === "dragover") {
            setDragActive(true);
        } else if (e.type === "dragleave") {
            setDragActive(false);
        }
    };

    const handleDrop = (e: React.DragEvent) => {
        e.preventDefault();
        e.stopPropagation();
        setDragActive(false);
        
        if (e.dataTransfer.files && e.dataTransfer.files[0]) {
            handleFileChange(e.dataTransfer.files[0]);
        }
    };

    const triggerFileInput = () => {
        fileInputRef.current?.click();
    };

    const handleRemoveImage = () => {
        setData('proof_image', null);
        setImagePreview(null);
        if (fileInputRef.current) {
            fileInputRef.current.value = '';
        }
    };

    const handleSubmit = (e: React.FormEvent) => {
        e.preventDefault();
        
        // Ensure image is selected
        if (!data.proof_image) {
            alert('Silakan unggah foto bukti transfer terlebih dahulu.');
            return;
        }

        post(route('customer.payments.store', order.order_number));
    };

    return (
        <PublicLayout>
            <Head title={`Unggah Bukti Pembayaran #${order.order_number} | Little Joy Jakarta`} />

            <div className="bg-cream-light/30 min-h-screen py-12 font-sans">
                <div className="max-w-3xl mx-auto px-4">
                    {/* Back button */}
                    <div className="mb-8">
                        <Link
                            href={route('customer.orders.show', order.order_number)}
                            className="inline-flex items-center text-xs font-semibold text-brandText-muted hover:text-primary transition-colors mb-3 group"
                        >
                            <ArrowLeft className="w-3.5 h-3.5 mr-1 group-hover:-translate-x-0.5 transition-transform" />
                            Kembali ke Detail Pesanan
                        </Link>
                        <h1 className="font-serif text-3xl font-bold text-primary tracking-tight">
                            Unggah Bukti Pembayaran
                        </h1>
                        <p className="text-sm text-brandText-muted mt-1">
                            Isi rincian transfer dan unggah foto resi transaksi Anda untuk verifikasi.
                        </p>
                    </div>

                    {flash?.error && (
                        <div className="mb-6">
                            <Alert variant="danger" message={flash.error} />
                        </div>
                    )}

                    <div className="grid grid-cols-1 md:grid-cols-3 gap-6 items-start">
                        {/* Order Snapshot Card */}
                        <div className="md:col-span-1 bg-white border border-brandOutline-soft/30 rounded-2xl p-5 shadow-sm space-y-4">
                            <h4 className="font-serif text-sm font-bold text-primary border-b border-brandOutline-soft/30 pb-2">
                                Detail Tagihan
                            </h4>
                            <div>
                                <p className="text-[10px] font-bold text-brandText-muted uppercase tracking-wider">No. Pesanan</p>
                                <p className="font-mono text-sm font-bold text-primary mt-0.5">#{order.order_number}</p>
                            </div>
                            <div>
                                <p className="text-[10px] font-bold text-brandText-muted uppercase tracking-wider">Total Harus Dibayar</p>
                                <p className="font-serif text-base font-bold text-primary mt-0.5">
                                    <CurrencyText value={Number(order.total)} />
                                </p>
                            </div>
                            
                            {order.order_status === 'rejected' && order.payment?.rejection_note && (
                                <div className="p-3 bg-red-50 border border-red-100 rounded-xl space-y-1">
                                    <p className="text-[10px] font-bold text-red-700 flex items-center">
                                        <AlertCircle className="w-3 h-3 mr-1" /> Pembayaran Ditolak
                                    </p>
                                    <p className="text-[10px] text-red-600 leading-relaxed italic">
                                        &ldquo;{order.payment.rejection_note}&rdquo;
                                    </p>
                                </div>
                            )}
                        </div>

                        {/* Upload Form */}
                        <div className="md:col-span-2">
                            <form onSubmit={handleSubmit} className="bg-white border border-brandOutline-soft/30 rounded-2xl p-6 shadow-sm space-y-6">
                                {/* Bank Details */}
                                <div className="space-y-4">
                                    <h3 className="font-serif text-base font-bold text-primary border-b border-brandOutline-soft/30 pb-2 flex items-center">
                                        <Landmark className="w-4 h-4 mr-2 text-gold" />
                                        Informasi Transfer Bank
                                    </h3>

                                    {/* Destination Bank */}
                                    <div>
                                        <label htmlFor="destination_bank" className="block text-xs font-bold text-brandText-muted uppercase tracking-wider mb-2">
                                            Rekening Bank Tujuan <span className="text-red-500">*</span>
                                        </label>
                                        <select
                                            id="destination_bank"
                                            value={data.destination_bank}
                                            onChange={(e) => setData('destination_bank', e.target.value)}
                                            className={`w-full border rounded-xl px-4 py-2.5 text-sm bg-cream-light/10 text-brandText focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary transition-all ${
                                                errors.destination_bank ? 'border-red-300 focus:ring-red-500 focus:border-red-500' : 'border-brandOutline'
                                            }`}
                                            required
                                        >
                                            <option value="">-- Pilih Rekening Tujuan --</option>
                                            <option value="BCA (123-456-7890 a/n Little Joy Jakarta)">BCA - 123-456-7890 a/n Little Joy Jakarta</option>
                                            <option value="Mandiri (098-765-4321 a/n Little Joy Jakarta)">Mandiri - 098-765-4321 a/n Little Joy Jakarta</option>
                                        </select>
                                        {errors.destination_bank && (
                                            <p className="text-xs text-red-500 mt-1.5 font-medium">{errors.destination_bank}</p>
                                        )}
                                    </div>

                                    <div className="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                        {/* Sender Bank */}
                                        <div>
                                            <label htmlFor="sender_bank" className="block text-xs font-bold text-brandText-muted uppercase tracking-wider mb-2">
                                                Bank Pengirim <span className="text-red-500">*</span>
                                            </label>
                                            <input
                                                type="text"
                                                id="sender_bank"
                                                value={data.sender_bank}
                                                onChange={(e) => setData('sender_bank', e.target.value)}
                                                className={`w-full border rounded-xl px-4 py-2.5 text-sm bg-cream-light/10 text-brandText focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary transition-all ${
                                                    errors.sender_bank ? 'border-red-300 focus:ring-red-500 focus:border-red-500' : 'border-brandOutline'
                                                }`}
                                                placeholder="Contoh: BCA, Mandiri, BRI, BNI"
                                                required
                                            />
                                            {errors.sender_bank && (
                                                <p className="text-xs text-red-500 mt-1.5 font-medium">{errors.sender_bank}</p>
                                            )}
                                        </div>

                                        {/* Account Holder Name */}
                                        <div>
                                            <label htmlFor="account_holder_name" className="block text-xs font-bold text-brandText-muted uppercase tracking-wider mb-2">
                                                Nama Pemilik Rekening Pengirim <span className="text-red-500">*</span>
                                            </label>
                                            <input
                                                type="text"
                                                id="account_holder_name"
                                                value={data.account_holder_name}
                                                onChange={(e) => setData('account_holder_name', e.target.value)}
                                                className={`w-full border rounded-xl px-4 py-2.5 text-sm bg-cream-light/10 text-brandText focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary transition-all ${
                                                    errors.account_holder_name ? 'border-red-300 focus:ring-red-500 focus:border-red-500' : 'border-brandOutline'
                                                }`}
                                                placeholder="Sesuai nama di buku tabungan/m-Banking"
                                                required
                                            />
                                            {errors.account_holder_name && (
                                                <p className="text-xs text-red-500 mt-1.5 font-medium">{errors.account_holder_name}</p>
                                            )}
                                        </div>
                                    </div>

                                    <div className="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                        {/* Transfer Amount */}
                                        <div>
                                            <label htmlFor="amount" className="block text-xs font-bold text-brandText-muted uppercase tracking-wider mb-2">
                                                Nominal yang Ditransfer <span className="text-red-500">*</span>
                                            </label>
                                            <div className="relative">
                                                <span className="absolute left-4 top-2.5 text-sm text-brandText-muted font-semibold">Rp</span>
                                                <input
                                                    type="number"
                                                    id="amount"
                                                    value={data.amount}
                                                    onChange={(e) => setData('amount', e.target.value)}
                                                    className={`w-full border rounded-xl pl-10 pr-4 py-2.5 text-sm bg-cream-light/10 text-brandText font-semibold focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary transition-all ${
                                                        errors.amount ? 'border-red-300 focus:ring-red-500 focus:border-red-500' : 'border-brandOutline'
                                                    }`}
                                                    required
                                                />
                                            </div>
                                            {errors.amount && (
                                                <p className="text-xs text-red-500 mt-1.5 font-medium">{errors.amount}</p>
                                            )}
                                        </div>

                                        {/* Transfer Date */}
                                        <div>
                                            <label htmlFor="transfer_date" className="block text-xs font-bold text-brandText-muted uppercase tracking-wider mb-2 flex items-center">
                                                <Calendar className="w-3.5 h-3.5 mr-1.5 text-primary" />
                                                Tanggal Transfer <span className="text-red-500">*</span>
                                            </label>
                                            <input
                                                type="date"
                                                id="transfer_date"
                                                max={todayStr}
                                                value={data.transfer_date}
                                                onChange={(e) => setData('transfer_date', e.target.value)}
                                                className={`w-full border rounded-xl px-4 py-2.5 text-sm bg-cream-light/10 text-brandText focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary transition-all ${
                                                    errors.transfer_date ? 'border-red-300 focus:ring-red-500 focus:border-red-500' : 'border-brandOutline'
                                                }`}
                                                required
                                            />
                                            {errors.transfer_date && (
                                                <p className="text-xs text-red-500 mt-1.5 font-medium">{errors.transfer_date}</p>
                                            )}
                                        </div>
                                    </div>
                                </div>

                                {/* Drag-and-Drop Image Uploader */}
                                <div className="space-y-3">
                                    <label className="block text-xs font-bold text-brandText-muted uppercase tracking-wider">
                                        Foto Bukti Pembayaran / Struk Transfer <span className="text-red-500">*</span>
                                    </label>
                                    
                                    <input
                                        type="file"
                                        ref={fileInputRef}
                                        onChange={(e) => handleFileChange(e.target.files?.[0] || null)}
                                        className="hidden"
                                        accept="image/jpeg,image/png,image/webp"
                                    />

                                    {!imagePreview ? (
                                        <div
                                            onDragEnter={handleDrag}
                                            onDragOver={handleDrag}
                                            onDragLeave={handleDrag}
                                            onDrop={handleDrop}
                                            onClick={triggerFileInput}
                                            className={`border-2 border-dashed rounded-2xl p-8 flex flex-col items-center justify-center text-center cursor-pointer transition-all ${
                                                dragActive 
                                                    ? 'border-gold bg-cream/10' 
                                                    : errors.proof_image
                                                        ? 'border-red-300 bg-red-50/10 hover:bg-red-50/20'
                                                        : 'border-brandOutline hover:border-primary hover:bg-cream-light/10'
                                            }`}
                                        >
                                            <Upload className="w-10 h-10 text-brandText-muted mb-4" />
                                            <p className="text-sm font-semibold text-brandText">
                                                Seret & taruh foto resi di sini, atau <span className="text-primary hover:underline">pilih berkas</span>
                                            </p>
                                            <p className="text-[10px] text-brandText-muted mt-2">
                                                Mendukung JPG, JPEG, PNG, dan WebP (Maksimal 2 MB).
                                            </p>
                                        </div>
                                    ) : (
                                        <div className="relative border border-brandOutline rounded-2xl overflow-hidden bg-cream-light/10 p-4 flex flex-col items-center justify-center">
                                            <div className="relative max-w-xs aspect-[3/4] w-full rounded-xl overflow-hidden border border-brandOutline bg-white">
                                                <img
                                                    src={imagePreview}
                                                    alt="Preview Bukti Transfer"
                                                    className="w-full h-full object-contain"
                                                />
                                                <button
                                                    type="button"
                                                    onClick={handleRemoveImage}
                                                    className="absolute top-2 right-2 p-1.5 bg-black/60 hover:bg-black/80 rounded-full text-white transition-colors focus:outline-none"
                                                    title="Hapus gambar"
                                                >
                                                    <X className="w-4 h-4" />
                                                </button>
                                            </div>
                                            <p className="text-[10px] text-brandText-muted mt-3 font-semibold flex items-center">
                                                <ImageIcon className="w-3.5 h-3.5 mr-1 text-primary" />
                                                {data.proof_image?.name} ({(data.proof_image?.size || 0) > 1024 * 1024 
                                                    ? `${((data.proof_image?.size || 0) / (1024 * 1024)).toFixed(2)} MB` 
                                                    : `${((data.proof_image?.size || 0) / 1024).toFixed(0)} KB`})
                                            </p>
                                        </div>
                                    )}
                                    {errors.proof_image && (
                                        <p className="text-xs text-red-500 mt-1.5 font-medium">{errors.proof_image}</p>
                                    )}
                                </div>

                                {/* Submit button */}
                                <div className="pt-4 border-t border-brandOutline-soft/30">
                                    <Button
                                        type="submit"
                                        variant="primary"
                                        className="w-full justify-center py-2.5 flex items-center space-x-2 shadow-md"
                                        isLoading={processing}
                                        disabled={processing || !data.proof_image}
                                    >
                                        <Upload className="w-4 h-4 mr-2" />
                                        Kirim Bukti Pembayaran
                                    </Button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </PublicLayout>
    );
}

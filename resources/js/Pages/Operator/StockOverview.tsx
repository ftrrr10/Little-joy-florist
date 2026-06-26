import React, { useState } from 'react';
import DashboardLayout from '@/Layouts/DashboardLayout';
import { Head, useForm, usePage, router } from '@inertiajs/react';
import { PageProps } from '@/types';
import Alert from '@/Components/common/Alert';
import Button from '@/Components/common/Button';
import { 
    Package, 
    History, 
    PlusCircle, 
    AlertTriangle, 
    User, 
    Calendar, 
    SlidersHorizontal,
    X, 
    ArrowUpRight, 
    ArrowDownRight 
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

interface Actor {
    id: number;
    name: string;
    role: string;
}

interface StockMovement {
    id: number;
    product_id: number;
    movement_type: string; // in, out, adjustment
    quantity: number;
    stock_before: number;
    stock_after: number;
    reference_type: string | null;
    reference_id: number | null;
    note: string | null;
    created_at: string;
    product?: Product;
    actor?: Actor;
}

interface StockProps extends PageProps {
    products: Product[];
    movements: StockMovement[];
}

export default function StockOverview() {
    const { products = [], movements = [], flash } = usePage<StockProps>().props;
    const [selectedProduct, setSelectedProduct] = useState<Product | null>(null);
    const [showModal, setShowModal] = useState(false);

    // Form setup for stock adjustment
    const { data, setData, post, processing, reset, errors } = useForm({
        product_id: '',
        adjustment_type: 'add', // add or subtract
        quantity: '',
        note: '',
    });

    const handleOpenModal = (product: Product) => {
        setSelectedProduct(product);
        setData({
            product_id: String(product.id),
            adjustment_type: 'add',
            quantity: '',
            note: '',
        });
        setShowModal(true);
    };

    const handleCloseModal = () => {
        setShowModal(false);
        setSelectedProduct(null);
        reset();
    };

    const handleSubmit = (e: React.FormEvent) => {
        e.preventDefault();
        
        // Convert quantity to signed integer based on type
        const signedQty = data.adjustment_type === 'add' 
            ? parseInt(data.quantity) 
            : -parseInt(data.quantity);

        router.post(route('operator.stock.adjust'), {
            product_id: data.product_id,
            quantity: signedQty,
            note: data.note,
        }, {
            onSuccess: () => {
                handleCloseModal();
            },
        });
    };

    const getStockIndicator = (stock: number) => {
        if (stock === 0) {
            return {
                text: 'Habis',
                bg: 'bg-red-50 text-red-700 border-red-200',
            };
        } else if (stock <= 5) {
            return {
                text: 'Stok Terbatas',
                bg: 'bg-yellow-50 text-yellow-700 border-yellow-200',
            };
        } else {
            return {
                text: 'Tersedia',
                bg: 'bg-green-50 text-green-700 border-green-200',
            };
        }
    };

    const getMovementTypeBadge = (type: string, note: string | null) => {
        const isOut = type === 'out' || (type === 'adjustment' && note?.toLowerCase().includes('kurang'));
        if (type === 'in') {
            return {
                text: 'Masuk',
                bg: 'bg-green-50 text-green-700',
                icon: <ArrowUpRight className="w-3 h-3 mr-1" />,
            };
        } else if (isOut) {
            return {
                text: 'Keluar',
                bg: 'bg-red-50 text-red-700',
                icon: <ArrowDownRight className="w-3 h-3 mr-1" />,
            };
        } else {
            return {
                text: 'Penyesuaian',
                bg: 'bg-amber-50 text-amber-700',
                icon: <SlidersHorizontal className="w-3 h-3 mr-1" />,
            };
        }
    };

    return (
        <DashboardLayout title="Stok & Inventori Bunga">
            <Head title="Ringkasan Stok | Little Joy Management" />

            <div className="space-y-8 font-sans">
                {/* 1. Welcome Header Banner */}
                <div className="bg-gradient-to-r from-primary to-primary-dark p-8 rounded-3xl text-white shadow-sm relative overflow-hidden">
                    <div className="absolute top-0 right-0 w-64 h-64 bg-white/5 rounded-full blur-3xl -mr-10 -mt-10 pointer-events-none"></div>
                    <span className="inline-block px-2.5 py-0.5 text-[10px] font-bold tracking-wider uppercase bg-white/20 text-white rounded-full mb-3 backdrop-blur-md">
                        Inventori Gudang
                    </span>
                    <h3 className="font-serif text-3xl font-bold mb-2">
                        Ringkasan Stok & Inventori
                    </h3>
                    <p className="text-white/80 text-sm max-w-2xl leading-relaxed">
                        Kelola ketersediaan tangkai bunga segar secara real-time. Anda dapat melakukan penyesuaian jumlah persediaan secara berkala dan memantau log historis riwayat keluar-masuk barang secara transparan.
                    </p>
                </div>

                {flash?.success && (
                    <Alert variant="success" message={flash.success} />
                )}
                {flash?.error && (
                    <Alert variant="danger" message={flash.error} />
                )}

                {/* 2. Main Workspace Grid */}
                <div className="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    {/* Left Column: Product Stock Level Table (Col-span 2) */}
                    <div className="lg:col-span-2 bg-white p-6 rounded-2xl border border-brandOutline-soft/30 shadow-sm">
                        <div className="flex items-center justify-between mb-4">
                            <h4 className="font-serif text-lg font-bold text-primary">
                                Tingkat Ketersediaan Produk Bunga
                            </h4>
                            <span className="text-xs text-brandText-muted font-semibold">
                                Total {products.length} produk terdaftar
                            </span>
                        </div>

                        <div className="overflow-x-auto">
                            <table className="w-full text-left text-xs border-collapse">
                                <thead>
                                    <tr className="border-b border-gray-100 text-brandText-muted font-bold uppercase tracking-wider bg-gray-50/50">
                                        <th className="py-3 px-4">Nama Produk Bunga</th>
                                        <th className="py-3 px-3">Kategori</th>
                                        <th className="py-3 px-3 text-center">Indikator</th>
                                        <th className="py-3 px-3 text-right">Jumlah Stok</th>
                                        <th className="py-3 px-4 text-right">Tindakan</th>
                                    </tr>
                                </thead>
                                <tbody className="divide-y divide-gray-100 text-brandText">
                                    {products.length === 0 ? (
                                        <tr>
                                            <td colSpan={5} className="py-8 text-center text-brandText-muted/60">
                                                Belum ada produk bunga aktif terdaftar.
                                            </td>
                                        </tr>
                                    ) : (
                                        products.map((product) => {
                                            const indicator = getStockIndicator(product.stock);
                                            return (
                                                <tr key={product.id} className="hover:bg-cream-light/5 transition-colors">
                                                    <td className="py-3.5 px-4 font-semibold text-primary">
                                                        {product.name}
                                                    </td>
                                                    <td className="py-3.5 px-3 uppercase tracking-wider text-[10px] text-brandText-muted">
                                                        {product.category?.name || 'Umum'}
                                                    </td>
                                                    <td className="py-3.5 px-3 text-center">
                                                        <span className={`inline-block px-2.5 py-0.5 border rounded-full text-[10px] font-bold tracking-wide ${indicator.bg}`}>
                                                            {indicator.text}
                                                        </span>
                                                    </td>
                                                    <td className="py-3.5 px-3 text-right font-mono font-bold text-sm">
                                                        {product.stock} tangkai
                                                    </td>
                                                    <td className="py-3.5 px-4 text-right">
                                                        <button
                                                            type="button"
                                                            onClick={() => handleOpenModal(product)}
                                                            className="inline-flex items-center justify-center px-2.5 py-1.5 bg-primary-soft/30 hover:bg-primary-soft text-primary-dark text-[10px] font-bold rounded-lg transition-all"
                                                        >
                                                            <PlusCircle className="w-3.5 h-3.5 mr-1" /> Adjust
                                                        </button>
                                                    </td>
                                                </tr>
                                            );
                                        })
                                    )}
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {/* Right Column: Stock Movement History Log */}
                    <div className="bg-white p-6 rounded-2xl border border-brandOutline-soft/30 shadow-sm flex flex-col justify-between">
                        <div>
                            <div className="flex items-center gap-2 mb-1">
                                <History className="w-5 h-5 text-gold" />
                                <h4 className="font-serif text-lg font-bold text-primary">
                                    Riwayat Log Mutasi Stok
                                </h4>
                            </div>
                            <p className="text-xs text-brandText-muted mb-4">
                                Catatan log 30 transaksi mutasi inventori terbaru.
                            </p>

                            <div className="space-y-4 max-h-[480px] overflow-y-auto pr-1">
                                {movements.length === 0 ? (
                                    <div className="py-16 text-center text-brandText-muted/60 flex flex-col items-center justify-center gap-2">
                                        <Package className="w-8 h-8 text-brandOutline-soft/40" />
                                        <p className="text-xs font-bold text-brandText-muted">Tidak Ada Mutasi</p>
                                        <p className="text-[10px] text-brandText-muted/70 max-w-[180px]">Belum ada riwayat perubahan stok yang tercatat.</p>
                                    </div>
                                ) : (
                                    movements.map((move) => {
                                        const badge = getMovementTypeBadge(move.movement_type, move.note);
                                        const moveDate = new Date(move.created_at).toLocaleDateString('id-ID', {
                                            day: 'numeric',
                                            month: 'short',
                                            hour: '2-digit',
                                            minute: '2-digit'
                                        });

                                        return (
                                            <div 
                                                key={move.id} 
                                                className="p-3 border border-brandOutline-soft/20 rounded-xl bg-cream-light/5 hover:bg-cream-light/10 transition-all space-y-2 text-xs"
                                            >
                                                <div className="flex items-center justify-between">
                                                    <span className="font-bold text-primary truncate max-w-[150px]" title={move.product?.name}>
                                                        {move.product?.name || 'Produk Dihapus'}
                                                    </span>
                                                    <span className={`inline-flex items-center px-1.5 py-0.5 rounded text-[9px] font-bold uppercase ${badge.bg}`}>
                                                        {badge.icon}
                                                        {badge.text}
                                                    </span>
                                                </div>
                                                
                                                <div className="grid grid-cols-2 gap-1 text-[10px] text-brandText-muted">
                                                    <div className="flex items-center">
                                                        <Calendar className="w-3 h-3 mr-1" />
                                                        {moveDate} WIB
                                                    </div>
                                                    <div className="flex items-center justify-end">
                                                        <User className="w-3 h-3 mr-1" />
                                                        {move.actor?.name || 'Sistem'}
                                                    </div>
                                                </div>

                                                <div className="pt-1.5 border-t border-brandOutline-soft/10 flex justify-between items-center">
                                                    <span className="text-[10px] text-brandText-muted italic truncate max-w-[170px]" title={move.note || ''}>
                                                        &ldquo;{move.note || 'Tanpa catatan'}&rdquo;
                                                    </span>
                                                    <span className="font-mono font-bold text-primary">
                                                        {move.stock_before} $\rightarrow$ {move.stock_after} ({move.stock_after >= move.stock_before ? '+' : '-'}{move.quantity})
                                                    </span>
                                                </div>
                                            </div>
                                        );
                                    })
                                )}
                            </div>
                        </div>
                    </div>
                </div>

                {/* 3. Manual Stock Adjustment Modal */}
                {showModal && selectedProduct && (
                    <div className="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-center justify-center p-4">
                        <div className="bg-white border border-brandOutline-soft/30 rounded-3xl max-w-md w-full overflow-hidden shadow-xl animate-in fade-in zoom-in-95 duration-200">
                            {/* Modal Header */}
                            <div className="px-6 py-4 border-b border-brandOutline-soft/20 flex items-center justify-between bg-cream/10">
                                <h3 className="font-serif text-lg font-bold text-primary flex items-center">
                                    <SlidersHorizontal className="w-4.5 h-4.5 mr-2 text-gold" />
                                    Penyesuaian Stok Bunga
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
                            <form onSubmit={handleSubmit} className="p-6 space-y-4">
                                {/* Product Summary */}
                                <div className="p-4 bg-brandBackground border border-brandOutline-soft/20 rounded-2xl flex justify-between items-center">
                                    <div>
                                        <p className="text-[10px] font-bold text-brandText-muted uppercase tracking-wider">Nama Produk</p>
                                        <p className="font-serif text-sm font-bold text-primary mt-0.5">{selectedProduct.name}</p>
                                    </div>
                                    <div className="text-right">
                                        <p className="text-[10px] font-bold text-brandText-muted uppercase tracking-wider">Stok Saat Ini</p>
                                        <p className="font-mono font-bold text-base text-primary mt-0.5">{selectedProduct.stock} tangkai</p>
                                    </div>
                                </div>

                                {/* Type of adjustment */}
                                <div>
                                    <label className="block text-xs font-bold text-brandText-muted uppercase tracking-wider mb-2">
                                        Jenis Penyesuaian
                                    </label>
                                    <div className="grid grid-cols-2 gap-3">
                                        <button
                                            type="button"
                                            onClick={() => setData('adjustment_type', 'add')}
                                            className={`py-2 px-4 text-xs font-bold rounded-xl border text-center transition-all ${
                                                data.adjustment_type === 'add'
                                                    ? 'bg-green-50 border-green-400 text-green-700 ring-2 ring-green-100'
                                                    : 'border-brandOutline hover:bg-gray-50 text-brandText-muted'
                                            }`}
                                        >
                                            + Tambah Stok
                                        </button>
                                        <button
                                            type="button"
                                            onClick={() => setData('adjustment_type', 'subtract')}
                                            className={`py-2 px-4 text-xs font-bold rounded-xl border text-center transition-all ${
                                                data.adjustment_type === 'subtract'
                                                    ? 'bg-red-50 border-red-300 text-red-700 ring-2 ring-red-50'
                                                    : 'border-brandOutline hover:bg-gray-50 text-brandText-muted'
                                            }`}
                                        >
                                            - Kurangi Stok
                                        </button>
                                    </div>
                                </div>

                                {/* Adjustment quantity */}
                                <div>
                                    <label htmlFor="quantity" className="block text-xs font-bold text-brandText-muted uppercase tracking-wider mb-2">
                                        Jumlah Penyesuaian (tangkai)
                                    </label>
                                    <input
                                        type="number"
                                        id="quantity"
                                        min="1"
                                        required
                                        value={data.quantity}
                                        onChange={(e) => setData('quantity', e.target.value)}
                                        placeholder="Contoh: 10"
                                        className={`w-full border rounded-xl px-4 py-2.5 text-sm bg-cream-light/10 text-brandText font-semibold focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary transition-all ${
                                            errors.quantity ? 'border-red-300 focus:ring-red-500' : 'border-brandOutline'
                                        }`}
                                    />
                                    {errors.quantity && (
                                        <p className="text-xs text-red-500 mt-1.5 font-medium">{errors.quantity}</p>
                                    )}
                                </div>

                                {/* Adjustment note */}
                                <div>
                                    <label htmlFor="note" className="block text-xs font-bold text-brandText-muted uppercase tracking-wider mb-2">
                                        Alasan / Catatan Penyesuaian
                                    </label>
                                    <textarea
                                        id="note"
                                        rows={3}
                                        required
                                        value={data.note}
                                        onChange={(e) => setData('note', e.target.value)}
                                        placeholder="Contoh: Pengiriman bunga segar pagi, Koreksi stok bunga layu, dll..."
                                        className={`w-full border rounded-xl px-4 py-2.5 text-sm bg-cream-light/10 text-brandText focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary transition-all ${
                                            errors.note ? 'border-red-300 focus:ring-red-500' : 'border-brandOutline'
                                        }`}
                                    />
                                    {errors.note && (
                                        <p className="text-xs text-red-500 mt-1.5 font-medium">{errors.note}</p>
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
                                        className="py-2 text-xs flex items-center gap-1 shadow-md"
                                        isLoading={processing}
                                        disabled={processing || !data.quantity || !data.note}
                                    >
                                        Simpan Penyesuaian
                                    </Button>
                                </div>
                            </form>
                        </div>
                    </div>
                )}
            </div>
        </DashboardLayout>
    );
}

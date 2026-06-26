import React from 'react';
import { Head, Link, router } from '@inertiajs/react';
import { Product, PageProps } from '@/types';
import DashboardLayout from '@/Layouts/DashboardLayout';
import Pagination from '@/Components/common/Pagination';
import Button from '@/Components/common/Button';
import CurrencyText from '@/Components/common/CurrencyText';
import Alert from '@/Components/common/Alert';

interface ProductListProps extends PageProps {
    products: {
        data: Product[];
        links: any[];
        total: number;
        from: number;
        to: number;
    };
}

export default function ProductList({ products, flash }: ProductListProps) {
    const handleDelete = (product: Product) => {
        if (confirm(`Apakah Anda yakin ingin menghapus produk "${product.name}"? Hapus produk akan bersifat soft-delete untuk menjaga keutuhan riwayat transaksi.`)) {
            router.delete(route('admin.products.destroy', product.id));
        }
    };

    return (
        <DashboardLayout title="Daftar Produk Rangkaian Bunga">
            <Head title="Kelola Produk - Admin" />

            <div className="space-y-6 font-sans">
                {/* Upper Action Bar */}
                <div className="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 bg-white p-5 rounded-2xl border border-brandOutline-soft/25 shadow-sm">
                    <div>
                        <h2 className="text-base font-bold text-brandText">Kelola Produk Master</h2>
                        <p className="text-xs text-brandText-muted mt-1">
                            Tambahkan rangkaian bunga baru, sesuaikan harga, stok, maupun unggah foto produk florist Anda.
                        </p>
                    </div>
                    <Link href={route('admin.products.create')}>
                        <Button variant="primary" size="sm" className="flex items-center gap-1.5">
                            <svg className="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2.5" d="M12 4v16m8-8H4" />
                            </svg>
                            Tambah Produk Baru
                        </Button>
                    </Link>
                </div>

                {/* Flash Messages */}
                {flash.success && <Alert variant="success" message={flash.success} />}
                {flash.error && <Alert variant="danger" message={flash.error} />}

                {/* Products Table Card */}
                <div className="bg-white border border-brandOutline-soft/25 rounded-2xl shadow-sm overflow-hidden">
                    <div className="overflow-x-auto">
                        <table className="min-w-full divide-y divide-brandSurface-high/60 text-left">
                            <thead className="bg-brandSurface-low/40">
                                <tr>
                                    <th scope="col" className="px-6 py-4 text-xs font-bold text-brandText-muted uppercase tracking-wider">
                                        Foto
                                    </th>
                                    <th scope="col" className="px-6 py-4 text-xs font-bold text-brandText-muted uppercase tracking-wider">
                                        Nama Produk
                                    </th>
                                    <th scope="col" className="px-6 py-4 text-xs font-bold text-brandText-muted uppercase tracking-wider">
                                        Kategori
                                    </th>
                                    <th scope="col" className="px-6 py-4 text-xs font-bold text-brandText-muted uppercase tracking-wider">
                                        Harga
                                    </th>
                                    <th scope="col" className="px-6 py-4 text-xs font-bold text-brandText-muted uppercase tracking-wider text-center">
                                        Stok
                                    </th>
                                    <th scope="col" className="px-6 py-4 text-xs font-bold text-brandText-muted uppercase tracking-wider">
                                        Status
                                    </th>
                                    <th scope="col" className="px-6 py-4 text-xs font-bold text-brandText-muted uppercase tracking-wider text-right">
                                        Aksi
                                    </th>
                                </tr>
                            </thead>
                            <tbody className="divide-y divide-brandSurface-low bg-white text-sm">
                                {products.data.length > 0 ? (
                                    products.data.map((product) => {
                                        const isLowStock = product.stock <= 5 && product.stock > 0;
                                        const isOutOfStock = product.stock <= 0;

                                        return (
                                            <tr key={product.id} className="hover:bg-brandSurface-low/20 transition-colors">
                                                {/* Image Thumbnail */}
                                                <td className="px-6 py-4 whitespace-nowrap">
                                                    <div className="h-11 w-14 rounded-lg overflow-hidden bg-brandSurface-low border border-brandOutline-soft/15 flex-shrink-0">
                                                        {product.image_path ? (
                                                            <img
                                                                src={`/storage/${product.image_path}`}
                                                                alt={product.name}
                                                                className="w-full h-full object-cover"
                                                            />
                                                        ) : (
                                                            <div className="w-full h-full flex items-center justify-center bg-primary-soft/10 text-primary">
                                                                <svg className="h-5 w-5 opacity-40" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="1.5" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364-6.364l-.707.707M6.343 17.657l-.707.707m0-12.728l.707.707m11.314 11.314l.707-.707M12 8a4 4 0 100 8 4 4 0 000-8z" />
                                                                </svg>
                                                            </div>
                                                        )}
                                                    </div>
                                                </td>

                                                {/* Product Name */}
                                                <td className="px-6 py-4 whitespace-nowrap font-bold text-brandText">
                                                    {product.name}
                                                </td>

                                                {/* Category */}
                                                <td className="px-6 py-4 whitespace-nowrap text-xs font-semibold text-primary">
                                                    {product.category?.name || 'Uncategorized'}
                                                </td>

                                                {/* Price */}
                                                <td className="px-6 py-4 whitespace-nowrap font-semibold text-brandText">
                                                    <CurrencyText value={product.price} />
                                                </td>

                                                {/* Stock Indicator */}
                                                <td className="px-6 py-4 whitespace-nowrap text-center">
                                                    {isOutOfStock ? (
                                                        <span className="px-2 py-0.5 rounded-md text-[10px] font-bold bg-red-50 text-danger border border-red-200">
                                                            Habis
                                                        </span>
                                                    ) : isLowStock ? (
                                                        <span className="px-2 py-0.5 rounded-md text-[10px] font-bold bg-yellow-50 text-warning border border-yellow-200">
                                                            Terbatas ({product.stock})
                                                        </span>
                                                    ) : (
                                                        <span className="px-2 py-0.5 rounded-md text-[10px] font-bold bg-green-50 text-success border border-green-200">
                                                            {product.stock}
                                                        </span>
                                                    )}
                                                </td>

                                                {/* Active Status */}
                                                <td className="px-6 py-4 whitespace-nowrap">
                                                    {product.is_active ? (
                                                        <span className="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-green-50 text-success border border-green-200">
                                                            Aktif
                                                        </span>
                                                    ) : (
                                                        <span className="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-red-50 text-danger border border-red-200">
                                                            Nonaktif
                                                        </span>
                                                    )}
                                                </td>

                                                {/* Actions */}
                                                <td className="px-6 py-4 whitespace-nowrap text-right text-xs font-bold space-x-3 select-none">
                                                    <Link
                                                        href={route('admin.products.edit', product.id)}
                                                        className="text-primary hover:text-primary-dark transition-colors"
                                                    >
                                                        Ubah
                                                    </Link>
                                                    <button
                                                        type="button"
                                                        onClick={() => handleDelete(product)}
                                                        className="text-danger hover:text-red-700 transition-colors"
                                                    >
                                                        Hapus
                                                    </button>
                                                </td>
                                            </tr>
                                        );
                                    })
                                ) : (
                                    <tr>
                                        <td colSpan={7} className="px-6 py-12 text-center text-brandText-muted/70 font-semibold">
                                            Belum ada produk terdaftar. Silakan tambahkan produk baru.
                                        </td>
                                    </tr>
                                )}
                            </tbody>
                        </table>
                    </div>

                    {/* Pagination Footer */}
                    {products.total > 0 && (
                        <div className="px-6 py-4 bg-brandSurface-low/20 border-t border-brandSurface-high/40 flex flex-col sm:flex-row justify-between items-center gap-4">
                            <p className="text-xs text-brandText-muted font-semibold">
                                Menampilkan {products.from}-{products.to} dari {products.total} Produk
                            </p>
                            <Pagination links={products.links} />
                        </div>
                    )}
                </div>
            </div>
        </DashboardLayout>
    );
}

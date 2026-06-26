import React from 'react';
import { Head, Link, router } from '@inertiajs/react';
import { Category, PageProps } from '@/types';
import DashboardLayout from '@/Layouts/DashboardLayout';
import Pagination from '@/Components/common/Pagination';
import Button from '@/Components/common/Button';
import Alert from '@/Components/common/Alert';

interface CategoryListProps extends PageProps {
    categories: {
        data: Category[];
        links: any[];
        total: number;
        from: number;
        to: number;
    };
}

export default function CategoryList({ categories, flash }: CategoryListProps) {
    const handleDelete = (category: Category) => {
        if (confirm(`Apakah Anda yakin ingin menghapus kategori "${category.name}"?`)) {
            router.delete(route('admin.categories.destroy', category.id));
        }
    };

    return (
        <DashboardLayout title="Daftar Kategori Bunga">
            <Head title="Kelola Kategori - Admin" />

            <div className="space-y-6 font-sans">
                {/* Upper Action Bar */}
                <div className="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 bg-white p-5 rounded-2xl border border-brandOutline-soft/25 shadow-sm">
                    <div>
                        <h2 className="text-base font-bold text-brandText">Kelola Kategori Master</h2>
                        <p className="text-xs text-brandText-muted mt-1">
                            Tambahkan, ubah, atau hapus kategori penataan bunga untuk sistem katalog Anda.
                        </p>
                    </div>
                    <Link href={route('admin.categories.create')}>
                        <Button variant="primary" size="sm" className="flex items-center gap-1.5">
                            <svg className="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2.5" d="M12 4v16m8-8H4" />
                            </svg>
                            Tambah Kategori Baru
                        </Button>
                    </Link>
                </div>

                {/* Inline Flash Banners for Success/Error feedback */}
                {flash.success && <Alert variant="success" message={flash.success} />}
                {flash.error && <Alert variant="danger" message={flash.error} />}

                {/* Categories Table Card */}
                <div className="bg-white border border-brandOutline-soft/25 rounded-2xl shadow-sm overflow-hidden">
                    <div className="overflow-x-auto">
                        <table className="min-w-full divide-y divide-brandSurface-high/60 text-left">
                            <thead className="bg-brandSurface-low/40">
                                <tr>
                                    <th scope="col" className="px-6 py-4 text-xs font-bold text-brandText-muted uppercase tracking-wider">
                                        Nama Kategori
                                    </th>
                                    <th scope="col" className="px-6 py-4 text-xs font-bold text-brandText-muted uppercase tracking-wider">
                                        Slug URL
                                    </th>
                                    <th scope="col" className="px-6 py-4 text-xs font-bold text-brandText-muted uppercase tracking-wider">
                                        Deskripsi
                                    </th>
                                    <th scope="col" className="px-6 py-4 text-xs font-bold text-brandText-muted uppercase tracking-wider text-center">
                                        Jumlah Produk
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
                                {categories.data.length > 0 ? (
                                    categories.data.map((cat) => (
                                        <tr key={cat.id} className="hover:bg-brandSurface-low/20 transition-colors">
                                            {/* Category Name */}
                                            <td className="px-6 py-4 whitespace-nowrap font-bold text-brandText">
                                                {cat.name}
                                            </td>
                                            {/* Slug */}
                                            <td className="px-6 py-4 whitespace-nowrap text-xs text-brandText-muted font-mono">
                                                {cat.slug}
                                            </td>
                                            {/* Description */}
                                            <td className="px-6 py-4 text-xs text-brandText-muted max-w-xs truncate">
                                                {cat.description || '-'}
                                            </td>
                                            {/* Products Count */}
                                            <td className="px-6 py-4 whitespace-nowrap text-xs font-bold text-primary text-center">
                                                {cat.products_count ?? 0}
                                            </td>
                                            {/* Active Status Badge */}
                                            <td className="px-6 py-4 whitespace-nowrap">
                                                {cat.is_active ? (
                                                    <span className="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-green-50 text-success border border-green-200">
                                                        Aktif
                                                    </span>
                                                ) : (
                                                    <span className="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-red-50 text-danger border border-red-200">
                                                        Nonaktif
                                                    </span>
                                                )}
                                            </td>
                                            {/* Action Buttons */}
                                            <td className="px-6 py-4 whitespace-nowrap text-right text-xs font-bold space-x-3 select-none">
                                                <Link
                                                    href={route('admin.categories.edit', cat.id)}
                                                    className="text-primary hover:text-primary-dark transition-colors"
                                                >
                                                    Ubah
                                                </Link>
                                                <button
                                                    type="button"
                                                    onClick={() => handleDelete(cat)}
                                                    className="text-danger hover:text-red-700 transition-colors"
                                                >
                                                    Hapus
                                                </button>
                                            </td>
                                        </tr>
                                    ))
                                ) : (
                                    <tr>
                                        <td colSpan={6} className="px-6 py-12 text-center text-brandText-muted/70 font-semibold">
                                            Belum ada kategori terdaftar. Silakan tambahkan kategori baru.
                                        </td>
                                    </tr>
                                )}
                            </tbody>
                        </table>
                    </div>

                    {/* Pagination Footer block */}
                    {categories.total > 0 && (
                        <div className="px-6 py-4 bg-brandSurface-low/20 border-t border-brandSurface-high/40 flex flex-col sm:flex-row justify-between items-center gap-4">
                            <p className="text-xs text-brandText-muted font-semibold">
                                Menampilkan {categories.from}-{categories.to} dari {categories.total} Kategori
                            </p>
                            <Pagination links={categories.links} />
                        </div>
                    )}
                </div>
            </div>
        </DashboardLayout>
    );
}

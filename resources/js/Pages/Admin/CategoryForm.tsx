import React, { useEffect } from 'react';
import { Head, Link, useForm } from '@inertiajs/react';
import { Category, PageProps } from '@/types';
import DashboardLayout from '@/Layouts/DashboardLayout';
import Input from '@/Components/common/Input';
import Button from '@/Components/common/Button';

interface CategoryFormProps extends PageProps {
    category: Category | null;
}

export default function CategoryForm({ category }: CategoryFormProps) {
    const isEditMode = !!category;

    const { data, setData, post, put, processing, errors } = useForm({
        name: category?.name || '',
        slug: category?.slug || '',
        description: category?.description || '',
        is_active: category ? category.is_active : true,
    });

    // Smart Auto-Slugify Helper
    const handleNameChange = (e: React.ChangeEvent<HTMLInputElement>) => {
        const name = e.target.value;
        setData((prev) => {
            const updated = { ...prev, name };
            // Auto generate slug only if the slug was empty or matched the previous slug of the old name
            const previousAutoSlug = prev.name.toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/(^-|-$)/g, '');
            if (!prev.slug || prev.slug === previousAutoSlug) {
                updated.slug = name.toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/(^-|-$)/g, '');
            }
            return updated;
        });
    };

    const handleSubmit = (e: React.FormEvent) => {
        e.preventDefault();
        
        if (isEditMode && category) {
            put(route('admin.categories.update', category.id));
        } else {
            post(route('admin.categories.store'));
        }
    };

    return (
        <DashboardLayout title={isEditMode ? 'Ubah Kategori Bunga' : 'Tambah Kategori Baru'}>
            <Head title={isEditMode ? 'Ubah Kategori - Admin' : 'Tambah Kategori - Admin'} />

            <div className="max-w-2xl font-sans">
                {/* Back link */}
                <div className="mb-6">
                    <Link
                        href={route('admin.categories.index')}
                        className="inline-flex items-center gap-1.5 text-xs font-bold text-primary hover:underline"
                    >
                        <svg className="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2.5" d="M15 19l-7-7 7-7" />
                        </svg>
                        Kembali ke Daftar Kategori
                    </Link>
                </div>

                {/* Form Card */}
                <div className="bg-white border border-brandOutline-soft/25 rounded-2xl p-8 shadow-sm">
                    <form onSubmit={handleSubmit} className="space-y-6">
                        {/* Name Input */}
                        <Input
                            label="Nama Kategori"
                            id="name"
                            type="text"
                            placeholder="Contoh: Bloom Box, Hand Bouquet, dll."
                            value={data.name}
                            error={errors.name}
                            required
                            onChange={handleNameChange}
                        />

                        {/* Slug Input */}
                        <Input
                            label="Slug URL (Otomatis)"
                            id="slug"
                            type="text"
                            placeholder="Contoh: bloom-box"
                            value={data.slug}
                            error={errors.slug}
                            required
                            helperText="Digunakan sebagai penunjuk URL unik di website (contoh: /katalog?category=slug)."
                            onChange={(e) => setData('slug', e.target.value.toLowerCase().replace(/[^a-z0-9]+/g, '-'))}
                        />

                        {/* Description Input */}
                        <div className="flex flex-col gap-1 w-full">
                            <label htmlFor="description" className="text-xs font-semibold text-brandText-muted tracking-wide">
                                Deskripsi Kategori
                            </label>
                            <textarea
                                id="description"
                                rows={4}
                                placeholder="Tulis deskripsi singkat kategori ini..."
                                value={data.description}
                                className={`w-full px-3.5 py-2 text-sm bg-brandSurface border rounded-lg transition-all duration-200 focus:outline-none focus:bg-white focus:ring-2 focus:ring-primary-muted/40 focus:border-primary ${
                                    errors.description ? 'border-danger focus:border-danger' : 'border-brandOutline-soft focus:border-primary'
                                }`}
                                onChange={(e) => setData('description', e.target.value)}
                            />
                            {errors.description && (
                                <span className="text-xs font-medium text-danger">{errors.description}</span>
                            )}
                        </div>

                        {/* Active status Toggle */}
                        <div className="flex items-center pt-2">
                            <label className="inline-flex items-center cursor-pointer">
                                <input
                                    type="checkbox"
                                    checked={data.is_active}
                                    className="rounded border-brandOutline-soft text-primary shadow-sm focus:ring-primary-soft/40 focus:ring-2 focus:border-primary h-4 w-4"
                                    onChange={(e) => setData('is_active', e.target.checked)}
                                />
                                <div className="ms-2.5">
                                    <span className="block text-xs font-bold text-brandText">Aktifkan Kategori</span>
                                    <span className="block text-[10px] text-brandText-muted mt-0.5">
                                        Kategori yang aktif akan ditampilkan di halaman filter katalog publik.
                                    </span>
                                </div>
                            </label>
                        </div>

                        {/* Action Buttons */}
                        <div className="pt-4 border-t border-brandSurface-low flex items-center justify-end gap-3 select-none">
                            <Link href={route('admin.categories.index')}>
                                <Button variant="ghost" size="md">
                                    Batal
                                </Button>
                            </Link>
                            <Button
                                type="submit"
                                variant="primary"
                                size="md"
                                isLoading={processing}
                            >
                                {isEditMode ? 'Simpan Perubahan' : 'Tambahkan Kategori'}
                            </Button>
                        </div>
                    </form>
                </div>
            </div>
        </DashboardLayout>
    );
}

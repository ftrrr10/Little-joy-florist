import React, { useEffect, useState } from 'react';
import { Head, Link, useForm, router } from '@inertiajs/react';
import { Category, Product, PageProps } from '@/types';
import DashboardLayout from '@/Layouts/DashboardLayout';
import Input from '@/Components/common/Input';
import Button from '@/Components/common/Button';

interface ProductFormProps extends PageProps {
    product: Product | null;
    categories: Category[];
}

export default function ProductForm({ product, categories }: ProductFormProps) {
    const isEditMode = !!product;
    const [imagePreview, setImagePreview] = useState<string | null>(null);

    const { data, setData, post, processing, errors } = useForm({
        category_id: product?.category_id || '',
        name: product?.name || '',
        slug: product?.slug || '',
        description: product?.description || '',
        price: product?.price || '',
        stock: product?.stock || '0',
        image: null as File | null,
        is_active: product ? product.is_active : true,
    });

    // Handle Image file selection & generate local preview
    const handleImageChange = (e: React.ChangeEvent<HTMLInputElement>) => {
        const file = e.target.files?.[0] || null;
        setData('image', file);

        if (file) {
            setImagePreview(URL.createObjectURL(file));
        } else {
            setImagePreview(null);
        }
    };

    // Auto-Slugify Helper
    const handleNameChange = (e: React.ChangeEvent<HTMLInputElement>) => {
        const name = e.target.value;
        setData((prev) => {
            const updated = { ...prev, name };
            const previousAutoSlug = prev.name.toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/(^-|-$)/g, '');
            if (!prev.slug || prev.slug === previousAutoSlug) {
                updated.slug = name.toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/(^-|-$)/g, '');
            }
            return updated;
        });
    };

    const handleSubmit = (e: React.FormEvent) => {
        e.preventDefault();

        // Prepare the payload (forcing multipart form data)
        if (isEditMode && product) {
            // CRITICAL SPOOFING RULE: Use POST with _method = 'PUT' for file uploads on update!
            router.post(route('admin.products.update', product.id), {
                _method: 'PUT',
                ...data,
            });
        } else {
            post(route('admin.products.store'));
        }
    };

    return (
        <DashboardLayout title={isEditMode ? 'Ubah Produk Rangkaian Bunga' : 'Tambah Produk Baru'}>
            <Head title={isEditMode ? 'Ubah Produk - Admin' : 'Tambah Produk - Admin'} />

            <div className="max-w-3xl font-sans">
                {/* Back link */}
                <div className="mb-6">
                    <Link
                        href={route('admin.products.index')}
                        className="inline-flex items-center gap-1.5 text-xs font-bold text-primary hover:underline"
                    >
                        <svg className="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2.5" d="M15 19l-7-7 7-7" />
                        </svg>
                        Kembali ke Daftar Produk
                    </Link>
                </div>

                {/* Form Wrapper */}
                <div className="bg-white border border-brandOutline-soft/25 rounded-2xl p-8 shadow-sm">
                    <form onSubmit={handleSubmit} className="space-y-6">
                        <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                            {/* Left Form Column */}
                            <div className="space-y-5">
                                {/* Category Dropdown */}
                                <div className="flex flex-col gap-1 w-full">
                                    <label htmlFor="category_id" className="text-xs font-semibold text-brandText-muted tracking-wide">
                                        Pilih Kategori Bunga
                                    </label>
                                    <select
                                        id="category_id"
                                        value={data.category_id}
                                        required
                                        className={`w-full px-3.5 py-2 text-sm bg-brandSurface border rounded-lg transition-all duration-200 focus:outline-none focus:bg-white focus:ring-2 focus:ring-primary-muted/40 focus:border-primary cursor-pointer ${
                                            errors.category_id ? 'border-danger focus:border-danger' : 'border-brandOutline-soft focus:border-primary'
                                        }`}
                                        onChange={(e) => setData('category_id', e.target.value)}
                                    >
                                        <option value="">-- Pilih Kategori --</option>
                                        {categories.map((cat) => (
                                            <option key={cat.id} value={cat.id}>
                                                {cat.name}
                                            </option>
                                        ))}
                                    </select>
                                    {errors.category_id && (
                                        <span className="text-xs font-medium text-danger">{errors.category_id}</span>
                                    )}
                                </div>

                                {/* Name Input */}
                                <Input
                                    label="Nama Produk Rangkaian Bunga"
                                    id="name"
                                    type="text"
                                    placeholder="Contoh: Classic Red Roses Bouquet"
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
                                    placeholder="Contoh: classic-red-roses-bouquet"
                                    value={data.slug}
                                    error={errors.slug}
                                    required
                                    helperText="Digunakan untuk penunjuk URL unik produk di website."
                                    onChange={(e) => setData('slug', e.target.value.toLowerCase().replace(/[^a-z0-9]+/g, '-'))}
                                />

                                <div className="grid grid-cols-2 gap-4">
                                    {/* Price Input */}
                                    <Input
                                        label="Harga Jual (Rupiah)"
                                        id="price"
                                        type="number"
                                        placeholder="Contoh: 350000"
                                        value={data.price}
                                        error={errors.price}
                                        required
                                        min="0"
                                        onChange={(e) => setData('price', e.target.value)}
                                    />

                                    {/* Stock Input */}
                                    <Input
                                        label="Stok Awal"
                                        id="stock"
                                        type="number"
                                        placeholder="Contoh: 15"
                                        value={data.stock}
                                        error={errors.stock}
                                        required
                                        min="0"
                                        onChange={(e) => setData('stock', e.target.value)}
                                    />
                                </div>
                            </div>

                            {/* Right Form Column: Image Upload & Preview */}
                            <div className="flex flex-col gap-4">
                                <label className="text-xs font-semibold text-brandText-muted tracking-wide">
                                    Foto Rangkaian Bunga
                                </label>

                                {/* Interactive Preview Box */}
                                <div className="border-2 border-dashed border-brandOutline-soft/75 rounded-2xl aspect-[4/3] bg-brandSurface-low/30 overflow-hidden flex flex-col items-center justify-center relative group select-none">
                                    {imagePreview ? (
                                        /* Local New Preview */
                                        <img
                                            src={imagePreview}
                                            alt="Preview unggahan baru"
                                            className="w-full h-full object-cover"
                                        />
                                    ) : product?.image_path ? (
                                        /* Database Existing Preview */
                                        <img
                                            src={`/storage/${product.image_path}`}
                                            alt={product.name}
                                            className="w-full h-full object-cover"
                                        />
                                    ) : (
                                        /* Empty State placeholder */
                                        <div className="text-center p-6 space-y-2">
                                            <svg className="h-10 w-10 text-primary/45 mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="1.25" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                            <p className="text-xs font-bold text-primary">Belum ada foto terpilih</p>
                                            <p className="text-[10px] text-brandText-muted leading-relaxed">Mendukung format JPG, PNG, WebP (Maks. 2 MB)</p>
                                        </div>
                                    )}

                                    {/* Upload Trigger overlay */}
                                    <div className="absolute inset-0 bg-black/45 opacity-0 group-hover:opacity-100 flex items-center justify-center transition-all duration-200 cursor-pointer">
                                        <span className="px-4 py-2 bg-white text-primary font-bold text-xs rounded-lg shadow-md">
                                            {imagePreview || product?.image_path ? 'Ubah Foto Bunga' : 'Unggah Foto Bunga'}
                                        </span>
                                    </div>
                                    <input
                                        type="file"
                                        id="image"
                                        accept="image/*"
                                        className="absolute inset-0 opacity-0 cursor-pointer"
                                        onChange={handleImageChange}
                                    />
                                </div>
                                {errors.image && (
                                    <span className="text-xs font-medium text-danger text-center">{errors.image}</span>
                                )}
                            </div>
                        </div>

                        {/* Description Input */}
                        <div className="flex flex-col gap-1 w-full">
                            <label htmlFor="description" className="text-xs font-semibold text-brandText-muted tracking-wide">
                                Deskripsi Rangkaian Bunga
                            </label>
                            <textarea
                                id="description"
                                rows={4}
                                placeholder="Jelaskan detail bunga segar yang digunakan, ukuran rangkaian, kecocokan acara, dan instruksi perawatan khusus..."
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

                        {/* Active Status */}
                        <div className="flex items-center pt-2">
                            <label className="inline-flex items-center cursor-pointer">
                                <input
                                    type="checkbox"
                                    checked={data.is_active}
                                    className="rounded border-brandOutline-soft text-primary shadow-sm focus:ring-primary-soft/40 focus:ring-2 focus:border-primary h-4 w-4"
                                    onChange={(e) => setData('is_active', e.target.checked)}
                                />
                                <div className="ms-2.5">
                                    <span className="block text-xs font-bold text-brandText">Tampilkan di Katalog Publik</span>
                                    <span className="block text-[10px] text-brandText-muted mt-0.5">
                                        Produk yang aktif akan ditampilkan di halaman katalog publik dan dapat dibeli oleh pelanggan.
                                    </span>
                                </div>
                            </label>
                        </div>

                        {/* Action Buttons */}
                        <div className="pt-4 border-t border-brandSurface-low flex items-center justify-end gap-3 select-none">
                            <Link href={route('admin.products.index')}>
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
                                {isEditMode ? 'Simpan Perubahan' : 'Tambahkan Produk'}
                            </Button>
                        </div>
                    </form>
                </div>
            </div>
        </DashboardLayout>
    );
}

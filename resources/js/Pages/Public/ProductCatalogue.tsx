import React, { useState } from 'react';
import { Head, Link, router } from '@inertiajs/react';
import { Category, Product, PageProps } from '@/types';
import PublicLayout from '@/Layouts/PublicLayout';
import ProductGrid from '@/Components/products/ProductGrid';
import Pagination from '@/Components/common/Pagination';
import Button from '@/Components/common/Button';
import Input from '@/Components/common/Input';

interface CatalogueProps extends PageProps {
    products: {
        data: Product[];
        links: any[];
        total: number;
        from: number;
        to: number;
    };
    categories: Category[];
    filters: {
        search?: string;
        category?: string;
        availability?: string;
        sort?: string;
    };
}

export default function ProductCatalogue({ products, categories, filters }: CatalogueProps) {
    const [searchVal, setSearchVal] = useState(filters.search || '');
    const [isMobileFilterOpen, setIsMobileFilterOpen] = useState(false);

    // Apply filter helper
    const updateFilter = (newFilters: Record<string, string | null | undefined>) => {
        const queryParams = {
            ...filters,
            ...newFilters,
        };

        // Clean up empty parameters
        Object.keys(queryParams).forEach((key) => {
            if (queryParams[key as keyof typeof queryParams] === '' || queryParams[key as keyof typeof queryParams] === null || queryParams[key as keyof typeof queryParams] === undefined) {
                delete queryParams[key as keyof typeof queryParams];
            }
        });

        router.get(route('catalogue.index'), queryParams, {
            preserveState: true,
            preserveScroll: true,
        });
    };

    const handleSearchSubmit = (e: React.FormEvent) => {
        e.preventDefault();
        updateFilter({ search: searchVal });
    };

    const handleCategorySelect = (categoryId: number | null) => {
        updateFilter({ category: categoryId ? String(categoryId) : null });
    };

    const handleAvailabilityToggle = (e: React.ChangeEvent<HTMLInputElement>) => {
        updateFilter({ availability: e.target.checked ? 'instock' : null });
    };

    const handleSortChange = (e: React.ChangeEvent<HTMLSelectElement>) => {
        updateFilter({ sort: e.target.value });
    };

    const handleClearFilters = () => {
        setSearchVal('');
        router.get(route('catalogue.index'), {}, {
            preserveState: false,
            preserveScroll: true,
        });
    };

    const activeFiltersCount = Object.keys(filters).filter(key => key !== 'sort').length;

    return (
        <PublicLayout>
            <Head title="Katalog Rangkaian Bunga Segar - Little Joy Jakarta" />

            <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 font-sans">
                {/* Hero Catalog Header */}
                <div className="text-center max-w-3xl mx-auto mb-16">
                    <span className="text-[10px] font-bold tracking-[0.3em] text-primary uppercase mb-3 block">
                        Katalog Florist
                    </span>
                    <h2 className="font-serif text-3xl sm:text-4xl lg:text-5xl font-bold text-primary leading-tight">
                        Temukan Rangkaian Bunga Terbaik
                    </h2>
                    <p className="text-sm sm:text-base text-brandText-muted mt-4 leading-relaxed">
                        Mulai dari buket mawar klasik hingga standing flower megah, setiap ciptaan kami dirangkai menggunakan bunga potong segar berkualitas terbaik untuk menghiasi momen berharga Anda.
                    </p>
                </div>

                <div className="flex flex-col lg:flex-row gap-8 items-start">
                    {/* 1. Desktop Filter Sidebar (Hidden on mobile) */}
                    <aside className="hidden lg:block w-64 flex-shrink-0 bg-white border border-brandOutline-soft/25 p-6 rounded-2xl shadow-sm sticky top-24">
                        <div className="space-y-6">
                            {/* Search Filter */}
                            <div>
                                <h4 className="text-xs font-bold text-primary uppercase tracking-wider mb-3">
                                    Cari Bunga
                                </h4>
                                <form onSubmit={handleSearchSubmit} className="flex gap-2">
                                    <Input
                                        type="text"
                                        placeholder="Cari nama bunga..."
                                        value={searchVal}
                                        onChange={(e) => setSearchVal(e.target.value)}
                                        className="py-1.5 px-3 text-xs"
                                    />
                                    <Button type="submit" variant="primary" size="sm" className="px-3">
                                        Cari
                                    </Button>
                                </form>
                            </div>

                            <hr className="border-brandSurface-low" />

                            {/* Category Filter */}
                            <div>
                                <h4 className="text-xs font-bold text-primary uppercase tracking-wider mb-3">
                                    Kategori
                                </h4>
                                <div className="flex flex-col gap-1">
                                    <button
                                        type="button"
                                        onClick={() => handleCategorySelect(null)}
                                        className={`text-left text-xs py-2 px-3 rounded-lg font-medium transition-all ${
                                            !filters.category
                                                ? 'bg-primary-soft/35 text-primary font-bold'
                                                : 'text-brandText-muted hover:bg-brandSurface-low hover:text-primary'
                                        }`}
                                    >
                                        Semua Rangkaian
                                    </button>
                                    {categories.map((cat) => (
                                        <button
                                            key={cat.id}
                                            type="button"
                                            onClick={() => handleCategorySelect(cat.id)}
                                            className={`text-left text-xs py-2 px-3 rounded-lg font-medium transition-all ${
                                                filters.category === String(cat.id)
                                                    ? 'bg-primary-soft/35 text-primary font-bold'
                                                    : 'text-brandText-muted hover:bg-brandSurface-low hover:text-primary'
                                            }`}
                                        >
                                            {cat.name}
                                        </button>
                                    ))}
                                </div>
                            </div>

                            <hr className="border-brandSurface-low" />

                            {/* Availability Filter */}
                            <div>
                                <h4 className="text-xs font-bold text-primary uppercase tracking-wider mb-3">
                                    Ketersediaan
                                </h4>
                                <label className="inline-flex items-center cursor-pointer">
                                    <input
                                        type="checkbox"
                                        checked={filters.availability === 'instock'}
                                        onChange={handleAvailabilityToggle}
                                        className="rounded border-brandOutline-soft text-primary shadow-sm focus:ring-primary-soft/40 focus:ring-2 focus:border-primary h-4 w-4"
                                    />
                                    <span className="ms-2.5 text-xs font-semibold text-brandText-muted hover:text-brandText transition-colors select-none">
                                        Hanya Stok Tersedia
                                    </span>
                                </label>
                            </div>

                            {/* Clear Filters Button */}
                            {activeFiltersCount > 0 && (
                                <div className="pt-2">
                                    <Button
                                        variant="outline"
                                        size="sm"
                                        className="w-full text-center text-xs justify-center border-red-200 text-danger hover:bg-red-50 hover:border-red-300"
                                        onClick={handleClearFilters}
                                    >
                                        Hapus Semua Filter ({activeFiltersCount})
                                    </Button>
                                </div>
                            )}
                        </div>
                    </aside>

                    {/* 2. Mobile Filter Header Bar (Visible on mobile) */}
                    <div className="w-full lg:hidden flex flex-wrap gap-3 items-center justify-between bg-white border border-brandOutline-soft/25 p-4 rounded-xl mb-2">
                        <Button
                            variant="outline"
                            size="sm"
                            className="flex items-center gap-2"
                            onClick={() => setIsMobileFilterOpen(!isMobileFilterOpen)}
                        >
                            <svg className="h-4 w-4 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                            </svg>
                            Filter {activeFiltersCount > 0 && `(${activeFiltersCount})`}
                        </Button>
                        
                        {/* Summary of products count */}
                        <p className="text-xs text-brandText-muted font-medium">
                            Menampilkan {products.total > 0 ? `${products.from}-${products.to} dari ${products.total}` : '0'} Rangkaian
                        </p>
                    </div>

                    {/* Mobile Filter Drawer (Visible when toggled on mobile) */}
                    {isMobileFilterOpen && (
                        <div className="lg:hidden w-full bg-white border border-brandOutline-soft/25 p-5 rounded-xl mb-4 space-y-4 animate-in slide-in-from-top-3 duration-200">
                            {/* Mobile Search */}
                            <form onSubmit={handleSearchSubmit} className="flex gap-2">
                                <Input
                                    type="text"
                                    placeholder="Cari nama bunga..."
                                    value={searchVal}
                                    onChange={(e) => setSearchVal(e.target.value)}
                                    className="py-1.5 px-3 text-xs"
                                />
                                <Button type="submit" variant="primary" size="sm" onClick={() => setIsMobileFilterOpen(false)}>
                                    Cari
                                </Button>
                            </form>

                            {/* Mobile Category Select */}
                            <div>
                                <h4 className="text-xs font-bold text-primary uppercase tracking-wider mb-2">Kategori</h4>
                                <div className="flex flex-wrap gap-1.5">
                                    <button
                                        onClick={() => { handleCategorySelect(null); setIsMobileFilterOpen(false); }}
                                        className={`text-xs py-1.5 px-3 rounded-full transition-all ${
                                            !filters.category ? 'bg-primary text-white font-bold' : 'bg-brandSurface-low text-brandText-muted hover:bg-brandSurface-high'
                                        }`}
                                    >
                                        Semua
                                    </button>
                                    {categories.map(cat => (
                                        <button
                                            key={cat.id}
                                            onClick={() => { handleCategorySelect(cat.id); setIsMobileFilterOpen(false); }}
                                            className={`text-xs py-1.5 px-3 rounded-full transition-all ${
                                                filters.category === String(cat.id) ? 'bg-primary text-white font-bold' : 'bg-brandSurface-low text-brandText-muted hover:bg-brandSurface-high'
                                            }`}
                                        >
                                            {cat.name}
                                        </button>
                                    ))}
                                </div>
                            </div>

                            {/* Mobile Availability Check */}
                            <div className="flex items-center justify-between pt-1">
                                <label className="inline-flex items-center cursor-pointer">
                                    <input
                                        type="checkbox"
                                        checked={filters.availability === 'instock'}
                                        onChange={(e) => { handleAvailabilityToggle(e); setIsMobileFilterOpen(false); }}
                                        className="rounded border-brandOutline-soft text-primary h-4 w-4"
                                    />
                                    <span className="ms-2 text-xs font-semibold text-brandText-muted">Hanya Stok Tersedia</span>
                                </label>

                                {activeFiltersCount > 0 && (
                                    <button
                                        onClick={() => { handleClearFilters(); setIsMobileFilterOpen(false); }}
                                        className="text-xs font-bold text-danger hover:underline"
                                    >
                                        Hapus Semua Filter
                                    </button>
                                )}
                            </div>
                        </div>
                    )}

                    {/* 3. Catalog Products Area */}
                    <div className="flex-grow w-full space-y-8">
                        {/* Toolbar: Sorting & Count */}
                        <div className="hidden lg:flex items-center justify-between border-b border-brandSurface-high/60 pb-4">
                            <p className="text-xs text-brandText-muted font-bold tracking-wide">
                                Menampilkan {products.total > 0 ? `${products.from}-${products.to} dari ${products.total}` : '0'} Rangkaian Bunga Segar
                            </p>

                            <div className="flex items-center gap-2 select-none">
                                <label htmlFor="sort" className="text-xs font-bold text-brandText-muted">
                                    Urutkan:
                                </label>
                                <select
                                    id="sort"
                                    value={filters.sort || 'latest'}
                                    onChange={handleSortChange}
                                    className="text-xs font-semibold text-brandText border-brandOutline-soft/70 rounded-lg bg-white py-1 pl-2.5 pr-8 focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary cursor-pointer transition-all"
                                >
                                    <option value="latest">Terbaru</option>
                                    <option value="price_asc">Harga: Rendah ke Tinggi</option>
                                    <option value="price_desc">Harga: Tinggi ke Rendah</option>
                                </select>
                            </div>
                        </div>

                        {/* Mobile Sorting bar (only visible on mobile) */}
                        <div className="lg:hidden flex items-center justify-between border-b border-brandSurface-high/60 pb-3 px-1">
                            <span className="text-[10px] font-bold text-brandText-muted uppercase tracking-wider">
                                Urutan Katalog
                            </span>
                            <select
                                value={filters.sort || 'latest'}
                                onChange={handleSortChange}
                                className="text-xs font-bold text-primary border-none bg-transparent py-0.5 pl-1 pr-6 focus:outline-none focus:ring-0 cursor-pointer"
                            >
                                <option value="latest">Terbaru</option>
                                <option value="price_asc">Harga Terendah</option>
                                <option value="price_desc">Harga Tertinggi</option>
                            </select>
                        </div>

                        {/* Product Cards Grid */}
                        <ProductGrid products={products.data} />

                        {/* Pagination Links */}
                        <div className="pt-8 border-t border-brandSurface-high/55">
                            <Pagination links={products.links} />
                        </div>
                    </div>
                </div>
            </div>
        </PublicLayout>
    );
}

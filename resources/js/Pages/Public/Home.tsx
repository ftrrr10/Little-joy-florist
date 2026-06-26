import React from 'react';
import PublicLayout from '@/Layouts/PublicLayout';
import { Head, Link, usePage } from '@inertiajs/react';
import Button from '@/Components/common/Button';
import ProductCard from '@/Components/products/ProductCard';
import ScrollReveal from '@/Components/common/ScrollReveal';
import { 
    Sparkles, 
    Truck, 
    Heart, 
    ArrowRight, 
    ChevronRight,
    Compass
} from 'lucide-react';
import { PageProps, Product, Category } from '@/types';

interface HomeProps extends PageProps {
    featuredProducts: Product[];
    categories: Category[];
}

export default function Home() {
    const { featuredProducts, categories } = usePage<HomeProps>().props;

    // Mapping category to their newly generated premium images for the visual gallery
    const categoryImageMap: Record<string, string> = {
        'Hand Bouquet': '/storage/products/hand-bouquet.png',
        'Bloom Box': '/storage/products/bloom-box.png',
        'Flower Stand': '/storage/products/flower-stand.png',
        'Vase Arrangement': '/storage/products/vase-arrangement.png',
        'Orchid Plant': '/storage/products/orchid-plant.png',
    };

    return (
        <PublicLayout>
            <Head title="Premium Florist Jakarta - Rangkaian Bunga Eksklusif" />

            <div className="space-y-24 pb-20 font-sans bg-background">
                {/* 1. HERO SECTION */}
                <section className="relative overflow-hidden pt-12 md:pt-20">
                    <div className="max-w-7xl mx-auto px-6 lg:px-8 grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
                        {/* Hero Text (Col-span 7) */}
                        <ScrollReveal duration={800} distance="translate-y-8" className="lg:col-span-7 space-y-8 z-10">
                            <span className="inline-flex items-center gap-1.5 px-3 py-1 text-[10px] font-bold tracking-[0.2em] text-primary uppercase bg-primary-soft/15 rounded-full">
                                <Sparkles className="w-3.5 h-3.5" /> Premium Florist Jakarta
                            </span>
                            <h1 className="font-serif text-4xl sm:text-5xl lg:text-6xl font-bold text-primary leading-[1.12] tracking-tight">
                                Rangkaian Bunga Segar <br />
                                <span className="text-secondary italic font-normal">Dirangkai dengan Jiwa</span> <br />
                                Untuk Momen Spesial Anda.
                            </h1>
                            <p className="text-brandText-muted text-sm sm:text-base max-w-xl leading-relaxed">
                                Hadirkan kebahagiaan sejati melalui keindahan botani terbaik. Setiap tangkai bunga di Little Joy Jakarta dipilih secara manual dan dirangkai oleh florist bersertifikat untuk melahirkan kemewahan visual yang tiada duanya.
                            </p>
                            <div className="flex flex-wrap gap-4 pt-2">
                                <Link href={route('catalogue.index')}>
                                    <Button variant="primary" size="lg" className="shadow-md hover:shadow-lg transition-all rounded-xl">
                                        Jelajahi Katalog <ArrowRight className="w-4 h-4 ml-1.5" />
                                    </Button>
                                </Link>
                                <Link href={route('about')}>
                                    <Button variant="outline" size="lg" className="rounded-xl">
                                        Tentang Kami
                                    </Button>
                                </Link>
                            </div>
                        </ScrollReveal>

                        {/* Hero Image (Col-span 5) */}
                        <ScrollReveal duration={1000} delay={200} distance="translate-y-8" className="lg:col-span-5 relative flex justify-center lg:justify-end">
                            <div className="absolute -inset-4 bg-primary-soft/10 rounded-3xl blur-3xl -z-10 pointer-events-none"></div>
                            <div className="relative w-full max-w-[400px] aspect-[4/5] bg-white p-3 rounded-3xl border border-brandOutline-soft/20 shadow-xl rotate-2 hover:rotate-0 transition-all duration-500 ease-out">
                                <img 
                                    src="/storage/products/vase-arrangement.png" 
                                    alt="Table Vase Arrangement Little Joy" 
                                    className="w-full h-full object-cover rounded-2xl"
                                />
                                <div className="absolute bottom-6 -left-6 bg-white border border-brandOutline-soft/30 px-5 py-3 rounded-2xl shadow-lg flex items-center gap-3">
                                    <div className="p-2 bg-green-50 text-primary rounded-xl">
                                        <Heart className="w-5 h-5 fill-current" />
                                    </div>
                                    <div>
                                        <p className="text-[10px] font-bold text-brandText-muted uppercase tracking-wider">Desain Terlaris</p>
                                        <p className="font-serif text-xs font-bold text-primary">Vase Table Arrangement</p>
                                    </div>
                                </div>
                            </div>
                        </ScrollReveal>
                    </div>
                </section>

                {/* 2. THE BOTANICAL PROMISE (BRAND VALUES) */}
                <section className="max-w-7xl mx-auto px-6 lg:px-8">
                    <div className="bg-white p-8 md:p-12 rounded-3xl border border-brandOutline-soft/20 shadow-sm grid grid-cols-1 md:grid-cols-3 gap-8 text-center md:text-left">
                        {/* Value 1 */}
                        <ScrollReveal delay={0} duration={800} distance="translate-y-6" className="space-y-3 p-4">
                            <div className="w-10 h-10 bg-primary-soft/20 text-primary rounded-2xl flex items-center justify-center mx-auto md:mx-0">
                                <Sparkles className="w-5 h-5" />
                            </div>
                            <h4 className="font-serif text-lg font-bold text-primary">100% Garansi Kesegaran</h4>
                            <p className="text-xs text-brandText-muted leading-relaxed">
                                Kami mendatangkan bunga segar setiap pagi hari untuk memastikan buket Anda tetap harum, mekar sempurna, dan tahan lama.
                            </p>
                        </ScrollReveal>

                        {/* Value 2 */}
                        <ScrollReveal delay={150} duration={800} distance="translate-y-6" className="space-y-3 p-4 border-y md:border-y-0 md:border-x border-gray-100">
                            <div className="w-10 h-10 bg-secondary-soft/30 text-secondary rounded-2xl flex items-center justify-center mx-auto md:mx-0">
                                <Heart className="w-5 h-5" />
                            </div>
                            <h4 className="font-serif text-lg font-bold text-primary">Rangkaian Hasil Seni</h4>
                            <p className="text-xs text-brandText-muted leading-relaxed">
                                Bunga Anda dirangkai khusus secara personal oleh florist ahli kami, melahirkan satu karya seni botani unik di setiap pesanan.
                            </p>
                        </ScrollReveal>

                        {/* Value 3 */}
                        <ScrollReveal delay={300} duration={800} distance="translate-y-6" className="space-y-3 p-4">
                            <div className="w-10 h-10 bg-amber-50 text-warning rounded-2xl flex items-center justify-center mx-auto md:mx-0">
                                <Truck className="w-5 h-5" />
                            </div>
                            <h4 className="font-serif text-lg font-bold text-primary">Pengiriman Hari Yang Sama</h4>
                            <p className="text-xs text-brandText-muted leading-relaxed">
                                Butuh kejutan mendadak? Kami menyediakan opsi pengiriman cepat di hari yang sama untuk wilayah seluruh area Jakarta.
                            </p>
                        </ScrollReveal>
                    </div>
                </section>

                {/* 3. CATEGORY SHOWCASE */}
                <section className="max-w-7xl mx-auto px-6 lg:px-8 space-y-8">
                    <ScrollReveal className="text-center max-w-2xl mx-auto space-y-2">
                        <span className="text-[10px] font-bold tracking-[0.2em] text-secondary uppercase">
                            Koleksi Eksklusif
                        </span>
                        <h2 className="font-serif text-3xl sm:text-4xl font-bold text-primary">
                            Pilih Berdasarkan Kategori Bunga
                        </h2>
                        <p className="text-xs sm:text-sm text-brandText-muted">
                            Temukan bentuk rangkaian terbaik yang dirancang khusus untuk mewakili setiap pesan emosi Anda.
                        </p>
                    </ScrollReveal>

                    <div className="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-6">
                        {categories.map((category, index) => {
                            const bgImage = categoryImageMap[category.name] || '/storage/products/hand-bouquet.png';
                            return (
                                <ScrollReveal 
                                    key={category.id} 
                                    delay={index * 100} 
                                    duration={800} 
                                    distance="translate-y-8"
                                    className="flex flex-col h-full"
                                >
                                    <Link 
                                        href={route('catalogue.index', { kategori: category.slug })}
                                        className="group flex flex-col bg-white rounded-2xl overflow-hidden border border-brandOutline-soft/10 shadow-sm hover:shadow-md transition-all duration-300 h-full"
                                    >
                                        <div className="relative aspect-[4/5] overflow-hidden bg-gray-50">
                                            <img 
                                                src={bgImage} 
                                                alt={category.name} 
                                                className="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500 ease-out"
                                            />
                                            <div className="absolute inset-0 bg-gradient-to-t from-primary/30 to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
                                        </div>
                                        <div className="p-4 flex-grow flex flex-col justify-between">
                                            <div>
                                                <h3 className="font-serif text-sm font-bold text-primary group-hover:text-secondary transition-colors truncate">
                                                    {category.name}
                                                </h3>
                                                <p className="text-[10px] text-brandText-muted leading-relaxed line-clamp-2 mt-1">
                                                    {category.description}
                                                </p>
                                            </div>
                                            <span className="inline-flex items-center gap-0.5 text-[10px] font-bold text-secondary mt-3 border-b border-secondary/0 group-hover:border-secondary/100 w-fit transition-all">
                                                Lihat <ChevronRight className="w-3 h-3" />
                                            </span>
                                        </div>
                                    </Link>
                                </ScrollReveal>
                            );
                        })}
                    </div>
                </section>

                {/* 4. FEATURED PRODUCTS (THE COLLECTION) */}
                <section className="max-w-7xl mx-auto px-6 lg:px-8 space-y-10">
                    <ScrollReveal className="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4">
                        <div className="space-y-1">
                            <span className="text-[10px] font-bold tracking-[0.2em] text-primary uppercase">
                                Produk Unggulan
                            </span>
                            <h2 className="font-serif text-3xl font-bold text-primary">
                                Rangkaian Terlaris Minggu Ini
                            </h2>
                            <p className="text-xs text-brandText-muted">
                                Bunga-bunga pilihan terfavorit yang paling sering dipesan oleh pelanggan kami.
                            </p>
                        </div>
                        <Link 
                            href={route('catalogue.index')} 
                            className="inline-flex items-center gap-1 text-xs font-bold text-secondary hover:text-secondary-dark group self-start sm:self-end"
                        >
                            Lihat Semua Produk <ArrowRight className="w-3.5 h-3.5 group-hover:translate-x-0.5 transition-transform" />
                        </Link>
                    </ScrollReveal>

                    {featuredProducts.length === 0 ? (
                        <div className="text-center py-12 text-brandText-muted">
                            Belum ada produk bunga yang diunggulkan saat ini.
                        </div>
                    ) : (
                        <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                            {featuredProducts.map((product, index) => (
                                <ScrollReveal key={product.id} delay={index * 100} duration={800} distance="translate-y-8">
                                    <ProductCard product={product} />
                                </ScrollReveal>
                            ))}
                        </div>
                    )}
                </section>

                {/* 5. BRAND STORY & CTA BANNER */}
                <section className="max-w-7xl mx-auto px-6 lg:px-8">
                    <ScrollReveal duration={1000} distance="scale-95 opacity-0" className="w-full">
                        <div className="bg-primary text-white rounded-3xl p-8 md:p-16 text-center space-y-6 relative overflow-hidden shadow-lg">
                            <div className="absolute top-0 right-0 w-80 h-80 bg-white/5 rounded-full blur-3xl pointer-events-none"></div>
                            <div className="absolute bottom-0 left-0 w-80 h-80 bg-secondary/10 rounded-full blur-3xl pointer-events-none"></div>
                            
                            <div className="max-w-2xl mx-auto space-y-6">
                                <Compass className="w-10 h-10 text-primary-soft mx-auto animate-spin-slow" />
                                <h3 className="font-serif text-3xl sm:text-4xl font-bold leading-tight">
                                    Ingin Rangkaian Kustom Khusus Untuk Momen Anda?
                                </h3>
                                <p className="text-white/80 text-sm leading-relaxed">
                                    Tim florist berpengalaman kami selalu siap melayani konsultasi desain bunga kustom, menyesuaikan dengan preferensi bunga favorit, palet warna, ukuran, hingga bujet spesifik Anda.
                                </p>
                                <div className="pt-4">
                                    <Link href={route('contact')}>
                                        <button className="px-8 py-3.5 bg-white hover:bg-gray-50 text-primary text-xs font-bold uppercase tracking-wider rounded-xl transition-all shadow-md">
                                            Hubungi Florist Kami
                                        </button>
                                    </Link>
                                </div>
                            </div>
                        </div>
                    </ScrollReveal>
                </section>
            </div>
        </PublicLayout>
    );
}

<template>
    <AppLayout>
        <!-- Hero Section -->
        <section class="bg-gradient-to-r from-green-600 to-blue-600 text-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 items-center">
                    <div>
                        <h1 class="text-4xl md:text-5xl font-bold mb-4">
                            Toko Makmur
                        </h1>
                        <p class="text-xl md:text-2xl mb-6 text-green-100">
                            Belanja kebutuhan sehari-hari dengan mudah dan terpercaya
                        </p>
                        <div class="flex flex-col sm:flex-row gap-4">
                            <Link
                                href="/products"
                                class="inline-block bg-white text-green-600 px-8 py-3 rounded-lg font-semibold hover:bg-gray-100 transition text-center"
                            >
                                Lihat Produk
                            </Link>
                            <Link
                                href="/cart"
                                class="inline-block bg-green-700 text-white px-8 py-3 rounded-lg font-semibold hover:bg-green-800 transition text-center"
                            >
                                Keranjang Belanja
                            </Link>
                        </div>
                    </div>
                    <div class="hidden lg:block">
                        <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-8">
                            <h3 class="text-2xl font-bold mb-4">Jam Operasional</h3>
                            <div class="space-y-2 text-green-100">
                                <div class="flex justify-between">
                                    <span>Senin - Jumat</span>
                                    <span>08:00 - 21:00</span>
                                </div>
                                <div class="flex justify-between">
                                    <span>Sabtu - Minggu</span>
                                    <span>08:00 - 22:00</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Quick Stats -->
        <section class="bg-white py-8 border-b">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <div class="text-center">
                        <div class="text-3xl font-bold text-green-600">{{ stats.totalProducts }}+</div>
                        <div class="text-gray-600">Produk Tersedia</div>
                    </div>
                    <div class="text-center">
                        <div class="text-3xl font-bold text-blue-600">{{ stats.categories }}+</div>
                        <div class="text-gray-600">Kategori</div>
                    </div>
                    <div class="text-center">
                        <div class="text-3xl font-bold text-purple-600">{{ stats.discountProducts }}+</div>
                        <div class="text-gray-600">Produk Diskon</div>
                    </div>
                    <div class="text-center">
                        <div class="text-3xl font-bold text-orange-600">24/7</div>
                        <div class="text-gray-600">Layanan</div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Featured Categories -->
        <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="text-center mb-8">
                <h2 class="text-3xl font-bold text-gray-900 mb-2">Kategori Produk</h2>
                <p class="text-gray-600">Temukan produk sesuai kebutuhan Anda</p>
            </div>
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4">
                <Link
                    v-for="category in featuredCategories"
                    :key="category.name"
                    :href="`/products?category=${category.name}`"
                    class="group"
                >
                    <div class="bg-white rounded-xl p-6 shadow-sm hover:shadow-lg transition-all duration-300 text-center border hover:border-blue-200">
                        <div class="text-4xl mb-3">{{ category.icon }}</div>
                        <h3 class="font-semibold text-gray-900 group-hover:text-blue-600 text-sm">
                            {{ category.name }}
                        </h3>
                        <p class="text-xs text-gray-500 mt-1">{{ category.count }} produk</p>
                    </div>
                </Link>
            </div>
        </section>

        <!-- Featured Products -->
        <section class="bg-gray-50 py-12">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center mb-8">
                    <div>
                        <h2 class="text-3xl font-bold text-gray-900">Produk Unggulan</h2>
                        <p class="text-gray-600 mt-1">Produk pilihan dengan kualitas terbaik</p>
                    </div>
                    <Link href="/products" class="text-blue-600 hover:text-blue-700 font-semibold flex items-center">
                        Lihat Semua
                        <ArrowRightIcon class="h-4 w-4 ml-1" />
                    </Link>
                </div>
                
                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-4">
                    <ProductCard
                        v-for="product in featuredProducts"
                        :key="product.id"
                        :product="product"
                    />
                </div>
            </div>
        </section>

        <!-- Best Sellers -->
        <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="flex justify-between items-center mb-8">
                <div>
                    <h2 class="text-3xl font-bold text-gray-900">Produk Terlaris</h2>
                    <p class="text-gray-600 mt-1">Produk favorit pelanggan</p>
                </div>
                <Link href="/products?sort=popular" class="text-blue-600 hover:text-blue-700 font-semibold flex items-center">
                    Lihat Semua
                    <ArrowRightIcon class="h-4 w-4 ml-1" />
                </Link>
            </div>
            
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-4">
                <ProductCard
                    v-for="product in bestSellers"
                    :key="product.id"
                    :product="product"
                />
            </div>
        </section>

        <!-- Discount Products -->
        <section class="bg-gradient-to-r from-red-50 to-pink-50 py-12" v-if="discountProducts && discountProducts.length > 0">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center mb-8">
                    <div>
                        <h2 class="text-3xl font-bold text-gray-900">🔥 Produk Diskon</h2>
                        <p class="text-gray-600 mt-1">Hemat lebih banyak dengan penawaran spesial</p>
                    </div>
                    <Link href="/products?discount=true" class="text-red-600 hover:text-red-700 font-semibold flex items-center">
                        Lihat Semua
                        <ArrowRightIcon class="h-4 w-4 ml-1" />
                    </Link>
                </div>
                
                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-4">
                    <ProductCard
                        v-for="product in discountProducts"
                        :key="product.id"
                        :product="product"
                    />
                </div>
            </div>
        </section>

        <!-- Store Features -->
        <section class="bg-white py-12">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-12">
                    <h2 class="text-3xl font-bold text-gray-900 mb-2">Mengapa Pilih Toko Makmur?</h2>
                    <p class="text-gray-600">Komitmen kami untuk memberikan pelayanan terbaik</p>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                    <div class="text-center">
                        <div class="bg-green-100 text-green-600 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                            <CheckCircleIcon class="h-8 w-8" />
                        </div>
                        <h3 class="text-xl font-semibold mb-2">Produk Berkualitas</h3>
                        <p class="text-gray-600">Semua produk telah melewati seleksi ketat untuk menjamin kualitas terbaik</p>
                    </div>
                    
                    <div class="text-center">
                        <div class="bg-blue-100 text-blue-600 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                            <CurrencyDollarIcon class="h-8 w-8" />
                        </div>
                        <h3 class="text-xl font-semibold mb-2">Harga Terjangkau</h3>
                        <p class="text-gray-600">Harga kompetitif dengan berbagai promo menarik setiap harinya</p>
                    </div>
                    
                    <div class="text-center">
                        <div class="bg-purple-100 text-purple-600 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                            <ClockIcon class="h-8 w-8" />
                        </div>
                        <h3 class="text-xl font-semibold mb-2">Pelayanan Cepat</h3>
                        <p class="text-gray-600">Proses pembelian yang mudah dan cepat untuk kenyamanan Anda</p>
                    </div>
                    
                    <div class="text-center">
                        <div class="bg-orange-100 text-orange-600 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                            <ChatBubbleLeftRightIcon class="h-8 w-8" />
                        </div>
                        <h3 class="text-xl font-semibold mb-2">Customer Service</h3>
                        <p class="text-gray-600">Tim customer service yang ramah dan siap membantu Anda</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Call to Action -->
        <section class="bg-gradient-to-r from-blue-600 to-purple-600 text-white py-12">
            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                <h2 class="text-3xl font-bold mb-4">Mulai Belanja Sekarang!</h2>
                <p class="text-xl mb-8 text-blue-100">
                    Dapatkan produk berkualitas dengan harga terbaik hanya di Toko Makmur
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <Link
                        href="/products"
                        class="inline-block bg-white text-blue-600 px-8 py-3 rounded-lg font-semibold hover:bg-gray-100 transition"
                    >
                        Jelajahi Produk
                    </Link>
                    <Link
                        href="/products?discount=true"
                        class="inline-block bg-red-500 text-white px-8 py-3 rounded-lg font-semibold hover:bg-red-600 transition"
                    >
                        Lihat Promo
                    </Link>
                </div>
            </div>
        </section>
    </AppLayout>
</template>

<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import ProductCard from '@/Components/ProductCard.vue';
import { Link } from '@inertiajs/vue3';
import { 
    ArrowRightIcon,
    CheckCircleIcon, 
    CurrencyDollarIcon, 
    ClockIcon, 
    ChatBubbleLeftRightIcon 
} from '@heroicons/vue/24/outline';

const props = defineProps({
    featuredProducts: Array,
    bestSellers: Array,
    discountProducts: Array,
});

// Mock stats - in real app, these would come from the backend
const stats = {
    totalProducts: 39,
    categories: 5,
    discountProducts: props.discountProducts?.length || 0,
};

const featuredCategories = [
    { name: 'Elektronik', icon: '💻', count: 4 },
    { name: 'Fashion', icon: '👕', count: 6 },
    { name: 'Makanan & Minuman', icon: '🍔', count: 5 },
    { name: 'Kesehatan & Kecantikan', icon: '💊', count: 1 },
    { name: 'Rumah Tangga', icon: '🏠', count: 23 },
];
</script>

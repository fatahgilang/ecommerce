<template>
    <AppLayout>
        <!-- Hero Section -->
        <section class="bg-gradient-to-r from-blue-600 to-purple-600 text-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
                <div class="text-center">
                    <h1 class="text-4xl md:text-6xl font-bold mb-4">
                        Belanja Mudah, Cepat & Terpercaya
                    </h1>
                    <p class="text-xl md:text-2xl mb-8 text-blue-100">
                        Temukan ribuan produk berkualitas dengan harga terbaik
                    </p>
                    <Link
                        href="/products"
                        class="inline-block bg-white text-blue-600 px-8 py-3 rounded-lg font-semibold hover:bg-gray-100 transition"
                    >
                        Mulai Belanja
                    </Link>
                </div>
            </div>
        </section>

        <!-- Featured Categories -->
        <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
            <h2 class="text-3xl font-bold text-gray-900 mb-8">Kategori Populer</h2>
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
                <Link
                    v-for="category in featuredCategories"
                    :key="category.name"
                    :href="`/products?category=${category.name}`"
                    class="group"
                >
                    <div class="bg-white rounded-xl p-6 shadow-sm hover:shadow-md transition text-center">
                        <div class="text-4xl mb-3">{{ category.icon }}</div>
                        <h3 class="font-semibold text-gray-900 group-hover:text-blue-600">
                            {{ category.name }}
                        </h3>
                    </div>
                </Link>
            </div>
        </section>

        <!-- Featured Products -->
        <section class="bg-gray-100 py-16">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center mb-8">
                    <h2 class="text-3xl font-bold text-gray-900">Produk Unggulan</h2>
                    <Link href="/products" class="text-blue-600 hover:text-blue-700 font-semibold">
                        Lihat Semua →
                    </Link>
                </div>
                
                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                    <ProductCard
                        v-for="product in featuredProducts"
                        :key="product.id"
                        :product="product"
                    />
                </div>
            </div>
        </section>

        <!-- Best Sellers -->
        <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
            <div class="flex justify-between items-center mb-8">
                <h2 class="text-3xl font-bold text-gray-900">Produk Terlaris</h2>
                <Link href="/products?sort=best-seller" class="text-blue-600 hover:text-blue-700 font-semibold">
                    Lihat Semua →
                </Link>
            </div>
            
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                <ProductCard
                    v-for="product in bestSellers"
                    :key="product.id"
                    :product="product"
                />
            </div>
        </section>

        <!-- Features -->
        <section class="bg-blue-50 py-16">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <div class="text-center">
                        <div class="bg-blue-600 text-white w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                            <TruckIcon class="h-8 w-8" />
                        </div>
                        <h3 class="text-xl font-semibold mb-2">Gratis Ongkir</h3>
                        <p class="text-gray-600">Untuk pembelian minimal Rp 100.000</p>
                    </div>
                    
                    <div class="text-center">
                        <div class="bg-blue-600 text-white w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                            <ShieldCheckIcon class="h-8 w-8" />
                        </div>
                        <h3 class="text-xl font-semibold mb-2">Pembayaran Aman</h3>
                        <p class="text-gray-600">Transaksi dijamin aman 100%</p>
                    </div>
                    
                    <div class="text-center">
                        <div class="bg-blue-600 text-white w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                            <ChatBubbleLeftRightIcon class="h-8 w-8" />
                        </div>
                        <h3 class="text-xl font-semibold mb-2">Customer Service 24/7</h3>
                        <p class="text-gray-600">Siap membantu kapan saja</p>
                    </div>
                </div>
            </div>
        </section>
    </AppLayout>
</template>

<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import ProductCard from '@/Components/ProductCard.vue';
import { Link } from '@inertiajs/vue3';
import { TruckIcon, ShieldCheckIcon, ChatBubbleLeftRightIcon } from '@heroicons/vue/24/outline';

defineProps({
    featuredProducts: Array,
    bestSellers: Array,
});

const featuredCategories = [
    { name: 'Elektronik', icon: '💻' },
    { name: 'Fashion', icon: '👕' },
    { name: 'Makanan', icon: '🍔' },
    { name: 'Olahraga', icon: '⚽' },
    { name: 'Kesehatan', icon: '💊' },
    { name: 'Rumah Tangga', icon: '🏠' },
];
</script>

<template>
    <AppLayout>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <!-- Breadcrumb -->
            <nav class="flex mb-6" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-3">
                    <li>
                        <Link href="/" class="text-gray-600 hover:text-blue-600">Home</Link>
                    </li>
                    <li>
                        <span class="mx-2 text-gray-400">/</span>
                        <span class="text-gray-900 font-medium">Produk</span>
                    </li>
                </ol>
            </nav>

            <!-- Products Section -->
            <div class="w-full">
                <!-- Header -->
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">
                            {{ pageTitle }}
                        </h1>
                        <p class="text-gray-600 mt-1">
                            {{ products.total }} produk ditemukan
                        </p>
                    </div>

                    <!-- Sort -->
                    <select
                        v-model="filters.sort"
                        @change="applyFilters"
                        class="rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500"
                    >
                        <option value="newest">Terbaru</option>
                        <option value="price_low">Harga Terendah</option>
                        <option value="price_high">Harga Tertinggi</option>
                        <option value="popular">Terpopuler</option>
                    </select>
                </div>

                <!-- Products Grid -->
                <div v-if="products.data.length > 0" class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-6">
                    <ProductCard
                        v-for="product in products.data"
                        :key="product.id"
                        :product="product"
                    />
                </div>

                <!-- Empty State -->
                <div v-else class="text-center py-16">
                    <div class="text-gray-400 mb-4">
                        <svg class="mx-auto h-24 w-24" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">Produk tidak ditemukan</h3>
                    <p class="text-gray-600 mb-4">Coba ubah kata kunci pencarian atau pilih kategori lain</p>
                    <Link
                        href="/products"
                        class="inline-block bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700"
                    >
                        Lihat Semua Produk
                    </Link>
                </div>

                <!-- Pagination -->
                <div v-if="products.data.length > 0" class="mt-8">
                    <Pagination :links="products.links" />
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import { ref, computed } from 'vue';
import { router } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import ProductCard from '@/Components/ProductCard.vue';
import Pagination from '@/Components/Pagination.vue';
import { Link } from '@inertiajs/vue3';

const props = defineProps({
    products: Object,
    filters: Object,
});

const filters = ref({
    sort: props.filters?.sort || 'newest',
});

const pageTitle = computed(() => {
    if (props.filters?.search) {
        return `Hasil pencarian "${props.filters.search}"`;
    }
    if (props.filters?.category) {
        return props.filters.category;
    }
    return 'Semua Produk';
});

const applyFilters = () => {
    const currentParams = new URLSearchParams(window.location.search);
    
    // Preserve existing filters (search, category, etc.) and only update sort
    const params = {};
    for (const [key, value] of currentParams.entries()) {
        params[key] = value;
    }
    params.sort = filters.value.sort;
    
    router.get('/products', params, {
        preserveState: true,
        preserveScroll: true,
    });
};
</script>

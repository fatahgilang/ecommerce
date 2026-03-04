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

            <div class="flex flex-col lg:flex-row gap-8">
                <!-- Sidebar Filters -->
                <aside class="lg:w-64 flex-shrink-0">
                    <div class="bg-white rounded-xl shadow-sm p-6 sticky top-20">
                        <h3 class="font-semibold text-lg mb-4">Filter</h3>

                        <!-- Category Filter -->
                        <div class="mb-6">
                            <h4 class="font-medium text-gray-900 mb-3">Kategori</h4>
                            <div class="space-y-2">
                                <label
                                    v-for="category in categories"
                                    :key="category"
                                    class="flex items-center"
                                >
                                    <input
                                        type="checkbox"
                                        :value="category"
                                        v-model="filters.categories"
                                        @change="applyFilters"
                                        class="rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                                    />
                                    <span class="ml-2 text-sm text-gray-700">{{ category }}</span>
                                </label>
                            </div>
                        </div>

                        <!-- Price Range -->
                        <div class="mb-6">
                            <h4 class="font-medium text-gray-900 mb-3">Harga</h4>
                            <div class="space-y-3">
                                <div>
                                    <label class="text-sm text-gray-600">Min</label>
                                    <input
                                        type="number"
                                        v-model="filters.minPrice"
                                        @change="applyFilters"
                                        placeholder="0"
                                        class="w-full mt-1 rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500"
                                    />
                                </div>
                                <div>
                                    <label class="text-sm text-gray-600">Max</label>
                                    <input
                                        type="number"
                                        v-model="filters.maxPrice"
                                        @change="applyFilters"
                                        placeholder="10000000"
                                        class="w-full mt-1 rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500"
                                    />
                                </div>
                            </div>
                        </div>

                        <!-- Reset Button -->
                        <button
                            @click="resetFilters"
                            class="w-full bg-gray-100 text-gray-700 py-2 rounded-lg hover:bg-gray-200 transition font-medium"
                        >
                            Reset Filter
                        </button>
                    </div>
                </aside>

                <!-- Products Grid -->
                <div class="flex-1">
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
                    <div v-if="products.data.length > 0" class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
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
                        <p class="text-gray-600 mb-4">Coba ubah filter atau kata kunci pencarian</p>
                        <button
                            @click="resetFilters"
                            class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700"
                        >
                            Reset Filter
                        </button>
                    </div>

                    <!-- Pagination -->
                    <div v-if="products.data.length > 0" class="mt-8">
                        <Pagination :links="products.links" />
                    </div>
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

const categories = [
    'Elektronik',
    'Fashion',
    'Makanan & Minuman',
    'Olahraga',
    'Kesehatan & Kecantikan',
    'Rumah Tangga',
];

const filters = ref({
    categories: props.filters?.categories || [],
    minPrice: props.filters?.minPrice || null,
    maxPrice: props.filters?.maxPrice || null,
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
    router.get('/products', {
        categories: filters.value.categories,
        min_price: filters.value.minPrice,
        max_price: filters.value.maxPrice,
        sort: filters.value.sort,
    }, {
        preserveState: true,
        preserveScroll: true,
    });
};

const resetFilters = () => {
    filters.value = {
        categories: [],
        minPrice: null,
        maxPrice: null,
        sort: 'newest',
    };
    router.get('/products');
};
</script>

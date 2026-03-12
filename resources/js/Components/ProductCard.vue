<template>
    <div class="group h-full flex flex-col">
        <div class="bg-white rounded-xl shadow-sm hover:shadow-lg transition overflow-hidden h-full flex flex-col">
            <!-- Image - Clickable to product detail -->
            <Link :href="`/products/${product.id}`" class="block">
                <div class="aspect-square bg-gray-200 overflow-hidden flex items-center justify-center">
                    <img
                        v-if="product.image_url || product.image"
                        :src="product.image_url || product.image"
                        :alt="product.product_name"
                        class="w-full h-full object-cover group-hover:scale-105 transition duration-300"
                    />
                    <div v-else class="text-gray-400 text-center p-4">
                        <svg class="mx-auto h-16 w-16 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        <span class="text-xs">Tidak ada gambar</span>
                    </div>
                </div>
            </Link>

            <!-- Content - Clickable to product detail -->
            <Link :href="`/products/${product.id}`" class="block p-4 flex-grow">
                <!-- Product Name -->
                <h3 class="font-semibold text-gray-900 mb-2 line-clamp-2 group-hover:text-blue-600">
                    {{ product.product_name }}
                </h3>

                <!-- Rating -->
                <div class="flex items-center mb-2" v-if="product.average_rating && product.average_rating > 0">
                    <div class="flex items-center">
                        <StarIcon class="h-4 w-4 text-yellow-400 fill-current" />
                        <span class="ml-1 text-sm text-gray-600">
                            {{ Number(product.average_rating).toFixed(1) }}
                        </span>
                    </div>
                    <span class="mx-2 text-gray-300">|</span>
                    <span class="text-sm text-gray-600">
                        {{ product.reviews_count || 0 }} ulasan
                    </span>
                </div>

                <!-- Price -->
                <div class="flex items-baseline justify-between">
                    <div>
                        <!-- Discount badge or placeholder space -->
                        <div class="mb-1 min-h-[24px] flex items-center">
                            <span v-if="product.has_discount && hasActiveDiscount(product)" class="bg-red-100 text-red-800 text-xs px-2 py-1 rounded-full font-medium">
                                -{{ getDiscountPercentage(product) }}%
                            </span>
                        </div>

                        <!-- Current Price (with discount if applicable) -->
                        <p class="text-xl font-bold text-blue-600">
                            {{ formatPrice(getCurrentPrice(product)) }}
                        </p>

                        <!-- Original Price (strikethrough if discounted) or placeholder space -->
                        <div class="min-h-[20px] flex items-center gap-2">
                            <div v-if="product.has_discount && hasActiveDiscount(product)" class="flex items-center gap-2">
                                <p class="text-sm text-gray-500 line-through">
                                    {{ formatPrice(product.product_price) }}
                                </p>
                                <span class="text-xs text-green-600 font-medium">
                                    Hemat {{ formatPrice(getDiscountAmount(product)) }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Stock Badge -->
                    <div v-if="product.stock < 10 && product.stock > 0" class="text-xs">
                        <span class="bg-red-100 text-red-800 px-2 py-1 rounded-full">
                            Stok: {{ product.stock }}
                        </span>
                    </div>
                </div>

                <!-- Shop Name -->
                <p class="text-xs text-gray-500 mt-2" v-if="product.shop">
                    {{ product.shop.shop_name }}
                </p>
            </Link>

            <!-- Add to Cart Button - Standalone, not inside Link -->
            <div class="px-4 pb-4 mt-auto">
                <button
                    @click="addToCart"
                    class="w-full bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700 transition font-medium disabled:bg-gray-400 disabled:cursor-not-allowed"
                    :disabled="product.stock === 0 || isAdding"
                >
                    <span v-if="isAdding" class="flex items-center justify-center">
                        <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Menambahkan...
                    </span>
                    <span v-else>
                        {{ product.stock === 0 ? 'Stok Habis' : 'Tambah ke Keranjang' }}
                    </span>
                </button>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue';
import { Link, router } from '@inertiajs/vue3';
import { StarIcon } from '@heroicons/vue/24/solid';

const props = defineProps({
    product: {
        type: Object,
        required: true,
    },
});

const isAdding = ref(false);

const formatPrice = (price) => {
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 0,
    }).format(price);
};

// Discount helper functions
const hasActiveDiscount = (product) => {
    if (!product.has_discount) return false;

    const now = new Date().toISOString().split('T')[0];
    return product.discount_start_date <= now && product.discount_end_date >= now;
};

const getCurrentPrice = (product) => {
    if (!hasActiveDiscount(product)) {
        return product.product_price;
    }

    // Prioritas: discount_price dulu, baru discount_percentage
    if (product.discount_price && product.discount_price > 0) {
        return parseFloat(product.discount_price);
    }

    if (product.discount_percentage && product.discount_percentage > 0) {
        return product.product_price * (1 - (parseFloat(product.discount_percentage) / 100));
    }

    return product.product_price;
};

const getDiscountAmount = (product) => {
    if (!hasActiveDiscount(product)) return 0;

    // Prioritas: discount_price dulu, baru discount_percentage
    if (product.discount_price && product.discount_price > 0) {
        return product.product_price - parseFloat(product.discount_price);
    }

    if (product.discount_percentage && product.discount_percentage > 0) {
        return product.product_price * (parseFloat(product.discount_percentage) / 100);
    }

    return 0;
};

const getDiscountPercentage = (product) => {
    if (!hasActiveDiscount(product)) return 0;

    // Jika ada discount_percentage, gunakan itu
    if (product.discount_percentage && product.discount_percentage > 0) {
        return Math.round(parseFloat(product.discount_percentage));
    }

    // Jika ada discount_price, hitung persentasenya
    if (product.discount_price && product.discount_price > 0) {
        const discountAmount = product.product_price - parseFloat(product.discount_price);
        return Math.round((discountAmount / product.product_price) * 100);
    }

    return 0;
};

const addToCart = () => {
    if (props.product.stock === 0 || isAdding.value) {
        return;
    }

    isAdding.value = true;

    // Get cart from localStorage
    const cart = JSON.parse(localStorage.getItem('cart') || '[]');

    // Check if product already in cart
    const existingIndex = cart.findIndex(item => item.id === props.product.id);

    if (existingIndex > -1) {
        // Update existing item with latest discount info
        const existingItem = cart[existingIndex];
        existingItem.quantity += 1;
        existingItem.product_price = getCurrentPrice(props.product); // Update with current price
        existingItem.original_price = props.product.product_price; // Update original price
        existingItem.has_discount = hasActiveDiscount(props.product); // Update discount status
    } else {
        // Add new item
        cart.push({
            id: props.product.id,
            product_name: props.product.product_name,
            product_price: getCurrentPrice(props.product), // Use current price (with discount)
            original_price: props.product.product_price, // Keep original price for reference
            unit: props.product.unit,
            image: props.product.image_url || props.product.image, // Use image_url if available
            quantity: 1,
            stock: props.product.stock,
            has_discount: hasActiveDiscount(props.product),
        });
    }

    // Save to localStorage
    localStorage.setItem('cart', JSON.stringify(cart));

    // Show notification (you can use a toast library here)
    setTimeout(() => {
        isAdding.value = false;
        alert(`${props.product.product_name} berhasil ditambahkan ke keranjang!`);

        // Trigger cart count update (emit event or use store)
        window.dispatchEvent(new CustomEvent('cart-updated', {
            detail: { count: cart.reduce((sum, item) => sum + item.quantity, 0) }
        }));
    }, 500);
};
</script>

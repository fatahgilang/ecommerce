<template>
    <div v-if="showDebug" class="fixed bottom-4 right-4 bg-gray-900 text-white p-4 rounded-lg shadow-lg max-w-md z-50">
        <div class="flex justify-between items-center mb-2">
            <h3 class="font-bold text-sm">Cart Debug Info</h3>
            <button @click="showDebug = false" class="text-gray-400 hover:text-white">×</button>
        </div>
        
        <div class="text-xs space-y-1">
            <p><strong>Total Items:</strong> {{ stats.totalItems }}</p>
            <p><strong>Total Quantity:</strong> {{ stats.totalQuantity }}</p>
            <p><strong>Discounted Items:</strong> {{ stats.discountedItems }}</p>
            <p><strong>Total Savings:</strong> {{ formatPrice(stats.totalSavings) }}</p>
        </div>
        
        <div class="mt-3 space-y-1">
            <button 
                @click="fixCart" 
                class="w-full bg-blue-600 text-white px-2 py-1 rounded text-xs hover:bg-blue-700"
            >
                Fix Cart Data
            </button>
            <button 
                @click="clearCart" 
                class="w-full bg-red-600 text-white px-2 py-1 rounded text-xs hover:bg-red-700"
            >
                Clear Cart
            </button>
            <button 
                @click="logCart" 
                class="w-full bg-green-600 text-white px-2 py-1 rounded text-xs hover:bg-green-700"
            >
                Log Cart to Console
            </button>
        </div>
    </div>
    
    <!-- Debug Toggle Button -->
    <button 
        v-if="!showDebug"
        @click="showDebug = true"
        class="fixed bottom-4 right-4 bg-gray-700 text-white p-2 rounded-full shadow-lg z-50 text-xs"
        title="Show Cart Debug"
    >
        🛒
    </button>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import { getCartStats, fixCartDiscountData, clearAndReloadCart } from '@/utils/cartUtils.js';

const showDebug = ref(false);
const stats = ref({
    totalItems: 0,
    totalQuantity: 0,
    discountedItems: 0,
    totalSavings: 0
});

const formatPrice = (price) => {
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 0,
    }).format(price);
};

const updateStats = () => {
    stats.value = getCartStats();
};

const fixCart = () => {
    fixCartDiscountData();
    updateStats();
    alert('Cart data has been fixed! Please refresh the page.');
};

const clearCart = () => {
    if (confirm('Are you sure you want to clear the cart?')) {
        clearAndReloadCart();
    }
};

const logCart = () => {
    const cart = JSON.parse(localStorage.getItem('cart') || '[]');
    console.log('Current Cart Data:', cart);
    console.log('Cart Stats:', stats.value);
};

onMounted(() => {
    updateStats();
    
    // Update stats when cart changes
    window.addEventListener('cart-updated', updateStats);
});
</script>
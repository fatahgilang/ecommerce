<template>
    <div class="min-h-screen bg-gray-50">
        <!-- Navigation -->
        <nav class="bg-white shadow-sm sticky top-0 z-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <!-- Logo & Brand -->
                    <div class="flex items-center">
                        <Link href="/" class="flex items-center space-x-2">
                            <span class="text-2xl font-bold text-blue-600">Akun Demo</span>
                        </Link>
                    </div>

                    <!-- Search Bar -->
                    <div class="hidden md:flex items-center flex-1 max-w-2xl mx-8">
                        <div class="relative w-full">
                            <input
                                type="text"
                                v-model="searchQuery"
                                @keyup.enter="handleSearch"
                                placeholder="Cari produk..."
                                class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            />
                            <MagnifyingGlassIcon class="absolute left-3 top-2.5 h-5 w-5 text-gray-400" />
                        </div>
                    </div>

                    <!-- Right Menu -->
                    <div class="flex items-center space-x-4">
                        <button @click="router.visit('/cart')" class="relative p-2 text-gray-600 hover:text-blue-600">
                            <ShoppingCartIcon class="h-6 w-6" />
                            <span v-if="cartCount > 0" class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center">
                                {{ cartCount }}
                            </span>
                        </button>
                        
                        <Link href="/login" class="text-gray-600 hover:text-blue-600 font-medium">
                            Masuk
                        </Link>
                        
                        <Link href="/register" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 font-medium">
                            Daftar
                        </Link>
                    </div>
                </div>

                <!-- Mobile Search -->
                <div class="md:hidden pb-3">
                    <div class="relative">
                        <input
                            type="text"
                            v-model="searchQuery"
                            @keyup.enter="handleSearch"
                            placeholder="Cari produk..."
                            class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        />
                        <MagnifyingGlassIcon class="absolute left-3 top-2.5 h-5 w-5 text-gray-400" />
                    </div>
                </div>
            </div>

            <!-- Categories -->
            <div class="border-t border-gray-200">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex space-x-8 overflow-x-auto py-3">
                        <Link
                            v-for="category in categories"
                            :key="category"
                            :href="`/products?category=${category}`"
                            class="text-sm text-gray-600 hover:text-blue-600 whitespace-nowrap"
                        >
                            {{ category }}
                        </Link>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Main Content -->
        <main>
            <slot />
        </main>

        <!-- Footer -->
        <footer class="bg-gray-900 text-white mt-16">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                    <!-- About -->
                    <div>
                        <h3 class="text-lg font-semibold mb-4">Tentang Kami</h3>
                        <p class="text-gray-400 text-sm">
                            Platform e-commerce terpercaya dengan berbagai pilihan produk berkualitas.
                        </p>
                    </div>

                    <!-- Links -->
                    <div>
                        <h3 class="text-lg font-semibold mb-4">Tautan</h3>
                        <ul class="space-y-2 text-sm text-gray-400">
                            <li><Link href="/about" class="hover:text-white">Tentang</Link></li>
                            <li><Link href="/contact" class="hover:text-white">Kontak</Link></li>
                            <li><Link href="/faq" class="hover:text-white">FAQ</Link></li>
                        </ul>
                    </div>

                    <!-- Customer Service -->
                    <div>
                        <h3 class="text-lg font-semibold mb-4">Layanan</h3>
                        <ul class="space-y-2 text-sm text-gray-400">
                            <li><Link href="/shipping" class="hover:text-white">Pengiriman</Link></li>
                            <li><Link href="/returns" class="hover:text-white">Pengembalian</Link></li>
                            <li><Link href="/terms" class="hover:text-white">Syarat & Ketentuan</Link></li>
                        </ul>
                    </div>

                    <!-- Contact -->
                    <div>
                        <h3 class="text-lg font-semibold mb-4">Hubungi Kami</h3>
                        <ul class="space-y-2 text-sm text-gray-400">
                            <li>Email: fatahgilang23@gmail.com</li>
                            <li>Telp: 082333058317</li>
                            <li>WhatsApp: 082333058317<</li>
                        </ul>
                    </div>
                </div>

                <div class="border-t border-gray-800 mt-8 pt-8 text-center text-sm text-gray-400">
                    <p>&copy; {{ new Date().getFullYear() }} E-Commerce. All rights reserved.</p>
                </div>
            </div>
        </footer>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { Link, router } from '@inertiajs/vue3';
import { MagnifyingGlassIcon, ShoppingCartIcon } from '@heroicons/vue/24/outline';

const searchQuery = ref('');
const cartCount = ref(0);

const categories = [
    'Elektronik',
    'Fashion',
    'Makanan & Minuman',
    'Olahraga',
    'Kesehatan & Kecantikan',
    'Rumah Tangga',
];

const handleSearch = () => {
    if (searchQuery.value.trim()) {
        router.visit(`/products?search=${searchQuery.value}`);
    }
};

const updateCartCount = () => {
    const cart = JSON.parse(localStorage.getItem('cart') || '[]');
    cartCount.value = cart.reduce((sum, item) => sum + item.quantity, 0);
};

onMounted(() => {
    updateCartCount();
    
    // Listen for cart updates
    window.addEventListener('cart-updated', (event) => {
        cartCount.value = event.detail.count;
    });
});
</script>

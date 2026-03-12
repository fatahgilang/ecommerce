<template>
    <div class="min-h-screen bg-gray-50">
        <!-- Navigation -->
        <nav class="bg-white shadow-sm sticky top-0 z-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <!-- Logo & Brand -->
                    <div class="flex items-center">
                        <Link href="/" class="flex items-center space-x-2">
                            <div class="bg-green-600 text-white p-2 rounded-lg">
                                <HomeIcon class="h-6 w-6" />
                            </div>
                            <span class="text-2xl font-bold text-green-600">Toko Makmur</span>
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
                        <Link 
                            href="/printer-setup" 
                            class="hidden md:flex items-center gap-1 text-gray-600 hover:text-blue-600 font-medium"
                            title="Pengaturan Printer"
                        >
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                            </svg>
                            <span class="hidden lg:inline">Printer</span>
                        </Link>
                        
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
                            href="/"
                            class="flex items-center space-x-1 text-sm text-gray-600 hover:text-blue-600 whitespace-nowrap"
                        >
                            <HomeIcon class="h-4 w-4" />
                            <span>Beranda</span>
                        </Link>
                        <Link
                            v-for="category in categories"
                            :key="category.slug || category"
                            :href="`/products?category=${category.name || category}`"
                            class="flex items-center space-x-1 text-sm text-gray-600 hover:text-blue-600 whitespace-nowrap"
                        >
                            <component 
                                v-if="category.icon" 
                                :is="getIconComponent(category.icon)" 
                                class="h-4 w-4" 
                            />
                            <span>{{ category.name || category }}</span>
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
                        <h3 class="text-lg font-semibold mb-4">Toko Makmur</h3>
                        <p class="text-gray-400 text-sm">
                            Toko terpercaya untuk kebutuhan sehari-hari dengan produk berkualitas dan pelayanan terbaik.
                        </p>
                    </div>

                    <!-- Links -->
                    <div>
                        <h3 class="text-lg font-semibold mb-4">Menu</h3>
                        <ul class="space-y-2 text-sm text-gray-400">
                            <li><Link href="/" class="hover:text-white">Beranda</Link></li>
                            <li><Link href="/products" class="hover:text-white">Produk</Link></li>
                            <li><Link href="/cart" class="hover:text-white">Keranjang</Link></li>
                        </ul>
                    </div>

                    <!-- Categories -->
                    <div>
                        <h3 class="text-lg font-semibold mb-4">Kategori</h3>
                        <ul class="space-y-2 text-sm text-gray-400">
                            <li><Link href="/products?category=Elektronik" class="hover:text-white">Elektronik</Link></li>
                            <li><Link href="/products?category=Fashion" class="hover:text-white">Fashion</Link></li>
                            <li><Link href="/products?category=Makanan & Minuman" class="hover:text-white">Makanan & Minuman</Link></li>
                            <li><Link href="/products?category=Rumah Tangga" class="hover:text-white">Rumah Tangga</Link></li>
                        </ul>
                    </div>

                    <!-- Contact -->
                    <div>
                        <h3 class="text-lg font-semibold mb-4">Hubungi Kami</h3>
                        <ul class="space-y-2 text-sm text-gray-400">
                            <li>📧 fatahgilang23@gmail.com</li>
                            <li>📞 082333058317</li>
                            <li>💬 WhatsApp: 082333058317</li>
                            <li>🕒 Senin-Minggu: 08:00-22:00</li>
                        </ul>
                    </div>
                </div>

                <div class="border-t border-gray-800 mt-8 pt-8 text-center text-sm text-gray-400">
                    <p>&copy; {{ new Date().getFullYear() }} Toko Makmur. Semua hak dilindungi.</p>
                </div>
            </div>
        </footer>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { Link, router } from '@inertiajs/vue3';
import { 
    MagnifyingGlassIcon, 
    ShoppingCartIcon,
    ComputerDesktopIcon,
    SparklesIcon,
    CakeIcon,
    HeartIcon,
    HomeIcon,
    TrophyIcon,
    AcademicCapIcon,
    WrenchScrewdriverIcon,
    GiftIcon,
    StarIcon
} from '@heroicons/vue/24/outline';

const searchQuery = ref('');
const cartCount = ref(0);

const categories = ref([]);

const fetchCategories = async () => {
    try {
        const response = await fetch('/api/v1/categories/main');
        const data = await response.json();
        categories.value = data;
    } catch (error) {
        console.error('Failed to fetch categories:', error);
        // Fallback to hardcoded categories
        categories.value = [
            { name: 'Elektronik', slug: 'elektronik' },
            { name: 'Fashion', slug: 'fashion' },
            { name: 'Makanan & Minuman', slug: 'makanan-minuman' },
            { name: 'Kesehatan & Kecantikan', slug: 'kesehatan-kecantikan' },
            { name: 'Rumah Tangga', slug: 'rumah-tangga' },
        ];
    }
};

const getIconComponent = (iconName) => {
    // Map icon names to actual icon components
    const iconMap = {
        'heroicon-o-computer-desktop': 'ComputerDesktopIcon',
        'heroicon-o-sparkles': 'SparklesIcon',
        'heroicon-o-cake': 'CakeIcon',
        'heroicon-o-heart': 'HeartIcon',
        'heroicon-o-home': 'HomeIcon',
        'heroicon-o-trophy': 'TrophyIcon',
        'heroicon-o-academic-cap': 'AcademicCapIcon',
        'heroicon-o-wrench-screwdriver': 'WrenchScrewdriverIcon',
        'heroicon-o-gift': 'GiftIcon',
        'heroicon-o-star': 'StarIcon',
    };
    
    return iconMap[iconName] || 'StarIcon';
};

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
    fetchCategories();
    
    // Listen for cart updates
    window.addEventListener('cart-updated', (event) => {
        cartCount.value = event.detail.count;
    });
});
</script>

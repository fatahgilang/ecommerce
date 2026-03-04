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
                        <span class="text-gray-900 font-medium">Keranjang</span>
                    </li>
                </ol>
            </nav>

            <h1 class="text-3xl font-bold text-gray-900 mb-8">Keranjang Belanja</h1>

            <!-- Cart Content -->
            <div v-if="cartItems.length > 0" class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Cart Items -->
                <div class="lg:col-span-2 space-y-4">
                    <div
                        v-for="item in cartItems"
                        :key="item.id"
                        class="bg-white rounded-xl shadow-sm p-4 flex gap-4"
                    >
                        <!-- Image -->
                        <img
                            :src="item.image || '/images/placeholder.svg'"
                            :alt="item.product_name"
                            class="w-24 h-24 object-cover rounded-lg"
                        />

                        <!-- Details -->
                        <div class="flex-1">
                            <h3 class="font-semibold text-gray-900 mb-1">
                                {{ item.product_name }}
                            </h3>
                            <p class="text-blue-600 font-bold mb-2">
                                {{ formatPrice(item.product_price) }}
                            </p>

                            <!-- Quantity Controls -->
                            <div class="flex items-center gap-3">
                                <button
                                    @click="decreaseQuantity(item.id)"
                                    class="w-8 h-8 rounded-lg border border-gray-300 flex items-center justify-center hover:bg-gray-100"
                                >
                                    -
                                </button>
                                <span class="w-12 text-center font-medium">{{ item.quantity }}</span>
                                <button
                                    @click="increaseQuantity(item.id)"
                                    :disabled="item.quantity >= item.stock"
                                    class="w-8 h-8 rounded-lg border border-gray-300 flex items-center justify-center hover:bg-gray-100 disabled:opacity-50 disabled:cursor-not-allowed"
                                >
                                    +
                                </button>
                                <button
                                    @click="removeItem(item.id)"
                                    class="ml-auto text-red-600 hover:text-red-700 text-sm font-medium"
                                >
                                    Hapus
                                </button>
                            </div>
                        </div>

                        <!-- Subtotal -->
                        <div class="text-right">
                            <p class="font-bold text-gray-900">
                                {{ formatPrice(item.product_price * item.quantity) }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Summary & Payment -->
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-xl shadow-sm p-6 sticky top-20">
                        <h2 class="text-xl font-bold text-gray-900 mb-4">Ringkasan Belanja</h2>

                        <div class="space-y-3 mb-6">
                            <div class="flex justify-between text-gray-600">
                                <span>Subtotal ({{ totalItems }} item)</span>
                                <span>{{ formatPrice(subtotal) }}</span>
                            </div>
                            <div class="flex justify-between text-gray-600">
                                <span>Ongkos Kirim</span>
                                <span>{{ formatPrice(shippingCost) }}</span>
                            </div>
                            <div class="border-t pt-3 flex justify-between text-lg font-bold text-gray-900">
                                <span>Total</span>
                                <span>{{ formatPrice(total) }}</span>
                            </div>
                        </div>

                        <!-- Payment Methods -->
                        <div class="mb-6">
                            <h3 class="font-semibold text-gray-900 mb-3">Pilih Metode Pembayaran</h3>
                            
                            <!-- Bank Transfer -->
                            <div class="space-y-2">
                                <label 
                                    v-for="method in paymentMethods"
                                    :key="method.id"
                                    class="flex items-start p-3 border-2 rounded-lg cursor-pointer transition-all"
                                    :class="selectedPayment === method.id ? 'border-blue-600 bg-blue-50' : 'border-gray-200 hover:border-blue-300'"
                                >
                                    <input
                                        type="radio"
                                        :value="method.id"
                                        v-model="selectedPayment"
                                        class="mt-1 text-blue-600 focus:ring-blue-500"
                                    />
                                    <div class="ml-3 flex-1">
                                        <div class="flex items-center justify-between">
                                            <span class="font-medium text-gray-900">{{ method.name }}</span>
                                            <span class="text-xs text-gray-500">{{ method.type }}</span>
                                        </div>
                                        <p class="text-sm text-gray-600 mt-1">{{ method.account }}</p>
                                        <p class="text-xs text-gray-500 mt-1">a.n. {{ method.holder }}</p>
                                    </div>
                                </label>
                            </div>
                        </div>

                        <!-- Payment Instructions -->
                        <div v-if="selectedPayment" class="mb-6 bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                            <h4 class="font-semibold text-yellow-900 text-sm mb-2">📝 Instruksi Pembayaran:</h4>
                            <ol class="text-xs text-yellow-800 space-y-1 list-decimal list-inside">
                                <li>Transfer sesuai total pembayaran</li>
                                <li>Simpan bukti transfer</li>
                                <li>Pesanan akan diproses setelah pembayaran dikonfirmasi</li>
                                <li>Hubungi toko untuk konfirmasi pembayaran</li>
                            </ol>
                        </div>

                        <button
                            @click="checkout"
                            :disabled="!selectedPayment || isProcessing"
                            class="w-full bg-blue-600 text-white py-3 rounded-lg hover:bg-blue-700 font-semibold disabled:opacity-50 disabled:cursor-not-allowed"
                        >
                            <span v-if="!isProcessing">Buat Pesanan</span>
                            <span v-else>Memproses...</span>
                        </button>

                        <Link
                            href="/products"
                            class="block text-center text-blue-600 hover:text-blue-700 mt-4 font-medium"
                        >
                            Lanjut Belanja
                        </Link>
                    </div>
                </div>
            </div>

            <!-- Empty Cart -->
            <div v-else class="text-center py-16">
                <div class="text-gray-400 mb-4">
                    <svg class="mx-auto h-24 w-24" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-2">Keranjang Belanja Kosong</h3>
                <p class="text-gray-600 mb-6">Yuk, mulai belanja sekarang!</p>
                <Link
                    href="/products"
                    class="inline-block bg-blue-600 text-white px-8 py-3 rounded-lg hover:bg-blue-700 font-semibold"
                >
                    Mulai Belanja
                </Link>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import { Link, router } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import axios from 'axios';

const cartItems = ref([]);
const shippingCost = ref(15000);
const isProcessing = ref(false);
const selectedPayment = ref('');

// Payment methods
const paymentMethods = ref([
    {
        id: 'bca',
        name: 'Bank BCA',
        type: 'Transfer Bank',
        account: '1234567890',
        holder: 'Toko Makmur Jaya'
    },
    {
        id: 'mandiri',
        name: 'Bank Mandiri',
        type: 'Transfer Bank',
        account: '0987654321',
        holder: 'Toko Makmur Jaya'
    },
    {
        id: 'bri',
        name: 'Bank BRI',
        type: 'Transfer Bank',
        account: '5555666677',
        holder: 'Toko Makmur Jaya'
    },
    {
        id: 'gopay',
        name: 'GoPay',
        type: 'E-Wallet',
        account: '081234567890',
        holder: 'Toko Makmur Jaya'
    },
    {
        id: 'ovo',
        name: 'OVO',
        type: 'E-Wallet',
        account: '081234567890',
        holder: 'Toko Makmur Jaya'
    },
    {
        id: 'dana',
        name: 'DANA',
        type: 'E-Wallet',
        account: '081234567890',
        holder: 'Toko Makmur Jaya'
    }
]);

const loadCart = () => {
    const cart = JSON.parse(localStorage.getItem('cart') || '[]');
    cartItems.value = cart;
};

const saveCart = () => {
    localStorage.setItem('cart', JSON.stringify(cartItems.value));
    window.dispatchEvent(new CustomEvent('cart-updated', { 
        detail: { count: totalItems.value }
    }));
};

const increaseQuantity = (id) => {
    const item = cartItems.value.find(i => i.id === id);
    if (item && item.quantity < item.stock) {
        item.quantity++;
        saveCart();
    }
};

const decreaseQuantity = (id) => {
    const item = cartItems.value.find(i => i.id === id);
    if (item && item.quantity > 1) {
        item.quantity--;
        saveCart();
    }
};

const removeItem = (id) => {
    if (confirm('Hapus produk dari keranjang?')) {
        cartItems.value = cartItems.value.filter(i => i.id !== id);
        saveCart();
    }
};

const formatPrice = (price) => {
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 0,
    }).format(price);
};

const totalItems = computed(() => {
    return cartItems.value.reduce((sum, item) => sum + item.quantity, 0);
});

const subtotal = computed(() => {
    return cartItems.value.reduce((sum, item) => sum + (item.product_price * item.quantity), 0);
});

const total = computed(() => {
    return subtotal.value + shippingCost.value;
});

const checkout = async () => {
    if (isProcessing.value) return;
    
    if (!selectedPayment.value) {
        alert('Mohon pilih metode pembayaran!');
        return;
    }

    isProcessing.value = true;

    try {
        // Get selected payment method details
        const paymentMethod = paymentMethods.value.find(m => m.id === selectedPayment.value);

        // Prepare checkout data
        const checkoutData = {
            payment_method: selectedPayment.value,
            items: cartItems.value.map(item => ({
                product_id: item.id,
                quantity: item.quantity,
                price: item.product_price,
            })),
        };

        // Send checkout request
        const response = await axios.post('/checkout', checkoutData);

        if (response.data.success) {
            // Clear cart
            localStorage.removeItem('cart');
            cartItems.value = [];
            
            // Update cart counter
            window.dispatchEvent(new CustomEvent('cart-updated', { 
                detail: { count: 0 }
            }));

            // Show payment instructions
            const orderData = response.data.data;
            const orderItems = orderData.orders.map(item => 
                `- ${item.product_name} x${item.quantity}: ${formatPrice(item.total)}`
            ).join('\n');

            alert(
                `✅ Pesanan Berhasil Dibuat!\n\n` +
                `ID Pesanan: #${orderData.orders[0].order_id}\n\n` +
                `Produk:\n${orderItems}\n\n` +
                `Total Pembayaran: ${formatPrice(orderData.total)}\n\n` +
                `💳 Metode Pembayaran: ${paymentMethod.name}\n` +
                `📱 ${paymentMethod.account}\n` +
                `👤 a.n. ${paymentMethod.holder}\n\n` +
                `Silakan transfer sesuai nominal dan simpan bukti transfer.\n` +
                `Hubungi toko untuk konfirmasi pembayaran.\n\n` +
                `Terima kasih telah berbelanja!`
            );

            // Reset selection
            selectedPayment.value = '';

            // Redirect to home
            router.visit('/');
        }
    } catch (error) {
        console.error('Checkout error:', error);
        
        let errorMessage = 'Gagal memproses pesanan. Silakan coba lagi.';
        
        if (error.response && error.response.data && error.response.data.message) {
            errorMessage = error.response.data.message;
        }
        
        alert(errorMessage);
    } finally {
        isProcessing.value = false;
    }
};

onMounted(() => {
    loadCart();
});
</script>

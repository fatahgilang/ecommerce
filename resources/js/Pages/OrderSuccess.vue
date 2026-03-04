<template>
    <AppLayout>
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <!-- Success Icon -->
            <div class="text-center mb-8">
                <div class="inline-flex items-center justify-center w-20 h-20 bg-green-100 rounded-full mb-4">
                    <svg class="w-10 h-10 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
                <h1 class="text-3xl font-bold text-gray-900 mb-2">Pesanan Berhasil!</h1>
                <p class="text-gray-600">Terima kasih telah berbelanja di toko kami</p>
            </div>

            <!-- Order Details -->
            <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
                <h2 class="text-xl font-bold text-gray-900 mb-4">Detail Pesanan</h2>
                
                <div class="space-y-3 mb-6">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Nama</span>
                        <span class="font-medium">{{ order.customer.name }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Email</span>
                        <span class="font-medium">{{ order.customer.email }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Telepon</span>
                        <span class="font-medium">{{ order.customer.phone }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Total Pesanan</span>
                        <span class="font-bold text-lg text-blue-600">{{ formatPrice(order.total) }}</span>
                    </div>
                </div>

                <div class="border-t pt-4">
                    <h3 class="font-semibold text-gray-900 mb-3">Produk yang Dipesan:</h3>
                    <div class="space-y-2">
                        <div
                            v-for="item in order.orders"
                            :key="item.order_id"
                            class="flex justify-between text-sm"
                        >
                            <span class="text-gray-600">
                                {{ item.product_name }} x{{ item.quantity }}
                            </span>
                            <span class="font-medium">{{ formatPrice(item.total) }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="flex gap-4">
                <Link
                    href="/"
                    class="flex-1 bg-blue-600 text-white text-center py-3 rounded-lg hover:bg-blue-700 font-semibold"
                >
                    Kembali ke Beranda
                </Link>
                <Link
                    href="/products"
                    class="flex-1 bg-white text-blue-600 border-2 border-blue-600 text-center py-3 rounded-lg hover:bg-blue-50 font-semibold"
                >
                    Belanja Lagi
                </Link>
            </div>

            <!-- Info -->
            <div class="mt-8 bg-blue-50 rounded-lg p-4">
                <p class="text-sm text-blue-800">
                    <strong>Catatan:</strong> Pesanan Anda sedang diproses. Kami akan menghubungi Anda melalui email atau telepon untuk konfirmasi pembayaran dan pengiriman.
                </p>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';

const props = defineProps({
    order: {
        type: Object,
        required: true,
    },
});

const formatPrice = (price) => {
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 0,
    }).format(price);
};
</script>

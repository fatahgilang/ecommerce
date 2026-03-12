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
                        <div class="w-24 h-24 bg-gray-200 rounded-lg overflow-hidden flex items-center justify-center flex-shrink-0">
                            <img
                                v-if="item.image"
                                :src="item.image"
                                :alt="item.product_name"
                                class="w-full h-full object-cover"
                            />
                            <svg v-else class="h-10 w-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        </div>

                        <!-- Details -->
                        <div class="flex-1 min-w-0">
                            <h3 class="font-semibold text-gray-900 mb-1">
                                {{ item.product_name }}
                            </h3>
                            
                            <!-- Price with discount info -->
                            <div class="mb-2">
                                <!-- Show original price (strikethrough) if there's discount -->
                                <div v-if="item.has_discount && item.original_price && item.original_price > item.product_price">
                                    <p class="text-xs text-gray-500 line-through mb-1">
                                        {{ formatPrice(item.original_price) }}
                                    </p>
                                    <p class="text-blue-600 font-bold">
                                        {{ formatPrice(item.product_price) }}
                                    </p>
                                    <span class="text-xs text-green-600 font-medium">
                                        Hemat {{ formatPrice(item.original_price - item.product_price) }}
                                    </span>
                                </div>
                                <!-- Show normal price if no discount -->
                                <div v-else>
                                    <p class="text-blue-600 font-bold">
                                        {{ formatPrice(item.product_price) }}
                                    </p>
                                </div>
                            </div>

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

                        <!-- Discount Code Section -->
                        <div class="mb-6 p-4 bg-gray-50 rounded-lg">
                            <h3 class="font-semibold text-gray-900 mb-3">Kode Diskon</h3>
                            <div class="flex gap-2">
                                <input
                                    type="text"
                                    v-model="discountCode"
                                    placeholder="Masukkan kode diskon"
                                    class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                    :disabled="isValidatingDiscount"
                                />
                                <button
                                    @click="applyDiscount"
                                    :disabled="!discountCode.trim() || isValidatingDiscount"
                                    class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 disabled:opacity-50 disabled:cursor-not-allowed"
                                >
                                    <span v-if="!isValidatingDiscount">Terapkan</span>
                                    <span v-else>...</span>
                                </button>
                            </div>
                            
                            <!-- Applied Discount Display -->
                            <div v-if="appliedDiscount" class="mt-3 p-3 bg-green-50 border border-green-200 rounded-lg">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="text-sm font-medium text-green-800">{{ appliedDiscount.name }}</p>
                                        <p class="text-xs text-green-600">
                                            Diskon {{ appliedDiscount.type === 'percentage' ? appliedDiscount.value + '%' : formatPrice(appliedDiscount.value) }}
                                        </p>
                                    </div>
                                    <button
                                        @click="removeDiscount"
                                        class="text-green-600 hover:text-green-800 text-sm font-medium"
                                    >
                                        Hapus
                                    </button>
                                </div>
                            </div>
                            
                            <!-- Discount Error -->
                            <div v-if="discountError" class="mt-3 p-3 bg-red-50 border border-red-200 rounded-lg">
                                <p class="text-sm text-red-600">{{ discountError }}</p>
                            </div>
                        </div>

                        <div class="space-y-3 mb-6">
                            <div class="flex justify-between text-gray-600">
                                <span>Subtotal ({{ totalItems }} item)</span>
                                <span>{{ formatPrice(subtotal) }}</span>
                            </div>
                            <div v-if="appliedDiscount && discountAmount > 0" class="flex justify-between text-green-600">
                                <span>Diskon ({{ appliedDiscount.name }})</span>
                                <span>-{{ formatPrice(discountAmount) }}</span>
                            </div>
                            <!-- Debug info (only in development) -->
                            <div v-if="isDevelopment && appliedDiscount" class="text-xs text-gray-500 border-t pt-2">
                                <div>Debug Info:</div>
                                <div>Subtotal: {{ subtotal }}</div>
                                <div>Discount Type: {{ appliedDiscount.type }}</div>
                                <div>Discount Value: {{ appliedDiscount.value }}</div>
                                <div>API Discount Amount: {{ appliedDiscount.discount_amount }}</div>
                                <div>Computed Discount Amount: {{ discountAmount }}</div>
                                <div>Final Total: {{ finalTotal }}</div>
                            </div>
                            <div class="border-t pt-3 flex justify-between text-lg font-bold text-gray-900">
                                <span>Total</span>
                                <span>{{ formatPrice(finalTotal) }}</span>
                            </div>
                        </div>

                        <!-- Payment Methods -->
                        <div class="mb-6">
                            <h3 class="font-semibold text-gray-900 mb-3">Pilih Metode Pembayaran</h3>
                            
                            <!-- Payment Options -->
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
                            <ol v-if="selectedPayment === 'cash'" class="text-xs text-yellow-800 space-y-1 list-decimal list-inside">
                                <li>Datang ke kasir dengan produk yang dipilih</li>
                                <li>Bayar sesuai total pembayaran</li>
                                <li>Terima struk pembayaran</li>
                                <li>Pesanan selesai</li>
                            </ol>
                            <ol v-else class="text-xs text-yellow-800 space-y-1 list-decimal list-inside">
                                <li>Transfer sesuai total pembayaran</li>
                                <li>Simpan bukti transfer</li>
                                <li>Pesanan akan diproses setelah pembayaran dikonfirmasi</li>
                                <li>Hubungi kasir untuk konfirmasi pembayaran</li>
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
        
        <!-- Debug Component (only in development) -->
        <CartDebug v-if="isDevelopment" />
    </AppLayout>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import { Link, router } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import CartDebug from '@/Components/CartDebug.vue';
import axios from 'axios';
import thermalPrinter from '@/utils/thermalPrinter';

const cartItems = ref([]);
const isProcessing = ref(false);
const selectedPayment = ref('');
const isDevelopment = ref(import.meta.env.DEV || false);

// Discount state
const discountCode = ref('');
const appliedDiscount = ref(null);
const discountError = ref('');
const isValidatingDiscount = ref(false);

// Payment methods
const paymentMethods = ref([
    {
        id: 'cash',
        name: 'Tunai',
        type: 'Pembayaran Langsung',
        account: 'Bayar di kasir',
        holder: 'Toko Makmur Jaya'
    },
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
    
    // Validate and fix cart items that might have incorrect discount data
    const validatedCart = cart.map(item => {
        // If item doesn't have original_price or has incorrect discount data, fix it
        if (item.has_discount && (!item.original_price || item.original_price <= item.product_price)) {
            // This might be an old cart item, try to fix it
            // Assume the current product_price is the discounted price
            // and estimate the original price (this is a fallback)
            if (!item.original_price) {
                item.original_price = item.product_price * 1.2; // Estimate 20% discount
            }
        }
        
        // Ensure has_discount is boolean
        item.has_discount = Boolean(item.has_discount);
        
        return item;
    });
    
    cartItems.value = validatedCart;
    
    // Save the validated cart back to localStorage
    if (JSON.stringify(cart) !== JSON.stringify(validatedCart)) {
        localStorage.setItem('cart', JSON.stringify(validatedCart));
    }
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
        
        // Revalidate discount if applied
        if (appliedDiscount.value) {
            validateCurrentDiscount();
        }
    }
};

const applyDiscount = async () => {
    if (!discountCode.value.trim()) return;
    
    isValidatingDiscount.value = true;
    discountError.value = '';
    
    try {
        console.log('Validating discount with subtotal:', subtotal.value);
        
        const response = await axios.post('/api/v1/discounts/validate', {
            code: discountCode.value.trim(),
            amount: subtotal.value
        });
        
        console.log('Discount validation response:', response.data);
        
        if (response.data.valid) {
            appliedDiscount.value = response.data.discount;
            discountError.value = '';
            console.log('Discount applied successfully:', appliedDiscount.value);
        }
    } catch (error) {
        console.error('Discount validation error:', error);
        
        if (error.response && error.response.data && error.response.data.message) {
            discountError.value = error.response.data.message;
        } else {
            discountError.value = 'Kode diskon tidak valid atau tidak dapat digunakan';
        }
        
        appliedDiscount.value = null;
    } finally {
        isValidatingDiscount.value = false;
    }
};

const removeDiscount = () => {
    appliedDiscount.value = null;
    discountCode.value = '';
    discountError.value = '';
};

const validateCurrentDiscount = async () => {
    if (!appliedDiscount.value) return;
    
    try {
        const response = await axios.post('/api/v1/discounts/validate', {
            code: discountCode.value.trim(),
            amount: subtotal.value
        });
        
        if (!response.data.valid) {
            // Discount is no longer valid
            removeDiscount();
            discountError.value = 'Diskon tidak lagi berlaku untuk total pembelian saat ini';
        } else {
            // Update discount amount
            appliedDiscount.value = response.data.discount;
        }
    } catch (error) {
        // Discount is no longer valid
        removeDiscount();
        discountError.value = 'Diskon tidak lagi berlaku';
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

const discountAmount = computed(() => {
    if (!appliedDiscount.value) return 0;
    
    const discount = appliedDiscount.value;
    
    // Debug: log the discount data
    console.log('Applied discount:', discount);
    console.log('Subtotal:', subtotal.value);
    
    // Use the discount_amount from API response directly
    // The API already calculated the correct discount amount
    if (discount.discount_amount !== undefined) {
        console.log('Using API calculated discount:', discount.discount_amount);
        return parseFloat(discount.discount_amount);
    }
    
    // Fallback calculation (should not be needed)
    let amount = 0;
    
    if (discount.type === 'percentage') {
        amount = subtotal.value * (parseFloat(discount.value) / 100);
    } else {
        amount = parseFloat(discount.value);
    }
    
    // Apply maximum discount limit if set
    if (discount.maximum_discount && amount > parseFloat(discount.maximum_discount)) {
        amount = parseFloat(discount.maximum_discount);
    }
    
    // Don't exceed subtotal
    if (amount > subtotal.value) {
        amount = subtotal.value;
    }
    
    console.log('Fallback calculated discount:', amount);
    return amount;
});

const finalTotal = computed(() => {
    return Math.max(0, subtotal.value - discountAmount.value);
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
            discount_code: appliedDiscount.value ? discountCode.value.trim() : null,
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

            // Prepare order data for printing
            const orderData = response.data.data;
            
            // Format data for thermal printer
            const printData = {
                order_id: orderData.orders[0].order_id,
                date: new Date().toLocaleString('id-ID'),
                cashier: 'Frontend',
                payment_method: selectedPayment.value,
                items: orderData.orders.map(item => ({
                    product_name: item.product_name,
                    quantity: item.quantity,
                    price: item.price,
                    total: item.total
                })),
                subtotal: orderData.subtotal || subtotal.value,
                discount_code: orderData.discount_code || null,
                discount_amount: orderData.discount_amount || 0,
                total: orderData.total
            };

            // Show success message with print option
            const orderItems = orderData.orders.map(item => 
                `- ${item.product_name} x${item.quantity}: ${formatPrice(item.total)}`
            ).join('\n');

            let alertMessage = `✅ Pesanan Berhasil Dibuat!\n\n` +
                `ID Pesanan: #${orderData.orders[0].order_id}\n\n` +
                `Produk:\n${orderItems}\n\n` +
                `Subtotal: ${formatPrice(orderData.subtotal || subtotal.value)}\n`;
                
            if (orderData.discount_amount && orderData.discount_amount > 0) {
                alertMessage += `Diskon: -${formatPrice(orderData.discount_amount)}\n`;
            }
            
            alertMessage += `Total Pembayaran: ${formatPrice(orderData.total)}\n\n`;

            if (selectedPayment.value === 'cash') {
                alertMessage += `💰 Metode Pembayaran: Tunai\n\n` +
                    `Silakan datang ke kasir untuk menyelesaikan pembayaran.\n\n` +
                    `Terima kasih telah berbelanja!`;
            } else {
                alertMessage += `💳 Metode Pembayaran: ${paymentMethod.name}\n` +
                    `📱 ${paymentMethod.account}\n` +
                    `👤 a.n. ${paymentMethod.holder}\n\n` +
                    `Silakan transfer sesuai nominal dan simpan bukti transfer.\n` +
                    `Hubungi kasir untuk konfirmasi pembayaran.\n\n` +
                    `Terima kasih telah berbelanja!`;
            }

            alert(alertMessage);

            // Ask if user wants to print receipt
            if (confirm('🖨️ Cetak struk sekarang?')) {
                try {
                    await thermalPrinter.printWithFallback(printData);
                    console.log('Receipt printed successfully');
                } catch (error) {
                    console.error('Print failed:', error);
                    alert('⚠️ Gagal mencetak struk. Anda bisa mencetak ulang dari menu pesanan.');
                }
            }

            // Reset selection and discount
            selectedPayment.value = '';
            removeDiscount();

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

<template>
    <div class="bg-white rounded-lg shadow-sm p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">⚙️ Pengaturan Printer</h3>
        
        <!-- Status -->
        <div class="mb-4">
            <div class="flex items-center gap-2 mb-2">
                <div 
                    class="w-3 h-3 rounded-full"
                    :class="isConnected ? 'bg-green-500' : 'bg-red-500'"
                ></div>
                <span class="text-sm text-gray-600">
                    {{ isConnected ? 'Terhubung' : 'Tidak Terhubung' }}
                </span>
            </div>
            
            <p class="text-xs text-gray-500">
                {{ statusMessage }}
            </p>
        </div>

        <!-- Printer Selection -->
        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-2">
                Pilih Printer Thermal
            </label>
            <select
                v-model="selectedPrinter"
                @change="savePrinter"
                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                :disabled="isLoading"
            >
                <option value="">-- Pilih Printer --</option>
                <option v-for="printer in printers" :key="printer" :value="printer">
                    {{ printer }}
                </option>
            </select>
        </div>

        <!-- Actions -->
        <div class="flex gap-2">
            <button
                @click="refreshPrinters"
                :disabled="isLoading"
                class="flex-1 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 disabled:opacity-50 disabled:cursor-not-allowed"
            >
                <span v-if="!isLoading">🔄 Refresh Printer</span>
                <span v-else>Loading...</span>
            </button>
            
            <button
                @click="testPrint"
                :disabled="!selectedPrinter || isLoading"
                class="flex-1 px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 disabled:opacity-50 disabled:cursor-not-allowed"
            >
                🖨️ Test Print
            </button>
        </div>

        <!-- Info -->
        <div class="mt-4 p-3 bg-blue-50 border border-blue-200 rounded-lg">
            <p class="text-xs text-blue-800">
                <strong>💡 Catatan:</strong> Untuk menggunakan printer thermal, install QZ Tray terlebih dahulu.
                <a href="https://qz.io/download/" target="_blank" class="underline">Download QZ Tray</a>
            </p>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import thermalPrinter from '@/utils/thermalPrinter';

const printers = ref([]);
const selectedPrinter = ref('');
const isConnected = ref(false);
const isLoading = ref(false);
const statusMessage = ref('Belum terhubung ke QZ Tray');

const refreshPrinters = async () => {
    isLoading.value = true;
    try {
        await thermalPrinter.connect();
        printers.value = await thermalPrinter.getPrinters();
        isConnected.value = true;
        statusMessage.value = `Ditemukan ${printers.value.length} printer`;
        
        // Load saved printer
        const saved = thermalPrinter.getPrinter();
        if (saved && printers.value.includes(saved)) {
            selectedPrinter.value = saved;
        }
    } catch (error) {
        console.error('Failed to get printers:', error);
        statusMessage.value = error.message || 'Gagal terhubung ke QZ Tray';
        isConnected.value = false;
    } finally {
        isLoading.value = false;
    }
};

const savePrinter = () => {
    if (selectedPrinter.value) {
        thermalPrinter.setPrinter(selectedPrinter.value);
        statusMessage.value = `Printer "${selectedPrinter.value}" dipilih`;
    }
};

const testPrint = async () => {
    if (!selectedPrinter.value) return;
    
    isLoading.value = true;
    try {
        const testData = {
            order_id: 'TEST-001',
            date: new Date().toLocaleString('id-ID'),
            cashier: 'Test User',
            payment_method: 'cash',
            items: [
                {
                    product_name: 'Produk Test',
                    quantity: 1,
                    price: 10000,
                    total: 10000
                }
            ],
            subtotal: 10000,
            discount_amount: 0,
            total: 10000
        };
        
        await thermalPrinter.print(testData);
        alert('✅ Test print berhasil!');
    } catch (error) {
        console.error('Test print failed:', error);
        alert('❌ Test print gagal: ' + error.message);
    } finally {
        isLoading.value = false;
    }
};

onMounted(() => {
    refreshPrinters();
});
</script>

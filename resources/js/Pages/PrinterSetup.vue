<template>
    <AppLayout>
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-8">🖨️ Pengaturan Printer Thermal</h1>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Printer Settings -->
                <PrinterSettings />

                <!-- Instructions -->
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">📖 Panduan Instalasi</h3>
                    
                    <div class="space-y-4">
                        <div>
                            <h4 class="font-medium text-gray-900 mb-2">1. Install QZ Tray</h4>
                            <p class="text-sm text-gray-600 mb-2">
                                Download dan install QZ Tray dari website resmi:
                            </p>
                            <a 
                                href="https://qz.io/download/" 
                                target="_blank"
                                class="inline-block px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 text-sm"
                            >
                                Download QZ Tray
                            </a>
                        </div>

                        <div>
                            <h4 class="font-medium text-gray-900 mb-2">2. Jalankan QZ Tray</h4>
                            <p class="text-sm text-gray-600">
                                Setelah install, jalankan QZ Tray. Icon akan muncul di system tray.
                            </p>
                        </div>

                        <div>
                            <h4 class="font-medium text-gray-900 mb-2">3. Hubungkan Printer</h4>
                            <p class="text-sm text-gray-600">
                                Pastikan printer thermal sudah terhubung via USB atau Bluetooth.
                            </p>
                        </div>

                        <div>
                            <h4 class="font-medium text-gray-900 mb-2">4. Pilih Printer</h4>
                            <p class="text-sm text-gray-600">
                                Klik "Refresh Printer" dan pilih printer thermal dari dropdown.
                            </p>
                        </div>

                        <div>
                            <h4 class="font-medium text-gray-900 mb-2">5. Test Print</h4>
                            <p class="text-sm text-gray-600">
                                Klik "Test Print" untuk memastikan printer berfungsi dengan baik.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Supported Printers -->
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">✅ Printer yang Didukung</h3>
                    
                    <ul class="space-y-2 text-sm text-gray-600">
                        <li class="flex items-start gap-2">
                            <span class="text-green-600">✓</span>
                            <span>Printer thermal 58mm (ESC/POS)</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <span class="text-green-600">✓</span>
                            <span>Printer thermal 80mm (ESC/POS)</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <span class="text-green-600">✓</span>
                            <span>Epson TM series</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <span class="text-green-600">✓</span>
                            <span>Star Micronics</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <span class="text-green-600">✓</span>
                            <span>Bixolon</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <span class="text-green-600">✓</span>
                            <span>Printer Bluetooth thermal</span>
                        </li>
                    </ul>
                </div>

                <!-- Features -->
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">🎯 Fitur</h3>
                    
                    <ul class="space-y-2 text-sm text-gray-600">
                        <li class="flex items-start gap-2">
                            <span class="text-blue-600">•</span>
                            <span>Cetak struk otomatis setelah checkout</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <span class="text-blue-600">•</span>
                            <span>Silent printing (tanpa dialog print browser)</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <span class="text-blue-600">•</span>
                            <span>Format struk thermal 58mm</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <span class="text-blue-600">•</span>
                            <span>Fallback ke browser print jika QZ Tray tidak tersedia</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <span class="text-blue-600">•</span>
                            <span>Cetak ulang dari menu pesanan</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <span class="text-blue-600">•</span>
                            <span>Informasi lengkap: produk, diskon, total</span>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Back Button -->
            <div class="mt-8">
                <Link
                    href="/"
                    class="inline-block px-6 py-3 bg-gray-600 text-white rounded-lg hover:bg-gray-700"
                >
                    ← Kembali ke Beranda
                </Link>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import PrinterSettings from '@/Components/PrinterSettings.vue';
</script>

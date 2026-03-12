<x-filament::section>
    <x-slot name="heading">
        📈 Laporan Laba Rugi
    </x-slot>

    <x-slot name="description">
        Periode: {{ $reportData['period']['start'] }} s/d {{ $reportData['period']['end'] }}
    </x-slot>

    <div class="space-y-6">
        <!-- Revenue Section -->
        <div>
            <h3 class="text-lg font-semibold mb-3 text-green-700">Pendapatan</h3>
            <div class="bg-green-50 border border-green-200 rounded-lg p-4 space-y-2">
                <div class="flex justify-between">
                    <span class="text-gray-700">Pendapatan Kotor</span>
                    <span class="font-semibold">
                        Rp {{ number_format($reportData['revenue']['gross_revenue'], 0, ',', '.') }}
                    </span>
                </div>
                <div class="flex justify-between text-red-600">
                    <span>Diskon</span>
                    <span class="font-semibold">
                        (Rp {{ number_format($reportData['revenue']['discounts'], 0, ',', '.') }})
                    </span>
                </div>
                <div class="flex justify-between border-t pt-2 text-lg font-bold text-green-700">
                    <span>Pendapatan Bersih</span>
                    <span>
                        Rp {{ number_format($reportData['revenue']['net_revenue'], 0, ',', '.') }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Costs Section -->
        <div>
            <h3 class="text-lg font-semibold mb-3 text-red-700">Biaya</h3>
            <div class="bg-red-50 border border-red-200 rounded-lg p-4 space-y-2">
                <div class="flex justify-between">
                    <span class="text-gray-700">Harga Pokok Penjualan (COGS)</span>
                    <span class="font-semibold">
                        Rp {{ number_format($reportData['costs']['cogs'], 0, ',', '.') }}
                    </span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-700">Biaya Operasional</span>
                    <span class="font-semibold">
                        Rp {{ number_format($reportData['costs']['operating_expenses'], 0, ',', '.') }}
                    </span>
                </div>
                <div class="flex justify-between border-t pt-2 text-lg font-bold text-red-700">
                    <span>Total Biaya</span>
                    <span>
                        Rp {{ number_format($reportData['costs']['total_costs'], 0, ',', '.') }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Profit Section -->
        <div>
            <h3 class="text-lg font-semibold mb-3 text-blue-700">Laba</h3>
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 space-y-3">
                <div class="flex justify-between items-center">
                    <div>
                        <div class="font-semibold text-gray-700">Laba Kotor</div>
                        <div class="text-sm text-gray-500">
                            Margin: {{ $reportData['profit']['gross_profit_margin'] }}%
                        </div>
                    </div>
                    <span class="text-xl font-bold text-blue-700">
                        Rp {{ number_format($reportData['profit']['gross_profit'], 0, ',', '.') }}
                    </span>
                </div>
                
                <div class="border-t pt-3 flex justify-between items-center">
                    <div>
                        <div class="font-bold text-gray-900 text-lg">Laba Bersih</div>
                        <div class="text-sm text-gray-500">
                            Margin: {{ $reportData['profit']['net_profit_margin'] }}%
                        </div>
                    </div>
                    <span class="text-2xl font-bold text-blue-900">
                        Rp {{ number_format($reportData['profit']['net_profit'], 0, ',', '.') }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Summary -->
        <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
            <h4 class="font-semibold mb-2">📊 Ringkasan</h4>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                <div>
                    <div class="text-gray-600">Pendapatan Bersih</div>
                    <div class="font-semibold">
                        Rp {{ number_format($reportData['revenue']['net_revenue'], 0, ',', '.') }}
                    </div>
                </div>
                <div>
                    <div class="text-gray-600">Total Biaya</div>
                    <div class="font-semibold">
                        Rp {{ number_format($reportData['costs']['total_costs'], 0, ',', '.') }}
                    </div>
                </div>
                <div>
                    <div class="text-gray-600">Laba Bersih</div>
                    <div class="font-semibold text-blue-600">
                        Rp {{ number_format($reportData['profit']['net_profit'], 0, ',', '.') }}
                    </div>
                </div>
                <div>
                    <div class="text-gray-600">Margin Laba Bersih</div>
                    <div class="font-semibold text-blue-600">
                        {{ $reportData['profit']['net_profit_margin'] }}%
                    </div>
                </div>
            </div>
        </div>

        <!-- Note -->
        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
            <p class="text-sm text-yellow-800">
                <strong>💡 Catatan:</strong> COGS dihitung sebagai 60% dari harga jual (estimasi). 
                Untuk akurasi lebih tinggi, input data COGS aktual untuk setiap produk.
            </p>
        </div>
    </div>
</x-filament::section>

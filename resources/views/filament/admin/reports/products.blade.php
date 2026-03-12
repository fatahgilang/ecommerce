<x-filament::section>
    <x-slot name="heading">
        📦 Laporan Produk
    </x-slot>

    <x-slot name="description">
        Periode: {{ $reportData['period']['start'] }} s/d {{ $reportData['period']['end'] }}
    </x-slot>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
            <div class="text-sm text-blue-600 font-medium">Total Produk Terjual</div>
            <div class="text-2xl font-bold text-blue-900">
                {{ number_format($reportData['summary']['total_products_sold']) }} unit
            </div>
        </div>

        <div class="bg-green-50 border border-green-200 rounded-lg p-4">
            <div class="text-sm text-green-600 font-medium">Total Pendapatan</div>
            <div class="text-2xl font-bold text-green-900">
                Rp {{ number_format($reportData['summary']['total_revenue'], 0, ',', '.') }}
            </div>
        </div>

        <div class="bg-purple-50 border border-purple-200 rounded-lg p-4">
            <div class="text-sm text-purple-600 font-medium">Produk Unik</div>
            <div class="text-2xl font-bold text-purple-900">
                {{ $reportData['summary']['unique_products'] }}
            </div>
        </div>

        <div class="bg-red-50 border border-red-200 rounded-lg p-4">
            <div class="text-sm text-red-600 font-medium">Stok Habis</div>
            <div class="text-2xl font-bold text-red-900">
                {{ $reportData['summary']['out_of_stock_count'] }}
            </div>
        </div>
    </div>

    <!-- Top Products -->
    <div class="mb-6">
        <h3 class="text-lg font-semibold mb-3">🏆 Top 10 Produk Terlaris</h3>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="text-left py-3 px-4">Rank</th>
                        <th class="text-left py-3 px-4">Produk</th>
                        <th class="text-left py-3 px-4">Kategori</th>
                        <th class="text-right py-3 px-4">Terjual</th>
                        <th class="text-right py-3 px-4">Pendapatan</th>
                        <th class="text-right py-3 px-4">Stok Saat Ini</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($reportData['top_products'] as $index => $product)
                        <tr class="border-b hover:bg-gray-50">
                            <td class="py-3 px-4 font-bold">{{ $index + 1 }}</td>
                            <td class="py-3 px-4 font-medium">{{ $product['product_name'] }}</td>
                            <td class="py-3 px-4">{{ $product['category'] }}</td>
                            <td class="py-3 px-4 text-right">{{ $product['total_sold'] }} unit</td>
                            <td class="py-3 px-4 text-right font-semibold">
                                Rp {{ number_format($product['total_revenue'], 0, ',', '.') }}
                            </td>
                            <td class="py-3 px-4 text-right">{{ $product['current_stock'] }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Low Stock Alert -->
    @if($reportData['low_stock']->count() > 0)
        <div>
            <h3 class="text-lg font-semibold mb-3">⚠️ Stok Menipis (≤10 unit)</h3>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="text-left py-3 px-4">Produk</th>
                            <th class="text-right py-3 px-4">Stok</th>
                            <th class="text-right py-3 px-4">Harga</th>
                            <th class="text-right py-3 px-4">Nilai Stok</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($reportData['low_stock'] as $product)
                            <tr class="border-b hover:bg-gray-50">
                                <td class="py-3 px-4 font-medium">{{ $product['product_name'] }}</td>
                                <td class="py-3 px-4 text-right text-red-600 font-bold">{{ $product['stock'] }}</td>
                                <td class="py-3 px-4 text-right">
                                    Rp {{ number_format($product['price'], 0, ',', '.') }}
                                </td>
                                <td class="py-3 px-4 text-right">
                                    Rp {{ number_format($product['stock_value'], 0, ',', '.') }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif
</x-filament::section>

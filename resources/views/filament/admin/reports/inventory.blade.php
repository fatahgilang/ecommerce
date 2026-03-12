<x-filament::section>
    <x-slot name="heading">
        📦 Laporan Inventory
    </x-slot>

    <x-slot name="description">
        Snapshot inventory saat ini
    </x-slot>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-5 gap-4 mb-6">
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
            <div class="text-sm text-blue-600 font-medium">Total Produk</div>
            <div class="text-2xl font-bold text-blue-900">
                {{ $reportData['summary']['total_products'] }}
            </div>
        </div>

        <div class="bg-green-50 border border-green-200 rounded-lg p-4">
            <div class="text-sm text-green-600 font-medium">Total Stok</div>
            <div class="text-2xl font-bold text-green-900">
                {{ number_format($reportData['summary']['total_stock_qty']) }}
            </div>
        </div>

        <div class="bg-purple-50 border border-purple-200 rounded-lg p-4">
            <div class="text-sm text-purple-600 font-medium">Nilai Stok</div>
            <div class="text-xl font-bold text-purple-900">
                Rp {{ number_format($reportData['summary']['total_stock_value'], 0, ',', '.') }}
            </div>
        </div>

        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
            <div class="text-sm text-yellow-600 font-medium">Stok Menipis</div>
            <div class="text-2xl font-bold text-yellow-900">
                {{ $reportData['summary']['low_stock'] }}
            </div>
        </div>

        <div class="bg-red-50 border border-red-200 rounded-lg p-4">
            <div class="text-sm text-red-600 font-medium">Stok Habis</div>
            <div class="text-2xl font-bold text-red-900">
                {{ $reportData['summary']['out_of_stock'] }}
            </div>
        </div>
    </div>

    <!-- By Category -->
    <div class="mb-6">
        <h3 class="text-lg font-semibold mb-3">Berdasarkan Kategori</h3>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="text-left py-3 px-4">Kategori</th>
                        <th class="text-right py-3 px-4">Jumlah Produk</th>
                        <th class="text-right py-3 px-4">Total Stok</th>
                        <th class="text-right py-3 px-4">Nilai Stok</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($reportData['by_category'] as $category)
                        <tr class="border-b hover:bg-gray-50">
                            <td class="py-3 px-4 font-medium">{{ $category['category'] }}</td>
                            <td class="py-3 px-4 text-right">{{ $category['product_count'] }}</td>
                            <td class="py-3 px-4 text-right">{{ number_format($category['total_stock']) }}</td>
                            <td class="py-3 px-4 text-right font-semibold">
                                Rp {{ number_format($category['stock_value'], 0, ',', '.') }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- All Products -->
    <div>
        <h3 class="text-lg font-semibold mb-3">Detail Produk</h3>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="text-left py-3 px-4">Produk</th>
                        <th class="text-left py-3 px-4">Kategori</th>
                        <th class="text-right py-3 px-4">Stok</th>
                        <th class="text-right py-3 px-4">Harga</th>
                        <th class="text-right py-3 px-4">Nilai Stok</th>
                        <th class="text-center py-3 px-4">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($reportData['products'] as $product)
                        <tr class="border-b hover:bg-gray-50">
                            <td class="py-3 px-4 font-medium">{{ $product['product_name'] }}</td>
                            <td class="py-3 px-4">{{ $product['category'] }}</td>
                            <td class="py-3 px-4 text-right">{{ $product['stock'] }}</td>
                            <td class="py-3 px-4 text-right">
                                Rp {{ number_format($product['price'], 0, ',', '.') }}
                            </td>
                            <td class="py-3 px-4 text-right font-semibold">
                                Rp {{ number_format($product['stock_value'], 0, ',', '.') }}
                            </td>
                            <td class="py-3 px-4 text-center">
                                @if($product['status'] === 'out_of_stock')
                                    <span class="px-2 py-1 bg-red-100 text-red-800 rounded-full text-xs font-medium">
                                        Habis
                                    </span>
                                @elseif($product['status'] === 'low_stock')
                                    <span class="px-2 py-1 bg-yellow-100 text-yellow-800 rounded-full text-xs font-medium">
                                        Menipis
                                    </span>
                                @else
                                    <span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-xs font-medium">
                                        Aman
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</x-filament::section>

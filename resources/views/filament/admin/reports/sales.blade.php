<x-filament::section>
    <x-slot name="heading">
        📊 Laporan Penjualan
    </x-slot>

    <x-slot name="description">
        Periode: {{ $reportData['period']['start'] }} s/d {{ $reportData['period']['end'] }} ({{ $reportData['period']['days'] }} hari)
    </x-slot>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
            <div class="text-sm text-blue-600 font-medium">Total Pendapatan</div>
            <div class="text-2xl font-bold text-blue-900">
                Rp {{ number_format($reportData['summary']['total_revenue'], 0, ',', '.') }}
            </div>
        </div>

        <div class="bg-green-50 border border-green-200 rounded-lg p-4">
            <div class="text-sm text-green-600 font-medium">Total Transaksi</div>
            <div class="text-2xl font-bold text-green-900">
                {{ number_format($reportData['summary']['total_transactions']) }}
            </div>
        </div>

        <div class="bg-purple-50 border border-purple-200 rounded-lg p-4">
            <div class="text-sm text-purple-600 font-medium">Rata-rata Transaksi</div>
            <div class="text-2xl font-bold text-purple-900">
                Rp {{ number_format($reportData['summary']['average_transaction'], 0, ',', '.') }}
            </div>
        </div>

        <div class="bg-orange-50 border border-orange-200 rounded-lg p-4">
            <div class="text-sm text-orange-600 font-medium">Rata-rata Harian</div>
            <div class="text-2xl font-bold text-orange-900">
                Rp {{ number_format($reportData['summary']['daily_average'], 0, ',', '.') }}
            </div>
        </div>
    </div>

    <!-- Payment Methods -->
    <div class="mb-6">
        <h3 class="text-lg font-semibold mb-3">Metode Pembayaran</h3>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="text-left py-3 px-4">Metode</th>
                        <th class="text-right py-3 px-4">Jumlah Transaksi</th>
                        <th class="text-right py-3 px-4">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($reportData['payment_methods'] as $method => $data)
                        <tr class="border-b">
                            <td class="py-3 px-4 font-medium">{{ strtoupper($method) }}</td>
                            <td class="py-3 px-4 text-right">{{ $data['count'] }}</td>
                            <td class="py-3 px-4 text-right font-semibold">
                                Rp {{ number_format($data['total'], 0, ',', '.') }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Chart Data -->
    <div>
        <h3 class="text-lg font-semibold mb-3">Grafik Penjualan</h3>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="text-left py-3 px-4">Periode</th>
                        <th class="text-right py-3 px-4">Transaksi</th>
                        <th class="text-right py-3 px-4">Pendapatan</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($reportData['chart_data'] as $data)
                        <tr class="border-b hover:bg-gray-50">
                            <td class="py-3 px-4">{{ $data['period'] }}</td>
                            <td class="py-3 px-4 text-right">{{ $data['transactions'] }}</td>
                            <td class="py-3 px-4 text-right font-semibold">
                                Rp {{ number_format($data['revenue'], 0, ',', '.') }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</x-filament::section>

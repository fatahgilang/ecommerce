<x-filament::section>
    <x-slot name="heading">
        👥 Laporan Kasir
    </x-slot>

    <x-slot name="description">
        Periode: {{ $reportData['period']['start'] }} s/d {{ $reportData['period']['end'] }}
    </x-slot>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
            <div class="text-sm text-blue-600 font-medium">Total Kasir</div>
            <div class="text-2xl font-bold text-blue-900">
                {{ $reportData['summary']['total_cashiers'] }}
            </div>
        </div>

        <div class="bg-green-50 border border-green-200 rounded-lg p-4">
            <div class="text-sm text-green-600 font-medium">Total Transaksi</div>
            <div class="text-2xl font-bold text-green-900">
                {{ number_format($reportData['summary']['total_transactions']) }}
            </div>
        </div>

        <div class="bg-purple-50 border border-purple-200 rounded-lg p-4">
            <div class="text-sm text-purple-600 font-medium">Total Pendapatan</div>
            <div class="text-2xl font-bold text-purple-900">
                Rp {{ number_format($reportData['summary']['total_revenue'], 0, ',', '.') }}
            </div>
        </div>
    </div>

    <!-- Cashier Performance -->
    <div>
        <h3 class="text-lg font-semibold mb-3">Performa Kasir</h3>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="text-left py-3 px-4">Rank</th>
                        <th class="text-left py-3 px-4">Nama Kasir</th>
                        <th class="text-right py-3 px-4">Transaksi</th>
                        <th class="text-right py-3 px-4">Total Pendapatan</th>
                        <th class="text-right py-3 px-4">Rata-rata Transaksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($reportData['cashiers'] as $index => $cashier)
                        <tr class="border-b hover:bg-gray-50">
                            <td class="py-3 px-4 font-bold">{{ $index + 1 }}</td>
                            <td class="py-3 px-4 font-medium">{{ $cashier['cashier_name'] }}</td>
                            <td class="py-3 px-4 text-right">{{ $cashier['transaction_count'] }}</td>
                            <td class="py-3 px-4 text-right font-semibold text-green-600">
                                Rp {{ number_format($cashier['total_revenue'], 0, ',', '.') }}
                            </td>
                            <td class="py-3 px-4 text-right">
                                Rp {{ number_format($cashier['average_transaction'], 0, ',', '.') }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</x-filament::section>

<x-filament::section>
    <x-slot name="heading">
        💰 Laporan Kas
    </x-slot>

    <x-slot name="description">
        Periode: {{ $reportData['period']['start'] }} s/d {{ $reportData['period']['end'] }}
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
            <div class="text-sm text-green-600 font-medium">Tunai</div>
            <div class="text-2xl font-bold text-green-900">
                Rp {{ number_format($reportData['summary']['cash_revenue'], 0, ',', '.') }}
            </div>
        </div>

        <div class="bg-purple-50 border border-purple-200 rounded-lg p-4">
            <div class="text-sm text-purple-600 font-medium">Non-Tunai</div>
            <div class="text-2xl font-bold text-purple-900">
                Rp {{ number_format($reportData['summary']['non_cash_revenue'], 0, ',', '.') }}
            </div>
        </div>

        <div class="bg-orange-50 border border-orange-200 rounded-lg p-4">
            <div class="text-sm text-orange-600 font-medium">Total Transaksi</div>
            <div class="text-2xl font-bold text-orange-900">
                {{ number_format($reportData['summary']['total_transactions']) }}
            </div>
        </div>
    </div>

    <!-- By Payment Method -->
    <div class="mb-6">
        <h3 class="text-lg font-semibold mb-3">Berdasarkan Metode Pembayaran</h3>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="text-left py-3 px-4">Metode</th>
                        <th class="text-right py-3 px-4">Jumlah</th>
                        <th class="text-right py-3 px-4">Total</th>
                        <th class="text-right py-3 px-4">Persentase</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($reportData['by_payment_method'] as $method)
                        <tr class="border-b hover:bg-gray-50">
                            <td class="py-3 px-4 font-medium">{{ strtoupper($method['method']) }}</td>
                            <td class="py-3 px-4 text-right">{{ $method['count'] }}</td>
                            <td class="py-3 px-4 text-right font-semibold">
                                Rp {{ number_format($method['total'], 0, ',', '.') }}
                            </td>
                            <td class="py-3 px-4 text-right">
                                <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded-full text-xs font-medium">
                                    {{ $method['percentage'] }}%
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Daily Cash Flow -->
    <div>
        <h3 class="text-lg font-semibold mb-3">Arus Kas Harian</h3>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="text-left py-3 px-4">Tanggal</th>
                        <th class="text-right py-3 px-4">Transaksi</th>
                        <th class="text-right py-3 px-4">Tunai</th>
                        <th class="text-right py-3 px-4">Non-Tunai</th>
                        <th class="text-right py-3 px-4">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($reportData['daily_cash_flow'] as $day)
                        <tr class="border-b hover:bg-gray-50">
                            <td class="py-3 px-4">{{ $day['date'] }}</td>
                            <td class="py-3 px-4 text-right">{{ $day['transactions'] }}</td>
                            <td class="py-3 px-4 text-right text-green-600">
                                Rp {{ number_format($day['cash'], 0, ',', '.') }}
                            </td>
                            <td class="py-3 px-4 text-right text-purple-600">
                                Rp {{ number_format($day['non_cash'], 0, ',', '.') }}
                            </td>
                            <td class="py-3 px-4 text-right font-semibold">
                                Rp {{ number_format($day['revenue'], 0, ',', '.') }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</x-filament::section>

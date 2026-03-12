<x-filament-widgets::widget>
    <x-filament::section>
        <x-slot name="heading">
            <div class="flex items-center gap-2">
                <span class="text-2xl">📊</span>
                <span>Prediksi Stok & Rekomendasi Order</span>
            </div>
        </x-slot>

        <x-slot name="description">
            Prediksi berdasarkan pola penjualan 30 hari terakhir
        </x-slot>

        <div class="overflow-x-auto">
            @php
                $predictions = $this->getPredictions();
            @endphp

            @if(count($predictions) > 0)
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b">
                            <th class="text-left py-3 px-2">Status</th>
                            <th class="text-left py-3 px-2">Produk</th>
                            <th class="text-right py-3 px-2">Stok</th>
                            <th class="text-right py-3 px-2">Terjual/Hari</th>
                            <th class="text-right py-3 px-2">Habis Dalam</th>
                            <th class="text-right py-3 px-2">Rekomendasi Order</th>
                            <th class="text-left py-3 px-2">Trend</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($predictions as $prediction)
                            <tr class="border-b hover:bg-gray-50">
                                <td class="py-3 px-2">
                                    @if($prediction['urgency'] === 'critical')
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                            🚨 Kritis
                                        </span>
                                    @elseif($prediction['urgency'] === 'high')
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-orange-100 text-orange-800">
                                            ⚡ Tinggi
                                        </span>
                                    @elseif($prediction['urgency'] === 'medium')
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                            ⚠️ Sedang
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            ✅ Aman
                                        </span>
                                    @endif
                                </td>
                                <td class="py-3 px-2 font-medium">
                                    {{ $prediction['product_name'] }}
                                </td>
                                <td class="py-3 px-2 text-right">
                                    {{ number_format($prediction['current_stock']) }}
                                </td>
                                <td class="py-3 px-2 text-right">
                                    {{ $prediction['daily_average'] }}
                                </td>
                                <td class="py-3 px-2 text-right font-semibold
                                    @if($prediction['urgency'] === 'critical') text-red-600
                                    @elseif($prediction['urgency'] === 'high') text-orange-600
                                    @elseif($prediction['urgency'] === 'medium') text-yellow-600
                                    @else text-green-600
                                    @endif
                                ">
                                    {{ $prediction['days_until_empty'] }} hari
                                </td>
                                <td class="py-3 px-2 text-right font-semibold text-blue-600">
                                    {{ number_format($prediction['reorder_quantity']) }} unit
                                </td>
                                <td class="py-3 px-2">
                                    @if($prediction['sales_trend'] === 'increasing')
                                        <span class="text-green-600">📈 Naik</span>
                                    @elseif($prediction['sales_trend'] === 'decreasing')
                                        <span class="text-red-600">📉 Turun</span>
                                    @else
                                        <span class="text-gray-600">➡️ Stabil</span>
                                    @endif
                                </td>
                            </tr>
                            <tr class="bg-gray-50">
                                <td colspan="7" class="py-2 px-2">
                                    <div class="text-xs text-gray-700">
                                        💡 <strong>Rekomendasi:</strong> {{ $prediction['recommendation'] }}
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="mt-4 p-3 bg-blue-50 border border-blue-200 rounded-lg">
                    <p class="text-xs text-blue-800">
                        <strong>💡 Catatan:</strong> Prediksi berdasarkan pola penjualan 30 hari terakhir. 
                        Pertimbangkan faktor eksternal seperti musim, promosi, dan kondisi pasar saat melakukan order.
                    </p>
                </div>
            @else
                <div class="text-center py-8 text-gray-500">
                    <p>Belum ada data prediksi yang tersedia.</p>
                    <p class="text-sm mt-2">Prediksi akan muncul setelah ada riwayat penjualan produk.</p>
                </div>
            @endif
        </div>
    </x-filament::section>
</x-filament-widgets::widget>

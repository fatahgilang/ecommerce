<x-filament-widgets::widget>
    <x-filament::section>
        <x-slot name="heading">
            <div class="flex items-center gap-2">
                <span class="text-2xl">🤖</span>
                <span>AI Insights & Rekomendasi</span>
            </div>
        </x-slot>

        <x-slot name="description">
            Analisis cerdas berdasarkan data penjualan dan stok Anda
        </x-slot>

        <div class="space-y-4">
            @forelse($this->getInsights() as $insight)
                <div class="rounded-lg border p-4 
                    @if($insight['type'] === 'critical') border-red-200 bg-red-50
                    @elseif($insight['type'] === 'warning') border-yellow-200 bg-yellow-50
                    @elseif($insight['type'] === 'success') border-green-200 bg-green-50
                    @else border-blue-200 bg-blue-50
                    @endif
                ">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <div class="flex items-center gap-2 mb-2">
                                <span class="text-2xl">{{ $insight['icon'] }}</span>
                                <h3 class="text-lg font-semibold
                                    @if($insight['type'] === 'critical') text-red-900
                                    @elseif($insight['type'] === 'warning') text-yellow-900
                                    @elseif($insight['type'] === 'success') text-green-900
                                    @else text-blue-900
                                    @endif
                                ">
                                    {{ $insight['title'] }}
                                </h3>
                            </div>
                            <p class="text-sm
                                @if($insight['type'] === 'critical') text-red-800
                                @elseif($insight['type'] === 'warning') text-yellow-800
                                @elseif($insight['type'] === 'success') text-green-800
                                @else text-blue-800
                                @endif
                            ">
                                {{ $insight['message'] }}
                            </p>

                            @if(isset($insight['data']) && is_array($insight['data']) && count($insight['data']) > 0)
                                <div class="mt-3 space-y-2">
                                    @foreach(array_slice($insight['data'], 0, 3) as $item)
                                        <div class="text-xs
                                            @if($insight['type'] === 'critical') text-red-700
                                            @elseif($insight['type'] === 'warning') text-yellow-700
                                            @elseif($insight['type'] === 'success') text-green-700
                                            @else text-blue-700
                                            @endif
                                        ">
                                            @if(isset($item['product_name']))
                                                • {{ $item['product_name'] }}
                                                @if(isset($item['total_sold']))
                                                    - Terjual: {{ $item['total_sold'] }} unit
                                                @endif
                                                @if(isset($item['days_until_empty']))
                                                    - Habis dalam: {{ $item['days_until_empty'] }} hari
                                                @endif
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center py-8 text-gray-500">
                    <p>Belum ada insight yang tersedia.</p>
                    <p class="text-sm mt-2">Data akan muncul setelah ada transaksi penjualan.</p>
                </div>
            @endforelse
        </div>
    </x-filament::section>
</x-filament-widgets::widget>

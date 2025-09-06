<x-filament-widgets::widget>
    <x-filament::section>
        <x-slot name="heading">
            Bugünkü Vardiyam
        </x-slot>
        
        <div class="p-4">
            @if($shift)
                <div class="space-y-3">
                    <div class="flex items-center space-x-2">
                        <x-heroicon-o-clock class="w-5 h-5 text-gray-500" />
                        <span class="font-medium">Vardiya Saati:</span>
                        <span>{{ $shift->start_time }} - {{ $shift->end_time ?? 'Belirsiz' }}</span>
                    </div>
                    
                    @if($location)
                        <div class="flex items-center space-x-2">
                            <x-heroicon-o-map-pin class="w-5 h-5 text-gray-500" />
                            <span class="font-medium">Lokasyon:</span>
                            <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded-full text-sm">
                                {{ $location->name }}
                            </span>
                        </div>
                        
                        <div class="text-sm text-gray-600">
                            <strong>Adres:</strong> {{ $location->address ?? 'Belirtilmemiş' }}
                        </div>
                    @endif
                    
                    <div class="flex items-center space-x-2">
                        <x-heroicon-o-calendar class="w-5 h-5 text-gray-500" />
                        <span class="font-medium">Tarih:</span>
                        <span>{{ $shift->work_date->format('d.m.Y') }}</span>
                    </div>
                </div>
            @else
                <div class="text-center py-8 text-gray-500">
                    <x-heroicon-o-calendar class="w-12 h-12 mx-auto mb-2" />
                    <p>Bugün için atanmış vardiya yok</p>
                </div>
            @endif
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
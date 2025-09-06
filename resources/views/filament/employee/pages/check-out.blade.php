<x-filament-panels::page>
    @push('styles')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    @endpush

    @if($activeCheckin)
        <!-- Check-out Formu -->
        <form wire:submit="checkOut">
            {{ $this->form }}
        </form>
        
        <!-- Submit Button -->
        <div class="mt-3 flex justify-center">
            <button 
                wire:click="checkOut"
                class="bg-orange-600 hover:bg-orange-700 text-white font-medium py-2 px-4 rounded-lg shadow-lg hover:shadow-xl transition-all duration-200 text-sm border-none cursor-pointer flex items-center space-x-2"
            >
                <span><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.2" stroke="currentColor" class="size-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12c0 1.268-.63 2.39-1.593 3.068a3.745 3.745 0 0 1-1.043 3.296 3.745 3.745 0 0 1-3.296 1.043A3.745 3.745 0 0 1 12 21c-1.268 0-2.39-.63-3.068-1.593a3.746 3.746 0 0 1-3.296-1.043 3.745 3.745 0 0 1-1.043-3.296A3.745 3.745 0 0 1 3 12c0-1.268.63-2.39 1.593-3.068a3.745 3.745 0 0 1 1.043-3.296 3.746 3.746 0 0 1 3.296-1.043A3.746 3.746 0 0 1 12 3c1.268 0 2.39.63 3.068 1.593a3.746 3.746 0 0 1 3.296 1.043 3.746 3.746 0 0 1 1.043 3.296A3.745 3.745 0 0 1 21 12Z" />
                </svg></span>
                <span>Mesai Bitir (Check-Out)</span>
            </button>
        </div>
    @else
        <!-- Aktif Check-in Yok Ekranı -->
        <div class="bg-white rounded-xl shadow-lg p-8 text-center max-w-md mx-auto">
            <!-- Uyarı İkonu -->
            <div class="mb-6">
                <div class="w-20 h-20 mx-auto bg-yellow-100 rounded-full flex items-center justify-center">
                    <svg class="w-10 h-10 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.998-.833-2.768 0L3.046 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                    </svg>
                </div>
            </div>
            
            <!-- Uyarı Mesajı -->
            <h2 class="text-2xl font-bold text-gray-900 mb-4">Aktif Check-in Bulunamadı</h2>
            <p class="text-gray-600 mb-6">Check-out yapabilmek için önce check-in yapmış olmanız gerekiyor.</p>
            
            <!-- Check-in Butonu -->
            <a 
                href="{{ \App\Filament\Employee\Pages\CheckInPage::getUrl() }}"
                class="inline-block bg-blue-600 hover:bg-blue-700 text-white font-medium py-3 px-6 rounded-lg transition-all duration-200 text-decoration-none"
            >
                📍 Check-in Yap
            </a>
        </div>
    @endif

    @push('scripts')
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    @endpush
</x-filament-panels::page>
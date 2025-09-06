<x-filament-panels::page>
    @push('styles')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    @endpush

    @if($alreadyCheckedInToday)
        <!-- Zaten Check-in Yapılmış Ekranı -->
        <div class="bg-white rounded-xl items-center container mx-auto p-4 sm:p-6 lg:p-8 text-center max-w-sm sm:max-w-md lg:max-w-2xl xl:max-w-3xl mx-auto">
            <!-- Bilgi İkonu -->
            <div class="mb-6">
                <div class="w-20 h-20 mx-auto bg-blue-100 rounded-full flex items-center justify-center">
                    <svg class="w-10 h-10 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
            
            <!-- Bilgi Mesajı -->
            <h2 class="text-lg sm:text-xl lg:text-2xl font-bold text-gray-900 mb-3 sm:mb-4">Zaten Check-in Yaptınız! ℹ️</h2>
            <p class="text-sm sm:text-base text-gray-600 mb-4 sm:mb-6">Bugün zaten check-in yapmışsınız. Check-out yapmak için aşağıdaki butona tıklayın.</p>
            
            <!-- Mevcut Check-in Detayları -->
            @if($todayCheckin)
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-3 sm:gap-4 mb-4 sm:mb-6 text-left bg-gray-50 p-3 sm:p-4 rounded-lg">
                <div class="flex flex-col sm:flex-row sm:justify-between gap-1 sm:gap-0">
                    <span class="text-xs sm:text-sm text-gray-600">📍 Lokasyon:</span>
                    <span class="text-xs sm:text-sm font-medium break-words">{{ $todayCheckin->location->name }}</span>
                </div>
                <div class="flex flex-col sm:flex-row sm:justify-between gap-1 sm:gap-0">
                    <span class="text-xs sm:text-sm text-gray-600">⏰ Check-in Zamanı:</span>
                    <span class="text-xs sm:text-sm font-medium">{{ $todayCheckin->created_at->format('d/m/Y H:i') }}</span>
                </div>
                <div class="flex flex-col sm:flex-row sm:justify-between gap-1 sm:gap-0 lg:col-span-2">
                    <span class="text-xs sm:text-sm text-gray-600">📊 Durum:</span>
                    <span class="text-xs sm:text-sm font-medium">
                        @if($todayCheckin->status === 'active')
                            <span class="text-green-600">Mesai Devam Ediyor</span>
                        @else
                            <span class="text-gray-600">{{ ucfirst($todayCheckin->status) }}</span>
                        @endif
                    </span>
                </div>
            </div>
            @endif
            
            <!-- Check-out Butonu -->
            <a 
                href="{{ \App\Filament\Employee\Pages\CheckOutPage::getUrl() }}"
                class="w-full bg-orange-600 hover:bg-orange-700 text-white font-medium py-2 px-3 rounded-lg transition duration-200 text-sm flex items-center justify-center space-x-2"
            >
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.2" stroke="currentColor" class="size-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12c0 1.268-.63 2.39-1.593 3.068a3.745 3.745 0 0 1-1.043 3.296 3.745 3.745 0 0 1-3.296 1.043A3.745 3.745 0 0 1 12 21c-1.268 0-2.39-.63-3.068-1.593a3.746 3.746 0 0 1-3.296-1.043 3.745 3.745 0 0 1-1.043-3.296A3.745 3.745 0 0 1 3 12c0-1.268.63-2.39 1.593-3.068a3.745 3.745 0 0 1 1.043-3.296 3.746 3.746 0 0 1 3.296-1.043A3.746 3.746 0 0 1 12 3c1.268 0 2.39.63 3.068 1.593a3.746 3.746 0 0 1 3.296 1.043 3.746 3.746 0 0 1 1.043 3.296A3.745 3.745 0 0 1 21 12Z" />
                </svg>
                <span>Check-out Yap</span>
            </a>
        </div>
    @elseif($checkInCompleted)
        <!-- Başarı Ekranı -->
        <div class="bg-white rounded-xl shadow-lg p-4 sm:p-6 lg:p-8 text-center max-w-sm sm:max-w-md lg:max-w-2xl xl:max-w-3xl mx-auto">
            <!-- Başarı İkonu -->
            <div class="mb-6">
                <div class="w-20 h-20 mx-auto bg-green-100 rounded-full flex items-center justify-center">
                    <svg class="w-10 h-10 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
            </div>
            
            <!-- Başarı Mesajı -->
            <h2 class="text-lg sm:text-xl lg:text-2xl font-bold text-gray-900 mb-3 sm:mb-4">Check-in Başarılı! ✅</h2>
            
            <!-- Detaylar -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-3 sm:gap-4 mb-4 sm:mb-6 text-left bg-gray-50 p-3 sm:p-4 rounded-lg">
                <div class="flex flex-col sm:flex-row sm:justify-between gap-1 sm:gap-0">
                    <span class="text-xs sm:text-sm text-gray-600">👤 Çalışan:</span>
                    <span class="text-xs sm:text-sm font-medium break-words">{{ $completedCheckIn['employee_name'] }}</span>
                </div>
                <div class="flex flex-col sm:flex-row sm:justify-between gap-1 sm:gap-0">
                    <span class="text-xs sm:text-sm text-gray-600">📍 Lokasyon:</span>
                    <span class="text-xs sm:text-sm font-medium break-words">{{ $completedCheckIn['location_name'] }}</span>
                </div>
                <div class="flex flex-col sm:flex-row sm:justify-between gap-1 sm:gap-0">
                    <span class="text-xs sm:text-sm text-gray-600">⏰ Zaman:</span>
                    <span class="text-xs sm:text-sm font-medium">{{ \Carbon\Carbon::parse($completedCheckIn['checkin_time'])->format('d/m/Y H:i') }}</span>
                </div>
                <div class="flex flex-col sm:flex-row sm:justify-between gap-1 sm:gap-0">
                    <span class="text-xs sm:text-sm text-gray-600">🆔 Check-in ID:</span>
                    <span class="text-xs sm:text-sm font-mono">{{ $completedCheckIn['checkin_id'] }}</span>
                </div>
            </div>
            
            <!-- Yeni Check-in Butonu -->
            <button 
                wire:click="resetCheckIn"
                class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 sm:py-3 px-3 sm:px-4 rounded-lg transition duration-200 text-sm sm:text-base"
            >
                🔄 Yeni Check-in Yap
            </button>
        </div>
    @else
        <!-- Check-in Formu -->
        <form wire:submit="checkIn">
            {{ $this->form }}
        </form>
        
        <!-- Submit Button -->
        <div class="mt-3 flex justify-center">
            <button 
                wire:click="checkIn"
                class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg shadow-lg hover:shadow-xl transition-all duration-200 text-sm border-none cursor-pointer flex items-center space-x-2"
            >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <span>Check-in Yap</span>
            </button>
        </div>
    @endif

    @push('scripts')
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        // Debug form submission
        document.addEventListener('DOMContentLoaded', function() {
            console.log('Check-in page loaded');
            
            // Form submit debug
            const checkInButton = document.querySelector('button[wire\\:click="checkIn"]');
            if (checkInButton) {
                checkInButton.addEventListener('click', function(e) {
                    console.log('Check-in button clicked');
                    console.log('Form data:', @this.data);
                    
                    // Check required fields
                    const requiredFields = ['location_id', 'latitude', 'longitude', 'selfie_photo'];
                    const missingFields = [];
                    
                    requiredFields.forEach(field => {
                        if (!@this.data[field]) {
                            missingFields.push(field);
                        }
                    });
                    
                    if (missingFields.length > 0) {
                        console.error('Missing required fields:', missingFields);
                    } else {
                        console.log('All required fields present');
                    }
                });
            }
        });
    </script>
    @endpush
</x-filament-panels::page>
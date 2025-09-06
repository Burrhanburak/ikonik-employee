<x-filament-panels::page>
    <div class="space-y-6">
        <!-- Stats Cards using Filament Components -->
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6 mb-8">
            <!-- Total Locations -->
            <x-filament::card class="hover:shadow-lg transition-all duration-300 hover:-translate-y-1">
                <div class="flex items-center justify-between">
                    <div class="flex-1">
                        <div class="text-sm font-medium text-gray-600 dark:text-gray-400 mb-2">Toplam Lokasyon</div>
                        <div class="text-3xl font-bold text-primary-600 dark:text-primary-400 mb-2">{{ $this->stats['total_locations'] }}</div>
                        <div class="text-xs text-gray-500 dark:text-gray-400 flex items-center">
                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                            </svg>
                            Kayıtlı çalışma alanları
                        </div>
                    </div>
                    <div class="ml-4">
                        <div class="w-14 h-14 bg-gradient-to-br from-blue-400 to-blue-600 rounded-2xl flex items-center justify-center shadow-lg">
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                        </div>
                    </div>
                </div>
            </x-filament::card>
            
            <!-- Active Employees -->
            <x-filament::card class=" hover:shadow-lg transition-all duration-300 hover:-translate-y-1">
                <div class="flex items-center justify-between">
                    <div class="flex-1">
                        <div class="text-sm font-medium text-gray-600 dark:text-gray-400 mb-2">Bugün Aktif</div>
                        <div class="text-3xl font-bold text-blue-600 dark:text-blue-400 mb-2">{{ $this->stats['active_employees'] }}</div>
                        <div class="text-xs text-gray-500 dark:text-gray-400 flex items-center">
                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                            </svg>
                            Check-in yapan çalışan
                        </div>
                    </div>
                    <div class="ml-4">
                        <div class="w-14 h-14 bg-gradient-to-br from-blue-400 to-blue-600 rounded-2xl flex items-center justify-center shadow-lg">
                            <svg class="w-7 h-7 text-white" fill="currentColor" viewBox="0 0 640 512">
                                <path d="M96 224c35.3 0 64-28.7 64-64s-28.7-64-64-64-64 28.7-64 64 28.7 64 64 64zm448 0c35.3 0 64-28.7 64-64s-28.7-64-64-64-64 28.7-64 64 28.7 64 64 64zm32 32h-64c-17.6 0-33.5 7.1-45.1 18.6 40.3 22.1 68.9 62 75.1 109.4h66c17.7 0 32-14.3 32-32v-32c0-35.3-28.7-64-64-64zm-256 0c61.9 0 112-50.1 112-112S381.9 32 320 32 208 82.1 208 144s50.1 112 112 112zm76.8 32h-8.3c-20.8 10-43.9 16-68.5 16s-47.6-6-68.5-16h-8.3C179.6 288 128 339.6 128 403.2V432c0 26.5 21.5 48 48 48h288c26.5 0 48-21.5 48-48v-28.8c0-63.6-51.6-115.2-115.2-115.2zm-223.7-13.4C161.5 263.1 145.6 256 128 256H64c-35.3 0-64 28.7-64 64v32c0 17.7 14.3 32 32 32h65.9c6.3-47.4 34.9-87.3 75.2-109.4z"/>
                            </svg>
                        </div>
                    </div>
                </div>
            </x-filament::card>
            
            <!-- Employees In Area -->
            <x-filament::card class="hover:shadow-lg transition-all duration-300 hover:-translate-y-1">
                <div class="flex items-center justify-between">
                    <div class="flex-1">
                        <div class="text-sm font-medium text-gray-600 dark:text-gray-400 mb-2">Alan İçinde</div>
                        <div class="text-3xl font-bold text-green-600 dark:text-green-400 mb-2">{{ $this->stats['employees_in_area'] }}</div>
                        <div class="text-xs text-gray-500 dark:text-gray-400 flex items-center">
                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            İzinli bölgede
                        </div>
                    </div>
                    <div class="ml-4">
                        <div class="w-14 h-14 bg-gradient-to-br from-green-400 to-green-600 rounded-2xl flex items-center justify-center shadow-lg">
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                </div>
            </x-filament::card>
            
            <!-- Employees Out of Area -->
            <x-filament::card class="hover:shadow-lg transition-all duration-300 hover:-translate-y-1">
                <div class="flex items-center justify-between">
                    <div class="flex-1">
                        <div class="text-sm font-medium text-gray-600 dark:text-gray-400 mb-2">Alan Dışında</div>
                        <div class="text-3xl font-bold text-orange-600 dark:text-orange-400 mb-2">{{ $this->stats['employees_out_area'] }}</div>
                        <div class="text-xs text-gray-500 dark:text-gray-400 flex items-center">
                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.864-.833-2.634 0L4.17 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                            </svg>
                            Dikkat gerekli
                        </div>
                    </div>
                    <div class="ml-4">
                        <div class="w-14 h-14 bg-gradient-to-br from-orange-400 to-orange-600 rounded-2xl flex items-center justify-center shadow-lg">
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.864-.833-2.634 0L4.17 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                            </svg>
                        </div>
                    </div>
                </div>
            </x-filament::card>
        </div>

        <!-- Interactive Map Section -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 overflow-hidden" 
             x-data="employeeTrackingMap()"
             x-init="initMap()"
             @map-data-updated.window="updateMapData($event.detail)">
            
            <!-- Map Header -->
            <div class="bg-gradient-to-r from-blue-600 to-blue-700 p-6 text-white">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between space-y-4 md:space-y-0">
                    <div>
                        <h2 class="text-xl font-bold mb-2">🗺️ Gerçek Zamanlı Çalışan Takibi</h2>
                        <p class="text-blue-100 text-sm">
                            Çalışanların anlık konumları, durum bilgileri ve çalışma alanları
                        </p>
                        <div class="flex items-center mt-2 text-xs text-blue-200">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Son güncelleme: <span x-text="lastUpdate" class="font-medium ml-1"></span>
                        </div>
                    </div>
                    
                    <div class="flex flex-col sm:flex-row space-y-2 sm:space-y-0 sm:space-x-3">
                        <button wire:click="refresh" 
                                class="px-4 py-2 bg-white/10 backdrop-blur-sm text-white rounded-lg hover:bg-white/20 transition-colors flex items-center justify-center space-x-2 text-sm font-medium">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                            </svg>
                            <span>Yenile</span>
                        </button>
                        
                        <div class="px-4 py-2 bg-white/10 backdrop-blur-sm rounded-lg text-xs text-white">
                            <div class="grid grid-cols-2 gap-4">
                                <div class="flex items-center space-x-2">
                                    <div class="w-3 h-3 bg-gradient-to-br from-blue-400 to-blue-600 rounded-full shadow-sm flex items-center justify-center">
                                        <svg class="w-2 h-2 text-white" viewBox="0 0 24 24" fill="currentColor">
                                            <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                                        </svg>
                                    </div>
                                    <span>Lokasyon Merkezleri</span>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <div class="w-3 h-3 border-2 border-blue-400 rounded-full opacity-60" style="border-style: dashed;"></div>
                                    <span>Çalışma Alanları</span>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <div class="w-3 h-3 bg-gradient-to-br from-green-400 to-green-600 rounded-full shadow-sm flex items-center justify-center">
                                        <span class="text-white text-xs font-bold">✓</span>
                                    </div>
                                    <span>Belirlenen Adreste</span>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <div class="w-3 h-3 bg-gradient-to-br from-orange-400 to-orange-600 rounded-full shadow-sm flex items-center justify-center">
                                        <span class="text-white text-xs font-bold">!</span>
                                    </div>
                                    <span>Alan Dışında</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Map Container -->
            <div id="employee-map" style="height: 500px; width: 100%; position: relative; z-index: 1;"></div>
        </div>

        <!-- Employee List using Filament Components -->
        <x-filament::section>
            <x-slot name="heading">
                <div class="flex items-center space-x-2">
                    <x-heroicon-o-users class="w-6 h-6 text-primary-600" />
                    <span>Aktif Çalışanlar</span>
                </div>
            </x-slot>
            
            <x-slot name="description">
                <div class="flex items-center space-x-2 text-gray-600 dark:text-gray-400">
                    <x-heroicon-o-clock class="w-4 h-4" />
                    <span>Bugün check-in yapan çalışanların anlık durum bilgileri</span>
                </div>
            </x-slot>
            
            <x-slot name="headerEnd">
                <div class="flex flex-wrap items-center gap-2">
                    <x-filament::badge color="success" icon="heroicon-o-check-circle" size="sm">
                        Belirlenen Adreste: {{ $this->stats['employees_in_area'] }}
                    </x-filament::badge>
                    <x-filament::badge color="warning" icon="heroicon-o-exclamation-triangle" size="sm">
                        Alan Dışında: {{ $this->stats['employees_out_area'] }}
                    </x-filament::badge>
                </div>
            </x-slot>

            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-3 xl:grid-cols-3 2xl:grid-cols-4 gap-6" x-data="{ checkins: @js($this->checkins) }">
                <template x-for="checkin in checkins" :key="checkin.id">
                    <div class="group relative block h-full w-full p-2">
                        <div class="bg-white dark:bg-gray-800 relative z-20 flex h-full flex-col items-center justify-start gap-4 rounded-3xl p-6 transition-all duration-300 hover:shadow-xl hover:-translate-y-1 border border-gray-100 dark:border-gray-700">
                            
                            <!-- Status Icon with Gradient Background -->
                            <div class="mb-4 flex items-center justify-center rounded-2xl w-16 h-16 shadow-lg"
                                 x-bind:class="checkin.is_in_area ? 'bg-gradient-to-br from-green-400 to-green-600' : 'bg-gradient-to-br from-orange-400 to-orange-600'">
                                
                                <!-- Employee Photo Priority: selfie_photo > employee_photo > default icon -->
                                <template x-if="checkin.selfie_photo && checkin.selfie_photo.trim() !== ''">
    <img 
        x-bind:src="checkin.selfie_photo.startsWith('data:image') 
                        ? checkin.selfie_photo 
                        : '/storage/' + checkin.selfie_photo" 
        alt="Check-in Selfie" 
        class="w-full h-full object-cover rounded-2xl border-2 border-white">
</template>
                                <template x-if="(!checkin.selfie_photo || checkin.selfie_photo.trim() === '') && checkin.employee_photo && checkin.employee_photo.trim() !== ''">
                                    <img x-bind:src="'/storage/' + checkin.employee_photo" 
                                         alt="Profile Photo" 
                                         class="w-full h-full object-cover rounded-2xl border-2 border-white">
                                </template>
                                <template x-if="(!checkin.selfie_photo || checkin.selfie_photo.trim() === '') && (!checkin.employee_photo || checkin.employee_photo.trim() === '')">
                                    <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center">
                                        <span class="text-2xl">👤</span>
                                    </div>
                                </template>
                                
                                <!-- Status Badge -->
                                <div class="absolute -top-1 -right-1 w-6 h-6 rounded-full border-2 border-white shadow-lg flex items-center justify-center text-white text-xs font-bold"
                                     x-bind:class="checkin.is_in_area ? 'bg-green-500' : 'bg-orange-500'">
                                    <span x-text="checkin.is_in_area ? '✓' : '!'"></span>
                                </div>
                            </div>
                            
                            <!-- Employee Name -->
                            <h1 class="text-lg font-semibold tracking-tight text-gray-900 dark:text-white text-center" 
                                x-text="checkin.employee_name"></h1>
                            
                            <!-- Status Description -->
                            <p class="text-sm text-center"
                               x-bind:class="checkin.is_in_area ? 'text-green-600 dark:text-green-400' : 'text-orange-600 dark:text-orange-400'"
                               x-text="checkin.is_in_area ? 'Belirlenen adreste çalışıyor' : 'Alan dışında konumlandı'"></p>
                            
                            <!-- Details Grid -->
                            <div class="w-full space-y-3 mt-2">
                                <!-- Location -->
                                <div class="flex items-center justify-between text-sm">
                                    <div class="flex items-center space-x-2 text-gray-600 dark:text-gray-400">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        </svg>
                                        <span>Lokasyon</span>
                                    </div>
                                    <span class="font-medium text-gray-900 dark:text-white truncate max-w-[100px]" x-text="checkin.location_name"></span>
                                </div>
                                
                                <!-- Time -->
                                <div class="flex items-center justify-between text-sm">
                                    <div class="flex items-center space-x-2 text-gray-600 dark:text-gray-400">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        <span>Zaman</span>
                                    </div>
                                    <span class="font-medium text-gray-900 dark:text-white" x-text="checkin.time"></span>
                                </div>
                                
                                <!-- Distance -->
                                <div class="flex items-center justify-between text-sm">
                                    <div class="flex items-center space-x-2 text-gray-600 dark:text-gray-400">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                                        </svg>
                                        <span>Mesafe</span>
                                    </div>
                                    <span class="font-medium"
                                          x-bind:class="checkin.distance_to_center <= 50 ? 'text-green-600 dark:text-green-400' : 'text-orange-600 dark:text-orange-400'"
                                          x-text="checkin.distance_to_center + 'm'"></span>
                                </div>
                                
                                <!-- Email -->
                                <div class="flex items-center justify-between text-sm pt-2 border-t border-gray-100 dark:border-gray-700">
                                    <div class="flex items-center space-x-2 text-gray-600 dark:text-gray-400">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"></path>
                                        </svg>
                                        <span>Email</span>
                                    </div>
                                    <span class="font-medium text-gray-900 dark:text-white truncate max-w-[100px]" x-text="checkin.employee_email.split('@')[0]"></span>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Hover Glow Effect -->
                        <div class="absolute inset-0 rounded-3xl transition-opacity duration-300 opacity-0 group-hover:opacity-100"
                             x-bind:class="checkin.is_in_area ? 'bg-gradient-to-r from-green-400/20 to-green-600/20' : 'bg-gradient-to-r from-orange-400/20 to-orange-600/20'">
                        </div>
                    </div>
                </template>
                
                <!-- Empty State -->
                <template x-if="checkins.length === 0">
                    <div class="col-span-full">
                        <x-filament::card class="text-center py-12">
                            <div class="w-16 h-16 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mx-auto mb-4">
                                <x-heroicon-o-map-pin class="w-8 h-8 text-gray-400" />
                            </div>
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">Henüz Check-in Yok</h3>
                            <p class="text-gray-600 dark:text-gray-400">Bugün henüz hiç çalışan check-in yapmadı.</p>
                        </x-filament::card>
                    </div>
                </template>
            </div>
        </x-filament::section>
    </div>

    @push('styles')
    @vite(['resources/css/app.css'])
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" 
          integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" 
          crossorigin="" />
    @endpush

    @push('scripts')
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" 
            integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" 
            crossorigin=""></script>
    
    <script>
        let map;
        let markers = [];

        function employeeTrackingMap() {
            return {
                lastUpdate: new Date().toLocaleTimeString('tr-TR'),
                stats: @js($this->stats),
                
                initMap() {
                    // Debug: Check if Leaflet is loaded
                    if (typeof L === 'undefined') {
                        console.error('Leaflet is not loaded!');
                        return;
                    }
                    
                    console.log('Initializing map...');
                    
                    try {
                        // Initialize Leaflet map - simple approach
                        map = L.map('employee-map').setView([41.0082, 28.9784], 11);
                        
                        // Ensure proper z-index for map container
                        const mapContainer = document.getElementById('employee-map');
                        if (mapContainer) {
                            mapContainer.style.zIndex = '1';
                            mapContainer.style.position = 'relative';
                        }
                        
                        // Add OpenStreetMap tiles
                        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                            maxZoom: 19,
                            attribution: '© OpenStreetMap contributors'
                        }).addTo(map);
                        
                        console.log('Map initialized successfully');
                        
                        // Load markers
                        this.loadMarkers();
                    } catch (error) {
                        console.error('Error initializing map:', error);
                    }
                },
                
                loadMarkers() {
                    console.log('Loading markers...');
                    
                    // Clear existing markers
                    markers.forEach(marker => map.removeLayer(marker));
                    markers = [];
                    
                    const locations = @js($this->locations);
                    const checkins = @js($this->checkins);
                    
                    console.log('Locations:', locations);
                    console.log('Checkins:', checkins);
                    
                    // Add location markers with custom icons
                    locations.forEach(location => {
                        // Create custom location icon
                        const locationIcon = L.divIcon({
                            className: 'custom-location-marker',
                            html: `
                                <div style="
                                    position: relative;
                                    width: 32px;
                                    height: 40px;
                                ">
                                    <div style="
                                        width: 32px;
                                        height: 32px;
                                        background: linear-gradient(135deg, #3b82f6, #1e40af);
                                        border: 3px solid white;
                                        border-radius: 50%;
                                        display: flex;
                                        align-items: center;
                                        justify-content: center;
                                        box-shadow: 0 4px 12px rgba(0,0,0,0.3);
                                        position: relative;
                                        z-index: 10;
                                    ">
                                        <svg width="16" height="16" fill="white" viewBox="0 0 24 24">
                                            <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                                        </svg>
                                    </div>
                                    <div style="
                                        position: absolute;
                                        bottom: -8px;
                                        left: 50%;
                                        transform: translateX(-50%);
                                        width: 0;
                                        height: 0;
                                        border-left: 6px solid transparent;
                                        border-right: 6px solid transparent;
                                        border-top: 8px solid #1e40af;
                                    "></div>
                                </div>
                            `,
                            iconSize: [32, 40],
                            iconAnchor: [16, 40],
                            popupAnchor: [0, -40]
                        });
                        
                        const marker = L.marker([location.lat, location.lng], {
                            icon: locationIcon
                        }).addTo(map).bindPopup(`
                            <div style="padding: 16px; font-family: system-ui; min-width: 250px;">
                                <div style="display: flex; align-items: center; margin-bottom: 12px;">
                                    <div style="width: 32px; height: 32px; background: linear-gradient(135deg, #3b82f6, #1e40af); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin-right: 12px;">
                                        <svg width="16" height="16" fill="white" viewBox="0 0 24 24">
                                            <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                                        </svg>
                                    </div>
                                    <h3 style="margin: 0; color: #1f2937; font-size: 16px; font-weight: 600;">${location.name}</h3>
                                </div>
                                <div style="background: #f8fafc; padding: 12px; border-radius: 8px; margin-bottom: 8px;">
                                    <div style="font-size: 14px; margin-bottom: 4px;"><strong>📏 Çalışma Yarıçapı:</strong> ${location.radius}m</div>
                                    <div style="font-size: 12px; color: #6b7280;">📍 ${location.lat.toFixed(4)}, ${location.lng.toFixed(4)}</div>
                                </div>
                            </div>
                        `);
                        
                        // Add work area circle with better styling
                        const circle = L.circle([location.lat, location.lng], {
                            radius: location.radius,
                            fillColor: '#3b82f6',
                            color: '#1e40af',
                            weight: 2,
                            opacity: 0.8,
                            fillOpacity: 0.15,
                            dashArray: '5, 5'
                        }).addTo(map);
                        
                        markers.push(marker);
                        markers.push(circle);
                    });
                    
                    // Add employee markers with custom icons
                    checkins.forEach(checkin => {
                        const isInArea = checkin.is_in_area;
                        const statusColor = isInArea ? '#22c55e' : '#f59e0b';
                        const statusText = isInArea ? 'Belirlenen Adreste' : 'Alan Dışında';
                        const statusIcon = isInArea ? '✓' : '!';
                        
                        // Create custom employee icon
                        const employeeIcon = L.divIcon({
                            className: 'custom-employee-marker',
                            html: `
                                <div style="
                                    position: relative;
                                    width: 28px;
                                    height: 36px;
                                ">
                                    <div style="
                                        width: 28px;
                                        height: 28px;
                                        background: linear-gradient(135deg, ${statusColor}, ${statusColor}dd);
                                        border: 2px solid white;
                                        border-radius: 50%;
                                        display: flex;
                                        align-items: center;
                                        justify-content: center;
                                        box-shadow: 0 3px 10px rgba(0,0,0,0.3);
                                        position: relative;
                                        z-index: 5;
                                    ">
                                        <span style="
                                            color: white;
                                            font-weight: bold;
                                            font-size: 14px;
                                        ">${statusIcon}</span>
                                    </div>
                                    <div style="
                                        position: absolute;
                                        bottom: -6px;
                                        left: 50%;
                                        transform: translateX(-50%);
                                        width: 0;
                                        height: 0;
                                        border-left: 5px solid transparent;
                                        border-right: 5px solid transparent;
                                        border-top: 6px solid ${statusColor}dd;
                                    "></div>
                                </div>
                            `,
                            iconSize: [28, 36],
                            iconAnchor: [14, 36],
                            popupAnchor: [0, -36]
                        });
                        
                        // Create photo HTML if available
                        if (checkin.selfie_photo) {
    // Eğer zaten base64 prefix içeriyorsa direk ata
    let src = checkin.selfie_photo.startsWith('data:image')
        ? checkin.selfie_photo
        : `data:image/jpeg;base64,${checkin.selfie_photo}`;

    photoHtml = `
        <div style="text-align: center; margin-bottom: 12px;">
            <img src="${src}" 
                 style="width: 80px; height: 80px; object-fit: cover; border-radius: 50%; border: 3px solid ${statusColor}; box-shadow: 0 4px 12px rgba(0,0,0,0.2);" 
                 alt="Check-in Selfie">
            <div style="font-size: 10px; color: #6b7280; margin-top: 4px; font-weight: 500;">Check-in Selfie</div>
        </div>
    `;
}
                        
                        const marker = L.marker([checkin.lat, checkin.lng], {
                            icon: employeeIcon
                        }).addTo(map).bindPopup(`
                            <div style="padding: 16px; font-family: system-ui; min-width: 280px;">
                                ${photoHtml}
                                <div style="text-align: center; margin-bottom: 12px;">
                                    <h3 style="margin: 0 0 4px 0; color: #1f2937; font-size: 16px; font-weight: 600;">👤 ${checkin.employee_name}</h3>
                                    <div style="font-size: 12px; color: #6b7280; margin-bottom: 8px;">${checkin.employee_email}</div>
                                    <div style="
                                        display: inline-flex;
                                        align-items: center;
                                        background: ${statusColor};
                                        color: white;
                                        padding: 6px 12px;
                                        border-radius: 20px;
                                        font-size: 12px;
                                        font-weight: 600;
                                        box-shadow: 0 2px 8px rgba(0,0,0,0.2);
                                    ">
                                        <span style="margin-right: 4px;">${statusIcon}</span>
                                        ${statusText}
                                    </div>
                                </div>
                                <div style="background: #f8fafc; border-radius: 12px; padding: 12px; font-size: 14px;">
                                    <div style="display: flex; align-items: center; margin-bottom: 8px;">
                                        <svg style="width: 16px; height: 16px; margin-right: 8px; color: #6b7280;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        </svg>
                                        <strong>Lokasyon:</strong> ${checkin.location_name}
                                    </div>
                                    <div style="display: flex; align-items: center; margin-bottom: 8px;">
                                        <svg style="width: 16px; height: 16px; margin-right: 8px; color: #6b7280;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        <strong>Check-in:</strong> ${checkin.time_exact}
                                    </div>
                                    <div style="display: flex; align-items: center; margin-bottom: 8px;">
                                        <svg style="width: 16px; height: 16px; margin-right: 8px; color: #6b7280;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                                        </svg>
                                        <strong>Merkez Mesafe:</strong> ${checkin.distance_to_center}m
                                    </div>
                                    <div style="font-size: 11px; color: #6b7280; border-top: 1px solid #e5e7eb; padding-top: 8px; margin-top: 8px; text-align: center;">
                                        📍 ${checkin.lat.toFixed(6)}, ${checkin.lng.toFixed(6)}
                                    </div>
                                </div>
                            </div>
                        `);
                        
                        markers.push(marker);
                    });
                    
                    // Fit map to show all markers
                    if (markers.length > 0) {
                        const group = new L.featureGroup(markers.filter(m => m instanceof L.Marker));
                        map.fitBounds(group.getBounds().pad(0.1));
                        
                        // Prevent excessive zoom
                        setTimeout(() => {
                            if (map.getZoom() > 15) {
                                map.setZoom(15);
                            }
                        }, 100);
                    }
                },
                
                updateMapData(data) {
                    this.stats = data.stats;
                    this.lastUpdate = new Date().toLocaleTimeString('tr-TR');
                    
                    // Update map data
                    setTimeout(() => {
                        this.loadMarkers();
                    }, 100);
                }
            }
        }
    </script>
    @endpush
</x-filament-panels::page>
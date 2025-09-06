@php
    $lat = old('lat_allowed', $getRecord()?->lat_allowed ?? 41.0082);
    $lng = old('lng_allowed', $getRecord()?->lng_allowed ?? 28.9784);
@endphp

<!-- Beautiful Location Map Picker - Matching Employee Tracking Map Style -->
<div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 overflow-hidden" 
     x-data="locationMapPicker()" 
     x-init="init()"
     wire:ignore>
    
    <!-- Map Header -->
    <div class="bg-gradient-to-r from-blue-600 to-blue-700 p-6 text-white">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between space-y-4 md:space-y-0">
            <div>
                <h2 class="text-xl font-bold mb-2">🗺️ Lokasyon Seçici</h2>
                <p class="text-blue-100 text-sm">
                    Harita üzerinde çalışma lokasyonunu seçin ve çalışma alanını belirleyin
                </p>
                <div class="flex items-center mt-2 text-xs text-blue-200">
                    <svg style="width: 12px; height: 12px; margin-right: 4px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                    Seçili Konum: <span x-text="currentCoords" style="font-weight: 500; margin-left: 4px;">Yükleniyor...</span>
                </div>
            </div>
            
             <div class="flex flex-col space-y-2">
                <div style="padding: 8px 12px; background: rgba(255,255,255,0.1); backdrop-filter: blur(4px); border-radius: 8px; font-size: 11px; color: white;">
                    <div style="display: grid; grid-template-columns: 1fr; gap: 4px;">
                        <div style="display: flex; align-items: center; gap: 8px;">
                            <div style="width: 8px; height: 8px; background: linear-gradient(135deg, #60a5fa, #3b82f6); border-radius: 50%; box-shadow: 0 1px 3px rgba(0,0,0,0.2); display: flex; align-items: center; justify-content: center;">
                                <svg style="width: 4px; height: 4px; color: white;" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                                </svg>
                            </div>
                            <span>Lokasyon Merkezi</span>
                        </div>
                        <div style="display: flex; align-items: center; gap: 8px;">
                            <div style="width: 8px; height: 8px; border: 1px dashed #60a5fa; border-radius: 50%; opacity: 0.6;"></div>
                            <span>Çalışma Alanı</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Map Container -->
    <div id="location-picker-map" class="w-full relative z-10" style="height: 500px;"></div>
    
    <!-- Info Cards -->
    <div class="p-2 bg-gray-50 dark:bg-gray-900">
        <div class="grid grid-cols-1 md:grid-cols-1 gap-6">
            
            <!-- Instructions Card -->
            <x-filament::card >
                <div class="flex items-start gap-4">
                    <div class="w-8 h-8 bg-gradient-to-br from-blue-400 to-blue-600 rounded-lg flex items-center justify-center shadow-lg shrink-0">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="flex-1 min-w-0">
                        <h3 class="font-semibold text-gray-900 dark:text-white mb-3 text-base">📍 Nasıl Kullanılır?</h3>
                        <ul class="space-y-2 text-sm text-gray-600 dark:text-gray-400">
                            <li class="flex items-center gap-3">
                                <span class="w-1.5 h-1.5 bg-blue-500 rounded-full shrink-0"></span>
                                <span>Haritaya tıklayarak konum seçin</span>
                            </li>
                            <li class="flex items-center gap-3">
                                <span class="w-1.5 h-1.5 bg-blue-500 rounded-full shrink-0"></span>
                                <span>Marker'ı sürükleyerek konumu ayarlayın</span>
                            </li>
                            <li class="flex items-center gap-3">
                                <span class="w-1.5 h-1.5 bg-blue-500 rounded-full shrink-0"></span>
                                <span>Koordinatlar otomatik güncellenecek</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </x-filament::card>
            
            <!-- Selected Location Card -->
            <x-filament::card >
                <div class="flex items-start gap-4">
                    <div class="w-8 h-8 bg-gradient-to-br from-green-400 to-green-600 rounded-lg flex items-center justify-center shadow-lg shrink-0">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="flex-1 min-w-0">
                        <h3 class="font-semibold text-gray-900 dark:text-white mb-3 text-base">✅ Seçili Konum</h3>
                        <div class="space-y-2 text-sm">
                            <div class="flex items-center justify-between gap-3">
                                <span class="text-gray-600 dark:text-gray-400 shrink-0">Koordinatlar:</span>
                                <span class="font-medium text-gray-900 dark:text-white font-mono text-xs text-right truncate" x-text="currentCoords">Yükleniyor...</span>
                            </div>
                            <div class="flex items-center justify-between gap-3">
                                <span class="text-gray-600 dark:text-gray-400 shrink-0">Durum:</span>
                                <span class="font-medium text-green-600 dark:text-green-400 text-right" x-text="locationInfo">Konum belirleniyor...</span>
                            </div>
                        </div>
                    </div>
                </div>
            </x-filament::card>
            
        </div>
    </div>
</div>

@push('scripts')
@vite(['resources/css/app.css', 'resources/js/app.js'])
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" 
      integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" 
      crossorigin="" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" 
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" 
        crossorigin=""></script>

<script>
function locationMapPicker() {
    const instance = {
        map: null,
        marker: null,
        circle: null,
        currentCoords: 'Yükleniyor...',
        locationInfo: 'Konum belirleniyor...',
        
        init() {
            console.log('Initializing location picker map...');
            
            try {
                // Get initial coordinates from form
                const initialLat = {{ $lat ?? 'null' }};
                const initialLng = {{ $lng ?? 'null' }};
                
                // Use initial coordinates if available, otherwise default to Istanbul
                const mapCenter = (initialLat && initialLng) ? [initialLat, initialLng] : [41.0082, 28.9784];
                const mapZoom = (initialLat && initialLng) ? 16 : 10;
                
                // Initialize Leaflet map with better styling
                this.map = L.map('location-picker-map', {
                    zoomControl: true,
                    scrollWheelZoom: true,
                    doubleClickZoom: true,
                    touchZoom: true
                }).setView(mapCenter, mapZoom);
                
                // Set z-index and ensure height for map container
                const mapContainer = document.getElementById('location-picker-map');
                mapContainer.style.zIndex = '1';
                mapContainer.style.height = '500px';
                mapContainer.style.minHeight = '500px';
                
                // Add OpenStreetMap tiles
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    maxZoom: 19,
                    attribution: '© OpenStreetMap contributors'
                }).addTo(this.map);
                
                // Create custom location marker icon - smaller and cleaner
                const locationIcon = L.divIcon({
                    className: 'custom-location-marker',
                    html: `
                        <div style="
                            position: relative;
                            width: 24px;
                            height: 30px;
                        ">
                            <div style="
                                width: 24px;
                                height: 24px;
                                background: linear-gradient(135deg, #3b82f6, #1e40af);
                                border: 2px solid white;
                                border-radius: 50%;
                                display: flex;
                                align-items: center;
                                justify-content: center;
                                box-shadow: 0 2px 8px rgba(0,0,0,0.3);
                                position: relative;
                                z-index: 10;
                                cursor: move;
                            ">
                                <svg width="10" height="10" fill="white" viewBox="0 0 24 24">
                                    <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                                </svg>
                            </div>
                            <div style="
                                position: absolute;
                                bottom: -6px;
                                left: 50%;
                                transform: translateX(-50%);
                                width: 0;
                                height: 0;
                                border-left: 4px solid transparent;
                                border-right: 4px solid transparent;
                                border-top: 6px solid #1e40af;
                            "></div>
                        </div>
                    `,
                    iconSize: [24, 30],
                    iconAnchor: [12, 30],
                    popupAnchor: [0, -30]
                });
                
                // Add draggable marker at initial position
                this.marker = L.marker(mapCenter, {
                    draggable: true,
                    icon: locationIcon
                }).addTo(this.map);
                
                // Add work area circle preview
                this.circle = L.circle(mapCenter, {
                    radius: 100, // Default 100m radius, will be updated from form
                    fillColor: '#3b82f6',
                    color: '#1e40af',
                    weight: 2,
                    opacity: 0.8,
                    fillOpacity: 0.15,
                    dashArray: '5, 5'
                }).addTo(this.map);
                
                // Update coordinates display with initial values
                if (initialLat && initialLng) {
                    this.updateCoords(initialLat, initialLng);
                } else {
                    this.currentCoords = 'Konum seçin';
                    this.locationInfo = 'Haritaya tıklayın';
                }
                
                // Marker drag event
                this.marker.on('dragend', (e) => {
                    const latlng = e.target.getLatLng();
                    this.updateCoords(latlng.lat, latlng.lng);
                    this.circle.setLatLng(latlng);
                });
                
                // Map click event
                this.map.on('click', (e) => {
                    const { lat, lng } = e.latlng;
                    this.marker.setLatLng([lat, lng]);
                    this.circle.setLatLng([lat, lng]);
                    this.updateCoords(lat, lng);
                });
                
                // Beautiful popup - smaller icons
                this.marker.bindPopup(`
                    <div style="padding: 12px; font-family: system-ui; min-width: 200px;">
                        <div style="display: flex; align-items: center; margin-bottom: 8px;">
                            <div style="width: 24px; height: 24px; background: linear-gradient(135deg, #3b82f6, #1e40af); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin-right: 8px;">
                                <svg width="12" height="12" fill="white" viewBox="0 0 24 24">
                                    <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                                </svg>
                            </div>
                            <h3 style="margin: 0; color: #1f2937; font-size: 14px; font-weight: 600;">📍 Seçili Lokasyon</h3>
                        </div>
                        <div style="background: #f8fafc; padding: 8px; border-radius: 6px; margin-bottom: 6px;">
                            <div style="font-size: 12px; margin-bottom: 2px;"><strong>📏 Koordinatlar:</strong> {{ $lat }}, {{ $lng }}</div>
                            <div style="font-size: 10px; color: #6b7280;">🎯 Marker'ı sürükleyerek konumu ayarlayabilirsiniz</div>
                        </div>
                    </div>
                `).openPopup();
                
                console.log('Location picker map initialized successfully');
                
                // Force map to resize after a short delay
                setTimeout(() => {
                    if (this.map) {
                        this.map.invalidateSize();
                    }
                }, 100);
                
            } catch (error) {
                console.error('Error initializing location picker map:', error);
            }
        },
        
        updateCoords(lat, lng) {
            // Koordinatları güncelle
            this.currentCoords = `${lat.toFixed(6)}, ${lng.toFixed(6)}`;
            
            // Form alanlarını güncelle - Livewire data binding
            if (this.$wire) {
                this.$wire.set('data.lat_allowed', lat);
                this.$wire.set('data.lng_allowed', lng);
            }
            
            // Konum bilgisini güncelle
            this.locationInfo = `Koordinatlar başarıyla güncellendi ✅`;
            
            // Get radius from form if available
            const radiusInput = document.querySelector('input[name="radius_allowed"]');
            const radius = radiusInput ? parseInt(radiusInput.value) || 100 : 100;
            
            // Update circle radius if circle exists
            if (this.circle) {
                this.circle.setRadius(radius);
            }
            
            // Update popup with smaller styling
            this.marker.setPopupContent(`
                <div style="padding: 12px; font-family: system-ui; min-width: 200px;">
                    <div style="display: flex; align-items: center; margin-bottom: 8px;">
                        <div style="width: 24px; height: 24px; background: linear-gradient(135deg, #3b82f6, #1e40af); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin-right: 8px;">
                            <svg width="12" height="12" fill="white" viewBox="0 0 24 24">
                                <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                            </svg>
                        </div>
                        <h3 style="margin: 0; color: #1f2937; font-size: 14px; font-weight: 600;">📍 Seçili Lokasyon</h3>
                    </div>
                    <div style="background: #f8fafc; padding: 8px; border-radius: 6px; margin-bottom: 6px;">
                        <div style="font-size: 12px; margin-bottom: 2px;"><strong>📏 Koordinatlar:</strong> \${lat.toFixed(6)}, \${lng.toFixed(6)}</div>
                        <div style="font-size: 12px; margin-bottom: 2px;"><strong>🎯 Çalışma Yarıçapı:</strong> \${radius}m</div>
                        <div style="font-size: 10px; color: #6b7280;">🎯 Marker'ı sürükleyerek konumu ayarlayabilirsiniz</div>
                    </div>
                </div>
            `);
        },
        
        // Function to update circle radius when form field changes
        updateRadius(radius) {
            if (this.circle) {
                this.circle.setRadius(parseInt(radius) || 100);
            }
        }
    };
    
    // Expose instance globally for form interactions
    window.locationMapPickerInstance = instance;
    
    return instance;
}
</script>
@endpush
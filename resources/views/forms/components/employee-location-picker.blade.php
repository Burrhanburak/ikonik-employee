<div 
    x-data="employeeLocationPicker()" 
    x-init="init()" 
    x-on:livewire:component-rendered="handleLivewireUpdate()"
    style="display: flex; flex-direction: column; gap: 12px;">
    <!-- Status and Button -->
    <div style="display: flex; align-items: center; justify-content: space-between; padding: 12px; background: #f9fafb; border-radius: 8px;">
        <div style="display: flex; align-items: center; gap: 8px;">
            <span x-show="!isLoading">📍</span>
            <span x-show="isLoading">⏳</span>
            <span style="font-size: 14px;" x-text="locationStatus">Konum alınıyor...</span>
        </div>
        <button 
            @click="getCurrentLocation()"
            style="padding: 8px; background: #3b82f6; color: white; border-radius: 8px; border: none; cursor: pointer; transition: background 0.2s;"
            :disabled="isLoading"
            title="Konumu Yenile"
        >
            <svg x-show="!isLoading" style="width: 16px; height: 16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
            </svg>
            <svg x-show="isLoading" style="width: 16px; height: 16px; animation: spin 1s linear infinite;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
            </svg>
        </button>
    </div>

    <!-- Map -->
    <div style="border-radius: 8px; overflow: hidden; border: 1px solid #e5e7eb; position: relative; z-index: 1;">
        <div id="employee-location-map" style="height: 250px; width: 100%; position: relative; z-index: 1;"></div>
    </div>

    <!-- Coordinates -->
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 8px; font-size: 12px;">
        <div style="text-align: center; padding: 8px; background: #f9fafb; border-radius: 6px;">
            <div style="font-weight: 500;">Enlem</div>
            <div style="font-family: monospace;" x-text="latitude || 'Bekleniyor...'"></div>
        </div>
        <div style="text-align: center; padding: 8px; background: #f9fafb; border-radius: 6px;">
            <div style="font-weight: 500;">Boylam</div>
            <div style="font-family: monospace;" x-text="longitude || 'Bekleniyor...'"></div>
        </div>
    </div>
</div>

@push('scripts')
<style>
@keyframes spin {
    from { transform: rotate(0deg); }
    to { transform: rotate(360deg); }
}
</style>
<script>
function employeeLocationPicker() {
    return {
        map: null,
        marker: null,
        latitude: null,
        longitude: null,
        isLoading: false,
        locationStatus: 'Konum alınıyor...',
        
        init() {
            console.log('Employee location picker initialized');
            this.initMap();
            
            // Önce localStorage'dan konum verilerini kontrol et
            this.loadLocationFromStorage();
            
            // Map visibility koruması
            this.setupMapVisibilityWatch();
        },
        
        handleLivewireUpdate() {
            console.log('Livewire component updated, checking map...');
            setTimeout(() => {
                const container = document.getElementById('employee-location-map');
                if (container && !this.map) {
                    console.log('Map lost after Livewire update, reinitializing...');
                    this.initMap();
                    // Konumu tekrar al
                    this.getCurrentLocation();
                } else if (container && this.map) {
                    console.log('Map exists, invalidating size...');
                    this.map.invalidateSize();
                }
            }, 100);
        },
        
        setupMapVisibilityWatch() {
            // Her 2 saniyede kontrol et, harita kaybolmuş mu
            setInterval(() => {
                const container = document.getElementById('employee-location-map');
                if (container) {
                    // Container varsa ve harita yoksa veya gizlenmişse
                    if (!this.map || container.style.display === 'none' || !container.hasChildNodes() || container.innerHTML.trim() === '') {
                        console.log('Map lost or hidden, reinitializing...');
                        container.style.display = 'block';
                        container.style.visibility = 'visible';
                        this.initMap();
                        if (this.latitude && this.longitude) {
                            // Önceki konumu geri yükle
                            this.map.setView([this.latitude, this.longitude], 16);
                            if (this.marker) {
                                this.map.removeLayer(this.marker);
                            }
                            this.marker = L.marker([this.latitude, this.longitude])
                                .addTo(this.map)
                                .bindPopup('Mevcut konumunuz')
                                .openPopup();
                        }
                    } else if (this.map) {
                        this.map.invalidateSize();
                    }
                }
            }, 2000);
        },
        
        initMap() {
            // Check if map is already initialized
            if (this.map) {
                this.map.remove();
                this.map = null;
            }
            
            // Clear the map container
            const mapContainer = document.getElementById('employee-location-map');
            if (mapContainer) {
                mapContainer._leaflet_id = null;
                // Set z-index for map container
                mapContainer.style.zIndex = '1';
                mapContainer.style.position = 'relative';
            }
            
            this.map = L.map('employee-location-map', {
                zoomControl: true
            }).setView([41.0082, 28.9784], 13);
            
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap contributors'
            }).addTo(this.map);
            
            // Set z-index for leaflet controls
            setTimeout(() => {
                const leafletControls = document.querySelectorAll('.leaflet-control-container .leaflet-top, .leaflet-control-container .leaflet-bottom');
                leafletControls.forEach(control => {
                    control.style.zIndex = '2';
                });
            }, 100);
        },
        
        getCurrentLocation() {
            this.isLoading = true;
            this.locationStatus = 'Konum alınıyor...';
            
            if (!navigator.geolocation) {
                this.locationStatus = 'Tarayıcınız konum desteği sağlamıyor';
                this.isLoading = false;
                return;
            }
            
            navigator.geolocation.getCurrentPosition(
                (position) => {
                    this.latitude = position.coords.latitude;
                    this.longitude = position.coords.longitude;
                    
                    // Update hidden form fields - Livewire ile direkt iletişim
                    if (window.Livewire && this.$wire) {
                        // Check-in ve check-out sayfaları için farklı alan isimleri
                        if (this.$wire.data.hasOwnProperty('checkout_latitude')) {
                            this.$wire.set('data.checkout_latitude', this.latitude);
                            this.$wire.set('data.checkout_longitude', this.longitude);
                            console.log('Checkout location set via Livewire:', this.latitude, this.longitude);
                        } else {
                            this.$wire.set('data.latitude', this.latitude);
                            this.$wire.set('data.longitude', this.longitude);
                            console.log('Checkin location set via Livewire:', this.latitude, this.longitude);
                        }
                    } else {
                        // Fallback: Input alanlarını bul ve doldur
                        const latInput = document.querySelector('input[wire\\:model="data.latitude"], input[wire\\:model="data.checkout_latitude"], input[name*="latitude"]');
                        const lngInput = document.querySelector('input[wire\\:model="data.longitude"], input[wire\\:model="data.checkout_longitude"], input[name*="longitude"]');
                        
                        if (latInput) {
                            latInput.value = this.latitude;
                            latInput.dispatchEvent(new Event('input', { bubbles: true }));
                        }
                        if (lngInput) {
                            lngInput.value = this.longitude;
                            lngInput.dispatchEvent(new Event('input', { bubbles: true }));
                        }
                        console.log('Location set via input fields:', this.latitude, this.longitude);
                    }
                    
                    // Update map
                    this.map.setView([this.latitude, this.longitude], 16);
                    
                    if (this.marker) {
                        this.map.removeLayer(this.marker);
                    }
                    
                    this.marker = L.marker([this.latitude, this.longitude])
                        .addTo(this.map)
                        .bindPopup('Mevcut konumunuz')
                        .openPopup();
                    
                    this.locationStatus = `Konum başarıyla alındı (±${Math.round(position.coords.accuracy)}m)`;
                    this.isLoading = false;
                    
                    // Konum bilgilerini localStorage'a kaydet
                    this.saveLocationToStorage();
                },
                (error) => {
                    let message = 'Konum alınamadı';
                    switch(error.code) {
                        case error.PERMISSION_DENIED:
                            message = 'Konum izni reddedildi';
                            break;
                        case error.POSITION_UNAVAILABLE:
                            message = 'Konum bilgisi mevcut değil';
                            break;
                        case error.TIMEOUT:
                            message = 'Konum alma zaman aşımı';
                            break;
                    }
                    this.locationStatus = message;
                    this.isLoading = false;
                },
                {
                    enableHighAccuracy: true,
                    timeout: 10000,
                    maximumAge: 300000
                }
            );
        },
        
        saveLocationToStorage() {
            if (this.latitude && this.longitude) {
                const locationData = {
                    latitude: this.latitude,
                    longitude: this.longitude,
                    timestamp: Date.now(),
                    status: this.locationStatus
                };
                localStorage.setItem('employee_location_data', JSON.stringify(locationData));
                console.log('Location data saved to localStorage');
            }
        },
        
        loadLocationFromStorage() {
            try {
                const stored = localStorage.getItem('employee_location_data');
                if (stored) {
                    const locationData = JSON.parse(stored);
                    const hoursPassed = (Date.now() - locationData.timestamp) / (1000 * 60 * 60);
                    
                    // Eğer konum 2 saatten eski değilse kullan
                    if (hoursPassed < 2 && locationData.latitude && locationData.longitude) {
                        this.latitude = locationData.latitude;
                        this.longitude = locationData.longitude;
                        this.locationStatus = locationData.status || 'Kaydedilen konum kullanılıyor';
                        
                        // Form alanlarını doldur - Livewire ile direkt iletişim
                        if (window.Livewire && this.$wire) {
                            // Check-in ve check-out sayfaları için farklı alan isimleri
                            if (this.$wire.data.hasOwnProperty('checkout_latitude')) {
                                this.$wire.set('data.checkout_latitude', this.latitude);
                                this.$wire.set('data.checkout_longitude', this.longitude);
                                console.log('Checkout location loaded from storage via Livewire:', this.latitude, this.longitude);
                            } else {
                                this.$wire.set('data.latitude', this.latitude);
                                this.$wire.set('data.longitude', this.longitude);
                                console.log('Checkin location loaded from storage via Livewire:', this.latitude, this.longitude);
                            }
                        } else {
                            // Fallback: Input alanlarını bul ve doldur
                            const latInput = document.querySelector('input[wire\\:model="data.latitude"], input[wire\\:model="data.checkout_latitude"], input[name*="latitude"]');
                            const lngInput = document.querySelector('input[wire\\:model="data.longitude"], input[wire\\:model="data.checkout_longitude"], input[name*="longitude"]');
                            
                            if (latInput) {
                                latInput.value = this.latitude;
                                latInput.dispatchEvent(new Event('input', { bubbles: true }));
                            }
                            if (lngInput) {
                                lngInput.value = this.longitude;
                                lngInput.dispatchEvent(new Event('input', { bubbles: true }));
                            }
                            console.log('Location loaded from storage via input fields:', this.latitude, this.longitude);
                        }
                        
                        // Haritayı güncelle
                        this.map.setView([this.latitude, this.longitude], 16);
                        
                        if (this.marker) {
                            this.map.removeLayer(this.marker);
                        }
                        
                        this.marker = L.marker([this.latitude, this.longitude])
                            .addTo(this.map)
                            .bindPopup('Kaydedilen konumunuz')
                            .openPopup();
                        
                        console.log('Location loaded from localStorage');
                        return true; // Konum yüklendi
                    } else {
                        console.log('Stored location is too old, getting fresh location');
                        localStorage.removeItem('employee_location_data');
                    }
                }
            } catch (error) {
                console.error('Error loading location from storage:', error);
                localStorage.removeItem('employee_location_data');
            }
            
            // Kaydedilmiş konum yoksa veya eski ise yeni konum al
            this.getCurrentLocation();
            return false;
        }
    }
}
</script>
@endpush
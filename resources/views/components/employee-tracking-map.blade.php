@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" 
      integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" 
      crossorigin="" />
@endpush

<div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 overflow-hidden" 
     x-data="employeeMap()" 
     x-init="setTimeout(() => initMap(), 100)">
    
    <!-- Map Header -->
    <div class="bg-gradient-to-r from-blue-600 to-blue-700 p-4 text-white">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between space-y-3 md:space-y-0">
            <div>
                <h2 class="text-lg font-bold mb-1">🗺️ Çalışan Takip Haritası</h2>
                <p class="text-blue-100 text-sm">Çalışanların anlık konumları ve durum bilgileri</p>
            </div>
            
            <div class="flex flex-wrap gap-2 text-xs">
                <div class="flex items-center space-x-1 px-2 py-1 bg-white/10 rounded">
                    <div class="w-2 h-2 bg-blue-300 rounded-full"></div>
                    <span>Lokasyonlar</span>
                </div>
                <div class="flex items-center space-x-1 px-2 py-1 bg-white/10 rounded">
                    <div class="w-2 h-2 bg-green-300 rounded-full"></div>
                    <span>Alan İçi</span>
                </div>
                <div class="flex items-center space-x-1 px-2 py-1 bg-white/10 rounded">
                    <div class="w-2 h-2 bg-red-300 rounded-full"></div>
                    <span>Alan Dışı</span>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Map Container -->
    <div id="employee-tracking-map" style="height: 500px; width: 100%;"></div>
</div>

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" 
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" 
        crossorigin=""></script>

<script>
    function employeeMap() {
        let map;
        let markers = [];
        
        return {
            initMap() {
                // Check if Leaflet is loaded
                if (typeof L === 'undefined') {
                    console.error('Leaflet is not loaded!');
                    return;
                }
                
                console.log('Initializing employee tracking map...');
                
                try {
                    // Initialize map
                    map = L.map('employee-tracking-map').setView([41.0082, 28.9784], 11);
                    
                    // Add tiles
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
                
                const locations = @json($locations);
                const checkins = @json($checkins);
                
                console.log('Locations:', locations);
                console.log('Checkins:', checkins);
                
                // Create custom icons with smaller sizes
                const locationIcon = L.divIcon({
                    className: 'custom-location-icon',
                    html: `
                        <div style="
                            background: #3b82f6; 
                            border: 2px solid white; 
                            border-radius: 50% 50% 50% 0; 
                            width: 20px; 
                            height: 20px; 
                            transform: rotate(-45deg);
                            display: flex;
                            align-items: center;
                            justify-content: center;
                            box-shadow: 0 2px 4px rgba(0,0,0,0.2);
                        ">
                            <span style="transform: rotate(45deg); font-size: 10px;">🏢</span>
                        </div>
                    `,
                    iconSize: [20, 20],
                    iconAnchor: [10, 20],
                    popupAnchor: [0, -20]
                });
                
                const employeeInAreaIcon = L.divIcon({
                    className: 'custom-employee-icon',
                    html: `
                        <div style="
                            background: #22c55e; 
                            border: 2px solid white; 
                            border-radius: 50% 50% 50% 0; 
                            width: 18px; 
                            height: 18px; 
                            transform: rotate(-45deg);
                            display: flex;
                            align-items: center;
                            justify-content: center;
                            box-shadow: 0 2px 4px rgba(0,0,0,0.2);
                        ">
                            <span style="transform: rotate(45deg); font-size: 8px; color: white; font-weight: bold;">✓</span>
                        </div>
                    `,
                    iconSize: [18, 18],
                    iconAnchor: [9, 18],
                    popupAnchor: [0, -18]
                });
                
                const employeeOutAreaIcon = L.divIcon({
                    className: 'custom-employee-icon',
                    html: `
                        <div style="
                            background: #ef4444; 
                            border: 2px solid white; 
                            border-radius: 50% 50% 50% 0; 
                            width: 18px; 
                            height: 18px; 
                            transform: rotate(-45deg);
                            display: flex;
                            align-items: center;
                            justify-content: center;
                            box-shadow: 0 2px 4px rgba(0,0,0,0.2);
                        ">
                            <span style="transform: rotate(45deg); font-size: 8px; color: white; font-weight: bold;">!</span>
                        </div>
                    `,
                    iconSize: [18, 18],
                    iconAnchor: [9, 18],
                    popupAnchor: [0, -18]
                });
                
                // Add location markers
                locations.forEach(location => {
                    const marker = L.marker([location.lat, location.lng], {
                        icon: locationIcon
                    }).addTo(map).bindPopup(`
                        <div style="padding: 12px; font-family: system-ui; min-width: 200px;">
                            <h3 style="margin: 0 0 8px 0; color: #1f2937; font-size: 14px; font-weight: 600;">📍 ${location.name}</h3>
                            <div style="font-size: 12px; margin-bottom: 4px;"><strong>Yarıçap:</strong> ${location.radius}m</div>
                            <div style="color: #6b7280; font-size: 11px;">
                                ${location.lat.toFixed(4)}, ${location.lng.toFixed(4)}
                            </div>
                        </div>
                    `);
                    
                    // Add work area circle
                    const circle = L.circle([location.lat, location.lng], {
                        radius: location.radius,
                        fillColor: '#3b82f6',
                        color: '#1e40af',
                        weight: 1,
                        opacity: 0.6,
                        fillOpacity: 0.1
                    }).addTo(map);
                    
                    markers.push(marker);
                    markers.push(circle);
                });
                
                // Add employee markers
                checkins.forEach(checkin => {
                    console.log('Processing checkin:', checkin);
                    console.log('Selfie photo:', checkin.selfie_photo ? 'EXISTS' : 'NOT FOUND');
                    
                    const isInArea = checkin.is_in_area;
                    const icon = isInArea ? employeeInAreaIcon : employeeOutAreaIcon;
                    const color = isInArea ? '#22c55e' : '#ef4444';
                    const statusText = isInArea ? 'İzinli Alan' : 'Alan Dışı';
                    
                    // Create photo HTML if available
                    let photoHtml = '';
                    if (checkin.selfie_photo) {
                        photoHtml = `
                            <div style="text-align: center; margin-bottom: 8px;">
                                <img src="${checkin.selfie_photo}" 
                                     style="width: 60px; height: 60px; object-fit: cover; border-radius: 6px; border: 2px solid ${color};" 
                                     alt="Check-in Selfie">
                                <div style="font-size: 9px; color: #6b7280; margin-top: 2px;">Check-in Selfie</div>
                            </div>
                        `;
                    } else if (checkin.employee_photo) {
                        photoHtml = `
                            <div style="text-align: center; margin-bottom: 8px;">
                                <img src="/storage/${checkin.employee_photo}" 
                                     style="width: 50px; height: 50px; object-fit: cover; border-radius: 50%; border: 2px solid ${color};" 
                                     alt="Profile Photo">
                                <div style="font-size: 9px; color: #6b7280; margin-top: 2px;">Profil Fotoğrafı</div>
                            </div>
                        `;
                    }
                    
                    const marker = L.marker([checkin.lat, checkin.lng], {
                        icon: icon
                    }).addTo(map).bindPopup(`
                        <div style="padding: 12px; font-family: system-ui; min-width: 220px;">
                            ${photoHtml}
                            <div style="text-align: center; margin-bottom: 8px;">
                                <h3 style="margin: 0 0 2px 0; color: #1f2937; font-size: 14px; font-weight: 600;">👤 ${checkin.employee_name}</h3>
                                <div style="font-size: 10px; color: #6b7280;">${checkin.employee_email}</div>
                            </div>
                            <div style="background: ${color}; color: white; text-align: center; padding: 4px 8px; border-radius: 4px; margin-bottom: 8px; font-size: 11px; font-weight: 600;">
                                ${statusText}
                            </div>
                            <div style="font-size: 11px;">
                                <div style="margin-bottom: 3px;"><strong>📍 Lokasyon:</strong> ${checkin.location_name}</div>
                                <div style="margin-bottom: 3px;"><strong>🕐 Check-in:</strong> ${checkin.time}</div>
                                <div style="margin-bottom: 3px;"><strong>📏 Mesafe:</strong> ${checkin.distance_to_center}m</div>
                            </div>
                        </div>
                    `);
                    
                    markers.push(marker);
                });
                
                // Fit map to show all markers
                if (markers.length > 0) {
                    const markerGroup = markers.filter(m => m instanceof L.Marker);
                    if (markerGroup.length > 0) {
                        const group = new L.featureGroup(markerGroup);
                        map.fitBounds(group.getBounds().pad(0.1));
                        
                        // Prevent excessive zoom
                        setTimeout(() => {
                            if (map.getZoom() > 15) {
                                map.setZoom(15);
                            }
                        }, 100);
                    }
                }
            }
        }
    }
</script>
@endpush
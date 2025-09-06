@if($checkin)
<div style="background: linear-gradient(to right, #eff6ff, #eef2ff); padding: 24px; border-radius: 12px; border: 1px solid #bfdbfe;">
    <div style="grid-template-columns: 1fr 1fr; gap: 16px;">
        <!-- Check-in Bilgileri -->
        <div style="display: flex; flex-direction: column; gap: 12px;">
            <h4 style="font-weight: 600; color: #111827; display: flex; align-items: center;">
                <svg style="width: 16px; height: 16px; margin-right: 8px; color: #10b981;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013 3v1"></path>
                </svg>
                Check-in Bilgileri
            </h4>
            <div style="display: flex; flex-direction: column; gap: 8px; font-size: 14px;">
                <div style="display: flex; justify-content: space-between;">
                    <span style="color: #6b7280;">📍 Lokasyon:</span>
                    <span style="font-weight: 500;">{{ $checkin->location->name }}</span>
                </div>
                <div style="display: flex; justify-content: space-between;">
                    <span style="color: #6b7280;">⏰ Başlangıç:</span>
                    <span style="font-weight: 500;">{{ $checkin->created_at->format('d/m/Y H:i') }}</span>
                </div>
                <div style="display: flex; justify-content: space-between;">
                    <span style="color: #6b7280;">📊 Durum:</span>
                    <span style="padding: 4px 8px; background-color: #dcfce7; color: #166534; border-radius: 9999px; font-size: 12px; font-weight: 500;">
                        {{ $checkin->status === 'active' ? 'Aktif' : ucfirst($checkin->status) }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Çalışma Süresi -->
        <div style="display: flex; flex-direction: column; gap: 12px;">
            <h4 style="font-weight: 600; color: #111827; display: flex; align-items: center;">
                <svg style="width: 16px; height: 16px; margin-right: 8px; color: #3b82f6;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                Çalışma Süresi
            </h4>
            <div style="display: flex; flex-direction: column; gap: 8px; font-size: 14px;">
                @php
                    $checkinTime = $checkin->created_at;
                    $currentTime = now();
                    
                    // Manuel hesap yapalım - saat farkı test
                    $checkinUnix = $checkinTime->timestamp;
                    $currentUnix = $currentTime->timestamp;
                    $diffSeconds = $currentUnix - $checkinUnix;
                    
                    $hours = floor($diffSeconds / 3600);
                    $minutes = floor(($diffSeconds % 3600) / 60);
                    
                    // Çalışma süresini hesapla
                @endphp
                <div style="display: flex; justify-content: space-between;">
                    <span style="color: #6b7280;">⏱️ Geçen Süre:</span>
                    <span style="font-weight: 500; color: #2563eb;">{{ $hours }}s {{ $minutes }}dk</span>
                </div>
                
                @if($checkin->shift)
                <div style="display: flex; justify-content: space-between;">
                    <span style="color: #6b7280;">🎯 Planlanan:</span>
                    <span style="font-weight: 500;">{{ $checkin->shift->start_time }} - {{ $checkin->shift->end_time }}</span>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- İstatistik Göstergesi -->
    <div style="margin-top: 16px; padding-top: 16px; border-top: 1px solid #bfdbfe;">
        <div style="display: flex; align-items: center; justify-content: space-between; font-size: 14px;">
            <span style="color: #6b7280;">Mesai devam ediyor...</span>
            <div style="display: flex; align-items: center; color: #059669;">
                <div style="width: 8px; height: 8px; background-color: #10b981; border-radius: 50%; margin-right: 8px; animation: pulse 2s infinite;"></div>
                <span style="font-weight: 500;">Aktif</span>
            </div>
        </div>
    </div>
</div>
@else
<div style="background-color: #fffbeb; padding: 16px; border-radius: 8px; border: 1px solid #fed7aa;">
    <div style="display: flex; align-items: center;">
        <svg style="width: 16px; height: 16px; margin-right: 8px; color: #d97706;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.998-.833-2.768 0L3.046 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
        </svg>
        <span style="color: #92400e; font-weight: 500;">Aktif check-in bulunamadı</span>
    </div>
</div>
@endif

<style>
@keyframes pulse {
    0%, 100% { opacity: 1; }
    50% { opacity: 0.5; }
}
</style>
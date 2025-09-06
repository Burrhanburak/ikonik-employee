@php
    $state = $getState();
@endphp

<div class="space-y-2">
    @if($state)
        @if(str_starts_with($state, 'data:image/'))
            {{-- Base64 encoded image --}}
            <div class="flex flex-col items-center space-y-2">
                <img 
                    src="{{ $state }}" 
                    alt="Selfie Fotoğrafı" 
                    class="max-w-xs max-h-64 rounded-lg shadow-lg object-cover border"
                    style="max-width: 300px; max-height: 300px;"
                />
                <p class="text-sm text-gray-500">Check-in Selfie Fotoğrafı</p>
            </div>
        @else
            {{-- Regular file path --}}
            <div class="flex flex-col items-center space-y-2">
                <img 
                    src="{{ Storage::url($state) }}" 
                    alt="Selfie Fotoğrafı" 
                    class="max-w-xs max-h-64 rounded-lg shadow-lg object-cover border"
                    style="max-width: 300px; max-height: 300px;"
                />
                <p class="text-sm text-gray-500">Check-in Selfie Fotoğrafı</p>
            </div>
        @endif
    @else
        <div class="flex flex-col items-center justify-center h-32 bg-gray-50 rounded-lg border-2 border-dashed border-gray-300">
            <svg class="w-8 h-8 text-gray-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
            </svg>
            <p class="text-sm text-gray-500">Fotoğraf bulunamadı</p>
        </div>
    @endif
</div>

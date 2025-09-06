@php
    $locationId = $getState();
    $location = \App\Models\Location::find($locationId);
@endphp

@if($location)
<div class="bg-blue-50 dark:bg-blue-900/20 rounded-lg p-4 border border-blue-200 dark:border-blue-800">
    <div class="flex items-start space-x-3">
        <div class="p-2 bg-blue-500 rounded-lg flex-shrink-0">
            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
            </svg>
        </div>
        <div class="flex-1">
            <h4 class="font-semibold text-blue-900 dark:text-blue-100 mb-2">{{ $location->name }}</h4>
            <div class="space-y-2 text-sm">
                <div class="flex justify-between items-center">
                    <span class="text-blue-700 dark:text-blue-300">Çalışma Yarıçapı:</span>
                    <span class="font-medium text-blue-900 dark:text-blue-100">{{ $location->radius_allowed }}m</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-blue-700 dark:text-blue-300">Koordinatlar:</span>
                    <span class="font-mono text-xs text-blue-900 dark:text-blue-100">{{ $location->lat_allowed }}, {{ $location->lng_allowed }}</span>
                </div>
            </div>
            <div class="mt-3 p-2 bg-blue-100 dark:bg-blue-800/30 rounded text-xs text-blue-800 dark:text-blue-200">
                ℹ️ Bu lokasyonda check-in yapmak için {{ $location->radius_allowed }} metre yarıçapı içinde olmalısınız.
            </div>
        </div>
    </div>
</div>
@endif
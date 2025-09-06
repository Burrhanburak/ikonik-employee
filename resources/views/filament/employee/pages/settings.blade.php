<x-filament-panels::page>
    {{ $this->form }}
    
    <x-filament-actions::actions :actions="$this->getCachedFormActions()" />
</x-filament-panels::page>

<x-filament-panels::page>
    <div class="max-w-4xl mx-auto">
        <!-- Welcome Message -->
        <div class="mb-8 text-center">
            <div class="flex justify-center mb-4">
                <div class="p-4 bg-gradient-to-br from-green-400 to-green-600 rounded-full">
                    <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
            
            @if($this->isAuthenticated && $this->employee)
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">
                    Hoş geldin {{ $this->employee->name }}! 👋
                </h2>
                <p class="text-gray-600 dark:text-gray-400">
                    Check-in yapmak için aşağıdaki formu doldurun
                </p>
            @else
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">
                    Mesai Check-In Sistemi
                </h2>
                <p class="text-gray-600 dark:text-gray-400">
                    Check-in yapmak için sistemde kayıtlı hesabınızla giriş yapın
                </p>
            @endif
        </div>

        <!-- Form -->
        <form wire:submit="checkIn">
            {{ $this->form }}
            
            <div class="mt-8 flex justify-center">
                {{ $this->getFormActions()[0] }}
            </div>
        </form>
        
        <!-- Help Section -->
        <div class="mt-12 bg-blue-50 dark:bg-blue-900/20 rounded-lg p-6">
            <div class="flex items-start space-x-3">
                <svg class="w-6 h-6 text-blue-500 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <div>
                    <h3 class="font-semibold text-blue-900 dark:text-blue-100 mb-2">
                        💡 Nasıl Kullanırım?
                    </h3>
                    <ul class="text-sm text-blue-800 dark:text-blue-200 space-y-1">
                        <li>• <strong>Sistemde kayıtlı hesabınızla:</strong> E-posta ve şifrenizi girin</li>
                        <li>• <strong>Giriş yaptıktan sonra:</strong> Lokasyon seçin, konumunuzu doğrulayın ve selfie çekin</li>
                        <li>• <strong>Check-in için:</strong> Belirlenen çalışma alanında olmanız gerekir</li>
                        <li>• <strong>Hesabınız yoksa:</strong> Lütfen yöneticinizle iletişime geçin</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</x-filament-panels::page>

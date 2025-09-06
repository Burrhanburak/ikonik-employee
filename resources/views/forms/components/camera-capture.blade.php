<div x-data="cameraCapture()" 
     x-init="init()"
     wire:ignore
     class="space-y-4">
    
    <!-- Camera Controls -->
    <div class="bg-gray-50 dark:bg-gray-800 rounded-lg p-4">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between space-y-4 sm:space-y-0">
            <div>
                <h4 class="font-medium text-gray-900 dark:text-white mb-1">📸 Check-in Fotoğrafı</h4>
                <p class="text-sm text-gray-600 dark:text-gray-400">Mesai başlangıcı için selfie çekin</p>
            </div>
            
            <div class="flex space-x-3">
                <button @click="startCamera()" 
                        x-show="!isCameraActive && !capturedImage"
                        class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors flex items-center space-x-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                    <span>Kamerayı Aç</span>
                </button>
                
                <button @click="capturePhoto()" 
                        x-show="isCameraActive"
                        class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors flex items-center space-x-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                    </svg>
                    <span>Fotoğraf Çek</span>
                </button>
                
                <button @click="retakePhoto()" 
                        x-show="capturedImage"
                        class="px-4 py-2 bg-orange-600 text-white rounded-lg hover:bg-orange-700 transition-colors flex items-center space-x-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                    </svg>
                    <span>Yeniden Çek</span>
                </button>
            </div>
        </div>
    </div>

    <!-- Camera/Photo Display -->
    <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
        <!-- Camera Video -->
        <div x-show="isCameraActive" class="relative">
            <video x-ref="video" 
                   autoplay 
                   playsinline 
                   class="w-full h-80 object-cover bg-black"
                   style="transform: scaleX(-1);">
            </video>
            
            <!-- Camera Overlay -->
            <div class="absolute inset-0 flex items-center justify-center pointer-events-none">
                <div class="border-2 border-white border-dashed rounded-full w-40 h-40 opacity-50"></div>
            </div>
            
            <!-- Camera Info -->
            <div class="absolute bottom-4 left-4 bg-black/50 backdrop-blur-sm text-white px-3 py-1 rounded-full text-sm">
                📹 Kamera Aktif - Merkezde durun
            </div>
        </div>
        
        <!-- Captured Photo -->
        <div x-show="capturedImage" class="relative">
            <img x-ref="capturedImg" 
                 src="" 
                 alt="Çekilen fotoğraf" 
                 class="w-full h-80 object-cover">
            
            <div class="absolute bottom-4 left-4 bg-green-500/80 backdrop-blur-sm text-white px-3 py-1 rounded-full text-sm flex items-center space-x-1">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <span>Fotoğraf hazır</span>
            </div>
        </div>
        
        <!-- Placeholder -->
        <div x-show="!isCameraActive && !capturedImage" class="h-80 flex items-center justify-center bg-gray-100 dark:bg-gray-700">
            <div class="text-center">
                <svg class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
                </svg>
                <p class="text-gray-500 dark:text-gray-400">Kamerayı açarak fotoğraf çekin</p>
            </div>
        </div>
        
        <!-- Error State -->
        <div x-show="error" class="h-80 flex items-center justify-center bg-red-50 dark:bg-red-900/20">
            <div class="text-center">
                <svg class="w-16 h-16 mx-auto text-red-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.732 15.5c-.77.833.192 2.5 1.732 2.5z"></path>
                </svg>
                <p class="text-red-600 dark:text-red-400 mb-2">Kamera Hatası</p>
                <p class="text-red-500 text-sm" x-text="error"></p>
            </div>
        </div>
    </div>

    <!-- Hidden canvas for processing -->
    <canvas x-ref="canvas" style="display: none;"></canvas>
</div>

@push('scripts')
<script>
function cameraCapture() {
    return {
        stream: null,
        isCameraActive: false,
        capturedImage: null,
        error: null,
        
        init() {
            // Initialize component
        },
        
        async startCamera() {
            this.error = null;
            
            try {
                this.stream = await navigator.mediaDevices.getUserMedia({ 
                    video: { 
                        width: { ideal: 1280 },
                        height: { ideal: 720 },
                        facingMode: 'user' // Front camera for selfies
                    } 
                });
                
                this.$refs.video.srcObject = this.stream;
                this.isCameraActive = true;
                
            } catch (err) {
                this.handleCameraError(err);
            }
        },
        
        capturePhoto() {
            const video = this.$refs.video;
            const canvas = this.$refs.canvas;
            const context = canvas.getContext('2d');
            
            // Set canvas dimensions
            canvas.width = video.videoWidth;
            canvas.height = video.videoHeight;
            
            // Flip horizontally for selfie effect
            context.scale(-1, 1);
            context.translate(-canvas.width, 0);
            
            // Draw video frame to canvas
            context.drawImage(video, 0, 0);
            
            // Convert to blob and create file
            canvas.toBlob((blob) => {
                const file = new File([blob], 'checkin-selfie.jpg', { type: 'image/jpeg' });
                
                // Create preview URL
                this.capturedImage = URL.createObjectURL(blob);
                this.$refs.capturedImg.src = this.capturedImage;
                
                // Update file input (if using Filament's FileUpload component)
                this.updateFileInput(file);
                
                // Stop camera
                this.stopCamera();
                
            }, 'image/jpeg', 0.8);
        },
        
        retakePhoto() {
            if (this.capturedImage) {
                URL.revokeObjectURL(this.capturedImage);
                this.capturedImage = null;
            }
            this.startCamera();
        },
        
        stopCamera() {
            if (this.stream) {
                this.stream.getTracks().forEach(track => track.stop());
                this.stream = null;
            }
            this.isCameraActive = false;
        },
        
        updateFileInput(file) {
            // Create a FileList-like object
            const dt = new DataTransfer();
            dt.items.add(file);
            
            // Find the file input (Filament creates inputs with specific naming)
            const fileInput = document.querySelector('input[type="file"][wire\\:model*="selfie_photo"], input[type="file"][name*="selfie_photo"]');
            if (fileInput) {
                fileInput.files = dt.files;
                
                // Trigger change event for Livewire
                const event = new Event('change', { bubbles: true });
                fileInput.dispatchEvent(event);
                
                // Also trigger input event
                const inputEvent = new Event('input', { bubbles: true });
                fileInput.dispatchEvent(inputEvent);
            }
            
            // If using Livewire directly
            if (this.$wire) {
                // Convert file to base64 for Livewire
                const reader = new FileReader();
                reader.onload = (e) => {
                    try {
                        this.$wire.set('data.selfie_photo', [file]);
                    } catch (error) {
                        console.log('Livewire file set error:', error);
                    }
                };
                reader.readAsDataURL(file);
            }
        },
        
        handleCameraError(error) {
            switch(error.name) {
                case 'NotAllowedError':
                    this.error = 'Kamera erişim izni reddedildi. Lütfen tarayıcı ayarlarından kamera iznini verin.';
                    break;
                case 'NotFoundError':
                    this.error = 'Kamera bulunamadı. Lütfen cihazınızda kamera olduğundan emin olun.';
                    break;
                case 'NotSupportedError':
                    this.error = 'Kamera bu tarayıcıda desteklenmiyor.';
                    break;
                default:
                    this.error = 'Kamera başlatılırken bir hata oluştu: ' + error.message;
            }
            console.error('Camera error:', error);
        }
    }
}
</script>
@endpush
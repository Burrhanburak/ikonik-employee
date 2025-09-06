<div x-data="webcamCapture()" style="display: flex; flex-direction: column; gap: 16px;">
    <!-- Webcam Preview -->
    <div style="position: relative; background: #f3f4f6; border-radius: 8px; overflow: hidden;">
        <video 
            x-ref="video" 
            autoplay 
            playsinline
            style="width: 100%; height: auto; min-height: 200px; max-height: 400px; object-fit: cover; transform: scaleX(-1);"
            x-show="!capturedImage && cameraActive">
        </video>
        
        <!-- Captured Image Preview -->
        <div x-show="capturedImage">
            <img :src="capturedImage" alt="Captured Photo" style="width: 100%; height: auto; min-height: 200px; max-height: 400px; object-fit: cover;">
        </div>
        
        <!-- Loading State -->
        <div x-show="!cameraActive && !capturedImage" style="display: flex; align-items: center; justify-content: center;  padding: 20px;">
            <div style="text-align: center; color: #6b7280;">
                <svg style="width: 32px; height: 32px; margin: 0 auto 8px; animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
                </svg>
                <p style="font-size: 14px;">Kamera başlatılıyor...</p>
            </div>
        </div>
        
        <!-- Error State -->
        <div x-show="error" style="display: flex; align-items: center; justify-content: center; height: 256px;">
            <div style="text-align: center; color: #ef4444;">
                <svg style="width: 32px; height: 32px; margin: 0 auto 8px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                </svg>
                <p style="font-size: 14px;" x-text="error"></p>
            </div>
        </div>
    </div>
    
    <!-- Controls -->
    <div style="display: flex; justify-content: center; gap: 16px; margin-top: 16px;">
        <!-- Start Camera Button -->
        <button 
            type="button"
            x-show="!cameraActive && !capturedImage"
            @click="startCamera()"
            style="display: inline-flex; align-items: center; padding: 8px 16px; background: #2563eb; border: none; border-radius: 6px; font-weight: 600; font-size: 12px; color: white;  cursor: pointer; transition: all 0.15s ease;">
            <svg style="width: 12px; height: 12px; margin-right: 8px; display: inline-block; vertical-align: middle;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
            </svg>
            Kamerayı Başlat
        </button>
        
        <!-- Capture Button -->
        <button 
            type="button"
            x-show="cameraActive && !capturedImage"
            @click="capturePhoto()"
            style="display: inline-flex; align-items: center; padding: 8px 16px; background: #16a34a; border: none; border-radius: 6px; font-weight: 600; font-size: 14px; color: white; cursor: pointer; transition: all 0.15s ease;">
            <svg style="width: 16px; height: 16px; margin-right: 8px; display: inline-block; vertical-align: middle;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
            </svg>
            Fotoğraf Çek
        </button>
        
        <!-- Retake Button -->
        <button 
            type="button"
            x-show="capturedImage"
            @click="retakePhoto()"
            style="display: inline-flex; align-items: center; padding: 8px 16px; background: #ea580c; border: none; border-radius: 6px; font-weight: 600; font-size: 12px; color: white; text-transform: uppercase; cursor: pointer; transition: all 0.15s ease;">
            <svg style="width: 14px; height: 14px; margin-right: 8px; display: inline-block; vertical-align: middle;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
            </svg>
            Tekrar Çek
        </button>
        
    </div>
    
    <!-- Hidden Canvas for Capture -->
    <canvas x-ref="canvas" style="display: none;"></canvas>
</div>

<script>
function webcamCapture() {
    return {
        stream: null,
        cameraActive: false,
        capturedImage: null,
        error: null,
        
        async startCamera() {
            this.error = null;
            
            try {
                // Check if running on HTTPS or localhost
                if (location.protocol !== 'https:' && location.hostname !== 'localhost' && location.hostname !== '127.0.0.1') {
                    throw new Error('Kamera erişimi için HTTPS gereklidir. Lütfen güvenli bağlantı kullanın.');
                }
                
                const constraints = {
                    video: {
                        width: { ideal: 1280 },
                        height: { ideal: 720 },
                        facingMode: 'user' // Front camera for selfies
                    }
                };
                
                this.stream = await navigator.mediaDevices.getUserMedia(constraints);
                this.$refs.video.srcObject = this.stream;
                this.cameraActive = true;
                
            } catch (err) {
                console.error('Camera access error:', err);
                
                if (err.name === 'NotAllowedError') {
                    this.error = 'Kamera erişimi reddedildi. Lütfen tarayıcı ayarlarından kamera izni verin.';
                } else if (err.name === 'NotFoundError') {
                    this.error = 'Kamera bulunamadı. Lütfen cihazınızın kamerası olduğundan emin olun.';
                } else if (err.name === 'NotSupportedError') {
                    this.error = 'Kamera bu tarayıcıda desteklenmiyor.';
                } else {
                    this.error = err.message || 'Kamera başlatılamadı. Lütfen tekrar deneyin.';
                }
            }
        },
        
        capturePhoto() {
            const video = this.$refs.video;
            const canvas = this.$refs.canvas;
            const context = canvas.getContext('2d');
            
            // Set canvas dimensions to match video
            canvas.width = video.videoWidth;
            canvas.height = video.videoHeight;
            
            // Draw the video frame to canvas (flip horizontally for natural selfie)
            context.scale(-1, 1);
            context.drawImage(video, -canvas.width, 0, canvas.width, canvas.height);
            
            // Convert to blob and create object URL
            canvas.toBlob((blob) => {
                this.capturedImage = URL.createObjectURL(blob);
                this.stopCamera();
                
                // Automatically use the photo
                this.usePhotoAutomatically(blob);
            }, 'image/jpeg', 0.8);
        },
        
        retakePhoto() {
            if (this.capturedImage) {
                URL.revokeObjectURL(this.capturedImage);
                this.capturedImage = null;
            }
            this.startCamera();
        },
        
        async usePhotoAutomatically(blob) {
            try {
                // Convert blob to base64
                const base64Data = await new Promise((resolve) => {
                    const reader = new FileReader();
                    reader.onloadend = () => resolve(reader.result);
                    reader.readAsDataURL(blob);
                });
                
                // Create File object
                const timestamp = new Date().getTime();
                const file = new File([blob], `webcam-photo-${timestamp}.jpg`, {
                    type: 'image/jpeg'
                });
                
                // Livewire ile direkt iletişim dene
                if (window.Livewire && this.$wire) {
                    this.$wire.set('data.selfie_photo', base64Data);
                    console.log('Photo set via Livewire (auto):', base64Data.substring(0, 50) + '...');
                    return;
                }
                
                // Fallback: Try multiple approaches to find the input
                let input = document.querySelector('input[name="data.selfie_photo"]') ||
                           document.querySelector('input[wire\\:model="data.selfie_photo"]') ||
                           document.querySelector('input[id*="selfie_photo"]') ||
                           document.querySelector('input[type="hidden"][name*="selfie"]');
                           
                console.log('Auto using photo, found input:', input);
                
                if (input) {
                    if (input.type === 'file') {
                        // File input - set files
                        const dataTransfer = new DataTransfer();
                        dataTransfer.items.add(file);
                        input.files = dataTransfer.files;
                        
                        // Trigger events
                        input.dispatchEvent(new Event('change', { bubbles: true }));
                        input.dispatchEvent(new Event('input', { bubbles: true }));
                    } else {
                        // Hidden input - convert to base64
                        const reader = new FileReader();
                        reader.onload = () => {
                            input.value = reader.result;
                            input.dispatchEvent(new Event('change', { bubbles: true }));
                            input.dispatchEvent(new Event('input', { bubbles: true }));
                            
                            // Try to trigger Alpine.js/Livewire update  
                            if (input._x_model) {
                                input._x_model.set(reader.result);
                            }
                            
                            // Modern Livewire dispatch method
                            if (window.Livewire) {
                                window.Livewire.dispatch('refreshComponent');
                            }
                        };
                        reader.readAsDataURL(blob);
                    }
                    
                    console.log('Photo automatically used!');
                } else {
                    console.error('Selfie photo input not found');
                }
                
            } catch (error) {
                console.error('Error auto using photo:', error);
            }
        },

        async usePhoto() {
            if (!this.capturedImage) return;
            
            try {
                // Convert blob URL to blob
                const response = await fetch(this.capturedImage);
                const blob = await response.blob();
                
                // Convert blob to base64
                const base64Data = await new Promise((resolve) => {
                    const reader = new FileReader();
                    reader.onloadend = () => resolve(reader.result);
                    reader.readAsDataURL(blob);
                });
                
                // Create File object
                const timestamp = new Date().getTime();
                const file = new File([blob], `webcam-photo-${timestamp}.jpg`, {
                    type: 'image/jpeg'
                });
                
                // Livewire ile direkt iletişim dene
                if (window.Livewire && this.$wire) {
                    this.$wire.set('data.selfie_photo', base64Data);
                    console.log('Photo set via Livewire (usePhoto):', base64Data.substring(0, 50) + '...');
                    return;
                }
                
                // Fallback: Try multiple approaches to find the input
                let input = document.querySelector('input[name="data.selfie_photo"]') ||
                           document.querySelector('input[wire\\:model="data.selfie_photo"]') ||
                           document.querySelector('input[id*="selfie_photo"]') ||
                           document.querySelector('input[type="hidden"][name*="selfie"]');
                           
                console.log('Found input:', input);
                
                if (input) {
                    if (input.type === 'file') {
                        // File input - set files
                        const dataTransfer = new DataTransfer();
                        dataTransfer.items.add(file);
                        input.files = dataTransfer.files;
                        
                        // Trigger events
                        input.dispatchEvent(new Event('change', { bubbles: true }));
                        input.dispatchEvent(new Event('input', { bubbles: true }));
                    } else {
                        // Hidden input - convert to base64
                        const reader = new FileReader();
                        reader.onload = () => {
                            input.value = reader.result;
                            input.dispatchEvent(new Event('change', { bubbles: true }));
                            input.dispatchEvent(new Event('input', { bubbles: true }));
                            
                            // Try to trigger Alpine.js/Livewire update  
                            if (input._x_model) {
                                input._x_model.set(reader.result);
                            }
                            
                            // Modern Livewire dispatch method
                            if (window.Livewire) {
                                window.Livewire.dispatch('refreshComponent');
                            }
                        };
                        reader.readAsDataURL(blob);
                    }
                    
                    // Success notification
                    alert('Fotoğraf başarıyla eklendi!');
                } else {
                    console.error('Selfie photo input not found');
                    alert('Fotoğraf input bulunamadı. Konsol kontrol edin.');
                }
                
            } catch (error) {
                console.error('Error using photo:', error);
                alert('Fotoğraf kullanılırken hata: ' + error.message);
            }
        },
        
        stopCamera() {
            if (this.stream) {
                this.stream.getTracks().forEach(track => track.stop());
                this.stream = null;
            }
            this.cameraActive = false;
        },
        
        // Cleanup when component is destroyed
        destroy() {
            this.stopCamera();
            if (this.capturedImage) {
                URL.revokeObjectURL(this.capturedImage);
            }
        }
    }
}
</script>

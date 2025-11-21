<template>
    <Teleport to="body">
        <div v-if="isMobile" class="fixed inset-0 z-[9999] bg-gradient-to-br from-sky-600 to-sky-800 flex items-center justify-center p-4">
            <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full p-8 text-center animate-fade-in">
                <!-- Icon -->
                <div class="mb-6 flex justify-center">
                    <div class="bg-red-100 rounded-full p-4">
                        <svg class="w-16 h-16 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                    </div>
                </div>

                <!-- Title -->
                <h2 class="text-2xl font-bold text-gray-900 mb-4">
                    Perangkat Tidak Didukung
                </h2>

                <!-- Message -->
                <p class="text-gray-600 mb-6 leading-relaxed">
                    Aplikasi <span class="font-semibold text-sky-700">Probabilitas Pro</span> hanya dapat diakses melalui laptop atau komputer desktop untuk pengalaman terbaik dan fungsionalitas lengkap.
                </p>

                <!-- Device Info -->
                <div class="bg-gray-50 rounded-lg p-4 mb-6">
                    <div class="flex items-center justify-center text-sm text-gray-700">
                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z" />
                        </svg>
                        <span>Terdeteksi: <strong>{{ deviceType }}</strong></span>
                    </div>
                </div>

                <!-- Instructions -->
                <div class="bg-sky-50 border border-sky-200 rounded-lg p-4 mb-6">
                    <p class="text-sm text-sky-800 font-medium mb-2">
                        💡 Untuk menggunakan aplikasi ini:
                    </p>
                    <ul class="text-sm text-sky-700 text-left space-y-1">
                        <li>✓ Gunakan laptop atau komputer desktop</li>
                        <li>✓ Browser: Chrome, Firefox, atau Edge (terbaru)</li>
                        <li>✓ Resolusi minimal: 1024x768px</li>
                    </ul>
                </div>

                <!-- Footer -->
                <p class="text-xs text-gray-500">
                    Jika Anda yakin ini adalah kesalahan, silakan hubungi administrator.
                </p>
            </div>
        </div>
    </Teleport>
</template>

<script setup>
import { ref, onMounted } from 'vue';

const isMobile = ref(false);
const deviceType = ref('');

const detectMobileDevice = () => {
    const userAgent = navigator.userAgent || navigator.vendor || window.opera;
    
    // Deteksi Android
    if (/android/i.test(userAgent)) {
        deviceType.value = 'Perangkat Android';
        return true;
    }
    
    // Deteksi iOS (iPhone/iPad/iPod)
    if (/iPad|iPhone|iPod/.test(userAgent) && !window.MSStream) {
        deviceType.value = 'Perangkat iOS';
        return true;
    }
    
    // Deteksi Windows Phone
    if (/windows phone/i.test(userAgent)) {
        deviceType.value = 'Windows Phone';
        return true;
    }
    
    // Deteksi tablet dan mobile menggunakan screen size dan touch
    const isTouchDevice = 'ontouchstart' in window || navigator.maxTouchPoints > 0;
    const isSmallScreen = window.innerWidth < 1024;
    
    if (isTouchDevice && isSmallScreen) {
        deviceType.value = 'Perangkat Mobile';
        return true;
    }
    
    // Deteksi tambahan menggunakan media query
    if (window.matchMedia && window.matchMedia('(max-width: 1023px)').matches) {
        const hasTouch = 'ontouchstart' in window;
        if (hasTouch) {
            deviceType.value = 'Perangkat Mobile';
            return true;
        }
    }
    
    return false;
};

onMounted(() => {
    isMobile.value = detectMobileDevice();
    
    // Prevent scrolling on mobile
    if (isMobile.value) {
        document.body.style.overflow = 'hidden';
    }
});
</script>

<style scoped>
@keyframes fade-in {
    from {
        opacity: 0;
        transform: scale(0.95);
    }
    to {
        opacity: 1;
        transform: scale(1);
    }
}

.animate-fade-in {
    animation: fade-in 0.3s ease-out;
}
</style>

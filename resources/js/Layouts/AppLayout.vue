<script setup>
import { ref } from 'vue';
import { Link } from '@inertiajs/vue3';
</script>

<template>
    <div class="min-h-screen flex flex-col bg-slate-50">
        <!-- Header - Fixed -->
        <nav class="bg-white border-b border-slate-200 shadow-sm z-50 sticky top-0">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex items-center">
                        <Link href="/" class="flex items-center space-x-3">
                            <div class="w-10 h-10 bg-gradient-to-br from-sky-500 to-blue-600 rounded-lg flex items-center justify-center">
                                <span class="text-white font-bold text-xl">P</span>
                            </div>
                            <span class="text-2xl font-bold bg-gradient-to-r from-sky-600 to-blue-700 bg-clip-text text-transparent">
                                Probabilitas Pro
                            </span>
                        </Link>
                    </div>
                    <div class="flex items-center gap-6">
                        <Link 
                            href="/documentation" 
                            class="text-slate-600 hover:text-sky-600 transition-colors font-medium flex items-center gap-2 group"
                            title="View comprehensive documentation"
                        >
                            <svg class="w-5 h-5 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                            </svg>
                            <span>Dokumentasi</span>
                        </Link>
                        <Link 
                            href="/credits" 
                            class="text-slate-600 hover:text-sky-600 transition-colors font-medium flex items-center gap-2 group"
                            title="View project credits and developer information"
                        >
                            <svg class="w-5 h-5 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span>Kredit</span>
                        </Link>
                        <div class="h-8 w-px bg-slate-300"></div>
                        <span class="text-sm text-slate-600">
                            © 2025 <span class="font-semibold text-sky-600">Ahda Firly Barori</span>
                        </span>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Main Content -->
        <main class="flex-1">
            <slot />
        </main>
    </div>
</template>

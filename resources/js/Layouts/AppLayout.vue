<script setup>
import { ref } from 'vue';
import { Link } from '@inertiajs/vue3';
</script>

<template>
    <div class="min-h-screen bg-slate-50">
        <!-- Header -->
        <nav class="bg-white border-b border-slate-200 shadow-sm">
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
                    <div class="flex items-center">
                        <span class="text-sm text-slate-600">
                            © 2025 <span class="font-semibold text-sky-600">Ahda Firly Barori</span>
                        </span>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Main Content -->
        <main>
            <slot />
        </main>

        <!-- Footer -->
        <footer class="bg-white border-t border-slate-200 mt-auto">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
                <div class="text-center text-sm text-slate-600">
                    <p>
                        <strong>Probabilitas Pro</strong> - Dynamic Expert System using Naive Bayes Classifier
                    </p>
                    <p class="mt-2">
                        Developed by <span class="font-semibold text-sky-600">Ahda Firly Barori</span> © 2025
                    </p>
                </div>
            </div>
        </footer>
    </div>
</template>

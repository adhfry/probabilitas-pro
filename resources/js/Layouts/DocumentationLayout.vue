<template>
    <div class="min-h-screen bg-slate-50 flex flex-col">
        <nav class="bg-white border-b border-slate-200 sticky top-0 z-50 shadow-sm">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex items-center">
                        <Link :href="route('dashboard')" class="flex items-center">
                            <div class="w-10 h-10 bg-gradient-to-br from-sky-500 to-blue-600 rounded-lg flex items-center justify-center">
                                <span class="text-white font-bold text-xl">P</span>
                            </div>
                            <span class="ml-2 text-xl font-bold bg-gradient-to-r from-sky-600 to-blue-700 bg-clip-text text-transparent">Probabilitas Pro</span>
                        </Link>
                    </div>

                    <div class="flex items-center space-x-4">
                        <Link :href="route('dashboard')" class="text-slate-600 hover:text-blue-600 transition-colors font-medium">
                            Dashboard
                        </Link>
                        <Link :href="route('documentation')" class="text-slate-600 hover:text-blue-600 transition-colors font-medium">
                            Dokumentasi
                        </Link>
                        <Link :href="route('credits')" class="text-slate-600 hover:text-blue-600 transition-colors font-medium">
                            Kredit
                        </Link>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Page Content - Scrollable -->
        <main class="flex-1 overflow-y-auto">
            <slot />
        </main>
    </div>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';
</script>

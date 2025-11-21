<script setup>
import { ref, computed, onMounted } from 'vue';
import { Head, router } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import MatrixTable from '@/Components/MatrixTable.vue';
import SidebarConfig from '@/Components/SidebarConfig.vue';
import AnalysisDrawer from '@/Components/AnalysisDrawer.vue';

const props = defineProps({
    project: Object,
    trainingData: Object
});

const sidebarCollapsed = ref(false);
const currentTab = ref('attributes');

const toggleSidebar = () => {
    sidebarCollapsed.value = !sidebarCollapsed.value;
};
</script>

<template>
    <Head :title="`Workspace: ${project.title}`" />

    <AppLayout>
        <div class="fixed inset-0 top-16 flex flex-col overflow-hidden">
            <!-- Workspace Header - Fixed -->
            <div class="bg-white border-b border-slate-200 shadow-sm flex-shrink-0 z-40">
                <div class="px-6 py-4">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-4">
                            <!-- Navigation Buttons -->
                            <div class="flex gap-2">
                                <a
                                    :href="route('dashboard')"
                                    class="px-4 py-2 bg-gradient-to-r from-sky-500 to-blue-600 hover:from-sky-600 hover:to-blue-700 text-white rounded-lg transition-all duration-200 flex items-center gap-2 shadow-md hover:shadow-lg group"
                                    title="Kembali ke beranda untuk melihat semua worksheet dan recent projects"
                                >
                                    <svg class="w-5 h-5 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                                    </svg>
                                    <span class="font-semibold">Beranda</span>
                                </a>
                                <button
                                    @click="router.get(route('dashboard'))"
                                    class="px-4 py-2 bg-green-500 hover:bg-green-600 text-white rounded-lg transition-all duration-200 flex items-center gap-2 shadow-md hover:shadow-lg group"
                                    title="Buat worksheet baru untuk project analisis yang berbeda dengan konfigurasi fresh"
                                >
                                    <svg class="w-5 h-5 group-hover:rotate-90 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                    </svg>
                                    <span class="font-semibold">Buat Worksheet Baru</span>
                                </button>
                            </div>
                            
                            <!-- Project Info -->
                            <div class="border-l-2 border-slate-200 pl-4">
                                <h1 class="text-2xl font-bold text-slate-800">{{ project.title }}</h1>
                                <p class="text-sm text-slate-600 mt-1">{{ project.description }}</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-4">
                            <div class="text-sm text-slate-600">
                                <span class="font-medium text-sky-600">{{ project.attributes.length }}</span> {{ project.x_label }} × 
                                <span class="font-medium text-blue-600">{{ project.classes.length }}</span> {{ project.y_label }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Workspace -->
            <div class="flex-1 flex overflow-hidden relative">
                <!-- Sidebar - Fixed -->
                <SidebarConfig
                    :project="project"
                    :collapsed="sidebarCollapsed"
                    :current-tab="currentTab"
                    @toggle="toggleSidebar"
                    @update-tab="(tab) => currentTab = tab"
                />

                <!-- Center Stage: Matrix Table -->
                <div 
                    :class="[
                        'flex-1 relative overflow-hidden bg-slate-50 transition-all duration-300',
                        !sidebarCollapsed ? 'ml-80' : 'ml-0'
                    ]"
                >
                    <MatrixTable
                        :project="project"
                        :training-data="trainingData"
                    />
                </div>
            </div>

            <!-- Bottom Drawer: Analysis -->
            <AnalysisDrawer
                :project="project"
            />
        </div>
    </AppLayout>
</template>

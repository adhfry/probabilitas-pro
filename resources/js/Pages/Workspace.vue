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
        <div class="h-screen flex flex-col">
            <!-- Workspace Header -->
            <div class="bg-white border-b border-slate-200 shadow-sm">
                <div class="px-6 py-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <h1 class="text-2xl font-bold text-slate-800">{{ project.title }}</h1>
                            <p class="text-sm text-slate-600 mt-1">{{ project.description }}</p>
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
            <div class="flex-1 flex overflow-hidden">
                <!-- Sidebar -->
                <SidebarConfig
                    :project="project"
                    :collapsed="sidebarCollapsed"
                    :current-tab="currentTab"
                    @toggle="toggleSidebar"
                    @update-tab="(tab) => currentTab = tab"
                />

                <!-- Center Stage: Matrix Table -->
                <div class="flex-1 relative overflow-hidden bg-slate-50">
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

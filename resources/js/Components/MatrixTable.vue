<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue';
import { router } from '@inertiajs/vue3';
import axios from 'axios';

const props = defineProps({
    project: Object,
    trainingData: Object
});

const scale = ref(1);
const translateX = ref(0);
const translateY = ref(0);
const isDragging = ref(false);
const dragStart = ref({ x: 0, y: 0 });
const containerRef = ref(null);

const checkboxStates = ref({});

onMounted(() => {
    // Initialize checkbox states from training data
    if (props.trainingData) {
        checkboxStates.value = { ...props.trainingData };
    }
    
    // Center the table
    if (containerRef.value) {
        const rect = containerRef.value.getBoundingClientRect();
        translateX.value = 50;
        translateY.value = 50;
    }
});

const handleWheel = (e) => {
    if (e.ctrlKey) {
        e.preventDefault();
        const delta = e.deltaY > 0 ? -0.1 : 0.1;
        const newScale = Math.max(0.5, Math.min(2.0, scale.value + delta));
        scale.value = newScale;
    }
};

const handleMouseDown = (e) => {
    if (e.target.tagName === 'INPUT') return;
    isDragging.value = true;
    dragStart.value = {
        x: e.clientX - translateX.value,
        y: e.clientY - translateY.value
    };
};

const handleMouseMove = (e) => {
    if (!isDragging.value) return;
    translateX.value = e.clientX - dragStart.value.x;
    translateY.value = e.clientY - dragStart.value.y;
};

const handleMouseUp = () => {
    isDragging.value = false;
};

const getCheckboxKey = (classId, attributeId) => {
    return `${classId}_${attributeId}`;
};

const isChecked = (classId, attributeId) => {
    const key = getCheckboxKey(classId, attributeId);
    return checkboxStates.value[key] === true;
};

const toggleCheckbox = async (classId, attributeId) => {
    const key = getCheckboxKey(classId, attributeId);
    const newValue = !checkboxStates.value[key];
    checkboxStates.value[key] = newValue;
    
    try {
        await axios.post(route('workspace.training-data.update', props.project.id), {
            class_id: classId,
            attribute_id: attributeId,
            is_associated: newValue
        });
    } catch (error) {
        console.error('Failed to update training data:', error);
        checkboxStates.value[key] = !newValue;
    }
};

const zoomIn = () => {
    scale.value = Math.min(2.0, scale.value + 0.1);
};

const zoomOut = () => {
    scale.value = Math.max(0.5, scale.value - 0.1);
};

const resetZoom = () => {
    scale.value = 1;
    translateX.value = 50;
    translateY.value = 50;
};

onMounted(() => {
    document.addEventListener('mousemove', handleMouseMove);
    document.addEventListener('mouseup', handleMouseUp);
});

onUnmounted(() => {
    document.removeEventListener('mousemove', handleMouseMove);
    document.removeEventListener('mouseup', handleMouseUp);
});
</script>

<template>
    <div
        ref="containerRef"
        class="w-full h-full overflow-hidden relative select-none"
        @wheel="handleWheel"
        @mousedown="handleMouseDown"
        :class="{ 'cursor-grabbing': isDragging, 'cursor-grab': !isDragging }"
    >
        <!-- Matrix Table -->
        <div
            class="absolute"
            :style="{
                transform: `translate(${translateX}px, ${translateY}px) scale(${scale})`,
                transformOrigin: 'top left',
                transition: isDragging ? 'none' : 'transform 0.1s ease-out'
            }"
        >
            <table class="border-collapse bg-white shadow-2xl rounded-lg overflow-hidden">
                <thead>
                    <tr>
                        <th class="bg-slate-200 border border-slate-300 p-3 min-w-[150px] sticky left-0 z-10">
                            <div class="text-sm font-bold text-slate-700">
                                {{ project.x_label }} \ {{ project.y_label }}
                            </div>
                        </th>
                        <th
                            v-for="cls in project.classes"
                            :key="cls.id"
                            class="bg-sky-100 border border-sky-200 p-3 min-w-[120px]"
                        >
                            <div class="text-xs font-mono text-sky-700 mb-1">{{ cls.code }}</div>
                            <div class="text-sm font-bold text-sky-900">{{ cls.name }}</div>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr
                        v-for="attr in project.attributes"
                        :key="attr.id"
                        class="hover:bg-slate-50 transition-colors"
                    >
                        <td class="bg-sky-100 border border-sky-200 p-3 sticky left-0 z-10">
                            <div class="text-xs font-mono text-sky-700 mb-1">{{ attr.code }}</div>
                            <div class="text-sm font-bold text-sky-900">{{ attr.name }}</div>
                        </td>
                        <td
                            v-for="cls in project.classes"
                            :key="cls.id"
                            class="border border-slate-200 p-3 text-center cursor-pointer hover:bg-blue-50 transition-colors"
                            @click="toggleCheckbox(cls.id, attr.id)"
                        >
                            <div class="flex items-center justify-center">
                                <div
                                    :class="[
                                        'w-8 h-8 rounded border-2 flex items-center justify-center transition-all cursor-pointer',
                                        isChecked(cls.id, attr.id)
                                            ? 'bg-blue-600 border-blue-600'
                                            : 'bg-white border-slate-300 hover:border-blue-400'
                                    ]"
                                >
                                    <svg
                                        v-if="isChecked(cls.id, attr.id)"
                                        class="w-5 h-5 text-white"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                    >
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                                    </svg>
                                </div>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Zoom Controls -->
        <div class="absolute bottom-6 right-6 bg-white rounded-lg shadow-lg border border-slate-200 p-2 flex flex-col gap-2">
            <button
                @click="zoomIn"
                class="w-10 h-10 flex items-center justify-center bg-sky-500 text-white rounded hover:bg-sky-600 transition-colors"
                title="Zoom In"
            >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
            </button>
            
            <button
                @click="resetZoom"
                class="w-10 h-10 flex items-center justify-center bg-slate-100 text-slate-700 rounded hover:bg-slate-200 transition-colors text-xs font-bold"
                title="Reset Zoom"
            >
                {{ Math.round(scale * 100) }}%
            </button>
            
            <button
                @click="zoomOut"
                class="w-10 h-10 flex items-center justify-center bg-sky-500 text-white rounded hover:bg-sky-600 transition-colors"
                title="Zoom Out"
            >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" />
                </svg>
            </button>
        </div>

        <!-- Instructions -->
        <div class="absolute top-4 left-4 bg-white/90 backdrop-blur-sm rounded-lg shadow-md px-4 py-2 text-sm text-slate-600">
            <div class="font-semibold mb-1">Kontrol:</div>
            <div class="text-xs space-y-1">
                <div>• <kbd class="px-1 bg-slate-200 rounded">Ctrl + Scroll</kbd> untuk zoom</div>
                <div>• <kbd class="px-1 bg-slate-200 rounded">Drag</kbd> untuk pan</div>
                <div>• <kbd class="px-1 bg-slate-200 rounded">Click</kbd> untuk toggle checkbox</div>
            </div>
        </div>
    </div>
</template>

<style scoped>
kbd {
    font-family: monospace;
    font-size: 0.75rem;
}
</style>

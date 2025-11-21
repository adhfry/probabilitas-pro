<script setup>
import { ref } from 'vue';
import { router } from '@inertiajs/vue3';
import axios from 'axios';

const props = defineProps({
    project: Object,
    collapsed: Boolean,
    currentTab: String
});

const emit = defineEmits(['toggle', 'update-tab']);

const editingId = ref(null);
const editingName = ref('');
const addingNew = ref(false);
const newItemName = ref('');

const startEdit = (item) => {
    editingId.value = item.id;
    editingName.value = item.name;
};

const saveEdit = async (item, type) => {
    try {
        const endpoint = type === 'attribute' 
            ? route('workspace.attributes.update', { project: props.project.id, attribute: item.id })
            : route('workspace.classes.update', { project: props.project.id, class: item.id });
        
        await axios.patch(endpoint, {
            name: editingName.value
        });
        
        item.name = editingName.value;
        editingId.value = null;
        
        router.reload({ only: ['project'] });
    } catch (error) {
        console.error('Failed to update:', error);
    }
};

const cancelEdit = () => {
    editingId.value = null;
    editingName.value = '';
};

const addNewItem = async () => {
    if (!newItemName.value.trim()) return;
    
    try {
        const endpoint = props.currentTab === 'attributes'
            ? route('workspace.attributes.add', props.project.id)
            : route('workspace.classes.add', props.project.id);
        
        await axios.post(endpoint, {
            name: newItemName.value
        });
        
        newItemName.value = '';
        addingNew.value = false;
        
        router.reload({ only: ['project'] });
    } catch (error) {
        console.error('Failed to add:', error);
    }
};

const items = computed(() => {
    return props.currentTab === 'attributes' ? props.project.attributes : props.project.classes;
});

const label = computed(() => {
    return props.currentTab === 'attributes' ? props.project.x_label : props.project.y_label;
});

const nextCode = computed(() => {
    const currentItems = items.value;
    const prefix = props.currentTab === 'attributes' ? 'X' : 'C';
    const nextNumber = currentItems.length + 1;
    return `${prefix}${nextNumber}`;
});
</script>

<template>
    <div
        :class="[
            'bg-white border-r border-slate-200 transition-all duration-300 flex flex-col absolute left-0 top-0 bottom-0 z-50',
            collapsed ? 'w-0' : 'w-80'
        ]"
    >
        <div v-if="!collapsed" class="flex-1 flex flex-col h-full overflow-hidden">
            <!-- Tabs -->
            <div class="flex border-b border-slate-200">
                <button
                    @click="emit('update-tab', 'attributes')"
                    :class="[
                        'flex-1 px-4 py-3 text-sm font-medium transition-colors',
                        currentTab === 'attributes'
                            ? 'text-sky-600 border-b-2 border-sky-600 bg-sky-50'
                            : 'text-slate-600 hover:bg-slate-50'
                    ]"
                >
                    Prediktor ($X$)
                </button>
                <button
                    @click="emit('update-tab', 'classes')"
                    :class="[
                        'flex-1 px-4 py-3 text-sm font-medium transition-colors',
                        currentTab === 'classes'
                            ? 'text-blue-600 border-b-2 border-blue-600 bg-blue-50'
                            : 'text-slate-600 hover:bg-slate-50'
                    ]"
                >
                    Kelas ($Y$)
                </button>
            </div>

            <!-- List - Scrollable dengan tombol tambah di dalamnya -->
            <div class="flex-1 overflow-y-auto custom-scrollbar">
                <div class="p-4 space-y-2">
                    <!-- Existing Items -->
                    <div
                        v-for="item in items"
                        :key="item.id"
                        class="bg-slate-50 rounded-lg p-3 hover:bg-slate-100 transition-colors"
                    >
                        <div v-if="editingId === item.id" class="flex gap-2">
                            <input
                                v-model="editingName"
                                type="text"
                                class="flex-1 px-3 py-1 text-sm border border-slate-300 rounded focus:ring-2 focus:ring-sky-500 focus:border-transparent"
                                @keyup.enter="saveEdit(item, currentTab === 'attributes' ? 'attribute' : 'class')"
                                @keyup.esc="cancelEdit"
                                autofocus
                            />
                            <button
                                @click="saveEdit(item, currentTab === 'attributes' ? 'attribute' : 'class')"
                                class="px-2 py-1 bg-sky-500 text-white rounded hover:bg-sky-600 text-sm"
                            >
                                ✓
                            </button>
                            <button
                                @click="cancelEdit"
                                class="px-2 py-1 bg-slate-300 text-slate-700 rounded hover:bg-slate-400 text-sm"
                            >
                                ✕
                            </button>
                        </div>
                        <div v-else class="flex items-center justify-between">
                            <div class="flex-1">
                                <div class="text-xs font-mono text-slate-500">{{ item.code }}</div>
                                <div class="text-sm font-medium text-slate-800">{{ item.name }}</div>
                            </div>
                            <button
                                @click="startEdit(item)"
                                class="text-slate-400 hover:text-sky-600 transition-colors"
                            >
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- Add New Form (Inside Scroll Area) -->
                    <div v-if="addingNew" class="bg-gradient-to-br from-sky-50 to-blue-50 rounded-lg p-4 border-2 border-sky-300 shadow-md animate-slideIn">
                        <div class="mb-3">
                            <div class="flex items-center gap-2 mb-2">
                                <svg class="w-5 h-5 text-sky-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                </svg>
                                <label class="text-sm font-semibold text-slate-700">
                                    {{ label }} Baru
                                </label>
                            </div>
                            <input
                                v-model="newItemName"
                                type="text"
                                class="w-full px-3 py-2 text-sm border-2 border-sky-300 rounded-lg focus:ring-2 focus:ring-sky-500 focus:border-sky-500 outline-none transition-all"
                                :placeholder="`Contoh: ${currentTab === 'attributes' ? 'Laptop tidak bisa hidup' : 'Kerusakan RAM'}`"
                                @keyup.enter="addNewItem"
                                @keyup.esc="addingNew = false; newItemName = ''"
                                autofocus
                            />
                            <div class="mt-2 text-xs text-slate-600 flex items-start gap-1">
                                <svg class="w-3 h-3 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                                </svg>
                                <span>Kode otomatis: <strong class="font-mono text-sky-700">{{ nextCode }}</strong></span>
                            </div>
                        </div>
                        <div class="flex gap-2">
                            <button
                                @click="addingNew = false; newItemName = ''"
                                class="flex-1 px-3 py-2 bg-slate-200 hover:bg-slate-300 text-slate-700 rounded-lg text-sm font-semibold transition-colors"
                            >
                                Batal
                            </button>
                            <button
                                @click="addNewItem"
                                :disabled="!newItemName.trim()"
                                class="flex-1 px-3 py-2 bg-gradient-to-r from-sky-500 to-blue-600 hover:from-sky-600 hover:to-blue-700 text-white rounded-lg text-sm font-semibold transition-all disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center gap-2"
                            >
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                Simpan
                            </button>
                        </div>
                        <div class="mt-3 text-xs text-center text-slate-500 italic">
                            <kbd class="px-1.5 py-0.5 bg-white border border-slate-300 rounded text-xs">Enter</kbd> untuk simpan · 
                            <kbd class="px-1.5 py-0.5 bg-white border border-slate-300 rounded text-xs">Esc</kbd> untuk batal
                        </div>
                    </div>

                    <!-- Add Button (Inside Scroll Area, after all items) -->
                    <div v-if="!addingNew" class="pt-2">
                        <button
                            @click="addingNew = true"
                            class="w-full px-4 py-3 bg-gradient-to-r from-sky-500 to-blue-600 hover:from-sky-600 hover:to-blue-700 text-white rounded-lg transition-all duration-200 flex items-center justify-center gap-2 shadow-md hover:shadow-lg group"
                        >
                            <svg class="w-5 h-5 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            <span class="font-semibold">Tambah {{ label }}</span>
                        </button>
                        
                        <!-- Help Text -->
                        <div class="mt-2 text-xs text-center text-slate-500">
                            <svg class="w-3 h-3 inline mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                            </svg>
                            Matrix table akan otomatis update
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Toggle Button -->
        <button
            @click="emit('toggle')"
            class="absolute -right-3 top-1/2 transform -translate-y-1/2 w-6 h-12 bg-white border border-slate-200 rounded-r-lg shadow-md hover:bg-slate-50 transition-colors flex items-center justify-center"
        >
            <svg
                :class="['w-4 h-4 text-slate-600 transition-transform', collapsed && 'rotate-180']"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
            >
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
        </button>
    </div>
</template>

<script>
import { computed } from 'vue';

export default {
    computed: {
        items() {
            return this.currentTab === 'attributes' ? this.project.attributes : this.project.classes;
        },
        label() {
            return this.currentTab === 'attributes' ? this.project.x_label : this.project.y_label;
        }
    }
};
</script>

<style scoped>
.custom-scrollbar::-webkit-scrollbar {
    width: 8px;
}

.custom-scrollbar::-webkit-scrollbar-track {
    background: #f1f5f9;
    border-radius: 4px;
}

.custom-scrollbar::-webkit-scrollbar-thumb {
    background: #cbd5e1;
    border-radius: 4px;
    transition: background 0.2s;
}

.custom-scrollbar::-webkit-scrollbar-thumb:hover {
    background: #94a3b8;
}

/* Firefox */
.custom-scrollbar {
    scrollbar-width: thin;
    scrollbar-color: #cbd5e1 #f1f5f9;
}

/* Animations */
@keyframes slideIn {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.animate-slideIn {
    animation: slideIn 0.3s ease-out;
}

/* Kbd styling */
kbd {
    font-family: monospace;
    font-size: 0.75rem;
}
</style>

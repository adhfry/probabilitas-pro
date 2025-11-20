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
</script>

<template>
    <div
        :class="[
            'bg-white border-r border-slate-200 transition-all duration-300 flex flex-col',
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

            <!-- List -->
            <div class="flex-1 overflow-y-auto p-4 space-y-2">
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

                <!-- Add New Form -->
                <div v-if="addingNew" class="bg-sky-50 rounded-lg p-3 border-2 border-sky-200">
                    <div class="flex gap-2">
                        <input
                            v-model="newItemName"
                            type="text"
                            class="flex-1 px-3 py-2 text-sm border border-sky-300 rounded focus:ring-2 focus:ring-sky-500 focus:border-transparent"
                            :placeholder="`Nama ${label} baru...`"
                            @keyup.enter="addNewItem"
                            @keyup.esc="addingNew = false; newItemName = ''"
                            autofocus
                        />
                        <button
                            @click="addNewItem"
                            class="px-3 py-2 bg-sky-500 text-white rounded hover:bg-sky-600 text-sm font-medium"
                        >
                            Tambah
                        </button>
                    </div>
                </div>
            </div>

            <!-- Add Button -->
            <div class="p-4 border-t border-slate-200">
                <button
                    v-if="!addingNew"
                    @click="addingNew = true"
                    class="w-full px-4 py-2 bg-sky-500 text-white rounded-lg hover:bg-sky-600 transition-colors flex items-center justify-center gap-2"
                >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    <span>Tambah {{ label }}</span>
                </button>
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

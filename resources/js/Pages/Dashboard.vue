<script setup>
import { ref } from 'vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';

const props = defineProps({
    projects: Array
});

const showModal = ref(false);

const form = useForm({
    title: '',
    description: '',
    x_label: 'Gejala',
    y_label: 'Kerusakan',
    x_count: 5,
    y_count: 5,
});

const openModal = () => {
    showModal.value = true;
};

const closeModal = () => {
    showModal.value = false;
    form.reset();
};

const submit = () => {
    form.post(route('projects.store'), {
        onSuccess: () => {
            closeModal();
        }
    });
};

const deleteProject = (projectId) => {
    if (confirm('Apakah Anda yakin ingin menghapus project ini?')) {
        form.delete(route('projects.destroy', projectId));
    }
};
</script>

<template>
    <Head title="Dashboard - Probabilitas Pro" />

    <AppLayout>
        <div class="py-12">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <!-- Header Section -->
                <div class="mb-8 flex items-center justify-between">
                    <div>
                        <h1 class="text-3xl font-bold text-slate-800 mb-2">Dashboard</h1>
                        <p class="text-slate-600">Kelola dan analisis studi kasus diagnosa Anda dengan metode Naive Bayes</p>
                    </div>
                    <button
                        @click="openModal"
                        title="Buat worksheet baru untuk memulai studi kasus dan analisis diagnosa baru dengan konfigurasi fresh"
                        class="px-6 py-3 bg-gradient-to-r from-sky-500 to-blue-600 text-white rounded-lg hover:from-sky-600 hover:to-blue-700 transition-all duration-200 font-medium shadow-lg hover:shadow-xl flex items-center gap-2"
                    >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Buat Worksheet Baru
                    </button>
                </div>

                <!-- Projects Grid -->
                <div v-if="projects && projects.length > 0" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
                    <div
                        v-for="project in projects"
                        :key="project.id"
                        class="bg-white rounded-lg shadow-md hover:shadow-xl transition-shadow duration-300 overflow-hidden border border-slate-200"
                    >
                        <div class="p-6">
                            <h3 class="text-xl font-semibold text-slate-800 mb-2">{{ project.title }}</h3>
                            <p class="text-sm text-slate-600 mb-4 line-clamp-2">{{ project.description || 'Tidak ada deskripsi' }}</p>
                            
                            <div class="flex items-center gap-4 text-sm text-slate-500 mb-4">
                                <div class="flex items-center gap-1">
                                    <span class="font-medium text-sky-600">{{ project.attributes_count }}</span>
                                    <span>Variabel X</span>
                                </div>
                                <div class="flex items-center gap-1">
                                    <span class="font-medium text-blue-600">{{ project.classes_count }}</span>
                                    <span>Variabel Y</span>
                                </div>
                            </div>

                            <div class="text-xs text-slate-400 mb-4">
                                {{ new Date(project.created_at).toLocaleDateString('id-ID', { year: 'numeric', month: 'long', day: 'numeric' }) }}
                            </div>

                            <div class="flex gap-2">
                                <Link
                                    :href="route('workspace.show', project.id)"
                                    class="flex-1 bg-gradient-to-r from-sky-500 to-blue-600 text-white px-4 py-2 rounded-lg hover:from-sky-600 hover:to-blue-700 transition-all duration-200 text-center font-medium"
                                >
                                    Buka Workspace
                                </Link>
                                <button
                                    @click="deleteProject(project.id)"
                                    class="px-4 py-2 bg-red-50 text-red-600 rounded-lg hover:bg-red-100 transition-colors"
                                >
                                    Hapus
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Empty State -->
                <div v-else class="bg-white rounded-lg shadow-md p-12 text-center mb-8">
                    <div class="w-20 h-20 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-10 h-10 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-slate-700 mb-2">Belum ada project</h3>
                    <p class="text-slate-500 mb-6">Mulai dengan membuat studi kasus baru</p>
                </div>


            </div>
        </div>

        <!-- Modal -->
        <Teleport to="body">
            <div
                v-if="showModal"
                class="fixed inset-0 z-50 overflow-y-auto"
                @click.self="closeModal"
            >
                <div class="flex items-center justify-center min-h-screen px-4">
                    <div class="fixed inset-0 bg-black bg-opacity-50 transition-opacity"></div>
                    
                    <div class="relative bg-white rounded-2xl shadow-2xl max-w-2xl w-full p-8 z-50">
                        <h2 class="text-2xl font-bold text-slate-800 mb-6">Inisiasi Studi Kasus Baru</h2>
                        
                        <form @submit.prevent="submit" class="space-y-6">
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-2">Judul Studi Kasus *</label>
                                <input
                                    v-model="form.title"
                                    type="text"
                                    required
                                    class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-sky-500 focus:border-transparent"
                                    placeholder="Contoh: Diagnosa Kerusakan Komputer"
                                />
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-2">Deskripsi</label>
                                <textarea
                                    v-model="form.description"
                                    rows="3"
                                    class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-sky-500 focus:border-transparent"
                                    placeholder="Jelaskan singkat tentang studi kasus ini..."
                                ></textarea>
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 mb-2">Label Variabel X (Prediktor) *</label>
                                    <input
                                        v-model="form.x_label"
                                        type="text"
                                        required
                                        class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-sky-500 focus:border-transparent"
                                        placeholder="Contoh: Gejala"
                                    />
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-slate-700 mb-2">Jumlah Awal</label>
                                    <input
                                        v-model.number="form.x_count"
                                        type="number"
                                        min="1"
                                        required
                                        class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-sky-500 focus:border-transparent"
                                    />
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 mb-2">Label Variabel Y (Kelas) *</label>
                                    <input
                                        v-model="form.y_label"
                                        type="text"
                                        required
                                        class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-sky-500 focus:border-transparent"
                                        placeholder="Contoh: Kerusakan"
                                    />
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-slate-700 mb-2">Jumlah Awal</label>
                                    <input
                                        v-model.number="form.y_count"
                                        type="number"
                                        min="1"
                                        required
                                        class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-sky-500 focus:border-transparent"
                                    />
                                </div>
                            </div>

                            <div class="flex gap-4 pt-4">
                                <button
                                    type="button"
                                    @click="closeModal"
                                    class="flex-1 px-6 py-3 border border-slate-300 text-slate-700 rounded-lg hover:bg-slate-50 transition-colors"
                                >
                                    Batal
                                </button>
                                <button
                                    type="submit"
                                    :disabled="form.processing"
                                    class="flex-1 px-6 py-3 bg-gradient-to-r from-sky-500 to-blue-600 text-white rounded-lg hover:from-sky-600 hover:to-blue-700 transition-all disabled:opacity-50"
                                >
                                    {{ form.processing ? 'Membuat...' : 'Generate Workspace' }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </Teleport>
    </AppLayout>
</template>

<style scoped>
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>


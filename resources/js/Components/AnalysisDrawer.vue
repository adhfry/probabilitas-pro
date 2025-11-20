<script setup>
import { ref, computed } from 'vue';
import axios from 'axios';

const props = defineProps({
    project: Object
});

const drawerHeight = ref(80);
const isDragging = ref(false);
const dragStartY = ref(0);
const selectedAttributes = ref([]);
const isAnalyzing = ref(false);
const results = ref(null);
const calculationSteps = ref(null);
const showSteps = ref(false);

const minHeight = 80;
const maxHeight = window.innerHeight * 0.7;

const handleMouseDown = (e) => {
    isDragging.value = true;
    dragStartY.value = e.clientY;
};

const handleMouseMove = (e) => {
    if (!isDragging.value) return;
    
    const deltaY = dragStartY.value - e.clientY;
    const newHeight = Math.max(minHeight, Math.min(maxHeight, drawerHeight.value + deltaY));
    drawerHeight.value = newHeight;
    dragStartY.value = e.clientY;
};

const handleMouseUp = () => {
    isDragging.value = false;
};

document.addEventListener('mousemove', handleMouseMove);
document.addEventListener('mouseup', handleMouseUp);

const toggleAttribute = (attrId) => {
    const index = selectedAttributes.value.indexOf(attrId);
    if (index > -1) {
        selectedAttributes.value.splice(index, 1);
    } else {
        selectedAttributes.value.push(attrId);
    }
};

const performAnalysis = async () => {
    if (selectedAttributes.value.length === 0) {
        alert('Pilih minimal 1 gejala untuk melakukan analisis');
        return;
    }
    
    isAnalyzing.value = true;
    drawerHeight.value = maxHeight;
    
    // Simulate animation delay
    await new Promise(resolve => setTimeout(resolve, 2000));
    
    try {
        const response = await axios.post(route('analysis.analyze', props.project.id), {
            selected_attributes: selectedAttributes.value
        });
        
        results.value = response.data.results;
        calculationSteps.value = response.data.calculation_steps;
    } catch (error) {
        console.error('Analysis failed:', error);
        alert('Gagal melakukan analisis. Silakan coba lagi.');
    } finally {
        isAnalyzing.value = false;
    }
};

const reset = () => {
    selectedAttributes.value = [];
    results.value = null;
    calculationSteps.value = null;
    showSteps.value = false;
    drawerHeight.value = 80;
};

const minimize = () => {
    drawerHeight.value = 80;
};
</script>

<template>
    <div
        class="fixed bottom-0 left-0 right-0 bg-white border-t-2 border-sky-500 shadow-2xl z-40 transition-all"
        :style="{ height: `${drawerHeight}px` }"
    >
        <!-- Drag Handle -->
        <div
            @mousedown="handleMouseDown"
            class="w-full h-8 flex items-center justify-center cursor-ns-resize hover:bg-sky-50 transition-colors"
        >
            <div class="w-12 h-1.5 bg-slate-300 rounded-full"></div>
        </div>

        <!-- Content -->
        <div class="h-full overflow-y-auto px-6 pb-6">
            <!-- Loading Animation -->
            <div v-if="isAnalyzing" class="flex flex-col items-center justify-center h-64">
                <div class="magic-loader w-64 h-64 rounded-full mb-6 flex items-center justify-center relative overflow-hidden">
                    <div class="stars"></div>
                    <div class="text-white text-2xl font-bold z-10">Menganalisis...</div>
                </div>
                <p class="text-slate-600 text-lg">Menghitung probabilitas dengan Naive Bayes</p>
            </div>

            <!-- Input Section -->
            <div v-else-if="!results" class="max-w-4xl mx-auto">
                <h3 class="text-xl font-bold text-slate-800 mb-4">Pilih {{ project.x_label }} yang Dialami</h3>
                
                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3 mb-6">
                    <div
                        v-for="attr in project.attributes"
                        :key="attr.id"
                        @click="toggleAttribute(attr.id)"
                        :class="[
                            'p-4 rounded-lg border-2 cursor-pointer transition-all',
                            selectedAttributes.includes(attr.id)
                                ? 'bg-sky-100 border-sky-500 shadow-md'
                                : 'bg-white border-slate-200 hover:border-sky-300'
                        ]"
                    >
                        <div class="flex items-start gap-2">
                            <div
                                :class="[
                                    'w-5 h-5 rounded border-2 flex items-center justify-center flex-shrink-0 mt-0.5',
                                    selectedAttributes.includes(attr.id)
                                        ? 'bg-sky-600 border-sky-600'
                                        : 'bg-white border-slate-300'
                                ]"
                            >
                                <svg
                                    v-if="selectedAttributes.includes(attr.id)"
                                    class="w-3 h-3 text-white"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                                </svg>
                            </div>
                            <div class="flex-1">
                                <div class="text-xs font-mono text-slate-500">{{ attr.code }}</div>
                                <div class="text-sm font-medium text-slate-800">{{ attr.name }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex gap-4 justify-center">
                    <button
                        @click="performAnalysis"
                        :disabled="selectedAttributes.length === 0"
                        class="px-8 py-3 bg-gradient-to-r from-sky-500 to-blue-600 text-white rounded-lg hover:from-sky-600 hover:to-blue-700 transition-all disabled:opacity-50 disabled:cursor-not-allowed font-semibold text-lg shadow-lg"
                    >
                        Lakukan Inferensi Probabilistik
                    </button>
                    
                    <button
                        v-if="selectedAttributes.length > 0"
                        @click="reset"
                        class="px-6 py-3 bg-slate-200 text-slate-700 rounded-lg hover:bg-slate-300 transition-colors"
                    >
                        Reset
                    </button>
                </div>
            </div>

            <!-- Results Section -->
            <div v-else class="max-w-6xl mx-auto">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-2xl font-bold text-slate-800">Hasil Analisis</h3>
                    <div class="flex gap-2">
                        <button
                            @click="showSteps = !showSteps"
                            class="px-4 py-2 bg-sky-100 text-sky-700 rounded-lg hover:bg-sky-200 transition-colors font-medium"
                        >
                            {{ showSteps ? 'Sembunyikan' : 'Tampilkan' }} Langkah Perhitungan
                        </button>
                        <button
                            @click="minimize"
                            class="px-4 py-2 bg-slate-100 text-slate-700 rounded-lg hover:bg-slate-200 transition-colors"
                        >
                            Minimize
                        </button>
                        <button
                            @click="reset"
                            class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors font-medium"
                        >
                            Analisis Baru
                        </button>
                    </div>
                </div>

                <!-- Calculation Steps -->
                <div v-if="showSteps && calculationSteps" class="mb-6 bg-slate-50 rounded-lg p-6 border border-slate-200">
                    <h4 class="text-lg font-bold text-slate-800 mb-4">📊 Langkah Perhitungan Naive Bayes</h4>
                    
                    <div
                        v-for="(step, index) in calculationSteps"
                        :key="index"
                        class="mb-6 pb-6 border-b border-slate-200 last:border-0"
                    >
                        <div class="flex items-center gap-2 mb-3">
                            <div class="w-8 h-8 bg-sky-600 text-white rounded-full flex items-center justify-center font-bold">
                                {{ index + 1 }}
                            </div>
                            <h5 class="text-lg font-semibold text-slate-800">
                                {{ step.class_name }} ({{ step.class_code }})
                            </h5>
                        </div>

                        <div class="pl-10 space-y-3">
                            <div class="bg-white rounded p-4 border border-slate-200 font-mono text-sm">
                                <div class="mb-2 text-slate-600">Prior Probability:</div>
                                <div class="text-lg">
                                    P({{ step.class_code }}) = {{ step.prior.toFixed(4) }}
                                </div>
                            </div>

                            <div class="bg-white rounded p-4 border border-slate-200">
                                <div class="mb-2 text-slate-600 font-mono text-sm">Likelihoods:</div>
                                <div class="space-y-2">
                                    <div
                                        v-for="(lh, lhIndex) in step.likelihoods"
                                        :key="lhIndex"
                                        class="flex items-center justify-between py-2 px-3 bg-slate-50 rounded"
                                    >
                                        <span class="font-mono text-sm">
                                            P({{ lh.attribute_code }} | {{ step.class_code }})
                                        </span>
                                        <span
                                            :class="[
                                                'font-bold',
                                                lh.is_associated ? 'text-green-600' : 'text-orange-600'
                                            ]"
                                        >
                                            = {{ lh.value.toFixed(2) }}
                                            <span class="text-xs ml-1">
                                                {{ lh.is_associated ? '(Terkait)' : '(Tidak Terkait)' }}
                                            </span>
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <div class="bg-blue-50 rounded p-4 border-2 border-blue-300">
                                <div class="mb-2 text-slate-700 font-semibold">Formula:</div>
                                <div class="font-mono text-sm mb-2 text-slate-700">
                                    Score({{ step.class_code }}) = P({{ step.class_code }}) × 
                                    <span v-for="(lh, lhIndex) in step.likelihoods" :key="lhIndex">
                                        P({{ lh.attribute_code }}|{{ step.class_code }})<span v-if="lhIndex < step.likelihoods.length - 1"> × </span>
                                    </span>
                                </div>
                                <div class="font-mono text-sm mb-2 text-slate-700">
                                    Score({{ step.class_code }}) = {{ step.prior.toFixed(4) }} × 
                                    <span v-for="(lh, lhIndex) in step.likelihoods" :key="lhIndex">
                                        {{ lh.value.toFixed(2) }}<span v-if="lhIndex < step.likelihoods.length - 1"> × </span>
                                    </span>
                                </div>
                                <div class="text-lg font-bold text-blue-700 mt-3">
                                    Score = {{ step.raw_score.toExponential(4) }}
                                </div>
                                <div class="text-xl font-bold text-blue-700 mt-2">
                                    Probability = {{ step.percentage.toFixed(2) }}%
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Results Cards -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    <div
                        v-for="(result, index) in results"
                        :key="result.class_id"
                        :class="[
                            'rounded-lg p-6 border-2 transition-all',
                            index === 0
                                ? 'bg-gradient-to-br from-green-50 to-emerald-50 border-green-400 shadow-lg scale-105'
                                : 'bg-white border-slate-200 shadow'
                        ]"
                    >
                        <div class="flex items-start justify-between mb-3">
                            <div>
                                <div class="text-xs font-mono text-slate-500 mb-1">{{ result.class_code }}</div>
                                <div class="text-lg font-bold text-slate-800">{{ result.class_name }}</div>
                            </div>
                            <div
                                v-if="index === 0"
                                class="bg-green-500 text-white px-3 py-1 rounded-full text-xs font-bold"
                            >
                                TERTINGGI
                            </div>
                        </div>

                        <div class="relative pt-2">
                            <div class="flex items-end justify-between mb-2">
                                <span class="text-sm text-slate-600">Probabilitas:</span>
                                <span
                                    :class="[
                                        'text-3xl font-bold',
                                        index === 0 ? 'text-green-600' : 'text-slate-700'
                                    ]"
                                >
                                    {{ result.percentage.toFixed(2) }}%
                                </span>
                            </div>
                            
                            <div class="w-full bg-slate-200 rounded-full h-3 overflow-hidden">
                                <div
                                    :class="[
                                        'h-full rounded-full transition-all duration-1000',
                                        index === 0 ? 'bg-gradient-to-r from-green-500 to-emerald-600' : 'bg-gradient-to-r from-sky-500 to-blue-600'
                                    ]"
                                    :style="{ width: `${result.percentage}%` }"
                                ></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Summary -->
                <div class="mt-6 bg-sky-50 rounded-lg p-6 border border-sky-200">
                    <h4 class="text-lg font-bold text-slate-800 mb-2">Kesimpulan</h4>
                    <p class="text-slate-700">
                        Berdasarkan {{ selectedAttributes.length }} {{ project.x_label }} yang dipilih, 
                        sistem mendiagnosa kemungkinan tertinggi adalah 
                        <strong class="text-green-600">{{ results[0].class_name }}</strong> 
                        dengan tingkat probabilitas <strong>{{ results[0].percentage.toFixed(2) }}%</strong>.
                    </p>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
@keyframes aurora {
    0% { background-position: 0% 50%; }
    50% { background-position: 100% 50%; }
    100% { background-position: 0% 50%; }
}

.magic-loader {
    background: linear-gradient(270deg, #3b82f6, #8b5cf6, #06b6d4, #10b981);
    background-size: 800% 800%;
    animation: aurora 3s ease infinite;
}

@keyframes twinkle {
    0%, 100% { opacity: 0; transform: scale(0); }
    50% { opacity: 1; transform: scale(1); }
}

.stars {
    position: absolute;
    width: 100%;
    height: 100%;
}

.stars::before,
.stars::after {
    content: '✦';
    position: absolute;
    color: white;
    font-size: 20px;
    animation: twinkle 2s infinite;
}

.stars::before {
    top: 20%;
    left: 30%;
    animation-delay: 0s;
}

.stars::after {
    top: 70%;
    right: 25%;
    animation-delay: 1s;
}
</style>

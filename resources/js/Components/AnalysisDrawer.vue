<script setup>
import { ref, computed } from 'vue';
import axios from 'axios';
import MathFormula from './MathFormula.vue';

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

const downloadPdf = async () => {
    if (!results.value) return;
    
    try {
        const response = await axios.post(
            route('analysis.export-pdf', props.project.id), 
            { selected_attributes: selectedAttributes.value },
            { responseType: 'blob' }
        );
        
        const url = window.URL.createObjectURL(new Blob([response.data]));
        const link = document.createElement('a');
        link.href = url;
        link.setAttribute('download', `Analisis_${props.project.name}_${new Date().toISOString().slice(0,10)}.pdf`);
        document.body.appendChild(link);
        link.click();
        link.remove();
        window.URL.revokeObjectURL(url);
    } catch (error) {
        console.error('PDF export failed:', error);
        alert('Gagal mengunduh PDF. Silakan coba lagi.');
    }
};
</script>

<template>
    <div
        class="absolute bottom-0 left-0 right-0 bg-white border-t-2 border-sky-500 shadow-2xl z-40 transition-all"
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
        <div class="h-full overflow-y-auto px-6 pb-16 custom-scrollbar">
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
                            @click="downloadPdf"
                            class="px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition-all duration-200 font-medium flex items-center gap-2 shadow-md hover:shadow-lg group"
                            title="Download laporan analisis dalam format PDF dengan detail perhitungan lengkap"
                        >
                            <svg class="w-5 h-5 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            Download PDF
                        </button>
                        <button
                            @click="showSteps = !showSteps"
                            class="px-4 py-2 bg-sky-100 text-sky-700 rounded-lg hover:bg-sky-200 transition-colors font-medium"
                            title="Tampilkan atau sembunyikan langkah-langkah perhitungan detail menggunakan metode Naive Bayes"
                        >
                            {{ showSteps ? 'Sembunyikan' : 'Tampilkan' }} Langkah Perhitungan
                        </button>
                        <button
                            @click="minimize"
                            class="px-4 py-2 bg-slate-100 text-slate-700 rounded-lg hover:bg-slate-200 transition-colors"
                            title="Minimalkan panel analisis ke bagian bawah layar"
                        >
                            Minimize
                        </button>
                        <button
                            @click="reset"
                            class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors font-medium"
                            title="Mulai analisis baru dengan memilih gejala yang berbeda"
                        >
                            Analisis Baru
                        </button>
                    </div>
                </div>

                <!-- Calculation Steps -->
                <div v-if="showSteps && calculationSteps" class="mb-6 bg-gradient-to-br from-slate-50 to-blue-50 rounded-xl p-8 border-2 border-blue-200 shadow-lg">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-sky-600 rounded-xl flex items-center justify-center shadow-md">
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <div>
                            <h4 class="text-2xl font-bold text-slate-800">Langkah Perhitungan Naive Bayes</h4>
                            <p class="text-sm text-slate-600 mt-1">Detail perhitungan probabilitas untuk setiap kelas</p>
                        </div>
                    </div>

                    <!-- Selected Attributes Summary -->
                    <div class="mb-6 bg-white rounded-lg p-4 border border-blue-200">
                        <h5 class="text-sm font-semibold text-slate-700 mb-2">{{ project.x_label }} yang Dipilih:</h5>
                        <div class="flex flex-wrap gap-2">
                            <span
                                v-for="attr in project.attributes.filter(a => selectedAttributes.includes(a.id))"
                                :key="attr.id"
                                class="inline-flex items-center gap-1 px-3 py-1 bg-sky-100 text-sky-700 rounded-full text-sm font-medium"
                            >
                                <span class="font-mono text-xs">{{ attr.code }}</span>
                                <span>{{ attr.name }}</span>
                            </span>
                        </div>
                    </div>

                    <!-- Langkah 0: Teorema Naive Bayes -->
                    <div class="mb-6 bg-gradient-to-br from-purple-50 to-pink-50 rounded-lg p-6 border-2 border-purple-300 shadow-lg">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="w-12 h-12 bg-purple-500 rounded-xl flex items-center justify-center shadow-md">
                                <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <div>
                                <h5 class="text-xl font-black text-purple-900">Teorema Naive Bayes</h5>
                                <p class="text-sm text-purple-700">Formula dasar klasifikasi probabilistik</p>
                            </div>
                        </div>
                        
                        <!-- Formula Utama -->
                        <div class="bg-white rounded-xl p-5 border-2 border-purple-300 shadow-inner mb-4">
                            <div class="text-center mb-3">
                                <div class="text-xs text-purple-700 font-semibold mb-2">Rumus Umum:</div>
                                <div class="text-xl font-serif bg-purple-50 rounded-lg p-4 border border-purple-200">
                                    <MathFormula 
                                        formula="P(C_k|X) = \\frac{P(C_k) × P(X|C_k)}{P(X)}"
                                        class="font-bold text-purple-900"
                                    />
                                </div>
                            </div>
                            
                            <!-- Simplified Form -->
                            <div class="mt-4 pt-4 border-t border-purple-200">
                                <div class="text-xs text-purple-700 font-semibold mb-2 text-center">Bentuk Sederhana (Tanpa Normalisasi):</div>
                                <div class="text-lg font-serif bg-purple-50 rounded-lg p-4 border border-purple-200 text-center">
                                    <MathFormula 
                                        formula="P(C_k|X) ∝ P(C_k) × \\prod_{i=1}^{n} P(X_i|C_k)"
                                        class="font-bold text-purple-900"
                                    />
                                </div>
                            </div>
                        </div>

                        <!-- Penjelasan Komponen -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                            <div class="bg-white rounded-lg p-3 border border-purple-200">
                                <div class="flex items-start gap-2">
                                    <div class="w-6 h-6 bg-purple-500 text-white rounded flex items-center justify-center text-xs font-bold flex-shrink-0">1</div>
                                    <div class="text-xs">
                                        <div class="font-semibold text-purple-900 mb-1">
                                            <MathFormula formula="P(C_k)" inline /> = Prior Probability
                                        </div>
                                        <div class="text-slate-600">Probabilitas awal kelas C<sub>k</sub></div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="bg-white rounded-lg p-3 border border-purple-200">
                                <div class="flex items-start gap-2">
                                    <div class="w-6 h-6 bg-purple-500 text-white rounded flex items-center justify-center text-xs font-bold flex-shrink-0">2</div>
                                    <div class="text-xs">
                                        <div class="font-semibold text-purple-900 mb-1">
                                            <MathFormula formula="P(X_i|C_k)" inline /> = Likelihood
                                        </div>
                                        <div class="text-slate-600">Probabilitas atribut X<sub>i</sub> pada kelas C<sub>k</sub></div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="bg-white rounded-lg p-3 border border-purple-200">
                                <div class="flex items-start gap-2">
                                    <div class="w-6 h-6 bg-purple-500 text-white rounded flex items-center justify-center text-xs font-bold flex-shrink-0">3</div>
                                    <div class="text-xs">
                                        <div class="font-semibold text-purple-900 mb-1">
                                            <MathFormula formula="\\prod_{i=1}^{n}" inline /> = Perkalian
                                        </div>
                                        <div class="text-slate-600">Perkalian semua likelihood (i=1 hingga n)</div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="bg-white rounded-lg p-3 border border-purple-200">
                                <div class="flex items-start gap-2">
                                    <div class="w-6 h-6 bg-purple-500 text-white rounded flex items-center justify-center text-xs font-bold flex-shrink-0">4</div>
                                    <div class="text-xs">
                                        <div class="font-semibold text-purple-900 mb-1">
                                            <MathFormula formula="P(X)" inline /> = Evidence
                                        </div>
                                        <div class="text-slate-600">Probabilitas total (sama untuk semua kelas)</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Note -->
                        <div class="mt-4 bg-purple-100 border border-purple-300 rounded-lg p-3">
                            <div class="flex items-start gap-2 text-xs text-purple-800">
                                <svg class="w-4 h-4 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                                </svg>
                                <div>
                                    <strong>Catatan:</strong> Karena P(X) sama untuk semua kelas, kita bisa menggunakan simbol ∝ (proporsional) dan mengabaikan denominator. Normalisasi dilakukan di akhir untuk mendapatkan persentase.
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div
                        v-for="(step, index) in calculationSteps"
                        :key="index"
                        :class="[
                            'mb-6 rounded-xl p-6 border-2 shadow-lg',
                            index === 0 
                                ? 'bg-gradient-to-br from-green-50 to-emerald-50 border-green-400' 
                                : 'bg-white border-slate-200'
                        ]"
                    >
                        <!-- Step Header -->
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center gap-3">
                                <div
                                    :class="[
                                        'w-10 h-10 rounded-full flex items-center justify-center font-bold text-lg shadow-md',
                                        index === 0 
                                            ? 'bg-gradient-to-br from-green-500 to-emerald-600 text-white' 
                                            : 'bg-gradient-to-br from-sky-500 to-blue-600 text-white'
                                    ]"
                                >
                                    {{ index + 1 }}
                                </div>
                                <div>
                                    <div class="flex items-center gap-2">
                                        <h5 class="text-xl font-bold text-slate-800">{{ step.class_name }}</h5>
                                        <span class="px-2 py-0.5 bg-slate-200 text-slate-700 rounded text-xs font-mono">{{ step.class_code }}</span>
                                    </div>
                                    <p class="text-sm text-slate-600 mt-0.5">Menghitung P({{ step.class_code }} | Evidence)</p>
                                </div>
                            </div>
                            <div
                                v-if="index === 0"
                                class="flex items-center gap-2 px-4 py-2 bg-green-500 text-white rounded-full font-bold shadow-md"
                            >
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                </svg>
                                <span>TERTINGGI</span>
                            </div>
                        </div>

                        <!-- Step 1: Prior Probability -->
                        <div class="space-y-4">
                            <div class="bg-white/70 rounded-lg p-5 border border-blue-200">
                                <div class="flex items-center gap-2 mb-3">
                                    <div class="w-6 h-6 bg-blue-500 text-white rounded-full flex items-center justify-center text-xs font-bold">1</div>
                                    <h6 class="font-bold text-slate-800">Prior Probability (Probabilitas Awal)</h6>
                                </div>
                                <div class="ml-8">
                                    <div class="bg-blue-50 rounded-lg p-4 mb-3 border border-blue-200">
                                        <div class="text-sm text-slate-700 mb-2">Formula:</div>
                                        <div class="font-mono text-base mb-2">
                                            <MathFormula 
                                                :formula="`P(${step.class_code}) = \\frac{1}{${step.prior_fraction.denominator}}`"
                                                inline
                                            />
                                        </div>
                                        <div class="text-xs text-slate-600">
                                            = <span class="text-lg font-bold text-blue-600">{{ step.prior.toFixed(4) }}</span>
                                            atau <span class="font-bold text-blue-600">{{ (step.prior * 100).toFixed(2) }}%</span>
                                        </div>
                                    </div>
                                    <p class="text-xs text-slate-600 italic">
                                        <strong>Penjelasan:</strong> Probabilitas awal untuk kelas {{ step.class_name }} diasumsikan sama untuk semua kelas (uniform distribution) karena tidak ada informasi prior.
                                    </p>
                                </div>
                            </div>

                            <!-- Step 2: Likelihoods -->
                            <div class="bg-white/70 rounded-lg p-5 border border-blue-200">
                                <div class="flex items-center gap-2 mb-3">
                                    <div class="w-6 h-6 bg-blue-500 text-white rounded-full flex items-center justify-center text-xs font-bold">2</div>
                                    <h6 class="font-bold text-slate-800">Likelihood (Kemungkinan / Probabilitas Kondisional)</h6>
                                </div>
                                <div class="ml-8 space-y-3">
                                    <div class="bg-blue-50 rounded-lg p-3 border border-blue-200 mb-3">
                                        <p class="text-xs text-slate-700">
                                            <strong>Definisi:</strong> Probabilitas munculnya setiap gejala <strong>jika</strong> termasuk kelas {{ step.class_name }}.
                                        </p>
                                        <p class="text-xs text-slate-600 mt-2">
                                            <strong>Metode:</strong> Bernoulli Naive Bayes dengan asumsi:
                                        </p>
                                        <ul class="text-xs text-slate-600 ml-4 mt-1 space-y-1">
                                            <li>• Jika gejala <strong>terkait</strong> dengan kelas → P = 0.9 (90%)</li>
                                            <li>• Jika gejala <strong>tidak terkait</strong> dengan kelas → P = 0.1 (10%)</li>
                                        </ul>
                                    </div>
                                    
                                    <div
                                        v-for="(lh, lhIndex) in step.likelihoods"
                                        :key="lhIndex"
                                        :class="[
                                            'rounded-lg border-2 overflow-hidden',
                                            lh.is_associated 
                                                ? 'bg-green-50 border-green-300' 
                                                : 'bg-orange-50 border-orange-300'
                                        ]"
                                    >
                                        <div class="flex items-center justify-between py-3 px-4">
                                            <div class="flex items-center gap-3">
                                                <div
                                                    :class="[
                                                        'w-8 h-8 rounded-full flex items-center justify-center shadow-sm',
                                                        lh.is_associated ? 'bg-green-500' : 'bg-orange-500'
                                                    ]"
                                                >
                                                    <svg v-if="lh.is_associated" class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                                                    </svg>
                                                    <svg v-else class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12" />
                                                    </svg>
                                                </div>
                                                <div>
                                                    <div class="font-mono text-sm font-bold text-slate-800">
                                                        <MathFormula 
                                                            :formula="`P(${lh.attribute_code} | ${step.class_code})`"
                                                            inline
                                                        />
                                                    </div>
                                                    <div class="text-xs text-slate-600">
                                                        {{ lh.attribute_name }}
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="text-right">
                                                <div
                                                    :class="[
                                                        'text-2xl font-black',
                                                        lh.is_associated ? 'text-green-600' : 'text-orange-600'
                                                    ]"
                                                >
                                                    {{ lh.value.toFixed(2) }}
                                                </div>
                                                <div
                                                    :class="[
                                                        'text-xs font-medium',
                                                        lh.is_associated ? 'text-green-600' : 'text-orange-600'
                                                    ]"
                                                >
                                                    {{ lh.is_associated ? '✓ Terkait' : '✗ Tidak Terkait' }}
                                                </div>
                                            </div>
                                        </div>
                                        <div
                                            :class="[
                                                'px-4 py-2 text-xs border-t',
                                                lh.is_associated 
                                                    ? 'bg-green-100/50 border-green-200 text-green-800' 
                                                    : 'bg-orange-100/50 border-orange-200 text-orange-800'
                                            ]"
                                        >
                                            <strong>Interpretasi:</strong> 
                                            <span v-if="lh.is_associated">
                                                Gejala "{{ lh.attribute_name }}" memiliki hubungan kuat dengan {{ step.class_name }}
                                            </span>
                                            <span v-else>
                                                Gejala "{{ lh.attribute_name }}" jarang terjadi pada {{ step.class_name }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Step 3: Calculation -->
                            <div class="bg-white/70 rounded-lg p-5 border border-blue-200">
                                <div class="flex items-center gap-2 mb-3">
                                    <div class="w-6 h-6 bg-blue-500 text-white rounded-full flex items-center justify-center text-xs font-bold">3</div>
                                    <h6 class="font-bold text-slate-800">Perhitungan Naive Bayes (Posterior Probability)</h6>
                                </div>
                                <div class="ml-8 space-y-3">
                                    <!-- Formula with symbols -->
                                    <div class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-lg p-5 border-2 border-blue-300">
                                        <div class="text-sm text-slate-700 mb-3 font-semibold flex items-center gap-2">
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M13 7H7v6h6V7z"/>
                                                <path fill-rule="evenodd" d="M7 2a1 1 0 012 0v1h2V2a1 1 0 112 0v1h2a2 2 0 012 2v2h1a1 1 0 110 2h-1v2h1a1 1 0 110 2h-1v2a2 2 0 01-2 2h-2v1a1 1 0 11-2 0v-1H9v1a1 1 0 11-2 0v-1H5a2 2 0 01-2-2v-2H2a1 1 0 110-2h1V9H2a1 1 0 010-2h1V5a2 2 0 012-2h2V2zM5 5h10v10H5V5z" clip-rule="evenodd"/>
                                            </svg>
                                            Formula Umum:
                                        </div>
                                        <div class="bg-white rounded-lg p-3 mb-3 border border-blue-200">
                                            <div class="text-center font-mono text-base text-slate-800">
                                                <MathFormula 
                                                    :formula="`P(${step.class_code} | Evidence) ∝ P(${step.class_code}) × ` + 
                                                    step.likelihoods.map((lh, i) => `P(${lh.attribute_code}|${step.class_code})`).join(' × ')"
                                                />
                                            </div>
                                        </div>
                                        <div class="text-xs text-slate-600 space-y-1">
                                            <div>• <strong>∝</strong> = "proporsional dengan" (mengabaikan denominator karena sama untuk semua kelas)</div>
                                            <div>• Formula ini menghitung <strong>unnormalized posterior probability</strong></div>
                                        </div>
                                    </div>

                                    <!-- Substitution -->
                                    <div class="bg-gradient-to-br from-purple-50 to-pink-50 rounded-lg p-5 border-2 border-purple-300">
                                        <div class="text-sm text-slate-700 mb-3 font-semibold flex items-center gap-2">
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd"/>
                                            </svg>
                                            Substitusi Nilai:
                                        </div>
                                        <div class="bg-white rounded-lg p-3 mb-2 border border-purple-200">
                                            <div class="font-mono text-sm text-slate-800 text-center">
                                                <MathFormula 
                                                    :formula="`P(${step.class_code} | Evidence) ∝ ${step.prior.toFixed(4)} × ` + 
                                                    step.likelihoods.map(lh => lh.value.toFixed(2)).join(' × ')"
                                                />
                                            </div>
                                        </div>
                                        <div class="text-xs text-slate-600">
                                            Mengganti setiap probabilitas dengan nilai numerik yang sudah dihitung
                                        </div>
                                    </div>

                                    <!-- Detailed Calculation -->
                                    <div class="bg-gradient-to-br from-amber-50 to-yellow-50 rounded-lg p-5 border-2 border-amber-300">
                                        <div class="text-sm text-slate-700 mb-3 font-semibold flex items-center gap-2">
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M2 11a1 1 0 011-1h2a1 1 0 011 1v5a1 1 0 01-1 1H3a1 1 0 01-1-1v-5zM8 7a1 1 0 011-1h2a1 1 0 011 1v9a1 1 0 01-1 1H9a1 1 0 01-1-1V7zM14 4a1 1 0 011-1h2a1 1 0 011 1v12a1 1 0 01-1 1h-2a1 1 0 01-1-1V4z"/>
                                            </svg>
                                            Perhitungan Step-by-Step:
                                        </div>
                                        <div class="bg-white rounded-lg p-4 border border-amber-200 space-y-2">
                                            <div class="flex items-center justify-between text-sm">
                                                <span class="text-slate-600">Prior Probability:</span>
                                                <span class="font-mono font-bold">{{ step.prior.toFixed(4) }}</span>
                                            </div>
                                            <div 
                                                v-for="(lh, lhIndex) in step.likelihoods" 
                                                :key="lhIndex"
                                                class="flex items-center justify-between text-sm"
                                            >
                                                <span class="text-slate-600">× P({{ lh.attribute_code }}|{{ step.class_code }}):</span>
                                                <span class="font-mono font-bold">{{ lh.value.toFixed(2) }}</span>
                                            </div>
                                            <div class="border-t-2 border-amber-300 pt-2 mt-2">
                                                <div class="flex items-center justify-between">
                                                    <span class="text-slate-700 font-semibold">Hasil Perkalian:</span>
                                                    <span class="font-mono text-lg font-bold text-amber-700">
                                                        {{ step.raw_score.toFixed(10) }}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Result -->
                                    <div
                                        :class="[
                                            'rounded-xl p-6 border-3 shadow-lg',
                                            index === 0 
                                                ? 'bg-gradient-to-br from-green-100 via-emerald-100 to-teal-100 border-green-500' 
                                                : 'bg-gradient-to-br from-sky-100 via-blue-100 to-indigo-100 border-blue-500'
                                        ]"
                                    >
                                        <div class="flex items-center gap-2 mb-3">
                                            <svg 
                                                :class="['w-6 h-6', index === 0 ? 'text-green-600' : 'text-blue-600']"
                                                fill="currentColor" 
                                                viewBox="0 0 20 20"
                                            >
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                            </svg>
                                            <span class="text-sm font-semibold" :class="index === 0 ? 'text-green-800' : 'text-blue-800'">
                                                Skor Akhir (Unnormalized):
                                            </span>
                                        </div>
                                        <div class="bg-white/70 rounded-lg p-4 border-2" :class="index === 0 ? 'border-green-300' : 'border-blue-300'">
                                            <div class="font-mono text-3xl font-black mb-2 text-center"
                                                :class="index === 0 ? 'text-green-700' : 'text-blue-700'"
                                            >
                                                {{ step.raw_score.toExponential(8) }}
                                            </div>
                                            <div class="text-xs text-center text-slate-600 italic">
                                                Notasi ilmiah: {{ step.raw_score.toExponential(2) }}
                                            </div>
                                        </div>
                                        <div class="mt-3 text-xs text-slate-600 italic">
                                            💡 <strong>Catatan:</strong> Skor ini masih dalam bentuk "raw" dan akan dinormalisasi pada langkah berikutnya agar total semua kelas = 100%
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Step 4: Normalization -->
                            <div class="bg-white/70 rounded-lg p-5 border border-blue-200">
                                <div class="flex items-center gap-2 mb-3">
                                    <div class="w-6 h-6 bg-blue-500 text-white rounded-full flex items-center justify-center text-xs font-bold">4</div>
                                    <h6 class="font-bold text-slate-800">Normalisasi (Konversi ke Persentase)</h6>
                                </div>
                                <div class="ml-8 space-y-3">
                                    <div class="bg-blue-50 rounded-lg p-4 border border-blue-200">
                                        <div class="text-sm text-slate-700 mb-2 font-semibold">Formula Normalisasi:</div>
                                        <div class="bg-white rounded-lg p-3 border border-blue-200">
                                            <div class="text-center font-mono text-base mb-2">
                                                <MathFormula 
                                                    :formula="`P(${step.class_code}|Evidence) = \\frac{Score(${step.class_code})}{\\sum_{i} Score(C_i)} × 100%`"
                                                />
                                            </div>
                                        </div>
                                        <div class="text-xs text-slate-600 mt-2">
                                            <strong>Tujuan:</strong> Mengubah skor mentah menjadi probabilitas yang dijumlahkan = 100%
                                        </div>
                                    </div>

                                    <div class="bg-purple-50 rounded-lg p-4 border border-purple-200">
                                        <div class="text-sm text-slate-700 mb-2 font-semibold">Substitusi:</div>
                                        <div class="bg-white rounded-lg p-3 border border-purple-200">
                                            <div class="text-center font-mono text-sm">
                                                <MathFormula 
                                                    :formula="`P(${step.class_code}|Evidence) = \\frac{${step.raw_score.toExponential(4)}}{${step.total_score.toExponential(4)}} × 100%`"
                                                />
                                            </div>
                                        </div>
                                    </div>

                                    <div class="bg-amber-50 rounded-lg p-4 border border-amber-200">
                                        <div class="text-sm text-slate-700 mb-2 font-semibold">Perhitungan:</div>
                                        <div class="space-y-2 text-sm">
                                            <div class="flex justify-between items-center">
                                                <span class="text-slate-600">Skor {{ step.class_name }}:</span>
                                                <span class="font-mono">{{ step.raw_score.toExponential(6) }}</span>
                                            </div>
                                            <div class="flex justify-between items-center">
                                                <span class="text-slate-600">Total Skor Semua Kelas:</span>
                                                <span class="font-mono">{{ step.total_score.toExponential(6) }}</span>
                                            </div>
                                            <div class="border-t-2 border-amber-300 pt-2">
                                                <div class="flex justify-between items-center">
                                                    <span class="text-slate-700 font-semibold">Hasil Pembagian:</span>
                                                    <span class="font-mono font-bold">{{ (step.raw_score / step.total_score).toFixed(6) }}</span>
                                                </div>
                                            </div>
                                            <div class="flex justify-between items-center">
                                                <span class="text-slate-700 font-semibold">× 100%:</span>
                                                <span class="font-mono text-lg font-bold text-amber-700">{{ step.percentage.toFixed(4) }}%</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Step 5: Final Probability -->
                            <div
                                :class="[
                                    'rounded-xl p-6 border-3 shadow-2xl relative overflow-hidden',
                                    index === 0 
                                        ? 'bg-gradient-to-br from-green-100 via-emerald-100 to-teal-100 border-green-500' 
                                        : 'bg-gradient-to-br from-sky-100 via-blue-100 to-indigo-100 border-blue-500'
                                ]"
                            >
                                <!-- Background Pattern -->
                                <div class="absolute inset-0 opacity-5">
                                    <div class="absolute inset-0" style="background-image: radial-gradient(circle, currentColor 1px, transparent 1px); background-size: 20px 20px;"></div>
                                </div>

                                <div class="relative z-10">
                                    <div class="flex items-center justify-between mb-4">
                                        <div class="flex items-center gap-3">
                                            <div
                                                :class="[
                                                    'w-12 h-12 rounded-xl flex items-center justify-center shadow-lg',
                                                    index === 0 
                                                        ? 'bg-gradient-to-br from-green-500 to-emerald-600' 
                                                        : 'bg-gradient-to-br from-sky-500 to-blue-600'
                                                ]"
                                            >
                                                <svg class="w-7 h-7 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                                </svg>
                                            </div>
                                            <div>
                                                <h6
                                                    :class="[
                                                        'font-black text-xl',
                                                        index === 0 ? 'text-green-800' : 'text-blue-800'
                                                    ]"
                                                >
                                                    Probabilitas Akhir
                                                </h6>
                                                <p class="text-xs text-slate-600">Setelah normalisasi Bayesian</p>
                                            </div>
                                        </div>
                                        <div 
                                            v-if="index === 0"
                                            class="flex items-center gap-2 px-4 py-2 bg-green-500 text-white rounded-full font-bold shadow-lg animate-pulse"
                                        >
                                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                            </svg>
                                            <span>TERTINGGI</span>
                                        </div>
                                    </div>

                                    <div class="bg-white/80 backdrop-blur-sm rounded-xl p-6 border-2" :class="index === 0 ? 'border-green-300' : 'border-blue-300'">
                                        <div class="text-center mb-4">
                                            <div
                                                :class="[
                                                    'text-7xl font-black mb-2 tracking-tight',
                                                    index === 0 ? 'text-green-600' : 'text-blue-600'
                                                ]"
                                            >
                                                {{ step.percentage.toFixed(2) }}%
                                            </div>
                                            <div class="text-sm text-slate-600">
                                                <MathFormula 
                                                    :formula="`P(${step.class_code} | Evidence) = ${step.percentage.toFixed(2)}\\%`"
                                                />
                                            </div>
                                        </div>

                                        <!-- Progress Bar -->
                                        <div class="w-full bg-slate-200 rounded-full h-4 overflow-hidden mb-3">
                                            <div
                                                :class="[
                                                    'h-full rounded-full transition-all duration-1000 ease-out',
                                                    index === 0 
                                                        ? 'bg-gradient-to-r from-green-500 via-emerald-500 to-teal-500' 
                                                        : 'bg-gradient-to-r from-sky-500 via-blue-500 to-indigo-500'
                                                ]"
                                                :style="{ width: `${step.percentage}%` }"
                                            ></div>
                                        </div>

                                        <p class="text-sm text-center text-slate-700">
                                            <strong>Interpretasi:</strong> Berdasarkan gejala yang dipilih, probabilitas bahwa diagnosis adalah 
                                            <strong :class="index === 0 ? 'text-green-700' : 'text-blue-700'">{{ step.class_name }}</strong> 
                                            adalah <strong>{{ step.percentage.toFixed(2) }}%</strong>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Total Probability Note -->
                    <div class="mt-6 bg-white rounded-lg p-4 border border-slate-300">
                        <div class="flex items-start gap-3">
                            <div class="w-6 h-6 bg-yellow-500 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                                <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="flex-1">
                                <div class="font-semibold text-slate-800 mb-1">Catatan Penting:</div>
                                <p class="text-sm text-slate-600">
                                    Total probabilitas dari semua kelas = <strong>100%</strong>. Kelas dengan probabilitas tertinggi merupakan prediksi terbaik dari sistem berdasarkan metode <strong>Naive Bayes Classification</strong>.
                                </p>
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

/* Custom Scrollbar */
.custom-scrollbar::-webkit-scrollbar {
    width: 10px;
}

.custom-scrollbar::-webkit-scrollbar-track {
    background: #f1f5f9;
    border-radius: 5px;
}

.custom-scrollbar::-webkit-scrollbar-thumb {
    background: linear-gradient(180deg, #0ea5e9, #3b82f6);
    border-radius: 5px;
    transition: background 0.2s;
}

.custom-scrollbar::-webkit-scrollbar-thumb:hover {
    background: linear-gradient(180deg, #0284c7, #2563eb);
}

/* Firefox */
.custom-scrollbar {
    scrollbar-width: thin;
    scrollbar-color: #3b82f6 #f1f5f9;
}
</style>

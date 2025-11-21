<template>
    <DocumentationLayout title="Documentation">
        <div class="py-12 bg-slate-50">
            <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
                <!-- Language Toggle -->
                <div class="flex justify-end mb-6">
                    <div class="bg-white rounded-lg shadow-md p-2 flex gap-2">
                        <button 
                            @click="locale = 'en'" 
                            :class="locale === 'en' ? 'bg-blue-600 text-white' : 'text-slate-600 hover:bg-slate-100'"
                            class="px-4 py-2 rounded-md font-medium transition-all flex items-center gap-2"
                            title="Switch to English"
                        >
                            <span class="text-xl">🇬🇧</span>
                            <span>English</span>
                        </button>
                        <button 
                            @click="locale = 'id'" 
                            :class="locale === 'id' ? 'bg-blue-600 text-white' : 'text-slate-600 hover:bg-slate-100'"
                            class="px-4 py-2 rounded-md font-medium transition-all flex items-center gap-2"
                            title="Ganti ke Bahasa Indonesia"
                        >
                            <span class="text-xl">🇮🇩</span>
                            <span>Indonesia</span>
                        </button>
                    </div>
                </div>

                <!-- Hero Section -->
                <div class="bg-gradient-to-r from-blue-600 to-indigo-700 rounded-2xl p-8 mb-8 text-white shadow-xl">
                    <h1 class="text-4xl font-bold mb-3">{{ t.hero.title }}</h1>
                    <p class="text-blue-100 text-lg">{{ t.hero.subtitle }}</p>
                </div>

                <!-- Table of Contents -->
                <div class="bg-white rounded-xl shadow-md p-6 mb-8 border border-slate-200">
                    <h2 class="text-2xl font-bold text-slate-800 mb-4 flex items-center gap-2">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                        {{ t.toc.title }}
                    </h2>
                    <nav class="space-y-2">
                        <a v-for="(item, index) in t.toc.items" :key="index" :href="`#section-${index + 1}`" 
                           class="block text-blue-600 hover:text-blue-800 hover:translate-x-2 transition-all">
                            {{ index + 1 }}. {{ item }}
                        </a>
                    </nav>
                </div>

                <!-- Section 1: Introduction -->
                <section id="section-1" class="bg-white rounded-xl shadow-md p-8 mb-8 border border-slate-200">
                    <h2 class="text-3xl font-bold text-slate-800 mb-4 flex items-center gap-3">
                        <span class="flex items-center justify-center w-10 h-10 bg-blue-600 text-white rounded-lg">1</span>
                        {{ t.sections[0].title }}
                    </h2>
                    <p class="text-slate-700 mb-4 leading-relaxed" v-html="t.sections[0].content"></p>
                    <div class="bg-blue-50 border-l-4 border-blue-600 p-4 rounded">
                        <p class="text-slate-700"><strong>{{ t.sections[0].keyFeaturesTitle }}:</strong></p>
                        <ul class="list-disc list-inside mt-2 space-y-1 text-slate-600">
                            <li v-for="(feature, idx) in t.sections[0].features" :key="idx">{{ feature }}</li>
                        </ul>
                    </div>
                </section>

                <!-- Section 2: Getting Started -->
                <section id="section-2" class="bg-white rounded-xl shadow-md p-8 mb-8 border border-slate-200">
                    <h2 class="text-3xl font-bold text-slate-800 mb-4 flex items-center gap-3">
                        <span class="flex items-center justify-center w-10 h-10 bg-blue-600 text-white rounded-lg">2</span>
                        {{ t.sections[1].title }}
                    </h2>
                    
                    <h3 class="text-xl font-semibold text-slate-800 mb-3 mt-6">{{ t.sections[1].prerequisites.title }}</h3>
                    <ul class="list-disc list-inside space-y-2 text-slate-700 ml-4">
                        <li v-for="(item, idx) in t.sections[1].prerequisites.items" :key="idx">{{ item }}</li>
                    </ul>

                    <h3 class="text-xl font-semibold text-slate-800 mb-3 mt-6">{{ t.sections[1].firstSteps.title }}</h3>
                    <div class="space-y-4">
                        <div v-for="(step, idx) in t.sections[1].firstSteps.steps" :key="idx" class="flex gap-4">
                            <div class="flex-shrink-0 w-8 h-8 bg-blue-600 text-white rounded-full flex items-center justify-center font-bold">{{ idx + 1 }}</div>
                            <div>
                                <h4 class="font-semibold text-slate-800">{{ step.title }}</h4>
                                <p class="text-slate-600">{{ step.description }}</p>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Section 3: Workspace Management -->
                <section id="section-3" class="bg-white rounded-xl shadow-md p-8 mb-8 border border-slate-200">
                    <h2 class="text-3xl font-bold text-slate-800 mb-4 flex items-center gap-3">
                        <span class="flex items-center justify-center w-10 h-10 bg-blue-600 text-white rounded-lg">3</span>
                        {{ t.sections[2].title }}
                    </h2>
                    
                    <h3 class="text-xl font-semibold text-slate-800 mb-3 mt-6">{{ t.sections[2].creating.title }}</h3>
                    <p class="text-slate-700 mb-3">{{ t.sections[2].creating.intro }}</p>
                    <ol class="list-decimal list-inside space-y-2 text-slate-700 ml-4">
                        <li v-for="(step, idx) in t.sections[2].creating.steps" :key="idx" v-html="step"></li>
                    </ol>

                    <h3 class="text-xl font-semibold text-slate-800 mb-3 mt-6">{{ t.sections[2].managing.title }}</h3>
                    <div class="grid md:grid-cols-2 gap-4 mt-4">
                        <div v-for="(item, idx) in t.sections[2].managing.items" :key="idx" class="border border-slate-200 rounded-lg p-4">
                            <h4 class="font-semibold text-slate-800 mb-2">{{ item.title }}</h4>
                            <p class="text-slate-600 text-sm">{{ item.description }}</p>
                        </div>
                    </div>
                </section>

                <!-- Section 4: Configuration -->
                <section id="section-4" class="bg-white rounded-xl shadow-md p-8 mb-8 border border-slate-200">
                    <h2 class="text-3xl font-bold text-slate-800 mb-4 flex items-center gap-3">
                        <span class="flex items-center justify-center w-10 h-10 bg-blue-600 text-white rounded-lg">4</span>
                        {{ t.sections[3].title }}
                    </h2>
                    
                    <h3 class="text-xl font-semibold text-slate-800 mb-3 mt-6">{{ t.sections[3].predictors.title }}</h3>
                    <p class="text-slate-700 mb-3">{{ t.sections[3].predictors.intro }}</p>
                    <ol class="list-decimal list-inside space-y-2 text-slate-700 ml-4">
                        <li v-for="(step, idx) in t.sections[3].predictors.steps" :key="idx" v-html="step"></li>
                    </ol>

                    <h3 class="text-xl font-semibold text-slate-800 mb-3 mt-6">{{ t.sections[3].classes.title }}</h3>
                    <p class="text-slate-700 mb-3">{{ t.sections[3].classes.intro }}</p>
                    <ol class="list-decimal list-inside space-y-2 text-slate-700 ml-4">
                        <li v-for="(step, idx) in t.sections[3].classes.steps" :key="idx" v-html="step"></li>
                    </ol>

                    <div class="bg-yellow-50 border-l-4 border-yellow-500 p-4 rounded mt-4">
                        <p class="text-slate-700 text-sm" v-html="t.sections[3].warning"></p>
                    </div>
                </section>

                <!-- Section 5: Data Input -->
                <section id="section-5" class="bg-white rounded-xl shadow-md p-8 mb-8 border border-slate-200">
                    <h2 class="text-3xl font-bold text-slate-800 mb-4 flex items-center gap-3">
                        <span class="flex items-center justify-center w-10 h-10 bg-blue-600 text-white rounded-lg">5</span>
                        {{ t.sections[4].title }}
                    </h2>
                    
                    <h3 class="text-xl font-semibold text-slate-800 mb-3 mt-6">{{ t.sections[4].understanding.title }}</h3>
                    <p class="text-slate-700 mb-3">{{ t.sections[4].understanding.intro }}</p>
                    <ul class="list-disc list-inside space-y-2 text-slate-700 ml-4">
                        <li v-for="(item, idx) in t.sections[4].understanding.items" :key="idx" v-html="item"></li>
                    </ul>

                    <h3 class="text-xl font-semibold text-slate-800 mb-3 mt-6">{{ t.sections[4].entering.title }}</h3>
                    <ol class="list-decimal list-inside space-y-2 text-slate-700 ml-4">
                        <li v-for="(step, idx) in t.sections[4].entering.steps" :key="idx">{{ step }}</li>
                    </ol>

                    <h3 class="text-xl font-semibold text-slate-800 mb-3 mt-6">{{ t.sections[4].hints.title }}</h3>
                    <p class="text-slate-700 mb-3">{{ t.sections[4].hints.intro }}</p>
                    <ul class="list-disc list-inside space-y-2 text-slate-700 ml-4">
                        <li v-for="(item, idx) in t.sections[4].hints.items" :key="idx" v-html="item"></li>
                    </ul>
                </section>

                <!-- Section 6: Analysis & Calculation -->
                <section id="section-6" class="bg-white rounded-xl shadow-md p-8 mb-8 border border-slate-200">
                    <h2 class="text-3xl font-bold text-slate-800 mb-4 flex items-center gap-3">
                        <span class="flex items-center justify-center w-10 h-10 bg-blue-600 text-white rounded-lg">6</span>
                        {{ t.sections[5].title }}
                    </h2>
                    
                    <h3 class="text-xl font-semibold text-slate-800 mb-3 mt-6">{{ t.sections[5].viewing.title }}</h3>
                    <p class="text-slate-700 mb-3">{{ t.sections[5].viewing.intro }}</p>
                    <ul class="list-disc list-inside space-y-2 text-slate-700 ml-4">
                        <li v-for="(item, idx) in t.sections[5].viewing.items" :key="idx" v-html="item"></li>
                    </ul>

                    <h3 class="text-xl font-semibold text-slate-800 mb-3 mt-6">{{ t.sections[5].understanding.title }}</h3>
                    <div class="bg-slate-50 border border-slate-200 rounded-lg p-4 mt-4">
                        <p class="text-slate-700 mb-2"><strong>{{ t.sections[5].understanding.formulaTitle }}:</strong></p>
                        <p class="text-center text-lg font-mono bg-white p-4 rounded border border-slate-300">
                            P(Ck|X) ∝ P(Ck) × ∏<sub>i=1</sub><sup>n</sup> P(Xi|Ck)
                        </p>
                        <p class="text-slate-600 text-sm mt-3">
                            {{ t.sections[5].understanding.formulaDesc }}
                        </p>
                    </div>

                    <h3 class="text-xl font-semibold text-slate-800 mb-3 mt-6">{{ t.sections[5].zooming.title }}</h3>
                    <p class="text-slate-700 mb-3">{{ t.sections[5].zooming.intro }}</p>
                    <ul class="list-disc list-inside space-y-2 text-slate-700 ml-4">
                        <li v-for="(item, idx) in t.sections[5].zooming.items" :key="idx" v-html="item"></li>
                    </ul>
                </section>

                <!-- Section 7: Export to PDF -->
                <section id="section-7" class="bg-white rounded-xl shadow-md p-8 mb-8 border border-slate-200">
                    <h2 class="text-3xl font-bold text-slate-800 mb-4 flex items-center gap-3">
                        <span class="flex items-center justify-center w-10 h-10 bg-blue-600 text-white rounded-lg">7</span>
                        {{ t.sections[6].title }}
                    </h2>
                    
                    <h3 class="text-xl font-semibold text-slate-800 mb-3 mt-6">{{ t.sections[6].generating.title }}</h3>
                    <p class="text-slate-700 mb-3">{{ t.sections[6].generating.intro }}</p>
                    <ol class="list-decimal list-inside space-y-2 text-slate-700 ml-4">
                        <li v-for="(step, idx) in t.sections[6].generating.steps" :key="idx" v-html="step"></li>
                    </ol>

                    <h3 class="text-xl font-semibold text-slate-800 mb-3 mt-6">{{ t.sections[6].contents.title }}</h3>
                    <p class="text-slate-700 mb-3">{{ t.sections[6].contents.intro }}</p>
                    <ul class="list-disc list-inside space-y-2 text-slate-700 ml-4">
                        <li v-for="(item, idx) in t.sections[6].contents.items" :key="idx">{{ item }}</li>
                    </ul>
                </section>

                <!-- Section 8: Tips & Tricks -->
                <section id="section-8" class="bg-white rounded-xl shadow-md p-8 mb-8 border border-slate-200">
                    <h2 class="text-3xl font-bold text-slate-800 mb-4 flex items-center gap-3">
                        <span class="flex items-center justify-center w-10 h-10 bg-blue-600 text-white rounded-lg">8</span>
                        {{ t.sections[7].title }}
                    </h2>
                    
                    <div class="grid md:grid-cols-2 gap-4">
                        <div v-for="(category, idx) in t.sections[7].categories" :key="idx" 
                             :class="`bg-${category.color}-50 border-l-4 border-${category.color}-500 p-4 rounded`">
                            <h4 :class="`font-semibold text-${category.color}-800 mb-2`">{{ category.title }}</h4>
                            <ul class="text-slate-700 text-sm space-y-1">
                                <li v-for="(tip, tipIdx) in category.tips" :key="tipIdx">• {{ tip }}</li>
                            </ul>
                        </div>
                    </div>
                </section>

                <!-- Footer -->
                <div class="bg-gradient-to-r from-slate-700 to-slate-900 rounded-xl p-6 text-center text-white">
                    <p class="text-lg font-semibold mb-2">{{ t.footer.title }}</p>
                    <p class="text-slate-300 text-sm">{{ t.footer.description }}</p>
                </div>
            </div>
        </div>
    </DocumentationLayout>
</template>

<script setup>
import DocumentationLayout from '@/Layouts/DocumentationLayout.vue';
import { ref, computed } from 'vue';

const locale = ref('id'); // Default Indonesian

const translations = {
    en: {
        hero: {
            title: 'Probabilitas Pro Documentation',
            subtitle: 'Comprehensive guide to using Naive Bayes probability analysis system'
        },
        toc: {
            title: 'Table of Contents',
            items: [
                'Introduction',
                'Getting Started',
                'Workspace Management',
                'Configuration',
                'Data Input',
                'Analysis & Calculation',
                'Export to PDF',
                'Tips & Tricks'
            ]
        },
        sections: [
            // Section 1: Introduction
            {
                title: 'Introduction',
                content: '<strong>Probabilitas Pro</strong> is a professional web-based application designed for probability analysis using the Naive Bayes classification method. This system helps users analyze diagnostic cases, make predictions, and generate detailed reports with mathematical precision.',
                keyFeaturesTitle: 'Key Features',
                features: [
                    'Multiple workspace management',
                    'Dynamic predictor and class configuration',
                    'Real-time probability calculations',
                    'Mathematical formula rendering with MathJax',
                    'Detailed step-by-step analysis',
                    'Professional PDF export'
                ]
            },
            // Section 2: Getting Started
            {
                title: 'Getting Started',
                prerequisites: {
                    title: 'Prerequisites',
                    items: [
                        'Modern web browser (Chrome, Firefox, Safari, Edge)',
                        'Stable internet connection',
                        'Basic understanding of probability and classification concepts'
                    ]
                },
                firstSteps: {
                    title: 'First Steps',
                    steps: [
                        { title: 'Open the Application', description: 'Navigate to the application URL in your web browser.' },
                        { title: 'View Dashboard', description: 'You\'ll immediately see the main dashboard with workspace cards.' },
                        { title: 'Create New Workspace', description: 'Click the "Create New Worksheet" button to start a new analysis project.' }
                    ]
                }
            },
            // Section 3: Workspace Management
            {
                title: 'Workspace Management',
                creating: {
                    title: 'Creating a Workspace',
                    intro: 'To create a new workspace:',
                    steps: [
                        'Click the <strong>"Create New Worksheet"</strong> button on the dashboard',
                        'Enter a descriptive <strong>title</strong> for your study case',
                        'Add a <strong>description</strong> explaining the purpose of the analysis',
                        'Click <strong>"Create"</strong> to create the workspace'
                    ]
                },
                managing: {
                    title: 'Managing Workspaces',
                    items: [
                        { title: 'Open Workspace', description: 'Click on any workspace card to open and start working on it.' },
                        { title: 'Edit Workspace', description: 'Click the pencil icon to edit title and description.' },
                        { title: 'Delete Workspace', description: 'Click the trash icon to permanently delete a workspace.' },
                        { title: 'Recent Workspaces', description: 'Access recent workspaces from the navigation menu.' }
                    ]
                }
            },
            // Section 4: Configuration
            {
                title: 'Configuration',
                predictors: {
                    title: 'Adding Predictors (X Variables)',
                    intro: 'Predictors are the input features used for classification:',
                    steps: [
                        'Scroll to the <strong>Predictors (X)</strong> section in the left panel',
                        'Click the <strong>"+ Add Variable"</strong> button',
                        'Enter the predictor name (e.g., "Symptom 1", "Temperature")',
                        'The new predictor will automatically appear in the main table'
                    ]
                },
                classes: {
                    title: 'Adding Classes (Y Variables)',
                    intro: 'Classes are the categories for classification:',
                    steps: [
                        'Scroll to the <strong>Classes (Y)</strong> section in the left panel',
                        'Click the <strong>"+ Add Variable"</strong> button',
                        'Enter the class name (e.g., "Disease A", "Healthy")',
                        'The new class will automatically appear in the main table'
                    ]
                },
                warning: '<strong>⚠️ Important:</strong> You can add as many predictors and classes as needed. The system dynamically adjusts the table structure.'
            },
            // Section 5: Data Input
            {
                title: 'Data Input',
                understanding: {
                    title: 'Understanding the Main Table',
                    intro: 'The main table displays the frequency matrix:',
                    items: [
                        '<strong>Rows:</strong> Represent predictors (X variables)',
                        '<strong>Columns:</strong> Represent classes (Y variables)',
                        '<strong>Cells:</strong> Contain frequency values for each predictor-class combination'
                    ]
                },
                entering: {
                    title: 'Entering Data',
                    steps: [
                        'Click on any cell in the main table',
                        'Enter the frequency value (must be a positive number)',
                        'Press Enter or click outside to save',
                        'The system automatically saves changes'
                    ]
                },
                hints: {
                    title: 'Using Hints',
                    intro: 'Toggle hints to help you understand the table structure:',
                    items: [
                        'Click the <strong>"+ Show Hints"</strong> button to display helpful labels',
                        'Click the <strong>"− Hide Hints"</strong> button to hide labels',
                        'Hints show predictor and class names in a simplified format'
                    ]
                }
            },
            // Section 6: Analysis & Calculation
            {
                title: 'Analysis & Calculation',
                viewing: {
                    title: 'Viewing Analysis Results',
                    intro: 'The analysis panel shows detailed calculations:',
                    items: [
                        '<strong>Naive Bayes Formula:</strong> Main classification equation',
                        '<strong>Frequency Tables:</strong> Raw data per class',
                        '<strong>Probability Calculations:</strong> P(Xi|Ck) for each predictor',
                        '<strong>Prior Probabilities:</strong> P(Ck) for each class',
                        '<strong>Likelihood Scores:</strong> Combined probabilities',
                        '<strong>Posterior Probabilities:</strong> Final classification percentages',
                        '<strong>Conclusion:</strong> Most likely class prediction'
                    ]
                },
                understanding: {
                    title: 'Understanding the Math',
                    formulaTitle: 'The Naive Bayes formula',
                    formulaDesc: 'Where P(Ck|X) is the posterior probability of class Ck given evidence X, P(Ck) is the prior probability, and P(Xi|Ck) are the likelihoods.'
                },
                zooming: {
                    title: 'Zooming the Analysis Panel',
                    intro: 'Control the analysis view size:',
                    items: [
                        'Click <strong>"+50px"</strong> button to expand the panel',
                        'Click <strong>"−50px"</strong> button to collapse the panel',
                        'Zoom buttons stay fixed and follow the panel height'
                    ]
                }
            },
            // Section 7: Export to PDF
            {
                title: 'Export to PDF',
                generating: {
                    title: 'Generating PDF Reports',
                    intro: 'Export your analysis to a professional PDF document:',
                    steps: [
                        'Complete your data input and ensure analysis is visible',
                        'Click the <strong>"Download PDF Analysis"</strong> button in the header',
                        'Wait for the system to generate the PDF (may take a few seconds)',
                        'The PDF will automatically download to your device'
                    ]
                },
                contents: {
                    title: 'PDF Contents',
                    intro: 'The exported PDF includes:',
                    items: [
                        'Study case title and description',
                        'Complete frequency table',
                        'All mathematical formulas and calculations',
                        'Step-by-step analysis breakdown',
                        'Final conclusion and predictions',
                        'Timestamp and workspace information'
                    ]
                }
            },
            // Section 8: Tips & Tricks
            {
                title: 'Tips & Tricks',
                categories: [
                    {
                        color: 'green',
                        title: '✓ Best Practices',
                        tips: [
                            'Use descriptive names for predictors and classes',
                            'Double-check frequency values before analysis',
                            'Save your work regularly (auto-saved)',
                            'Export PDF for backup and sharing'
                        ]
                    },
                    {
                        color: 'blue',
                        title: '💡 Pro Tips',
                        tips: [
                            'Use zoom controls for better visibility',
                            'Toggle hints when teaching or presenting',
                            'Create multiple workspaces for comparison',
                            'Review recent workspaces for quick access'
                        ]
                    },
                    {
                        color: 'yellow',
                        title: '⚠️ Common Issues',
                        tips: [
                            'Ensure all cells have valid numbers',
                            'Don\'t use negative or zero values',
                            'Refresh if calculations don\'t update',
                            'Clear browser cache if UI is slow'
                        ]
                    },
                    {
                        color: 'purple',
                        title: '🎯 Accuracy Tips',
                        tips: [
                            'Use sufficient training data',
                            'Balance class distributions',
                            'Verify calculation steps manually',
                            'Compare results with expected outcomes'
                        ]
                    }
                ]
            }
        ],
        footer: {
            title: 'Need More Help?',
            description: 'For additional support or questions, please contact your system administrator or refer to the Probabilitas Pro Project documentation.'
        }
    },
    id: {
        hero: {
            title: 'Dokumentasi Probabilitas Pro',
            subtitle: 'Panduan lengkap menggunakan sistem analisis probabilitas Naive Bayes'
        },
        toc: {
            title: 'Daftar Isi',
            items: [
                'Pengenalan',
                'Memulai',
                'Manajemen Workspace',
                'Konfigurasi',
                'Input Data',
                'Analisis & Perhitungan',
                'Ekspor ke PDF',
                'Tips & Trik'
            ]
        },
        sections: [
            // Section 1: Pengenalan
            {
                title: 'Pengenalan',
                content: '<strong>Probabilitas Pro</strong> adalah aplikasi berbasis web profesional yang dirancang untuk analisis probabilitas menggunakan metode klasifikasi Naive Bayes. Sistem ini membantu pengguna menganalisis kasus diagnostik, membuat prediksi, dan menghasilkan laporan detail dengan presisi matematis.',
                keyFeaturesTitle: 'Fitur Utama',
                features: [
                    'Manajemen workspace berganda',
                    'Konfigurasi prediktor dan kelas dinamis',
                    'Perhitungan probabilitas real-time',
                    'Rendering rumus matematika dengan MathJax',
                    'Analisis langkah demi langkah yang detail',
                    'Ekspor PDF profesional'
                ]
            },
            // Section 2: Memulai
            {
                title: 'Memulai',
                prerequisites: {
                    title: 'Persyaratan',
                    items: [
                        'Browser web modern (Chrome, Firefox, Safari, Edge)',
                        'Koneksi internet stabil',
                        'Pemahaman dasar tentang konsep probabilitas dan klasifikasi'
                    ]
                },
                firstSteps: {
                    title: 'Langkah Awal',
                    steps: [
                        { title: 'Buka Aplikasi', description: 'Navigasi ke URL aplikasi di web browser Anda.' },
                        { title: 'Lihat Dashboard', description: 'Anda akan langsung melihat dashboard utama dengan kartu workspace.' },
                        { title: 'Buat Workspace Baru', description: 'Klik tombol "Buat Worksheet Baru" untuk memulai proyek analisis baru.' }
                    ]
                }
            },
            // Section 3: Manajemen Workspace
            {
                title: 'Manajemen Workspace',
                creating: {
                    title: 'Membuat Workspace',
                    intro: 'Untuk membuat workspace baru:',
                    steps: [
                        'Klik tombol <strong>"Buat Worksheet Baru"</strong> di dashboard',
                        'Masukkan <strong>judul</strong> deskriptif untuk kasus studi Anda',
                        'Tambahkan <strong>deskripsi</strong> yang menjelaskan tujuan analisis',
                        'Klik <strong>"Buat"</strong> untuk membuat workspace'
                    ]
                },
                managing: {
                    title: 'Mengelola Workspace',
                    items: [
                        { title: 'Buka Workspace', description: 'Klik kartu workspace mana pun untuk membuka dan mulai bekerja.' },
                        { title: 'Edit Workspace', description: 'Klik ikon pensil untuk mengedit judul dan deskripsi.' },
                        { title: 'Hapus Workspace', description: 'Klik ikon tempat sampah untuk menghapus workspace secara permanen.' },
                        { title: 'Workspace Terbaru', description: 'Akses workspace terbaru dari menu navigasi.' }
                    ]
                }
            },
            // Section 4: Konfigurasi
            {
                title: 'Konfigurasi',
                predictors: {
                    title: 'Menambah Prediktor (Variabel X)',
                    intro: 'Prediktor adalah fitur input yang digunakan untuk klasifikasi:',
                    steps: [
                        'Scroll ke bagian <strong>Prediktor (X)</strong> di panel kiri',
                        'Klik tombol <strong>"+ Tambah Variabel"</strong>',
                        'Masukkan nama prediktor (mis., "Gejala 1", "Suhu")',
                        'Prediktor baru akan otomatis muncul di tabel utama'
                    ]
                },
                classes: {
                    title: 'Menambah Kelas (Variabel Y)',
                    intro: 'Kelas adalah kategori untuk klasifikasi:',
                    steps: [
                        'Scroll ke bagian <strong>Kelas (Y)</strong> di panel kiri',
                        'Klik tombol <strong>"+ Tambah Variabel"</strong>',
                        'Masukkan nama kelas (mis., "Penyakit A", "Sehat")',
                        'Kelas baru akan otomatis muncul di tabel utama'
                    ]
                },
                warning: '<strong>⚠️ Penting:</strong> Anda dapat menambahkan prediktor dan kelas sebanyak yang diperlukan. Sistem secara dinamis menyesuaikan struktur tabel.'
            },
            // Section 5: Input Data
            {
                title: 'Input Data',
                understanding: {
                    title: 'Memahami Tabel Utama',
                    intro: 'Tabel utama menampilkan matriks frekuensi:',
                    items: [
                        '<strong>Baris:</strong> Mewakili prediktor (variabel X)',
                        '<strong>Kolom:</strong> Mewakili kelas (variabel Y)',
                        '<strong>Sel:</strong> Berisi nilai frekuensi untuk setiap kombinasi prediktor-kelas'
                    ]
                },
                entering: {
                    title: 'Memasukkan Data',
                    steps: [
                        'Klik sel mana pun di tabel utama',
                        'Masukkan nilai frekuensi (harus berupa angka positif)',
                        'Tekan Enter atau klik di luar untuk menyimpan',
                        'Sistem secara otomatis menyimpan perubahan'
                    ]
                },
                hints: {
                    title: 'Menggunakan Petunjuk',
                    intro: 'Toggle petunjuk untuk membantu Anda memahami struktur tabel:',
                    items: [
                        'Klik tombol <strong>"+ Tampilkan Petunjuk"</strong> untuk menampilkan label bantuan',
                        'Klik tombol <strong>"− Sembunyikan Petunjuk"</strong> untuk menyembunyikan label',
                        'Petunjuk menunjukkan nama prediktor dan kelas dalam format sederhana'
                    ]
                }
            },
            // Section 6: Analisis & Perhitungan
            {
                title: 'Analisis & Perhitungan',
                viewing: {
                    title: 'Melihat Hasil Analisis',
                    intro: 'Panel analisis menunjukkan perhitungan detail:',
                    items: [
                        '<strong>Rumus Naive Bayes:</strong> Persamaan klasifikasi utama',
                        '<strong>Tabel Frekuensi:</strong> Data mentah per kelas',
                        '<strong>Perhitungan Probabilitas:</strong> P(Xi|Ck) untuk setiap prediktor',
                        '<strong>Probabilitas Prior:</strong> P(Ck) untuk setiap kelas',
                        '<strong>Skor Likelihood:</strong> Probabilitas gabungan',
                        '<strong>Probabilitas Posterior:</strong> Persentase klasifikasi akhir',
                        '<strong>Kesimpulan:</strong> Prediksi kelas yang paling mungkin'
                    ]
                },
                understanding: {
                    title: 'Memahami Matematika',
                    formulaTitle: 'Rumus Naive Bayes',
                    formulaDesc: 'Di mana P(Ck|X) adalah probabilitas posterior kelas Ck given evidence X, P(Ck) adalah probabilitas prior, dan P(Xi|Ck) adalah likelihood.'
                },
                zooming: {
                    title: 'Zoom Panel Analisis',
                    intro: 'Kontrol ukuran tampilan analisis:',
                    items: [
                        'Klik tombol <strong>"+50px"</strong> untuk memperluas panel',
                        'Klik tombol <strong>"−50px"</strong> untuk memperkecil panel',
                        'Tombol zoom tetap fixed dan mengikuti tinggi panel'
                    ]
                }
            },
            // Section 7: Ekspor ke PDF
            {
                title: 'Ekspor ke PDF',
                generating: {
                    title: 'Membuat Laporan PDF',
                    intro: 'Ekspor analisis Anda ke dokumen PDF profesional:',
                    steps: [
                        'Lengkapi input data Anda dan pastikan analisis terlihat',
                        'Klik tombol <strong>"Download PDF Analisis"</strong> di header',
                        'Tunggu sistem menghasilkan PDF (mungkin memerlukan beberapa detik)',
                        'PDF akan otomatis diunduh ke perangkat Anda'
                    ]
                },
                contents: {
                    title: 'Konten PDF',
                    intro: 'PDF yang diekspor mencakup:',
                    items: [
                        'Judul dan deskripsi kasus studi',
                        'Tabel frekuensi lengkap',
                        'Semua rumus dan perhitungan matematis',
                        'Rincian analisis langkah demi langkah',
                        'Kesimpulan dan prediksi akhir',
                        'Timestamp dan informasi workspace'
                    ]
                }
            },
            // Section 8: Tips & Trik
            {
                title: 'Tips & Trik',
                categories: [
                    {
                        color: 'green',
                        title: '✓ Praktik Terbaik',
                        tips: [
                            'Gunakan nama deskriptif untuk prediktor dan kelas',
                            'Periksa ulang nilai frekuensi sebelum analisis',
                            'Simpan pekerjaan Anda secara teratur (auto-saved)',
                            'Ekspor PDF untuk backup dan berbagi'
                        ]
                    },
                    {
                        color: 'blue',
                        title: '💡 Tips Pro',
                        tips: [
                            'Gunakan kontrol zoom untuk visibilitas lebih baik',
                            'Toggle petunjuk saat mengajar atau presentasi',
                            'Buat workspace berganda untuk perbandingan',
                            'Tinjau workspace terbaru untuk akses cepat'
                        ]
                    },
                    {
                        color: 'yellow',
                        title: '⚠️ Masalah Umum',
                        tips: [
                            'Pastikan semua sel memiliki angka yang valid',
                            'Jangan gunakan nilai negatif atau nol',
                            'Refresh jika perhitungan tidak update',
                            'Hapus cache browser jika UI lambat'
                        ]
                    },
                    {
                        color: 'purple',
                        title: '🎯 Tips Akurasi',
                        tips: [
                            'Gunakan data training yang cukup',
                            'Seimbangkan distribusi kelas',
                            'Verifikasi langkah perhitungan secara manual',
                            'Bandingkan hasil dengan hasil yang diharapkan'
                        ]
                    }
                ]
            }
        ],
        footer: {
            title: 'Butuh Bantuan Lebih Lanjut?',
            description: 'Untuk dukungan tambahan atau pertanyaan, silakan hubungi administrator sistem Anda atau rujuk dokumentasi Proyek Probabilitas Pro.'
        }
    }
};

const t = computed(() => translations[locale.value]);
</script>

<style scoped>
kbd {
    font-family: monospace;
}

section nav a {
    transition: all 0.3s ease;
}
</style>

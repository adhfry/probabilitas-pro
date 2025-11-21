<template>
    <DocumentationLayout title="Credits">
        <div class="py-12 bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50">
            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
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

                <!-- Hero Card -->
                <div class="bg-white rounded-2xl shadow-2xl overflow-hidden mb-8">
                    <div class="bg-gradient-to-r from-blue-600 via-indigo-600 to-purple-700 p-8 text-white text-center">
                        <div class="w-24 h-24 bg-white rounded-full mx-auto mb-4 flex items-center justify-center shadow-lg">
                            <svg class="w-12 h-12 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </div>
                        <h1 class="text-4xl font-bold mb-2">{{ t.hero.title }}</h1>
                        <p class="text-blue-100 text-lg">{{ t.hero.subtitle }}</p>
                    </div>

                    <!-- Developer Info -->
                    <div class="p-8">
                        <h2 class="text-2xl font-bold text-slate-800 mb-6 text-center">{{ t.developerSection.title }}</h2>
                        
                        <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl p-6 mb-6 border-2 border-blue-200">
                            <div class="text-center mb-4">
                                <div class="inline-block bg-gradient-to-r from-blue-600 to-indigo-600 text-white px-6 py-2 rounded-full text-sm font-semibold mb-3">
                                    {{ t.developerSection.role }}
                                </div>
                            </div>
                            
                            <div class="grid md:grid-cols-2 gap-4 text-slate-700">
                                <div class="flex items-start gap-3">
                                    <svg class="w-5 h-5 text-blue-600 mt-1 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                    <div>
                                        <p class="text-sm text-slate-500 uppercase tracking-wide">{{ t.developerSection.fields.fullName }}</p>
                                        <p class="font-bold text-lg text-slate-800">Ahda Firly Barori</p>
                                    </div>
                                </div>

                                <div class="flex items-start gap-3">
                                    <svg class="w-5 h-5 text-blue-600 mt-1 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2" />
                                    </svg>
                                    <div>
                                        <p class="text-sm text-slate-500 uppercase tracking-wide">{{ t.developerSection.fields.studentId }}</p>
                                        <p class="font-bold text-lg text-slate-800">2302310186</p>
                                    </div>
                                </div>

                                <div class="flex items-start gap-3">
                                    <svg class="w-5 h-5 text-blue-600 mt-1 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                    </svg>
                                    <div>
                                        <p class="text-sm text-slate-500 uppercase tracking-wide">{{ t.developerSection.fields.class }}</p>
                                        <p class="font-bold text-lg text-slate-800">Informatika D-23</p>
                                    </div>
                                </div>

                                <div class="flex items-start gap-3">
                                    <svg class="w-5 h-5 text-blue-600 mt-1 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                    </svg>
                                    <div>
                                        <p class="text-sm text-slate-500 uppercase tracking-wide">{{ t.developerSection.fields.faculty }}</p>
                                        <p class="font-bold text-slate-800">{{ t.developerSection.facultyName }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- University Info -->
                        <div class="bg-gradient-to-r from-indigo-50 to-purple-50 rounded-xl p-6 mb-6 border-2 border-indigo-200">
                            <div class="flex items-start gap-4">
                                <div class="flex-shrink-0">
                                    <svg class="w-8 h-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm text-slate-500 uppercase tracking-wide mb-1">{{ t.universitySection.label }}</p>
                                    <h3 class="text-xl font-bold text-slate-800 mb-1">{{ t.universitySection.name }}</h3>
                                    <p class="text-slate-600 flex items-center gap-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                        Sumenep, Jawa Timur, Indonesia
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Project Info -->
                        <div class="bg-gradient-to-r from-amber-50 to-orange-50 rounded-xl p-6 mb-6 border-2 border-amber-200">
                            <h3 class="text-lg font-bold text-slate-800 mb-4 flex items-center gap-2">
                                <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                {{ t.projectSection.title }}
                            </h3>
                            <div class="space-y-3 text-slate-700">
                                <div class="flex items-start gap-3">
                                    <span class="text-amber-600 font-semibold min-w-[120px]">{{ t.projectSection.fields.purpose }}:</span>
                                    <span>{{ t.projectSection.purposeValue }}</span>
                                </div>
                                <div class="flex items-start gap-3">
                                    <span class="text-amber-600 font-semibold min-w-[120px]">{{ t.projectSection.fields.supervisor }}:</span>
                                    <span>Pyepit Rineksoa Andriyanto, S.Kom., M.Kom.</span>
                                </div>
                                <div class="flex items-start gap-3">
                                    <span class="text-amber-600 font-semibold min-w-[120px]">{{ t.projectSection.fields.course }}:</span>
                                    <span>{{ t.projectSection.courseValue }}</span>
                                </div>
                                <div class="flex items-start gap-3">
                                    <span class="text-amber-600 font-semibold min-w-[120px]">{{ t.projectSection.fields.semester }}:</span>
                                    <span>{{ t.projectSection.semesterValue }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Development Stats -->
                        <div class="bg-gradient-to-r from-green-50 to-emerald-50 rounded-xl p-6 border-2 border-green-200">
                            <h3 class="text-lg font-bold text-slate-800 mb-4 flex items-center gap-2">
                                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                {{ t.timelineSection.title }}
                            </h3>
                            <div class="grid grid-cols-2 gap-4">
                                <div class="text-center p-4 bg-white rounded-lg shadow-sm">
                                    <p class="text-3xl font-bold text-green-600">1</p>
                                    <p class="text-sm text-slate-600">{{ t.timelineSection.dayDuration }}</p>
                                </div>
                                <div class="text-center p-4 bg-white rounded-lg shadow-sm">
                                    <p class="text-3xl font-bold text-green-600">18h 21m</p>
                                    <p class="text-sm text-slate-600">{{ t.timelineSection.activeDev }}</p>
                                </div>
                            </div>
                            <div class="mt-4 p-4 bg-white rounded-lg shadow-sm">
                                <p class="text-sm text-slate-600 text-center">
                                    <span class="font-semibold text-slate-800">{{ t.timelineSection.totalEffort }}:</span> 
                                    {{ t.timelineSection.effortDesc }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Technology Stack -->
                <div class="bg-white rounded-2xl shadow-xl p-8 mb-8">
                    <h2 class="text-2xl font-bold text-slate-800 mb-6 text-center">{{ t.techStack.title }}</h2>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        <div class="text-center p-4 bg-slate-50 rounded-lg hover:bg-slate-100 transition-colors">
                            <div class="text-3xl mb-2">⚡</div>
                            <p class="font-semibold text-slate-800">Laravel 10</p>
                            <p class="text-xs text-slate-500">Backend</p>
                        </div>
                        <div class="text-center p-4 bg-slate-50 rounded-lg hover:bg-slate-100 transition-colors">
                            <div class="text-3xl mb-2">🎨</div>
                            <p class="font-semibold text-slate-800">Vue 3</p>
                            <p class="text-xs text-slate-500">Frontend</p>
                        </div>
                        <div class="text-center p-4 bg-slate-50 rounded-lg hover:bg-slate-100 transition-colors">
                            <div class="text-3xl mb-2">🎯</div>
                            <p class="font-semibold text-slate-800">Inertia.js</p>
                            <p class="text-xs text-slate-500">SPA Framework</p>
                        </div>
                        <div class="text-center p-4 bg-slate-50 rounded-lg hover:bg-slate-100 transition-colors">
                            <div class="text-3xl mb-2">💨</div>
                            <p class="font-semibold text-slate-800">Tailwind CSS</p>
                            <p class="text-xs text-slate-500">Styling</p>
                        </div>
                        <div class="text-center p-4 bg-slate-50 rounded-lg hover:bg-slate-100 transition-colors">
                            <div class="text-3xl mb-2">📐</div>
                            <p class="font-semibold text-slate-800">MathJax</p>
                            <p class="text-xs text-slate-500">Math Rendering</p>
                        </div>
                        <div class="text-center p-4 bg-slate-50 rounded-lg hover:bg-slate-100 transition-colors">
                            <div class="text-3xl mb-2">📄</div>
                            <p class="font-semibold text-slate-800">mPDF</p>
                            <p class="text-xs text-slate-500">PDF Export</p>
                        </div>
                        <div class="text-center p-4 bg-slate-50 rounded-lg hover:bg-slate-100 transition-colors">
                            <div class="text-3xl mb-2">🗄️</div>
                            <p class="font-semibold text-slate-800">MySQL</p>
                            <p class="text-xs text-slate-500">Database</p>
                        </div>
                        <div class="text-center p-4 bg-slate-50 rounded-lg hover:bg-slate-100 transition-colors">
                            <div class="text-3xl mb-2">🔐</div>
                            <p class="font-semibold text-slate-800">Jetstream</p>
                            <p class="text-xs text-slate-500">Auth System</p>
                        </div>
                    </div>
                </div>

                <!-- Features Highlight -->
                <div class="bg-white rounded-2xl shadow-xl p-8 mb-8">
                    <h2 class="text-2xl font-bold text-slate-800 mb-6 text-center">{{ t.keyFeatures.title }}</h2>
                    <div class="grid md:grid-cols-2 gap-4">
                        <div v-for="(feature, idx) in t.keyFeatures.features" :key="idx" :class="`flex gap-3 p-4 bg-${feature.color}-50 rounded-lg`">
                            <svg :class="`w-6 h-6 text-${feature.color}-600 flex-shrink-0`" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <div>
                                <p class="font-semibold text-slate-800">{{ feature.title }}</p>
                                <p class="text-sm text-slate-600">{{ feature.description }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Acknowledgments -->
                <div class="bg-gradient-to-r from-slate-700 to-slate-900 rounded-2xl p-8 text-white text-center">
                    <h2 class="text-2xl font-bold mb-4">{{ t.acknowledgments.title }}</h2>
                    <p class="text-slate-300 mb-6 max-w-2xl mx-auto">
                        {{ t.acknowledgments.content }}
                    </p>
                    <div class="flex items-center justify-center gap-2 text-slate-400 text-sm">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span>{{ t.acknowledgments.footer }}</span>
                    </div>
                </div>

                <!-- Version Info -->
                <div class="mt-6 text-center text-slate-500 text-sm">
                    <p>{{ t.versionInfo.version }}</p>
                    <p class="mt-1">{{ t.versionInfo.copyright }}</p>
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
            title: 'Probabilitas Pro',
            subtitle: 'Naive Bayes Analysis System'
        },
        developerSection: {
            title: 'Developed By',
            role: 'Developer & Creator',
            fields: {
                fullName: 'Full Name',
                studentId: 'Student ID',
                class: 'Class',
                faculty: 'Faculty'
            },
            facultyName: 'Science and Technology'
        },
        universitySection: {
            label: 'University',
            name: 'Universitas Bahaudin Mudhary Madura',
            location: 'Sumenep, East Java, Indonesia'
        },
        projectSection: {
            title: 'Project Information',
            fields: {
                purpose: 'Purpose',
                supervisor: 'Supervisor',
                course: 'Course',
                semester: 'Semester'
            },
            purposeValue: 'Assignment 3 - Artificial Intelligence',
            courseValue: 'Artificial Intelligence',
            semesterValue: 'Odd Semester 2024/2025'
        },
        timelineSection: {
            title: 'Development Timeline',
            dayDuration: 'Day Duration',
            activeDev: 'Active Development',
            totalEffort: 'Total Effort',
            effortDesc: 'Intensive development session completed in record time with full feature implementation'
        },
        techStack: {
            title: 'Technology Stack'
        },
        keyFeatures: {
            title: 'Key Features',
            features: [
                { color: 'blue', title: 'Dynamic Variable Management', description: 'Add unlimited predictors and classes' },
                { color: 'indigo', title: 'Real-time Calculations', description: 'Instant Naive Bayes analysis' },
                { color: 'purple', title: 'Mathematical Precision', description: 'MathJax formula rendering' },
                { color: 'pink', title: 'Professional Reports', description: 'Detailed PDF export capability' },
                { color: 'green', title: 'Multiple Workspaces', description: 'Manage multiple study cases' },
                { color: 'yellow', title: 'User-Friendly Interface', description: 'Intuitive and modern design' }
            ]
        },
        acknowledgments: {
            title: 'Acknowledgments',
            content: 'Special thanks to my supervisor, Pyepit Rineksoa Andriyanto, for guidance throughout this project. This system represents the culmination of intensive research, development, and testing in applying Naive Bayes algorithm to real-world diagnostic scenarios.',
            footer: 'Developed with dedication and precision • 2024'
        },
        versionInfo: {
            version: 'Probabilitas Pro v1.0.0 • Built with Laravel & Vue.js',
            copyright: '© 2024 Ahda Firly Barori. All rights reserved.'
        }
    },
    id: {
        hero: {
            title: 'Probabilitas Pro',
            subtitle: 'Sistem Analisis Naive Bayes'
        },
        developerSection: {
            title: 'Dikembangkan Oleh',
            role: 'Developer & Pembuat',
            fields: {
                fullName: 'Nama Lengkap',
                studentId: 'NIM',
                class: 'Kelas',
                faculty: 'Fakultas'
            },
            facultyName: 'Sains dan Teknologi'
        },
        universitySection: {
            label: 'Universitas',
            name: 'Universitas Bahaudin Mudhary Madura',
            location: 'Sumenep, Jawa Timur, Indonesia'
        },
        projectSection: {
            title: 'Informasi Proyek',
            fields: {
                purpose: 'Tujuan',
                supervisor: 'Pembimbing',
                course: 'Mata Kuliah',
                semester: 'Semester'
            },
            purposeValue: 'Tugas 3 - Kecerdasan Buatan',
            courseValue: 'Kecerdasan Buatan (Artificial Intelligence)',
            semesterValue: 'Ganjil 2024/2025'
        },
        timelineSection: {
            title: 'Timeline Pengembangan',
            dayDuration: 'Durasi Hari',
            activeDev: 'Pengembangan Aktif',
            totalEffort: 'Total Usaha',
            effortDesc: 'Sesi pengembangan intensif selesai dalam waktu singkat dengan implementasi fitur lengkap'
        },
        techStack: {
            title: 'Stack Teknologi'
        },
        keyFeatures: {
            title: 'Fitur Utama',
            features: [
                { color: 'blue', title: 'Manajemen Variabel Dinamis', description: 'Tambahkan prediktor dan kelas tanpa batas' },
                { color: 'indigo', title: 'Perhitungan Real-time', description: 'Analisis Naive Bayes instan' },
                { color: 'purple', title: 'Presisi Matematis', description: 'Rendering rumus MathJax' },
                { color: 'pink', title: 'Laporan Profesional', description: 'Kemampuan ekspor PDF detail' },
                { color: 'green', title: 'Workspace Berganda', description: 'Kelola beberapa studi kasus' },
                { color: 'yellow', title: 'Antarmuka Ramah Pengguna', description: 'Desain intuitif dan modern' }
            ]
        },
        acknowledgments: {
            title: 'Ucapan Terima Kasih',
            content: 'Terima kasih khusus kepada pembimbing saya, Pyepit Rineksoa Andriyanto, atas bimbingan selama proyek ini. Sistem ini merupakan puncak dari riset intensif, pengembangan, dan pengujian dalam menerapkan algoritma Naive Bayes ke skenario diagnostik dunia nyata.',
            footer: 'Dikembangkan dengan dedikasi dan presisi • 2024'
        },
        versionInfo: {
            version: 'Probabilitas Pro v1.0.0 • Dibuat dengan Laravel & Vue.js',
            copyright: '© 2024 Ahda Firly Barori. Hak cipta dilindungi.'
        }
    }
};

const t = computed(() => translations[locale.value]);
</script>



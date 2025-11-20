# Probabilitas Pro

**Dynamic Expert System using Naive Bayes Classifier**

![License](https://img.shields.io/badge/License-Proprietary-red.svg)
![Laravel](https://img.shields.io/badge/Laravel-11-red.svg)
![Vue](https://img.shields.io/badge/Vue.js-3-green.svg)
![Inertia](https://img.shields.io/badge/Inertia.js-2-purple.svg)

---

## 📋 Deskripsi

**Probabilitas Pro** adalah platform berbasis web (SPA) untuk membangun sistem pakar diagnosa menggunakan metode **Naive Bayes Classifier**. Sistem ini dirancang **agnostik**, artinya pengguna dapat mendefinisikan sendiri variabel Gejala (Predictor/X) dan Kerusakan (Class/Y) secara dinamis.

Studi kasus default: **Diagnosa Kerusakan Komputer**

---

## 🎨 Fitur Utama

### 1. **Dynamic Variable Management**
- Kelola variabel X (Prediktor/Gejala) dan Y (Kelas/Kerusakan) secara dinamis
- Edit, tambah, hapus variabel real-time
- Customizable labels untuk setiap project

### 2. **Interactive Matrix Workspace**
- **Infinite Canvas** dengan zoom (Ctrl + Scroll) dan pan (Drag)
- Matrix table untuk menandai relasi antara X dan Y
- Auto-save ke database setiap perubahan
- Responsive dan smooth interaction

### 3. **Probabilistic Inference Engine**
- Implementasi **Bernoulli Naive Bayes** dengan Laplace Smoothing
- Calculation steps detail seperti Photomath
- Visualisasi hasil dengan bar chart dan percentage
- Real-time analysis dengan animasi loading

### 4. **Professional UI/UX**
- Tema: **Light Blue Professional** (Slate-50, Sky-500, Blue-600)
- Aurora Borealis loading animation
- Draggable analysis drawer
- Responsive design untuk berbagai device

---

## 🛠️ Tech Stack

| Category | Technology |
|----------|-----------|
| **Backend** | Laravel 11 |
| **Frontend** | Vue.js 3 (Composition API) |
| **SPA Framework** | Inertia.js |
| **Styling** | TailwindCSS |
| **Database** | MySQL |
| **Icons** | Lucide Icons / Heroicons |

---

## 📐 Arsitektur Database

### Tables:
1. **projects** - Menyimpan studi kasus
2. **attributes** - Variabel X (Gejala/Prediktor)
3. **classes** - Variabel Y (Kerusakan/Kelas)
4. **training_data** - Matrix relasi X-Y

Schema lengkap dapat dilihat di `database/migrations/`

---

## 🚀 Instalasi

### Requirements:
- PHP >= 8.2
- Composer
- Node.js >= 18
- MySQL/MariaDB
- Laragon (recommended)

### Setup:

```bash
# Clone repository
cd D:\Ahda\Web\probabilitas-pro

# Install PHP dependencies
composer install

# Install Node dependencies
npm install
# atau
pnpm install

# Copy environment file (jika belum)
cp .env.example .env

# Generate app key (sudah dilakukan)
php artisan key:generate

# Setup database di .env
DB_DATABASE=probabilitas_pro
DB_USERNAME=root
DB_PASSWORD=

# Run migrations (sudah dilakukan)
php artisan migrate:fresh

# Build assets
npm run build
# atau untuk development
npm run dev

# Start server
php artisan serve
```

---

## 📊 Algoritma Naive Bayes

### Formula:

```
P(Y_k | X_1, X_2, ..., X_n) ∝ P(Y_k) × ∏ P(X_i | Y_k)
```

### Implementasi:
- **Prior Probability**: P(Y) - distribusi uniform atau manual
- **Likelihood**: P(X|Y) dengan Laplace Smoothing
  - Terkait (checkbox ✓) = 0.9
  - Tidak terkait = 0.1
- **Normalization**: Konversi score ke persentase

---

## 🎯 Cara Penggunaan

### 1. Dashboard
- Klik FAB button "+" untuk membuat project baru
- Isi form: Judul, Deskripsi, Label X/Y, Jumlah awal variabel
- Klik "Generate Workspace"

### 2. Workspace
- **Sidebar Kiri**: Edit nama variabel X/Y, tambah variabel baru
- **Matrix Table**: Klik checkbox untuk menandai relasi
  - Centang = Gejala X muncul pada Kerusakan Y
- **Kontrol**:
  - Ctrl + Scroll untuk zoom
  - Drag untuk pan
  - Klik untuk toggle checkbox

### 3. Analysis
- **Bottom Drawer**: Pilih gejala yang dialami
- Klik "Lakukan Inferensi Probabilistik"
- Lihat hasil diagnosa dengan persentase
- Toggle "Tampilkan Langkah Perhitungan" untuk detail matematis

---

## 📁 Struktur Project

```
probabilitas-pro/
├── app/
│   ├── Http/Controllers/
│   │   ├── ProjectController.php
│   │   ├── WorkspaceController.php
│   │   └── AnalysisController.php
│   └── Models/
│       ├── Project.php
│       ├── Attribute.php
│       ├── ClassModel.php
│       └── TrainingData.php
├── database/migrations/
├── resources/
│   ├── js/
│   │   ├── Components/
│   │   │   ├── MatrixTable.vue
│   │   │   ├── SidebarConfig.vue
│   │   │   └── AnalysisDrawer.vue
│   │   ├── Layouts/
│   │   │   └── AppLayout.vue
│   │   └── Pages/
│   │       ├── Dashboard.vue
│   │       └── Workspace.vue
│   └── css/
└── routes/
    └── web.php
```

---

## 🎨 Design Philosophy

### Color Palette:
- **Primary**: Sky-500 (#0ea5e9), Blue-600 (#2563eb)
- **Background**: Slate-50 (#f8fafc)
- **Text**: Slate-800 (#1e293b)
- **Success**: Green-600 (#16a34a)
- **Border**: Slate-200 (#e2e8f0)

### UI Principles:
- Clean and professional
- Interactive and responsive
- Mathematical precision
- Educational value (show calculation steps)

---

## 🔮 Future Enhancements

- [ ] Export hasil analisis ke PDF
- [ ] Import/Export dataset CSV
- [ ] Multi-user authentication
- [ ] Comparison between multiple algorithms
- [ ] Advanced visualization (charts, graphs)
- [ ] Mobile app version
- [ ] API untuk integrasi eksternal

---

## 📄 License & Copyright

**© 2025 Ahda Firly Barori. All Rights Reserved.**

This software is proprietary and confidential. Unauthorized copying, modification, distribution, or use of this software, via any medium, is strictly prohibited.

### Permissions:
- ✅ Personal use for educational purposes
- ❌ Commercial use without permission
- ❌ Redistribution or resale
- ❌ Modification and derivative works without permission

For licensing inquiries, please contact the author.

---

## 👨‍💻 Author

**Ahda Firly Barori**

Developed with ❤️ using Laravel 11, Vue 3, and Inertia.js

---

## 🙏 Acknowledgments

- Laravel Framework
- Vue.js Community
- Inertia.js Team
- TailwindCSS
- All open-source contributors

---

**Probabilitas Pro** - Transforming Expert Systems with Dynamic Naive Bayes

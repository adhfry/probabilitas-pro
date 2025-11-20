# 🚀 Quick Start Guide - Probabilitas Pro

**by Ahda Firly Barori**

---

## 📦 Langkah 1: Install Dependencies

### Opsi A: Gunakan Batch File (PALING MUDAH)
1. Double click: `install-dependencies.bat`
2. Tunggu sampai selesai
3. Check apakah SUCCESS

### Opsi B: Manual via Terminal
```bash
cd D:\Ahda\Web\probabilitas-pro

# Hapus instalasi lama
Remove-Item node_modules -Recurse -Force -ErrorAction SilentlyContinue
Remove-Item package-lock.json -Force -ErrorAction SilentlyContinue

# Clear cache
npm cache clean --force

# Install
npm install --legacy-peer-deps
```

### Opsi C: Gunakan pnpm (Jika npm gagal)
```bash
npm install -g pnpm
pnpm install
```

---

## 🎮 Langkah 2: Jalankan Aplikasi

### Terminal 1: Vite Dev Server
**Opsi A: Batch File**
- Double click: `start-dev.bat`

**Opsi B: Manual**
```bash
cd D:\Ahda\Web\probabilitas-pro
npm run dev
```

### Terminal 2: Laravel Server  
**Opsi A: Batch File**
- Double click: `start-laravel.bat`

**Opsi B: Manual**
```bash
cd D:\Ahda\Web\probabilitas-pro
php artisan serve
```

---

## 🌐 Langkah 3: Buka Browser

Buka: **http://localhost:8000**

---

## ✅ Testing Checklist

### 1. Dashboard
- [ ] Halaman dashboard muncul
- [ ] Klik tombol FAB (+) di pojok kanan bawah
- [ ] Modal form muncul
- [ ] Isi form:
  - Judul: "Test Diagnosa Komputer"
  - Deskripsi: "Testing sistem"
  - Label X: "Gejala"
  - Jumlah: 5
  - Label Y: "Kerusakan"  
  - Jumlah: 5
- [ ] Klik "Generate Workspace"
- [ ] Redirect ke halaman Workspace

### 2. Workspace
- [ ] Sidebar kiri terlihat dengan tab Prediktor dan Kelas
- [ ] Matrix table muncul di tengah
- [ ] Bottom drawer terlihat (bisa ditarik ke atas)
- [ ] Coba zoom: Ctrl + Scroll
- [ ] Coba pan: Drag mouse
- [ ] Klik beberapa checkbox di matrix
- [ ] Edit nama variabel di sidebar
- [ ] Tambah variabel baru

### 3. Analysis
- [ ] Tarik drawer ke atas
- [ ] Pilih beberapa gejala (checkbox)
- [ ] Klik "Lakukan Inferensi Probabilistik"
- [ ] Loading animation muncul
- [ ] Hasil analisis tampil
- [ ] Klik "Tampilkan Langkah Perhitungan"
- [ ] Formula dan calculation steps muncul
- [ ] Check ranking dan persentase

### 4. Dashboard Kembali
- [ ] Klik logo "Probabilitas Pro" di header
- [ ] Project tersimpan di dashboard
- [ ] Klik "Buka Workspace" - bisa buka lagi
- [ ] Klik "Hapus" - project terhapus

---

## 🐛 Troubleshooting

### Error: "Vite not found"
```bash
npm install vite --save-dev --force
```

### Error: "Cannot find module '@inertiajs/vue3'"
```bash
npm install @inertiajs/vue3 --save-dev --force
```

### Error: Database connection failed
1. Pastikan MySQL running di Laragon
2. Check .env:
   ```
   DB_DATABASE=probabilitas_pro
   DB_USERNAME=root
   DB_PASSWORD=
   ```
3. Run: `php artisan migrate:fresh`

### Error: Route not found
```bash
php artisan route:clear
php artisan route:cache
```

### Error: 500 Internal Server Error
```bash
composer dump-autoload
php artisan config:clear
php artisan cache:clear
```

---

## 📁 File Structure

```
D:\Ahda\Web\probabilitas-pro\
├── 📄 install-dependencies.bat  ← Run first
├── 📄 start-dev.bat            ← Terminal 1
├── 📄 start-laravel.bat        ← Terminal 2
├── 📄 QUICK_START.md           ← You are here
├── 📄 README.md                ← Full documentation
├── 📄 INSTRUCTIONS_FOR_AHDA.md ← Detailed status
│
├── app\                        ← Backend
│   ├── Http\Controllers\       ← 3 controllers
│   └── Models\                 ← 4 models
│
├── database\                   ← Database
│   └── migrations\             ← 4 custom migrations
│
├── resources\                  ← Frontend
│   ├── js\
│   │   ├── Components\         ← 3 custom Vue components
│   │   ├── Layouts\            ← AppLayout.vue
│   │   └── Pages\              ← Dashboard, Workspace
│   └── css\
│
├── routes\web.php              ← 8 routes defined
├── .env                        ← Configuration
└── package.json                ← Dependencies
```

---

## 🎨 Features Preview

### Dashboard
- 📊 Card grid untuk list projects
- ➕ FAB button untuk create new
- 🎨 Light blue professional theme
- 📱 Responsive design

### Workspace
- 🗂️ Sidebar: Manage variables
- 📊 Matrix: Interactive checkbox table
- 🔍 Zoom & Pan: Infinite canvas
- 💾 Auto-save: Real-time to database

### Analysis
- ✅ Select symptoms
- 🎭 Aurora loading animation
- 📈 Results with percentage
- 🧮 Calculation steps (like Photomath)

---

## 👨‍💻 Credits

**Developed by: Ahda Firly Barori**  
**Copyright © 2025**

Built with:
- ❤️ Love for coding
- ⚡ Laravel 11
- 🎨 Vue 3
- 🔗 Inertia.js
- 🎯 TailwindCSS

---

## 📞 Support

Jika ada masalah:
1. Check INSTRUCTIONS_FOR_AHDA.md
2. Check README.md
3. Check error logs di console browser (F12)
4. Check Laravel logs: `storage/logs/laravel.log`

---

**Happy Coding! 🎉**

Ahda Firly Barori

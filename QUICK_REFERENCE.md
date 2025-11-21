# 🚀 Quick Reference Guide - Probabilitas Pro

## 🎯 Perubahan Utama (TL;DR)

### 1️⃣ Sidebar: Fixed & Scrollable
```
Position: Fixed (tidak terhalang)
Location: Kiri layar
Scrollable: Ya ✓
Toggle: Tombol < di samping
```

### 2️⃣ Zoom Controls: Selalu Terlihat
```
Position: Fixed di tengah kanan
Buttons: + (zoom in), % (reset), - (zoom out)
Shortcut: Ctrl + Scroll
```

### 3️⃣ Langkah Perhitungan: Super Detail
```
Format:
├─ Gejala yang Dipilih
├─ Step 1: Prior Probability
├─ Step 2: Likelihood (per gejala)
├─ Step 3: Perhitungan (formula)
└─ ✓ Probabilitas Akhir
```

---

## 🎨 Color Guide

| Warna | Meaning |
|-------|---------|
| 🟢 Hijau | Kelas tertinggi / Gejala terkait |
| 🔵 Biru | Kelas normal / UI primary |
| 🟠 Orange | Gejala tidak terkait |
| ⚪ Abu-abu | Neutral / Disabled |
| 🟡 Kuning | Warning / Note |

---

## ⌨️ Keyboard Shortcuts

| Shortcut | Action |
|----------|--------|
| `Ctrl + Scroll` | Zoom matrix table |
| `Enter` | Save edit / Submit |
| `Esc` | Cancel edit |
| `Click + Drag` | Pan matrix table |

---

## 📐 Layout Structure

```
┌─────────────────────────────────────────┐
│  Navigation Bar (Fixed)                 │ ← Z-index: 50 (HIGHEST)
├─────────────────────────────────────────┤
│  Workspace Header                       │
├──────────┬──────────────┬───────────────┤
│ Sidebar  │              │ Zoom Controls │
│ (Fixed)  │ Matrix Table │ (Fixed)       │
│          │              │               │
│ Z-30     │ Scrollable   │ Z-20          │
└──────────┴──────────────┴───────────────┘
            Analysis Drawer (Fixed) Z-40
```

---

## 🎯 Files Modified

```bash
resources/js/Components/
├── SidebarConfig.vue      # Sidebar scrollable
├── MatrixTable.vue        # Zoom controls static
├── AnalysisDrawer.vue     # Enhanced steps
└── ...

resources/js/Pages/
└── Workspace.vue          # Layout adjustments
```

---

## 🔧 Build & Run

```bash
# Build assets
npm run build

# Development
npm run dev

# Laravel server
php artisan serve
```

---

## 📊 Performance

```
Build Time: 3.43s
Bundle Size: 250.77 kB (89.42 kB gzipped)
No Performance Degradation: ✓
```

---

## ✅ Testing Checklist

- [ ] Buka workspace
- [ ] Test sidebar scroll dengan banyak item
- [ ] Test zoom controls (klik + mouse wheel)
- [ ] Pilih gejala dan analisis
- [ ] Lihat langkah perhitungan detail
- [ ] Test responsive (resize window)
- [ ] Test collapse sidebar

---

## 🐛 Troubleshooting

### Sidebar tidak muncul?
- Check z-index conflicts
- Pastikan `top-[73px]` sesuai dengan header height

### Zoom tidak bekerja?
- Check fixed positioning
- Test dengan Ctrl + Scroll

### Langkah perhitungan tidak tampil?
- Klik "Tampilkan Langkah Perhitungan"
- Pastikan sudah pilih minimal 1 gejala

---

## 📚 Reference Links

- Naive Bayes: [ilmuskripsi.com](https://www.ilmuskripsi.com/2017/08/contoh-perhitungan-naive-bayes.html)
- Vue 3 Docs: [vuejs.org](https://vuejs.org)
- Tailwind CSS: [tailwindcss.com](https://tailwindcss.com)

---

## 💬 Support

Issues? Contact:
- Email: ahda.firly@example.com
- Docs: `/FITUR_BARU.md`
- Changelog: `/CHANGELOG_UI_IMPROVEMENTS.md`

---

**Happy Coding! 🎉**

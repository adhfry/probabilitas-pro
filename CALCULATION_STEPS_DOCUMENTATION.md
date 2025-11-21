# 📐 Dokumentasi Langkah Perhitungan Naive Bayes

## 🎯 Overview
Dokumentasi lengkap tentang langkah-langkah perhitungan Naive Bayes yang diimplementasikan dalam sistem Probabilitas Pro, dengan simbol matematika internasional dan penjelasan detail.

---

## 📊 Langkah-Langkah Perhitungan

### Langkah 0: Teorema Naive Bayes

#### Rumus Lengkap:
```
P(Cₖ|X) = [P(Cₖ) × P(X|Cₖ)] / P(X)
```

#### Bentuk Sederhana (Tanpa Normalisasi):
```
P(Cₖ|X) ∝ P(Cₖ) × ∏ᵢ₌₁ⁿ P(Xᵢ|Cₖ)
```

#### Komponen:
- **P(Cₖ|X)** = Posterior Probability (probabilitas kelas Cₖ given evidence X)
- **P(Cₖ)** = Prior Probability (probabilitas awal kelas Cₖ)
- **P(Xᵢ|Cₖ)** = Likelihood (probabilitas atribut Xᵢ pada kelas Cₖ)
- **∏** = Produk (perkalian semua likelihood)
- **P(X)** = Evidence (probabilitas total, sama untuk semua kelas)
- **∝** = Proporsional dengan (mengabaikan denominator)

---

### Tabel Frekuensi

Untuk setiap kelas, tampilkan tabel frekuensi:

| Gejala | Status | Frekuensi |
|--------|--------|-----------|
| X₁     | ✓ Terkait | 1 |
| X₂     | ✗ Tidak  | 0 |
| X₃     | ✓ Terkait | 1 |
| **Total** |  | **2** |

**Keterangan:**
- ✓ Terkait = Gejala muncul pada kelas ini
- ✗ Tidak = Gejala tidak muncul pada kelas ini
- Frekuensi = 1 (terkait) atau 0 (tidak)

---

### Langkah 1: Prior Probability

#### Formula:
```
P(Cₖ) = 1 / Total_Kelas
```

#### Contoh:
Jika ada 3 kelas (C₁, C₂, C₃):
```
P(C₁) = 1/3 = 0.3333 = 33.33%
P(C₂) = 1/3 = 0.3333 = 33.33%
P(C₃) = 1/3 = 0.3333 = 33.33%
```

#### Penjelasan:
- Menggunakan **Uniform Distribution** (probabilitas sama untuk semua kelas)
- Karena tidak ada informasi prior, setiap kelas memiliki peluang yang sama
- Ini adalah probabilitas awal sebelum melihat evidence

#### Simbol Matematika:
- **P(Cₖ)** = Prior probability dari kelas k
- **1/n** = Satu dibagi total jumlah kelas

---

### Langkah 2: Likelihood (Probabilitas Kondisional)

#### Formula:
```
P(Xᵢ|Cₖ) = {
    0.9  jika Xᵢ terkait dengan Cₖ
    0.1  jika Xᵢ tidak terkait dengan Cₖ
}
```

#### Metode: Bernoulli Naive Bayes
- Binary classification (terkait atau tidak)
- Asumsi independensi antara atribut
- Probabilitas tetap untuk setiap kondisi

#### Contoh:
Untuk kelas C₁ dengan gejala yang dipilih [X₁, X₂, X₃]:

| Gejala | Status | P(Xᵢ|C₁) |
|--------|--------|----------|
| X₁ | ✓ Terkait | 0.9 |
| X₂ | ✗ Tidak | 0.1 |
| X₃ | ✓ Terkait | 0.9 |

#### Penjelasan:
- **0.9 (90%)** = Probabilitas tinggi bahwa gejala muncul jika termasuk kelas ini
- **0.1 (10%)** = Probabilitas rendah (noise/error) jika gejala tidak terkait

#### Interpretasi:
- **Terkait**: Gejala ini memiliki hubungan kuat dengan kelas
- **Tidak Terkait**: Gejala ini jarang/tidak muncul pada kelas ini

---

### Langkah 3: Perhitungan Posterior (Unnormalized)

#### Formula Umum:
```
P(Cₖ|X) ∝ P(Cₖ) × ∏ᵢ₌₁ⁿ P(Xᵢ|Cₖ)
```

#### Formula Expanded:
```
P(Cₖ|X) ∝ P(Cₖ) × P(X₁|Cₖ) × P(X₂|Cₖ) × ... × P(Xₙ|Cₖ)
```

#### Contoh Substitusi:
Untuk C₁ dengan gejala [X₁, X₂, X₃]:
```
P(C₁|X) ∝ 0.3333 × 0.9 × 0.1 × 0.9
```

#### Perhitungan Step-by-Step:
```
Step 1: Prior Probability
P(C₁) = 0.3333

Step 2: Multiply dengan likelihood pertama
0.3333 × 0.9 = 0.2999

Step 3: Multiply dengan likelihood kedua  
0.2999 × 0.1 = 0.0300

Step 4: Multiply dengan likelihood ketiga
0.0300 × 0.9 = 0.0270
```

#### Hasil (Raw Score):
```
Score(C₁) = 0.0270 atau 2.70 × 10⁻²
```

#### Penjelasan:
- Skor ini masih dalam bentuk "raw" (belum dinormalisasi)
- Semakin besar skor, semakin besar probabilitasnya
- Perlu dinormalisasi agar total semua kelas = 100%

---

### Langkah 4: Normalisasi

#### Formula:
```
P(Cₖ|X) = Score(Cₖ) / Σⱼ Score(Cⱼ) × 100%
```

#### Contoh:
Misalkan kita punya 3 kelas dengan raw scores:
```
Score(C₁) = 0.0270
Score(C₂) = 0.0081
Score(C₃) = 0.0045

Total = 0.0270 + 0.0081 + 0.0045 = 0.0396
```

#### Perhitungan:
```
P(C₁|X) = 0.0270 / 0.0396 × 100% = 68.18%
P(C₂|X) = 0.0081 / 0.0396 × 100% = 20.45%
P(C₃|X) = 0.0045 / 0.0396 × 100% = 11.36%
```

#### Verifikasi:
```
68.18% + 20.45% + 11.36% = 99.99% ≈ 100% ✓
```

#### Penjelasan:
- Normalisasi mengubah raw score menjadi probabilitas yang dijumlahkan = 100%
- Ini memudahkan interpretasi: "berapa persen kemungkinan kelas ini?"
- Formula membagi setiap score dengan total semua score

---

### Langkah 5: Hasil Akhir

#### Output:
```
┌────────────────────────────┐
│ C₁: Kerusakan RAM          │
│ 68.18%                     │
│ █████████████░░░░░░░       │
└────────────────────────────┘

┌────────────────────────────┐
│ C₂: Kerusakan VGA          │
│ 20.45%                     │
│ ████░░░░░░░░░░░░░░░░       │
└────────────────────────────┘

┌────────────────────────────┐
│ C₃: Kerusakan Motherboard  │
│ 11.36%                     │
│ ██░░░░░░░░░░░░░░░░░░       │
└────────────────────────────┘
```

#### Interpretasi:
Berdasarkan gejala yang dipilih:
- **68.18%** kemungkinan adalah Kerusakan RAM
- **20.45%** kemungkinan adalah Kerusakan VGA
- **11.36%** kemungkinan adalah Kerusakan Motherboard

**Kesimpulan:** Diagnosis paling mungkin adalah **Kerusakan RAM**

---

## 🔣 Simbol Matematika Internasional

### Greek Letters:
- **α** (alpha)
- **β** (beta)
- **π** (pi)
- **Σ** (sigma) = Summation (penjumlahan)
- **∏** (pi besar) = Product (perkalian)

### Mathematical Operators:
- **×** = Multiplication (kali)
- **÷** = Division (bagi)
- **∝** = Proportional to (proporsional dengan)
- **≈** = Approximately equal (kurang lebih sama dengan)
- **≠** = Not equal (tidak sama dengan)
- **≤** = Less than or equal (kurang dari atau sama dengan)
- **≥** = Greater than or equal (lebih dari atau sama dengan)

### Set Theory:
- **∈** = Element of (elemen dari)
- **⊂** = Subset of (subset dari)
- **∪** = Union (gabungan)
- **∩** = Intersection (irisan)

### Calculus:
- **∫** = Integral
- **∂** = Partial derivative
- **∞** = Infinity (tak hingga)

### Subscripts & Superscripts:
- **Xᵢ** = X subscript i
- **X²** = X squared
- **Cₖ** = C subscript k
- **X₁, X₂, ..., Xₙ** = Sequence

---

## 📐 Notasi yang Digunakan

### Variables:
- **C** atau **Y** = Class (Kelas)
- **X** = Feature/Attribute (Atribut/Gejala)
- **n** = Jumlah atribut
- **k** = Index kelas
- **i** = Index atribut

### Probability Notation:
- **P(A)** = Probability of A
- **P(A|B)** = Conditional probability of A given B
- **P(A,B)** = Joint probability of A and B
- **P(A∩B)** = Probability of A and B
- **P(A∪B)** = Probability of A or B

---

## 🎓 Contoh Lengkap End-to-End

### Data:
**Project:** Diagnosa Laptop  
**Gejala yang Dipilih:** X₁, X₃, X₅  
**Kelas:** C₁ (RAM), C₂ (VGA), C₃ (Motherboard)

### Tabel Training:
```
     C₁   C₂   C₃
X₁   ✓    ✓    ✗
X₂   ✓    ✗    ✗
X₃   ✓    ✓    ✓
X₄   ✗    ✓    ✓
X₅   ✓    ✗    ✗
```

### Perhitungan untuk C₁:

**1. Prior:**
```
P(C₁) = 1/3 = 0.3333
```

**2. Likelihood:**
```
P(X₁|C₁) = 0.9  (terkait ✓)
P(X₃|C₁) = 0.9  (terkait ✓)
P(X₅|C₁) = 0.9  (terkait ✓)
```

**3. Posterior (Unnormalized):**
```
Score(C₁) = 0.3333 × 0.9 × 0.9 × 0.9
         = 0.3333 × 0.729
         = 0.2430
```

**4. Normalisasi:**
```
Misalkan:
Score(C₁) = 0.2430
Score(C₂) = 0.0729
Score(C₃) = 0.0270
Total     = 0.3429

P(C₁|X) = 0.2430 / 0.3429 × 100% = 70.87%
P(C₂|X) = 0.0729 / 0.3429 × 100% = 21.26%
P(C₃|X) = 0.0270 / 0.3429 × 100% = 7.87%
```

**5. Hasil:**
**Diagnosis: Kerusakan RAM (70.87%)**

---

## 🎯 Key Principles

### Naive Bayes Assumptions:
1. **Independence**: Setiap atribut independen satu sama lain
2. **Equal Importance**: Semua atribut memiliki bobot yang sama
3. **Binary**: Status terkait/tidak terkait

### When to Use:
✅ Text classification  
✅ Spam filtering  
✅ Medical diagnosis  
✅ Fault diagnosis  
✅ Sentiment analysis  

### Advantages:
✅ Simple dan cepat  
✅ Bekerja baik dengan data kecil  
✅ Tidak butuh training data banyak  
✅ Interpretable (mudah dijelaskan)  

### Limitations:
❌ Asumsi independence tidak selalu benar  
❌ Zero probability problem (butuh smoothing)  
❌ Tidak bisa handle missing values langsung  

---

## 📚 References

### Mathematical Foundation:
- Bayes' Theorem (Thomas Bayes, 1763)
- Probability Theory
- Conditional Probability
- Bernoulli Distribution

### Implementation:
- Bernoulli Naive Bayes
- Laplace Smoothing (untuk handle zero probability)
- Maximum A Posteriori (MAP) estimation

### Resources:
- ilmuskripsi.com/naive-bayes
- Bishop - Pattern Recognition and Machine Learning
- Murphy - Machine Learning: A Probabilistic Perspective
- Scikit-learn Documentation

---

## ✅ Quality Assurance

### Validation:
- [x] Percentages sum to 100%
- [x] Prior probabilities valid (0 ≤ P ≤ 1)
- [x] Likelihood probabilities valid (0 ≤ P ≤ 1)
- [x] No division by zero
- [x] No negative probabilities
- [x] Formulas mathematically correct
- [x] Symbols rendered properly
- [x] Step-by-step clear and logical

---

## 🎨 Visual Hierarchy

### Color Coding:
- 🟢 **Green** - Highest probability
- 🔵 **Blue** - Medium probability  
- 🟡 **Yellow** - Low probability
- 🟣 **Purple** - Formulas and theory
- ⚪ **White** - Background and cards

### Typography:
- **Bold** - Important values and headers
- *Italic* - Explanations and notes
- `Monospace` - Codes and mathematical values
- Serif - Mathematical formulas

---

## 💡 Tips untuk Pengguna

### Interpretasi Hasil:
1. Persentase > 50% → Diagnosis cukup yakin
2. Persentase > 70% → Diagnosis sangat yakin
3. Persentase < 30% → Kurang yakin, butuh gejala tambahan
4. Persentase hampir sama → Ambiguous, perlu data lebih banyak

### Best Practices:
1. Pilih minimal 3-5 gejala untuk hasil akurat
2. Pastikan training data akurat dan lengkap
3. Update training data secara berkala
4. Validasi hasil dengan expert knowledge

---

**Status:** ✅ **COMPLETE & VERIFIED**  
**Version:** 2.0  
**Date:** 2025-11-21  
**Author:** Ahda Firly Barori  

---

*© 2025 Probabilitas Pro - Making Machine Learning Understandable*

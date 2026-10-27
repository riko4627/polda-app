# Product Requirement Document (PRD)
## Sistem Pendukung Keputusan Penempatan Personel Kepolisian (SPK-TOPSIS)

**Versi:** 1.0  
**Tanggal:** 27 Oktober 2026  
**Status:** Approved / Ready for Implementation  
**Teknologi:** Laravel 11 / PHP 8.2+, MySQL / SQLite, Tailwind CSS / Blade  

---

## 1. Ringkasan Eksekutif & Tujuan

Sistem Pendukung Keputusan (SPK) Penempatan Personel ini dirancang untuk membantu Bagian Pembinaan Karir (Bagbinkar) / Biro SDM dan Dewan Pertimbangan Karir (Wanjak) dalam menentukan penempatan personel kepolisian pada jabatan yang tepat (*the right man on the right place*).

Sistem menggunakan pendekatan **Hybrid Architecture**:
1. **Tahap 1 - Rule-Based Screening (Hard Filter):** Penyaringan syarat mutlak administrasi (Pangkat, Dikbang, Sertifikasi, Riwayat Jabatan). Personel yang tidak memenuhi syarat langsung dinyatakan Tidak Memenuhi Syarat (TMS).
2. **Tahap 2 - TOPSIS Multi-Criteria Decision Engine (Soft Scoring):** Perangkingan objektif bagi kandidat yang Memenuhi Syarat (MS) menggunakan metode *Technique for Order of Preference by Similarity to Ideal Solution* (TOPSIS), dengan parameter kriteria dan bobot yang **dapat diatur secara dinamis per jabatan**.

---

## 2. Peran Pengguna (User Roles & Permissions)

| Role | Deskripsi | Hak Akses |
|:---|:---|:---|
| **Super Admin** | Administrator Sistem SDM | Akses penuh: manajemen user, audit log, konfigurasi sistem master. |
| **Admin SDM / Operator** | Staf Bagbinkar / Bagdalpers | Mengelola data personel, riwayat jabatan, nilai asesmen, konfigurasi kriteria & bobot jabatan, menjalankan kalkulasi TOPSIS. |
| **Pimpinan / Tim Wanjak** | Kapolda, Karo SDM, Kapolres | Memantau dashboard, melihat matriks analisis TOPSIS, menyetujui rekomendasi penempatan, mengunduh laporan rekomendasi / draf SK. |

---

## 3. Aturan Syarat Mutlak (Rule-Based Screening)

Terdapat 7 profil jabatan spesifik dengan aturan dasar kelayakan (*eligibility rule*):

| No | Jabatan Target | Pangkat Minimal / Wajib | Pendidikan Pengembangan (Dikbang) | Sertifikasi / Dikjur Khusus | Riwayat Jabatan Relevan |
|:---|:---|:---|:---|:---|:---|
| 1 | **Kapolsek** | Min. **Iptu** (Iptu, AKP, Kompol) | - | - | - |
| 2 | **Kabagdalpers** | Wajib **AKBP** | Wajib **Sespimmen** | - | Wajib pernah menjabat **Kapolres** |
| 3 | **Kapolres** | Wajib **AKBP** | Wajib **Sespimmen** | - | - |
| 4 | **Wadir** (Fungsi Opsnal) | Wajib **AKBP** | Wajib **Sespimmen** | - | Wajib memiliki rekam jejak tugas pada fungsi terkait (misal Reskrim) |
| 5 | **Bintara Penyidik** | Golongan **Bintara** (Bripda s.d. Aiptu) | - | Wajib memiliki sertifikasi/ijazah **Dikjur Penyidik** | - |
| 6 | **Kanit Reskrim** | Min. **Iptu** | Min. **S1** (Umum/Hukum) | Wajib sertifikasi/sertifikat **Penyidik** | - |
| 7 | **Bintara Bhabinkamtibmas** | Golongan **Bintara** | - | Wajib sertifikasi **Bhabinkamtibmas** & Dikjur terkait | - |

> *Catatan:* Sistem menyediakan antarmuka bagi Admin untuk menambah jabatan baru atau memperbarui aturan syarat mutlak tanpa mengubah kode program.

---

## 4. Parameter Kriteria Dinamis & Fleksibilitas Bobot

Sistem mengizinkan pengaturan parameter kriteria secara dinamis:
1. **Daftar Kriteria Global (CRUD Kriteria):**
   - Kode Kriteria (misal: $C_1, C_2, \dots$)
   - Nama Kriteria
   - Tipe Kriteria: **Benefit** (semakin tinggi semakin baik) atau **Cost** (semakin rendah semakin baik)
   - Skala Nilai: misal 0 - 100
2. **Konfigurasi Bobot per Jabatan (*Position Criteria Pivot*):**
   - Setiap jabatan dapat mengaktifkan himpunan kriteria tertentu.
   - Bobot $w_j$ ditentukan spesifik untuk jabatan tersebut.
   - Validasi ketat: Total bobot untuk satu jabatan wajib berjumlah **100% (atau 1.0)**.

### Contoh Template Kriteria Bawaan:
- $C_1$: **Nilai SMK (Sistem Manajemen Kinerja)** (Benefit)
- $C_2$: **Nilai Psikologi / Assessment Center** (Benefit)
- $C_3$: **Nilai Kesamaptaan Jasmani (TKJ)** (Benefit)
- $C_4$: **Masa Dinas dalam Pangkat (MDDP) / Masa Kerja** (Benefit)
- $C_5$: **Penghargaan / Tanda Kehormatan** (Benefit)
- $C_6$: **Catatan Pelanggaran Disiplin/Kode Etik** (Cost)

---

## 5. Algoritma Matematis TOPSIS

Jika $m$ kandidat lolos syarat mutlak ($A_1, A_2, \dots, A_m$) dan terdapat $n$ kriteria yang aktif ($C_1, C_2, \dots, C_n$):

1. **Matriks Keputusan ($X_{m \times n}$)**:
   $$X = \begin{bmatrix} x_{11} & x_{12} & \dots & x_{1n} \\ x_{21} & x_{22} & \dots & x_{2n} \\ \vdots & \vdots & \ddots & \vdots \\ x_{m1} & x_{m2} & \dots & x_{mn} \end{bmatrix}$$

2. **Normalisasi Matriks ($R$)**:
   $$r_{ij} = \frac{x_{ij}}{\sqrt{\sum_{k=1}^m x_{kj}^2}}$$

3. **Matriks Ternormalisasi Terbobot ($Y$)**:
   $$y_{ij} = w_j \cdot r_{ij}$$

4. **Solusi Ideal Positif ($A^+$) dan Solusi Ideal Negatif ($A^-$)**:
   - Solusi Ideal Positif:
     $$A^+ = (y_1^+, y_2^+, \dots, y_n^+)$$
     $$y_j^+ = \max_i(y_{ij}) \text{ jika } j \in \text{Benefit}, \quad \min_i(y_{ij}) \text{ jika } j \in \text{Cost}$$
   - Solusi Ideal Negatif:
     $$A^- = (y_1^-, y_2^-, \dots, y_n^-)$$
     $$y_j^- = \min_i(y_{ij}) \text{ jika } j \in \text{Benefit}, \quad \max_i(y_{ij}) \text{ jika } j \in \text{Cost}$$

5. **Jarak Solusi ($D_i^+$ dan $D_i^-$)**:
   $$D_i^+ = \sqrt{\sum_{j=1}^n (y_{ij} - y_j^+)^2}, \quad D_i^- = \sqrt{\sum_{j=1}^n (y_{ij} - y_j^-)^2}$$

6. **Nilai Preferensi Akhir ($V_i$)**:
   $$V_i = \frac{D_i^-}{D_i^+ + D_i^-}$$
   *Rentang nilai $0 \le V_i \le 1$. Nilai mendekati 1 merupakan kandidat prioritas tertinggi.*

---

## 6. Desain Arsitektur Data (Entity Relationship)

```text
[ranks] ───────────────┐
                       ▼
[users] ─────────> [personnel] ──┬──> [career_histories]
                       ▲         ├──> [personnel_certifications]
                       │         └──> [assessment_scores]
                       │                     │
                       │                     ▼
[positions] ────> [position_criteria] <── [criteria]
    │                  │
    ▼                  ▼
[position_rules]  [topsis_runs] ──> [topsis_results]
```

### Rincian Tabel Utama:

1. **`ranks` (Data Pangkat)**
   - `id`, `name` (misal: Bripda, Iptu, AKBP), `level_order` (integer urutan kepangkatan untuk komparasi `>=`), `category` (Bintara, Pama, Pamen, Pati).
2. **`positions` (Master Jabatan)**
   - `id`, `code`, `title`, `unit_name`, `quota`, `description`, `is_active`.
3. **`position_rules` (Aturan Syarat Mutlak Jabatan)**
   - `id`, `position_id`
   - `min_rank_id` (nullable - relasi ke ranks)
   - `required_rank_id` (nullable - pangkat mutlak harus sama)
   - `min_education_level` (nullable, misal: S1)
   - `requires_sespimmen` (boolean)
   - `required_certification_keyword` (string/json, misal: 'Penyidik', 'Bhabinkamtibmas')
   - `required_past_position_title` (nullable, misal: 'Kapolres' untuk Kabagdalpers)
   - `required_function_track` (nullable, misal: 'Reskrim' untuk Wadir Reskrim)
4. **`criteria` (Master Kriteria)**
   - `id`, `code` (C1, C2..), `name`, `type` (enum: 'benefit', 'cost'), `default_weight`, `is_active`.
5. **`position_criteria` (Pivot Bobot Kriteria Jabatan)**
   - `id`, `position_id`, `criterion_id`, `weight` (decimal/percentage).
6. **`personnel` (Data Personel Polri)**
   - `id`, `nrp`, `name`, `rank_id`, `education_level`, `has_sespimmen` (boolean), `current_position`, `function_track` (Reskrim, Lantas, Intelkam, Binmas, Samapta, SDM), `phone`, `email`.
7. **`personnel_certifications` & `career_histories`**
   - Sertifikasi/Dikjur yang dimiliki, tanggal perolehan, nomor ijazah/sertifikat.
   - Riwayat jabatan terdahulu (posisi, tmt_mulai, tmt_selesai, unit).
8. **`assessment_scores` (Nilai Penilaian Kinerja Personel)**
   - `id`, `personnel_id`, `criterion_id`, `score` (numeric, e.g., 85.50), `period_year`.
9. **`topsis_runs` & `topsis_results` (Riwayat Perhitungan & Audit)**
   - `topsis_runs`: `id`, `position_id`, `run_date`, `executed_by`, `status`.
   - `topsis_results`: `id`, `run_id`, `personnel_id`, `is_eligible` (boolean), `ineligibility_reason`, `d_plus`, `d_minus`, `preference_score` ($V_i$), `rank_position`.

---

## 7. Fitur & Kebutuhan Fungsional

### Modul 1: Manajemen Master Data & Personel
- CRUD Personel lengkap dengan riwayat pangkat, riwayat jabatan, dan portofolio sertifikasi/dikjur.
- Import data massal (Excel/CSV) untuk nilai kinerja (SMK, Jasmani, Psikologi).

### Modul 2: Konfigurasi Jabatan & Kriteria Dinamis
- Manajemen daftar jabatan dan pengaturan batas kuota lowongan.
- Pengaturan Syarat Mutlak (Rule-Based Eligibility Builder).
- Pengaturan Kriteria & Bobot per Jabatan dengan validator otomatis (peringatan jika bobot $\ne 100\%$).

### Modul 3: Engine Seleksi & Kalkulasi TOPSIS
- **Tombol "Jalankan Rekomendasi" per Jabatan**:
  - Langkah 1: Sistem menyaring seluruh personel yang memenuhi syarat mutlak jabatan.
  - Langkah 2: Sistem menarik matriks nilai personel yang lolos.
  - Langkah 3: Menjalankan normalisasi, pembobotan, penentuan $A^+ / A^-$, jarak Euclidean, dan preferensi $V_i$.
- Menampilkan tab visual:
  - Tab 1: **Hasil Akhir (Peringkat & Rekomendasi)**
  - Tab 2: **Daftar Personel TMS (disertai alasan tidak memenuhi syarat)**
  - Tab 3: **Detail Matriks Matematis (Transparansi perhitungan: $X, R, Y, D^+, D^-$)**

### Modul 4: Pelaporan & Keputusan
- Ekspor Hasil Rekomendasi ke format PDF (Format Draf Rekomendasi Wanjak) dan Excel.
- Audit Trail: Riwayat log siapa yang mengubah bobot dan kapan kalkulasi dijalankan.

---

## 8. Kebutuhan Non-Fungsional (NFR)

1. **Akurasi & Integritas Perhitungan:** Algoritma TOPSIS harus menggunakan presisi floating-point yang konsisten (minimal 4 digit desimal di belakang koma).
2. **Kinerja:** Proses eliminasi rule-based dan perhitungan TOPSIS untuk 1.000 personel harus selesai dalam waktu $< 3$ detik.
3. **Keamanan Data:** Enkripsi password pengguna, otorisasi berbasis peran (Role-Based Access Control), perlindungan CSRF, dan validasi input ketat.
4. **Responsif & Intuitif:** Desain UI modern, profesional, bernuansa kepolisian (Presisi / Navy Blue & Gold), mudah dipahami oleh pengambil keputusan.

---

## 9. Rencana Tahapan Pengembangan (Roadmap)

| Tahap | Aktivitas | Estimasi Durasi |
|:---|:---|:---:|
| **Fase 1** | Migrasi Database, Model Eloquent, dan Seeder (7 Jabatan, Pangkat, Kriteria) | Sprint 1 |
| **Fase 2** | Implementasi Service Engine: `EligibilityFilterService` & `TopsisEngineService` | Sprint 1 |
| **Fase 3** | CRUD Master Personel, Kriteria Dinamis, dan Pengaturan Bobot Jabatan | Sprint 2 |
| **Fase 4** | Antarmuka Pengujian Rekomendasi, Detail Transparansi Perhitungan Matriks | Sprint 2 |
| **Fase 5** | Ekspor Laporan (PDF/Excel), Verifikasi Unit Testing, dan Polishing UI | Sprint 3 |

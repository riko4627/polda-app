# BUKU PANDUAN PENGGUNA & STANDAR OPERASIONAL PROSEDUR (SOP) ADMIN
## SISTEM PENDUKUNG KEPUTUSAN PENEMPATAN PERSONEL POLRI (SPK-TOPSIS HYBRID)
### BIRO SDM POLDA &bull; BAGBINKAR &bull; DEWAN PERTIMBANGAN KARIR (WANJAK)

---

> **PENGANTAR UNTUK OPERATOR / ADMIN BARU:**  
> Selamat datang di Aplikasi **SPK Penempatan Personel Polri Presisi**. Dokumen ini disusun secara lengkap dan sistematis agar Anda dapat langsung mengoperasikan sistem dari awal hingga akhir **secara mandiri tanpa perlu bimbingan teknis tatap muka**. Cukup ikuti panduan ini langkah demi langkah.

---

## DAFTAR ISI
1. [Akses Sistem & Akun Login](#1-akses-sistem--akun-login)
2. [Prinsip Kerja Sistem: Metode Hybrid 2 Tahap](#2-prinsip-kerja-sistem-metode-hybrid-2-tahap)
3. [Panduan Antarmuka & Peta Menu](#3-panduan-antarmuka--peta-menu)
4. [Lembar Cepat Operator (Selesai dalam 5 Menit)](#4-lembar-cepat-operator-selesai-dalam-5-menit)
5. [Panduan Langkah demi Langkah (Step-by-Step)](#5-panduan-langkah-demi-langkah-step-by-step)
   - [Langkah 1: Menyiapkan Master Data Jabatan & Syarat Mutlak](#langkah-1-menyiapkan-master-data-jabatan--syarat-mutlak)
   - [Langkah 2: Mengatur Bobot Kriteria Penilaian (Wajib 100%)](#langkah-2-mengatur-bobot-kriteria-penilaian-wajib-100)
   - [Langkah 3: Mendaftarkan & Mengelola Data Personel](#langkah-3-mendaftarkan--mengelola-data-personel)
   - [Langkah 4: Menginput Nilai Kinerja Personel (SMK, Jasmani, Psikologi)](#langkah-4-menginput-nilai-kinerja-personel-smk-jasmani-psikologi)
   - [Langkah 5: Menjalankan Simulasi Sidang Rekomendasi Wanjak](#langkah-5-menjalankan-simulasi-sidang-rekomendasi-wanjak)
   - [Langkah 6: Membaca, Mempresentasikan, & Mencetak Hasil Sidang (3 Tab)](#langkah-6-membaca-mempresentasikan--mencetak-hasil-sidang-3-tab)
   - [Langkah 7: Mengakses Riwayat Keputusan Sidang](#langkah-7-mengakses-riwayat-keputusan-sidang)
6. [Studi Kasus Nyata di Lapangan](#6-studi-kasus-nyata-di-lapangan)
7. [Panduan Mengatasi Kendala (Troubleshooting & Solusi)](#7-panduan-mengatasi-kendala-troubleshooting--solusi)
8. [Lembar Kendali / Checklist Sidang Wanjak](#8-lembar-kendali--checklist-sidang-wanjak)

---

## 1. Akses Sistem & Akun Login

### A. Alamat URL Aplikasi
Buka peramban web (*Google Chrome* atau *Microsoft Edge*) pada komputer Anda, kemudian masukkan alamat:
- **Komputer Lokal / Laptop:** `http://127.0.0.1:8000` atau `http://localhost:8000`
- **Server Jaringan Polda (LAN/Intranet):** Sesuaikan dengan IP Server yang diberikan Divisi TIK/Admin Server.

> 💡 **Jika Menjalankan di Komputer Lokal (Bagi Operator Teknis):**  
> Pastikan layanan Laravel dan Vite berjalan di terminal PowerShell:
> ```powershell
> # Terminal 1 (Menjalankan Web Server):
> php artisan serve
> 
> # Terminal 2 (Menjalankan Aset Visual):
> npm run dev
> ```

### B. Akun Login Super Admin
Sesuai konfigurasi keamanan saat ini, **akses login diproteksi khusus untuk akun Super Administrator SDM**:

| Peran (Role) | Nama Pengguna | Alamat Email | Kata Sandi Bawaan | Status Akses Saat Ini |
|---|---|---|:---:|:---:|
| **Super Admin** | Super Administrator SDM | `superadmin@polda.polri.go.id` | `password` | <span style="color:#10b981;font-weight:bold;">AKTIF (Akses Penuh)</span> |
| **Admin / Operator** | Operator Bagbinkar SDM | `adminsdm@polda.polri.go.id` | `password` | *Dibatasi (Tahap Integrasi)* |
| **Pimpinan** | Kapolda / Tim Wanjak | `kapolda@polda.polri.go.id` | `password` | *Dibatasi (Tahap Integrasi)* |

> 🔑 **Cara Masuk ke Sistem:**
> 1. Buka alamat `http://127.0.0.1:8000/login`.
> 2. Masukkan email: `superadmin@polda.polri.go.id` dan kata sandi: `password`.
>    *(Anda juga dapat langsung mengklik tombol **"Klik untuk Isi Otomatis"** pada kartu bantuan di halaman login).*
> 3. Klik tombol **"Masuk ke Dashboard Presisi"**.
> 4. Untuk keluar dari sesi, klik tombol icon pintu/logout di pojok kanan atas navbar atau di bagian bawah sidebar.

---

## 2. Prinsip Kerja Sistem: Metode Hybrid 2 Tahap

Aplikasi ini menggunakan pendekatan **Hybrid Decision Support System (Rule-Based + TOPSIS)** untuk memastikan penempatan jabatan berjalan transparan, objektif, dan bebas titipan (*The Right Man on The Right Place*).

```text
┌───────────────────────────────────────────────────────────────────────────┐
│                        SEMUA PERSONEL POLRI AKTIF                         │
│                    (Data Master Anggota dalam Database)                   │
└─────────────────────────────────────┬─────────────────────────────────────┘
                                      │
                                      ▼
┌───────────────────────────────────────────────────────────────────────────┐
│ TAHAP 1: HARD FILTER (Penyaringan Syarat Mutlak Administrasi)            │
│ Sistem memeriksa 8 aturan wajib: Pangkat, Golongan, Pendidikan Minimal,    │
│ Kelulusan Sespimmen, Riwayat Jabatan Lampau, Sertifikasi Kejuruan, dll.   │
└──────────────────────┬──────────────────────────────────────────┬─────────┘
                       │                                          │
             (MEMENUHI SYARAT / MS)                     (TIDAK MEMENUHI / TMS)
                       │                                          │
                       ▼                                          ▼
┌──────────────────────────────────────────────┐       ┌────────────────────┐
│ TAHAP 2: TOPSIS SCORING (Perangkingan Nilai) │       │ TAB PERSONEL TMS   │
│ Nilai SMK, Psikologi, Jasmani, MDDP, Piagam  │       │ Dicatat lengkap    │
│ dihitung dengan rumus kedekatan ideal TOPSIS.│       │ beserta ALASAN     │
│ Menghasilkan Nilai Preferensi (Vi: 0 - 1).   │       │ diskualifikasinya. │
└──────────────────────┬───────────────────────┘       │ (Transparan 100%)  │
                       │                               └────────────────────┘
                       ▼
┌───────────────────────────────────────────────────────────────────────────┐
│ HASIL AKHIR SIDANG WANJAK:                                                │
│ • Rekomendasi Utama (Peringkat 1 s.d. batas Kuota Kursi - Lencana Emas)   │
│ • Cadangan Wanjak (Peringkat berikutnya jika kandidat utama berhalangan) │
│ • Bukti Ilmiah Matriks TOPSIS (Untuk pertanggungjawaban sidang)          │
└───────────────────────────────────────────────────────────────────────────┘
```

---

## 3. Panduan Antarmuka & Peta Menu

### A. Struktur Navigasi Menu (Sidebar Kiri)
Pada sisi kiri layar terdapat bilah navigasi dengan kelompok menu:

1. **Menu Utama:**
   - **Dashboard:** Ringkasan statistik cepat (Total Anggota Aktif, Jumlah Jabatan Formasi, Parameter Kriteria, dan Total Sidang yang pernah dieksekusi).
   - **Seleksi SPK-TOPSIS:** Halaman peluncuran simulasi sidang rekomendasi penempatan.
2. **Master Data SDM:**
   - **Data Personel (`/personnel`):** Daftar seluruh anggota, pencarian NRP/Nama, filter pangkat/fungsi, tambah anggota baru, input riwayat penugasan, sertifikasi, serta input nilai kinerja (SMK, Jasmani, Psikologi).
   - **Jabatan & Syarat (`/positions`):** Kartu profil formasi jabatan, pengaturan kuota kursi, aturan syarat mutlak (*Rule Builder*), dan pembobotan kriteria dinamis per jabatan.
   - **Kriteria & Bobot (`/criteria`):** Daftar master 6 kriteria TOPSIS (C1 - C6), tipe kriteria (*Benefit* / *Cost*), skala nilai, dan bobot bawaan.
3. **Sidang Wanjak:**
   - **Riwayat Keputusan (`/recommendations/history`):** Arsip riwayat seluruh sidang yang pernah dijalankan beserta tanggal, kuota, jumlah kandidat lolos/TMS, dan tombol buka hasil.

### B. Arti Kode Warna & Tombol di Aplikasi
- **Kuning Emas (`bg-amber-500`):** Tombol aksi utama (Simpan, Tambah Baru, Jalankan Analisis) & Lencana Kuota Terpilih.
- **Hijau (`bg-emerald-500`):** Status Lolos Syarat Mutlak (MS), Status Sukses, atau Indikator Bobot Valid (Pas 100%).
- **Merah / Mawar (`bg-rose-500`):** Status Tidak Memenuhi Syarat (TMS), Indikator Bobot Tidak Valid (<100% atau >100%), atau Catatan Pelanggaran Disiplin.
- **Hitam Gelap (`bg-slate-900`):** Tombol aksi sekunder, tombol edit syarat mutlak, dan panel ringkasan informasi.

---

## 4. Lembar Cepat Operator (Selesai dalam 5 Menit)

Jika Anda harus segera menyajikan data rekomendasi untuk pimpinan dalam waktu singkat:

```text
[1. CEK SYARAT JABATAN] ➔ Buka "Jabatan & Syarat", klik "Edit Syarat", pastikan kuota kursi & aturan sudah pas.
           ▼
[2. CEK BOBOT 100%]     ➔ Klik tombol "Bobot (100%)", pastikan bar berwarna HIJAU (Pas 100%).
           ▼
[3. PASTIKAN DATA SIAP] ➔ Di "Data Personel", pastikan nilai SMK & Asesmen periode berjalan sudah terisi.
           ▼
[4. JALANKAN SELEKSI]   ➔ Masuk "Seleksi SPK-TOPSIS", pilih Jabatan & Tahun, klik "JALANKAN ANALISIS".
           ▼
[5. SAJIKAN 3 TAB]      ➔ Tunjukkan Tab 1 (Kandidat Terpilih), Tab 2 (TMS & Alasan), Tab 3 (Matriks TOPSIS).
```

---

## 5. Panduan Langkah demi Langkah (Step-by-Step)

### Langkah 1: Menyiapkan Master Data Jabatan & Syarat Mutlak

Sebelum sidang dimulai, pastikan formasi jabatan yang akan diisi sudah memiliki aturan mutlak yang tepat.

#### A. Menambah Formasi Jabatan Baru:
1. Buka menu **Master Data SDM** &rarr; **Jabatan & Syarat** (`/positions`).
2. Klik tombol kuning **"+ Tambah Jabatan Baru"** di pojok kanan atas.
3. Masukkan data identitas:
   - **Kode Unik / Akronim:** Ketik huruf kapital tanpa spasi (misal: `KASAT_LANTAS` atau `KANIT_INTEL`).
   - **Nama Lengkap Jabatan:** Contoh: *Kepala Satuan Lalu Lintas (Kasat Lantas)*.
   - **Satuan Kerja / Kesatuan:** Contoh: *Polres Jajaran*.
   - **Kuota Formasi:** Jumlah kursi yang dibuka (misal: `1`).
4. Atur aturan syarat mutlak (*Rule Builder*) sesuai kualifikasi dinas (pangkat, pendidikan, sespimmen, sertifikasi, dll).
5. Klik **"Simpan Formasi & Aturan Mutlak"**. Jabatan baru akan langsung aktif dan otomatis dibekali bobot kriteria awal 100%.

#### B. Mengubah Aturan Syarat Mutlak Jabatan yang Ada:
1. Buka menu **Jabatan & Syarat** (`/positions`).
2. Pilih kartu jabatan yang akan disidangkan (contoh: *Kepala Kepolisian Sektor (Kapolsek)* atau *Kabagdalpers*).
3. Klik tombol hitam **"Edit Syarat"**.
4. Di formulir pengaturan aturan (*Rule Builder*), atur parameter berikut:
   - **Nama Jabatan & Satker:** Nama resmi posisi dan kesatuan.
   - **Kuota Formasi (Kursi):** Jumlah personel yang dibutuhkan (misal: jika ada 3 Polsek kosong, isi `3`).
   - **Pangkat Wajib Mutlak (Exact Match):** Gunakan ini HANYA jika pangkat personel harus persis sama. *(Contoh: Kabagdalpers wajib AKBP. Jika memilih AKBP, maka Kompol atau Kombes akan otomatis gugur)*.
   - **Pangkat Minimal (Min Rank):** Gunakan ini jika pangkat di atasnya juga diperkenankan ikut. *(Contoh: Kapolsek minimal IPTU. Maka IPTU, AKP, dan Kompol otomatis boleh ikut)*.
   - **Golongan Kepangkatan:** Pilih kategori yang berhak: `Bintara`, `Pama` (Perwira Pertama), atau `Pamen` (Perwira Menengah).
   - **Minimal Tingkat Pendidikan:** Pilih kualifikasi terendah: `SMA`, `D3`, `S1`, `S2`, atau `S3`.
   - **Wajib Lulus Sespimmen Polri:** Beri centang `[✓]` jika jabatan pimpinan mensyaratkan kelulusan Sespimmen.
   - **Kata Kunci Sertifikasi Wajib:** Ketik kata kunci kompetensi kejuruan *(misal: ketik `Penyidik` untuk formasi reskrim, atau `Bhabinkamtibmas` untuk binmas)*.
   - **Wajib Pernah Menjabat Sebagai:** Ketik nama jabatan masa lalu yang disyaratkan *(misal: ketik `Kapolres` untuk syarat Kabagdalpers)*.
   - **Rekam Jejak Jalur Fungsi:** Ketik fungsi kepolisian yang disyaratkan *(misal: `Reskrim`, `Lantas`, `SDM`, atau `Intel`)*.
5. Klik tombol kuning **"Simpan Aturan Syarat Mutlak"**.

---

### Langkah 2: Mengelola Master Kriteria & Mengatur Bobot (Wajib 100%)

#### A. Menambah Parameter Kriteria Penilaian Baru (Jika Diperlukan):
1. Buka menu **Master Data SDM** &rarr; **Kriteria & Bobot** (`/criteria`).
2. Klik tombol kuning **"+ Tambah Kriteria Baru"**.
3. Isi parameter kriteria:
   - **Kode:** Ketik kode unik (misal: `C7`).
   - **Nama Kriteria:** Misal: *Nilai Uji Menembak* atau *Uji Kesamaptaan Lanjutan*.
   - **Tipe Kriteria:** Pilih **Benefit** (semakin tinggi semakin baik) atau **Cost** (semakin rendah semakin baik).
   - **Skala Nilai:** Minimal `0` dan Maksimal `100`.
   - **Bobot Bawaan ($w$):** Nilai desimal standar (misal: `0.10` untuk 10%).
   - **Deskripsi:** Uraian penilaian.
4. Klik **"Simpan Kriteria Baru"**. Parameter ini akan otomatis muncul pada formulir penilaian kinerja personel dan kalkulator bobot jabatan.

#### B. Mengatur Bobot Kriteria Penilaian per Jabatan (Wajib Tepat 100%):
Setiap jabatan dapat memiliki prioritas pembobotan yang berbeda sesuai karakteristik tugas lapangan:
- **Jabatan Operasional Tempur/Lapangan:** Mengutamakan Tes Kesamaptaan Jasmani (TKJ) dan Kinerja (SMK).
- **Jabatan Penyelidikan/Penyidik:** Mengutamakan Asesmen Psikologi dan Nilai SMK.
- **Jabatan Staf/Pembinaan:** Lebih menimbang Masa Dinas dalam Pangkat (MDDP).

#### Cara Mengatur Bobot:
1. Pada menu **Jabatan & Syarat**, klik tombol putih **"Bobot (100%)"** pada jabatan yang diinginkan.
2. Anda akan melihat **Kalkulator Bobot Visual Real-Time** di bagian atas:
   - **Bar HIJAU ("Bobot Valid - Pas 100% / w = 1.0000"):** Komposisi bobot benar. Tombol simpan **AKTIF**.
   - **Bar MERAH ("Kurang X%" atau "Lebih X%"):** Total bobot belum pas. Tombol simpan **TERKUNCI OTOMATIS** untuk mencegah kesalahan komputasi matematis.
3. Ubah nilai desimal pada 6 kriteria:
   - **C1 - Nilai SMK:** Contoh `0.25` (25%)
   - **C2 - Asesmen Psikologi:** Contoh `0.20` (20%)
   - **C3 - Kesamaptaan Jasmani (TKJ):** Contoh `0.15` (15%)
   - **C4 - Masa Dinas Pangkat (MDDP):** Contoh `0.15` (15%)
   - **C5 - Penghargaan / Prestasi:** Contoh `0.10` (10%)
   - **C6 - Catatan Pelanggaran:** Contoh `0.15` (15%)
4. Pastikan jumlahnya tepat **1.0000 (100%)**.
5. *Tips:* Jika ragu, klik tombol abu-abu **"Reset ke Bobot Default"** untuk mengembalikan ke standar baku Mabes Polri.
6. Klik tombol kuning **"Simpan Bobot Kriteria"**.

---

### Langkah 3: Mendaftarkan & Mengelola Data Personel

Data personel yang lengkap dan valid merupakan kunci keakuratan sidang Wanjak.

#### A. Menambah Anggota Baru:
1. Buka menu **Master Data SDM** &rarr; **Data Personel** (`/personnel`).
2. Klik tombol kuning **"+ Tambah Personel Baru"** di pojok kanan atas.
3. Isi ketiga bagian formulir:
   - **Bagian 1: Identitas & Pangkat:** Masukkan NRP (unik 8 digit), Nama Lengkap beserta Gelar, Pangkat saat ini, dan Jalur Fungsi Utama.
   - **Bagian 2: Pendidikan & Dikbang:** Pilih Tingkat Pendidikan Formal terakhir, Jurusan, dan beri centang pada **"Lulus Sespimmen Polri"** jika yang bersangkutan adalah alumni Sespimmen.
   - **Bagian 3: Penugasan Saat Ini & Kontak:** Isi Nama Jabatan sekarang, Kesatuan/Satker, No. HP/WhatsApp, dan Email.
4. Klik **"Simpan Data Personel"**. Anda akan langsung dialihkan ke Halaman Profil Personel tersebut.

#### B. Mengisi Riwayat Jabatan Lampau (Penting untuk Syarat Kepemimpinan):
> ⚠️ **PENTING:** Jabatan pimpinan seperti Kabagdalpers mewajibkan kandidat pernah menduduki jabatan tertentu (misal: pernah menjabat *Kapolres*). Jika riwayat ini tidak diinput, sistem akan mendiskualifikasi personel ke Tab TMS.

1. Buka profil personel yang bersangkutan.
2. Gulir ke panel bawah bertuliskan **"Riwayat Penugasan & Jabatan Terdahulu"**.
3. Pada baris input formulir:
   - **Nama Jabatan:** Ketik nama jabatan terdahulu secara jelas *(contoh: `Kapolres Sleman`)*.  
     *(Catatan: Sistem mendeteksi kata kunci `Kapolres`. Jika ditulis `Wakapolres`, sistem otomatis tahu bahwa itu bukan Kapolres dan tidak akan meloloskannya)*.
   - **Kesatuan / Satker:** Ketik nama satker *(contoh: `Polres Sleman`)*.
   - **TMT Mulai & Selesai:** Masukkan tanggal mulai dan selesai dinas.
4. Klik tombol **"Simpan Riwayat Jabatan"**.

#### C. Mengisi Sertifikasi & Dikjur (Penting untuk Formasi Fungsional):
> ⚠️ **PENTING:** Formasi seperti *Bintara Penyidik* mewajibkan kepemilikan sertifikasi atau dikjur kejuruan yang mengandung kata `Penyidik`.

1. Pada halaman profil personel, perhatikan panel **"Sertifikasi & Dikjur"** di sebelah kanan.
2. Masukkan data:
   - **Nama Dikjur/Sertifikasi:** Ketik nama kejuruan *(contoh: `Dikjur Penyidik Pembantu Reskrim`)*.
   - **Lembaga Penerbit:** Contoh `Lemdiklat Polri` atau `SPN Polda`.
   - **Tanggal Perolehan:** Masukkan tanggal sertifikat diterbitkan.
3. Klik tombol **"Simpan Sertifikasi"**.

---

### Langkah 4: Menginput Nilai Kinerja Personel (SMK, Jasmani, Psikologi)

Nilai ini adalah angka yang diadu pada Tahap 2 Perangkingan TOPSIS.

1. Buka halaman profil personel di menu **Data Personel**.
2. Perhatikan panel tengah bertuliskan **"Nilai Penilaian Kinerja (SMK & Asesmen)"**.
3. Pilih **Tahun Periode** yang dievaluasi *(misal: **T.A. 2026**)*.
4. Masukkan skor dengan rentang angka **0 sampai 100**:
   - **C1 - Nilai SMK (Benefit):** Nilai evaluasi kerja semesteran (contoh: `88.50`).
   - **C2 - Uji Psikologi (Benefit):** Hasil tes assessment center / psikologi (contoh: `82.00`).
   - **C3 - Kesamaptaan Jasmani TKJ (Benefit):** Nilai lari, push-up, pull-up, shuttle run (contoh: `85.00`).
   - **C4 - Masa Dinas Pangkat MDDP (Benefit):** Pengalaman masa dinas pada pangkat saat ini (contoh: `75.00`).
   - **C5 - Penghargaan / Prestasi (Benefit):** Skor akumulasi tanda jasa, pin emas, atau piagam (contoh: `80.00`).
   - **C6 - Catatan Pelanggaran (TIPE COST / BEBAN):**
     > 🚨 **PERHATIAN KHUSUS KRITERIA C6 (TIPE COST):**  
     > - Kriteria Pelanggaran bertipe **Cost**, yang berarti: **Semakin KECIL angkanya, semakin BAIK peringkatnya**.  
     > - Jika personel **BERSIH / TIDAK MEMILIKI CATATAN PELANGGARAN**, **WAJIB DIISI ANGKA `0`**.  
     > - Jika personel pernah melanggar disiplin/kode etik, masukkan skor beban pelanggaran (misal: `10`, `25`, atau `50`).
5. Klik tombol kuning **"Simpan Nilai Penilaian T.A. 2026"**.

---

### Langkah 5: Menjalankan Simulasi Sidang Rekomendasi Wanjak

Setelah master jabatan, aturan mutlak, data personel, dan nilai kinerja terisi:

1. Klik menu navigasi **Seleksi SPK-TOPSIS** (`/recommendations`).
2. Isi formulir parameter sidang:
   - **Pilih Jabatan Target:** Pilih jabatan yang sedang disidangkan *(contoh: `Kepala Bagian Pengendalian Personel (Kabagdalpers)`)*.
   - **Tahun Periode Penilaian:** Pilih tahun anggaran berjalan *(contoh: `Tahun Anggaran 2026`)*.
   - **Keterangan / Catatan Sidang Wanjak:** Masukkan keterangan agenda *(contoh: `Sidang Wanjak Mutasi Jabatan Pamen Semester 1 T.A. 2026`)*.
3. Klik tombol besar gradasi kuning:  
   **`⚡ JALANKAN ANALISIS REKOMENDASI SPK-TOPSIS`**
4. Dalam hitungan detik, algoritma hybrid akan mengeksekusi penyaringan Hard Filter dan kalkulasi matriks TOPSIS, lalu langsung menampilkan **Halaman Hasil 3 Tab**.

---

### Langkah 6: Membaca, Mempresentasikan, & Mencetak Hasil Sidang (3 Tab)

Di hadapan Pimpinan Sidang Wanjak (Kapolda, Wakapolda, Karo SDM, dan para Pejabat Utama), presentasikan hasil penempatan melalui 3 tab berikut:

#### 🥇 Tab 1: Peringkat Rekomendasi (Kandidat Lolos & Kuota Formasi)
Tab ini menampilkan kandidat yang **Lolos Seluruh Syarat Mutlak (MS)** dan telah diperingkatkan secara objektif:
- **Lencana Emas ("✓ Rekomendasi Utama (Terpilih)"):**  
  Diberikan otomatis kepada kandidat peringkat **#1 sampai dengan kuota kursi yang tersedia**.  
  *Contoh:* Jika kuota formasi Kapolsek ada 3 kursi, maka peringkat **#1, #2, dan #3** otomatis mendapatkan status Terpilih Utama.
- **Lencana Abu-abu ("Cadangan Wanjak"):**  
  Diberikan kepada peringkat berikutnya (**#4, #5, dst.**). Jika suatu saat kandidat terpilih utama memiliki halangan dinas, pimpinan cukup menunjuk kandidat urutan teratas pada daftar cadangan ini.
- **Skor Preferensi TOPSIS ($V_i$):**  
  Angka kedekatan relatif (antara $0.0000$ hingga $1.0000$). Semakin mendekati $1$, kandidat tersebut memiliki profil kinerja yang paling mendekati solusi ideal kepolisian.

#### ❌ Tab 2: Personel TMS (Tidak Memenuhi Syarat Administrasi)
Tab ini menjawab pertanyaan pimpinan sidang: *"Mengapa anggota si X atau si Y tidak masuk dalam perangkingan?"*
- Menampilkan seluruh anggota yang terdiskualifikasi pada Tahap 1.
- Setiap personel dilengkapi **Kotak Penjelasan Alasan Diskualifikasi Secara Rinci dan Faktual**, contohnya:
  - *"Pangkat wajib adalah AKBP, sedangkan pangkat personel saat ini adalah Kompol."*
  - *"Personel belum menempuh atau lulus Pendidikan Pengembangan Sespimmen Polri."*
  - *"Wajib memiliki rekam jejak pernah menduduki jabatan 'Kapolres'."*
  - *"Belum memiliki sertifikasi atau dikjur kejuruan dengan spesifikasi 'Penyidik'."*
- Transparansi ini membuktikan bahwa proses sidang bebas dari unsur suka/tidak suka (*like and dislike*).

#### 📊 Tab 3: Transparansi Matriks Matematis TOPSIS
Tab ini berfungsi sebagai **bukti ilmiah (*Scientific Evidence*) dan akuntabilitas**:
- Menampilkan seluruh tahapan matriks perhitungan matematis:
  - Matriks Keputusan Mentah ($X$).
  - Matriks Ternormalisasi Euclidean ($R$).
  - Matriks Ternormalisasi Terbobot ($Y$).
  - Nilai Solusi Ideal Positif ($A^+$) dan Solusi Ideal Negatif ($A^-$).
  - Jarak Euclidean $D^+$ dan $D^-$, serta formula $V_i = \frac{D^-}{D^+ + D^-}$.

#### Cara Cetak / Ekspor Berkas Laporan untuk Pimpinan:
1. Buka tab hasil yang ingin disajikan (Tab 1, Tab 2, atau Tab 3).
2. Tekan tombol **Ctrl + P** pada keyboard komputer Anda.
3. Pada opsi tujuan pencetak (*Destination*), pilih printer fisik atau pilih **"Save as PDF"** untuk menyimpannya sebagai berkas PDF resmi lampiran Surat Keputusan (Skep/Sprin) Kapolda.

---

### Langkah 7: Mengakses Riwayat Keputusan Sidang

Seluruh hasil analisis tersimpan secara otomatis dan permanen dalam basis data:
1. Klik menu **Sidang Wanjak** &rarr; **Riwayat Keputusan** (`/recommendations/history`).
2. Anda dapat melihat daftar tanggal sidang terdahulu, jabatan target, jumlah formasi kuota, serta rasio kandidat yang Lolos (MS) vs Gugur (TMS).
3. Klik tombol hitam **"Buka 3 Tab Hasil"** pada baris riwayat yang diinginkan untuk membuka kembali arsip lengkap sidang tersebut.

---

## 6. Studi Kasus Nyata di Lapangan

### Skenario A: Pemilihan Formasi Kapolsek (Kuota: 3 Kursi)
- **Aturan Syarat Mutlak:** Pangkat minimal **IPTU**.
- **Hasil di Sistem:**
  - Personel golongan Bintara (Bripda s.d. Aiptu) otomatis masuk ke **Tab 2 (TMS)** dengan alasan: *"Pangkat personel saat ini belum memenuhi batas minimal pangkat yang dipersyaratkan (IPTU)"*.
  - Personel Pama dan Pamen (IPTU, AKP, Kompol) lolos ke **Tahap 2 TOPSIS**.
  - Sistem mengadu nilai SMK, Jasmani, Psikologi, MDDP, Piagam, dan Catatan Pelanggaran.
  - Peringkat **#1, #2, dan #3** mendapatkan lencana **Rekomendasi Utama (Terpilih)** untuk ditempatkan pada 3 Polsek yang kosong. Peringkat #4 ke atas berstatus **Cadangan Wanjak**.

### Skenario B: Pemilihan Formasi Kabagdalpers Biro SDM (Kuota: 1 Kursi)
- **Aturan Syarat Mutlak:**
  1. Pangkat wajib tepat **AKBP**.
  2. Wajib lulus **Sespimmen Polri**.
  3. Wajib memiliki riwayat pernah menjabat **Kapolres**.
- **Hasil di Sistem:**
  - **Kandidat 1 (AKBP, Lulus Sespimmen, Riwayat pernah menjabat Kapolres Sleman):**  
    &rarr; **LOLOS KE TAHAP 2 (MS)**.
  - **Kandidat 2 (AKBP, Lulus Sespimmen, Riwayat hanya pernah menjabat Wakapolres):**  
    &rarr; **GUGUR KE TAB 2 (TMS)**. Sistem dengan cerdas mendeteksi kata *Wakapolres* bukan *Kapolres*. Alasan diskualifikasi: *"Wajib memiliki rekam jejak pernah menduduki jabatan 'Kapolres'"*.
  - **Kandidat 3 (AKBP, Riwayat pernah Kapolres, namun belum lulus Sespimmen):**  
    &rarr; **GUGUR KE TAB 2 (TMS)** dengan alasan: *"Personel belum menempuh atau lulus Pendidikan Pengembangan Sespimmen Polri"*.

### Skenario C: Pemilihan Formasi Bintara Penyidik Reskrim (Kuota: 5 Kursi)
- **Aturan Syarat Mutlak:**
  1. Golongan kepangkatan: **Bintara**.
  2. Wajib memiliki sertifikasi/dikjur dengan kata kunci **Penyidik**.
- **Hasil di Sistem:**
  - Anggota Bintara yang hanya memiliki sertifikasi umum atau Lantas akan gugur ke Tab TMS.
  - Anggota Bintara yang memiliki sertifikasi seperti *Dikjur Penyidik Pembantu Reskrim* lolos ke perangkingan TOPSIS.
  - Lima personel dengan nilai kinerja tertinggi otomatis mengisi formasi kuota 5 kursi tersebut.

---

## 7. Panduan Mengatasi Kendala (Troubleshooting & Solusi)

### Masalah 1: "Tombol Simpan Bobot Kriteria tidak bisa diklik (berwarna redup)."
- **Penyebab:** Penjumlahan seluruh bobot kriteria pada jabatan tersebut belum berjumlah tepat **100% (1.0000)**.
- **Solusi:** Periksa bar indikator visual di atas form. Jika bertuliskan *"Kurang 5%"*, tambahkan salah satu kriteria sebesar `0.05`. Jika *"Lebih 10%"*, kurangi salah satu kriteria sebesar `0.10`. Ketika bar berwarna **HIJAU**, tombol simpan akan otomatis aktif. Atau, klik tombol **"Reset ke Bobot Default"**.

### Masalah 2: "Muncul notifikasi 'Tidak Ada Personel yang Memenuhi Syarat' saat simulasi dijalankan."
- **Penyebab:** Seluruh personel aktif dalam basis data tereliminasi oleh salah satu syarat mutlak jabatan.
- **Solusi:**
  1. Buka **Tab 2: Personel TMS** pada hasil simulasi untuk membaca alasan diskualifikasi para personel.
  2. Buka menu **Jabatan & Syarat** &rarr; klik **Edit Syarat**.
  3. Cek apakah ada syarat yang terlalu ketat (misalnya: salah memilih Pangkat Wajib Mutlak, padahal maksudnya adalah Pangkat Minimal).
  4. Periksa apakah pada profil anggota bersangkutan riwayat jabatan atau sertifikasinya belum diinput oleh operator.

### Masalah 3: "Ada dua kandidat yang memiliki skor preferensi yang persis sama ($V_i$ kembar)."
- **Solusi:** Sistem telah dilengkapi mekanisme pemecah nilai kembar (*tie-breaker*) otomatis: sistem akan memprioritaskan kandidat yang memiliki nilai jarak negatif ($D^-$) paling tinggi (yaitu kandidat yang paling jauh dari profil negatif/paling bersih dari catatan pelanggaran).

### Masalah 4: "Riwayat penugasan anggota sudah diisi, tetapi tetap tereliminasi TMS."
- **Penyebab:** Penulisan nama jabatan riwayat tidak mengandung kata kunci yang dicari, atau terdapat kata pengecualian (seperti menulis *Wakil* padahal disyaratkan pimpinan definitif).
- **Solusi:** Buka profil anggota, periksa ejaan pada tabel **Riwayat Jabatan**. Pastikan kata kuncinya sesuai (misal: jika syarat jabatan adalah `Kapolres`, pastikan di nama jabatan lampau tertulis kata `Kapolres`, bukan singkatan yang tidak baku).

### Masalah 5: "Tampilan website berantakan / tidak rapi di layar monitor."
- **Solusi:** Buka terminal komputer lokal dan jalankan:
  ```powershell
  npm run build
  ```
  Kemudian segarkan (*refresh*) browser Anda dengan menekan tombol **Ctrl + F5**.

---

## 8. Lembar Kendali / Checklist Sidang Wanjak

Cetak atau gunakan lembar kendali berikut sebagai pedoman kerja harian operator Bagbinkar:

| Fase | No | Item Pemeriksaan Operator | Status |
|:---:|:---:|:---|:---:|
| **H-1 Sidang** | 1 | Cek formasi jabatan yang dibuka dan kuota kursi di menu **Jabatan & Syarat** | `[  ]` |
| | 2 | Periksa aturan mutlak (Pangkat, Sespimmen, Riwayat Jabatan, Dikjur) | `[  ]` |
| | 3 | Pastikan bar pembobotan kriteria berstatus **HIJAU (Pas 100%)** | `[  ]` |
| | 4 | Verifikasi seluruh data personel kandidat sudah terdaftar di menu **Data Personel** | `[  ]` |
| | 5 | Pastikan Nilai SMK, Psikologi, Jasmani, MDDP, Piagam, dan Pelanggaran (C6 = 0 jika bersih) sudah tersimpan | `[  ]` |
| **Hari H Sidang** | 6 | Masuk ke menu **Seleksi SPK-TOPSIS**, pilih jabatan target dan tahun anggaran | `[  ]` |
| | 7 | Klik tombol **JALANKAN ANALISIS REKOMENDASI** | `[  ]` |
| | 8 | Tampilkan **Tab 1** untuk memaparkan Rekomendasi Utama (Terpilih sesuai kuota) & Cadangan | `[  ]` |
| | 9 | Buka **Tab 2** jika pimpinan menanyakan alasan personel tertentu yang gugur (TMS) | `[  ]` |
| | 10 | Buka **Tab 3** sebagai bukti transparansi matematis kaidah ilmiah TOPSIS | `[  ]` |
| **Pasca Sidang** | 11 | Cetak berkas hasil (Ctrl + P) untuk lampiran resmi Berita Acara Sidang Wanjak | `[  ]` |
| | 12 | Pastikan hasil tersimpan pada arsip **Riwayat Keputusan** | `[  ]` |

---

*Dokumen Resmi Biro Sumber Daya Manusia (SDM) Kepolisian Daerah*  
*Bagian Pembinaan Karir (Bagbinkar) & Dewan Pertimbangan Karir (Wanjak)*  
*Standar Operasional Prosedur (SOP) & Petunjuk Teknis Operator - Versi 2.0 (Oktober 2026)*

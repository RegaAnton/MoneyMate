# 💸 MoneyMate - Expense Tracker App

MoneyMate adalah aplikasi web pencatat pengeluaran keuangan pribadi yang ringan, cepat, dan simpel. Dibangun menggunakan Node.js dan Express, aplikasi ini menggunakan sistem file JSON sebagai media penyimpanan (database), sehingga sangat mudah diatur dan dijalankan di _local environment_ maupun _shared hosting_ konvensional seperti cPanel.

## ✨ Fitur Utama

- 📊 **Dashboard Interaktif**: Visualisasi pengeluaran menggunakan _Donut Chart_ (Chart.js).
- 🌓 **Dark Mode / Light Mode**: Tampilan yang nyaman di mata dan preferensi tersimpan otomatis di browser.
- 📑 **Sorting & Pagination**: Manajemen tabel riwayat transaksi yang rapi (10 data per halaman) dan bisa diurutkan berdasarkan tanggal atau jumlah.
- 📅 **Filter Waktu Dinamis**: Filter transaksi berdasarkan Hari Ini, Minggu Ini, Bulan Ini, Tahun Ini, atau rentang tanggal kustom.
- 📥 **Ekspor ke CSV**: Unduh riwayat transaksi yang sedang difilter ke dalam format CSV/Excel dengan satu klik.
- 🔐 **Sistem Autentikasi**: Login dan Register sederhana berbasis ID pengguna.

## 🛠️ Tech Stack

- **Frontend**: HTML5, CSS3, Vanilla JavaScript, Bootstrap 5.3, FontAwesome 6
- **Backend**: Node.js, Express.js
- **Database**: Local JSON Files (`users.json`, `categories.json`, `expenses.json`)
- **Library Tambahan**: Chart.js (Visualisasi data)

## 📂 Struktur Direktori

```text
moneymate/
├── public/                 # File statis Frontend
│   └── index.html          # Halaman utama (Single Page Application UI)
├── server.js               # Konfigurasi Backend Express.js
├── users.json              # Database: Data akun pengguna
├── categories.json         # Database: Master data kategori pengeluaran
├── expenses.json           # Database: Catatan riwayat transaksi
├── package.json            # Informasi project dan dependencies
└── README.md
```

## 🚀 Panduan Instalasi (Lokal)

Ikuti langkah-langkah di bawah ini untuk menjalankan aplikasi MoneyMate di komputer lokal Anda:

### 1. Persyaratan Sistem

Pastikan komputer Anda sudah terinstal **[Node.js](https://nodejs.org/)** (disarankan versi 18.x atau yang lebih baru). Anda bisa mengeceknya dengan menjalankan perintah berikut di terminal:

```bash
node -v
```

### 2. Clone Repository

Unduh kode sumber aplikasi ini ke komputer Anda menggunakan Git:

```bash
git clone https://github.com/RegaAnton/MoneyMate.git
cd moneymate
```

### 3. Install Dependencies

Instal semua pustaka yang dibutuhkan oleh server (seperti Express.js) dengan menjalankan:

```bash
npm install
```

### 4. Jalankan Server Aplikasi

Setelah semuanya siap, nyalakan server lokal dengan perintah:

```bash
node server.js
```

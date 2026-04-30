# Panduan Menambahkan Artikel Baru (Untuk Junior Developer)

Sistem artikel pada project `MoneyMate` saat ini dirancang agar ringan, cepat, dan ramah SEO tanpa perlu menggunakan database. Semua artikel menggunakan sistem *file Blade statis* yang dipadukan dengan *routing dinamis* Laravel.

Berikut adalah standar operasional prosedur (SOP) untuk mempublikasikan artikel SEO baru.

---

## Langkah 1: Buat File View Artikel

Setiap artikel direpresentasikan oleh satu file `.blade.php` di dalam folder `resources/views/articles/`. **Nama file otomatis menjadi URL (slug) dari artikel tersebut.**

1. Tentukan judul dan *slug* artikel. Hindari spasi pada nama file, gunakan tanda hubung (`-`).
   - Contoh Judul: "Cara Cepat Melunasi Hutang"
   - Contoh Slug (Nama File): `cara-cepat-melunasi-hutang.blade.php`
2. Buat file baru di direktori: `resources/views/articles/cara-cepat-melunasi-hutang.blade.php`.
3. Salin dan tempel (copy-paste) *boilerplate template* di bawah ini ke dalam file tersebut, lalu ubah bagian teks sesuai konten artikel:

```html
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <!-- UBAH: Sesuaikan Title, Description, dan Keywords untuk SEO -->
    <title>Judul Lengkap Artikel Anda - MoneyMate</title>
    <meta name="description" content="Tuliskan 1-2 kalimat ringkasan artikel di sini. Ini akan muncul di hasil pencarian Google.">
    <meta name="keywords" content="keyword 1, keyword 2, keuangan">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Instrument+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>body { font-family: 'Instrument Sans', sans-serif; }</style>
</head>
<body class="antialiased text-gray-900 bg-white">
    <!-- Navbar Kembali -->
    <nav class="border-b border-gray-100 py-4">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <a href="/" class="text-blue-600 font-semibold hover:underline">&larr; Kembali ke Beranda</a>
        </div>
    </nav>

    <!-- Konten Utama -->
    <main class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <article>
            <!-- UBAH: Judul H1 -->
            <h1 class="text-4xl font-extrabold tracking-tight text-gray-900 mb-4 leading-tight">
                Judul Lengkap Artikel Anda
            </h1>
            
            <!-- UBAH: Tanggal dan Kategori -->
            <p class="text-sm text-gray-500 mb-10">Dipublikasikan: 30 April 2026 • Kategori Edukasi</p>
            
            <!-- UBAH: Konten Artikel -->
            <!-- Gunakan tag <p> untuk paragraf, <h2> untuk subjudul, dan <ul><li> untuk list -->
            <div class="prose prose-lg text-gray-600 space-y-6">
                <p>Paragraf pembuka artikel dimulai di sini.</p>
                
                <h2 class="text-2xl font-bold text-gray-900 mt-8 mb-4">Subjudul Pertama</h2>
                <p>Isi dari subjudul pertama.</p>
                <ul class="list-disc pl-6 space-y-2">
                    <li>Poin penting pertama.</li>
                    <li>Poin penting kedua.</li>
                </ul>
            </div>
            
            <!-- Call To Action (Jangan Dihapus) -->
            <div class="mt-12 bg-blue-50 border border-blue-100 rounded-2xl p-8 text-center">
                <h3 class="text-xl font-bold text-blue-900 mb-3">Mulai Kelola Keuangan dengan Cerdas!</h3>
                <p class="text-blue-800 mb-6">Gunakan MoneyMate untuk mencatat pengeluaran secara mudah dan gratis.</p>
                <a href="{{ route('register') }}" class="inline-block bg-blue-600 text-white font-bold py-3 px-8 rounded-full hover:bg-blue-700 transition-colors">Daftar MoneyMate Sekarang</a>
            </div>
        </article>
    </main>
</body>
</html>
```

---

## Langkah 2: Daftarkan ke Sitemap XML (Sangat Penting untuk SEO)

Mesin pencari (Google, Bing) membaca file `sitemap.xml` untuk menemukan halaman baru. Jika artikel tidak didaftarkan di sini, artikel akan sangat sulit ditemukan di Google.

1. Buka file `public/sitemap.xml`.
2. Gulir ke bagian paling bawah, dan tambahkan blok `<url>` baru tepat **sebelum** baris `</urlset>`.
3. Ganti URL `<loc>` dengan slug artikel yang baru dibuat.
4. Ganti `<lastmod>` dengan tanggal rilis artikel.

**Contoh yang harus ditambahkan:**
```xml
  <url>
    <loc>https://moneymate.my.id/artikel/cara-cepat-melunasi-hutang</loc>
    <lastmod>2026-04-30</lastmod>
    <changefreq>weekly</changefreq>
    <priority>0.8</priority>
  </url>
```

---

## Langkah 3: Verifikasi Hasil

1. Jika menggunakan environment lokal, pastikan server menyala (`php artisan serve`).
2. Buka URL: `http://localhost:8000/artikel/nama-slug-kamu`.
   - *Catatan: Jika muncul error 404, artinya nama file `.blade.php` tidak cocok persis dengan URL (perhatikan typo atau tanda hubung).*
3. Buka URL: `http://localhost:8000/sitemap.xml` dan pastikan file XML tersebut tidak mengalami error struktur dan URL baru sudah muncul di dalam daftar.

Selamat bekerja!

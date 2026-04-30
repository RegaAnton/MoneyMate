<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Mengenal Aturan Budgeting 50/30/20 dan Cara Praktis Menerapkannya - MoneyMate</title>
    <meta name="description" content="Pelajari aturan budgeting 50/30/20 untuk mengelola keuangan pribadi secara seimbang antara kebutuhan, keinginan, dan tabungan.">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Instrument+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>body { font-family: 'Instrument Sans', sans-serif; }</style>
</head>
<body class="antialiased text-gray-900 bg-white">
    <nav class="border-b border-gray-100 py-4">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <a href="/" class="text-blue-600 font-semibold hover:underline">&larr; Kembali ke Beranda</a>
        </div>
    </nav>
    <main class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <article>
            <h1 class="text-4xl font-extrabold tracking-tight text-gray-900 mb-4 leading-tight">
                Mengenal Aturan Budgeting 50/30/20 dan Cara Praktis Menerapkannya
            </h1>
            <p class="text-sm text-gray-500 mb-10">Dipublikasikan: {{ date('d F Y') }} • Edukasi Keuangan</p>
            <div class="prose prose-lg text-gray-600 space-y-6">
                <p>Mengatur keuangan tidak harus rumit. Salah satu metode paling populer dan efektif adalah aturan 50/30/20. Metode ini membantu Anda menyeimbangkan gaya hidup dengan tujuan finansial jangka panjang.</p>
                <h2 class="text-2xl font-bold text-gray-900 mt-8 mb-4">Apa Itu Aturan 50/30/20?</h2>
                <p>Metode ini pertama kali dipopulerkan oleh Senator Elizabeth Warren. Intinya adalah membagi pendapatan bersih Anda menjadi tiga kategori utama:</p>
                <ul class="list-disc pl-6 space-y-2">
                    <li><strong>50% untuk Kebutuhan:</strong> Biaya sewa, utilitas, bahan makanan, dan asuransi.</li>
                    <li><strong>30% untuk Keinginan:</strong> Makan di luar, hobi, dan belanja barang non-esensial.</li>
                    <li><strong>20% untuk Tabungan & Hutang:</strong> Dana darurat, investasi, dan pelunasan hutang ekstra.</li>
                </ul>
                <h2 class="text-2xl font-bold text-gray-900 mt-8 mb-4">Cara Praktis Menerapkannya</h2>
                <p>Mulai dengan mencatat seluruh pendapatan bulanan Anda. Gunakan aplikasi seperti <strong>MoneyMate</strong> untuk melacak setiap pengeluaran dan memastikan Anda tetap berada di dalam batas persentase tersebut.</p>
            </div>
            <div class="mt-12 bg-blue-50 border border-blue-100 rounded-2xl p-8 text-center">
                <h3 class="text-xl font-bold text-blue-900 mb-3">Siap Mengatur Anggaran?</h3>
                <p class="text-blue-800 mb-6">Mulai terapkan aturan 50/30/20 dengan mudah menggunakan MoneyMate.</p>
                <a href="{{ route('register') }}" class="inline-block bg-blue-600 text-white font-bold py-3 px-8 rounded-full hover:bg-blue-700 transition-colors">Daftar Sekarang</a>
            </div>
        </article>
    </main>
</body>
</html>

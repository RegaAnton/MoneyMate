<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Bahaya Overbudget! Ini Pentingnya Menetapkan Batas Anggaran per Kategori - MoneyMate</title>
    <meta name="description" content="Mengapa menetapkan batas anggaran untuk setiap kategori pengeluaran sangat penting untuk kesehatan finansial Anda.">
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
                Bahaya Overbudget! Ini Pentingnya Menetapkan Batas Anggaran per Kategori
            </h1>
            <p class="text-sm text-gray-500 mb-10">Dipublikasikan: {{ date('d F Y') }} • Manajemen Anggaran</p>
            <div class="prose prose-lg text-gray-600 space-y-6">
                <p>Banyak orang merasa sudah berhemat namun tetap kehabisan uang di tengah bulan. Masalahnya seringkali bukan pada total pengeluaran, melainkan pada <em>overbudget</em> di kategori tertentu.</p>
                <h2 class="text-2xl font-bold text-gray-900 mt-8 mb-4">Mengapa Harus Per Kategori?</h2>
                <p>Menetapkan batas total bulanan saja tidak cukup. Anda perlu mendetailkan batas maksimal untuk makan, transportasi, belanja, hingga hiburan. Ini memberikan kontrol yang lebih spesifik dan mencegah Anda "meminjam" jatah dari pos kebutuhan pokok.</p>
                <h2 class="text-2xl font-bold text-gray-900 mt-8 mb-4">Tips Menetapkan Batas Anggaran</h2>
                <p>Analisis pengeluaran Anda 3 bulan terakhir. Gunakan rata-rata tersebut sebagai dasar, lalu tantang diri Anda untuk menguranginya sebesar 10% setiap bulan hingga mencapai angka ideal.</p>
            </div>
            <div class="mt-12 bg-blue-50 border border-blue-100 rounded-2xl p-8 text-center">
                <h3 class="text-xl font-bold text-blue-900 mb-3">Atur Batas Anggaran Anda!</h3>
                <p class="text-blue-800 mb-6">Gunakan fitur Budgeting di MoneyMate untuk menetapkan limit per kategori secara otomatis.</p>
                <a href="{{ route('register') }}" class="inline-block bg-blue-600 text-white font-bold py-3 px-8 rounded-full hover:bg-blue-700 transition-colors">Atur Anggaran Sekarang</a>
            </div>
        </article>
    </main>
</body>
</html>

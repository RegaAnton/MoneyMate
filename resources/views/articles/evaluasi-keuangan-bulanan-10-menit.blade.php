<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Cara Melakukan Evaluasi Keuangan Bulanan Hanya dalam 10 Menit - MoneyMate</title>
    <meta name="description" content="Tips praktis melakukan evaluasi keuangan bulanan secara cepat dan efektif agar strategi finansial Anda tetap tepat sasaran.">
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
                Cara Melakukan Evaluasi Keuangan Bulanan Hanya dalam 10 Menit
            </h1>
            <p class="text-sm text-gray-500 mb-10">Dipublikasikan: {{ date('d F Y') }} • Evaluasi Finansial</p>
            <div class="prose prose-lg text-gray-600 space-y-6">
                <p>Banyak orang malas melakukan evaluasi karena dianggap memakan waktu lama. Padahal, jika Anda memiliki catatan yang rapi, evaluasi hanya butuh 10 menit.</p>
                <h2 class="text-2xl font-bold text-gray-900 mt-8 mb-4">Metode Evaluasi 10 Menit</h2>
                <ul class="list-disc pl-6 space-y-2">
                    <li><strong>Menit 1-3:</strong> Cek total pengeluaran vs total pendapatan. Apakah Anda surplus atau defisit?</li>
                    <li><strong>Menit 4-7:</strong> Identifikasi 3 kategori pengeluaran terbesar. Apakah sudah sesuai anggaran?</li>
                    <li><strong>Menit 8-10:</strong> Tentukan satu penyesuaian untuk bulan depan (misal: mengurangi jajan boba).</li>
                </ul>
                <p>Gunakan dashboard <strong>MoneyMate</strong> untuk mendapatkan data-data tersebut secara instan tanpa perlu menghitung manual.</p>
            </div>
            <div class="mt-12 bg-blue-50 border border-blue-100 rounded-2xl p-8 text-center">
                <h3 class="text-xl font-bold text-blue-900 mb-3">Siap Evaluasi Bulan Ini?</h3>
                <p class="text-blue-800 mb-6">Dapatkan laporan keuangan otomatis Anda hanya di MoneyMate.</p>
                <a href="{{ route('register') }}" class="inline-block bg-blue-600 text-white font-bold py-3 px-8 rounded-full hover:bg-blue-700 transition-colors">Lihat Laporan Saya</a>
            </div>
        </article>
    </main>
</body>
</html>

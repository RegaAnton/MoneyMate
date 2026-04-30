<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Panduan Menentukan Target Anggaran Bulanan Agar Tabungan Cepat Terkumpul - MoneyMate</title>
    <meta name="description" content="Cara menentukan target anggaran yang realistis namun menantang agar Anda bisa menabung lebih banyak setiap bulannya.">
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
                Panduan Menentukan Target Anggaran Bulanan Agar Tabungan Cepat Terkumpul
            </h1>
            <p class="text-sm text-gray-500 mb-10">Dipublikasikan: {{ date('d F Y') }} • Perencanaan Keuangan</p>
            <div class="prose prose-lg text-gray-600 space-y-6">
                <p>Menabung bukan soal sisa uang, tapi soal target yang direncanakan. Berikut adalah panduan menentukan target anggaran yang efektif:</p>
                <h2 class="text-2xl font-bold text-gray-900 mt-8 mb-4">Mulai dari Tujuan (Goals)</h2>
                <p>Tentukan apa yang ingin Anda capai (misal: DP rumah, dana nikah, atau liburan). Hitung berapa yang harus Anda sisihkan setiap bulan. Angka inilah yang menjadi prioritas utama sebelum menentukan jatah anggaran lainnya.</p>
                <h2 class="text-2xl font-bold text-gray-900 mt-8 mb-4">Evaluasi Realitas</h2>
                <p>Bandingkan target tabungan dengan pendapatan Anda. Jika tidak memungkinkan, cari pos pengeluaran gaya hidup yang bisa ditekan. Gunakan fitur *Goals* di <strong>MoneyMate</strong> untuk memantau progres tabungan Anda secara real-time.</p>
            </div>
            <div class="mt-12 bg-blue-50 border border-blue-100 rounded-2xl p-8 text-center">
                <h3 class="text-xl font-bold text-blue-900 mb-3">Capai Target Finansial Anda!</h3>
                <p class="text-blue-800 mb-6">MoneyMate membantu Anda merencanakan dan mencapai target tabungan lebih cepat.</p>
                <a href="{{ route('register') }}" class="inline-block bg-blue-600 text-white font-bold py-3 px-8 rounded-full hover:bg-blue-700 transition-colors">Mulai Buat Target</a>
            </div>
        </article>
    </main>
</body>
</html>

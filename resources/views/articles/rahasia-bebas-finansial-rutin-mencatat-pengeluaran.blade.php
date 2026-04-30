<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Rahasia Bebas Finansial Dimulai dari Rutin Mencatat Pengeluaran Harian - MoneyMate</title>
    <meta name="description" content="Mengapa kebiasaan kecil mencatat pengeluaran harian adalah fondasi utama menuju kebebasan finansial jangka panjang.">
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
                Rahasia Bebas Finansial Dimulai dari Rutin Mencatat Pengeluaran Harian
            </h1>
            <p class="text-sm text-gray-500 mb-10">Dipublikasikan: {{ date('d F Y') }} • Kebebasan Finansial</p>
            <div class="prose prose-lg text-gray-600 space-y-6">
                <p>Banyak orang bermimpi mencapai *Financial Freedom*, namun melupakan langkah paling dasarnya: mengetahui ke mana uang mereka pergi setiap harinya.</p>
                <h2 class="text-2xl font-bold text-gray-900 mt-8 mb-4">Kesadaran Finansial (Financial Awareness)</h2>
                <p>Mencatat pengeluaran harian bukan berarti Anda harus pelit. Tujuannya adalah membangun kesadaran. Saat Anda tahu bahwa Anda telah menghabiskan terlalu banyak untuk hal yang tidak penting, Anda secara alami akan menyesuaikan perilaku belanja Anda di hari berikutnya.</p>
                <h2 class="text-2xl font-bold text-gray-900 mt-8 mb-4">Langkah Kecil untuk Hasil Besar</h2>
                <p>Mulailah dari sekarang. Jangan menunggu besok. Gunakan <strong>MoneyMate</strong> untuk mencatat pengeluaran harian Anda hanya dalam waktu kurang dari 10 detik setiap kali bertransaksi.</p>
            </div>
            <div class="mt-12 bg-blue-50 border border-blue-100 rounded-2xl p-8 text-center">
                <h3 class="text-xl font-bold text-blue-900 mb-3">Mulai Perjalanan Anda!</h3>
                <p class="text-blue-800 mb-6">Wujudkan kebebasan finansial Anda bersama MoneyMate.</p>
                <a href="{{ route('register') }}" class="inline-block bg-blue-600 text-white font-bold py-3 px-8 rounded-full hover:bg-blue-700 transition-colors">Daftar Sekarang</a>
            </div>
        </article>
    </main>
</body>
</html>

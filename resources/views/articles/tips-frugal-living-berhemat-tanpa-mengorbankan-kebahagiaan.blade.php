<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Tips Frugal Living: Cara Seru Berhemat Tanpa Harus Mengorbankan Kebahagiaan - MoneyMate</title>
    <meta name="description" content="Mengenal konsep frugal living dan bagaimana cara menerapkannya untuk hidup yang lebih bermakna dan hemat tanpa merasa kekurangan.">
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
                Tips Frugal Living: Cara Seru Berhemat Tanpa Harus Mengorbankan Kebahagiaan
            </h1>
            <p class="text-sm text-gray-500 mb-10">Dipublikasikan: {{ date('d F Y') }} • Gaya Hidup</p>
            <div class="prose prose-lg text-gray-600 space-y-6">
                <p>*Frugal living* bukan berarti menjadi pelit. Ini adalah gaya hidup yang berfokus pada nilai (value) sebuah barang atau pengalaman, bukan sekadar harga.</p>
                <h2 class="text-2xl font-bold text-gray-900 mt-8 mb-4">Cara Menjalani Frugal Living</h2>
                <ul class="list-disc pl-6 space-y-2">
                    <li><strong>Beli Kualitas, Bukan Kuantitas:</strong> Lebih baik beli satu sepatu mahal yang awet 5 tahun daripada 5 sepatu murah yang rusak dalam setahun.</li>
                    <li><strong>Utamakan Pengalaman:</strong> Bahagia tidak selalu datang dari barang baru. Liburan kecil bersama keluarga seringkali lebih berkesan.</li>
                    <li><strong>Gunakan Barang Sampai Habis:</strong> Memaksimalkan penggunaan barang sebelum memutuskan membeli yang baru.</li>
                </ul>
                <p>Melacak pengeluaran dengan <strong>MoneyMate</strong> akan membantu Anda melihat apakah uang Anda benar-benar dihabiskan untuk hal-hal yang memberikan nilai kebahagiaan sejati bagi Anda.</p>
            </div>
            <div class="mt-12 bg-blue-50 border border-blue-100 rounded-2xl p-8 text-center">
                <h3 class="text-xl font-bold text-blue-900 mb-3">Mulai Hidup Frugal!</h3>
                <p class="text-blue-800 mb-6">Kelola uang Anda dengan bijak bersama komunitas MoneyMate.</p>
                <a href="{{ route('register') }}" class="inline-block bg-blue-600 text-white font-bold py-3 px-8 rounded-full hover:bg-blue-700 transition-colors">Bergabung Sekarang</a>
            </div>
        </article>
    </main>
</body>
</html>

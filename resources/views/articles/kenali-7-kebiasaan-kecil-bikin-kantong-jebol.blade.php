<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sering Bocor Halus? Kenali 7 Kebiasaan Kecil yang Bikin Kantong Cepat Jebol - MoneyMate</title>
    <meta name="description" content="Ketahui kebiasaan kecil yang sering dianggap sepele namun sebenarnya menguras kantong Anda secara perlahan.">
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
                Sering Bocor Halus? Kenali 7 Kebiasaan Kecil yang Bikin Kantong Cepat Jebol
            </h1>
            <p class="text-sm text-gray-500 mb-10">Dipublikasikan: {{ date('d F Y') }} • Tips Keuangan</p>
            <div class="prose prose-lg text-gray-600 space-y-6">
                <p>"Bocor halus" dalam keuangan adalah pengeluaran kecil yang tidak terasa namun jika dikumpulkan jumlahnya sangat besar. Berikut adalah 7 kebiasaan yang wajib Anda waspadai:</p>
                <ol class="list-decimal pl-6 space-y-4">
                    <li><strong>Biaya Admin Bank:</strong> Membiarkan saldo mengendap di banyak rekening tanpa bunga yang sebanding.</li>
                    <li><strong>Biaya Parkir & Transportasi Pendek:</strong> Sering menggunakan ojek online untuk jarak yang bisa ditempuh dengan jalan kaki.</li>
                    <li><strong>Langganan yang Tidak Digunakan:</strong> Aplikasi atau layanan streaming yang jarang dibuka.</li>
                    <li><strong>Jajan Minuman Kekinian:</strong> Kopi atau boba harian yang harganya setara dengan makan siang.</li>
                    <li><strong>Biaya Pengiriman Belanja Online:</strong> Tidak memanfaatkan promo gratis ongkir atau belanja barang kecil secara terpisah.</li>
                    <li><strong>Membeli Barang Diskon:</strong> Membeli sesuatu hanya karena diskon, padahal tidak benar-benar butuh.</li>
                    <li><strong>Biaya Makan di Luar yang Berlebihan:</strong> Kurangnya perencanaan makan (meal prep) sehingga sering membeli makanan instan.</li>
                </ol>
                <p>Mencatat setiap transaksi kecil adalah kunci untuk menghentikan kebocoran ini. Gunakan fitur pelacakan di <strong>MoneyMate</strong> untuk melihat data pengeluaran Anda secara detail.</p>
            </div>
            <div class="mt-12 bg-blue-50 border border-blue-100 rounded-2xl p-8 text-center">
                <h3 class="text-xl font-bold text-blue-900 mb-3">Hentikan Bocor Halus Sekarang!</h3>
                <p class="text-blue-800 mb-6">Mulai catat pengeluaran kecil Anda dengan MoneyMate secara praktis.</p>
                <a href="{{ route('register') }}" class="inline-block bg-blue-600 text-white font-bold py-3 px-8 rounded-full hover:bg-blue-700 transition-colors">Coba MoneyMate Gratis</a>
            </div>
        </article>
    </main>
</body>
</html>

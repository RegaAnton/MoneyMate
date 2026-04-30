<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Tinggalkan Buku Catatan Manual: Keuntungan Beralih ke Catatan Keuangan Digital - MoneyMate</title>
    <meta name="description" content="Mengapa beralih dari buku catatan fisik ke sistem digital akan mengubah cara Anda mengelola uang selamanya.">
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
                Tinggalkan Buku Catatan Manual: Keuntungan Beralih ke Catatan Keuangan Digital
            </h1>
            <p class="text-sm text-gray-500 mb-10">Dipublikasikan: {{ date('d F Y') }} • Keuangan Digital</p>
            <div class="prose prose-lg text-gray-600 space-y-6">
                <p>Mencatat keuangan di buku fisik mungkin terasa nostalgia, namun dari sisi efisiensi, sistem digital jauh lebih unggul.</p>
                <h2 class="text-2xl font-bold text-gray-900 mt-8 mb-4">Efisiensi dan Akurasi</h2>
                <p>Dengan catatan digital, Anda tidak perlu lagi menghitung total dengan kalkulator secara manual. Semua data diproses secara otomatis oleh sistem, mengurangi risiko kesalahan hitung yang sering terjadi pada catatan buku.</p>
                <h2 class="text-2xl font-bold text-gray-900 mt-8 mb-4">Pencarian Data yang Cepat</h2>
                <p>Ingin tahu berapa pengeluaran makan Anda setahun yang lalu? Pada buku manual, Anda harus membolak-balik halaman. Di aplikasi digital, cukup pilih filter tanggal dan hasilnya muncul seketika.</p>
            </div>
            <div class="mt-12 bg-blue-50 border border-blue-100 rounded-2xl p-8 text-center">
                <h3 class="text-xl font-bold text-blue-900 mb-3">Siap Go Digital?</h3>
                <p class="text-blue-800 mb-6">Beralihlah ke MoneyMate untuk pencatatan keuangan yang lebih modern dan aman.</p>
                <a href="{{ route('register') }}" class="inline-block bg-blue-600 text-white font-bold py-3 px-8 rounded-full hover:bg-blue-700 transition-colors">Beralih Sekarang</a>
            </div>
        </article>
    </main>
</body>
</html>

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>7 Alasan Mengapa Anda Wajib Menggunakan Aplikasi Pencatat Keuangan di Tahun Ini - MoneyMate</title>
    <meta name="description" content="Mengapa aplikasi pencatat keuangan adalah alat wajib untuk siapa saja yang ingin mencapai kebebasan finansial dengan lebih cepat.">
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
                7 Alasan Mengapa Anda Wajib Menggunakan Aplikasi Pencatat Keuangan di Tahun Ini
            </h1>
            <p class="text-sm text-gray-500 mb-10">Dipublikasikan: {{ date('d F Y') }} • Teknologi Finansial</p>
            <div class="prose prose-lg text-gray-600 space-y-6">
                <p>Di era digital, mengelola uang secara manual sudah ketinggalan zaman. Berikut adalah 7 alasan mengapa aplikasi seperti <strong>MoneyMate</strong> sangat krusial:</p>
                <ol class="list-decimal pl-6 space-y-4">
                    <li><strong>Akses Kapan Saja:</strong> Mencatat langsung setelah transaksi terjadi melalui smartphone.</li>
                    <li><strong>Visualisasi Data:</strong> Melihat grafik pengeluaran bulanan secara instan.</li>
                    <li><strong>Keamanan Data:</strong> Penyimpanan berbasis cloud yang aman dan terenkripsi.</li>
                    <li><strong>Pengingat Tagihan:</strong> Menghindari denda keterlambatan pembayaran.</li>
                    <li><strong>Laporan Otomatis:</strong> Tidak perlu menghitung manual di akhir bulan.</li>
                    <li><strong>Evaluasi yang Lebih Cepat:</strong> Menemukan pos pengeluaran boros hanya dalam hitungan detik.</li>
                    <li><strong>Membangun Kebiasaan Disiplin:</strong> Memotivasi diri untuk tetap berada di jalur finansial yang benar.</li>
                </ol>
            </div>
            <div class="mt-12 bg-blue-50 border border-blue-100 rounded-2xl p-8 text-center">
                <h3 class="text-xl font-bold text-blue-900 mb-3">Mulai Digitalisasi Keuangan Anda!</h3>
                <p class="text-blue-800 mb-6">Rasakan kemudahan mencatat keuangan dengan aplikasi MoneyMate yang user-friendly.</p>
                <a href="{{ route('register') }}" class="inline-block bg-blue-600 text-white font-bold py-3 px-8 rounded-full hover:bg-blue-700 transition-colors">Daftar MoneyMate</a>
            </div>
        </article>
    </main>
</body>
</html>

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <!-- SEO Optimization -->
    <title>Cara Ampuh Mengatur Keuangan Pribadi Agar Gaji Tidak Numpang Lewat</title>
    <meta name="description" content="Pelajari cara ampuh mengatur keuangan pribadi dengan metode 50/30/20, catat pengeluaran, dan siapkan dana darurat agar gaji bulanan Anda tidak cepat habis.">
    <meta name="keywords" content="cara mengatur keuangan pribadi, kelola gaji, tips menabung, keuangan, budgeting, money mate">
    
    <!-- Fonts & Styles -->
    <link href="https://fonts.googleapis.com/css2?family=Instrument+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
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
                Cara Ampuh Mengatur Keuangan Pribadi Agar Gaji Tidak Sekadar Numpang Lewat
            </h1>
            
            <p class="text-sm text-gray-500 mb-10">Dipublikasikan: {{ date('d F Y') }} • Panduan Keuangan</p>

            <div class="prose prose-lg text-gray-600 space-y-6">
                <p>
                    Pernahkah Anda merasa gaji bulanan baru saja masuk, tapi dalam hitungan minggu (atau bahkan hari) saldonya sudah menipis? Anda tidak sendirian. Fenomena "gaji numpang lewat" sering terjadi akibat kurangnya manajemen keuangan dan pelacakan pengeluaran yang baik.
                </p>

                <h2 class="text-2xl font-bold text-gray-900 mt-8 mb-4">1. Gunakan Metode Budgeting 50/30/20</h2>
                <p>
                    Langkah pertama yang sangat ampuh adalah membagi gaji Anda sejak awal. Metode 50/30/20 adalah salah satu yang termudah:
                </p>
                <ul class="list-disc pl-6 space-y-2">
                    <li><strong>50% untuk Kebutuhan Pokok:</strong> Sewa tempat tinggal, makanan sehari-hari, transportasi, tagihan listrik, dan cicilan wajib.</li>
                    <li><strong>30% untuk Keinginan:</strong> Hiburan, makan di kafe, langganan streaming, atau hobi.</li>
                    <li><strong>20% untuk Tabungan & Investasi:</strong> Dana darurat, investasi reksadana atau saham untuk masa depan.</li>
                </ul>

                <h2 class="text-2xl font-bold text-gray-900 mt-8 mb-4">2. Bayar Diri Anda Terlebih Dahulu (Pay Yourself First)</h2>
                <p>
                    Kesalahan terbesar banyak orang adalah menabung dari <em>sisa uang</em> di akhir bulan. Praktik terbaiknya adalah langsung menyisihkan 20% uang untuk ditabung di hari Anda menerima gaji.
                </p>

                <h2 class="text-2xl font-bold text-gray-900 mt-8 mb-4">3. Catat Semua Pengeluaran (Bocor Halus)</h2>
                <p>
                    Seringkali, gaji habis bukan karena membeli barang mewah, melainkan karena pengeluaran kecil yang menumpuk—seperti jajan minuman kekinian atau biaya admin. Dengan mencatat pengeluaran harian, Anda dapat menemukan ke mana uang tersebut pergi.
                </p>

                <h2 class="text-2xl font-bold text-gray-900 mt-8 mb-4">4. Bangun Dana Darurat</h2>
                <p>
                    Pastikan Anda memiliki dana darurat (idealnya 3 hingga 6 bulan pengeluaran). Dana ini berfungsi sebagai bantalan jika terjadi hal tak terduga, sehingga Anda tidak perlu berutang.
                </p>
            </div>

            <!-- Call to Action -->
            <div class="mt-12 bg-blue-50 border border-blue-100 rounded-2xl p-8 text-center">
                <h3 class="text-xl font-bold text-blue-900 mb-3">Mulai Lacak Pengeluaran Anda Sekarang!</h3>
                <p class="text-blue-800 mb-6">Gunakan aplikasi MoneyMate untuk mencatat pengeluaran dan mengatur anggaran dengan mudah, aman, dan gratis.</p>
                <a href="{{ route('register') }}" class="inline-block bg-blue-600 text-white font-bold py-3 px-8 rounded-full hover:bg-blue-700 transition-colors">
                    Daftar MoneyMate Gratis
                </a>
            </div>
        </article>
    </main>

</body>
</html>

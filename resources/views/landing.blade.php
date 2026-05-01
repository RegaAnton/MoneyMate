<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>MoneyMate - Kelola Keuangan Pribadi Jadi Lebih Cerdas</title>
    
    <!-- SEO Meta Tags -->
    <meta name="description" content="Ambil kendali keuangan Anda dengan MoneyMate. Lacak pengeluaran, buat anggaran, dan capai tujuan finansial Anda dengan manajer keuangan pribadi yang mudah digunakan.">
    <meta name="keywords" content="pelacak keuangan, manajer pengeluaran, perencana anggaran, keuangan pribadi, money mate">
    <meta property="og:title" content="MoneyMate - Pelacak Keuangan Pribadi Cerdas">
    <meta property="og:description" content="Kuasai keuangan Anda tanpa stres. Lacak, atur anggaran, dan menabung dengan MoneyMate.">
    <meta property="og:type" content="website">
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Instrument+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Styles / Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        body {
            font-family: 'Instrument Sans', sans-serif;
        }
        .glass-card {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(10px);
            border: 1px border rgba(255, 255, 255, 0.2);
        }
    </style>
</head>
<body class="antialiased text-gray-900 bg-white selection:bg-blue-100 selection:text-blue-900">
    <!-- Navigation -->
    <nav class="sticky top-0 z-50 bg-white/80 backdrop-blur-md border-b border-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-20 items-center">
                <div class="flex items-center">
                    <a href="/" class="flex items-center gap-3 group">
                        <img src="{{ asset('icon-192.png') }}" alt="MoneyMate Logo" class="w-10 h-10 rounded-xl group-hover:rotate-6 transition-transform shadow-lg shadow-blue-100">
                        <span class="text-2xl font-bold tracking-tight text-gray-900">MoneyMate</span>
                    </a>
                </div>
                
                <div class="hidden md:flex items-center space-x-10">
                    <a href="#features" class="text-sm font-semibold text-gray-600 hover:text-blue-600 transition-colors">Fitur</a>
                    <a href="#about" class="text-sm font-semibold text-gray-600 hover:text-blue-600 transition-colors">Tentang</a>
                    <div class="h-4 w-px bg-gray-200"></div>
                    <a href="{{ route('login') }}" class="text-sm font-semibold text-gray-600 hover:text-blue-600 transition-colors">Masuk</a>
                    <a href="{{ route('register') }}" class="inline-flex items-center justify-center px-6 py-2.5 border border-transparent text-sm font-bold rounded-full text-white bg-blue-600 hover:bg-blue-700 transition-all shadow-md shadow-blue-200 hover:shadow-lg">
                        Gabung Sekarang
                    </a>
                </div>

                <!-- Mobile menu button -->
                <div class="md:hidden flex items-center">
                    <button id="mobile-menu-button" class="p-2 text-gray-600 hover:text-blue-600 focus:outline-none transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Menu -->
        <div id="mobile-menu" class="hidden md:hidden bg-white border-b border-gray-100 animate-in slide-in-from-top duration-300">
            <div class="px-4 pt-2 pb-6 space-y-3 shadow-xl">
                <a href="#features" class="block px-3 py-3 rounded-xl text-base font-semibold text-gray-600 hover:text-blue-600 hover:bg-blue-50">Fitur</a>
                <a href="#about" class="block px-3 py-3 rounded-xl text-base font-semibold text-gray-600 hover:text-blue-600 hover:bg-blue-50">Tentang</a>
                <div class="h-px bg-gray-100 my-2"></div>
                <a href="{{ route('login') }}" class="block px-3 py-3 rounded-xl text-base font-semibold text-gray-600 hover:text-blue-600">Masuk</a>
                <a href="{{ route('register') }}" class="block px-4 py-4 rounded-xl text-base font-bold text-center text-white bg-blue-600 shadow-lg shadow-blue-100">Mulai Gratis</a>
            </div>
        </div>
    </nav>

    <main>
        <!-- Hero Section -->
        <section class="relative overflow-hidden pt-20 pb-32 lg:pt-40 lg:pb-56 bg-gradient-to-b from-blue-50/50 to-white">
            <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-blue-100 text-blue-700 text-xs font-bold uppercase tracking-wider mb-8">
                    <span class="relative flex h-2 w-2">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-blue-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-blue-500"></span>
                    </span>
                    Dipercaya oleh 1000+ Pengguna
                </div>
                <h1 class="text-5xl font-extrabold tracking-tight text-gray-900 sm:text-7xl lg:text-8xl mb-8">
                    Kuasai keuangan Anda <br/>
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-indigo-500">tanpa rasa stres</span>
                </h1>
                <p class="mt-8 text-xl md:text-2xl text-gray-600 leading-relaxed max-w-3xl mx-auto">
                    MoneyMate adalah cara simpel, cantik, dan aman untuk melacak pengeluaran, mengatur anggaran, dan mencapai tujuan finansial Anda lebih cepat.
                </p>
                <div class="mt-12 flex flex-col sm:flex-row justify-center gap-5">
                    <a href="{{ route('register') }}" class="inline-flex items-center justify-center px-12 py-5 border border-transparent text-lg font-bold rounded-2xl text-white bg-blue-600 hover:bg-blue-700 transition-all shadow-xl shadow-blue-200 hover:-translate-y-1">
                        Mulai Menabung Hari Ini
                    </a>
                    <a href="#features" class="inline-flex items-center justify-center px-12 py-5 border border-gray-200 text-lg font-bold rounded-2xl text-gray-700 bg-white hover:bg-gray-50 transition-all hover:border-gray-300">
                        Lihat Cara Kerja
                    </a>
                </div>
            </div>
            <!-- Decorative Blobs -->
            <div class="absolute top-0 left-1/2 -translate-x-1/2 w-full max-w-4xl h-96 bg-blue-200 rounded-full blur-[120px] opacity-20 -z-10"></div>
        </section>

        <!-- Features Section -->
        <section id="features" class="py-32 bg-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center max-w-3xl mx-auto mb-24">
                    <h2 class="text-blue-600 font-bold tracking-widest uppercase text-sm mb-4">Fitur Unggulan</h2>
                    <p class="text-4xl font-extrabold tracking-tight text-gray-900 sm:text-5xl">Semua yang Anda butuhkan untuk sukses finansial</p>
                    <p class="mt-6 text-xl text-gray-500">Berhenti bertanya-tanya ke mana uang Anda pergi dan mulailah mengaturnya.</p>
                </div>

                <div class="grid grid-cols-1 gap-10 md:grid-cols-3">
                    <!-- Feature 1 -->
                    <div class="group p-10 rounded-[2.5rem] bg-gray-50 hover:bg-white hover:shadow-2xl hover:shadow-blue-100 transition-all duration-500 border border-transparent hover:border-blue-100">
                        <div class="w-16 h-16 bg-blue-600 text-white rounded-2xl flex items-center justify-center mb-8 transform group-hover:scale-110 group-hover:rotate-6 transition-all shadow-lg shadow-blue-200">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 1.343-3 3s1.343 3 3 3 3-1.343 3-3-1.343-3-3-3zM12 2C6.477 2 2 6.477 2 12s4.477 10 10 10 10-4.477 10-10S17.523 2 12 2z" />
                            </svg>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-900 mb-4">Pelacakan Instan</h3>
                        <p class="text-gray-500 leading-relaxed text-lg">Catat transaksi dalam satu ketukan. Kategorisasi otomatis membantu Anda melihat pengeluaran setiap bulan dengan jelas.</p>
                    </div>

                    <!-- Feature 2 -->
                    <div class="group p-10 rounded-[2.5rem] bg-gray-50 hover:bg-white hover:shadow-2xl hover:shadow-indigo-100 transition-all duration-500 border border-transparent hover:border-indigo-100">
                        <div class="w-16 h-16 bg-indigo-600 text-white rounded-2xl flex items-center justify-center mb-8 transform group-hover:scale-110 group-hover:rotate-6 transition-all shadow-lg shadow-indigo-200">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                            </svg>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-900 mb-4">Anggaran Cerdas</h3>
                        <p class="text-gray-500 leading-relaxed text-lg">Atur batas pengeluaran yang realistis. Sistem kami membantu memprediksi pengeluaran dan memberi peringatan sebelum Anda boros.</p>
                    </div>

                    <!-- Feature 3 -->
                    <div class="group p-10 rounded-[2.5rem] bg-gray-50 hover:bg-white hover:shadow-2xl hover:shadow-purple-100 transition-all duration-500 border border-transparent hover:border-purple-100">
                        <div class="w-16 h-16 bg-purple-600 text-white rounded-2xl flex items-center justify-center mb-8 transform group-hover:scale-110 group-hover:rotate-6 transition-all shadow-lg shadow-purple-200">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                            </svg>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-900 mb-4">Keamanan Standar Bank</h3>
                        <p class="text-gray-500 leading-relaxed text-lg">Data Anda dienkripsi dan bersifat privat. Kami menggunakan standar keamanan terbaru untuk memastikan info finansial Anda tetap aman.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Stats Section -->
        <section class="py-24 bg-blue-900 relative overflow-hidden">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
                <div class="grid grid-cols-2 md:grid-cols-4 gap-12 text-center">
                    <div>
                        <div class="text-5xl font-extrabold text-white mb-2">$50Jt+</div>
                        <div class="text-blue-300 font-semibold uppercase tracking-widest text-xs">Dilacak Setiap Tahun</div>
                    </div>
                    <div>
                        <div class="text-5xl font-extrabold text-white mb-2">1Rb+</div>
                        <div class="text-blue-300 font-semibold uppercase tracking-widest text-xs">Penabung Aktif</div>
                    </div>
                    <div>
                        <div class="text-5xl font-extrabold text-white mb-2">4.5/5</div>
                        <div class="text-blue-300 font-semibold uppercase tracking-widest text-xs">Rating Aplikasi</div>
                    </div>
                    <div>
                        <div class="text-5xl font-extrabold text-white mb-2">100%</div>
                        <div class="text-blue-300 font-semibold uppercase tracking-widest text-xs">Privasi Data</div>
                    </div>
                </div>
            </div>
            <!-- Background Decoration -->
            <div class="absolute top-0 left-0 w-full h-full opacity-10">
                <svg class="h-full w-full" fill="none" viewBox="0 0 400 400" xmlns="http://www.w3.org/2000/svg">
                    <path d="M0 400V0H400V400H0Z" fill="url(#grid)"/>
                    <defs>
                        <pattern id="grid" width="40" height="40" patternUnits="userSpaceOnUse">
                            <path d="M 40 0 L 0 0 0 40" fill="none" stroke="white" stroke-width="1"/>
                        </pattern>
                    </defs>
                </svg>
            </div>
        </section>

        <!-- CTA Section -->
        <section class="py-32">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="bg-gradient-to-br from-blue-600 to-indigo-700 rounded-[3rem] p-12 md:p-24 text-center relative overflow-hidden shadow-2xl shadow-blue-200">
                    <div class="relative z-10">
                        <h2 class="text-4xl font-extrabold text-white sm:text-6xl mb-8">Siap mengubah hidup finansial Anda?</h2>
                        <p class="text-blue-50 text-xl md:text-2xl max-w-2xl mx-auto leading-relaxed mb-12 opacity-90">
                            Bergabunglah dengan ribuan orang yang menggunakan MoneyMate untuk mengendalikan pengeluaran dan menabung lebih banyak setiap hari.
                        </p>
                        <div class="flex flex-col sm:flex-row justify-center gap-6">
                            <a href="{{ route('register') }}" class="px-12 py-6 bg-white text-blue-700 font-bold text-xl rounded-2xl hover:bg-blue-50 transition-all shadow-xl hover:-translate-y-1">
                                Buat Akun Gratis Sekarang
                            </a>
                            <a href="{{ route('login') }}" class="px-12 py-6 bg-blue-500/20 text-white font-bold text-xl rounded-2xl border-2 border-white/30 hover:bg-blue-500/30 transition-all backdrop-blur-sm">
                                Masuk ke Akun
                            </a>
                        </div>
                    </div>
                    <!-- Decorative Circles -->
                    <div class="absolute top-0 right-0 -mr-32 -mt-32 w-96 h-96 bg-white/10 rounded-full blur-3xl"></div>
                    <div class="absolute bottom-0 left-0 -ml-32 -mb-32 w-96 h-96 bg-black/10 rounded-full blur-3xl"></div>
                </div>
            </div>
        </section>
    </main>
    <!-- Footer Section -->
    <footer class="bg-gray-50 border-t border-gray-200 pt-16 pb-12 mt-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">        
            <div class="border-t border-gray-200 pt-8 flex flex-col md:flex-row justify-between items-center gap-4">
                <p class="text-gray-400 text-sm">
                    &copy; {{ date('Y') }} MoneyMate. Hak Cipta Dilindungi.
                </p>
                <div class="flex items-center gap-6">
                    <a href="{{ url('/sitemap.xml') }}" target="_blank" class="text-gray-400 hover:text-gray-600 text-xs font-medium uppercase tracking-widest transition-colors flex items-center gap-1">
                        Sitemap XML
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" /></svg>
                    </a>
                </div>
            </div>
        </div>
    </footer>


    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const btn = document.getElementById('mobile-menu-button');
            const menu = document.getElementById('mobile-menu');
            
            btn.addEventListener('click', () => {
                menu.classList.toggle('hidden');
            });

            // Tutup menu saat klik di luar
            document.addEventListener('click', (e) => {
                if (!btn.contains(e.target) && !menu.contains(e.target)) {
                    menu.classList.add('hidden');
                }
            });
            
            // Scroll halus untuk anchor
            document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                anchor.addEventListener('click', function (e) {
                    e.preventDefault();
                    menu.classList.add('hidden');
                    const target = document.querySelector(this.getAttribute('href'));
                    if (target) {
                        target.scrollIntoView({
                            behavior: 'smooth'
                        });
                    }
                });
            });
        });
    </script>
</body>
</html>

<!doctype html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <title>{{ $title ?? 'MoneyMate - Aplikasi Pencatat Keuangan Pribadi Terpercaya' }}</title>
    <meta name="description"
        content="{{ $description ?? 'Kelola keuangan, catat pengeluaran bulanan, dan capai target finansialmu dengan mudah dan otomatis menggunakan MoneyMate.' }}" />
    <meta name="keywords"
        content="aplikasi keuangan, pencatat pengeluaran, pengatur uang, money tracker, web app keuangan, manajemen keuangan, MoneyMate" />
    <meta name="author" content="MoneyMate Team" />
    <link rel="canonical" href="{{ url()->current() }}" />

    <meta property="og:type" content="website" />
    <meta property="og:url" content="{{ url()->current() }}" />
    <meta property="og:title" content="{{ $title ?? 'MoneyMate - Aplikasi Pencatat Keuangan Pribadi' }}" />
    <meta property="og:description"
        content="{{ $description ?? 'Kelola keuangan dan catat pengeluaran dengan mudah menggunakan MoneyMate.' }}" />
    <meta property="og:image" content="{{ asset('icon-192.png') }}" />

    <meta name="twitter:card" content="summary" />
    <meta name="twitter:title" content="{{ $title ?? 'MoneyMate - Aplikasi Pencatat Keuangan' }}" />
    <meta name="twitter:description"
        content="{{ $description ?? 'Kelola keuangan dan catat pengeluaran dengan mudah.' }}" />
    <meta name="twitter:image" content="{{ asset('icon-192.png') }}" />

    <script>
        const savedTheme = localStorage.getItem('theme') || 'light';
        document.documentElement.setAttribute('data-bs-theme', savedTheme);
    </script>

    <link rel="manifest" href="{{ asset('manifest.json') }}" />
    <meta name="theme-color" content="#0d6efd" />
    <link rel="apple-touch-icon" href="{{ asset('icon-192.png') }}" />

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet" />

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="{{ asset('style.css') }}" />

    @livewireStyles
</head>

<body>

    @auth
        <nav class="navbar navbar-expand-lg bg-body-tertiary sticky-top mb-4 shadow-sm">
            <div class="container">
                <a class="navbar-brand text-primary fw-bold" href="{{ route('dashboard') }}" wire:navigate>
                    <i class="fa-solid fa-wallet me-2"></i> MoneyMate
                </a>

                <div class="d-flex align-items-center gap-2 order-lg-last">
                    <button id="themeToggle" class="btn btn-sm btn-outline-secondary border-0"><i
                            class="fa-solid fa-moon"></i></button>
                    <button class="navbar-toggler border-0 shadow-none" type="button" data-bs-toggle="collapse"
                        data-bs-target="#navbarNav">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                </div>

                <div class="collapse navbar-collapse mt-3 mt-lg-0" id="navbarNav">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0 gap-1">
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}"
                                href="{{ route('dashboard') }}" wire:navigate><i class="fa-solid fa-chart-pie me-1"></i>
                                Dashboard</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('expenses') ? 'active' : '' }}"
                                href="{{ route('expenses') }}" wire:navigate><i
                                    class="fa-solid fa-money-bill-transfer me-1"></i> Transaksi</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('settings') ? 'active' : '' }}"
                                href="{{ route('settings') }}" wire:navigate><i class="fa-solid fa-gear me-1"></i>
                                Pengaturan</a>
                        </li>
                        @if (auth()->user()->is_admin)
                            <li class="nav-item">
                                <a class="nav-link text-danger {{ request()->routeIs('admin') ? 'active fw-bold' : '' }}"
                                    href="{{ route('admin') }}" wire:navigate><i class="fa-solid fa-users-gear me-1"></i>
                                    Admin Panel</a>
                            </li>
                        @endif
                    </ul>

                    <div class="d-flex align-items-center justify-content-between mt-3 mt-lg-0">
                        <span class="me-3 text-muted"><i class="fa-regular fa-circle-user me-1"></i> <span
                                class="fw-bold text-body">{{ auth()->user()->name }}</span></span>
                        <form action="{{ route('logout') }}" method="POST" class="m-0">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-outline-danger px-3"><i
                                    class="fa-solid fa-right-from-bracket"></i> Keluar</button>
                        </form>
                    </div>

                </div>
            </div>
        </nav>

        <div class="container pb-5">
            @if (session()->has('success'))
                <span id="session-flash" class="d-none">{{ session('success') }}</span>
            @endif

            {{ $slot }}
        </div>
    @else
        {{ $slot }}
    @endauth

    <div id="pwa-install-banner" class="position-fixed bottom-0 start-0 w-100 p-3 bg-white shadow-lg border-top d-none"
        style="z-index: 1050; border-radius: 20px 20px 0 0;">
        <div class="container d-flex align-items-center justify-content-between gap-3">
            <div class="d-flex align-items-center gap-3">
                <img src="{{ asset('icon-192.png') }}" alt="Icon" width="45" height="45"
                    class="rounded-3 shadow-sm border"
                    onerror="this.src='https://via.placeholder.com/45/0d6efd/ffffff?text=MM'">
                <div>
                    <h6 class="fw-bold mb-0 text-body m-0">Install MoneyMate</h6>
                    <span class="small text-muted" style="font-size: 0.75rem;">Akses lebih cepat & tanpa kuota!</span>
                </div>
            </div>
            <div class="d-flex gap-2">
                <button id="pwa-close-btn"
                    class="btn btn-sm btn-light rounded-pill fw-medium px-3 border">Nanti</button>
                <button id="pwa-install-btn"
                    class="btn btn-sm btn-primary rounded-pill fw-bold px-3 shadow-sm">Install</button>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script data-navigate-once>
        // Logika Mode Malam & Toast
        window.formatRibuan = function(input) {
            let v = input.value.replace(/\D/g, "");
            input.value = v ? parseInt(v, 10).toLocaleString("id-ID") : "";
        }

        function showToast(msg, icon = 'success') {
            const isDark = document.documentElement.getAttribute('data-bs-theme') === 'dark';
            Swal.mixin({
                toast: true,
                position: 'bottom-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
                background: isDark ? '#212529' : '#ffffff',
                color: isDark ? '#ffffff' : '#000000'
            }).fire({
                icon: icon,
                title: msg
            });
        }

        document.addEventListener('livewire:navigated', () => {
            const savedTheme = localStorage.getItem('theme') || 'light';
            document.documentElement.setAttribute('data-bs-theme', savedTheme);
            const themeBtn = document.getElementById('themeToggle');
            if (themeBtn) {
                const icon = themeBtn.querySelector('i');
                icon.className = savedTheme === 'dark' ? 'fa-solid fa-sun' : 'fa-solid fa-moon';
                themeBtn.onclick = () => {
                    const next = document.documentElement.getAttribute('data-bs-theme') === 'light' ? 'dark' :
                        'light';
                    document.documentElement.setAttribute('data-bs-theme', next);
                    localStorage.setItem('theme', next);
                    icon.className = next === 'dark' ? 'fa-solid fa-sun' : 'fa-solid fa-moon';
                };
            }
            let flash = document.getElementById('session-flash');
            if (flash && flash.innerText.trim() !== '') {
                showToast(flash.innerText);
                flash.innerText = '';
            }
        });
        window.addEventListener('show-toast', e => showToast(e.detail.message, e.detail.icon || 'success'));

        // --- FITUR: LOGIKA BANNER INSTALL PWA ---
        window.deferredPrompt = window.deferredPrompt || null;

        window.addEventListener('beforeinstallprompt', (e) => {
            e.preventDefault();
            window.deferredPrompt = e;
            const pwaBanner = document.getElementById('pwa-install-banner');
            // Banner hanya muncul di tampilan HP
            if (pwaBanner && window.innerWidth <= 768) {
                pwaBanner.classList.remove('d-none');
            }
        });

        // Menggunakan event delegation agar tetap berfungsi setelah navigasi Livewire
        document.addEventListener('click', async (e) => {
            const installBtn = e.target.closest('#pwa-install-btn');
            const closeBtn = e.target.closest('#pwa-close-btn');

            if (installBtn) {
                const pwaBanner = document.getElementById('pwa-install-banner');
                if (pwaBanner) pwaBanner.classList.add('d-none');
                
                if (window.deferredPrompt) {
                    window.deferredPrompt.prompt();
                    const { outcome } = await window.deferredPrompt.userChoice;
                    console.log(`PWA Install: ${outcome}`);
                    window.deferredPrompt = null;
                }
            }

            if (closeBtn) {
                const pwaBanner = document.getElementById('pwa-install-banner');
                if (pwaBanner) pwaBanner.classList.add('d-none');
            }
        });

        // --- REGISTRASI SW (Murni Caching) ---
        if ("serviceWorker" in navigator) {
            window.addEventListener("load", () => {
                navigator.serviceWorker.register("/sw.js").then(reg => {
                    console.log("Service Worker terdaftar!");
                }).catch(err => console.error("SW Gagal:", err));
            });
        }
    </script>

    @livewireScripts
</body>

</html>

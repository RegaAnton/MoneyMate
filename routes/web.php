<?php

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

Route::middleware('guest')->group(function () {
    Volt::route('/login', 'auth.login')->name('login');
    Volt::route('/register', 'auth.register')->name('register');
});

Route::middleware('auth')->group(function () {
    Route::redirect('/', '/dashboard');

    Volt::route('/dashboard', 'dashboard')->name('dashboard');
    Volt::route('/expenses', 'expenses')->name('expenses');
    Volt::route('/settings', 'settings')->name('settings');
    Volt::route('/admin', 'admin')->name('admin');

    Route::post('/logout', function () {
        auth()->logout();
        session()->invalidate();
        session()->regenerateToken();
        return redirect()->route('login');
    })->name('logout');
});

Route::get('/migrate-old-data', function () {
    // Matikan proteksi kolom database sementara
    \Illuminate\Database\Eloquent\Model::unguard();

    $path = storage_path('app/db.json');
    if (!\Illuminate\Support\Facades\File::exists($path)) {
        return "ERROR: File db.json tidak ditemukan di folder storage/app/. Pastikan file ada di sana!";
    }

    $data = json_decode(\Illuminate\Support\Facades\File::get($path), true);
    $categoriesCount = 0;
    $usersCount = 0;
    $expensesCount = 0;

    // 1. Migrasi Kategori (Jika ada di JSON)
    if (isset($data['categories'])) {
        foreach ($data['categories'] as $cat) {
            \App\Models\Category::updateOrCreate(['id' => $cat['id']], ['name' => $cat['name']]);
            $categoriesCount++;
        }
    }

    // 2. Migrasi User (Sesuai format JSON Anda)
    if (isset($data['users'])) {
        foreach ($data['users'] as $u) {
            \App\Models\User::updateOrCreate(
                ['id' => $u['id']], // Menggunakan ID panjang Anda (1773557902234)
                [
                    'name' => $u['fullName'],
                    'username' => $u['username'],
                    'email' => $u['email'],
                    // PENTING: Langsung masukkan password karena sudah berupa hash
                    'password' => $u['password'],
                    'monthly_budget' => $u['monthlyBudget'] ?? 0,
                    'is_admin' => ($u['username'] === 'admin')
                ]
            );
            $usersCount++;
        }
    }

    // 3. Migrasi Transaksi (Sesuai format JSON Anda)
    if (isset($data['expenses'])) {
        foreach ($data['expenses'] as $exp) {
            \App\Models\Expense::updateOrCreate(
                ['id' => $exp['id']],
                [
                    'user_id' => (int) $exp['userId'], // Konversi string "1773557902234" jadi integer
                    'category_id' => $exp['categoryId'],
                    'amount' => $exp['amount'],
                    'date' => $exp['date'],
                    'note' => $exp['note'] ?? null,
                ]
            );
            $expensesCount++;
        }
    }

    // 4. Reset Auto Increment MySQL (Agar ID baru tidak bentrok dengan ID panjang)
    $tables = ['categories', 'users', 'expenses'];
    foreach ($tables as $table) {
        // Query khusus MySQL untuk menyamakan urutan ID otomatis ke angka terbesar
        $maxId = \Illuminate\Support\Facades\DB::table($table)->max('id') + 1;
        \Illuminate\Support\Facades\DB::statement("ALTER TABLE {$table} AUTO_INCREMENT = {$maxId}");
    }

    \Illuminate\Database\Eloquent\Model::reguard();

    return "<h2>🚀 Migrasi Berhasil!</h2>
            <p>Data lama Anda telah dipindahkan ke MySQL.</p>
            <ul>
                <li>User: $usersCount</li>
                <li>Kategori: $categoriesCount</li>
                <li>Transaksi: $expensesCount</li>
            </ul>
            <a href='/login'>Ke Halaman Login</a>";
});

Route::get('/fix-passwords', function () {
    $path = storage_path('app/db.json');
    if (!\Illuminate\Support\Facades\File::exists($path)) {
        return "File db.json tidak ditemukan.";
    }

    $data = json_decode(\Illuminate\Support\Facades\File::get($path), true);
    $fixedCount = 0;

    if (isset($data['users'])) {
        foreach ($data['users'] as $u) {
            // 1. Ubah awalan Bcrypt Node.js ($2b$) menjadi format standar PHP ($2y$)
            $phpCompatibleHash = str_replace('$2b$', '$2y$', $u['password']);

            // 2. Gunakan DB::table() untuk melakukan bypass fitur "Auto-Hash" bawaan Model Laravel
            \Illuminate\Support\Facades\DB::table('users')
                ->where('id', $u['id'])
                ->update(['password' => $phpCompatibleHash]);

            $fixedCount++;
        }
    }

    return "<h2>🛠️ Perbaikan Password Selesai!</h2>
            <p>$fixedCount password pengguna telah disesuaikan dengan standar keamanan Laravel.</p>
            <a href='/login'>Klik di sini untuk Login</a>";
});

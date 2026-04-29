<?php

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;
use App\Http\Controllers\Auth\ForgotPasswordOtpController;
use Illuminate\Support\Facades\Artisan;

Route::get('/', function () {
    if (auth()->check()) {
        return redirect('/dashboard');
    }
    return view('landing');
});

// Route sementara untuk membersihkan cache di cPanel tanpa CMD
Route::get('/clear-cache', function () {
    Artisan::call('route:clear');
    Artisan::call('view:clear');
    Artisan::call('config:clear');
    return "Cache berhasil dibersihkan! Silakan kembali ke halaman utama.";
});

Route::middleware('guest')->group(function () {
    Volt::route('/login', 'auth.login')->name('login');
    Volt::route('/register', 'auth.register')->name('register');

    Route::get('/forgot-password', [ForgotPasswordOtpController::class, 'showForgotForm'])->name('password.request');
    Route::post('/forgot-password', [ForgotPasswordOtpController::class, 'sendOtp'])->name('password.email');
    Route::get('/verify-otp', [ForgotPasswordOtpController::class, 'showVerifyForm'])->name('password.verify');
    Route::post('/verify-otp', [ForgotPasswordOtpController::class, 'verifyOtp'])->name('password.verify.post');
    Route::get('/reset-password', [ForgotPasswordOtpController::class, 'showResetForm'])->name('password.reset');
    Route::post('/reset-password', [ForgotPasswordOtpController::class, 'resetPassword'])->name('password.update');
});

Route::middleware('auth')->group(function () {

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

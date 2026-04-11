<?php

use Illuminate\Support\Facades\Auth;
use Livewire\Volt\Component;
use Livewire\Attributes\Layout;

new #[Layout('components.layouts.app')] class extends Component {
    public string $username = '';
    public string $password = '';

    public function login()
    {
        $this->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        if (Auth::attempt(['username' => $this->username, 'password' => $this->password])) {
            session()->regenerate();
            return redirect()->route('dashboard');
        }

        $this->addError('username', 'Username atau password salah.');
    }
}; ?>

<div class="auth-wrapper">
    <div class="card auth-card shadow-lg">
        <div class="card-body text-center p-0">
            <div class="auth-icon-wrapper">
                <i class="fa-solid fa-wallet fa-2x"></i>
            </div>
            <h3 class="fw-bold mb-1">Selamat Datang</h3>
            <p class="text-muted mb-4 small">Masuk ke akun MoneyMate Anda</p>

            <form wire:submit.prevent="login">
                <div class="form-floating mb-3">
                    <input type="text" wire:model="username"
                        class="form-control @error('username') is-invalid @enderror" placeholder="Username" />
                    <label><i class="fa-regular fa-user me-2"></i>Username</label>
                </div>
                <div class="form-floating mb-3">
                    <input type="password" wire:model="password"
                        class="form-control @error('username') is-invalid @enderror" placeholder="Password" />
                    <label><i class="fa-solid fa-lock me-2 text-muted"></i>Password</label>
                    @error('username')
                        <span class="text-danger small">{{ $message }}</span>
                    @enderror
                </div>

                <button type="submit" class="btn btn-auth btn-auth-primary w-100 mb-3">
                    <span wire:loading.remove wire:target="login">Masuk Sekarang</span>
                    <span wire:loading wire:target="login"><i class="fa-solid fa-spinner fa-spin"></i>
                        Mengecek...</span>
                </button>
            </form>

            <div class="mb-4">
                <a href="https://wa.me/6281234567890?text=Halo%20Admin,%20saya%20lupa%20password..." target="_blank"
                    class="text-decoration-none small text-muted hover-primary">
                    <i class="fa-brands fa-whatsapp text-success me-1"></i> Lupa Password? Hubungi Admin
                </a>
            </div>

            <div class="text-center border-top pt-3">
                <span class="text-muted small">Belum punya akun?</span>
                <a href="{{ route('register') }}" wire:navigate
                    class="text-primary fw-bold small text-decoration-none ms-1">Daftar di sini</a>
            </div>
        </div>
    </div>
</div>

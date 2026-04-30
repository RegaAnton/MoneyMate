<?php

use Illuminate\Support\Facades\Auth;
use Livewire\Volt\Component;
use Livewire\Attributes\Layout;

new #[Layout('components.layouts.app')] class extends Component {
    public string $loginField = '';
    public string $password = '';

    public function login()
    {
        $this->validate([
            'loginField' => 'required',
            'password' => 'required',
        ]);

        $fieldType = filter_var($this->loginField, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        if (Auth::attempt([$fieldType => $this->loginField, 'password' => $this->password])) {
            session()->regenerate();
            return redirect()->route('dashboard');
        }

        $this->addError('loginField', 'Username/Email atau password salah.');
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
                    <input type="text" wire:model="loginField"
                        class="form-control @error('loginField') is-invalid @enderror" placeholder="Username atau Email" />
                    <label><i class="fa-regular fa-user me-2"></i>Username atau Email</label>
                </div>
                <div class="form-floating mb-3">
                    <input type="password" wire:model="password"
                        class="form-control @error('loginField') is-invalid @enderror" placeholder="Password" />
                    <label><i class="fa-solid fa-lock me-2 text-muted"></i>Password</label>
                    @error('loginField')
                        <span class="text-danger small">{{ $message }}</span>
                    @enderror
                </div>

                <button type="submit" class="btn btn-auth btn-auth-primary w-100 mb-3">
                    <span wire:loading.remove wire:target="login">Masuk Sekarang</span>
                    <span wire:loading wire:target="login"><i class="fa-solid fa-spinner fa-spin"></i>
                        Mengecek...</span>
                </button>
            </form>

            <div class="mb-2">
                <a href="{{ route('password.request') }}" wire:navigate
                    class="text-decoration-none small text-primary fw-bold hover-primary">
                    <i class="fa-solid fa-key me-1"></i> Lupa Password?
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

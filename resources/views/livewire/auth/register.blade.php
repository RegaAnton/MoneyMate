<?php

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Livewire\Volt\Component;
use Livewire\Attributes\Layout;

new #[Layout('components.layouts.app')] class extends Component {
    public string $name = '';
    public string $username = '';
    public string $email = '';
    public string $password = '';

    public function register()
    {
        $this->validate([
            'name' => 'required|min:3',
            'username' => 'required|min:3|unique:users,username',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
        ]);

        $user = User::create([
            'name' => $this->name,
            'username' => $this->username,
            'email' => $this->email,
            'password' => Hash::make($this->password),
            'monthly_budget' => 0,
            'is_admin' => $this->username === 'admin' ? true : false,
        ]);

        Auth::login($user);

        return redirect()->route('dashboard');
    }
}; ?>

<div class="auth-wrapper">
    <div class="card auth-card shadow-lg">
        <div class="card-body text-center p-0">
            <div class="auth-icon-wrapper success-gradient">
                <i class="fa-solid fa-user-plus fa-2x"></i>
            </div>
            <h3 class="fw-bold mb-1">Buat Akun</h3>
            <p class="text-muted mb-4 small">Mulai kelola keuangan Anda hari ini</p>

            <form wire:submit.prevent="register">
                <div class="form-floating mb-3">
                    <input type="text" wire:model="name" class="form-control @error('name') is-invalid @enderror"
                        placeholder="Nama Lengkap" />
                    <label><i class="fa-regular fa-id-card me-2 text-muted"></i>Nama Lengkap</label>
                    @error('name')
                        <span class="text-danger small">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-floating mb-3">
                    <input type="text" wire:model="username"
                        class="form-control @error('username') is-invalid @enderror" placeholder="Username" />
                    <label><i class="fa-regular fa-user me-2 text-muted"></i>Username</label>
                    @error('username')
                        <span class="text-danger small">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-floating mb-3">
                    <input type="email" wire:model="email" class="form-control @error('email') is-invalid @enderror"
                        placeholder="name@example.com" />
                    <label><i class="fa-regular fa-envelope me-2 text-muted"></i>Alamat Email</label>
                    @error('email')
                        <span class="text-danger small">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-floating mb-4">
                    <input type="password" wire:model="password"
                        class="form-control @error('password') is-invalid @enderror" placeholder="Password" />
                    <label><i class="fa-solid fa-lock me-2 text-muted"></i>Buat Password</label>
                    @error('password')
                        <span class="text-danger small">{{ $message }}</span>
                    @enderror
                </div>

                <button type="submit" class="btn btn-auth btn-auth-success w-100 mb-4">
                    <span wire:loading.remove wire:target="register">Daftar Sekarang</span>
                    <span wire:loading wire:target="register"><i class="fa-solid fa-spinner fa-spin"></i>
                        Memproses...</span>
                </button>
            </form>

            <div class="text-center border-top pt-3">
                <span class="text-muted small">Sudah punya akun?</span>
                <a href="{{ route('login') }}" wire:navigate
                    class="text-success fw-bold small text-decoration-none ms-1">Masuk di sini</a>
            </div>
        </div>
    </div>
</div>

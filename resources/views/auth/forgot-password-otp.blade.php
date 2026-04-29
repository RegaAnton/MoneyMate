<x-layouts.app>
    <div class="auth-wrapper">
        <div class="card auth-card shadow-lg">
            <div class="card-body text-center p-0">
                <div class="auth-icon-wrapper">
                    <i class="fa-solid fa-key fa-2x"></i>
                </div>
                <h3 class="fw-bold mb-1">Lupa Password</h3>
                <p class="text-muted mb-4 small">Masukkan email Anda untuk menerima kode OTP</p>

                @if (session('success'))
                    <div class="alert alert-success small mb-3">
                        {{ session('success') }}
                    </div>
                @endif

                <form action="{{ route('password.email') }}" method="POST">
                    @csrf
                    <div class="form-floating mb-3">
                        <input type="email" name="email"
                            class="form-control @error('email') is-invalid @enderror" placeholder="Email" value="{{ old('email') }}" required />
                        <label><i class="fa-regular fa-envelope me-2"></i>Email Address</label>
                        @error('email')
                            <span class="text-danger small">{{ $message }}</span>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-auth btn-auth-primary w-100 mb-3">
                        Kirim Kode OTP
                    </button>
                </form>

                <div class="text-center border-top pt-3">
                    <a href="{{ route('login') }}"
                        class="text-primary fw-bold small text-decoration-none">Kembali ke Login</a>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>

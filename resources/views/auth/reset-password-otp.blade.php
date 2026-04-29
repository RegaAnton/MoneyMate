<x-layouts.app>
    <div class="auth-wrapper">
        <div class="card auth-card shadow-lg">
            <div class="card-body text-center p-0">
                <div class="auth-icon-wrapper">
                    <i class="fa-solid fa-lock-open fa-2x"></i>
                </div>
                <h3 class="fw-bold mb-1">Reset Password</h3>
                <p class="text-muted mb-4 small">Buat password baru untuk akun Anda</p>

                @if (session('success'))
                    <div class="alert alert-success small mb-3">
                        {{ session('success') }}
                    </div>
                @endif

                <form action="{{ route('password.update') }}" method="POST">
                    @csrf
                    <div class="form-floating mb-3">
                        <input type="password" name="password"
                            class="form-control @error('password') is-invalid @enderror" placeholder="Password Baru" required />
                        <label><i class="fa-solid fa-lock me-2"></i>Password Baru</label>
                        @error('password')
                            <span class="text-danger small">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-floating mb-3">
                        <input type="password" name="password_confirmation"
                            class="form-control" placeholder="Konfirmasi Password" required />
                        <label><i class="fa-solid fa-check-double me-2"></i>Konfirmasi Password</label>
                    </div>

                    <button type="submit" class="btn btn-auth btn-auth-primary w-100 mb-3">
                        Update Password
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-layouts.app>

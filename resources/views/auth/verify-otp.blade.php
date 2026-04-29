<x-layouts.app>
    <div class="auth-wrapper">
        <div class="card auth-card shadow-lg">
            <div class="card-body text-center p-0">
                <div class="auth-icon-wrapper">
                    <i class="fa-solid fa-shield-halved fa-2x"></i>
                </div>
                <h3 class="fw-bold mb-1">Verifikasi OTP</h3>
                <p class="text-muted mb-4 small">Masukkan 6 digit kode yang dikirim ke email Anda</p>

                @if (session('success'))
                    <div class="alert alert-success small mb-3">
                        {{ session('success') }}
                    </div>
                @endif

                <form action="{{ route('password.verify.post') }}" method="POST">
                    @csrf
                    <div class="form-floating mb-3">
                        <input type="text" name="otp"
                            class="form-control text-center @error('otp') is-invalid @enderror" 
                            placeholder="6 Digit OTP" maxlength="6" required style="letter-spacing: 10px; font-size: 24px; font-weight: bold;"/>
                        <label>Kode OTP</label>
                        @error('otp')
                            <span class="text-danger small">{{ $message }}</span>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-auth btn-auth-primary w-100 mb-3">
                        Verifikasi Sekarang
                    </button>
                </form>

                <div class="text-center border-top pt-3">
                    <p class="text-muted small mb-0">Tidak menerima kode? 
                        <form action="{{ route('password.email') }}" method="POST" class="d-inline">
                            @csrf
                            <input type="hidden" name="email" value="{{ session('reset_email') }}">
                            <button type="submit" class="btn btn-link p-0 small text-primary fw-bold text-decoration-none" style="vertical-align: baseline;">Kirim Ulang</button>
                        </form>
                    </p>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>

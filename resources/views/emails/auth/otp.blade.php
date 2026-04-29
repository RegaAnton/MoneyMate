<x-mail::message>
# Permintaan Atur Ulang Kata Sandi

Halo,

Kami menerima permintaan untuk mengatur ulang kata sandi akun **MoneyMate** Anda. Keamanan akun Anda adalah prioritas kami. Silakan gunakan kode OTP (One-Time Password) berikut ini untuk melanjutkan proses verifikasi:

<x-mail::panel>
# {{ $otp }}
</x-mail::panel>

**Ketentuan Keamanan:**
- Kode ini hanya berlaku selama **5 menit** sejak email ini dikirimkan.
- Jangan berikan kode ini kepada siapa pun, termasuk pihak yang mengaku dari MoneyMate.
- Kode ini hanya dapat digunakan satu kali.

Jika Anda tidak merasa melakukan permintaan ini, tidak ada tindakan lebih lanjut yang diperlukan. Keamanan akun Anda tidak terpengaruh dan Anda dapat mengabaikan email ini dengan aman.

Terima kasih telah menggunakan layanan kami untuk mengelola keuangan Anda.

Salam hangat,<br>
**Tim {{ config('app.name') }}**
</x-mail::message>

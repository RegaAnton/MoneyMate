<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\PasswordResetOtp;
use App\Models\User;
use App\Mail\SendOtpMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;

class ForgotPasswordOtpController extends Controller
{
    public function showForgotForm()
    {
        return view('auth.forgot-password-otp');
    }

    public function sendOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        $lastOtp = PasswordResetOtp::where('email', $request->email)->first();
        if ($lastOtp && Carbon::parse($lastOtp->created_at)->addMinutes(5)->isFuture()) {
            $diff = Carbon::now()->diff(Carbon::parse($lastOtp->created_at)->addMinutes(5));
            $timeRemaining = $diff->i > 0 ? "$diff->i menit $diff->s detik" : "$diff->s detik";
            
            return back()->withErrors(['email' => "Silakan tunggu $timeRemaining sebelum meminta kode baru."]);
        }

        $otp = rand(100000, 999999);

        PasswordResetOtp::updateOrCreate(
            ['email' => $request->email],
            ['otp' => $otp, 'created_at' => Carbon::now()]
        );

        Mail::to($request->email)->send(new SendOtpMail($otp));

        Session::put('reset_email', $request->email);

        return redirect()->route('password.verify')->with('success', 'Kode OTP telah dikirim ke email Anda.');
    }

    public function showVerifyForm()
    {
        if (!Session::has('reset_email')) {
            return redirect()->route('password.request');
        }
        return view('auth.verify-otp');
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'otp' => 'required|digits:6',
        ]);

        $email = Session::get('reset_email');
        $otpData = PasswordResetOtp::where('email', $email)
            ->where('otp', $request->otp)
            ->first();

        if (!$otpData || Carbon::parse($otpData->created_at)->addMinutes(5)->isPast()) {
            return back()->withErrors(['otp' => 'Kode OTP tidak valid atau sudah kedaluwarsa.']);
        }

        Session::put('otp_verified_email', $email);
        
        return redirect()->route('password.reset')->with('success', 'OTP berhasil diverifikasi. Silakan ubah password Anda.');
    }

    public function showResetForm()
    {
        if (!Session::has('otp_verified_email')) {
            return redirect()->route('password.verify');
        }
        return view('auth.reset-password-otp');
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'password' => 'required|min:8|confirmed',
        ]);

        $email = Session::get('otp_verified_email');
        $user = User::where('email', $email)->first();

        if (!$user) {
            return redirect()->route('password.request')->withErrors(['email' => 'User tidak ditemukan.']);
        }

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        PasswordResetOtp::where('email', $email)->delete();
        Session::forget(['reset_email', 'otp_verified_email']);

        return redirect()->route('login')->with('success', 'Password berhasil diubah. Silakan login dengan password baru Anda.');
    }
}

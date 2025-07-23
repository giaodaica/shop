<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Laravel\Socialite\Facades\Socialite;

class GoogleController extends Controller
{
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();

            $user = User::where('email', $googleUser->getEmail())->first();

            if (!$user) {
                $user = User::create([
                    'name' => $googleUser->getName(),
                    'email' => $googleUser->getEmail(),
                    'google_id' => $googleUser->getId(),
                    'password' => bcrypt('google_' . $googleUser->getId()),
                    'email_verified_at' => null,
                ]);
            }
            if ($user->status === 'inactive') {
                return redirect()->route('login')->with('error', 'Tài khoản của bạn đã bị khóa. Vui lòng liên hệ quản trị viên.');
            }
            Auth::login($user);

            if (!$user->hasVerifiedEmail()) {
                $user->sendEmailVerificationNotification();
                return redirect()->route('verification.notice');
            }
            return redirect()->route('home');
        } catch (\Exception $e) {
            Log::error('Google login failed: ' . $e->getMessage());
            return redirect()->route('login')->with('error', 'Đăng nhập Google thất bại!');
        }
    }
}

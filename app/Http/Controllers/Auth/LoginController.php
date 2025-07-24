<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');
    }
    protected function sendFailedLoginResponse(Request $request)
    {
        throw ValidationException::withMessages([
            $this->username() => ['Email hoặc mật khẩu không chính xác'],
        ]);
    }
    protected function validateLogin(Request $request)
    {
        $this->validate($request, [
            $this->username() => 'required|email',  // Email không được bỏ trống và phải là định dạng email
            'password' => 'required|string|min:8',  // Mật khẩu không được bỏ trống và phải có ít nhất 8 ký tự
        ], [
            'required' => ':attribute không được bỏ trống.',
            'email' => 'Email không hợp lệ.',
            'password.min' => 'Mật khẩu phải có ít nhất 8 ký tự.',
        ]);
    }
    protected function authenticated(Request $request, $user)
    {
        if ($user->status == 'inactive') {
            Auth::logout(); // Đăng xuất user ngay sau khi login
            return redirect()->route('login')->with('error', 'Tài khoản của bạn đã bị khóa. Vui lòng liên hệ quản trị viên.');
        }

        // Nếu không bị khoá thì vẫn redirect như bình thường
        return redirect()->intended($this->redirectPath());
    }
}

<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use App\Models\amount;

class LoginController extends Controller
{
    //

    protected $redirectTo = '/home';

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }


    protected function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            // Add validation rules for name, email, password, etc.
            'email' => ['required', 'string', 'email', 'max:255'],
            'password' => ['required'],
            'captcha' => ['required', 'numeric'],
        ]);
        if($validator->fails()){
            return redirect('login')
                ->withErrors($validator)
                ->withInput();
        }

        if ($request->captcha != session('captcha_answer')) {
            throw ValidationException::withMessages(['captcha' => 'Captcha validation failed.']);
        }

        if(Auth::attempt(['email' => $request->email, 'password' => $request->password])){
            return redirect()->route('home');
        }

        return $this->sendFailedLoginResponse($request);
    }

    protected function sendFailedLoginResponse(Request $request)
    {
        throw ValidationException::withMessages([
            $this->username() => [trans('auth.failed')],
            'captcha' => ['Captcha validation failed.'],
        ]);
    }

    protected function getCaptchaResult(Request $request)
    {
        $result = eval('return '.$request->input('num1').$request->input('operator').$request->input('num2').';');
        return $result;
    }

    public function username()
    {
        return 'email';
    }

    public function logout(Request $request)
    {
        // $this->guard()->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('login');
    }
}

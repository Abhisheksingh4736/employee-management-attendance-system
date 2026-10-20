<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class WebAuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (!Auth::attempt($credentials, $request->boolean('remember'))) {
            throw ValidationException::withMessages([
                'email' => 'Invalid email or password.',
            ]);
        }

        $request->session()->regenerate();

       $user = Auth::user();

if ($user->role === Role::EMPLOYEE && $user->employee?->status !== EmployeeStatus::Active) {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return back()->withErrors(['email' => 'Your account is inactive. Contact the administrator.'])->onlyInput('email');
}

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        abort(403, 'Invalid user role.');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
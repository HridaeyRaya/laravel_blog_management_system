<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // Show register form
    public function showRegister()
    {
        return view('auth.register');
    }

    // Handle register
    public function register(RegisterRequest $request)
    {
        $validated = $request->validated();

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // Assign the 'user' role automatically
        $userRole = Role::where('name', 'user')->first();
        if ($userRole) {
            $user->roles()->attach($userRole);
        }

        Auth::login($user);

        return redirect()->route('posts.index')->with('success', 'Welcome!');
    }

    // Show login form
    public function showLogin()
    {
        return view('auth.login');
    }

//    // Handle login
//    public function login(LoginRequest $request)
//    {
//        $validated = $request->validated();
//
//        if (Auth::attempt($request->only('email', 'password'), $request->boolean('remember'))) {
//            $request->session()->regenerate();
//            return redirect()->intended(route('posts.index'));
//        }
//
//        return back()->withErrors([
//            'email' => 'These credentials do not match our records.',
//        ]);
//    }

// Handle login
    public function login(LoginRequest $request)
    {
        $validated = $request->validated();

        if (Auth::attempt($request->only('email', 'password'), $request->boolean('remember'))) {
            $user = Auth::user();

            // Check if account is suspended
            if (!$user->is_active) {
                Auth::logout(); // immediately log out the suspended user
                return back()->withErrors([
                    'email' => 'Your account has been suspended. Please contact support.',
                ])->onlyInput('email');
            }

            $request->session()->regenerate();
            return redirect()->intended(route('posts.index'));
        }

        return back()->withErrors([
            'email' => 'These credentials do not match our records.',
        ])->onlyInput('email');
    }

    // Handle logout
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }
}


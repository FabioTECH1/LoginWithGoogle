<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Laravel\Socialite\Facades\Socialite;

class LoginController extends Controller
{
    public function index()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $user = $request->validate([
            'email' => 'required|email|max:255',
            'password' => 'required|string',
        ]);
        $user_with_googlelogin = User::where('email', $user['email'])->whereNotNull('google_id')->first();
        if ($user_with_googlelogin) {
            return redirect()->route('login')->with(
                'error',
                "user_with_google"
            );
        }
        if (Auth::attempt(['email' => $user['email'], 'password' => $user['password']])) {
            return redirect()->route('home');
        } else {
            return redirect()->route('login')->with('error',  'Incorrect Login Credentials');
        }
    }

    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            $user = Socialite::driver('google')->user();
            if (!$user) {
                return redirect()->route('register')->with('error',  'Unauthorized');
            }
            $user = User::firstOrCreate([
                'email' => $user->email
            ], [
                'name' => $user->name,
                'google_id' => $user->id,
                'email_verified_at' => now()
            ]);
        } catch (\Throwable $th) {
            Log::error($th);
            return redirect()->route('register')->with('error',  'Oops, something went wrong');
        }
        Auth::login($user);
        return redirect()->route('home');
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('login.index');
    }
}

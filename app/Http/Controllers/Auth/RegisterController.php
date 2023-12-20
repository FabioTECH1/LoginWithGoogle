<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RegisterController extends Controller
{
    public function index()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $user = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users|max:255',
            'password' => 'required|string|min:8|confirmed',
        ]);
        User::create($user);
        if (Auth::attempt(['email' => $user['email'], 'password' => $user['password']])) {
            return redirect()->route('home');
        }
        return redirect()->route('register')->with('error',  'Oops, something went wrong');
    }
}

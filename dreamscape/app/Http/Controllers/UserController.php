<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules;

class UserController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'username' => 'required|string|unique:users',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'username' => $request->username,
            'password' => Hash::make($request->password),
        ]);

        // Optionally, log the user in after registration
        Auth::login($user);

        return redirect()->intended();
    }

     public function login(Request $request)
    {
        $credentials = $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended();
        }

        return back()->withErrors([
            'username' => 'The provided credentials do not match our records.',
        ])->onlyInput('username');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }

    public function show(User $user)
    {
        // Show user profile.  Includes inventory.
        $user->load('inventory.item'); // Efficiently load inventory with item details

        if(request()->expectsJson()){
            return response()->json($user);
        }
        return view('user.show', compact('user'));

    }

    public function update(Request $request, User $user)
    {
        // Update user profile (e.g., allow changing username or password)
        $request->validate([
            'username' => 'sometimes|required|string|unique:users,username,' . $user->id, // Unique, except for current user
            'password' => 'sometimes|required|string|min:6',
        ]);

        if ($request->has('username')) {
            $user->username = $request->username;
        }
        if ($request->has('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();
        return response()->json(['message' => 'Profile updated successfully', 'user' => $user]);
    }
}

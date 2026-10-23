<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Asegurarse de que el usuario esté activo
        $credentials['estado'] = 'Activo';

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            // Redirigir según el rol del usuario
            $user = Auth::user();
            if ($user->id_rol == 1) {
                return redirect()->intended('admin/dashboard');
            } elseif ($user->id_rol == 2) {
                return redirect()->intended('vendedor/dashboard');
            } else {
                return redirect()->intended('cliente/dashboard');
            }
        }

        return back()->withErrors([
            'email' => 'Las credenciales proporcionadas no coinciden con nuestros registros o la cuenta está inactiva.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}

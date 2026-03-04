<?php

namespace App\Http\Controllers;

use App\Models\User; 
use App\Models\Perfil;
use App\Models\Rol;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;


class UsuarioController extends Controller
{
    // Mostrar el formulario de registro
    public function create()
    {
        return view('auth.register'); // tu vista de registro
    }

    // Guardar el usuario
    public function store(Request $request)
    {
        $request->validate([
            'nombres' => 'required',
            'apellidos' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed',
            'genero' => 'required',
            'fecha_nacimiento' => 'required',
            'pais' => 'required',
            'ciudad' => 'required'
        ]);

        DB::transaction(function () use ($request) {

            $usuario = User::create([
                'name' => $request->nombres . ' ' . $request->apellidos,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            Perfil::create([
                'user_id' => $usuario->id,
                'nombres' => $request->nombres,
                'apellidos' => $request->apellidos,
                'genero' => $request->genero,
                'fecha_nacimiento' => $request->fecha_nacimiento,
                'pais' => $request->pais,
                'ciudad' => $request->ciudad,
                'fecha_actualizacion' => now()
            ]);

            Rol::create([
                'user_id' => $usuario->id,
                'rol' => 'comprador',
                'estado' => true,
                'fecha_asignacion' => now()
            ]);

        });

        return redirect()->route('login')->with('success', 'Usuario registrado correctamente');
    }

    // MOSTRAR FORMULARIO
    public function form()
    {
        return view('auth.login');
    }

    // PROCESAR LOGIN
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        // INTENTAR AUTENTICACIÓN
        if (Auth::attempt([
            'email' => $request->email,
            'password' => $request->password
        ])) {

            // Regenerar sesión (seguridad)
            $request->session()->regenerate();

            return redirect()->route('index')
                ->with('success', 'Bienvenido ' . Auth::user()->name);
        }

        return back()->withErrors([
            'email' => 'Correo o contraseña incorrectos'
        ])->withInput();
    }

    // CERRAR SESIÓN
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
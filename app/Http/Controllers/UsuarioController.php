<?php

namespace App\Http\Controllers;

use App\Models\User; 
use App\Models\Perfil;
use App\Models\Rol;
use App\Services\UserService;
use Illuminate\Auth\Events\Registered;
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

        $usuario = DB::transaction(function () use ($request) {

            $usuario = User::create([
                'name' => $request->nombres . ' ' . $request->apellidos,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            UserService::crearPerfilYRol($usuario, $request->all());

            return $usuario;
        });

        event(new Registered($usuario));

        Auth::login($usuario);

        return redirect()->route('verification.notice');

        // return redirect()->route('index')->with('success', 'Usuario registrado correctamente');
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

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials, $request->boolean('remember'))) {

            // regenerar sesión (seguridad)
            $request->session()->regenerate();

            return redirect()->route('index')->with('success', 'Bienvenido de nuevo ' . (Auth::user()->perfil->nombres ?? Auth::user()->name));
        }

        return back()->withErrors(['email' => 'Correo o contraseña incorrectos'])->withInput();
    }

    // CERRAR SESIÓN
    public function logout(Request $request)
    {
        $user = Auth::user();

        if ($user) {
            $user->remember_token = null;
            $user->save();
        }

        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
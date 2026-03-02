<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use App\Models\Perfil;
use App\Models\Rol;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
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
            'correo' => 'required|email|unique:usuarios,correo',
            'password' => 'required|min:6|confirmed',
            'genero' => 'required',
            'fecha_nacimiento' => 'required',
            'pais' => 'required',
            'ciudad' => 'required'
        ]);

        DB::transaction(function () use ($request) {

            $usuario = Usuario::create([
                'correo' => $request->correo,
                'contrasena_hash' => Hash::make($request->password),
                'estado' => 'pendiente',
                'fecha_registro' => now(),
                'ultimo_acceso' => null
            ]);

            Perfil::create([
                'usuario_id' => $usuario->id,
                'nombres' => $request->nombres,
                'apellidos' => $request->apellidos,
                'pais' => $request->pais,
                'ciudad' => $request->ciudad,
                'fecha_actualizacion' => now()
            ]);

            Rol::create([
                'usuario_id' => $usuario->id,
                'rol' => 'comprador',
                'estado' => 'activo',
                'fecha_asignacion' => now()
            ]);

        });

        return back()->with('success', 'Usuario registrado correctamente');
    }

    // Mostrar formulario
    public function form()
    {
        return view('auth.login');
    }

    // Procesar login
    public function login(Request $request)
    {
        $request->validate([
            'correo' => 'required|email',
            'password' => 'required'
        ]);

        $usuario = Usuario::where('correo', $request->correo)->first();

        if (!$usuario || !Hash::check($request->password, $usuario->contrasena_hash)) {
            return back()->withErrors(['correo' => 'Correo o contraseña incorrectos']);
        }

        // Guardar sesión manualmente
        session(['usuario_id' => $usuario->id, 'usuario_correo' => $usuario->correo]);

        // Redirigir a página de inicio
        return redirect()->route('index')->with('success', 'Bienvenido ' . $usuario->correo);
    }

    // Cerrar sesión
    public function logout()
    {
        session()->flush();
        return redirect()->route('login');
    }
}
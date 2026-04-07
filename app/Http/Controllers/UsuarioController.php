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
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

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

            $user = Auth::user();

            if (!$user->hasVerifiedEmail()) {
                $user->sendEmailVerificationNotification();
            }

            // regenerar sesión (seguridad)
            $request->session()->regenerate();

            return redirect()->route('index')->with('success', 'Bienvenido de nuevo ' . (Auth::user()->name ?? Auth::user()->perfil->nombres));
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

    // PERFIL DE USUARIO
    public function perfil()
    {
        $user = Auth::user()->load('roles', 'perfil');;

        return view('auth.perfil', compact('user'));
    }

    // EDITAR PERFIL DE USUARIO
    public function editar(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'bio' => 'nullable',
            'usuario' => 'required',
            'nombres' => 'required',
            'apellidos' => 'required',
            'genero' => 'required',
            'fecha_nacimiento' => 'required|date',
            'pais' => 'required',
            'ciudad' => 'required',
            'email' => [
                'required',
                'email',
                Rule::unique('users','email')->ignore($user->id)
            ],
            'telefono' => 'nullable|digits_between:7,15',
            'avatar' => 'nullable|image|mimes:jpg,jpeg,png,webp,gif|max:2048',
            'web' => 'nullable|url',
            'facebook' => 'nullable|url',
            'instagram' => 'nullable|url',
            'x' => 'nullable|url',
        ]);

        DB::transaction(function () use ($request, $user) {

            $user->update([
                'name' => $request->usuario,
                'email' => $request->email,
            ]);

            $perfil = $user->perfil;

            if ($request->hasFile('avatar')) {

                if ($user->avatar && !str_contains($user->avatar, 'googleusercontent')) {
                    Storage::disk('public')->delete($user->avatar);
                }

                $ruta = $request->file('avatar')->store('avatars', 'public');

                $user->avatar = $ruta;
                $user->save();
            }

            $perfil->update([
                'bio' => $request->bio,
                'nombres' => $request->nombres,
                'apellidos' => $request->apellidos,
                'genero' => $request->genero,
                'fecha_nacimiento' => $request->fecha_nacimiento,
                'pais' => $request->pais,
                'ciudad' => $request->ciudad,
                'telefono' => $request->telefono,
                'web' => $request->web,
                'facebook' => $request->facebook,
                'instagram' => $request->instagram,
                'x' => $request->x,
                'avatar' => $perfil->avatar ?? null, 
            ]);

        });

        return back()->with('success', 'Perfil actualizado correctamente');

    }
    
}
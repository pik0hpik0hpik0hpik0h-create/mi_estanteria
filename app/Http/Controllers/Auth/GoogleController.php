<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\UserService;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class GoogleController extends Controller
{

    public function redirect()
    {
        return Socialite::driver('google')->redirect();
    }

    public function callback()
    {
        $googleUser = Socialite::driver('google')->stateless()->user();

        // buscar por google_id
        $user = User::where('google_id', $googleUser->getId())->first();

        // si no existe buscar por email
        if (!$user) {
            $user = User::where('email', $googleUser->getEmail())->first();
        }

        // si NO existe crear
        if (!$user) {

            $user = User::create([
                'name' => $googleUser->getName(),
                'email' => $googleUser->getEmail(),
                'google_id' => $googleUser->getId(),
                'avatar' => $googleUser->getAvatar(),
                'email_verified_at' => now(),
                'password' => bcrypt(uniqid()),
            ]);

        } else {

            $updateData = [
                'avatar' => $googleUser->getAvatar(),
            ];

            if (!$user->google_id) {
                $updateData['google_id'] = $googleUser->getId();
            }

            if (!$user->email_verified_at) {
                $updateData['email_verified_at'] = now();
            }

            $user->update($updateData);
        }

        // crear perfil si no existe
        if (!$user->perfil()->exists()) {
            UserService::crearPerfilYRol($user, [
                'nombres' => $googleUser->getName(),
            ]);
        }

        // login
        Auth::login($user, true);

        return redirect('/');
    }
}
@extends('layouts.app')

@section('content')

<div class="flex flex-col min-h-screen motion-preset-blur-right bg-cover bg-center "
    style="background-image: url('{{ asset('assets/img/verify_email_bg.png') }}');">

    <div class="flex justify-between items-center p-5 motion-preset-slide-right">

        <form method="POST" action="{{ route('logout_not_verified') }}">
            @csrf
                <button class="btn btn-text font-inconsolata btn-sm" type="submit">
                    <- Salir
                </button>
        </form>

        <img src="{{ asset('assets/img/logo_navbar.png') }}" class="w-15" alt="Mi Estantería">
    </div>

    <div class="flex-1 flex flex-col items-center justify-center gap-y-10 mx-10 motion-preset-slide-right">

        <h2 class="font-serif text-center text-3xl">¡ Verifica tu correo !</h2>

        <p class="font-inconsolata text-center">
        Te hemos enviado un enlace de verificación a tu correo electrónico.
        Por favor revisa tu bandeja de entrada.
        </p>

        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <button class="btn btn-primary font-inconsolata" type="submit">
                Reenviar correo
            </button>
        </form>

    </div>

</div>

@endsection
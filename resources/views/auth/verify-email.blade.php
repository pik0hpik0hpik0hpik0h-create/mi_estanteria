@extends('layouts.app')

@section('content')

<div class="flex flex-col min-h-screen motion-preset-slide-right bg-cover bg-center"
    style="background-image: url('{{ asset('assets/img/verify_email_bg.png') }}');">

    <div class="flex justify-between items-center p-5">
        <a href="{{ route('index') }}" class="btn btn-text font-inconsolata btn-sm"><- Regresar</a>
        <img src="{{ asset('assets/img/logo_navbar.png') }}" class="w-15" alt="Mi Estantería">
    </div>

    <div class="flex-1 flex flex-col items-center justify-center gap-y-10 mx-10">

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
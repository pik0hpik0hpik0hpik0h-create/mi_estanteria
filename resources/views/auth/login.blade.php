@extends('layouts.app')

@section('content')

 <div class="flex">

        <div class="bg-cover bg-center w-1/2 md:block hidden m-3 rounded-4xl motion-preset-blur-right"
            style="background-image: url('{{ asset('assets/img/login_page.jpg') }}');">
            
        </div>

        <div class="md:w-1/2 w-full flex flex-col min-h-screen rounded-4xl motion-preset-slide-right">

            <div class="flex justify-between p-5">
                <a href="{{ route('index') }}" class="btn btn-text font-inconsolata btn-sm"><- Regresar</a>
                <img src="{{ asset('assets/img/logo_navbar.png') }}" class="w-15" alt="Mi Estantería">
            </div>

            <div class="flex-1 flex flex-col items-center justify-center gap-y-8 mx-10">

                <div class="text-center">
                    <h1 class="font-serif text-2xl">¡ Bienvenido de Vuelta !</h1>
                    <h2 class="font-inconsolata text-sm">Vuelve a conectar con historias que inspiran y compártelas</h2>
                </div>

                <form novalidate autocomplete="off" method="POST" action="{{ route('login.submit') }}" class="text-center font-inconsolata w-75 md:w-85">
                    @csrf

                    <div class="input-floating w-full max-w-md">
                        <input type="text" placeholder="Ingrese correo" class="input" id="correo" name="correo"/>
                        <label class="input-floating-label" for="correo">Correo Electrónico</label>
                    </div>

                    <div class="mt-4">

                        <div class="input">

                            <div class="input-floating w-full max-w-md">
                                <input id="toggle-password-floating" type="password" placeholder="Ingrese contraseña" name="password"/>
                                <label class="input-floating-label ms-0" for="toggle-password-floating">
                                    Contraseña
                                </label>
                            </div>

                            <button type="button" data-toggle-password='{ "target": "#toggle-password-floating" }' class="block cursor-pointer" aria-label="password toggle">
                                <span class="icon-[tabler--eye] text-base-content/80 password-active:block hidden size-5 shrink-0"></span>
                                <span class="icon-[tabler--eye-off] text-base-content/80 password-active:hidden block size-5 shrink-0"></span>
                            </button>

                        </div>

                        <div class="mt-3 flex justify-between">

                            <div class="flex items-center gap-2">
                                <input type="checkbox" class="checkbox checkbox-primary checkbox-sm" id="recuerdame" />
                                <label class="label-text text-base-content/80 p-0 text-sm" for="recuerdame">Recuerdame</label>
                            </div>

                            <a href="#" class="link link-animated link-primary text-sm">
                                Olvidé mi contraseña
                            </a>

                        </div>

                    </div>

                    

                    <div class="w-full max-w-md mt-8">
                        <button class="btn btn-primary w-full">Ingresar</button>
                    </div>

                    <div class="divider mt-8">Otros Métodos</div>

                    

                </form>

                <div class="w-75 md:w-85 flex flex-row justify-between px-10">
                    <button class="btn btn-text hover:bg-primary/20 active:bg-primary/40">
                        <img src="https://cdn.flyonui.com/fy-assets/blocks/marketing-ui/brand-logo/google-icon.png" alt="google icon" class="size-4 object-cover" />
                    </button>

                    <button class="btn btn-text hover:bg-primary/20 active:bg-primary/40">
                        <img src="https://cdn.flyonui.com/fy-assets/blocks/marketing-ui/brand-logo/facebook-icon.png" alt="google icon" class="size-4 object-cover" />
                    </button>

                    <button class="btn btn-text hover:bg-primary/20 active:bg-primary/40">
                        <img src="https://cdn.flyonui.com/fy-assets/blocks/marketing-ui/brand-logo/twitter-icon.png" alt="google icon" class="size-4 object-cover" />
                    </button>
                </div>

                <a href="{{ route('register.create') }}" class="font-inconsolata link link-animated link-primary motion-preset-blink motion-duration-2000">¿Sin cuenta?, registrate ahora</a>

            </div>

        </div>

    </div>

    @endsection
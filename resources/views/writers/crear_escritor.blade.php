@extends('layouts.app')

@section('content')

<div class="flex">

    <!-- Imagen -->
    <div class="bg-cover bg-center w-1/2 md:block hidden m-3 rounded-4xl motion-preset-blur-right"
        style="background-image: url('{{ asset('assets/img/serious-young-woman-designer-sitting-indoors-night-writing-notes.jpg') }}');">
    </div>

    <!-- Wizard -->
    <div class="md:w-1/2 w-full flex flex-col min-h-screen rounded-4xl motion-preset-slide-right">

        <!-- Header -->
        <div class="flex justify-between items-center p-5">
            <a href="{{ route('perfil') }}" class="btn btn-text font-inconsolata btn-sm"><- Regresar</a>
            <img src="{{ asset('assets/img/logo_navbar.png') }}" class="w-15">
        </div>

        <div class="flex-1 flex flex-col items-center justify-center gap-y-6 mx-10">

            <!-- TITULO -->
            <div class="text-center">
                <h1 class="font-serif text-2xl">Conviértete en Escritor</h1>
                <h2 class="font-inconsolata text-sm">
                    Comparte tus historias y comienza a generar ingresos
                </h2>
            </div>

            <!-- STEPPER -->
            <div data-stepper class="w-75 md:w-85 font-inconsolata">

                <!-- NAV -->
                <ul class="flex justify-between mb-6">

                    <!-- Paso 1 -->
                    <li class="flex flex-col items-center flex-1" data-stepper-nav-item='{"index":1}'>
                        <span class="flex flex-col items-center gap-2">
                            <span class="flex items-center justify-center size-7 rounded-full 
                                bg-base-200 text-base-content text-sm font-medium
                                stepper-active:bg-primary stepper-active:text-white
                                stepper-completed:bg-success stepper-completed:text-white">
                                1
                            </span>
                            <span class="text-xs text-base-content/70">Perfil</span>
                        </span>
                    </li>

                    <!-- Paso 2 -->
                    <li class="flex flex-col items-center flex-1" data-stepper-nav-item='{"index":2}'>
                        <span class="flex flex-col items-center gap-2">
                            <span class="flex items-center justify-center size-7 rounded-full 
                                bg-base-200 text-base-content text-sm font-medium
                                stepper-active:bg-primary stepper-active:text-white
                                stepper-completed:bg-success stepper-completed:text-white">
                                2
                            </span>
                            <span class="text-xs text-base-content/70">Pagos</span>
                        </span>
                    </li>

                    <!-- Paso 3 -->
                    <li class="flex flex-col items-center flex-1" data-stepper-nav-item='{"index":3}'>
                        <span class="flex flex-col items-center gap-2">
                            <span class="flex items-center justify-center size-7 rounded-full 
                                bg-base-200 text-base-content text-sm font-medium
                                stepper-active:bg-primary stepper-active:text-white
                                stepper-completed:bg-success stepper-completed:text-white">
                                3
                            </span>
                            <span class="text-xs text-base-content/70">Redes</span>
                        </span>
                    </li>

                </ul>

                <!-- FORM -->
                <form method="POST" action="{{ route('writers_store') }}" class="text-center">
                    @csrf

                    <!-- PASO 1: PERFIL -->
                    <div data-stepper-content-item='{"index":1}' class="space-y-4">

                        <div class="input-floating">
                            <input type="text" name="nombre_pluma" class="input" placeholder=" " required>
                            <label class="input-floating-label">Nombre de pluma</label>
                        </div>

                        <div class="input-floating">
                            <textarea name="bio" rows="3" class="input" placeholder=" " required></textarea>
                            <label class="input-floating-label">Cuéntanos sobre ti</label>
                        </div>

                    </div>

                    <!-- PASO 2: PAGOS -->
                    <div data-stepper-content-item='{"index":2}' style="display:none" class="space-y-4">

                        <div class="w-full">

                            <select name="tipo_documento"
                                data-select='{
                                    "placeholder": "Tipo de documento",
                                    "toggleTag": "<button type=\"button\" aria-expanded=\"false\"></button>",
                                    "toggleClasses": "advance-select-toggle w-full input text-left",
                                    "dropdownClasses": "advance-select-menu bg-base-200",
                                    "optionClasses": "advance-select-option selected:select-active",
                                    "optionTemplate": "<div class=\"flex justify-between items-center w-full\"><span data-title></span><span class=\"icon-[tabler--check] shrink-0 size-4 text-primary hidden selected:block\"></span></div>",
                                    "extraMarkup": "<span class=\"icon-[tabler--caret-up-down] shrink-0 size-4 text-base-content absolute top-1/2 end-3 -translate-y-1/2\"></span>"
                                }'
                                class="hidden"
                                required
                            >

                                <option value="">Selecciona</option>
                                <option value="cedula">Cédula</option>
                                <option value="pasaporte">Pasaporte</option>
                                <option value="ruc">RUC</option>

                            </select>

                        </div>

                        <div class="input-floating">
                            <input type="text" name="documento_identidad" class="input" placeholder=" " required>
                            <label class="input-floating-label">Número de documento</label>
                        </div>

                        <div class="input-floating">
                            <input type="email" name="paypal_email" class="input" placeholder=" " required>
                            <label class="input-floating-label">Correo PayPal</label>
                        </div>

                        <div class="input-floating">
                            <input type="text" name="paypal_nombre_cuenta" class="input" placeholder=" ">
                            <label class="input-floating-label">Nombre cuenta</label>
                        </div>

                    </div>

                    <!-- PASO 3: REDES -->
                    <div data-stepper-content-item='{"index":3}' style="display:none" class="space-y-4">

                        <div class="input-floating">
                            <input type="text" name="instagram" class="input" placeholder=" ">
                            <label class="input-floating-label">Instagram</label>
                        </div>

                        <div class="input-floating">
                            <input type="text" name="facebook" class="input" placeholder=" ">
                            <label class="input-floating-label">Facebook</label>
                        </div>

                        <div class="input-floating">
                            <input type="text" name="x" class="input" placeholder=" ">
                            <label class="input-floating-label">X (Twitter)</label>
                        </div>

                        <div class="input-floating">
                            <input type="text" name="web" class="input" placeholder=" ">
                            <label class="input-floating-label">Sitio web</label>
                        </div>

                        <div class="flex items-center gap-2 text-sm">
                            <input type="checkbox" name="terms" class="checkbox checkbox-primary" required>
                            <label>Acepto términos y condiciones</label>
                        </div>

                    </div>

                    <!-- BOTONES -->
                    <div class="flex justify-between mt-6">

                        <button type="button" class="btn btn-text"
                            data-stepper-back-btn>
                            ←
                        </button>

                        <button type="button" class="btn btn-primary"
                            data-stepper-next-btn>
                            Siguiente →
                        </button>

                        <button type="submit" class="btn btn-primary"
                            data-stepper-finish-btn style="display:none">
                            Finalizar
                        </button>

                    </div>

                </form>

            </div>

        </div>

    </div>

</div>

@endsection
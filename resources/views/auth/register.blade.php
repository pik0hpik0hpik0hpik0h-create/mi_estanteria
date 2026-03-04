@extends('layouts.app')

@section('content')

<div class="flex">

        <div class="md:w-1/2 w-full flex flex-col min-h-screen rounded-4xl motion-preset-slide-left">

            <div class="flex justify-between p-5">
                <a href="{{ route('login') }}" class="btn btn-text font-inconsolata btn-sm"><- Regresar</a>
                <img src="{{ asset('assets/img/logo_navbar.png') }}" class="w-15" alt="Mi Estantería">
            </div>

            <div class="flex  flex-col items-center justify-center gap-y-8 mx-10">

                <div class="text-center">
                    <h1 class="font-serif text-2xl">¡ Todo empieza aquí !</h1>
                    <h2 class="font-inconsolata text-sm">Completa este registro gratuito y comienza una nueva historia</h2>
                </div>

                <form novalidate autocomplete="off" method="POST" action="{{ route('register.store') }}" class="text-center font-inconsolata w-75 md:w-85">
                    @csrf

                    <div class="flex flex-row justify-between items-center gap-5">
                        <div class="input-floating w-full">
                            <input type="text" placeholder="Ingrese nombre" class="input" id="nombre" name="nombres"/>
                            <label class="input-floating-label" for="nombre">Nombre</label>
                        </div>

                        <div class="input-floating w-full">
                            <input type="text" placeholder="Ingrese apellido" class="input" id="apellido" name="apellidos"/>
                            <label class="input-floating-label" for="apellido">Apellido</label>
                        </div>
                    </div>

                    <div class="input-floating w-full max-w-md mt-4">
                        <input type="text" placeholder="Ingrese correo" class="input" id="email" name="email"/>
                        <label class="input-floating-label" for="email">Correo Electrónico</label>
                    </div>

                    <div class="max-w-sm mt-4">
                        <div class="relative">

                            <div class="input-floating">
                                <input 
                                    type="password" 
                                    id="password" 
                                    name="password"
                                    class="input w-full" 
                                    placeholder="Ingrese Contraseña" 
                                />
                                <label 
                                    class="input-floating-label" 
                                    for="password">
                                    Contraseña
                                </label>
                            </div>

                            <div 
                                id="password-content" 
                                class="card absolute z-10 w-full hidden p-4 mt-2 bg-base-200">

                                <div
                                    data-strong-password='{
                                        "target": "#password",
                                        "hints": "#password-content",
                                        "stripClasses": "strong-password:bg-secondary strong-password-accepted:bg-primary h-1.5 flex-auto bg-neutral/20",
                                        "mode": "popover"
                                    }'
                                    class="rounded-full overflow-hidden mt-2.5 flex gap-0.5">
                                </div>

                                <h6 class="text-base text-base-content my-2 font-semibold">
                                    Tu contraseña debe contener:
                                </h6>

                                <ul class="text-base-content/80 space-y-1 text-sm">

                                    <li data-pw-strength-rule="min-length"
                                        class="strong-password-active:text-primary flex items-center gap-x-2 text-xs">
                                        <span class="icon-[tabler--circle-check] hidden size-5 shrink-0" data-check></span>
                                        <span class="icon-[tabler--circle-x] hidden size-5 shrink-0" data-uncheck></span>
                                        El mínimo de caracteres debe ser 6.
                                    </li>

                                    <li data-pw-strength-rule="lowercase"
                                        class="strong-password-active:text-primary flex items-center gap-x-2 text-xs">
                                        <span class="icon-[tabler--circle-check] hidden size-5 shrink-0" data-check></span>
                                        <span class="icon-[tabler--circle-x] hidden size-5 shrink-0" data-uncheck></span>
                                        Debe contener minúsculas.
                                    </li>

                                    <li data-pw-strength-rule="uppercase"
                                        class="strong-password-active:text-primary flex items-center gap-x-2 text-xs">
                                        <span class="icon-[tabler--circle-check] hidden size-5 shrink-0" data-check></span>
                                        <span class="icon-[tabler--circle-x] hidden size-5 shrink-0" data-uncheck></span>
                                        Debe contener mayúsculas.
                                    </li>

                                    <li data-pw-strength-rule="numbers"
                                        class="strong-password-active:text-primary flex items-center gap-x-2 text-xs">
                                        <span class="icon-[tabler--circle-check] hidden size-5 shrink-0" data-check></span>
                                        <span class="icon-[tabler--circle-x] hidden size-5 shrink-0" data-uncheck></span>
                                        Debe contener números.
                                    </li>

                                    <li data-pw-strength-rule="special-characters"
                                        class="strong-password-active:text-primary flex items-center gap-x-2 text-xs">
                                        <span class="icon-[tabler--circle-check] hidden size-5 shrink-0" data-check></span>
                                        <span class="icon-[tabler--circle-x] hidden size-5 shrink-0" data-uncheck></span>
                                        Debe contener caracteres especiales.
                                    </li>

                                </ul>
                            </div>

                        </div>
                    </div>

                    <div class="max-w-sm mt-4">
                        <div class="relative">

                            <div class="input-floating">
                                <input 
                                    type="password" 
                                    id="password_confirmation" 
                                    name="password_confirmation" 
                                    class="input w-full" 
                                    placeholder="Confirmar Contraseña" 
                                />
                                <label 
                                    class="input-floating-label" 
                                    for="password_confirmation">
                                    Confirmar Contraseña
                                </label>
                            </div>

                            <p id="password-match-message"  class="text-xs text-left mt-2 hidden text-red-500">
                                Las contraseñas no coinciden
                            </p>

                        </div>
                    </div>

                    <div class="flex flex-row gap-5 mt-4">

                        <div class="max-w-md w-full">
                            <select
                                data-select='{
                                "placeholder": "Género",
                                "toggleTag": "<button type=\"button\" aria-expanded=\"false\"></button>",
                                "toggleClasses": "advance-select-toggle select-disabled:pointer-events-none select-disabled:opacity-40",
                                "dropdownClasses": "advance-select-menu bg-base-200",
                                "optionClasses": "advance-select-option selected:select-active",
                                "optionTemplate": "<div class=\"flex justify-between items-center w-full\"><span data-title></span><span class=\"icon-[tabler--check] shrink-0 size-4 text-primary hidden selected:block \"></span></div>",
                                "extraMarkup": "<span class=\"icon-[tabler--caret-up-down] shrink-0 size-4 text-base-content absolute top-1/2 end-3 -translate-y-1/2 \"></span>"
                                }'
                                class="hidden w-full" name="genero" 
                            >
                                <option value="">Elija</option>
                                <option value="M">Masculino</option>
                                <option value="F">Femenino</option>
                            </select>
                        </div>

                        <div class="input-floating max-w-md w-full">
                            <input type="text" placeholder="AAAA-MM-DD" class="input" id="flatpickr-floating" name="fecha_nacimiento" />
                            <label class="input-floating-label" for="flatpickr-floating">Nacimiento</label>
                        </div>
                    
                    </div>

                    <div class="max-w-sm mt-4">

                        <select id="pais" name="pais" >
                            <option value="">Elija</option>
                        </select>
                            
                    </div>

                    <div class="input-floating w-full mt-4">
                        <input type="text" placeholder="Ingrese su ciudad" class="input" id="ciudad" name="ciudad" />
                        <label class="input-floating-label" for="ciudad">Ciudad</label>
                    </div>

                    <div class="w-full max-w-md mt-8">
                        <button type="submmit" class="btn btn-primary w-full">Registrarse</button>
                    </div>

                </form>

            </div>

        </div>

        <div class="bg-cover bg-center w-1/2 md:block hidden m-3 rounded-4xl motion-preset-blur-left"
            style="background-image: url('{{ asset('assets/img/register_page.jpg') }}');">
            
        </div>

    </div>

    <script>
        window.addEventListener('load', function () {
            // floating Type
            flatpickr('#flatpickr-floating', {
            monthSelectorType: 'static',
            allowInput: true,
            maxDate: 'today',
            dateFormat:	"Y-m-d",
            })
        })
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", function () {

            const select = document.getElementById("pais");

            fetch("https://restcountries.com/v3.1/all?fields=name,cca2")
                .then(res => res.json())
                .then(data => {

                    if (!Array.isArray(data)) return;

                    data.sort((a, b) =>
                        a.name.common.localeCompare(b.name.common)
                    );

                    data.forEach(country => {
                        const option = document.createElement("option");
                        option.value = country.cca2;
                        option.textContent = country.name.common;
                        select.appendChild(option);
                    });

                    select.setAttribute("data-select", JSON.stringify({
                        placeholder: "País",
                        toggleTag: '<button type="button" aria-expanded="false"></button>',
                        toggleClasses: "advance-select-toggle",
                        hasSearch: true,
                        searchWrapperClasses: "bg-base-200 sticky top-0 mb-2 px-2 pt-3",
                        searchNoResultText: "No se encontraron resultados",
                        dropdownClasses: "advance-select-menu p-0 bg-base-200 max-h-60 overflow-y-auto overflow-x-hidden",
                        optionClasses: "advance-select-option selected:select-active m-2 text-xs",
                        optionTemplate: '<div class="flex justify-between items-center w-full"><span data-title></span><span class="icon-[tabler--check] shrink-0 size-4 text-primary hidden selected:block"></span></div>',
                        extraMarkup: '<span class="icon-[tabler--caret-up-down] shrink-0 size-4 text-base-content absolute top-1/2 end-3 -translate-y-1/2"></span>'
                    }));

                    HSStaticMethods.autoInit();

                });

        });
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const password = document.getElementById("password");
            const confirmPassword = document.getElementById("password_confirmation");
            const message = document.getElementById("password-match-message");

            function validatePasswordMatch() {

                if (confirmPassword.value === "") {
                    confirmPassword.classList.remove("border-red-500", "border-green-500");
                    message.classList.add("hidden");
                    return;
                }

                if (password.value === confirmPassword.value) {
                    confirmPassword.classList.remove("border-red-500");
                    message.classList.add("hidden");
                } else {
                    confirmPassword.classList.add("border-red-500");
                    message.classList.remove("hidden");
                }
            }

            password.addEventListener("input", validatePasswordMatch);
            confirmPassword.addEventListener("input", validatePasswordMatch);
        });
    </script>

    @endsection
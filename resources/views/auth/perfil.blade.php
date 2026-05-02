@extends('layouts.app')

@section('content')

@include('components.navbar')

<div class="mt-15 md:mt-25"></div>

<div class="flex justify-left md:justify-center gap-x-8 px-8 motion-preset-focus">

    <div class="text-base-content py-8 w-3/5 hidden md:block">
        <h1 class="text-5xl font-serif">Perfil</h1>
        <h2 class="font-inconsolata">Observa todos los detalles de tu perfil aquí.</h2>
    </div>

    <div class="flex justify-between items-center py-8 w-full md:w-2/5">

        <div class="text-left">
            <h1 class="text-3xl font-serif">{{ Auth::user()->name }}</h1>
            <h1 class="font-inconsolata text-primary">{{ $user->perfil->nombres }} {{ $user->perfil->apellidos }}</h1>
        </div>

                
        <div class="avatar flex items-center">
            <div class="size-16 rounded-full border-4 border-primary">
                <img src="
                        @if(Auth::user()->avatar)
                            {{ str_contains(Auth::user()->avatar, 'http') 
                                ? Auth::user()->avatar 
                                : asset('storage/' . Auth::user()->avatar) }}
                        @else
                            {{ asset('assets/img/default_avatar.jpg') }}
                        @endif
                        "
                    alt="Avatar"
                />
            </div>
        </div>

    </div>

</div>

@if(auth()->check() && auth()->user()->isWriter())

<div class="md:flex justify-center px-8 gap-8 mb-8">

  <div class="font-inconsolata stats w-full md:w-3/5 shadow-md bg-linear-to-r from-accent/30 to-accent rounded-md p-6 motion-preset-slide-right">

    <div class="stat">
      <div class="stat-title">Saldo disponible</div>
      <div class="stat-value text-3xl font-serif">
        ${{ number_format($wallet->saldo_disponible ?? 0, 2) }}
      </div>
      <div class="stat-desc">
        Fondos listos para retirar
      </div>
    </div>

    <div class="stat text-right">
      <div class="stat-title">Último retiro</div>
      <div class="stat-value text-xl text-primary font-serif">
        ${{ $lastWithdraw ? number_format($lastWithdraw->monto, 2) : '0.00' }}
      </div>
      <div class="stat-desc">
        {{ $lastWithdraw ? ucfirst($lastWithdraw->estado) : 'Sin solicitudes' }}
      </div>
    </div>

  </div>

  <div class="w-full border border-base-content/20 md:w-2/5 rounded-md p-6 motion-preset-slide-left mt-8 md:mt-0">

    @if($hasPending)

    <div class="bg-warning font-inconsolata p-5 rounded-md h-full flex items-center">
        <div class="text-warning-content">
            Ya tienes una solicitud en proceso. Espera a que sea aprobada.
        </div>
    </div>

    @else

    <h1 class="text-xl font-serif mb-4">Solicitar retiro</h1>
    
    <form method="POST" action="{{ route('writer.withdraw.store') }}">
        @csrf

        <div class="input w-full">
  
            <span class="text-base-content/80 my-auto shrink-0">
                $
            </span>

            <div class="input-floating grow">
                
                <input 
                type="text"
                placeholder="0.00"
                class="ps-3"
                id="monto"
                name="monto"
                />

                <label class="input-floating-label" for="monto">
                Monto
                </label>

            </div>

        </div>

      <button type="submit" class="btn btn-accent w-full mt-4"><span class="icon-[tabler--brand-paypal]"></span>Solicitar retiro</button>

      @if ($errors->any())
        <div class="font-inconsolata text-sm text-red-500 mt-3">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
    @endif

    </form>

    @endif

  </div>

</div>

@endif

<div class="md:flex justify-center px-8 gap-8 mb-8">

    <div class="border border-base-content/20 w-full md:w-3/5 rounded-md p-8 motion-preset-slide-right">

        <div class="text-left">
            <h1 class="text-xl font-serif">Datos de perfil</h1>
        </div>

        <form novalidate autocomplete="off" enctype="multipart/form-data" method="POST" action="{{ route('editar_perfil') }}" class="font-inconsolata">
            @csrf

            <div class="input-floating w-full mt-8">
                <input type="text" placeholder="Ingrese bio" class="input" id="bio" name="bio" value="{{ $user->perfil->bio }}"/>
                <label class="input-floating-label" for="bio">Bio</label>
            </div>

            <div class="input-floating w-full mt-4">
                <input type="text" placeholder="Ingrese usuario" class="input" id="usuario" name="usuario" value="{{ Auth::user()->name }}"/>
                <label class="input-floating-label" for="usuario">Usuario</label>
            </div>

            <div class="flex flex-row justify-between items-center gap-5 mt-4">
                <div class="input-floating w-full">
                    <input type="text" placeholder="Ingrese nombre" class="input" id="nombre" name="nombres" value="{{ $user->perfil->nombres }}"/>
                    <label class="input-floating-label" for="nombre">Nombre</label>
                </div>

                <div class="input-floating w-full">
                    <input type="text" placeholder="Ingrese apellido" class="input" id="apellido" name="apellidos" value="{{ $user->perfil->apellidos }}"/>
                    <label class="input-floating-label" for="apellido">Apellido</label>
                </div>
            </div>

        

            <div class="w-full mt-4">
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
                        class="hidden w-full" name="genero" value="{{ $user->perfil->apellidos }}"
                        > 
                        <option value="">Elija</option>

                        <option value="M" {{ $user->perfil->genero == 'M' ? 'selected' : '' }}>
                            Masculino
                        </option>

                        <option value="F" {{ $user->perfil->genero == 'F' ? 'selected' : '' }}>
                            Femenino
                        </option>
                </select>
            </div>

            <div class="input-floating w-full mt-4">
                <input type="text" placeholder="AAAA-MM-DD" class="input" id="flatpickr-floating" name="fecha_nacimiento" value="{{ $user->perfil->fecha_nacimiento }}"/>
                <label class="input-floating-label" for="flatpickr-floating">Nacimiento</label>
            </div>
                    
            <div class="w-full mt-4">
                <select id="pais" name="pais" >
                    <option value="">Elija</option>
                </select>
            </div>

            <div class="input-floating w-full mt-4">
                <input type="text" placeholder="Ingrese su ciudad" class="input" id="ciudad" name="ciudad" value="{{ $user->perfil->ciudad }}"/>
                <label class="input-floating-label" for="ciudad">Ciudad</label>
            </div>

            <div class="flex flex-row justify-between items-center gap-5 mt-4">
                <div class="input-floating w-full">
                    <input type="text" placeholder="Ingrese e-mail" class="input" id="email" name="email" value="{{ Auth::user()->email }}"/>
                    <label class="input-floating-label" for="email">E-mail</label>
                </div>

                <div class="input-floating w-full">
                    <input type="text" placeholder="Ingrese telefono" class="input" id="telefono" name="telefono" value="{{ $user->perfil->telefono }}"/>
                    <label class="input-floating-label" for="telefono">Teléfono</label>
                </div>
            </div>

            <div class="w-full mt-4 flex items-center gap-x-2">

                <div class="avatar">
                    <div class="size-10 rounded-full border-2 border-primary">
                    <img id="preview-avatar"
                        src="
                        @if(Auth::user()->avatar)
                            {{ str_contains(Auth::user()->avatar, 'http') 
                                ? Auth::user()->avatar 
                                : asset('storage/' . Auth::user()->avatar) }}
                        @else
                            {{ asset('assets/img/default_avatar.jpg') }}
                        @endif
                        "
                        alt="Avatar"
                    />
                    </div>
                </div>

                <div class="input-floating">
                    <input type="file" placeholder="Foto" class="input" id="avatar" name="avatar" accept="image/*"/>
                    <label class="input-floating-label" for="avatar">Foto de Perfil</label>
                </div>

            </div>

            <div class="flex flex-row justify-between items-center gap-5 mt-4">

                <div class="input-floating w-full">
                    <input type="text" placeholder="Ingrese Web" class="input" id="web" name="web" value="{{ $user->perfil->web }}"/>
                    <label class="input-floating-label" for="web">Web</label>
                </div>

                <div class="input-floating w-full">
                    <input type="text" placeholder="Ingrese Facebook" class="input" id="facebook" name="facebook" value="{{ $user->perfil->facebook }}"/>
                    <label class="input-floating-label" for="apellido">Facebook</label>
                </div>

            </div>

            <div class="flex flex-row justify-between items-center gap-5 mt-4">

                <div class="input-floating w-full">
                    <input type="text" placeholder="Ingrese Instagram" class="input" id="instagram" name="instagram" value="{{ $user->perfil->instagram }}"/>
                    <label class="input-floating-label" for="instagram">Instagram</label>
                </div>

                <div class="input-floating w-full">
                    <input type="text" placeholder="Ingrese X" class="input" id="x" name="x" value="{{ $user->perfil->x }}"/>
                    <label class="input-floating-label" for="x">X</label>
                </div>

            </div>

            @if ($errors->any())
                <div class="font-inconsolata text-sm text-red-500 mt-3">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="flex justify-center mt-8">
                <button type="submit" class="btn btn-primary"><span class="icon-[tabler--edit]"></span>Editar Perfil</button>
            </div>
            
        </form>

    </div>

    <div class="flex flex-col border border-base-content/20 w-full md:w-2/5 rounded-md p-8 mt-8 md:mt-0 motion-preset-slide-left">

        <div class="text-left">
            <h1 class="text-xl font-serif">Tus Roles</h1>
        </div>

        <p class="font-inconsolata mt-6">
            Ahora mismo tienes permisos como
            @foreach($user->roles as $rol)
                <span class="font-inconsolata text-primary">
                    <span class="font-black">{{ $rol->rol }}</span><span class="text-base-content"></span>
                </span>@if(!$loop->last), @endif
            @endforeach
            .
        </p>

        <div class="divider my-8 font-inconsolata">Modificar roles</div>

        <p class="font-inconsolata text-justify text-sm md:text-md md:leading-5 lg:leading-6.5 lg:text-lg">
            En <strong class="text-primary">Mi Estantería</strong> no solo puedes descubrir libros increíbles como <strong class="italic font-black">lector</strong>, dejando reseñas y comentarios para ayudar a otros a encontrar su próxima gran lectura. También puedes ser <strong class="italic font-black">escritor</strong>, publicando tus propias obras y ganando dinero con cada venta, o convertirte en <strong class="italic font-black">vendedor</strong>, recomendando libros y obteniendo comisiones a través de tu enlace o código único. En <strong class="text-primary">Mi Estantería</strong>, no solo lees historias, también puedes crear oportunidades con ellas.
        </p>

        <div class="mt-8 sm:mt-auto">

            <div class="flex justify-center">
                <a href="{{ route('writers_create') }}" class="btn btn-secondary text-secondary-content font-inconsolata w-full text-sm"><span class="icon-[tabler--writing]"></span>Quiero ser Escritor</a>
            </div>

            <div class="flex justify-center mt-4">
                <button class="btn btn-accent text-accent-content font-inconsolata w-full text-sm"><span class="icon-[tabler--pig-money]"></span>Quiero ser Vendedor</button>
            </div>
        </div>

    </div>

</div>

<div class="flex justify-end p-8 pt-0 intersect:motion-preset-focus">
    <form method="POST" class="w-full md:w-auto" action="{{ route('logout') }}">
        @csrf
            <button class="btn btn-primary w-full md:w-auto" type="submit">
                <span class="icon-[tabler--logout-2] size-5"></span>
                    Salir
            </button>
    </form>
</div>

<script>
    const paisSeleccionado = "{{ old('pais', $user->perfil->pais ?? '') }}";
</script>

<script>
    document.getElementById('avatar').addEventListener('change', function(e) {
        const file = e.target.files[0];

        if (file) {
            const preview = document.getElementById('preview-avatar');
            preview.src = URL.createObjectURL(file);
        }
    });
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

                select.value = paisSeleccionado;

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

@endsection
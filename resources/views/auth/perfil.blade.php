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

{{-- AVISO: solicitud de escritor pendiente / rechazada (no aprobada) --}}
@if(auth()->check() && auth()->user()->writer && !auth()->user()->isWriter())
    @php $writerEstado = auth()->user()->writer->estado; @endphp
    <div class="px-4 sm:px-8 mb-6 motion-preset-slide-up">
        @if($writerEstado === 'pendiente')
            <div class="alert bg-warning/15 border border-warning text-warning-content font-inconsolata flex items-start gap-3 rounded-lg p-4">
                <span class="icon-[tabler--clock] text-xl shrink-0 mt-0.5"></span>
                <div class="text-sm md:text-base">
                    <strong>Tu solicitud de escritor está en revisión.</strong><br>
                    Un administrador la revisará pronto. Mientras tanto no podrás publicar libros.
                </div>
            </div>
        @elseif($writerEstado === 'rechazado')
            <div class="alert bg-error/15 border border-error text-error-content font-inconsolata flex items-start gap-3 rounded-lg p-4">
                <span class="icon-[tabler--x] text-xl shrink-0 mt-0.5"></span>
                <div class="text-sm md:text-base">
                    <strong>Tu solicitud de escritor fue rechazada.</strong><br>
                    No puedes publicar libros en este momento.
                </div>
            </div>
        @elseif($writerEstado === 'suspendido')
            <div class="alert bg-warning/15 border border-warning text-warning-content font-inconsolata flex items-start gap-3 rounded-lg p-4">
                <span class="icon-[tabler--alert-triangle] text-xl shrink-0 mt-0.5"></span>
                <div class="text-sm md:text-base">
                    <strong>Tu cuenta de escritor está suspendida.</strong><br>
                    Contactá al equipo de soporte para más información.
                </div>
            </div>
        @endif
    </div>
@endif

{{-- AVISO: solicitud de vendedor pendiente / rechazada (no aprobada) --}}
@if(auth()->check() && auth()->user()->vendedor && !auth()->user()->isVendedor())
    @php $vendedorEstado = auth()->user()->vendedor->estado; @endphp
    <div class="px-4 sm:px-8 mb-6 motion-preset-slide-up">
        @if($vendedorEstado === 'pendiente')
            <div class="alert bg-warning/15 border border-warning text-warning-content font-inconsolata flex items-start gap-3 rounded-lg p-4">
                <span class="icon-[tabler--clock] text-xl shrink-0 mt-0.5"></span>
                <div class="text-sm md:text-base">
                    <strong>Tu solicitud de vendedor está en revisión.</strong><br>
                    Un administrador la revisará pronto.
                </div>
            </div>
        @elseif($vendedorEstado === 'rechazado')
            <div class="alert bg-error/15 border border-error text-error-content font-inconsolata flex items-start gap-3 rounded-lg p-4">
                <span class="icon-[tabler--x] text-xl shrink-0 mt-0.5"></span>
                <div class="text-sm md:text-base">
                    <strong>Tu solicitud de vendedor fue rechazada.</strong>
                </div>
            </div>
        @elseif($vendedorEstado === 'suspendido')
            <div class="alert bg-warning/15 border border-warning text-warning-content font-inconsolata flex items-start gap-3 rounded-lg p-4">
                <span class="icon-[tabler--alert-triangle] text-xl shrink-0 mt-0.5"></span>
                <div class="text-sm md:text-base">
                    <strong>Tu cuenta de vendedor está suspendida.</strong><br>
                    Contactá al equipo de soporte para más información.
                </div>
            </div>
        @endif
    </div>
@endif

@if(auth()->check() && (auth()->user()->isWriter() || auth()->user()->isVendedor()))

<div class="md:flex justify-center px-8 gap-8 mb-8">

  <a href="{{ route('wallet.movimientos') }}" title="Ver movimientos de la billetera"
     class="font-inconsolata stats w-full md:w-3/5 shadow-md bg-linear-to-r from-accent/30 to-accent rounded-md p-6 motion-preset-slide-right cursor-pointer transition hover:shadow-lg hover:scale-[1.01]">

    <div class="stat">
      <div class="stat-title">Saldo disponible</div>
      <div class="stat-value text-3xl font-serif">
        ${{ number_format($wallet->saldo_disponible ?? 0, 2) }}
      </div>
      <div class="stat-desc">
        Fondos listos para retirar · <span class="link">Ver movimientos</span>
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

  </a>

  <div class="w-full border border-base-content/20 md:w-2/5 rounded-md p-6 motion-preset-slide-left mt-8 md:mt-0">

    @if($hasPending)

    <div class="bg-warning font-inconsolata p-5 rounded-md h-full flex items-center">
        <div class="text-warning-content">
            Ya tienes una solicitud en proceso. Espera a que sea aprobada.
            <a href="{{ route('writer.withdraw_history') }}" class="font-inconsolata text-warning-content link text-center">Historial de retiros</a>
        </div>
    </div>

    @else

    <div class="flex justify-between mb-4">
        <h1 class="text-xl font-serif text-center">Solicitar retiro</h1>
        <a href="{{ route('writer.withdraw_history') }}" class="font-inconsolata link text-center">Historial de retiros</a>
    </div>
    
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


@if(auth()->user()->isWriter())
<div class="mx-8 mb-8 bg-base-300 p-6.5 rounded-xl flex items-center gap-10 overflow-x-auto motion-preset-slide-left">

    <a href="{{ route('books.create') }}" class="bg-primary rounded-lg h-90 min-w-65 duration-300 relative hover:scale-105 active:scale-98 flex items-center justify-center">
        <span class="icon-[tabler--plus] text-primary-content"></span>
        <h1 class="font-inconsolata ml-5 text-primary-content">Subir Libro</h1>
    </a>

    @foreach($libros as $libro)

        <div class="bg-base-200 rounded-lg h-90 min-w-65 duration-300 relative">

            <a href="{{ route('books.show', $libro) }}" class="btn btn-sm btn-square absolute border-none top-2 right-2 z-10 bg-primary">
                <span class="icon-[tabler--pencil] text-primary-content"></span>
            </a>

            <div class="flex justify-center p-5">

                <img 
                    src="{{ $libro->portada 
                        ? asset('storage/' . $libro->portada) 
                        : asset('assets/img/book_cover_mockup.jpg') }}"
                    class="w-28 h-40 object-cover rounded-xs"
                    alt="{{ $libro->titulo }}"
                >

            </div>

            <div class="px-5">

                <h1 class="font-serif text-lg">
                    {{ $libro->titulo }}
                </h1>

                <h2 class="font-inconsolata text-sm">
                    {{ $libro->category->nombre ?? 'Sin categoría' }}
                </h2>

                <h2 class="font-inconsolata text-sm mt-10">
                    Autor: {{ $libro->writer->nombre_pluma ?? 'Desconocido' }}
                </h2>

            </div>

            <div class="px-5 mt-5 flex items-center justify-between">

                <div class="flex items-center gap-2">

                    <div 
                        class="flex raty-read-only"
                        data-score="{{ $libro->destacado ?? 0 }}">
                    </div>

                    <div class="-translate-y-1 rounded-field text-xs font-semibold font-inconsolata">
                        {{ $libro->destacado ?? 0 }}
                    </div>

                </div>

                <div>

                    <h2 class="font-inconsolata text-sm font-black">
                        ${{ number_format($libro->precio, 2) }}
                    </h2>

                </div>

            </div>

        </div>

        @endforeach

</div>

@endif
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

        @php $rolesActivos = $user->roles->where('estado', 1); @endphp

        <p class="font-inconsolata mt-6">
            @if($rolesActivos->isEmpty())
                Todavía no tienes permisos activos.
            @else
                Ahora mismo tienes permisos como
                @foreach($rolesActivos as $rol)
                    <span class="font-inconsolata text-primary">
                        <span class="font-black">{{ $rol->rol }}</span><span class="text-base-content"></span>
                    </span>@if(!$loop->last), @endif
                @endforeach
                .
            @endif
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
                <a href="{{ route('vendedores_create') }}" class="btn btn-accent text-accent-content font-inconsolata w-full text-sm"><span class="icon-[tabler--pig-money]"></span>Quiero ser Vendedor</a>
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

        fetch("{{ route('paises') }}")
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

<script>
    document.addEventListener('DOMContentLoaded', function () {

        document.querySelectorAll('.raty-read-only').forEach(function (element) {

            const score = element.dataset.score;

            const rating = new Raty(element, {
                half: true,
                starType: 'i',
                starOff: 'icon-[tabler--star-filled] opacity-20 size-4',
                starHalf: 'icon-[tabler--star-half-filled] size-4 text-primary',
                starOn: 'icon-[tabler--star-filled] size-4 text-primary',
                readOnly: true,
                score: score
            });

            rating.init();

                // Mostrar el score al lado
            const scoreContainer = element.parentElement.querySelector('.raty-score');
            if (scoreContainer) {
                scoreContainer.textContent = score;
            }

        });

    });
</script>

<script>
    window.addEventListener('load', function () {
        const animationButtons = document.querySelectorAll('.animation-button')
        const box = document.getElementById('animated-box')

        animationButtons.forEach(button => {
        button.addEventListener('click', () => {
            const animationClass = button.value

            // Remove all existing motion- classes
            const currentClasses = Array.from(box.classList)
            const motionClasses = currentClasses.filter(className => className.startsWith('motion-'))
            motionClasses.forEach(className => box.classList.remove(className))

            // Temporarily remove the animation class to re-trigger it
            void box.offsetWidth // Trigger reflow to allow re-adding the class
            box.classList.add(animationClass, 'motion-duration-1000')
            })
        })
    })
</script>

@endsection
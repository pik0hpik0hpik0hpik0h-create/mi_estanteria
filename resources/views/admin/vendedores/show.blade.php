@extends('layouts.app')

@section('content')
@include('components.navbar')

<div class="mt-15 md:mt-25"></div>

<div class="p-4 sm:p-6 md:p-8 pb-0 max-w-7xl mx-auto">

    {{-- BARRA STICKY --}}
    <div class="sticky top-20 md:top-25 z-30 -mx-2 mb-6 md:mb-8 px-2 motion-preset-slide-down">
        <div class="flex flex-wrap gap-2 md:gap-3 justify-between items-center
                    bg-base-100/70 glass border border-base-300 rounded-xl shadow-md
                    px-3 py-2 md:px-4 md:py-3 backdrop-blur-md">

            <a href="{{ route('admin.vendedores.index') }}"
               class="btn btn-outline btn-sm font-inconsolata
                      transition-transform duration-200 hover:-translate-x-1">
                <span class="icon-[tabler--arrow-left]"></span>
                <span class="hidden sm:inline">Volver</span>
            </a>

            @if($vendedor->estado === 'pendiente')
                <div class="flex items-center gap-2 font-inconsolata">
                    <span class="relative flex h-3 w-3">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-warning opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-3 w-3 bg-warning"></span>
                    </span>
                    <span class="text-xs sm:text-sm uppercase tracking-wider">En revisión</span>
                </div>
            @else
                <span class="badge badge-lg font-inconsolata text-white
                    {{ $vendedor->estado === 'aprobado' ? 'bg-emerald-600 border-emerald-600' : '' }}
                    {{ $vendedor->estado === 'rechazado' ? 'bg-rose-600 border-rose-600' : '' }}
                    {{ $vendedor->estado === 'suspendido' ? 'bg-amber-600 border-amber-600' : '' }}">
                    {{ ucfirst($vendedor->estado) }}
                </span>
            @endif

            @if($vendedor->estado === 'pendiente')
                <div class="flex flex-wrap gap-2 w-full sm:w-auto">
                    {{-- RECHAZAR --}}
                    <button type="button"
                            onclick="document.getElementById('modal-rechazar-vendedor').classList.remove('hidden')"
                            class="font-inconsolata font-bold inline-flex items-center justify-center gap-2 px-4 sm:px-5 py-2 rounded-lg
                                   bg-rose-600 hover:bg-rose-700 text-white shadow-md
                                   transition-all duration-200 hover:scale-105 active:scale-95
                                   group flex-1 sm:flex-none">
                        <span class="icon-[tabler--x] text-lg group-hover:rotate-90 transition-transform duration-300"></span>
                        Rechazar
                    </button>

                    {{-- AUTORIZAR --}}
                    <button type="button"
                            onclick="document.getElementById('modal-aprobar-vendedor').classList.remove('hidden')"
                            class="font-inconsolata font-bold inline-flex items-center justify-center gap-2 px-4 sm:px-5 py-2 rounded-lg
                                   bg-emerald-600 hover:bg-emerald-700 text-white shadow-md
                                   transition-all duration-200 hover:scale-105 active:scale-95
                                   group flex-1 sm:flex-none">
                        <span class="icon-[tabler--check] text-lg group-hover:scale-125 transition-transform duration-300"></span>
                        Autorizar
                    </button>
                </div>
            @endif
        </div>
    </div>

    {{-- ENCABEZADO --}}
    <div class="text-center mb-8 md:mb-10 motion-preset-focus">

        <div class="flex justify-center mb-4">
            <div class="avatar relative">
                <div class="w-24 h-24 rounded-full border-4 border-primary shadow-lg overflow-hidden
                            transition-transform duration-300 hover:scale-110 hover:rotate-3">
                    <img class="w-full h-full object-cover" src="
                        @if($vendedor->user?->avatar)
                            {{ str_contains($vendedor->user->avatar, 'http')
                                ? $vendedor->user->avatar
                                : asset('storage/' . $vendedor->user->avatar) }}
                        @else
                            {{ asset('assets/img/default_avatar.jpg') }}
                        @endif
                    " alt="Avatar de {{ $vendedor->nombre_publico }}" />
                </div>
                @if($vendedor->estado === 'pendiente')
                    <span class="absolute -bottom-1 -right-1 w-7 h-7 rounded-full bg-warning border-4 border-base-100
                                 flex items-center justify-center animate-bounce">
                        <span class="icon-[tabler--clock] text-white text-sm"></span>
                    </span>
                @endif
            </div>
        </div>

        <h1 class="text-2xl sm:text-3xl md:text-5xl font-serif mb-2 break-words">{{ $vendedor->nombre_publico }}</h1>
        <p class="font-inconsolata text-base-content/70 text-xs sm:text-sm md:text-base">
            Solicitud enviada el {{ $vendedor->created_at?->format('d/m/Y H:i') }}
        </p>
    </div>

    {{-- TARJETAS DE INFORMACIÓN --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-6 font-inconsolata mb-10">

        <div class="bg-base-200 p-4 md:p-6 rounded-xl shadow-sm border border-transparent break-words
                    motion-preset-slide-right
                    transition-all duration-300 hover:-translate-y-1 hover:shadow-lg hover:border-primary/40">
            <h3 class="font-bold text-lg mb-4 border-b border-base-content/20 pb-2 flex items-center gap-2">
                <span class="icon-[tabler--user] text-primary"></span>
                Datos del usuario
            </h3>
            <p><strong>Nombre:</strong> {{ $vendedor->user->name ?? '—' }}</p>
            <p><strong>Email:</strong> {{ $vendedor->user->email ?? '—' }}</p>
            <p><strong>Verificado:</strong>
                @if($vendedor->user?->email_verified_at)
                    <span class="text-emerald-600 font-bold">Sí</span>
                    <span class="text-base-content/60 text-sm">({{ $vendedor->user->email_verified_at->format('d/m/Y') }})</span>
                @else
                    <span class="text-rose-600 font-bold">No</span>
                @endif
            </p>
        </div>

        <div class="bg-base-200 p-4 md:p-6 rounded-xl shadow-sm border border-transparent break-words
                    motion-preset-slide-left
                    transition-all duration-300 hover:-translate-y-1 hover:shadow-lg hover:border-primary/40">
            <h3 class="font-bold text-lg mb-4 border-b border-base-content/20 pb-2 flex items-center gap-2">
                <span class="icon-[tabler--id] text-primary"></span>
                Documento de identidad
            </h3>
            <p><strong>Tipo:</strong> {{ strtoupper($vendedor->tipo_documento ?? '—') }}</p>
            <p><strong>Número:</strong> {{ $vendedor->documento_identidad }}</p>
        </div>

        <div class="bg-base-200 p-4 md:p-6 rounded-xl shadow-sm border border-transparent md:col-span-2 break-words
                    intersect:motion-preset-focus
                    transition-all duration-300 hover:-translate-y-1 hover:shadow-lg hover:border-primary/40">
            <h3 class="font-bold text-lg mb-4 border-b border-base-content/20 pb-2 flex items-center gap-2">
                <span class="icon-[tabler--quote] text-primary"></span>
                Biografía
            </h3>
            <p class="italic text-base-content/90">"{{ $vendedor->perfil?->bio ?? 'No proporcionada.' }}"</p>
        </div>

        @if($vendedor->perfil)
        <div class="bg-base-200 p-4 md:p-6 rounded-xl shadow-sm border border-transparent md:col-span-2 break-words
                    intersect:motion-preset-slide-up
                    transition-all duration-300 hover:-translate-y-1 hover:shadow-lg hover:border-primary/40">
            <h3 class="font-bold text-lg mb-4 border-b border-base-content/20 pb-2 flex items-center gap-2">
                <span class="icon-[tabler--link] text-primary"></span>
                Redes y web
            </h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-8 gap-y-1">
                <p><span class="icon-[tabler--brand-instagram] mr-2"></span><strong>Instagram:</strong> {{ $vendedor->perfil->instagram ?? '—' }}</p>
                <p><span class="icon-[tabler--brand-facebook] mr-2"></span><strong>Facebook:</strong> {{ $vendedor->perfil->facebook ?? '—' }}</p>
                <p><span class="icon-[tabler--brand-x] mr-2"></span><strong>X:</strong> {{ $vendedor->perfil->x ?? '—' }}</p>
                <p><span class="icon-[tabler--world] mr-2"></span><strong>Web:</strong> {{ $vendedor->perfil->web ?? '—' }}</p>
            </div>
        </div>
        @endif

        @if($vendedor->payAccount)
        <div class="bg-base-200 p-4 md:p-6 rounded-xl shadow-sm border border-transparent md:col-span-2 break-words
                    intersect:motion-preset-slide-up
                    transition-all duration-300 hover:-translate-y-1 hover:shadow-lg hover:border-primary/40">
            <h3 class="font-bold text-lg mb-4 border-b border-base-content/20 pb-2 flex items-center gap-2">
                <span class="icon-[tabler--brand-paypal] text-primary"></span>
                Cuenta PayPal
            </h3>
            <p><strong>Email PayPal:</strong> {{ $vendedor->payAccount->paypal_email }}</p>
            <p><strong>Nombre de la cuenta:</strong> {{ $vendedor->payAccount->paypal_nombre_cuenta }}</p>
        </div>
        @endif

    </div>

</div>

{{-- MODALES --}}
@if($vendedor->estado === 'pendiente')
<div id="modal-aprobar-vendedor" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4">
    <div class="absolute inset-0 bg-black/60 backdrop-blur-sm"
         onclick="document.getElementById('modal-aprobar-vendedor').classList.add('hidden')"></div>
    <div class="relative bg-base-100 rounded-2xl shadow-2xl p-6 md:p-8 max-w-md w-full motion-preset-pop">
        <div class="flex justify-center mb-4">
            <div class="size-16 rounded-full bg-emerald-100 flex items-center justify-center">
                <span class="icon-[tabler--check] text-emerald-600 text-4xl"></span>
            </div>
        </div>
        <h2 class="font-serif text-xl md:text-2xl text-center mb-2">¿Autorizar a este vendedor?</h2>
        <p class="font-inconsolata text-center text-base-content/70 mb-6 text-sm md:text-base">
            <strong>{{ $vendedor->nombre_publico }}</strong> podrá recomendar libros y ganar comisiones.
        </p>
        <div class="flex flex-col sm:flex-row gap-3 justify-center">
            <button type="button"
                    onclick="document.getElementById('modal-aprobar-vendedor').classList.add('hidden')"
                    class="btn btn-outline font-inconsolata order-2 sm:order-1">
                Cancelar
            </button>
            <form action="{{ route('admin.vendedores.approve', $vendedor) }}" method="POST" class="order-1 sm:order-2">
                @csrf
                <button type="submit"
                        class="font-inconsolata font-bold inline-flex items-center justify-center gap-2 px-5 py-2 rounded-lg w-full
                               bg-emerald-600 hover:bg-emerald-700 text-white shadow-md
                               transition-transform duration-200 hover:scale-105">
                    <span class="icon-[tabler--check]"></span>
                    Sí, autorizar
                </button>
            </form>
        </div>
    </div>
</div>

<div id="modal-rechazar-vendedor" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4">
    <div class="absolute inset-0 bg-black/60 backdrop-blur-sm"
         onclick="document.getElementById('modal-rechazar-vendedor').classList.add('hidden')"></div>
    <div class="relative bg-base-100 rounded-2xl shadow-2xl p-6 md:p-8 max-w-md w-full motion-preset-pop">
        <div class="flex justify-center mb-4">
            <div class="size-16 rounded-full bg-rose-100 flex items-center justify-center">
                <span class="icon-[tabler--x] text-rose-600 text-4xl"></span>
            </div>
        </div>
        <h2 class="font-serif text-xl md:text-2xl text-center mb-2">¿Rechazar a este vendedor?</h2>
        <p class="font-inconsolata text-center text-base-content/70 mb-6 text-sm md:text-base">
            <strong>{{ $vendedor->nombre_publico }}</strong> no podrá recomendar libros. Puedes revertirlo después.
        </p>
        <div class="flex flex-col sm:flex-row gap-3 justify-center">
            <button type="button"
                    onclick="document.getElementById('modal-rechazar-vendedor').classList.add('hidden')"
                    class="btn btn-outline font-inconsolata order-2 sm:order-1">
                Cancelar
            </button>
            <form action="{{ route('admin.vendedores.reject', $vendedor) }}" method="POST" class="order-1 sm:order-2">
                @csrf
                <button type="submit"
                        class="font-inconsolata font-bold inline-flex items-center justify-center gap-2 px-5 py-2 rounded-lg w-full
                               bg-rose-600 hover:bg-rose-700 text-white shadow-md
                               transition-transform duration-200 hover:scale-105">
                    <span class="icon-[tabler--x]"></span>
                    Sí, rechazar
                </button>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') {
            document.getElementById('modal-aprobar-vendedor')?.classList.add('hidden');
            document.getElementById('modal-rechazar-vendedor')?.classList.add('hidden');
        }
    });
</script>
@endif

@endsection

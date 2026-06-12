@extends('layouts.app')

@section('content')
@include('components.navbar')

<div class="mt-15 md:mt-25"></div>

<div class="p-8 pb-0 max-w-7xl mx-auto">

    {{-- BARRA STICKY: siempre visible al hacer scroll --}}
    <div class="sticky top-25 z-30 -mx-2 mb-8 px-2 motion-preset-slide-down">
        <div class="flex flex-wrap gap-3 justify-between items-center
                    bg-base-100/70 glass border border-base-300 rounded-xl shadow-md
                    px-4 py-3 backdrop-blur-md">

            <a href="{{ route('admin.writers.index') }}"
               class="btn btn-outline btn-sm font-inconsolata
                      transition-transform duration-200 hover:-translate-x-1">
                <span class="icon-[tabler--arrow-left]"></span> Volver
            </a>

            {{-- Estado del escritor con badge animado --}}
            @if($writer->estado === 'pendiente')
                <div class="flex items-center gap-2 font-inconsolata">
                    <span class="relative flex h-3 w-3">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-warning opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-3 w-3 bg-warning"></span>
                    </span>
                    <span class="text-sm uppercase tracking-wider">En revisión</span>
                </div>
            @else
                <span class="badge badge-lg font-inconsolata text-white
                    {{ $writer->estado === 'aprobado' ? 'bg-emerald-600 border-emerald-600' : '' }}
                    {{ $writer->estado === 'rechazado' ? 'bg-rose-600 border-rose-600' : '' }}
                    {{ $writer->estado === 'suspendido' ? 'bg-amber-600 border-amber-600' : '' }}">
                    {{ ucfirst($writer->estado) }}
                </span>
            @endif

            @if($writer->estado === 'pendiente')
                <div class="flex gap-2">
                    {{-- RECHAZAR --}}
                    <button type="button"
                            onclick="document.getElementById('modal-rechazar').classList.remove('hidden')"
                            class="font-inconsolata font-bold inline-flex items-center gap-2 px-5 py-2 rounded-lg
                                   bg-rose-600 hover:bg-rose-700 text-white shadow-md
                                   transition-all duration-200 hover:scale-105 active:scale-95
                                   group">
                        <span class="icon-[tabler--x] text-lg group-hover:rotate-90 transition-transform duration-300"></span>
                        Rechazar
                    </button>

                    {{-- AUTORIZAR --}}
                    <button type="button"
                            onclick="document.getElementById('modal-aprobar').classList.remove('hidden')"
                            class="font-inconsolata font-bold inline-flex items-center gap-2 px-5 py-2 rounded-lg
                                   bg-emerald-600 hover:bg-emerald-700 text-white shadow-md
                                   transition-all duration-200 hover:scale-105 active:scale-95
                                   group">
                        <span class="icon-[tabler--check] text-lg group-hover:scale-125 transition-transform duration-300"></span>
                        Autorizar
                    </button>
                </div>
            @endif
        </div>
    </div>

    {{-- ENCABEZADO --}}
    <div class="text-center mb-10 motion-preset-focus">

        {{-- Avatar circular del usuario --}}
        <div class="flex justify-center mb-4">
            <div class="avatar relative">
                <div class="size-24 rounded-full border-4 border-primary shadow-lg
                            transition-transform duration-300 hover:scale-110 hover:rotate-3">
                    <img src="
                        @if($writer->user?->avatar)
                            {{ str_contains($writer->user->avatar, 'http')
                                ? $writer->user->avatar
                                : asset('storage/' . $writer->user->avatar) }}
                        @else
                            {{ asset('assets/img/default_avatar.jpg') }}
                        @endif
                    " alt="Avatar de {{ $writer->nombre_pluma }}" />
                </div>
                @if($writer->estado === 'pendiente')
                    <span class="absolute -bottom-1 -right-1 size-7 rounded-full bg-warning border-4 border-base-100
                                 flex items-center justify-center animate-bounce">
                        <span class="icon-[tabler--clock] text-white text-sm"></span>
                    </span>
                @endif
            </div>
        </div>

        <h1 class="text-4xl md:text-5xl font-serif mb-2">{{ $writer->nombre_pluma }}</h1>
        <p class="font-inconsolata text-base-content/70">
            Solicitud enviada el {{ $writer->created_at?->format('d/m/Y H:i') }}
        </p>
    </div>

    {{-- TARJETAS DE INFORMACIÓN --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 font-inconsolata mb-10">

        <div class="bg-base-200 p-6 rounded-xl shadow-sm border border-transparent
                    motion-preset-slide-right
                    transition-all duration-300 hover:-translate-y-1 hover:shadow-lg hover:border-primary/40">
            <h3 class="font-bold text-lg mb-4 border-b border-base-content/20 pb-2 flex items-center gap-2">
                <span class="icon-[tabler--user] text-primary"></span>
                Datos del usuario
            </h3>
            <p><strong>Nombre:</strong> {{ $writer->user->name ?? '—' }}</p>
            <p><strong>Email:</strong> {{ $writer->user->email ?? '—' }}</p>
            <p><strong>Verificado:</strong>
                @if($writer->user?->email_verified_at)
                    <span class="text-emerald-600 font-bold">Sí</span>
                    <span class="text-base-content/60 text-sm">({{ $writer->user->email_verified_at->format('d/m/Y') }})</span>
                @else
                    <span class="text-rose-600 font-bold">No</span>
                @endif
            </p>
        </div>

        <div class="bg-base-200 p-6 rounded-xl shadow-sm border border-transparent
                    motion-preset-slide-left
                    transition-all duration-300 hover:-translate-y-1 hover:shadow-lg hover:border-primary/40">
            <h3 class="font-bold text-lg mb-4 border-b border-base-content/20 pb-2 flex items-center gap-2">
                <span class="icon-[tabler--id] text-primary"></span>
                Documento de identidad
            </h3>
            <p><strong>Tipo:</strong> {{ strtoupper($writer->tipo_documento ?? '—') }}</p>
            <p><strong>Número:</strong> {{ $writer->documento_identidad }}</p>
            <p><strong>País:</strong> {{ $writer->pais ?? '—' }}</p>
            <p><strong>Ciudad:</strong> {{ $writer->ciudad ?? '—' }}</p>
            <p><strong>Teléfono:</strong> {{ $writer->telefono ?? '—' }}</p>
        </div>

        <div class="bg-base-200 p-6 rounded-xl shadow-sm border border-transparent md:col-span-2
                    intersect:motion-preset-focus
                    transition-all duration-300 hover:-translate-y-1 hover:shadow-lg hover:border-primary/40">
            <h3 class="font-bold text-lg mb-4 border-b border-base-content/20 pb-2 flex items-center gap-2">
                <span class="icon-[tabler--quote] text-primary"></span>
                Biografía
            </h3>
            <p class="italic text-base-content/90">"{{ $writer->perfil?->bio ?? $writer->biografia ?? 'No proporcionada.' }}"</p>
        </div>

        @if($writer->perfil)
        <div class="bg-base-200 p-6 rounded-xl shadow-sm border border-transparent md:col-span-2
                    intersect:motion-preset-slide-up
                    transition-all duration-300 hover:-translate-y-1 hover:shadow-lg hover:border-primary/40">
            <h3 class="font-bold text-lg mb-4 border-b border-base-content/20 pb-2 flex items-center gap-2">
                <span class="icon-[tabler--link] text-primary"></span>
                Redes y web
            </h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-8 gap-y-1">
                <p><span class="icon-[tabler--brand-instagram] mr-2"></span><strong>Instagram:</strong> {{ $writer->perfil->instagram ?? '—' }}</p>
                <p><span class="icon-[tabler--brand-facebook] mr-2"></span><strong>Facebook:</strong> {{ $writer->perfil->facebook ?? '—' }}</p>
                <p><span class="icon-[tabler--brand-x] mr-2"></span><strong>X:</strong> {{ $writer->perfil->x ?? '—' }}</p>
                <p><span class="icon-[tabler--world] mr-2"></span><strong>Web:</strong> {{ $writer->perfil->web ?? '—' }}</p>
            </div>
        </div>
        @endif

        @if($writer->payAccount)
        <div class="bg-base-200 p-6 rounded-xl shadow-sm border border-transparent md:col-span-2
                    intersect:motion-preset-slide-up
                    transition-all duration-300 hover:-translate-y-1 hover:shadow-lg hover:border-primary/40">
            <h3 class="font-bold text-lg mb-4 border-b border-base-content/20 pb-2 flex items-center gap-2">
                <span class="icon-[tabler--brand-paypal] text-primary"></span>
                Cuenta PayPal
            </h3>
            <p><strong>Email PayPal:</strong> {{ $writer->payAccount->paypal_email }}</p>
            <p><strong>Nombre de la cuenta:</strong> {{ $writer->payAccount->paypal_nombre_cuenta }}</p>
        </div>
        @endif

    </div>

</div>

{{-- MODAL DE CONFIRMACIÓN: APROBAR --}}
@if($writer->estado === 'pendiente')
<div id="modal-aprobar" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4">
    <div class="absolute inset-0 bg-black/60 backdrop-blur-sm"
         onclick="document.getElementById('modal-aprobar').classList.add('hidden')"></div>
    <div class="relative bg-base-100 rounded-2xl shadow-2xl p-8 max-w-md w-full motion-preset-pop">
        <div class="flex justify-center mb-4">
            <div class="size-16 rounded-full bg-emerald-100 flex items-center justify-center">
                <span class="icon-[tabler--check] text-emerald-600 text-4xl"></span>
            </div>
        </div>
        <h2 class="font-serif text-2xl text-center mb-2">¿Autorizar a este escritor?</h2>
        <p class="font-inconsolata text-center text-base-content/70 mb-6">
            <strong>{{ $writer->nombre_pluma }}</strong> podrá publicar libros en la plataforma.
        </p>
        <div class="flex gap-3 justify-center">
            <button type="button"
                    onclick="document.getElementById('modal-aprobar').classList.add('hidden')"
                    class="btn btn-outline font-inconsolata">
                Cancelar
            </button>
            <form action="{{ route('admin.writers.approve', $writer) }}" method="POST">
                @csrf
                <button type="submit"
                        class="font-inconsolata font-bold inline-flex items-center gap-2 px-5 py-2 rounded-lg
                               bg-emerald-600 hover:bg-emerald-700 text-white shadow-md
                               transition-transform duration-200 hover:scale-105">
                    <span class="icon-[tabler--check]"></span>
                    Sí, autorizar
                </button>
            </form>
        </div>
    </div>
</div>

{{-- MODAL DE CONFIRMACIÓN: RECHAZAR --}}
<div id="modal-rechazar" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4">
    <div class="absolute inset-0 bg-black/60 backdrop-blur-sm"
         onclick="document.getElementById('modal-rechazar').classList.add('hidden')"></div>
    <div class="relative bg-base-100 rounded-2xl shadow-2xl p-8 max-w-md w-full motion-preset-pop">
        <div class="flex justify-center mb-4">
            <div class="size-16 rounded-full bg-rose-100 flex items-center justify-center">
                <span class="icon-[tabler--x] text-rose-600 text-4xl"></span>
            </div>
        </div>
        <h2 class="font-serif text-2xl text-center mb-2">¿Rechazar a este escritor?</h2>
        <p class="font-inconsolata text-center text-base-content/70 mb-6">
            <strong>{{ $writer->nombre_pluma }}</strong> no podrá publicar libros. Puedes revertirlo después si cambias de opinión.
        </p>
        <div class="flex gap-3 justify-center">
            <button type="button"
                    onclick="document.getElementById('modal-rechazar').classList.add('hidden')"
                    class="btn btn-outline font-inconsolata">
                Cancelar
            </button>
            <form action="{{ route('admin.writers.reject', $writer) }}" method="POST">
                @csrf
                <button type="submit"
                        class="font-inconsolata font-bold inline-flex items-center gap-2 px-5 py-2 rounded-lg
                               bg-rose-600 hover:bg-rose-700 text-white shadow-md
                               transition-transform duration-200 hover:scale-105">
                    <span class="icon-[tabler--x]"></span>
                    Sí, rechazar
                </button>
            </form>
        </div>
    </div>
</div>

{{-- ESC cierra los modales --}}
<script>
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') {
            document.getElementById('modal-aprobar')?.classList.add('hidden');
            document.getElementById('modal-rechazar')?.classList.add('hidden');
        }
    });
</script>
@endif

@endsection

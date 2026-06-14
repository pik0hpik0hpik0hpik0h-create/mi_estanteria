@extends('layouts.app')

@section('content')
@include('components.navbar')

<div class="mt-15 md:mt-25"></div>

<div class="p-4 sm:p-6 md:p-8 pb-0 max-w-7xl mx-auto">

    {{-- BARRA STICKY: siempre visible al hacer scroll --}}
    <div class="sticky top-20 md:top-25 z-30 -mx-2 mb-6 md:mb-8 px-2 motion-preset-slide-down">
        <div class="flex flex-wrap gap-3 justify-between items-center
                    bg-base-100/70 glass border border-base-300 rounded-xl shadow-md
                    px-3 py-2 md:px-4 md:py-3 backdrop-blur-md">

            <a href="{{ route('admin.books.index') }}"
               class="btn btn-outline btn-sm font-inconsolata
                      transition-transform duration-200 hover:-translate-x-1">
                <span class="icon-[tabler--arrow-left]"></span>
                <span class="hidden sm:inline">Volver</span>
            </a>

            {{-- Estado del libro --}}
            @if($book->estado === 'revision')
                <div class="flex items-center gap-2 font-inconsolata">
                    <span class="relative flex h-3 w-3">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-warning opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-3 w-3 bg-warning"></span>
                    </span>
                    <span class="text-xs sm:text-sm uppercase tracking-wider">En revisión</span>
                </div>
            @else
                <span class="badge badge-lg font-inconsolata text-white
                    {{ $book->estado === 'publicado' ? 'bg-emerald-600 border-emerald-600' : '' }}
                    {{ $book->estado === 'rechazado' ? 'bg-rose-600 border-rose-600' : '' }}">
                    {{ ucfirst($book->estado) }}
                </span>
            @endif

            @if($book->estado === 'revision')
                <div class="flex flex-wrap gap-2 w-full sm:w-auto">
                    {{-- RECHAZAR --}}
                    <button type="button"
                            onclick="document.getElementById('modal-rechazar-libro').classList.remove('hidden')"
                            class="font-inconsolata font-bold inline-flex items-center justify-center gap-2 px-4 sm:px-5 py-2 rounded-lg
                                   bg-rose-600 hover:bg-rose-700 text-white shadow-md
                                   transition-all duration-200 hover:scale-105 active:scale-95
                                   group flex-1 sm:flex-none">
                        <span class="icon-[tabler--x] text-lg group-hover:rotate-90 transition-transform duration-300"></span>
                        Rechazar
                    </button>

                    {{-- AUTORIZAR --}}
                    <button type="button"
                            onclick="document.getElementById('modal-aprobar-libro').classList.remove('hidden')"
                            class="font-inconsolata font-bold inline-flex items-center justify-center gap-2 px-4 sm:px-5 py-2 rounded-lg
                                   bg-emerald-600 hover:bg-emerald-700 text-white shadow-md
                                   transition-all duration-200 hover:scale-105 active:scale-95
                                   group flex-1 sm:flex-none">
                        <span class="icon-[tabler--check] text-lg group-hover:scale-125 transition-transform duration-300"></span>
                        <span class="whitespace-nowrap">Autorizar y Publicar</span>
                    </button>
                </div>
            @endif
        </div>
    </div>

    <h1 class="text-2xl sm:text-3xl md:text-4xl font-serif text-center mb-2 break-words">{{ $book->titulo }}</h1>
    <p class="text-center font-inconsolata text-base-content/70 mb-8 text-sm md:text-base">
        Por {{ $book->writer->nombre_pluma ?? 'Autor' }}
    </p>

    {{-- CARGA DEL VISUALIZADOR PDF --}}
    @php
        // Priorizamos el archivo completo para que el admin pueda revisarlo todo
        $archivo_leer = $book->files->where('tipo', 'completo')->first() ?? $book->files->where('tipo', 'preview')->first();
    @endphp

    @if($archivo_leer)
        <div class="w-full flex justify-center mb-8 md:mb-10 bg-base-300 p-2 md:p-4 rounded-lg overflow-x-auto">
            <x-flipbook-preview :pdf="asset('storage/' . $archivo_leer->archivo)" />
        </div>
    @else
        <div class="alert alert-warning shadow-sm font-inconsolata mb-8 md:mb-10">
            <span class="icon-[tabler--alert-triangle] text-2xl"></span>
            <span>El escritor no ha subido ningún archivo PDF (ni completo ni preview) para este libro.</span>
        </div>
    @endif

    {{-- DETALLES EXTRA --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-8 font-inconsolata mb-10">
        <div class="bg-base-200 p-4 md:p-6 rounded-lg shadow-sm
                    motion-preset-slide-right
                    transition-all duration-300 hover:-translate-y-1 hover:shadow-lg">
            <h3 class="font-bold text-base md:text-lg mb-3 md:mb-4 border-b border-base-content/20 pb-2 flex items-center gap-2">
                <span class="icon-[tabler--info-circle] text-primary"></span>
                Información Técnica
            </h3>
            <p><strong>Categoría:</strong> {{ $book->category->nombre ?? 'N/A' }}</p>
            <p><strong>Precio:</strong> ${{ $book->precio }}</p>
            <p><strong>Tipo:</strong> {{ ucfirst($book->tipo) }}</p>
            <p><strong>Formato:</strong> {{ strtoupper($book->formato ?? 'N/A') }}</p>
            <p><strong>Páginas:</strong> {{ $book->paginas ?? '—' }}</p>
        </div>
        <div class="bg-base-200 p-4 md:p-6 rounded-lg shadow-sm
                    motion-preset-slide-left
                    transition-all duration-300 hover:-translate-y-1 hover:shadow-lg">
            <h3 class="font-bold text-base md:text-lg mb-3 md:mb-4 border-b border-base-content/20 pb-2 flex items-center gap-2">
                <span class="icon-[tabler--quote] text-primary"></span>
                Sinopsis
            </h3>
            <p class="italic text-base-content/90 break-words">"{{ $book->descripcion }}"</p>
        </div>
    </div>

</div>

{{-- MODALES DE CONFIRMACIÓN --}}
@if($book->estado === 'revision')

{{-- MODAL APROBAR --}}
<div id="modal-aprobar-libro" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4">
    <div class="absolute inset-0 bg-black/60 backdrop-blur-sm"
         onclick="document.getElementById('modal-aprobar-libro').classList.add('hidden')"></div>
    <div class="relative bg-base-100 rounded-2xl shadow-2xl p-6 md:p-8 max-w-md w-full motion-preset-pop">
        <div class="flex justify-center mb-4">
            <div class="size-16 rounded-full bg-emerald-100 flex items-center justify-center">
                <span class="icon-[tabler--check] text-emerald-600 text-4xl"></span>
            </div>
        </div>
        <h2 class="font-serif text-xl md:text-2xl text-center mb-2">¿Publicar este libro?</h2>
        <p class="font-inconsolata text-center text-base-content/70 mb-6 text-sm md:text-base">
            <strong>{{ $book->titulo }}</strong> quedará visible para todos los lectores de la plataforma.
        </p>
        <div class="flex flex-col sm:flex-row gap-3 justify-center">
            <button type="button"
                    onclick="document.getElementById('modal-aprobar-libro').classList.add('hidden')"
                    class="btn btn-outline font-inconsolata order-2 sm:order-1">
                Cancelar
            </button>
            <form action="{{ route('admin.books.approve', $book) }}" method="POST" class="order-1 sm:order-2">
                @csrf
                <button type="submit"
                        class="font-inconsolata font-bold inline-flex items-center justify-center gap-2 px-5 py-2 rounded-lg w-full
                               bg-emerald-600 hover:bg-emerald-700 text-white shadow-md
                               transition-transform duration-200 hover:scale-105">
                    <span class="icon-[tabler--check]"></span>
                    Sí, publicar
                </button>
            </form>
        </div>
    </div>
</div>

{{-- MODAL RECHAZAR --}}
<div id="modal-rechazar-libro" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4">
    <div class="absolute inset-0 bg-black/60 backdrop-blur-sm"
         onclick="document.getElementById('modal-rechazar-libro').classList.add('hidden')"></div>
    <div class="relative bg-base-100 rounded-2xl shadow-2xl p-6 md:p-8 max-w-md w-full motion-preset-pop">
        <div class="flex justify-center mb-4">
            <div class="size-16 rounded-full bg-rose-100 flex items-center justify-center">
                <span class="icon-[tabler--x] text-rose-600 text-4xl"></span>
            </div>
        </div>
        <h2 class="font-serif text-xl md:text-2xl text-center mb-2">¿Rechazar este libro?</h2>
        <p class="font-inconsolata text-center text-base-content/70 mb-6 text-sm md:text-base">
            <strong>{{ $book->titulo }}</strong> no será publicado. Podrás revertirlo después si cambias de opinión.
        </p>
        <div class="flex flex-col sm:flex-row gap-3 justify-center">
            <button type="button"
                    onclick="document.getElementById('modal-rechazar-libro').classList.add('hidden')"
                    class="btn btn-outline font-inconsolata order-2 sm:order-1">
                Cancelar
            </button>
            <form action="{{ route('admin.books.reject', $book) }}" method="POST" class="order-1 sm:order-2">
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

{{-- ESC cierra los modales --}}
<script>
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') {
            document.getElementById('modal-aprobar-libro')?.classList.add('hidden');
            document.getElementById('modal-rechazar-libro')?.classList.add('hidden');
        }
    });
</script>
@endif

@endsection

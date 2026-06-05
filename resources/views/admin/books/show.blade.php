@extends('layouts.app')

@section('content')
@include('components.navbar')

<div class="mt-15 md:mt-25"></div>

<div class="p-8 pb-0 motion-preset-focus max-w-7xl mx-auto">
    
    <div class="flex justify-between items-center mb-6">
        <a href="{{ route('admin.books.index') }}" class="btn btn-outline btn-sm font-inconsolata">
            <span class="icon-[tabler--arrow-left]"></span> Volver
        </a>
        <div class="flex gap-2">
            {{-- BOTÓN RECHAZAR --}}
            <form action="{{ route('admin.books.reject', $book) }}" method="POST" onsubmit="return confirm('¿Seguro que deseas RECHAZAR este libro?');">
                @csrf
                <button type="submit" class="btn btn-error text-white font-inconsolata">
                    <span class="icon-[tabler--x]"></span> Rechazar
                </button>
            </form>
            
            {{-- BOTÓN APROBAR --}}
            <form action="{{ route('admin.books.approve', $book) }}" method="POST" onsubmit="return confirm('¿Seguro que deseas PUBLICAR este libro?');">
                @csrf
                <button type="submit" class="btn btn-success text-white font-inconsolata">
                    <span class="icon-[tabler--check]"></span> Autorizar y Publicar
                </button>
            </form>
        </div>
    </div>

    <h1 class="text-4xl font-serif text-center mb-2">{{ $book->titulo }}</h1>
    <p class="text-center font-inconsolata text-base-content/70 mb-8">Por {{ $book->writer->nombre_pluma ?? 'Autor' }}</p>

    {{-- CARGA DEL VISUALIZADOR PDF --}}
    @php
        // Priorizamos el archivo completo para que el admin pueda revisarlo todo
        $archivo_leer = $book->files->where('tipo', 'completo')->first() ?? $book->files->where('tipo', 'preview')->first();
    @endphp

    @if($archivo_leer)
        <div class="w-full flex justify-center mb-10 bg-base-300 p-4 rounded-lg">
            <x-flipbook-preview :pdf="asset('storage/' . $archivo_leer->archivo)" />
        </div>
    @else
        <div class="alert alert-warning shadow-sm font-inconsolata mb-10">
            <span class="icon-[tabler--alert-triangle] text-2xl"></span>
            <span>El escritor no ha subido ningún archivo PDF (ni completo ni preview) para este libro.</span>
        </div>
    @endif

    {{-- DETALLES EXTRA --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 font-inconsolata mb-10">
        <div class="bg-base-200 p-6 rounded-lg shadow-sm">
            <h3 class="font-bold text-lg mb-4 border-b border-base-content/20 pb-2">Información Técnica</h3>
            <p><strong>Categoría:</strong> {{ $book->category->nombre ?? 'N/A' }}</p>
            <p><strong>Precio:</strong> ${{ $book->precio }}</p>
            <p><strong>Tipo:</strong> {{ ucfirst($book->tipo) }}</p>
            <p><strong>Formato:</strong> {{ strtoupper($book->formato ?? 'N/A') }}</p>
            <p><strong>Páginas:</strong> {{ $book->paginas }}</p>
        </div>
        <div class="bg-base-200 p-6 rounded-lg shadow-sm">
            <h3 class="font-bold text-lg mb-4 border-b border-base-content/20 pb-2">Sinopsis</h3>
            <p class="italic">"{{ $book->descripcion }}"</p>
        </div>
    </div>

</div>
@endsection
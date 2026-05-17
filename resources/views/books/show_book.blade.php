@extends('layouts.app')

@section('content')

@include('components.navbar')

<div class="mt-15 md:mt-25"></div>

<div class="p-8 pb-0 motion-preset-focus">

    {{-- HEADER --}}
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6">

        <div>

            <h1 class="text-5xl font-serif">
                {{ $book->titulo }}
            </h1>

            <div class="font-inconsolata mt-3 flex flex-wrap gap-3 items-center">

                <span class="badge badge-primary">
                    {{ $book->category->nombre ?? 'Sin categoría' }}
                </span>

                <span class="text-base-content/70">
                    Por {{ $book->writer->nombre_pluma ?? 'Autor desconocido' }}
                </span>

                <span class="text-base-content/50 italic">
                    {{ $book->idioma }}
                </span>

            </div>

        </div>

        <div class="text-primary font-bold font-serif text-3xl">
            ${{ $book->precio }}
        </div>

    </div>
       
    <div class="mt-8 italic font-inconsolata">
        "{{ $book->descripcion }}"
    </div>

</div>

@php
    $archivo_preview = $book->files->where('tipo', 'preview')->first();
@endphp

@if($archivo_preview)

<div class="w-full flex justify-center">

        <x-flipbook-preview
            :pdf="asset('storage/' . $archivo_preview->archivo)"
        />
</div>

@endif

@if($canEdit)
<form 
            method="POST"
            action="{{ route('books.update', $book) }}"
            enctype="multipart/form-data"
            autocomplete="off"
            class="font-inconsolata w-full"
        >

            @csrf
            @method('PUT')

{{-- CONTENIDO --}}
<div class="px-8 mt-10 flex flex-col lg:flex-row gap-8 md:mb-8">

    <div class="w-full lg:w-1/4">

        <div class="bg-base-200 rounded-lg min-w-65 duration-300 p-5 top-30">

            <img 
                src="{{ $book->portada 
                    ? asset('storage/' . $book->portada)
                    : asset('assets/img/book_cover_mockup.jpg') }}"
                class="object-cover rounded-xs"
            >

            <div class="mt-5">

                <label class="text-sm block font-inconsolata">
                    Cambiar portada
                </label>

                <input 
                    type="file"
                    name="portada"
                    class="input input-xs w-full"
                    aria-label="Portada principal"
                    accept="image/*"
                >

            </div>

        </div>

        <div class="bg-base-200 rounded-lg min-w-65 duration-300 p-5 mt-8 top-30">

            @php
                $archivo_preview = $book->files->where('tipo', 'preview')->first();
            @endphp

            @if($archivo_preview)

            <iframe
                src="{{ asset('storage/' . $archivo_preview->archivo) }}"
                class="w-full h-96 rounded-lg border border-base-content/10"
            ></iframe>

            <div class="mt-5 flex justify-between items-center">

                <p class="text-xs font-inconsolata text-base-content/60 truncate">
                    {{ $archivo_preview->nombre_original }}
                </p>

                <a 
                    href="{{ asset('storage/' . $archivo_preview->archivo) }}"
                    target="_blank"
                    class="btn btn-xs btn-primary"
                >
                    Abrir
                </a>

            </div>

        @else

            <div class="h-96 flex flex-col items-center justify-center bg-base-300 rounded-xs">

                <span class="icon-[tabler--file-off] text-5xl text-base-content/30"></span>

                <p class="font-inconsolata text-sm text-base-content/50 mt-3">
                    Sin preview disponible
                </p>

            </div>

        @endif

            <div class="mt-5">

                <label class="text-sm block font-inconsolata">
                    Cambiar archivo preview
                </label>

                <input 
                    type="file"
                    name="archivo_preview"
                    class="input input-xs w-full"
                    aria-label="Preview del libro"
                    accept=".pdf"
                >

            </div>

        </div>

        <div class="bg-base-200 rounded-lg min-w-65 duration-300 p-5 mt-8 top-30">

            @php
                $archivo_principal = $book->files->where('tipo', 'completo')->first();
            @endphp

            @if($archivo_principal)

            <iframe
                src="{{ asset('storage/' . $archivo_principal->archivo) }}"
                class="w-full h-96 rounded-lg border border-base-content/10"
            ></iframe>

            <div class="mt-5 flex justify-between items-center">

                <p class="text-xs font-inconsolata text-base-content/60 truncate">
                    {{ $archivo_principal->nombre_original }}
                </p>

                <a 
                    href="{{ asset('storage/' . $archivo_principal->archivo) }}"
                    target="_blank"
                    class="btn btn-xs btn-primary"
                >
                    Abrir
                </a>

            </div>

            @else

            <div class="h-96 flex flex-col items-center justify-center bg-base-300 rounded-xs">

                <span class="icon-[tabler--file-off] text-5xl text-base-content/30"></span>

                <p class="font-inconsolata text-sm text-base-content/50 mt-3">
                    Sin preview disponible
                </p>

            </div>

        @endif

            <div class="mt-5">

                <label class="text-sm block font-inconsolata">
                    Cambiar archivo principal
                </label>

                <input 
                    type="file"
                    name="archivo_completo"
                    class="input input-xs w-full"
                    aria-label="Archivo principal"
                    accept=".pdf"
                >

            </div>

        </div>

    </div>

    {{-- INFORMACIÓN --}}
    <div class="w-full lg:w-3/4">

        <div class="border border-base-content/20 w-full rounded-md p-8 motion-preset-slide-right mb-8">

        <div class="text-left">
            <h1 class="text-xl font-serif">Datos del libro</h1>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

            {{-- TITULO --}}
            <div class="input-floating w-full md:col-span-2 mt-8">

                <input 
                    type="text"
                    placeholder="Título del libro"
                    class="input w-full"
                    id="titulo"
                    name="titulo"
                    value="{{ old('titulo', $book->titulo) }}"
                />

                <label class="input-floating-label" for="titulo">
                    Título
                </label>

            </div>

            {{-- SLUG --}}
            <div class="input-floating w-full">

                <input 
                    type="text"
                    placeholder="Slug"
                    class="input w-full"
                    id="slug"
                    name="slug"
                    value="{{ old('slug', $book->slug) }}"
                />

                <label class="input-floating-label" for="slug">
                    Slug
                </label>

            </div>

            {{-- ISBN --}}
            <div class="input-floating w-full">

                <input 
                    type="text"
                    placeholder="ISBN"
                    class="input w-full"
                    id="isbn"
                    name="isbn"
                    value="{{ old('isbn', $book->isbn) }}"
                />

                <label class="input-floating-label" for="isbn">
                    ISBN
                </label>

            </div>

            {{-- DESCRIPCION CORTA --}}
            <div class="md:col-span-2">

                <textarea 
                    name="descripcion_corta"
                    class="textarea textarea-bordered w-full font-inconsolata h-25"
                    placeholder="Descripción corta"
                >{{ old('descripcion_corta', $book->descripcion_corta) }}</textarea>

            </div>

            {{-- DESCRIPCIÓN --}}
            <div class="md:col-span-2">

                <textarea 
                    name="descripcion"
                    class="textarea textarea-bordered w-full font-inconsolata h-50"
                    placeholder="Descripción completa"
                >{{ old('descripcion', $book->descripcion) }}</textarea>

            </div>

            {{-- PRECIO --}}
            <div>

                <div class="input">

                    <span class="text-base-content/80 my-auto shrink-0">
                        $
                    </span>

                    <div class="input-floating grow">

                        <input 
                            type="number"
                            step="0.01"
                            placeholder="0.00"
                            class="ps-3"
                            id="precio"
                            name="precio"
                            value="{{ old('precio', $book->precio) }}"
                        />

                        <label class="input-floating-label" for="precio">
                            Precio
                        </label>

                    </div>

                </div>

            </div>

            {{-- IDIOMA --}}
            <div class="input-floating w-full">

                <input 
                    type="text"
                    placeholder="Idioma"
                    class="input w-full"
                    id="idioma"
                    name="idioma"
                    value="{{ old('idioma', $book->idioma) }}"
                />

                <label class="input-floating-label" for="idioma">
                    Idioma
                </label>

            </div>

            {{-- PÁGINAS --}}
            <div class="input-floating w-full">

                <input 
                    type="number"
                    placeholder="Páginas"
                    class="input w-full"
                    id="paginas"
                    name="paginas"
                    min="1"
                    value="{{ old('paginas', $book->paginas) }}"
                />

                <label class="input-floating-label" for="paginas">
                    Páginas
                </label>

</div>

            {{-- STOCK --}}
            <div class="input-floating w-full">

                <input 
                    type="number"
                    placeholder="Stock"
                    class="input w-full"
                    id="stock"
                    name="stock"
                    value="{{ old('stock', $book->stock) }}"
                />

                <label class="input-floating-label" for="stock">
                    Stock
                </label>

            </div>

        </div>

        @if ($errors->any())
        <div class="font-inconsolata text-sm text-red-500 mt-4">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <div class="flex justify-center mt-8">
            <button type="submit" class="btn btn-primary"><span class="icon-[tabler--edit]"></span>Guardar Cambios</button>
        </div>

        </div>

    </div>

</div>

</form>

@endif

@endsection
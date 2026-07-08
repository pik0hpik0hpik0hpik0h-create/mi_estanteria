@extends('layouts.app')

@section('content')

@include('components.navbar')

<div class="mt-15 md:mt-25"></div>

<div class="p-8 pb-0 motion-preset-focus">

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

    <div class="mt-8 italic font-inconsolata">
        "{{ $book->descripcion }}"
    </div>

</div>

<div class="w-full flex justify-center">

    <x-flipbook-archivo
        :pdf="asset('storage/' . $archivo->archivo)"
    />

</div>

@endsection
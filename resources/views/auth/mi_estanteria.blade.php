@extends('layouts.app') 

@section('content')

@include('components.navbar')

<div class="mt-15 md:mt-25"></div>

<div class="px-8 mb-8 motion-preset-focus">

    <div class="flex flex-col md:flex-row justify-between items-start md:items-center">

        <div>
            <h1 class="text-5xl font-serif">Mi Biblioteca</h1>
            <h2 class="font-inconsolata">
                Todos los libros que has adquirido se encuentran aquí.
            </h2>
        </div>

        <div class="mt-4 md:mt-0">
            <div class="stats shadow bg-base-200">

                <div class="stat">
                    <div class="stat-title">Libros</div>
                    <div class="stat-value text-primary">
                        {{ $userBooks->count() }}
                    </div>
                </div>

            </div>
        </div>

    </div>

</div>

@if($userBooks->count())

<div class="px-8">

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 xl:grid-cols-5 gap-6">

        @foreach($userBooks as $userBook)

            @php
                $book = $userBook->book;
            @endphp

            <div class="bg-base-200 rounded-lg overflow-hidden shadow-md duration-300 hover:scale-105 motion-preset-slide-up">

                <div class="relative">

                    <img
                        src="{{ $book->portada
                            ? asset('storage/' . $book->portada)
                            : asset('assets/img/book_cover_mockup.jpg') }}"
                        alt="{{ $book->titulo }}"
                        class="w-full h-72 object-cover"
                    >

                    {{-- ✔ CORRECTO: viene desde user_books --}}
                    @if($userBook->acceso_desde ?? false)
                        <div class="badge badge-success absolute top-3 right-3">
                            Disponible
                        </div>
                    @endif

                </div>

                <div class="p-5">

                    <h2 class="font-serif text-lg line-clamp-2">
                        {{ $book->titulo }}
                    </h2>

                    <p class="font-inconsolata text-sm text-base-content/70 mt-1">
                        {{ $book->writer->nombre_pluma ?? 'Autor desconocido' }}
                    </p>

                    <p class="font-inconsolata text-xs mt-2">
                        {{ $book->category->nombre ?? 'Sin categoría' }}
                    </p>

                    <div class="divider my-3"></div>

                    <div class="flex justify-between items-center">

                        <div class="font-inconsolata text-xs">
                            Comprado:
                            <br>
                            {{ $userBook->created_at->format('d/m/Y') }}
                        </div>

                        <a href="{{ route('library.show', $userBook) }}" class="btn btn-primary">
                            Leer libro
                        </a>

                    </div>

                </div>

            </div>

        @endforeach

    </div>

</div>

@else

<div class="px-8">

    <div class="bg-base-200 rounded-xl p-10 text-center motion-preset-slide-up">

        <span class="icon-[tabler--books] text-6xl text-primary"></span>

        <h2 class="font-serif text-3xl mt-4">
            Tu biblioteca está vacía
        </h2>

        <p class="font-inconsolata mt-2">
            Cuando compres libros aparecerán aquí para que puedas acceder a ellos cuando quieras.
        </p>

        <a href="{{ route('index') }}" class="btn btn-primary mt-6">
            Explorar libros
        </a>

    </div>

</div>

@endif

@endsection
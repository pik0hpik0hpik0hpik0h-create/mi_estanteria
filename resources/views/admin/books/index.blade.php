@extends('layouts.app')

@section('content')
@include('components.navbar')

<div class="mt-15 md:mt-25"></div>

<div class="p-8 motion-preset-focus max-w-7xl mx-auto">
    <h1 class="text-4xl font-serif mb-6">Autorización de Libros</h1>

    @if(session('success'))
        <div class="alert alert-success mb-6 shadow-sm">
            <span class="icon-[tabler--check] text-xl"></span>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    <div class="overflow-x-auto bg-base-200 rounded-lg shadow-sm">
        <table class="table font-inconsolata w-full">
            <thead>
                <tr>
                    <th>Portada</th>
                    <th>Título</th>
                    <th>Autor (Pluma)</th>
                    <th>Fecha Envío</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($books as $book)
                <tr class="hover">
                    <td>
                        <div class="avatar">
                            <div class="w-12 h-16 rounded-sm">
                                <img src="{{ $book->portada ? asset('storage/' . $book->portada) : asset('assets/img/book_cover_mockup.jpg') }}" alt="Portada" />
                            </div>
                        </div>
                    </td>
                    <td class="font-bold">{{ $book->titulo }}</td>
                    <td>{{ $book->writer->nombre_pluma ?? 'Desconocido' }}</td>
                    <td>{{ $book->updated_at->format('d/m/Y') }}</td>
                    <td>
                        <a href="{{ route('admin.books.show', $book) }}" class="btn btn-sm btn-primary">
                            Revisar
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center text-base-content/50 py-10">
                        No hay libros pendientes de revisión. ¡Todo al día!
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <div class="mt-4">
        {{ $books->links() }}
    </div>
</div>
@endsection
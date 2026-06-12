@extends('layouts.app')

@section('content')
@include('components.navbar')

<div class="mt-15 md:mt-25"></div>

<div class="p-8 motion-preset-focus max-w-7xl mx-auto">
    <h1 class="text-4xl font-serif mb-6">Panel Admin</h1>

    @include('admin.tabs', ['activeTab' => 'writers'])

    <h2 class="text-2xl font-serif mb-4">Autorización de Escritores</h2>

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
                    <th>Usuario</th>
                    <th>Nombre de pluma</th>
                    <th>Documento</th>
                    <th>Email</th>
                    <th>Fecha solicitud</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($writers as $writer)
                <tr class="hover">
                    <td class="font-bold">{{ $writer->user->name ?? 'N/A' }}</td>
                    <td>{{ $writer->nombre_pluma }}</td>
                    <td>{{ strtoupper($writer->tipo_documento ?? '') }} {{ $writer->documento_identidad }}</td>
                    <td>{{ $writer->user->email ?? '—' }}</td>
                    <td>{{ $writer->created_at?->format('d/m/Y') }}</td>
                    <td>
                        <a href="{{ route('admin.writers.show', $writer) }}" class="btn btn-sm btn-primary">
                            Revisar
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center text-base-content/50 py-10">
                        No hay solicitudes de escritores pendientes. ¡Todo al día!
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $writers->links() }}
    </div>
</div>
@endsection

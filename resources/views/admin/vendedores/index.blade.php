@extends('layouts.app')

@section('content')
@include('components.navbar')

<div class="mt-15 md:mt-25"></div>

<div class="p-4 sm:p-6 md:p-8 motion-preset-focus max-w-7xl mx-auto">
    <h1 class="text-2xl sm:text-3xl md:text-4xl font-serif mb-4 md:mb-6">Panel Admin</h1>

    @include('admin.tabs', ['activeTab' => 'vendedores'])

    <h2 class="text-lg sm:text-xl md:text-2xl font-serif mb-4">Autorización de Vendedores</h2>

    @if(session('success'))
        <div class="alert alert-success mb-6 shadow-sm">
            <span class="icon-[tabler--check] text-xl"></span>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    {{-- TABLA: visible en md+ --}}
    <div class="hidden md:block overflow-x-auto bg-base-200 rounded-lg shadow-sm">
        <table class="table font-inconsolata w-full">
            <thead>
                <tr>
                    <th>Usuario</th>
                    <th>Nombre público</th>
                    <th>Documento</th>
                    <th>Email</th>
                    <th>Fecha solicitud</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($vendedores as $vendedor)
                <tr class="hover">
                    <td class="font-bold">{{ $vendedor->user->name ?? 'N/A' }}</td>
                    <td>{{ $vendedor->nombre_publico }}</td>
                    <td>{{ strtoupper($vendedor->tipo_documento ?? '') }} {{ $vendedor->documento_identidad }}</td>
                    <td>{{ $vendedor->user->email ?? '—' }}</td>
                    <td>{{ $vendedor->created_at?->format('d/m/Y') }}</td>
                    <td>
                        <a href="{{ route('admin.vendedores.show', $vendedor) }}" class="btn btn-sm btn-primary">
                            Revisar
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center text-base-content/50 py-10">
                        No hay solicitudes de vendedores pendientes. ¡Todo al día!
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- CARDS: visible solo en mobile --}}
    <div class="md:hidden space-y-3">
        @forelse ($vendedores as $vendedor)
            <div class="bg-base-200 rounded-lg shadow-sm p-4 font-inconsolata">
                <div class="flex justify-between items-start gap-2 mb-2">
                    <div class="min-w-0 flex-1">
                        <h3 class="font-bold text-sm truncate">{{ $vendedor->user->name ?? 'N/A' }}</h3>
                        <p class="text-xs text-primary truncate">{{ $vendedor->nombre_publico }}</p>
                    </div>
                    <span class="text-xs text-base-content/50 shrink-0">{{ $vendedor->created_at?->format('d/m/Y') }}</span>
                </div>
                <p class="text-xs text-base-content/70 truncate"><span class="icon-[tabler--mail] mr-1"></span>{{ $vendedor->user->email ?? '—' }}</p>
                <p class="text-xs text-base-content/70 truncate"><span class="icon-[tabler--id] mr-1"></span>{{ strtoupper($vendedor->tipo_documento ?? '') }} {{ $vendedor->documento_identidad }}</p>
                <a href="{{ route('admin.vendedores.show', $vendedor) }}" class="btn btn-xs btn-primary mt-3 w-full">
                    Revisar
                </a>
            </div>
        @empty
            <div class="bg-base-200 rounded-lg p-6 text-center text-base-content/50">
                No hay solicitudes de vendedores pendientes. ¡Todo al día!
            </div>
        @endforelse
    </div>

    <div class="mt-4">
        {{ $vendedores->links() }}
    </div>
</div>
@endsection

@extends('layouts.app')

@section('content')

@include('components.navbar')

<div class="mt-15 md:mt-25"></div>


<div class="p-4 sm:p-6 md:p-8 motion-preset-focus max-w-7xl mx-auto">


    <h1 class="text-2xl sm:text-3xl md:text-4xl font-serif mb-4 md:mb-6">
        Panel Admin
    </h1>


    @include('admin.tabs', ['activeTab' => 'withdrawals'])



    <h2 class="text-lg sm:text-xl md:text-2xl font-serif mb-4">
        Solicitudes de retiro
    </h2>



    @if(session('success'))

        <div class="alert alert-success mb-6 shadow-sm">

            <span class="icon-[tabler--check] text-xl"></span>

            <span>
                {{ session('success') }}
            </span>

        </div>

    @endif



    @if(session('error'))

        <div class="alert alert-error mb-6 shadow-sm">

            <span class="icon-[tabler--alert-circle] text-xl"></span>

            <span>
                {{ session('error') }}
            </span>

        </div>

    @endif





    {{-- TABLA DESKTOP --}}
    <div class="hidden md:block overflow-x-auto bg-base-200 rounded-lg shadow-sm">


        <table class="table font-inconsolata w-full">


            <thead>

                <tr>

                    <th>Usuario</th>

                    <th>Tipo</th>

                    <th>Wallet</th>

                    <th>Estado</th>

                    <th>Fecha solicitud</th>

                    <th>Acciones</th>

                </tr>

            </thead>



            <tbody>


            @forelse($withdrawals as $withdrawal)


                <tr class="hover">


                    <td class="font-bold">


                        @if($withdrawal->writer)

                            {{ $withdrawal->writer->user->name ?? 'N/A' }}

                        @elseif($withdrawal->vendedor)

                            {{ $withdrawal->vendedor->user->name ?? 'N/A' }}

                        @else

                            Usuario desconocido

                        @endif


                    </td>



                    <td>


                        @if($withdrawal->writer)

                            <span class="badge badge-primary">
                                Escritor
                            </span>

                        @elseif($withdrawal->vendedor)

                            <span class="badge badge-secondary">
                                Vendedor
                            </span>

                        @endif


                    </td>




                    <td>

                        #{{ $withdrawal->wallet_id }}

                    </td>




                    <td>

                        <span class="badge badge-warning">

                            {{ ucfirst($withdrawal->estado) }}

                        </span>

                    </td>




                    <td>

                        {{ $withdrawal->created_at?->format('d/m/Y') }}

                    </td>




                    <td>


                        <a href="{{ route('admin.retiros.show',$withdrawal) }}" class="btn btn-sm btn-primary">

                            Revisar

                        </a>


                    </td>



                </tr>



            @empty


                <tr>

                    <td colspan="6"
                    class="text-center text-base-content/50 py-10">


                        No existen solicitudes de retiro pendientes.


                    </td>

                </tr>


            @endforelse



            </tbody>


        </table>


    </div>







    {{-- CARDS MOBILE --}}
    <div class="md:hidden space-y-3">


    @forelse($withdrawals as $withdrawal)


        <div class="bg-base-200 rounded-lg shadow-sm p-4 font-inconsolata">



            <div class="flex justify-between items-start gap-2 mb-2">


                <div>


                    <h3 class="font-bold text-sm">


                    @if($withdrawal->writer)

                        {{ $withdrawal->writer->user->name ?? 'N/A' }}

                    @elseif($withdrawal->vendedor)

                        {{ $withdrawal->vendedor->user->name ?? 'N/A' }}

                    @endif


                    </h3>



                    <p class="text-xs text-primary">


                        @if($withdrawal->writer)

                            Escritor

                        @else

                            Vendedor

                        @endif


                    </p>


                </div>



                <span class="text-xs text-base-content/50">

                    {{ $withdrawal->created_at?->format('d/m/Y') }}

                </span>


            </div>




            <p class="text-xs text-base-content/70">

                <span class="icon-[tabler--wallet] mr-1"></span>

                Wallet #{{ $withdrawal->wallet_id }}

            </p>




            <p class="text-xs text-base-content/70 mt-1">

                Estado:

                {{ ucfirst($withdrawal->estado) }}

            </p>




            <a 
            href="{{ route('admin.retiros.show',$withdrawal) }}"
            class="btn btn-xs btn-primary mt-3 w-full">


                Revisar


            </a>




        </div>



    @empty


        <div class="bg-base-200 rounded-lg p-6 text-center text-base-content/50">


            No existen solicitudes de retiro pendientes.


        </div>


    @endforelse



    </div>




    <div class="mt-4">

        {{ $withdrawals->links() }}

    </div>



</div>


@endsection
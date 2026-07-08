@extends('layouts.app')

@section('content')

@include('components.navbar')


<div class="mt-15 md:mt-25"></div>


<div class="p-4 sm:p-6 md:p-8 pb-0 max-w-7xl mx-auto">


    {{-- BARRA STICKY --}}
    <div class="sticky top-20 md:top-25 z-30 -mx-2 mb-6 md:mb-8 px-2">

        <div class="flex flex-wrap gap-2 justify-between items-center
                    bg-base-100/70 glass border border-base-300 rounded-xl
                    shadow-md px-3 py-2 md:px-4 md:py-3">


            <a href="{{ route('admin.retiros.index') }}"
               class="btn btn-outline btn-sm font-inconsolata">

                <span class="icon-[tabler--arrow-left]"></span>
                Volver

            </a>



            <span class="badge badge-lg font-inconsolata">

                {{ ucfirst($withdrawal->estado) }}

            </span>



            @if($withdrawal->estado === 'pendiente')

            <div class="flex gap-2">


                <form method="POST"
                      action="{{ route('admin.retiros.reject',$withdrawal) }}">

                    @csrf


                    <button class="btn btn-error btn-sm text-white">

                        <span class="icon-[tabler--x]"></span>
                        Rechazar

                    </button>

                </form>




                <form method="POST"
                      action="{{ route('admin.retiros.approve',$withdrawal) }}">

                    @csrf


                    <button class="btn btn-success btn-sm text-white">

                        <span class="icon-[tabler--check]"></span>
                        Aprobar

                    </button>


                </form>


            </div>

            @endif



        </div>

    </div>





    {{-- TITULO --}}
    <div class="text-center mb-8">


        <div class="avatar mb-4">

            <div class="w-24 h-24 rounded-full border-4 border-primary shadow-lg overflow-hidden">


                @if($withdrawal->writer)

                    <img src="{{ 
                        $withdrawal->writer->user?->avatar 
                        ? asset('storage/'.$withdrawal->writer->user->avatar)
                        : asset('assets/img/default_avatar.jpg')
                    }}">


                @elseif($withdrawal->vendedor)

                    <img src="{{ 
                        $withdrawal->vendedor->user?->avatar 
                        ? asset('storage/'.$withdrawal->vendedor->user->avatar)
                        : asset('assets/img/default_avatar.jpg')
                    }}">


                @endif


            </div>

        </div>




        <h1 class="text-3xl md:text-5xl font-serif">

            Solicitud de retiro

        </h1>



        <p class="font-inconsolata text-base-content/70">

            Enviada:
            {{ $withdrawal->created_at?->format('d/m/Y H:i') }}

        </p>


    </div>






    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 font-inconsolata">





        {{-- USUARIO --}}
        <div class="bg-base-200 p-6 rounded-xl shadow">


            <h3 class="font-bold text-lg mb-4 border-b pb-2">

                <span class="icon-[tabler--user] text-primary"></span>

                Usuario

            </h3>



            @if($withdrawal->writer)


                <p>
                    <strong>Tipo:</strong>
                    Escritor
                </p>


                <p>
                    <strong>Nombre:</strong>
                    {{ $withdrawal->writer->user->name ?? '—' }}
                </p>


                <p>
                    <strong>Email:</strong>
                    {{ $withdrawal->writer->user->email ?? '—' }}
                </p>



            @elseif($withdrawal->vendedor)



                <p>
                    <strong>Tipo:</strong>
                    Vendedor
                </p>


                <p>
                    <strong>Nombre:</strong>
                    {{ $withdrawal->vendedor->user->name ?? '—' }}
                </p>


                <p>
                    <strong>Email:</strong>
                    {{ $withdrawal->vendedor->user->email ?? '—' }}
                </p>


            @endif


        </div>







        {{-- WALLET --}}
        <div class="bg-base-200 p-6 rounded-xl shadow">


            <h3 class="font-bold text-lg mb-4 border-b pb-2">


                <span class="icon-[tabler--wallet] text-primary"></span>

                Wallet


            </h3>



            <p>

                <strong>ID Wallet:</strong>

                {{ $withdrawal->wallet_id }}

            </p>



            @if($withdrawal->wallet)

            <p>

                <strong>Saldo disponible:</strong>

                ${{ number_format($withdrawal->wallet->saldo_disponible,2) }}

            </p>


            <p>

                <strong>Saldo retenido:</strong>

                ${{ number_format($withdrawal->wallet->saldo_retenido,2) }}

            </p>


            @endif


        </div>






    
        {{-- MONTO RETIRO --}}
<div class="bg-base-200 p-6 rounded-xl shadow md:col-span-2">

    <h3 class="font-bold text-lg mb-4 border-b pb-2">

        <span class="icon-[tabler--currency-dollar] text-primary"></span>

        Monto solicitado

    </h3>


    <div class="flex justify-between items-center">

        <span class="font-semibold">
            Valor a retirar:
        </span>


        <span class="text-3xl font-bold text-primary">

            ${{ number_format($withdrawal->monto, 2) }}

        </span>

    </div>


</div>




{{-- PAYPAL --}}
<div class="bg-base-200 p-6 rounded-xl shadow md:col-span-2">

    <h3 class="font-bold text-lg mb-4 border-b pb-2">

        <span class="icon-[tabler--brand-paypal] text-primary"></span>

        Cuenta PayPal

    </h3>


    @if($withdrawal->paypal_email)

        <p>
            <strong>Email PayPal:</strong>
            {{ $withdrawal->paypal_email }}
        </p>


        @if($withdrawal->paypal_merchant_id)

            <p>
                <strong>Merchant ID:</strong>
                {{ $withdrawal->paypal_merchant_id }}
            </p>

        @endif


    @else

        <p class="text-base-content/50">

            No existe cuenta PayPal registrada.

        </p>

    @endif


</div>




    </div>



</div>


@endsection























{{-- MODALES --}}
@if($withdrawal->estado === 'pendiente')


{{-- MODAL APROBAR --}}
<div id="modal-aprobar-withdrawal"
     class="hidden fixed inset-0 z-50 flex items-center justify-center p-4">


    <div class="absolute inset-0 bg-black/60 backdrop-blur-sm"
         onclick="document.getElementById('modal-aprobar-withdrawal').classList.add('hidden')">
    </div>



    <div class="relative bg-base-100 rounded-2xl shadow-2xl p-6 md:p-8 max-w-md w-full motion-preset-pop">


        <div class="flex justify-center mb-4">

            <div class="size-16 rounded-full bg-emerald-100 flex items-center justify-center">

                <span class="icon-[tabler--check] text-emerald-600 text-4xl"></span>

            </div>

        </div>



        <h2 class="font-serif text-xl md:text-2xl text-center mb-2">

            ¿Aprobar este retiro?

        </h2>



        <p class="font-inconsolata text-center text-base-content/70 mb-6 text-sm md:text-base">


            El retiro solicitado por

            <strong>

            @if($withdrawal->writer)

                {{ $withdrawal->writer->user->name ?? 'Usuario' }}

            @elseif($withdrawal->vendedor)

                {{ $withdrawal->vendedor->user->name ?? 'Usuario' }}

            @endif

            </strong>

            será marcado como completado.


        </p>




        <div class="flex flex-col sm:flex-row gap-3 justify-center">


            <button type="button"

                    onclick="document.getElementById('modal-aprobar-withdrawal').classList.add('hidden')"

                    class="btn btn-outline font-inconsolata order-2 sm:order-1">

                Cancelar

            </button>




            <form action="{{ route('admin.retiros.approve',$withdrawal) }}"
                  method="POST"
                  class="order-1 sm:order-2">


                @csrf
             

                <button type="submit"

                        class="font-inconsolata font-bold inline-flex items-center justify-center gap-2 px-5 py-2 rounded-lg w-full
                               bg-emerald-600 hover:bg-emerald-700 text-white shadow-md
                               transition-transform duration-200 hover:scale-105">


                    <span class="icon-[tabler--check]"></span>

                    Sí, aprobar


                </button>


            </form>


        </div>


    </div>


</div>







{{-- MODAL RECHAZAR --}}
<div id="modal-rechazar-withdrawal"
     class="hidden fixed inset-0 z-50 flex items-center justify-center p-4">


    <div class="absolute inset-0 bg-black/60 backdrop-blur-sm"

         onclick="document.getElementById('modal-rechazar-withdrawal').classList.add('hidden')">

    </div>




    <div class="relative bg-base-100 rounded-2xl shadow-2xl p-6 md:p-8 max-w-md w-full motion-preset-pop">



        <div class="flex justify-center mb-4">


            <div class="size-16 rounded-full bg-rose-100 flex items-center justify-center">


                <span class="icon-[tabler--x] text-rose-600 text-4xl"></span>


            </div>


        </div>




        <h2 class="font-serif text-xl md:text-2xl text-center mb-2">

            ¿Rechazar este retiro?

        </h2>





        <p class="font-inconsolata text-center text-base-content/70 mb-6 text-sm md:text-base">


            La solicitud será marcada como rechazada.


        </p>





        <div class="flex flex-col sm:flex-row gap-3 justify-center">


            <button type="button"

                    onclick="document.getElementById('modal-rechazar-withdrawal').classList.add('hidden')"

                    class="btn btn-outline font-inconsolata order-2 sm:order-1">


                Cancelar


            </button>





            <form action="{{ route('admin.retiros.reject',$withdrawal) }}"
                  method="POST"
                  class="order-1 sm:order-2">


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






<script>

document.addEventListener('keydown', function (e) {


    if(e.key === 'Escape'){


        document.getElementById('modal-aprobar-withdrawal')
            ?.classList.add('hidden');


        document.getElementById('modal-rechazar-withdrawal')
            ?.classList.add('hidden');


    }


});


</script>


@endif
@extends('layouts.app')

@section('content')

@include('components.navbar')

<div class="mt-15 md:mt-25"></div>

<div class="p-8 pb-0 motion-preset-focus">

    {{-- HEADER --}}
    <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-6">

        <div>

            <div class="flex items-center gap-3">

                <div class="bg-primary/10 text-primary p-3 rounded-md">
                    <span class="icon-[tabler--shopping-cart] text-3xl"></span>
                </div>

                <div>

                    <h1 class="text-5xl font-serif">
                        Carrito de compra
                    </h1>

                    <p class="font-inconsolata text-base-content/60 mt-2">
                        Revisa tus libros antes de completar tu compra
                    </p>

                </div>

            </div>

        </div>

        @if($cart && $cart->items->count())
        <div class="badge badge-primary badge-lg p-5 font-inconsolata">
            {{ $cart->items->count() }} libro(s)
        </div>
        @endif

    </div>

</div>

{{-- CARRITO VACÍO --}}
@if(!$cart || $cart->items->count() === 0)

<div class="p-8">

    <div class="bg-base-200 border border-base-content/10 rounded-md p-10 text-center motion-preset-slide-up">

        <div class="flex justify-center">

            <div class="bg-base-300 w-28 h-28 rounded-md flex items-center justify-center">

                <span class="icon-[tabler--shopping-cart-off] text-6xl text-base-content/30"></span>

            </div>

        </div>

        <h2 class="text-3xl font-serif mt-8">
            Tu carrito está vacío
        </h2>

        <p class="font-inconsolata text-base-content/60 mt-4 max-w-xl mx-auto">
            Aún no has agregado libros a tu carrito. Descubre nuevas historias,
            autores y contenido exclusivo dentro de nuestra estantería virtual.
        </p>

        <div class="mt-8">

            <a 
                href="{{ route('index') }}"
                class="btn btn-primary btn-lg rounded-md"
            >
                <span class="icon-[tabler--book]"></span>
                Explorar libros
            </a>

        </div>

    </div>

</div>

@else

{{-- CONTENIDO --}}
<div class="px-8 mt-10 flex flex-col xl:flex-row gap-8 md:mb-8">

    {{-- LIBROS --}}
    <div class="w-full xl:w-2/3">

        <div class="space-y-6">

            @foreach($cart->items as $item)

            @php
                $book = $item->book;
            @endphp

            <div class="bg-base-200 border border-base-content/10 rounded-md p-5 motion-preset-slide-right hover:border-primary/30 duration-300">

                <div class="flex flex-col md:flex-row gap-5">

                    {{-- PORTADA --}}
                    <div class="w-full md:w-44 shrink-0">

                        <img 
                            src="{{ $book->portada 
                                ? asset('storage/' . $book->portada)
                                : asset('assets/img/book_cover_mockup.jpg') }}"
                            class="rounded-md w-full h-64 md:h-full object-cover shadow-lg"
                        >

                    </div>

                    {{-- INFO --}}
                    <div class="flex-1 flex flex-col justify-between">

                        <div>

                            <div class="flex flex-wrap gap-2 items-center">

                                <span class="badge badge-primary">
                                    {{ $book->category->nombre ?? 'Sin categoría' }}
                                </span>

                                <span class="text-xs text-base-content/50 font-inconsolata">
                                    {{ $book->idioma }}
                                </span>

                            </div>

                            <h2 class="text-3xl font-serif mt-4">
                                {{ $book->titulo }}
                            </h2>

                            <p class="font-inconsolata text-base-content/60 mt-2">
                                Por {{ $book->writer->nombre_pluma ?? 'Autor desconocido' }}
                            </p>

                            <p class="font-inconsolata text-sm mt-5 line-clamp-3 text-base-content/70">
                                {{ $book->descripcion_corta }}
                            </p>

                        </div>

                        {{-- FOOTER --}}
                        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-5 mt-8">

                            <div class="flex items-center gap-3">

                                <div class="badge badge-outline p-4 font-inconsolata">
                                    Cantidad: {{ $item->cantidad }}
                                </div>

                                <div class="badge badge-outline p-4 font-inconsolata">
                                    ${{ number_format($item->precio_unitario, 2) }}
                                </div>

                            </div>

                            <div class="flex items-center justify-between gap-4">

                                <div class="text-right">

                                    <p class="font-inconsolata text-xs text-base-content/50">
                                        Subtotal
                                    </p>

                                    <div class="text-3xl font-serif text-primary">
                                        ${{ number_format($item->subtotal, 2) }}
                                    </div>

                                </div>

                                {{-- ELIMINAR --}}
                                <form 
                                    method="POST"
                                    action="{{ route('cart.remove', $item) }}"
                                >

                                    @csrf
                                    @method('DELETE')

                                    <button 
                                        class="btn btn-error btn-soft rounded-md"
                                        type="submit"
                                    >
                                        <span class="icon-[tabler--trash] text-primary"></span>
                                    </button>

                                </form>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

            @endforeach

        </div>

    </div>

    {{-- RESUMEN --}}
    <div class="w-full xl:w-1/3">

        <div class="bg-base-200 border border-base-content/10 rounded-md p-8 sticky top-30 motion-preset-slide-left">

            <div class="flex items-center gap-3">

                <div class="bg-primary/10 text-primary p-3 rounded-md">
                    <span class="icon-[tabler--receipt-2] text-2xl"></span>
                </div>

                <div>

                    <h2 class="text-2xl font-serif">
                        Resumen
                    </h2>

                    <p class="font-inconsolata text-sm text-base-content/60">
                        Detalle del pedido
                    </p>

                </div>

            </div>

            <div class="divider my-3"></div>

            {{-- RESUMEN --}}
            <div class="space-y-5 font-inconsolata my-3">

                <div class="flex justify-between items-center">

                    <span class="text-base-content/70">
                        Libros
                    </span>

                    <span>
                        {{ $cart->items->count() }}
                    </span>

                </div>

                <div class="flex justify-between items-center">

                    <span class="text-base-content/70">
                        Subtotal
                    </span>

                    <span>
                        ${{ number_format($subtotal, 2) }}
                    </span>

                </div>

                <div class="flex justify-between items-center">

                    <span class="text-base-content/70">
                        Comisión
                    </span>

                    <span>
                        ${{ number_format($comision, 2) }}
                    </span>

                </div>

                <div class="flex justify-between items-center">

                    <span class="text-base-content/70">
                        Impuestos
                    </span>

                    <span>
                        ${{ number_format($impuestos, 2) }}
                    </span>

                </div>

            </div>

            <div class="divider my-3"></div>

            {{-- TOTAL --}}
            <div class="flex justify-between items-center">

                <div>

                    <p class="font-inconsolata text-sm text-base-content/60">
                        Total a pagar
                    </p>

                    <div class="text-4xl font-serif text-primary mt-1">
                        ${{ number_format($total, 2) }}
                    </div>

                </div>

            </div>

            {{-- PAYPAL --}}
            <div class="mt-8">

                <form action="{{ route('paypal.create') }}" method="POST">

                    @csrf

                    <button
                        type="submit"
                        class="btn btn-accent w-full text-base sm:text-lg py-3 shadow-lg hover:scale-102 transition-transform flex items-center justify-center gap-2"
                    >
                        <span class="icon-[tabler--brand-paypal] text-xl sm:text-2xl shrink-0"></span>
                        <span>Pagar con PayPal (${{ number_format($total, 2) }})</span>
                    </button>

                </form>

            </div>

            {{-- CONTINUAR --}}
            <div class="mt-4">

                <a 
                    href="{{ route('index') }}"
                    class="btn btn-soft w-full rounded-md"
                >
                    <span class="icon-[tabler--arrow-left]"></span>
                    Seguir comprando
                </a>

            </div>

        </div>

    </div>

</div>

@endif

@endsection
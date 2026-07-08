@extends('layouts.app')

@section('content')

@include('components.navbar')

<div class="mt-15 md:mt-25"></div>

<div class="flex justify-left md:justify-center gap-x-8 px-8 motion-preset-focus">

    <div class="text-base-content py-8 w-3/5 hidden md:block">
        <h1 class="text-5xl font-serif">Movimientos</h1>
        <h2 class="font-inconsolata">Todas las transacciones de tu billetera.</h2>
    </div>

    <div class="flex justify-between items-center py-8 w-full md:w-2/5">

        <div class="text-left">
            <h1 class="text-3xl font-serif">{{ Auth::user()->name }}</h1>
            <h1 class="font-inconsolata text-primary">{{ $user->perfil->nombres }} {{ $user->perfil->apellidos }}</h1>
        </div>

        <div class="avatar flex items-center">
            <div class="size-16 rounded-full border-4 border-primary">
                <img src="
                        @if(Auth::user()->avatar)
                            {{ str_contains(Auth::user()->avatar, 'http')
                                ? Auth::user()->avatar
                                : asset('storage/' . Auth::user()->avatar) }}
                        @else
                            {{ asset('assets/img/default_avatar.jpg') }}
                        @endif
                        "
                    alt="Avatar"
                />
            </div>
        </div>

    </div>

</div>

<div class="md:flex justify-center px-8 gap-8 mb-8">

  <div class="font-inconsolata stats w-full shadow-md bg-linear-to-r from-accent/30 to-accent rounded-md p-6 motion-preset-slide-right">

    <div class="stat">
      <div class="stat-title">Saldo disponible</div>
      <div class="stat-value text-3xl font-serif">
        ${{ number_format($wallet->saldo_disponible ?? 0, 2) }}
      </div>
      <div class="stat-desc">
        Fondos listos para retirar
      </div>
    </div>

    <div class="stat text-right">
      <div class="stat-title">Movimientos registrados</div>
      <div class="stat-value text-xl text-primary font-serif">
        {{ $movimientos->total() }}
      </div>
      <div class="stat-desc">
        <a href="{{ route('writer.withdraw_history') }}" class="link">Ver historial de retiros</a>
      </div>
    </div>

  </div>

</div>

<div class="md:flex justify-center px-8 gap-8 mb-8">

    <div class="border border-base-content/20 w-full rounded-md p-8 motion-preset-slide-right">

        <div class="flex justify-between items-center mb-4">
            <h1 class="text-xl font-serif">Movimientos de la billetera</h1>
            <a href="{{ route('perfil') }}" class="font-inconsolata link">Volver al perfil</a>
        </div>

        @if($movimientos->isEmpty())

            <div class="font-inconsolata text-base-content/70 py-8 text-center">
                Aún no tienes movimientos registrados en tu billetera.
            </div>

        @else

        <div class="w-full overflow-x-auto font-inconsolata">
            <table class="table-striped table">
                <thead>
                <tr>
                    <th>Nº</th>
                    <th>Tipo</th>
                    <th>Descripción</th>
                    <th>Monto</th>
                    <th>Fecha</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($movimientos as $mov)
                <tr>
                    <td>{{ $mov->id }}</td>
                    <td>
                        <span class="badge text-xs capitalize
                            @if($mov->tipo == 'ingreso')
                                badge-success
                            @elseif($mov->tipo == 'retiro')
                                badge-warning
                            @else
                                badge-neutral
                            @endif
                        ">{{ $mov->tipo }}</span>
                    </td>
                    <td>{{ $mov->descripcion ?? '—' }}</td>
                    <td class="@if($mov->tipo == 'ingreso') text-success @elseif($mov->tipo == 'retiro') text-error @endif">
                        {{ $mov->tipo == 'retiro' ? '-' : '+' }}${{ number_format($mov->monto, 2) }}
                    </td>
                    <td>{{ $mov->created_at->format('d/m/Y H:i') }}</td>
                </tr>
                @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $movimientos->links() }}
        </div>

        @endif

    </div>

</div>

<div class="flex justify-end p-8 pt-0 intersect:motion-preset-focus">
    <form method="POST" class="w-full md:w-auto" action="{{ route('logout') }}">
        @csrf
            <button class="btn btn-primary w-full md:w-auto" type="submit">
                <span class="icon-[tabler--logout-2] size-5"></span>
                    Salir
            </button>
    </form>
</div>

@endsection

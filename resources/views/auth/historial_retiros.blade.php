@extends('layouts.app')

@section('content')

@include('components.navbar')

<div class="mt-15 md:mt-25"></div>

<div class="flex justify-left md:justify-center gap-x-8 px-8 motion-preset-focus">

    <div class="text-base-content py-8 w-3/5 hidden md:block">
        <h1 class="text-5xl font-serif">Perfil</h1>
        <h2 class="font-inconsolata">Observa todos los detalles de tu perfil aquí.</h2>
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

@if(auth()->check() && auth()->user()->isWriter())

<div class="md:flex justify-center px-8 gap-8 mb-8">

  <div class="font-inconsolata stats w-full md:w-3/5 shadow-md bg-linear-to-r from-accent/30 to-accent rounded-md p-6 motion-preset-slide-right">

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
      <div class="stat-title">Último retiro</div>
      <div class="stat-value text-xl text-primary font-serif">
        ${{ $lastWithdraw ? number_format($lastWithdraw->monto, 2) : '0.00' }}
      </div>
      <div class="stat-desc">
        {{ $lastWithdraw ? ucfirst($lastWithdraw->estado) : 'Sin solicitudes' }}
      </div>
    </div>

  </div>

  <div class="w-full border border-base-content/20 md:w-2/5 rounded-md p-6 motion-preset-slide-left mt-8 md:mt-0">

    @if($hasPending)

    <div class="bg-warning font-inconsolata p-5 rounded-md h-full flex items-center">
        <div class="text-warning-content">
            Ya tienes una solicitud en proceso. Espera a que sea aprobada.
            <a href="{{ route('writer.withdraw_history') }}" class="font-inconsolata text-warning-content link text-center">Historial de retiros</a>
        </div>
    </div>

    @else

    <div class="flex justify-between mb-4">
        <h1 class="text-xl font-serif text-center">Solicitar retiro</h1>
        <a href="{{ route('writer.withdraw_history') }}" class="font-inconsolata link text-center">Historial de retiros</a>
    </div>
    
    <form method="POST" action="{{ route('writer.withdraw.store') }}">
        @csrf

        <div class="input w-full">
  
            <span class="text-base-content/80 my-auto shrink-0">
                $
            </span>

            <div class="input-floating grow">
                
                <input 
                type="text"
                placeholder="0.00"
                class="ps-3"
                id="monto"
                name="monto"
                />

                <label class="input-floating-label" for="monto">
                Monto
                </label>

            </div>

        </div>

      <button type="submit" class="btn btn-accent w-full mt-4"><span class="icon-[tabler--brand-paypal]"></span>Solicitar retiro</button>

      @if ($errors->any())
        <div class="font-inconsolata text-sm text-red-500 mt-3">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
    @endif

    </form>

    @endif

  </div>

</div>

@endif

<div class="md:flex justify-center px-8 gap-8 mb-8">

    <div class="border border-base-content/20 w-full rounded-md p-8 motion-preset-slide-right">

        <div class="text-left">
            <h1 class="text-xl font-serif">Historial de Retiros</h1>
        </div>

        <div class="w-full overflow-x-auto font-inconsolata">
            <table class="table-striped table">
                <thead>
                <tr>
                    <th>Nº</th>
                    <th>Monto</th>
                    <th>Estado</th>
                    <th>Creado</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($payouts as $payout)
                <tr>
                    <td>{{ $payout->id }}</td>
                    <td>${{ $payout->monto }}</td>
                    <td><span class="badge text-xs capitalize
                            @if($payout->estado == 'aprobado')
                                badge-success
                            @elseif($payout->estado == 'pendiente')
                                badge-warning
                            @elseif($payout->estado == 'rechazado')
                                badge-error
                            @else
                                badge-neutral
                            @endif
                        ">{{ $payout->estado }}</span></td>
                    <td>{{ $payout->created_at->format('d/m/Y') }}</td>
                </tr>
                @endforeach
                </tbody>
            </table>
            </div>

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
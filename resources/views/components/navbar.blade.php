<nav class="font-inconsolata fixed top-3 left-1/2 -translate-x-1/2 w-[98%] rounded-lg border border-base-300 shadow-md z-50">

    <div class="motion-preset-fade navbar md:h-22 px-6 rounded-lg bg-base-200/50 glass">

        <div class="w-full md:flex md:items-center md:justify-between md:gap-2">

            <div class="flex items-center justify-between w-auto">

                <div class="navbar-start items-center justify-between max-md:w-full">

                    <img src="{{ asset('assets/img/logo_navbar.png') }}" class="w-10 md:w-40 h-auto" alt="Mi Estantería">

                    <div class="md:hidden">
                        <button type="button" class="collapse-toggle btn btn-outline btn-primary btn-sm btn-square" data-collapse="#navbar-collapse">
                            <span class="icon-[tabler--menu-2] collapse-open:hidden size-4"></span>
                            <span class="icon-[tabler--x] collapse-open:block hidden size-4"></span>
                        </button>
                    </div>

                </div>

            </div>


            <div id="navbar-collapse" class="collapse hidden md:flex md:justify-center md:flex-1 overflow-hidden transition-[height] duration-300 max-md:w-full">

                <ul class="menu md:menu-horizontal gap-2 p-0 text-base-content max-md:mt-2">

                    @auth
                    <div class="mt-2 flex gap-2">
                        <a class="btn btn-primary w-1/2 md:hidden text-sm md:text-lg" href="{{ route('perfil') }}">
                            <div class="avatar">
                                <div class="size-5 rounded-full">
                                    <img src="{{ Auth::user()->avatar }}" alt="avatar" />
                                </div>
                            </div>
                            Perfil
                        </a>

                        <form method="POST" class="w-1/2"action="{{ route('logout') }}">
                            @csrf
                            <button class="btn btn-primary w-full md:hidden text-sm md:text-lg" type="submit">
                                <span class="icon-[tabler--logout-2] size-5"></span>
                                Salir
                            </button>
                        </form>
                    </div>
                    @else
                    <a class="btn btn-primary w-full md:hidden mt-2 text-sm md:text-lg" href="{{ route('login') }}">
                        Ingresar
                    </a>
                    @endauth

                    <button class="btn md:hidden font-light" href="#"><span class="icon-[tabler--shopping-cart] size-5"></span></button>

                    <li><a class="hover:text-primary text-sm md:text-lg" href="{{ route('index') }}">Inicio</a></li>

                    <li class="dropdown relative inline-flex [--auto-close:inside] [--offset:8] [--placement:bottom-end]">
                        <button type="button" class="hover:text-primary text-sm md:text-lg dropdown-toggle dropdown-open:bg-base-content/10 dropdown-open:text-base-content">
                            Explorar
                            <span class="icon-[tabler--chevron-down] dropdown-open:rotate-180 size-4"></span>
                        </button>

                        <ul class="dropdown-menu dropdown-open:opacity-100 hidden bg-base-300/50 glass">
                            <li><a class="hover:text-primary text-sm md:text-lg dropdown-item" href="#">Todos</a></li>
                            <li><a class="hover:text-primary text-sm md:text-lg dropdown-item" href="{{ route('index') }}#mas_vendidos">Más Vendidos</a></li>
                            <li><a class="hover:text-primary text-sm md:text-lg dropdown-item" href="{{ route('index') }}#novedades">Novedades</a></li>
                        </ul>
                    </li>

                    <li><a class="hover:text-primary text-sm md:text-lg" href="{{ route('index') }}#contacto">Contacto</a></li>

                </ul>

            </div>

            <div class="items-center justify-between w-auto hidden md:flex gap-3">

                <button class="btn bg-none font-light" href="#"><span class="icon-[tabler--shopping-cart] size-5"></span></button>

                @auth
                <a href="{{ route('perfil') }}">
                    <div class="avatar">
                        <div class="size-10 rounded-full border-2 border-primary">
                            <img src="{{ Auth::user()->avatar }}" alt="avatar" />
                        </div>
                    </div>
                </a>
                @else
                    <a class="btn btn-primary text-lg" href="{{ route('login') }}">Ingresar</a>
                @endauth

            </div>

        </div>
    </div>

</nav>
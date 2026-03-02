@extends('layouts.app')

@section('content')

<?php

// Simulación de datos dinámicos

$usuarioLogueado = false; // luego será con $_SESSION

$librosMasVendidos = [
    [
        "titulo" => "El Arte del Amor",
        "autor" => "Autor Demo",
        "categoria" => "Negocios",
        "precio" => 9.99,
        "review" => 5,
        "portada" => "assets/img/book_cover_mockup.jpg"
    ],
    [
        "titulo" => "Mentalidad Millonaria",
        "autor" => "Autor Demo",
        "categoria" => "Romance",
        "precio" => 14.99,
        "review" => 4.5,
        "portada" => "assets/img/book_cover_mockup.jpg"
    ],
    [
        "titulo" => "Cálculo Simplificado",
        "autor" => "Autor Demo",
        "categoria" => "Tecnología",
        "precio" => 0.00,
        "review" => 3.5,
        "portada" => "assets/img/book_cover_mockup.jpg"
    ],
    [
        "titulo" => "Cálculo II",
        "autor" => "Autor Demo",
        "categoria" => "Tecnología",
        "precio" => 0.10,
        "review" => 2,
        "portada" => "assets/img/book_cover_mockup.jpg"
    ]
];

$categorias = ["Negocios", "Romance", "Tecnología", "Educación", "Ficción", "Misterio", "Poesía", "Salud"];
?>

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

                    @if(session()->has('usuario_id'))
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button class="btn btn-primary w-full md:hidden mt-2 text-sm md:text-lg" type="submit">Salir</button>
                    </form>
                    @else
                        <a class="btn btn-primary w-full md:hidden mt-2 text-sm md:text-lg" href="{{ route('login') }}">Ingresar</a>
                    @endif

                    <button class="btn md:hidden font-light" href="#"><span class="icon-[tabler--shopping-cart] size-5"></span></button>

                    <li><a class="hover:text-primary text-sm md:text-lg" href="#">Inicio</a></li>

                    <li class="dropdown relative inline-flex [--auto-close:inside] [--offset:8] [--placement:bottom-end]">
                        <button type="button" class="hover:text-primary text-sm md:text-lg dropdown-toggle dropdown-open:bg-base-content/10 dropdown-open:text-base-content">
                            Explorar
                            <span class="icon-[tabler--chevron-down] dropdown-open:rotate-180 size-4"></span>
                        </button>

                        <ul class="dropdown-menu dropdown-open:opacity-100 hidden bg-base-300/50 glass">
                            <li><a class="hover:text-primary text-sm md:text-lg dropdown-item" href="#">Todos</a></li>
                            <li><a class="hover:text-primary text-sm md:text-lg dropdown-item" href="#">Novedades</a></li>
                            <li><a class="hover:text-primary text-sm md:text-lg dropdown-item" href="#">Más Vendidos</a></li>
                        </ul>
                    </li>

                    <li><a class="hover:text-primary text-sm md:text-lg" href="#">Contacto</a></li>

                </ul>

            </div>

            <div class="items-center justify-between w-auto hidden md:flex gap-3">

                <button class="btn bg-none font-light" href="#"><span class="icon-[tabler--shopping-cart] size-5"></span></button>

                @if(session()->has('usuario_id'))
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="btn btn-primary text-lg" type="submit">Salir</button>
                </form>
                @else
                    <a class="btn btn-primary text-lg" href="{{ route('login') }}">Ingresar</a>
                @endif

            </div>

        </div>
    </div>

</nav>

    <!-- IMAGEN (fondo completo arriba) -->
     
    <div class="w-full ">
        <img src="{{ asset('assets/img/landing_page.webp') }}" class="w-full h-auto motion-preset-fade" alt="Mi Estantería">
    </div>

    <div class="font-serif text-xl md:text-7xl text-center text-secondary px-10 -translate-y-10 md:-translate-y-40">
        <p class="intersect:motion-preset-slide-right">No escribas libros, <span class="text-primary">construye</span></p>
        <p class="intersect:motion-preset-slide-left">tu <span class="text-primary">identidad</span>, <span class="text-primary">autoridad</span> y</p>
        <p class="intersect:motion-preset-slide-right"><span class="text-primary">legado</span></p>
    </div>

    <div class="divider divider-dashed -translate-y-5 md:-translate-y-20"></div>

    <article class="intersect:motion-preset-fade font-inconsolata items-center px-10 pb-10 md:px-30">
        <h3 class="md:pb-10 pb-5 text-center md:text-5xl text-lg text-secondary font-serif font-light">¿Qué es MiEstantería?</h3>
        <p class="text-center md:text-2xl text-xs text-base-content text-pretty"><strong>MiEstantería</strong> es una plataforma digital donde puedes <strong>comprar</strong> y <strong>vender</strong><strong>e-books de forma</strong> <strong>fácil</strong> y <strong>segura</strong>. Descubre <strong>nuevos libros</strong>, <strong>apoya a</strong><strong>autores independientes</strong> y publica tus propias <strong>obras para llegar</strong>a <strong>más lectores</strong>, todo en <strong>un solo lugar</strong>.</p>
    </article>

    <div class="flex items-center justify-center md:pb-10 pb-5">
    
        <button class="btn btn-primary btn-md md:btn-lg font-inconsolata text-md motion-preset-pulse">Empezar Ahora</button>

    </div>

    <div class="divider divider-dashed mt-8"></div>

    <div id="auto-height" data-carousel='{ "loadingClasses": "opacity-0", "isAutoPlay": true, "speed": 4000, "isInfiniteLoop": true }' class="relative w-full p-5">
    <div class="carousel">
        <div class="carousel-body relative opacity-0 intersect:motion-preset-focus">
        <!-- Slide 1 -->
        <div class="carousel-slide">

            <img src="{{ asset('assets/img/landing_page.webp') }}" class="w-full h-full object-cover" alt=" ">
            
        </div>
        <!-- Slide 2 -->
        <div class="carousel-slide">

            <img src="{{ asset('assets/img/man-using-digital-tablet-cafe.jpg') }}" class="w-full h-full object-cover" alt=" ">
            
        </div>
        <!-- Slide 3 -->
         <div class="carousel-slide">

            <img src="{{ asset('assets/img/young-woman-using-tablet-coffee-shop.jpg') }}" class="w-full h-full object-cover" alt=" ">
            
        </div>
       
        </div>
    </div>

    <!-- Previous Slide -->
    <button type="button" class="ml-6 carousel-prev start-5 max-sm:start-3 carousel-disabled:opacity-50 size-9.5 bg-base-100 flex items-center justify-center rounded-full shadow-base-300/20 shadow-sm">
        <span class="icon-[tabler--chevron-left] size-5 cursor-pointer"></span>
        <span class="sr-only">Previous</span>
    </button>
    <!-- Next Slide -->
    <button type="button" class="mr-6 carousel-next end-5 max-sm:end-3 carousel-disabled:opacity-50 size-9.5 bg-base-100 flex items-center justify-center rounded-full shadow-base-300/20 shadow-sm">
        <span class="icon-[tabler--chevron-right] size-5"></span>
        <span class="sr-only">Next</span>
    </button>
    </div>
    
    <div class="divider divider-dashed"></div>

    <div class="bg-secondary m-5 rounded-lg text-secondary-content p-4 flex justify-between gap-10 overflow-auto items-center intersect:motion-preset-slide-right">
        <div class="font-serif text-md md:text-xl">CATEGORÍAS</div>
        <div class="font-inconsolata text-sm md:text-lg flex md:gap-10 gap-5">
            <?php foreach ($categorias as $categoria): ?>
                
                <a href="categoria.php?nombre=<?php echo urlencode($categoria); ?>" 
                class="hover:bg-secondary-content/20 active:bg-secondary-content/50 px-3 py-1 rounded-sm transition duration-300 whitespace-nowrap">
                
                <?php echo htmlspecialchars($categoria); ?>
                
                </a>

            <?php endforeach; ?>
        </div>
    </div>

    <h1 class="font-serif text-center md:text-4xl text-xl m-10">LO MÁS VENDIDO ESTE MES</h1>

    <div class="bg-base-300 mx-5 p-10 rounded-xl flex items-center justify-between gap-10 overflow-x-auto intersect:motion-preset-slide-left">

    <?php foreach ($librosMasVendidos as $index => $libro): ?>

        <div class="bg-base-200 rounded-lg h-90 min-w-65 duration-300 hover:scale-110 relative">

            <button class="btn btn-sm btn-square absolute border-none top-2 right-2 z-10 bg-primary"><span class="icon-[tabler--shopping-cart-plus] text-primary-content"></span></button>

            <div class="flex justify-center p-5">
                <img src="<?php echo htmlspecialchars($libro['portada']); ?>" class="w-2/5 rounded-xs">
            </div>

            <div class="px-5">
                
                <h1 class="font-serif text-lg">
                    <?php echo htmlspecialchars($libro['titulo']); ?>
                </h1>

                <h2 class="font-inconsolata text-sm">
                    <?php echo htmlspecialchars($libro['categoria']); ?>
                </h2>

                <h2 class="font-inconsolata text-sm mt-10">
                    Autor: <?php echo htmlspecialchars($libro['autor']); ?>
                </h2>
            </div>

            <div class="px-5 mt-5 flex items-center justify-between">
                
                <div class="flex items-center gap-2">
                    <div class="flex raty-read-only" 
                        data-score="<?php echo $libro['review']; ?>">
                    </div>

                    <div class="-translate-y-1 rounded-field text-xs font-semibold font-inconsolata">
                        <?php echo $libro['review']; ?>
                    </div>
                </div>
                
                <div>
                    <h2 class="font-inconsolata text-sm font-black">
                        $<?php echo number_format($libro['precio'], 2); ?>
                    </h2>
                </div>
            </div>

        </div>

    <?php endforeach; ?>

    </div>

    <h1 class="font-serif text-center md:text-4xl text-xl m-10">NOVEDADES ESTE MES</h1>

    <div class="bg-base-300 mx-5 p-10 rounded-xl flex items-center justify-between gap-10 overflow-x-auto intersect:motion-preset-slide-right">

    <?php foreach ($librosMasVendidos as $index => $libro): ?>

        <div class="bg-base-200 rounded-lg h-90 min-w-65 duration-300 hover:scale-110 relative">

            <button class="btn btn-sm btn-square absolute border-none top-2 right-2 z-10 bg-primary"><span class="icon-[tabler--shopping-cart-plus] text-primary-content"></span></button>

            <div class="flex justify-center p-5">
                <img src="<?php echo htmlspecialchars($libro['portada']); ?>" 
                    class="w-2/5 rounded-xs">
            </div>

            <div class="px-5">
                <h1 class="font-serif text-lg">
                    <?php echo htmlspecialchars($libro['titulo']); ?>
                </h1>

                <h2 class="font-inconsolata text-sm">
                    <?php echo htmlspecialchars($libro['categoria']); ?>
                </h2>

                <h2 class="font-inconsolata text-sm mt-10">
                    Autor: <?php echo htmlspecialchars($libro['autor']); ?>
                </h2>
            </div>

            <div class="px-5 mt-5 flex items-center justify-between">
                
                <div class="flex items-center gap-2">
                    <div class="flex raty-read-only" 
                        data-score="<?php echo $libro['review']; ?>">
                    </div>

                    <div class="-translate-y-1 rounded-field text-xs font-semibold font-inconsolata">
                        <?php echo $libro['review']; ?>
                    </div>
                </div>
                
                <div>
                    <h2 class="font-inconsolata text-sm font-black">
                        $<?php echo number_format($libro['precio'], 2); ?>
                    </h2>
                </div>
            </div>

        </div>

    <?php endforeach; ?>

    </div>

    <div class="divider divider-dashed my-5"></div>

    <div class="px-8 pt-5 bg-linear-to-b from-transparent to-base-300 ">

        <footer class="footer bg-secondary p-15 font-inconsolata rounded-xl">
            <aside class="gap-6">
                <div class="flex items-center gap-2 text-xl font-bold text-secondary-content">
                    <img src="{{ asset('assets/img/logo_navbar.png') }}" class="w-20 h-auto saturate-0 brightness-0 invert-90" alt="Mi Estantería">
                </div>
                <p class="text-secondary-content text-sm">No escribas libros, construye <br />tu identidad, autoridad y legado. </p>
            </aside>
            <nav class="text-secondary-content">
                <h6 class="footer-title text-secondary-content font-black">Mi Estantería</h6>
                <a href="#" class="link link-hover text-secondary-content hover:text-primary">Nosotros</a>
                <a href="#" class="link link-hover text-secondary-content hover:text-primary">Contacto</a>
                <a href="#" class="link link-hover text-secondary-content hover:text-primary">Otro Proyectos</a>
            </nav>
            <nav class="text-secondary-content">
                <h6 class="footer-title text-secondary-content font-black">Redes</h6>
                <a href="#" class="link link-hover text-secondary-content hover:text-primary">Facebook</a>
                <a href="#" class="link link-hover text-secondary-content hover:text-primary">Instagram</a>
                <a href="#" class="link link-hover text-secondary-content hover:text-primary">LinkedIn</a>
                <a href="#" class="link link-hover text-secondary-content hover:text-primary">X</a>
            </nav>
            <nav class="text-secondary-content">
                <h6 class="footer-title text-secondary-content font-black">Legal</h6>
                <a href="#" class="link link-hover text-secondary-content hover:text-primary">Terminos y Condiciones</a>
                <a href="#" class="link link-hover text-secondary-content hover:text-primary">Políticas de Privacidad</a>
                <a href="#" class="link link-hover text-secondary-content hover:text-primary">Polìtica de Cookies</a>
            </nav>
        </footer>

    </div>

    <footer class="footer footer-center bg-base-300 px-6 py-4">
        <aside>
            <p class="font-inconsolata text-base-content">Copyright © 2026 - Todos los derechos reservados.</p>
        </aside>
    </footer>

    <script>
        document.addEventListener('DOMContentLoaded', function () {

        document.querySelectorAll('.raty-read-only').forEach(function (element) {

            const score = element.dataset.score;

            const rating = new Raty(element, {
            half: true,
            starType: 'i',
            starOff: 'icon-[tabler--star-filled] opacity-20 size-4',
            starHalf: 'icon-[tabler--star-half-filled] size-4 text-primary',
            starOn: 'icon-[tabler--star-filled] size-4 text-primary',
            readOnly: true,
            score: score
            });

            rating.init();

            // Mostrar el score al lado
            const scoreContainer = element.parentElement.querySelector('.raty-score');
            if (scoreContainer) {
            scoreContainer.textContent = score;
            }

        });

        });
    </script>

    <script>
        window.addEventListener('load', function () {
            const animationButtons = document.querySelectorAll('.animation-button')
            const box = document.getElementById('animated-box')

            animationButtons.forEach(button => {
            button.addEventListener('click', () => {
                const animationClass = button.value

                // Remove all existing motion- classes
                const currentClasses = Array.from(box.classList)
                const motionClasses = currentClasses.filter(className => className.startsWith('motion-'))
                motionClasses.forEach(className => box.classList.remove(className))

                // Temporarily remove the animation class to re-trigger it
                void box.offsetWidth // Trigger reflow to allow re-adding the class
                box.classList.add(animationClass, 'motion-duration-1000')
            })
            })
        })
    </script>

@endsection
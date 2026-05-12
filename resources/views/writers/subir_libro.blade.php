@extends('layouts.app')

@section('content')

<div class="flex">

    <!-- Imagen -->
    <div class="bg-cover bg-center w-1/2 md:block hidden m-3 rounded-4xl motion-preset-blur-right"
        style="background-image: url('{{ asset('assets/img/serious-young-woman-designer-sitting-indoors-night-writing-notes.jpg') }}');">
    </div>

    <!-- Wizard -->
    <div class="md:w-1/2 w-full flex flex-col min-h-screen rounded-4xl motion-preset-slide-right">

        <!-- Header -->
        <div class="flex justify-between items-center p-5">
            <a href="{{ route('perfil') }}" class="btn btn-text font-inconsolata btn-sm"><- Regresar</a>
            <img src="{{ asset('assets/img/logo_navbar.png') }}" class="w-15">
        </div>

        <div class="flex-1 flex flex-col items-center justify-center gap-y-6 mx-10">

            <!-- TITULO -->
            <div class="text-center">
                <h1 class="font-serif text-2xl">Registrar Libro</h1>
                <h2 class="font-inconsolata text-sm">
                    Comparte tu obra con miles de lectores
                </h2>
            </div>

            <!-- STEPPER -->
            <div data-stepper class="w-75 md:w-85 font-inconsolata">

                <!-- NAV -->
                <ul class="flex justify-between mb-6">

                    <!-- Paso 1 -->
                    <li class="flex flex-col items-center flex-1" data-stepper-nav-item='{"index":1}'>
                        <span class="flex flex-col items-center gap-2">
                            <span class="flex items-center justify-center size-7 rounded-full 
                                bg-base-200 text-base-content text-sm font-medium
                                stepper-active:bg-primary stepper-active:text-white
                                stepper-completed:bg-success stepper-completed:text-white">
                                1
                            </span>
                            <span class="text-xs text-base-content/70">Información</span>
                        </span>
                    </li>

                    <!-- Paso 2 -->
                    <li class="flex flex-col items-center flex-1" data-stepper-nav-item='{"index":2}'>
                        <span class="flex flex-col items-center gap-2">
                            <span class="flex items-center justify-center size-7 rounded-full 
                                bg-base-200 text-base-content text-sm font-medium
                                stepper-active:bg-primary stepper-active:text-white
                                stepper-completed:bg-success stepper-completed:text-white">
                                2
                            </span>
                            <span class="text-xs text-base-content/70">Precio</span>
                        </span>
                    </li>

                    <!-- Paso 3 -->
                    <li class="flex flex-col items-center flex-1" data-stepper-nav-item='{"index":3}'>
                        <span class="flex flex-col items-center gap-2">
                            <span class="flex items-center justify-center size-7 rounded-full 
                                bg-base-200 text-base-content text-sm font-medium
                                stepper-active:bg-primary stepper-active:text-white
                                stepper-completed:bg-success stepper-completed:text-white">
                                3
                            </span>
                            <span class="text-xs text-base-content/70">Archivos</span>
                        </span>
                    </li>

                    <!-- Paso 4 -->
                    <li class="flex flex-col items-center flex-1" data-stepper-nav-item='{"index":4}'>
                        <span class="flex flex-col items-center gap-2">
                            <span class="flex items-center justify-center size-7 rounded-full 
                                bg-base-200 text-base-content text-sm font-medium
                                stepper-active:bg-primary stepper-active:text-white
                                stepper-completed:bg-success stepper-completed:text-white">
                                4
                            </span>
                            <span class="text-xs text-base-content/70">Imágenes</span>
                        </span>
                    </li>

                </ul>

                <!-- FORM -->
                <form method="POST" action="{{ route('books.store') }}" enctype="multipart/form-data" class="text-center">
                    @csrf

                    <!-- PASO 1: INFORMACIÓN -->
                    <div data-stepper-content-item='{"index":1}' class="space-y-4">

                        <div class="input-floating">
                            <input type="text" name="titulo" class="input" placeholder=" " required>
                            <label class="input-floating-label">Título del libro</label>
                        </div>

                        <div class="w-full">

                            <select name="book_category_id"
                                data-select='{
                                    "placeholder": "Categoría",
                                    "toggleTag": "<button type=\"button\" aria-expanded=\"false\"></button>",
                                    "toggleClasses": "advance-select-toggle w-full input text-left",
                                    "dropdownClasses": "advance-select-menu bg-base-200 h-50 overflow-y-auto",
                                    "optionClasses": "advance-select-option selected:select-active",
                                    "optionTemplate": "<div class=\"flex justify-between items-center w-full\"><span data-title></span><span class=\"icon-[tabler--check] shrink-0 size-4 text-primary hidden selected:block\"></span></div>",
                                    "extraMarkup": "<span class=\"icon-[tabler--caret-up-down] shrink-0 size-4 text-base-content absolute top-1/2 end-3 -translate-y-1/2\"></span>"
                                }'
                                class="hidden"
                                required
                            >

                                <option value="">Selecciona</option>

                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}">
                                        {{ $category->nombre }}
                                    </option>
                                @endforeach

                            </select>

                        </div>

                        <div class="flex gap-5">
                            <div class="input-floating">
                                <textarea name="descripcion_corta" rows="2" class="input" placeholder=" " required></textarea>
                                <label class="input-floating-label">Dscrp. corta</label>
                            </div>

                            <div class="input-floating">
                                <textarea name="descripcion" rows="4" class="input" placeholder=" " required></textarea>
                                <label class="input-floating-label">Dscrp. larga</label>
                            </div>
                        </div>

                        <div class="input-floating">
                            <input type="text" name="idioma" class="input" placeholder=" " value="Español">
                            <label class="input-floating-label">Idioma</label>
                        </div>

                        <div class="flex gap-5">
                            <div class="input-floating">
                                <input type="text" name="isbn" class="input" placeholder=" ">
                                <label class="input-floating-label">ISBN</label>
                            </div>

                            <div class="input-floating">
                                <input type="number" name="paginas" class="input" placeholder=" ">
                                <label class="input-floating-label">Nº de páginas</label>
                            </div>
                        </div>

                    </div>

                    <!-- PASO 2: PRECIO -->
                    <div data-stepper-content-item='{"index":2}' style="display:none" class="space-y-4">

                        <div class="w-full">

                            <select name="tipo"
                                data-select='{
                                    "placeholder": "Tipo de libro",
                                    "toggleTag": "<button type=\"button\" aria-expanded=\"false\"></button>",
                                    "toggleClasses": "advance-select-toggle w-full input text-left",
                                    "dropdownClasses": "advance-select-menu bg-base-200",
                                    "optionClasses": "advance-select-option selected:select-active",
                                    "optionTemplate": "<div class=\"flex justify-between items-center w-full\"><span data-title></span><span class=\"icon-[tabler--check] shrink-0 size-4 text-primary hidden selected:block\"></span></div>",
                                    "extraMarkup": "<span class=\"icon-[tabler--caret-up-down] shrink-0 size-4 text-base-content absolute top-1/2 end-3 -translate-y-1/2\"></span>"
                                }'
                                class="hidden"
                                required
                            >

                                <option value="">Selecciona</option>
                                <option value="ebook">Ebook</option>
                                <option disabled value="fisico">Físico</option>
                                <option disabled value="ambos">Ambos</option>

                            </select>

                        </div>

                        <div class="w-full">

                            <select name="formato"
                                data-select='{
                                    "placeholder": "Formato digital",
                                    "toggleTag": "<button type=\"button\" aria-expanded=\"false\"></button>",
                                    "toggleClasses": "advance-select-toggle w-full input text-left",
                                    "dropdownClasses": "advance-select-menu bg-base-200",
                                    "optionClasses": "advance-select-option selected:select-active",
                                    "optionTemplate": "<div class=\"flex justify-between items-center w-full\"><span data-title></span><span class=\"icon-[tabler--check] shrink-0 size-4 text-primary hidden selected:block\"></span></div>",
                                    "extraMarkup": "<span class=\"icon-[tabler--caret-up-down] shrink-0 size-4 text-base-content absolute top-1/2 end-3 -translate-y-1/2\"></span>"
                                }'
                                class="hidden"
                            >

                                <option value="">Selecciona</option>
                                <option value="pdf">PDF</option>
                                <option disabled value="epub">EPUB</option>
                                <option disabled value="mobi">MOBI</option>

                            </select>

                        </div>

                        <div class="input-floating">
                            <input type="number" step="0.01" name="precio" class="input" placeholder=" " required>
                            <label class="input-floating-label">Precio</label>
                        </div>

                        <div class="input-floating">
                            <input type="number" name="stock" class="input" placeholder=" ">
                            <label class="input-floating-label">Stock (solo físico)</label>
                        </div>

                        <div class="input-floating">
                            <input type="date" name="fecha_publicacion" class="input" placeholder=" ">
                            <label class="input-floating-label">Fecha de publicación</label>
                        </div>

                    </div>

                    <!-- PASO 3: ARCHIVOS -->
                    <div data-stepper-content-item='{"index":3}' style="display:none" class="space-y-4">

                        <!-- Archivo principal -->
                        <div class="text-left space-y-2">

                            <label class="text-sm block">
                                Archivo principal
                            </label>

                            <input 
                                type="file"
                                name="archivo_completo"
                                class="input input-xs w-full"
                                aria-label="Archivo principal"
                                accept=".pdf,.epub,.mobi"
                            >

                            <p class="text-xs text-base-content/60">
                                PDF, EPUB o MOBI
                            </p>

                        </div>

                        <!-- Preview -->
                        <div class="text-left space-y-2">

                            <label class="text-sm block">
                                Preview del libro
                            </label>

                            <input 
                                type="file"
                                name="archivo_preview"
                                class="input input-xs w-full"
                                aria-label="Preview del libro"
                                accept=".pdf"
                            >

                            <p class="text-xs text-base-content/60">
                                Vista previa gratuita
                            </p>

                        </div>

                        <!-- Extras -->
                        <div class="text-left space-y-2">

                            <label class="text-sm block">
                                Archivos extra
                            </label>

                            <input 
                                disabled
                                type="file"
                                name="archivos_extra[]"
                                multiple
                                class="input input-xs w-full"
                                aria-label="Archivos extra"
                            >

                            <p class="text-xs text-base-content/60">
                                Recursos adicionales
                            </p>

                        </div>

                    </div>

                    <!-- PASO 4: IMÁGENES -->
                    <div data-stepper-content-item='{"index":4}' style="display:none" class="space-y-4">

                        <!-- Portada -->
                        <div class="text-left space-y-2">

                            <label class="text-sm block">
                                Portada principal
                            </label>

                            <input 
                                type="file"
                                name="portada"
                                class="input input-xs w-full"
                                aria-label="Portada principal"
                                accept="image/*"
                            >

                            <p class="text-xs text-base-content/60">
                                Imagen principal del libro
                            </p>

                        </div>

                        <!-- Galería -->
                        <div class="text-left space-y-2">

                            <label class="text-sm block">
                                Galería del libro
                            </label>

                            <input 
                                disabled
                                type="file"
                                name="imagenes[]"
                                multiple
                                class="input input-xs w-full"
                                aria-label="Galería del libro"
                                accept="image/*"
                            >

                            <p class="text-xs text-base-content/60">
                                Puedes subir múltiples imágenes
                            </p>

                        </div>

                    </div>

                    <!-- BOTONES -->
                    <div class="flex justify-between mt-6">

                        <button type="button" class="btn btn-text"
                            data-stepper-back-btn>
                            ←
                        </button>

                        <button type="button" class="btn btn-primary"
                            data-stepper-next-btn>
                            Siguiente →
                        </button>

                        <button type="submit" class="btn btn-primary"
                            data-stepper-finish-btn style="display:none">
                            Publicar libro
                        </button>

                    </div>

                </form>

            </div>

        </div>

    </div>

</div>

@endsection
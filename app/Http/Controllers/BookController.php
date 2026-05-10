<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

use App\Models\Book;
use App\Models\BookCategory;
use App\Models\BookFile;
use App\Models\BookImage;

class BookController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | FORMULARIO CREAR LIBRO
    |--------------------------------------------------------------------------
    */

    public function create()
    {
        $user = auth()->user();

        // VALIDAR SI ES ESCRITOR
        if (!$user->writer) {
            return redirect()->route('perfil')
                ->with('error', 'Debes registrarte como escritor.');
        }

        // CATEGORÍAS ACTIVAS
        $categories = BookCategory::where('activo', true)
            ->orderBy('nombre')
            ->get();

        return view('writers.subir_libro', compact('categories'));
    }

    /*
    |--------------------------------------------------------------------------
    | GUARDAR LIBRO
    |--------------------------------------------------------------------------
    */

    public function store(Request $request)
    {
        $request->validate([

            /*
            |--------------------------------------------------------------------------
            | INFORMACIÓN
            |--------------------------------------------------------------------------
            */

            'titulo' => 'required|string|max:255',

            'book_category_id' => 'required|exists:book_categories,id',

            'descripcion_corta' => 'required|string|max:500',

            'descripcion' => 'required|string',

            'idioma' => 'nullable|string|max:100',

            'isbn' => 'nullable|string|max:100',

            'paginas' => 'nullable|integer|min:1',

            /*
            |--------------------------------------------------------------------------
            | PRECIO
            |--------------------------------------------------------------------------
            */

            'tipo' => 'required|in:ebook,fisico,ambos',

            'formato' => 'nullable|in:pdf,epub,mobi',

            'precio' => 'required|numeric|min:0',

            'stock' => 'nullable|integer|min:0',

            'fecha_publicacion' => 'nullable|date',

            /*
            |--------------------------------------------------------------------------
            | ARCHIVOS
            |--------------------------------------------------------------------------
            */

            'archivo_completo' => 'nullable|file|max:51200',

            'archivo_preview' => 'nullable|file|max:20480',

            'archivos_extra.*' => 'nullable|file|max:20480',

            /*
            |--------------------------------------------------------------------------
            | IMÁGENES
            |--------------------------------------------------------------------------
            */

            'portada' => 'nullable|image|max:5120',

            'imagenes.*' => 'nullable|image|max:5120',

        ]);

        $user = auth()->user();

        // VALIDAR ESCRITOR
        if (!$user->writer) {

            return back()
                ->withInput()
                ->with('error', 'No tienes permisos de escritor.');
        }

        DB::beginTransaction();

        try {

            /*
            |--------------------------------------------------------------------------
            | SLUG
            |--------------------------------------------------------------------------
            */

            $slug = Str::slug($request->titulo);

            $slugOriginal = $slug;

            $contador = 1;

            while (Book::where('slug', $slug)->exists()) {

                $slug = $slugOriginal . '-' . $contador;

                $contador++;
            }

            /*
            |--------------------------------------------------------------------------
            | PORTADA
            |--------------------------------------------------------------------------
            */

            $rutaPortada = null;

            if ($request->hasFile('portada')) {

                $rutaPortada = $request->file('portada')
                    ->store('books/portadas', 'public');
            }

            /*
            |--------------------------------------------------------------------------
            | CREAR LIBRO
            |--------------------------------------------------------------------------
            */

            $book = Book::create([

                'writer_id' => $user->writer->id,

                'book_category_id' => $request->book_category_id,

                'titulo' => $request->titulo,

                'slug' => $slug,

                'descripcion_corta' => $request->descripcion_corta,

                'descripcion' => $request->descripcion,

                'portada' => $rutaPortada,

                'tipo' => $request->tipo,

                'formato' => $request->formato,

                'idioma' => $request->idioma ?? 'Español',

                'isbn' => $request->isbn,

                'paginas' => $request->paginas,

                'fecha_publicacion' => $request->fecha_publicacion,

                'precio' => $request->precio,

                'stock' => $request->stock,

                'estado' => 'borrador',

                'visibilidad' => true,

                'destacado' => false,

                'total_ventas' => 0,

                'promedio_rating' => 0,
            ]);

            /*
            |--------------------------------------------------------------------------
            | ARCHIVO PRINCIPAL
            |--------------------------------------------------------------------------
            */

            if ($request->hasFile('archivo_completo')) {

                $archivo = $request->file('archivo_completo');

                $ruta = $archivo->store('books/files', 'public');

                BookFile::create([

                    'book_id' => $book->id,

                    'tipo' => 'completo',

                    'archivo' => $ruta,

                    'nombre_original' => $archivo->getClientOriginalName(),

                    'peso' => $archivo->getSize(),

                    'mime_type' => $archivo->getMimeType(),

                    'extension' => $archivo->getClientOriginalExtension(),
                ]);
            }

            /*
            |--------------------------------------------------------------------------
            | PREVIEW
            |--------------------------------------------------------------------------
            */

            if ($request->hasFile('archivo_preview')) {

                $archivo = $request->file('archivo_preview');

                $ruta = $archivo->store('books/previews', 'public');

                BookFile::create([

                    'book_id' => $book->id,

                    'tipo' => 'preview',

                    'archivo' => $ruta,

                    'nombre_original' => $archivo->getClientOriginalName(),

                    'peso' => $archivo->getSize(),

                    'mime_type' => $archivo->getMimeType(),

                    'extension' => $archivo->getClientOriginalExtension(),
                ]);
            }

            /*
            |--------------------------------------------------------------------------
            | ARCHIVOS EXTRA
            |--------------------------------------------------------------------------
            */

            if ($request->hasFile('archivos_extra')) {

                foreach ($request->file('archivos_extra') as $archivo) {

                    $ruta = $archivo->store('books/extras', 'public');

                    BookFile::create([

                        'book_id' => $book->id,

                        'tipo' => 'extra',

                        'archivo' => $ruta,

                        'nombre_original' => $archivo->getClientOriginalName(),

                        'peso' => $archivo->getSize(),

                        'mime_type' => $archivo->getMimeType(),

                        'extension' => $archivo->getClientOriginalExtension(),
                    ]);
                }
            }

            /*
            |--------------------------------------------------------------------------
            | GALERÍA
            |--------------------------------------------------------------------------
            */

            if ($request->hasFile('imagenes')) {

                foreach ($request->file('imagenes') as $index => $imagen) {

                    $ruta = $imagen->store('books/gallery', 'public');

                    BookImage::create([

                        'book_id' => $book->id,

                        'imagen' => $ruta,

                        'orden' => $index + 1,
                    ]);
                }
            }

            DB::commit();

            return redirect()->route('perfil')
                ->with('success', 'Libro registrado correctamente.');

        } catch (\Exception $e) {

            DB::rollBack();

            return back()
                ->withInput()
                ->with('error', 'Error: ' . $e->getMessage());
        }
    }
}
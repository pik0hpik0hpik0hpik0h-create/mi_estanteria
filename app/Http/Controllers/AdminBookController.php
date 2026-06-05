<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;

class AdminBookController extends Controller
{
    public function index()
    {
        // Validación usando el campo de la migración
        if (!auth()->user()->is_admin) {
            abort(403, 'Acceso denegado. No tienes permisos de administrador.');
        }

        // Traemos los libros en revisión, paginados
        $books = Book::where('estado', 'revision')->with('writer')->paginate(10);
        
        return view('admin.books.index', compact('books'));
    }

    public function show(Book $book)
    {
        if (!auth()->user()->is_admin) {
            abort(403, 'Acceso denegado.');
        }

        return view('admin.books.show', compact('book'));
    }

    public function approve(Book $book)
    {
        if (!auth()->user()->is_admin) {
            abort(403, 'Acceso denegado.');
        }

        $book->update(['estado' => 'publicado']);

        return redirect()->route('admin.books.index')
                         ->with('success', 'El libro "'.$book->titulo.'" ha sido publicado exitosamente.');
    }

    public function reject(Book $book)
    {
        if (!auth()->user()->is_admin) {
            abort(403, 'Acceso denegado.');
        }

        $book->update(['estado' => 'rechazado']);

        return redirect()->route('admin.books.index')
                         ->with('success', 'El libro "'.$book->titulo.'" ha sido rechazado.');
    }
}
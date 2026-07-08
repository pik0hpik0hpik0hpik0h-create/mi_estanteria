<?php

namespace App\Http\Controllers;

use App\Models\UserBook;
use Illuminate\Http\Request;

class LibraryController extends Controller
{
    public function index()
    {
        $userBooks = UserBook::with([
            'book.writer',
            'book.category',
            'book.images'
        ])
        ->where('user_id', auth()->id())
        ->latest()
        ->get();

        return view('auth.mi_estanteria', compact('userBooks'));
    }

    public function leer(UserBook $userBook)
    {
        abort_if($userBook->user_id !== auth()->id(), 403);

        $userBook->load([
            'book.writer',
            'book.category',
            'book.files',
        ]);

        $book = $userBook->book;

        $archivo = $book->files->firstWhere('tipo', 'completo');

        abort_if(!$archivo, 404);

        return view('auth.leer_libro', compact('book', 'archivo'));
    }
}
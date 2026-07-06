<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserBook;

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
}
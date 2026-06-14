<?php

namespace App\Http\Controllers;

use App\Services\CountryService;

class PaisController extends Controller
{
    /**
     * Devuelve la lista de paises .
     * El JS del registro y del perfil hace fetch a esta ruta.
     */
    public function index(CountryService $countries)
    {
        return response()->json($countries->obtener());
    }
}

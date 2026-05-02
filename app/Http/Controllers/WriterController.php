<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Writer;
use App\Models\WriterPaypalAccount;
use App\Models\WriterWallet;
use App\Models\Rol;

class WriterController extends Controller
{
    public function create()
    {
        $user = auth()->user();

        if ($user->writer) {
            return redirect()->route('perfil')
                ->with('error', 'Ya tienes una solicitud de escritor.');
        }

        return view('writers.crear_escritor');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre_pluma' => 'required|string|max:255',
            'bio' => 'required|string|max:1000',

            'tipo_documento' => 'required|string|max:50',
            'documento_identidad' => 'required|string|max:50',

            'paypal_email' => 'required|email|max:255',
            'paypal_nombre_cuenta' => 'required|string|max:255',

            'instagram' => 'nullable|string|max:255',
            'facebook' => 'nullable|string|max:255',
            'x' => 'nullable|string|max:255',
            'web' => 'nullable|string|max:255',

            'terms' => 'required|accepted'
        ], [
            'terms.required' => 'Debes aceptar los términos.',
            'terms.accepted' => 'Debes aceptar los términos.'
        ]);

        $user = auth()->user();

        if ($user->writer) {
            return back()->with('error', 'Ya estás registrado como escritor.');
        }

        DB::beginTransaction();

        try {

            
            $writer = Writer::create([
                'user_id' => $user->id,
                'nombre_pluma' => $request->nombre_pluma,
                'tipo_documento' => $request->tipo_documento,
                'documento_identidad' => $request->documento_identidad,
                'estado' => 'pendiente',
                'aprobado_en' => null,
            ]);

            
            WriterPaypalAccount::create([
                'writer_id' => $writer->id,
                'paypal_email' => $request->paypal_email,
                'paypal_nombre_cuenta' => $request->paypal_nombre_cuenta,
                'paypal_merchant_id' => null,
                'paypal_verificado' => false,
                'verificado_en' => null,
                'estado' => 'activo',
            ]);

             
            WriterWallet::create([
                'writer_id' => $writer->id,
                'saldo_disponible' => 0,
                'saldo_retenido' => 0,
                'total_generado' => 0,
                'total_pagado' => 0,
            ]);

             
            $user->perfil()->updateOrCreate(
                ['user_id' => $user->id],
                [
                    'bio' => $request->bio,
                    'instagram' => $request->instagram,
                    'facebook' => $request->facebook,
                    'x' => $request->x,
                    'web' => $request->web,
                ]
            );

            
            Rol::updateOrCreate(
                [
                    'user_id' => $user->id,
                    'rol' => 'escritor'
                ],
                [
                    'estado' => 1,
                    'fecha_asignacion' => now()
                ]
            );

            DB::commit();

            return redirect()->route('perfil')
                ->with('success', 'Solicitud enviada correctamente. Espera aprobación.');

        } catch (\Exception $e) {

            DB::rollBack();

            return back()
                ->withInput()
                ->with('error', 'Error: ' . $e->getMessage());
        }
    }
}
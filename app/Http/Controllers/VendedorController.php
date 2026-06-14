<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Vendedor;
use App\Models\VendedorPaypalAccount;
use App\Models\Rol;

class VendedorController extends Controller
{
    public function create()
    {
        $user = auth()->user();

        if ($user->vendedor) {
            return redirect()->route('perfil')
                ->with('error', 'Ya tienes una solicitud de vendedor.');
        }

        return view('vendedores.crear_vendedor');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre_publico' => 'required|string|max:255',
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

        if ($user->vendedor) {
            return back()->with('error', 'Ya estás registrado como vendedor.');
        }

        DB::beginTransaction();

        try {

            $vendedor = Vendedor::create([
                'user_id' => $user->id,
                'nombre_publico' => $request->nombre_publico,
                'tipo_documento' => $request->tipo_documento,
                'documento_identidad' => $request->documento_identidad,
                'estado' => 'pendiente',
                'aprobado_en' => null,
            ]);

            VendedorPaypalAccount::create([
                'vendedor_id' => $vendedor->id,
                'paypal_email' => $request->paypal_email,
                'paypal_nombre_cuenta' => $request->paypal_nombre_cuenta,
                'paypal_merchant_id' => null,
                'paypal_verificado' => false,
                'verificado_en' => null,
                'estado' => 'activo',
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

            // El rol queda INACTIVO (estado=0) hasta que un administrador
            // apruebe manualmente la solicitud desde el Panel Admin.
            Rol::updateOrCreate(
                [
                    'user_id' => $user->id,
                    'rol' => 'vendedor'
                ],
                [
                    'estado' => 0,
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

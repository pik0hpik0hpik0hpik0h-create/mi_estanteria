<?php

namespace App\Http\Controllers;

use App\Models\Vendedor;
use App\Models\Rol;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminVendedorController extends Controller
{
    /**
     * Helper: corta el acceso si el usuario no es admin.
     */
    private function aseguraAdmin(): void
    {
        if (!auth()->user() || !auth()->user()->is_admin) {
            abort(403, 'Acceso denegado. No tienes permisos de administrador.');
        }
    }

    /**
     * Lista de vendedores pendientes de revisión.
     */
    public function index()
    {
        $this->aseguraAdmin();

        $vendedores = Vendedor::where('estado', 'pendiente')
            ->with(['user', 'perfil'])
            ->orderBy('created_at', 'asc')
            ->paginate(10);

        return view('admin.vendedores.index', compact('vendedores'));
    }

    /**
     * Detalle de un vendedor pendiente.
     */
    public function show(Vendedor $vendedor)
    {
        $this->aseguraAdmin();

        $vendedor->load(['user', 'perfil', 'payAccount']);

        return view('admin.vendedores.show', compact('vendedor'));
    }

    /**
     * Aprueba: vendedor.estado='aprobado' y activa el rol 'vendedor'.
     */
    public function approve(Vendedor $vendedor)
    {
        $this->aseguraAdmin();

        DB::transaction(function () use ($vendedor) {

            $vendedor->update([
                'estado'      => 'aprobado',
                'aprobado_en' => now(),
            ]);

            Rol::updateOrCreate(
                [
                    'user_id' => $vendedor->user_id,
                    'rol'     => 'vendedor',
                ],
                [
                    'estado'           => 1,
                    'fecha_asignacion' => now(),
                ]
            );
        });

        return redirect()->route('admin.vendedores.index')
            ->with('success', 'El vendedor "' . ($vendedor->nombre_publico ?? 'N/A') . '" ha sido aprobado.');
    }

    /**
     * Rechaza: vendedor.estado='rechazado' y desactiva el rol.
     */
    public function reject(Vendedor $vendedor)
    {
        $this->aseguraAdmin();

        DB::transaction(function () use ($vendedor) {

            $vendedor->update([
                'estado'      => 'rechazado',
                'aprobado_en' => null,
            ]);

            Rol::where('user_id', $vendedor->user_id)
                ->where('rol', 'vendedor')
                ->update(['estado' => 0]);
        });

        return redirect()->route('admin.vendedores.index')
            ->with('success', 'El vendedor "' . ($vendedor->nombre_publico ?? 'N/A') . '" ha sido rechazado.');
    }
}

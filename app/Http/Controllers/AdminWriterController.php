<?php

namespace App\Http\Controllers;

use App\Models\Writer;
use App\Models\Rol;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminWriterController extends Controller
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
     * Lista de escritores pendientes de revisión.
     */
    public function index()
    {
        $this->aseguraAdmin();

        $writers = Writer::where('estado', 'pendiente')
            ->with(['user', 'perfil'])
            ->orderBy('created_at', 'asc')
            ->paginate(10);

        return view('admin.writers.index', compact('writers'));
    }

    /**
     * Detalle de un escritor pendiente (revisión manual).
     */
    public function show(Writer $writer)
    {
        $this->aseguraAdmin();

        $writer->load(['user', 'perfil', 'payAccount']);

        return view('admin.writers.show', compact('writer'));
    }

    /**
     * Aprueba el registro: marca writer.estado='aprobado' y activa el rol 'escritor'.
     */
    public function approve(Writer $writer)
    {
        $this->aseguraAdmin();

        DB::transaction(function () use ($writer) {

            $writer->update([
                'estado'      => 'aprobado',
                'aprobado_en' => now(),
            ]);

            Rol::updateOrCreate(
                [
                    'user_id' => $writer->user_id,
                    'rol'     => 'escritor',
                ],
                [
                    'estado'           => 1,
                    'fecha_asignacion' => now(),
                ]
            );
        });

        return redirect()->route('admin.writers.index')
            ->with('success', 'El escritor "' . ($writer->nombre_pluma ?? 'N/A') . '" ha sido aprobado.');
    }

    /**
     * Rechaza el registro: writer.estado='rechazado' y desactiva el rol 'escritor'.
     */
    public function reject(Writer $writer)
    {
        $this->aseguraAdmin();

        DB::transaction(function () use ($writer) {

            $writer->update([
                'estado'      => 'rechazado',
                'aprobado_en' => null,
            ]);

            Rol::where('user_id', $writer->user_id)
                ->where('rol', 'escritor')
                ->update(['estado' => 0]);
        });

        return redirect()->route('admin.writers.index')
            ->with('success', 'El escritor "' . ($writer->nombre_pluma ?? 'N/A') . '" ha sido rechazado.');
    }
}

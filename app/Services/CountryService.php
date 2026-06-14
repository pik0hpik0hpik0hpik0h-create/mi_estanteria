<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Servicio que devuelve la lista de paises desde restcountries.com v5.
 *
 * - Cachea la respuesta 24h para no quemar la cuota mensual.
 * - Transforma la respuesta de v5 al formato antiguo de v3.1 (name.common + cca2)
 *   asi el JS del registro y del perfil no necesita cambiar su forma de leer.
 * - Si la API esta caida y la cache nueva no se pudo armar, intenta servir
 *   la ultima cache valida que hubiera quedado.
 */
class CountryService
{
    /** Clave principal de cache (con TTL de 24h). */
    private const CACHE_KEY = 'paises:lista';

    /** Clave de respaldo: ultima respuesta exitosa, sin expiracion. */
    private const FALLBACK_KEY = 'paises:lista:fallback';

    /** Maximo permitido por la API. */
    private const PAGE_SIZE = 100;

    /**
     * Devuelve la lista completa de paises.
     * Si la cache esta caliente, no toca restcountries.
     */
    public function obtener(): array
    {
        return Cache::remember(self::CACHE_KEY, now()->addDay(), function () {

            try {

                $paises = $this->traerDeApi();

                // Si la API respondio bien, guardamos tambien como fallback.
                Cache::forever(self::FALLBACK_KEY, $paises);

                return $paises;

            } catch (\Throwable $e) {

                Log::warning('restcountries: fallo el fetch, intento fallback', [
                    'error' => $e->getMessage(),
                ]);

                // Si la API esta caida, servimos la ultima cache valida si existe.
                $fallback = Cache::get(self::FALLBACK_KEY);
                if ($fallback) {
                    return $fallback;
                }

                // No hay nada que servir.
                throw $e;
            }
        });
    }

    /**
     * Hace las llamadas paginadas a v5 y devuelve la lista normalizada.
     */
    private function traerDeApi(): array
    {
        $baseUrl = config('services.restcountries.base_url');
        $key     = config('services.restcountries.key');

        $todos  = [];
        $offset = 0;

        do {
            $response = Http::withToken($key)
                ->timeout(15)
                ->get($baseUrl, [
                    'limit'           => self::PAGE_SIZE,
                    'offset'          => $offset,
                    'response_fields' => 'names.common,codes.alpha_2',
                ]);

            if (!$response->successful()) {
                throw new \RuntimeException(
                    'restcountries respondio ' . $response->status()
                );
            }

            $payload = $response->json('data');
            $objetos = $payload['objects'] ?? [];

            foreach ($objetos as $pais) {

                // El API devuelve los campos anidados (a pesar de que la doc
                // los muestra como claves planas con punto), por eso bajamos un nivel.
                $nombre = $pais['names']['common']  ?? null;
                $codigo = $pais['codes']['alpha_2'] ?? null;

                if (!$nombre || !$codigo) {
                    continue;
                }

                // Devolvemos exactamente el shape que el JS antiguo espera.
                $todos[] = [
                    'name' => ['common' => $nombre],
                    'cca2' => $codigo,
                ];
            }

            $hayMas = $payload['meta']['more'] ?? false;
            $offset += self::PAGE_SIZE;

        } while ($hayMas);

        return $todos;
    }
}

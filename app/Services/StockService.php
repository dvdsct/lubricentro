<?php

namespace App\Services;

use App\Models\Stock;
use App\Models\StockMovement;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class StockService
{
    /**
     * Ensure a stock row exists for sucursal/producto and return it.
     */
    public function ensureStockRecord(int $sucursalId, int $productoId): Stock
    {
        return Stock::firstOrCreate([
            'sucursal_id' => $sucursalId,
            'producto_id' => $productoId,
            'estado' => '1',
        ], [
            'cantidad' => 0,
        ]);
    }

    /**
     * Get available stock for sucursal/producto (0 if missing)
     */
    public function getAvailableStock(int $sucursalId, int $productoId): int
    {
        $row = Stock::where('sucursal_id', $sucursalId)
            ->where('producto_id', $productoId)
            ->first();
        return intval($row?->cantidad ?? 0);
    }

    /**
     * Atomically adjust stock by a delta (can be positive or negative).
     * Throws exception if result would be negative.
     */
    public function adjustStock(int $sucursalId, int $productoId, int $delta, array $meta = []): Stock
    {
        return DB::transaction(function () use ($sucursalId, $productoId, $delta, $meta) {
            $row = Stock::where('sucursal_id', $sucursalId)
                ->where('producto_id', $productoId)
                ->lockForUpdate()
                ->first();

            if (!$row) {
                $row = $this->ensureStockRecord($sucursalId, $productoId);
                // lock row after creation
                $row = Stock::where('id', $row->id)->lockForUpdate()->first();
            }

            $nuevo = intval($row->cantidad) + intval($delta);
            if ($nuevo < 0) {
                throw new \RuntimeException('Stock insuficiente para realizar la operación');
            }

            $anterior = intval($row->cantidad);
            $row->update(['cantidad' => $nuevo]);

            // Registrar movimiento
            StockMovement::create([
                'producto_id' => $productoId,
                'sucursal_id' => $sucursalId,
                'delta' => intval($delta),
                'cantidad_anterior' => $anterior,
                'cantidad_nueva' => $nuevo,
                'motivo' => $meta['motivo'] ?? null,
                'operacion' => $meta['operacion'] ?? null,
                'referencia_type' => $meta['referencia_type'] ?? null,
                'referencia_id' => $meta['referencia_id'] ?? null,
                'user_id' => $meta['user_id'] ?? (auth()->id() ?? null),
                'precio_unitario' => $meta['precio_unitario'] ?? null,
                // Guardamos monto total con el mismo signo que el delta para facilitar visual
                'monto_total' => $meta['monto_total'] ?? (isset($meta['precio_unitario']) ? (intval($delta) * floatval($meta['precio_unitario'])) : null),
            ]);
            return $row;
        });
    }
}

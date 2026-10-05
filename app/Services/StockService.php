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
            'cantidad_num' => 0,
        ]);
    }

    /**
     * Get available stock for sucursal/producto (0 if missing)
     */
    public function getAvailableStock(int $sucursalId, int $productoId): float
    {
        $row = Stock::where('sucursal_id', $sucursalId)
            ->where('producto_id', $productoId)
            ->first();
        return floatval($row?->cantidad ?? 0);
    }

    /**
     * Atomically adjust stock by a delta (can be positive or negative).
     * Returns false if result would be negative (insufficient stock).
     * Returns the Stock model on success.
     */
    public function adjustStock(int $sucursalId, int $productoId, float|int $delta, array $meta = []): Stock|false
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

            $nuevo = floatval($row->cantidad) + floatval($delta);
            if ($nuevo < 0.0) {
                return false;
            }

            $anterior = floatval($row->cantidad);
            $row->update([
                'cantidad' => $nuevo,
                'cantidad_num' => $nuevo,
            ]);

            // Registrar movimiento
            StockMovement::create([
                'producto_id' => $productoId,
                'sucursal_id' => $sucursalId,
                'delta' => floatval($delta),
                'cantidad_anterior' => $anterior,
                'cantidad_nueva' => $nuevo,
                'motivo' => $meta['motivo'] ?? null,
                'operacion' => $meta['operacion'] ?? null,
                'referencia_type' => $meta['referencia_type'] ?? null,
                'referencia_id' => $meta['referencia_id'] ?? null,
                'user_id' => $meta['user_id'] ?? (auth()->id() ?? null),
                'precio_unitario' => $meta['precio_unitario'] ?? null,
                // Guardamos monto total con el mismo signo que el delta para facilitar visual
                'monto_total' => $meta['monto_total'] ?? (isset($meta['precio_unitario']) ? (floatval($delta) * floatval($meta['precio_unitario'])) : null),
            ]);
            return $row;
        });
    }

    /**
     * Fraccionar / desarmar stock de un producto origen (ej. Tambor o Bidón) a un producto destino (ej. Granel por litro).
     */
    public function fractionStock(int $sucursalId, int $sourceProductoId, float $sourceQty, int $destProductoId, float $destQty, array $meta = []): bool
    {
        return DB::transaction(function () use ($sucursalId, $sourceProductoId, $sourceQty, $destProductoId, $destQty, $meta) {
            $sourceStock = $this->getAvailableStock($sucursalId, $sourceProductoId);
            if ($sourceStock < $sourceQty) {
                return false;
            }

            $user = $meta['user_id'] ?? (auth()->id() ?? null);
            $motivo = $meta['motivo'] ?? 'Fraccionamiento / Desarme a Granel';

            // 1. Descontar producto origen
            $resSource = $this->adjustStock($sucursalId, $sourceProductoId, -abs($sourceQty), [
                'motivo' => $motivo,
                'operacion' => 'Fraccionamiento (Salida)',
                'referencia_type' => 'Producto',
                'referencia_id' => $destProductoId,
                'user_id' => $user,
            ]);

            if ($resSource === false) {
                throw new \Exception('Stock insuficiente en producto origen');
            }

            // 2. Aumentar producto destino
            $this->ensureStockRecord($sucursalId, $destProductoId);
            $resDest = $this->adjustStock($sucursalId, $destProductoId, abs($destQty), [
                'motivo' => $motivo,
                'operacion' => 'Fraccionamiento (Ingreso)',
                'referencia_type' => 'Producto',
                'referencia_id' => $sourceProductoId,
                'user_id' => $user,
            ]);

            if ($resDest === false) {
                throw new \Exception('Error al ingresar stock al producto destino');
            }

            return true;
        });
    }
}

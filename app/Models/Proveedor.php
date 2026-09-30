<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Proveedor extends Model
{
    use HasFactory;

    protected $fillable = [
        'perfil_id',
        'tipo',
        'cuit',
        'nombre_fantasia',
        'direccion',
        'rubro',
        'telefono',
        'email',
        'estado',
    ];

    public function perfiles()
    {
        return $this->belongsTo(Perfil::class, 'perfil_id');
    }

    public function productos()
    {
        return $this->belongsToMany(Producto::class, 'producto_x_proveedors');
    }

    public function pedidos()
    {
        return $this->hasMany(PedidoProveedor::class);
    }

    public function facturas()
    {
        return $this->hasMany(Factura::class, 'proveedor_id');
    }

    public function notasCredito()
    {
        return $this->hasMany(NotaCredito::class, 'proveedor_id');
    }

    public function ordenesPago()
    {
        return $this->hasMany(OrdenPago::class, 'proveedor_id');
    }

    public function cuentaCorrienteMovimientos()
    {
        return $this->hasMany(CuentaCorrienteProveedor::class, 'proveedor_id')->orderBy('fecha', 'desc')->orderBy('id', 'desc');
    }

    public function getNombreCompletoAttribute(): string
    {
        if (!empty($this->nombre_fantasia)) {
            return $this->nombre_fantasia;
        }
        $p = $this->perfiles?->personas;
        if ($p) {
            return trim(($p->nombre ?? '') . ' ' . ($p->apellido ?? ''));
        }
        return 'Proveedor #' . $this->id;
    }

    public function getSaldoTotalAttribute(): float
    {
        $debe = floatval($this->cuentaCorrienteMovimientos()->where('estado', 'activo')->sum('debe'));
        $haber = floatval($this->cuentaCorrienteMovimientos()->where('estado', 'activo')->sum('haber'));
        return max(0, $debe - $haber);
    }

    public function getSaldoBloqueadoAttribute(): float
    {
        return floatval($this->facturas()->where('estado', '!=', 'pagada')->sum('monto_bloqueado'));
    }

    public function getSaldoDisponibleAttribute(): float
    {
        return max(0, $this->saldo_total - $this->saldo_bloqueado);
    }
}

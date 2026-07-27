<?php

namespace App\Livewire;

use App\Models\Cliente;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class ClientPortal extends Component
{
    public $activeTab = 'turnos'; // 'turnos' | 'presupuestos' | 'vehiculos'

    public function setTab($tab)
    {
        $this->activeTab = $tab;
    }

    public function render()
    {
        $user = Auth::user();

        /** @var Cliente|null $cliente */
        $cliente = null;

        if ($user) {
            $cliente = Cliente::with([
                'perfiles.personas',
                'vehiculos.modelos.marcas',
                'ordenes' => function ($query) {
                    $query->orderBy('fecha_turno', 'desc')->orderBy('created_at', 'desc');
                },
                'ordenes.items.productos',
                'ordenes.vehiculos.modelos.marcas',
                'presupuestos' => function ($query) {
                    $query->orderBy('created_at', 'desc');
                },
                'presupuestos.itemspres',
            ])
            ->whereHas('perfiles', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            })
            ->first();
        }

        return view('livewire.client-portal', [
            'cliente' => $cliente,
        ])->layout('components.layouts.client-portal', [
            'title' => 'Mi Cuenta - Rocket Lubricentro',
        ]);
    }
}

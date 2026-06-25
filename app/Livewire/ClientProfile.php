<?php

namespace App\Livewire;

use App\Models\Cliente;
use Livewire\Component;

class ClientProfile extends Component
{
    public Cliente $cliente;

    public function mount(Cliente $cliente)
    {
        $this->cliente = $cliente->load(['perfiles.personas', 'vehiculos.modelos.marcas']);
    }

    public function render()
    {
        return view('livewire.client-profile')
            ->layout('components.layouts.page', [
                'title' => 'Perfil de Cliente - Rocket',
                'header' => 'PERFIL DE CLIENTE',
            ]);
    }
}

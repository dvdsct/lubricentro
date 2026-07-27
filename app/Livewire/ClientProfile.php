<?php

namespace App\Livewire;

use App\Models\Cliente;
use App\Models\Perfil;
use App\Models\Persona;
use Livewire\Component;

class ClientProfile extends Component
{
    public Cliente $cliente;

    public $showEditModal = false;

    public $nombre;
    public $apellido;
    public $dni;
    public $numero_telefono;
    public $fecha_nac;

    public function mount(Cliente $cliente)
    {
        $this->cliente = $cliente->load(['perfiles.personas', 'vehiculos.modelos.marcas']);
    }

    public function openEditModal()
    {
        $persona = optional($this->cliente->perfiles)->personas;

        $this->nombre = $persona->nombre ?? '';
        $this->apellido = $persona->apellido ?? '';
        $this->dni = $persona->DNI ?? '';
        $this->numero_telefono = $persona->numero_telefono ?? '';
        $this->fecha_nac = $persona->fecha_nac ?? '';

        $this->resetValidation();
        $this->showEditModal = true;
    }

    public function closeEditModal()
    {
        $this->showEditModal = false;
        $this->resetValidation();
    }

    public function updateClient()
    {
        $this->validate([
            'nombre' => 'required|string|max:255',
            'apellido' => 'required|string|max:255',
            'dni' => 'nullable|string|max:50',
            'numero_telefono' => 'nullable|string|max:50',
            'fecha_nac' => 'nullable|date',
        ], [
            'nombre.required' => 'El nombre es obligatorio.',
            'apellido.required' => 'El apellido es obligatorio.',
            'fecha_nac.date' => 'La fecha de nacimiento no es válida.',
        ]);

        $persona = optional($this->cliente->perfiles)->personas;

        if ($persona) {
            $persona->update([
                'nombre' => $this->nombre,
                'apellido' => $this->apellido,
                'DNI' => $this->dni,
                'numero_telefono' => $this->numero_telefono,
                'fecha_nac' => $this->fecha_nac ?: null,
            ]);
        } else {
            $newPersona = Persona::create([
                'nombre' => $this->nombre,
                'apellido' => $this->apellido,
                'DNI' => $this->dni,
                'numero_telefono' => $this->numero_telefono,
                'fecha_nac' => $this->fecha_nac ?: null,
                'estado' => 1,
            ]);

            if (!$this->cliente->perfiles) {
                $perfil = Perfil::create(['persona_id' => $newPersona->id]);
                $this->cliente->update(['perfil_id' => $perfil->id]);
            } else {
                $this->cliente->perfiles->update(['persona_id' => $newPersona->id]);
            }
        }

        $this->cliente->load(['perfiles.personas', 'vehiculos.modelos.marcas']);
        $this->showEditModal = false;

        session()->flash('message', 'Datos del cliente actualizados correctamente.');
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

<?php

namespace App\Livewire;

use App\Models\Persona;
use App\Models\Perfil;
use App\Models\Proveedor;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Validate;

class Proveedores extends Component
{
    use WithPagination;
    protected string $paginationTheme = 'bootstrap';

    public $modal = false;

    public $tipo = '';
    public $cuit = '';
    public $nombre_fantasia = '';
    public $direccion = '';
    public $rubro = '';
    public $telefono = '';
    public $email = '';

    #[Validate('required')]
    public $nombre;
    #[Validate('required')]
    public $apellido;

    public function openModal()
    {
        $this->modal = true;
    }

    public function closeModal()
    {
        $this->reset('tipo','cuit','nombre_fantasia','direccion','rubro','telefono','email','nombre','apellido');
        $this->modal = false;
    }

    public function save()
    {
        $this->validate();

        $persona = Persona::create([
            'nombre' => $this->nombre,
            'apellido' => $this->apellido,
            'estado' => 1,
        ]);

        $perfil = Perfil::create([
            'persona_id' => $persona->id,
        ]);

        Proveedor::create([
            'perfil_id' => $perfil->id,
            'tipo' => $this->tipo ?: 'juridica',
            'cuit' => $this->cuit,
            'nombre_fantasia' => $this->nombre_fantasia,
            'direccion' => $this->direccion,
            'rubro' => $this->rubro,
            'telefono' => $this->telefono,
            'email' => $this->email,
            'estado' => '1',
        ]);

        $this->closeModal();
    }

    public function render()
    {
        return view('livewire.proveedores', [
            'proveedores' => Proveedor::with('perfiles.personas')->latest()->paginate(10)
        ]);
    }
}

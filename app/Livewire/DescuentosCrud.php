<?php

namespace App\Livewire;

use App\Models\Descuentos;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithPagination;

class DescuentosCrud extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $modal = false;
    public $descuento_id;
    #[Validate('required|string|max:255')]
    public $descripcion = '';
    #[Validate('required|numeric|min:0|max:100')]
    public $porcentaje = '';
    public $estado = '1';

    public $search = '';
    public $perPage = 10;

    public function openModal($id = null)
    {
        $this->resetValidation();
        $this->reset('descuento_id','descripcion','porcentaje','estado');
        if ($id) {
            $d = Descuentos::findOrFail($id);
            $this->descuento_id = $d->id;
            $this->descripcion = $d->descripcion;
            $this->porcentaje = $d->porcentaje;
            $this->estado = $d->estado;
        }
        $this->modal = true;
    }

    public function closeModal()
    {
        $this->modal = false;
    }

    public function save()
    {
        $this->validate();
        if ($this->descuento_id) {
            Descuentos::findOrFail($this->descuento_id)->update([
                'descripcion' => $this->descripcion,
                'porcentaje' => $this->porcentaje,
                'estado' => $this->estado,
            ]);
        } else {
            Descuentos::create([
                'descripcion' => $this->descripcion,
                'porcentaje' => $this->porcentaje,
                'estado' => $this->estado,
            ]);
        }
        $this->closeModal();
    }

    public function toggleEstado($id)
    {
        $d = Descuentos::findOrFail($id);
        $d->estado = $d->estado === '1' ? '0' : '1';
        $d->save();
    }

    public function render()
    {
        $query = Descuentos::query();
        if ($this->search) {
            $query->where('descripcion','like','%'.$this->search.'%');
        }
        $descuentos = $query->orderByDesc('created_at')->paginate($this->perPage);
        return view('livewire.descuentos-crud', [
            'descuentos' => $descuentos
        ])->layout('components.layouts.adminlte');
    }
}

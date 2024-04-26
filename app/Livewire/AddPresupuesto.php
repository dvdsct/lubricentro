<?php

namespace App\Livewire;

use App\Models\Cliente;
use App\Models\Presupuesto;
use App\Models\PresupuestoItem;
use App\Models\Producto;
use App\Models\Stock;
use Livewire\Attributes\On;
use Livewire\Component;
use Carbon\Carbon;
use Carbon\CarbonInterval;
use Carbon\CarbonInterface;
use Carbon\CarbonImmutable;

// Establecer el idioma a español
Carbon::setLocale('es');


class AddPresupuesto extends Component
{
    public $clientes;
    public $cliente;
    public $modal;

    public $presupuesto;



    public function mount()
    {
        $this->clientes = Cliente::all();
    }




    public function continueForm()
    {

        $this->presupuesto = Presupuesto::create([
            'cliente_id' => $this->cliente,
            'estado' => '1'
        ]);
        $this->modalOnOff();
        redirect('presupuesto/'. $this->presupuesto->id);
    }

    #[On('addPresupuesto')]
    public function modalOnOff()
    {
        if ($this->modal == true) {
            $this->modal = false;
        } else {

            $this->modal = true;
        }
    }


    public function render()
    {
        return view('livewire.add-presupuesto');
    }
}

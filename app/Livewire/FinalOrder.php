<?php

namespace App\Livewire;

use Livewire\Attributes\On;
use Livewire\Component;

class FinalOrder extends Component
{
    public $orden;
    public $total;

    #[On('suma-items')]
    public function sumaItms()
    {

        $this->total = $this->orden->items->sum('subtotal');
    }

    public function render()
    {
        $this->total = $this->orden->items->sum('subtotal');
        return view('livewire.final-order');
    }
}

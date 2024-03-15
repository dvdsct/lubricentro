<?php

namespace App\Livewire;

use Livewire\Component;

class FinalOrder extends Component
{
    public $orden;
    public function render()
    {
        return view('livewire.final-order');
    }
}

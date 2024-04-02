<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\On;

class AddSupplierOrder extends Component
{

    public $modal = false;

    #[On('modalSupOrder')]
    public function modalOn()
    {
        $this->modal = true;
    }
    public function render()
    {
        return view('livewire.add-supplier-order');
    }
}

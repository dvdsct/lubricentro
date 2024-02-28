<?php

namespace App\Livewire;

use Livewire\Attributes\On;
use Livewire\Component;

class FormCreateOrder extends Component
{
    public $modal;


    #[On('modal-order')]
    public function openModal()
    {
        dd('ds');
        if ($this->modal == false) {
            $this->modal = true;
        }
    }
    public function render()
    {
        return view('livewire.form-create-order');
    }
}

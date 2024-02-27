<?php

namespace App\Livewire;

use Livewire\Attributes\On;
use Livewire\Component;

class TableList extends Component
{
    public $head;
    public $list;


   public function render()
    {
        return view('livewire.table-list');
    }
}

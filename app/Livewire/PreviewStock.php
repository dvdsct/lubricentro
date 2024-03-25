<?php

namespace App\Livewire;

use App\Models\Stock;
use Livewire\Component;

class PreviewStock extends Component
{
    public $stock;
    public function render()
    {
        $this->stock = Stock::all();
        return view('livewire.preview-stock');
    }
}

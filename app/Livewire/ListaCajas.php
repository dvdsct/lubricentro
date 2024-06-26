<?php

namespace App\Livewire;

use App\Models\Caja;
use App\Models\Perfil;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;
use Livewire\Component;

class ListaCajas extends Component
{
    public $cajas;
    public $caja;
    public $step  = 1;
    public $cajero;
    public $perfil;
    public $montoInicial;
    public $modalAbrirCaja;
    public $sucursal;
    public $query= '';

    public function mount()
    {
        if (Auth::user()->hasRole(['cajero'])) {

            $this->caja = Caja::where('estado', '200')->get();
            $this->perfil = Perfil::where('user_id', Auth::user()->id)->get();


            if ($this->caja->isEmpty()) {
                $this->modalAbrirCaja = true;
            } else {
                if ($this->caja->first()->estado) {

                    redirect('venta/' . $this->caja->first()->id);
                } else {
                }
            }
        }
        if (Auth::user()->hasRole(['admin'])) {
            $this->cajas = Caja::all();
        }
    }




    public function abrirCaja()
    {

        if ($this->step == 2) {

            $this->caja->update([

                'monto_inicial' => $this->montoInicial,
                'estado' => '200'
            ]);

            redirect('venta/' . $this->caja->id);
        }
        if ($this->step == 1) {


            // Caja Estado 200 es una caja abierta
            $this->cajero = $this->perfil->first()->cajeros->first();
            // dd($this->cajero);

            if (Auth::user()->hasRole(['cajero'])) {
                $this->caja =  Caja::firstOrCreate([
                    'cajero_id' => $this->cajero->id,
                    'estado' => '100',
                    'sucursal_id' => $this->cajero->sucursal_id

                ]);;
            }
            $this->step = 2;
        }
    }

    // Método para abrir el modal

    #[On('setModalCaja')]
    public function modalCaja()
    {

        if ($this->modalAbrirCaja) {

            $this->modalAbrirCaja = false;
        } else {

            $this->modalAbrirCaja = true;
        }
    }

    // Manejo del Modal

    public function cerrarModal()
    {
        $this->modalAbrirCaja = false;
    }



    public function render()
    {
        $this->cajas = Caja::all();

        return view('livewire.lista-cajas');
    }
}

<?php

namespace App\Livewire;

use App\Models\Caja;
use App\Models\Banco;
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
    public $bancoId;
    public $bancos;
    public $modalAbrirCaja;
    public $sucursal;
    public $query = '';

    public function mount()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $this->bancos = Banco::where('estado', 'Activo')->orWhereNull('estado')->get();
        if ($this->bancos->isEmpty()) {
            $this->bancos = Banco::all();
        }

        if (Auth::check() && Auth::user()->hasRole(['cajero'])) {
            $this->caja = Caja::where('estado', '200')->get();
            $this->perfil = Perfil::where('user_id', Auth::user()->id)->get();

            if ($this->caja->isEmpty()) {
                $this->modalAbrirCaja = true;
            } else {
                if ($this->caja->first()->estado) {
                    redirect('venta/' . $this->caja->first()->id);
                }
            }
        }
        if (Auth::check() && Auth::user()->hasRole(['admin'])) {
            $this->cajas = Caja::with(['cajeros.perfiles.personas', 'bancos'])->orderByDesc('created_at')->get();
        }
    }

    public function abrirCaja()
    {
        if ($this->step == 1) {
            $this->validate([
                'bancoId' => 'required',
                'montoInicial' => 'required|numeric|min:0',
            ], [
                'bancoId.required' => 'Debe seleccionar una cuenta donde van los pagos.',
                'montoInicial.required' => 'Debe ingresar el monto inicial.',
                'montoInicial.numeric' => 'El monto inicial debe ser un número.',
                'montoInicial.min' => 'El monto inicial no puede ser negativo.',
            ]);

            $this->cajero = $this->perfil->first()?->cajeros?->first();

            if (Auth::check() && Auth::user()->hasRole(['cajero']) && $this->cajero) {
                $this->caja = Caja::firstOrCreate([
                    'cajero_id' => $this->cajero->id,
                    'estado' => '100',
                    'sucursal_id' => $this->cajero->sucursal_id
                ]);

                $this->caja->update([
                    'banco_id' => $this->bancoId,
                ]);
            }
            $this->step = 2;
            return;
        }

        if ($this->step == 2) {
            if ($this->caja) {
                $this->caja->update([
                    'monto_inicial' => $this->montoInicial,
                    'banco_id' => $this->bancoId,
                    'estado' => '200'
                ]);

                redirect('venta/' . $this->caja->id);
            }
        }
    }

    #[On('setModalCaja')]
    public function modalCaja()
    {
        if ($this->modalAbrirCaja) {
            $this->modalAbrirCaja = false;
        } else {
            $this->modalAbrirCaja = true;
        }
    }

    public function cerrarModal()
    {
        $this->modalAbrirCaja = false;
        $this->step = 1;
    }

    public function search()
    {
        // Livewire search trigger
    }

    public function render()
    {
        $this->bancos = Banco::all();

        $cajasQuery = Caja::with(['cajeros.perfiles.personas', 'bancos'])
            ->orderByDesc('created_at');

        if (!empty($this->query)) {
            $term = '%' . $this->query . '%';
            $cajasQuery->where(function ($q) use ($term) {
                $q->where('id', 'like', $term)
                  ->orWhere('observaciones', 'like', $term)
                  ->orWhereHas('bancos', function ($bQuery) use ($term) {
                      $bQuery->where('descripcion', 'like', $term);
                  })
                  ->orWhereHas('cajeros.perfiles.personas', function ($pQuery) use ($term) {
                      $pQuery->where('nombre', 'like', $term)
                             ->orWhere('apellido', 'like', $term);
                  });
            });
        }

        $this->cajas = $cajasQuery->get();

        return view('livewire.lista-cajas');
    }
}

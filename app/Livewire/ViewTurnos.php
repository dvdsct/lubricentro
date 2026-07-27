<?php

namespace App\Livewire;

use App\Models\Orden;
use Livewire\Component;
use App\Models\Producto;
use Illuminate\Support\Carbon;
use Livewire\Attributes\On;
use App\Services\StockService;

class ViewTurnos extends Component
{
    public $turnlav;
    public $headslav = ['vehiculo', 'motivo', 'encargado'];
    public $turnlub;
    public $headslub = [];

    public $ordenes;
    public $fecha;
    public $vehiculo;
    public $orden;
    public $des;
    public $reprogramar =false;
    public $vista = 'diario';
    public $semanaLav = [];
    public $semanaLub = [];

    public function mount()
    {
        $this->fecha = Carbon::now()->format('Y-m-d');
        $this->reprogramar = false;
    }

    public function openModal()
    {

        $this->dispatch('modal-order')->to(FormCreateOrder::class);
    }


    public function reprTurn($id){

        $turno = Orden::find($id);

        $this->des = 'disabled';


        if($this->reprogramar){
            $this->reprogramar = false;
        }else{
            $this->reprogramar = true;  

        }

    }


    
    public function reproTurno(){
        $turno = Orden::where('orden_id', $this->orden->id)->get();
        $turno->update([
            'horario' => $this->fecha,
            'fecha_turno' => $this->horario

        ]);
    }


    #[On('change-day')]
    public function change_day()
    {
        if ($this->vista === 'semanal') {
            $this->fecha = Carbon::parse($this->fecha)->addWeek()->format('Y-m-d');
        } else {
            $this->fecha = Carbon::parse($this->fecha)->addDay()->format('Y-m-d');
        }
    }

    #[On('change-yes')]
    public function change_yes()
    {
        if ($this->vista === 'semanal') {
            $this->fecha = Carbon::parse($this->fecha)->subWeek()->format('Y-m-d');
        } else {
            $this->fecha = Carbon::parse($this->fecha)->subDay()->format('Y-m-d');
        }
    }


    public function cancelTurn($orden){
        $turno = Orden::find($orden);
        if (!$turno) {
            return;
        }

        try {
            \DB::transaction(function () use ($turno) {
                $sucursalId = $turno->sucursal_id ?: 1;
                $service = app(StockService::class);
                $itemsByProduct = $turno->items->groupBy('producto_id');

                foreach ($itemsByProduct as $productoId => $items) {
                    $p = Producto::find($productoId);
                    if (!$p || $p->es_provisional) {
                        continue;
                    }

                    $itemIds = $items->pluck('id')->toArray();

                    // Consultar el delta neto real que fue restado previamente para esta orden o sus ítems
                    $netDelta = \App\Models\StockMovement::where('producto_id', $p->id)
                        ->where(function ($query) use ($turno, $itemIds) {
                            $query->where(function ($q) use ($turno) {
                                $q->where('referencia_type', 'Orden')
                                  ->where('referencia_id', $turno->id);
                            })
                            ->orWhere(function ($q) use ($itemIds) {
                                $q->where('referencia_type', 'Item')
                                  ->whereIn('referencia_id', $itemIds);
                            });
                        })
                        ->sum('delta');

                    $movementsExist = \App\Models\StockMovement::where('producto_id', $p->id)
                        ->where(function ($query) use ($turno, $itemIds) {
                            $query->where(function ($q) use ($turno) {
                                $q->where('referencia_type', 'Orden')
                                  ->where('referencia_id', $turno->id);
                            })
                            ->orWhere(function ($q) use ($itemIds) {
                                $q->where('referencia_type', 'Item')
                                  ->whereIn('referencia_id', $itemIds);
                            });
                        })
                        ->exists();

                    if ($movementsExist) {
                        // Solo devolver si el neto acumulado es negativo (se restó más de lo que se devolvió)
                        $cantidadADevolver = $netDelta < 0 ? abs(floatval($netDelta)) : 0.0;
                    } else {
                        // Si no hay registro explícito de movimientos, se devuelve la suma de cantidades de los ítems
                        $cantidadADevolver = floatval($items->sum('cantidad'));
                    }

                    if ($cantidadADevolver > 0.0) {
                        $result = $service->adjustStock($sucursalId, $p->id, $cantidadADevolver, [
                            'motivo' => 'Cancelación de orden',
                            'operacion' => 'Cancelación de orden',
                            'referencia_type' => 'Orden',
                            'referencia_id' => $turno->id,
                        ]);
                        if ($result === false) {
                            throw new \Exception('Stock adjustment failed.');
                        }
                    }
                }

                $turno->update([
                    'estado' => '700'
                ]);
            });
        } catch (\Exception $e) {
            $this->dispatch('nonstock');
            return;
        }

        $this->des = 'disabled';
    }


    #[On('added-turn')]
    public function render()
    {
        $startOfWeek = Carbon::parse($this->fecha)->startOfWeek();
        $endOfWeek = Carbon::parse($this->fecha)->endOfWeek();
        $semanaRango = $startOfWeek->locale('es')->isoFormat('DD/MM') . ' al ' . $endOfWeek->locale('es')->isoFormat('DD/MM');

        if ($this->vista === 'semanal') {
            // Obtener todos los turnos de la semana para Lavadero
            $this->turnlav = Orden::select(
                'ordens.*',
                'clientes.id as cliente_id',
                'perfils.id as perfil_id',
                'personas.id as persona_id',
                'personas.apellido',
                'personas.DNI'
            )
                ->leftJoin('clientes', 'ordens.cliente_id', '=', 'clientes.id')
                ->leftJoin('perfils', 'clientes.perfil_id', '=', 'perfils.id')
                ->leftJoin('personas', 'perfils.persona_id', '=', 'personas.id')
                ->whereColumn('perfils.id', 'clientes.perfil_id')
                ->whereColumn('personas.id', 'perfils.persona_id')
                ->whereBetween('ordens.fecha_turno', [$startOfWeek->copy()->startOfDay(), $endOfWeek->copy()->endOfDay()])
                ->where('motivo', '1')
                ->where('ordens.estado', '!=', '555')
                ->orderBy('ordens.fecha_turno', 'asc')
                ->orderBy('ordens.horario', 'asc')
                ->get();

            // Obtener todos los turnos de la semana para Lubricentro
            $this->turnlub = Orden::select(
                'ordens.*',
                'clientes.id as cliente_id',
                'perfils.id as perfil_id',
                'personas.id as persona_id',
                'personas.apellido',
                'personas.DNI'
            )
                ->leftJoin('clientes', 'ordens.cliente_id', '=', 'clientes.id')
                ->leftJoin('perfils', 'clientes.perfil_id', '=', 'perfils.id')
                ->leftJoin('personas', 'perfils.persona_id', '=', 'personas.id')
                ->whereColumn('perfils.id', 'clientes.perfil_id')
                ->whereColumn('personas.id', 'perfils.persona_id')
                ->whereBetween('ordens.fecha_turno', [$startOfWeek->copy()->startOfDay(), $endOfWeek->copy()->endOfDay()])
                ->where('motivo', '2')
                ->where('ordens.estado', '!=', '555')
                ->orderBy('ordens.fecha_turno', 'asc')
                ->orderBy('ordens.horario', 'asc')
                ->get();
        } else {
            // Vista diaria original
            $this->turnlav = Orden::select(
                'ordens.*',
                'clientes.id as cliente_id',
                'perfils.id as perfil_id',
                'personas.id as persona_id',
                'personas.apellido',
                'personas.DNI'
            )
                ->leftJoin('clientes', 'ordens.cliente_id', '=', 'clientes.id')
                ->leftJoin('perfils', 'clientes.perfil_id', '=', 'perfils.id')
                ->leftJoin('personas', 'perfils.persona_id', '=', 'personas.id')
                ->whereColumn('perfils.id', 'clientes.perfil_id')
                ->whereColumn('personas.id', 'perfils.persona_id')
                ->whereDate('ordens.fecha_turno', $this->fecha)
                ->where('motivo', '1')
                ->where('ordens.estado', '!=', '555')
                ->get();

            $this->turnlub = Orden::select(
                'ordens.*',
                'clientes.id as cliente_id',
                'perfils.id as perfil_id',
                'personas.id as persona_id',
                'personas.apellido',
                'personas.DNI'
            )
                ->leftJoin('clientes', 'ordens.cliente_id', '=', 'clientes.id')
                ->leftJoin('perfils', 'clientes.perfil_id', '=', 'perfils.id')
                ->leftJoin('personas', 'perfils.persona_id', '=', 'personas.id')
                ->whereColumn('perfils.id', 'clientes.perfil_id')
                ->whereColumn('personas.id', 'perfils.persona_id')
                ->whereDate('ordens.fecha_turno', $this->fecha)
                ->where('motivo', '2')
                ->where('ordens.estado', '!=', '555')
                ->get();
        }


        return view('livewire.view-turnos', compact('semanaRango'));
    }
}

<?php

namespace App\Livewire;

use App\Models\Caja;
use App\Models\Cajero;
use App\Models\Colores;
use App\Models\ModeloVehiculo;
use App\Models\MarcaVehiculo;
use App\Models\TipoVehiculo;
use App\Models\Perfil;
use App\Models\Persona;
use App\Models\Cliente;
use App\Models\Item;
use App\Models\ItemsXOrden;
use App\Models\Orden;
use App\Models\Presupuesto;
use App\Models\Producto;
use App\Models\Servicio;
use App\Models\Stock;
use App\Models\Vehiculo;
use App\Models\VehiculosXCliente;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Locked;
use Livewire\Attributes\On;
use Livewire\Attributes\Validate;
use Livewire\Component;

class FormCreateOrder extends Component
{
    // de la vista
    public $modal;
    public $clientes;
    public $servicios;
    public $butt;
    public $act;
    public $selecedtVehiculo;
    public $btnLub = true;
    public $s_btnLub = 'btn-secondary';
    public $btnLav = false;
    public $s_btnLav = 'btn-secondary';

    #[Locked]
    public $formperson = false;

    #[Locked]
    public $formVehiculo = false;
    // de la Orden
    public $cliente;
    public $orden;
    public $servicio;
    public $vehiculos;
    public $fecha;
    public $fechaSelected;
    public $horario;
    public $motivo;
    public $producto;
    public $presupuesto;

    // del cliente
    public $persona;
    public $vehiculo;

    #[Validate('required', message: 'Debe ingresar el Nombre!', translate: false)]
    public $nombre;
    #[Validate('required', message: 'Debe ingresar el Apellido!', translate: false)]
    public $apellido;
    public $fecha_nac;
    // DNI ahora es opcional
    public $dni;
    // Nuevo campo: número de teléfono, solo números
    #[Validate('nullable|regex:/^\\d+$/', message: 'El número de teléfono debe contener solo dígitos.', translate: false)]
    public $numero_telefono;

    //Select formperson==TRUE
    public $tiposVehiculo;
    public $tipo;
    public $marcas;
    public $marca;
    public $modelos;
    public $modelo;
    public $colores;
    public $color;
    public $dominio;
    public $version;
    public $año;

    public $caja;
    public $cajero;

    public $query = '';



    public function mount($fecha)

    {
        $this->fecha = $fecha;
        $this->fechaSelected = $fecha;

        $perfil = Perfil::where('user_id', Auth::user()->id)->get();
        $this->cajero = Cajero::where('perfil_id', $perfil->first()->id)->get();
        $this->caja = Caja::where('cajero_id', $this->cajero->first()->id)->get();



        $this->clientes = Cliente::all();

        $this->servicios = Servicio::all();
        $this->tiposVehiculo = TipoVehiculo::all();
        $this->marcas = [];
        $this->modelos = [];
        $this->colores = Colores::all();
        $this->horario = Carbon::now()->format('H:i');
        // $this->cliente = 'ok';
    }


    #[On('change-day')]
    public function change_day()
    {

        $this->fecha = Carbon::parse($this->fecha)->addDay()->format('Y-m-d');
        $this->fechaSelected = Carbon::parse($this->fechaSelected)->addDay()->format('Y-m-d');
    }

    #[On('change-yes')]
    public function change_yes()
    {
        $this->fecha = Carbon::parse($this->fecha)->subDay()->format('Y-m-d');
        $this->fechaSelected = Carbon::parse($this->fechaSelected)->subDay()->format('Y-m-d');
    }

    public function search()
    {
        $this->resetPage();
    }



    public function upMarcas()
    {

        $this->marcas = MarcaVehiculo::where('tipo_vehiculo_id', $this->tipo)
            ->get();
    }
    public function upModelos()
    {
        // dd($this->marca . $this->tipo   );
        $this->modelos = ModeloVehiculo::where('marca_vehiculo_id', $this->marca)
            ->where('tipo_vehiculo_id', $this->tipo)
            ->get();
        // dd($this->marca   );
    }



    public function upPerson()
    {
        $this->cliente = Cliente::find($this->cliente);
        // dd($this->cliente);

        $this->nombre = $this->cliente->perfiles->personas->nombre ?? '';
        $this->apellido = $this->cliente->perfiles->personas->apellido ?? '';
        $this->dni = $this->cliente->perfiles->personas->DNI ?? '';
        $this->fecha_nac = $this->cliente->perfiles->personas->fecha_nac ?? '';
        $this->numero_telefono = $this->cliente->perfiles->personas->numero_telefono ?? '';
    }

    public function addClient()
    {
        if (Auth::user()->hasRole(['cajero', 'admin'])) {


            $this->validate();


            $persona = Persona::create([
                'nombre' => $this->nombre,
                'apellido' => $this->apellido,
                'DNI' => $this->dni,
                'numero_telefono' => $this->numero_telefono,
                'fecha_nac' => $this->fecha_nac,
                'estado' => 1
            ]);

            $perfil = Perfil::create([
                'persona_id' => $persona->id
            ]);

            $this->cliente = Cliente::create([
                'perfil_id' => $perfil->id,
            ]);
            $this->formperson = false;
            $this->formVehiculo = true;
        } else {
            return    abort(404);
        }
    }



    public function setForm()
    {
        if ($this->formVehiculo == true) {
            if ($this->selecedtVehiculo == true) {
                $this->selecedtVehiculo = false;
                $this->reset('vehiculo');
            }
            $this->formVehiculo = false;
        } else {
            $this->formVehiculo = true;
        }
    }







    public function setMot($mot)
    {
        if ($mot == 'lub') {
            $this->s_btnLub = 'bg-orange';
            $this->s_btnLav = 'btn-secondary';
            $this->btnLub = true;
            $this->btnLav = false;
        } else {
            // dd('lav');
            $this->s_btnLub = 'btn btn-secondary';
            $this->s_btnLav = 'btn btn-primary';
            $this->btnLub = false;
            $this->btnLav = true;
        }
    }



    public function addVehicle()
    {
        $this->vehiculo = Vehiculo::firstOrCreate([
            'modelo_vehiculo_id' => $this->modelo,
            'dominio' => $this->dominio,
            'color' => $this->color,
            'version' => $this->version,
            'año' => $this->año,
            'estado' => 1
        ]);

        VehiculosXCliente::firstOrCreate([
            'cliente_id' => $this->cliente->id,
            'vehiculo_id' => $this->vehiculo->id
        ]);

        $this->vehiculo = $this->vehiculo->id;
        $this->formVehiculo = false;

        $this->selectVehiculo();
    }


    public function selectVehiculo()
    {
        $this->vehiculo = Vehiculo::find($this->vehiculo);
        $this->selecedtVehiculo = true;
    }





    public function addTurno()
    {

        if (is_object($this->vehiculo)) {
            
            $this->vehiculo = $this->vehiculo->id;
        }
        if ($this->btnLav == true) {
            
            

            $this->orden = Orden::create([

                'cliente_id' => $this->cliente->id,
                'vehiculo_id' => $this->vehiculo,
                'sucursal_id' => $this->caja->first()->sucursal_id,
                'motivo' => '1',
                'horario' => $this->horario,
                'fecha_turno' => $this->fecha,
                'estado' => '1'
            ]);
        } else {
            $this->orden = Orden::create([

                'cliente_id' => $this->cliente->id,
                'vehiculo_id' => $this->vehiculo,
                'motivo' => '2',
                'sucursal_id' => $this->caja->first()->sucursal_id,

                'horario' => $this->horario,
                'fecha_turno' => $this->fecha,
                'estado' => '1'
            ]);
        }

        $this->vehiculo = Vehiculo::find($this->vehiculo);


        // __________________________________________________________
        //____________________ PRESUPUESTO___________________________
        // __________________________________________________________
        if ($this->presupuesto != null) {

            foreach ($this->presupuesto->itemspres as $i) {


                $p = $i->producto_id;
                $this->producto = Producto::find($p);

                $stock = Stock::where('producto_id', $this->producto->id)->first();
                $pst = floatval($this->producto->precio_venta) * floatval($i->cantidad);

                // Permitir si es provisional aunque no tenga stock
                if ((!$stock || $stock->cantidad == 0) && !$this->producto->es_provisional) {
                    // dd('aqui');
                    $this->orden->update([
                        'estado' => '555'
                    ]);
                    return  $this->dispatch('nonstock');
                } else {

                    $iO = Item::create([
                        'producto_id' => $this->producto->id,
                        'precio' => $i->precio_venta,
                        'cantidad' => $i->cantidad,
                        'subtotal' => $pst,
                        'estado' => '2',
                    ]);

                    ItemsXOrden::create([
                        'item_id' => $iO->id,
                        'orden_id' => $this->orden->id,
                        'estado' => '1',

                    ]);

                    // Descontar stock solo si el producto NO es provisional y existe registro de stock
                    if ($stock && !$this->producto->es_provisional) {
                        $stock->update([
                            'cantidad' => $stock->cantidad - $i->cantidad
                        ]);
                    }
                }
            }
            $this->orden->update([
                'estado' => '1'
            ]);
            $this->presupuesto->update([
                'estado' => '4'
            ]);
        }
        // END PRESUPUESTO


        $this->dispatch('added-turn');
        $this->formperson == false;
        $this->closeModal();
        redirect('ordenes/' . $this->orden->id);
    }




    public function formPerson()
    {
        if ($this->formperson == true) {

            $this->formperson = false;
        } else {
            if ($this->cliente != null) {

                $this->reset('cliente', 'nombre', 'apellido', 'dni', 'fecha_nac', 'numero_telefono');
                $this->formperson = false;
            } else {
                $this->formperson = true;
            }
        }
    }


    #[On('modal-order')]
    public function openModal()
    {
        if ($this->modal) {

            $this->modal = false;
        } else {
            $this->modal = true;
        }
    }




    #[On('presupuesto')]
    public function dePresupuesto($id)
    {

        $this->presupuesto = Presupuesto::find($id);
        $this->formperson =false;
        $this->selecedtVehiculo= false;

        $this->cliente = $this->presupuesto->clientes;

        $this->nombre = $this->cliente->perfiles->personas->nombre ?? '';
        $this->apellido = $this->cliente->perfiles->personas->apellido ?? '';
        $this->dni = $this->cliente->perfiles->personas->DNI ?? '';
        $this->fecha_nac = $this->cliente->perfiles->personas->fecha_nac ?? '';
        $this->openModal();
    }




    public function closeModal()
    {
        $this->reset(
            'nombre',
            'apellido',
            'dni',
            'fecha_nac',
            'numero_telefono',
            'cliente',
            'vehiculo',
            'servicio',
            'motivo',
            'tipo',
            'modelo',
            'marca',
            'dominio',
            'color',
            'version',
            'selecedtVehiculo',
            'año',
            'btnLub',
            's_btnLub',
            'btnLav',
            's_btnLav',

        );
        $this->modal = false;
        $this->formperson = false;
        $this->fecha = $this->fechaSelected;
        $this->horario = Carbon::now()->format('H:i');

        // $this->reset('nombre');
    }





    public function render()
    {
        return view('livewire.form-create-order');
    }
}

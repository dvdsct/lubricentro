<?php

namespace App\Livewire;

use App\Models\Cliente;
use App\Models\Colores;
use App\Models\MarcaVehiculo;
use App\Models\Perfil;
use App\Models\Persona;
use App\Models\Presupuesto;
use App\Models\PresupuestoItem;
use App\Models\Producto;
use App\Models\Stock;
use App\Models\TipoVehiculo;
use App\Models\Vehiculo;
use App\Models\VehiculosXCliente;
use Livewire\Attributes\On;
use Livewire\Component;
use Carbon\Carbon;
use Carbon\CarbonInterval;
use Carbon\CarbonInterface;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Validate;

// Establecer el idioma a español
Carbon::setLocale('es');


class AddPresupuesto extends Component
{
    public $cliente;
    public $modal;

    // Client search modal
    public $showClientModal = false;
    public $searchCliente = '';
    public $clientPage = 1;

    public $presupuesto;

    #[Locked]
    public $formperson = false;
    #[Locked]
    public $formVehiculo = false;


    #[Validate('required', message: 'Debe ingresar el Nombre!')]
    public $nombre;
    #[Validate('required', message: 'Debe ingresar el Apellido!')]
    public $apellido;
    public $fecha_nac;
    public $dni;
    // Nuevo: número de teléfono (solo dígitos, opcional)
    #[Validate('nullable|regex:/^\\d+$/', message: 'El número de teléfono debe contener solo dígitos.', translate: false)]
    public $numero_telefono;


    // del cliente
    public $persona;
    public $vehiculo;
    public $vehiculos = [];


    public function mount()
    {
        // Vehículo: catálogos
        $this->tiposVehiculo = TipoVehiculo::all();
        $this->marcas = [];
        $this->modelos = [];
        $this->colores = Colores::all();
    }

    /**
     * Toggle the client search modal.
     */
    public function toggleClientModal()
    {
        $this->showClientModal = !$this->showClientModal;
        if (!$this->showClientModal) {
            $this->searchCliente = '';
            $this->clientPage = 1;
        }
    }

    /**
     * Reset page when search changes.
     */
    public function updatedSearchCliente()
    {
        $this->clientPage = 1;
    }

    public function previousClientPage()
    {
        if ($this->clientPage > 1) {
            $this->clientPage--;
        }
    }

    public function nextClientPage()
    {
        $paginator = $this->filteredClientes;
        if ($this->clientPage < $paginator->lastPage()) {
            $this->clientPage++;
        }
    }

    /**
     * Return filtered clients based on search query.
     */
    public function getFilteredClientesProperty()
    {
        $query = Cliente::with('perfiles.personas');

        if (!empty($this->searchCliente)) {
            $search = $this->searchCliente;
            $query->whereHas('perfiles.personas', function ($q) use ($search) {
                $q->where('nombre', 'like', "%{$search}%")
                  ->orWhere('apellido', 'like', "%{$search}%")
                  ->orWhere('DNI', 'like', "%{$search}%")
                  ->orWhere('numero_telefono', 'like', "%{$search}%");
            });
        }

        return $query->orderByDesc('id')
            ->paginate(30, ['*'], 'clientPage', $this->clientPage);
    }

    /**
     * Select a client from the search modal.
     */
    public function selectCliente($clienteId)
    {
        $this->cliente = $clienteId;
        $this->upPerson();
        $this->showClientModal = false;
        $this->searchCliente = '';
        $this->clientPage = 1;
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

        // cargar vehículos existentes del cliente
        $this->vehiculos = $this->cliente->vehiculos ?? [];
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

    // ================= Vehículo (similar a Turnos) =================
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
    public $selecedtVehiculo = false;

    public function setFormVehiculo()
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

    public function upMarcas()
    {
        $this->marcas = MarcaVehiculo::where(function ($q) {
            $q->where('tipo_vehiculo_id', $this->tipo)
              ->orWhereNull('tipo_vehiculo_id');
        })->orderBy('descripcion')->get();
        $this->modelos = [];
        $this->modelo = null;
    }

    public function upModelos()
    {
        if (empty($this->marca)) {
            $this->modelos = [];
            return;
        }
        $this->modelos = \App\Models\ModeloVehiculo::where('marca_vehiculo_id', $this->marca)
            ->when($this->tipo, function ($q) {
                $q->where(function ($sub) {
                    $sub->where('tipo_vehiculo_id', $this->tipo)
                        ->orWhereNull('tipo_vehiculo_id');
                });
            })
            ->orderBy('descripcion')
            ->get();
    }

    // Modal para agregar nuevos vehículos (Tipo/Marca/Modelo) al catálogo
    public $modalNuevoCatalogo = false;
    public $cat_target = 'general';
    public $cat_tipo_id = '';
    public $cat_crear_nuevo_tipo = false;
    public $cat_nuevo_tipo_nombre = '';
    public $cat_marca_id = '';
    public $cat_crear_nueva_marca = false;
    public $cat_nueva_marca_nombre = '';
    public $cat_nuevo_modelo_nombre = '';
    public $cat_marcasDisponibles = [];

    public function openModalNuevoCatalogo($target = 'general')
    {
        $this->resetCatForm();
        $this->cat_target = $target;
        $this->tiposVehiculo = TipoVehiculo::orderBy('descripcion')->get();
        if ($this->tipo) {
            $this->cat_tipo_id = $this->tipo;
        } else {
            $this->cat_tipo_id = $this->tiposVehiculo->first()?->id ?? '';
        }
        $this->updatedCatTipoId($this->cat_tipo_id);

        if ($target === 'marca') {
            $this->cat_crear_nueva_marca = true;
            $this->cat_marca_id = '';
        } elseif ($target === 'modelo') {
            $this->cat_crear_nueva_marca = false;
            $this->cat_marca_id = $this->marca ?: ($this->cat_marcasDisponibles->first()?->id ?? '');
        } elseif ($target === 'tipo') {
            $this->cat_crear_nuevo_tipo = true;
            $this->cat_tipo_id = '';
            $this->cat_crear_nueva_marca = true;
        } else {
            if ($this->marca) {
                $this->cat_marca_id = $this->marca;
            }
        }
        $this->modalNuevoCatalogo = true;
    }

    public function closeModalNuevoCatalogo()
    {
        $this->modalNuevoCatalogo = false;
        $this->resetCatForm();
    }

    public function resetCatForm()
    {
        $this->reset([
            'cat_target',
            'cat_tipo_id',
            'cat_crear_nuevo_tipo',
            'cat_nuevo_tipo_nombre',
            'cat_marca_id',
            'cat_crear_nueva_marca',
            'cat_nueva_marca_nombre',
            'cat_nuevo_modelo_nombre'
        ]);
        $this->resetErrorBag();
    }

    public function updatedCatTipoId($value)
    {
        $this->cat_marca_id = '';
        if ($value) {
            $this->cat_marcasDisponibles = MarcaVehiculo::where(function ($q) use ($value) {
                $q->where('tipo_vehiculo_id', $value)
                  ->orWhereNull('tipo_vehiculo_id');
            })->orderBy('descripcion')->get();
        } else {
            $this->cat_marcasDisponibles = MarcaVehiculo::orderBy('descripcion')->get();
        }
    }

    public function toggleCatNuevoTipo()
    {
        $this->cat_crear_nuevo_tipo = !$this->cat_crear_nuevo_tipo;
        if ($this->cat_crear_nuevo_tipo) {
            $this->cat_tipo_id = '';
            $this->cat_crear_nueva_marca = true;
            $this->cat_marca_id = '';
        } else {
            $this->cat_tipo_id = $this->tiposVehiculo->first()?->id ?? '';
            $this->updatedCatTipoId($this->cat_tipo_id);
        }
    }

    public function toggleCatNuevaMarca()
    {
        $this->cat_crear_nueva_marca = !$this->cat_crear_nueva_marca;
        if ($this->cat_crear_nueva_marca) {
            $this->cat_marca_id = '';
        } else {
            $this->updatedCatTipoId($this->cat_tipo_id);
        }
    }

    public function saveNuevoCatalogo()
    {
        $rules = [
            'cat_nuevo_tipo_nombre' => $this->cat_crear_nuevo_tipo ? 'required|string|min:2|max:50' : 'nullable',
            'cat_tipo_id' => !$this->cat_crear_nuevo_tipo ? 'required' : 'nullable',
            'cat_nueva_marca_nombre' => $this->cat_crear_nueva_marca ? 'required|string|min:2|max:50' : 'nullable',
            'cat_marca_id' => !$this->cat_crear_nueva_marca ? 'required' : 'nullable',
            'cat_nuevo_modelo_nombre' => $this->cat_crear_nueva_marca ? 'nullable|string|max:100' : 'required|string|min:1|max:100',
        ];

        $messages = [
            'cat_nuevo_tipo_nombre.required' => 'Ingrese el nombre del nuevo tipo.',
            'cat_tipo_id.required' => 'Seleccione el tipo.',
            'cat_nueva_marca_nombre.required' => 'Ingrese el nombre de la nueva marca.',
            'cat_marca_id.required' => 'Seleccione la marca.',
            'cat_nuevo_modelo_nombre.required' => 'Ingrese el nombre del modelo.',
        ];

        $this->validate($rules, $messages);

        if ($this->cat_crear_nuevo_tipo) {
            $tipoName = mb_strtoupper(trim($this->cat_nuevo_tipo_nombre));
            $tipoObj = TipoVehiculo::firstOrCreate(['descripcion' => $tipoName], ['estado' => 1]);
        } else {
            $tipoObj = TipoVehiculo::findOrFail($this->cat_tipo_id);
        }

        if ($this->cat_crear_nueva_marca) {
            $marcaName = mb_strtoupper(trim($this->cat_nueva_marca_nombre));
            $marcaObj = MarcaVehiculo::firstOrCreate(['descripcion' => $marcaName, 'tipo_vehiculo_id' => $tipoObj->id]);
        } else {
            $marcaObj = MarcaVehiculo::findOrFail($this->cat_marca_id);
        }

        $modeloObj = null;
        if (!empty(trim($this->cat_nuevo_modelo_nombre))) {
            $modeloObj = \App\Models\ModeloVehiculo::firstOrCreate(
                [
                    'descripcion' => trim($this->cat_nuevo_modelo_nombre),
                    'marca_vehiculo_id' => $marcaObj->id,
                    'tipo_vehiculo_id' => $tipoObj->id,
                ],
                ['estado' => '1']
            );
        }

        $this->tiposVehiculo = TipoVehiculo::orderBy('descripcion')->get();
        $this->tipo = $tipoObj->id;
        $this->upMarcas();
        $this->marca = $marcaObj->id;
        $this->upModelos();
        if ($modeloObj) {
            $this->modelo = $modeloObj->id;
        }

        $this->closeModalNuevoCatalogo();
    }

    public function addVehicle()
    {
        // Validaciones mínimas para poder crear el vehículo
        $this->validate([
            'tipo' => 'required',
            'marca' => 'required',
            'modelo' => 'required',
        ], [
            'tipo.required' => 'Seleccione el tipo de vehículo',
            'marca.required' => 'Seleccione la marca',
            'modelo.required' => 'Seleccione el modelo',
        ]);

        // Normalizar dominio (si existe)
        $dominio = $this->dominio ? strtoupper(trim($this->dominio)) : null;

        if ($dominio) {
            // Identificar por dominio únicamente para evitar colisiones por otros campos
            $this->vehiculo = Vehiculo::firstOrCreate(
                ['dominio' => $dominio],
                [
                    'modelo_vehiculo_id' => $this->modelo,
                    'color' => $this->color ?: null,
                    'version' => $this->version ?: null,
                    'año' => $this->año ?: null,
                    'estado' => 1,
                ]
            );
        } else {
            // Crear siempre un nuevo vehículo si no hay dominio para no colapsar registros
            $this->vehiculo = Vehiculo::create([
                'modelo_vehiculo_id' => $this->modelo,
                'dominio' => null,
                'color' => $this->color ?: null,
                'version' => $this->version ?: null,
                'año' => $this->año ?: null,
                'estado' => 1,
            ]);
        }

        VehiculosXCliente::firstOrCreate([
            'cliente_id' => $this->cliente->id,
            'vehiculo_id' => $this->vehiculo->id
        ]);

        $this->vehiculo = $this->vehiculo->id;
        // refresh vehicles list for the selector
        $this->vehiculos = Cliente::find($this->cliente->id)->vehiculos()->get();
        $this->formVehiculo = false;
        $this->selecedtVehiculo = true;
    }

    public function selectVehiculo()
    {
        // cuando se elige desde el selector existente
        if ($this->vehiculo) {
            $this->selecedtVehiculo = true;
            $this->formVehiculo = false;
        }
    }

    public function refreshVehiculos()
    {
        if ($this->cliente) {
            // fuerza recarga desde base
            $this->vehiculos = Cliente::find($this->cliente->id)->vehiculos()->get();
        }
    }


    public function addClient()
    {
        if (Auth::user()->hasAnyRole(['cajero', 'admin', 'vendedor']) || Auth::user()->can('presupuestos') || Auth::user()->can('hacer-presupuestos')) {


            $this->validate();

            $persona = Persona::create([
                'nombre' => $this->nombre,
                'apellido' => $this->apellido,
                'DNI' => $this->dni,
                'numero_telefono' => $this->numero_telefono,
                'fecha_nac' => $this->fecha_nac,
                'estado' => 1
            ]);

            /*         $perfil = Perfil::create([
        'persona_id'=>$persona->id,
        ]); */
            $perfil = Perfil::create([
                'persona_id' => $persona->id
            ]);

            $this->cliente = Cliente::create([
                'perfil_id' => $perfil->id,
            ]);
            $this->formperson = false;

        } else {
            return    abort(404);
        }
    }

    public function continueForm()
    {

        // si $vehiculo es objeto, usar su id
        $vehiculoId = is_object($this->vehiculo) ? ($this->vehiculo->id ?? null) : $this->vehiculo;

        $this->presupuesto = Presupuesto::create([
            'cliente_id' => $this->cliente->id,
            'vehiculo_id' => $vehiculoId,
            'estado' => '1'
        ]);
        $this->modalOnOff();
        redirect('presupuesto/'. $this->presupuesto->id);
    }

    #[On('addPresupuesto')]
    public function modalOnOff()
    {
        if ($this->modal == true) {
            $this->modal = false;
        } else {

            $this->modal = true;
        }
    }


    public function render()
    {
        return view('livewire.add-presupuesto');
    }
}

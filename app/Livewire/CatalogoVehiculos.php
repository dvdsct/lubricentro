<?php

namespace App\Livewire;

use App\Models\MarcaVehiculo;
use App\Models\ModeloVehiculo;
use App\Models\TipoVehiculo;
use Livewire\Component;
use Livewire\WithPagination;

class CatalogoVehiculos extends Component
{
    use WithPagination;

    protected string $paginationTheme = 'bootstrap';

    public $search = '';
    public $perPage = 15;

    // Control de modal de creación
    public $modalNuevo = false;

    // Campos del formulario
    public $tipo_id = '';
    public $crear_nuevo_tipo = false;
    public $nuevo_tipo_nombre = '';

    public $marca_id = '';
    public $crear_nueva_marca = false;
    public $nueva_marca_nombre = '';

    public $nuevo_modelo_nombre = '';

    // Colecciones para los selects del modal
    public $tiposDisponibles = [];
    public $marcasDisponibles = [];

    protected function rules(): array
    {
        $rules = [
            'nuevo_modelo_nombre' => 'required|string|min:1|max:100',
        ];

        if ($this->crear_nuevo_tipo) {
            $rules['nuevo_tipo_nombre'] = 'required|string|min:2|max:50';
        } else {
            $rules['tipo_id'] = 'required';
        }

        if ($this->crear_nueva_marca) {
            $rules['nueva_marca_nombre'] = 'required|string|min:2|max:50';
        } else {
            $rules['marca_id'] = 'required';
        }

        return $rules;
    }

    protected $messages = [
        'nuevo_modelo_nombre.required' => 'Ingrese el nombre del modelo.',
        'nuevo_tipo_nombre.required' => 'Ingrese el nombre del nuevo tipo.',
        'tipo_id.required' => 'Seleccione el tipo de vehículo.',
        'nueva_marca_nombre.required' => 'Ingrese el nombre de la nueva marca.',
        'marca_id.required' => 'Seleccione la marca.',
    ];

    public function mount()
    {
        $this->loadTipos();
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function loadTipos()
    {
        $this->tiposDisponibles = TipoVehiculo::orderBy('descripcion')->get();
    }

    public function updatedTipoId($value)
    {
        $this->marca_id = '';
        if ($value) {
            $this->marcasDisponibles = MarcaVehiculo::where(function ($q) use ($value) {
                $q->where('tipo_vehiculo_id', $value)
                  ->orWhereNull('tipo_vehiculo_id');
            })->orderBy('descripcion')->get();
        } else {
            $this->marcasDisponibles = MarcaVehiculo::orderBy('descripcion')->get();
        }
    }

    public function openModal()
    {
        $this->resetForm();
        $this->loadTipos();
        if ($this->tiposDisponibles->isNotEmpty()) {
            $this->tipo_id = $this->tiposDisponibles->first()->id;
            $this->updatedTipoId($this->tipo_id);
        }
        $this->modalNuevo = true;
    }

    public function closeModal()
    {
        $this->modalNuevo = false;
        $this->resetForm();
    }

    public function resetForm()
    {
        $this->reset([
            'tipo_id',
            'crear_nuevo_tipo',
            'nuevo_tipo_nombre',
            'marca_id',
            'crear_nueva_marca',
            'nueva_marca_nombre',
            'nuevo_modelo_nombre'
        ]);
        $this->resetErrorBag();
    }

    public function toggleNuevoTipo()
    {
        $this->crear_nuevo_tipo = !$this->crear_nuevo_tipo;
        if ($this->crear_nuevo_tipo) {
            $this->tipo_id = '';
            $this->crear_nueva_marca = true; // Por defecto nueva marca si es nuevo tipo
            $this->marca_id = '';
        } else {
            $this->loadTipos();
            $this->tipo_id = $this->tiposDisponibles->first()?->id ?? '';
            $this->updatedTipoId($this->tipo_id);
        }
    }

    public function toggleNuevaMarca()
    {
        $this->crear_nueva_marca = !$this->crear_nueva_marca;
        if ($this->crear_nueva_marca) {
            $this->marca_id = '';
        } else {
            $this->updatedTipoId($this->tipo_id);
        }
    }

    public function saveModelo()
    {
        $this->validate();

        // 1. Resolver Tipo
        if ($this->crear_nuevo_tipo) {
            $tipoName = mb_strtoupper(trim($this->nuevo_tipo_nombre));
            $tipo = TipoVehiculo::firstOrCreate(
                ['descripcion' => $tipoName],
                ['estado' => 1]
            );
        } else {
            $tipo = TipoVehiculo::findOrFail($this->tipo_id);
        }

        // 2. Resolver Marca
        if ($this->crear_nueva_marca) {
            $marcaName = mb_strtoupper(trim($this->nueva_marca_nombre));
            $marca = MarcaVehiculo::firstOrCreate(
                [
                    'descripcion' => $marcaName,
                    'tipo_vehiculo_id' => $tipo->id
                ]
            );
        } else {
            $marca = MarcaVehiculo::findOrFail($this->marca_id);
        }

        // 3. Crear Modelo
        $modeloName = trim($this->nuevo_modelo_nombre);
        $modelo = ModeloVehiculo::firstOrCreate(
            [
                'descripcion' => $modeloName,
                'marca_vehiculo_id' => $marca->id,
                'tipo_vehiculo_id' => $tipo->id,
            ],
            [
                'estado' => '1'
            ]
        );

        session()->flash('message', "¡Modelo '{$modelo->descripcion}' de marca '{$marca->descripcion}' guardado con éxito en el catálogo!");

        $this->closeModal();
    }

    public function deleteModelo($id)
    {
        $mod = ModeloVehiculo::find($id);
        if ($mod) {
            $mod->delete();
            session()->flash('message', "Modelo eliminado del catálogo.");
        }
    }

    public function render()
    {
        $query = ModeloVehiculo::with(['marcas', 'tipos']);

        if (trim($this->search) !== '') {
            $term = '%' . trim($this->search) . '%';
            $query->where(function ($q) use ($term) {
                $q->where('descripcion', 'like', $term)
                  ->orWhereHas('marcas', fn($m) => $m->where('descripcion', 'like', $term))
                  ->orWhereHas('tipos', fn($t) => $t->where('descripcion', 'like', $term));
            });
        }

        $modelos = $query->orderBy('id', 'desc')->paginate($this->perPage);

        return view('livewire.catalogo-vehiculos', [
            'modelos' => $modelos
        ]);
    }
}

<?php

namespace App\Livewire;

use App\Models\Cliente;
use App\Models\Perfil;
use App\Models\Persona;
use Livewire\Component;

class ClientProfile extends Component
{
    public Cliente $cliente;

    public $showEditModal = false;

    public $nombre;
    public $apellido;
    public $dni;
    public $numero_telefono;
    public $fecha_nac;

    public $showUserModal = false;
    public $userEmail;
    public $userPassword;

    // Gestión de Vehículos para el Cliente
    public $showVehicleModal = false;
    public $isEditingVehicle = false;
    public $editingVehicleId = null;
    public $dominio;
    public $color;
    public $version;
    public $año;
    public $tipo_vehiculo_id;
    public $marca_vehiculo_id;
    public $modelo_vehiculo_id;

    public $tipos = [];
    public $marcas = [];
    public $modelos = [];
    public $colores = [];

    public function mount(Cliente $cliente)
    {
        $this->cliente = $cliente->load(['perfiles.personas', 'perfiles.users', 'vehiculos.modelos.marcas']);
    }

    public function openUserModal()
    {
        $existingUser = optional($this->cliente->perfiles)->users;
        $this->userEmail = $existingUser->email ?? '';
        $this->userPassword = '';
        $this->resetValidation();
        $this->showUserModal = true;
    }

    public function closeUserModal()
    {
        $this->showUserModal = false;
        $this->resetValidation();
    }

    public function createWebAccess()
    {
        $existingUser = optional($this->cliente->perfiles)->users;
        $userId = $existingUser->id ?? null;

        $this->validate([
            'userEmail' => 'required|email|max:255|unique:users,email,' . ($userId ?: 'NULL'),
            'userPassword' => $userId ? 'nullable|string|min:6' : 'required|string|min:6',
        ], [
            'userEmail.required' => 'El correo electrónico es obligatorio.',
            'userEmail.email' => 'Ingrese un correo electrónico válido.',
            'userEmail.unique' => 'Este correo electrónico ya está registrado en el sistema.',
            'userPassword.required' => 'La contraseña es obligatoria.',
            'userPassword.min' => 'La contraseña debe tener al menos 6 caracteres.',
        ]);

        $persona = optional($this->cliente->perfiles)->personas;
        $fullName = trim(($persona->nombre ?? 'Cliente') . ' ' . ($persona->apellido ?? ''));

        if ($existingUser) {
            $existingUser->email = $this->userEmail;
            if (!empty($this->userPassword)) {
                $existingUser->password = \Illuminate\Support\Facades\Hash::make($this->userPassword);
            }
            $existingUser->save();
            $user = $existingUser;
        } else {
            $user = \App\Models\User::create([
                'name' => $fullName ?: 'Cliente',
                'email' => $this->userEmail,
                'password' => \Illuminate\Support\Facades\Hash::make($this->userPassword),
            ]);
        }

        // Asignar rol de cliente (asegurando que el rol exista)
        $roleCliente = \Spatie\Permission\Models\Role::firstOrCreate(['name' => 'cliente']);
        if (!$user->hasRole($roleCliente)) {
            $user->assignRole($roleCliente);
        }

        // Vincular user_id a perfil
        $perfil = $this->cliente->perfiles;
        if (!$perfil) {
            $perfil = Perfil::create(['persona_id' => $persona->id ?? null, 'user_id' => $user->id]);
            $this->cliente->update(['perfil_id' => $perfil->id]);
        } else {
            $perfil->update(['user_id' => $user->id]);
        }

        $this->cliente->load(['perfiles.personas', 'perfiles.users', 'vehiculos.modelos.marcas']);
        $this->showUserModal = false;

        session()->flash('message', 'Acceso web del cliente guardado exitosamente.');
    }

    public function openEditModal()
    {
        $persona = optional($this->cliente->perfiles)->personas;

        $this->nombre = $persona->nombre ?? '';
        $this->apellido = $persona->apellido ?? '';
        $this->dni = $persona->DNI ?? '';
        $this->numero_telefono = $persona->numero_telefono ?? '';
        $this->fecha_nac = $persona->fecha_nac ?? '';

        $this->resetValidation();
        $this->showEditModal = true;
    }

    public function closeEditModal()
    {
        $this->showEditModal = false;
        $this->resetValidation();
    }

    public function updateClient()
    {
        $this->validate([
            'nombre' => 'required|string|max:255',
            'apellido' => 'required|string|max:255',
            'dni' => 'nullable|string|max:50',
            'numero_telefono' => 'nullable|string|max:50',
            'fecha_nac' => 'nullable|date',
        ], [
            'nombre.required' => 'El nombre es obligatorio.',
            'apellido.required' => 'El apellido es obligatorio.',
            'fecha_nac.date' => 'La fecha de nacimiento no es válida.',
        ]);

        $persona = optional($this->cliente->perfiles)->personas;

        if ($persona) {
            $persona->update([
                'nombre' => $this->nombre,
                'apellido' => $this->apellido,
                'DNI' => $this->dni,
                'numero_telefono' => $this->numero_telefono,
                'fecha_nac' => $this->fecha_nac ?: null,
            ]);
        } else {
            $newPersona = Persona::create([
                'nombre' => $this->nombre,
                'apellido' => $this->apellido,
                'DNI' => $this->dni,
                'numero_telefono' => $this->numero_telefono,
                'fecha_nac' => $this->fecha_nac ?: null,
                'estado' => 1,
            ]);

            if (!$this->cliente->perfiles) {
                $perfil = Perfil::create(['persona_id' => $newPersona->id]);
                $this->cliente->update(['perfil_id' => $perfil->id]);
            } else {
                $this->cliente->perfiles->update(['persona_id' => $newPersona->id]);
            }
        }

        $this->cliente->load(['perfiles.personas', 'vehiculos.modelos.marcas']);
        $this->showEditModal = false;

        session()->flash('message', 'Datos del cliente actualizados correctamente.');
    }

    public function openAddVehicleModal()
    {
        $this->reset([
            'dominio', 'color', 'version', 'año',
            'marca_vehiculo_id', 'modelo_vehiculo_id', 'tipo_vehiculo_id'
        ]);
        $this->editingVehicleId = null;
        $this->isEditingVehicle = false;
        $this->tipos = \App\Models\TipoVehiculo::all();
        $this->marcas = [];
        $this->modelos = [];
        $this->colores = \App\Models\Colores::all();
        $this->resetValidation();
        $this->showVehicleModal = true;
    }

    public function openEditVehicleModal($vehicleId)
    {
        $v = \App\Models\Vehiculo::with('modelos.marcas')->find($vehicleId);
        if (!$v) {
            return;
        }

        $this->editingVehicleId = $v->id;
        $this->isEditingVehicle = true;
        $this->dominio = $v->dominio;
        $this->color = $v->color;
        $this->version = $v->version;
        $this->año = $v->año;
        $this->modelo_vehiculo_id = $v->modelo_vehiculo_id;
        $this->marca_vehiculo_id = optional($v->modelos)->marca_vehiculo_id;
        $this->tipo_vehiculo_id = optional($v->modelos)->tipo_vehiculo_id;

        $this->tipos = \App\Models\TipoVehiculo::all();
        $this->colores = \App\Models\Colores::all();

        if ($this->tipo_vehiculo_id) {
            $this->marcas = \App\Models\MarcaVehiculo::where('tipo_vehiculo_id', $this->tipo_vehiculo_id)->get();
        } else {
            $this->marcas = \App\Models\MarcaVehiculo::all();
        }

        if ($this->marca_vehiculo_id) {
            $this->modelos = \App\Models\ModeloVehiculo::where('marca_vehiculo_id', $this->marca_vehiculo_id)->get();
        } else {
            $this->modelos = [];
        }

        $this->resetValidation();
        $this->showVehicleModal = true;
    }

    public function updatedTipoVehiculoId($value)
    {
        if ($value) {
            $this->marcas = \App\Models\MarcaVehiculo::where('tipo_vehiculo_id', $value)->get();
        } else {
            $this->marcas = [];
        }
        $this->marca_vehiculo_id = null;
        $this->modelos = [];
        $this->modelo_vehiculo_id = null;
    }

    public function updatedMarcaVehiculoId($value)
    {
        if ($value) {
            $this->modelos = \App\Models\ModeloVehiculo::where('marca_vehiculo_id', $value)->get();
        } else {
            $this->modelos = [];
        }
        $this->modelo_vehiculo_id = null;
    }

    public function closeVehicleModal()
    {
        $this->showVehicleModal = false;
        $this->resetValidation();
    }

    public function saveVehicle()
    {
        $this->validate([
            'dominio' => 'required|string|max:50',
            'modelo_vehiculo_id' => 'required|exists:modelo_vehiculos,id',
            'año' => 'nullable|integer|min:1900|max:' . (date('Y') + 1),
        ], [
            'dominio.required' => 'La patente / dominio es obligatoria.',
            'modelo_vehiculo_id.required' => 'Debe seleccionar un modelo de vehículo.',
            'año.integer' => 'El año debe ser un número entero válido.',
        ]);

        $dominioNorm = strtoupper(trim($this->dominio));

        if ($this->isEditingVehicle && $this->editingVehicleId) {
            $v = \App\Models\Vehiculo::find($this->editingVehicleId);
            if ($v) {
                $v->update([
                    'dominio' => $dominioNorm,
                    'modelo_vehiculo_id' => $this->modelo_vehiculo_id,
                    'color' => $this->color ?: null,
                    'version' => $this->version ?: null,
                    'año' => $this->año ?: null,
                ]);
            }
            session()->flash('message', 'Vehículo y patente actualizados exitosamente.');
        } else {
            $v = \App\Models\Vehiculo::where('dominio', $dominioNorm)->first();
            if ($v) {
                $v->update([
                    'modelo_vehiculo_id' => $this->modelo_vehiculo_id ?: $v->modelo_vehiculo_id,
                    'color' => $this->color ?: $v->color,
                    'version' => $this->version ?: $v->version,
                    'año' => $this->año ?: $v->año,
                ]);
            } else {
                $v = \App\Models\Vehiculo::create([
                    'dominio' => $dominioNorm,
                    'modelo_vehiculo_id' => $this->modelo_vehiculo_id,
                    'color' => $this->color ?: null,
                    'version' => $this->version ?: null,
                    'año' => $this->año ?: null,
                    'estado' => 1
                ]);
            }

            \App\Models\VehiculosXCliente::firstOrCreate([
                'cliente_id' => $this->cliente->id,
                'vehiculo_id' => $v->id
            ]);

            session()->flash('message', 'Vehículo agregado exitosamente al cliente.');
        }

        $this->cliente->load(['perfiles.personas', 'perfiles.users', 'vehiculos.modelos.marcas']);
        $this->showVehicleModal = false;
    }

    public function unlinkVehicle($vehicleId)
    {
        \App\Models\VehiculosXCliente::where('cliente_id', $this->cliente->id)
            ->where('vehiculo_id', $vehicleId)
            ->delete();

        $this->cliente->load(['perfiles.personas', 'perfiles.users', 'vehiculos.modelos.marcas']);
        session()->flash('message', 'Vehículo desvinculado del cliente.');
    }

    public function render()
    {
        return view('livewire.client-profile')
            ->layout('components.layouts.page', [
                'title' => 'Perfil de Cliente - Rocket',
                'header' => 'PERFIL DE CLIENTE',
            ]);
    }
}

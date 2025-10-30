@extends('adminlte::page')

@section('title', 'Generar presupuesto - Rocket')

@section('content_header')
    <h1> <strong> GENERAR PRESUPUESTO </strong> </h1>
@stop

@section('content')
    @livewire('datos-personales', ['orden' => $presupuesto, 'persona' => $cliente])

    @if($presupuesto->vehiculos)
        <div class="card mt-2">
            <div class="card-header bg-secondary">
                <h5 class="m-0"><strong> Vehículo seleccionado </strong></h5>
            </div>
            <div class="card-body py-2">
                <div class="row">
                    <div class="col-md-3">
                        <strong>Tipo:</strong>
                        {{ optional(optional($presupuesto->vehiculos->modelos)->tipos)->descripcion ?? '-' }}
                    </div>
                    <div class="col-md-3">
                        <strong>Marca:</strong>
                        {{ optional(optional($presupuesto->vehiculos->modelos)->marcas)->descripcion ?? '-' }}
                    </div>
                    <div class="col-md-3">
                        <strong>Modelo:</strong>
                        {{ optional($presupuesto->vehiculos->modelos)->descripcion ?? '-' }}
                    </div>
                    <div class="col-md-3">
                        <strong>Dominio:</strong> {{ $presupuesto->vehiculos->dominio ?? '-' }}
                    </div>
                </div>
                <div class="row mt-1">
                    <div class="col-md-3">
                        @php
                            $colorVal = $presupuesto->vehiculos->color ?? null;
                            $colorName = $colorVal;
                            if ($colorVal) {
                                if (is_numeric($colorVal)) {
                                    $c = \App\Models\Colores::find($colorVal);
                                    $colorName = $c->descripcion ?? $colorVal;
                                } elseif (substr($colorVal, 0, 1) === '#') {
                                    $c = \App\Models\Colores::where('hexadecimal', $colorVal)->first();
                                    $colorName = $c->descripcion ?? $colorVal;
                                }
                            }
                        @endphp
                        <strong>Color:</strong> {{ $colorName ?? '-' }}
                    </div>
                    <div class="col-md-3">
                        <strong>Versión:</strong> {{ $presupuesto->vehiculos->version ?? '-' }}
                    </div>
                    <div class="col-md-3">
                        <strong>Año:</strong> {{ $presupuesto->vehiculos->año ?? '-' }}
                    </div>
                </div>
            </div>
        </div>
    @endif

    @livewire('add-product-presupuesto', ['presupuesto' => $presupuesto])
@stop

@section('css')

@stop

@section('js')

@stop

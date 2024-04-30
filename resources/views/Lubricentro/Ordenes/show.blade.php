@extends('adminlte::page')

@section('title', 'Nueva Orden - Rocket')

@section('content_header')
    <h1> <strong> NUEVA ORDEN </strong> </h1>
@stop

@section('content')





<div class="row">
    <div class="col-md-8">
        @livewire('datos-personales',['orden' => $orden, 'persona' => $cliente])
        @livewire('add-products',['orden' => $orden])
    </div>
    <div class="col-md-4">
        @if ($orden->vehiculos)

        @livewire('about-vehicle',['orden' => $orden])
        @endif
        @livewire('final-order',['orden' => $orden])
        @livewire('form-pago',['orden' => $orden])

    </div>
</div>
<div class="row">
    <div class="col">

    </div>
    <div class="col"></div>
    <div class="col"></div>
</div>



@stop

@section('css')
@stop

@section('js')
@stop

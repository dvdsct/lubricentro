@extends('adminlte::page')

@section('title', 'Nueva Orden - Rocket')

@section('content_header')
    <h1> <strong> NUEVA ORDEN </strong> </h1>
@stop

@section('content')





<div class="row">
    <div class="col-8">

        @livewire('datos-personales',['orden' => $orden])
        @livewire('add-products',['orden' => $orden])
    </div>
    <div class="col-4">
        @livewire('about-vehicle',['orden' => $orden])
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
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')
    <script> console.log('Hi!'); </script>
@stop

@extends('adminlte::page')

@section('title', 'Ver presupuesto - Rocket')

@section('content_header')
    <h1> <strong> VER PRESUPUESTO </strong> </h1>
@stop

@section('content')

    @livewire('datos-personales', ['orden' => $presupuesto, 'persona' => $cliente])
    <div class="row">
        <div class="col">
            @livewire('add-product-presupuesto', ['presupuesto' => $presupuesto])
        </div>
        <div class="col">
            @livewire('facturar-presupuesto', ['presupuesto' => $presupuesto])
        </div>
    </div>


@stop

@section('css')

@stop

@section('js')
    <script>
        console.log('Hi!');
    </script>
@stop

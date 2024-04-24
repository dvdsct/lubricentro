@extends('adminlte::page')

@section('title', 'Presupuestos - Rocket')

@section('content_header')
    <h1> <strong> PRESUPUESTOS </strong> </h1>
@stop

@section('content')

    @livewire('list-presupuesto')
    @livewire('add-presupuesto')
@stop

@section('css')

@stop

@section('js')
    <script>
        console.log('Hi!');
    </script>
@stop

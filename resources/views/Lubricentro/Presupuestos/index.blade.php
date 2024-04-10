@extends('adminlte::page')

@section('title', 'Pedidos Proveeddores - Rocket')

@section('content_header')
    <h1> <strong> PEDIDOS PROVEEDORES </strong> </h1>
@stop

@section('content')

    @livewire('list-presupuesto')
@stop

@section('css')

@stop

@section('js')
    <script>
        console.log('Hi!');
    </script>
@stop

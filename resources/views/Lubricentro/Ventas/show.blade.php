@extends('adminlte::page')

@section('title', 'Facturacion - Rocket')

@section('content_header')
    <h1> <strong> FACTURACION </strong> <span class="badge"> - DIA 03 DE MAYO DE 2024 </span> </h1>
@stop

@section('content')


@livewire('view-caja',['caja' => $caja])


@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')
    <script> console.log('Hi!'); </script>
@stop

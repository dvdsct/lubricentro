@extends('adminlte::page')

@section('title', 'Catálogo de Vehículos - Rocket')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1><strong>CATÁLOGO DE VEHÍCULOS</strong></h1>
    </div>
@stop

@section('content')
    @livewire('catalogo-vehiculos')
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop

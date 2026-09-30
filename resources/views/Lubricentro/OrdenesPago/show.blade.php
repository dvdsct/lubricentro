@extends('adminlte::page')

@section('title', 'Gestionar Orden de Pago - Rocket')

@section('content_header')
    <div class="d-flex align-items-center justify-content-between">
        <h1 class="m-0 text-dark"><strong>GESTIÓN DE ORDEN DE PAGO</strong></h1>
    </div>
@stop

@section('content')
    @livewire('gestion-orden-pago', ['id' => $ordenPago->id])
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')
@stop

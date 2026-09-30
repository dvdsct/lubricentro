@extends('adminlte::page')

@section('title', 'Cuenta Corriente Proveedor - Rocket')

@section('content_header')
    <div class="d-flex align-items-center justify-content-between">
        <h1 class="m-0 text-dark"><strong>CUENTA CORRIENTE DE PROVEEDOR</strong></h1>
    </div>
@stop

@section('content')
    @livewire('cta-cte-proveedor-detalle', ['proveedorId' => $proveedor->id])
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')
@stop

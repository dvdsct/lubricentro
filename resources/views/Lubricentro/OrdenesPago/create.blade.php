@extends('adminlte::page')

@section('title', 'Nueva Orden de Pago - Rocket')

@section('content_header')
    <div class="d-flex align-items-center justify-content-between">
        <h1 class="m-0 text-dark"><strong>NUEVA ORDEN DE PAGO</strong></h1>
    </div>
@stop

@section('content')
    @livewire('create-orden-pago', ['proveedor_id' => $proveedor_id, 'factura_id' => $factura_id])
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')
@stop

@extends('adminlte::page')

@section('title', 'Órdenes de Pago - Rocket')

@section('content_header')
    <div class="d-flex align-items-center justify-content-between">
        <h1 class="m-0 text-dark"><strong>ÓRDENES DE PAGO A PROVEEDORES</strong></h1>
    </div>
@stop

@section('content')
    @livewire('ordenes-pago-list')
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')
@stop

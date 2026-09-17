@extends('adminlte::page')

@section('title', 'Pedidos Proveedores - Rocket')

@section('content_header')
    <div class="d-flex align-items-center justify-content-between">
        <h1 class="m-0 text-dark"><strong>PEDIDO A PROVEEDOR #{{ $pedido->id }}</strong></h1>
    </div>
@stop

@section('content')


@livewire('datos-provedor',['orden' => $pedido, 'proveedor'=> $proveedor])
@livewire('add-products-p-p',['pedido' => $pedido, 'proveedor'=> $proveedor])

@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')
    <script>
        console.log('Hi!');
    </script>
@stop

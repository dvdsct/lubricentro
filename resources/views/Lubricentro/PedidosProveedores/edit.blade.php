@extends('adminlte::page')

@section('title', 'Pedidos Proveeddores - Rocket')

@section('content_header')
    <h1> <strong> PEDIDOS PROVEEDORES</strong> </h1>
@stop

@section('content')


@livewire('datos-provedor',['orden' => $pedido, 'persona'=> $proveedor])
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

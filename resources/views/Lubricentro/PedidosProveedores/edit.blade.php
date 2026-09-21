@extends('adminlte::page')

@section('title', 'Órdenes de Compra - Rocket')

@section('content_header')
     <h1> <strong> ÓRDENES DE COMPRA </strong> </h1>
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

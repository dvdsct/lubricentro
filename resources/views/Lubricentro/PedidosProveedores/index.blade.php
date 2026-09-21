@extends('adminlte::page')

@section('title', 'Órdenes de Compra - Rocket')

@section('content_header')
    <h1> <strong> ÓRDENES DE COMPRA </strong> </h1>
@stop

@section('content')

@livewire('lista-pedidos-prov')
@livewire('add-supplier-order')



@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')

@stop

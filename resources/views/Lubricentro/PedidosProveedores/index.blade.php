@extends('adminlte::page')

@section('title', 'Pedidos Proveeddores - Rocket')

@section('content_header')
    <h1> <strong> PEDIDOS PROVEEDORES </strong> </h1>
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

@extends('adminlte::page')

@section('title', 'Productos Provisionales - Rocket')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1><strong> PRODUCTOS PROVISIONALES </strong></h1>
    </div>
@stop

@section('content')
    @livewire('productos-provisionales')
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop

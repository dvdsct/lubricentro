@extends('adminlte::page')

@section('plugins.Chartjs', true)

@section('title', 'Informe de Productos - Rocket')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center flex-wrap">
        <h1 class="m-0"> <strong> INFORME DE PRODUCTOS </strong> </h1>
        <a href="{{ route('productos.index') }}" class="btn btn-outline-secondary shadow-sm mt-2 mt-md-0">
            <i class="fas fa-arrow-left mr-1"></i> Volver a Productos
        </a>
    </div>
@stop

@section('content')

    @livewire('productos-informe')

@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')
@stop

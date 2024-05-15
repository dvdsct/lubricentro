@extends('adminlte::page')

@section('title', 'Ver presupuesto - Rocket')

@section('content_header')
    <h1> <strong> GENERAR PRESUPUESTO </strong> </h1>
@stop

@section('content')
    @livewire('datos-personales', ['orden' => $presupuesto, 'persona' => $cliente])
    @livewire('add-product-presupuesto', ['presupuesto' => $presupuesto])
@stop

@section('css')

@stop

@section('js')

@stop

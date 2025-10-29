@extends('adminlte::page')

@section('title', 'Facturacion - Rocket')

@php
\Carbon\Carbon::setLocale('es');
@endphp

@section('content_header')
<h1>
    <strong>
        DETALLE CUENTA
    </strong>
</h1>

@stop

@section('content')

@livewire('datos-personales', ['orden' => '1', 'persona' => $cliente])

@livewire('cta-cte-detalle', ['clienteId' => $cliente->id])



@stop


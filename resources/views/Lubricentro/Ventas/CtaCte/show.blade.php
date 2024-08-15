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

<div class="card">
    <table class="table table-striped">
        <thead>
            <th>Pago ID</th>
            <th>FECHA TRANSACCION</th>
            <th>CONCEPTO</th>
            <th>TOTAL</th>
            <th>ESTADO</th>
            <th></th>
        </thead>
        <tbody>
            @foreach ($pagos as $pago)
                @livewire('pago-cta-cte',['pago' => $pago])

            @endforeach
        </tbody>
    </table>


</div>



@stop


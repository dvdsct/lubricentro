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
            <tr>
                <td>{{ $pago->id }}</td>
                <td>{{ $pago->created_at->translatedFormat('j \d\e F \d\e\l Y') }}</td>
                <td>{{ $pago->concepto}}</td>
                <td>${{ $pago->total }}</td>
                <td>{{ $pago->estado }}</td>
                <td><button class="btn btn-danger btn-sm">Cobrar</button></td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@stop

@section('css')
<link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')
<script>
    console.log('Hi!');
</script>
@stop
@extends('adminlte::page')

@section('title', 'Facturacion - Rocket')

@section('content_header')
<h1>
    <strong>
        FACTURACION
        <span class="badge badge-info">
            {{ mb_strtoupper(Carbon\Carbon::parse($caja->created_at)->locale('es')->translatedFormat('j \d\e F \d\e Y')) }}
        </span>
    </strong>
</h1>

@stop

@section('content')


<table class="table">
    <thead>
        <th>Subtotal</th>
        <th>total</th>
        <th>cupon</th>
        <th></th>
    </thead>
    <tbody>
        @foreach ($pagos as $pago )
        <td>{{ $pago->subtotal }}</td>
        <td>{{ $pago->total }}</td>
        <td>{{ $pago->nro_cupon }}</td>
        <td></td>

        @endforeach
    </tbody>
</table>

@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')
    <script> console.log('Hi!'); </script>
@stop

@extends('adminlte::page')

@section('title', 'Facturacion - Rocket')

@section('content_header')
    <h1> <strong> FACTURACION </strong> </h1>
@stop

@section('content')


<table>
    <thead>
        <th></th>
        <th></th>
        <th></th>
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

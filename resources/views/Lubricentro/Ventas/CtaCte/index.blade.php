@extends('adminlte::page')

@section('title', 'Facturacion - Rocket')

@section('content_header')
<h1> <strong> CUENTA CORRIENTE </strong> </h1>
@stop

@section('content')

<div class="card">
    <table class="table table-striped">
        <thead>
            <th>CLIENTE ID</th>
            <th>NOMBRE Y APELLIDO</th>
            <th>PAGOS PENDIENTES</th>
            <th></th>
        </thead>
        <tbody>
            @foreach ($clientes as $cliente)
            <tr>

                <td>{{ $cliente->id }}</td>
                <td>{{ $cliente->perfiles->personas->nombre }} {{ $cliente->perfiles->personas->apellido }}</td>
                <td>{{count($cliente->pagosctas) }}</td>
                <td><a class="btn btn-primary btn-sm" href="{{route('pagos-cta.show',$cliente->id)}}">Ver detalle</a></td>
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
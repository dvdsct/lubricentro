@extends('adminlte::page')

@section('title', 'Facturacion - Rocket')

@section('content_header')
    <h1> <strong> FACTURACION </strong> </h1>
@stop

@section('content')


<table class="table">
    <thead>
            <th>Cliente ID</th>
            <th>Nombre y Apellido</th>
            <th>Pagos Pendientes</th>
            <th></th>
        </thead>
        <tbody>
            @foreach ($clientes as $cliente)
                <tr>

                    <td>{{ $cliente->id }}</td>
                    <td>{{ $cliente->perfiles->personas->nombre }} {{ $cliente->perfiles->personas->apellido }}</td>
                    <td>{{count($cliente->pagosctas) }}</td>
                    <td><a href="{{route('pagos-cta.show',$cliente->id)}}">ver</a></td>
                </tr>
            @endforeach
        </tbody>
    </table>
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')
    <script>
        console.log('Hi!');
    </script>
@stop

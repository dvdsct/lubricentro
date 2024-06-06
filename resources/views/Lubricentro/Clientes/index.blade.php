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
            @foreach ($clientes as $cliente)
                <tr>

                    <td>{{ $cliente->id }}</td>
                    <td>{{ $cliente->perfiles->personas->nombre }}</td>
                    <td>{{ $cliente->total }}</td>
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

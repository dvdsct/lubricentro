@extends('adminlte::page')

@section('title', 'Facturacion - Rocket')

@section('content_header')
    <h1>
        <strong>
            Cuenta Corriente

        </strong>
    </h1>

@stop

@section('content')

    @livewire('datos-personales', ['orden' => '1', 'persona' => $cliente])

    <table class="table">
        <thead>
            <th>Pago ID</th>
            <th>Fecha</th>
            <th>total</th>
            <th>estado</th>
            <th></th>
        </thead>
        <tbody>
            @foreach ($pagos as $pago)
                <tr>
                    <td>{{ $pago->id }}</td>
                    <td>{{ $pago->total }}</td>
                    <td>{{ $pago->estado }}</td>
                    <td><button>pagar</button></td>
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

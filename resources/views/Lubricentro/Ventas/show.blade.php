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


@livewire('view-caja',['caja' => $caja])


@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')
    <script> console.log('Hi!'); </script>
@stop

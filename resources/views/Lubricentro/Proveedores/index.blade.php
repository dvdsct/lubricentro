@extends('adminlte::page')

@section('title', 'Proveedores - Rocket')

@section('content_header')
    <h1> <strong> Proveedores </strong> </h1>
@stop

@section('content')

    @livewire('proveedores')


@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')
    <script> console.log('Hi!'); </script>
@stop

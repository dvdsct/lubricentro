@extends('adminlte::page')

@section('title', 'Productos - Rocket')

@section('content_header')
    <h1> <strong> PRODUCTOS </strong> </h1>
@stop

@section('content')


@livewire('form-add-prod')
@livewire('lw-productos')


@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')
    <script> console.log('Hi!'); </script>
@stop

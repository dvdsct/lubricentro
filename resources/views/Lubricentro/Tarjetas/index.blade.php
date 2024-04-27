@extends('adminlte::page')

@section('title', 'Tarjetas - Rocket')

@section('content_header')
    <h1> <strong> TARJETAS </strong> </h1>
@stop

@section('content')

    @livewire('list-tarjetas')

@stop

@section('css')

@stop

@section('js')
    <script>
        console.log('Hi!');
    </script>
@stop
